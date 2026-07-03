@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-sub', 'Tampilan, API, dan identitas desa')

@php
$themes = [
  'hijau' => ['name'=>'Hijau Padi',   'desc'=>'Agraris & alami',    'color'=>'#059669', 'vars'=>['50'=>'236 253 245','100'=>'209 250 229','500'=>'16 185 129', '600'=>'5 150 105', '700'=>'4 120 87']],
  'biru'  => ['name'=>'Biru Bahari',  'desc'=>'Pesisir & maritim',  'color'=>'#2563eb', 'vars'=>['50'=>'239 246 255','100'=>'219 234 254','500'=>'59 130 246', '600'=>'37 99 235', '700'=>'29 78 216']],
  'teal'  => ['name'=>'Teal Laut',    'desc'=>'Segar & modern',     'color'=>'#0d9488', 'vars'=>['50'=>'240 253 250','100'=>'204 251 241','500'=>'20 184 166', '600'=>'13 148 136','700'=>'15 118 110']],
  'ungu'  => ['name'=>'Ungu Senja',   'desc'=>'Elegan & kreatif',   'color'=>'#7c3aed', 'vars'=>['50'=>'245 243 255','100'=>'237 233 254','500'=>'139 92 246', '600'=>'124 58 237','700'=>'109 40 217']],
  'merah' => ['name'=>'Merah Garuda', 'desc'=>'Tegas & nasional',   'color'=>'#dc2626', 'vars'=>['50'=>'254 242 242','100'=>'254 226 226','500'=>'239 68 68',  '600'=>'220 38 38', '700'=>'185 28 28']],
  'amber' => ['name'=>'Amber Agung',  'desc'=>'Hangat & bersahaja', 'color'=>'#d97706', 'vars'=>['50'=>'255 251 235','100'=>'254 243 199','500'=>'245 158 11', '600'=>'217 119 6', '700'=>'180 83 9']],
];
@endphp

@section('content')

{{-- Tab buttons --}}
<div class="flex gap-1 p-1 w-fit rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
  <button onclick="switchTab('tampilan')" id="tab-btn-tampilan"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-palette text-base"></i>Tampilan &amp; Tema
  </button>
  <button onclick="switchTab('api')" id="tab-btn-api"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-plug text-base"></i>Token API
  </button>
  <button onclick="switchTab('desa')" id="tab-btn-desa"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-building-community text-base"></i>Identitas Desa
  </button>
  <button onclick="switchTab('navbar')" id="tab-btn-navbar"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-layout-navbar text-base"></i>Navbar
  </button>
  <button onclick="switchTab('header')" id="tab-btn-header"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-layout-banner text-base"></i>Header
  </button>
  <button onclick="switchTab('profil')" id="tab-btn-profil"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-id-badge text-base"></i>Profil Desa
  </button>
  <button onclick="switchTab('fitur')" id="tab-btn-fitur"
    class="inline-flex items-center gap-1.5 px-4 h-9 rounded-lg text-sm font-medium transition-all">
    <i class="ti ti-toggle-right text-base"></i>Fitur
  </button>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 1 — Tampilan & Tema
══════════════════════════════════════════════════ --}}
<div id="panel-tampilan">
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

    {{-- Preset warna --}}
    <div class="lg:col-span-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <h3 class="font-semibold text-sm mb-1">Preset Warna Tema</h3>
      <p class="text-xs text-slate-400 mb-4">Perubahan diterapkan langsung ke seluruh panel.</p>

      <div class="space-y-2">
        @foreach($themes as $key => $theme)
        <button onclick="applyPreset('{{ $key }}')" id="preset-{{ $key }}"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border-2 text-left transition-all
                 border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600">
          <span class="w-8 h-8 rounded-lg flex-shrink-0 shadow-sm" style="background:{{ $theme['color'] }}"></span>
          <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $theme['name'] }}</div>
            <div class="text-xs text-slate-400">{{ $theme['desc'] }}</div>
          </div>
          <i class="ti ti-circle-check text-lg flex-shrink-0 opacity-0" id="check-{{ $key }}"></i>
        </button>
        @endforeach
      </div>

      <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <span class="text-xs text-slate-400">Aktif: <strong id="activeThemeName" class="text-slate-700 dark:text-slate-200">—</strong></span>
        <button onclick="applyPreset('hijau')"
          class="text-xs px-3 h-7 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          ↺ Reset
        </button>
      </div>
    </div>

    {{-- Kanan: preview + mode gelap --}}
    <div class="lg:col-span-3 space-y-4">

      {{-- Preview topbar live --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
        <h3 class="font-semibold text-sm mb-3">Pratinjau Panel Admin</h3>
        <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
          <div class="flex">
            {{-- Sidebar mini --}}
            <div class="w-11 flex-shrink-0 flex flex-col items-center gap-3 py-3 px-1.5"
                 style="background: rgb(var(--brand-50)); border-right: 1px solid rgb(var(--brand-100))">
              <div class="w-7 h-7 rounded-lg grid place-items-center text-white text-[10px] font-bold shadow-sm"
                   style="background: rgb(var(--brand-600))">
                {{ mb_strtoupper(mb_substr($desaNama, 0, 2)) }}
              </div>
              <div class="w-6 h-1.5 rounded" style="background: rgb(var(--brand-600)); opacity: 0.8"></div>
              <div class="w-6 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
              <div class="w-6 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
              <div class="w-6 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
              <div class="mt-auto w-6 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
            </div>
            <div class="flex-1 min-w-0">
              {{-- Topbar mini --}}
              <div class="h-10 flex items-center gap-2 px-3 border-b border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80">
                <div class="flex-1 h-6 rounded-md bg-slate-100 dark:bg-slate-800 flex items-center px-2 gap-1.5">
                  <div class="w-2.5 h-2.5 rounded-sm bg-slate-300 dark:bg-slate-600"></div>
                  <div class="w-16 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
                </div>
                <div class="w-7 h-7 rounded-full grid place-items-center text-white text-[9px] font-bold shadow-sm flex-shrink-0"
                     style="background: rgb(var(--brand-600))">
                  {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
              </div>
              {{-- Content mini --}}
              <div class="p-2.5 space-y-2 bg-slate-50 dark:bg-slate-950">
                <div class="grid grid-cols-3 gap-1.5">
                  @foreach(['brand-50','amber-50','violet-50'] as $bg)
                  <div class="h-8 rounded-lg bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center px-2 gap-1.5">
                    <div class="w-3 h-3 rounded-sm" style="{{ $bg === 'brand-50' ? 'background:rgb(var(--brand-600));opacity:.3' : ($bg === 'amber-50' ? 'background:#fbbf24;opacity:.5' : 'background:#a78bfa;opacity:.5') }}"></div>
                    <div class="flex-1 h-1.5 rounded bg-slate-200 dark:bg-slate-700"></div>
                  </div>
                  @endforeach
                </div>
                <div class="h-14 rounded-lg bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-2.5 flex flex-col justify-between">
                  <div class="flex gap-1.5 items-center">
                    <div class="w-14 h-2 rounded" style="background:rgb(var(--brand-600));opacity:.2"></div>
                    <div class="flex-1 h-2 rounded bg-slate-100 dark:bg-slate-700"></div>
                  </div>
                  <div class="flex gap-1.5 mt-1">
                    <div class="h-5 px-2 rounded-md grid place-items-center text-white text-[8px] font-bold" style="background:rgb(var(--brand-600))">Aksi</div>
                    <div class="h-5 w-12 rounded-md bg-slate-100 dark:bg-slate-700"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Mode gelap --}}
      <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
        <h3 class="font-semibold text-sm mb-4">Mode Tampilan</h3>
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm font-medium">Mode Gelap</div>
            <div class="text-xs text-slate-400 mt-0.5">Aktifkan tampilan gelap untuk seluruh panel</div>
          </div>
          <div class="toggle" id="darkToggle" onclick="toggleDarkMode(this)"><i></i></div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 2 — Token API
══════════════════════════════════════════════════ --}}
@php
$apiActive    = $settingsApi['api.active'] ?? 'openai';
$providers = [
  'anthropic' => [
    'label'  => 'Anthropic',
    'sub'    => 'Claude Fable 5 / Opus 4.8 / Sonnet 4.6 / Haiku 4.5',
    'icon'   => 'ti-robot',
    'color'  => 'text-orange-500',
    'bg'     => 'bg-orange-50 dark:bg-orange-500/10',
    'models' => ['claude-fable-5','claude-opus-4-8','claude-sonnet-4-6','claude-haiku-4-5-20251001'],
    'ph_key' => 'sk-ant-api…',
    'hint'   => 'Dapatkan dari console.anthropic.com',
  ],
  'openai' => [
    'label'  => 'OpenAI',
    'sub'    => 'GPT-4o / o3 / o1',
    'icon'   => 'ti-brand-openai',
    'color'  => 'text-emerald-600',
    'bg'     => 'bg-emerald-50 dark:bg-emerald-500/10',
    'models' => ['gpt-4o','gpt-4o-mini','o3','o3-mini','o1'],
    'ph_key' => 'sk-proj-…',
    'hint'   => 'Dapatkan dari platform.openai.com/api-keys',
  ],
  'google' => [
    'label'  => 'Google',
    'sub'    => 'Gemini 2.5 Pro / Flash / 2.0 Flash',
    'icon'   => 'ti-brand-google',
    'color'  => 'text-blue-500',
    'bg'     => 'bg-blue-50 dark:bg-blue-500/10',
    'models' => ['gemini-2.5-pro','gemini-2.5-flash','gemini-2.0-flash','gemini-2.0-flash-lite'],
    'ph_key' => 'AIzaSy…',
    'hint'   => 'Dapatkan dari aistudio.google.com/app/apikey',
  ],
];
@endphp

