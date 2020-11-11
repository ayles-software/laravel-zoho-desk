<?php

namespace AylesSoftware\ZohoDesk\OAuth;

use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ZohoDeskResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response.
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'data.0.id');
    }

    /**
     * Get resource owner organization Name.
     *
     * @return string|null
     */
    public function getOrganizationName()
    {
        return $this->getValueByKey($this->response, 'data.0.companyName');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
