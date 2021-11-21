<?php

namespace Field;

use Field\FieldCell\FieldCell;
use Field\FieldCell\CellValueEnum;
use Field\FieldCell\CellStatusEnum;

class Field
{
    /**
     * @var array<array<FieldCell>>
     */
    private array $field;
    private ?int $minesCount = null;
    private ?int $fieldWidth = null;
    private ?int $fieldHeight = null;
    private ?int $openedMinesCount = null;
    private ?int $openedCells = null;
    private ?GameStatusEnum $gameStatus = null;

    /**
     * @param array<array<FieldCell>> $data
     */
    public function __construct(
        array $data = [],
        $minesCount = 0,
        $openedMines = 0,
        $openedCells = 0,
        ?GameStatusEnum $gameStatus = null
    ) {
        $this->field = $data;
        if (count($data) != 0) {
            $this->minesCount = $minesCount;
            $this->openedMinesCount = $openedMines;
            $this->fieldHeight = count($data);
            $this->fieldWidth = count($data[0]);
            $this->fieldWidth = count($data[0]);
            $this->openedCells = $openedCells;
            $this->gameStatus = $gameStatus;
        }
    }

    public function getOpenedMinesCount(): int
    {
        return $this->openedMinesCount;
    }

    public function getField(): array
    {
        return $this->field;
    }

    public function getGameStatus(): GameStatusEnum
    {
        return $this->gameStatus;
    }

    public function getMinesCount(): int
    {
        return $this->minesCount;
    }

    public function generate($height, $width, $minesCnt): array
    {
        $this->fieldWidth = $width;
        $this->fieldHeight = $height;
        $this->minesCount = $minesCnt;
        $this->openedMinesCount = 0;
        $this->openedCells = 0;
        $this->gameStatus = new GameStatusEnum(GameStatusEnum::GAME_STATUS_PROCESS);

        /** @var array<array<FieldCell>> $field */
        $field = [];
        for ($i = 0; $i < $height; $i++) {
            $row = [];
            for ($j = 0; $j < $width; $j++) {
                $row[] = new FieldCell(
                    new CellValueEnum(CellValueEnum::CELL_VALUE_EMPTY),
                    new CellStatusEnum(CellStatusEnum::CELL_STATUS_HIDDEN)
                );
            }
            $field[] = $row;
        }

        $this->field = $field;
        for ($i = 0; $i < $minesCnt; $i++) {
            $this->setRandomMine();
        }

        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $targetCell = $field[$i][$j];
                if (!$targetCell->isMine()) {
                    $this->calcCellNumber($i, $j);
                }
            }
        }

        $this->field = $field;
        return $field;
    }

    public function makeStep(int $stepX, int $stepY, StepTypeEnum $stepType): void
    {
        $targetCell = $this->field[$stepY][$stepX];

        if ($targetCell->isOpened()) {
            return;
        }

        if ($stepType->getValue() === StepTypeEnum::STEP_TYPE_RIGHT_CLICK) {
            $targetCell->setFlagged();
            return;
        }

        switch($targetCell->getValue()->getValue()) {
            case CellValueEnum::CELL_VALUE_EMPTY:
                $this->processEmptyTarget($stepX, $stepY);
                break;
            case CellValueEnum::CELL_VALUE_MINE:
                $this->processMineTarget($stepX, $stepY);
                break;
            default:
                $this->processNumberTarget($stepX, $stepY);
                break;
        }
        if ($this->fieldHeight * $this->fieldWidth === $this->minesCount + $this->openedCells) {
            $this->gameStatus = new GameStatusEnum(GameStatusEnum::GAME_STATUS_WIN);
        }
    }

    private function processNumberTarget(int $stepX, int $stepY): void
    {
        $this->field[$stepY][$stepX]->setOpened();
        $this->openedCells++;
    }

    private function processMineTarget(int $stepX, int $stepY): void
    {
        if ($this->openedCells === 0) {
            $this->setRandomMine();
            $this->calcCellNumber($stepY, $stepX);
            $this->makeStep($stepX, $stepY);
        } else {
            for ($i = 0; $i < $this->fieldHeight; $i++) {
                for ($j = 0; $j < $this->fieldWidth; $j++) {
                    $targetCell = $this->field[$i][$j];
                    if ($targetCell->isMine()) {
                        $targetCell->setOpened();
                    }
                }
            }
            $this->gameStatus = new GameStatusEnum(GameStatusEnum::GAME_STATUS_LOOSE);
        }
    }

    private function processEmptyTarget(int $stepX, int $stepY): void
    {
        $field = $this->field;
        $used = [];
        for ($i = 0; $i < $this->fieldHeight; $i++){
            $usedRow = [];
            for ($j = 0; $j < $this->fieldHeight; $j++) {
                $usedRow[] = 0;
            }
            $used[] = $usedRow;
        }

        $stack = [[$stepY, $stepX]];
        while ($stack) {
            [$curY, $curX] = array_shift($stack);
            $field[$curY][$curX]->setOpened();
            $this->openedCells++;
            $used[$curY][$curX] = 1;

            for ($i = -1; $i < 2; $i++) {
                for ($j = -1; $j < 2; $j++) {
                    if ($i === 0 && $j === 0) {
                        continue;
                    }
                    if (isset($field[$curY + $i][$curX + $j]) && !$used[$curY + $i][$curX + $j]) {
                        if ($field[$curY + $i][$curX + $j]->isEmpty()) {
                            $stack[] = [$curY + $i, $curX + $j];
                        } else {
                            $field[$curY + $i][$curX + $j]->setOpened();
                            $this->openedCells++;
                        }
                    }
                }
            }
        }
    }

    private function setRandomMine(): void
    {
        $isMineSettled = false;
        while (!$isMineSettled) {
            $x = rand(0, $this->fieldWidth - 1);
            $y = rand(0, $this->fieldHeight - 1);
            /** @var FieldCell $targetCell */
            $targetCell = $this->field[$y][$x];
            if (!$targetCell->isMine()) {
                $targetCell->setValue(new CellValueEnum(CellValueEnum::CELL_VALUE_MINE));
                $isMineSettled = true;
            }
        }
    }

    private function calcCellNumber(int $i, int $j): void
    {
        $field = $this->field;

        if ($field[$i][$j]->isMine()) {
            return;
        }

        $minesAround = 0;
        if ($i > 0) {
            $minesAround += $field[$i-1][$j]->isMine() ? 1 : 0;
        }
        if ($i < $this->fieldHeight - 1) {
            $minesAround += $field[$i+1][$j]->isMine() ? 1 : 0;
        }
        if ($j > 0) {
            $minesAround += $field[$i][$j-1]->isMine() ? 1 : 0;
        }
        if ($j < $this->fieldWidth - 1) {
            $minesAround += $field[$i][$j+1]->isMine() ? 1 : 0;
        }
        if ($i > 0 && $j > 0) {
            $minesAround += $field[$i-1][$j-1]->isMine() ? 1 : 0;
        }
        if ($i > 0 && $j < $this->fieldWidth - 1) {
            $minesAround += $field[$i-1][$j+1]->isMine() ? 1 : 0;
        }
        if ($i < $this->fieldHeight - 1 && $j > 0) {
            $minesAround += $field[$i+1][$j-1]->isMine() ? 1 : 0;
        }
        if ($i < $this->fieldHeight - 1 && $j < $this->fieldWidth - 1) {
            $minesAround += $field[$i+1][$j+1]->isMine() ? 1 : 0;
        }

        $field[$i][$j]->setValue(new CellValueEnum($minesAround));
    }
}