<?php

namespace Winponta\ETL;

use Illuminate\Contracts\Foundation\Application;

/**
 * Etl class
 */
class Etl
{
    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Class constructor.
     *
     * @param Application          $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


}
