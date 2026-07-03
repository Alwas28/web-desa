@extends('layouts.admin')

@section('title', 'Pengaturan Surat')

@section('content')
@php
  $alignVal   = $kopAlign ?? 'center';
  $logoPosVal = $logoPos  ?? 'above';
  $fontVal    = $kopFont  ?? 'Inter';
  $sz1        = $kopSizes['l1'] ?? 11;
  $sz2        = $kopSizes['l2'] ?? 18;
  $sz3        = $kopSizes['l3'] ?? 11;
  $sz4        = $kopSizes['l4'] ?? 11;
  $sz5        = $kopSizes['l5'] ?? 11;
  $fontCssVal = \App\Http\Controllers\Admin\SuratPengaturanController::FONTS[$fontVal] ?? 'Inter, sans-serif';
  $kopLines   = array_values(array_filter(explode("\n", $kopRendered ?? ''), fn($l) => trim($l) !== ''));
@endphp

<form method="POST" action="{{ route('admin.surat.pengaturan.update') }}" id="fmPengaturan">
@csrf @method('PUT')
<input type="hidden" name="kop_align"     id="kop_align"     value="{{ $alignVal }}">
<input type="hidden" name="logo_position" id="logo_position" value="{{ $logoPosVal }}">

<div class="max-w-5xl mx-auto space-y-6">

  {{-- ── Header ─────────────────────────────────────────────────────────── --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-xl font-bold text-slate-800 dark:text-white">Pengaturan Surat</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">KOP surat dan format nomor otomatis</p>
    </div>
    <a href="{{ route('admin.layanan-surat.index') }}"
       class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
      <i class="ti ti-arrow-left text-sm"></i> Kembali
    </a>
  </div>

  {{-- ── Pratinjau KOP ───────────────────────────────────────────────────── --}}
  <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">

    {{-- Header card --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-3.5 border-b border-slate-200 dark:border-slate-700">
      <div class="flex items-center gap-2">
        <i class="ti ti-eye text-slate-400"></i>
        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pratinjau KOP Surat</span>
        <span class="text-[10px] text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full uppercase tracking-wide">Live</span>
      </div>
      <div class="flex flex-wrap items-center gap-3">
        {{-- Logo position --}}
        <div class="flex items-center gap-1">
          <span class="text-xs text-slate-400 mr-1">Logo:</span>
          <button type="button" id="btnLogoDiAtas" onclick="setLogoPos('above')"
            class="tgl-btn {{ $logoPosVal === 'above' ? 'tgl-on' : 'tgl-off' }}">
            <i class="ti ti-layout-align-top text-sm"></i> Di Atas
          </button>
          <button type="button" id="btnLogoDiKiri" onclick="setLogoPos('left')"
            class="tgl-btn {{ $logoPosVal === 'left' ? 'tgl-on' : 'tgl-off' }}">
            <i class="ti ti-layout-sidebar text-sm"></i> Di Kiri
          </button>
        </div>
        <div class="w-px h-5 bg-slate-200 dark:bg-slate-600"></div>
        {{-- Text alignment --}}
        <div class="flex items-center gap-1">
          <span class="text-xs text-slate-400 mr-1">Teks:</span>
          <button type="button" id="btnAlignLeft" onclick="setAlign('left')"
            class="tgl-btn {{ $alignVal === 'left' ? 'tgl-on' : 'tgl-off' }}">
            <i class="ti ti-align-left text-sm"></i> Kiri
          </button>
          <button type="button" id="btnAlignCenter" onclick="setAlign('center')"
            class="tgl-btn {{ $alignVal === 'center' ? 'tgl-on' : 'tgl-off' }}">
            <i class="ti ti-align-center text-sm"></i> Tengah
          </button>
        </div>
      </div>
    </div>

    {{-- KOP visual --}}
    <div class="px-8 pt-6 pb-2">
      <div id="kopWrapper"
           class="{{ $logoPosVal === 'above' ? 'flex flex-col ' . ($alignVal === 'center' ? 'items-center' : 'items-start') : 'flex flex-row items-start gap-5' }}">

        @if($logoUrl)
        <img id="kopLogo" src="{{ $logoUrl }}" alt="Logo"
             class="{{ $logoPosVal === 'above' ? 'w-16 h-16 mb-3' : 'w-16 h-16 flex-shrink-0' }} object-contain">
        @else
        <div id="kopLogoPlaceholder"
             class="{{ $logoPosVal === 'above' ? 'w-16 h-16 mb-3' : 'w-16 h-16 flex-shrink-0' }}
                    rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center bg-slate-50 dark:bg-slate-700/50">
          <i class="ti ti-photo text-xl text-slate-300 dark:text-slate-600"></i>
        </div>
        @endif

        <div id="kopTextBlock"
             class="{{ $alignVal === 'center' ? 'text-center' : 'text-left' }} flex-1"
             style="font-family: {{ $fontCssVal }}">
          @foreach($kopLines as $i => $line)
            @if($i === 0)
              <p class="font-semibold text-slate-600 dark:text-slate-300 uppercase leading-tight" style="font-size:{{ $sz1 }}px">{{ $line }}</p>
            @elseif($i === 1)
              <p class="font-extrabold text-slate-900 dark:text-white uppercase leading-tight" style="font-size:{{ $sz2 }}px">{{ $line }}</p>
            @else
              <p class="text-slate-500 dark:text-slate-400 mt-0.5 leading-snug" style="font-size:{{ $sz3 }}px">{{ $line }}</p>
            @endif
          @endforeach
        </div>
      </div>
      <div class="mt-4 border-b-[3px] border-slate-800 dark:border-slate-200"></div>
      <div class="mt-0.5 border-b border-slate-800 dark:border-slate-200"></div>
    </div>

    {{-- Nomor preview --}}
    <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center gap-3">
      <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Contoh Nomor:</span>
      <select id="prevJenis" onchange="updateNomorPreview()"
        class="text-xs border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-2.5 py-1.5 outline-none">
        @foreach($jenisList as $js)
        <option value="{{ $js->nama }}">{{ $js->kode ?? '—' }} — {{ $js->nama }}</option>
        @endforeach
      </select>
      <button type="button" onclick="updateNomorPreview()" title="Regenerasi"
        class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-600 text-slate-400 hover:text-slate-600 hover:bg-white dark:hover:bg-slate-700 transition-colors">
        <i class="ti ti-refresh text-sm"></i>
      </button>
      <code id="nomorPreview"
        class="font-mono text-sm font-semibold text-brand-700 dark:text-brand-400 px-3 py-1.5 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 tracking-wide">
        —
      </code>
      @if(!$logoUrl)
      <div class="ml-auto flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400">
        <i class="ti ti-alert-triangle text-sm"></i>
        Logo belum diatur —
        <a href="{{ route('admin.settings.index') }}" class="underline font-semibold">Pengaturan Desa</a>
      </div>
      @endif
    </div>
  </div>

  {{-- ── 2 Kolom Form ────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

    {{-- Kiri: Template KOP + Tipografi ──────────────────────────────────── --}}
    <div class="space-y-5">

      {{-- Template KOP --}}
      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-2">
          <i class="ti ti-file-text text-brand-600 dark:text-brand-400"></i>
          <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Template KOP</h3>
        </div>
        <div class="p-5 space-y-4">

          {{-- Referensi identitas --}}
          <div class="rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden text-xs">
            <div class="px-3 py-2 bg-slate-50 dark:bg-slate-700/50 flex items-center justify-between">
              <span class="font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Identitas Desa</span>
              <a href="{{ route('admin.settings.index') }}" target="_blank"
                 class="text-brand-600 dark:text-brand-400 hover:underline text-[11px]">
                <i class="ti ti-external-link text-[11px]"></i> Edit
              </a>
            </div>
            @foreach([
              ['Nama Desa',   $desa['desa.nama']      ?? null],
              ['Kabupaten',   $desa['desa.kabupaten'] ?? null],
              ['Kecamatan',   $desa['desa.kecamatan'] ?? null],
              ['Alamat',      $desa['desa.alamat']    ?? null],
              ['Email',       $desa['desa.email']     ?? null],
            ] as [$lbl, $val])
            <div class="flex items-center gap-2 px-3 py-1.5 border-t border-slate-100 dark:border-slate-700">
              <span class="text-slate-400 w-20 flex-shrink-0">{{ $lbl }}</span>
              <span class="{{ $val ? 'text-slate-700 dark:text-slate-300 font-medium' : 'italic text-slate-300 dark:text-slate-600' }}">{{ $val ?: '(belum diisi)' }}</span>
            </div>
            @endforeach
          </div>

          {{-- Textarea --}}
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Teks KOP</label>
            <textarea name="kop_template" id="kopTemplate" rows="4"
              oninput="syncKopPreview()"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-mono leading-relaxed resize-y focus:outline-none focus:ring-2 focus:ring-brand-500/40">{{ $kopTemplate }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">Baris 1 = teks kecil &nbsp;·&nbsp; Baris 2 = judul besar &nbsp;·&nbsp; Baris 3+ = info tambahan</p>
          </div>

          {{-- Placeholder chips --}}
          <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Klik untuk sisipkan:</p>
            <div class="flex flex-wrap gap-1.5">
              @foreach(['{nama_desa}','{nama_kabupaten}','{kecamatan}','{alamat}','{email_resmi}','{telepon}','{kode_pos}','{provinsi}','{kepala_desa}','{website}'] as $ph)
              <button type="button" onclick="insertPlaceholder('{{ $ph }}')"
                class="px-2 py-1 rounded-md border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-[11px] font-mono text-brand-700 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 hover:border-brand-300 transition-colors">
                {{ $ph }}
              </button>
              @endforeach
            </div>
          </div>

          <div class="flex justify-end">
            <button type="button" onclick="resetTemplate()"
              class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
              <i class="ti ti-refresh text-xs mr-1"></i> Kembalikan ke default
            </button>
          </div>
        </div>
      </div>

      {{-- Tipografi ──────────────────────────────────────────────────────── --}}
      <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-2">
          <i class="ti ti-typography text-brand-600 dark:text-brand-400"></i>
          <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Tipografi KOP</h3>
        </div>
        <div class="p-5 space-y-4">

          {{-- Font family --}}
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jenis Huruf</label>
            <select name="kop_font" id="kop_font" onchange="syncKopPreview()"
              class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
              @foreach($fonts as $name => $css)
              <option value="{{ $name }}" {{ $fontVal === $name ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
            <div id="fontPreviewText"
              class="mt-2 px-3 py-2 rounded-lg border border-slate-100 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm"
              style="font-family: {{ $fontCssVal }}">
              Contoh: PEMERINTAH DESA SEJAHTERA
            </div>
          </div>

          {{-- Ukuran huruf per baris --}}
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Ukuran Huruf (px)</label>
            <div class="space-y-3">
              @foreach([
                ['l1', 'kop_size_l1', 'Baris 1', 'Teks instansi kecil', 8, 24, $sz1],
                ['l2', 'kop_size_l2', 'Baris 2', 'Judul nama desa', 10, 48, $sz2],
                ['l3', 'kop_size_l3', 'Baris 3', 'Info alamat', 8, 24, $sz3],
                ['l4', 'kop_size_l4', 'Baris 4', 'Info tambahan', 8, 24, $sz4],
                ['l5', 'kop_size_l5', 'Baris 5', 'Info tambahan', 8, 24, $sz5],
              ] as [$id, $name, $label, $hint, $min, $max, $val])
              <div class="flex items-center gap-3">
                <div class="flex-1">
                  <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400">{{ $label }}</span>
                    <span class="text-[11px] text-slate-400">{{ $hint }}</span>
                  </div>
                  <input type="range" name="{{ $name }}" id="{{ $name }}"
                    min="{{ $min }}" max="{{ $max }}" value="{{ $val }}"
                    oninput="document.getElementById('disp_{{ $id }}').textContent=this.value+'px'; syncKopPreview();"
                    class="w-full accent-brand-600 cursor-pointer">
                </div>
                <span id="disp_{{ $id }}"
                  class="text-xs font-mono font-semibold text-brand-700 dark:text-brand-400 w-10 text-right flex-shrink-0">
                  {{ $val }}px
                </span>
              </div>
              @endforeach
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- Kanan: Nomor Surat ───────────────────────────────────────────────── --}}
    <div class="space-y-5">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-2">
        <i class="ti ti-hash text-brand-600 dark:text-brand-400"></i>
        <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Format Nomor Surat</h3>
      </div>
      <div class="p-5 space-y-4">

        {{-- Placeholder help --}}
        <div class="rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-4">
          <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2.5">Placeholder:</p>
          <div class="space-y-2">
            @foreach([
              '{kode1}'      => 'Kode Tambahan 1 (bebas diisi)',
              '{kode2}'      => 'Kode Tambahan 2 (bebas diisi)',
              '{kode_jenis}' => 'Kode jenis surat otomatis',
              '{tahun}'      => 'Tahun sekarang (mis. 2026)',
              '{bulan}'      => 'Bulan romawi (I – XII)',
              '{urutan}'     => 'Nomor urut dalam tahun (001…)',
            ] as $ph => $desc)
            <div class="flex items-start gap-2">
              <code class="text-[11px] bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded px-1.5 py-0.5 font-mono text-brand-700 dark:text-brand-400 flex-shrink-0 mt-0.5">{{ $ph }}</code>
              <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $desc }}</span>
            </div>
            @endforeach
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kode Tambahan 1</label>
            <input type="text" name="nomor_kode1" id="nomor_kode1"
              value="{{ $nom['surat.nomor_kode1'] ?? '' }}"
              oninput="updateNomorPreview()"
              placeholder="mis. 474"
              class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500/40">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kode Tambahan 2</label>
            <input type="text" name="nomor_kode2" id="nomor_kode2"
              value="{{ $nom['surat.nomor_kode2'] ?? '' }}"
              oninput="updateNomorPreview()"
              placeholder="mis. II"
              class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500/40">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Format Nomor</label>
          <input type="text" name="nomor_format" id="nomor_format"
            value="{{ $nom['surat.nomor_format'] ?? '{kode1}/{kode_jenis}/{tahun}/{urutan}' }}"
            oninput="updateNomorPreview()"
            placeholder="{kode1}/{kode_jenis}/{tahun}/{urutan}"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500/40">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Panjang Nomor Urut</label>
          <select name="nomor_panjang" id="nomor_panjang" onchange="updateNomorPreview()"
            class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
            @foreach([2,3,4,5] as $p)
            <option value="{{ $p }}" {{ (($nom['surat.nomor_panjang'] ?? 3) == $p) ? 'selected' : '' }}>
              {{ $p }} digit — contoh: {{ str_pad('1', $p, '0', STR_PAD_LEFT) }}
            </option>
            @endforeach
          </select>
        </div>

        {{-- Tabel kode jenis --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden">
          <div class="px-3 py-2 bg-slate-50 dark:bg-slate-700/50">
            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Kode {kode_jenis} otomatis</span>
          </div>
          <div class="divide-y divide-slate-100 dark:divide-slate-700 max-h-52 overflow-y-auto">
            @foreach($jenisList as $js)
            <div class="flex items-center gap-2 px-3 py-1.5">
              <code class="text-[11px] font-mono text-brand-700 dark:text-brand-400 w-12 flex-shrink-0">{{ $js->kode ?? '—' }}</code>
              <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $js->nama }}</span>
            </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>{{-- /Format Nomor card --}}

    {{-- ── Counter & Reset Nomor ──────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-2">
        <i class="ti ti-123 text-brand-600 dark:text-brand-400"></i>
        <h3 class="font-semibold text-slate-800 dark:text-white text-sm">Counter & Reset Nomor</h3>
      </div>
      <div class="p-5 space-y-4">

        {{-- Pengaturan reset --}}
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Reset Per</label>
            <select name="nomor_reset_periode" id="nomor_reset_periode" onchange="toggleBulanMulai()"
              class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
              <option value="tahun" {{ $resetPeriode === 'tahun' ? 'selected' : '' }}>Tahunan</option>
              <option value="bulan" {{ $resetPeriode === 'bulan' ? 'selected' : '' }}>Bulanan</option>
            </select>
          </div>
          <div id="wrap_bulan_mulai" class="transition-opacity {{ $resetPeriode === 'bulan' ? 'opacity-30 pointer-events-none' : '' }}">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Mulai Bulan</label>
            <select name="nomor_reset_bulan_mulai" id="nomor_reset_bulan_mulai"
              class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/40">
              @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
              <option value="{{ $i + 1 }}" {{ $resetBulanMulai === ($i + 1) ? 'selected' : '' }}>{{ $bln }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Info periode --}}
        <div class="rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-4 space-y-2.5">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Periode Saat Ini</span>
            <span class="text-xs text-slate-700 dark:text-slate-300">{{ $periodLabel }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Reset Berikutnya</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $nextResetLabel }}</span>
          </div>
          <div class="flex items-center justify-between border-t border-slate-200 dark:border-slate-600 pt-2.5">
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Surat Selesai</span>
            <span class="font-bold text-brand-700 dark:text-brand-400">{{ $counterTotal }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Nomor Berikutnya</span>
            <code class="font-mono font-bold text-brand-700 dark:text-brand-400">#{{ str_pad($counterTotal + 1, $nom['surat.nomor_panjang'] ?? 3, '0', STR_PAD_LEFT) }}</code>
          </div>
        </div>

        {{-- Tabel breakdown per bulan --}}
        @if($counterRows->isNotEmpty())
        @php $byJenis = $counterRows->groupBy('jenis_surat'); @endphp
        <div class="rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-xs">
              <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                  <th class="px-4 py-2.5 text-left font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Jenis</th>
                  @foreach($periodMonths as $pm)
                  <th class="px-2 py-2.5 text-center font-semibold text-slate-400 uppercase tracking-wide w-10 whitespace-nowrap">{{ $pm['label'] }}</th>
                  @endforeach
                  <th class="px-3 py-2.5 text-right font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($byJenis as $jenis => $rows)
                @php
                  $monthMap = [];
                  foreach ($rows as $r) { $monthMap[$r->tahun_col . '_' . $r->bulan] = $r->total; }
                  $rowTotal = $rows->sum('total');
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-colors">
                  <td class="px-4 py-2 font-medium text-slate-700 dark:text-slate-300 max-w-[140px] truncate" title="{{ $jenis }}">{{ $jenis }}</td>
                  @foreach($periodMonths as $pm)
                  @php $cnt = $monthMap[$pm['year'] . '_' . $pm['month']] ?? 0; @endphp
                  <td class="px-2 py-2 text-center">
                    @if($cnt > 0)
                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 font-semibold">{{ $cnt }}</span>
                    @else
                      <span class="text-slate-200 dark:text-slate-700">—</span>
                    @endif
                  </td>
                  @endforeach
                  <td class="px-3 py-2 text-right font-bold text-slate-700 dark:text-slate-300">{{ $rowTotal }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="border-t-2 border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/30">
                  <td class="px-4 py-2 font-bold text-slate-500 dark:text-slate-400 uppercase text-[10px]">Total</td>
                  @foreach($periodMonths as $pm)
                  @php $mTotal = $counterRows->where('tahun_col', $pm['year'])->where('bulan', $pm['month'])->sum('total'); @endphp
                  <td class="px-2 py-2 text-center font-semibold text-slate-500 dark:text-slate-400">{{ $mTotal ?: '' }}</td>
                  @endforeach
                  <td class="px-3 py-2 text-right font-bold text-brand-700 dark:text-brand-400">{{ $counterTotal }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        @else
        <div class="text-center py-5">
          <i class="ti ti-file-off text-2xl text-slate-300 dark:text-slate-600 block mb-2"></i>
          <p class="text-sm text-slate-400">Belum ada surat selesai dalam periode ini</p>
        </div>
        @endif

      </div>
    </div>{{-- /Counter card --}}

    </div>{{-- /space-y-5 kanan --}}

  </div>

  {{-- ── Simpan ──────────────────────────────────────────────────────────── --}}
  <div class="flex items-center justify-end gap-3">
    <a href="{{ route('admin.layanan-surat.index') }}"
       class="px-5 h-10 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center">
      Batal
    </a>
    <button type="submit"
      class="inline-flex items-center gap-2 px-7 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors shadow-sm">
      <i class="ti ti-device-floppy"></i> Simpan Pengaturan
    </button>
  </div>

</div>
</form>

@endsection

@section('scripts')
<style>
.tgl-btn {
  display: inline-flex; align-items: center; gap: 0.375rem;
  padding: 0 0.625rem; height: 2rem; border-radius: 0.5rem;
  border-width: 1px; font-size: 0.75rem; font-weight: 500;
  transition: background .15s, color .15s, border-color .15s;
}
.tgl-on  { background: var(--color-brand-600,#16a34a); border-color: var(--color-brand-600,#16a34a); color: #fff; }
.tgl-off { border-color: #e2e8f0; color: #475569; }
.dark .tgl-off { border-color: #475569; color: #94a3b8; }
.tgl-off:hover { background: #f8fafc; }
.dark .tgl-off:hover { background: #1e293b; }
</style>
<script>
const DESA = @js([
  'nama_desa'      => $desa['desa.nama']      ?? '',
  'nama_kabupaten' => $desa['desa.kabupaten'] ?? '',
  'kecamatan'      => $desa['desa.kecamatan'] ?? '',
  'alamat'         => $desa['desa.alamat']    ?? '',
  'email_resmi'    => $desa['desa.email']     ?? '',
  'email'          => $desa['desa.email']     ?? '',
  'telepon'        => $desa['desa.whatsapp']  ?? '',
  'kode_pos'       => $desa['desa.kode_pos']  ?? '',
  'provinsi'       => $desa['desa.provinsi']  ?? '',
  'kepala_desa'    => $desa['desa.kepala']    ?? '',
  'website'        => $desa['desa.website']   ?? '',
]);
const FONTS = @js(\App\Http\Controllers\Admin\SuratPengaturanController::FONTS);
const KODE_JENIS = @js($jenisList->pluck('kode', 'nama'));
const DEFAULT_TEMPLATE = @js(\App\Http\Controllers\Admin\SuratPengaturanController::DEFAULT_TEMPLATE);

let _align   = @js($alignVal);
let _logoPos = @js($logoPosVal);

function toggleBulanMulai() {
  const isBulan = document.getElementById('nomor_reset_periode').value === 'bulan';
  const wrap    = document.getElementById('wrap_bulan_mulai');
  wrap.classList.toggle('opacity-30', isBulan);
  wrap.classList.toggle('pointer-events-none', isBulan);
}

/* ── toggle state ─────────────────────────────────────────────────── */
function _tog(id, on) {
  const b = document.getElementById(id);
  if (!b) return;
  b.classList.toggle('tgl-on', on);
  b.classList.toggle('tgl-off', !on);
}

function setAlign(v) {
  _align = v;
  document.getElementById('kop_align').value = v;
  _tog('btnAlignLeft', v === 'left');
  _tog('btnAlignCenter', v === 'center');
  _applyLayout();
}

function setLogoPos(v) {
  _logoPos = v;
  document.getElementById('logo_position').value = v;
  _tog('btnLogoDiAtas', v === 'above');
  _tog('btnLogoDiKiri', v === 'left');
  _applyLayout();
}

function _applyLayout() {
  const wrapper = document.getElementById('kopWrapper');
  const logo    = document.getElementById('kopLogo') || document.getElementById('kopLogoPlaceholder');
  const text    = document.getElementById('kopTextBlock');
  if (!wrapper) return;

  if (_logoPos === 'above') {
    wrapper.className = 'flex flex-col ' + (_align === 'center' ? 'items-center' : 'items-start');
    if (logo) logo.className = logo.className.replace('flex-shrink-0','').replace('mb-3','').trim() + ' w-16 h-16 object-contain mb-3';
  } else {
    wrapper.className = 'flex flex-row items-start gap-5';
    if (logo) logo.className = 'w-16 h-16 object-contain flex-shrink-0';
  }

  if (text) {
    text.className = 'flex-1 ' + (_align === 'center' ? 'text-center' : 'text-left');
  }
}

/* ── render KOP template ─────────────────────────────────────────── */
function esc(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function renderKop(tpl) {
  let r = tpl;
  for (const [k, v] of Object.entries(DESA)) r = r.replaceAll('{'+k+'}', v);
  return r;
}

function syncKopPreview() {
  const block = document.getElementById('kopTextBlock');
  if (!block) return;

  const fontName = document.getElementById('kop_font')?.value || 'Inter';
  const fontCss  = FONTS[fontName] || 'Inter, sans-serif';
  block.style.fontFamily = fontCss;

  const fp = document.getElementById('fontPreviewText');
  if (fp) fp.style.fontFamily = fontCss;

  const szMap = [
    (document.getElementById('kop_size_l1')?.value || 11) + 'px',
    (document.getElementById('kop_size_l2')?.value || 18) + 'px',
    (document.getElementById('kop_size_l3')?.value || 11) + 'px',
    (document.getElementById('kop_size_l4')?.value || 11) + 'px',
    (document.getElementById('kop_size_l5')?.value || 11) + 'px',
  ];

  const lines = renderKop(document.getElementById('kopTemplate').value)
    .split('\n').filter(l => l.trim() !== '');

  block.innerHTML = lines.map((line, i) => {
    const sz  = szMap[Math.min(i, 4)];
    const cls = i === 0
      ? 'font-semibold text-slate-600 dark:text-slate-300 uppercase leading-tight'
      : (i === 1
        ? 'font-extrabold text-slate-900 dark:text-white uppercase leading-tight'
        : 'text-slate-500 dark:text-slate-400 mt-0.5 leading-snug');
    return `<p class="${cls}" style="font-size:${sz}">${esc(line)}</p>`;
  }).join('');
}

/* ── placeholder insert ──────────────────────────────────────────── */
function insertPlaceholder(ph) {
  const ta = document.getElementById('kopTemplate');
  const s = ta.selectionStart, e = ta.selectionEnd;
  ta.value = ta.value.slice(0, s) + ph + ta.value.slice(e);
  ta.selectionStart = ta.selectionEnd = s + ph.length;
  ta.focus();
  syncKopPreview();
}

function resetTemplate() {
  konfirmasiHapus(
    { submit() { document.getElementById('kopTemplate').value = DEFAULT_TEMPLATE; syncKopPreview(); } },
    'Template KOP akan dikembalikan ke format default. Perubahan baru aktif setelah disimpan.',
    'Reset Template KOP'
  );
}

/* ── nomor preview ───────────────────────────────────────────────── */
function updateNomorPreview() {
  const jenis   = document.getElementById('prevJenis')?.value || '';
  const format  = document.getElementById('nomor_format')?.value  || '{kode1}/{kode_jenis}/{tahun}/{urutan}';
  const kode1   = document.getElementById('nomor_kode1')?.value  || '';
  const kode2   = document.getElementById('nomor_kode2')?.value  || '';
  const panjang = parseInt(document.getElementById('nomor_panjang')?.value || 3);
  const roman   = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
  const now     = new Date();
  const tahun   = now.getFullYear();
  const bulan   = roman[now.getMonth()];
  const urutan  = String(1).padStart(panjang, '0');
  const kj      = (jenis && KODE_JENIS[jenis]) ? KODE_JENIS[jenis] : 'SK';

  const nomor = format
    .replace('{kode1}', kode1)
    .replace('{kode2}', kode2)
    .replace('{kode_jenis}', kj)
    .replace('{tahun}', tahun)
    .replace('{bulan}', bulan)
    .replace('{urutan}', urutan);

  const el = document.getElementById('nomorPreview');
  if (el) el.textContent = nomor || '—';
}

document.addEventListener('DOMContentLoaded', () => updateNomorPreview());
</script>
@endsection