<div id="panel-api" style="display:none">
  <div class="max-w-2xl space-y-4">

    {{-- Info --}}
    <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-xs text-blue-700 dark:text-blue-300">
      <i class="ti ti-shield-lock text-base flex-shrink-0 mt-0.5"></i>
      Semua API key disimpan <strong>terenkripsi</strong> di server. Pilih penyedia yang akan digunakan untuk fitur AI Desa.
    </div>

    {{-- ── Provider cards ── --}}
    @foreach($providers as $slug => $prov)
    @php $isActive = $apiActive === $slug; @endphp
    <div class="rounded-xl bg-white dark:bg-slate-900 border-2 transition-all
                {{ $isActive ? 'border-brand-400 dark:border-brand-500' : 'border-slate-200 dark:border-slate-800' }}"
         id="card-{{ $slug }}">

      {{-- Card header --}}
      <label class="flex items-center gap-4 px-5 py-4 cursor-pointer select-none">
        <input type="radio" name="api_active" value="{{ $slug }}"
               {{ $isActive ? 'checked' : '' }}
               onchange="onProviderChange('{{ $slug }}')"
               class="w-4 h-4 accent-brand-600 flex-shrink-0">
        <div class="w-9 h-9 rounded-xl {{ $prov['bg'] }} grid place-items-center flex-shrink-0">
          <i class="ti {{ $prov['icon'] }} {{ $prov['color'] }} text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">{{ $prov['label'] }}</div>
          <div class="text-xs text-slate-400">{{ $prov['sub'] }}</div>
        </div>
        @if($isActive)
        <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 uppercase tracking-wide">Aktif</span>
        @else
        <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 uppercase tracking-wide" id="badge-{{ $slug }}">Nonaktif</span>
        @endif
      </label>

      {{-- Card body --}}
      <div class="px-5 pb-5 space-y-4 border-t border-slate-100 dark:border-slate-800 pt-4">

        {{-- API Key --}}
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="text-sm font-medium">API Key</label>
            @if($apiKeysExist[$slug])
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
              <i class="ti ti-circle-check text-[10px]"></i> Tersimpan
            </span>
            @else
            <span class="text-[10px] text-slate-400">Belum ada token</span>
            @endif
          </div>
          <div class="flex gap-2">
            <input type="password" id="{{ $slug }}_key"
              class="flex-1 min-w-0 px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
              placeholder="{{ $apiKeysExist[$slug] ? 'Ketik key baru untuk mengganti…' : $prov['ph_key'] }}">
            <button type="button" onclick="toggleApiKey('{{ $slug }}_key', this)"
              class="flex-shrink-0 w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
              <i class="ti ti-eye text-base"></i>
            </button>
          </div>
          <p class="text-[10px] text-slate-400 mt-1">{{ $prov['hint'] }}</p>
        </div>

        {{-- Model --}}
        <div>
          <label class="block text-sm font-medium mb-1.5">Model</label>
          <select id="{{ $slug }}_model"
            class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            @foreach($prov['models'] as $m)
            <option {{ ($settingsApi["api.{$slug}.model"] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
          </select>
        </div>

      </div>
    </div>
    @endforeach

    {{-- ── Custom / Self-hosted card ── --}}
    @php $isCustom = $apiActive === 'custom'; @endphp
    <div class="rounded-xl bg-white dark:bg-slate-900 border-2 transition-all
                {{ $isCustom ? 'border-brand-400 dark:border-brand-500' : 'border-slate-200 dark:border-slate-800' }}"
         id="card-custom">

      <label class="flex items-center gap-4 px-5 py-4 cursor-pointer select-none">
        <input type="radio" name="api_active" value="custom"
               {{ $isCustom ? 'checked' : '' }}
               onchange="onProviderChange('custom')"
               class="w-4 h-4 accent-brand-600 flex-shrink-0">
        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 grid place-items-center flex-shrink-0">
          <i class="ti ti-server text-slate-500 text-lg"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Lainnya / Self-hosted</div>
          <div class="text-xs text-slate-400">Ollama, Mistral, Groq, AWS Bedrock, atau OpenAI-compatible lainnya</div>
        </div>
        @if($isCustom)
        <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 uppercase tracking-wide">Aktif</span>
        @else
        <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 uppercase tracking-wide" id="badge-custom">Nonaktif</span>
        @endif
      </label>

      <div class="px-5 pb-5 space-y-4 border-t border-slate-100 dark:border-slate-800 pt-4">

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1.5">Label / Nama Penyedia</label>
            <input type="text" id="custom_label"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
              value="{{ $settingsApi['api.custom.label'] ?? '' }}"
              placeholder="Contoh: Ollama, Groq, …">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Model</label>
            <input type="text" id="custom_model"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
              value="{{ $settingsApi['api.custom.model'] ?? '' }}"
              placeholder="Contoh: llama3.2, mistral:7b">
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">API Base URL</label>
          <input type="text" id="custom_url"
            class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
            value="{{ $settingsApi['api.custom.url'] ?? '' }}"
            placeholder="http://localhost:11434  atau  https://api.groq.com/openai">
          <p class="text-[10px] text-slate-400 mt-1">Harus kompatibel dengan format OpenAI Chat Completions (<code>/v1/chat/completions</code>).</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {{-- API Key --}}
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="text-sm font-medium">API Key <span class="text-slate-400 font-normal text-xs">(opsional)</span></label>
              @if($apiKeysExist['custom'])
              <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                <i class="ti ti-circle-check text-[10px]"></i> Tersimpan
              </span>
              @endif
            </div>
            <div class="flex gap-2">
              <input type="password" id="custom_key"
                class="flex-1 min-w-0 px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="{{ $apiKeysExist['custom'] ? 'Ketik baru untuk mengganti…' : 'API key / bearer token' }}">
              <button type="button" onclick="toggleApiKey('custom_key', this)"
                class="flex-shrink-0 w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="ti ti-eye text-base"></i>
              </button>
            </div>
          </div>
          {{-- Secret Key --}}
          <div>
            <label class="block text-sm font-medium mb-1.5">Secret Key <span class="text-slate-400 font-normal text-xs">(opsional)</span></label>
            <div class="flex gap-2">
              <input type="password" id="custom_secret"
                class="flex-1 min-w-0 px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                placeholder="Secret / access key">
              <button type="button" onclick="toggleApiKey('custom_secret', this)"
                class="flex-shrink-0 w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <i class="ti ti-eye text-base"></i>
              </button>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Untuk AWS Bedrock atau provider dengan dual-key auth.</p>
          </div>
        </div>

      </div>
    </div>

    {{-- ── Batas penggunaan ── --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5">
      <label class="block text-sm font-medium mb-1.5">Batas Penggunaan Bulanan <span class="text-slate-400 font-normal">(token)</span></label>
      <input type="text" id="api_limit"
        class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
        value="{{ $settingsApi['api.limit'] ?? '' }}" placeholder="Contoh: 2.000.000">
      <p class="text-xs text-slate-400 mt-1.5">Informasi batas pemakaian — sistem akan memberi peringatan mendekati batas ini.</p>
    </div>

    {{-- ── Save button ── --}}
    <div class="flex items-center gap-3">
      <button type="button" onclick="saveApi()"
        class="flex items-center gap-2 px-5 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
        <i class="ti ti-device-floppy text-base"></i> Simpan Semua Pengaturan API
      </button>
      <span id="apiHint" class="text-xs text-brand-600 dark:text-brand-100" style="display:none">
        <i class="ti ti-circle-check"></i> Tersimpan
      </span>
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 3 — Identitas Desa
══════════════════════════════════════════════════ --}}
<div id="panel-desa" style="display:none">
  <div class="max-w-3xl">
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">

      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        <h3 class="font-semibold text-sm">Identitas Desa</h3>
        <p class="text-xs text-slate-400 mt-0.5">Informasi ditampilkan di website publik dan dokumen resmi desa.</p>
      </div>

      <div class="p-6 space-y-4">

        {{-- ── Logo Desa ── --}}
        <div class="pb-4 border-b border-slate-100 dark:border-slate-800">
          <label class="block text-sm font-medium mb-3">Logo Desa</label>
          <div class="flex items-center gap-4">
            {{-- Preview --}}
            <div class="relative flex-shrink-0">
              <div class="w-20 h-20 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 grid place-items-center overflow-hidden">
                @if($logoUrl)
                  <img id="logoPreview" src="{{ $logoUrl }}" alt="Logo Desa" class="w-full h-full object-contain p-1">
                  <i id="logoPlaceholder" class="ti ti-building text-slate-400 text-2xl hidden"></i>
                @else
                  <img id="logoPreview" src="" alt="" class="w-full h-full object-contain p-1 hidden">
                  <i id="logoPlaceholder" class="ti ti-building text-slate-400 text-2xl"></i>
                @endif
              </div>
              <button type="button" id="hapusLogoBtn" onclick="hapusLogo()"
                style="{{ $logoUrl ? '' : 'display:none' }}"
                class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 hover:bg-rose-600 text-white grid place-items-center transition-colors shadow">
                <i class="ti ti-x text-[10px]"></i>
              </button>
            </div>
            {{-- Upload area --}}
            <div class="flex-1">
              <label for="logoInput"
                class="flex flex-col items-center justify-center gap-1.5 h-20 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 cursor-pointer
                       hover:border-brand-400 dark:hover:border-brand-500 hover:bg-brand-50/40 dark:hover:bg-brand-500/5 transition-all">
                <i class="ti ti-upload text-slate-400 text-lg"></i>
                <span class="text-xs text-slate-400 text-center leading-tight">
                  Klik untuk pilih logo<br>
                  <span class="text-[10px]">JPG, PNG, SVG, WEBP — maks. 2 MB</span>
                </span>
              </label>
              <input type="file" id="logoInput" accept="image/*" class="sr-only" onchange="previewLogo(this)">
              <input type="hidden" id="hapusLogoInput" value="0">
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <div>
            <label class="block text-sm font-medium mb-1.5">Nama Desa <span class="text-rose-500">*</span></label>
            <input type="text" id="d_nama"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.nama'] ?? '' }}" placeholder="Contoh: Sukamaju">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Kecamatan <span class="text-rose-500">*</span></label>
            <input type="text" id="d_kecamatan"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.kecamatan'] ?? '' }}" placeholder="Nama kecamatan">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Kabupaten / Kota <span class="text-rose-500">*</span></label>
            <input type="text" id="d_kabupaten"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.kabupaten'] ?? '' }}" placeholder="Nama kabupaten/kota">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Provinsi <span class="text-rose-500">*</span></label>
            <input type="text" id="d_provinsi"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.provinsi'] ?? '' }}" placeholder="Nama provinsi">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Kode Pos</label>
            <input type="text" id="d_kode_pos"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.kode_pos'] ?? '' }}" placeholder="Contoh: 93116" maxlength="6">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Nama Kepala Desa</label>
            <input type="text" id="d_kepala"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.kepala'] ?? '' }}" placeholder="Nama lengkap kepala desa">
          </div>

          <div>
            <label class="block text-sm font-medium mb-1.5">Nama Sekretaris Desa</label>
            <input type="text" id="d_sekretaris"
              class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
              value="{{ $settingsDesa['desa.sekretaris'] ?? '' }}" placeholder="Nama lengkap sekretaris desa">
          </div>

        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Alamat Kantor Desa</label>
          <input type="text" id="d_alamat"
            class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
            value="{{ $settingsDesa['desa.alamat'] ?? '' }}" placeholder="Jl. Raya Desa No. 1, …">
        </div>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Kontak Resmi</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1.5">
                <i class="ti ti-brand-whatsapp text-green-500 mr-1"></i>WhatsApp Resmi
              </label>
              <div class="flex">
                <span class="inline-flex items-center px-3 h-10 rounded-l-lg border border-r-0 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-500 text-sm">+62</span>
                <input type="text" id="d_whatsapp"
                  class="flex-1 min-w-0 px-3 h-10 rounded-r-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                  value="{{ $settingsDesa['desa.whatsapp'] ?? '' }}" placeholder="81234567890">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1.5">
                <i class="ti ti-mail text-blue-500 mr-1"></i>Email Resmi
              </label>
              <input type="email" id="d_email"
                class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"
                value="{{ $settingsDesa['desa.email'] ?? '' }}" placeholder="info@desa.id">
            </div>
          </div>
        </div>

        {{-- Kartu pratinjau identitas --}}
        <div class="rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-4">
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Pratinjau</p>
          <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-xl flex-shrink-0 shadow-sm overflow-hidden grid place-items-center"
                 id="prev-logo-wrap"
                 style="{{ $logoUrl ? 'background:#f8fafc' : 'background:rgb(var(--brand-600))' }}">
              @if($logoUrl)
                <img id="prev-logo-img" src="{{ $logoUrl }}" alt="" class="w-full h-full object-contain p-0.5">
                <span id="prev-inisial" class="text-white text-sm font-bold hidden">{{ mb_strtoupper(mb_substr($settingsDesa['desa.nama'] ?? $desaNama, 0, 2)) }}</span>
              @else
                <img id="prev-logo-img" src="" alt="" class="w-full h-full object-contain p-0.5 hidden">
                <span id="prev-inisial" class="text-white text-sm font-bold">{{ mb_strtoupper(mb_substr($settingsDesa['desa.nama'] ?? $desaNama, 0, 2)) }}</span>
              @endif
            </div>
            <div class="min-w-0">
              <div class="font-semibold text-slate-900 dark:text-slate-100 text-sm" id="prev-nama">
                {{ $settingsDesa['desa.nama'] ?? $desaNama }}
              </div>
              <div class="text-xs text-slate-400 mt-0.5" id="prev-lokasi">
                {{ collect([$settingsDesa['desa.kecamatan'] ?? '', $settingsDesa['desa.kabupaten'] ?? '', $settingsDesa['desa.provinsi'] ?? ''])->filter()->implode(', ') ?: '—' }}
              </div>
              <div class="text-xs text-slate-400 mt-0.5" id="prev-kontak">
                {{ collect([$settingsDesa['desa.email'] ?? '', $settingsDesa['desa.whatsapp'] ?? ''])->filter()->implode(' · ') ?: '—' }}
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center gap-3">
        <button type="button" onclick="saveDesa()"
          class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-device-floppy text-base"></i> Simpan Identitas Desa
        </button>
        <span id="desaHint" class="text-xs text-brand-600 dark:text-brand-100" style="display:none">
          <i class="ti ti-circle-check"></i> Tersimpan
        </span>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 4 — Navbar
══════════════════════════════════════════════════ --}}
<div id="panel-navbar" style="display:none">
  <div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-semibold text-base">Menu Navigasi Homepage</h3>
        <p class="text-xs text-slate-400 mt-0.5">Atur menu yang ditampilkan di navbar halaman publik desa</p>
      </div>
      <button type="button" onclick="openNavModal()"
        class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
        <i class="ti ti-plus text-base"></i> Tambah Item Menu
      </button>
    </div>

    @if(session('success') && request()->routeIs('admin.settings.index'))
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm">
      <i class="ti ti-circle-check flex-shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Tabel nav items --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      @php
        $hasNavItems = $navParents->isNotEmpty() || $navParents->flatMap->children->isNotEmpty();
      @endphp
      @if($navParents->isEmpty())
        <div class="p-14 text-center">
          <i class="ti ti-layout-navbar-collapse text-4xl text-slate-300 dark:text-slate-600 block mb-3"></i>
          <p class="text-sm text-slate-400 mb-4">Belum ada item menu. Buat item pertama.</p>
          <button type="button" onclick="openNavModal()"
            class="inline-flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
            <i class="ti ti-plus text-base"></i> Tambah Item Menu
          </button>
        </div>
      @else
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                <th class="px-4 py-3">Label</th>
                <th class="px-4 py-3">Sumber Link</th>
                <th class="px-4 py-3">Link / Target</th>
                <th class="px-4 py-3 text-center w-16">Urutan</th>
                <th class="px-4 py-3 text-center w-20">Aktif</th>
                <th class="px-4 py-3 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
              @foreach($navParents as $parent)
                @include('admin.pengaturan._nav_row', ['item' => $parent, 'depth' => 0])
                @foreach($parent->children as $child)
                  @include('admin.pengaturan._nav_row', ['item' => $child, 'depth' => 1])
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 5 — Header Homepage
══════════════════════════════════════════════════ --}}
@php
  $hTipe       = $settingsHeader['header.tipe']       ?? 'biasa';
  $hJudul      = $settingsHeader['header.judul']       ?? '';
  $hSubjudul   = $settingsHeader['header.subjudul']    ?? '';
  $hVideoUrl   = $settingsHeader['header.video_url']   ?? '';
  $hBgVideo    = $settingsHeader['header.bg_video']    ?? null;
  $hVideoMode  = $hBgVideo ? 'upload' : 'url';
  $hSlides     = json_decode($settingsHeader['header.slides'] ?? '[]', true) ?: [];
  $hBadge          = $settingsHeader['header.badge']           ?? '';
  $hBtnLayanan     = $settingsHeader['header.btn_layanan']     ?? '';
  $hBtnLayananUrl  = $settingsHeader['header.btn_layanan_url'] ?? '';
  $hBtnProfil      = $settingsHeader['header.btn_profil']      ?? '';
  $hBtnProfilUrl   = $settingsHeader['header.btn_profil_url']  ?? '';
