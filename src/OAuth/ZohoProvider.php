<?php

namespace AylesSoftware\ZohoDesk\OAuth;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class ZohoProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function accountsServer()
    {
        return config('zoho-desk.accounts_server');
    }

    public function hostResourceLocation()
    {
        return config('zoho-desk.base_url');
    }

    /**
     * Get authorization url to begin OAuth flow.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->accountsServer().'/oauth/v2/auth';
    }

    /**
     * Get access token url to retrieve token.
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->accountsServer().'/oauth/v2/token';
    }

    /**
     * Get provider url to fetch organization details.
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->hostResourceLocation().'/api/v1/organizations';
    }

    protected function getAuthorizationParameters(array $options)
    {
        return array_merge(
            parent::getAuthorizationParameters($options),
            array_filter([
                'access_type' => config('zoho-desk.access_type'),
            ])
        );
    }

    /**
     * Get the default scopes used by this provider.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return ZohoDeskResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ZohoDeskResourceOwner($response);
    }
}
