<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // 👈 1. ضيف هذا السطر هنا
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate; // <--- هذا هو السطر الذي كان ناقصاً
use App\Models\User; // <--- وتأكد من وجود هذا السطر أيضاً

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
        Schema::defaultStringLength(191);
        // تعريف بوابة أمنية باسم 'is-admin'
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
