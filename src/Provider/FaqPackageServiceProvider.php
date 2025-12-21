<?php

namespace atikullahnasar\faq\Provider;

use atikullahnasar\faq\Repositories\Faqs\FaqRepository;
use atikullahnasar\faq\Services\Faqs\FaqService;
use atikullahnasar\faq\Services\Faqs\FaqServiceInterface;
use atikullahnasar\faq\Repositories\Faqs\FaqRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class FaqPackageServiceProvider extends ServiceProvider
{

    public function boot()
    {

        // Publish config
        $this->publishes([
            __DIR__ . '/../Config/faq.php' => config_path('faq.php'),
        ], 'faq-config');

        // Load views based on config
        $layout = config('faq.layout', 'tailwind');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views/' . $layout, 'faq');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Publish migrations
        $this->publishes([__DIR__ . '/../Database/migrations' => database_path('migrations'),
        ], 'faq-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }

    public function register()
    {
        $this->app->bind( FaqServiceInterface::class, FaqService::class );
        $this->app->bind( FaqRepositoryInterface::class, FaqRepository::class );
    }
}
