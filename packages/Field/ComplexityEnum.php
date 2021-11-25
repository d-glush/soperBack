<?php

namespace Field;

use Enum\Enum;

class ComplexityEnum extends Enum
{
    public const COMPLEXITY_EASY = 'easy';
    public const COMPLEXITY_MEDIUM = 'medium';
    public const COMPLEXITY_HARD = 'hard';

    public function getPossibleValues(): array
    {
        return [
            ComplexityEnum::COMPLEXITY_EASY => 'COMPLEXITY_EASY',
            ComplexityEnum::COMPLEXITY_MEDIUM => 'COMPLEXITY_MEDIUM',
            ComplexityEnum::COMPLEXITY_HARD => 'COMPLEXITY_HARD',
        ];
    }
}