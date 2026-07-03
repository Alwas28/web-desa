<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPenjual;
use App\Models\Produk;
use App\Models\User;
use App\Notifications\PengajuanPenjualBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /** Submit permohonan jadi penjual */
    public function ajukan(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $existing = PengajuanPenjual::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->first();

        if ($existing?->status === 'disetujui') {
            return back()->with('error', 'Anda sudah terverifikasi sebagai penjual.');
        }
        if ($existing?->status === 'menunggu') {
            return back()->with('error', 'Pengajuan Anda sedang dalam proses review oleh admin.');
        }

        $request->validate([
            'alasan' => ['required', 'string', 'min:20', 'max:1000'],
        ], [
            'alasan.required' => 'Alasan tidak boleh kosong.',
            'alasan.min'      => 'Alasan minimal 20 karakter agar admin dapat meninjau dengan baik.',
        ]);

        $pengajuan = PengajuanPenjual::create([
            'user_id'     => $user->id,
            'penduduk_id' => $user->penduduk_id,
            'alasan'      => $request->alasan,
            'status'      => 'menunggu',
        ]);

        $admins = User::whereHas('roles')->get();
        Notification::send($admins, new PengajuanPenjualBaru($pengajuan));

        return back()->with('success', 'Pengajuan berhasil dikirim! Admin akan segera meninjau permohonan Anda.');
    }

    /** Simpan produk baru */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $this->isVerified($user)) {
            return back()->with('error', 'Anda belum terverifikasi sebagai penjual.');
        }

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:150'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'satuan'    => ['required', 'string', 'max:50'],
            'nomor_wa'  => ['required', 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'foto'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama.required'     => 'Nama produk wajib diisi.',
            'harga.required'    => 'Harga wajib diisi.',
            'harga.numeric'     => 'Harga harus berupa angka.',
            'satuan.required'   => 'Satuan wajib diisi.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'foto.image'        => 'File harus berupa gambar.',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create(array_merge($data, [
            'user_id'     => $user->id,
            'penduduk_id' => $user->penduduk_id,
            'status'      => 'aktif',
        ]));

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    /** Update produk */
    public function update(Request $request, Produk $produk)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($produk->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:150'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'satuan'    => ['required', 'string', 'max:50'],
            'nomor_wa'  => ['required', 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'foto'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'File harus berupa gambar.',
            'foto.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    /** Toggle status aktif/nonaktif */
    public function toggle(Produk $produk)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($produk->user_id !== $user->id) {
            abort(403);
        }

        $produk->update([
            'status' => $produk->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        $label = $produk->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$label}.");
    }

    /** Hapus produk */
    public function destroy(Produk $produk)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($produk->user_id !== $user->id) {
            abort(403);
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function isVerified(User $user): bool
    {
        return PengajuanPenjual::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->exists();
    }
}
