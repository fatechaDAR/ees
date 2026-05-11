@extends('layouts.dashboard')

@section('title', 'Monitoring Evaluasi')
@section('page_title', 'Monitoring Evaluasi')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: MONITORING EVALUASI
       ========================================= */
    .me-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER SECTION --- */
    .me-header { margin-bottom: 8px; }
    .me-label {
        font-size: 0.7rem; font-weight: 800; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
    }
    .me-title { font-size: 2rem; font-weight: 800; color: var(--primary-color); margin: 0; letter-spacing: -0.5px; }

    /* --- 2. KPI KIRI & FILTER KANAN --- */
    .me-top-bar { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; }
    
    .me-kpi-left { display: flex; gap: 16px; }
    .kpi-mini {
        background-color: var(--bg-white); border-radius: var(--radius-md);
        padding: 12px 20px; box-shadow: var(--card-shadow);
        border: 1px solid var(--border-light);
        border-left: 4px solid var(--primary-color); /* Aksen garis sisi kiri */
        display: flex; flex-direction: column; gap: 4px;
        min-width: 140px;
    }
    .kpi-mini-lbl { font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .kpi-mini-val { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }

    .me-filters { display: flex; gap: 12px; align-items: center; }
    .filter-select {
        padding: 10px 32px 10px 16px; border: 1px solid var(--border-light); border-radius: 20px;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text-dark);
        background-color: var(--bg-white); appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.2s;
    }
    .filter-select:hover { border-color: var(--primary-color); }
    .btn-icon-filter {
        width: 38px; height: 38px; border-radius: 50%; background-color: var(--bg-white);
        border: 1px solid var(--border-light); display: flex; justify-content: center; align-items: center;
        color: var(--text-dark); cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .btn-icon-filter:hover { background-color: var(--bg-layout); color: var(--primary-color); border-color: var(--primary-color); }

    /* --- 3. TABEL EVALUASI --- */
    .me-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    
    .me-list-wrapper { display: flex; flex-direction: column; }
    /* Grid 6 Kolom */
    .me-list-header {
        display: grid; grid-template-columns: 2.2fr 1.5fr 1.8fr 80px 2fr 90px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .me-list-row {
        display: grid; grid-template-columns: 2.2fr 1.5fr 1.8fr 80px 2fr 90px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .me-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .me-list-row:last-child { border-bottom: none; }

    /* Data Cell Styles */
    .col-user { display: flex; align-items: center; gap: 16px; }
    .user-ava {
        width: 36px; height: 36px; border-radius: 50%; color: white;
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;
    }
    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .user-email { font-size: 0.75rem; color: var(--text-muted); }

    .divisi-pill {
        padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800;
        background-color: var(--bg-layout); color: var(--text-dark); border: 1px solid var(--border-light);
    }
    
    .col-evaluator { font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }
    
    .score-badge {
        width: 32px; height: 32px; border-radius: 50%; display: flex; justify-content: center; align-items: center;
        font-size: 0.9rem; font-weight: 800; color: var(--bg-white); box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .score-high { background-color: #3b82f6; } /* Skor 4-5 Biru */
    .score-low { background-color: #ef4444; } /* Skor 1-2 Merah */

    .col-komentar {
        font-size: 0.8rem; color: var(--text-muted); font-style: italic;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;
    }

    .col-actions { display: flex; gap: 8px; justify-content: flex-end;}
    .btn-act { background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md); transition: 0.2s; color: var(--text-muted); }
    .btn-act:hover { background-color: var(--bg-layout); color: var(--primary-color); }
    .btn-alert { color: #ef4444; }
    .btn-alert:hover { background-color: #fef2f2; }

    /* Footer Pagination */
    .me-pagination {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-info { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .page-controls { display: flex; gap: 6px; align-items: center;}
    .page-btn {
        width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
        border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 700; color: var(--text-dark);
        cursor: pointer; transition: 0.2s; border: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); border-color: var(--primary-color);}
    .page-btn.active { background-color: var(--primary-color); color: var(--bg-white); border-color: var(--primary-color); }
    .page-dots { color: var(--text-muted); font-weight: 700; padding: 0 4px; }

    /* --- 6. INSIGHT CARDS (BAGIAN BAWAH) --- */
    .insight-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 8px;}
    .insight-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        display: flex; align-items: center; justify-content: space-between;
        transition: transform 0.3s; cursor: default;
    }
    .insight-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    /* Insight 1: Maroon (Prioritas Utama) */
    .insight-dark { background-color: var(--primary-color); color: var(--bg-white); border: none; }
    .insight-dark .ins-val { color: var(--bg-white); }
    .insight-dark .ins-lbl { color: rgba(255,255,255,0.8); }
    .btn-detail {
        background-color: var(--bg-white); color: var(--primary-color); border: none;
        padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800;
        cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .btn-detail:hover { background-color: var(--bg-layout); transform: scale(1.05); }

    .ins-left { display: flex; flex-direction: column; gap: 4px; }
    .ins-lbl { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .ins-val { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    .ins-sub { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); }
    .ins-sub-alert { color: #ef4444; display: flex; align-items: center; gap: 4px; }
    .dot-alert { width: 8px; height: 8px; background-color: #ef4444; border-radius: 50%; }

    .ins-icon-box {
        width: 48px; height: 48px; border-radius: 50%; background-color: var(--bg-layout);
        display: flex; justify-content: center; align-items: center; color: var(--text-dark);
    }

    /* Responsif */
    @media (max-width: 1024px) {
        .me-list-header { display: none; }
        .me-list-row { grid-template-columns: 1fr; gap: 10px; padding: 20px; position: relative; }
        .col-actions { justify-content: flex-start; margin-top: 8px;}
        .col-komentar { white-space: normal; } /* Teks komentar tidak dipotong di mobile */
    }
</style>

<div class="me-wrapper">

    <div class="me-header">
        <div class="me-label">PRESTASI DAN EVALUASI AKADEMIK</div>
        <h1 class="me-title">Monitoring Evaluasi</h1>
    </div>

    <div class="me-top-bar">
        <div class="me-kpi-left">
            <div class="kpi-mini">
                <div class="kpi-mini-lbl">TOTAL ENTRIES</div>
                <div class="kpi-mini-val">1,284</div>
            </div>
            <div class="kpi-mini">
                <div class="kpi-mini-lbl">AVG SCORE</div>
                <div class="kpi-mini-val">4.2</div>
            </div>
        </div>
        <div class="me-filters">
            <select class="filter-select">
                <option>Dies Natalis 2024</option>
                <option>Wisuda Angkatan 60</option>
            </select>
            <select class="filter-select">
                <option>Semua Divisi</option>
                <option>Acara</option>
                <option>Humas</option>
            </select>
            <button class="btn-icon-filter" title="Filter Lanjutan">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </button>
        </div>
    </div>

    <div class="me-table-card">
        <div class="me-list-wrapper">
            <div class="me-list-header">
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>EVALUATOR</div>
                <div>NILAI</div>
                <div>KOMENTAR</div>
                <div style="text-align: right;">AKSI</div>
            </div>

            <div class="me-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: #3b82f6;">AD</div>
                    <div>
                        <div class="user-name">Arya Dimas</div>
                        <div class="user-email">arya.dimas@student.univ.ac.id</div>
                    </div>
                </div>
                <div><span class="divisi-pill">ACARA</span></div>
                <div class="col-evaluator">Dr. Hendra Wijaya</div>
                <div><div class="score-badge score-high">5</div></div>
                <div class="col-komentar">"Kontribusi luar biasa dalam koordinasi talent..."</div>
                <div class="col-actions">
                    <button class="btn-act" title="View Detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="me-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: #10b981;">BP</div>
                    <div>
                        <div class="user-name">Bambang Pamungkas</div>
                        <div class="user-email">bambang.p@student.univ.ac.id</div>
                    </div>
                </div>
                <div><span class="divisi-pill">PERLENGKAPAN</span></div>
                <div class="col-evaluator">Siska Maharani, M.T.</div>
                <div><div class="score-badge score-high">4</div></div>
                <div class="col-komentar">"Sangat proaktif dalam mempersiapkan layout..."</div>
                <div class="col-actions">
                    <button class="btn-act" title="View Detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="me-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: #8b5cf6;">CK</div>
                    <div>
                        <div class="user-name">Citra Kirana</div>
                        <div class="user-email">citra.k@student.univ.ac.id</div>
                    </div>
                </div>
                <div><span class="divisi-pill">HUMAS</span></div>
                <div class="col-evaluator">Dr. Hendra Wijaya</div>
                <div><div class="score-badge score-low">2</div></div>
                <div class="col-komentar">"Sering terlambat dalam merespon email mitra..."</div>
                <div class="col-actions">
                    <button class="btn-act btn-alert" title="Issue Detected">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </button>
                    <button class="btn-act" title="View Detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="me-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: #f59e0b;">DA</div>
                    <div>
                        <div class="user-name">Doni Akbari</div>
                        <div class="user-email">doni.a@student.univ.ac.id</div>
                    </div>
                </div>
                <div><span class="divisi-pill">KONSUMSI</span></div>
                <div class="col-evaluator">Lutfi Hakim, M.Si.</div>
                <div><div class="score-badge score-high">4</div></div>
                <div class="col-komentar">"Manajemen vendor katering sangat tersusun rapi..."</div>
                <div class="col-actions">
                    <button class="btn-act" title="View Detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="me-pagination">
            <div class="page-info">Menampilkan 1-10 dari 1,284 evaluasi</div>
            <div class="page-controls">
                <button class="page-btn" title="Previous"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <span class="page-dots">...</span>
                <button class="page-btn">128</button>
                <button class="page-btn" title="Next"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
        </div>
    </div>

    <div class="insight-grid">
        <div class="insight-card insight-dark">
            <div class="ins-left">
                <div class="ins-lbl">EVALUASI BELUM SELESAI</div>
                <div class="ins-val">42</div>
            </div>
            <button class="btn-detail">LIHAT DETAIL</button>
        </div>
        
        <div class="insight-card">
            <div class="ins-left">
                <div class="ins-lbl">DIVISI TERBAIK</div>
                <div class="ins-val" style="color: var(--primary-color);">Acara</div>
                <div class="ins-sub">AVG SCORE 4.8</div>
            </div>
            <div class="ins-icon-box">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>

        <div class="insight-card">
            <div class="ins-left">
                <div class="ins-lbl">ANOMALI TERDETEKSI</div>
                <div class="ins-val">12</div>
                <div class="ins-sub ins-sub-alert">
                    <div class="dot-alert"></div>
                    MEMBUTUHKAN TINJAUAN
                </div>
            </div>
            <div class="ins-icon-box" style="background-color: #fef2f2; color: #ef4444;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection