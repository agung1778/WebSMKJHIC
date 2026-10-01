<?php

namespace App\AI\Core;

use App\AI\Memory\ConversationMemory;
use App\AI\Support\AIConfig;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Orkestrator utama AI Engine (pipeline: question → intent → expansion
 * → search → relevance → context → answer → response).
 *
 * Seluruh operasi bersifat read-only terhadap database, lokal, dan tidak
 * memanggil layanan AI eksternal.
 */
class AIEngine
{
    public function __construct(
        protected QueryExpansion $expansion = new QueryExpansion(),
        protected SearchEngine $search = new SearchEngine(),
        protected RelevanceEngine $relevance = new RelevanceEngine(),
        protected IntentEngine $intent = new IntentEngine(),
        protected EntityExtractor $entities = new EntityExtractor(),
        protected ContextBuilder $context = new ContextBuilder(),
        protected AnswerGenerator $answer = new AnswerGenerator(),
        protected ResponseFormatter $formatter = new ResponseFormatter(),
        protected ConversationMemory $memory = new ConversationMemory(),
    ) {
    }

    /**
     * @param  array<string,mixed>  $options
     */
    public function chat(string $message, ?string $sessionId = null, array $options = []): array
    {
        $start = microtime(true);

        try {
            $sessionId = $sessionId ?: ConversationMemory::generateSessionId();
            $question = trim($message);

            if ($question === '') {
                return $this->formatter->error('Pertanyaan tidak boleh kosong.');
            }

            if (mb_strlen($question) > AIConfig::maxQuestionLength()) {
                $question = mb_substr($question, 0, AIConfig::maxQuestionLength());
            }

            // Ingat pertanyaan pengguna
            $this->memory->rememberUser($sessionId, $question);

            // 1. Intent & entities
            $intent = $this->intent->detect($question);

            // Sapaan/sosial tidak butuh retrieval: kembalikan jawaban baku
            // agar tidak ada sumber yang tidak relevan ikut terpakai.
            if ($this->intent->isConversational($intent)) {
                return $this->formatter->direct(
                    $this->intent->cannedReply($intent, $question),
                    $intent
                );
            }

            // Pertanyaan lanjutan yang bergantung konteks ("terus bagaimana?")
            // digabung dengan fokus percakapan sebelumnya. Bila pertanyaan
            // baru menyebut topik yang berbeda (mis. "itu di mana?" setelah
            // pertanyaan jurusan), topik baru itu yang menang agar tidak
            // membawa konteks lama ke kategori yang keliru.
            $context = $this->memory->getContext($sessionId);
            $isFollowUp = false;

            if ($context !== [] && $intent === 'general' && $this->looksLikeFollowUp($question, $context)) {
                // Hanya berlaku bila pertanyaan baru tidak membawa intent
                // sendiri; bila ada ("terus bagaimana mendaftarnya" → spmb)
                // topik baru itu yang menentukan, bukan sisa konteks lama.
                $question = trim($question . ' ' . implode(' ', $context['focus']));
                $isFollowUp = true;

                if (! empty($context['intent'])) {
                    $intent = (string) $context['intent'];
                }
            }

            $entities = $this->entities->extract($question);

            // 2. Query expansion
            $expanded = $this->expansion->expand($question, 10);

            // Deteksi ulang intent pada bentuk yang sudah dinormalisasi bila
            // hasil pertama umum (mis. typo "extracurricularnya" ->
            // "ekstrakurikuler" yang memakai ejaan Indonesia).
            if ($intent === 'general' && $expanded !== []) {
                $refined = $this->intent->detect(implode(' ', $expanded));

                if ($refined !== 'general') {
                    $intent = $refined;
                }
            }

            // Kategori yang cocok dengan intent diberi bonus agar pertanyaan
            // dengan token umum (mis. "dimana sekolah") tetap relevan.
            $intentCategories = $this->intent->categoriesFor($intent);
            $categoryBoost = $intentCategories === [] ? 0.0 : 0.22;

            $queryTokens = array_values(array_unique(\App\AI\NLP\Tokenizer::tokensForSearch($question)));

            // Pertanyaan yang seluruh tokennya stopword ("itu di mana",
            // "lalu bagaimana") tidak punya sinyal retrieval. Dalam kasus ini
            // intent adalah satu-satunya petunjuk, sehingga label kategori
            // dipakai sebagai token pengganti agar retrieval tetap mungkin.
            $intentDriven = false;

            if ($queryTokens === [] && $intentCategories !== []) {
                $queryTokens = $this->tokensFromCategories($intentCategories);
                $intentDriven = true;
            }

            // Cakupan: pertanyaan harus menyentuh domain sekolah. Tanpa gate ini
            // query umum ("kursi kantor", "berapa 1+1") bisa mendapat skor
            // dari dokumen yang kebetulan memuat kata tersebut. Mode intentDriven
            // sudah lolos karena sinyalnya datang dari intent, bukan dari teks.
            if (! $intentDriven && ! $this->withinScope($question, $intent, $entities, $expanded)) {
                return $this->formatter->direct(
                    'Maaf, saya hanya bisa menjawab pertanyaan seputar SMK Amaliah '
                    . 'seperti jurusan, pendaftaran, fasilitas, kegiatan, guru, '
                    . 'dan informasi sekolah lainnya.',
                    'out_of_scope'
                );
            }

            // 3. Search
            $searchResults = $this->search->search($question, $expanded, AIConfig::maxResults());

            // 4. Relevance

            $maxSearchScore = 0.0;

            foreach ($searchResults as $item) {
                $maxSearchScore = max($maxSearchScore, (float) $item['score']);
            }

            $evaluated = [];

            foreach ($searchResults as $item) {
                $result = $this->relevance->evaluate(
                    $item['knowledge'],
                    $item['score'],
                    $queryTokens,
                    $expanded,
                    $maxSearchScore
                );

                if ($categoryBoost > 0.0 && in_array($item['knowledge']->category, $intentCategories, true)) {
                    $result['score'] = round(min(1.0, $result['score'] + $categoryBoost), 6);
                    $result['confidence'] = round(min(1.0, $result['confidence'] + ($categoryBoost * 0.5)), 6);
                    $result['breakdown']['intent_category'] = $categoryBoost;
                }

                $evaluated[] = $result;
            }

            // 5. Dorong kandidat dari kategori intent agar tetap ikut penilaian
            // meski tidak muncul di hasil search keyword.
            if ($intentCategories !== []) {
                $existingIds = array_map(
                    static fn (array $e): int => $e['knowledge']->id,
                    $evaluated
                );

                $boosted = \App\Models\AiKnowledge::whereIn('category', $intentCategories)
                    ->whereNotIn('id', $existingIds)
                    ->limit(3)
                    ->get();

                foreach ($boosted as $knowledge) {
                    $result = $this->relevance->evaluate($knowledge, 0.0, $queryTokens, $expanded, $maxSearchScore);

                    if ($intentDriven) {
                        // Tidak ada sinyal tekstual sama sekali, jadi dokumen
                        // dari kategori intent adalah jawaban terbaik yang ada.
                        // Skor dasar moderat dipakai agar tidak dianggap
                        // "tidak yakin" maupun terlalu jauh di atas jawaban
                        // yang benar-benar berbasis keyword.
                        $result['score'] = round(max($result['score'], 0.55), 6);
                        $result['confidence'] = round(max($result['confidence'], 0.5), 6);
                        $result['breakdown']['intent_driven'] = 0.55;
                    }

                    $result['score'] = round(min(1.0, $result['score'] + $categoryBoost), 6);
                    $result['confidence'] = round(min(1.0, $result['confidence'] + ($categoryBoost * 0.4)), 6);
                    $result['breakdown']['intent_category'] = $categoryBoost;
                    $evaluated[] = $result;
                }
            }

            // 5. Urutkan berdasarkan score desc
            usort($evaluated, static fn ($a, $b): int => $b['score'] <=> $a['score']);

            // 6. Context & sources
            $topScore = $evaluated !== [] ? (float) $evaluated[0]['score'] : 0.0;
            $topConfidence = $evaluated !== [] ? (float) $evaluated[0]['confidence'] : 0.0;

            $ctx = $this->context->build($evaluated);

            // 7. Answer
            $answer = $this->answer->generate(
                $question,
                $intent,
                $ctx['context'],
                $ctx['sources'],
                $topConfidence,
                $topScore
            );

            // 8. Response
            $duration = (microtime(true) - $start) * 1000;

            $meta = [
                'session_id' => $sessionId,
                'expanded_terms' => $expanded,
                'entities' => $entities,
                'evaluated_count' => count($evaluated),
                'duration_ms' => round($duration, 2),
                'mode' => AIConfig::mode(),
            ];

            if (AIConfig::isDebug()) {
                $meta['evaluated'] = array_slice(array_map(static fn ($e) => [
                    'category' => $e['knowledge']->category,
                    'title' => $e['knowledge']->title,
                    'score' => $e['score'],
                    'confidence' => $e['confidence'],
                    'support' => $e['support'],
                ], $evaluated), 0, 5);
            }

            $response = $this->formatter->format(
                true,
                $answer,
                $intent,
                $topConfidence,
                $topScore,
                $ctx['sources'],
                $meta
            );

            // Ingat jawaban asisten
            $this->memory->rememberAssistant($sessionId, $answer, $meta);

            // Simpan pointer percakapan untuk pertanyaan lanjutan.
            $this->memory->rememberContext($sessionId, [
                'intent' => $intent,
                'focus' => array_values(array_filter(array_merge(
                    $entities['majors'] ?? [],
                    $entities['teachers'] ?? [],
                    $entities['extracurriculars'] ?? [],
                    $entities['subjects'] ?? [],
                    array_slice($expanded, 0, 3),
                ))),
                'titles' => array_map(
                    static fn (array $s): string => (string) $s['title'],
                    array_slice($ctx['sources'], 0, 3)
                ),
                'question' => $question,
            ]);

            // Logging pencarian
            $this->memory->logSearch([
                'question' => $question,
                'intent' => $intent,
                'entities' => $entities,
                'expanded_terms' => $expanded,
                'method' => $isFollowUp ? 'hybrid+followup' : 'hybrid',
                'result_count' => count($ctx['sources']),
                'top_score' => $topScore,
                'duration_ms' => round($duration, 2),
            ]);

            return $response;
        } catch (Throwable $e) {
            // During local debugging the log channel may be unavailable, so the
            // exception is surfaced via stderr when explicitly requested.
            if (getenv('AI_ENGINE_DEBUG')) {
                fwrite(STDERR, get_class($e) . ': ' . $e->getMessage() . "\n"
                    . $e->getFile() . ':' . $e->getLine() . "\n"
                    . $e->getTraceAsString() . "\n");
            }

            Log::error('AI Engine error: ' . $e->getMessage(), ['exception' => $e]);

            return $this->formatter->error();
        }
    }

