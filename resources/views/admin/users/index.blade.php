@extends('layouts.admin')

@section('title', 'Pengguna')
@section('page-title', 'Pengguna')
@section('page-sub', 'Kelola akun yang dapat mengakses panel admin')

@section('content')

{{-- Header --}}
<div class="flex flex-wrap items-end justify-between gap-3">
  <p class="text-sm text-slate-500 dark:text-slate-400">Setiap pengguna dapat memiliki lebih dari satu role.</p>
  <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
    class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    <i class="ti ti-plus text-base"></i> Tambah Pengguna
  </button>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.users.index') }}"
  class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-[180px] max-w-xs px-3 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 border border-transparent focus-within:border-brand-500">
    <i class="ti ti-search text-slate-400 text-sm"></i>
    <input name="search" value="{{ request('search') }}" placeholder="Cari nama atau email…"
           class="bg-transparent outline-none text-sm w-full placeholder:text-slate-400 border-0 p-0 focus:ring-0">
  </div>
  <select name="role"
    class="h-9 px-3 rounded-lg bg-slate-100 dark:bg-slate-800 border border-transparent text-sm text-slate-700 dark:text-slate-300 focus:ring-0 focus:border-brand-500 py-0">
    <option value="">Semua Role</option>
    @foreach($roles as $role)
      <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->label }}</option>
    @endforeach
  </select>
  <button type="submit"
    class="px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
    Filter
  </button>
  @if(request()->hasAny(['search','role']))
    <a href="{{ route('admin.users.index') }}"
       class="px-3 h-9 inline-flex items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      Reset
    </a>
  @endif
</form>

{{-- Table --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left text-slate-400 dark:text-slate-500">
        <tr class="border-b border-slate-200 dark:border-slate-800">
          <th class="px-5 py-3 font-medium w-10">#</th>
          <th class="px-5 py-3 font-medium">Nama</th>
          <th class="px-5 py-3 font-medium">Email</th>
          <th class="px-5 py-3 font-medium">Role</th>
          <th class="px-5 py-3 font-medium">Bergabung</th>
          <th class="px-5 py-3 font-medium text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($users as $user)
          <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
            <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>
            <td class="px-5 py-3.5">
              <div class="flex items-center gap-3">
                <div class="grid place-items-center w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-100 text-xs font-semibold flex-shrink-0">
                  {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                  <div class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                  @if($user->id === auth()->id())
                    <div class="text-xs text-brand-600 dark:text-brand-100 font-medium">Anda</div>
                  @endif
                </div>
              </div>
            </td>
            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
            <td class="px-5 py-3.5">
              @forelse($user->roles as $role)
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-100 mr-1">
                  {{ $role->label }}
                </span>
              @empty
                <span class="text-slate-400 text-xs">—</span>
              @endforelse
            </td>
            <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
            <td class="px-5 py-3.5">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.users.roles', $user) }}"
                   class="grid place-items-center w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors"
                   title="Atur Role">
                  <i class="ti ti-shield-lock text-base"></i>
                </a>
                @if($user->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="m-0">
                    @csrf @method('DELETE')
                    <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Pengguna {{ addslashes($user->name) }} akan dihapus dari sistem.', 'Hapus Pengguna')"
                      class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 text-slate-400 hover:text-rose-600 transition-colors"
                      title="Hapus">
                      <i class="ti ti-trash text-base"></i>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-10 text-center text-slate-400 dark:text-slate-500">
              <i class="ti ti-users-off text-2xl block mb-2"></i>
              Tidak ada pengguna ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Pagination --}}
@if($users->hasPages())
  <div class="flex items-center gap-1 justify-center">
    @if($users->onFirstPage())
      <span class="px-3 h-9 grid place-items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-300 dark:text-slate-600">‹</span>
    @else
      <a href="{{ $users->previousPageUrl() }}"
         class="px-3 h-9 grid place-items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">‹</a>
    @endif

    @foreach($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
      @if($page == $users->currentPage())
        <span class="w-9 h-9 grid place-items-center rounded-lg text-sm font-semibold bg-brand-600 text-white">{{ $page }}</span>
      @else
        <a href="{{ $url }}"
           class="w-9 h-9 grid place-items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">{{ $page }}</a>
      @endif
    @endforeach

    @if($users->hasMorePages())
      <a href="{{ $users->nextPageUrl() }}"
         class="px-3 h-9 grid place-items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">›</a>
    @else
      <span class="px-3 h-9 grid place-items-center rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-300 dark:text-slate-600">›</span>
    @endif
  </div>
@endif

{{-- Modal Tambah Pengguna --}}
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
  <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
      <h3 class="font-semibold text-base">Tambah Pengguna</h3>
      <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
        class="grid place-items-center w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="px-6 py-5 space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1.5">Nama Lengkap</label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none @error('name') border-rose-500 @enderror"
                 type="text" name="name" value="{{ old('name') }}" placeholder="Nama pengguna" required>
          @error('name')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Email</label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none @error('email') border-rose-500 @enderror"
                 type="email" name="email" value="{{ old('email') }}" placeholder="email@desa.id" required>
          @error('email')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Password</label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none @error('password') border-rose-500 @enderror"
                 type="password" name="password" placeholder="Min. 8 karakter" required>
          @error('password')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Konfirmasi Password</label>
          <input class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
                 type="password" name="password_confirmation" placeholder="Ulangi password" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Role <span class="text-slate-400 font-normal">(opsional)</span></label>
          <div class="flex flex-wrap gap-2">
            @foreach($roles as $role)
              <label class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 cursor-pointer transition-colors
                            {{ in_array($role->id, old('roles',[])) ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-100' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300' }}"
                     id="rchip-{{ $role->id }}">
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                       {{ in_array($role->id, old('roles',[]))?'checked':'' }} class="sr-only"
                       onchange="toggleChip('rchip-{{ $role->id }}', this.checked)">
                <i class="ti ti-circle-check text-base {{ in_array($role->id, old('roles',[])) ? 'opacity-100' : 'opacity-0' }}" id="chk-{{ $role->id }}"></i>
                <span class="text-sm font-medium">{{ $role->label }}</span>
              </label>
            @endforeach
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
          class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          Simpan Pengguna
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
function toggleChip(id, checked) {
  const label = document.getElementById(id);
  const icon  = document.getElementById('chk-' + id.replace('rchip-',''));
  if (checked) {
    label.classList.add('border-brand-500','bg-brand-50','dark:bg-brand-500/10','text-brand-700','dark:text-brand-100');
    label.classList.remove('border-slate-200','dark:border-slate-700');
    icon?.classList.remove('opacity-0');
  } else {
    label.classList.remove('border-brand-500','bg-brand-50','dark:bg-brand-500/10','text-brand-700','dark:text-brand-100');
    label.classList.add('border-slate-200','dark:border-slate-700');
    icon?.classList.add('opacity-0');
  }
}
@if($errors->any())
  document.getElementById('modalTambah').classList.remove('hidden');
@endif
</script>
@endsection
