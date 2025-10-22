<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Redirect;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    // ===================== INDEX =====================
    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $today = Carbon::now()->toDateString();

        // Ambil presensi hari ini
        $presensihariini = DB::table('presensi')
            ->where('npm', $mahasiswa->npm)
            ->where('tgl_presensi', $today)
            ->first();

        // Rekap bulan ini
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('
                SUM(IF(status="i",1,0)) as jmlizin,
                SUM(IF(status="s",1,0)) as jmlsakit
            ')
            ->where('npm', $mahasiswa->npm)
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahun])
            ->first();

        // Hitung hadir dari presensi
        $hadir = DB::table('presensi')
            ->where('npm', $mahasiswa->npm)
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahun])
            ->count();

        $rekapizin->hadir = $hadir;

        return view('pages.mahasiswa.presensi', compact('presensihariini', 'rekapizin'));
    }

    // ===================== STORE (ABSEN) =====================
    public function store(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $tgl_presensi = Carbon::now()->toDateString();
        $jam = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $lokasi = $request->lokasi;
        $catat_harian = $request->catat_harian;
        $image = $request->image;
        $type = $request->type; // 'in' for masuk, 'out' for pulang

        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Foto tidak boleh kosong']);
        }

        // Generate different filenames for masuk and pulang
        $fileName = $npm . "-" . $tgl_presensi . "-" . ($type === 'out' ? 'out' : 'in') . ".png";

        try {
            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            Storage::disk('public')->put('uploads/presensi/' . $fileName, $image_base64);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan foto: ' . $e->getMessage()]);
        }

        $presensi = DB::table('presensi')->where('npm', $npm)->where('tgl_presensi', $tgl_presensi)->first();

        if ($type === 'out') {
            // Absen pulang
            if (!$presensi) {
                return response()->json(['status' => 'error', 'message' => 'Belum absen masuk hari ini']);
            }

            if ($presensi->jam_out) {
                // Re-upload foto keluar tanpa mengubah jam
                $update = DB::table('presensi')->where('id', $presensi->id)->update([
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                    'catat_harian' => $catat_harian
                ]);

                if ($update) {
                    return response()->json(['status' => 'success', 'message' => 'Foto absen pulang berhasil diupdate', 'type' => 'out']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Gagal update foto pulang']);
                }
            } else {
                // Absen pulang pertama kali
                $update = DB::table('presensi')->where('id', $presensi->id)->update([
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                    'catat_harian' => $catat_harian
                ]);

                if ($update) {
                    return response()->json(['status' => 'success', 'message' => 'Terimakasih, Hati-Hati di Jalan', 'type' => 'out']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Gagal absen pulang']);
                }
            }
        } else {
            // Absen masuk
            if ($presensi) {
                // Update foto_in if record exists (allow re-upload)
                $update = DB::table('presensi')->where('id', $presensi->id)->update([
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ]);

                if ($update) {
                    return response()->json(['status' => 'success', 'message' => 'Foto absen masuk berhasil diupdate', 'type' => 'in']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Gagal update foto masuk']);
                }
            } else {
                // Insert new record
                $insert = DB::table('presensi')->insert([
                    'npm' => $npm,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi
                ]);

                if ($insert) {
                    return response()->json(['status' => 'success', 'message' => 'Selamat Beraktivitas', 'type' => 'in']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Gagal absen masuk']);
                }
            }
        }
    }

    // ===================== EDIT PROFILE =====================
    public function editprofile()
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();
        return view('pages.mahasiswa.editprofile', compact('mahasiswa'));
    }

    public function updateprofile(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $mahasiswa = DB::table('mahasiswa')->where('npm', $npm)->first();

        $foto = $request->hasFile('foto') ? $npm . '.' . $request->file('foto')->getClientOriginalExtension() : $mahasiswa->foto;

        $data = [
            'nama_mhs' => $request->nama_lengkap,
            'nohp_mhs' => $request->no_hp,
            'foto' => $foto
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $update = DB::table('mahasiswa')->where('npm', $npm)->update($data);

        if ($update && $request->hasFile('foto')) {
            $request->file('foto')->storeAs('public/uploads/mahasiswa/', $foto);
        }

        return $update
            ? Redirect::back()->with('success', 'Data berhasil diubah')
            : Redirect::back()->with('error', 'Data gagal diubah');
    }

    // ===================== HISTORI =====================
    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('pages.mahasiswa.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $npm = Auth::guard('mahasiswa')->user()->npm;

        $histori = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahun])
            ->where('presensi.npm', $npm)
            ->orderBy('tgl_presensi')
            ->select('presensi.*', 'mahasiswa.nama_mhs', 'mahasiswa.prodi')
            ->get();

        $totalSeconds = 0;
        foreach ($histori as $h) {
            if ($h->jam_in && $h->jam_out) {
                $totalSeconds += Carbon::parse($h->jam_out)->diffInSeconds(Carbon::parse($h->jam_in));
            }
        }

        $total_waktu = sprintf(
            '%02d:%02d:%02d',
            floor($totalSeconds / 3600),
            floor(($totalSeconds % 3600) / 60),
            $totalSeconds % 60
        );

        return view('pages.mahasiswa.gethistori', compact('histori', 'total_waktu'));
    }

    // ===================== IZIN =====================
    public function izin()
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;
        $dataizin = DB::table('pengajuan_izin')->where('npm', $npm)->get();
        return view('pages.mahasiswa.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('pages.mahasiswa.buatizin');
    }

    public function storeizin(Request $request)
    {
        $npm = Auth::guard('mahasiswa')->user()->npm;

        $insert = DB::table('pengajuan_izin')->insert([
            'id' => mt_rand(1, 999999999),
            'npm' => $npm,
            'tgl_izin' => $request->tgl_izin,
            'status' => $request->status,
            'lampiran' => $request->lampiran
        ]);

        return $insert
            ? redirect()->route('mahasiswa.izin')->with('success', 'Data berhasil disimpan')
            : redirect()->route('mahasiswa.izin')->with('error', 'Data gagal disimpan');
    }

    // ===================== MONITORING =====================
    public function monitoring()
    {
        return view('pages.mahasiswa.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select('presensi.*', 'mahasiswa.nama_mhs', 'mahasiswa.prodi')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('pages.mahasiswa.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->where('presensi.id', $id)
            ->first();

        return view('pages.mahasiswa.showmap', compact('presensi'));
    }

    // ===================== EXPORT / LAPORAN =====================
    public function export(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $npm = $request->npm;

        return Excel::download(new PresensiExport($bulan, $tahun, $npm), 'data_presensi.xlsx');
    }

    public function rekapMahasiswa(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $npm = Auth::guard('mahasiswa')->user()->npm;

        // Query dasar untuk presensi mahasiswa
        $query = DB::table('presensi')
            ->where('npm', $npm)
            ->orderBy('tgl_presensi', 'desc');

        // Filter berdasarkan bulan dan tahun jika ada
        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_presensi', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tgl_presensi', $request->tahun);
        }

        // Handle export Excel
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new PresensiExport($request->bulan, $request->tahun, $npm), 'rekap_presensi_' . $npm . '.xlsx');
        }

        // Handle print
        if ($request->has('print')) {
            $presensi = $query->get();
            return view('pages.mahasiswa.rekap_print', compact('presensi', 'namabulan', 'request'));
        }

        // Pagination untuk tampilan normal
        $presensi = $query->paginate(20);

        return view('pages.mahasiswa.rekap', compact('presensi', 'namabulan'));
    }

    // ===================== CETAK REKAP =====================
    public function cetakrekap(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select('presensi.*', 'mahasiswa.nama_mhs', 'mahasiswa.prodi')
            ->orderBy('presensi.tgl_presensi', 'desc');

        if ($bulan) {
            $query->whereMonth('presensi.tgl_presensi', $bulan);
        }

        if ($tahun) {
            $query->whereYear('presensi.tgl_presensi', $tahun);
        }

        $presensi = $query->get();

        // Handle export Excel
        if ($request->has('exportexcel')) {
            return Excel::download(new PresensiExport($bulan, $tahun), 'rekap_presensi_' . ($bulan ? $namabulan[$bulan] : 'semua') . '_' . ($tahun ?: date('Y')) . '.xlsx');
        }

        return view('pages.admin.cetakrekap', compact('presensi', 'namabulan', 'bulan', 'tahun'));
    }

    // ===================== CETAK LAPORAN =====================
    public function cetaklaporan(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $npm = $request->npm;

        $query = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select('presensi.*', 'mahasiswa.nama_mhs', 'mahasiswa.prodi')
            ->orderBy('presensi.tgl_presensi', 'desc');

        if ($npm) {
            $query->where('presensi.npm', $npm);
        }

        if ($bulan) {
            $query->whereMonth('presensi.tgl_presensi', $bulan);
        }

        if ($tahun) {
            $query->whereYear('presensi.tgl_presensi', $tahun);
        }

        $presensi = $query->get();

        // Handle export Excel
        if ($request->has('exportexcel')) {
            return Excel::download(new PresensiExport($bulan, $tahun, $npm), 'laporan_presensi_' . ($npm ?: 'semua') . '_' . ($bulan ? $namabulan[$bulan] : 'semua') . '_' . ($tahun ?: date('Y')) . '.xlsx');
        }

        return view('pages.admin.cetaklaporan', compact('presensi', 'namabulan', 'bulan', 'tahun', 'npm'));
    }

    // ===================== REKAP FORM =====================
    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Total students
        $totalMahasiswa = DB::table('mahasiswa')->count();

        // Total attendance records for current month
        $totalPresensi = DB::table('presensi')
            ->whereMonth('tgl_presensi', $currentMonth)
            ->whereYear('tgl_presensi', $currentYear)
            ->count();

        // Average attendance rate
        $totalWorkingDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $avgAttendanceRate = $totalMahasiswa > 0 ? round(($totalPresensi / ($totalMahasiswa * $totalWorkingDays)) * 100, 1) : 0;

        // Monthly attendance trend (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $count = DB::table('presensi')
                ->whereMonth('tgl_presensi', $month)
                ->whereYear('tgl_presensi', $year)
                ->count();
            $monthlyData[] = [
                'month' => $namabulan[(int)$month],
                'count' => $count
            ];
        }

        // Top 5 most active students this month
        $topStudents = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->select('mahasiswa.nama_mhs', DB::raw('COUNT(*) as total_presensi'))
            ->whereMonth('presensi.tgl_presensi', $currentMonth)
            ->whereYear('presensi.tgl_presensi', $currentYear)
            ->groupBy('presensi.npm', 'mahasiswa.nama_mhs')
            ->orderBy('total_presensi', 'desc')
            ->limit(5)
            ->get();

        return view('pages.admin.rekapindex', compact(
            'namabulan',
            'totalMahasiswa',
            'totalPresensi',
            'avgAttendanceRate',
            'monthlyData',
            'topStudents'
        ));
    }

    // ===================== LAPORAN FORM =====================
    public function laporan(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $mahasiswa = DB::table('mahasiswa')->get();

        // Get filter parameters
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $prodi = $request->input('prodi');
        $npm = $request->input('npm');

        // Base query for presensi
        $presensiQuery = DB::table('presensi')
            ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
            ->whereMonth('presensi.tgl_presensi', $bulan)
            ->whereYear('presensi.tgl_presensi', $tahun);

        // Apply filters
        if ($prodi) {
            $presensiQuery->where('mahasiswa.prodi', $prodi);
        }
        if ($npm) {
            $presensiQuery->where('presensi.npm', $npm);
        }

        // Total attendance records
        $totalPresensi = $presensiQuery->count();

        // Total izin records
        $izinQuery = DB::table('pengajuan_izin')
            ->join('mahasiswa', 'pengajuan_izin.npm', '=', 'mahasiswa.npm')
            ->whereMonth('pengajuan_izin.tgl_izin', $bulan)
            ->whereYear('pengajuan_izin.tgl_izin', $tahun);

        if ($prodi) {
            $izinQuery->where('mahasiswa.prodi', $prodi);
        }
        if ($npm) {
            $izinQuery->where('pengajuan_izin.npm', $npm);
        }

        $totalIzin = $izinQuery->count();

        // Attendance by program study
        $attendanceByProdi = $presensiQuery
            ->select('mahasiswa.prodi', DB::raw('COUNT(*) as total'))
            ->groupBy('mahasiswa.prodi')
            ->orderBy('total', 'desc')
            ->get();

        // Daily attendance trend
        $dailyData = [];
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($day = 1; $day <= $totalDays; $day++) {
            $date = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
            $count = DB::table('presensi')
                ->join('mahasiswa', 'presensi.npm', '=', 'mahasiswa.npm')
                ->where('presensi.tgl_presensi', $date);

            if ($prodi) {
                $count->where('mahasiswa.prodi', $prodi);
            }
            if ($npm) {
                $count->where('presensi.npm', $npm);
            }

            $count = $count->count();
            $dailyData[] = [
                'day' => $day,
                'count' => $count
            ];
        }

        // Students with perfect attendance
        $mahasiswaQuery = DB::table('mahasiswa');
        if ($prodi) {
            $mahasiswaQuery->where('prodi', $prodi);
        }
        if ($npm) {
            $mahasiswaQuery->where('npm', $npm);
        }

        $perfectAttendance = $mahasiswaQuery
            ->leftJoin('presensi', function($join) use ($bulan, $tahun) {
                $join->on('mahasiswa.npm', '=', 'presensi.npm')
                     ->whereMonth('presensi.tgl_presensi', $bulan)
                     ->whereYear('presensi.tgl_presensi', $tahun);
            })
            ->select('mahasiswa.nama_mhs', 'mahasiswa.prodi', DB::raw('COUNT(presensi.id) as attendance_count'))
            ->groupBy('mahasiswa.npm', 'mahasiswa.nama_mhs', 'mahasiswa.prodi')
            ->having('attendance_count', '>=', $totalDays)
            ->orderBy('mahasiswa.nama_mhs')
            ->get();

        // Get unique prodi for filter
        $prodiList = DB::table('mahasiswa')->select('prodi')->distinct()->orderBy('prodi')->get();

        return view('pages.admin.laporanindex', compact(
            'namabulan',
            'mahasiswa',
            'totalPresensi',
            'totalIzin',
            'attendanceByProdi',
            'dailyData',
            'perfectAttendance',
            'prodiList',
            'bulan',
            'tahun',
            'prodi',
            'npm'
        ));
    }
}
