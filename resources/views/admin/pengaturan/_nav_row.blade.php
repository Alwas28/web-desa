@php
  $tipeBadge = match($item->tipe_link) {
      'page'  => ['Dari Page', 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
      'arsip' => ['Arsip', 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400'],
      default => ['Custom URL', 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'],
  };
  $link = match($item->tipe_link) {
      'page'  => $item->page ? '/' . $item->page->slug : '—',
      'arsip' => $item->arsip_filter === 'kategori' && $item->kategoriArsip
                   ? '/arsip?kategori=' . $item->kategori_arsip_id . ' (' . $item->kategoriArsip->nama . ')'
                   : '/arsip (Semua)',
      default => $item->link_url ?: '—',
  };
@endphp
<tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition-colors">
  <td class="px-4 py-3">
    <div class="flex items-center gap-2" style="{{ $depth > 0 ? 'padding-left:1.25rem' : '' }}">
      @if($depth > 0)
        <i class="ti ti-corner-down-right text-slate-300 dark:text-slate-600 text-sm flex-shrink-0"></i>
      @endif
      <span class="font-medium text-slate-900 dark:text-slate-100 text-sm">{{ $item->label }}</span>
    </div>
  </td>
  <td class="px-4 py-3">
    <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full {{ $tipeBadge[1] }}">
      {{ $tipeBadge[0] }}
    </span>
  </td>
  <td class="px-4 py-3 text-xs text-slate-400 max-w-xs">
    <span class="truncate block font-mono">{{ $link }}</span>
    @if($item->target === '_blank')
      <span class="text-slate-300 dark:text-slate-600">tab baru</span>
    @endif
  </td>
  <td class="px-4 py-3 text-center text-sm text-slate-500">
    {{ $item->urutan }}
  </td>
  <td class="px-4 py-3 text-center">
    @if($item->aktif)
      <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Aktif
      </span>
    @else
      <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-500">
        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block"></span> Nonaktif
      </span>
    @endif
  </td>
  <td class="px-4 py-3">
    <div class="flex items-center justify-end gap-1.5">
      <button type="button"
        onclick="openNavModal(@js(['id'=>$item->id,'label'=>$item->label,'parent_id'=>$item->parent_id,'tipe_link'=>$item->tipe_link,'link_url'=>$item->link_url,'page_id'=>$item->page_id,'target'=>$item->target,'urutan'=>$item->urutan,'aktif'=>$item->aktif,'arsip_filter'=>$item->arsip_filter,'kategori_arsip_id'=>$item->kategori_arsip_id]))"
        class="flex items-center px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
        <i class="ti ti-edit text-sm"></i>
      </button>
      <form method="POST" action="{{ route('admin.navbar.destroy', $item) }}" class="m-0">
        @csrf @method('DELETE')
        <button type="button" onclick="konfirmasiHapus(this.closest('form'), 'Item menu ini akan dihapus dari navigasi.', 'Hapus Item Menu')"
          class="flex items-center px-3 h-8 rounded-lg border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
          <i class="ti ti-trash text-sm"></i>
        </button>
      </form>
    </div>
  </td>
</tr>
