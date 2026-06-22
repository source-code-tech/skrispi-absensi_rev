@extends('layouts.guest') 

@section('title', 'Login - ' . ($globalSettings['school_name'] ?? 'Sistem Absensi'))

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden bg-white">
    
    {{-- SISI KIRI: Branding & Ilustrasi (Full Screen White) --}}
    <div class="lg:w-1/2 bg-white flex flex-col justify-between p-12 relative overflow-hidden" data-aos="fade-right">
        
        {{-- Background Pattern Halus --}}
        <div class="absolute inset-0 z-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-50 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        {{-- Top Branding --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="bg-slate-50 p-1.5 rounded-xl border border-slate-100 shadow-sm">
                <img src="{{ $globalSettings['logo_url'] ?? asset('images/default_logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
            </div>
            <span class="text-slate-800 font-bold text-lg tracking-wide uppercase">{{ $globalSettings['school_name'] ?? 'E-Absensi' }}</span>
        </div>

        {{-- Logo Besar & Teks Tengah --}}
        <div class="relative z-10 flex flex-col items-center text-center flex-1 justify-center py-12">
            <div class="animate-float mb-10">
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-slate-50">
                    <img src="{{ $globalSettings['logo_url'] ?? asset('images/default_logo.png') }}" 
                         alt="Logo Sekolah" 
                         class="w-48 h-48 object-contain">
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 leading-tight">
                Mewujudkan Absensi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">
                    Digital Modern
                </span>
            </h1>
            <p class="text-slate-500 mt-6 max-w-sm mx-auto text-lg leading-relaxed">
                Sistem monitoring kehadiran siswa yang cerdas, cepat, dan terintegrasi.
            </p>
        </div>

        {{-- Copyright Bawah --}}
        <div class="relative z-10 text-slate-400 text-sm font-medium">
            &copy; {{ date('Y') }} {{ $globalSettings['school_name'] }}. All rights reserved.
        </div>

        {{-- SVG WAVE (Pembatas Melengkung ke Sisi Kanan) --}}
        <div class="hidden lg:block absolute top-0 right-[-1px] h-full w-24 z-20">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 0 C 50 0, 50 100, 100 100 L 100 0 Z" fill="white" />
            </svg>
        </div>
    </div>

    {{-- SISI KANAN: Form Login (Deep Indigo Full Screen) --}}
    <div class="lg:w-1/2 bg-indigo-950 flex flex-col justify-center p-10 sm:p-16 lg:p-24 relative" data-aos="fade-left">
        
        {{-- Decorative Background Pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="relative z-10 max-w-md mx-auto w-full">
            <div class="mb-12">
                <h2 class="text-5xl font-bold text-white mb-4">Login</h2>
                <p class="text-indigo-300 text-lg">Selamat datang kembali! Silakan masuk ke akun Anda.</p>
            </div>

            {{-- Alerts (Logika AMAN) --}}
            @if (session('status') || session('success'))
                <div class="mb-8 bg-green-500/10 border border-green-500/20 p-4 rounded-2xl text-green-400 text-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') ?? session('success') }}
                </div>
            @endif

            @if ($errors->any())
                 <div class="mb-8 bg-red-500/10 border border-red-500/20 p-4 rounded-2xl text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

                {{-- Form Login (Sama Persis Secara Logika - AMAN 100%) --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-6" id="loginForm">
                @csrf
                
                {{-- Email --}}
                <div class="w-full">
                    <label for="email" class="block text-xs font-bold text-indigo-300 uppercase tracking-widest mb-3">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-indigo-600">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="block w-full bg-white/10 border border-white/10 rounded-2xl pl-12 pr-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/20 transition-all duration-300" 
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div class="w-full">
                    <label for="password" class="block text-xs font-bold text-indigo-300 uppercase tracking-widest mb-3">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-indigo-600">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="block w-full bg-white/10 border border-white/10 rounded-2xl pl-12 pr-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/20 transition-all duration-300" 
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Baris Ingat Saya & Lupa Password (POSISI ASLI) --}}
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-3">
                        <input id="remember-me" name="remember" type="checkbox" class="w-4 h-4 rounded border-white/10 bg-white/5 text-indigo-600 focus:ring-offset-indigo-950">
                        <label for="remember-me" class="text-sm text-indigo-300 cursor-pointer">Ingat saya</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-400 hover:text-white transition font-medium">Lupa Password?</a>
                    @endif
                </div>

                {{-- Tombol Login --}}
                <div class="pt-2 w-full">
                    <button type="submit" id="loginBtn" class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all transform hover:-translate-y-1 active:scale-[0.98] text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk Ke Sistem
                    </button>
                    
                    <p class="mt-8 text-center text-indigo-400 text-sm">
                        Belum memiliki akun? 
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-white font-bold hover:underline">Daftar Sekarang</a>
                        @endif
                    </p>
                </div>
            </form>

            {{-- Back Button --}}
            <div class="mt-12 text-center">
                 <a href="{{ url('/') }}" class="inline-flex items-center text-xs text-indigo-300/60 hover:text-white transition-all uppercase tracking-widest font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Script loading tetap dipertahankan agar UX bagus saat login klik
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-3"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection