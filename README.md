# Stack Reporter Package for Laravel

A simple Laravel package that provides system information endpoints for monitoring your application stack.

## Installation

Install the package with Composer:

```bash
composer require grayloon/stack-reporter-laravel
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="GrayLoon\StackReporter\StackReporterServiceProvider" --tag="config"
```

This will create a `config/grayloon_stack_reporter.php` file with the following content:

```php
<?php

return [
    'api_key' => env('STACK_REPORTER_API_KEY'),
];
```

Add your API key to your `.env` file:

```env
STACK_REPORTER_API_KEY=your-secret-api-key-here
```

## Service Provider Registration

If you're using Laravel 5.5 or higher, the service provider will be automatically discovered. For older versions, manually register the service provider in `config/app.php`:

```php
// config/app.php
'providers' => [
    // Other Service Providers...
    GrayLoon\StackReporter\StackReporterServiceProvider::class,
],
```

## Usage

Once installed and configured, the package automatically registers an API endpoint that accepts POST requests with your configured API key.

### Endpoint

- **POST** `/api/v1/stack-reporter`
  - **Parameter**: `apikey` - Your configured API key
  - **Returns**: JSON response with system information

### Making a Request

```bash
curl -X POST http://your-app.com/api/v1/stack-reporter \
  -H "Content-Type: application/json" \
  -d '{"apikey": "your-secret-api-key-here"}'
```

### Response Data

The endpoint returns information about your application stack:
- Laravel version
- PHP version
- Node version (if available)

## Example Response

```json
{
  "laravel_version": "10.0.0",
  "php_version": "8.2.12",
  "node_version": "22.14.0"
}
```

## Security

- The endpoint requires a valid API key to access
- Returns a 403 error for invalid API keys
- Returns a 500 error if no API key is configured

## Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher

## License

This package is available under the MIT License.
