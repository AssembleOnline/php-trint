<?php

namespace Assemble\PHPTrint\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array list($limit = NULL, $skip = NULL)
 * @method static array get($trintId, $format = 'json', $params = [], $returnUrl = false)
 * @method static array put($filePath, $params)
 *
 * @see \Assemble\PHPTrint\Client
 */
class Trint extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'trint';
    }
}
