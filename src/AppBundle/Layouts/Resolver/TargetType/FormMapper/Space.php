<?php

namespace AppBundle\Layouts\Resolver\TargetType\FormMapper;

use Netgen\BlockManager\Layout\Resolver\Form\TargetType\Mapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Space extends Mapper
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

    /**
     * Returns the form type that will be used to edit the value of this condition type.
     *
     * @return string
     */
    public function getFormType()
    {
        return ChoiceType::class;
    }

    public function getFormOptions()
    {
        return array(
            'choices' => $this->contentful->getSpacesAsChoices()
        );
    }


}
