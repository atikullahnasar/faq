Step 1: composer require atikullahnasar/faq:dev-main
Step 2: php artisan vendor:publish --provider="atikullahnasar\faq\Provider\FaqPackageServiceProvider" --tag=faq-migrations
Step 3: php artisan migrate
