@extends('layouts.admin-app')
@section('content')

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <title>Dashboard</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f2f5;
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .text-green-custom {
                color: #6CF600;
            }
        </style>
    </head>

    <body class="bg-[#f0f2f5] text-[#292929] font-sans p-6 md:p-10">
        <div class="max-w-7xl mx-auto">
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-xl font-bold">
                        Welcome,
                        <span class="text-green-custom">
                            {{ Auth::user()->name }}
                        </span>
                    </h1>
                    <p class="text-sm font-normal text-gray-600">
                        Lets Customize Something <span class="text-green-custom">Here</span>
                    </p>
                </div>
                <div class="flex items-center space-x-2 text-gray-600">
                    <span id="current-date"></span>
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </header>

            <div class="mb-6">
                <div class="w-full rounded-lg overflow-hidden border border-[#D9D9D9]">
                    <img alt="Panoramic aerial view of modern building complex with glass and greenery"
                        class="w-full h-40 object-cover" src="{{ asset('assets/image/DroneView.jpg') }}" />
                </div>
            </div>

            <main>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-home fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Main Page</h2>
                        <p class="text-sm text-gray-600 leading-tight">Kelola tampilan dan konten utama yang dilihat
                            pengunjung pertama kali.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-globe-asia fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Discover Amaliah</h2>
                        <p class="text-sm text-gray-600 leading-tight">Atur dan perbarui informasi profil sekolah serta
                            visi-misi.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-quote-right fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Testimonials</h2>
                        <p class="text-sm text-gray-600 leading-tight">Kelola ulasan dan testimoni inspiratif dari siswa,
                            orang tua, dan alumni.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-building fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Facilities</h2>
                        <p class="text-sm text-gray-600 leading-tight">Perbarui informasi dan galeri foto fasilitas sekolah
                            yang tersedia.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Education Preview</h2>
                        <p class="text-sm text-gray-600 leading-tight">Tinjau dan sesuaikan pratinjau kurikulum dan program
                            pendidikan.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-chart-bar fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Statistics</h2>
                        <p class="text-sm text-gray-600 leading-tight">Lihat data dan statistik penting untuk memantau
                            performa website.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Major Competency</h2>
                        <p class="text-sm text-gray-600 leading-tight">Kelola informasi detail tentang jurusan dan
                            kompetensi unggulan sekolah.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-newspaper fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Discover News</h2>
                        <p class="text-sm text-gray-600 leading-tight">Buat, sunting, dan publikasikan berita atau artikel
                            terbaru sekolah.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>

                    <a href="#"
                        class="bg-white rounded-lg p-4 flex flex-col space-y-3 shadow-md border border-[#D9D9D9] transition-transform duration-300 hover:scale-[1.02]">
                        <div class="text-green-custom">
                            <i class="fas fa-handshake fa-lg"></i>
                        </div>
                        <h2 class="font-semibold text-base">Industry Partners</h2>
                        <p class="text-sm text-gray-600 leading-tight">Kelola dan tampilkan daftar mitra industri dan
                            kolaborasi.</p>
                        <span class="text-xs text-green-custom font-semibold flex items-center space-x-1">
                            <span>Kustomisasi</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </a>
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dateSpan = document.getElementById('current-date');

                function updateDate() {
                    const now = new Date();
                    const options = {
                        timeZone: 'Asia/Jakarta',
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    const formattedDate = new Intl.DateTimeFormat('id-ID', options).format(now);
                    dateSpan.textContent = formattedDate;
                }

                updateDate();
                // Optional: update date every minute to keep it current
                setInterval(updateDate, 60000);
            });
        </script>
    </body>

    </html>

@endsection