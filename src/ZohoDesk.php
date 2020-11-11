<?php

namespace AylesSoftware\ZohoDesk;

use Illuminate\Support\Facades\Http;
use AylesSoftware\ZohoDesk\Entities\ZohoDeskAccess;

class ZohoDesk
{
    protected $access;

    protected $client;

    public function __construct(ZohoDeskAccess $access)
    {
        $this->access = $access;
        $this->client = Http::withHeaders([
            'orgId' => config('zoho-desk.organisation_id'),
            'Authorization' => 'Zoho-oauthtoken '.$this->access->token,
        ]);
    }

    public function createTicket($payload)
    {
        return $this->client->post(
            config('zoho-desk.base_url').'/api/v1/tickets',
            $payload
        )->json();
    }
}
