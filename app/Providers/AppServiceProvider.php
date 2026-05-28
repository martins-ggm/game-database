<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositorios\Gerenciador\Interfaces\IUsuarioRepositorio::class, \App\Repositorios\Gerenciador\UsuarioRepositorio::class);
        $this->app->bind(
            \App\Services\Gerenciador\Interfaces\IUsuarioService::class,
            \App\Services\Gerenciador\UsuarioService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
