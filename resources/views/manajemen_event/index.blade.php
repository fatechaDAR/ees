@extends('layouts.dashboard')

@section('title', 'Manajemen Event')
@section('page_title', 'Manajemen Event')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: MANAGEMENT EVENT
       (Tersinkronisasi dengan CSS Variables)
       ========================================= */
    .event-management-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 1. Header Section */
    .em-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 20px;
    }
    .em-title-area { max-width: 600px; }
    .em-title {
        font-size: 1.75rem; font-weight: 800; 
        color: var(--text-dark); margin-bottom: 4px; 
        letter-spacing: -0.5px;
    }
    .em-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; }
    
    .btn-add-event {
        display: flex; align-items: center; gap: 10px;
        background-color: var(--primary-color);
        color: var(--bg-white);
        padding: 10px 20px; border-radius: 30px;
        font-size: 0.9rem; font-weight: 700;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-add-event:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3);
    }
    .plus-circle {
        display: flex; justify-content: center; align-items: center;
        width: 20px; height: 20px; background-color: var(--bg-white);
        color: var(--primary-color); border-radius: 50%;
        font-weight: 800; font-size: 1.2rem; line-height: 1;
    }

    /* 2. KPI Cards */
    .em-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
    .em-kpi-card {
        background-color: var(--bg-white);
        border-radius: var(--radius-lg); padding: 24px;
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .em-kpi-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1);
    }
    .em-kpi-label {
        font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;
    }
    .em-kpi-value {
        font-size: 2.2rem; font-weight: 800; color: var(--text-dark);
        display: flex; align-items: baseline; gap: 12px; line-height: 1;
    }
    .em-kpi-sub { font-size: 0.85rem; font-weight: 700; color: #10b981; }

    /* 3. Table / List Hybrid Section */
    .em-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light);
        overflow: hidden; margin-top: 8px;
    }
    .em-table-header {
        padding: 24px; display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--border-light);
    }
    .em-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .em-table-tools { display: flex; gap: 16px; color: var(--text-muted); }
    .em-tool-icon { width: 20px; height: 20px; cursor: pointer; transition: color 0.2s; }
    .em-tool-icon:hover { color: var(--primary-color); }

    /* Baris Tabel */
    .em-list-wrapper { display: flex; flex-direction: column; }
    .em-list-header {
        display: grid; grid-template-columns: 2.5fr 1.5fr 1fr 100px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800;
        color: var(--text-muted); background-color: var(--bg-layout);
        border-bottom: 1px solid var(--border-light); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .em-list-row {
        display: grid; grid-template-columns: 2.5fr 1.5fr 1fr 100px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }
    /* Efek Bergeser saat Hover */
    .em-list-row:hover {
        background-color: rgba(121, 33, 49, 0.02);
        transform: translateX(8px);
        border-color: transparent;
    }
    .em-list-row:last-child { border-bottom: none; }

    /* Isi Baris */
    .col-name-wrapper { display: flex; align-items: center; gap: 16px; }
    .event-icon-box {
        width: 44px; height: 44px; border-radius: var(--radius-md);
        background-color: rgba(121, 33, 49, 0.05); /* Latar ikon pakai marun transparan */
        color: var(--primary-color);
        display: flex; justify-content: center; align-items: center;
    }
    .event-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .event-dept { font-size: 0.75rem; color: var(--text-muted); }
    
    .col-date { font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }
    
    /* Badges Status */
    .badge {
        padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; 
        font-weight: 800; display: inline-block; letter-spacing: 0.5px;
    }
    .badge-active { background-color: #dbeafe; color: #1e3a8a; } /* Biru */
    .badge-completed { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); }

    /* Tombol Aksi (Edit & Delete) */
    .col-actions { display: flex; gap: 8px; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md); transition: 0.2s; font-size: 1rem; }
    .btn-edit { color: var(--text-muted); }
    .btn-edit:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); }
    .btn-delete { color: #ef4444; }
    .btn-delete:hover { background-color: #fef2f2; color: #dc2626; }

    /* Responsive untuk Mobile */
    @media (max-width: 800px) {
        .em-list-header { display: none; }
        .em-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; position: relative; }
        .col-actions { position: absolute; top: 20px; right: 20px; }
    }
</style>

<div class="event-management-wrapper">

    <div class="em-header">
        <div class="em-title-area">
            <h1 class="em-title">Management Event</h1>
            <p class="em-desc">Mengawasi dan mengevaluasi kegiatan akademik, melacak metrik kinerja, dan menjaga keunggulan institusi di seluruh departemen.</p>
        </div>
        <button class="btn-add-event">
            <span class="plus-circle">+</span>
            Tambah Event
        </button>
    </div>

    <div class="em-kpi-grid">
        <div class="em-kpi-card">
            <div class="em-kpi-label">JUMLAH ACARA YANG SEDANG BERLANGSUNG</div>
            <div class="em-kpi-value">
                {{ $activeEvents }} <span class="em-kpi-sub">Aktif</span>
            </div>
        </div>
        <div class="em-kpi-card">
            <div class="em-kpi-label">SELESAI</div>
            <div class="em-kpi-value">{{ $completedEvents }}</div>
        </div>
        <div class="em-kpi-card">
            <div class="em-kpi-label">PERINGKAT RATA-RATA</div>
            <div class="em-kpi-value">{{ number_format($avgRating, 1) }}</div>
        </div>
    </div>

    <div class="em-table-card">
        <div class="em-table-header">
            <h2 class="em-table-title">Acara Mendatang dan Terkini</h2>
            <div class="em-table-tools">
                <svg class="em-tool-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <svg class="em-tool-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
        </div>

        <div class="em-list-wrapper">
            <div class="em-list-header">
                <div>NAMA ACARA</div>
                <div>TANGGAL KEGIATAN</div>
                <div>STATUS SAAT INI</div>
                <div>ACTIONS</div>
            </div>

            @forelse($events as $event)
            <div class="em-list-row">
                <div class="col-name-wrapper">
                    <div class="event-icon-box">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="event-name">{{ $event->name }}</div>
                        <div class="event-dept">{{ $event->description }}</div>
                    </div>
                </div>
                <div class="col-date">
                    {{ \Carbon\Carbon::parse($event->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($event->end_date)->format('d, Y') }}
                </div>
                <div><span class="badge {{ $event->status == 'active' ? 'badge-active' : 'badge-completed' }}">{{ strtoupper($event->status) }}</span></div>
                <div class="col-actions">
                    <button class="action-btn btn-edit" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada data event.</div>
            @endforelse

        </div>
    </div>

        </div>
    </div>
    
    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder);">
        &copy; 2026 Sistem Evaluasi Panitia Event Kampus
    </footer>
</div>
@endsection