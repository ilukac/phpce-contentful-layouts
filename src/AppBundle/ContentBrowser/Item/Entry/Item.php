<?php

namespace AppBundle\ContentBrowser\Item\Entry;

use AppBundle\Entity\ContentfulEntry;
use Netgen\ContentBrowser\Item\ItemInterface;

class Item implements ItemInterface
{
    private $entry;
    
    public function __construct(ContentfulEntry $entry)
    {
        $this->entry = $entry;
    }

    public function getValue()
    {
        return $this->entry->getId();
    }

    public function getName()
    {
        return $this->getEntry()->getName();
    }

    public function isVisible()
    {
        return true;
    }

    public function isSelectable()
    {
        return true;
    }

    public function getEntry()
    {
        return $this->entry;
    }
}
