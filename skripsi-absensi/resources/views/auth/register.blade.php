@extends('layouts.guest') 

@section('title', 'Daftar - ' . ($globalSettings['school_name'] ?? 'Sistem Absensi'))

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden bg-white">
    
    {{-- SISI KIRI: Branding & Logo (Full Screen White) --}}
    <div class="lg:w-1/2 bg-white flex flex-col justify-between p-12 relative overflow-hidden" data-aos="fade-right">
        
        {{-- Background Pattern Halus (Mirror Login) --}}
        <div class="absolute inset-0 z-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-50 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        {{-- Top Branding --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="bg-white p-1.5 rounded-xl border border-slate-100 shadow-sm">
                <img src="{{ $globalSettings['logo_url'] ?? asset('images/default_logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
            </div>
            <span class="text-slate-800 font-bold text-lg tracking-wide uppercase">{{ $globalSettings['school_name'] ?? 'E-Absensi' }}</span>
        </div>

            {{-- Logo Besar & Teks Tengah --}}
        <div class="relative z-10 flex flex-col items-center text-center flex-1 justify-center py-12">
            <div class="relative animate-float mb-10">
                {{-- EFEK GLOW (Tambahkan ini biar ada cahaya di belakang logo) --}}
                <div class="absolute -inset-10 bg-indigo-100 rounded-full blur-[80px] opacity-60"></div>
                
                {{-- KOTAK LOGO DENGAN SHADOW TEBAL --}}
                <div class="relative bg-white p-10 rounded-[3rem] shadow-2xl shadow-indigo-200/50 border border-slate-50">
                    <img src="{{ $globalSettings['logo_url'] ?? asset('images/default_logo.png') }}" 
                         alt="Logo Sekolah" 
                         class="w-60 h-60 object-contain">
                </div>
            </div>
            
            <h1 class="text-4xl font-extrabold text-slate-900 leading-tight">
                Bergabunglah <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">
                    Bersama Kami
                </span>
            </h1>
            <p class="text-slate-500 mt-6 max-w-sm mx-auto text-lg leading-relaxed">
                Buat akun baru untuk mulai mengelola atau memantau aktivitas akademik secara digital.
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

    {{-- SISI KANAN: Form Registrasi (Deep Navy - Mirror Login) --}}
    <div class="lg:w-1/2 bg-[#1e1b4b] flex flex-col justify-center p-10 sm:p-16 lg:p-20 xl:p-24 relative overflow-y-auto" data-aos="fade-left">
        
        {{-- Decorative Background Pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="relative z-10 max-w-md mx-auto w-full py-8">
            <div class="mb-10">
                <h2 class="text-5xl font-bold text-white mb-4">Registrasi</h2>
                <p class="text-indigo-300 text-lg opacity-80">Lengkapi formulir di bawah ini dengan benar.</p>
            </div>

            {{-- Alerts (Logika AMAN) --}}
            @if ($errors->any())
                 <div class="mb-8 bg-red-500/10 border border-red-500/20 p-4 rounded-2xl text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> Mohon periksa kembali inputan Anda.
                </div>
            @endif

            {{-- FORM REGISTRASI (Atribut Name & ID Dijamin AMAN) --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-6" id="registerForm">
                @csrf
                
                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" autocomplete="name" required autofocus
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all duration-300 @error('name') border-red-500 @enderror" 
                        placeholder="Contoh: Budi Santoso" value="{{ old('name') }}">
                    @error('name') <p class="mt-2 text-xs text-red-400 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all duration-300 @error('email') border-red-500 @enderror" 
                        placeholder="nama@email.com" value="{{ old('email') }}">
                    @error('email') <p class="mt-2 text-xs text-red-400 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Mendaftar Sebagai (Role Select) --}}
                <div>
                    <label for="role" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Mendaftar Sebagai</label>
                    <div class="relative">
                        <select id="role" name="role" required 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all appearance-none cursor-pointer @error('role') border-red-500 @enderror">
                            <option value="" disabled selected class="text-slate-900">Pilih Peran...</option>
                            <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }} class="text-slate-900">Guru / Wali Kelas</option>
                            <option value="orang_tua" {{ old('role') == 'orang_tua' ? 'selected' : '' }} class="text-slate-900">Orang Tua</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-indigo-300">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('role') <p class="mt-2 text-xs text-red-400 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password Section (Grid 2 Kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all duration-300 @error('password') border-red-500 @enderror" 
                            placeholder="••••••••">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Ulangi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all duration-300" 
                            placeholder="••••••••">
                    </div>
                </div>
                @error('password') <p class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</p> @enderror

                {{-- Tombol Daftar --}}
                <div class="pt-4 w-full">
                            <button type="submit" id="registerBtn" class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-900/40 transition-all transform hover:-translate-y-1 active:scale-[0.98] text-center text-lg">
                                <i class="fas fa-user-plus mr-3"></i> Daftar Sekarang
                            </button>
                    
                    <p class="mt-8 text-center text-indigo-400 text-sm">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-white font-bold hover:underline transition-all">Login disini</a>
                    </p>
                </div>
            </form>

            {{-- Back Button --}}
            <div class="mt-12 text-center border-t border-white/5 pt-8">
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
    // Script loading tetap dijaga agar fungsionalitas tombol tetap aman
    document.getElementById('registerForm').addEventListener('submit', function() {
        const btn = document.getElementById('registerBtn');
        if(this.checkValidity()){
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-3"></i> Memproses...';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });
</script>
@endsection