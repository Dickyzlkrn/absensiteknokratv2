<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ABSENSI PKL/MAGANG - Universitas Teknokrat Indonesia</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #e30613;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c50511;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Warna gradasi merah khas Teknokrat */
        .from-teknokrat-red {
            --tw-gradient-from: #e30613 var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(227 6 19 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }

        .to-teknokrat-dark {
            --tw-gradient-to: #b0040f var(--tw-gradient-to-position);
        }
    </style>
</head>

<body class="bg-gradient-to-b from-slate-50 to-white text-slate-900">

    <!-- HEADER -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 flex items-center justify-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Teknokrat" class="w-full h-full object-contain">
                </div>

                <div>
                    <h1 class="text-xl font-bold">ABSENSI PKL/MAGANG</h1>
                    <p class="text-xs text-slate-600">Universitas Teknokrat Indonesia</p>
                </div>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="#features" class="text-slate-700 hover:text-[#e30613] font-medium">Fitur</a>
                <a href="#stats" class="text-slate-700 hover:text-[#e30613] font-medium">Statistik</a>
                <a href="#footer" class="text-slate-700 hover:text-[#e30613] font-medium">Kontak</a>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="pt-32 pb-20 px-6 text-center">
        <div class="container mx-auto max-w-6xl">
            <span class="px-4 py-2 bg-red-100 text-[#e30613] rounded-full text-sm font-semibold">
                Sistem Presensi UTI untuk PKL & Magang
            </span>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mt-8 leading-tight">
                Kelola Presensi PKL/Magang <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#e30613] to-[#b0040f]">
                    Lebih Mudah & Efisien
                </span>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto mt-6 leading-relaxed">
                Sistem presensi mahasiswa PKL dan magang Universitas Teknokrat Indonesia.
                Catat kehadiran dengan mudah, pantau secara real-time, dan kelola data dengan efisien.
            </p>

            <!-- CTA BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-10">
                <button onclick="studentLogin()" class="bg-[#e30613] hover:bg-[#b0040f] text-white px-8 py-4 text-lg font-semibold rounded-xl shadow-lg hover:shadow-xl transition-transform hover:scale-105 flex items-center">
                    <i data-lucide="user-check" class="w-5 h-5 mr-2"></i> Login Mahasiswa
                </button>
                <button onclick="adminLogin()" class="border-2 border-slate-300 hover:border-[#e30613] text-slate-700 hover:text-[#e30613] px-8 py-4 text-lg font-semibold rounded-xl transition-transform hover:scale-105 flex items-center">
                    <i data-lucide="shield" class="w-5 h-5 mr-2"></i> Login Admin
                </button>
            </div>

            <!-- STATS -->
            <div id="stats" class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-16 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-slate-600">Mahasiswa Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-slate-600">Tingkat Akurasi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <div class="text-slate-600">Sistem Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-slate-600">Lokasi PKL</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-20 px-6 bg-white">
        <div class="container mx-auto max-w-6xl text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Fitur Unggulan Sistem</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-16">
                Dilengkapi dengan fitur-fitur modern untuk memudahkan proses presensi dan monitoring mahasiswa PKL
            </p>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-red-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="clock" class="w-8 h-8 text-[#e30613]"></i></div>
                    <h3 class="text-xl font-bold mb-3">Presensi Real-time</h3>
                    <p class="text-slate-600">Catat kehadiran mahasiswa PKL secara real-time dengan sistem yang akurat dan mudah digunakan.</p>
                </div>
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="map-pin" class="w-8 h-8 text-blue-500"></i></div>
                    <h3 class="text-xl font-bold mb-3">Lokasi GPS</h3>
                    <p class="text-slate-600">Verifikasi lokasi kehadiran mahasiswa dengan teknologi GPS untuk memastikan kehadiran di tempat PKL.</p>
                </div>
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="bar-chart-3" class="w-8 h-8 text-green-600"></i></div>
                    <h3 class="text-xl font-bold mb-3">Laporan Lengkap</h3>
                    <p class="text-slate-600">Dashboard lengkap dengan statistik dan laporan kehadiran untuk monitoring yang lebih baik.</p>
                </div>
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="calendar" class="w-8 h-8 text-purple-500"></i></div>
                    <h3 class="text-xl font-bold mb-3">Jadwal Fleksibel</h3>
                    <p class="text-slate-600">Kelola jadwal PKL dan magang dengan mudah, atur shift dan periode kehadiran.</p>
                </div>
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-teal-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="user-check" class="w-8 h-8 text-teal-500"></i></div>
                    <h3 class="text-xl font-bold mb-3">Verifikasi Mudah</h3>
                    <p class="text-slate-600">Proses verifikasi kehadiran yang cepat dan mudah untuk mahasiswa dan admin.</p>
                </div>
                <div class="p-8 border-2 border-slate-100 hover:border-red-200 rounded-2xl hover:shadow-xl transition-all cursor-pointer">
                    <div class="w-16 h-16 bg-red-50 rounded-xl flex items-center justify-center mb-6"><i data-lucide="shield" class="w-8 h-8 text-[#e30613]"></i></div>
                    <h3 class="text-xl font-bold mb-3">Keamanan Data</h3>
                    <p class="text-slate-600">Data kehadiran tersimpan dengan aman dan terenkripsi, terlindungi dengan sistem keamanan tinggi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-20 px-6 bg-gradient-to-br from-red-50 to-red-100 text-center">
        <div class="container mx-auto max-w-4xl">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Siap Mulai Menggunakan Sistem?</h2>
            <p class="text-xl text-slate-700 mb-10 max-w-2xl mx-auto">
                Bergabunglah dengan mahasiswa dan admin Universitas Teknokrat Indonesia dalam sistem presensi yang modern dan efisien.
            </p>
            <button onclick="studentLogin()" class="bg-[#e30613] hover:bg-[#b0040f] text-white px-8 py-4 text-lg font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                Mulai Sekarang
            </button>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="footer" class="bg-slate-900 text-white py-12 px-6">
        <div class="container mx-auto max-w-6xl">
            <div class="grid md:grid-cols-3 gap-12 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-[#e30613] rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">PresensiTek</h3>
                            <p class="text-sm text-slate-400">UTI</p>
                        </div>
                    </div>
                    <p class="text-slate-400 leading-relaxed">
                        Sistem presensi modern untuk mahasiswa PKL dan magang Universitas Teknokrat Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Kontak</h4>
                    <ul class="space-y-3 text-slate-400">
                        <li>Jl. ZA. Pagar Alam No.9-11</li>
                        <li>Labuhan Ratu, Bandar Lampung</li>
                        <li>Lampung 35132</li>
                        <li>Email: info@teknokrat.ac.id</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="#features" class="hover:text-[#e30613]">Fitur</a></li>
                        <li><a href="#stats" class="hover:text-[#e30613]">Statistik</a></li>
                        <li><a href="https://teknokrat.ac.id" target="_blank" class="hover:text-[#e30613]">Website UTI</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-slate-400">
                <p>&copy; 2024 Universitas Teknokrat Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function studentLogin() {
            window.location.href = "{{ url('auth/loginmahasiswa') }}";
        }

        function adminLogin() {
            window.location.href = "{{ url('auth/login') }}";
        }
    </script>
</body>

</html>