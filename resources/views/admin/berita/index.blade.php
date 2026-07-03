@extends('layouts.admin')

@section('title', 'Berita')
@section('page-title', 'Berita')
@section('page-sub', 'Kelola artikel dan informasi desa')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-3 gap-3 max-w-lg">
  <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 grid place-items-center flex-shrink-0">
      <i class="ti ti-news text-brand-600 dark:text-brand-100"></i>
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

{{-- Toolbar: filter + tombol buat --}}
<form method="GET" action="{{ route('admin.berita.index') }}"
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

  <select name="kategori"
    class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
    <option value="">Semua Kategori</option>
    @foreach($kategoris as $kat)
      <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
        {{ $kat->nama }}
      </option>
    @endforeach
  </select>

  <button type="submit"
    class="px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
    <i class="ti ti-filter text-sm"></i> Filter
  </button>
  @if(request()->hasAny(['q','status','kategori']))
    <a href="{{ route('admin.berita.index') }}"
       class="px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
      <i class="ti ti-x text-sm"></i>
    </a>
  @endif

  <a href="{{ route('admin.berita.create') }}"
     class="ml-auto flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors flex-shrink-0">
    <i class="ti ti-plus text-base"></i> Buat Berita
  </a>
</form>

{{-- Tabel --}}
<div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
  @if($beritas->isEmpty())
    <div class="p-16 text-center">
      <i class="ti ti-news-off text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
      <p class="text-sm text-slate-400 mb-4">
        {{ request()->hasAny(['q','status','kategori']) ? 'Tidak ada berita yang sesuai filter.' : 'Belum ada berita. Mulai tulis artikel pertama.' }}
      </p>
      @unless(request()->hasAny(['q','status','kategori']))
        <a href="{{ route('admin.berita.create') }}"
           class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-plus text-base"></i> Buat Berita Pertama
        </a>
      @endunless
    </div>
  @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
            <th class="px-5 py-3 w-14"></th>
            <th class="px-4 py-3">Judul</th>
            <th class="px-4 py-3">Kategori</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Penulis</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3 text-center w-16"><i class="ti ti-eye text-sm"></i></th>
            <th class="px-4 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
          @foreach($beritas as $berita)
            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">

              {{-- Gambar --}}
              <td class="px-5 py-3">
                @if($berita->gambar_url)
                  <img src="{{ $berita->gambar_url }}" alt=""
                    class="w-10 h-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                @else
                  <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 grid place-items-center border border-slate-200 dark:border-slate-700">
                    <i class="ti ti-photo text-slate-400 text-sm"></i>
                  </div>
                @endif
              </td>

              {{-- Judul --}}
              <td class="px-4 py-3 max-w-xs">
                <div class="font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $berita->judul }}</div>
                <div class="text-xs text-slate-400 truncate mt-0.5">{{ $berita->ringkasan }}</div>
              </td>

              {{-- Kategori --}}
              <td class="px-4 py-3">
                @if($berita->kategori)
                  <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-md font-medium"
                    style="{{ $berita->kategori->warna ? 'background:' . $berita->kategori->warna . '22; color:' . $berita->kategori->warna : '' }}"
                    class="{{ !$berita->kategori->warna ? 'bg-slate-100 dark:bg-slate-800 text-slate-500' : '' }}">
                    {{ $berita->kategori->nama }}
                  </span>
                @else
                  <span class="text-slate-300 dark:text-slate-600">—</span>
                @endif
              </td>

              {{-- Status --}}
              <td class="px-4 py-3">
                @if($berita->status === 'diterbitkan')
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
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $berita->user->name }}</span>
              </td>

              {{-- Tanggal --}}
              <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                {{ ($berita->published_at ?? $berita->created_at)->format('d M Y') }}
              </td>

              {{-- Views --}}
              <td class="px-4 py-3 text-center text-xs text-slate-400">
                {{ number_format($berita->dilihat) }}
              </td>

              {{-- Aksi --}}
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ route('admin.berita.edit', $berita) }}"
                    class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="ti ti-edit text-sm"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('admin.berita.destroy', $berita) }}" class="m-0">
                    @csrf @method('DELETE')
                    <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Berita ini akan dihapus permanen.', 'Hapus Berita')"
                      class="flex items-center gap-1.5 px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($beritas->hasPages())
      <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $beritas->links() }}
      </div>
    @endif
  @endif
</div>

@endsection
