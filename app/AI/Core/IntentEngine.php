<?php

namespace App\AI\Core;

use App\AI\Knowledge\KnowledgeRepository;
use App\AI\NLP\Normalizer;
use App\AI\NLP\Tokenizer;
use App\Models\AiKnowledge;

/**
 * Deteksi intent sederhana berbasis aturan (rule-based).
 *
 * Tidak menggunakan LLM, seluruh pola didefinisikan secara eksplisit
 * dan dapat diekspansi di ai_intents.
 */
class IntentEngine
{
    /** @var array<int,array{name:string,patterns:array<string>,category?:string}> */
    protected array $rules = [];

    public function __construct()
    {
        $this->loadDefaultRules();
        $this->loadDbRules();
    }

    /**
     * Intent yang cukup dijawab dengan teks baku (tanpa retrieval).
     *
     * @var array<int,string>
     */
    protected const CONVERSATIONAL = ['greeting', 'thanks', 'bye'];

    public function isConversational(string $intent): bool
    {
        return in_array($intent, self::CONVERSATIONAL, true);
    }

    /**
     * Jawaban baku untuk intent percakapan. Deliberately singkat dan
     * mengarahkan pengguna ke topik yang benar-benar tersedia.
     */
    public function cannedReply(string $intent, string $question = ''): string
    {
        $hour = (int) date('G');

        return match ($intent) {
            'greeting' => 'Halo'
                . ($hour < 11 ? ' pagi' : ($hour < 15 ? ' siang' : ($hour < 18 ? ' sore' : ' malam')))
                . '! Saya asisten informasi SMK Amaliah. Silakan tanyakan tentang '
                . 'jurusan, pendaftaran (SPMB), fasilitas, kegiatan, ekstrakurikuler, '
                . 'atau guru di sekolah ini.',
            'thanks' => 'Sama-sama! Kalau ada pertanyaan lain tentang SMK Amaliah, silakan saja.',
            'bye' => 'Sampai jumpa! Semoga bermanfaat.',
            default => 'Silakan tanyakan seputar SMK Amaliah.',
        };
    }

    public function detect(string $question): string
    {
        $q = strtolower($question);

        foreach ($this->rules as $rule) {
            foreach ($rule['patterns'] as $pattern) {
                if ($pattern === '') {
                    continue;
                }

                // Regex tetap dipakai apa adanya; pattern teks biasa dicocokkan
                // pada batas kata agar "lab" tidak cocok di dalam "syllabus".
                if (@preg_match(self::toRegex($pattern), $q)) {
                    return $rule['name'];
                }
            }
        }

        return 'general';
    }

    /**
     * Deteksi apakah sebuah pattern adalah regex.
     *
     * Pattern dianggap regex bila sudah dibungkus delimiter (/…/ atau #…#),
     * atau mengandung metakarakter reguler (| ( ) [ ] + ?) sehingga rule yang
     * disimpan di ai_intents tetap dapat memakai regex tanpa delimiter.
     */
    protected static function isRegexPattern(string $pattern): bool
    {
        if (
            (str_starts_with($pattern, '/') && str_ends_with($pattern, '/'))
            || (str_starts_with($pattern, '#') && str_ends_with($pattern, '#'))
        ) {
            return true;
        }

        return (bool) preg_match('/[|()\[\]+?]/', $pattern)
            && ! str_contains($pattern, ' ');
    }

    /**
     * Regex siap pakai untuk sebuah pattern (dibungkus bila perlu).
     */
    protected static function toRegex(string $pattern): string
    {
        if (self::isRegexPattern($pattern)) {
            return $pattern;
        }

        return self::wordPattern($pattern);
    }

    /**
     * Bangun regex batas kata yang aman untuk pattern teks biasa.
     */
    protected static function wordPattern(string $pattern): string
    {
        return '/(?<![\p{L}\p{N}])'
            . preg_quote(mb_strtolower($pattern), '/')
            . '(?:nya|mu|ku|lah|kah|an)?(?![\p{L}\p{N}])/u';
    }

    /**
     * Kategori knowledge yang paling relevan untuk sebuah intent.
     *
     * Dipakai sebagai bonus relevansi agar pertanyaan dengan token umum
     * (mis. "dimana sekolah") tetap bisa menjangkau kategori yang tepat.
     *
     * @return array<int,string>
     */
    public function categoriesFor(string $intent): array
    {
        return self::INTENT_CATEGORIES[$intent] ?? [];
    }

    /** @var array<string,array<int,string>> */
    public const INTENT_CATEGORIES = [
        'spmb' => ['spmb'],
        'major_list' => ['major'],
        'major_detail' => ['major'],
        'teacher' => ['teacher'],
        'facility' => ['facility'],
        'extracurricular' => ['extracurricular'],
        'achievement' => ['achievement'],
        'news' => ['news'],
        'partner' => ['partner'],
        'testimonial' => ['testimonial'],
        'program' => ['program'],
        'location' => ['identity', 'contact'],
        'contact' => ['contact'],
        'statistic' => ['school_stat'],
        'vision_mission' => ['about'],
        'history' => ['about'],
        'about' => ['about', 'identity'],
        'general' => [],
    ];

