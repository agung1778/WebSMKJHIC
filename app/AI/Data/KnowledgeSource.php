<?php

namespace App\AI\Data;

/**
 * Kontrak untuk SEMUA sumber pengetahuan AI.
 *
 * Engine hanya mengenal interface ini — tidak pernah bicara langsung ke
 * controller website atau query string SQL. Menambah sumber baru (mis. API
 * internal, file JSON, Google Drive) cukup dengan implements KnowledgeSource.
 */
interface KnowledgeSource
{
    /**
     * Cari record relevan untuk query (sudah di-tokenize).
     *
     * @param  array<int,string>  $tokens  token hasil normalisasi+stemming
     * @return array<int,array>  daftar record: ['uid','type','title','content','category','url', ...]
     */
    public function search(string $query, array $tokens): array;

    /**
     * Ambil satu record berdasarkan uid (mis. "major-3").
     *
     * @return array<string,mixed>|null
     */
    public function get(string $uid): ?array;

    /**
     * Tarik semua record dari sumber ini (dipakai auto indexer).
     *
     * @return array<int,array>
     */
    public function sync(): array;

    /** Nama sumber, mis. "database". */
    public function name(): string;

    /** Sumber ini boleh dibaca engine? */
    public function enabled(): bool;
}