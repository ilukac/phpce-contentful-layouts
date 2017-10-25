<?php

namespace AppBundle\RoutingEnhancer;

use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContentfulEnhancer implements RouteEnhancerInterface
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

    public function enhance(array $defaults, Request $request)
    {
        $defaults["_content"] = $this->contentful->loadContentfulEntry($defaults["_route"]);

        return $defaults;
    }
}
