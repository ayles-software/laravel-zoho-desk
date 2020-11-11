<?php

namespace AylesSoftware\ZohoDesk\Facades;

use Illuminate\Support\Facades\Facade;

class ZohoDesk extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ZohoDesk';
    }
}
