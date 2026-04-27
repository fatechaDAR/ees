<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul dinamis, jika tidak ada fallback ke default -->
    <title>@yield('title', 'Sistem Evaluasi Kinerja')</title>
    
    <!-- Import Font Google (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS (Sebaiknya dipisah ke file public/css/app.css nantinya, namun untuk sekarang diletakkan di sini sesuai contoh) -->
    <style>
        :root {
            --primary-color: #792131;
            --primary-hover: #5a1824;
            --bg-layout: #F8F4F0;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --text-placeholder: #9CA3AF;
            --border-light: #E5E7EB;
            --bg-input: #F9FAFB;
            --radius-md: 8px;
            --radius-lg: 12px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 
                           0 10px 15px -3px rgba(0, 0, 0, 0.05),
                           0 30px 40px -15px rgba(121, 33, 49, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-layout); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; }

        /* Navbar Styles */
        .navbar { background-color: transparent; padding: 20px 0; width: 100%; }
        .navbar-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; justify-content: space-between; align-items: center; }
        .brand-link { display: flex; align-items: center; gap: 12px; color: var(--text-dark); }
        .brand-icon { background-color: var(--primary-color); color: white; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
        .brand-text { font-weight: 700; font-size: 1.1rem; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s ease; border: none; }
        .btn-outline-primary { background-color: var(--primary-color); color: white; }
        .btn-outline-primary:hover { background-color: var(--primary-hover); }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-block { width: 100%; padding: 14px; font-size: 1rem; }

        /* Main Layout & Forms (Digabung untuk efisiensi) */
        .main-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px 24px; }
        .register-card { background-color: white; width: 100%; max-width: 520px; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--card-shadow); }
        .card-header { margin-bottom: 24px; }
        .card-title { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .card-subtitle { font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #374151; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .form-input { width: 100%; padding: 12px 14px; padding-left: 40px; font-size: 0.95rem; font-family: inherit; color: var(--text-dark); background-color: white; border: 1px solid var(--border-light); border-radius: var(--radius-md); transition: all 0.2s; }
        .form-input::placeholder { color: var(--text-placeholder); }
        .form-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(121, 33, 49, 0.1); }
        .input-icon { position: absolute; color: var(--text-placeholder); display: flex; align-items: center; justify-content: center; }
        .left-icon { left: 12px; }
        .right-icon { right: 12px; }
        .toggle-password-btn { background: none; border: none; cursor: pointer; padding: 4px; }
        .toggle-password-btn:hover { color: var(--text-dark); }
        .input-icon svg { width: 18px; height: 18px; }

        /* Radio Buttons Roles */
        .role-selection-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; }
        .role-option { cursor: pointer; }
        .role-card-ui { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px; background-color: var(--bg-input); border: 1px solid var(--border-light); border-radius: var(--radius-md); transition: all 0.2s ease; gap: 8px; }
        .role-icon { color: var(--text-muted); }
        .role-icon svg { width: 24px; height: 24px; }
        .role-text { font-size: 0.9rem; font-weight: 600; color: var(--text-muted); }
        .role-option input[type="radio"]:checked + .role-card-ui { border-color: var(--primary-color); background-color: #fffafb; }
        .role-option input[type="radio"]:checked + .role-card-ui .role-icon, .role-option input[type="radio"]:checked + .role-card-ui .role-text { color: var(--primary-color); }

        /* Others */
        .password-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-actions { margin-top: 28px; }
        .card-footer { margin-top: 24px; text-align: center; }
        .login-prompt { font-size: 0.9rem; color: var(--text-muted); }
        .text-link-primary { color: var(--primary-color); font-weight: 600; }
        .text-link-primary:hover { text-decoration: underline; }
        .page-footer { padding: 24px; text-align: center; }
        .copyright-text { font-size: 0.8rem; color: #9CA3AF; }

        @media (max-width: 600px) {
            .register-card { padding: 24px; }
            .password-grid { grid-template-columns: 1fr; gap: 0; }
            .card-title { font-size: 1.5rem; }
        }
        /* =========================================
   REFINEMENT & CONSISTENCY IMPROVEMENTS
   ========================================= */

/* Label Row untuk Password + Forgot Link */
.label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

/* Checkbox Group */
.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
}

.checkbox-group label {
    font-size: 0.9rem;
    color: var(--text-muted);
    cursor: pointer;
    margin: 0;
    user-select: none;
}

/* Card Footer dengan Border */
.card-footer {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border-light);
    text-align: center;
}

/* Perbaikan Placeholder dan Typography */
.form-input::placeholder {
    color: var(--text-placeholder);
}

/* Small improvement for better UX */
.toggle-password-btn {
    color: var(--text-placeholder);
}

.toggle-password-btn:hover {
    color: var(--primary-color);
}

/*2 grid*/
/* item yang harus full 2 kolom */
.password-grid .full-width {
  grid-column: 1 / -1;
}

/* checkbox biar rapi */
.checkbox-group{
  display:flex;
  align-items:center;
  gap:10px;
}

/* tombol full */
.btn-block{
  width:100%;
}

    </style>
</head>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-password-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;

      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';

      btn.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
  });
});
</script>

<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="{{ url('/') }}" class="brand-link">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="8" y2="16"></line><line x1="16" y1="10" x2="16" y2="16"></line></svg>
                </div>
                <span class="brand-text">Evalytics</span>
            </a>
            <div class="navbar-actions">
                <!-- Helper route Laravel, asumsikan nama route login adalah 'login' -->
                <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA AKAN DI-INJECT DI SINI -->
    @yield('content')

    <!-- FOOTER -->
    <footer class="page-footer">
        <p class="copyright-text">&copy; {{ date('Y') }} Sistem Evaluasi Kinerja Panitia. Seluruh hak cipta dilindungi.</p>
    </footer>

</body>
</html>