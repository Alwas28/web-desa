@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-sub', 'Edit informasi akun Anda')

@section('content')
<div class="max-w-lg">
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-slate-800 dark:text-white">Informasi Akun</h2>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perbarui nama dan alamat email akun Anda</p>
    </div>

    <form method="post" action="{{ route('profile.update') }}" class="px-6 py-5 space-y-4">
      @csrf
      @method('patch')

      {{-- Nama --}}
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama</label>
        <input id="name" name="name" type="text"
               value="{{ old('name', $user->name) }}"
               required autocomplete="name" autofocus
               class="w-full h-10 px-3 rounded-xl border text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                      {{ $errors->has('name') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        @error('name')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
        <input id="email" name="email" type="email"
               value="{{ old('email', $user->email) }}"
               required autocomplete="username"
               class="w-full h-10 px-3 rounded-xl border text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                      {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        @error('email')
          <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
      </div>

      <div class="pt-1 flex items-center gap-4">
        <button type="submit"
          class="h-10 px-5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors flex items-center gap-2">
          <i class="ti ti-device-floppy"></i> Simpan Perubahan
        </button>

        @if(session('status') === 'profile-updated')
          <p class="text-sm text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
            <i class="ti ti-circle-check"></i> Profil berhasil diperbarui
          </p>
        @endif
      </div>
    </form>
  </div>
</div>
@endsection
