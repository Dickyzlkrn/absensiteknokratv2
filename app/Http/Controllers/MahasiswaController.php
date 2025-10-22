<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->filled('nama_mhs')) {
            $query->where('nama_mhs', 'like', '%' . $request->nama_mhs . '%');
        }

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        $mahasiswa = $query->orderBy('nama_mhs')->paginate(5)->withQueryString();
        $prodi = DB::table('prodis')->pluck('prodi');

        return view('pages.admin.mahasiswaindex', compact('mahasiswa', 'prodi'));
    }

    public function store(Request $request)
    {
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->npm . '.' . $request->file('foto')->getClientOriginalExtension();
        }

        try {
            $data = [
                'npm' => $request->npm,
                'nama_mhs' => $request->nama_mhs,
                'prodi' => $request->prodi,
                'nohp_mhs' => $request->nohp_mhs,
                'tempat_pkl' => $request->tempat_pkl,
                'foto' => $foto,
                'password' => Hash::make('12345')
            ];

            DB::table('mahasiswa')->insert($data);

            if ($foto) {
                $request->file('foto')->storeAs('public/uploads/mahasiswa/', $foto);
            }

            return Redirect::back()->with(['success' => 'Data mahasiswa berhasil disimpan.']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan: ' . $e->getMessage()]);
        }
    }

    public function destroy($npm)
    {
        $mahasiswa = Mahasiswa::where('npm', $npm)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $mahasiswa->delete();
        return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx']);

        $path = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($data as $index => $row) {
            if ($index == 1 || empty($row['A'])) continue;
            $tempat = $row['G'] ?? 'Tidak Tersedia';

            Mahasiswa::updateOrCreate(
                ['npm' => $row['B']],
                [
                    'nama_mhs' => $row['C'],
                    'nohp_mhs' => $row['D'],
                    'prodi' => $row['E'],
                    'tempat_pkl' => $tempat,
                    'password' => Hash::make('12345')
                ]
            );
        }

        return back()->with('success', 'Data mahasiswa berhasil diimpor!');
    }
}