@endphp
<div id="panel-header" style="display:none">
  <div class="max-w-3xl space-y-5">

    {{-- ── Pilih Tipe ── --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        <h3 class="font-semibold text-sm">Jenis Header Homepage</h3>
        <p class="text-xs text-slate-400 mt-0.5">Pilih tampilan header / hero section yang muncul di halaman utama website desa.</p>
      </div>
      <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-3">

        {{-- Biasa --}}
        <label class="header-type-card cursor-pointer" id="htcard-biasa">
          <input type="radio" name="h_tipe" value="biasa" class="sr-only" {{ $hTipe === 'biasa' ? 'checked' : '' }} onchange="onHtipeChange('biasa')">
          <div class="rounded-xl border-2 p-4 transition-all {{ $hTipe === 'biasa' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300' }}">
            <div class="w-full h-24 rounded-lg mb-3 overflow-hidden flex flex-col justify-end"
                 style="background:linear-gradient(160deg,#CFE8F7,#EAF6EE)">
              <div class="p-2">
                <div class="w-20 h-2.5 rounded bg-slate-700/30 mb-1.5"></div>
                <div class="w-14 h-1.5 rounded bg-slate-500/25"></div>
              </div>
            </div>
            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Header Biasa</div>
            <div class="text-xs text-slate-400 mt-0.5">Tampilan statis dengan ilustrasi & teks.</div>
          </div>
        </label>

        {{-- Slide --}}
        <label class="header-type-card cursor-pointer" id="htcard-slide">
          <input type="radio" name="h_tipe" value="slide" class="sr-only" {{ $hTipe === 'slide' ? 'checked' : '' }} onchange="onHtipeChange('slide')">
          <div class="rounded-xl border-2 p-4 transition-all {{ $hTipe === 'slide' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300' }}">
            <div class="w-full h-24 rounded-lg mb-3 overflow-hidden relative"
                 style="background:linear-gradient(135deg,#0C7C46,#0E63A8)">
              <div class="absolute inset-0 flex items-center justify-around opacity-40">
                <div class="w-1 h-full bg-white/30"></div>
                <div class="w-1 h-full bg-white/30"></div>
              </div>
              <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                <span class="w-4 h-1 rounded-full bg-white"></span>
                <span class="w-1.5 h-1 rounded-full bg-white/40"></span>
                <span class="w-1.5 h-1 rounded-full bg-white/40"></span>
              </div>
              <div class="absolute inset-0 flex flex-col justify-center p-3">
                <div class="w-16 h-2 rounded bg-white/60 mb-1.5"></div>
                <div class="w-10 h-1.5 rounded bg-white/40"></div>
              </div>
            </div>
            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Slide Foto</div>
            <div class="text-xs text-slate-400 mt-0.5">Slideshow otomatis dari foto yang diunggah.</div>
          </div>
        </label>

        {{-- Video --}}
        <label class="header-type-card cursor-pointer" id="htcard-video">
          <input type="radio" name="h_tipe" value="video" class="sr-only" {{ $hTipe === 'video' ? 'checked' : '' }} onchange="onHtipeChange('video')">
          <div class="rounded-xl border-2 p-4 transition-all {{ $hTipe === 'video' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300' }}">
            <div class="w-full h-24 rounded-lg mb-3 overflow-hidden relative flex items-center justify-center"
                 style="background:#0F1B12">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" class="opacity-60"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="1.5"/><path d="M10 8l6 4-6 4V8z" fill="#fff"/></svg>
              <div class="absolute inset-0" style="background:repeating-linear-gradient(0deg,rgba(255,255,255,.04) 0 1px,transparent 1px 4px)"></div>
            </div>
            <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Background Video</div>
            <div class="text-xs text-slate-400 mt-0.5">Video YouTube atau file MP4 sebagai latar.</div>
          </div>
        </label>

      </div>
    </div>

    {{-- ── Teks & Elemen Header (semua tipe) ── --}}
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 space-y-5">
      <h3 class="font-semibold text-sm">Teks & Elemen Header</h3>

      {{-- Badge --}}
      <div>
        <label class="block text-sm font-medium mb-1.5">
          Teks Badge
          <span class="ml-1.5 text-xs font-normal text-slate-400">label kecil di atas judul</span>
        </label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 dark:text-slate-600">
            <i class="ti ti-tag text-sm"></i>
          </span>
          <input type="text" id="h_badge"
            class="w-full pl-8 pr-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
            value="{{ $hBadge }}"
            placeholder="Portal Resmi Pemerintah Desa">
        </div>
        <p class="text-xs text-slate-400 mt-1">Isi untuk menampilkan, kosongkan untuk menyembunyikan.</p>
      </div>

      {{-- Judul & Subjudul --}}
      <div class="grid grid-cols-1 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1.5">Judul Utama</label>
          <input type="text" id="h_judul"
            class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
            value="{{ $hJudul }}"
            placeholder="Selamat Datang di Desa {{ $settingsDesa['desa.nama'] ?? '' }}">
          <p class="text-xs text-slate-400 mt-1">Kosongkan untuk teks default otomatis.</p>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">Subjudul / Deskripsi</label>
          <textarea id="h_subjudul" rows="2"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"
            placeholder="Pusat informasi dan pelayanan publik digital desa…">{{ $hSubjudul }}</textarea>
        </div>
      </div>

      {{-- Tombol CTA --}}
      <div>
        <p class="text-sm font-medium mb-3">
          Tombol Aksi
          <span class="text-xs font-normal text-slate-400">— isi teks untuk menampilkan, kosongkan untuk menyembunyikan</span>
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          {{-- Tombol Primer --}}
          <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded bg-brand-600 flex-shrink-0"></span>
              <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wide">Tombol Primer</span>
            </div>
            <div>
              <label class="block text-xs text-slate-500 mb-1">Teks Tombol</label>
              <input type="text" id="h_btn_layanan"
                class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                value="{{ $hBtnLayanan }}"
                placeholder="cth: Lihat Layanan">
            </div>
            <div>
              <label class="block text-xs text-slate-500 mb-1">URL Tujuan</label>
              <div class="relative">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">#</span>
                <input type="text" id="h_btn_layanan_url"
                  class="w-full pl-6 pr-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                  value="{{ $hBtnLayananUrl }}"
                  placeholder="layanan  atau  https://...">
              </div>
              <p class="text-xs text-slate-400 mt-1">Kosong = otomatis ke <code>#layanan</code></p>
            </div>
          </div>

          {{-- Tombol Sekunder --}}
          <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded border-2 border-slate-400 flex-shrink-0"></span>
              <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wide">Tombol Sekunder</span>
            </div>
            <div>
              <label class="block text-xs text-slate-500 mb-1">Teks Tombol</label>
              <input type="text" id="h_btn_profil"
                class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                value="{{ $hBtnProfil }}"
                placeholder="cth: Profil Desa">
            </div>
            <div>
              <label class="block text-xs text-slate-500 mb-1">URL Tujuan</label>
              <div class="relative">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">#</span>
                <input type="text" id="h_btn_profil_url"
                  class="w-full pl-6 pr-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                  value="{{ $hBtnProfilUrl }}"
                  placeholder="profil  atau  https://...">
              </div>
              <p class="text-xs text-slate-400 mt-1">Kosong = otomatis ke <code>#profil</code></p>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- ── Pengaturan BIASA (foto background opsional) ── --}}
    @php $hBgFoto = $settingsHeader['header.bg_foto'] ?? null; @endphp
    <div id="hpanel-biasa" class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden"
         style="{{ $hTipe === 'biasa' ? '' : 'display:none' }}">
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
        <h3 class="font-semibold text-sm">Foto Background <span class="font-normal text-slate-400">(opsional)</span></h3>
        <p class="text-xs text-slate-400 mt-0.5">Jika diisi, foto ini akan menjadi latar header. Kosongkan untuk menggunakan ilustrasi bawaan.</p>
      </div>

      {{-- Preview area — tampil seperti hero di homepage --}}
      <div id="bgFotoPreviewBox"
           style="height:220px;background:#0F1B12;position:relative;overflow:hidden;{{ $hBgFoto ? '' : '' }}">
        {{-- Foto background --}}
        <img id="bgFotoPreviewImg"
             src="{{ $hBgFoto ? Storage::url($hBgFoto) : '' }}"
             alt=""
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:{{ $hBgFoto ? 'block' : 'none' }}">
        {{-- Gradient overlay --}}
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(5,20,12,.75) 0%,rgba(5,20,12,.2) 55%,transparent 100%);display:{{ $hBgFoto ? 'block' : 'none' }}" id="bgFotoOverlay"></div>
        {{-- Placeholder saat belum ada foto --}}
        <div id="bgFotoPh" style="position:absolute;inset:0;display:{{ $hBgFoto ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;gap:12px">
          <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center">
            <i class="ti ti-photo" style="font-size:1.8rem;color:rgba(255,255,255,.3)"></i>
          </div>
          <span style="color:rgba(255,255,255,.35);font-size:.82rem">Belum ada foto background</span>
        </div>
        {{-- Teks hero preview --}}
        <div id="bgFotoTextOverlay" style="position:absolute;bottom:0;left:0;right:0;padding:20px 24px;display:{{ $hBgFoto ? 'block' : 'none' }}">
          <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(8px);padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:700;color:#6FD5A0;margin-bottom:8px">
            <span style="width:6px;height:6px;border-radius:50%;background:#6FD5A0"></span> Portal Resmi Desa
          </div>
          <div style="font-size:1.05rem;font-weight:700;color:#fff;text-shadow:0 1px 8px rgba(0,0,0,.5)">{{ $hJudul ?: 'Judul Header Homepage' }}</div>
          <div style="font-size:.78rem;color:rgba(255,255,255,.7);margin-top:4px">{{ $hSubjudul ?: 'Subjudul / deskripsi singkat desa' }}</div>
        </div>
        {{-- Label preview --}}
        <div style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,.5);color:rgba(255,255,255,.7);font-size:.65rem;padding:3px 8px;border-radius:4px;letter-spacing:.05em">PREVIEW</div>
      </div>

      {{-- Tombol aksi --}}
      <div class="p-4 flex flex-wrap gap-2 items-center border-t border-slate-100 dark:border-slate-800">
        <label for="h_bg_foto_input"
          class="flex items-center gap-1.5 px-4 h-9 rounded-lg bg-brand-600 text-white text-sm font-medium cursor-pointer transition-colors"
          style="display:inline-flex">
          <i class="ti ti-upload text-sm"></i>
          <span id="bgFotoUploadLbl">{{ $hBgFoto ? 'Ganti Foto' : 'Upload Foto' }}</span>
        </label>
        <input type="file" id="h_bg_foto_input" accept="image/*" class="sr-only" onchange="previewBgFoto(this)">
        <input type="hidden" id="h_bg_foto_hapus" value="0">
        <button type="button" id="bgFotoHapusBtn" onclick="hapusBgFoto()"
          class="flex items-center gap-1.5 px-4 h-9 rounded-lg border border-rose-200 dark:border-rose-500/30 text-sm text-rose-500 transition-colors"
          style="{{ $hBgFoto ? '' : 'display:none' }}">
          <i class="ti ti-trash text-sm"></i> Hapus Foto
        </button>
        @if($hBgFoto)
          <span class="text-xs text-slate-400 ml-1">{{ basename($hBgFoto) }}</span>
        @endif
      </div>
    </div>

    {{-- ── Pengaturan VIDEO ── --}}
    <div id="hpanel-video" class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-5 space-y-4"
         style="{{ $hTipe === 'video' ? '' : 'display:none' }}">
      <h3 class="font-semibold text-sm">Pengaturan Video</h3>

      {{-- Mode switcher --}}
      <div class="flex gap-2">
        <button type="button" id="vmode-url-btn" onclick="setVideoMode('url')"
          class="flex items-center gap-1.5 px-4 h-8 rounded-lg text-sm font-medium transition-colors
                 {{ $hVideoMode === 'url' ? 'bg-brand-600 text-white' : 'border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
          <i class="ti ti-link text-sm"></i> URL YouTube / MP4
        </button>
        <button type="button" id="vmode-upload-btn" onclick="setVideoMode('upload')"
          class="flex items-center gap-1.5 px-4 h-8 rounded-lg text-sm font-medium transition-colors
                 {{ $hVideoMode === 'upload' ? 'bg-brand-600 text-white' : 'border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
          <i class="ti ti-upload text-sm"></i> Upload Video
        </button>
      </div>

      {{-- Mode: URL --}}
      <div id="vmode-url" style="{{ $hVideoMode === 'upload' ? 'display:none' : '' }}" class="space-y-3">
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-xs text-blue-700 dark:text-blue-300">
          <i class="ti ti-info-circle text-base flex-shrink-0 mt-0.5"></i>
          <span>Masukkan URL YouTube atau URL langsung file <code>.mp4</code>. Video diputar otomatis, tanpa suara, dan berulang.</span>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1.5">URL Video</label>
          <input type="text" id="h_video_url"
            class="w-full px-3 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
            value="{{ $hVideoUrl }}"
            placeholder="https://youtu.be/xxx  atau  https://example.com/video.mp4">
        </div>
        <div id="h_video_preview_wrap" style="{{ $hVideoUrl ? '' : 'display:none' }}">
          <label class="block text-xs text-slate-400 mb-1.5">Pratinjau</label>
          <div class="rounded-xl overflow-hidden aspect-video bg-black max-w-sm" id="h_video_preview_box">
            @if($hVideoUrl)
              @php
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $hVideoUrl, $ytm);
                $ytId = $ytm[1] ?? null;
              @endphp
              @if($ytId)
                <iframe src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=0&mute=1&loop=1&playlist={{ $ytId }}&controls=0"
                  class="w-full h-full" frameborder="0" allowfullscreen></iframe>
              @else
                <video src="{{ $hVideoUrl }}" class="w-full h-full object-cover" muted controls></video>
              @endif
            @endif
          </div>
        </div>
        <button type="button" onclick="previewVideo()" class="text-sm px-4 h-8 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          <i class="ti ti-player-play mr-1"></i>Pratinjau Video
        </button>
      </div>

      {{-- Mode: Upload file --}}
      <div id="vmode-upload" style="{{ $hVideoMode === 'upload' ? '' : 'display:none' }}" class="space-y-3">
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 text-xs text-amber-700 dark:text-amber-300">
          <i class="ti ti-alert-triangle text-base flex-shrink-0 mt-0.5"></i>
          <span>Upload file <code>.mp4</code>, <code>.webm</code>, atau <code>.ogg</code>. Maks 50 MB. Video diputar otomatis, tanpa suara, dan berulang di homepage.</span>
        </div>

        {{-- Preview area --}}
        <div id="bgVideoPreviewBox" style="position:relative;width:100%;border-radius:12px;overflow:hidden;background:#050D08;aspect-ratio:16/9;max-height:280px">
          {{-- Placeholder --}}
          <div id="bgVideoPh" style="position:absolute;inset:0;display:{{ $hBgVideo ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;gap:12px">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,.06);display:flex;align-items:center;justify-content:center">
              <i class="ti ti-video" style="font-size:2rem;color:rgba(255,255,255,.25)"></i>
            </div>
            <span style="color:rgba(255,255,255,.3);font-size:.82rem">Belum ada video</span>
          </div>
          {{-- Video player --}}
          <video id="bgVideoPreview"
                 src="{{ $hBgVideo ? Storage::url($hBgVideo) : '' }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:{{ $hBgVideo ? 'block' : 'none' }}"
                 muted controls playsinline></video>
          {{-- Label --}}
          <div style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,.55);color:rgba(255,255,255,.7);font-size:.65rem;padding:3px 8px;border-radius:4px;letter-spacing:.05em;z-index:5;pointer-events:none">PREVIEW</div>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex flex-wrap gap-2 items-center">
          <label for="h_bg_video_input"
            class="flex items-center gap-1.5 px-4 h-9 rounded-lg bg-brand-600 text-white text-sm font-medium cursor-pointer transition-colors"
            style="display:inline-flex">
            <i class="ti ti-upload text-sm"></i>
            <span id="bgVideoUploadLbl">{{ $hBgVideo ? 'Ganti Video' : 'Pilih Video' }}</span>
          </label>
          <input type="file" id="h_bg_video_input" accept="video/mp4,video/webm,video/ogg" class="sr-only" onchange="previewBgVideo(this)">
          <input type="hidden" id="h_bg_video_hapus" value="0">
          <button type="button" id="bgVideoHapusBtn" onclick="hapusBgVideo()"
            class="flex items-center gap-1.5 px-4 h-9 rounded-lg border border-rose-200 dark:border-rose-500/30 text-sm text-rose-500 transition-colors"
            style="{{ $hBgVideo ? '' : 'display:none' }}">
            <i class="ti ti-trash text-sm"></i> Hapus Video
          </button>
          @if($hBgVideo)
            <span class="text-xs text-slate-400">{{ basename($hBgVideo) }}</span>
          @endif
        </div>
      </div>
    </div>

    {{-- ── Pengaturan SLIDE ── --}}
    <div id="hpanel-slide" class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden"
         style="{{ $hTipe === 'slide' ? '' : 'display:none' }}">
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
        <div>
          <h3 class="font-semibold text-sm">Foto Slide</h3>
          <p class="text-xs text-slate-400 mt-0.5">Maksimal 8 slide. Rasio ideal 16:9. Maks 5 MB per foto.</p>
        </div>
        <button type="button" onclick="addSlide()"
          class="flex items-center gap-1.5 px-3 h-8 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold transition-colors">
          <i class="ti ti-plus text-sm"></i> Tambah Slide
        </button>
      </div>

      <div id="slides-list" class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($hSlides as $si => $slide)
        <div class="slide-item flex gap-4 p-4" data-index="{{ $si }}" data-path="{{ $slide['path'] }}">
          <div class="slide-thumb-wrap flex-shrink-0 relative w-28 h-16 rounded-lg overflow-hidden">
            <img src="{{ Storage::url($slide['path']) }}" alt="" class="slide-thumb absolute inset-0 w-full h-full object-cover">
            <div class="slide-placeholder absolute inset-0 flex flex-col items-center justify-center text-slate-400 text-xs gap-1 pointer-events-none hidden">
              <i class="ti ti-photo text-xl"></i><span>Pilih Foto</span>
            </div>
            <label class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100
                          cursor-pointer transition-opacity text-white text-[11px] font-semibold z-10">
              <i class="ti ti-camera mr-1"></i><span class="slide-upload-lbl">Ganti</span>
              <input type="file" accept="image/*" class="sr-only slide-file-input" onchange="onSlideFileChange(this)">
            </label>
          </div>
          <div class="flex-1 min-w-0 space-y-2">
            <input type="text" class="slide-judul w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                   value="{{ $slide['judul'] }}" placeholder="Judul slide (opsional)">
            <input type="text" class="slide-subjudul w-full px-3 h-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                   value="{{ $slide['subjudul'] }}" placeholder="Subjudul slide (opsional)">
          </div>
          <button type="button" onclick="removeSlide(this)"
            class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-700 text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:border-rose-300 transition-colors self-center">
            <i class="ti ti-trash text-sm"></i>
          </button>
        </div>
        @empty
        <div id="slides-empty" class="p-10 text-center text-sm text-slate-400">
          <i class="ti ti-photo-off text-3xl block mb-2 text-slate-300 dark:text-slate-600"></i>
          Belum ada slide. Klik <strong>Tambah Slide</strong> untuk menambahkan foto.
        </div>
        @endforelse
      </div>
    </div>

    {{-- ── Save ── --}}
    <div class="flex items-center gap-3">
      <button type="button" onclick="saveHeader()"
        class="flex items-center gap-2 px-5 h-10 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold transition-colors">
        <i class="ti ti-device-floppy text-base"></i> Simpan Pengaturan Header
      </button>
      <span id="headerHint" class="text-xs text-brand-600 dark:text-brand-100" style="display:none">
        <i class="ti ti-circle-check"></i> Tersimpan
      </span>
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB — Profil Desa
══════════════════════════════════════════════════ --}}
<div id="panel-profil" style="display:none">
  <div class="max-w-3xl space-y-5">

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h3 class="font-semibold text-sm text-slate-900 dark:text-slate-100">Sambutan Kepala Desa</h3>
        <p class="text-xs text-slate-400 mt-0.5">Teks sambutan ditampilkan di halaman beranda dan halaman profil desa.</p>
      </div>
      <div class="p-6">
        <label class="block text-sm font-medium mb-1.5">Isi Sambutan</label>
        <textarea id="p_sambutan" rows="6"
          class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-y leading-relaxed"
          placeholder="Tuliskan sambutan kepala desa di sini…" maxlength="3000">{{ $settingsDesa['desa.sambutan'] ?? '' }}</textarea>
        <p class="text-xs text-slate-400 mt-1.5">Maksimal 3.000 karakter. Di beranda hanya ditampilkan ringkasan ~200 karakter pertama.</p>
      </div>
    </div>

    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
        <h3 class="font-semibold text-sm text-slate-900 dark:text-slate-100">Visi &amp; Misi</h3>
        <p class="text-xs text-slate-400 mt-0.5">Ditampilkan di beranda dan halaman profil desa.</p>
      </div>
      <div class="p-6 space-y-4">

        <div>
          <label class="block text-sm font-medium mb-1.5">Visi Desa</label>
          <textarea id="p_visi" rows="3"
            class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-y leading-relaxed"
            placeholder="Tuliskan visi desa…" maxlength="1000">{{ $settingsDesa['desa.visi'] ?? '' }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1.5">Misi Desa</label>
          <textarea id="p_misi" rows="6"
            class="w-full px-3 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent resize-y leading-relaxed"
            placeholder="Satu misi per baris, contoh:&#10;Mewujudkan tata kelola pemerintahan yang transparan&#10;Meningkatkan kualitas pelayanan publik&#10;…" maxlength="3000">{{ $settingsDesa['desa.misi'] ?? '' }}</textarea>
          <p class="text-xs text-slate-400 mt-1.5">Tulis satu poin misi per baris. Setiap baris akan ditampilkan sebagai butir misi terpisah.</p>
        </div>

      </div>
    </div>

    <div class="flex items-center gap-3 pt-1">
      <button type="button" onclick="saveProfil()"
        class="flex items-center gap-2 px-4 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
        <i class="ti ti-device-floppy text-base"></i> Simpan Profil Desa
      </button>
      <span id="profilHint" class="text-xs text-brand-600 dark:text-brand-400 flex items-center gap-1" style="display:none">
        <i class="ti ti-circle-check"></i> Tersimpan
      </span>
    </div>

  </div>
</div>

{{-- ══════════════════════════════════════════════════
     TAB 6 — Fitur
══════════════════════════════════════════════════ --}}
<div id="panel-fitur" style="display:none">
  <div class="space-y-4">

    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-semibold text-sm text-slate-900 dark:text-slate-100">Fitur Publik</h3>
        <p class="text-xs text-slate-400 mt-0.5">Perubahan disimpan otomatis saat toggle diubah.</p>
      </div>
      <span id="fiturHint" class="text-xs text-brand-600 dark:text-brand-400 flex items-center gap-1" style="display:none">
        <i class="ti ti-circle-check"></i> Tersimpan
      </span>
    </div>

    <div class="rounded-xl border border-slate-200 dark:border-slate-800 divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">

      {{-- Grafik: Demografi Penduduk --}}
      @php $demografiOn = ($settingsFitur['publik.grafik.demografi'] ?? '1') === '1'; @endphp
      <div class="bg-white dark:bg-slate-900 border-l-4 transition-colors {{ $demografiOn ? 'border-l-violet-400 dark:border-l-violet-500' : 'border-l-transparent' }} px-5 py-4 flex items-center gap-4"
           id="card-grafik_demografi">
        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-users text-xl text-violet-600 dark:text-violet-400"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Grafik Demografi</div>
          <div class="text-xs text-slate-400 mt-0.5">Jenis kelamin, kelompok umur, pendidikan, pekerjaan, agama, status perkawinan.</div>
        </div>
        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $demografiOn ? 'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}"
              id="badge-grafik_demografi">{{ $demografiOn ? 'Aktif' : 'Nonaktif' }}</span>
        <div class="toggle {{ $demografiOn ? 'on' : '' }} flex-shrink-0" id="toggleGrafikDemografi" onclick="toggleFitur('grafik_demografi', this)"><i></i></div>
      </div>

      {{-- Grafik: APBDes --}}
      @php $apbdesGrafikOn = ($settingsFitur['publik.grafik.apbdes'] ?? '1') === '1'; @endphp
      <div class="bg-white dark:bg-slate-900 border-l-4 transition-colors {{ $apbdesGrafikOn ? 'border-l-emerald-400 dark:border-l-emerald-500' : 'border-l-transparent' }} px-5 py-4 flex items-center gap-4"
           id="card-grafik_apbdes">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-chart-line text-xl text-emerald-600 dark:text-emerald-400"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Grafik APBDes</div>
          <div class="text-xs text-slate-400 mt-0.5">Anggaran vs realisasi pendapatan dan belanja desa per tahun anggaran.</div>
        </div>
        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $apbdesGrafikOn ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}"
              id="badge-grafik_apbdes">{{ $apbdesGrafikOn ? 'Aktif' : 'Nonaktif' }}</span>
        <div class="toggle {{ $apbdesGrafikOn ? 'on' : '' }} flex-shrink-0" id="toggleGrafikApbdes" onclick="toggleFitur('grafik_apbdes', this)"><i></i></div>
      </div>

      {{-- Grafik: Layanan Surat --}}
      @php $suratGrafikOn = ($settingsFitur['publik.grafik.surat'] ?? '1') === '1'; @endphp
      <div class="bg-white dark:bg-slate-900 border-l-4 transition-colors {{ $suratGrafikOn ? 'border-l-amber-400 dark:border-l-amber-500' : 'border-l-transparent' }} px-5 py-4 flex items-center gap-4"
           id="card-grafik_surat">
        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-file-text text-xl text-amber-600 dark:text-amber-400"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Grafik Layanan Surat</div>
          <div class="text-xs text-slate-400 mt-0.5">Tren pengajuan surat per bulan dan distribusi per jenis surat.</div>
        </div>
        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $suratGrafikOn ? 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}"
              id="badge-grafik_surat">{{ $suratGrafikOn ? 'Aktif' : 'Nonaktif' }}</span>
        <div class="toggle {{ $suratGrafikOn ? 'on' : '' }} flex-shrink-0" id="toggleGrafikSurat" onclick="toggleFitur('grafik_surat', this)"><i></i></div>
      </div>

      {{-- Grafik: Publikasi & Konten --}}
      @php $kontenGrafikOn = ($settingsFitur['publik.grafik.konten'] ?? '1') === '1'; @endphp
      <div class="bg-white dark:bg-slate-900 border-l-4 transition-colors {{ $kontenGrafikOn ? 'border-l-blue-400 dark:border-l-blue-500' : 'border-l-transparent' }} px-5 py-4 flex items-center gap-4"
           id="card-grafik_konten">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 grid place-items-center flex-shrink-0">
          <i class="ti ti-news text-xl text-blue-600 dark:text-blue-400"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="font-semibold text-sm text-slate-900 dark:text-slate-100">Grafik Publikasi</div>
          <div class="text-xs text-slate-400 mt-0.5">Total berita, arsip, pengumuman, serta tren publikasi per bulan.</div>
        </div>
        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $kontenGrafikOn ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}"
              id="badge-grafik_konten">{{ $kontenGrafikOn ? 'Aktif' : 'Nonaktif' }}</span>
        <div class="toggle {{ $kontenGrafikOn ? 'on' : '' }} flex-shrink-0" id="toggleGrafikKonten" onclick="toggleFitur('grafik_konten', this)"><i></i></div>
      </div>

    </div>

  </div>

  </div>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL NAVBAR
