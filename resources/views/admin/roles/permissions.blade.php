@extends('layouts.admin')

@section('title', 'Hak Akses — ' . $role->label)
@section('page-title', 'Hak Akses: ' . $role->label)
@section('page-sub', 'Centang permission yang boleh dijalankan role ini')

@section('content')

{{-- Toolbar --}}
<div class="flex flex-wrap items-center gap-2">
  <a href="{{ route('admin.roles.index') }}"
     class="inline-flex items-center gap-2 px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-arrow-left text-sm"></i> Kembali ke Role
  </a>

  <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-sm text-slate-500 dark:text-slate-400">
    <span>Role:</span>
    <strong class="text-slate-900 dark:text-slate-100">{{ $role->label }}</strong>
    <code class="text-xs bg-white dark:bg-slate-700 px-1.5 py-0.5 rounded">{{ $role->name }}</code>
  </div>

  <div class="ml-auto text-sm text-slate-500 dark:text-slate-400">
    Terpilih: <strong id="countSelected" class="text-brand-600 dark:text-brand-100">{{ count($granted) }}</strong>
    dari {{ $permissions->flatten()->count() }}
  </div>
</div>

<form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}" id="permForm">
  @csrf @method('PUT')

  {{-- Quick actions --}}
  <div class="flex flex-wrap items-center gap-2">
    <button type="button" onclick="selectAll(true)"
      class="inline-flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-check text-sm"></i> Pilih Semua
    </button>
    <button type="button" onclick="selectAll(false)"
      class="inline-flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-x text-sm"></i> Hapus Semua
    </button>
    @foreach(['lihat','tambah','edit','hapus'] as $act)
      <button type="button" onclick="selectByAction('{{ $act }}')"
        class="px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        Semua <strong>{{ $act }}</strong>
      </button>
    @endforeach
    <button type="submit"
      class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
      <i class="ti ti-device-floppy text-base"></i> Simpan Hak Akses
    </button>
  </div>

  {{-- Permission groups --}}
  @foreach($permissions as $group => $perms)
    @php
      $labels = [
        'berita'=>'Berita & Info','surat'=>'Layanan Surat','pengaduan'=>'Pengaduan',
        'penduduk'=>'Kependudukan','bansos'=>'Bantuan Sosial','anggaran'=>'Transparansi Anggaran',
        'potensi'=>'Potensi Desa','bumdes'=>'BUMDes','peta'=>'Peta Desa',
        'download'=>'Download Center','ai'=>'AI Desa','pengaturan'=>'Pengaturan Sistem',
        'pengguna'=>'Manajemen Pengguna','role'=>'Manajemen Role',
      ];
      $groupName   = $labels[$group] ?? ucfirst($group);
      $actionOrder = ['lihat','tambah','edit','hapus'];
      $permsSorted = $perms->sortBy(fn($p) => array_search($p->action, $actionOrder));
    @endphp

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="flex items-center justify-between px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        <div class="flex items-center gap-2.5">
          <span class="font-semibold text-sm text-slate-900 dark:text-slate-100">{{ $groupName }}</span>
          <code class="text-xs text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $group }}</code>
        </div>
        <div class="flex items-center gap-1.5">
          <button type="button" onclick="selectGroup('{{ $group }}', true)"
            class="px-2.5 h-7 rounded-md border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            Semua
          </button>
          <button type="button" onclick="selectGroup('{{ $group }}', false)"
            class="px-2.5 h-7 rounded-md border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
            Tidak Ada
          </button>
        </div>
      </div>

      <table class="w-full text-sm">
        <thead class="text-left text-slate-400 dark:text-slate-500 text-xs">
          <tr class="border-b border-slate-100 dark:border-slate-800">
            <th class="px-5 py-2.5 font-medium">Aksi</th>
            <th class="px-5 py-2.5 font-medium">Permission</th>
            <th class="px-5 py-2.5 font-medium text-center w-20">Aktif</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($permsSorted as $perm)
            @php $isGranted = in_array($perm->id, $granted); @endphp
            <tr id="row-{{ $perm->id }}"
              class="transition-colors {{ $isGranted ? 'bg-brand-50/60 dark:bg-brand-500/5' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
              <td class="px-5 py-3 font-medium text-slate-700 dark:text-slate-300">{{ $perm->label }}</td>
              <td class="px-5 py-3">
                <code class="text-xs bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded font-mono text-slate-600 dark:text-slate-400"
                      data-action="{{ $perm->action }}" data-group="{{ $group }}">{{ $perm->name }}</code>
              </td>
              <td class="px-5 py-3 text-center">
                <label class="cursor-pointer inline-block">
                  <div class="toggle {{ $isGranted ? 'on' : '' }}" id="tgl-{{ $perm->id }}"><i></i></div>
                  <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                         {{ $isGranted ? 'checked' : '' }}
                         data-group="{{ $group }}" data-action="{{ $perm->action }}"
                         onchange="syncToggle({{ $perm->id }}, this.checked); updateCount()"
                         class="sr-only">
                </label>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endforeach

  <div class="flex justify-end pt-2">
    <button type="submit"
      class="flex items-center gap-2 px-5 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
      <i class="ti ti-device-floppy text-base"></i> Simpan Hak Akses
    </button>
  </div>
</form>

@endsection

@section('scripts')
<script>
function syncToggle(id, checked) {
  const tgl = document.getElementById('tgl-' + id);
  const row = document.getElementById('row-' + id);
  tgl.classList.toggle('on', checked);
  if (checked) {
    row.classList.add('bg-brand-50/60','dark:bg-brand-500/5');
    row.classList.remove('hover:bg-slate-50','dark:hover:bg-slate-800/50');
  } else {
    row.classList.remove('bg-brand-50/60','dark:bg-brand-500/5');
    row.classList.add('hover:bg-slate-50','dark:hover:bg-slate-800/50');
  }
}
function updateCount() {
  const n = document.querySelectorAll('#permForm input[type=checkbox]:checked').length;
  document.getElementById('countSelected').textContent = n;
}
function selectAll(checked) {
  document.querySelectorAll('#permForm input[type=checkbox]').forEach(cb => {
    cb.checked = checked;
    syncToggle(parseInt(cb.value), checked);
  });
  updateCount();
}
function selectGroup(group, checked) {
  document.querySelectorAll(`#permForm input[data-group="${group}"]`).forEach(cb => {
    cb.checked = checked;
    syncToggle(parseInt(cb.value), checked);
  });
  updateCount();
}
function selectByAction(action) {
  document.querySelectorAll(`#permForm input[data-action="${action}"]`).forEach(cb => {
    cb.checked = true;
    syncToggle(parseInt(cb.value), true);
  });
  updateCount();
}
</script>
@endsection
