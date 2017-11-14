<?php

namespace AppBundle\ContentBrowser\Item\Client;

use Netgen\ContentBrowser\Item\LocationInterface;

final class RootLocation implements LocationInterface
{
    public function getLocationId()
    {
        return 0;
    }

    public function getName()
    {
        return 'Content';
    }

    public function getParentId()
    {
        return null;
    }

    public function getClient()
    {
        return null;
    }
}
