@extends('layouts.admin-app')
@section('content')

    
        <style>
            

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

        <section class="bg-white py-16 sm:py-24 space-y-20">

            {{-- BAGIAN 1: SLIDER (SWIPE) --}}
            <div>
                {{-- Header untuk slider diletakkan di dalam container agar rapi --}}
                {{-- Header Section (Tidak ada perubahan) --}}
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


            </div>

            {{-- BAGIAN 2: GRID --}}
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8  ">


                {{-- Wadah untuk grid Curator.io --}}
                <div id="curator-feed-grid-layout">

                    <!-- Place <div> tag where you want the feed to appear -->
                    <div id="curator-feed-default-feed-layout"><a href="https://curator.io" target="_blank"
                            class="crt-logo crt-tag">Powered by Curator.io</a></div>

                    <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
                    <script type="text/javascript">
                        /* curator-feed-default-feed-layout */
                        (function () {
                            var i, e, d = document, s = "script"; i = d.createElement("script"); i.async = 1; i.charset = "UTF-8";
                            i.src = "https://cdn.curator.io/published/9b122a7e-d39e-40c4-abc3-8ab6bc446899.js";
                            e = d.getElementsByTagName(s)[0]; e.parentNode.insertBefore(i, e);
                        })();
                    </script>
                </div>
            </div>
        </section>




    </body>

    </html>

@endsection