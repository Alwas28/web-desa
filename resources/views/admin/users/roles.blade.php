@extends('layouts.admin')

@section('title', 'Atur Role — ' . $user->name)
@section('page-title', 'Atur Role Pengguna')
@section('page-sub', 'Tetapkan role untuk ' . $user->name)

@section('content')

<div class="flex items-center gap-3 flex-wrap">
  <a href="{{ route('admin.users.index') }}"
     class="inline-flex items-center gap-2 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-arrow-left text-sm"></i> Kembali
  </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

  {{-- ── Info Pengguna ──────────────────────────── --}}
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 space-y-4">
    <h2 class="font-semibold text-base">Info Pengguna</h2>

    <div class="flex items-center gap-4">
      <div class="grid place-items-center w-14 h-14 rounded-2xl bg-brand-600 text-white text-lg font-semibold flex-shrink-0">
        {{ strtoupper(substr($user->name, 0, 2)) }}
      </div>
      <div>
        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
        <div class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
        <div class="text-xs text-slate-400 mt-0.5">Bergabung {{ $user->created_at->format('d M Y') }}</div>
      </div>
    </div>

    <div>
      <h3 class="text-sm font-medium mb-2">Role Aktif Saat Ini</h3>
      @forelse($user->roles as $role)
        <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 mb-2">
          <i class="ti ti-shield-lock text-brand-600 dark:text-brand-100 mt-0.5"></i>
          <div>
            <div class="text-sm font-medium">{{ $role->label }}</div>
            @if($role->description)
              <div class="text-xs text-slate-400 mt-0.5">{{ $role->description }}</div>
            @endif
            <div class="text-xs text-slate-400 mt-1">{{ $role->permissions_count ?? $role->permissions()->count() }} hak akses</div>
          </div>
        </div>
      @empty
        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800 text-sm text-slate-400">
          Belum ada role yang ditetapkan.
        </div>
      @endforelse
    </div>

    {{-- Ringkasan permissions --}}
    @php $allPerms = $user->allPermissions(); @endphp
    @if($allPerms->count())
      <div>
        <h3 class="text-sm font-medium mb-2">
          Total Hak Akses <span class="text-slate-400 font-normal">({{ $allPerms->count() }} dari semua role)</span>
        </h3>
        <div class="flex flex-wrap gap-1.5">
          @foreach($allPerms->sortBy('name') as $perm)
            <span class="text-xs px-2 py-0.5 rounded-md bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-100 font-mono">
              {{ $perm->name }}
            </span>
          @endforeach
        </div>
      </div>
    @endif
  </div>

  {{-- ── Form Atur Role ─────────────────────────── --}}
  <div class="space-y-4">
    <h2 class="font-semibold text-base">Tetapkan Role</h2>

    <form method="POST" action="{{ route('admin.users.roles.update', $user) }}" class="space-y-3">
      @csrf @method('PUT')

      @foreach($roles as $role)
        @php $checked = in_array($role->id, $userRoles); @endphp
        <label id="rolecard-{{ $role->id }}"
          class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all
                 {{ $checked ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600' }}">
          <div class="mt-0.5 flex-shrink-0">
            <div class="toggle {{ $checked ? 'on' : '' }}" id="toggle-{{ $role->id }}"><i></i></div>
          </div>
          <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $checked ? 'checked' : '' }}
                 onchange="syncRoleCard({{ $role->id }}, this.checked)" class="sr-only">
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">{{ $role->label }}</div>
            @if($role->description)
              <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $role->description }}</div>
            @endif
            <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-400">
              <span>{{ $role->permissions_count }} hak akses</span>
              <a href="{{ route('admin.roles.permissions', $role) }}"
                 class="text-brand-600 dark:text-brand-100 font-medium hover:underline"
                 target="_blank" onclick="event.stopPropagation()">Lihat detail →</a>
            </div>
          </div>
        </label>
      @endforeach

      <button type="submit"
        class="w-full flex items-center justify-center gap-2 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
        <i class="ti ti-device-floppy text-base"></i> Simpan Penugasan Role
      </button>
    </form>
  </div>

</div>

@endsection

@section('scripts')
<script>
function syncRoleCard(id, checked) {
  const card   = document.getElementById('rolecard-' + id);
  const toggle = document.getElementById('toggle-' + id);
  toggle.classList.toggle('on', checked);
  if (checked) {
    card.classList.add('border-brand-500','bg-brand-50','dark:bg-brand-500/10');
    card.classList.remove('border-slate-200','dark:border-slate-700','hover:border-slate-300','dark:hover:border-slate-600');
  } else {
    card.classList.remove('border-brand-500','bg-brand-50','dark:bg-brand-500/10');
    card.classList.add('border-slate-200','dark:border-slate-700','hover:border-slate-300','dark:hover:border-slate-600');
  }
}
document.querySelectorAll('[id^="rolecard-"]').forEach(card => {
  card.addEventListener('click', e => {
    if (e.target.tagName === 'A') return;
    const cb = card.querySelector('input[type=checkbox]');
    cb.checked = !cb.checked;
    cb.dispatchEvent(new Event('change'));
  });
});
</script>
@endsection
