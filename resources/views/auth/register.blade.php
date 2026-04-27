@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Sistem Evaluasi Kinerja')

@section('content')
<main class="main-wrapper">
    <div class="register-card">
        
        <div class="card-header">
            <h1 class="card-title">Daftar Akun Baru</h1>
            <p class="card-subtitle">Silakan isi formulir di bawah ini untuk bergabung dengan Sistem Evaluasi Kinerja Panitia Event Kampus.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="register-form">
            @csrf
            
            <!-- Input Nama Lengkap -->
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-wrapper">
                    <span class="input-icon left-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="{{ old('name') }}" 
                           placeholder="Masukkan nama lengkap Anda" 
                           autocomplete="name" required autofocus>
                </div>
            </div>

            <!-- Input Alamat Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrapper">
                    <span class="input-icon left-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="{{ old('email') }}" 
                           placeholder="contoh@kampus.ac.id" 
                           autocomplete="email" required>
                </div>
            </div>

            <!-- Input Pilihan Peran (Role) -->
            <div class="form-group">
                <label class="form-label">Pilih Peran (Role)</label>
                
                <div class="role-selection-grid">
                    <!-- Opsi Panitia -->
                    <label class="role-option">
                        <input type="radio" name="role" value="panitia" class="sr-only" {{ old('role', 'panitia') == 'panitia' ? 'checked' : '' }}>
                        <div class="role-card-ui">
                            <span class="role-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </span>
                            <span class="role-text">Panitia</span>
                        </div>
                    </label>

                    <!-- Opsi Admin -->
                    <label class="role-option">
                        <input type="radio" name="role" value="admin" class="sr-only" {{ old('role') == 'admin' ? 'checked' : '' }}>
                        <div class="role-card-ui">
                            <span class="role-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                            </span>
                            <span class="role-text">Admin</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Input Kata Sandi & Konfirmasi (Grid 2 Kolom) -->
            <div class="password-grid">

                <!-- Kata Sandi -->
                <div class="form-group">
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
                            placeholder="Minimal 8 karakter" autocomplete="new-password" required>

                        <!-- Tombol toggle mata -->
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

                <!-- Konfirmasi Sandi -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon left-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>

                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            placeholder="Ulangi kata sandi" autocomplete="new-password" required>

                        <!-- Tombol toggle mata -->
                        <button type="button"
                            class="input-icon right-icon toggle-password-btn"
                            data-target="password_confirmation"
                            aria-label="Tampilkan konfirmasi kata sandi">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
            <!-- Tombol Submit -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-block">Daftar Sekarang</button>
            </div>
        </form>

        <!-- Link Navigasi ke Login -->
        <div class="card-footer">
            <p class="login-prompt">
                Sudah memiliki akun? 
                <a href="/login" class="text-link-primary">Masuk di sini</a>
            </p>
        </div>

    </div> 
</main>
@endsection