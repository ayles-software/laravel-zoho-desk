<?php

namespace AylesSoftware\ZohoDesk;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use AylesSoftware\ZohoDesk\OAuth\ZohoProvider;
use AylesSoftware\ZohoDesk\Entities\ZohoDeskAccess;

class ZohoOAuth
{
    public $request;

    public $token;

    public $credentials;

    protected $provider;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->credentials = ZohoDeskAccess::latest();

        $this->provider = new ZohoProvider([
            'clientId' => config('zoho-desk.client_id'),
            'clientSecret' => config('zoho-desk.client_secret'),
            'redirectUri' => config('zoho-desk.redirect_url'),
        ]);

        $this->refresh();
    }

    public function flow()
    {
        if ($this->request->has('code')) {
            $this->generate('authorization_code', ['code' => $this->request->input('code')]);

            return redirect(config('zoho-desk.redirect_after_authorization_url'));
        }

        return redirect(
            $this->provider->getAuthorizationUrl([
                'scope' => [config('zoho-desk.scopes')],
            ])
        );
    }

    public function refresh()
    {
        if ($this->credentials && $this->credentials->has_expired) {
            $this->generate('refresh_token', ['refresh_token' => $this->credentials->refresh_token]);
        }
    }

    protected function generate($type, $data)
    {
        $this->token = $this->provider->getAccessToken($type, $data);

        $this->saveCredentials();
    }

    protected function saveCredentials()
    {
        DB::transaction(function () {
            ZohoDeskAccess::setAllObsolete();

            $this->credentials = ZohoDeskAccess::create([
                'refresh_token' => $this->token->getRefreshToken() ?: ($this->credentials->refresh_token ?? null),
                'token' => $this->token->getToken(),
                'expires_at' => now()->addMinutes(59),
            ]);
        });
    }
}
