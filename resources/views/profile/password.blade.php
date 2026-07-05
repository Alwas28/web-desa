@extends('layouts.admin')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')
@section('page-sub', 'Perbarui password akun Anda')

@section('content')
<div class="max-w-lg">
  <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
      <h2 class="font-semibold text-slate-800 dark:text-white">Ubah Password</h2>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Gunakan password yang panjang dan acak agar akun tetap aman</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="px-6 py-5 space-y-4">
      @csrf
      @method('put')

      {{-- Password Saat Ini --}}
      <div>
        <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
          Password Saat Ini
        </label>
        <input id="current_password" name="current_password" type="password"
               autocomplete="current-password"
               class="w-full h-10 px-3 rounded-xl border text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                      {{ $errors->updatePassword->has('current_password') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        @if($errors->updatePassword->has('current_password'))
          <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
        @endif
      </div>

      {{-- Password Baru --}}
      <div>
        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
          Password Baru
        </label>
        <input id="password" name="password" type="password"
               autocomplete="new-password"
               class="w-full h-10 px-3 rounded-xl border text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                      {{ $errors->updatePassword->has('password') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        @if($errors->updatePassword->has('password'))
          <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
        @endif
      </div>

      {{-- Konfirmasi Password --}}
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
          Konfirmasi Password Baru
        </label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               autocomplete="new-password"
               class="w-full h-10 px-3 rounded-xl border text-sm bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition
                      {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-400' : 'border-slate-200 dark:border-slate-700' }}">
        @if($errors->updatePassword->has('password_confirmation'))
          <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
        @endif
      </div>

      <div class="pt-1 flex items-center gap-4">
        <button type="submit"
          class="h-10 px-5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors flex items-center gap-2">
          <i class="ti ti-lock"></i> Perbarui Password
        </button>

        @if(session('status') === 'password-updated')
          <p class="text-sm text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
            <i class="ti ti-circle-check"></i> Password berhasil diperbarui
          </p>
        @endif
      </div>
    </form>
  </div>
</div>
@endsection
