<?php

uses()->group('controller', 'sync');

use Illuminate\Support\Facades\Config;
use function Pest\Laravel\{post};

$key = 'abcdefghijklmnopqrstuvwxyz1234567890';

/**
 * Run the callback with PATH pointing at a directory that contains only a fake
 * `node` script printing the given output, or no `node` at all when null.
 */
function withFakeNode(?string $output, Closure $callback): void
{
    $dir = sys_get_temp_dir() . '/stack-reporter-' . uniqid();
    mkdir($dir);

    if ($output !== null) {
        file_put_contents("{$dir}/node", "#!/bin/sh\necho '{$output}'\n");
        chmod("{$dir}/node", 0755);
    }

    $path = getenv('PATH');
    putenv("PATH={$dir}");

    try {
        $callback();
    } finally {
        putenv("PATH={$path}");
        @unlink("{$dir}/node");
        rmdir($dir);
    }
}

beforeEach(function () use ($key) {
    Config::set('grayloon_stack_reporter.api_key', $key);
});

it('fails when no API key is configured', function (?string $configured) use ($key) {
    Config::set('grayloon_stack_reporter.api_key', $configured);

    post(route('stackreporter'), ['apikey' => $key])
        ->assertStatus(500)
        ->assertContent('StackReporter API key is not configured.');
})->with([null, '']);

it('fails when no API key is given in the request', function () {
    post(route('stackreporter'))
        ->assertStatus(401)
        ->assertContent('Missing API key.');
});

it('fails when the API key in the request is not a string', function () use ($key) {
    post(route('stackreporter'), ['apikey' => [$key]])
        ->assertStatus(401)
        ->assertContent('Missing API key.');
});

it('fails when API keys do not match', function () {
    post(route('stackreporter'), ['apikey' => '1234567890'])
        ->assertStatus(403)
        ->assertContent('Invalid API key given.');
});

it('returns stack versions', function () use ($key) {
    withFakeNode('v22.14.0', function () use ($key) {
        post(route('stackreporter'), ['apikey' => $key])
            ->assertOk()
            ->assertExactJson([
                'laravel_version' => app()->version(),
                'php_version' => phpversion(),
                'node_version' => '22.14.0',
            ]);
    });
});

it('returns a null node version when node is not installed', function () use ($key) {
    withFakeNode(null, function () use ($key) {
        post(route('stackreporter'), ['apikey' => $key])
            ->assertOk()
            ->assertJsonPath('node_version', null);
    });
});

it('rate limits requests', function () {
    for ($i = 0; $i < 60; $i++) {
        post(route('stackreporter'), ['apikey' => 'wrong'])->assertStatus(403);
    }

    post(route('stackreporter'), ['apikey' => 'wrong'])->assertStatus(429);
});

it('adds the package version to the about command', function () {
    $this->artisan('about', ['--only' => 'stackreporter'])
        ->expectsOutputToContain('StackReporter')
        ->assertSuccessful();
});
