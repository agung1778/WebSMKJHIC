<?php

namespace Tests\Feature;

use App\Services\ChatbotService;
use Tests\TestCase;

class ChatbotIntentTest extends TestCase
{
    private function reply(string $question): string
    {
        return app(ChatbotService::class)->ask($question)['reply'];
    }

    public function test_it_answers_subject_questions_with_only_the_relevant_teachers(): void
    {
        $reply = $this->reply('siapa guru ipas');

        $this->assertStringContainsString('IPAS', $reply);
        $this->assertStringContainsString('Ami Listiami, S.P.', $reply);
        $this->assertStringContainsString('Hadi Pasito, S.Si., Gr.', $reply);

        // Tidak boleh melempar daftar seluruh guru.
        $this->assertStringNotContainsString('Dr. Gugun Gunadi', $reply);
    }

    public function test_it_includes_the_position_of_each_teacher(): void
    {
        $reply = $this->reply('siapa guru ipas');

        $this->assertStringContainsString('Koordinator BK SMK Amaliah 1', $reply);
        $this->assertStringContainsString('Guru A1 & A2', $reply);
    }

    public function test_it_matches_a_subject_prefix_instead_of_falling_back_to_a_teacher_list(): void
    {
        $reply = $this->reply('guru ipa siapa');

        $this->assertStringContainsString('IPAS', $reply);
        $this->assertStringNotContainsString('Berikut guru/tenaga pendidik', $reply);
    }

    public function test_it_does_not_mix_subjects_that_only_share_a_generic_word(): void
    {
        // "bahasa" juga ada di mapel lain, tapi jawabannya harus Bahasa Jepang.
        $reply = $this->reply('guru bahasa jepang');

        $this->assertStringContainsString('B. Jepang', $reply);
        $this->assertStringNotContainsString('Bahasa Inggris', $reply);
    }

    public function test_it_says_so_honestly_when_a_subject_has_no_teacher(): void
    {
        $reply = $this->reply('guru fisika siapa');

        $this->assertStringContainsString('Belum ada guru', $reply);
        $this->assertStringContainsString('Fisika', $reply);
        $this->assertStringNotContainsString('Berikut guru/tenaga pendidik', $reply);
    }

    public function test_it_lists_all_teachers_only_when_no_subject_is_mentioned(): void
    {
        foreach (['siapa guru', 'daftar guru', 'sebutkan semua guru'] as $question) {
            $this->assertStringContainsString(
                'Berikut guru/tenaga pendidik',
                $this->reply($question),
                $question
            );
        }
    }

    public function test_it_answers_a_teacher_count_instead_of_a_list(): void
    {
        $reply = $this->reply('ada berapa guru');

        $this->assertMatchesRegularExpression('/\d+/', $reply);
        $this->assertStringNotContainsString('Berikut guru/tenaga pendidik', $reply);
    }

    public function test_it_does_not_invent_student_grades(): void
    {
        $reply = $this->reply('nilai rapor');

        $this->assertStringContainsString('tidak dipublikasikan', $reply);
    }

    public function test_it_still_answers_common_school_questions(): void
    {
        $this->assertStringContainsString('SMK Amaliah', $this->reply('dimana lokasi sekolah'));
        $this->assertStringContainsString('memiliki jurusan', $this->reply('apa saja jurusan'));
        $this->assertStringContainsString('Ekstrakurikuler', $this->reply('daftar ekstrakurikuler'));
        $this->assertStringContainsString('Kepala SMK Amaliah', $this->reply('siapa kepala sekolah'));
    }
}