    public function ask(string $question, ?string $sessionId = null): array
    {
        return $this->chat($question, $sessionId);
    }

    /**
     * Deteksi pertanyaan lanjutan yang hanya bermakna bersama konteks
     * percakapan sebelumnya.
     *
     * @param  array<string,mixed>  $context
     */
    /**
     * Token pengganti dari nama kategori intent.
     *
     * Dipakai ketika pertanyaan hanya comprised dari kata umum/stopword
     * sehingga tidak ada sinyal retrieval lain.
     *
     * @param  array<int,string>  $categories
     * @return array<int,string>
     */
    protected function tokensFromCategories(array $categories): array
    {
        $tokens = [];

        foreach ($categories as $category) {
            foreach (\App\AI\NLP\Normalizer::normalize(str_replace('_', ' ', (string) $category)) as $token) {
                if (! in_array($token, $tokens, true)) {
                    $tokens[] = $token;
                }
            }
        }

        return $tokens;
    }

    /**
     * @param  array<string,mixed>  $context
     */
    protected function looksLikeFollowUp(string $question, array $context): bool
    {
        $text = mb_strtolower(trim($question));

        if ($text === '' || mb_strlen($text) > 90) {
            return false;
        }

        foreach (self::REFERENTIAL_TERMS as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }

        // Pertanyaan sangat pendek (1-2 kata) tanpa topik sendiri dianggap lanjutan.
        // Batas ini sengaja ketat: pertanyaan bertiga kata atau lebih dianggap
        // topik baru agar konteks lama tidak ikut tercampur ("harga iphone terbaru").
        $meaningful = array_filter(
            preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [],
            static fn (string $w): bool => mb_strlen($w) > 2
        );

        return count($meaningful) <= 2;
    }

