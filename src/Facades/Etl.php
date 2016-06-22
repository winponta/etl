<?php

namespace Winponta\ETL\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Etl facade
 */
class Etl extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'etl';
    }
}
