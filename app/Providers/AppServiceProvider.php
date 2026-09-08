<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        Paginator::useBootstrap();

        if ($this->app->environment('local')) {
            Blade::directive('loremword', fn () => "<?php echo e(fake('es_ES')->word()); ?>");
            Blade::directive('loremwords', fn ($n) => "<?php echo e(fake('es_ES')->words({$n} ?: 3, true)); ?>");
            Blade::directive('loremsentence', fn () => "<?php echo e(fake('es_ES')->sentence()); ?>");
            Blade::directive('loremipsum', fn ($n) => "<?php echo nl2br(e(fake('es_ES')->paragraphs({$n} ?: 1, true))); ?>");
        }
    }
}
