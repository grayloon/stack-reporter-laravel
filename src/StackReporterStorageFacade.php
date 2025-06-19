<?php

namespace GrayLoon\StackReporter;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelBackboneStorageFacade.
 */
class StackReporterStorageFacade extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor(): string
	{
		return 'stackReporter';
	}
}
