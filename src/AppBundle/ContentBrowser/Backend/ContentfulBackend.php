<?php

namespace AppBundle\ContentBrowser\Backend;

use AppBundle\Entity\ContentfulEntry;
use Netgen\ContentBrowser\Backend\BackendInterface;
use Netgen\ContentBrowser\Item\LocationInterface;
use Contentful\Delivery\Client;
use AppBundle\ContentBrowser\Item\Entry\Item;
use AppBundle\ContentBrowser\Item\Client\Location;
use AppBundle\ContentBrowser\Item\Client\RootLocation;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ContentfulBackend implements BackendInterface
{
    /**
     * @var \AppBundle\Service\Contentful
     */
    private $contentful;

    public function __construct(
        \AppBundle\Service\Contentful $contentful
    ) {
        $this->contentful = $contentful;
    }

    public function getDefaultSections()
    {
        return array(new RootLocation());
    }

    public function loadLocation($id)
    {
        if ($id === "0") {
            return new RootLocation();
        }

        /**
         * @var \Contentful\Delivery\Client $client_service
         */
        $client_service = $this->contentful->getClientByName($id);
        $space = $this->contentful->getSpaceByClientName($id);
        return new Location($client_service, $space);
    }

    /**
     * Loads contentful entry
     *
     * @param string $id
     *
     * @return \AppBundle\ContentBrowser\Item\Entry\Item
     */
    public function loadItem($id)
    {
        /**
         * @var \AppBundle\Entity\ContentfulEntry $contentfulEntry
         */
        $contentfulEntry = $this->contentful->loadContentfulEntry($id);

        return $this->buildItem($contentfulEntry);
    }

    public function getSubLocations(LocationInterface $location)
    {
        if (!$location instanceof RootLocation) {
            return array();
        }

        return $this->buildLocations(
            $this->contentful->getClients()
        );
    }

    public function getSubLocationsCount(LocationInterface $location)
    {
        if (!$location instanceof RootLocation) {
            return 0;
        }

        return count($this->contentful->getClients());
    }

    /**
     * gets contentful entries
     *
     * @return \AppBundle\ContentBrowser\Item\Entry\Item[]
     */
    public function getSubItems(LocationInterface $location, $offset = 0, $limit = 25)
    {
        if ($location instanceof RootLocation) {
            $contentfulEntries = $this->contentful->getContentfulEntries($offset, $limit);
        } else {
            $contentfulEntries = $this->contentful->getContentfulEntries($offset, $limit, $location->getClient());
        }

        return $this->buildItems(
            $contentfulEntries
        );
    }

    public function getSubItemsCount(LocationInterface $location)
    {
        if ($location instanceof RootLocation) {
            return array();
        }

        return $this->contentful->getContentfulEntriesCount($location->getClient());
    }

    public function search($searchText, $offset = 0, $limit = 25)
    {
        return $this->buildItems(
            $this->contentful->searchContentfulEntries($searchText, $offset, $limit)
        );
    }

    public function searchCount($searchText)
    {
        return $this->contentful->searchContentfulEntriesCount($searchText);
    }

    /**
     * Builds the location from provided client.
     *
     * @param \Contentful\Delivery\Client $client
     *
     * @return \AppBundle\ContentBrowser\Item\Client\Location
     */
    private function buildLocation(Client $client, $id)
    {
        return new Location($client, $id);
    }

    /**
     * Builds the locations from provided clients.
     *
     * @param \Contentful\Delivery\Client[] $clients
     *
     * @return \AppBundle\ContentBrowser\Item\Client\Location[]
     */
    private function buildLocations($clients)
    {
        return array_map(
            function (Client $client, $id) {
                return $this->buildLocation($client, $id);
            },
            $clients,
            $this->contentful->getClientsNames()
        );
    }

    /**
     * Builds the item from provided client.
     *
     * @param \AppBundle\Entity\ContentfulEntry $entry
     *
     * @return \AppBundle\ContentBrowser\Item\Entry\Item
     */
    private function buildItem(ContentfulEntry $entry)
    {
        return new Item($entry);
    }

    /**
     * Builds the locations from provided clients.
     *
     * @param \AppBundle\Entity\ContentfulEntry[] $entries
     *
     * @return \AppBundle\ContentBrowser\Item\Entry\Item[]
     */
    private function buildItems($entries)
    {
        return array_map(
            function (ContentfulEntry $entry) {
                return $this->buildItem($entry);
            },
            $entries
        );
    }
}
