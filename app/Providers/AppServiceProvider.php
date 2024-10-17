<?php

namespace App\Providers;

use App\Models\Modul;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = auth()->user(); // Mengambil roles
            $roles = User::with('role.modul')->where('id', $user->id)->first();
            // dd($roles);
            // $menus = $roles->role->modul;

            $view->with('user', $user);
        });
    }
}
