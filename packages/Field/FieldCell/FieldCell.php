<?php

namespace Field\FieldCell;

use JetBrains\PhpStorm\Pure;

class FieldCell
{
    public ?CellValueEnum $cellValue = null;
    public ?CellStatusEnum $cellStatus = null;

    public function __construct
    (
        CellValueEnum $value,
        CellStatusEnum $status
    ) {
        $this->cellValue = $value;
        $this->cellStatus = $status;
    }

    public function getStatus(): CellStatusEnum
    {
        return $this->cellStatus;
    }

    public function getValue(): CellValueEnum
    {
        return $this->cellValue;
    }

    public function setValue(CellValueEnum $value): void
    {
        $this->cellValue = $value;
    }

    public function setStatus(CellStatusEnum $status): void
    {
        $this->cellStatus = $status;
    }

    #[Pure] public function isMine(): bool
    {
        return ($this->cellValue->getValue() == CellValueEnum::CELL_VALUE_MINE);
    }

    #[Pure] public function isEmpty(): bool
    {
        return ($this->cellValue->getValue() == CellValueEnum::CELL_VALUE_EMPTY);
    }

    #[Pure] public function isOpened(): bool
    {
        return ($this->getStatus()->getValue() == CellStatusEnum::CELL_STATUS_OPENED);
    }

    public function setOpened()
    {
        $this->cellStatus->setOpened();
    }

    public function setFlagged()
    {
        $this->cellStatus->setFlagged();
    }

    public function increaseValue(): self
    {
        if (!$this->isMine()) {
            $this->cellValue = new CellValueEnum($this->cellValue->getValue());
        }
        return $this;
    }
}