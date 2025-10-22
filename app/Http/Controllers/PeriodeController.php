<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Menampilkan daftar periode PKL
     */
    public function index()
    {
        $periodes = Periode::orderBy('tanggal_mulai', 'desc')->get();
        return view('pages.admin.periodepkl', compact('periodes'));

    }

    /**
     * Menyimpan periode baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Periode::create($request->only(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->back()->with('success', 'Periode berhasil ditambahkan.');
    }

    /**
     * Memperbarui periode
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $periode = Periode::findOrFail($id);
        $periode->update($request->only(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->back()->with('success', 'Periode berhasil diperbarui.');
    }

    /**
     * Menghapus periode
     */
    public function destroy($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->delete();

        return redirect()->back()->with('success', 'Periode berhasil dihapus.');
    }
}
