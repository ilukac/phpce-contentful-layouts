<?php

namespace AppBundle\ContentBrowser\Item\Client;

use Netgen\ContentBrowser\Item\LocationInterface;
use Contentful\Delivery\Client;

final class Location implements LocationInterface
{
    /**
     * @var \Contentful\Delivery\Client
     */
    private $client;

    private $id;

    public function __construct(Client $client, $id)
    {
        $this->client = $client;
        $this->id = $id;
    }

    public function getLocationId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->client->getSpace()->getName();
    }

    public function getParentId()
    {
        return null;
    }

    public function getClient()
    {
        return $this->client;
    }
}
