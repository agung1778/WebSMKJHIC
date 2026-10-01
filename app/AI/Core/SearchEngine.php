<?php

namespace App\AI\Core;

use App\AI\Knowledge\KnowledgeRepository;
use App\AI\NLP\Normalizer;
use App\Models\AiKnowledge;

/**
 * Mesin pencarian (hybrid ringan): keyword + TF-IDF + BM25.
 *
 * Prioritas: BM25 untuk relevansi dokumen panjang, TF-IDF untuk recall,
 * keyword (exact/partial token match) sebagai booster. Tidak menggunakan
 * embedding eksternal.
 */
class SearchEngine
{
    public function __construct(
        protected KnowledgeRepository $repository = new KnowledgeRepository(),
    ) {
    }

    /**
     * @return array<int, array{
     *     knowledge: AiKnowledge,
     *     score: float,
     *     method: string,
     *     terms: array<string,int>
     * }>
     */
    public function search(string $question, array $expandedTerms = [], int $limit = 8): array
    {
        $allKnowledge = $this->repository->all();

        if ($allKnowledge->isEmpty()) {
            return [];
        }

        $queryTerms = $this->queryTerms($question, $expandedTerms);
        $queryTf = $this->normalizeTf($queryTerms);

        $results = [];

        foreach ($allKnowledge as $knowledge) {
            $docTerms = Normalizer::termFrequency($this->documentText($knowledge));
            $bm25 = $this->bm25($queryTf, $docTerms);
            $tfidf = $this->tfidf($queryTf, $docTerms);
            $keyword = $this->keywordScore($queryTf, $docTerms);

            $score = $bm25 * 0.6 + $tfidf * 0.25 + $keyword * 0.15;
            $score = max(0.0, round($score, 6));

            if ($score <= 0.0) {
                continue;
            }

            $results[] = [
                'knowledge' => $knowledge,
                'score' => $score,
                'method' => 'hybrid',
                'terms' => $docTerms,
            ];
        }

        usort($results, static fn ($a, $b): int => $b['score'] <=> $a['score']);

        return array_slice($results, 0, $limit);
    }

    /** @return array<string,int> */
    protected function queryTerms(string $question, array $expandedTerms): array
    {
        $base = Normalizer::termFrequency($question);

        foreach ($expandedTerms as $term) {
            if ($term === '') {
                continue;
            }
            $base[$term] = ($base[$term] ?? 0) + 0.8;
        }

        return $base;
    }

    /** @param  array<string,int|float>  $terms */
    protected function normalizeTf(array $terms): array
    {
        $max = max(1.0, array_sum($terms));

        $out = [];

        foreach ($terms as $token => $count) {
            $out[$token] = ((float) $count) / $max;
        }

        return $out;
    }

    /** @param  array<string,float>  $queryTf */
    protected function bm25(array $queryTf, array $docTerms): float
    {
        $k1 = (float) config('ai.bm25_k1', 1.5);
        $b = (float) config('ai.bm25_b', 0.75);

        $n = $this->repository->totalDocuments();
        $avgLen = $this->repository->averageLength();
        $docLen = array_sum($docTerms);
        $dl = $docLen <= 0 ? 1.0 : $docLen;

        $score = 0.0;

        foreach ($queryTf as $token => $weight) {
            $tf = (float) ($docTerms[$token] ?? 0);

            if ($tf <= 0.0) {
                continue;
            }

            $idf = $this->repository->idfFor($token);

            $num = $tf * ($k1 + 1);
            $den = $tf + $k1 * (1 - $b + $b * ($dl / $avgLen));

            if ($den <= 0.0) {
                continue;
            }

            $score += $idf * ($num / $den) * ($weight + 1);
        }

        return $score;
    }

    /** @param  array<string,float>  $queryTf */
    protected function tfidf(array $queryTf, array $docTerms): float
    {
        $idfMap = $this->repository->idf();

        $score = 0.0;

        foreach ($queryTf as $token => $weight) {
            $tf = (float) ($docTerms[$token] ?? 0);

            if ($tf <= 0.0) {
                continue;
            }

            $idf = $idfMap[$token] ?? $this->repository->idfFor($token);
            $score += $weight * $tf * $idf;
        }

        return $score;
    }

    /** @param  array<string,float>  $queryTf */
    protected function keywordScore(array $queryTf, array $docTerms): float
    {
        $queryTokens = array_keys($queryTf);
        $docTokens = array_keys($docTerms);

        if ($queryTokens === [] || $docTokens === []) {
            return 0.0;
        }

        $inter = count(array_intersect($queryTokens, $docTokens));
        $union = count(array_unique(array_merge($queryTokens, $docTokens)));

        if ($union === 0) {
            return 0.0;
        }

        return $inter / $union;
    }

    protected function documentText(AiKnowledge $knowledge): string
    {
        return trim(
            $knowledge->title . ' '
            . implode(' ', $knowledge->keywords ?? []) . ' '
            . $knowledge->content
        );
    }
}