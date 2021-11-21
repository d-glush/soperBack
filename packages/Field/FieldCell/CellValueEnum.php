<?php

namespace Field\FieldCell;

use Enum\Enum;
use UnexpectedValueException;

class CellValueEnum extends Enum
{
    public const CELL_VALUE_MINE = 99;
    public const CELL_VALUE_EMPTY = 0;
    public const CELL_VALUE_ONE = 1;
    public const CELL_VALUE_TWO = 2;
    public const CELL_VALUE_THREE = 3;
    public const CELL_VALUE_FOUR = 4;
    public const CELL_VALUE_FIVE = 5;
    public const CELL_VALUE_SIX = 6;
    public const CELL_VALUE_SEVEN = 7;
    public const CELL_VALUE_EIGHT = 8;

    public function getValue(): int
    {
        return $this->value;
    }

    public function getPossibleValues(): array
    {
        return [
            CellValueEnum::CELL_VALUE_MINE => 'CELL_VALUE_MINE',
            CellValueEnum::CELL_VALUE_EMPTY => 'CELL_VALUE_EMPTY',
            CellValueEnum::CELL_VALUE_ONE => 'CELL_VALUE_ONE',
            CellValueEnum::CELL_VALUE_TWO => 'CELL_VALUE_TWO',
            CellValueEnum::CELL_VALUE_THREE => 'CELL_VALUE_THREE',
            CellValueEnum::CELL_VALUE_FOUR => 'CELL_VALUE_FOUR',
            CellValueEnum::CELL_VALUE_FIVE => 'CELL_VALUE_FIVE',
            CellValueEnum::CELL_VALUE_SIX => 'CELL_VALUE_SIX',
            CellValueEnum::CELL_VALUE_SEVEN => 'CELL_VALUE_SEVEN',
            CellValueEnum::CELL_VALUE_EIGHT => 'CELL_VALUE_EIGHT',
        ];
    }
}