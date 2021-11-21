<?php

namespace Field\FieldCell;

use Enum\Enum;
use UnexpectedValueException;

class CellStatusEnum extends Enum
{
    public const CELL_STATUS_HIDDEN = 0;
    public const CELL_STATUS_OPENED = 1;

    public function getPossibleValues(): array
    {
        return [
            CellStatusEnum::CELL_STATUS_HIDDEN => 'CELL_STATUS_HIDDEN',
            CellStatusEnum::CELL_STATUS_OPENED => 'CELL_STATUS_OPENED',
        ];
    }

    public function open()
    {
        $this->value = self::CELL_STATUS_OPENED;
    }
}