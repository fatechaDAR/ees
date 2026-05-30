@extends('layouts.app')

@section('title', 'Login - Sistem Evaluasi Kinerja')

@section('content')
<main class="main-wrapper">
    <div class="register-card">
        
        <div class="card-header">
            <h1 class="card-title">Selamat Datang Kembali</h1>
            <p class="card-subtitle">Masuk untuk mengelola kinerja event Anda.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="register-form">
            @csrf

            @if($errors->any())
            <div style="background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- Input Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrapper">
                    <span class="input-icon left-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="{{ old('email') }}" 
                           placeholder="contoh@kampus.ac.id" 
                           autocomplete="email" required autofocus>
                </div>
            </div>

        <div class="password-grid">

                <!-- Kata Sandi (FULL) -->
                <div class="form-group full-width">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon left-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>

                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Minimal 8 karakter" autocomplete="current-password" required>

                        <button type="button"
                            class="input-icon right-icon toggle-password-btn"
                            data-target="password"
                            aria-label="Tampilkan kata sandi">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember me (DI BAWAH, FULL) -->
                <div class="form-group checkbox-group full-width">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat udah saya</label>
                </div>

                <!-- Tombol Masuk (FULL) -->
                <div class="form-actions full-width">
                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                </div>

                <!-- Footer (DI BAWAH, FULL) -->
                <div class="card-footer full-width">
                    <p class="login-prompt">
                        Belum memiliki akun?
                        <a href="/register" class="text-link-primary">Daftar Sekarang</a>
                    </p>
                </div>

            </div>
        </form>

    </div> 
</main>
@endsection