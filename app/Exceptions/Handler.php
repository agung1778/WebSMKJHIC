<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     * Mencegah 'Page Expired' (419) agar tidak merusak profesionalitas web.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException || ($e instanceof HttpException && $e->getStatusCode() === 419)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi telah kedaluwarsa. Token keamanan telah diperbarui otomatis.',
                    'csrf_token' => csrf_token(),
                ], 419);
            }

            if ($request->is('login') || $request->is('admin/login')) {
                return redirect()->route('login')
                    ->with('warning', 'Sesi login telah disegarkan. Silakan masukkan kembali akun Anda.');
            }

            // Jika ada halaman sebelumnya, kembalikan dengan data formulir agar tidak hilang
            if (url()->previous() && url()->previous() !== url()->current()) {
                return redirect()->back()
                    ->withInput($request->except($this->dontFlash))
                    ->with('warning', 'Sesi keamanan formulir telah diperbarui secara otomatis. Data yang Anda ketik telah dipulihkan, silakan klik tombol Simpan kembali.');
            }

            // Jika tidak ada halaman sebelumnya, tampilkan halaman 419 kustom elegan
            return response()->view('errors.419', [], 419);
        }

        return parent::render($request, $e);
    }
}
