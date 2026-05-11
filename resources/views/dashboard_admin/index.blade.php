@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('page_title', 'Dashboard Admin')

@section('content')
   <style>
    /* =========================================
       STYLE MAIN CONTENT DASHBOARD ADMIN
       (Tersinkronisasi dengan variabel CSS di Head)
       ========================================= */
    .dashboard-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out; /* Efek muncul perlahan */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Typography */
    .dash-title { font-size: 1.75rem; font-weight: 800; color: var(--primary-color); margin-bottom: 4px; letter-spacing: -0.5px; }
    .dash-subtitle { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; }

    /* Grid System */
    .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; }
    .grid-2-chart { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    @media (max-width: 768px) { .grid-2-chart { grid-template-columns: 1fr; } }

    /* KPI Cards dengan Efek Hover */
    .card-kpi {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-light);
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-kpi:hover {
        transform: translateY(-4px); /* Efek melayang */
        box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); /* Shadow warna marun tipis */
    }
    
    /* Card Maroon Khusus */
    .card-kpi.maroon {
        background: var(--primary-color);
        color: var(--bg-white);
        border: none;
    }

    /* KPI Texts */
    .kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .maroon .kpi-label { color: rgba(255, 255, 255, 0.8); }
    .kpi-value-row { display: flex; align-items: baseline; gap: 4px; }
    .kpi-value { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); }
    .maroon .kpi-value { color: var(--bg-white); }
    .kpi-sub { font-size: 0.9rem; color: var(--text-muted); font-weight: 600; }
    .kpi-trend { margin-top: 12px; font-size: 0.85rem; color: #10b981; font-weight: 700; display: flex; align-items: center; gap: 4px; }

    /* Avatar Tumpuk */
    .avatar-stack { display: flex; margin-left: 8px; margin-top: 12px; align-items: center; gap: 8px; }
    .av-stack-item { width: 28px; height: 28px; border-radius: 50%; border: 2px solid var(--bg-white); margin-left: -8px; background: var(--border-light); }
    .av-stack-text { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-left: 4px; }

    /* Bar Chart CSS Mockup (Warna disesuaikan dgn Primary Color) */
    .bar-chart-container { display: flex; align-items: flex-end; justify-content: space-around; height: 180px; padding-top: 20px; border-bottom: 1px solid var(--border-light); margin-top: 20px; }
    .bar-wrapper { display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; width: 40px; cursor: pointer; }
    .bar { width: 100%; border-top-left-radius: 6px; border-top-right-radius: 6px; transition: 0.3s ease; }
    .bar-wrapper:hover .bar { filter: brightness(0.8); transform: scaleY(1.05); } /* Efek grafik naik saat hover */
    .bar-label { font-size: 0.7rem; color: var(--text-muted); margin-top: 8px; text-align: center; font-weight: 700; }
    .bar-value { font-size: 0.8rem; font-weight: 800; color: var(--text-dark); margin-bottom: 5px; opacity: 0; transition: 0.3s; transform: translateY(5px); }
    .bar-wrapper:hover .bar-value { opacity: 1; transform: translateY(0); } /* Angka muncul saat hover */

    /* Donut Chart CSS Mockup */
    .donut-chart { width: 140px; height: 140px; border-radius: 50%; margin: 20px auto; position: relative; background: conic-gradient( #10b981 0% 45%, #3b82f6 45% 75%, #f59e0b 75% 90%, #ef4444 90% 100% ); box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.4s; cursor: pointer; }
    .donut-chart:hover { transform: scale(1.05); }
    .donut-hole { width: 90px; height: 90px; background: var(--bg-white); border-radius: 50%; position: absolute; top: 25px; left: 25px; display: flex; flex-direction: column; align-items: center; justify-content: center; }

    /* =========================================
       RANKING CARD KHUSUS ADMIN
       ========================================= */
    .rank-card {
        background: var(--bg-white); border-radius: var(--radius-lg); padding: 32px;
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); margin-top: 8px;
    }
    .rank-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .rank-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); }
    .rank-global-btn { 
        background: rgba(121, 33, 49, 0.05); color: var(--primary-color);
        padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(121, 33, 49, 0.1); 
    }
    .rank-global-btn:hover { background: var(--primary-color); color: white; }

    /* Rank Rows dengan Efek Slider masuk ke kanan */
    .rank-row {
        display: grid; grid-template-columns: 50px 1fr 120px 80px; align-items: center;
        padding: 16px 12px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease; border-radius: var(--radius-md);
    }
    .rank-row:hover {
        background-color: rgba(121, 33, 49, 0.02); /* Warna hover mengikuti primary-color */
        transform: translateX(8px); /* Efek baris bergeser sedikit ke kanan */
        border-color: transparent;
    }
    .rank-row:last-child { border-bottom: none; }

    .rank-badge { width: 32px; height: 32px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem; }
    .rank-1 { background: #fef08a; color: #854d0e; box-shadow: 0 0 10px rgba(254, 240, 138, 0.5); }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fed7aa; color: #9a3412; }

    .rank-profile { display: flex; align-items: center; gap: 12px; }
    .rank-avatar { width: 40px; height: 40px; border-radius: 50%; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 0.85rem; }
    .avatar-1 { background: var(--text-dark); } .avatar-2 { background: var(--text-muted); } .avatar-3 { background: var(--text-placeholder); }
    
    .rank-info { display: flex; flex-direction: column; }
    .rank-name { font-weight: 700; color: var(--text-dark); font-size: 0.95rem; margin-bottom: 2px;}
    .rank-email { color: var(--text-muted); font-size: 0.75rem; }

    .divisi-pill { background: var(--bg-layout); color: var(--text-dark); padding: 4px 12px; border-radius: 12px; font-size: 0.7rem; font-weight: 800; border: 1px solid var(--border-light); }
    .rank-score { font-size: 1.1rem; font-weight: 800; color: var(--primary-color); text-align: right; }

    .rank-footer { display: flex; justify-content: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light); }
    .btn-show-all { padding: 10px 24px; border: 2px solid var(--primary-color); background: transparent; color: var(--primary-color); border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
    .btn-show-all:hover { background: var(--primary-color); color: var(--bg-white); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2); }
