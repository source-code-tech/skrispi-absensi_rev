@extends('layouts.guest') 

@section('title', 'Lupa Password - ' . ($globalSettings['school_name'] ?? 'Sistem Absensi'))

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden bg-white">
    
    {{-- SISI KIRI: Branding & Logo (Full Screen White) --}}
    <div class="lg:w-1/2 bg-white flex flex-col justify-between p-12 relative overflow-hidden" data-aos="fade-right">
        
        {{-- Background Pattern Halus (Mirror Login/Register) --}}
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

        {{-- Logo Besar & Teks Tengah (Glow & Float) --}}
        <div class="relative z-10 flex flex-col items-center text-center flex-1 justify-center py-12">
            <div class="relative animate-float mb-10">
                {{-- Efek Glow di belakang logo --}}
                <div class="absolute -inset-10 bg-indigo-100 rounded-full blur-[80px] opacity-60"></div>
                
                {{-- Kotak Logo Putih Besar --}}
                <div class="relative bg-white p-10 rounded-[3rem] shadow-2xl shadow-indigo-200/50 border border-slate-50">
                    <img src="{{ $globalSettings['logo_url'] ?? asset('images/default_logo.png') }}" 
                         alt="Logo Sekolah" 
                         class="w-48 h-48 object-contain">
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 leading-tight">
                Pemulihan Akun <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">
                    Tetap Aman
                </span>
            </h1>
            <p class="text-slate-500 mt-6 max-w-sm mx-auto text-lg leading-relaxed">
                Lupa password bukan masalah besar. Masukkan email Anda dan kami akan membantu Anda kembali.
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

    {{-- SISI KANAN: Form (Deep Navy - Mirror Login) --}}
    <div class="lg:w-1/2 bg-[#1e1b4b] flex flex-col justify-center p-10 sm:p-16 lg:p-24 relative" data-aos="fade-left">
        
        {{-- Decorative Background Pattern --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="relative z-10 max-w-md mx-auto w-full">
            <div class="mb-12">
                <h2 class="text-5xl font-bold text-white mb-4">Reset Password</h2>
                <p class="text-indigo-300 text-lg opacity-80">Masukkan email terdaftar untuk menerima link pemulihan.</p>
            </div>

            {{-- Alerts (Logika AMAN) --}}
            @if (session('status'))
                <div class="mb-8 bg-green-500/10 border border-green-500/20 p-4 rounded-2xl text-green-400 text-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                 <div class="mb-8 bg-red-500/10 border border-red-500/20 p-4 rounded-2xl text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            {{-- Form (ID & Action Dijamin AMAN) --}}
            <form action="{{ route('password.email') }}" method="POST" class="space-y-8" id="forgotForm">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mb-3 ml-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-indigo-300/40 group-focus-within:text-indigo-400 transition-colors">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required autofocus
                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-5 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white/10 transition-all duration-300" 
                            placeholder="nama@email.com" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="forgotBtn" class="block w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-5 rounded-2xl shadow-xl shadow-indigo-900/40 transition-all transform hover:-translate-y-1 active:scale-[0.98] text-center text-lg">
                        <i class="fas fa-paper-plane mr-3"></i> Kirim Link Reset
                    </button>
                    
                    <p class="mt-8 text-center text-indigo-400">
                        Ingat password Anda? 
                        <a href="{{ route('login') }}" class="text-white font-bold hover:underline transition-all">Kembali Login</a>
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
    document.getElementById('forgotForm').addEventListener('submit', function() {
        const btn = document.getElementById('forgotBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-3"></i> Memproses...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection