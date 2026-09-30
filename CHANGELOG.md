# Changelog

All notable changes to `stack-reporter-laravel` will be documented in this file.

## [Unreleased]

### Added
- Rate limit of 60 requests per minute on the `/api/v1/stack-reporter` endpoint
- Test suite covering the endpoint, rate limiting, and the `about` command
- `.gitignore`

### Changed
- A request without an `apikey` now returns `401 Missing API key.` (was `500`)
- An unconfigured API key now returns `500 StackReporter API key is not configured.`
- An invalid API key now returns `403 Invalid API key given.`
- API keys are compared with `hash_equals()`
- `php artisan about` now reports the installed package version instead of a hard-coded `1.0.0`
- `laravel/framework` moved from `require-dev` to `require`
- Updated `orchestra/testbench` to `^9.0|^10.0`
- CI now tests PHP 8.2–8.4 against Laravel 11 and 12
- README updated to match the code, including the `STACKREPORTER_API_KEY` env variable

### Removed
- `StackReporter` facade and alias (it pointed to a binding that was never registered)
- Unused `guzzlehttp/guzzle` dependency
- Unused `InstalledVersions` constructor argument on `StackReporterSyncController`

### Fixed
- Endpoint returned a 500 (`Undefined variable $node_version`) when Node isn't installed, e.g. on AWS Lambda; `node_version` is now `null`

## [2.0.0] - 2026-06-16

### Changed
- Added Laravel 12 support; now supports Laravel 11 and 12
- Bumped minimum PHP requirement to `^8.2`
- Updated `orchestra/testbench` to `^8.0|^9.0`
- Updated `pestphp/pest` to `^3.0` and `pestphp/pest-plugin-laravel` to `^3.0`

### Removed
- Removed `AuthorizesRequests` and `ValidatesRequests` traits from `StackReporterSyncController` (removed in Laravel 12)
- Removed deprecated `namespace` option from route group in `StackReporterServiceProvider`

## [1.0.2] - 2025-06-20

### Changed
- Renamed config key `api_site_key` to `api_key` and env variable `STACKREPORTER_API_SITE_KEY` to `STACKREPORTER_API_KEY`

## [1.0.1] - 2025-06-20

### Changed
- Composer metadata and dependency updates

## [1.0.0] - 2025-06-19

### Added
- Initial release of Stack Reporter Laravel package
- System information endpoints
- Composer package details reporting
- Laravel integration with service provider
- Configuration management
