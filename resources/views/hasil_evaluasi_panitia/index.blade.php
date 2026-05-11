@extends('layouts.panitia')

@section('title', 'Dashboard Panitia')

@section('page_title', 'Dashboard Panitia')

@section('content')
<style>
    /* =========================================
       STYLE MAIN CONTENT: EVALUASI PRIBADI
       ========================================= */
    .lhep-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER SECTION --- */
    .lhep-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .lhep-title-area { max-width: 650px; }
    .lhep-title { font-size: 1.85rem; font-weight: 800; color: var(--text-dark); margin: 0 0 6px 0; letter-spacing: -0.5px; }
    .lhep-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; margin: 0; }
    
    .btn-download {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--primary-color); color: var(--bg-white);
        padding: 10px 20px; border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-download:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3); }

    /* --- 2. KPI CARDS (2 KOLOM) --- */
    .lhep-kpi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media (max-width: 768px) { .lhep-kpi-grid { grid-template-columns: 1fr; } }

    .lhep-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 28px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        transition: transform 0.3s ease; display: flex; flex-direction: column; justify-content: space-between;
    }
    .lhep-kpi-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.08); }

    .kpi-lbl { font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    
    /* Card Kiri: Rata-rata */
    .kpi-score-wrapper { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px; }
    .kpi-score-group { display: flex; align-items: baseline; gap: 8px; }
    .kpi-score { font-size: 3.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    .kpi-scale { font-size: 1.25rem; font-weight: 600; color: var(--text-muted); }
    
    .kpi-badge-wrapper { text-align: right; }
    .kpi-badge-label { font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 4px; display: block;}
    .kpi-badge-predikat { background-color: #dcfce7; color: #166534; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 800; letter-spacing: 0.5px;}
    
    .kpi-trend { font-size: 0.85rem; font-weight: 700; color: #10b981; display: flex; align-items: center; gap: 6px; }

    /* Card Kanan: Peringkat */
    .kpi-rank-group { display: flex; align-items: baseline; gap: 8px; margin-bottom: 12px; }
    .kpi-rank { font-size: 3.5rem; font-weight: 800; color: var(--primary-color); line-height: 1; }
    .kpi-total { font-size: 1.25rem; font-weight: 600; color: var(--text-muted); }
    .kpi-rank-desc { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); background-color: var(--bg-layout); padding: 12px 16px; border-radius: var(--radius-md); border-left: 4px solid var(--primary-color); }

    /* --- 3. SECTION: DETAIL PER KRITERIA --- */
    .sec-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .sec-header { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .sec-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .sec-subtitle { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); background-color: var(--bg-layout); padding: 6px 12px; border-radius: 20px;}

    /* List Layout Grid */
    .crit-list-wrapper { display: flex; flex-direction: column; }
    .crit-list-header {
        display: grid; grid-template-columns: 2fr 1.5fr 80px 120px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .crit-list-row {
        display: grid; grid-template-columns: 2fr 1.5fr 80px 120px; align-items: center;
        padding: 20px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .crit-list-row:hover { background-color: rgba(121, 33, 49, 0.02); }
    .crit-list-row:last-child { border-bottom: none; }

    /* Kriteria Cell */
    .crit-name { font-size: 0.95rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px; }
    .crit-desc { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; }
    
    /* Visual Score (Stars) */
    .crit-stars { display: flex; gap: 4px; color: #facc15; }
    .star-icon { width: 18px; height: 18px; }
    .star-empty { color: #e2e8f0; }

    /* Angka & Kategori */
    .crit-score { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); text-align: center; }
    
    .badge-cat { padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; display: inline-block; text-align: center; width: 100%; letter-spacing: 0.5px;}
    .cat-ex { background-color: #e0e7ff; color: #3730a3; } /* Exemplary - Biru Ungu */
    .cat-sb { background-color: #dcfce7; color: #166534; } /* Sangat Baik - Hijau */

    /* --- 4. SECTION: RANKING PANITIA (LEADERBOARD) --- */
    .rank-list-wrapper { padding: 12px 24px; }
    .rank-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid var(--border-light); border-radius: var(--radius-md);
        margin-bottom: 8px; transition: 0.2s;
    }
    .rank-item:last-child { margin-bottom: 0; border-bottom: none; }
    
    .rank-left { display: flex; align-items: center; gap: 16px; }
    .rank-num { font-size: 1.1rem; font-weight: 800; color: var(--text-muted); width: 28px; }
    .rank-ava { width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: 800; font-size: 0.9rem;}
    .ava-1 { background-color: #f59e0b; } .ava-3 { background-color: #10b981; } .ava-4 { background-color: #3b82f6; }
    
    .rank-name-box { display: flex; flex-direction: column; gap: 2px;}
    .rank-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
    .rank-role { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
    
    .rank-score { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); }

    /* HIGHLIGHT ROW (ANDA) */
    .rank-item.is-me { background-color: var(--primary-color); border: none; box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2); transform: scale(1.01);}
    .is-me .rank-num { color: rgba(255,255,255,0.7); }
    .is-me .rank-name { color: var(--bg-white); }
    .is-me .rank-role { color: rgba(255,255,255,0.8); }
    .is-me .rank-score { color: var(--bg-white); }
    .is-me .rank-ava { background-color: var(--bg-white); color: var(--primary-color); }
    .badge-me { background-color: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; font-weight: 800; margin-left: 8px; }

    .sec-footer { padding: 16px 24px; text-align: center; border-top: 1px solid var(--border-light); background-color: var(--bg-layout); }
    .btn-link { font-size: 0.85rem; font-weight: 800; color: var(--primary-color); text-decoration: none; display: inline-block; transition: 0.2s;}
    .btn-link:hover { transform: translateX(4px); }

    /* Responsif */
    @media (max-width: 900px) {
        .crit-list-header { display: none; }
        .crit-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; }
        .crit-score { text-align: left; }
    }
</style>

<div class="lhep-wrapper">

    <div class="lhep-header">
        <div class="lhep-title-area">
            <h1 class="lhep-title">Laporan Hasil Evaluasi Pribadi</h1>
            <p class="lhep-desc">Analisis performa komprehensif berdasarkan penilaian tim sejawat dan koordinator selama event <strong>Dies Natalis 2026</strong>.</p>
        </div>
        <button class="btn-download">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download PDF
        </button>
    </div>

    <div class="lhep-kpi-grid">
        <div class="lhep-kpi-card">
            <div class="kpi-lbl">RATA-RATA NILAI PERFORMA</div>
            <div class="kpi-score-wrapper">
                <div class="kpi-score-group">
                    <span class="kpi-score">4.82</span>
                    <span class="kpi-scale">/ 5.00</span>
                </div>
                <div class="kpi-badge-wrapper">
                    <span class="kpi-badge-label">Predikat Performa</span>
                    <span class="kpi-badge-predikat">SANGAT BAIK</span>
                </div>
            </div>
            <div class="kpi-trend">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Meningkat 0.15 dari event sebelumnya
            </div>
        </div>

        <div class="lhep-kpi-card">
            <div class="kpi-lbl">PERINGKAT PANITIA</div>
            <div class="kpi-rank-group">
                <span class="kpi-rank">#3</span>
                <span class="kpi-total">/ 45</span>
            </div>
            <div class="kpi-rank-desc">
                ✨ Anda berada di <strong>Top 10%</strong> panitia terbaik dalam event ini.
            </div>
        </div>
    </div>

    <div class="sec-card">
        <div class="sec-header">
            <h2 class="sec-title">Detail Per Kriteria</h2>
            <div class="sec-subtitle">Berdasarkan 12 Responden</div>
        </div>

        <div class="crit-list-wrapper">
            <div class="crit-list-header">
                <div>KRITERIA PENILAIAN</div>
                <div>VISUAL SCORE</div>
                <div style="text-align: center;">RATA-RATA</div>
                <div style="text-align: center;">KATEGORI</div>
            </div>

            <div class="crit-list-row">
                <div>
                    <div class="crit-name">Inisiatif & Kerjasama Tim</div>
                    <div class="crit-desc">Menekankan kemampuan bekerja sama dan proaktif dalam menyelesaikan hambatan kelompok.</div>
                </div>
                <div class="crit-stars">
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div class="crit-score">4.95</div>
                <div><span class="badge-cat cat-ex">EXEMPLARY</span></div>
            </div>

            <div class="crit-list-row">
                <div>
                    <div class="crit-name">Kedisiplinan & Waktu</div>
                    <div class="crit-desc">Ketepatan waktu kehadiran rapat dan kepatuhan terhadap deadline tugas divisi.</div>
                </div>
                <div class="crit-stars">
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div class="crit-score">4.20</div>
                <div><span class="badge-cat cat-sb">SANGAT BAIK</span></div>
            </div>

            <div class="crit-list-row">
                <div>
                    <div class="crit-name">Kualitas Komunikasi</div>
                    <div class="crit-desc">Kejelasan & efektivitas penyampaian informasi antar sesama anggota dan stakeholder.</div>
                </div>
                <div class="crit-stars">
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="star-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div class="crit-score">4.88</div>
                <div><span class="badge-cat cat-ex">EXEMPLARY</span></div>
            </div>
        </div>
    </div>

    <div class="sec-card">
        <div class="sec-header">
            <h2 class="sec-title">Tabel Ranking Panitia dalam Event</h2>
        </div>

        <div class="rank-list-wrapper">
            <div class="rank-item">
                <div class="rank-left">
                    <span class="rank-num">01</span>
                    <div class="rank-ava ava-1">SA</div>
                    <div class="rank-name-box">
                        <span class="rank-name">Siti Aminah</span>
                        <span class="rank-role">Sekretaris Utama</span>
                    </div>
                </div>
                <div class="rank-score">4.92</div>
            </div>

            <div class="rank-item is-me">
                <div class="rank-left">
                    <span class="rank-num">03</span>
                    <div class="rank-ava">AF</div>
                    <div class="rank-name-box">
                        <span class="rank-name">Ahmad Faisal <span class="badge-me">(Anda)</span></span>
                        <span class="rank-role">Koordinator Program</span>
                    </div>
                </div>
                <div class="rank-score">4.82</div>
            </div>

            <div class="rank-item">
                <div class="rank-left">
                    <span class="rank-num">04</span>
                    <div class="rank-ava ava-4">BS</div>
                    <div class="rank-name-box">
                        <span class="rank-name">Budi Santoso</span>
                        <span class="rank-role">Logistik / Perlengkapan</span>
                    </div>
                </div>
                <div class="rank-score">4.75</div>
            </div>
        </div>

        <div class="sec-footer">
            <a href="#" class="btn-link">LIHAT SELURUH PERINGKAT &rarr;</a>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 8px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
    @endsection