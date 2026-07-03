@extends('layouts.admin')

@section('title', 'Role & Akses')
@section('page-title', 'Role & Akses')
@section('page-sub', 'Kelola role dan hak akses fitur per role')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

  {{-- ── Kiri: Daftar Role ─────────────────────────── --}}
  <div class="lg:col-span-2 space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-base">Daftar Role</h2>
    </div>

    @forelse($roles as $role)
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2.5 flex-wrap mb-1">
              <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $role->label }}</span>
              <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 px-2 py-0.5 rounded-md">{{ $role->name }}</code>
            </div>
            @if($role->description)
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">{{ $role->description }}</p>
            @endif
            <div class="flex items-center gap-4 text-sm text-slate-400">
              <span class="flex items-center gap-1.5">
                <i class="ti ti-key text-base"></i>
                <b class="text-slate-700 dark:text-slate-300">{{ $role->permissions_count }}</b> hak akses
              </span>
              <span class="flex items-center gap-1.5">
                <i class="ti ti-user-circle text-base"></i>
                <b class="text-slate-700 dark:text-slate-300">{{ $role->users_count }}</b> pengguna
              </span>
            </div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.roles.permissions', $role) }}"
               class="flex items-center gap-1.5 px-3 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium transition-colors">
              <i class="ti ti-settings text-sm"></i> Atur Akses
            </a>
            @if($role->users_count === 0)
              <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="m-0">
                @csrf @method('DELETE')
                <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Role \'{{ addslashes($role->label) }}\' akan dihapus permanen.', 'Hapus Role')"
                  class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                  <i class="ti ti-trash text-sm"></i> Hapus
                </button>
              </form>
            @else
              <button disabled
                class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-300 dark:text-slate-600 text-xs font-medium cursor-not-allowed"
                title="Role masih digunakan pengguna">
                <i class="ti ti-trash text-sm"></i> Hapus
              </button>
            @endif
          </div>
        </div>
      </div>
    @empty
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-10 text-center text-slate-400 dark:text-slate-500">
        <i class="ti ti-shield-off text-3xl block mb-2"></i>
        Belum ada role. Buat role baru di panel kanan.
      </div>
    @endforelse
  </div>

  {{-- ── Kanan: Form Buat Role ────────────────────── --}}
  <div class="space-y-4">
    <h2 class="font-semibold text-base">Buat Role Baru</h2>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1.5">
            Nama Role <span class="text-slate-400 font-normal">(huruf kecil &amp; underscore)</span>
          </label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none @error('name') !border-rose-500 @enderror"
                 type="text" name="name" value="{{ old('name') }}"
                 placeholder="contoh: editor_berita" pattern="[a-z_]+" required>
          @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
          <p class="text-xs text-slate-400 mt-1">Contoh: <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">operator</code>, <code class="bg-slate-100 dark:bg-slate-800 px-1 rounded">editor_berita</code></p>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Label Tampilan</label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none @error('label') !border-rose-500 @enderror"
                 type="text" name="label" value="{{ old('label') }}" placeholder="contoh: Editor Berita" required>
          @error('label')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">
            Deskripsi <span class="text-slate-400 font-normal">(opsional)</span>
          </label>
          <textarea class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none resize-none"
                    name="description" rows="3" placeholder="Jelaskan fungsi role ini…">{{ old('description') }}</textarea>
        </div>
        <button type="submit"
          class="w-full flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-plus text-base"></i> Buat Role
        </button>
      </form>
    </div>

    {{-- Panduan pola permission --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <h3 class="font-medium text-sm mb-3">Pola Hak Akses</h3>
      <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
        Setiap hak akses mengikuti pola <code class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-xs font-mono">aksi.fitur</code>.
        Tersedia 4 aksi:
      </p>
      <div class="grid grid-cols-2 gap-2">
        @foreach([['lihat','Membaca & melihat data'],['tambah','Menambah data baru'],['edit','Mengubah data'],['hapus','Menghapus data']] as [$act,$desc])
          <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800">
            <code class="text-xs font-semibold text-brand-600 dark:text-brand-100">{{ $act }}</code>
            <p class="text-xs text-slate-400 mt-0.5">{{ $desc }}</p>
          </div>
        @endforeach
      </div>
      <p class="text-xs text-slate-400 mt-3">
        Contoh:
        <code class="text-brand-600 dark:text-brand-100">edit.berita</code> ·
        <code class="text-brand-600 dark:text-brand-100">hapus.surat</code>
      </p>
    </div>
  </div>

</div>

@endsection
