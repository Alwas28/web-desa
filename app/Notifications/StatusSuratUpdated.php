<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusSuratUpdated extends Notification
{
    use Queueable;

    private static array $labels = [
        'menunggu_ttd' => 'Menunggu Tanda Tangan',
        'disetujui'    => 'Disetujui',
        'selesai'      => 'Selesai',
        'ditolak'      => 'Ditolak',
        'diajukan'     => 'Diajukan',
    ];

    private static array $messages = [
        'menunggu_ttd' => 'sedang menunggu tanda tangan pejabat',
        'disetujui'    => 'telah disetujui',
        'selesai'      => 'telah selesai diproses dan siap diunduh',
        'ditolak'      => 'tidak dapat diproses',
        'diajukan'     => 'sedang dalam antrian',
    ];

    public function __construct(private PengajuanSurat $pengajuan) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status  = $this->pengajuan->status;
        $kalimat = self::$messages[$status] ?? 'diperbarui';

        return [
            'pengajuan_id' => $this->pengajuan->id,
            'jenis_surat'  => $this->pengajuan->jenis_surat,
            'status'       => $status,
            'status_label' => self::$labels[$status] ?? $status,
            'catatan'      => $this->pengajuan->catatan,
            'message'      => 'Surat ' . $this->pengajuan->jenis_surat . ' Anda ' . $kalimat . '.',
            'url'          => route('portal.layanan'),
        ];
    }
}
