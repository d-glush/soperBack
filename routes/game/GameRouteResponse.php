<?php

namespace game;

use Field\GameStatusEnum;
use Response\Response;

class GameRouteResponse implements Response
{
    public int $fieldHeight;
    public int $fieldWidth;
    public int $minesCount;
    public int $openedMinesCount;
    public array $field;
    public GameStatusEnum $gameStatus;
    public string $error = 'false';
    public int $openedCells;

    public function __construct(int $fieldHeight = 0, int $fieldWidth = 0, int $minesCount = 0, array $field = [])
    {
        $this->fieldHeight = $fieldHeight;
        $this->fieldWidth = $fieldWidth;
        $this->minesCount = $minesCount;
        $this->openedMinesCount = 0;
        $this->openedCells = 0;
        $this->field = $field;
    }

    public function getOpenedCells(): int
    {
        return $this->openedCells;
    }

    public function setOpenedCells(int $openedCells): self {
        $this->openedCells = $openedCells;
        return $this;
    }

    public function getGameStatus(): GameStatusEnum
    {
        return $this->gameStatus;
    }

    public function setGameStatus(GameStatusEnum $gameStatus): self
    {
        $this->gameStatus = $gameStatus;
        return $this;
    }

    public function getOpenedMinesCount(): int
    {
        return $this->openedMinesCount;
    }

    public function setOpenedMinesCount(int $openedMinesCount): self
    {
        $this->openedMinesCount = $openedMinesCount;
        return $this;
    }

    public function getFieldHeight(): int
    {
        return $this->fieldHeight;
    }

    public function setFieldHeight(int $fieldHeight): self
    {
        $this->fieldHeight = $fieldHeight;
        return $this;
    }

    public function getFieldWidth(): int
    {
        return $this->fieldWidth;
    }

    public function setFieldWidth(int $fieldWidth): self
    {
        $this->fieldWidth = $fieldWidth;
        return $this;
    }

    public function getMinesCount(): int
    {
        return $this->minesCount;
    }

    public function setMinesCount(int $minesCount): self
    {
        $this->minesCount = $minesCount;
        return $this;
    }

    public function getField(): array
    {
        return $this->field;
    }

    public function setField(array $field): self
    {
        $this->field = $field;
        return $this;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        return $this;
    }

    public function getJson(): string
    {
        return json_encode([
            $this->fieldHeight,
            $this->fieldWidth,
            $this->minesCount,
            $this->field,

        ]);
    }
}