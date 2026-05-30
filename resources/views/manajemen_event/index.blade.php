@extends('layouts.dashboard')

@section('title', 'Manajemen Event')
@section('page_title', 'Evalytics')

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
        flex-wrap: wrap; gap: 12px;
    }
    .em-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .mp-tools { display: flex; gap: 16px; color: var(--text-muted); }
    .mp-tools svg { width: 20px; height: 20px; cursor: pointer; transition: 0.2s; }
    .mp-tools svg:hover { color: var(--primary-color); }

    /* Filter Panel */
    .mp-filter-panel {
        padding: 0 24px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease-out, border-bottom 0.3s ease-out;
        background-color: rgba(121, 33, 49, 0.01);
        border-bottom: 1px solid transparent;
    }
    .mp-filter-panel.open {
        padding: 16px 24px;
        max-height: 120px;
        border-bottom: 1px solid var(--border-light);
    }
    .filter-field input:focus {
        border-color: var(--primary-color);
    }
    .btn-filter-apply:hover {
        background-color: var(--primary-hover);
    }
    .btn-filter-reset:hover {
        background-color: #f3f4f6;
        color: var(--text-dark);
    }
    .active-filter-btn {
        color: var(--primary-color) !important;
    }

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
        <button class="btn-add-event" onclick="openAddModal()">
            <span class="plus-circle">+</span>
            Tambah Event
        </button>
    </div>

    @if(session('success'))
    <div style="background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 16px; border-radius: var(--radius-md); font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 12px; animation: fadeIn 0.4s ease-out; margin-bottom: 8px;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

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
            <div class="mp-tools">
                <button onclick="toggleFilterPanel()" style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; display: flex; align-items: center;" title="Filter Pencarian">
                    <svg id="filterIconSvg" class="{{ request('search') || request('status') ? 'active-filter-btn' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
                <a href="{{ route('event.export', ['search' => request('search'), 'status' => request('status')]) }}" title="Unduh CSV" style="color: inherit; display: flex; align-items: center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
        </div>

        {{-- Panel Filter --}}
        <div id="filterPanel" class="mp-filter-panel {{ (request('search') || request('status')) ? 'open' : '' }}">
            <form method="GET" action="{{ route('event.index') }}" id="filterForm" style="display: flex; gap: 16px; align-items: center; width: 100%; flex-wrap: wrap;">
                
                <div class="filter-field" style="flex: 1; min-width: 200px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event..." style="width: 100%; padding: 8px 16px; border-radius: 20px; border: 1px solid var(--border-light); font-size: 0.85rem; outline: none; font-weight: 500; transition: border-color 0.2s;">
                </div>
                
                <div class="filter-field" style="min-width: 200px;">
                    <select name="status" style="width: 100%; padding: 8px 16px; border-radius: 20px; border: 1px solid var(--border-light); font-size: 0.85rem; outline: none; font-weight: 600; color: var(--text-dark); background-color: var(--bg-white);">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                
                <div class="filter-actions" style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-filter-apply" style="background-color: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: background-color 0.2s;">
                        Terapkan
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('event.index') }}" class="btn-filter-reset" style="background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
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
                    <button class="action-btn btn-edit" title="Edit" onclick="openEditModal({{ json_encode($event) }})">✎</button>
                    <button class="action-btn btn-delete" title="Delete" onclick="deleteEvent({{ $event->id }}, '{{ addslashes($event->name) }}')">🗑</button>
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

