<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\Request;

class NavItemController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'label'     => 'required|string|max:100',
            'parent_id' => 'nullable|exists:nav_items,id',
            'urutan'    => 'required|integer|min:0|max:255',
            'aktif'     => 'nullable|boolean',
            'tipe_link'         => 'required|in:custom,page,arsip',
            'link_url'          => 'nullable|string|max:500',
            'page_id'           => 'nullable|exists:pages,id',
            'target'            => 'required|in:_self,_blank',
            'arsip_filter'      => 'nullable|in:semua,kategori',
            'kategori_arsip_id' => 'nullable|exists:kategori_arsips,id',
        ]);

        $data['aktif']        = $request->boolean('aktif');
        $data['arsip_filter'] = $data['arsip_filter'] ?? 'semua';

        NavItem::create($data);

        return back()->with('success', 'Item menu berhasil ditambahkan.');
    }

    public function update(Request $request, NavItem $navItem)
    {
        $data = $request->validate([
            'label'     => 'required|string|max:100',
            'parent_id' => 'nullable|exists:nav_items,id',
            'urutan'    => 'required|integer|min:0|max:255',
            'aktif'     => 'nullable|boolean',
            'tipe_link'         => 'required|in:custom,page,arsip',
            'link_url'          => 'nullable|string|max:500',
            'page_id'           => 'nullable|exists:pages,id',
            'target'            => 'required|in:_self,_blank',
            'arsip_filter'      => 'nullable|in:semua,kategori',
            'kategori_arsip_id' => 'nullable|exists:kategori_arsips,id',
        ]);

        $data['aktif']        = $request->boolean('aktif');
        $data['arsip_filter'] = $data['arsip_filter'] ?? 'semua';

        // Cegah item menjadi child dari dirinya sendiri
        if ($data['parent_id'] == $navItem->id) {
            $data['parent_id'] = null;
        }

        $navItem->update($data);

        return back()->with('success', 'Item menu berhasil diperbarui.');
    }

    public function destroy(NavItem $navItem)
    {
        // Hapus juga children (menjadi orphan karena nullOnDelete di migration)
        $navItem->children()->delete();
        $navItem->delete();

        return back()->with('success', 'Item menu berhasil dihapus.');
    }
}
