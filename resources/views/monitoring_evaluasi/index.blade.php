@extends('layouts.dashboard')

@section('title', 'Monitoring Evaluasi')
@section('page_title', 'Evalytics')

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
                <div class="kpi-mini-val">{{ number_format($totalEvaluations) }}</div>
            </div>
            <div class="kpi-mini">
                <div class="kpi-mini-lbl">AVG SCORE</div>
                <div class="kpi-mini-val">{{ number_format($avgScore, 1) }}</div>
            </div>
        </div>
        <div class="me-filters">
            <select class="filter-select">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
            <select class="filter-select">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $divisi)
                    <option value="{{ $divisi->id }}">{{ $divisi->name }}</option>
                @endforeach
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

            @forelse($evaluations as $eval)
            <div class="me-list-row">
                <div class="col-user">
                    <div class="user-ava" style="background-color: {{ '#' . substr(md5($eval->evaluatee->user->name ?? 'User'), 0, 6) }}">
                        {{ strtoupper(substr($eval->evaluatee->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $eval->evaluatee->user->name ?? 'N/A' }}</div>
                        <div class="user-email">{{ $eval->evaluatee->user->email ?? 'N/A' }}</div>
                    </div>
                </div>
                <div><span class="divisi-pill">{{ strtoupper($eval->evaluatee->division->name ?? 'N/A') }}</span></div>
                <div class="col-evaluator">{{ $eval->evaluator->name ?? 'N/A' }}</div>
                <div><div class="score-badge {{ ($eval->final_score >= 3.5) ? 'score-high' : 'score-low' }}">{{ number_format($eval->final_score, 0) }}</div></div>
                <div class="col-komentar">"{{ Str::limit($eval->notes ?? 'Tidak ada komentar', 40) }}"</div>
                <div class="col-actions">
                    <button class="btn-act" title="View Detail">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada data evaluasi.</div>
            @endforelse
        </div>

        <div class="me-pagination">
            <div class="page-info">Menampilkan {{ $evaluations->firstItem() ?? 0 }}-{{ $evaluations->lastItem() ?? 0 }} dari {{ number_format($evaluations->total()) }} evaluasi</div>
            <div class="page-controls">
                {{ $evaluations->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="insight-grid">
        <div class="insight-card insight-dark">
            <div class="ins-left">
                <div class="ins-lbl">EVALUASI BELUM DINILAI</div>
                <div class="ins-val">{{ $pendingEvaluations }}</div>
            </div>
            <button class="btn-detail">LIHAT DETAIL</button>
        </div>
        
        <div class="insight-card">
            <div class="ins-left">
                <div class="ins-lbl">STATUS SISTEM</div>
                <div class="ins-val" style="color: var(--primary-color);">Aktif</div>
                <div class="ins-sub">DATABASE TERHUBUNG</div>
            </div>
            <div class="ins-icon-box">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
        </div>

        <div class="insight-card">
            <div class="ins-left">
                <div class="ins-lbl">WAKTU SERVER</div>
                <div class="ins-val">{{ now()->format('H:i') }}</div>
                <div class="ins-sub">
                    {{ now()->format('d M Y') }}
                </div>
            </div>
            <div class="ins-icon-box">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection