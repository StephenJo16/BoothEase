<?php

namespace App\Providers;

use App\Events\EventPublished;
use App\Listeners\NotifyTenantsAboutNewEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ViteHelper;

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
        // Register Vite helper directives
        Blade::directive('viteCss', function () {
            return "<?php echo \\App\\Helpers\\ViteHelper::cssAssets(); ?>";
        });

        Blade::directive('viteJs', function () {
            return "<?php echo \\App\\Helpers\\ViteHelper::jsAssets(); ?>";
        });
        // Register event listeners
        Event::listen(
            EventPublished::class,
            NotifyTenantsAboutNewEvent::class,
        );
    }
}
