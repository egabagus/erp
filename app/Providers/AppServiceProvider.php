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
            $id = auth()->user()->id; // Mengambil roles
            $roles = User::with('role.modul')->where('id', $id)->first();
            dd($roles);
            $menus = Modul::whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('roles.id', $roles->pluck('id'));
            })->get();

            $view->with('menus', $menus);
        });
    }
}
