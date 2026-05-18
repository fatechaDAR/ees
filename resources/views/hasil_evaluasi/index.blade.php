@extends('layouts.dashboard')

@section('title', 'Hasil Evaluasi')
@section('page_title', 'Evalytics')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: HASIL EVALUASI PANITIA
       ========================================= */
    .hep-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER & ACTIONS --- */
    .hep-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .hep-label {
        font-size: 0.7rem; font-weight: 800; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;
    }
    .hep-title-area { max-width: 650px; }
    .hep-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; letter-spacing: -0.5px; }
    .hep-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; }
    
    .hep-actions { display: flex; gap: 12px; align-items: center; }
    .btn-outline {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--bg-white); color: var(--text-dark);
        border: 1px solid var(--border-light); padding: 10px 20px;
        border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .btn-outline:hover { border-color: var(--primary-color); color: var(--primary-color); }
    
    .btn-primary {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--primary-color); color: var(--bg-white);
        padding: 10px 24px; border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3); }

    /* --- 2. KPI CARDS --- */
    /* Grid dengan proporsi 1.5 : 1 : 1 agar card pertama lebih besar */
    .hep-kpi-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 24px; }
    
    .hep-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex; flex-direction: column; justify-content: center;
    }
    .hep-kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }
    
    /* Card 1: Global Score */
    .kpi-main-watermark {
        position: absolute; right: -20px; bottom: -30px; width: 120px; height: 120px;
        opacity: 0.03; color: var(--primary-color); pointer-events: none;
    }
    .kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .kpi-main-row { display: flex; align-items: baseline; gap: 8px; margin-bottom: 12px; }
    .kpi-main-val { font-size: 3rem; font-weight: 800; color: var(--primary-color); line-height: 1; }
    .kpi-main-scale { font-size: 1.2rem; font-weight: 600; color: var(--text-muted); }
    
    .kpi-chip-row { display: flex; align-items: center; gap: 8px; }
    .kpi-chip { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; }
    .kpi-chip-text { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

    /* Card 2 & 3: Standard KPI */
    .kpi-val { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); line-height: 1; margin-bottom: 8px; }
    .kpi-sub { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }
    .kpi-progress-track { width: 100%; height: 6px; background-color: var(--border-light); border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .kpi-progress-fill { height: 100%; background-color: #10b981; border-radius: 4px; } /* Warna Hijau Sangat Baik */

    /* --- 3. TABLE SECTION --- */
    .hep-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .hep-table-head { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .hep-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    
    .filter-select {
        padding: 8px 32px 8px 16px; border: 1px solid var(--border-light); border-radius: 20px;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text-dark);
        background-color: var(--bg-white); appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; transition: 0.2s;
    }
    .filter-select:hover { border-color: var(--primary-color); }

    /* Grid Table Layout */
    .hep-list-wrapper { display: flex; flex-direction: column; }
    .hep-list-header {
        display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1.2fr;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .hep-list-row {
        display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1.2fr; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .hep-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .hep-list-row:last-child { border-bottom: none; }

    /* Cell Styles */
    .col-user { display: flex; align-items: center; gap: 16px; }
    .user-ava {
        width: 38px; height: 38px; border-radius: 50%; color: white;
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;
    }
    .ava-1 { background-color: #3b82f6; } .ava-2 { background-color: #10b981; } .ava-3 { background-color: #f59e0b; } .ava-4 { background-color: #8b5cf6; } .ava-5 { background-color: #ef4444; }

    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .user-id { font-size: 0.75rem; color: var(--text-muted); }

    .divisi-pill {
        padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700;
        background-color: var(--bg-layout); color: var(--text-dark); border: 1px solid var(--border-light);
        display: inline-block; white-space: nowrap;
    }
    
    .col-total { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); }
    .col-avg { font-size: 0.95rem; font-weight: 800; color: var(--primary-color); }
    
    /* Category Badges */
    .badge-cat { padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
    .cat-sb { background-color: #dcfce7; color: #166534; } /* Sangat Baik */
    .cat-b { background-color: #ecfccb; color: #3f6212; } /* Baik */
    .cat-c { background-color: #dbeafe; color: #1e3a8a; } /* Cukup */
    .cat-k { background-color: #ffedd5; color: #c2410c; } /* Kurang */
    .cat-sk { background-color: #fee2e2; color: #b91c1c; } /* Sangat Kurang */

    /* Footer Pagination */
    .hep-pagination {
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
    .page-btn.active { background-color: var(--primary-color); color: var(--bg-white); border-color: var(--primary-color); box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2); }

    /* --- 6. INSIGHT BANNER --- */
    .hep-banner {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a131e 100%);
        border-radius: var(--radius-lg); padding: 32px 40px; margin-top: 8px;
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: 0 10px 20px -5px rgba(121, 33, 49, 0.3); flex-wrap: wrap; gap: 20px;
    }
    .banner-text-area { max-width: 70%; }
    .banner-title { font-size: 1.25rem; font-weight: 800; color: var(--bg-white); margin: 0 0 8px 0; }
    .banner-desc { font-size: 0.9rem; color: rgba(255,255,255,0.8); line-height: 1.6; margin: 0; }
    
    .btn-banner {
        background-color: var(--bg-white); color: var(--primary-color);
        padding: 12px 24px; border-radius: 30px; font-weight: 800; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        white-space: nowrap;
    }
    .btn-banner:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.3); background-color: #f8fafc; }

    /* Responsif */
    @media (max-width: 1024px) {
        .hep-kpi-grid { grid-template-columns: 1fr; }
        .hep-list-header { display: none; }
        .hep-list-row { grid-template-columns: 1fr; gap: 10px; padding: 20px; }
        .banner-text-area { max-width: 100%; }
        .hep-banner { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="hep-wrapper">

    <div class="hep-header">
        <div class="hep-title-area">
            <div class="hep-label">REKAPITULASI KINERJA</div>
            <h1 class="hep-title">Hasil Evaluasi Panitia</h1>
            <p class="hep-desc">Laporan agregat performa seluruh divisi pada gelaran Dies Natalis ke-65 Universitas Gadjah Mada.</p>
        </div>
        <div class="hep-actions">
            <button class="btn-outline">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </button>
            <button class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                Share Report
            </button>
        </div>
    </div>

    <div class="hep-kpi-grid">
        <div class="hep-kpi-card">
            <svg class="kpi-main-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            
            <div class="kpi-label">SKOR RATA-RATA GLOBAL</div>
            <div class="kpi-main-row">
                <span class="kpi-main-val">{{ number_format($avgScoreGlobal, 2) }}</span>
                <span class="kpi-main-scale">/ 5.0</span>
            </div>
            <div class="kpi-chip-row">
                <span class="kpi-chip">Sinkron</span>
                <span class="kpi-chip-text">Data riil database</span>
            </div>
        </div>

        <div class="hep-kpi-card">
            <div class="kpi-label">TOTAL PANITIA</div>
            <div class="kpi-val">{{ $totalPanitia }}</div>
            <div class="kpi-sub">Anggota Terdaftar</div>
        </div>

        <div class="hep-kpi-card">
            <div class="kpi-label">SANGAT BAIK (>4.0)</div>
            <div class="kpi-val">{{ $persentaseSangatBaik }}%</div>
            <div class="kpi-progress-track">
                <div class="kpi-progress-fill" style="width: {{ $persentaseSangatBaik }}%;"></div>
            </div>
        </div>
    </div>

    <div class="hep-table-card">
        <div class="hep-table-head">
            <h2 class="hep-table-title">Rincian Nilai Individu</h2>
            <select class="filter-select">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="hep-list-wrapper">
            <div class="hep-list-header">
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>EVENT</div>
                <div>RATA-RATA</div>
                <div>KATEGORI</div>
            </div>

            @forelse($results as $res)
            <div class="hep-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: {{ '#' . substr(md5($res->evaluatee->user->name ?? 'User'), 0, 6) }}">
                        {{ strtoupper(substr($res->evaluatee->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $res->evaluatee->user->name ?? 'N/A' }}</div>
                        <div class="user-id">ID: PNT-{{ str_pad($res->evaluatee->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div><span class="divisi-pill">{{ $res->evaluatee->division->name ?? 'N/A' }}</span></div>
                <div class="col-total">{{ Str::limit($res->event->name ?? 'N/A', 20) }}</div>
                <div class="col-avg">{{ number_format($res->final_score, 2) }}</div>
                <div>
                    @php
                        $score = $res->final_score;
                        $cat = 'SK'; $label = 'SANGAT KURANG';
                        if($score >= 4.5) { $cat = 'sb'; $label = 'SANGAT BAIK'; }
                        elseif($score >= 3.5) { $cat = 'b'; $label = 'BAIK'; }
                        elseif($score >= 2.5) { $cat = 'c'; $label = 'CUKUP'; }
                        elseif($score >= 1.5) { $cat = 'k'; $label = 'KURANG'; }
                    @endphp
                    <span class="badge-cat cat-{{ $cat }}">{{ $label }}</span>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada hasil evaluasi.</div>
            @endforelse
        </div>

        <div class="hep-pagination">
            <div class="page-info">Menampilkan {{ $results->firstItem() ?? 0 }} sampai {{ $results->lastItem() ?? 0 }} dari {{ $results->total() }} panitia</div>
            <div class="page-controls">
                {{ $results->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="hep-banner">
        <div class="banner-text-area">
            <h3 class="banner-title">Evaluasi Strategis 2026</h3>
            <p class="banner-desc">Divisi Acara menunjukkan konsistensi tertinggi dalam pelaksanaan tugas, namun terdapat beberapa individu dengan anomali performa. Divisi Konsumsi memerlukan perhatian khusus dan peningkatan alokasi SDM pada event berikutnya.</p>
        </div>
        <button class="btn-banner">Buka Laporan Anomali</button>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection