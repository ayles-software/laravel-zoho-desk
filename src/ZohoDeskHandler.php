<?php

namespace AylesSoftware\ZohoDesk;

class ZohoDeskHandler
{
    public function __call($name, $arguments)
    {
        $instance = new ZohoDesk(app(ZohoOAuth::class)->credentials);

        return $instance->{$name}(...$arguments);
    }
}
