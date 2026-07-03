<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'statUsers'       => User::count(),
            'statRoles'       => Role::count(),
            'statPermissions' => Permission::count(),
            'settingsDesa'    => Setting::forGroup('desa.'),
            'settingsApi'     => Setting::forGroup('api.'),
            'apiKeyExists'    => Setting::where('key', 'api.key')->exists(),
        ]);
    }
}
