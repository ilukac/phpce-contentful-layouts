<?php

namespace AppBundle\Layouts\Resolver\TargetType;

use Exception;
use Netgen\BlockManager\Layout\Resolver\TargetTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class Entry implements TargetTypeInterface
{
    public function getType()
    {
        return 'contentful_entry';
    }

    public function getConstraints()
    {
        return array(
            new Constraints\NotBlank()
        );
    }

    public function provideValue(Request $request)
    {
        $id = $request->attributes->get("_content_id");
        if ($id == null)
            return null;
        
        $id_array = explode(":", $id);
        if (count($id_array) != 2) {
            throw new Exception(
                sprintf(
                    'Item ID %s not valid.',
                    $id
                )
            );
        }

        if ($id_array[0] == "AppBundle\Entity\ContentfulEntry")
            return $id_array[1];

        return null;
    }
}
