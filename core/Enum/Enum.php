<?php

namespace Enum;

use UnexpectedValueException;

abstract class Enum
{
    public int|string $value;

    public function __construct(int|string $value)
    {
        if (isset($this->getPossibleValues()[$value])) {
            $this->value = $value;
        } else {
            throw new UnexpectedValueException();
        }
    }

    public abstract function getPossibleValues(): array;
    public function getValue(): int
    {
        return $this->value;
    }
}