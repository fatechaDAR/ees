<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Evaluasi - {{ $user->name }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 2px solid #792131; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #792131; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px 0; }
        .info-label { font-weight: bold; width: 150px; color: #555; }
        
        .kpi-section { margin-bottom: 30px; padding: 20px; background: #f8f4f0; border-radius: 8px; border: 1px solid #e5e7eb; }
        .kpi-box { display: inline-block; width: 45%; margin-right: 4%; vertical-align: top; }
        .kpi-title { font-size: 12px; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 10px; }
        .kpi-value { font-size: 28px; font-weight: bold; color: #792131; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { padding: 12px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        .table th { background-color: #f4f4f4; color: #333; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Hasil Evaluasi Pribadi</h1>
        <p>Sistem Evaluasi Panitia Event Kampus</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Panitia</td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Event</td>
            <td>: {{ $eventName }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Cetak</td>
            <td>: {{ date('d F Y') }}</td>
        </tr>
    </table>

    <div class="kpi-section">
        <div class="kpi-box">
            <div class="kpi-title">Rata-rata Nilai Performa</div>
            <div class="kpi-value">{{ number_format($avgScore, 2) }} <span style="font-size:16px; color:#666;">/ 5.00</span></div>
            <div style="margin-top: 5px; font-size: 14px; font-weight: bold; color: {{ $avgScore >= 3.5 ? '#166534' : '#b91c1c' }}">
                Predikat: {{ $avgScore >= 4.5 ? 'SANGAT BAIK' : ($avgScore >= 3.5 ? 'BAIK' : ($avgScore >= 2.5 ? 'CUKUP' : 'KURANG')) }}
            </div>
        </div>
        <div class="kpi-box" style="margin-right: 0;">
            <div class="kpi-title">Peringkat Panitia</div>
            <div class="kpi-value">#{{ $rank }} <span style="font-size:16px; color:#666;">/ {{ $totalPanitia }}</span></div>
            <div style="margin-top: 5px; font-size: 14px; color: #555;">Berdasarkan total rata-rata skor seluruh panitia aktif.</div>
        </div>
    </div>

    <h3 style="color: #333; margin-bottom: 10px;">Detail Riwayat Penilaian ({{ $evaluations->count() }})</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Penilai</th>
                <th width="50%">Komentar / Catatan</th>
                <th width="10%">Skor</th>
                <th width="15%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evaluations as $index => $eval)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $eval->evaluator->name ?? 'Evaluator' }}</td>
                <td style="font-style: italic;">"{{ $eval->feedback ?? 'Tidak ada catatan tambahan' }}"</td>
                <td style="text-align: center; font-weight: bold; color: #792131;">{{ number_format($eval->final_score, 1) }}</td>
                <td style="font-size: 12px; text-align: center;">{{ $eval->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999; padding: 20px;">Belum ada data evaluasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis dari Sistem Evaluasi Panitia (Evalytics) &copy; {{ date('Y') }}
    </div>

</body>
</html>
