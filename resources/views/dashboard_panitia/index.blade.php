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
    .ava-am { background-color: #3b82f6; }
    .ava-sp { background-color: #ec4899; }
    .ava-rk { background-color: #f59e0b; }

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
        
        <button class="dp-filter-btn">
            <div>
                <span class="dp-filter-label">FILTER GLOBAL EVENT</span>
                <div class="dp-filter-content">
                    <svg width="16" height="16" fill="none" stroke="var(--primary-color)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Grand Seminar IT 2026
                </div>
            </div>
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>
    </div>

    <div class="dp-kpi-grid">
        <div class="dp-kpi-card">
            <div>
                <div class="kpi-chip-up">↑ +0.2 dari kemarin</div>
                <div class="kpi-label" style="margin-bottom: 4px;">RATA-RATA NILAI</div>
            </div>
            <div class="kpi-val-row">
                <span class="kpi-val">4.5</span>
                <span class="kpi-scale">/ 5.0</span>
            </div>
        </div>

        <div class="dp-kpi-card">
            <div class="kpi-label">JUMLAH EVALUASI</div>
            <div>
                <div class="kpi-val">12</div>
                <div class="kpi-subteks">panitia dinilai</div>
            </div>
        </div>

        <div class="dp-kpi-card">
            <div class="kpi-progress-wrapper">
                <div class="progress-text">
                    <div class="kpi-label">PROGRESS PENILAIAN</div>
                    <div class="kpi-subteks">3 dari 5 panitia terselesaikan</div>
                </div>
                <div class="progress-donut">
                    <div class="progress-inner">80%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="dp-section-card">
        <div class="sec-header">
            <h2 class="sec-title">Skor per Kriteria</h2>
            <svg class="sec-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </div>

        <div class="bar-item">
            <div class="bar-name">KERJA SAMA</div>
            <div class="bar-track"><div class="bar-fill" style="width: 84%;"></div></div>
            <div class="bar-score">4.2</div>
        </div>
        <div class="bar-item">
            <div class="bar-name">DISIPLIN</div>
            <div class="bar-track"><div class="bar-fill" style="width: 96%;"></div></div>
            <div class="bar-score">4.8</div>
        </div>
        <div class="bar-item">
            <div class="bar-name">TANGGUNG JAWAB</div>
            <div class="bar-track"><div class="bar-fill" style="width: 90%;"></div></div>
            <div class="bar-score">4.5</div>
        </div>

        <div class="sec-footnote">Catatan: Skor ditampilkan berdasarkan performa rata-rata kumulatif seluruh panitia yang telah dinilai.</div>
    </div>

    <div class="dp-table-card">
        <div class="tb-header-area">
            <div>
                <h2 class="tb-title">Tabel Evaluasi Panitia</h2>
                <p class="tb-desc">Kelola dan input nilai kinerja individu panitia</p>
            </div>
            <button class="btn-filter-sm">Filter</button>
        </div>

        <div class="dp-list-wrapper">
            <div class="dp-list-header">
                <div>NAMA PANITIA</div>
                <div>DIVISI</div>
                <div>STATUS</div>
                <div>NILAI</div>
                <div>AKSI</div>
            </div>

            <div class="dp-list-row">
                <div class="col-user">
                    <div class="user-ava ava-am">AM</div>
                    <div>
                        <div class="user-name">Arya Mahendra</div>
                        <div class="user-id">ID: PAN-001</div>
                    </div>
                </div>
                <div class="col-div">Acara</div>
                <div><span class="badge-status stat-done"><span class="dot"></span> Sudah Dinilai</span></div>
                <div class="col-score">
                    4.8 <svg class="icon-star" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div><button class="btn-eval">EVALUASI</button></div>
            </div>

            <div class="dp-list-row">
                <div class="col-user">
                    <div class="user-ava ava-sp">SP</div>
                    <div>
                        <div class="user-name">Siska Putri</div>
                        <div class="user-id">ID: PAN-012</div>
                    </div>
                </div>
                <div class="col-div">Humas</div>
                <div><span class="badge-status stat-wait"><span class="dot"></span> Belum Dinilai</span></div>
                <div class="col-score" style="color: var(--text-placeholder);">--</div>
                <div><button class="btn-eval">EVALUASI</button></div>
            </div>

            <div class="dp-list-row">
                <div class="col-user">
                    <div class="user-ava ava-rk">RK</div>
                    <div>
                        <div class="user-name">Raka Kusuma</div>
                        <div class="user-id">ID: PAN-005</div>
                    </div>
                </div>
                <div class="col-div">Logistik</div>
                <div><span class="badge-status stat-done"><span class="dot"></span> Sudah Dinilai</span></div>
                <div class="col-score">
                    4.2 <svg class="icon-star" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div><button class="btn-eval">EVALUASI</button></div>
            </div>
        </div>

        <div class="dp-pagination">
            <div class="page-info">Menampilkan 12 dari 15 Panitia</div>
            <div class="page-nav">
                <button class="nav-btn">Sebelumnya</button>
                <button class="nav-btn">Selanjutnya</button>
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 8px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
    @endsection