<?php

namespace Field\FieldCell;

use Enum\Enum;
use UnexpectedValueException;

class CellStatusEnum extends Enum
{
    public const CELL_STATUS_HIDDEN = 0;
    public const CELL_STATUS_OPENED = 1;
    public const CELL_STATUS_FLAGGED = 2;

    public function getPossibleValues(): array
    {
        return [
            CellStatusEnum::CELL_STATUS_HIDDEN => 'CELL_STATUS_HIDDEN',
            CellStatusEnum::CELL_STATUS_OPENED => 'CELL_STATUS_OPENED',
            CellStatusEnum::CELL_STATUS_FLAGGED => 'CELL_STATUS_FLAGGED',
        ];
    }

    public function setOpened()
    {
        $this->value = self::CELL_STATUS_OPENED;
    }

    public function setFlagged()
    {
        $this->value = self::CELL_STATUS_FLAGGED;
    }

    public function setUnflagged()
    {
        $this->value = self::CELL_STATUS_OPENED;
    }
}