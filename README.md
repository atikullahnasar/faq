

Of course! Here is a well-structured and professional README file for your Laravel FAQ package.

***

# Laravel FAQ Package

A lightweight and easy-to-use Laravel package for managing Frequently Asked Questions (FAQs) with built-in migrations and routes.

## Features

-   Manage FAQs through a simple admin interface.
-   Built-in database migrations for a quick setup.
-   Pre-configured routes for the admin panel.
-   Customizable views via a published config file.

## Requirements

-   PHP 8.0 or higher
-   Laravel 9.0 or higher
-   An existing authentication system in your Laravel project.

## Installation

This package is not yet published on Packagist, so you'll need to add the repository to your project's `composer.json` file first.

### Step 0: Add the Repository to Composer

Add the following repository to your main project's `composer.json` file:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/atikullahnasar/faq"
    }
]
```

Save the file after adding this entry.

### Step 1: Install the Package

After adding the repository, you can install the package using Composer:

```bash
composer require atikullahnasar/faq:dev-main
```

### Step 2: Publish Assets

You need to publish the migrations and the configuration file.

**Publish the Migrations:**

```bash
php artisan vendor:publish --provider="atikullahnasar\faq\Provider\FaqPackageServiceProvider" --tag=faq-migrations
```

**Publish the Configuration File:**

This will create a `config/faq.php` file, allowing you to customize the views.

```bash
php artisan vendor:publish --tag=faq-config
```

### Step 3: Run the Migrations

Finally, run the database migrations to create the necessary tables for the FAQs:

```bash
php artisan migrate
```

## Usage

After completing the installation, you can access the FAQ management panel from the following URL:

```
/beft/faqs
```

**Example:**

If your application is running at `http://example.com`, you can access the FAQs page at:

```
http://example.com/beft/faqs
```

## Configuration

After publishing the configuration file, you can find it at `config/faq.php`. You can modify this file to suit your application's needs, such as changing the views or middleware.

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue on the [GitHub repository](https://github.com/atikullahnasar/faq).

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
