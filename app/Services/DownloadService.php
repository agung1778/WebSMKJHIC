<?php

namespace App\Services;

use App\Models\SchoolDownload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;
use ZipArchive;

class DownloadService
{
    public function create(array $attributes, UploadedFile $file, int $userId): SchoolDownload
    {
        if (($attributes['category'] ?? 'logo') !== 'pkl') {
            $attributes['major_id'] = null;
        }
        $metadata = $this->storeOriginal($file, $attributes['category'] ?? 'logo');
        try {
            return DB::transaction(fn () => SchoolDownload::create(array_merge($attributes, $metadata, ['uploaded_by' => $userId])));
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($metadata['file_path']);
            throw $exception;
        }
    }

    public function update(SchoolDownload $logo, array $attributes, ?UploadedFile $file): void
    {
        $oldPath = $logo->file_path;
        $category = $attributes['category'] ?? $logo->category;
        if ($category !== 'pkl') {
            $attributes['major_id'] = null;
        }
        if (! $file && ! in_array($logo->extension, SchoolDownload::formatsFor($category), true)) {
            throw ValidationException::withMessages(['file' => 'Ganti file sesuai kategori yang dipilih.']);
        }
        $metadata = $file ? $this->storeOriginal($file, $category) : [];
        try {
            DB::transaction(fn () => $logo->fill(array_merge($attributes, $metadata))->save());
        } catch (Throwable $exception) {
            if ($metadata !== []) {
                Storage::disk('local')->delete($metadata['file_path']);
            }
            throw $exception;
        }
        // Only remove the previous file after the replacement record is committed.
        if ($metadata !== []) {
            Storage::disk('local')->delete($oldPath);
        }
    }

    public function delete(SchoolDownload $logo): void
    {
        $path = $logo->file_path;
        DB::transaction(fn () => $logo->delete());
        Storage::disk('local')->delete($path);
    }

    public function exists(SchoolDownload $logo): bool
    {
        return (str_starts_with($logo->file_path, 'logo-downloads/') || str_starts_with($logo->file_path, 'downloads/'))
            && ! str_contains($logo->file_path, '..')
            && Storage::disk('local')->exists($logo->file_path);
    }

    public function path(SchoolDownload $logo): string
    {
        abort_unless($this->exists($logo), 404);

        return Storage::disk('local')->path($logo->file_path);
    }

    private function storeOriginal(UploadedFile $file, string $category): array
    {
        $mime = $file->getMimeType();
        if (strtolower($file->getClientOriginalExtension()) === 'docx'
            || $mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $this->validateDocx($file);
            $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }
        $extension = match ($mime) {
            'image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp', 'application/pdf' => 'pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            default => throw ValidationException::withMessages(['file' => 'Jenis file tidak didukung.']),
        };
        if (! in_array($extension, SchoolDownload::formatsFor($category), true)) {
            throw ValidationException::withMessages(['file' => 'Format file tidak sesuai kategori yang dipilih.']);
        }
        $isImage = str_starts_with($mime, 'image/');
        $dimensions = $isImage ? @getimagesize($file->getRealPath()) : false;
        if ($isImage && $dimensions === false) {
            throw ValidationException::withMessages(['file' => 'File gambar logo tidak dapat dibaca.']);
        }
        $filename = basename(str_replace('\\', '/', $file->getClientOriginalName()));
        $stem = preg_replace('/[\x00-\x1F\x7F]/', '', pathinfo($filename, PATHINFO_FILENAME));
        $filename = mb_substr(trim($stem) ?: 'unduhan', 0, 200).'.'.$extension;
        $path = $file->store('downloads', 'local');
        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages(['file' => 'File gagal disimpan. Silakan coba kembali atau hubungi pengelola server.']);
        }

        // Store bytes as-is; no resizing, optimization, or image re-encoding.
        return [
            'file_path' => $path, 'original_name' => $filename, 'mime_type' => $mime,
            'extension' => $extension, 'file_size' => $file->getSize(),
            'width' => $dimensions ? $dimensions[0] : null,
            'height' => $dimensions ? $dimensions[1] : null,
        ];
    }

    private function validateDocx(UploadedFile $file): void
    {
        $invalid = fn () => throw ValidationException::withMessages(['file' => 'DOCX tidak valid, terlalu kompleks, atau mengandung macro/objek aktif. Gunakan DOCX tanpa macro atau PDF.']);
        if (! class_exists(ZipArchive::class)) {
            throw ValidationException::withMessages(['file' => 'Server belum mendukung pemeriksaan DOCX (ekstensi PHP ZIP). Gunakan PDF atau hubungi pengelola server.']);
        }
        $zip = new ZipArchive;
        if (@$zip->open($file->getRealPath()) !== true) {
            $invalid();
        }
        try {
            if ($zip->numFiles > 2000) {
                $invalid();
            }
            $total = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->statIndex($i);
                if ($entry === false) {
                    $invalid();
                }
                $name = str_replace('\\', '/', $entry['name']);
                $total += $entry['size'];
                if ($total > 104857600 || str_contains($name, '..') || str_starts_with($name, '/')
                    || ($entry['encryption_method'] ?? 0) !== 0
                    || preg_match('~(^|/)(vbaProject\.bin|activeX|embeddings)(/|$)~i', $name)) {
                    $invalid();
                }
            }
            $types = $this->readDocxXml($zip, '[Content_Types].xml', 1048576);
            $document = $this->readDocxXml($zip, 'word/document.xml', 5242880);
            $relationships = $this->readDocxXml($zip, '_rels/.rels', 1048576);
            $relationshipsPath = new \DOMXPath($relationships);
            $target = $relationshipsPath->evaluate('string(/*[local-name()="Relationships"]/*[local-name()="Relationship"][contains(@Type, "/officeDocument") and not(@TargetMode="External")]/@Target)');
            $xpath = new \DOMXPath($types);
            $mainType = $xpath->evaluate('string(/*[local-name()="Types"]/*[local-name()="Override"][@PartName="/word/document.xml"]/@ContentType)');
            if (! in_array($target, ['word/document.xml', '/word/document.xml'], true)
                || preg_match('/vbaProject|macroEnabled|activeX|oleObject/i', $types->saveXML())
                || $mainType !== 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml'
                || $document->documentElement?->localName !== 'document'
                || ! in_array($document->documentElement?->namespaceURI, [
                    'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
                    'http://purl.oclc.org/ooxml/wordprocessingml/main',
                ], true)) {
                $invalid();
            }
        } finally {
            $zip->close();
        }
    }

    private function readDocxXml(ZipArchive $zip, string $name, int $limit): \DOMDocument
    {
        $stat = $zip->statName($name);
        $xml = $stat && $stat['size'] <= $limit ? $zip->getFromName($name) : false;
        if (! is_string($xml) || $xml === '' || str_contains($xml, "\0") || preg_match('/<!DOCTYPE|<!ENTITY/i', $xml)) {
            throw ValidationException::withMessages(['file' => 'Struktur DOCX tidak valid atau tidak aman.']);
        }
        $document = new \DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            if (! $document->loadXML($xml, LIBXML_NONET)) {
                throw ValidationException::withMessages(['file' => 'Struktur XML DOCX tidak valid.']);
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return $document;
    }
}
