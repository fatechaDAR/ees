@extends('layouts.dashboard')

@section('title', 'Deteksi Anomali')
@section('page_title', 'Deteksi Anomali')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: DETEKSI BIAS & ANOMALI
       ========================================= */
    .db-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER & FILTER KONTROL --- */
    .db-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;
    }
    .db-title-area { max-width: 600px; }
    .db-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; letter-spacing: -0.5px; line-height: 1.1; }
    .db-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; margin: 0; }
    
    .db-filters {
        display: flex; align-items: center; gap: 16px; background-color: var(--bg-white);
        padding: 12px 20px; border-radius: var(--radius-lg); border: 1px solid var(--border-light);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .filter-label { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
    .filter-select {
        padding: 6px 32px 6px 12px; border: 1px solid var(--border-light); border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text-dark);
        background-color: var(--bg-layout); appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 14px;
        transition: 0.2s; outline: none;
    }
    .filter-select:focus { border-color: var(--primary-color); }
    
    /* Toggle Switch */
    .toggle-container { display: flex; align-items: center; gap: 10px; border-left: 1px solid var(--border-light); padding-left: 16px; }
    .toggle-label { font-size: 0.85rem; font-weight: 600; color: var(--text-dark); cursor: pointer; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    input:checked + .slider { background-color: var(--primary-color); }
    input:checked + .slider:before { transform: translateX(20px); }

    /* --- 2. KPI CARDS --- */
    .db-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .db-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .db-kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }
    
    /* KPI Maroon */
    .db-kpi-card.maroon { background-color: var(--primary-color); color: var(--bg-white); border: none; }
    .maroon .kpi-label { color: rgba(255,255,255,0.8); }
    .maroon .kpi-val { color: var(--bg-white); }
    .kpi-watermark { position: absolute; right: -10px; bottom: -20px; width: 100px; height: 100px; opacity: 0.1; color: var(--bg-white); pointer-events: none; }
    
    .kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; }
    .kpi-val { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); line-height: 1; margin-bottom: 8px; }
    
    .kpi-sub-trend { font-size: 0.8rem; font-weight: 600; color: #fca5a5; } /* Light red for maroon card */
    .kpi-chip-moderate { background-color: #fef3c7; color: #d97706; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
    .kpi-status-green { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; color: #10b981; }

    /* --- 3. TABLE SECTION --- */
    .db-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .db-table-head { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .db-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .db-tools { display: flex; gap: 16px; color: var(--text-muted); }
    .db-tools svg { width: 20px; height: 20px; cursor: pointer; transition: 0.2s; }
    .db-tools svg:hover { color: var(--primary-color); }

    /* List Layout Grid */
    .db-list-wrapper { display: flex; flex-direction: column; }
    .db-list-header {
        display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1fr 1.2fr;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .db-list-row {
        display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1fr 1.2fr; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .db-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .db-list-row:last-child { border-bottom: none; }

    /* Cell Styles */
    .col-user { display: flex; align-items: center; gap: 12px; }
    .user-ava {
        width: 36px; height: 36px; border-radius: 50%; color: white; background-color: var(--text-placeholder);
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;
    }
    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 6px;}
    .icon-warning { color: #ef4444; width: 16px; height: 16px; }

    .col-dept { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }
    
    .col-num { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
    
    /* Selisih Styles */
    .col-diff { font-size: 0.95rem; font-weight: 800; }
    .diff-normal { color: var(--text-muted); }
    .diff-alert { color: #ef4444; background-color: #fef2f2; padding: 4px 8px; border-radius: 6px; display: inline-block;} /* Merah menonjol */

    /* Badges Status */
    .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
    .status-anomali { background-color: #fef08a; color: #854d0e; } /* Kuning */
    .status-normal { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); } /* Abu-abu */

    /* Pagination */
    .db-pagination {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-info { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .page-controls { display: flex; gap: 6px; }
    .page-btn {
        width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
        border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 700; color: var(--text-dark);
        cursor: pointer; transition: 0.2s; border: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); border-color: var(--primary-color);}
    .page-btn.active { background-color: var(--primary-color); color: var(--bg-white); border-color: var(--primary-color); box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2); }

    /* --- 6. INTERPRETASI DATA SECTION --- */
    .db-interpretasi {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px 32px;
        border: 1px solid var(--border-light); border-top: 4px solid var(--primary-color);
        box-shadow: var(--card-shadow); margin-top: 8px;
    }
    .int-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0 0 12px 0; }
    .int-desc { font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; margin: 0 0 20px 0; max-width: 800px; }
    .int-highlight { font-weight: 700; color: var(--text-dark); }
    
    .int-legend { display: flex; gap: 24px; align-items: center; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .dot-legend { width: 12px; height: 12px; border-radius: 4px; }
    .bg-yellow { background-color: #fcd34d; }
    .bg-red { background-color: #fca5a5; }

    /* Responsif */
    @media (max-width: 1024px) {
        .db-list-header { display: none; }
        .db-list-row { grid-template-columns: 1fr; gap: 10px; padding: 20px; border-bottom: 2px solid var(--bg-layout); }
        .db-header { flex-direction: column; align-items: flex-start; }
        .toggle-container { border-left: none; padding-left: 0; }
    }
</style>

<div class="db-wrapper">

    <div class="db-header">
        <div class="db-title-area">
            <h1 class="db-title">Deteksi Bias &<br>Nilai Anomali</h1>
            <p class="db-desc">Sistem mendeteksi deviasi nilai signifikan yang berada di luar batas toleransi akademik (ambang batas selisih &plusmn;1.5).</p>
        </div>
        
        <div class="db-filters">
            <span class="filter-label">Filter:</span>
            <select class="filter-select">
                <option>Semua Data</option>
                <option>Dies Natalis 2024</option>
                <option>Seminar Nasional</option>
            </select>
            <div class="toggle-container">
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
                <span class="toggle-label" onclick="document.querySelector('.switch input').click()">Tampilkan hanya data anomali</span>
            </div>
        </div>
    </div>

    <div class="db-kpi-grid">
        <div class="db-kpi-card maroon">
            <svg class="kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            <div class="kpi-label">TOTAL DEVIASI</div>
            <div class="kpi-val">{{ $totalAnomalies }}</div>
            <div class="kpi-sub-trend">Butuh Tinjauan</div>
        </div>

        <div class="db-kpi-card">
            <div class="kpi-label">RATA-RATA SKOR ANOMALI</div>
            <div class="kpi-val">{{ number_format($avgDeviation, 1) }}</div>
            <div class="kpi-chip-moderate">Kategori: {{ $avgDeviation < 1.5 ? 'Critical' : 'Warning' }}</div>
        </div>

        <div class="db-kpi-card">
            <div class="kpi-label">AKURASI PENILAIAN</div>
            <div class="kpi-val">{{ $accuracy }}%</div>
            <div class="kpi-status-green" style="color: {{ $accuracy >= 90 ? '#10b981' : ($accuracy >= 75 ? '#f59e0b' : '#ef4444') }}">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ $accuracy >= 90 ? 'Sistem Stabil' : ($accuracy >= 75 ? 'Butuh Perhatian' : 'Kritis') }}
            </div>
        </div>
    </div>

    <div class="db-table-card">
        <div class="db-table-head">
            <h2 class="db-table-title">Daftar Audit Penilaian Panitia</h2>
            <div class="db-tools">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </div>
        </div>

        <div class="db-list-wrapper">
            <div class="db-list-header">
                <div>NAMA PANITIA</div>
                <div>EVENT</div>
                <div>NILAI</div>
                <div>PENILAI</div>
                <div>STATUS</div>
            </div>

            @forelse($anomalies as $anomaly)
            <div class="db-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: {{ '#' . substr(md5($anomaly->evaluatee->user->name ?? 'User'), 0, 6) }}">
                        {{ strtoupper(substr($anomaly->evaluatee->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="user-name">
                        {{ $anomaly->evaluatee->user->name ?? 'N/A' }}
                        <svg class="icon-warning" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
                <div class="col-dept">{{ Str::limit($anomaly->event->name ?? 'N/A', 20) }}</div>
                <div class="col-num">{{ number_format($anomaly->final_score, 1) }}</div>
                <div class="col-dept">{{ $anomaly->evaluator->name ?? 'N/A' }}</div>
                <div><span class="badge-status status-anomali">ANOMALI</span></div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Tidak ada anomali yang terdeteksi.</div>
            @endforelse
        </div>

        <div class="db-pagination">
            <div class="page-info">Menampilkan {{ $anomalies->firstItem() ?? 0 }} sampai {{ $anomalies->lastItem() ?? 0 }} dari {{ $anomalies->total() }} total data anomali</div>
            <div class="page-controls">
                {{ $anomalies->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="db-interpretasi">
        <h3 class="int-title">Interpretasi Data</h3>
        <p class="int-desc">
            Sistem secara otomatis menandai baris sebagai anomali apabila nilai yang diberikan memiliki selisih mutlak <span class="int-highlight">&gt; 1.5</span> dibandingkan dengan rata-rata kelompok departemen tersebut. Hal ini dapat menjadi indikator adanya <span class="int-highlight">bias penilaian pribadi</span>, ketidakpahaman terhadap rubrik, atau performa individu yang memang ekstrem di lapangan.
        </p>
        <div class="int-legend">
            <div class="legend-item"><div class="dot-legend bg-yellow"></div> DEVIASI TINGGI</div>
            <div class="legend-item"><div class="dot-legend bg-red"></div> SKOR KRITIS</div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 8px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection