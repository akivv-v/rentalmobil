<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            // Cek apakah user sedang login DAN tabel messages sudah ada di database
            if (Auth::check() && Schema::hasTable('messages')) {
                $unreadGlobal = Message::where('is_read', false)
                    ->where('sender', 'user')
                    ->count();
                $view->with('unreadGlobal', $unreadGlobal);
            } else {
                $view->with('unreadGlobal', 0);
            }
        });
    }
}
