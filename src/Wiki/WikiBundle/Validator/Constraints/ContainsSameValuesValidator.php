<?php

namespace Wiki\WikiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsSameValuesValidator extends ConstraintValidator
{
    /**
     * @param string                                  $value      Property value
     * @param \Symfony\Component\Validator\Constraint $constraint All properties
     *
     * @return boolean
     */
    public function validate($value, Constraint $constraint)
    {
        $repeated = $this->context->getRoot()->get('repeatPassword')->getData();

        if ($value!== $repeated) {
            $this->context->addViolation(
                $constraint->message,
                array('%string%', $value)
            );
            return false;
          }
          return true;
    }
}