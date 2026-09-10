<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Major;
use App\Models\SchoolProgram;
use App\Models\Facility;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Teacher;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Image;
use App\Models\Writing;
use App\Models\PkkProject;

class ExportController extends Controller
{
    /**
     * Export data tabel ke CSV secara generik.
     *
     * @param  string  $resource
     */
    public function export($resource)
    {
        $map = [
            'news'           => News::class,
            'majors'         => Major::class,
            'programs'       => SchoolProgram::class,
            'facilities'     => Facility::class,
            'partners'       => Partner::class,
            'testimonials'   => Testimonial::class,
            'teachers'       => Teacher::class,
            'achievements'   => Achievement::class,
            'extracurriculars' => Extracurricular::class,
            'image'          => Image::class,
            'writings'       => Writing::class,
            'pkk'            => PkkProject::class,
        ];

        $model = $map[$resource] ?? abort(404, 'Sumber data tidak dikenal.');
        $rows = $model::all()->toArray();

        if (empty($rows)) {
            $columns = array_keys((new $model)->getFillable());
            $rows[] = array_fill_keys(array_merge($columns, ['id']), '');
        }

        $handle = fopen('php://temp', 'w');
        fputs($handle, "\xEF\xBB\xBF"); // BOM UTF-8 agar Excel membaca huruf dengan benar

        fputcsv($handle, array_keys($rows[0] ?? []));
        foreach ($rows as $row) {
            $sanitized = array_map(fn ($v) => is_string($v) ? strip_tags(html_entity_decode($v)) : $v, $row);
            fputcsv($handle, $sanitized);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = $resource . '-' . now()->format('Ymd-His') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}