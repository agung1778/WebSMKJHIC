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

    @php
        $amaliahGreen = '#63cd00';
        $amaliahDark = '#282829';
        $amaliahBlue = '#E0E7FF';

        // Cek Variabel 
        $hasImages = isset($mainImages) && $mainImages->isNotEmpty();
    @endphp

    <body class="bg-[#f0f2f5] text-[#292929] font-sans p-6 md:p-10">
        <section class="bg-white py-16 sm:py-24">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Header Section --}}
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold" style="color: {{ $amaliahDark }};">
                        Our Latest Instagram Post
                    </h2>
                    <div class="flex items-center justify-center gap-x-2 mx-auto mt-4">
                        <div class="w-20 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                        <div class="w-8 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                        <div class="w-4 h-1 rounded-full" style="background-color: {{ $amaliahDark }};"></div>
                    </div>
                </div>

                {{-- Konten Utama (Layout Dua Kolom) --}}
                <div class="mt-12 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">

                    {{-- Kolom Kiri: Statis --}}
                    <div class="lg:col-span-1">
                        {{-- Placeholder untuk Post Utama --}}
                        <div class="bg-gray-200 aspect-square w-full rounded-2xl flex items-center justify-center">
                            <i class="fas fa-image text-5xl text-gray-400"></i>
                        </div>
                        <div class="mt-6 flex items-start gap-4">
                            <i class="fab fa-instagram text-4xl" style="color: {{ $amaliahDark }};"></i>
                            <div>
                                <p class="text-gray-600 leading-relaxed">
                                    Read our latest news, and know about smk amaliah. Read our latest news, and know
                                    about smk amaliah.
                                </p>
                                <a href="#"
                                    class="inline-flex items-center mt-4 text-blue-600 font-semibold hover:underline">
                                    <span>Buka Instagram</span>
                                    <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Untuk Widget Curator.io --}}
                    <div class="lg:col-span-2">
                        <!-- Place <div> tag where you want the feed to appear -->
                        <div id="curator-feed-default-feed-layout"><a href="https://curator.io" target="_blank"
                                class="crt-logo crt-tag">Powered by Curator.io</a></div>

                        <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
                        <script type="text/javascript">
                            /* curator-feed-default-feed-layout */
                            (function () {
                                var i, e, d = document, s = "script"; i = d.createElement("script"); i.async = 1; i.charset = "UTF-8";
                                i.src = "https://cdn.curator.io/published/5a9914b1-ce8f-4211-8162-46ff6f9b2eb1.js";
                                e = d.getElementsByTagName(s)[0]; e.parentNode.insertBefore(i, e);
                            })();
                        </script>
                        <div id="curator-feed-default-layout"
                            class="bg-black w-full min-h-[600px] rounded-2xl flex items-center justify-center">
                            <p class="text-gray-500 text-center">Menunggu koneksi dari Curator.io...</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </body>

    </html>

@endsection