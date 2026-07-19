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
        $this->app->bind(
            \App\Services\Catalogo\Interfaces\IPlataformaService::class,
            \App\Services\Catalogo\PlataformaService::class,
        );
        $this->app->bind(
            \App\Repositorios\Catalogo\Interfaces\IPlataformaRepositorio::class,
            \App\Repositorios\Catalogo\PlataformaRepositorio::class,
        );
        $this->app->bind(
            \App\Repositorios\Catalogo\Interfaces\IDesenvolvedoraRepositorio::class,
            \App\Repositorios\Catalogo\DesenvolvedoraRepositorio::class
        );
        $this->app->bind(
            \App\Services\Catalogo\Interfaces\IDesenvolvedoraService::class,
            \App\Services\Catalogo\DesenvolvedoraService::class
        );
        $this->app->bind(
            \App\Services\Catalogo\Interfaces\IGeneroService::class,
            \App\Services\Catalogo\GeneroService::class
        );
        $this->app->bind(
            \App\Repositorios\Catalogo\Interfaces\IGeneroRepositorio::class,
            \App\Repositorios\Catalogo\GeneroRepositorio::class
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