══════════════════════════════════════════════════ --}}
<div id="navModal" style="display:none"
  class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeNavModal()"></div>
  <div class="relative z-10 w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 max-h-[90vh] overflow-y-auto">

    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900 z-10">
      <h2 class="font-semibold text-base" id="navModalTitle">Tambah Item Menu</h2>
      <button type="button" onclick="closeNavModal()"
        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-400">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <form id="navForm" method="POST" class="p-6 space-y-4">
      @csrf
      <div id="navMethodField"></div>

      {{-- Label --}}
      <div>
        <label class="block text-sm font-medium mb-1.5">Label <span class="text-rose-500">*</span></label>
        <input type="text" name="label" id="nf_label" required maxlength="100"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
          placeholder="Contoh: Profil Desa">
      </div>

      {{-- Jenis menu --}}
      <div>
        <label class="block text-sm font-medium mb-2">Jenis Menu</label>
        <div class="flex gap-3">
          <label class="flex items-center gap-2 cursor-pointer text-sm">
            <input type="radio" name="nf_jenis" value="utama" checked onchange="onJenisChange('utama')"
              class="accent-brand-600"> Menu Utama
          </label>
          <label class="flex items-center gap-2 cursor-pointer text-sm">
            <input type="radio" name="nf_jenis" value="sub" onchange="onJenisChange('sub')"
              class="accent-brand-600"> Sub Menu
          </label>
        </div>
      </div>

      {{-- Parent (muncul saat sub menu) --}}
      <div id="nf_parent_wrap" style="display:none">
        <label class="block text-sm font-medium mb-1.5">Menu Induk <span class="text-rose-500">*</span></label>
        <select name="parent_id" id="nf_parent_id"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
          <option value="">-- pilih menu utama --</option>
          @foreach($navParents as $prt)
            <option value="{{ $prt->id }}">{{ $prt->label }}</option>
          @endforeach
        </select>
      </div>

      {{-- Sumber link --}}
      <div>
        <label class="block text-sm font-medium mb-2">Sumber Link</label>
        <div class="grid grid-cols-3 gap-2">
          <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all border-slate-200 dark:border-slate-700" id="tipe_label_custom">
            <input type="radio" name="tipe_link" value="custom" checked onchange="onTipeChange('custom')" class="hidden">
            <i class="ti ti-link text-lg"></i>
            <span class="text-xs font-medium">Custom URL</span>
          </label>
          <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all border-slate-200 dark:border-slate-700" id="tipe_label_page">
            <input type="radio" name="tipe_link" value="page" onchange="onTipeChange('page')" class="hidden">
            <i class="ti ti-file-text text-lg"></i>
            <span class="text-xs font-medium">Dari Page</span>
          </label>
          <label class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all border-slate-200 dark:border-slate-700" id="tipe_label_arsip">
            <input type="radio" name="tipe_link" value="arsip" onchange="onTipeChange('arsip')" class="hidden">
            <i class="ti ti-archive text-lg"></i>
            <span class="text-xs font-medium">Arsip</span>
          </label>
        </div>
      </div>

      {{-- Custom URL --}}
      <div id="nf_custom_wrap" class="space-y-3">
        <div>
          <label class="block text-sm font-medium mb-1.5">URL <span class="text-rose-500">*</span></label>
          <input type="text" name="link_url" id="nf_link_url"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none"
            placeholder="https://... atau /path/relatif">
        </div>
      </div>

      {{-- Dari Page --}}
      <div id="nf_page_wrap" style="display:none">
        <label class="block text-sm font-medium mb-1.5">Pilih Halaman (Page) <span class="text-rose-500">*</span></label>
        <select name="page_id" id="nf_page_id"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
          <option value="">-- pilih halaman --</option>
          @foreach($pagesForNav as $pg)
            <option value="{{ $pg->id }}">{{ $pg->judul }}</option>
          @endforeach
        </select>
      </div>

      {{-- Arsip filter --}}
      <div id="nf_arsip_wrap" style="display:none" class="space-y-3">
        <div>
          <label class="block text-sm font-medium mb-2">Tampilkan</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 text-sm cursor-pointer">
              <input type="radio" name="arsip_filter" value="semua" checked onchange="onArsipFilterChange('semua')"
                class="accent-brand-600"> Semua Arsip (Publish)
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
              <input type="radio" name="arsip_filter" value="kategori" onchange="onArsipFilterChange('kategori')"
                class="accent-brand-600"> Per Kategori
            </label>
          </div>
        </div>
        <div id="nf_arsip_kat_wrap" style="display:none">
          <label class="block text-sm font-medium mb-1.5">Pilih Kategori Arsip <span class="text-rose-500">*</span></label>
          <select name="kategori_arsip_id" id="nf_kategori_arsip_id"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
            <option value="">-- pilih kategori --</option>
            @foreach($kategoriArsips as $ka)
              <option value="{{ $ka->id }}">{{ $ka->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Target buka --}}
      <div>
        <label class="block text-sm font-medium mb-1.5">Buka Di</label>
        <select name="target" id="nf_target"
          class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
          <option value="_self">Tab yang sama</option>
          <option value="_blank">Tab baru</option>
        </select>
      </div>

      {{-- Urutan & Aktif --}}
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1.5">Urutan</label>
          <input type="number" name="urutan" id="nf_urutan" value="0" min="0" max="255"
            class="w-full px-3 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none">
        </div>
        <div class="flex flex-col">
          <label class="block text-sm font-medium mb-1.5">Status</label>
          <label class="flex items-center gap-3 h-9 cursor-pointer select-none">
            <input type="hidden" name="aktif" value="0">
            <div class="relative flex-shrink-0 w-10 h-6">
              <input type="checkbox" name="aktif" id="nf_aktif" value="1" checked class="sr-only peer">
              <div class="absolute inset-0 rounded-full bg-slate-200 dark:bg-slate-700 peer-checked:bg-brand-600 transition-colors duration-200"></div>
              <div class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white shadow-sm transition-transform duration-200 peer-checked:translate-x-4"></div>
            </div>
            <span class="text-sm">Aktif</span>
          </label>
        </div>
      </div>

      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeNavModal()"
          class="flex-1 h-9 rounded-lg border border-slate-200 dark:border-slate-700 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
          Batal
        </button>
        <button type="submit"
          class="flex-1 flex items-center justify-center gap-2 h-9 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium transition-colors">
          <i class="ti ti-device-floppy text-base"></i>
          <span id="nf_btn_label">Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
