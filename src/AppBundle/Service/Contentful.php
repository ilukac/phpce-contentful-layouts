<?php

namespace AppBundle\Service;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Contentful {

    /**
     * @var \Contentful\Delivery\Client
     */
    private $default_client;

    /**
     * @var array
     */
    private $clients_config;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        array $clients_config,
        ContainerInterface $container
    ) {
        $this->clients_config = $clients_config;
        $this->container = $container;
        $this->default_client = $this->container->get("contentful.delivery");

        if (count($this->clients_config) === 0) {
            throw new Contentful\Exception\ApiException(
                sprintf(
                    'No Contentful clients configured'
                )
            );
        }
    }

    public function getClientByName($name) {
        return $this->container->get($this->clients_config[$name]["service"]);
    }

    public function getSpaceByClientName($name) {
        return $this->clients_config[$name]["space"];
    }

    public function getClientBySpaceId($spaceId) {
        foreach ($this->clients_config as $clientName) {
            if ($clientName["space"] == $spaceId) {
                return $this->container->get($clientName["service"]);
            }
        }
        return null;
    }

    public function getClients() {
        /**
         * @var \Contentful\Delivery\Client[] $services
         */
        $clients = array();
        foreach ($this->clients_config as $clientName) {
            $client = $this->container->get($clientName["service"]);
            $clients[] = $client;
        }
        return $clients;
    }

    public function getContentType($id) {
        foreach ($this->clients_config as $clientName) {
            /**
             * @var \Contentful\Delivery\Client $service
             */
            $client = $this->container->get($clientName["service"]);
            foreach ($client->getContentTypes()->getItems() as $contentType)
                if ($contentType->getId() == $id)
                    return $contentType;
        }
        return null;
    }

    public function getClientsNames() {
        return array_keys($this->clients_config);
    }

    public function __toString() {
        return "Contentful service wrapper";
    }
}