<style>
    /* =========================================
       STYLE MODAL (POPUP) TAMBAH/EDIT EVENT
       ========================================= */
    /* Overlay Latar Belakang Gelap/Blur */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(31, 41, 55, 0.6); /* Warna gelap transparan */
        backdrop-filter: blur(4px); /* Efek blur modern */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        
        /* Default sembunyi. Akan aktif jika ada class 'active' */
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Kontainer Utama Modal */
    .modal-content {
        background-color: var(--bg-white);
        width: 100%;
        max-width: 500px;
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        
        /* Animasi turun dari atas */
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    /* --- Header Modal --- */
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
    }
    .btn-close {
        background: none; border: none;
        color: var(--text-muted); cursor: pointer;
        display: flex; justify-content: center; align-items: center;
        padding: 4px; border-radius: 50%; transition: 0.2s;
    }
    .btn-close:hover {
        background-color: var(--bg-layout);
        color: var(--text-dark);
    }

    /* --- Body (Form Inputs) --- */
    .modal-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label {
        font-size: 0.85rem; font-weight: 700; color: var(--text-dark);
    }
    .form-label span.required { color: #ef4444; }

    .form-input, .form-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem; color: var(--text-dark);
        background-color: var(--bg-white);
        outline: none; transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus { border-color: var(--primary-color); }
    .form-input::placeholder { color: var(--text-placeholder); }

    /* Validasi Error State */
    .form-group.has-error .form-input {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    .error-msg {
        font-size: 0.75rem; color: #ef4444; font-weight: 600;
        display: none; /* Default sembunyi */
    }
    .form-group.has-error .error-msg { display: block; }

    /* Info Box / Catatan */
    .info-box {
        background-color: #eff6ff; /* Biru muda */
        border-left: 4px solid #3b82f6;
        padding: 12px 16px; border-radius: 4px;
        display: flex; gap: 12px; align-items: flex-start;
        margin-top: 8px;
    }
    .info-icon { color: #3b82f6; flex-shrink: 0; margin-top: 2px; }
    .info-text { font-size: 0.8rem; color: #1e3a8a; line-height: 1.5; margin: 0; }
    .info-text strong { font-weight: 700; }

    /* --- Footer (Actions) --- */
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border-light);
        background-color: var(--bg-layout);
        border-bottom-left-radius: var(--radius-lg);
        border-bottom-right-radius: var(--radius-lg);
        display: flex; justify-content: flex-end; gap: 12px;
    }
    
    .btn-secondary {
        background-color: var(--bg-white); color: var(--text-dark);
        border: 1px solid var(--border-light); padding: 10px 20px;
        border-radius: var(--radius-md); font-weight: 700; font-size: 0.85rem;
        cursor: pointer; transition: 0.2s;
    }
    .btn-secondary:hover { background-color: #f1f5f9; }
    
    .btn-primary {
        background-color: var(--primary-color); color: var(--bg-white);
        border: none; padding: 10px 24px; border-radius: var(--radius-md);
        font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.3s ease;
        box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2);
    }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 12px rgba(121, 33, 49, 0.3); }

</style>

<div id="modalEvent" class="modal-overlay">
    <div class="modal-content">
        <form action="{{ route('event.store') }}" method="POST" id="eventForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="event_id" id="eventIdInput" value="{{ old('event_id') }}">
            
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Tambah Event</h2>
                <button type="button" class="btn-close" onclick="toggleModal('modalEvent', false)">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group @error('name') has-error @enderror">
                    <label class="form-label">Nama Event <span class="required">*</span></label>
                    
                    <input 
                        type="text" 
                        name="name"
                        class="form-input" 
                        placeholder="Contoh: Orientasi Mahasiswa Baru" 
                        value="{{ old('name') }}"
                        required
                        oninput="this.parentElement.classList.remove('has-error')"
                    >
                    
                    @error('name')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @else
                        <span class="error-msg">Field ini wajib diisi</span>
                    @enderror
                </div>

                <div class="form-group @error('description') has-error @enderror">
                    <label class="form-label">Deskripsi Event</label>
                    <textarea 
                        name="description" 
                        class="form-input" 
                        style="resize: vertical; min-height: 80px;" 
                        placeholder="Contoh: Deskripsi singkat tentang event..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group @error('date') has-error @enderror">
                    <label class="form-label">Tanggal Event</label>
                    <input type="date" name="date" class="form-input" value="{{ old('date') }}">
                    @error('date')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group @error('status') has-error @enderror">
                    <label class="form-label">Status Event</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="info-box">
                    <svg class="info-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <p class="info-text">
                        <strong>Catatan Penting:</strong> Pastikan nama event sesuai dengan dokumen akademik resmi. Perubahan status menjadi "Selesai" akan mengunci seluruh akses penilaian panitia secara permanen.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="toggleModal('modalEvent', false)">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Fungsi sederhana untuk membuka & menutup modal
    function toggleModal(modalID, isShow) {
        const modal = document.getElementById(modalID);
        if(isShow) {
            modal.classList.add('active');
        } else {
            modal.classList.remove('active');
        }
    }

    function openAddModal() {
        const form = document.getElementById('eventForm');
        form.action = "{{ route('event.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('eventIdInput').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Event';
        
        // Reset fields
        form.name.value = '';
        form.description.value = '';
        form.date.value = '';
        form.status.value = 'active';
        
        toggleModal('modalEvent', true);
    }

    function openEditModal(event) {
        const form = document.getElementById('eventForm');
        form.action = "/manajemen-event/" + event.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('eventIdInput').value = event.id;
        document.getElementById('modalTitle').innerText = 'Edit Event';
        
        // Populate fields
        form.name.value = event.name || '';
        form.description.value = event.description || '';
        form.date.value = event.start_date || '';
        form.status.value = event.status || 'active';
        
        toggleModal('modalEvent', true);
    }

    function deleteEvent(id, name) {
        if (confirm('Apakah Anda yakin ingin menghapus event "' + name + '"? Semua data terkait (divisi, panitia, evaluasi) juga mungkin akan terhapus.')) {
            const form = document.getElementById('deleteForm');
            form.action = "/manajemen-event/" + id;
            form.submit();
        }
    }

    // ---- Fungsi Filter Panel ----
    function toggleFilterPanel() {
        const panel = document.getElementById('filterPanel');
        const iconSvg = document.getElementById('filterIconSvg');
        panel.classList.toggle('open');
        if (panel.classList.contains('open')) {
            iconSvg.classList.add('active-filter-btn');
        } else {
            iconSvg.classList.remove('active-filter-btn');
        }
    }

    function resetFilter() {
        window.location.href = "{{ route('event.index') }}";
    }

    // Auto-open modal jika terjadi error validasi saat reload
    @if($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            const oldMethod = "{{ old('_method') }}";
            const oldId = "{{ old('event_id') }}";
            if (oldMethod === 'PUT' && oldId) {
                const event = {
                    id: oldId,
                    name: "{{ old('name') }}",
                    description: `{!! addslashes(old('description')) !!}`,
                    start_date: "{{ old('date') }}",
                    status: "{{ old('status') }}"
                };
                openEditModal(event);
            } else {
                openAddModal();
            }
        });
    @endif
</script>
@endsection