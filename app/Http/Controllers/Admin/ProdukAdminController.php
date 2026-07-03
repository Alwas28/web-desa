<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = Produk::with('penduduk', 'user')->latest();

        if ($s = $request->input('q')) {
            $q->where('nama', 'like', "%{$s}%");
        }
        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }

        $produks      = $q->paginate(20)->withQueryString();
        $totalAktif   = Produk::where('status', 'aktif')->count();
        $totalNonaktif = Produk::where('status', 'nonaktif')->count();

        return view('admin.produk.index', compact('produks', 'totalAktif', 'totalNonaktif'));
    }
}
