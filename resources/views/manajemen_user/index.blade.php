@extends('layouts.dashboard')

@section('title', 'Manajemen User')
@section('page_title', 'Evalytics')

@section('content')

<style>
    /* =========================================
       STYLE MAIN CONTENT: MANAJEMEN USER
       ========================================= */
    .mu-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- 1. HEADER & ACTIONS --- */
    .mu-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 20px;
    }
    .mu-title-area { max-width: 600px; }
    .mu-title { font-size: 1.75rem; font-weight: 800; color: var(--text-dark); margin-bottom: 4px; letter-spacing: -0.5px; }
    .mu-desc { font-size: 0.95rem; color: var(--text-muted); font-weight: 500; line-height: 1.5; }
    
    .btn-add {
        display: flex; align-items: center; gap: 8px;
        background-color: var(--primary-color); color: var(--bg-white);
        padding: 10px 20px; border-radius: 30px; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
    }
    .btn-add:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 6px 15px rgba(121, 33, 49, 0.3); }

    /* --- 2. KPI CARDS (3 KOLOM) --- */
    .mu-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .mu-kpi-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg); padding: 24px;
        border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
        position: relative; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .mu-kpi-card:hover { transform: translateY(-6px); box-shadow: 0 12px 20px -5px rgba(121, 33, 49, 0.1); }
    
    .kpi-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .mu-kpi-label { font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-corner-icon { color: var(--border-light); }
    
    .mu-kpi-val { font-size: 2.2rem; font-weight: 800; color: var(--text-dark); line-height: 1; margin-bottom: 8px; display: flex; align-items: center; gap: 12px;}
    .mu-kpi-sub { font-size: 0.8rem; font-weight: 500; color: var(--text-muted); }
    
    .badge-blue-soft { background-color: #dbeafe; color: #1e3a8a; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; }
    
    /* Progress Bar KPI */
    .kpi-progress-track { width: 100%; height: 6px; background-color: var(--border-light); border-radius: 4px; margin-top: 12px; overflow: hidden; }
    .kpi-progress-fill { height: 100%; background-color: var(--primary-color); border-radius: 4px; }

    /* --- 3. TOOLBAR FILTER --- */
    .mu-toolbar {
        display: flex; gap: 16px; align-items: center; flex-wrap: wrap;
        background-color: var(--bg-white); padding: 16px 24px;
        border-radius: var(--radius-lg); border: 1px solid var(--border-light); box-shadow: var(--card-shadow);
    }
    .search-wrapper { position: relative; flex-grow: 1; min-width: 250px; }
    .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-placeholder); width: 18px; height: 18px; }
    .search-input {
        width: 100%; padding: 10px 16px 10px 40px; border: 1px solid var(--border-light);
        border-radius: var(--radius-md); font-family: 'Inter', sans-serif; font-size: 0.85rem;
        color: var(--text-dark); outline: none; transition: 0.2s;
    }
    .search-input:focus { border-color: var(--primary-color); }
    .search-input::placeholder { color: var(--text-placeholder); }
    
    .filter-select {
        padding: 10px 14px; border: 1px solid var(--border-light); border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600; color: var(--text-dark);
        background-color: var(--bg-white); outline: none; cursor: pointer; transition: 0.2s;
    }
    .filter-select:hover { border-color: var(--primary-color); }

    /* --- 4. TABLE SECTION --- */
    .mu-table-card {
        background-color: var(--bg-white); border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow); border: 1px solid var(--border-light); overflow: hidden;
    }
    
    /* List Layout Grid */
    .mu-list-wrapper { display: flex; flex-direction: column; }
    .mu-list-header {
        display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 80px;
        padding: 16px 24px; font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        background-color: var(--bg-layout); border-bottom: 1px solid var(--border-light);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .mu-list-row {
        display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 80px; align-items: center;
        padding: 16px 24px; gap: 16px; border-bottom: 1px solid var(--border-light); transition: all 0.3s ease;
    }
    .mu-list-row:hover { background-color: rgba(121, 33, 49, 0.02); transform: translateX(8px); border-color: transparent; }
    .mu-list-row:last-child { border-bottom: none; }

    /* Row Details */
    .col-user { display: flex; align-items: center; gap: 16px; }
    .user-avatar {
        width: 40px; height: 40px; border-radius: 50%; color: white;
        display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 0.85rem;
    }
    .ava-jd { background-color: #3b82f6; }
    .ava-as { background-color: #10b981; }
    .ava-rb { background-color: #f59e0b; }

    .user-name { font-size: 0.95rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
    .user-email { font-size: 0.75rem; color: var(--text-muted); }
    
    /* Role Badges */
    .badge-role { padding: 6px 12px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; display: inline-block; letter-spacing: 0.5px; }
    .role-admin { background-color: #fee2e2; color: #991b1b; }
    .role-evaluator { background-color: #e0e7ff; color: #3730a3; }
    .role-panitia { background-color: var(--bg-layout); color: var(--text-muted); border: 1px solid var(--border-light); }
    
    /* Status Dot */
    .status-indicator { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: var(--text-dark); }
    .dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-active { background-color: #3b82f6; }
    .dot-inactive { background-color: #ef4444; }

    .col-activity { font-size: 0.85rem; color: var(--text-muted); }

    /* Actions */
    .col-actions { display: flex; gap: 8px; }
    .action-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: var(--radius-md); transition: 0.2s; font-size: 1rem; color: var(--text-muted); }
    .action-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); }
    .btn-delete:hover { background-color: #fef2f2; color: #dc2626; }

    /* Pagination Footer */
    .mu-pagination {
        padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
        border-top: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-info { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); }
    .page-controls { display: flex; gap: 6px; }
    .page-btn {
        width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
        border-radius: var(--radius-md); font-size: 0.85rem; font-weight: 700; color: var(--text-dark);
        cursor: pointer; transition: 0.2s; border: 1px solid var(--border-light); background-color: var(--bg-white);
    }
    .page-btn:hover { background-color: rgba(121, 33, 49, 0.05); color: var(--primary-color); border-color: var(--primary-color);}
    .page-btn.active { background-color: var(--primary-color); color: var(--bg-white); border-color: var(--primary-color); box-shadow: 0 4px 6px rgba(121, 33, 49, 0.2); }

    /* Responsif */
    @media (max-width: 900px) {
        .mu-list-header { display: none; }
        .mu-list-row { grid-template-columns: 1fr; gap: 12px; padding: 20px; position: relative; }
        .col-actions { position: absolute; top: 20px; right: 20px; }
        .mu-pagination { flex-direction: column; gap: 16px; }
    }
</style>

<div class="mu-wrapper">

    <div class="mu-header">
        <div class="mu-title-area">
            <h1 class="mu-title">Management User</h1>
            <p class="mu-desc">Konfigurasi tingkat akses dan kelola kredensial personel untuk platform kinerja institusional.</p>
        </div>

        <!-- 
        <button class="btn-add">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah User
        </button>
        --> 

    </div>

    <div class="mu-kpi-grid">
        <div class="mu-kpi-card">
            <div class="kpi-header-row">
                <div class="mu-kpi-label">PENGGUNA {{ request('role') ? strtoupper(request('role')) : 'TERDAFTAR' }}</div>
                <svg class="kpi-corner-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="mu-kpi-val">
                {{ $filteredTotal }} <span class="badge-blue-soft">{{ request('role') ? 'Filtered' : 'Total' }}</span>
            </div>
        </div>
        
        <div class="mu-kpi-card">
            <div class="kpi-header-row">
                <div class="mu-kpi-label">ADMINISTRATOR SISTEM</div>
                <svg class="kpi-corner-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div class="mu-kpi-val" style="margin-bottom: 4px;">{{ $totalAdmin }}</div>
            <div class="mu-kpi-sub">Kontrol akses terbatas diaktifkan</div>
        </div>

        <div class="mu-kpi-card">
            <div class="kpi-header-row">
                <div class="mu-kpi-label">ROLE USER</div>
                <svg class="kpi-corner-icon" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div class="mu-kpi-val" style="margin-bottom: 0;">Multi-Role</div>
            <div class="kpi-progress-track">
                <div class="kpi-progress-fill" style="width: 100%;"></div>
            </div>
        </div>
    </div>

    <form action="{{ route('user.index') }}" method="GET" class="mu-toolbar" id="filterForm">
        <div class="search-wrapper">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" class="search-input" placeholder="Cari nama atau email..." value="{{ request('search') }}">
        </div>
        <select name="role" class="filter-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>ADMIN</option>
            <option value="evaluator" {{ request('role') == 'evaluator' ? 'selected' : '' }}>EVALUATOR</option>
            <option value="panitia" {{ request('role') == 'panitia' ? 'selected' : '' }}>PANITIA</option>
        </select>
        <button type="submit" style="display: none;">Cari</button>
    </form>

    <div class="mu-table-card">
        <div class="mu-list-wrapper">
            <div class="mu-list-header">
                <div>PROFIL PENGGUNA</div>
                <div>ROLE</div>
                <div>STATUS</div>
                <div>AKSI</div>
            </div>

            @forelse($users as $user)
            <div class="mu-list-row">
                <div class="col-user">
                    <div class="user-avatar" style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                </div>
                <div>
                    <span class="badge-role {{ $user->role == 'admin' ? 'role-admin' : ($user->role == 'evaluator' ? 'role-evaluator' : 'role-panitia') }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
                <div class="status-indicator"><span class="dot dot-active"></span> Active</div>
                <div class="col-actions">
                    <button class="action-btn" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                </div>
            </div>
            @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada data user.</div>
            @endforelse
        </div>

        <div class="mu-pagination">
            <div class="page-info">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries</div>
            <div class="page-controls">
                {{ $users->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </div>

    <footer style="text-align: center; margin-top: 16px; font-size: 0.75rem; color: var(--text-placeholder); font-weight: 600;">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>
@endsection