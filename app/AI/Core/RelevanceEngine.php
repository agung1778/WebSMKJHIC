<?php

namespace App\AI\Core;

use App\AI\Knowledge\KnowledgeRepository;
use App\AI\NLP\Normalizer;
use App\AI\Support\AIConfig;
use App\Models\AiKnowledge;

/**
 * Penentuan relevansi dan penilaian confidence.
 *
 * Relevansi memadukan beberapa sinyal, masing-masing dinormalisasi terhadap
 * jumlah token QUERY (bukan dokumen), sehingga pertanyaan pendek tidak
 * dihukum oleh dokumen yang panjang:
 *
 *   - keyword : berapa persen token query yang ditemukan di dokumen
 *   - title   : berapa persen token query yang ditemukan di judul
 *   - category: kecocokan token query dengan kategori knowledge
 *   - content : skor mesin pencarian (BM25/TF-IDF/keyword hybrid)
 *
 * Confidence menggabungkan relevance, jumlah token pendukung, dan kekuatan
 * skor pencarian.
 */
class RelevanceEngine
{
    public function __construct(
        protected KnowledgeRepository $repository = new KnowledgeRepository(),
    ) {
    }

    /**
     * @param  array<int,string>  $queryTokens
     * @param  array<int,string>  $expandedTerms  hasil query expansion (sinonim/typo)
     * @return array{
     *     knowledge: AiKnowledge, score: float, confidence: float,
     *     breakdown: array<string,float>, support: int
     * }
     */
    public function evaluate(
        AiKnowledge $knowledge,
        float $searchScore,
        array $queryTokens,
        array $expandedTerms = [],
        float $maxSearchScore = 0.0
    ): array {
        $queryTokens = array_values(array_unique(array_filter($queryTokens)));
        $queryCount = count($queryTokens);

        if ($queryCount === 0) {
            $queryCount = 1;
            $queryTokens = ['-'];
        }

        $weights = AIConfig::weights();
        $breakdown = [];
        $total = 0.0;

        // --- keyword: cakupan token query di dokumen -----------------------
        // Token hasil ekspansi (sinonim/typo) dihitung setengah bobot karena
        // tidak disebut langsung oleh pengguna.
        $docTokens = $this->tokens($this->documentText($knowledge));
        $directMatches = array_intersect($queryTokens, $docTokens);

        $extra = array_values(array_diff(
            array_intersect($expandedTerms, $docTokens),
            $queryTokens
        ));

        $keyword = (count($directMatches) + 0.5 * count($extra)) / $queryCount;
        $keyword = min(1.0, $keyword);
        $total += $keyword * ($weights['keyword'] ?? 0.35);
        $breakdown['keyword'] = round($keyword, 4);

        // --- title: cakupan token query di judul ----------------------------
        $titleTokens = $this->tokens((string) $knowledge->title);
        $title = $titleTokens === []
            ? 0.0
            : count(array_intersect($queryTokens, $titleTokens)) / $queryCount;
        $title = min(1.0, $title);
        $total += $title * ($weights['title'] ?? 0.25);
        $breakdown['title'] = round($title, 4);

        // --- category ------------------------------------------------------
        $categoryTokens = $this->tokens(
            str_replace('_', ' ', (string) $knowledge->category)
        );
        $category = count(array_intersect($queryTokens, $categoryTokens)) / $queryCount;
        $category = min(1.0, $category);
        $total += $category * ($weights['category'] ?? 0.15);
        $breakdown['category'] = round($category, 4);

        // --- content: skor pencarian hybrid ---------------------------------
        $content = $this->normalizeSearchScore($searchScore, $maxSearchScore);
        $total += $content * ($weights['content'] ?? 0.25);
        $breakdown['content'] = round($content, 4);

        $score = max(0.0, round($total, 6));
        $support = count($directMatches);

        // Confidence: relevance dominan, ditambah bonus dukungan token dan
        // bonus kekuatan pencarian.
        $supportBonus = match (true) {
            $support >= 3 => 0.15,
            $support === 2 => 0.12,
            $support === 1 => 0.06,
            default => 0.0,
        };

        // Kecocokan lewat sinonim/typo tetap memberi tambahan keyakinan.
        $expansionBonus = $extra === [] ? 0.0 : min(0.08, 0.04 * count($extra));

        $confidence = ($score * 0.7) + $supportBonus + $expansionBonus + ($content * 0.15);
        $confidence = max(0.0, min(1.0, round($confidence, 6)));

        return [
            'knowledge' => $knowledge,
            'score' => $score,
            'confidence' => $confidence,
            'breakdown' => $breakdown,
            'support' => $support,
        ];
    }

    protected function documentText(AiKnowledge $knowledge): string
    {
        return trim(
            $knowledge->title . ' '
            . implode(' ', $knowledge->keywords ?? []) . ' '
            . $knowledge->content
        );
    }

    /** @return array<int,string> */
    protected function tokens(string $text): array
    {
        return Normalizer::normalize($text);
    }

    /**
     * Skor pencarian dinormalisasi RELATIF terhadap skor tertinggi pada
     * sekumpulan hasil, bukan absolut. Ini menjaga perbedaan kekuatan
     * relevansi tetap terlihat (skor BM25 absolut bisa sangat kecil maupun
     * sangat besar tergantung ukuran korpus).
     */
    protected function normalizeSearchScore(float $score, float $max): float
    {
        if ($score <= 0.0 || $max <= 0.0) {
            return 0.0;
        }

        return max(0.0, min(1.0, $score / $max));
    }
}