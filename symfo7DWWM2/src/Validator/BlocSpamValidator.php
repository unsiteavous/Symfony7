<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use UnexpectedValueException;

class BlocSpamValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /** @var BlocSpam $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = strtolower($value);

        foreach ($constraint->blockSpam as $spam) {
            if (str_contains($value, $spam)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $spam)
                    ->addViolation();
            }
        }
    }
}
