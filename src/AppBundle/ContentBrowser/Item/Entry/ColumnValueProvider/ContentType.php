<?php

namespace AppBundle\ContentBrowser\Item\Entry\ColumnValueProvider;

use Netgen\ContentBrowser\Item\ColumnProvider\ColumnValueProviderInterface;
use Netgen\ContentBrowser\Item\ItemInterface;
use AppBundle\ContentBrowser\Item\Entry\Item;

final class ContentType implements ColumnValueProviderInterface
{
    public function getValue(ItemInterface $item)
    {
        if (!$item instanceof Item) {
            return null;
        }

        return $item->getEntry()->getContentType()->getName();
    }
}
