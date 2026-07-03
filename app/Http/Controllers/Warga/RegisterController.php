<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /** Step 1 — Form verifikasi identitas */
    public function step1()
    {
        return view('warga.register.step1');
    }

    /** Step 1 POST — Verifikasi NIK, No KK, Tanggal Lahir */
    public function verifikasiIdentitas(Request $request)
    {
        $request->validate([
            'nik'           => ['required', 'digits:16'],
            'nomor_kk'      => ['required', 'digits:16'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
        ], [
            'nik.digits'           => 'NIK harus 16 digit angka.',
            'nomor_kk.digits'      => 'Nomor KK harus 16 digit angka.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak valid.',
        ]);

        $penduduk = Penduduk::where('nik', $request->nik)
            ->whereDate('tanggal_lahir', $request->tanggal_lahir)
            ->whereHas('kartuKeluarga', fn ($q) => $q->where('nomor_kk', $request->nomor_kk))
            ->first();

        if (! $penduduk) {
            return back()->withInput()->withErrors([
                'identitas' => 'Data tidak ditemukan. Pastikan NIK, Nomor KK, dan Tanggal Lahir sesuai KTP.',
            ]);
        }

        if ($penduduk->sudahPunyaAkun()) {
            return back()->withInput()->withErrors([
                'identitas' => 'NIK ini sudah terdaftar. Silakan login atau hubungi admin jika ada masalah.',
            ]);
        }

        // Simpan ID penduduk yang terverifikasi di session
        session(['warga_register_penduduk_id' => $penduduk->id]);

        return redirect()->route('warga.register.akun');
    }

    /** Step 2 — Form buat akun (email + password) */
    public function step2()
    {
        $pendudukId = session('warga_register_penduduk_id');

        if (! $pendudukId) {
            return redirect()->route('warga.register')->with('error', 'Sesi habis. Silakan verifikasi ulang.');
        }

        $penduduk = Penduduk::find($pendudukId);

        if (! $penduduk || $penduduk->sudahPunyaAkun()) {
            session()->forget('warga_register_penduduk_id');
            return redirect()->route('warga.register')->withErrors(['identitas' => 'Data tidak valid. Silakan coba kembali.']);
        }

        return view('warga.register.step2', compact('penduduk'));
    }

    /** Step 2 POST — Buat akun dan hubungkan ke penduduk */
    public function simpanAkun(Request $request)
    {
        $pendudukId = session('warga_register_penduduk_id');

        if (! $pendudukId) {
            return redirect()->route('warga.register')->with('error', 'Sesi habis. Silakan verifikasi ulang.');
        }

        $penduduk = Penduduk::find($pendudukId);

        if (! $penduduk || $penduduk->sudahPunyaAkun()) {
            session()->forget('warga_register_penduduk_id');
            return redirect()->route('warga.register')->withErrors(['identitas' => 'Data tidak valid.']);
        }

        $request->validate([
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.unique'       => 'Email ini sudah digunakan akun lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'penduduk_id' => $penduduk->id,
            'name'        => $penduduk->nama_lengkap,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
        ]);

        session()->forget('warga_register_penduduk_id');

        Auth::login($user);

        return redirect()->route('portal')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $penduduk->nama_lengkap . '.');
    }
}
