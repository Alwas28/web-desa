<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanSuratBaru extends Notification
{
    use Queueable;

    public function __construct(private PengajuanSurat $pengajuan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_surat'   => $this->pengajuan->jenis_surat,
            'nama_penduduk' => $this->pengajuan->penduduk?->nama_lengkap ?? 'Warga',
            'message'       => 'Pengajuan baru dari ' . ($this->pengajuan->penduduk?->nama_lengkap ?? 'Warga')
                                . ': ' . $this->pengajuan->jenis_surat,
            'url'           => route('admin.layanan-surat.index'),
        ];
    }
}
