    {{-- BAGIAN 2: CARD MOTTO, VISI, MISI --}}
                <div class="relative z-20 -mt-16 max-w-5xl mx-auto">
                    <div class="bg-[#282829] rounded-2xl shadow-xl p-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-white">
                            @foreach ($vision as $item)
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas {{ $item['icon'] }} text-xl" style="color: {{ $amaliahGreen }};"></i>
                                        <h3 class="text-lg font-semibold">{{ $item['title'] }}</h3>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-300">{{ $item['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>