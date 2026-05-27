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
            <p class="lhep-desc">Analisis performa komprehensif berdasarkan penilaian tim sejawat dan koordinator selama event <strong>{{ $eventName }}</strong>.</p>
        </div>
        <a href="{{ route('hasil-panitia.pdf') }}" class="btn-download" style="text-decoration: none;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download PDF
        </a>
    </div>

    <div class="lhep-kpi-grid">
        <div class="lhep-kpi-card">
            <div class="kpi-lbl">RATA-RATA NILAI PERFORMA</div>
            <div class="kpi-score-wrapper">
                <div class="kpi-score-group">
                    <span class="kpi-score">{{ number_format($avgScore, 2) }}</span>
                    <span class="kpi-scale">/ 5.00</span>
                </div>
                <div class="kpi-badge-wrapper">
                    <span class="kpi-badge-label">Predikat Performa</span>
                    @php
                        $predikat = 'KURANG';
                        if($avgScore >= 4.5) $predikat = 'SANGAT BAIK';
                        elseif($avgScore >= 3.5) $predikat = 'BAIK';
                        elseif($avgScore >= 2.5) $predikat = 'CUKUP';
                    @endphp
                    <span class="kpi-badge-predikat" style="background-color: {{ $avgScore >= 3.5 ? '#dcfce7' : '#fee2e2' }}; color: {{ $avgScore >= 3.5 ? '#166534' : '#b91c1c' }}">
                        {{ $predikat }}
                    </span>
                </div>
            </div>
            <div class="kpi-trend">
                Sinkronisasi Data Database Berhasil
            </div>
        </div>

        <div class="lhep-kpi-card">
            <div class="kpi-lbl">PERINGKAT PANITIA</div>
            <div class="kpi-rank-group">
                <span class="kpi-rank">#{{ $rank }}</span>
                <span class="kpi-total">/ {{ $totalPanitia }}</span>
            </div>
            <div class="kpi-rank-desc">
                ✨ Peringkat Anda dikalkulasi berdasarkan rata-rata skor seluruh panitia aktif.
            </div>
        </div>
    </div>

    <div class="sec-card">
        <div class="sec-header">
            <h2 class="sec-title">Detail Riwayat Evaluasi</h2>
            <div class="sec-subtitle">Total {{ $evaluations->count() }} Penilaian</div>
        </div>

        <div class="crit-list-wrapper">
            <div class="crit-list-header">
                <div>PENILAI</div>
                <div>KOMENTAR / CATATAN</div>
                <div style="text-align: center;">SKOR</div>
                <div style="text-align: center;">TANGGAL</div>
            </div>

            @forelse($evaluations as $eval)
            <div class="crit-list-row">
                <div>
                    <div class="crit-name">{{ $eval->evaluator->name ?? 'Evaluator' }}</div>
                    <div class="crit-desc">Peran: PANITIA</div>
                </div>
                <div>
                    <div class="crit-desc">"{{ $eval->feedback ?? 'Tidak ada catatan tambahan' }}"</div>
                </div>
                <div class="crit-score">{{ number_format($eval->final_score, 1) }}</div>
                <div style="text-align: center; font-size: 0.8rem; color: var(--text-muted);">
                    {{ $eval->created_at->format('d/m/Y') }}
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada data evaluasi untuk Anda.</div>
            @endforelse
        </div>
    </div>

    <div class="sec-card">
        <div class="sec-header">
            <h2 class="sec-title">Top Leaderboard</h2>
        </div>

        <div class="rank-list-wrapper">
            @php
                $topRankings = \App\Models\Evaluation::selectRaw('evaluatee_id, AVG(final_score) as avg_score')
                    ->groupBy('evaluatee_id')
                    ->with('evaluatee.user')
                    ->orderByDesc('avg_score')
                    ->limit(5)
                    ->get();
            @endphp

            @foreach($topRankings as $index => $tr)
            <div class="rank-item {{ auth()->user()->id == ($tr->evaluatee->user_id ?? 0) ? 'is-me' : '' }}">
                <div class="rank-left">
                    <span class="rank-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="rank-ava" style="background-color: {{ '#' . substr(md5($tr->evaluatee->user->name ?? 'User'), 0, 6) }}">
                        {{ strtoupper(substr($tr->evaluatee->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="rank-name-box">
                        <span class="rank-name">
                            {{ $tr->evaluatee->user->name ?? 'N/A' }} 
                            @if(auth()->user()->id == ($tr->evaluatee->user_id ?? 0))
                                <span class="badge-me">(Anda)</span>
                            @endif
                        </span>
                        <span class="rank-role">{{ $tr->evaluatee->division->name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="rank-score">{{ number_format($tr->avg_score, 2) }}</div>
            </div>
            @endforeach
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