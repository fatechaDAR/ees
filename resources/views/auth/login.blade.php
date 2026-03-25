@extends('layouts.app')

@section('title', 'Login - Sistem Evaluasi Kinerja')

@section('content')
<!-- 1. Gunakan class 'main-wrapper' persis seperti di register agar otomatis ke tengah (center) -->
<main class="main-wrapper">
    
    <!-- 2. Gunakan class 'register-card' agar mendapatkan desain box putih, shadow, & padding yang persis sama -->
    <div class="register-card">
        
        <!-- 3. Gunakan 'card-header' untuk konsistensi layout judul -->
        <div class="card-header">
            <h1 class="card-title">Welcome Back</h1>
            <p class="card-subtitle">Sign in to manage your event performance.</p>
        </div>

        <!-- 4. Form menggunakan class 'register-form' -->
        <form action="{{ route('login') }}" method="POST" class="register-form">
            @csrf
            
            <!-- Input Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrapper">
                    <span class="input-icon left-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="e.g. student@univ.ac.id" required autofocus>
                </div>
            </div>

            <!-- Input Password -->
            <div class="form-group">
                <!-- Penyesuaian inline flex agar label "Password" dan link "Forgot" bisa sejajar rapi -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-link-primary" style="font-size: 0.85rem;">Forgot?</a>
                    @endif
                </div>
                
                <div class="input-wrapper">
                    <span class="input-icon left-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    <button type="button" class="input-icon right-icon toggle-password-btn" aria-label="Tampilkan password">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
            </div>

            <!-- Checkbox Remember Me -->
            <!-- Menggunakan inline style sederhana agar checkbox rapi tanpa perlu tambah CSS baru -->
            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="remember" id="remember" style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary-color);" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="font-size: 0.85rem; color: var(--text-muted); cursor: pointer; margin: 0;">Keep me logged in</label>
            </div>

            <!-- Tombol Login -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-block">
                    Login to Dashboard
                </button>
            </div>
        </form>

        <!-- 5. Gunakan struktur 'card-footer' persis seperti di halaman register -->
        <div class="card-footer" style="margin-top: 24px; border-top: 1px solid var(--border-light); padding-top: 24px;">
            <p class="login-prompt">
                Belum memiliki akun? <a href="{{ route('register') }}" class="text-link-primary">Daftar Sekarang</a>
            </p>
        </div>

    </div> 
</main>
@endsection