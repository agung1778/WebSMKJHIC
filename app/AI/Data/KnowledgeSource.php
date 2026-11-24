<?php

namespace App\AI\Data;

/**
 * Kontrak sumber knowledge. Setiap sumber data (database, halaman website,
 * dokumen) diimplementasikan sebagai kelas yang memenuhi interface ini.
 *
 * Metode fetch() wajib mengembalikan array of KnowledgeRecord.
 */
interface KnowledgeSource
{
    /**
     * @return array<int, \App\AI\Knowledge\KnowledgeRecord>
     */
    public function fetch(): array;
}