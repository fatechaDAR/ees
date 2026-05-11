@extends('layouts.dashboard')

@section('title', 'Manajemen Divisi')
@section('page_title', 'Manajemen Divisi')

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
    .dm-table-head { padding: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); }
    .dm-table-title { font-size: 1.1rem; font-weight: 800; color: var(--text-dark); margin: 0; }
    .dm-tools { display: flex; gap: 16px; color: var(--text-muted); }
    .dm-tools svg { width: 20px; height: 20px; cursor: pointer; transition: 0.2s; }
    .dm-tools svg:hover { color: var(--primary-color); }

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
            <button class="dm-dropdown">
                Dies Natalis ke-60
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <button class="btn-add">
                <span class="plus-icon">+</span>
                Tambah Divisi
            </button>
        </div>
    </div>

    <div class="dm-kpi-grid">
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <div class="dm-kpi-label">TOTAL DIVISI</div>
            <div class="dm-kpi-val">12</div>
        </div>
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <div class="dm-kpi-label">TOTAL PANITIA</div>
            <div class="dm-kpi-val">248</div>
        </div>
        <div class="dm-kpi-card">
            <svg class="dm-kpi-watermark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <div class="dm-kpi-label">RERATA PER DIVISI</div>
            <div class="dm-kpi-val">20.6</div>
        </div>
    </div>

    <div class="dm-table-card">
        <div class="dm-table-head">
            <h2 class="dm-table-title">Daftar Divisi Aktif</h2>
            <div class="dm-tools">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
        </div>

        <div class="dm-list-wrapper">
            <div class="dm-list-header">
                <div>NAMA DIVISI</div>
                <div>STATUS</div>
                <div>JUMLAH PANITIA</div>
                <div>AKSI</div>
            </div>

            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">Divisi Humas & Publikasi</div>
                        <div class="divisi-desc">Branding, Media Sosial, & Pers</div>
                    </div>
                </div>
                <div><span class="badge badge-blue">LENGKAP</span></div>
                <div class="dm-count">32</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>

            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">Divisi Konsumsi</div>
                        <div class="divisi-desc">Katering, Snack, & Logistik Makanan</div>
                    </div>
                </div>
                <div><span class="badge badge-orange">KURANG 2</span></div>
                <div class="dm-count">18</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>

            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">Divisi Perlengkapan</div>
                        <div class="divisi-desc">Vendor, Sound System, & Venue</div>
                    </div>
                </div>
                <div><span class="badge badge-blue">LENGKAP</span></div>
                <div class="dm-count">45</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>

            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">Divisi Keamanan</div>
                        <div class="divisi-desc">Crowd Control & Perizinan</div>
                    </div>
                </div>
                <div><span class="badge badge-blue">LENGKAP</span></div>
                <div class="dm-count">24</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>

            <div class="dm-list-row">
                <div class="col-divisi">
                    <div class="divisi-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <div>
                        <div class="divisi-name">Divisi Acara & Kreatif</div>
                        <div class="divisi-desc">Rundown, Talent, & Stage Manager</div>
                    </div>
                </div>
                <div><span class="badge badge-red">KURANG 5</span></div>
                <div class="dm-count">15</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Hapus">🗑</button>
                </div>
            </div>
        </div>

        <div class="dm-pagination">
            <div class="page-info">Menampilkan 5 dari 12 divisi</div>
            <div class="page-controls">
                <div class="page-btn active">1</div>
                <div class="page-btn">2</div>
                <div class="page-btn">3</div>
            </div>
        </div>
    </div>

    <div class="dm-helper-card">
        <div class="helper-icon">+</div>
        <h3 class="helper-title">Butuh struktur tambahan?</h3>
        <p class="helper-desc">Anda bisa menambahkan sub-divisi atau menduplikasi struktur dari event sebelumnya.</p>
        <a href="#" class="helper-link">Lihat Template Divisi &rarr;</a>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection