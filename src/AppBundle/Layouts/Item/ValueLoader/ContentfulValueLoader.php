<?php

namespace AppBundle\Layouts\Item\ValueLoader;

use Netgen\BlockManager\Item\ValueLoaderInterface;

final class ContentfulValueLoader implements ValueLoaderInterface
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

    public function load($id)
    {
        return $this->contentful->loadContentfulEntry($id);
    }
}
