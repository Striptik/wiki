<?php

namespace Wiki\WikiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class ContainsSameValues extends Constraint
{
    /**
     *
     * @var string
     */
    public $message = 'Les champs passwords doivent etre identiques';

    /**
     *
     *
     * @return string
     */
    public function validatedBy()
    {
        return 'password_not_identical';
    }

    /**
     * @return array
     */
    public function getTargets()
    {
        return array(self::CLASS_CONSTRAINT, self::PROPERTY_CONSTRAINT);
    }
}