<?php

namespace App\Http\Controllers;

use App\Models\SpmbSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpmbSettingController extends Controller
{
    /**
     * Kolom upload: nama field => [disk folder, ekstensi, ukuran maks (KB)]
     */
    private const FILE_FIELDS = [
        'brochure_image_1'    => ['image', 'jpeg,png,jpg,webp', 2048],
        'brochure_image_2'    => ['image', 'jpeg,png,jpg,webp', 2048],
        'brochure_full_image' => ['image', 'jpeg,png,jpg,webp', 5120],
        'brochure_file'       => ['pdf', 'pdf', 10240],
        'popup_logo'          => ['image', 'jpeg,png,jpg,webp,svg', 1024],
        'popup_image'         => ['image', 'jpeg,png,jpg,webp', 3072],
    ];

    private function rules(): array
    {
        return [
            'status'            => 'required|in:Buka,Tutup',
            'is_active'         => 'nullable|boolean',
            'popup_enabled'     => 'nullable|boolean',
            'popup_frequency'   => 'nullable|in:' . implode(',', array_keys(SpmbSetting::popupFrequencies())),
            'popup_theme'       => 'nullable|in:' . implode(',', array_keys(SpmbSetting::popupThemes())),
            'popup_delay'       => 'nullable|integer|min:0|max:10000',
            'popup_size'        => 'nullable|in:' . implode(',', array_keys(SpmbSetting::popupSizes())),
            'popup_position'    => 'nullable|in:' . implode(',', array_keys(SpmbSetting::popupPositions())),
            'popup_show_image'  => 'nullable|boolean',
            'popup_show_detail_button' => 'nullable|boolean',
            'popup_subtitle'    => 'nullable|string|max:120',
            'popup_title'       => 'nullable|string|max:160',
            'popup_badge'       => 'nullable|string|max:60',
            'popup_button_text' => 'nullable|string|max:60',
            'popup_logo'        => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
            'popup_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'wave_name'         => 'nullable|string|max:255',
            'wave_category'     => 'nullable|string|max:255',
            'wave_description'  => 'nullable|string|max:1000',
            'period_date'       => 'nullable|string|max:255',
            'quota_note'        => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:255',
            'brochure_image_1'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brochure_image_2'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brochure_full_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'brochure_file'       => 'nullable|mimes:pdf|max:10240',
        ];
    }

    public function index()
    {
        $settings = SpmbSetting::orderByDesc('is_active')->orderByDesc('id')->get();

        return view('admin.spmb_settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.spmb_settings.create', [
            'spmbSetting' => new SpmbSetting(['status' => 'Buka', 'is_active' => ! $this->hasActive()]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->normalize($request);

        // array_merge (bukan +) supaya path file hasil upload menimpa nilai mentah dari validasi.
        $setting = SpmbSetting::create(array_merge($validated, $this->storeFiles($request)));
        $this->syncActive($setting);

        return redirect()->route('admin.spmb_settings.index')
            ->with('success', 'Data SPMB berhasil ditambahkan!');
    }

    public function edit(SpmbSetting $spmb_setting)
    {
        return view('admin.spmb_settings.edit', ['spmbSetting' => $spmb_setting]);
    }

    public function update(Request $request, SpmbSetting $spmb_setting)
    {
        $validated = $this->normalize($request);

        $validated = array_merge($validated, $this->storeFiles($request, $spmb_setting));
        $spmb_setting->update($validated);
        $this->syncActive($spmb_setting);

        return redirect()->route('admin.spmb_settings.index')
            ->with('success', 'Data SPMB berhasil diperbarui!');
    }

    public function destroy(SpmbSetting $spmb_setting)
    {
        $wasActive = $spmb_setting->is_active;

        foreach (array_keys(self::FILE_FIELDS) as $field) {
            if ($spmb_setting->$field) {
                Storage::disk('public')->delete($spmb_setting->$field);
            }
        }

        $spmb_setting->delete();

        if ($wasActive) {
            $next = SpmbSetting::query()->orderByDesc('id')->first();
            $next?->update(['is_active' => true]);
        }

        return redirect()->route('admin.spmb_settings.index')
            ->with('success', 'Data SPMB berhasil dihapus!');
    }

    /**
     * Validasi + rapikan nilai checkbox & opsi popup.
     */
    private function normalize(Request $request): array
    {
        $validated = $request->validate($this->rules());

        $validated['is_active'] = $request->boolean('is_active');
        $validated['popup_enabled'] = $request->boolean('popup_enabled');
        $validated['popup_show_image'] = $request->boolean('popup_show_image');
        $validated['popup_show_detail_button'] = $request->boolean('popup_show_detail_button');

        if ($validated['is_active'] || ! $this->hasActive()) {
            $validated['is_active'] = true;
        }

        $validated['popup_frequency'] = $validated['popup_frequency'] ?? 'always';
        $validated['popup_theme'] = $validated['popup_theme'] ?? 'hijau';
        $validated['popup_delay'] = $validated['popup_delay'] ?? 900;
        $validated['popup_size'] = $validated['popup_size'] ?? 'sedang';
        $validated['popup_position'] = $validated['popup_position'] ?? 'tengah';

        return $validated;
    }

    private function hasActive(): bool
    {
        return SpmbSetting::query()->active()->exists();
    }

    /**
     * Simpan file baru dan hapus file lama yang diganti.
     */
    private function storeFiles(Request $request, ?SpmbSetting $setting = null): array
    {
        $stored = [];

        foreach (self::FILE_FIELDS as $field => $config) {
            if (! $request->hasFile($field)) {
                continue;
            }

            if ($setting && $setting->$field) {
                Storage::disk('public')->delete($setting->$field);
            }

            $stored[$field] = $request->file($field)->store('spmb', 'public');
        }

        return $stored;
    }

    /**
     * Jaga agar hanya ada satu data aktif (dipakai halaman publik).
     */
    private function syncActive(?SpmbSetting $setting): void
    {
        if (! $setting) {
            return;
        }

        if ($setting->is_active) {
            SpmbSetting::where('id', '!=', $setting->id)->update(['is_active' => false]);
        }
    }
}