</style>

<div class="dashboard-wrapper">
    <div>
        <h1 class="dash-title">Dashboard Admin</h1>
        <p class="dash-subtitle">Ringkasan Evaluasi Kinerja Panitia</p>
    </div>

    <div class="grid-3">
        <div class="card-kpi">
            <div class="kpi-label">RATA-RATA NILAI</div>
            <div class="kpi-value-row">
                <span class="kpi-value">{{ number_format($avgScore, 1) }}</span>
                <span class="kpi-sub">/ 5.0</span>
            </div>
            <div class="kpi-trend">
                <span>↑</span> Performa Keseluruhan
            </div>
        </div>

        <div class="card-kpi">
            <div class="kpi-label">JUMLAH EVALUASI</div>
            <div class="kpi-value">{{ $totalEvaluasi }}</div>
            <div class="avatar-stack">
                <span class="av-stack-text">Total evaluasi masuk</span>
            </div>
        </div>

        <div class="card-kpi maroon">
            <svg style="position: absolute; right: 20px; top: 20px; width: 60px; height: 60px; opacity: 0.1;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="kpi-label">PROGRESS PENILAIAN</div>
            <div class="kpi-value">{{ $progress }}%</div>
            <p style="margin: 12px 0 0 0; font-size: 0.85rem; color: rgba(255,255,255,0.8); font-weight: 500;">
                Berdasarkan evaluasi yang sudah dinilai.
            </p>
        </div>
    </div>

    <div class="grid-2-chart">
        <div class="card-kpi">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: var(--text-dark);">Rata-rata Nilai per Event</h3>
                <a href="#" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 700;">View Detailed &rarr;</a>
            </div>
            
            <div class="bar-chart-container">
                @forelse($eventScores as $es)
                <div class="bar-wrapper">
                    <span class="bar-value">{{ number_format($es->avg_score, 1) }}</span>
                    <div class="bar" style="height: {{ ($es->avg_score / 5) * 100 }}%; background: {{ $es->avg_score > 4 ? 'var(--primary-color)' : 'rgba(121, 33, 49, 0.4)' }};"></div>
                    <span class="bar-label">{{ strtoupper(substr($es->event->name ?? 'EVENT', 0, 10)) }}</span>
                </div>
                @empty
                <div style="width: 100%; text-align: center; color: var(--text-muted); font-size: 0.8rem;">Belum ada data event.</div>
                @endforelse
            </div>
        </div>

        <div class="card-kpi">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: var(--text-dark); text-align: center;">Distribusi Kategori Nilai</h3>
            <div class="donut-chart">
                <div class="donut-hole">
                    <span style="font-size: 1.5rem; font-weight: 800; color: var(--text-dark);">{{ $totalEvaluasi }}</span>
                    <span style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">TOTAL</span>
                </div>
            </div>
            <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 8px; font-size: 0.8rem; color: var(--text-dark);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></span> Sangat Baik</div>
                    <span style="font-weight: 700;">{{ $distribusi['sangat_baik'] }}%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #3b82f6;"></span> Baik</div>
                    <span style="font-weight: 700;">{{ $distribusi['baik'] }}%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></span> Cukup</div>
                    <span style="font-weight: 700;">{{ $distribusi['cukup'] }}%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444;"></span> Kurang</div>
                    <span style="font-weight: 700;">{{ $distribusi['kurang'] }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rank-card">
    <div class="rank-header">
        <h2 class="rank-title">Ranking Panitia Terbaik</h2>
        <a href="#" class="rank-global-btn">Global Leaderboard</a>
    </div>

    <div class="rank-list">
        @forelse($topPanitia as $index => $rank)
        <div class="rank-row">
            <div class="rank-badge-col"><div class="rank-badge {{ 'rank-' . ($index + 1) }}">{{ $index + 1 }}</div></div>
            <div class="rank-profile">
                <div class="rank-avatar {{ 'avatar-' . ($index + 1) }}">
                    {{ strtoupper(substr($rank->evaluatee->user->name ?? '?', 0, 2)) }}
                </div>
                <div class="rank-info">
                    <span class="rank-name">{{ $rank->evaluatee->user->name ?? 'Unknown' }}</span>
                    <span class="rank-email">{{ $rank->evaluatee->user->email ?? '-' }}</span>
                </div>
            </div>
            <div class="rank-divisi">
                <span class="divisi-pill">{{ strtoupper($rank->evaluatee->division->name ?? 'UMUM') }}</span>
            </div>
            <div class="rank-score">{{ number_format($rank->avg_score, 2) }}</div>
        </div>
        @empty
        <div style="padding: 24px; text-align: center; color: var(--text-muted);">
            Belum ada data ranking tersedia.
        </div>
        @endforelse
    </div>

    <div class="rank-footer">
        <a href="#" class="btn-show-all">Tampilkan Seluruh Ranking</a>
    </div>
</div>

    @endsection