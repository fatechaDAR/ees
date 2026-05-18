@extends('layouts.dashboard')

@section('title', 'Manajemen Divisi')
@section('page_title', 'Evalytics')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: MANAJEMEN PANITIA
       ========================================= */
    .mp-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER & ACTIONS --- */
    .mp-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .mp-title-area { max-width: 600px; }
    .mp-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px; letter-spacing: -0.5px; }
    .mp-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5;}
    
    .mp-actions { display: flex; gap: 12px; align-items: center; }
    .mp-dropdown {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--bg-white); border: 1px solid var(--border-light);
        padding: 10px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;
        color: var(--text-dark); cursor: pointer; transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .mp-dropdown:hover { border-color: var(--primary-color); }
    .mp-dropdown span { color: var(--text-muted); font-weight: 500; }
    
    .btn-add {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--primary-color); color: var(--bg-white);
        padding: 10px 20px; border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-add:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3); }
    .btn-add .plus-icon { font-weight: 800; font-size: 1.2rem; line-height: 1; }

    /* --- 2. KPI CARDS (4 KOLOM) --- */
    .mp-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; }
    .mp-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .mp-kpi-card:hover { transform: translateY(-6px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }
    
    .mp-kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; }
    .mp-kpi-val { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); line-height: 1; margin-bottom: 8px; }
    .mp-kpi-sub { font-size: 0.8rem; font-weight: 600; }
    
    /* Warna Status Sub-teks KPI */
    .text-green { color: #10b981; }
    .text-muted-alt { color: var(--text-placeholder); font-weight: 500; }
    .text-red { color: #ef4444; display: flex; align-items: center; gap: 4px; }
    .text-primary { color: var(--primary-color); }

    /* --- 3. TABLE SECTION --- */
    .mp-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .mp-table-head { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .mp-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .mp-tools { display: flex; gap: 16px; color: var(--text-muted); }
    .mp-tools svg { width: 20px; height: 20px; cursor: pointer; transition: 0.2s; }
    .mp-tools svg:hover { color: var(--primary-color); }

    /* List Layout (Grid untuk keselarasan kolom) */
    .mp-list-wrapper { display: flex; flex-direction: column; }
    .mp-list-header {
        display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 100px 80px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .mp-list-row {
        display: grid; grid-template-columns: 2fr 1.5fr 1.5fr 100px 80px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .mp-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .mp-list-row:last-child { border-bottom: none; }

    /* Row Details */
    .col-user { display: flex; align-items: center; gap: 16px; }
    .user-avatar {
        width: 40px; height: 40px; border-radius: 50%; color: white;
        display: flex; justify-content: center; align-items: center;
        font-weight: 800; font-size: 0.85rem;
    }
    .ava-1 { background-color: #3b82f6; }
    .ava-2 { background-color: #10b981; }
    .ava-3 { background-color: #f59e0b; }
    .ava-4 { background-color: #8b5cf6; }

    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .user-id { font-size: 0.75rem; color: var(--text-muted); }
    
    .col-divisi { font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }
    .col-email { font-size: 0.85rem; color: var(--text-muted); }
    
    /* Badges Role */
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
    .badge-lead { background-color: #dbeafe; color: #1e3a8a; } /* Biru Tua */
    .badge-member { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); } /* Abu-abu */

    /* Actions */
    .col-actions { display: flex; gap: 8px; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md); transition: 0.2s; font-size: 1rem; color: var(--text-muted); }
    .action-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); }
    .btn-delete:hover { background-color: #fef2f2; color: #dc2626; }

    /* Pagination Footer */
    .mp-pagination {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-info { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .page-controls { display: flex; gap: 8px; }
    .page-btn {
        width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
        border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 700; color: var(--text-dark);
        cursor: pointer; transition: 0.2s; border: 1px solid transparent;
    }
    .page-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); }
    .page-btn.active { background-color: var(--primary-color); color: var(--bg-white); box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2); }

    /* Responsif */
    @media (max-width: 900px) {
        .mp-list-header { display: none; }
        .mp-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; position: relative; }
        .col-actions { position: absolute; top: 20px; right: 20px; }
        .mp-pagination { flex-direction: column; gap: 16px; }
    }
</style>

<div class="mp-wrapper">

    <div class="mp-header">
        <div class="mp-title-area">
            <h1 class="mp-title">Manajemen Panitia</h1>
            <p class="mp-desc">Kelola data anggota panitia, atur penugasan divisi, monitor status/aktivitas, dalam event kampus yang sedang berjalan.</p>
        </div>
        <div class="mp-actions">
            <button class="mp-dropdown">
                <span>Pilih Event:</span> Dies Natalis 64
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>
    </div>

    <div class="mp-kpi-grid">
        <div class="mp-kpi-card">
            <div class="mp-kpi-label">TOTAL PANITIA</div>
            <div class="mp-kpi-val">{{ $totalPanitia }}</div>
            <div class="mp-kpi-sub text-green">Terdaftar di sistem</div>
        </div>
        <div class="mp-kpi-card">
            <div class="mp-kpi-label">DIVISI AKTIF</div>
            <div class="mp-kpi-val">{{ $totalDivisi }}</div>
            <div class="mp-kpi-sub text-muted-alt">Semua divisi event</div>
        </div>
        <div class="mp-kpi-card">
            <div class="mp-kpi-label">EVALUASI TERTUNDA</div>
            <div class="mp-kpi-val">{{ $pendingEvaluations }}</div>
            <div class="mp-kpi-sub {{ $pendingEvaluations > 0 ? 'text-red' : 'text-green' }}">
                @if($pendingEvaluations > 0)
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                Perlu Tindakan
                @else
                Semua Selesai
                @endif
            </div>
        </div>
        <div class="mp-kpi-card">
            <div class="mp-kpi-label">RERATA PERFORMA</div>
            <div class="mp-kpi-val">{{ number_format($avgPerformance, 1) }} <span style="font-size: 1.2rem; color: var(--text-muted);">/ 5</span></div>
            <div class="mp-kpi-sub text-primary">
                {{ $avgPerformance >= 4.0 ? 'Sangat Baik' : ($avgPerformance >= 3.0 ? 'Baik' : 'Cukup') }}
            </div>
        </div>
    </div>

    <div class="mp-table-card">
        <div class="mp-table-head">
            <h2 class="mp-table-title">Daftar Anggota Panitia</h2>
            <div class="mp-tools">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
        </div>

        <div class="mp-list-wrapper">
            <div class="mp-list-header">
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>EMAIL</div>
                <div>ROLE</div>
                <div>AKSI</div>
            </div>

            @forelse ($panitiaList as $panitia)
            <div class="mp-list-row">
                <div class="col-user">
                    <div class="user-avatar" style="background-color: {{ '#' . substr(md5($panitia->name), 0, 6) }}">
                        {{ strtoupper(substr($panitia->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $panitia->name }}</div>
                        <div class="user-id">ID: PNT-{{ str_pad($panitia->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="col-divisi">
                    @if($panitia->committeeMembers->isNotEmpty())
                        {{ $panitia->committeeMembers->first()->division->name ?? 'Belum ada Divisi' }}
                    @else
                        Belum ada Divisi
                    @endif
                </div>
                <div class="col-email">{{ $panitia->email }}</div>
                <div>
                    @if($panitia->committeeMembers->isNotEmpty())
                        <span class="badge badge-lead">{{ strtoupper($panitia->committeeMembers->first()->position ?? 'Member') }}</span>
                    @else
                        <span class="badge badge-member">MEMBER</span>
                    @endif
                </div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>
            @empty
            <div class="mp-list-row" style="grid-template-columns: 1fr; text-align: center; color: var(--text-muted); padding: 40px;">
                Belum ada data panitia yang terdaftar.
            </div>
            @endforelse
        </div>

        <div class="mp-pagination">
            <div class="page-info">Menampilkan {{ $panitiaList->firstItem() ?? 0 }} sampai {{ $panitiaList->lastItem() ?? 0 }} dari {{ $panitiaList->total() }} panitia</div>
            <div class="page-controls">
                {{ $panitiaList->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

</div>
@endsection