/* ── Data dari server ── */
let SETTINGS_DESA  = @json($settingsDesa ?? []);
let SETTINGS_API   = @json($settingsApi  ?? []);
let API_KEY_EXISTS = @json($apiKeyExists ?? false);

/* ── Tema JS (sama dengan di <head> anti-FOUC) ── */
const THEMES = {
  hijau: {50:'236 253 245',100:'209 250 229',500:'16 185 129', 600:'5 150 105', 700:'4 120 87'},
  biru:  {50:'239 246 255',100:'219 234 254',500:'59 130 246', 600:'37 99 235', 700:'29 78 216'},
  teal:  {50:'240 253 250',100:'204 251 241',500:'20 184 166', 600:'13 148 136',700:'15 118 110'},
  ungu:  {50:'245 243 255',100:'237 233 254',500:'139 92 246', 600:'124 58 237',700:'109 40 217'},
  merah: {50:'254 242 242',100:'254 226 226',500:'239 68 68',  600:'220 38 38', 700:'185 28 28'},
  amber: {50:'255 251 235',100:'254 243 199',500:'245 158 11', 600:'217 119 6', 700:'180 83 9'},
};
const THEME_NAMES = {
  hijau:'Hijau Padi', biru:'Biru Bahari', teal:'Teal Laut',
  ungu:'Ungu Senja', merah:'Merah Garuda', amber:'Amber Agung',
};
let activeTheme = localStorage.getItem('villageTheme') || 'hijau';

