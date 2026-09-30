# Stack Reporter Package for Laravel

A simple Laravel package that provides an endpoint reporting your application's Laravel, PHP, and Node versions to StackReporter.

## Requirements

- PHP 8.2 – 8.4
- Laravel 11 or 12

## Installation

Install the package with Composer:

```bash
composer require grayloon/stack-reporter-laravel
```

The service provider is registered automatically through Laravel package discovery.

## Configuration

Add your API key to your `.env` file:

```env
STACKREPORTER_API_KEY=your-secret-api-key-here
```

Optionally, publish the configuration file:

```bash
php artisan vendor:publish --provider="GrayLoon\StackReporter\StackReporterServiceProvider" --tag="config"
```

This creates `config/grayloon_stack_reporter.php`:

```php
<?php

return [
    'api_key' => env('STACKREPORTER_API_KEY'),
];
```

## Usage

The package registers a single endpoint that accepts POST requests containing your API key.

### Endpoint

- **POST** `/api/v1/stack-reporter`
  - **Parameter**: `apikey` - Your configured API key
  - **Returns**: JSON with the application's stack versions
  - **Rate limit**: 60 requests per minute

### Making a Request

```bash
curl -X POST https://your-app.com/api/v1/stack-reporter \
  -H "Content-Type: application/json" \
  -d '{"apikey": "your-secret-api-key-here"}'
```

### Example Response

```json
{
  "laravel_version": "12.0.0",
  "php_version": "8.4.0",
  "node_version": "22.14.0"
}
```

`node_version` is `null` when Node isn't installed or can't be run from PHP, such as on AWS Lambda or when `exec()` is disabled.

## Responses

| Status | Body | When |
| --- | --- | --- |
| 200 | JSON (above) | The API key matches |
| 401 | `Missing API key.` | The request has no `apikey` |
| 403 | `Invalid API key given.` | The `apikey` doesn't match |
| 429 | Too Many Requests | More than 60 requests in a minute |
| 500 | `StackReporter API key is not configured.` | `STACKREPORTER_API_KEY` isn't set |

## About Command

The installed package version appears in `php artisan about` under **StackReporter**.

## Testing

```bash
composer install
vendor/bin/pest
```

## License

This package is available under the MIT License.
