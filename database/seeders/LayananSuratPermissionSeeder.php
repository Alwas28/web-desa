<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class LayananSuratPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            ['name' => 'lihat.layanan-surat',   'label' => 'Lihat Layanan Surat',           'group' => 'layanan-surat', 'action' => 'lihat'],
            ['name' => 'buat.layanan-surat',    'label' => 'Buat Pengajuan Surat',          'group' => 'layanan-surat', 'action' => 'buat'],
            ['name' => 'proses.layanan-surat',  'label' => 'Proses & Teruskan Surat',       'group' => 'layanan-surat', 'action' => 'proses'],
            ['name' => 'setujui.layanan-surat', 'label' => 'Setujui Surat (Kepala Desa)',   'group' => 'layanan-surat', 'action' => 'setujui'],
            ['name' => 'hapus.layanan-surat',   'label' => 'Hapus Pengajuan Surat',         'group' => 'layanan-surat', 'action' => 'hapus'],
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // Administrator: semua permission layanan-surat
        $admin = Role::where('name', 'administrator')->first();
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching(
                Permission::where('group', 'layanan-surat')->pluck('id')
            );
        }

        // Operator: lihat + buat + proses + hapus (tidak bisa setujui)
        $operator = Role::where('name', 'operator')->first();
        if ($operator) {
            $operator->permissions()->syncWithoutDetaching(
                Permission::where('group', 'layanan-surat')
                    ->whereIn('action', ['lihat', 'buat', 'proses', 'hapus'])
                    ->pluck('id')
            );
        }

        // Viewer: hanya lihat
        $viewer = Role::where('name', 'viewer')->first();
        if ($viewer) {
            $viewer->permissions()->syncWithoutDetaching(
                Permission::where('group', 'layanan-surat')->where('action', 'lihat')->pluck('id')
            );
        }

        // Kepala Desa: role baru — lihat + setujui
        $kades = Role::firstOrCreate(
            ['name' => 'kepala_desa'],
            ['label' => 'Kepala Desa', 'description' => 'Persetujuan surat keterangan warga']
        );
        $kades->permissions()->syncWithoutDetaching(
            Permission::where('group', 'layanan-surat')
                ->whereIn('action', ['lihat', 'setujui'])
                ->pluck('id')
        );
    }
}
