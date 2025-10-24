<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Update Profile Mahasiswa
     */
    public function updateProfile(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $request->validate([
            'nama_mhs' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'nohp_mhs' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = [
            'nama_mhs' => $request->nama_mhs,
            'prodi' => $request->prodi,
            'nohp_mhs' => $request->nohp_mhs,
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/img'), $fotoName);
            $updateData['foto'] = $fotoName;
        }

        DB::table('mahasiswa')->where('npm', $mahasiswa->npm)->update($updateData);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Reset Password Mahasiswa
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();

        if (!Hash::check($request->current_password, $mahasiswa->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        DB::table('mahasiswa')
            ->where('npm', $mahasiswa->npm)
            ->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Index - List Mahasiswa untuk Admin
     */
    public function index(Request $request)
    {
        $nama_mhs = $request->nama_mhs;
        $prodi = $request->prodi;
        $kategori = $request->kategori;

        $mahasiswa = DB::table('mahasiswa')
            ->when($nama_mhs, function ($query) use ($nama_mhs) {
                return $query->where('nama_mhs', 'like', '%' . $nama_mhs . '%');
            })
            ->when($prodi, function ($query) use ($prodi) {
                return $query->where('prodi', $prodi);
            })
            ->when($kategori, function ($query) use ($kategori) {
                return $query->where('kategori', $kategori);
            })
            ->paginate(10);

        $prodi = DB::table('mahasiswa')->select('prodi')->distinct()->pluck('prodi');

        return view('pages.admin.mahasiswaindex', compact('mahasiswa', 'prodi'));
    }

    /**
     * Store - Tambah Mahasiswa Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'npm' => 'required|unique:mahasiswa',
            'nama_mhs' => 'required',
            'prodi' => 'required',
            'nohp_mhs' => 'required',
            'tempat_pkl' => 'nullable',
            'kategori' => 'required|in:PKL,Magang',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'npm' => $request->npm,
            'nama_mhs' => $request->nama_mhs,
            'prodi' => $request->prodi,
            'nohp_mhs' => $request->nohp_mhs,
            'tempat_pkl' => $request->tempat_pkl,
            'kategori' => $request->kategori,
            'password' => Hash::make($request->password ?: 'password123'),
        ];

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/img'), $fotoName);
            $data['foto'] = $fotoName;
        }

        DB::table('mahasiswa')->insert($data);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Update - Edit Mahasiswa
     */
    public function update(Request $request, $npm)
    {
        $request->validate([
            'nama_mhs' => 'required',
            'prodi' => 'required',
            'nohp_mhs' => 'required',
            'tempat_pkl' => 'nullable',
            'kategori' => 'required|in:PKL,Magang',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'nama_mhs' => $request->nama_mhs,
            'prodi' => $request->prodi,
            'nohp_mhs' => $request->nohp_mhs,
            'tempat_pkl' => $request->tempat_pkl,
            'kategori' => $request->kategori,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('assets/img'), $fotoName);
            $data['foto'] = $fotoName;
        }

        DB::table('mahasiswa')->where('npm', $npm)->update($data);

        return redirect()->back()->with('success', 'Mahasiswa berhasil diperbarui!');
    }

    /**
     * Destroy - Hapus Mahasiswa
     */
    public function destroy($npm)
    {
        // Hapus data presensi terkait terlebih dahulu
        DB::table('presensi')->where('npm', $npm)->delete();

        // Hapus data pengajuan izin terkait
        DB::table('pengajuan_izin')->where('npm', $npm)->delete();

        // Hapus data mahasiswa
        DB::table('mahasiswa')->where('npm', $npm)->delete();

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus!');
    }

    /**
     * Export Format Excel Mahasiswa
     */
    public function exportFormat(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MahasiswaExport, 'format_import_mahasiswa.xlsx');
    }

    /**
     * Import Excel Mahasiswa
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\MahasiswaImport, $request->file('file'));

            return redirect()->back()->with('success', 'Import data mahasiswa berhasil! Semua data telah dimasukkan ke database.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return redirect()->back()->withErrors($errors);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Import gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Store Pengajuan Izin
     */
    public function storeIzin(Request $request)
    {
        $request->validate([
            'tgl_izin' => 'required|date|after_or_equal:today',
            'status' => 'required|in:i,s',
            'deskripsi_izin' => 'required|string|max:500'
        ]);

        $mahasiswa = Auth::guard('mahasiswa')->user();

        // Cek apakah sudah ada pengajuan izin untuk tanggal tersebut
        $existingIzin = DB::table('pengajuan_izin')
            ->where('npm', $mahasiswa->npm)
            ->where('tgl_izin', $request->tgl_izin)
            ->first();

        if ($existingIzin) {
            return redirect()->route('mahasiswa.izin')->with('error', 'Anda sudah mengajukan izin untuk tanggal tersebut.');
        }

        $insert = DB::table('pengajuan_izin')->insert([
            'npm' => $mahasiswa->npm,
            'tgl_izin' => $request->tgl_izin,
            'status' => $request->status,
            'lampiran' => '',
            'deskripsi_izin' => $request->deskripsi_izin,
            'status_approved' => '0', // 0 = pending, 1 = approved, 2 = rejected
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($insert) {
            return redirect()->route('mahasiswa.izin')->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
        } else {
            return redirect()->route('mahasiswa.izin')->with('error', 'Gagal mengirim pengajuan izin.');
        }
    }
}
