@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan sistem {{ $desaNama }}')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
  <div class="p-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between">
      <span class="text-sm text-slate-500 dark:text-slate-400">Total Pengguna</span>
      <span class="grid place-items-center w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-100">
        <i class="ti ti-user-circle"></i>
      </span>
    </div>
    <div class="mt-3 text-3xl font-semibold">{{ $statUsers }}</div>
    <a href="{{ route('admin.users.index') }}" class="mt-1 text-xs text-brand-600 dark:text-brand-100 hover:underline inline-flex items-center gap-1">
      Kelola pengguna <i class="ti ti-arrow-right text-sm"></i>
    </a>
  </div>

  <div class="p-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between">
      <span class="text-sm text-slate-500 dark:text-slate-400">Role Terdaftar</span>
      <span class="grid place-items-center w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
        <i class="ti ti-shield-lock"></i>
      </span>
    </div>
    <div class="mt-3 text-3xl font-semibold">{{ $statRoles }}</div>
    <a href="{{ route('admin.roles.index') }}" class="mt-1 text-xs text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-1">
      Kelola role <i class="ti ti-arrow-right text-sm"></i>
    </a>
  </div>

  <div class="p-5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between">
      <span class="text-sm text-slate-500 dark:text-slate-400">Hak Akses</span>
      <span class="grid place-items-center w-9 h-9 rounded-lg bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400">
        <i class="ti ti-key"></i>
      </span>
    </div>
    <div class="mt-3 text-3xl font-semibold">{{ $statPermissions }}</div>
    <div class="mt-1 text-xs text-slate-400">permission terdaftar di sistem</div>
  </div>
</div>

{{-- Welcome + Module status --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

  <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
    <h2 class="font-semibold text-base mb-1">Selamat datang di Panel Admin</h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
      Sistem manajemen pengguna dan hak akses sudah aktif. Fitur-fitur desa lainnya sedang dalam pengembangan dan akan segera tersedia.
    </p>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('admin.users.index') }}"
         class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
        <i class="ti ti-user-plus text-base"></i> Tambah Pengguna
      </a>
      <a href="{{ route('admin.roles.index') }}"
         class="inline-flex items-center gap-2 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-shield-lock text-base"></i> Kelola Role
      </a>
      <a href="{{ route('admin.settings.index') }}"
         class="inline-flex items-center gap-2 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-settings text-base"></i> Pengaturan
      </a>
    </div>
  </div>

  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
    <h2 class="font-semibold text-base mb-4">Status Modul</h2>
    <ul class="space-y-3 text-sm">
      @foreach([
        ['Autentikasi & sesi',   true,  'ti-circle-check', 'text-brand-600 dark:text-brand-100',  'bg-brand-50 dark:bg-brand-500/10'],
        ['Manajemen Pengguna',   true,  'ti-circle-check', 'text-brand-600 dark:text-brand-100',  'bg-brand-50 dark:bg-brand-500/10'],
        ['Role & Permission',    true,  'ti-circle-check', 'text-brand-600 dark:text-brand-100',  'bg-brand-50 dark:bg-brand-500/10'],
        ['Layanan Surat',        false, 'ti-clock',         'text-amber-600 dark:text-amber-400', 'bg-amber-50 dark:bg-amber-500/10'],
        ['Kependudukan',         false, 'ti-clock',         'text-amber-600 dark:text-amber-400', 'bg-amber-50 dark:bg-amber-500/10'],
        ['AI Desa',              false, 'ti-clock',         'text-violet-600 dark:text-violet-400','bg-violet-50 dark:bg-violet-500/10'],
      ] as [$label, $active, $icon, $iconColor, $iconBg])
      <li class="flex items-center gap-3">
        <span class="grid place-items-center w-7 h-7 rounded-full flex-shrink-0 {{ $iconBg }}">
          <i class="ti {{ $icon }} text-sm {{ $iconColor }}"></i>
        </span>
        <span class="{{ $active ? '' : 'text-slate-400 dark:text-slate-500' }}">{{ $label }}</span>
        @if($active)
          <span class="ml-auto text-xs font-semibold text-brand-600 dark:text-brand-100 bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 rounded">Aktif</span>
        @else
          <span class="ml-auto text-xs font-medium text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">Segera</span>
        @endif
      </li>
      @endforeach
    </ul>
  </div>
</div>

@endsection
