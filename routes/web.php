<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PartnerController; // Mengimpor PartnerController
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\FacilityController; // Mengimpor FacilityController
use App\Http\Controllers\SchoolProgramController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AchievementController; // Mengimpor AchievementController
use App\Http\Controllers\ExtracurricularController; // Mengimpor ExtracurricularController
use App\Http\Controllers\ImageController; // Mengimpor ImageController
use App\Http\Controllers\WritingController; // Mengimpor WritingController

use App\Models\SchoolProgram;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\PublicPage\HomeController;
use App\Http\Controllers\PublicPage\PublicMajorsController;
use App\Http\Controllers\PublicPage\PublicNewsController;
use App\Http\Controllers\PublicPage\PublicPartnersController;
use App\Http\Controllers\PublicPage\PublicFacilityController;
use App\Http\Controllers\PublicPage\PublicTestimonialController;
use App\Http\Controllers\PublicPage\PublicTeacherController;
use App\Http\Controllers\PublicPage\PublicAboutController;
use App\Http\Controllers\PublicPage\PublicFoundationController;
use App\Http\Controllers\PublicPage\PublicAchievementController;
use App\Http\Controllers\PublicPage\PublicProgramController;
use App\Http\Controllers\PublicPage\PublicExtracurricularController;
use App\Http\Controllers\PublicPage\PublicHelpcenterController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index']);


// Rute Halaman Publik Jurusan
Route::get('/majors', [PublicMajorsController::class, 'index'])->name('public.majors.index');
Route::get('/majors/{major}', [PublicMajorsController::class, 'show'])->name('public.majors.show');


Route::get('/news', [PublicNewsController::class, 'index'])->name('public.news.index');
Route::get('/news/{news}', [PublicNewsController::class, 'show'])->name('public.news.show');

Route::get('/partners', [PublicPartnersController::class, 'index'])->name('public.partners.index');
Route::get('/partners/{partner}', [PublicPartnersController::class, 'show'])->name('public.partners.show');

Route::get('/facilities', [PublicFacilityController::class, 'index'])->name('public.facilities.index');
Route::get('/facilities/{facility}', [PublicFacilityController::class, 'show'])->name('public.facilities.show');


// Route untuk halaman testimoni publik
Route::get('/testimonials', [PublicTestimonialController::class, 'index'])->name('public.testimonials.index');
Route::get('/testimonials/{testimonial}', [PublicTestimonialController::class, 'show'])->name('public.testimonials.show');

Route::get('/teachers', [PublicTeacherController::class, 'index'])->name('public.teachers.index');
Route::get('/teachers/{teacher}', [PublicTeacherController::class, 'show'])->name('public.teachers.show');

Route::get('/about', [PublicAboutController::class, 'index'])->name('public.about.index');
Route::get('/about/vision', [PublicAboutController::class, 'vision'])->name('public.about.vision');
Route::get('/about/history', [PublicAboutController::class, 'history'])->name('public.about.history');
Route::get('/about/foundation', [PublicAboutController::class, 'foundation'])->name('public.about.foundation');

Route::get('/achievements', [PublicAchievementController::class, 'index'])->name('public.achievement.index');
Route::get('/achievements/{achievement}', [PublicAchievementController::class, 'show'])->name('public.achievement.show');

Route::get('/programs', [PublicProgramController::class, 'index'])->name('public.program.index');
Route::get('/programs/{program}', [PublicProgramController::class, 'show'])->name('public.program.show');

Route::get('/extracurriculars', [PublicExtracurricularController::class, 'index'])->name('public.extracurricular.index');
Route::get('/extracurriculars/{extracurricular}', [PublicExtracurricularController::class, 'show'])->name('public.extracurricular.show');

Route::get('/help/faq', [PublicHelpcenterController::class, 'faq'])->name('public.help.faq');
Route::get('/help/feedback', [PublicHelpcenterController::class, 'feedback'])->name('public.help.feedback');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/', [HomeController::class, 'index']);

// Authentication Routes
// Tambahkan rute untuk menampilkan form login dan beri nama 'login'
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 1. Menampilkan form permintaan link reset
Route::get('lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// 2. Mengirim link reset ke email
Route::post('lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// 3. Menampilkan form untuk memasukkan password baru (diakses dari link email)
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
// 4. Memproses dan menyimpan password baru
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Rute yang dilindungi oleh middleware 'auth'
// Semua rute di dalam grup ini hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    // Jika pengguna mengakses '/admin', arahkan ke '/admin/dashboard'
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });

    // Rute untuk dashboard admin yang hanya bisa diakses setelah login
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/curator', [AdminController::class, 'curator'])->name('admin.curator');

    // routes/web.php
    Route::get('/admin/users', [AdminController::class, 'user'])->name('admin.users');

    // Grup rute untuk manajemen konten di dashboard admin
    Route::prefix('admin')->name('admin.')->group(function () {
        // Rute untuk Berita
        Route::resource('news', NewsController::class);

        // Rute untuk Jurusan
        Route::resource('majors', MajorController::class);

        // Rute untuk Mitra Industri
        Route::resource('partners', PartnerController::class);

        // Rute untuk Testimoni
        Route::resource('testimonials', TestimonialController::class);

        // Rute untuk Fasilitas
        Route::resource('facilities', FacilityController::class);

        // Rute untuk Fasilitas 
        Route::resource('programs', SchoolProgramController::class);

        // Rute untuk Guru
        Route::resource('teachers', TeacherController::class);

        Route::post('/teachers/{teacher}/upload-photo', [App\Http\Controllers\TeacherController::class, 'uploadPhoto'])->name('teachers.uploadPhoto');

        // Rute untuk Prestasi
        Route::resource('achievements', AchievementController::class);

        // Rute untuk Ekstrakurikuler
        Route::resource('extracurriculars', ExtracurricularController::class);

        // Rute untuk Galeri Media (menggunakan nama singular 'image')
        Route::resource('image', ImageController::class)->except(['create']);

        Route::resource('writings', WritingController::class);
    });
});
