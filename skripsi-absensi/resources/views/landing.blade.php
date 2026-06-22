<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- LOGIKA PENGAMBILAN SETTINGS --}}
    @php
        use Illuminate\Support\Facades\Storage;
        
        $settings = $settings ?? \App\Models\Setting::pluck('value', 'key')->toArray(); 
        $schoolName = $settings['school_name'] ?? 'E-Absensi Siswa';
        $schoolLogoPath = $settings['school_logo'] ?? null;
        
        $defaultLogo = asset('images/default_logo.png'); 
        $finalLogo = ($schoolLogoPath && Storage::disk('public')->exists($schoolLogoPath)) ? asset('storage/' . $schoolLogoPath) : $defaultLogo;
    @endphp
    
    <title>{{ $schoolName }}</title>
    <link rel="icon" type="image/png" href="{{ $finalLogo }}">

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Styles & Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
        <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Background Gradasi Navy Mewah */
        .bg-dark-gradient {
            background: linear-gradient(135deg, #060b19 0%, #0f172a 50%, #020617 100%);
            background-attachment: fixed;
        }

        /* Efek Cahaya di Belakang */
        .glow-orb {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
        }

        /* Kotak Kaca (Glassmorphism) */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .scrolled-nav {
            background-color: rgba(6, 11, 25, 0.8) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
    </style>
</head>

    <body class="antialiased text-slate-300 bg-dark-gradient overflow-x-hidden"
     x-data="{ scrolled: false, activeSection: 'home' }" @scroll.window="scrolled = (window.pageYOffset > 20)">

{{-- LOADER --}}
@include('layouts.partials.loader')

        {{-- NAVBAR DENGAN DETEKSI HALAMAN AKTIF & MOBILE MENU --}}
<nav x-data="{ mobileMenuOpen: false }"
     :class="{ 'scrolled-nav py-3': scrolled, 'py-6': !scrolled, 'bg-white/95 backdrop-blur-md shadow-sm': mobileMenuOpen }" 
     class="fixed top-0 w-full z-50 transition-all duration-300"
     x-init="
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) activeSection = entry.target.id;
            });
        }, { threshold: 0.5 });
        document.querySelectorAll('section[id]').forEach(section => observer.observe(section));
     ">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="#" @click="activeSection = 'home'" class="flex items-center gap-3 group z-50">
            <div class="bg-white p-2 rounded-xl group-hover:rotate-12 transition-transform shadow-sm">
                <img src="{{ $finalLogo }}" class="w-8 h-8 object-contain rounded-lg">
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-white tracking-tight leading-none">{{ $schoolName }}</span>
                    <span class="text-[9px] font-bold text-indigo-500 tracking-[0.2em] uppercase">Sistem Absensi Digital</span>
                </div>
            </a>

            {{-- Tombol Hamburger untuk HP --}}
            <div class="md:hidden flex items-center z-50">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-indigo-600 focus:outline-none p-2">
                    <i class="fas text-2xl transition-all duration-300" :class="mobileMenuOpen ? 'fa-times rotate-90' : 'fa-bars'"></i>
                </button>
            </div>

            {{-- Desktop Menu (Hanya tampil di Laptop) --}}
             
            <div class="ml-auto hidden md:flex items-center space-x-2">
            <a href="#" @click="activeSection = 'home'"
            :class="activeSection === 'home' ? 'text-white bg-white/10 border border-white/10 shadow-[0_0_15px_rgba(255,255,255,0.05)]' : 'text-slate-400 hover:text-white hover:bg-white/5'"
            class="group flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300">
                <i class="fas fa-home-alt text-base transition-transform group-hover:scale-110" :class="activeSection === 'home' ? 'text-indigo-400' : 'opacity-40'"></i>
                <span>Beranda</span>
            </a>

            <a href="#tentang" @click="activeSection = 'tentang'"
            :class="activeSection === 'tentang' ? 'text-white bg-white/10 border border-white/10 shadow-[0_0_15px_rgba(255,255,255,0.05)]' : 'text-slate-400 hover:text-white hover:bg-white/5'"
            class="group flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300">
                <i class="fas fa-layer-group text-base transition-transform group-hover:scale-110" :class="activeSection === 'tentang' ? 'text-indigo-400' : 'opacity-40'"></i>
                <span>Tentang Kami</span>
            </a>

            <a href="#fitur" @click="activeSection = 'fitur'"
            :class="activeSection === 'fitur' ? 'text-white bg-white/10 border border-white/10 shadow-[0_0_15px_rgba(255,255,255,0.05)]' : 'text-slate-400 hover:text-white hover:bg-white/5'"
            class="group flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300">
                <i class="fas fa-rocket text-base transition-transform group-hover:scale-110" :class="activeSection === 'fitur' ? 'text-indigo-400' : 'opacity-40'"></i>
                <span>Fitur</span>
            </a>
            <div class="h-8 w-[1px] bg-slate-800 mx-2"></div>

            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-grid-2 mr-2"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </a>
            @endauth
        </div>

        {{-- Mobile Menu (Muncul saat tombol hamburger diklik di HP) --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden absolute top-full left-0 w-full bg-white shadow-2xl border-t border-slate-100 flex flex-col py-4 px-6 space-y-2 mt-2 pb-6 rounded-b-3xl"
             style="display: none;">
            
            <a href="#" @click="activeSection = 'home'; mobileMenuOpen = false"
               :class="activeSection === 'home' ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600'"
               class="px-5 py-4 rounded-xl font-bold transition-all flex items-center hover:bg-slate-50">
               <i class="fas fa-home-alt w-8 text-center"></i> Beranda
            </a>

            <a href="#tentang" @click="activeSection = 'tentang'; mobileMenuOpen = false"
               :class="activeSection === 'tentang' ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600'"
               class="px-5 py-4 rounded-xl font-bold transition-all flex items-center hover:bg-slate-50">
               <i class="fas fa-layer-group w-8 text-center"></i> Tentang Kami
            </a>

            <a href="#fitur" @click="activeSection = 'fitur'; mobileMenuOpen = false"
               :class="activeSection === 'fitur' ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600'"
               class="px-5 py-4 rounded-xl font-bold transition-all flex items-center hover:bg-slate-50">
               <i class="fas fa-rocket w-8 text-center"></i> Fitur
            </a>

            <div class="h-[1px] bg-slate-100 my-4"></div>

            @auth
                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center py-4 text-base font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                    <i class="fas fa-grid-2 mr-2"></i> Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center py-4 text-base font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                </a>
            @endauth
        </div>
    </div>
</nav>

        {{-- HERO SECTION (DARK GRADIENT & GLOWING MOCKUP) --}}
<section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    {{-- Efek Glow Gradasi di Belakang --}}
    <div class="glow-orb top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

    <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="flex flex-col items-center justify-center text-center gap-12">
            
            {{-- KONTEN TEKS (SEKARANG FULL RATA TENGAH) --}}
            <div class="w-full max-w-3xl mx-auto flex flex-col items-center">
                <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 px-4 py-2 rounded-full text-xs font-bold mb-6 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    E-ABSENSI SISWA
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-extrabold text-white mb-6 leading-[1.15] tracking-tight text-center">
                    ABSENSI QR-CODE SISWA <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-blue-400 drop-shadow-md">
                        TERINTEGRASI
                    </span>
                </h1>
                
                <p class="text-base lg:text-lg text-slate-400/90 mb-10 max-w-2xl mx-auto leading-relaxed font-normal text-center">
                    Mewujudkan transparansi kehadiran siswa melalui integrasi sistem QR Code dan layanan pesan otomatis demi efisiensi komunikasi antara sekolah dan orang tua.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full sm:w-auto">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-bold hover:from-indigo-500 hover:to-blue-500 transition-all duration-300 shadow-[0_0_20px_rgba(79,70,229,0.4)] active:scale-95 group">
                        <i class="fas fa-qrcode mr-3"></i> Mulai Presensi
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 glass-card text-white rounded-xl font-bold hover:bg-white/10 transition-all duration-300 active:scale-95 group">
                        <i class="fas fa-rocket mr-3 text-indigo-400 group-hover:rotate-12 transition-transform"></i> 
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            {{-- VISUAL MOCKUP WINDOW (SEKARANG TURUN DI BAWAH TEKS) --}}
            <div class="w-full flex justify-center relative mt-6">
                <div class="relative w-full max-w-2xl z-20">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-[2.5rem] transform rotate-3 scale-105 opacity-20 blur-xl"></div>
                    
                    <div class="relative bg-white/5 backdrop-blur-md p-2 rounded-[2rem] shadow-[0_30px_60px_rgba(0,0,0,0.5)] border border-white/10">
                        <div class="bg-[#0f172a] rounded-t-[1.5rem] px-4 py-3 flex items-center border-b border-white/5">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                                <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                                <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                            </div>
                        </div>
                        
                        <div class="rounded-b-[1.5rem] overflow-hidden bg-[#0B1121] relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-20 z-10 pointer-events-none"></div>
                            <img src="{{ asset('images/ilustrasi-sekolah.png') }}" class="animate-float w-full h-auto object-cover relative z-0 opacity-90 hover:scale-105 transition-transform duration-700" alt="Ilustrasi Absensi Siswa">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

    {{-- SECTION: TENTANG KAMI --}}
<section id="tentang" class="relative pt-24 pb-20 lg:pt-32 lg:pb-32 overflow-hidden border-t border-white/5">
    <div class="glow-orb top-0 right-0 pointer-events-none"></div>

    <div class="container mx-auto px-6 lg:px-12 relative z-10">
        <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 px-4 py-2 rounded-full text-[10px] font-extrabold mb-6 tracking-widest uppercase shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                <i class="fas fa-heart text-[8px]"></i> Mengenal Kami
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                Lebih Dekat dengan <br> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400 drop-shadow-sm">Sistem E-Absensi</span>
            </h2>
        </div>

        {{-- SLIDER TENTANG KAMI --}}
        <div x-data="{ aboutActive: 0, total: 3 }" x-init="setInterval(() => { aboutActive = (aboutActive + 1) % total }, 5000)" class="relative max-w-6xl mx-auto px-4">
            <div class="relative overflow-hidden">
                <div class="flex transition-transform duration-1000 ease-in-out" :style="`transform: translateX(-${aboutActive * 100}%)`" style="width: 100%;">
                    
                    {{-- Slide 1 --}}
                    <div class="w-full flex-shrink-0 px-2 lg:px-4">
                        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
                            <div class="w-full lg:w-[50%] relative group text-center lg:text-left">
                                <div class="relative glass-card p-3 rounded-[3rem] shadow-2xl">
                                    <div class="rounded-[2.5rem] overflow-hidden bg-[#0B1121]">
                                        <img src="{{ asset('images/misi-digitalisasi.png') }}" class="animate-float w-full aspect-video object-cover opacity-90" alt="Visi">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-[50%] mt-8 lg:mt-0 text-center lg:text-left">
                                <h3 class="text-3xl font-extrabold text-white mb-6">Misi Digitalisasi</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Kami hadir untuk membawa perubahan nyata dalam manajemen sekolah melalui teknologi tepat guna bagi seluruh activitas akademika di {{ $schoolName }}.</p>
                                <div class="flex items-center gap-4 p-5 glass-card rounded-2xl shadow-lg inline-flex text-left">
                                    <div class="bg-indigo-500/20 text-indigo-400 w-12 h-12 flex items-center justify-center rounded-xl border border-indigo-500/30">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Inovasi Berkelanjutan</h4>
                                        <p class="text-xs text-slate-400">Update teknologi berkala untuk Madrasah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Slide 2 --}}
                    <div class="w-full flex-shrink-0 px-2 lg:px-4">
                        <div class="flex flex-col lg:flex-row-reverse items-center gap-8 lg:gap-16">
                            <div class="w-full lg:w-[50%] relative group text-center lg:text-left">
                                    <div class="relative glass-card p-3 rounded-[3rem] shadow-2xl">
                                    <div class="rounded-[2.5rem] overflow-hidden bg-[#0B1121]">
                                        <img src="{{ asset('images/protect-data.png') }}" class="animate-float w-full aspect-video object-cover opacity-90" alt="Keamanan">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-[50%] mt-8 lg:mt-0 text-center lg:text-left">
                                <h3 class="text-3xl font-extrabold text-white mb-6">Prioritas Keamanan Data</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Sistem database yang aman menjamin tidak ada manipulasi data absensi, menjaga integritas informasi di sekolah.</p>
                                <div class="flex items-center gap-4 p-5 glass-card rounded-2xl shadow-lg inline-flex text-left">
                                    <div class="bg-blue-500/20 text-blue-400 w-12 h-12 flex items-center justify-center rounded-xl border border-blue-500/30">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Data Terproteksi</h4>
                                        <p class="text-xs text-slate-400">Privasi adalah prioritas utama kami.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="w-full flex-shrink-0 px-2 lg:px-4">
                        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
                            <div class="w-full lg:w-[50%] relative group text-center lg:text-left">
                             <div class="relative glass-card p-3 rounded-[3rem] shadow-2xl">
                                <div class="rounded-[2.5rem] overflow-hidden bg-[#0B1121]">
                                    <img src="{{ asset('images/parent.png') }}" class="animate-float w-full aspect-video object-cover opacity-90" alt="Kemitraan">
                                </div>
                            </div>
                            </div>
                            <div class="w-full lg:w-[50%] mt-8 lg:mt-0 text-center lg:text-left">
                                <h3 class="text-3xl font-extrabold text-white mb-6">Membangun Kepercayaan</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Transparansi laporan real-time memperkuat sinergi antara madrasah dan wali murid demi kesuksesan siswa.</p>
                                <div class="flex items-center gap-4 p-5 glass-card rounded-2xl shadow-lg inline-flex text-left">
                                    <div class="bg-green-500/20 text-green-400 w-12 h-12 flex items-center justify-center rounded-xl border border-green-500/30">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Komunitas Solid</h4>
                                        <p class="text-xs text-slate-400">Sinergi sekolah dan orang tua.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Navigasi Slider --}}
            <div class="flex justify-between items-center mt-12 px-2 lg:px-6">
                <div class="flex gap-2">
                    <template x-for="i in [0, 1, 2]" :key="i">
                        <div @click="aboutActive = i" :class="aboutActive === i ? 'w-10 bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]' : 'w-2 bg-slate-600'" class="h-2 rounded-full cursor-pointer transition-all duration-500"></div>
                    </template>
                </div>
                <div class="flex gap-2">
                    <button @click="aboutActive = aboutActive === 0 ? 2 : aboutActive - 1" class="w-10 h-10 rounded-lg glass-card flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition-all active:scale-90">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button @click="aboutActive = aboutActive === 2 ? 0 : aboutActive + 1" class="w-10 h-10 rounded-lg glass-card flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition-all active:scale-90">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION: FITUR --}}
