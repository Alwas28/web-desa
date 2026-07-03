<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Definisi semua permission.
     * Format: 'aksi.group' => ['label', 'group', 'action']
     */
    private function allPermissions(): array
    {
        $standard = ['lihat', 'tambah', 'edit', 'hapus'];

        // Group dengan aksi standar (lihat/tambah/edit/hapus)
        $standardGroups = [
            'penduduk'       => 'Data Penduduk',
            'kartu-keluarga' => 'Kartu Keluarga',
            'berita'         => 'Berita & Info',
            'pengumuman'     => 'Pengumuman',
            'page'           => 'Halaman (Page)',
            'arsip'          => 'Arsip Dokumen',
            'kategori-arsip' => 'Kategori Arsip',
            'idm'            => 'IDM',
            'apbdes'         => 'APBDes',
            'bansos'         => 'Bantuan Sosial',
            'peta'           => 'Peta Desa (GIS)',
            'kategori'       => 'Master Kategori',
            'jabatan'        => 'Master Jabatan',
            'periode'        => 'Master Periode',
            'pejabat'        => 'Master Pejabat Desa',
            'pengguna'       => 'Manajemen Pengguna',
            'role'           => 'Manajemen Role',
            'posyandu'       => 'Data Posyandu',
            'balita'         => 'Data Balita',
            'ibu-hamil'      => 'Data Ibu Hamil',
        ];

        $perms = [];
        foreach ($standardGroups as $group => $label) {
            foreach ($standard as $action) {
                $actionLabel = match ($action) {
                    'lihat'  => 'Lihat',
                    'tambah' => 'Tambah',
                    'edit'   => 'Edit',
                    'hapus'  => 'Hapus',
                };
                $perms["{$action}.{$group}"] = [
                    'label'  => "{$actionLabel} {$label}",
                    'group'  => $group,
                    'action' => $action,
                ];
            }
        }

        // Group dengan aksi custom
        $custom = [
            // Layanan Surat
            'lihat.layanan-surat'    => ['label' => 'Lihat Layanan Surat',         'group' => 'layanan-surat',    'action' => 'lihat'],
            'buat.layanan-surat'     => ['label' => 'Buat Pengajuan Surat',        'group' => 'layanan-surat',    'action' => 'buat'],
            'proses.layanan-surat'   => ['label' => 'Proses & Teruskan Surat',     'group' => 'layanan-surat',    'action' => 'proses'],
            'setujui.layanan-surat'  => ['label' => 'Setujui Surat (Kepala Desa)', 'group' => 'layanan-surat',    'action' => 'setujui'],
            'hapus.layanan-surat'    => ['label' => 'Hapus Pengajuan Surat',       'group' => 'layanan-surat',    'action' => 'hapus'],

            // Jenis Surat
            'lihat.surat-jenis'      => ['label' => 'Lihat Jenis Surat',           'group' => 'surat-jenis',      'action' => 'lihat'],
            'tambah.surat-jenis'     => ['label' => 'Tambah Jenis Surat',          'group' => 'surat-jenis',      'action' => 'tambah'],
            'edit.surat-jenis'       => ['label' => 'Edit Jenis Surat',            'group' => 'surat-jenis',      'action' => 'edit'],

            // Pengaturan Surat
            'lihat.surat-pengaturan' => ['label' => 'Lihat Pengaturan Surat',      'group' => 'surat-pengaturan', 'action' => 'lihat'],
            'edit.surat-pengaturan'  => ['label' => 'Edit Pengaturan Surat',       'group' => 'surat-pengaturan', 'action' => 'edit'],

            // Pasar Desa
            'lihat.pasar-desa'       => ['label' => 'Lihat Pasar Desa',            'group' => 'pasar-desa',       'action' => 'lihat'],
            'setujui.pasar-desa'     => ['label' => 'Setujui/Tolak Penjual',       'group' => 'pasar-desa',       'action' => 'setujui'],
            'hapus.pasar-desa'       => ['label' => 'Hapus Data Pasar',            'group' => 'pasar-desa',       'action' => 'hapus'],

            // Pengaturan Sistem
            'lihat.pengaturan'       => ['label' => 'Lihat Pengaturan Sistem',     'group' => 'pengaturan',       'action' => 'lihat'],
            'edit.pengaturan'        => ['label' => 'Edit Pengaturan Sistem',      'group' => 'pengaturan',       'action' => 'edit'],

            // AI
            'akses.ai'               => ['label' => 'Akses Fitur AI',              'group' => 'ai',               'action' => 'akses'],
        ];

        return array_merge($perms, $custom);
    }

    public function run(): void
    {
        // Seed semua permission
        foreach ($this->allPermissions() as $name => $data) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'label'  => $data['label'],
                    'group'  => $data['group'],
                    'action' => $data['action'],
                ]
            );
        }

        $this->seedDefaultRoles();
    }

    private function seedDefaultRoles(): void
    {
        // ── Administrator: semua permission ──────────────────────────
        $admin = Role::firstOrCreate(
            ['name' => 'administrator'],
            ['label' => 'Administrator', 'description' => 'Akses penuh ke seluruh sistem']
        );
        $admin->permissions()->sync(Permission::pluck('id'));

        // ── Kepala Desa: lihat + setujui surat ────────────────────────
        $kades = Role::firstOrCreate(
            ['name' => 'kepala_desa'],
            ['label' => 'Kepala Desa', 'description' => 'Persetujuan surat dan pemantauan desa']
        );
        $kadesPerms = Permission::where('action', 'lihat')
            ->orWhere('name', 'setujui.layanan-surat')
            ->orWhere('name', 'akses.ai')
            ->pluck('id');
        $kades->permissions()->sync($kadesPerms);

        // ── Operator: akses operasional harian ────────────────────────
        $operator = Role::firstOrCreate(
            ['name' => 'operator'],
            ['label' => 'Operator', 'description' => 'Akses operasional layanan harian']
        );
        $operatorPerms = Permission::whereIn('name', [
            // Kependudukan
            'lihat.penduduk','tambah.penduduk','edit.penduduk',
            'lihat.kartu-keluarga','tambah.kartu-keluarga','edit.kartu-keluarga',
            // Layanan Surat
            'lihat.layanan-surat','buat.layanan-surat','proses.layanan-surat','hapus.layanan-surat',
            // Jenis & Pengaturan Surat
            'lihat.surat-jenis','lihat.surat-pengaturan',
            // Berita & Pengumuman
            'lihat.berita','tambah.berita','edit.berita','hapus.berita',
            'lihat.pengumuman','tambah.pengumuman','edit.pengumuman','hapus.pengumuman',
            // Pasar Desa
            'lihat.pasar-desa','setujui.pasar-desa',
            // AI
            'akses.ai',
            // Statistik (lihat saja)
            'lihat.idm','lihat.apbdes',
            // Bansos
            'lihat.bansos','tambah.bansos','edit.bansos','hapus.bansos',
            // Kesehatan
            'lihat.posyandu','tambah.posyandu','edit.posyandu','hapus.posyandu',
            'lihat.balita','tambah.balita','edit.balita','hapus.balita',
            'lihat.ibu-hamil','tambah.ibu-hamil','edit.ibu-hamil','hapus.ibu-hamil',
            // Peta
            'lihat.peta','tambah.peta','edit.peta','hapus.peta',
        ])->pluck('id');
        $operator->permissions()->sync($operatorPerms);

        // ── Editor: kelola konten ─────────────────────────────────────
        $editor = Role::firstOrCreate(
            ['name' => 'editor'],
            ['label' => 'Editor', 'description' => 'Kelola konten berita, pengumuman, dan halaman']
        );
        $editorPerms = Permission::whereIn('name', [
            'lihat.berita','tambah.berita','edit.berita','hapus.berita',
            'lihat.pengumuman','tambah.pengumuman','edit.pengumuman','hapus.pengumuman',
            'lihat.page','tambah.page','edit.page','hapus.page',
            'lihat.arsip','tambah.arsip','edit.arsip','hapus.arsip',
            'lihat.kategori-arsip','tambah.kategori-arsip','edit.kategori-arsip','hapus.kategori-arsip',
            'akses.ai',
        ])->pluck('id');
        $editor->permissions()->sync($editorPerms);

        // ── Viewer: hanya bisa lihat ──────────────────────────────────
        $viewer = Role::firstOrCreate(
            ['name' => 'viewer'],
            ['label' => 'Viewer', 'description' => 'Hanya bisa melihat data']
        );
        $viewerPerms = Permission::where('action', 'lihat')
            ->orWhere('name', 'akses.ai')
            ->pluck('id');
        $viewer->permissions()->sync($viewerPerms);
    }
}
