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
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'faq');

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
