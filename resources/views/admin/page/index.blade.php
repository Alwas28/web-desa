@extends('layouts.admin')

@section('title', 'Page')
@section('page-title', 'Page')
@section('page-sub', 'Kelola halaman statis website desa')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-3 gap-3 max-w-lg">
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-file-text text-brand-600 dark:text-brand-100"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $total }}</div>
      <div class="text-xs text-slate-400">Total</div>
    </div>
  </div>
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-circle-check text-emerald-600 dark:text-emerald-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $publish }}</div>
      <div class="text-xs text-slate-400">Diterbitkan</div>
    </div>
  </div>
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-pencil text-amber-600 dark:text-amber-400"></i>
    </div>
    <div>
      <div class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $draft }}</div>
      <div class="text-xs text-slate-400">Draft</div>
    </div>
  </div>
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.page.index') }}"
      class="flex flex-wrap items-center gap-2">
  <div class="flex items-center gap-2 flex-1 min-w-0 max-w-xs px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
    <i class="ti ti-search text-slate-400 text-sm flex-shrink-0"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul…"
      class="bg-transparent outline-none text-sm w-full border-0 p-0 focus:ring-0 placeholder:text-slate-400">
  </div>

  <select name="status"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    <option value="">Semua Status</option>
    <option value="diterbitkan" {{ request('status') === 'diterbitkan' ? 'selected' : '' }}>Diterbitkan</option>
    <option value="draft"       {{ request('status') === 'draft'       ? 'selected' : '' }}>Draft</option>
  </select>

  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i> Filter
  </button>
  @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.page.index') }}"
       class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
      <i class="ti ti-x text-sm"></i>
    </a>
  @endif

  @if(Auth::user()->hasPermission('tambah.page'))
  <a href="{{ route('admin.page.create') }}"
     class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors flex-shrink-0">
    <i class="ti ti-plus text-base"></i> Buat Page
  </a>
  @endif
</form>

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($pages->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-file-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400 mb-4">
        {{ request()->hasAny(['q','status']) ? 'Tidak ada halaman yang sesuai filter.' : 'Belum ada halaman statis.' }}
      </p>
      @unless(request()->hasAny(['q','status']))
        @if(Auth::user()->hasPermission('tambah.page'))
        <a href="{{ route('admin.page.create') }}"
           class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-plus text-base"></i> Buat Page Pertama
        </a>
        @endif
      @endunless
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
            <th class="px-5 py-3 w-14"></th>
            <th class="px-4 py-3">Judul</th>
            <th class="px-4 py-3">Slug</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Penulis</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-center w-16"><i class="ti ti-eye text-sm"></i></th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($pages as $p)
            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">

              {{-- Gambar --}}
              <td class="px-5 py-3">
                @if($p->gambar_url)
                  <img src="{{ $p->gambar_url }}" alt=""
                    class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                @else
                  <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center border border-slate-200 dark:border-slate-700">
                    <i class="ti ti-file-text text-slate-400 text-sm"></i>
                  </div>
                @endif
              </td>

              {{-- Judul --}}
              <td class="px-4 py-3 max-w-xs">
                <div class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $p->judul }}</div>
                <div class="text-xs text-slate-400 truncate mt-0.5">{{ $p->ringkasan }}</div>
              </td>

              {{-- Slug --}}
              <td class="px-4 py-3">
                <span class="text-xs font-mono text-slate-400 dark:text-slate-500">/{{ $p->slug }}</span>
              </td>

              {{-- Status --}}
              <td class="px-4 py-3">
                @if($p->status === 'diterbitkan')
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Diterbitkan
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Draft
                  </span>
                @endif
              </td>

              {{-- Penulis --}}
              <td class="px-4 py-3">
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $p->user->name }}</span>
              </td>

              {{-- Tanggal --}}
              <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                {{ ($p->published_at ?? $p->created_at)->format('d M Y') }}
              </td>

              {{-- Views --}}
              <td class="px-4 py-3 text-center text-xs text-slate-400">
                {{ number_format($p->dilihat) }}
              </td>

              {{-- Aksi --}}
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-1.5">
                  @if(Auth::user()->hasPermission('edit.page'))
                  <a href="{{ route('admin.page.edit', $p) }}"
                    class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="ti ti-edit text-sm"></i> Edit
                  </a>
                  @endif
                  @if(Auth::user()->hasPermission('hapus.page'))
                  <form method="POST" action="{{ route('admin.page.destroy', $p) }}" class="m-0">
                    @csrf @method('DELETE')
                    <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Halaman ini akan dihapus permanen.', 'Hapus Halaman')"
                      class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($pages->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $pages->links() }}
      </div>
    @endif
  @endif
</div>

@endsection