    /**
     * Kata penunjuk yang bergantung pada percakapan sebelumnya.
     *
     * @var array<int,string>
     */
    protected const REFERENTIAL_TERMS = [
        'itu', 'ini', 'tersebut', 'terus', 'lanjutan', 'lagi',
        'juga', 'sama', 'selain itu', 'kemudian', 'bagaimana', 'berapa',
    ];

    /**
     * Gate cakupan: menyaring pertanyaan yang sama sekali tidak berhubungan
     * dengan domain sekolah sebelum pencarian dijalankan.
     *
     * Lolos bila salah satu kondisi terpenuhi:
     * - intent terdeteksi dan bukan umum, atau
     * - pertanyaan mengandung token domain sekolah, atau
     * - pertanyaan mengandung entity yang dikenal (mis. nama jurusan).
     *
     * @param  array<string,mixed>  $entities
     * @param  array<int,string>  $expanded
     */
    protected function withinScope(string $question, string $intent, array $entities, array $expanded): bool
    {
        if ($intent !== 'general' && $intent !== '') {
            return true;
        }

        $text = mb_strtolower($question . ' ' . implode(' ', $expanded));

        foreach (self::SCOPE_PHRASES as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }

        // Istilah pendek/singkat dicek pada batas kata supaya "ipa" tidak
        // ikut cocok di dalam kata lain.
        foreach (self::SCOPE_WORDS as $word) {
            if (preg_match('/(?<![\p{L}\p{N}])' . preg_quote($word, '/') . '(?![\p{L}\p{N}])/u', $text)) {
                return true;
            }
        }

        // Entity yang benar-benar dikenal (nama jurusan, guru, lokasi).
        foreach (['majors', 'teachers', 'subjects', 'extracurriculars', 'locations'] as $bucket) {
            foreach ((array) ($entities[$bucket] ?? []) as $value) {
                if (is_string($value) && trim($value) !== '') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Frasa khas konteks sekolah; dicek dengan pencocokan substring.
     *
     * @var array<int,string>
     */
    protected const SCOPE_PHRASES = [
        'sekolah', 'amaliah', 'siswa', 'murid', 'guru', 'mengajar',
        'jurusan', 'spmb', 'ppdb', 'pendaftaran', 'daftar',
        'fasilitas', 'ruang kelas', 'perpustakaan', 'laboratorium',
        'kantin', 'lapangan', 'ekstrakurikuler', 'pramuka',
        'kegiatan', 'berita', 'pengumuman', 'acara',
        'prestasi', 'mitra', 'kerjasama', 'program sekolah',
        'alamat', 'lokasi', 'kontak', 'telepon', 'whatsapp', 'email',
        'instagram', 'facebook', 'youtube', 'visi', 'misi', 'yayasan',
        'profil', 'sejarah', 'statistik', 'jumlah', 'mata pelajaran', 'nilai',
        'kurikulum', 'wali kelas', 'kapro', 'kepala sekolah', 'alumni',
        'testimoni', 'beasiswa', 'rekrutmen', 'smk',
    ];

    /**
     * Kata singkat khas sekolah; dicek pada batas kata.
     *
     * @var array<int,string>
     */
    protected const SCOPE_WORDS = [
        'lab', 'ekskul', 'paskibra', 'osis', 'rohis', 'juara', 'lomba',
        'prodi', 'smk',
    ];
}