/* ── Terapkan preset warna ── */
function applyPreset(key) {
  const t = THEMES[key]; if (!t) return;
  activeTheme = key;
  const s = document.documentElement.style;
  s.setProperty('--brand-50',  t[50]);
  s.setProperty('--brand-100', t[100]);
  s.setProperty('--brand-500', t[500]);
  s.setProperty('--brand-600', t[600]);
  s.setProperty('--brand-700', t[700]);
  localStorage.setItem('villageTheme', key);
  highlightActivePreset();
  toast('Tema "' + THEME_NAMES[key] + '" diterapkan', 'success');
}

function highlightActivePreset() {
  Object.keys(THEMES).forEach(k => {
    const btn   = document.getElementById('preset-' + k);
    const check = document.getElementById('check-' + k);
    const isOn  = k === activeTheme;
    if (btn) {
      btn.style.borderColor  = isOn ? 'rgb(var(--brand-600))' : '';
      btn.style.background   = isOn ? 'rgb(var(--brand-50))'  : '';
    }
    if (check) check.style.opacity = isOn ? '1' : '0';
    if (check) check.style.color = 'rgb(var(--brand-600))';
  });
  const nameEl = document.getElementById('activeThemeName');
  if (nameEl) nameEl.textContent = THEME_NAMES[activeTheme] || '—';
}

/* ── Dark mode ── */
function toggleDarkMode(el) {
  const dark = !document.documentElement.classList.contains('dark');
  el.classList.toggle('on', dark);
  document.documentElement.classList.toggle('dark', dark);
  localStorage.setItem('villageMode', dark ? 'dark' : 'light');
  toast(dark ? 'Mode gelap aktif' : 'Mode terang aktif', 'success');
}

/* ── Tab switching (pakai style.display — tidak tergantung Tailwind) ── */
const PANELS = ['tampilan', 'api', 'desa', 'navbar', 'header', 'profil', 'fitur'];

function switchTab(active) {
  PANELS.forEach(t => {
    const panel = document.getElementById('panel-' + t);
    const btn   = document.getElementById('tab-btn-' + t);
    const isOn  = t === active;

    if (panel) panel.style.display = isOn ? 'block' : 'none';

    if (btn) {
      if (isOn) {
        btn.style.background = 'white';
        btn.style.color      = '#0f172a';
        btn.style.boxShadow  = '0 1px 3px rgba(0,0,0,.1)';
      } else {
        btn.style.background = 'transparent';
        btn.style.color      = '';
        btn.style.boxShadow  = '';
      }
    }
  });
}

/* ── Toggle API key visibility (per field) ── */
function toggleApiKey(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  const show   = input.type === 'password';
  input.type   = show ? 'text' : 'password';
  const icon   = btn.querySelector('i');
  if (icon) icon.className = show ? 'ti ti-eye-off text-base' : 'ti ti-eye text-base';
}

/* ── Provider selection ── */
function onProviderChange(slug) {
  const all = ['anthropic','openai','google','custom'];
  all.forEach(s => {
    const card  = document.getElementById('card-' + s);
    const badge = document.getElementById('badge-' + s);
    const on    = s === slug;
    if (card) {
      card.classList.toggle('border-brand-400',           on);
      card.classList.toggle('dark:border-brand-500',      on);
      card.classList.toggle('border-slate-200',          !on);
      card.classList.toggle('dark:border-slate-800',     !on);
    }
    if (badge) {
      badge.textContent = on ? 'Aktif' : 'Nonaktif';
      badge.classList.toggle('bg-brand-100',              on);
      badge.classList.toggle('dark:bg-brand-500/20',      on);
      badge.classList.toggle('text-brand-700',            on);
      badge.classList.toggle('dark:text-brand-300',       on);
      badge.classList.toggle('bg-slate-100',             !on);
      badge.classList.toggle('dark:bg-slate-800',        !on);
      badge.classList.toggle('text-slate-400',           !on);
    }
  });
}

/* ── Logo upload helpers ── */
function previewLogo(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img  = document.getElementById('logoPreview');
    const icon = document.getElementById('logoPlaceholder');
    img.src = e.target.result;
    img.classList.remove('hidden');
    icon.classList.add('hidden');
    document.getElementById('hapusLogoBtn').style.display = '';
    document.getElementById('hapusLogoInput').value = '0';
    // Update preview card
    syncLogoPreview(e.target.result);
  };
  reader.readAsDataURL(file);
}

function hapusLogo() {
  document.getElementById('logoInput').value        = '';
  document.getElementById('logoPreview').src        = '';
  document.getElementById('logoPreview').classList.add('hidden');
  document.getElementById('logoPlaceholder').classList.remove('hidden');
  document.getElementById('hapusLogoBtn').style.display = 'none';
  document.getElementById('hapusLogoInput').value   = '1';
  syncLogoPreview(null);
}

function syncLogoPreview(src) {
  const wrap    = document.getElementById('prev-logo-wrap');
  const img     = document.getElementById('prev-logo-img');
  const inisial = document.getElementById('prev-inisial');
  if (src) {
    img.src = src;
    img.classList.remove('hidden');
    inisial.classList.add('hidden');
    if (wrap) wrap.style.background = '#f8fafc';
  } else {
    img.src = '';
    img.classList.add('hidden');
    inisial.classList.remove('hidden');
    if (wrap) wrap.style.background = 'rgb(var(--brand-600))';
  }
}

/* ── Live preview identitas desa ── */
function updateDesaPreview() {
  const g = id => document.getElementById(id)?.value.trim() || '';
  const nama = g('d_nama');
  const inisial = nama ? nama.replace(/\s+/g,' ').split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase() : '—';
  const lokasi  = [g('d_kecamatan'), g('d_kabupaten'), g('d_provinsi')].filter(Boolean).join(', ') || '—';
  const kontak  = [g('d_email'), g('d_whatsapp')].filter(Boolean).join(' · ') || '—';
  const pn = document.getElementById('prev-nama');
  const pi = document.getElementById('prev-inisial');
  const pl = document.getElementById('prev-lokasi');
  const pk = document.getElementById('prev-kontak');
  if (pn) pn.textContent = nama || '—';
  if (pi) pi.textContent = inisial;
  if (pl) pl.textContent = lokasi;
  if (pk) pk.textContent = kontak;
}

