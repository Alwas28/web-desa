<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function user(): User
    {
        /** @var User */
        return Auth::user();
    }

    public function unreadCount()
    {
        return response()->json(['count' => $this->user()->unreadNotifications()->count()]);
    }

    public function dropdown()
    {
        $iconMap = [
            'selesai'      => '✅',
            'ditolak'      => '❌',
            'disetujui'    => '👍',
            'menunggu_ttd' => '✍️',
        ];

        $notifs = $this->user()->notifications()->latest()->take(8)->get()
            ->map(function ($n) use ($iconMap) {
                $data = $n->data;
                return [
                    'id'      => $n->id,
                    'message' => $data['message'] ?? '-',
                    'url'     => $data['url'] ?? '#',
                    'icon'    => $iconMap[$data['status'] ?? ''] ?? '📄',
                    'read_at' => $n->read_at,
                    'time'    => $n->created_at->locale('id')->diffForHumans(),
                ];
            });

        return response()->json($notifs);
    }

    public function index()
    {
        $user   = $this->user();
        $notifs = $user->notifications()->latest()->paginate(20);
        $user->unreadNotifications()->update(['read_at' => now()]);

        if ($user->isWarga()) {
            $desa     = Setting::forGroup('desa.');
            $penduduk = $user->penduduk_id
                ? Penduduk::with('kartuKeluarga')->find($user->penduduk_id)
                : null;
            return view('mobile.notifikasi', compact('notifs', 'desa', 'penduduk', 'user'));
        }

        return view('admin.notifikasi', compact('notifs'));
    }

    public function markRead(string $id)
    {
        $this->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function readAll()
    {
        $this->user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
