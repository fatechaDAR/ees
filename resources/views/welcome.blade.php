<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Evaluasi Kinerja')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #792131;
            --primary-hover: #5a1824;
            --bg-layout: #F8F4F0;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --text-placeholder: #9CA3AF;
            --border-light: #E5E7EB;
            --bg-input: #FFFFFF;
            --radius-md: 8px;
            --radius-lg: 12px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 
                           0 10px 15px -3px rgba(0, 0, 0, 0.05),
                           0 30px 40px -15px rgba(121, 33, 49, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-layout); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        a { text-decoration: none; }

        /* Layout Utama */
        .main-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px 24px; }
        .register-card { background-color: white; width: 100%; max-width: 520px; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--card-shadow); }
        .card-header { margin-bottom: 24px; }
        .card-title { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .card-subtitle { font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; }
        .card-footer { margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light); text-align: center; }
        .login-prompt { font-size: 0.9rem; color: var(--text-muted); }

        /* Form & Input */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #374151; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .form-input { width: 100%; padding: 12px 14px; padding-left: 40px; font-size: 0.95rem; font-family: inherit; color: var(--text-dark); background-color: white; border: 1px solid var(--border-light); border-radius: var(--radius-md); transition: all 0.2s; }
        .form-input::placeholder { color: var(--text-placeholder); }
        .input-icon { position: absolute; color: var(--text-placeholder); display: flex; align-items: center; justify-content: center; }
        .left-icon { left: 12px; }
        .right-icon { right: 12px; }
        .input-icon svg { width: 18px; height: 18px; }
        .toggle-password-btn { background: none; border: none; cursor: pointer; padding: 4px; }

        /* Tombol */
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; border-radius: var(--radius-md); font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s ease; border: none; }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-block { width: 100%; padding: 14px; font-size: 1rem; }
        .form-actions { margin-top: 28px; }

        /* Pilihan Role */
        .role-selection-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0; }
        .role-option { cursor: pointer; }
        .role-card-ui { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px; background-color: var(--bg-input); border: 1px solid var(--border-light); border-radius: var(--radius-md); transition: all 0.2s ease; gap: 8px; }
        .role-icon { color: var(--text-muted); }
        .role-icon svg { width: 24px; height: 24px; }
        .role-text { font-size: 0.9rem; font-weight: 600; color: var(--text-muted); }
        .role-option input[type="radio"]:checked + .role-card-ui { border-color: var(--primary-color); background-color: #fffafb; }
        .role-option input[type="radio"]:checked + .role-card-ui .role-icon,
        .role-option input[type="radio"]:checked + .role-card-ui .role-text { color: var(--primary-color); }

        /* Password Grid */
        .password-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Link */
        .text-link-primary { color: var(--primary-color); font-weight: 600; }

        /* Footer */
        .page-footer { padding: 24px; text-align: center; }
        .copyright-text { font-size: 0.8rem; color: #9CA3AF; }

        /* =====================================
           UX ENHANCEMENTS (MICRO INTERACTIONS)
           ===================================== */
        .form-input, .btn, .input-icon, .role-card-ui, .text-link-primary {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(121, 33, 49, 0.15); 
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(121, 33, 49, 0.25);
        }

        .btn-primary:active {
            transform: translateY(1px);
            box-shadow: 0 2px 5px rgba(121, 33, 49, 0.2);
        }

        .text-link-primary:hover {
            color: var(--primary-hover);
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .role-option:hover .role-card-ui {
            border-color: var(--primary-color);
            background-color: rgba(121, 33, 49, 0.02);
        }

        button:focus-visible, a:focus-visible,
        .role-option input[type="radio"]:focus-visible + .role-card-ui {
            outline: 2px dashed var(--primary-color);
            outline-offset: 4px;
        }

        /* Responsive Mobile */
        @media (max-width: 600px) {
            .register-card { padding: 24px; }
            .password-grid { grid-template-columns: 1fr; gap: 0; }
            .card-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    @yield('content')

    <footer class="page-footer">
        <p class="copyright-text">&copy; {{ date('Y') }} Sistem Evaluasi Kinerja Panitia. Seluruh hak cipta dilindungi.</p>
    </footer>

</body>
</html>