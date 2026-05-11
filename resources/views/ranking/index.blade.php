@extends('layouts.dashboard')

@section('title', 'Ranking')
@section('page_title', 'Ranking')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: RANKING PANITIA
       ========================================= */
    .rp-wrapper {
        display: flex; flex-direction: column; gap: 32px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER SECTION --- */
    .rp-header { margin-bottom: -8px; }
    .rp-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; letter-spacing: -0.5px; }
    .rp-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; max-width: 700px; margin: 0;}

    /* --- 2. PODIUM HIGHLIGHT (TOP 3) --- */
    /* Grid diatur sejajar bawah (align-items: end) agar card tengah bisa lebih tinggi */
    .podium-grid {
        display: grid; grid-template-columns: 1fr 1.2fr 1fr; gap: 24px;
        align-items: end; margin-top: 16px;
    }
    .podium-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        padding: 32px 20px; text-align: center; border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow); transition: transform 0.3s ease;
        display: flex; flex-direction: column; align-items: center;
    }
    .podium-card:hover { transform: translateY(-6px); box-shadow: 0 12px 20px -5px rgba(0,0,0,0.08); }
    
    /* Center Card (Top Performer) */
    .podium-card.maroon {
        background: linear-gradient(145deg, var(--primary-color) 0%, #5a1824 100%);
        color: var(--bg-white); border: none; padding: 48px 20px 36px 20px;
        box-shadow: 0 15px 30px -5px rgba(121, 33, 49, 0.4); z-index: 2;
    }
    .podium-card.maroon:hover { transform: translateY(-8px); box-shadow: 0 20px 35px -5px rgba(121, 33, 49, 0.5); }

    .podium-icon { color: #facc15; margin-bottom: -15px; z-index: 3; position: relative; width: 40px; height: 40px; background: var(--bg-white); border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
    
    .podium-ava {
        width: 80px; height: 80px; border-radius: 50%; font-size: 1.5rem; font-weight: 800;
        display: flex; justify-content: center; align-items: center; color: var(--bg-white);
        margin-bottom: 16px; border: 4px solid var(--bg-layout); box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .maroon .podium-ava { border-color: rgba(255,255,255,0.2); width: 96px; height: 96px; font-size: 1.8rem; }
    
    .ava-sw { background-color: #3b82f6; }
    .ava-bs { background-color: var(--text-dark); color: #facc15; } /* Kontras untuk juara 1 */
    .ava-nk { background-color: #f59e0b; }

    .podium-name { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; }
    .maroon .podium-name { color: var(--bg-white); font-size: 1.3rem;}
    
    .podium-divisi { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 16px; }
    .maroon .podium-divisi { color: rgba(255,255,255,0.8); }

    .podium-score { font-size: 2.5rem; font-weight: 800; color: var(--primary-color); line-height: 1; margin-bottom: 12px; }
    .maroon .podium-score { color: var(--bg-white); font-size: 3.2rem; }

    .badge-rank { padding: 6px 16px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; }
    .badge-mod { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); }
    .badge-top { background-color: #fef08a; color: #854d0e; box-shadow: 0 4px 10px rgba(254, 240, 138, 0.2); }

    /* --- 3. TABEL RANKING LENGKAP --- */
    .rp-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .rp-table-head {
        padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light);
        flex-wrap: wrap; gap: 16px;
    }
    .rp-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    
    .rp-tools { display: flex; gap: 12px; align-items: center; }
    .filter-select {
        padding: 8px 32px 8px 16px; border: 1px solid var(--border-light); border-radius: 20px;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text-dark);
        background-color: var(--bg-white); appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; transition: 0.2s;
    }
    .btn-export {
        display: flex; align-items: center; gap: 8px; background-color: var(--bg-layout); color: var(--text-dark);
        border: 1px solid var(--border-light); padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-export:hover { border-color: var(--primary-color); color: var(--primary-color); background-color: var(--bg-white); }

    /* Grid Table Layout */
    .rp-list-wrapper { display: flex; flex-direction: column; }
    .rp-list-header {
        display: grid; grid-template-columns: 60px 2fr 1.5fr 1.5fr 100px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .rp-list-row {
        display: grid; grid-template-columns: 60px 2fr 1.5fr 1.5fr 100px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .rp-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .rp-list-row:last-child { border-bottom: none; }

    /* Cell Styles */
    .col-rank { font-size: 1rem; font-weight: 800; color: var(--text-muted); }
    
    .col-user { display: flex; align-items: center; gap: 12px; }
    .user-ava-sm {
        width: 32px; height: 32px; border-radius: 50%; color: white; background-color: var(--text-placeholder);
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.75rem;
    }
    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
    
    .col-div { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }
    
    /* Progress Bar (Stabilitas Kerja) */
    .stab-container { display: flex; align-items: center; gap: 10px; width: 100%; }
    .stab-track { flex-grow: 1; height: 6px; background-color: var(--border-light); border-radius: 4px; overflow: hidden; }
    .stab-fill { height: 100%; background-color: var(--primary-color); border-radius: 4px; }
    
    .col-score { font-size: 1.1rem; font-weight: 800; color: var(--primary-color); text-align: right; }

    /* Footer Load More */
    .rp-table-footer { padding: 20px; text-align: center; border-top: 1px solid var(--border-light); background-color: var(--bg-white); }
    .btn-load {
        background: none; border: none; font-size: 0.85rem; font-weight: 700; color: var(--primary-color);
        cursor: pointer; transition: 0.2s; padding: 8px 16px; border-radius: 20px;
    }
    .btn-load:hover { background-color: rgba(121, 33, 49, 0.05); }

    /* --- 4. INSIGHT CARDS (BAGIAN BAWAH) --- */
    .rp-insight-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 24px; }
    .ins-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        transition: transform 0.3s; display: flex; flex-direction: column; justify-content: center;
    }
    .ins-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    /* Left Card (Maroon Trend) */
    .ins-card.maroon { background-color: var(--primary-color); color: var(--bg-white); border: none; }
    .maroon .ins-label { color: rgba(255,255,255,0.8); }
    .maroon .ins-val { color: var(--bg-white); font-size: 2.2rem; }
    
    .ins-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .ins-val { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1.2; margin-bottom: 4px; }
    .ins-sub { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .maroon .ins-sub { color: #dcfce7; } /* Hijau terang untuk growth positif */

    /* Responsif */
    @media (max-width: 1024px) {
        .podium-grid { grid-template-columns: 1fr; align-items: stretch; }
        .podium-card.maroon { padding: 32px 20px; transform: none; box-shadow: var(--card-shadow); z-index: 1;}
        .podium-card.maroon:hover { transform: translateY(-6px); }
        .rp-insight-grid { grid-template-columns: 1fr; }
        .rp-list-header { display: none; }
        .rp-list-row { grid-template-columns: 40px 1fr 60px; gap: 12px; padding: 20px; position: relative; }
        .col-div, .stab-container { display: none; } /* Sembunyikan bbrp kolom di HP */
    }
</style>

<div class="rp-wrapper">

    <div class="rp-header">
        <h1 class="rp-title">Ranking Panitia</h1>
        <p class="rp-desc">Leaderboard performa panitia pada periode <strong>Festival Akbar Tahunan 2024</strong>. Pembaruan peringkat dikalkulasi berdasarkan ulasan sejawat dan metrik kinerja harian.</p>
    </div>

    <div class="podium-grid">
        <div class="podium-card">
            <div class="podium-ava ava-sw">SW</div>
            <h3 class="podium-name">Sarah Wijaya</h3>
            <div class="podium-divisi">Divisi Hubungan Masyarakat</div>
            <div class="podium-score">94.8</div>
            <div class="badge-rank badge-mod">Skor Moderate</div>
        </div>

        <div class="podium-card maroon">
            <div class="podium-icon">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"></path></svg>
            </div>
            <div class="podium-ava ava-bs">BS</div>
            <h3 class="podium-name">Budi Santoso</h3>
            <div class="podium-divisi">Divisi Operasional</div>
            <div class="podium-score">98.2</div>
            <div class="badge-rank badge-top">Top Performer</div>
        </div>

        <div class="podium-card">
            <div class="podium-ava ava-nk">NK</div>
            <h3 class="podium-name">Nina Kurnia</h3>
            <div class="podium-divisi">Divisi Konsumsi</div>
            <div class="podium-score">91.5</div>
            <div class="badge-rank badge-mod">Skor Moderate</div>
        </div>
    </div>

    <div class="rp-table-card">
        <div class="rp-table-head">
            <h2 class="rp-table-title">Full Committee Standing</h2>
            <div class="rp-tools">
                <select class="filter-select">
                    <option>Semua Divisi</option>
                    <option>Operasional</option>
                    <option>Humas</option>
                    <option>Logistik</option>
                </select>
                <button class="btn-export">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Report
                </button>
            </div>
        </div>

        <div class="rp-list-wrapper">
            <div class="rp-list-header">
                <div># RANK</div>
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>STABILITAS KERJA</div>
                <div style="text-align: right;">NILAI AKHIR</div>
            </div>

            <div class="rp-list-row">
                <div class="col-rank">#4</div>
                <div class="col-user">
                    <div class="user-ava-sm" style="background-color: #8b5cf6;">AP</div>
                    <div class="user-name">Andi Pratama</div>
                </div>
                <div class="col-div">Divisi Logistik</div>
                <div class="stab-container">
                    <div class="stab-track"><div class="stab-fill" style="width: 89%;"></div></div>
                </div>
                <div class="col-score">89.4</div>
            </div>

            <div class="rp-list-row">
                <div class="col-rank">#5</div>
                <div class="col-user">
                    <div class="user-ava-sm" style="background-color: #ec4899;">SA</div>
                    <div class="user-name">Siska Amelia</div>
                </div>
                <div class="col-div">Divisi Acara</div>
                <div class="stab-container">
                    <div class="stab-track"><div class="stab-fill" style="width: 88%;"></div></div>
                </div>
                <div class="col-score">88.2</div>
            </div>

            <div class="rp-list-row">
                <div class="col-rank">#6</div>
                <div class="col-user">
                    <div class="user-ava-sm" style="background-color: #10b981;">RH</div>
                    <div class="user-name">Rian Hidayat</div>
                </div>
                <div class="col-div">Divisi Perlengkapan</div>
                <div class="stab-container">
                    <div class="stab-track"><div class="stab-fill" style="width: 85%;"></div></div>
                </div>
                <div class="col-score">85.9</div>
            </div>

            <div class="rp-list-row">
                <div class="col-rank">#7</div>
                <div class="col-user">
                    <div class="user-ava-sm" style="background-color: #f59e0b;">EP</div>
                    <div class="user-name">Eka Putri</div>
                </div>
                <div class="col-div">Divisi Dokumentasi</div>
                <div class="stab-container">
                    <div class="stab-track"><div class="stab-fill" style="width: 84%;"></div></div>
                </div>
                <div class="col-score">84.1</div>
            </div>
        </div>

        <div class="rp-table-footer">
            <button class="btn-load">Load all list (120 panitia) &darr;</button>
        </div>
    </div>

    <div class="rp-insight-grid">
        <div class="ins-card maroon">
            <div class="ins-label">AVERAGE SCORE GROWTH</div>
            <div class="ins-val">+12.4%</div>
            <div class="ins-sub">↑ dibanding semester sebelumnya</div>
        </div>

        <div class="ins-card">
            <div class="ins-label">TOP DIVISION</div>
            <div class="ins-val" style="color: var(--primary-color);">Operasional</div>
            <div class="ins-sub">Avg Score 92.4</div>
        </div>

        <div class="ins-card">
            <div class="ins-label">BEST IMPROVEMENT</div>
            <div class="ins-val">Deni Ramadhan</div>
            <div class="ins-sub" style="color: #10b981; display: flex; align-items: center; gap: 4px;">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                +15.2 pts jump
            </div>
        </div>
    </div>

</div>
@endsection