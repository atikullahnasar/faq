FAQ Package:

A lightweight and easy-to-use Laravel package for managing Frequently Asked Questions (FAQs) with built-in migrations and routes.
must need to have authentication.
must need to be add on composer.json on your main project "repositories": [{"type": "vcs", "url": "https://github.com/atikullahnasar/faq" }],

Installation & Setup

Follow the steps below to install and configure the package in your Laravel project:

Step 1: Install the Package
composer require atikullahnasar/faq:dev-main

Step 2: Publish the Migrations
php artisan vendor:publish --provider="atikullahnasar\faq\Provider\FaqPackageServiceProvider" --tag=faq-migrations

Step 3: Run the Migrations
php artisan migrate

Step 4 (Final): Access the FAQs Page

After installation, go to:

/beft/faqs

Example:

http://example.com/beft/faqs
