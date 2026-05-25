<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title dinamis dari view masing-masing -->
    <title>@yield('title', 'Dashboard - Sistem Evaluasi')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =========================================
           1. DESIGN SYSTEM VARIABLES (Sync dgn Auth)
           ========================================= */
        :root {
            --primary-color: #792131; 
            --primary-hover: #5a1824; 
            --bg-layout: #F8F4F0; 
            --bg-white: #FFFFFF; 
            
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --text-placeholder: #9CA3AF;
            
            --border-light: #E5E7EB;
            
            --radius-md: 8px; 
            --radius-lg: 12px; 
            
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            
            --sidebar-width: 280px;
            --topbar-height: 80px;
        }

        /* =========================================
           2. BASE RESET & TYPOGRAPHY
           ========================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-layout); 
            color: var(--text-dark); 
            overflow: hidden; 
        }

        a { text-decoration: none; transition: all 0.3s ease-in-out; }
        ul { list-style: none; }

        button, .sidebar__link, .btn-help-outline {
            transition: all 0.3s ease-in-out;
        }

        /* =========================================
           3. LAYOUT UTAMA
           ========================================= */
        .dashboard-layout { display: flex; height: 100vh; width: 100vw; }

        /* =========================================
           4. SIDEBAR KIRI
           ========================================= */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-white);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid var(--border-light);
            z-index: 10;
        }

        .sidebar__brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 32px;
            gap: 12px;
        }

        .brand__icon {
            width: 36px; height: 36px;
            background-color: var(--primary-color);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            color: white;
        }

        .brand__text { display: flex; flex-direction: column; }
        .brand__title { font-weight: 800; font-size: 1.1rem; color: var(--primary-color); line-height: 1.2; text-transform: uppercase; }
        .brand__subtitle { font-size: 0.65rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        .sidebar__nav {
            padding: 24px 16px;
            display: flex; flex-direction: column; gap: 8px; 
            flex: 1; /* Mengisi ruang kosong di tengah */
            overflow-y: auto; /* Memunculkan scroll otomatis jika menu banyak */
        }

        /* Styling scrollbar khusus untuk sidebar agar tipis dan elegan */
        .sidebar__nav::-webkit-scrollbar { width: 4px; }
        .sidebar__nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar__nav::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .sidebar__nav::-webkit-scrollbar-thumb:hover { background: #9CA3AF; }

        /* Buat wadah baru untuk area bawah (Logout & Bantuan) */
        .sidebar__footer {
            padding: 16px;
            border-top: 1px solid var(--border-light); /* Garis pemisah tipis */
            background-color: var(--bg-white);
        }

        /* Modifikasi margin help-card agar fit di footer */
        .sidebar__help-card {
            margin: 0 0 16px 0; /* Ubah marginnya */
            padding: 20px;
            background-color: var(--bg-layout);
            border-radius: var(--radius-lg); text-align: center;
        }

        .sidebar__link {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px; border-radius: var(--radius-md);
            color: var(--text-muted); font-weight: 600; font-size: 0.95rem;
        }

        .sidebar__link:hover {
            background-color: rgba(121, 33, 49, 0.05);
            color: var(--primary-color);
        }

        .sidebar__link--active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(121, 33, 49, 0.2);
        }

        .sidebar__link--active:hover {
            background-color: var(--primary-hover); color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(121, 33, 49, 0.25);
        }

        .sidebar__help-card {
            margin: 24px 16px; padding: 20px;
            background-color: var(--bg-layout);
            border-radius: var(--radius-lg); text-align: center;
        }

        .help-card__title { font-size: 0.75rem; font-weight: 800; color: var(--primary-color); text-transform: uppercase; margin-bottom: 8px; }
        .help-card__desc { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 16px; line-height: 1.4; }

        .btn-help-outline {
            display: block; width: 100%; padding: 10px;
            background-color: var(--bg-white); color: var(--primary-color);
            border: 1px solid var(--border-light); border-radius: var(--radius-md);
            font-weight: 700; font-size: 0.85rem; cursor: pointer;
        }

        .btn-help-outline:hover { 
            border-color: var(--primary-color); 
            background-color: rgba(121, 33, 49, 0.03); 
        }

        /* =========================================
           5. MAIN CONTAINER
           ========================================= */
        .dashboard-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* =========================================
           6. TOPBAR HEADER
           ========================================= */
        .topbar {
            height: var(--topbar-height);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 40px; background-color: transparent;
        }

        .topbar__title { font-size: 1.5rem; font-weight: 800; color: #792131; }
        .topbar__actions { display: flex; align-items: center; gap: 24px; }

        .notification-btn { background: none; border: none; color: var(--text-muted); cursor: pointer; position: relative; padding: 4px; }
        .notification-btn:hover { color: var(--primary-color); }
        .notification-btn::after {
            content: ''; position: absolute; top: 2px; right: 4px; width: 8px; height: 8px;
            background-color: #dc2626; border-radius: 50%; border: 2px solid var(--bg-layout);
        }

        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; transition: opacity 0.2s; }
        .user-profile:hover { opacity: 0.8; }
        .user-profile__info { display: flex; flex-direction: column; align-items: flex-end; }
        .user-profile__name { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); }
        .user-profile__role { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .user-profile__avatar { width: 40px; height: 40px; border-radius: 50%; border: 1px solid var(--border-light); object-fit: cover; }

        /* =========================================
           7. CONTENT AREA
           ========================================= */
        .content-area { flex: 1; overflow-y: auto; padding: 10px 40px 40px; position: relative; }
        .content-area::-webkit-scrollbar { width: 6px; }
        .content-area::-webkit-scrollbar-track { background: transparent; }
        .content-area::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .content-area::-webkit-scrollbar-thumb:hover { background: var(--text-placeholder); }

        /* =========================================
           8. atur logo, style nya
           ========================================= */
        /* Atur container brand agar sejajar ke samping */
        .sidebar__brand {
            display: flex;
            align-items: center; /* Membuat logo dan teks sejajar di tengah secara vertikal */
            gap: 10px; /* Memberi jarak antara logo dan teks */
            font-weight: 800; /* Opsional: Menebalkan teks Evalytics */
        }

        /* Atur ukuran logo agar tidak kebesaran */
        .brand-logo {
            width: 60px; /* Silakan ubah angka ini untuk memperbesar/memperkecil logo */
            height: auto; /* Menjaga proporsi gambar agar tidak gepeng */
            border-radius: 9px; /* Opsional: Memberikan efek membulat di ujung logo */
        }

        /* =========================================
            9. MODAL PROFILE
            ========================================= */
            /* Modal Overlay */
    .modal-overlay-profile {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);
        display: flex; justify-content: center; align-items: center;
        z-index: 9999; opacity: 0; visibility: hidden;
        transition: all 0.3s ease;
    }
    .modal-overlay-profile.active { opacity: 1; visibility: visible; }

    /* Modal Content */
    .modal-content-profile {
        background-color: #ffffff; width: 100%; max-width: 400px;
        border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        transform: translateY(-20px); transition: 0.3s ease;
        padding: 24px; font-family: 'Inter', sans-serif;
    }
    .modal-overlay-profile.active .modal-content-profile { transform: translateY(0); }

    /* Header & Teks */
    .profile-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .profile-header h3 { margin: 0; font-size: 1.2rem; color: #1e293b; font-weight: 800; }
    .btn-close-profile { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
    
    /* Area Preview Foto */
    .profile-body { text-align: center; }
    .preview-avatar {
        width: 120px; height: 120px; border-radius: 50%; object-fit: cover;
        border: 4px solid #f8fafc; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        margin-bottom: 16px;
    }
    
    /* Input File Styling */
    .file-input-wrapper { margin-bottom: 24px; }
    .file-input-wrapper input[type="file"] {
        font-size: 0.85rem; color: #64748b;
    }

    /* Footer / Tombol */
    .profile-footer { display: flex; justify-content: flex-end; gap: 12px; }
    .btn-batal { padding: 10px 16px; border: 1px solid #e2e8f0; background: white; border-radius: 8px; cursor: pointer; font-weight: 600; color: #64748b; }
    .btn-simpan { padding: 10px 16px; border: none; background: #792131; border-radius: 8px; cursor: pointer; font-weight: 600; color: white; transition: 0.2s;}
    .btn-simpan:hover { background: #5a1824; }
    </style>
</head>

<div id="modalGantiFoto" class="modal-overlay-profile">
    <div class="modal-content-profile">
        
        <div class="profile-header">
            <h3>Ganti Foto Profil</h3>
            <button class="btn-close-profile" onclick="toggleModalProfile(false)">&times;</button>
        </div>

        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="profile-body">
                <img id="previewGambar" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=792131&color=fff&size=120" alt="Preview" class="preview-avatar">
                
                <div class="file-input-wrapper">
                    <input type="file" name="foto" id="inputFoto" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                </div>
            </div>

            <div class="profile-footer">
                <button type="button" class="btn-batal" onclick="toggleModalProfile(false)">Batal</button>
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>

<script>
    // Fungsi buka/tutup modal
    function toggleModalProfile(isShow) {
        const modal = document.getElementById('modalGantiFoto');
        if (isShow) {
            modal.classList.add('active');
        } else {
            modal.classList.remove('active');
            // Opsional: reset form kalau di-cancel
            document.getElementById('inputFoto').value = ""; 
        }
    }

    // Fungsi canggih untuk Live Preview Foto
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        
        reader.onload = function() {
            // Mengganti src gambar preview dengan file yang baru dipilih
            const output = document.getElementById('previewGambar');
            output.src = reader.result;
        };
        
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<body>

    <div class="dashboard-layout">
        
        <aside class="sidebar">
            
            <div class="sidebar__brand">
                <img src="/img/logo.png" alt="Logo Evalytics" class="brand-logo">

                <span>Evalytics</span>
            </div>

            <nav class="sidebar__nav">
                <a href="/dashboard-admin" class="sidebar__link {{ request()->is('dashboard-admin') ? 'sidebar__link--active' : '' }}">                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard Admin
                </a>
                <a href="/manajemen-event" class="sidebar__link {{ request()->is('manajemen-event') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <line x1="8" y1="14" x2="8" y2="14"></line>
                        <line x1="12" y1="14" x2="12" y2="14"></line>
                        <line x1="16" y1="14" x2="16" y2="14"></line>
                    </svg>
                    Manajemen Event
                </a>
                <a href="/manajemen-divisi" class="sidebar__link {{ request()->is('manajemen-divisi') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Manajemen Divisi
                </a>
                <a href="/manajemen-panitia" class="sidebar__link {{ request()->is('manajemen-panitia') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <circle cx="12" cy="10" r="3"></circle>
                        <path d="M7 20c0-3.3 2.7-5 5-5s5 1.7 5 5"></path>
                    </svg>
                    Manajemen Panitia
                </a>
                <a href="/manajemen-user" class="sidebar__link {{ request()->is('manajemen-user') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Manajemen User
                </a>
                <a href="/monitoring-evaluasi" class="sidebar__link {{ request()->is('monitoring-evaluasi') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                    Monitoring Evaluasi
                </a>
                <a href="/hasil-evaluasi" class="sidebar__link {{ request()->is('hasil-evaluasi') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="8" y1="17" x2="8" y2="10"></line>
                        <line x1="12" y1="17" x2="12" y2="7"></line>
                        <line x1="16" y1="17" x2="16" y2="13"></line>
                    </svg>
                    Hasil Evaluasi
                </a>
                 <a href="/deteksi-anomali" class="sidebar__link {{ request()->is('deteksi-anomali') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Deteksi Anomali
                </a>
                <a href="/ranking" class="sidebar__link {{ request()->is('ranking') ? 'sidebar__link--active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Ranking
                </a>
                </nav>

            <div class="sidebar__footer">

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar__link" style="color: #792131; width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        <!-- KONTEN KANAN -->
        <main class="dashboard-main">
            
            <!-- TOPBAR -->
            <header class="topbar">
                <!-- JUDUL DINAMIS BERDASARKAN HALAMAN -->
                <h1 class="topbar__title">@yield('page_title', 'Dashboard')</h1>

                <div class="topbar__actions">

                    <div class="user-profile" 
                    onclick="toggleModalProfile(true)" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                        <div class="user-profile__info">
                            <span class="user-profile__name">{{ Auth::user()->name }}</span>
                            <span class="user-profile__role">{{ strtoupper(Auth::user()->role) }}</span>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=792131&color=fff" alt="Avatar" class="user-profile__avatar">
                    </div>
                </div>
            </header>

            <!-- AREA KONTEN (ISI INJEKSI DARI VIEW) -->
            <div class="content-area">
                @yield('content')
            </div>

        </main>
    </div>

</body>
</html>