/* ── AJAX helper ── */
async function apiPost(url, data) {
  try {
    const res  = await fetch(url, {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        'Accept':       'application/json',
      },
      body: JSON.stringify(data),
    });
    const json = await res.json();
    if (!res.ok) {
      const err = json.errors ? Object.values(json.errors)[0]?.[0] : null;
      toast(err || json.message || 'Gagal menyimpan', 'error');
      return false;
    }
    toast(json.message, 'success');
    return true;
  } catch(e) {
    toast('Gagal terhubung ke server', 'error');
    return false;
  }
}

function showHint(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.style.display = 'inline-flex';
  clearTimeout(el._t);
  el._t = setTimeout(() => el.style.display = 'none', 3000);
}

/* ── Save identitas desa (FormData — support file upload) ── */
async function saveDesa() {
  const g  = id => document.getElementById(id)?.value.trim() || '';
  const fd = new FormData();
  ['nama','kecamatan','kabupaten','provinsi','kode_pos','kepala','sekretaris','alamat','whatsapp','email']
    .forEach(f => fd.append(f, g('d_' + f)));
  const logoFile = document.getElementById('logoInput')?.files[0];
  if (logoFile) fd.append('logo', logoFile);
  fd.append('hapus_logo', document.getElementById('hapusLogoInput')?.value || '0');

  try {
    const res  = await fetch('{{ route("admin.settings.desa") }}', {
      method:  'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        'Accept':       'application/json',
      },
      body: fd,
    });
    const json = await res.json();
    if (!res.ok) {
      const err = json.errors ? Object.values(json.errors)[0]?.[0] : null;
      toast(err || json.message || 'Gagal menyimpan', 'error');
      return;
    }
    // Jika logo baru berhasil disimpan, update preview dari URL server
    if (json.logo_url) syncLogoPreview(json.logo_url);
    toast(json.message, 'success');
    showHint('desaHint');
  } catch(e) {
    toast('Gagal terhubung ke server', 'error');
  }
}

/* ── Save API settings (multi-provider) ── */
async function saveApi() {
  const g      = id => document.getElementById(id)?.value.trim() || '';
  const active = document.querySelector('input[name="api_active"]:checked')?.value || 'openai';

  const payload = {
    active,
    limit: g('api_limit'),
    anthropic: { key: g('anthropic_key'), model: g('anthropic_model') },
    openai:    { key: g('openai_key'),    model: g('openai_model')    },
    google:    { key: g('google_key'),    model: g('google_model')    },
    custom: {
      label:  g('custom_label'),
      url:    g('custom_url'),
      key:    g('custom_key'),
      secret: g('custom_secret'),
      model:  g('custom_model'),
    },
  };

  const ok = await apiPost('{{ route("admin.settings.api") }}', payload);
  if (ok) {
    // Clear key fields after save
    ['anthropic_key','openai_key','google_key','custom_key','custom_secret'].forEach(id => {
      const el = document.getElementById(id);
      if (el && el.value) el.value = '';
    });
    showHint('apiHint');
  }
}

/* ── Profil desa ── */
async function saveProfil() {
  const ok = await apiPost('{{ route("admin.settings.profil") }}', {
    sambutan: document.getElementById('p_sambutan')?.value ?? '',
    visi:     document.getElementById('p_visi')?.value ?? '',
    misi:     document.getElementById('p_misi')?.value ?? '',
  });
  if (ok) showHint('profilHint');
}

/* ── Fitur publik ── */
const _fiturState = {
  grafik_demografi: {{ ($settingsFitur['publik.grafik.demografi'] ?? '1') === '1' ? 'true' : 'false' }},
  grafik_apbdes:    {{ ($settingsFitur['publik.grafik.apbdes']    ?? '1') === '1' ? 'true' : 'false' }},
  grafik_surat:     {{ ($settingsFitur['publik.grafik.surat']     ?? '1') === '1' ? 'true' : 'false' }},
  grafik_konten:    {{ ($settingsFitur['publik.grafik.konten']    ?? '1') === '1' ? 'true' : 'false' }},
};

const _fiturColors = {
  grafik_demografi: { border: 'border-l-violet-400 dark:border-l-violet-500',   badge: 'bg-violet-100 dark:bg-violet-500/20 text-violet-700 dark:text-violet-300' },
  grafik_apbdes:    { border: 'border-l-emerald-400 dark:border-l-emerald-500', badge: 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300' },
  grafik_surat:     { border: 'border-l-amber-400 dark:border-l-amber-500',     badge: 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300' },
  grafik_konten:    { border: 'border-l-blue-400 dark:border-l-blue-500',       badge: 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300' },
};

function toggleFitur(key, el) {
  _fiturState[key] = !_fiturState[key];
  const on     = _fiturState[key];
  const colors = _fiturColors[key] || { border: 'border-l-brand-400', badge: 'bg-brand-100 text-brand-700' };
  const card   = document.getElementById('card-' + key);
  const badge  = document.getElementById('badge-' + key);

  el.classList.toggle('on', on);
  if (card) {
    colors.border.split(' ').forEach(c => card.classList.toggle(c, on));
    card.classList.toggle('border-l-transparent', !on);
  }
  if (badge) {
    badge.textContent = on ? 'Aktif' : 'Nonaktif';
    colors.badge.split(' ').forEach(c => badge.classList.toggle(c, on));
    ['bg-slate-100','dark:bg-slate-800','text-slate-400'].forEach(c => badge.classList.toggle(c, !on));
  }

  saveFitur();
}

async function saveFitur() {
  const ok = await apiPost('{{ route("admin.settings.fitur") }}', {
    grafik_demografi: _fiturState.grafik_demografi,
    grafik_apbdes:    _fiturState.grafik_apbdes,
    grafik_surat:     _fiturState.grafik_surat,
    grafik_konten:    _fiturState.grafik_konten,
  });
  if (ok) showHint('fiturHint');
}

/* ══════════════════════════════════════════════════
   HEADER HOMEPAGE
══════════════════════════════════════════════════ */

let _hTipe = '{{ $hTipe }}';

function onHtipeChange(tipe) {
  _hTipe = tipe;
  ['biasa','slide','video'].forEach(t => {
    const card = document.getElementById('htcard-' + t);
    if (!card) return;
    const inner = card.querySelector('div');
    const on = t === tipe;
    inner.classList.toggle('border-brand-500', on);
    inner.classList.toggle('bg-brand-50',      on);
    inner.classList.toggle('dark:bg-brand-500/10', on);
    inner.classList.toggle('border-slate-200', !on);
    inner.classList.toggle('dark:border-slate-700', !on);
  });
  document.getElementById('hpanel-biasa').style.display = tipe === 'biasa' ? '' : 'none';
  document.getElementById('hpanel-video').style.display = tipe === 'video' ? '' : 'none';
  document.getElementById('hpanel-slide').style.display = tipe === 'slide' ? '' : 'none';
}

/* ── Video mode switcher ── */
let _videoMode = '{{ $hVideoMode }}';
function setVideoMode(mode) {
  _videoMode = mode;
  document.getElementById('vmode-url').style.display    = mode === 'url'    ? '' : 'none';
  document.getElementById('vmode-upload').style.display = mode === 'upload' ? '' : 'none';
  ['url', 'upload'].forEach(m => {
    const btn = document.getElementById('vmode-' + m + '-btn');
    if (!btn) return;
    const active = m === mode;
    btn.classList.toggle('bg-brand-600', active);
    btn.classList.toggle('text-white', active);
    btn.classList.toggle('border', !active);
    btn.classList.toggle('border-slate-200', !active);
    btn.classList.toggle('dark:border-slate-700', !active);
    btn.classList.toggle('text-slate-600', !active);
    btn.classList.toggle('dark:text-slate-300', !active);
    btn.classList.toggle('hover:bg-slate-50', !active);
    btn.classList.toggle('dark:hover:bg-slate-800', !active);
  });
}

/* ── Video preview (URL mode) ── */
function previewVideo() {
  const url  = document.getElementById('h_video_url')?.value.trim();
  const wrap = document.getElementById('h_video_preview_wrap');
  const box  = document.getElementById('h_video_preview_box');
  if (!url || !box) return;
  const ytMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
  const ytId = ytMatch?.[1];
  if (ytId) {
    box.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1&mute=1&loop=1&playlist=${ytId}&controls=0" class="w-full h-full" frameborder="0" allowfullscreen></iframe>`;
  } else {
    box.innerHTML = `<video src="${url}" class="w-full h-full object-cover" autoplay muted loop controls></video>`;
  }
  if (wrap) wrap.style.display = '';
}

/* ── Video upload (Upload mode) ── */
function previewBgVideo(input) {
  const file = input.files[0];
  if (!file) return;
  if (file.size > 50 * 1024 * 1024) { toast('Ukuran video maks 50 MB', 'error'); input.value = ''; return; }
  const url  = URL.createObjectURL(file);
  const vid  = document.getElementById('bgVideoPreview');
  const ph   = document.getElementById('bgVideoPh');
  const lbl  = document.getElementById('bgVideoUploadLbl');
  if (vid) { vid.src = url; vid.style.display = 'block'; vid.load(); }
  if (ph)  ph.style.display = 'none';
  if (lbl) lbl.textContent = 'Ganti Video';
  document.getElementById('bgVideoHapusBtn').style.display = '';
  document.getElementById('h_bg_video_hapus').value = '0';
}

function hapusBgVideo() {
  const vid = document.getElementById('bgVideoPreview');
  const ph  = document.getElementById('bgVideoPh');
  const lbl = document.getElementById('bgVideoUploadLbl');
  if (vid) { vid.src = ''; vid.style.display = 'none'; }
  if (ph)  ph.style.display = 'flex';
  if (lbl) lbl.textContent = 'Pilih Video';
  document.getElementById('h_bg_video_hapus').value = '1';
  document.getElementById('bgVideoHapusBtn').style.display = 'none';
  document.getElementById('h_bg_video_input').value = '';
}

/* ── Slide management ── */
function addSlide() {
  const list  = document.getElementById('slides-list');
  const empty = document.getElementById('slides-empty');
  if (empty) empty.remove();

  const idx = list.querySelectorAll('.slide-item').length;
  if (idx >= 8) { toast('Maksimal 8 slide', 'error'); return; }

  list.insertAdjacentHTML('beforeend', `
  <div class="slide-item flex gap-4 p-4" data-index="${idx}" data-path="">
    <div class="flex-shrink-0 slide-thumb-wrap relative w-28 h-16 rounded-lg overflow-hidden
                bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-300 dark:border-slate-600">
      <img class="slide-thumb absolute inset-0 w-full h-full object-cover hidden" src="" alt="">
      <div class="slide-placeholder absolute inset-0 flex flex-col items-center justify-center text-slate-400 text-xs gap-1 pointer-events-none">
        <i class="ti ti-photo text-xl"></i><span>Pilih Foto</span>
      </div>
      <label class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100
                    cursor-pointer transition-opacity text-white text-[11px] font-semibold z-10">
        <i class="ti ti-upload mr-1"></i><span class="slide-upload-lbl">Upload</span>
        <input type="file" accept="image/*" class="sr-only slide-file-input" onchange="onSlideFileChange(this)">
      </label>
    </div>
    <div class="flex-1 min-w-0 space-y-2">
      <input type="text" class="slide-judul w-full px-3 h-8 rounded-lg border border-slate-200
             dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
             placeholder="Judul slide (opsional)">
      <input type="text" class="slide-subjudul w-full px-3 h-8 rounded-lg border border-slate-200
             dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
             placeholder="Subjudul slide (opsional)">
    </div>
    <button type="button" onclick="removeSlide(this)"
      class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200
             dark:border-slate-700 text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10
             hover:border-rose-300 transition-colors self-center">
      <i class="ti ti-trash text-sm"></i>
    </button>
  </div>`);
}

function removeSlide(btn) {
  const item = btn.closest('.slide-item');
  if (item) item.remove();
  const list = document.getElementById('slides-list');
  if (!list.querySelector('.slide-item')) {
    list.innerHTML = `<div id="slides-empty" class="p-10 text-center text-sm text-slate-400">
      <i class="ti ti-photo-off text-3xl block mb-2 text-slate-300 dark:text-slate-600"></i>
      Belum ada slide. Klik <strong>Tambah Slide</strong>.</div>`;
  }
}

/* Hanya update preview — jangan ganti DOM agar file input tetap ada */
function onSlideFileChange(input) {
  const file = input.files[0];
  if (!file) return;
  const item = input.closest('.slide-item');
  const reader = new FileReader();
  reader.onload = e => {
    const thumb       = item.querySelector('.slide-thumb');
    const placeholder = item.querySelector('.slide-placeholder');
    const lbl         = item.querySelector('.slide-upload-lbl');
    const wrap        = item.querySelector('.slide-thumb-wrap');
    if (thumb) { thumb.src = e.target.result; thumb.classList.remove('hidden'); }
    if (placeholder) placeholder.classList.add('hidden');
    if (lbl) lbl.textContent = 'Ganti';
    if (wrap) { wrap.classList.remove('border-dashed','border-slate-300','dark:border-slate-600','bg-slate-100','dark:bg-slate-800'); }
  };
  reader.readAsDataURL(file);
}

/* ── Foto background (header biasa) ── */
function previewBgFoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img     = document.getElementById('bgFotoPreviewImg');
    const ph      = document.getElementById('bgFotoPh');
    const overlay = document.getElementById('bgFotoOverlay');
    const txtOver = document.getElementById('bgFotoTextOverlay');
    const lbl     = document.getElementById('bgFotoUploadLbl');
    if (img)     { img.src = e.target.result; img.style.display = 'block'; }
    if (ph)      ph.style.display = 'none';
    if (overlay) overlay.style.display = 'block';
    if (txtOver) txtOver.style.display = 'block';
    if (lbl)     lbl.textContent = 'Ganti Foto';
    document.getElementById('bgFotoHapusBtn').style.display = '';
    document.getElementById('h_bg_foto_hapus').value = '0';
  };
  reader.readAsDataURL(file);
}

function hapusBgFoto() {
  const img     = document.getElementById('bgFotoPreviewImg');
  const ph      = document.getElementById('bgFotoPh');
  const overlay = document.getElementById('bgFotoOverlay');
  const txtOver = document.getElementById('bgFotoTextOverlay');
  const lbl     = document.getElementById('bgFotoUploadLbl');
  if (img)     { img.src = ''; img.style.display = 'none'; }
  if (ph)      ph.style.display = 'flex';
  if (overlay) overlay.style.display = 'none';
  if (txtOver) txtOver.style.display = 'none';
  if (lbl)     lbl.textContent = 'Upload Foto';
  document.getElementById('h_bg_foto_hapus').value = '1';
  document.getElementById('bgFotoHapusBtn').style.display = 'none';
  document.getElementById('h_bg_foto_input').value = '';
}

/* ── Save header ── */
async function saveHeader() {
  const tipe       = _hTipe;
  const judul      = document.getElementById('h_judul')?.value.trim()      || '';
  const subjudul   = document.getElementById('h_subjudul')?.value.trim()   || '';
  const badge          = document.getElementById('h_badge')?.value              ?? '';
  const btnLayanan     = document.getElementById('h_btn_layanan')?.value.trim()  || '';
  const btnLayananUrl  = document.getElementById('h_btn_layanan_url')?.value.trim() || '';
  const btnProfil      = document.getElementById('h_btn_profil')?.value.trim()   || '';
  const btnProfilUrl   = document.getElementById('h_btn_profil_url')?.value.trim()  || '';
  const videoUrl   = _videoMode === 'url' ? (document.getElementById('h_video_url')?.value.trim() || '') : '';

  const fd = new FormData();
  fd.append('tipe',           tipe);
  fd.append('judul',          judul);
  fd.append('subjudul',       subjudul);
  fd.append('badge',           badge);
  fd.append('btn_layanan',     btnLayanan);
  fd.append('btn_layanan_url', btnLayananUrl);
  fd.append('btn_profil',      btnProfil);
  fd.append('btn_profil_url',  btnProfilUrl);
  fd.append('video_url',      videoUrl);
  fd.append('video_mode',     _videoMode);
  fd.append('hapus_bg_foto',  document.getElementById('h_bg_foto_hapus')?.value  || '0');
  fd.append('hapus_bg_video', document.getElementById('h_bg_video_hapus')?.value || '0');

  // Foto background (tipe biasa)
  const bgInput = document.getElementById('h_bg_foto_input');
  if (bgInput?.files[0]) fd.append('bg_foto', bgInput.files[0]);

  // Video upload (tipe video, mode upload)
  const bgVideoInput = document.getElementById('h_bg_video_input');
  if (bgVideoInput?.files[0]) fd.append('bg_video', bgVideoInput.files[0]);

  // Slides — baca file langsung dari input, tidak simpan terpisah
  const slidesMeta = [];
  document.querySelectorAll('#slides-list .slide-item').forEach((item, i) => {
    const path      = item.dataset.path || null;
    const fileInput = item.querySelector('.slide-file-input');
    const hasFile   = fileInput && fileInput.files.length > 0;
    slidesMeta.push({
      path:     hasFile ? null : path,
      judul:    item.querySelector('.slide-judul')?.value.trim()    || '',
      subjudul: item.querySelector('.slide-subjudul')?.value.trim() || '',
    });
    if (hasFile) fd.append(`slide_photo[${i}]`, fileInput.files[0]);
  });
  fd.append('slides_json', JSON.stringify(slidesMeta));

  try {
    const res  = await fetch('{{ route("admin.settings.header") }}', {
      method:  'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        'Accept':       'application/json',
      },
      body: fd,
    });
    const json = await res.json();
    if (!res.ok) { toast(json.message || 'Gagal menyimpan', 'error'); return; }
    toast(json.message, 'success');
    showHint('headerHint');
  } catch(e) {
    toast('Gagal terhubung ke server', 'error');
  }
}

