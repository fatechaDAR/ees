@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('page_title', 'Dashboard')

@section('content')
    <style>
        .dashboard-grid {
            display: grid;
            /* Auto-fit akan membuat card otomatis turun ke bawah jika layar sempit */
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 8px;
        }

        .stat-card {
            background-color: var(--bg-white);
            padding: 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px); /* Efek melayang saat di-hover */
        }

        .stat-card__icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            /* Menggunakan warna utama dengan transparansi untuk background icon */
            background-color: rgba(121, 33, 49, 0.1); 
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card__info {
            display: flex;
            flex-direction: column;
        }

        .stat-card__title {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .stat-card__value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-card__trend {
            font-size: 0.55rem; /* Ukuran teks lebih kecil */
            color: #2563EB; /* Warna biru standar UI modern */
            font-weight: 300;
            margin-top: 3px; /* Memberi jarak sedikit dari angka besar */
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* =========================================
           STYLE TABEL EVALUASI
           ========================================= */
        .table-container {
            background-color: var(--bg-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
            margin-top: 32px; /* Jarak antara Card dan Tabel */
            padding: 24px;
        }

        .table-header {
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            background-color: var(--bg-layout);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 12px 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-light);
        }

        .custom-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        /* Efek hover tiap baris agar interaktif */
        .custom-table tbody tr:hover {
            background-color: rgba(121, 33, 49, 0.02); /* Sedikit hint warna primary-mu */
        }

        /* Desain Status Badge (Label Hijau/Kuning) */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge--success { background-color: #dcfce7; color: #166534; }
        .badge--warning { background-color: #fef08a; color: #854d0e; }
        
        .btn-link {
            color: var(--primary-color);
            font-weight: 600;
        }
        .btn-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* =========================================
           STYLE MANAJEMEN EVENT (LIST VIEW)
           ========================================= */
        .event-section {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Header & Button */
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--bg-white);
            padding: 10px 16px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        /* Filter Area */
        .event-filters {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .filter-input, .filter-select {
            padding: 10px 14px;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--text-dark);
            background-color: var(--bg-white);
            outline: none;
        }
        
        .filter-input { flex: 1; max-width: 300px; } /* Kolom search lebih panjang */

        /* Event List & Card */
        .event-list {
            display: flex;
            flex-direction: column;
            gap: 12px; /* Jarak antar baris event */
        }

        .event-card {
            background-color: var(--bg-white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: border-color 0.2s;
        }

        .event-card:hover {
            border-color: var(--primary-color);
        }

        .event-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .event-info__header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .event-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge--active { background-color: #dcfce7; color: #166534; } /* Hijau */
        .badge--done { background-color: #f3f4f6; color: #4b5563; } /* Abu-abu */
        .badge--type { background-color: #e0e7ff; color: #3730a3; } /* Biru Pucat */

        .event-date {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Action Button (Titik 3) */
        .btn-action {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: var(--radius-md);
        }
        
        .btn-action:hover {
            background-color: var(--bg-layout);
            color: var(--text-dark);
        }


        /*Revisi bentar ----------------------------------------------------------------------*/
        /* 1. LAYOUT UTAMA DASHBOARD */
        .dashboard-content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 24px; /* Jarak antar Row */
            padding-top: 8px;
            padding-bottom: 32px;
        }

        /* 2. CARD GLOBAL STYLE */
        .box-card {
            background-color: var(--bg-white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--card-shadow);
        }

        .box-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .box-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); }
        .box-subtitle { font-size: 0.85rem; color: var(--text-muted); margin-top: 4px; }

        /* 3. ROW 1: GRID 2 KOLOM (Kiri 65%, Kanan 35%) */
        .row-1 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        /* 3A. CHART AREA (MOCKUP SVG) */
        .target-indicator {
            display: flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 600; color: var(--text-muted);
        }
        .dot-red { width: 8px; height: 8px; border-radius: 50%; background-color: #dc2626; }
        
        .chart-placeholder {
            width: 100%; height: 220px; margin-top: 20px;
            border-bottom: 1px solid var(--border-light);
            border-left: 1px solid var(--border-light);
            position: relative;
            background-image: linear-gradient(to bottom, var(--border-light) 1px, transparent 1px);
            background-size: 100% 40px; /* Garis bantu horizontal */
        }
        
        .x-axis-labels {
            display: flex; justify-content: space-between; margin-top: 8px;
            font-size: 0.75rem; color: var(--text-muted); font-weight: 600;
        }

        /* 3B. LEADERBOARD TOP 5 */
        .leaderboard-list { display: flex; flex-direction: column; gap: 16px; margin-top: 20px; }
        .leaderboard-item { display: flex; align-items: center; justify-content: space-between; }
        .leaderboard-left { display: flex; align-items: center; gap: 12px; }
        
        .rank-number { font-size: 0.9rem; font-weight: 800; color: var(--text-muted); width: 16px;}
        .avatar-sm { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-size: 0.9rem; font-weight: 700; color: var(--text-dark); }
        .user-role { font-size: 0.75rem; color: var(--text-muted); }
        
        .score-info { text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
        .score-number { font-size: 1rem; font-weight: 800; color: var(--primary-color); }
        
        .badge-rank { font-size: 0.6rem; padding: 2px 6px; border-radius: 4px; font-weight: 800; }
        .badge-gold { background-color: #fef08a; color: #854d0e; }
        .badge-blue { background-color: #e0e7ff; color: #3730a3; }
        
        .link-cta {
            display: block; text-align: center; margin-top: 24px; font-size: 0.85rem; font-weight: 700;
            color: var(--primary-color);
        }

        /* 4. ROW 2: EVENT AKTIF (GRID 3 KOLOM) */
        .row-2-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .row-2-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); }
        
        .events-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        
        .event-card {
            background-color: var(--bg-white); border: 1px solid var(--border-light);
            border-radius: var(--radius-lg); padding: 20px;
        }

        .event-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .status-chip { font-size: 0.65rem; padding: 4px 8px; border-radius: 20px; font-weight: 800; letter-spacing: 0.5px; }
        .chip-ongoing { background-color: #fee2e2; color: #991b1b; } /* Merah */
        .chip-planning { background-color: #f3f4f6; color: #374151; } /* Abu */
        .chip-wrapping { background-color: #dbeafe; color: #1e3a8a; } /* Biru Tua */

        .event-title { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 4px; }
        .event-desc { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 16px; }

        .progress-label { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; margin-bottom: 6px; }
        .progress-track { width: 100%; height: 8px; background-color: var(--border-light); border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; background-color: var(--primary-color); border-radius: 4px; }

        /* 5. FOOTER */
        .dashboard-footer { text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); }
    </style>

    <div class="dashboard-grid">
        
        <div class="stat-card">
            <div class="stat-card__icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div class="stat-card__info">
                <span class="stat-card__title">Total Event</span>
                <span class="stat-card__value">12</span> </div>

                <span class="stat-card__trend">
                    +2 Bulan Ini
                </span>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-card__info">
                <span class="stat-card__title">Total Panitia</span>
                <span class="stat-card__value">145</span> </div>

                <span class="stat-card__trend">
                    Terdaftar Aktif
                </span>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div class="stat-card__info">
                <span class="stat-card__title">Event Aktif</span>
                <span class="stat-card__value">2</span> </div>

                <span class="stat-card__trend">
                    Sedang Berjalan
                </span>
        </div>

    </div>

<!--     /*Revisi Bentar-------------------------------------------------------------------------------------*/ -->

    <div class="dashboard-content-wrapper">

        <div class="row-1">
            
            <div class="box-card">
                <div class="box-header">
                    <div>
                        <h2 class="box-title">Tren Performa Panitia</h2>
                        <p class="box-subtitle">Rata-rata skor evaluasi 6 bulan terakhir</p>
                    </div>
                    <div class="target-indicator">
                        <div class="dot-red"></div> Target: 85.0
                    </div>
                </div>
                
                <div class="chart-placeholder">
                    <svg viewBox="0 0 500 220" style="width: 100%; height: 100%; overflow: visible;">
                        <polyline fill="none" stroke="var(--primary-color)" stroke-width="4" 
                            points="0,180 100,140 200,160 300,90 400,110 500,40" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="500" cy="40" r="6" fill="var(--primary-color)" />
                    </svg>
                </div>
                <div class="x-axis-labels">
                    <span>JUL</span><span>AGU</span><span>SEP</span><span>OKT</span><span>NOV</span><span>DES</span>
                </div>
            </div>

            <div class="box-card">
                <h2 class="box-title">Top 5 Panitia</h2>
                
                <div class="leaderboard-list">
                    <div class="leaderboard-item">
                        <div class="leaderboard-left">
                            <span class="rank-number">1</span>
                            <img src="https://ui-avatars.com/api/?name=Budi&background=random" class="avatar-sm">
                            <div class="user-info">
                                <span class="user-name">Budi Pratama</span>
                                <span class="user-role">Acara & Kreatif</span>
                            </div>
                        </div>
                        <div class="score-info">
                            <span class="badge-rank badge-gold">GOLD STAR</span>
                            <span class="score-number">98.5</span>
                        </div>
                    </div>

                    <div class="leaderboard-item">
                        <div class="leaderboard-left">
                            <span class="rank-number">2</span>
                            <img src="https://ui-avatars.com/api/?name=Siti&background=random" class="avatar-sm">
                            <div class="user-info">
                                <span class="user-name">Siti Aisyah</span>
                                <span class="user-role">Perlengkapan</span>
                            </div>
                        </div>
                        <div class="score-info">
                            <span class="badge-rank badge-blue">EXCELLENT</span>
                            <span class="score-number">96.2</span>
                        </div>
                    </div>

                    <div class="leaderboard-item">
                        <div class="leaderboard-left">
                            <span class="rank-number">3</span>
                            <img src="https://ui-avatars.com/api/?name=Andi&background=random" class="avatar-sm">
                            <div class="user-info">
                                <span class="user-name">Andi Wijaya</span>
                                <span class="user-role">Bendahara</span>
                            </div>
                        </div>
                        <div class="score-info">
                            <span class="badge-rank badge-blue">CONSISTENT</span>
                            <span class="score-number">95.0</span>
                        </div>
                    </div>
                    
                    <div class="leaderboard-item">
                        <div class="leaderboard-left">
                            <span class="rank-number">4</span>
                            <img src="https://ui-avatars.com/api/?name=Riko&background=random" class="avatar-sm">
                            <div class="user-info">
                                <span class="user-name">Riko Sanjaya</span>
                                <span class="user-role">Keamanan</span>
                            </div>
                        </div>
                        <div class="score-info"><span class="score-number">93.8</span></div>
                    </div>

                    <div class="leaderboard-item">
                        <div class="leaderboard-left">
                            <span class="rank-number">5</span>
                            <img src="https://ui-avatars.com/api/?name=Dina&background=random" class="avatar-sm">
                            <div class="user-info">
                                <span class="user-name">Dina Lestari</span>
                                <span class="user-role">Sekretariat</span>
                            </div>
                        </div>
                        <div class="score-info"><span class="score-number">91.4</span></div>
                    </div>
                </div>

                <a href="#" class="link-cta">LIHAT SELURUH RANKING</a>
            </div>

        </div> <div class="row-2">
            <div class="row-2-header">
                <h2 class="row-2-title">Event Aktif & Progres Evaluasi</h2>
                <a href="#" style="font-size: 0.85rem; font-weight: 600; color: var(--primary-color);">Lihat Semua</a>
            </div>

            <div class="events-grid">
                
                <div class="event-card">
                    <div class="event-card-header">
                        <span class="status-chip chip-ongoing">ON-GOING</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </div>
                    <h3 class="event-title">Dies Natalis 64</h3>
                    <p class="event-desc">Evaluasi kinerja seluruh divisi dan kepanitiaan inti selama acara berlangsung.</p>
                    
                    <div class="progress-section">
                        <div class="progress-label">
                            <span style="color: var(--text-muted)">Progress Evaluasi</span>
                            <span style="color: var(--primary-color)">65%</span>
                        </div>
                        <div class="progress-track"><div class="progress-fill" style="width: 65%;"></div></div>
                    </div>
                </div>

                <div class="event-card">
                    <div class="event-card-header">
                        <span class="status-chip chip-planning">PLANNING</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </div>
                    <h3 class="event-title">Seminar Nasional AI</h3>
                    <p class="event-desc">Penetapan kriteria penilaian untuk pembicara dan panitia operasional.</p>
                    
                    <div class="progress-section">
                        <div class="progress-label">
                            <span style="color: var(--text-muted)">Progress Evaluasi</span>
                            <span style="color: var(--primary-color)">12%</span>
                        </div>
                        <div class="progress-track"><div class="progress-fill" style="width: 12%;"></div></div>
                    </div>
                </div>

                <div class="event-card">
                    <div class="event-card-header">
                        <span class="status-chip chip-wrapping">WRAPPING UP</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </div>
                    <h3 class="event-title">Lomba Karya Tulis</h3>
                    <p class="event-desc">Finalisasi laporan penilaian peserta dan rekapan absensi panitia juri.</p>
                    
                    <div class="progress-section">
                        <div class="progress-label">
                            <span style="color: var(--text-muted)">Progress Evaluasi</span>
                            <span style="color: var(--primary-color)">94%</span>
                        </div>
                        <div class="progress-track"><div class="progress-fill" style="width: 94%;"></div></div>
                    </div>
                </div>

            </div>
        

    </div>

<!--     /*Revisi Bentar-------------------------------------------------------------------------------------*/ -->

    
    <div class="event-section">
        
        <div class="event-header">
            <h2 class="event-title">Manajemen Event</h2>
            <button class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Event
            </button>
        </div>

        <div class="event-filters">
            <input type="text" class="filter-input" placeholder="Cari Event...">
            <select class="filter-select">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="selesai">Selesai</option>
            </select>
            <select class="filter-select">
                <option value="">Semua Tipe</option>
                <option value="reguler">Reguler</option>
                <option value="khusus">Khusus</option>
            </select>
        </div>

        <div class="event-list">
            
            <div class="event-card">
                <div class="event-info">
                    <div class="event-info__header">
                        <span class="event-name">Evaluasi Tahunan 2023</span>
                        <span class="badge badge--active">Aktif</span>
                        <span class="badge badge--type">Reguler</span>
                    </div>
                    <div class="event-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        1 Jan 2023 - 31 Des 2023
                    </div>
                </div>
                <button class="btn-action">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                </button>
            </div>

            <div class="event-card">
                <div class="event-info">
                    <div class="event-info__header">
                        <span class="event-name">Evaluasi Proyek Alpha</span>
                        <span class="badge badge--done">Selesai</span>
                        <span class="badge badge--type">Khusus</span>
                    </div>
                    <div class="event-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        1 Jun 2023 - 30 Jun 2023
                    </div>
                </div>
                <button class="btn-action">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                </button>
            </div>

            </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Daftar Evaluasi Terbaru</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Event Evaluasi</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><strong>Evaluasi Kinerja Dosen Semester Ganjil</strong></td>
                        <td>12 Apr 2026</td>
                        <td><span class="badge badge--success">Selesai</span></td>
                        <td><a href="#" class="btn-link">Lihat Detail</a></td>
                    </tr>
                    
                    <tr>
                        <td>2</td>
                        <td><strong>Evaluasi Layanan Fasilitas Kampus</strong></td>
                        <td>15 Apr 2026</td>
                        <td><span class="badge badge--warning">Berjalan</span></td>
                        <td><a href="#" class="btn-link">Lihat Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        </div>
        <footer class="dashboard-footer">
            &copy; 2026 Sistem Evaluasi Panitia Event Kampus
        </footer>

    </div>

    @endsection