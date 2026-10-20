<?php

namespace App\Providers;

use App\Models\Incomes\Income;
use App\Models\MaterialRequests\MaterialRequest;
use App\Models\MaterialRequests\MaterialRequestFulfillment;
use App\Models\MoneyRequests\MoneyRequest;
use App\Observers\Incomes\IncomeObserver;
use App\Observers\MaterialRequests\MaterialRequestObserver;
use App\Observers\MaterialRequests\MaterialRequestFulfillmentObserver;
use App\Observers\MoneyRequests\MoneyRequestObserver;
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
        MoneyRequest::observe(MoneyRequestObserver::class);
        MaterialRequest::observe(MaterialRequestObserver::class);
        Income::observe(IncomeObserver::class);
        MaterialRequestFulfillment::observe(MaterialRequestFulfillmentObserver::class);
    }
}