<section id="fitur" class="relative py-24 overflow-hidden border-t border-white/5">
    <div class="glow-orb bottom-0 left-0 pointer-events-none"></div>

    <div class="container mx-auto px-6 lg:px-12 relative z-10">
        <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 px-4 py-2 rounded-full text-[10px] font-extrabold mb-6 tracking-widest uppercase shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                <i class="fas fa-star text-[8px]"></i> Keunggulan Sistem
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                Fitur Utama <br> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400 drop-shadow-sm">E-Absensi Siswa</span>
            </h2>
            <p class="text-slate-400 text-lg leading-relaxed max-w-2xl mx-auto">
                Sistem yang dirancang khusus untuk memenuhi kebutuhan sekolah modern dengan fokus utama pada kemudahan penggunaan dan kecepatan akses.
            </p>
        </div>

        {{-- SLIDER FITUR --}}
        <div x-data="{ active: 0, loop() { setInterval(() => { this.active = this.active === 2 ? 0 : this.active + 1 }, 5000) } }" x-init="loop()" class="relative">
            <div class="relative overflow-hidden">
                <div class="flex transition-transform duration-700 ease-out" :style="`transform: translateX(-${active * 100}%)`" style="width: 100%;">
                    
                    {{-- Fitur 1 --}}
                    <div class="w-full flex-shrink-0 px-2">
                        <div class="glass-card p-8 lg:p-12 rounded-[3rem] shadow-2xl flex flex-col lg:flex-row items-center gap-12 border-t border-l border-white/10">
                            <div class="w-full lg:w-1/2 text-center lg:text-left">
                                <div class="bg-indigo-500/20 w-16 h-16 flex items-center justify-center rounded-2xl text-indigo-400 mb-8 shadow-[0_0_15px_rgba(99,102,241,0.3)] border border-indigo-500/30 mx-auto lg:mx-0">
                                    <i class="fas fa-qrcode text-3xl"></i>
                                </div>
                                <h3 class="text-3xl font-extrabold text-white mb-6">Presensi Cepat via QR Code</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Siswa hanya perlu melakukan scan kartu pelajar atau aplikasi di depan perangkat sekolah. Proses kurang dari 1 detik!</p>
                                <ul class="space-y-3 text-slate-300 font-medium inline-block text-left">
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-indigo-400"></i> Akurasi Waktu Real-time</li>
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-indigo-400"></i> Mencegah Penitipan Absen</li>
                                </ul>
                            </div>
                            <div class="w-full lg:w-1/2 bg-white/5 border border-white/10 rounded-[2.5rem] p-8 flex justify-center backdrop-blur-md">
                                <img src="{{ asset('images/fitur-qr.png') }}" class="w-full max-w-xs animate-float opacity-90" alt="QR Code Feature">
                            </div>
                        </div>
                    </div>

                    {{-- Fitur 2 --}}
                    <div class="w-full flex-shrink-0 px-2">
                        <div class="glass-card p-8 lg:p-12 rounded-[3rem] shadow-2xl flex flex-col lg:flex-row items-center gap-12 border-t border-l border-white/10">
                            <div class="w-full lg:w-1/2 text-center lg:text-left">
                                <div class="bg-green-500/20 w-16 h-16 flex items-center justify-center rounded-2xl text-green-400 mb-8 shadow-[0_0_15px_rgba(34,197,94,0.3)] border border-green-500/30 mx-auto lg:mx-0">
                                    <i class="fab fa-whatsapp text-3xl"></i>
                                </div>
                                <h3 class="text-3xl font-extrabold text-white mb-6">Notifikasi WhatsApp Orang Tua</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Setiap siswa yang absen otomatis mengirimkan pesan ke WA orang tua sebagai bentuk laporan kehadiran.</p>
                                <ul class="space-y-3 text-slate-300 font-medium inline-block text-left">
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-green-400"></i> Monitoring Jarak Jauh</li>
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-green-400"></i> Ketenangan Pikiran Wali Murid</li>
                                </ul>
                            </div>
                            <div class="w-full lg:w-1/2 bg-white/5 border border-white/10 rounded-[2.5rem] p-8 flex justify-center backdrop-blur-md">
                                <img src="{{ asset('images/fitur-notif.png') }}" class="w-full max-w-xs animate-float opacity-90" alt="WhatsApp Notification">
                            </div>
                        </div>
                    </div>

                    {{-- Fitur 3 --}}
                    <div class="w-full flex-shrink-0 px-2">
                        <div class="glass-card p-8 lg:p-12 rounded-[3rem] shadow-2xl flex flex-col lg:flex-row items-center gap-12 border-t border-l border-white/10">
                            <div class="w-full lg:w-1/2 text-center lg:text-left">
                                <div class="bg-orange-500/20 w-16 h-16 flex items-center justify-center rounded-2xl text-orange-400 mb-8 shadow-[0_0_15px_rgba(249,115,22,0.3)] border border-orange-500/30 mx-auto lg:mx-0">
                                    <i class="fas fa-chart-line text-3xl"></i>
                                </div>
                                <h3 class="text-3xl font-extrabold text-white mb-6">Rekap Laporan Otomatis</h3>
                                <p class="text-slate-400 text-lg leading-relaxed mb-8">Admin dan Guru tidak perlu merekap manual. Semua data tersimpan rapi dan bisa diunduh PDF/Excel.</p>
                                <ul class="space-y-3 text-slate-300 font-medium inline-block text-left">
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-orange-400"></i> Hemat Waktu Administrasi</li>
                                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-orange-400"></i> Data Akurat Tanpa Manipulasi</li>
                                </ul>
                            </div>
                            <div class="w-full lg:w-1/2 bg-white/5 border border-white/10 rounded-[2.5rem] p-8 flex justify-center backdrop-blur-md">
                                <img src="{{ asset('images/fitur-rekap.png') }}" class="w-full max-w-xs animate-float opacity-90" alt="Reporting Feature">
                            </div>
                        </div> 
                    </div>

                </div>
            </div>

            {{-- Navigasi Fitur --}}
            <div class="flex justify-center items-center gap-6 mt-12">
                <button @click="active = active === 0 ? 2 : active - 1" class="w-14 h-14 rounded-2xl glass-card flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition-all active:scale-90 border border-white/10">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="flex gap-3">
                    <template x-for="i in [0, 1, 2]" :key="i">
                        <button @click="active = i" :class="active === i ? 'w-10 bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]' : 'w-3 bg-slate-600'" class="h-3 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
                <button @click="active = active === 2 ? 0 : active + 1" class="w-14 h-14 rounded-2xl glass-card flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/10 transition-all active:scale-90 border border-white/10">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-slate-900 py-16 text-white">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 justify-between items-center gap-8">
            <div class="text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">{{ $schoolName }}</h3>
                <p class="text-slate-400 text-sm mb-4">Sistem Absensi Digital</p>
                
                {{-- Menampilkan Alamat & Kontak --}}
                <div class="space-y-2 text-xs text-slate-500">
                    @if(!empty($settings['school_address']))
                        <p><i class="fas fa-map-marker-alt w-5 text-indigo-500"></i> {{ $settings['school_address'] }}</p>
                    @endif
                    @if(!empty($settings['school_phone']))
                        <p><i class="fas fa-phone w-5 text-indigo-500"></i> {{ $settings['school_phone'] }}</p>
                    @endif
                    @if(!empty($settings['school_email']))
                        <p><i class="fas fa-envelope w-5 text-indigo-500"></i> {{ $settings['school_email'] }}</p>
                    @endif
                </div>
            </div>

            {{-- Media Sosial --}}
            <div class="flex justify-center md:justify-end gap-6 text-2xl">
                @if(!empty($settings['social_facebook']))
                    <a href="{{ $settings['social_facebook'] }}" target="_blank" class="hover:text-indigo-500 transition-all transform hover:scale-110">
                        <i class="fab fa-facebook"></i>
                    </a>
                @endif

                @if(!empty($settings['social_instagram']))
                    <a href="{{ $settings['social_instagram'] }}" target="_blank" class="hover:text-pink-500 transition-all transform hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                
                
            </div>
        </div>

        <div class="border-t border-slate-800 mt-12 pt-8 text-center text-slate-500 text-[10px] uppercase tracking-widest">
            © {{ date('Y') }} {{ $schoolName }} — Sistem Absensi QR Code
        </div>
    </div>
</footer>
</body>
</html>