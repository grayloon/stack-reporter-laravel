<?php

namespace GrayLoon\StackReporter\Http\Controllers;

use Composer\InstalledVersions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class StackReporterSyncController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * @param  InstalledVersions|null  $composer
     */
    public function __construct(
        public ?InstalledVersions $composer = null
    ) {
        $this->composer = $composer ?? new InstalledVersions();
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request): mixed
    {
        if (
            config('grayloon_stack_reporter.api_key')
            && $request->get('apikey')
        ) {
            if (config('grayloon_stack_reporter.api_key') === $request->get('apikey')) {
                if (function_exists('exec')) {
                    exec('node -v 2>/dev/null', $output, $return_var);
                    if ($return_var === 0 && !empty($output[0])) {
                        $node_version = trim(str_replace('v', '', end($output)));
                    }
                }

                return response()->json([
                    'laravel_version' => app()->version(),
                    'php_version'      => phpversion(),
                    'node_version' => $node_version,
                ]);
            }

            return response('Invalid API Site Key given.', status: 403);
        }

        return response('Application not in production or missing API Site Key.', status: 500);
    }
}
