@extends('layouts.dashboard')

@section('title', 'Ranking')
@section('page_title', 'Ranking')

@section('content')

    <style>
        .event-page-wrapper {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 32px;
        }

        /* 1. HEADER HALAMAN */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .header-text { max-width: 60%; }
        .header-title { font-size: 1.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 8px; }
        .header-desc { font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; }

        .btn-pill-primary {
            background-color: var(--primary-color);
            color: var(--bg-white);
            padding: 8px 20px 8px 10px; /* Padding kiri lebih kecil untuk mengakomodasi ikon */
            border-radius: 50px; /* Bentuk Pill */
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2);
        }

        .btn-pill-primary:hover { background-color: var(--primary-hover); transform: translateY(-2px); }

        .icon-circle {
            background-color: rgba(255, 255, 255, 0.2);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* 2. RINGKASAN METRIK (STAT CARDS) */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .metric-card {
            background-color: var(--bg-white);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .metric-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .metric-value { font-size: 2.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
        .metric-sub { font-size: 0.8rem; font-weight: 600; color: #16a34a; /* Warna hijau untuk pertumbuhan */ }

        /* 3. SECTION DAFTAR EVENT (TABLE CARD) */
        .table-card {
            background-color: var(--bg-white);
            border-radius: var(--radius-lg);
            padding: 24px 0; /* Padding atas bawah saja, kiri kanan diatur per sel */
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
        }

        .table-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 24px 20px 24px;
            border-bottom: 1px solid var(--border-light);
        }

        .section-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); }
        
        .table-controls { display: flex; gap: 12px; }
        .btn-icon-only {
            background: none; border: 1px solid var(--border-light); border-radius: var(--radius-md);
            width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); cursor: pointer; transition: all 0.2s;
        }
        .btn-icon-only:hover { background-color: var(--bg-layout); color: var(--text-dark); }

        /* 4. MAIN TABLE */
        .event-table { width: 100%; border-collapse: collapse; }
        
        .event-table th {
            text-align: left; padding: 16px 24px; font-size: 0.75rem; font-weight: 700;
            color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-light); background-color: #FAFAFA;
        }

        .event-table td { padding: 16px 24px; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        .event-table tbody tr:hover { background-color: #F8F9FA; }

        /* Info Sel (Nama Acara) */
        .event-info-cell { display: flex; align-items: center; gap: 16px; }
        .event-icon {
            width: 40px; height: 40px; border-radius: var(--radius-md); background-color: #F3F4F6;
            display: flex; align-items: center; justify-content: center; color: var(--text-muted);
        }
        .event-text { display: flex; flex-direction: column; gap: 4px; }
        .event-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
        .event-dept { font-size: 0.8rem; color: var(--text-muted); }

        /* Teks Tanggal */
        .event-date-text { font-size: 0.9rem; font-weight: 600; color: var(--text-dark); }

        /* Badge Status */
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        .badge-active { background-color: #EFF6FF; color: #1D4ED8; } /* Biru */
        .badge-completed { background-color: #F3F4F6; color: #4B5563; } /* Abu-abu */

        /* Aksi (Edit/Delete) */
        .action-group { display: flex; gap: 8px; }
        .btn-action-sm {
            background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md);
            transition: all 0.2s; color: var(--text-muted);
        }
        .btn-action-sm:hover { background-color: #E5E7EB; }
        .btn-delete:hover { background-color: #FEE2E2; color: #DC2626; } /* Hover merah untuk delete */
    </style>

    <div class="event-page-wrapper">

        <header class="page-header">
            <div class="header-text">
                <h1 class="header-title">Ranking</h1>
                <p class="header-desc">Mengawasi dan mengevaluasi kegiatan akademik. Melacak metrik kinerja dan menjaga keunggulan institusi di seluruh departemen.</p>
            </div>
            <button class="btn-pill-primary">
                <div class="icon-circle">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                Tambah Event
            </button>
        </header>

        <div class="metrics-grid">
            <div class="metric-card">
                <span class="metric-label">Jumlah Acara Yang Sedang Berlangsung</span>
                <span class="metric-value">12</span>
                <span class="metric-sub">+2 bulan ini</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Selesai</span>
                <span class="metric-value">148</span>
            </div>
            <div class="metric-card">
                <span class="metric-label">Peringkat Rata-Rata</span>
                <span class="metric-value">4.8</span>
            </div>
        </div>

        <div class="table-card">
            
            <div class="table-header-section">
                <h2 class="section-title">Acara Mendatang dan Terkini</h2>
                <div class="table-controls">
                    <button class="btn-icon-only">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    </button>
                    <button class="btn-icon-only">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </button>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="event-table">
                    <thead>
                        <tr>
                            <th>Nama Acara</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Status Saat Ini</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td>
                                <div class="event-info-cell">
                                    <div class="event-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                    </div>
                                    <div class="event-text">
                                        <span class="event-name">International Symposium 2024</span>
                                        <span class="event-dept">Academic Affairs Department</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="event-date-text">Oct 24 - 26, 2024</span></td>
                            <td><span class="badge-status badge-active">ACTIVE</span></td>
                            <td>
                                <div class="action-group">
                                    <button class="btn-action-sm"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                                    <button class="btn-action-sm btn-delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="event-info-cell">
                                    <div class="event-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                                    </div>
                                    <div class="event-text">
                                        <span class="event-name">University Sports Week</span>
                                        <span class="event-dept">Student Executive Body</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="event-date-text">Sep 12 - 19, 2024</span></td>
                            <td><span class="badge-status badge-completed">COMPLETED</span></td>
                            <td>
                                <div class="action-group">
                                    <button class="btn-action-sm"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                                    <button class="btn-action-sm btn-delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="event-info-cell">
                                    <div class="event-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                    </div>
                                    <div class="event-text">
                                        <span class="event-name">Annual Innovation Fair</span>
                                        <span class="event-dept">Research & Development</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="event-date-text">Nov 05, 2024</span></td>
                            <td><span class="badge-status badge-active">ACTIVE</span></td>
                            <td>
                                <div class="action-group">
                                    <button class="btn-action-sm"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                                    <button class="btn-action-sm btn-delete"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
        
        <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder);">
            &copy; 2026 Sistem Evaluasi Panitia Event Kampus
        </footer>

    </div>

@endsection