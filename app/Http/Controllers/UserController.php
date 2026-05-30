<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        $search = $request->get('search');

        // Data KPI Global (untuk info tetap)
        $globalTotal = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        
        // Query Daftar User
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Hitung Total Berdasarkan Filter untuk KPI
        $filteredTotal = (clone $query)->count();

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('manajemen_user.index', compact('globalTotal', 'totalAdmin', 'filteredTotal', 'users'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('profile_photos', 'public');
            
            $user->update([
                'profile_photo' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
