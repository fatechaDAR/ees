@extends('layouts.dashboard')

@section('title', 'Manajemen Divisi')
@section('page_title', 'Evalytics')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: MANAJEMEN DIVISI
       ========================================= */
    .dm-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER & ACTIONS --- */
    .dm-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .dm-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px; letter-spacing: -0.5px; }
    .dm-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; }
    
    .dm-actions { display: flex; gap: 12px; align-items: center; }
    .dm-dropdown {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--bg-white); border: 1px solid var(--border-light);
        padding: 10px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem;
        color: var(--text-dark); cursor: pointer; transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .dm-dropdown:hover { border-color: var(--primary-color); }
    
    .btn-add {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--primary-color); color: var(--bg-white);
        padding: 10px 20px; border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-add:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3); }
    .btn-add .plus-icon { font-weight: 800; font-size: 1.2rem; line-height: 1; }

    /* --- 2. KPI CARDS --- */
    .dm-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .dm-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dm-kpi-card:hover { transform: translateY(-6px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }
    
    .dm-kpi-watermark {
        position: absolute; right: -10px; bottom: -15px;
        width: 80px; height: 80px; opacity: 0.03; color: var(--text-dark); pointer-events: none;
    }
    .dm-kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .dm-kpi-val { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); line-height: 1; }

    /* --- 3. TABLE SECTION --- */
    .dm-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    .dm-table-head { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); flex-wrap: wrap; gap: 12px; }
    .dm-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
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

    /* List Layout */
    .dm-list-wrapper { display: flex; flex-direction: column; }
    .dm-list-header {
        display: grid; grid-template-columns: 2.5fr 1fr 1fr 100px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .dm-list-row {
        display: grid; grid-template-columns: 2.5fr 1fr 1fr 100px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .dm-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .dm-list-row:last-child { border-bottom: none; }

    /* Row Details */
    .col-divisi { display: flex; align-items: center; gap: 16px; }
    .divisi-icon {
        width: 44px; height: 44px; border-radius: 50%;
        background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color);
        display: flex; justify-content: center; align-items: center;
    }
    .divisi-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .divisi-desc { font-size: 0.75rem; color: var(--text-muted); }
    
    /* Badges */
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
    .badge-blue { background-color: #dbeafe; color: #1e3a8a; } /* Biru Tua */
    .badge-orange { background-color: #ffedd5; color: #c2410c; } /* Oranye */
    .badge-red { background-color: #fee2e2; color: #b91c1c; } /* Merah */

    .dm-count { font-size: 1.1rem; font-weight: 800; color: var(--primary-color); }

    /* Actions */
    .col-actions { display: flex; gap: 8px; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md); transition: 0.2s; font-size: 1rem; color: var(--text-muted); }
    .action-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); }
    .btn-delete:hover { background-color: #fef2f2; color: #dc2626; }

    /* Pagination Footer */
    .dm-pagination {
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

    /* --- 4. HELPER CARD (EMPTY STATE / ADDON) --- */
    .dm-helper-card {
        background-color: rgba(121, 33, 49, 0.02);
        border: 2px dashed rgba(121, 33, 49, 0.2);
        border-radius: var(--radius-lg); padding: 32px; text-align: center;
        display: flex; flex-direction: column; align-items: center; gap: 12px;
        transition: all 0.3s; cursor: pointer;
    }
    .dm-helper-card:hover { background-color: rgba(121, 33, 49, 0.05); border-color: var(--primary-color); transform: translateY(-2px); }
    .helper-icon {
        width: 48px; height: 48px; border-radius: 50%; background-color: var(--bg-white);
        color: var(--primary-color); display: flex; justify-content: center; align-items: center;
        font-size: 1.5rem; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .helper-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0;}
    .helper-desc { font-size: 0.85rem; color: var(--text-muted); max-width: 400px; line-height: 1.5; margin: 0; }
    .helper-link { font-size: 0.85rem; font-weight: 800; color: var(--primary-color); text-decoration: none; margin-top: 4px; }

    /* Responsif */
    @media (max-width: 800px) {
        .dm-list-header { display: none; }
        .dm-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; position: relative; }
        .col-actions { position: absolute; top: 20px; right: 20px; }
        .dm-pagination { flex-direction: column; gap: 16px; }
    }
</style>

<div class="dm-wrapper">

    <div class="dm-header">
        <div>
            <h1 class="dm-title">Manajemen Divisi</h1>
            <p class="dm-desc">Mengelola struktur kepanitiaan dan alokasi SDM untuk setiap departemen dalam acara kampus.</p>
        </div>
        <div class="dm-actions">
            @if($events->isEmpty())
                <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">Belum ada Event</span>
            @else
                <select class="dm-dropdown" onchange="window.location.href='/manajemen-divisi?event_id=' + this.value" style="border: 1px solid var(--border-light); border-radius: 20px; font-weight: 700; font-size: 0.85rem; padding: 10px 16px; outline: none; cursor: pointer; color: var(--text-dark); background-color: var(--bg-white);">
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ $event->id == $selectedEventId ? 'selected' : '' }}>
                            {{ $event->name }}
                        </option>
                    @endforeach
                </select>
            @endif
            <button class="btn-add" onclick="openAddModal()">
                <span class="plus-icon">+</span>
                Tambah Divisi
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background-color: #d1fae5; border-left: 4px solid #10b981; color: #065f46; padding: 16px; border-radius: var(--radius-md); font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 12px; animation: fadeIn 0.4s ease-out; margin-bottom: 8px;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="dm-kpi-grid">
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <div class="dm-kpi-label">TOTAL DIVISI</div>
            <div class="dm-kpi-val">{{ $totalDivisi }}</div>
        </div>
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <div class="dm-kpi-label">TOTAL PANITIA</div>
            <div class="dm-kpi-val">{{ $totalPanitia }}</div>
        </div>
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <div class="dm-kpi-label">RERATA PER DIVISI</div>
            <div class="dm-kpi-val">{{ $avgPerDivisi }}</div>
        </div>
    </div>

    <div class="dm-table-card">
        <div class="dm-table-head">
            <h2 class="dm-table-title">Daftar Divisi Aktif</h2>
            <div class="mp-tools">
                <button onclick="toggleFilterPanel()" style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; display: flex; align-items: center;" title="Filter Pencarian">
                    <svg id="filterIconSvg" class="{{ request('search_divisi') ? 'active-filter-btn' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
                <a href="{{ route('divisi.export', ['event_id' => $selectedEventId, 'search_divisi' => request('search_divisi')]) }}" title="Unduh CSV" style="color: inherit; display: flex; align-items: center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
        </div>

        {{-- Panel Filter --}}
        <div id="filterPanel" class="mp-filter-panel {{ request('search_divisi') ? 'open' : '' }}">
            <form method="GET" action="{{ route('divisi.index') }}" style="display: flex; gap: 16px; align-items: center; width: 100%; flex-wrap: wrap;">
                <input type="hidden" name="event_id" value="{{ $selectedEventId }}">
                
                <div class="filter-field" style="flex: 1; min-width: 200px;">
                    <input type="text" name="search_divisi" value="{{ request('search_divisi') }}" placeholder="Cari nama divisi..." style="width: 100%; padding: 8px 16px; border-radius: 20px; border: 1px solid var(--border-light); font-size: 0.85rem; outline: none; font-weight: 500; transition: border-color 0.2s;">
                </div>
                
                <div class="filter-actions" style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-filter-apply" style="background-color: var(--primary-color); color: white; border: none; padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: background-color 0.2s;">
                        Terapkan
                    </button>
                    @if(request()->filled('search_divisi'))
                        <a href="{{ route('divisi.index', ['event_id' => $selectedEventId]) }}" class="btn-filter-reset" style="background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-decoration: none; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="dm-list-wrapper">
            <div class="dm-list-header">
                <div>NAMA DIVISI</div>
                <div>STATUS</div>
                <div>JUMLAH PANITIA</div>
                <div>AKSI</div>
            </div>

            @forelse ($divisiList as $divisi)
            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">{{ $divisi->name }}</div>
                        <div class="divisi-desc">{{ $divisi->description ?? 'Tidak ada deskripsi' }}</div>
                    </div>
                </div>
                <div><span class="badge badge-blue">LENGKAP</span></div>
                <div class="dm-count">{{ $divisi->committee_members_count }}</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit" onclick="openEditModal({{ json_encode($divisi) }})">✎</button>
                    <button class="action-btn btn-delete" title="Hapus" onclick="deleteDivisi({{ $divisi->id }}, '{{ addslashes($divisi->name) }}')">🗑</button>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                Belum ada data divisi yang terdaftar.
            </div>
            @endforelse
        </div>

        <div class="dm-pagination">
            <div class="page-info">Menampilkan {{ $divisiList->firstItem() ?? 0 }} sampai {{ $divisiList->lastItem() ?? 0 }} dari {{ $divisiList->total() }} divisi</div>
            <div class="page-controls">
                {{ $divisiList->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="dm-helper-card" onclick="openAddModal()">
        <div class="helper-icon">+</div>
        <h3 class="helper-title">Butuh divisi tambahan?</h3>
        <p class="helper-desc">Anda bisa menambahkan divisi baru untuk departemen event yang sedang aktif ini.</p>
        <span class="helper-link">Tambah Divisi Baru &rarr;</span>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>

<style>
    /* =========================================
       STYLE MODAL (POPUP) TAMBAH/EDIT DIVISI
       ========================================= */
    /* Overlay Latar Belakang Gelap/Blur */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(31, 41, 55, 0.6);
        backdrop-filter: blur(4px);
        display: flex; justify-content: center; align-items: center;
        z-index: 1000;
        
        opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal-overlay.active { opacity: 1; visibility: visible; }

    /* Kontainer Utama Modal */
    .modal-content {
        background-color: var(--bg-white);
        width: 100%; max-width: 520px;
        border-radius: var(--radius-lg);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex; flex-direction: column;
        transform: translateY(-20px); transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-content { transform: translateY(0); }

    /* --- Header Modal --- */
    .modal-header {
        padding: 24px; border-bottom: 1px solid var(--border-light);
        position: relative;
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin: 0 0 8px 0; }
    
    .badge-event-context {
        display: inline-flex; align-items: center; gap: 6px;
        background-color: #1e3a8a; /* Biru Tua */
        color: white; padding: 4px 12px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px;
    }

    .btn-close {
        position: absolute; top: 20px; right: 24px;
        background: none; border: none; color: var(--text-muted); cursor: pointer;
        display: flex; justify-content: center; align-items: center;
        padding: 4px; border-radius: 50%; transition: 0.2s;
    }
    .btn-close:hover { background-color: var(--bg-layout); color: var(--text-dark); }

    /* --- Body (Form Inputs & Callout) --- */
    .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label { font-size: 0.85rem; font-weight: 700; color: var(--text-dark); }

    /* Input dengan Ikon Alert di Dalam */
    .input-wrapper { position: relative; display: flex; align-items: center; }
    .form-input {
        width: 100%; padding: 10px 14px; border: 1px solid var(--border-light);
        border-radius: var(--radius-md); font-family: 'Inter', sans-serif;
        font-size: 0.9rem; color: var(--text-dark); outline: none; transition: 0.2s;
    }
    .form-input:focus { border-color: var(--primary-color); }
    .form-input::placeholder { color: var(--text-placeholder); }

    /* State Error Validasi */
    .icon-alert {
        position: absolute; right: 12px; color: #ef4444;
        width: 20px; height: 20px; display: none;
    }
    .error-msg { font-size: 0.75rem; color: #ef4444; font-weight: 600; display: none; }
    
    /* Trigger saat parent punya class 'has-error' */
    .form-group.has-error .form-input { border-color: #ef4444; background-color: #fef2f2; padding-right: 40px; }
    .form-group.has-error .icon-alert { display: block; }
    .form-group.has-error .error-msg { display: block; }

    /* Callout Box (Catatan Editorial) */
    .callout-box {
        background-color: #f3f4f6; /* Abu-abu muda */
        padding: 16px; border-radius: var(--radius-md);
        display: flex; gap: 12px; align-items: flex-start;
    }
    .callout-icon { color: #f97316; flex-shrink: 0; width: 24px; height: 24px; } /* Aksen Oranye Muda */
    .callout-content { display: flex; flex-direction: column; gap: 4px; }
    .callout-title { font-size: 0.75rem; font-weight: 800; color: var(--text-dark); text-transform: uppercase; margin: 0; letter-spacing: 0.5px;}
    .callout-text { font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin: 0; }

    /* --- Footer (Actions) --- */
    .modal-footer {
        padding: 16px 24px; border-top: 1px solid var(--border-light);
        background-color: var(--bg-white); border-bottom-left-radius: var(--radius-lg);
        border-bottom-right-radius: var(--radius-lg); display: flex; justify-content: flex-end; gap: 12px;
    }
    .btn-secondary {
        background-color: var(--bg-layout); color: var(--text-dark);
        border: 1px solid var(--border-light); padding: 10px 20px;
        border-radius: 30px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.2s;
    }
    .btn-secondary:hover { background-color: var(--border-light); }
    
    .btn-primary {
        background-color: var(--primary-color); color: var(--bg-white);
        border: none; padding: 10px 24px; border-radius: 30px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: 0.3s ease;
        box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2);
    }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); box-shadow: 0 6px 12px rgba(121, 33, 49, 0.3); }
</style>

<div id="modalDivisi" class="modal-overlay">
    <div class="modal-content">
        <form action="{{ route('divisi.store') }}" method="POST" id="divisiForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="division_id" id="divisionIdInput" value="{{ old('division_id') }}">
            <input type="hidden" name="event_id" value="{{ $selectedEventId }}">
            
            <div class="modal-header">
                <button type="button" class="btn-close" onclick="toggleModal('modalDivisi', false)">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <h2 class="modal-title" id="modalTitle">Tambah Divisi</h2>
                <div class="badge-event-context">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    EVENT AKTIF: {{ $events->firstWhere('id', $selectedEventId)?->name ?? 'TIDAK ADA EVENT' }}
                </div>
            </div>

            <div class="modal-body">
                
                <div class="form-group @error('name') has-error @enderror">
                    <label class="form-label">Nama Divisi <span style="color: #ef4444;">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="name" class="form-input" placeholder="contoh: Divisi Acara" 
                               value="{{ old('name') }}" required
                               oninput="this.closest('.form-group').classList.remove('has-error')">
                        
                        <svg class="icon-alert" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    @error('name')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @else
                        <span class="error-msg">Nama divisi harus minimal 3 karakter.</span>
                    @enderror
                </div>

                <div class="form-group @error('description') has-error @enderror">
                    <label class="form-label">Deskripsi Divisi</label>
                    <textarea name="description" class="form-input" style="resize: vertical; min-height: 80px;" placeholder="contoh: Bertanggung jawab atas jalannya rangkaian acara...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-msg" style="display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="callout-box">
                    <svg class="callout-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="callout-content">
                        <h4 class="callout-title">Catatan Editorial</h4>
                        <p class="callout-text">Nama divisi akan muncul pada sertifikat kepanitiaan dan laporan akhir evaluasi. Pastikan penulisan sesuai dengan SK Rektor.</p>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="toggleModal('modalDivisi', false)">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteFormDivisi" method="POST" style="display: none;">
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
        const form = document.getElementById('divisiForm');
        form.action = "{{ route('divisi.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('divisionIdInput').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Divisi';
        
        // Reset fields
        form.name.value = '';
        form.description.value = '';
        
        toggleModal('modalDivisi', true);
    }

    function openEditModal(division) {
        const form = document.getElementById('divisiForm');
        form.action = "/manajemen-divisi/" + division.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('divisionIdInput').value = division.id;
        document.getElementById('modalTitle').innerText = 'Edit Divisi';
        
        // Populate fields
        form.name.value = division.name || '';
        form.description.value = division.description || '';
        
        toggleModal('modalDivisi', true);
    }

    function deleteDivisi(id, name) {
        if (confirm('Apakah Anda yakin ingin menghapus divisi "' + name + '"? Semua panitia dan data evaluasi di dalam divisi ini juga akan terhapus.')) {
            const form = document.getElementById('deleteFormDivisi');
            form.action = "/manajemen-divisi/" + id;
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
        window.location.href = "{{ route('divisi.index') }}?event_id={{ $selectedEventId }}";
    }

    // Auto-open modal jika terjadi error validasi saat reload
    @if($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            const oldMethod = "{{ old('_method') }}";
            const oldId = "{{ old('division_id') }}";
            if (oldMethod === 'PUT' && oldId) {
                const division = {
                    id: oldId,
                    name: {!! json_encode(old('name')) !!},
                    description: {!! json_encode(old('description')) !!}
                };
                openEditModal(division);
            } else {
                openAddModal();
            }
        });
    @endif
</script>
@endsection