<?php

namespace App\Notifications;

use App\Models\PengajuanPenjual;
use Illuminate\Notifications\Notification;

class StatusPenjualUpdated extends Notification
{
    public function __construct(private PengajuanPenjual $pengajuan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $label   = $this->pengajuan->status_label;
        $icon    = $this->pengajuan->status === 'disetujui' ? '✅' : '❌';
        $message = $this->pengajuan->status === 'disetujui'
            ? "Selamat! Pengajuan penjual Anda telah disetujui. Anda kini dapat menjual produk di portal desa."
            : "Pengajuan penjual Anda ditolak. Silakan ajukan kembali dengan alasan yang lebih lengkap.";

        return [
            'type'         => 'status_penjual',
            'pengajuan_id' => $this->pengajuan->id,
            'status'       => $this->pengajuan->status,
            'status_label' => $label,
            'catatan'      => $this->pengajuan->catatan_admin,
            'message'      => "{$icon} {$message}",
            'url'          => route('portal.produk'),
        ];
    }
}
