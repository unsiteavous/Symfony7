<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BlocSpam extends Constraint
{
    public $blockSpam = ['viagra', 'porn', 'bitcoin'];
    public $message = 'Le mot "{{ value }}" n\'est pas autorisé.';

    public function __construct(array $blockSpam = null, string $message = null, $options = null, $groups = null, $payload = null)
    {
        parent::__construct($options, $groups, $payload);
        $this->blockSpam = $blockSpam ?? $this->blockSpam;
        $this->message = $message ?? $this->message;
    }
}
