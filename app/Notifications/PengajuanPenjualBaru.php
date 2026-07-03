<?php

namespace App\Notifications;

use App\Models\PengajuanPenjual;
use Illuminate\Notifications\Notification;

class PengajuanPenjualBaru extends Notification
{
    public function __construct(private PengajuanPenjual $pengajuan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $nama = $this->pengajuan->penduduk?->nama_lengkap
            ?? $this->pengajuan->user->name;

        return [
            'type'           => 'pengajuan_penjual',
            'pengajuan_id'   => $this->pengajuan->id,
            'nama_warga'     => $nama,
            'message'        => "{$nama} mengajukan persetujuan sebagai penjual produk.",
            'url'            => route('admin.penjual.index'),
        ];
    }
}