    protected function loadDefaultRules(): void
    {
        $this->rules = [
            [
                'name' => 'greeting',
                'patterns' => [
                    'halo', 'hai', 'hey', 'hello', 'helo', 'pagi',
                    'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam',
                    'assalam', 'alaikum', 'assalamualaikum',
                ],
            ],
            [
                'name' => 'thanks',
                'patterns' => ['terima kasih', 'makasih', 'terimakasih', 'thank', 'thanks'],
            ],
            [
                'name' => 'bye',
                'patterns' => ['bye', 'dadah', 'sampai jumpa', 'terima kasih sudah', 'wassalam'],
            ],
            [
                'name' => 'whoami_help',
                'patterns' => ['bisa bantu', 'tolong bantu', 'bisa jelasin', 'bantu dong', 'help'],
            ],
            [
                'name' => 'spmb',
                'patterns' => [
                    'spmb', 'ppdb', 'pendaftaran', 'pendaftar', 'mendaftar',
                    'mendaftarkan', 'registrasi', 'biaya masuk',
                    // "daftar" hanya bermakna sebagai pendaftaran bila tidak
                    // diikuti kata jurusan/prodi/ekskul, yang ditangani
                    // major_list dan extracurricular.
                    '#daftar\b(?!.{0,12}\b(jurusan|prodi|fakultas|ekstrakurikuler|ekskul))#',
                ],
            ],
            [
                'name' => 'major_detail',
                'patterns' => [
                    '#\bjurusan\s+(rpl|tkj|multimedia|animasi|tata boga|pemasaran|akuntansi)\b#',
                    'tentang jurusan',
                ],
            ],
            [
                'name' => 'major_list',
                'patterns' => ['jurusan', 'jurusan apa', 'jurusan apa aja', 'jurusan apa saja', 'daftar jurusan', 'jurusan tersedia', 'ada jurusan apa', 'prodi', 'jurusan apa'],
            ],
            [
                'name' => 'teacher',
                'patterns' => ['guru', 'pengajar', 'dosen sekolah', 'siapa guru'],
            ],
            [
                'name' => 'facility',
                'patterns' => ['fasilitas', 'lab', 'laboratorium', 'perpustakaan', 'lapangan', 'kantin'],
            ],
            [
                'name' => 'extracurricular',
                'patterns' => ['ekstrakurikuler', 'ekskul', 'pramuka', 'paskibra', 'osis', 'rohis'],
            ],
            [
                'name' => 'achievement',
                'patterns' => ['prestasi', 'juara', 'lomba', 'pemenang'],
            ],
            [
                'name' => 'news',
                'patterns' => ['berita', 'informasi terbaru', 'kegiatan sekolah', 'pengumuman'],
            ],
            [
                'name' => 'partner',
                'patterns' => ['mitra', 'industri', 'perusahaan mitra', 'kerjasama'],
            ],
            [
                'name' => 'testimonial',
                'patterns' => ['testimoni', 'alumni', 'pendapat alumni'],
            ],
            [
                'name' => 'program',
                'patterns' => ['program sekolah', 'kegiatan unggulan'],
            ],
            [
                'name' => 'location',
                'patterns' => ['alamat', 'lokasi', 'dimana', 'di mana', 'rumahnya sekolah', 'alamat sekolah'],
            ],
            [
                'name' => 'contact',
                'patterns' => ['kontak', 'telepon', 'nomor wa', 'wa me', 'whatsapp', 'email', 'hubungi', 'sosial media', 'instagram sekolah', 'akun instagram'],
            ],
            [
                'name' => 'statistic',
                'patterns' => ['jumlah siswa', 'banyak siswa', 'terbanyak siswa', 'siswa', 'jumlah murid', 'statistik sekolah'],
            ],
            [
                'name' => 'vision_mission',
                'patterns' => ['visi', 'misi', 'visi misi'],
            ],
            [
                'name' => 'history',
                'patterns' => ['sejarah', 'sejarah sekolah'],
            ],
            [
                'name' => 'about',
                'patterns' => ['tentang sekolah', 'profil sekolah', 'smk amaliah', 'yayasan', 'sebagai sekolah', 'deskripsi sekolah'],
            ],
        ];
    }

    protected function loadDbRules(): void
    {
        try {
            foreach (\App\Models\AiIntent::where('is_active', true)->get() as $intent) {
                $patterns = $intent->patterns ?? [];

                if (! is_array($patterns) || $patterns === []) {
                    continue;
                }

                $this->rules[] = [
                    'name' => $intent->name,
                    'patterns' => array_map(static fn ($p): string => (string) $p, $patterns),
                    'category' => $intent->category,
                ];
            }
        } catch (\Throwable) {
            // Abaikan jika tabel belum ada.
        }
    }
}