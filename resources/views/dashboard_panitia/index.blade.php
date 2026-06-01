@extends('layouts.panitia')

@section('title', 'Dashboard Panitia')

@section('page_title', 'Dashboard Panitia')

@section('content')
<style>
    /* =========================================
       STYLE MAIN CONTENT: DASHBOARD PANITIA
       ========================================= */
    .dp-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER SECTION --- */
    .dp-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .dp-title { font-size: 2rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; letter-spacing: -0.5px; }
    .dp-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; margin: 0; line-height: 1.5; }

    .dp-filter-btn {
        display: flex; align-items: center; gap: 12px;
        background-color: var(--bg-white); border: 1px solid var(--border-light);
        padding: 10px 20px; border-radius: var(--radius-lg); color: var(--text-dark);
        font-weight: 700; font-size: 0.9rem; cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: all 0.3s ease;
    }
    .dp-filter-btn:hover { border-color: var(--primary-color); box-shadow: 0 4px 10px rgba(121, 33, 49, 0.1); }
    .dp-filter-label {
        font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase;
        font-weight: 800; display: block; margin-bottom: 2px; text-align: left;
    }
    .dp-filter-content { display: flex; align-items: center; gap: 8px; }

    /* --- 2. KPI CARDS --- */
    .dp-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .dp-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .dp-kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }

    .kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; }
    
    /* Card 1 & 2 Text */
    .kpi-val-row { display: flex; align-items: baseline; gap: 8px; }
    .kpi-val { font-size: 2.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    .kpi-scale { font-size: 1.1rem; font-weight: 600; color: var(--text-muted); }
    .kpi-chip-up { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; display: inline-block; margin-bottom: 8px;}
    .kpi-subteks { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-top: 8px;}

    /* Card 3: Donut Progress */
    .kpi-progress-wrapper { display: flex; justify-content: space-between; align-items: center; }
    .progress-donut {
        width: 64px; height: 64px; border-radius: 50%;
        background: conic-gradient(var(--primary-color) 80%, var(--border-light) 0);
        display: flex; justify-content: center; align-items: center;
    }
    .progress-inner {
        width: 48px; height: 48px; border-radius: 50%; background-color: var(--bg-white);
        display: flex; justify-content: center; align-items: center;
        font-size: 0.95rem; font-weight: 800; color: var(--text-dark);
    }
    .progress-text { max-width: 60%; }

    /* --- 3. BAR CHART KONTEN (SKOR PER KRITERIA) --- */
    .dp-section-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 32px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
    }
    .sec-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .sec-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .sec-icon { color: var(--text-muted); cursor: pointer; transition: 0.2s; }
    .sec-icon:hover { color: var(--primary-color); }

    .bar-item { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .bar-item:last-child { margin-bottom: 0; }
    .bar-name { width: 130px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; }
    .bar-track { flex-grow: 1; height: 8px; background-color: var(--border-light); border-radius: 4px; overflow: hidden; }
    .bar-fill { height: 100%; background-color: var(--primary-color); border-radius: 4px; transition: width 1s ease; }
    .bar-score { width: 30px; font-size: 0.95rem; font-weight: 800; color: var(--text-dark); text-align: right; }

    .sec-footnote { font-size: 0.75rem; color: var(--text-placeholder); margin-top: 20px; font-weight: 500; }

    /* --- 4. TABEL EVALUASI PANITIA --- */
    .dp-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow); overflow: hidden;
    }
    .tb-header-area { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .tb-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; }
    .tb-desc { font-size: 0.85rem; color: var(--text-muted); margin: 0; }
    
    .btn-filter-sm {
        background-color: var(--bg-layout); border: 1px solid var(--border-light);
        padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;
        color: var(--text-dark); cursor: pointer; transition: 0.2s;
    }
    .btn-filter-sm:hover { background-color: var(--bg-white); border-color: var(--primary-color); color: var(--primary-color); }

    /* List Layout Grid */
    .dp-list-wrapper { display: flex; flex-direction: column; }
    .dp-list-header {
        display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 80px 100px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .dp-list-row {
        display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 80px 100px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .dp-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .dp-list-row:last-child { border-bottom: none; }

    /* Cell Styles */
    .col-user { display: flex; align-items: center; gap: 12px; }
    .user-ava {
        width: 36px; height: 36px; border-radius: 50%; color: white;
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;
    }
    
    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .user-id { font-size: 0.75rem; color: var(--text-muted); }
    .col-div { font-size: 0.9rem; font-weight: 600; color: var(--text-dark); }

    /* Badges Status */
    .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; letter-spacing: 0.5px; }
    .stat-done { background-color: #dbeafe; color: #1e3a8a; }
    .stat-wait { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); }
    .dot { width: 6px; height: 6px; border-radius: 50%; }
    .stat-done .dot { background-color: #3b82f6; }
    .stat-wait .dot { background-color: var(--text-placeholder); }

    /* Kolom Nilai */
    .col-score { font-size: 1.05rem; font-weight: 800; color: var(--text-dark); display: flex; align-items: center; gap: 4px; }
    .icon-star { color: var(--primary-color); width: 14px; height: 14px; }

    /* Tombol Aksi */
    .btn-eval {
        background-color: var(--primary-color); color: var(--bg-white); border: none;
        padding: 8px 16px; border-radius: var(--radius-md); font-size: 0.75rem; font-weight: 800;
        cursor: pointer; letter-spacing: 0.5px; transition: all 0.2s;
    }
    .btn-eval:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(121, 33, 49, 0.2); }

    /* Pagination */
    .dp-pagination {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-info { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .page-nav { display: flex; gap: 8px; }
    .nav-btn {
        background-color: var(--bg-white); border: 1px solid var(--border-light); color: var(--text-dark);
        padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: 0.2s;
    }
    .nav-btn:hover { border-color: var(--primary-color); color: var(--primary-color); }

    /* Responsif */
    @media (max-width: 900px) {
        .dp-list-header { display: none; }
        .dp-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; }
        .dp-pagination { flex-direction: column; gap: 16px; }
    }
</style>

<div class="dp-wrapper">

    <div class="dp-header">
        <div>
            <h1 class="dp-title">Dashboard</h1>
            <p class="dp-desc">Ringkasan dan Proses Evaluasi Panitia</p>
        </div>

    </div>

    <div class="dp-kpi-grid">
        <div class="dp-kpi-card">
            <div>
                <div class="kpi-chip-up">Performa Pribadi</div>
                <div class="kpi-label" style="margin-bottom: 4px;">RATA-RATA NILAI</div>
            </div>
            <div class="kpi-val-row">
                <span class="kpi-val">{{ number_format($avgScore, 1) }}</span>
                <span class="kpi-scale">/ 5.0</span>
            </div>
        </div>

        <div class="dp-kpi-card">
            <div class="kpi-label">JUMLAH EVALUASI</div>
            <div>
                <div class="kpi-val">{{ $totalEvaluationsPerformed }}</div>
                <div class="kpi-subteks">panitia telah Anda nilai</div>
            </div>
        </div>

        <div class="dp-kpi-card">
            <div class="kpi-progress-wrapper">
                <div class="progress-text">
                    <div class="kpi-label">PROGRESS PENILAIAN</div>
                    <div class="kpi-subteks">{{ $totalEvaluationsPerformed }} dari {{ $totalTasks }} tugas selesai</div>
                </div>
                <div class="progress-donut" style="background: conic-gradient(var(--primary-color) {{ $progress }}%, var(--border-light) 0);">
                    <div class="progress-inner">{{ $progress }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="dp-section-card">
        <div class="sec-header">
            <h2 class="sec-title">Skor per Kriteria</h2>
            <svg class="sec-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </div>

        @foreach($kriteriaScores as $label => $score)
        <div class="bar-item">
            <div class="bar-name">{{ $label }}</div>
            <div class="bar-track"><div class="bar-fill" style="width: {{ ($score / 5) * 100 }}%;"></div></div>
            <div class="bar-score">{{ number_format($score, 1) }}</div>
        </div>
        @endforeach

        <div class="sec-footnote">Catatan: Skor ditampilkan berdasarkan penilaian yang Anda terima dari rekan sejawat.</div>
    </div>

    <div class="dp-table-card">
        <div class="tb-header-area">
            <div>
                <h2 class="tb-title">Tugas Evaluasi Rekan</h2>
                <p class="tb-desc">Daftar panitia yang harus Anda berikan penilaian kinerja</p>
            </div>
        </div>

        <div class="dp-list-wrapper">
            <div class="dp-list-header">
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>STATUS</div>
                <div>NILAI</div>
                <div>AKSI</div>
            </div>

            @forelse($evaluationsToPerform as $eval)
            @php
                $existingEvaluation = $eval->evaluationsAsEvaluatee->first();
            @endphp
            <div class="dp-list-row">
                <div class="col-user">
                    <div class="user-avatar" style="background-color: {{ '#' . substr(md5($eval->user->name ?? 'User'), 0, 6) }}; width: 36px; height: 36px; border-radius: 50%; color: white; display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;">
                        {{ strtoupper(substr($eval->user->name ?? 'U', 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $eval->user->name ?? 'N/A' }}</div>
                        <div class="user-id">ID: PNT-{{ str_pad($eval->id ?? 0, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="col-div">{{ $eval->division->name ?? 'Belum ada Divisi' }}</div>
                <div>
                    @if($existingEvaluation)
                        <span class="badge-status stat-done"><span class="dot"></span> Sudah Dinilai</span>
                    @else
                        <span class="badge-status stat-wait"><span class="dot"></span> Belum Dinilai</span>
                    @endif
                </div>
                <div class="col-score">
                    @if($existingEvaluation)
                        {{ number_format($existingEvaluation->final_score, 1) }} <svg class="icon-star" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @else
                        <span style="color: var(--text-placeholder);">--</span>
                    @endif
                </div>
                <div>
                    <button class="btn-eval" onclick="openModalEval({{ $eval->id }}, '{{ $eval->user->name }}')" {{ $existingEvaluation ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : '' }}>
                        {{ $existingEvaluation ? 'SELESAI' : 'EVALUASI' }}
                    </button>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Tidak ada tugas evaluasi saat ini.</div>
            @endforelse
        </div>

        <div class="dp-pagination">
            <div class="page-info">Menampilkan {{ $evaluationsToPerform->firstItem() ?? 0 }} sampai {{ $evaluationsToPerform->lastItem() ?? 0 }} dari {{ $evaluationsToPerform->total() }} tugas</div>
            <div class="page-nav">
                {{ $evaluationsToPerform->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 8px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>

<style>
    /* =========================================
       STYLE MODAL (POPUP) EVALUASI PANITIA
       ========================================= */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(31, 41, 55, 0.6); backdrop-filter: blur(4px);
        display: flex; justify-content: center; align-items: center;
        z-index: 1000; opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }

    .modal-eval-content {
        background-color: var(--bg-white); width: 100%; max-width: 550px;
        border-radius: var(--radius-lg); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: flex; flex-direction: column; max-height: 90vh;
        transform: translateY(-20px); transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-eval-content { transform: translateY(0); }

    /* --- Header --- */
    .modal-header {
        padding: 24px; border-bottom: 1px solid var(--border-light); position: relative;
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin: 0 0 4px 0; }
    .modal-subtitle { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin: 0; }
    
    .btn-close {
        position: absolute; top: 24px; right: 24px; background: none; border: none;
        color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 50%; transition: 0.2s;
    }
    .btn-close:hover { background-color: var(--bg-layout); color: var(--text-dark); }

    /* --- Body (Scrollable) --- */
    .modal-body {
        padding: 24px; /* Ini kunci untuk membuatnya bisa di-scroll */
        max-height: 60vh; /* Membatasi tinggi maksimal isi modal (60% dari tinggi layar) */
        overflow-y: auto; /* Memunculkan scrollbar vertikal JIKA isinya kepanjangan */
        
        display: flex; flex-direction: column; gap: 24px;
    }

    /* Alert Box Warning (Tampilan Front-End) */
    .alert-warning {
        background-color: #fdf2f8; border: 1px solid #fbcfe8; border-radius: var(--radius-md);
        padding: 12px 16px; display: flex; gap: 12px; align-items: center; transition: 0.3s;
    }
    .alert-icon { color: #be185d; flex-shrink: 0; width: 20px; height: 20px; }
    .alert-text { font-size: 0.85rem; color: #9d174d; font-weight: 600; margin: 0; }

    /* Rating Group */
    .rating-group { display: flex; flex-direction: column; gap: 6px; padding: 12px; border-radius: var(--radius-md); border: 1px solid transparent; transition: 0.3s; }
    
    /* State Error untuk Kriteria Kosong */
    .rating-group.has-error { background-color: #fef2f2; border-color: #fecaca; }

    .rating-header { display: flex; justify-content: space-between; align-items: center; }
    .rating-title { font-size: 0.95rem; font-weight: 800; color: var(--text-dark); }
    .req-label { font-size: 0.75rem; font-weight: 800; color: #ef4444; }

    /* Star Interaction */
    .stars-container { display: flex; gap: 4px; cursor: pointer; margin-top: 4px; }
    .star-btn { width: 28px; height: 28px; color: #e2e8f0; transition: 0.2s; }
    .star-btn.filled { color: var(--primary-color); }
    .star-btn:hover { transform: scale(1.1); }

    .rating-desc { font-size: 0.8rem; color: var(--text-muted); line-height: 1.4; margin-top: 4px; }

    /* Textarea Feedback */
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-label { font-size: 0.85rem; font-weight: 800; color: var(--text-dark); }
    .form-textarea {
        width: 100%; padding: 12px 14px; border: 1px solid var(--border-light);
        border-radius: var(--radius-md); font-family: 'Inter', sans-serif;
        font-size: 0.9rem; color: var(--text-dark); outline: none; resize: vertical; min-height: 100px; transition: 0.2s;
    }
    .form-textarea:focus { border-color: var(--primary-color); }
    .form-textarea::placeholder { color: var(--text-placeholder); }

    /* --- Footer --- */
    .modal-footer {
        padding: 16px 24px; border-top: 1px solid var(--border-light);
        background-color: var(--bg-white); display: flex; justify-content: flex-end; gap: 12px;
        border-bottom-left-radius: var(--radius-lg); border-bottom-right-radius: var(--radius-lg);
    }
    .btn-secondary {
        background-color: var(--bg-layout); color: var(--text-muted);
        border: 1px solid var(--border-light); padding: 10px 20px;
        border-radius: 30px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.2s;
    }
    .btn-secondary:hover { background-color: var(--border-light); color: var(--text-dark); }
    
    .btn-primary {
        background-color: var(--primary-color); color: var(--bg-white);
        border: none; padding: 10px 24px; border-radius: 30px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.3s ease; box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2);
    }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 12px rgba(121, 33, 49, 0.3); }
</style>

<div id="modalEvaluasi" class="modal-overlay">
    <div class="modal-eval-content">
        <form action="/evaluasi/store" method="POST" id="evalForm" onsubmit="return validateEvalForm(event)">
            @csrf
            <input type="hidden" name="evaluatee_id" id="evaluatee_id" value="">
            
            <div class="modal-header">
                <h2 class="modal-title">Formulir Evaluasi: <span id="evalTargetName">Panitia</span></h2>
                <p class="modal-subtitle">Berikan penilaian objektif berdasarkan kinerja panitia di lapangan.</p>
                <button type="button" class="btn-close" onclick="toggleModalEval('modalEvaluasi', false)">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="modal-body">
                
                @if(session('error'))
                <div class="alert-warning" style="margin-bottom: 15px; background-color: #fef2f2; border-color: #fca5a5;">
                    <p class="alert-text" style="color: #b91c1c;">{{ session('error') }}</p>
                </div>
                @endif
                @if(session('success'))
                <div class="alert-warning" style="margin-bottom: 15px; background-color: #f0fdf4; border-color: #bbf7d0;">
                    <p class="alert-text" style="color: #15803d;">{{ session('success') }}</p>
                </div>
                @endif

                <div id="evalAlert" class="alert-warning" style="display: none;">
                    <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <p class="alert-text">Beberapa bidang penilaian wajib diisi sebelum mengirimkan formulir.</p>
                </div>

                <!-- Modul Pop up bintang dengan kriteria dari database -->
                @foreach($evaluationCriterias as $criteria)
                <div class="rating-group">
                    <div class="rating-header">
                        <span class="rating-title">{{ $criteria->name }}</span>
                        <span class="req-label">* Wajib</span>
                    </div>
                    <div class="stars-container">
                        <input type="hidden" name="scores[{{ $criteria->id }}]" class="criteria-score-input" required>
                        <svg class="star-btn" onclick="setRating(this, 1)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="star-btn" onclick="setRating(this, 2)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="star-btn" onclick="setRating(this, 3)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="star-btn" onclick="setRating(this, 4)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="star-btn" onclick="setRating(this, 5)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <div class="rating-desc">Berikan penilaian sesuai kriteria ini.</div>
                </div>
                @endforeach
                <!-- Sampai sini untuk modul pop up bintang-->
                <div class="form-group">
                    <label class="form-label">Feedback/Komentar</label>
                    <textarea class="form-textarea" name="feedback" placeholder="Tulis feedback untuk panitia..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="toggleModalEval('modalEvaluasi', false)">Batal</button>
                <button type="submit" class="btn-primary" id="btnSubmitEval">Submit Penilaian</button>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Fungsi Buka Tutup Modal Evaluasi
    function toggleModalEval(modalID, isShow) {
        const modal = document.getElementById(modalID);
        if(isShow) {
            modal.classList.add('active');
        } else {
            modal.classList.remove('active');
        }
    }

    function openModalEval(evaluateeId, evaluateeName) {
        document.getElementById('evaluatee_id').value = evaluateeId;
        document.getElementById('evalTargetName').innerText = evaluateeName;
        
        // Reset stars and inputs
        const allStars = document.querySelectorAll('.star-btn');
        allStars.forEach(s => s.classList.remove('filled'));
        const allInputs = document.querySelectorAll('.criteria-score-input');
        allInputs.forEach(i => i.value = '');

        // PENTING: Bersihkan semua pesan error/warna merah sisa dari pengisian sebelumnya
        const allGroups = document.querySelectorAll('.rating-group');
        allGroups.forEach(g => g.classList.remove('has-error'));
        const alertBox = document.getElementById('evalAlert');
        if(alertBox) alertBox.style.display = 'none';

        toggleModalEval('modalEvaluasi', true);
    }

    // 2. Fungsi Interaktif Klik Bintang
    function setRating(clickedStar, ratingValue) {
        // Cari pembungkus bintang-bintang tersebut
        const container = clickedStar.closest('.stars-container');
        const stars = container.querySelectorAll('.star-btn');
        
        // Simpan nilai ke input hidden
        const inputField = container.querySelector('.criteria-score-input');
        if (inputField) {
            inputField.value = ratingValue;
        }

        // Warnai bintang sesuai urutan yang diklik
        stars.forEach((star, index) => {
            if (index < ratingValue) {
                star.classList.add('filled');
            } else {
                star.classList.remove('filled');
            }
        });

        // Hapus background merah (error state) pada kriteria ini secara instan
        const group = clickedStar.closest('.rating-group');
        group.classList.remove('has-error');

        // Sembunyikan Alert Box di atas jika sudah tidak ada error
        const alertBox = document.getElementById('evalAlert');
        if(alertBox && alertBox.style.display !== 'none') {
            alertBox.style.opacity = '0';
            setTimeout(() => { alertBox.style.display = 'none'; }, 300);
        }
    }

    // 3. FUNGSI BARU: Validasi Client-Side (Mencegah submit jika bintang kosong)
    function validateEvalForm(event) {
        let isValid = true;
        const allInputs = document.querySelectorAll('.criteria-score-input');

        // Cek satu-satu apakah ada kriteria yang belum diberi nilai
        allInputs.forEach(input => {
            if (!input.value) {
                isValid = false;
                const group = input.closest('.rating-group');
                group.classList.add('has-error'); // Nyalakan warna merah
            }
        });

        // Jika ada yang kosong, cegat form dan munculkan alert
        if (!isValid) {
            event.preventDefault(); // Hentikan pengiriman data ke Backend
            
            const alertBox = document.getElementById('evalAlert');
            alertBox.style.display = 'flex';
            
            // Animasi kemunculan yang halus
            setTimeout(() => { alertBox.style.opacity = '1'; }, 10);
            
            // Otomatis scroll ke bagian atas modal agar peringatan terlihat
            document.querySelector('.modal-body').scrollTop = 0;
            return false;
        }
        
        return true;
    }
</script>
@endsection