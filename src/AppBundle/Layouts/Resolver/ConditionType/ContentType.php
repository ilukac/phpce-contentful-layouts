<?php

namespace AppBundle\Layouts\Resolver\ConditionType;

use Exception;
use Netgen\BlockManager\Layout\Resolver\ConditionTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class ContentType implements ConditionTypeInterface
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

    public function getType()
    {
        return 'contentful_content_type';
    }

    public function getConstraints()
    {
        return array(
            new Constraints\NotBlank(),
            new Constraints\Type(array('type' => 'array'))
        );
    }

    public function matches(Request $request, $value)
    {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        $content_id = $request->attributes->get("_content_id");
        if ($content_id == null)
            return false;

        $cid_array = explode(":", $content_id);
        if (count($cid_array) != 2)
            return false;

        if ($cid_array[0] != "AppBundle\Entity\ContentfulEntry")
            return false;

        $contentfulEntry = $this->contentful->loadContentfulEntry($cid_array[1]);
        if ($contentfulEntry == null)
            return false;

        return in_array($contentfulEntry->getContentType()->getId(), $value, true);
    }
}
