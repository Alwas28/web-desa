<?php

namespace App\Providers;

use App\Models\NavItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        try {
            $desaNama = Setting::get('desa.nama') ?: 'Panel Admin';
            $logoPath = Setting::get('desa.logo');
            $logoUrl  = $logoPath ? Storage::url($logoPath) : null;
        } catch (\Exception) {
            $desaNama = 'Panel Admin';
            $logoUrl  = null;
        }

        View::share('desaNama', $desaNama);
        View::share('logoUrl', $logoUrl);

        // Inject nav items + desa info ke semua halaman publik
        View::composer('layouts.public', function ($view) {
            try {
                $pubNavItems = NavItem::whereNull('parent_id')
                    ->where('aktif', true)
                    ->orderBy('urutan')
                    ->with([
                        'children' => fn($q) => $q->where('aktif', true)->orderBy('urutan')
                            ->with(['page', 'kategoriArsip']),
                        'page', 'kategoriArsip',
                    ])
                    ->get();
                $desaInfo    = Setting::forGroup('desa.');
                $desaLogoUrl = ($p = $desaInfo['desa.logo'] ?? null) ? Storage::url($p) : null;
            } catch (\Exception) {
                $pubNavItems = collect();
                $desaInfo    = [];
                $desaLogoUrl = null;
            }
            $view->with(compact('pubNavItems', 'desaInfo', 'desaLogoUrl'));
        });
    }
}
