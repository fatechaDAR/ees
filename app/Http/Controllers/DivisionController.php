<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\CommitteeMember;
use App\Models\User;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        // Mengambil total divisi dari database
        $totalDivisi = Division::count();

        // Mengambil total panitia (role: panitia) dari database
        $totalPanitia = User::where('role', 'panitia')->count();

        // Rerata panitia per divisi
        $avgPerDivisi = $totalDivisi > 0 ? round($totalPanitia / $totalDivisi, 1) : 0;

        // Mengambil daftar divisi beserta jumlah anggotanya
        $divisiList = Division::withCount('committeeMembers')
            ->latest()
            ->paginate(10);

        return view('manajemen_divisi.index', compact(
            'totalDivisi',
            'totalPanitia',
            'avgPerDivisi',
            'divisiList'
        ));
    }
}
