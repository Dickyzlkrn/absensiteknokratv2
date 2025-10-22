<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\mpMahasiswaController;
use App\Http\Controllers\PresensiController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// ===================================================
// == LOGIN / LOGOUT
// ===================================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Halaman login mahasiswa & admin
Route::get('auth/loginmahasiswa', function () {
    return view('pages.auth.loginmahasiswa');
})->name('login.mahasiswa.view');

Route::get('auth/login', function () {
    return view('pages.auth.login');
})->name('login.admin.view');

// Proses Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('login.mahasiswa');
Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin'])->name('login.admin');
Route::post('/logout', [AuthController::class, 'proseslogout'])->name('logout');
Route::post('/logoutadmin', [AuthController::class, 'proseslogoutadmin'])->name('logoutadmin');

// ===================================================
// == MAHASISWA AREA
// ===================================================
Route::prefix('mahasiswa')->middleware('auth:mahasiswa')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('mahasiswa.dashboard');

    // Presensi
    Route::get('/presensi', [PresensiController::class, 'index'])->name('mahasiswa.presensi');

    // Rekap Presensi
    Route::get('/rekap', [PresensiController::class, 'rekapMahasiswa'])->name('mahasiswa.rekap');

    // Izin Mahasiswa ✅
    Route::view('/izin', 'pages.mahasiswa.izin')->name('mahasiswa.izin');

    // Histori Izin ✅
    Route::view('/histori', 'pages.mahasiswa.histori')->name('mahasiswa.histori');

    // Profil Akun ✅
    Route::view('/profil', 'pages.mahasiswa.profil')->name('mahasiswa.profil');

    // Akun ✅
    Route::view('/akun', 'pages.mahasiswa.akun')->name('mahasiswa.akun');

    // Update Profile & Password
    Route::post('/updateprofile', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.updateprofile');
    Route::post('/resetpassword', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.resetpassword');

    // Logout Mahasiswa
    Route::post('/logout', [AuthController::class, 'proseslogout'])->name('logoutmahasiswa');

    // Route untuk Presensi Cepat AJAX
    Route::post('/mahasiswa/presensi/store', [\App\Http\Controllers\PresensiController::class, 'store'])
        ->name('mahasiswa.storepresensi');
});

// ===================================================
// == ADMIN AREA
// ===================================================
Route::prefix('admin')->middleware('auth:web')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboardadmin'])->name('admin.dashboard');

    // Daftar Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
    Route::put('/mahasiswa/{npm}', [MahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
    Route::delete('/mahasiswa/{npm}', [MahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');
    Route::post('/mahasiswa/import', [MahasiswaController::class, 'importExcel'])->name('admin.mahasiswa.import');

    // Presensi
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan'])->name('admin.presensi.laporan');
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap'])->name('admin.presensi.rekap');

    // Periode PKL
    Route::get('/periodepkl', [PeriodeController::class, 'index'])->name('admin.periodepkl');
    Route::post('/periodepkl', [PeriodeController::class, 'store'])->name('admin.periodepkl.store');
    Route::put('/periodepkl/{id}', [PeriodeController::class, 'update'])->name('admin.periodepkl.update');
    Route::delete('/periodepkl/{id}', [PeriodeController::class, 'destroy'])->name('admin.periodepkl.destroy');

    // Maps & Foto Mahasiswa
    Route::get('/mpmahasiswa', [mpMahasiswaController::class, 'index'])->name('admin.mpmahasiswa');

    // Setting Akun Admin
    Route::get('/setting', [SettingController::class, 'index'])->name('admin.settingakun');
    Route::post('/ubah-password', [SettingController::class, 'ubahPassword'])->name('admin.ubah.password');
});
