<?php

namespace Field;

use Enum\Enum;

class StepTypeEnum extends Enum
{
    public const STEP_TYPE_LEFT_CLICK = 0;
    public const STEP_TYPE_RIGHT_CLICK = 1;

    public function getPossibleValues(): array
    {
        return [
            self::STEP_TYPE_LEFT_CLICK => 'STEP_TYPE_LEFT_CLICK',
            self::STEP_TYPE_RIGHT_CLICK => 'STEP_TYPE_RIGHT_CLICK',
        ];
    }
}