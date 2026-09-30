<?php

namespace GrayLoon\StackReporter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class StackReporterSyncController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $apiKey = config('grayloon_stack_reporter.api_key');

        if (! is_string($apiKey) || $apiKey === '') {
            return response('StackReporter API key is not configured.', status: 500);
        }

        $requestKey = $request->input('apikey');

        if (! is_string($requestKey) || $requestKey === '') {
            return response('Missing API key.', status: 401);
        }

        if (! hash_equals($apiKey, $requestKey)) {
            return response('Invalid API key given.', status: 403);
        }

        return response()->json([
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'node_version' => $this->nodeVersion(),
        ]);
    }

    /**
     * Get the installed Node version, or null when Node is unavailable (e.g. Lambda).
     */
    protected function nodeVersion(): ?string
    {
        if (! function_exists('exec')) {
            return null;
        }

        exec('node -v 2>/dev/null', $output, $exitCode);

        if ($exitCode !== 0 || empty($output[0])) {
            return null;
        }

        return ltrim(trim($output[0]), 'v');
    }
}