/* ── Navbar modal ── */
const NAV_STORE_URL  = '{{ route("admin.navbar.store") }}';
const NAV_UPDATE_BASE = '{{ url("admin/pengaturan/navbar") }}/';

function openNavModal(data = null) {
  const form = document.getElementById('navForm');
  form.reset();
  document.getElementById('navMethodField').innerHTML = '';
  document.querySelector('input[name="nf_jenis"][value="utama"]').checked = true;
  onJenisChange('utama');
  onTipeChange('custom');

  if (data) {
    document.getElementById('navModalTitle').textContent = 'Edit Item Menu';
    document.getElementById('nf_label').value   = data.label ?? '';
    document.getElementById('nf_urutan').value  = data.urutan ?? 0;
    document.getElementById('nf_aktif').checked = !!data.aktif;
    document.getElementById('nf_target').value  = data.target ?? '_self';

    const jenis = data.parent_id ? 'sub' : 'utama';
    document.querySelector(`input[name="nf_jenis"][value="${jenis}"]`).checked = true;
    onJenisChange(jenis);
    if (jenis === 'sub') {
      document.getElementById('nf_parent_id').value = data.parent_id ?? '';
    }

    const tipe = data.tipe_link ?? 'custom';
    document.querySelector(`input[name="tipe_link"][value="${tipe}"]`).checked = true;
    onTipeChange(tipe);
    if (tipe === 'custom') document.getElementById('nf_link_url').value = data.link_url ?? '';
    if (tipe === 'page')   document.getElementById('nf_page_id').value  = data.page_id  ?? '';
    if (tipe === 'arsip') {
      const af = data.arsip_filter ?? 'semua';
      document.querySelector(`input[name="arsip_filter"][value="${af}"]`).checked = true;
      onArsipFilterChange(af);
      if (af === 'kategori') {
        document.getElementById('nf_kategori_arsip_id').value = data.kategori_arsip_id ?? '';
      }
    }

    document.getElementById('navMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    form.action = NAV_UPDATE_BASE + data.id;
    document.getElementById('nf_btn_label').textContent = 'Simpan Perubahan';
  } else {
    document.getElementById('navModalTitle').textContent  = 'Tambah Item Menu';
    form.action = NAV_STORE_URL;
    document.getElementById('nf_btn_label').textContent = 'Tambah Menu';
  }

  document.getElementById('navModal').style.display = 'flex';
}

function closeNavModal() {
  document.getElementById('navModal').style.display = 'none';
}

function onJenisChange(val) {
  const wrap = document.getElementById('nf_parent_wrap');
  wrap.style.display = val === 'sub' ? 'block' : 'none';
  if (val === 'utama') {
    document.getElementById('nf_parent_id').value = '';
  }
}

const TIPE_ON  = ['border-brand-500', 'bg-brand-50', 'dark:bg-brand-500/10', 'text-brand-700', 'dark:text-brand-100'];
const TIPE_OFF = ['border-slate-200', 'dark:border-slate-700'];

function onTipeChange(tipe) {
  ['custom', 'page', 'arsip'].forEach(t => {
    const el = document.getElementById('tipe_label_' + t);
    if (!el) return;
    if (t === tipe) {
      el.classList.remove(...TIPE_OFF);
      el.classList.add(...TIPE_ON);
    } else {
      el.classList.remove(...TIPE_ON);
      el.classList.add(...TIPE_OFF);
    }
  });

  document.getElementById('nf_custom_wrap').style.display = tipe === 'custom' ? 'block' : 'none';
  document.getElementById('nf_page_wrap').style.display   = tipe === 'page'   ? 'block' : 'none';
  document.getElementById('nf_arsip_wrap').style.display  = tipe === 'arsip'  ? 'block' : 'none';

  if (tipe !== 'arsip') {
    document.querySelector('input[name="arsip_filter"][value="semua"]').checked = true;
    onArsipFilterChange('semua');
  }
}

function onArsipFilterChange(val) {
  document.getElementById('nf_arsip_kat_wrap').style.display = val === 'kategori' ? 'block' : 'none';
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', function () {
  // Aktifkan tab pertama
  switchTab('tampilan');

  // Init navbar modal tipe styling
  onTipeChange('custom');

  // Tandai tema aktif saat ini
  highlightActivePreset();

  // Sync dark mode toggle
  const dkToggle = document.getElementById('darkToggle');
  if (dkToggle && document.documentElement.classList.contains('dark')) {
    dkToggle.classList.add('on');
  }

  // Live preview saat mengisi form desa
  ['d_nama','d_kecamatan','d_kabupaten','d_provinsi','d_email','d_whatsapp'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', updateDesaPreview);
  });

  // Sync teks header ke preview biasa foto
  function syncHeaderText() {
    const judul    = document.getElementById('h_judul')?.value.trim()    || 'Judul Header Homepage';
    const subjudul = document.getElementById('h_subjudul')?.value.trim() || 'Subjudul / deskripsi singkat desa';
    const box = document.getElementById('bgFotoTextOverlay');
    if (!box) return;
    const lines = box.querySelectorAll('div');
    if (lines[1]) lines[1].textContent = judul;
    if (lines[2]) lines[2].textContent = subjudul;
  }
  document.getElementById('h_judul')?.addEventListener('input', syncHeaderText);
  document.getElementById('h_subjudul')?.addEventListener('input', syncHeaderText);
});
</script>
@endsection
