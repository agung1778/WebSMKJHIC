<?php

namespace App\Http\Requests;

use App\Models\SchoolDownload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DownloadRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->routeIs('admin.logo-downloads.*') && ! $this->has('category')) {
            $this->merge(['category' => 'logo']);
        }
        if ($this->input('category') === 'pkl') {
            $this->merge(['dark_preview' => false]);
        } elseif ($this->input('category') === 'logo') {
            $this->merge(['major_id' => null]);
        }
    }

    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'superadmin'], true);
    }

    public function rules(): array
    {
        $fileTypes = $this->input('category') === 'pkl'
            ? ['mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/zip,application/x-zip-compressed', 'extensions:pdf,docx']
            : ['mimes:png,jpg,jpeg,webp,pdf', 'extensions:png,jpg,jpeg,webp,pdf'];

        return [
            'category' => ['required', 'string', Rule::in(array_keys(SchoolDownload::categories()))],
            'major_id' => ['nullable', 'integer', Rule::exists('majors', 'id')],
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file', ...$fileTypes, 'max:10240',
            ],
            'is_active' => ['required', 'boolean'],
            'dark_preview' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $download = $this->route('download');
            if (! $download instanceof SchoolDownload || $this->hasFile('file')) {
                return;
            }
            $category = $this->input('category');
            $allowed = is_string($category) ? SchoolDownload::formatsFor($category) : [];
            if (! in_array($download->extension, $allowed, true)) {
                $validator->errors()->add('file', 'Unggah file pengganti yang sesuai dengan kategori baru.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama berkas', 'description' => 'deskripsi', 'file' => 'file unduhan', 'category' => 'kategori', 'major_id' => 'jurusan',
            'is_active' => 'status publikasi', 'dark_preview' => 'latar pratinjau', 'sort_order' => 'urutan',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Pilih file yang akan diunggah.',
            'file.mimes' => 'Logo harus berupa PNG, JPG, WebP, atau PDF. SVG dan file aplikasi tidak diizinkan.',
            'file.mimetypes' => 'Contoh laporan PKL harus berupa PDF atau DOCX yang valid.',
            'file.extensions' => 'Ekstensi file tidak sesuai kategori yang dipilih.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'name.required' => 'Nama berkas wajib diisi.',
            'major_id.exists' => 'Jurusan tidak ditemukan. Pilih jurusan yang tersedia atau Umum / Semua Jurusan.',
            'major_id.integer' => 'Pilihan jurusan tidak valid.',
        ];
    }
}
