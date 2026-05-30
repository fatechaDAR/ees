<style>
    /* =========================================
       STYLE HALAMAN: PILIH EVENT & DIVISI
       ========================================= */
    
    /* Reset & Variabel Dasar (Jika belum ada di master layout) */
    :root {
        --primary-color: #792131; /* Marun */
        --primary-hover: #5a1824;
        --bg-layout: #f8fafc; /* Soft gray */
        --bg-white: #ffffff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --text-placeholder: #94a3b8;
        --border-light: #e2e8f0;
        --radius-md: 8px;
        --radius-lg: 16px;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }

    /* Container Layar Penuh (Memusatkan konten) */
    .onboarding-layout {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: var(--bg-layout);
        font-family: 'Inter', sans-serif;
        padding: 24px;
        box-sizing: border-box;
    }

    /* --- 1. BRAND HEADER --- */
    .brand-header {
        text-align: center;
        margin-bottom: 32px;
        animation: fadeInDown 0.5s ease-out;
    }
    
    /* Badge Logo Bawaan (Bisa Diganti) */
    .logo-badge {
        width: 64px; height: 64px;
        background-color: var(--primary-color);
        border-radius: 20px;
        display: flex; justify-content: center; align-items: center;
        color: white; margin: 0 auto 16px auto;
        box-shadow: 0 10px 15px -3px rgba(121, 33, 49, 0.3);
    }
    
    .brand-title {
        font-size: 1.85rem; font-weight: 800; color: var(--text-dark);
        margin: 0 0 6px 0; letter-spacing: -0.5px;
    }
    .brand-tagline {
        font-size: 0.95rem; font-weight: 500; color: var(--text-muted); margin: 0;
    }

    /* --- 2. CARD UTAMA --- */
    .selection-card {
        background-color: var(--bg-white);
        width: 100%; max-width: 440px;
        padding: 40px 32px;
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-light);
        animation: fadeInUp 0.5s ease-out;
    }

    .card-title {
        font-size: 1.35rem; font-weight: 800; color: var(--text-dark);
        margin: 0 0 8px 0; text-align: center;
    }
    .card-instruksi {
        font-size: 0.85rem; color: var(--text-muted); font-weight: 500;
        text-align: center; margin: 0 0 32px 0; line-height: 1.5;
    }

    /* --- 3. FORM INPUTS --- */
    .form-group {
        display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px;
    }
    .form-label {
        font-size: 0.75rem; font-weight: 800; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    
    .form-select {
        width: 100%; padding: 14px 16px;
        background-color: var(--bg-layout);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        font-family: 'Inter', sans-serif; font-size: 0.95rem;
        font-weight: 600; color: var(--text-dark);
        outline: none; cursor: pointer; transition: 0.2s;
        
        /* Custom Caret Icon Dropdown */
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }
    .form-select:focus {
        border-color: var(--primary-color);
        background-color: var(--bg-white);
        box-shadow: 0 0 0 3px rgba(121, 33, 49, 0.1);
    }
    /* Warna abu-abu untuk teks placeholder di dalam dropdown */
    .form-select option[value=""][disabled] { color: var(--text-placeholder); }

    /* --- 4. CTA BUTTON --- */
    .btn-submit {
        width: 100%; margin-top: 12px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a131e 100%);
        color: var(--bg-white); border: none;
        padding: 16px; border-radius: var(--radius-md);
        font-weight: 800; font-size: 1rem; cursor: pointer;
        transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(121, 33, 49, 0.2);
        display: flex; justify-content: center; align-items: center; gap: 8px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(121, 33, 49, 0.3);
    }

    /* --- 5. BANTUAN & FOOTER --- */
    .support-text {
        text-align: center; margin-top: 32px; font-size: 0.85rem;
        color: var(--text-muted); font-weight: 500; animation: fadeIn 1s ease-in;
    }
    .support-link {
        color: var(--primary-color); font-weight: 800; text-decoration: none; transition: 0.2s;
    }
    .support-link:hover { text-decoration: underline; }

    .page-footer {
        margin-top: auto; /* Mendorong footer ke paling bawah layar jika ruang berlebih */
        padding-top: 40px; font-size: 0.75rem; color: var(--text-placeholder);
        font-weight: 700; text-align: center;
    }

    /* Keyframes Animasi Halus */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="onboarding-layout">

    <div class="brand-header">
        
        <div class="logo-badge">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h1 class="brand-title">Evalytics</h1>
        <p class="brand-tagline">Platform Evaluasi Operasional Kegiatan Kampus</p>
    </div>

    <div class="selection-card">
        <h2 class="card-title">Pilih Event & Divisi</h2>
        <p class="card-instruksi">Pilih event dan divisi sebelum masuk ke dashboard evaluasi.</p>

        <form method="POST" action="{{ route('pilih-event.store') }}">
            @csrf
            
            @if(session('warning'))
                <div style="background-color: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 0.85rem; font-weight: 600; text-align: center;">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">EVENT</label>
                <select class="form-select" name="event_id" id="event_select" required>
                    <option value="" disabled selected>Pilih event yang akan dievaluasi</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" data-divisions="{{ json_encode($event->divisions) }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">DIVISI</label>
                <select class="form-select" name="division_id" id="division_select" required>
                    <option value="" disabled selected>Pilih divisi tugas Anda</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">
                Lanjut &rarr;
            </button>
        </form>
    </div>

    <div class="support-text">
        Memerlukan bantuan akses? <a href="#" class="support-link">Hubungi Administrator Sistem.</a>
    </div>

    <footer class="page-footer">
        &copy; 2026 SISTEM EVALUASI PANITIA EVENT KAMPUS
    </footer>

</div>

<script>
    document.getElementById('event_select').addEventListener('change', function() {
        const divisionSelect = document.getElementById('division_select');
        divisionSelect.innerHTML = '<option value="" disabled selected>Pilih divisi tugas Anda</option>';
        
        const selectedOption = this.options[this.selectedIndex];
        const divisionsStr = selectedOption.getAttribute('data-divisions');
        
        if (divisionsStr) {
            const divisions = JSON.parse(divisionsStr);
            divisions.forEach(div => {
                const option = document.createElement('option');
                option.value = div.id;
                option.textContent = div.name;
                divisionSelect.appendChild(option);
            });
        }
    });
</script>