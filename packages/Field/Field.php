<?php

namespace Field;

use Field\FieldCell\FieldCell;
use Field\FieldCell\CellValueEnum;
use Field\FieldCell\CellStatusEnum;
use JetBrains\PhpStorm\Pure;

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

    public function getOpenedCells(): int
    {
        return $this->openedCells;
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
                    $this->recalcCellNumber(new Position2D($j, $i));
                }
            }
        }

        $this->field = $field;
        return $field;
    }

    public function makeStep(Position2D $stepPos, StepTypeEnum $stepType): void
    {
        $targetCell = $this->getCellByPos($stepPos);

        if ($targetCell->isOpened()) {
            return;
        }

        if ($stepType->getValue() === StepTypeEnum::STEP_TYPE_RIGHT_CLICK) {
            $targetCell->setFlagged();
            $this->openedMinesCount++;
            return;
        }

        switch($targetCell->getValue()->getValue()) {
            case CellValueEnum::CELL_VALUE_EMPTY:
                $this->processEmptyTarget($stepPos);
                break;
            case CellValueEnum::CELL_VALUE_MINE:
                $this->processMineTarget($stepPos);
                break;
            default:
                $this->processNumberTarget($stepPos);
                break;
        }
        if ($this->fieldHeight * $this->fieldWidth === $this->minesCount + $this->openedCells) {
            $this->gameStatus = new GameStatusEnum(GameStatusEnum::GAME_STATUS_WIN);
        }
    }

    private function processNumberTarget(Position2D $cellPos): void
    {
        $this->getCellByPos($cellPos)->setOpened();
        $this->openedCells++;
    }

    private function recalcNeighbors(Position2D $cellPos) {
        $neighborsPos = $this->getNeighborsPos($cellPos);
        foreach ($neighborsPos as $neighborPos) {
            $curNeighborCell = $this->getCellByPos($neighborPos);
            if (!$curNeighborCell->isMine()) {
                $this->recalcCellNumber($cellPos);
            }
        }
    }

    private function processMineTarget(Position2D $cellPos): void
    {
        if ($this->openedCells === 0) {
            $newMinePosition = $this->setRandomMine();
            $this->recalcNeighbors($newMinePosition);
            $this->recalcCellNumber($cellPos);
            $this->recalcNeighbors($cellPos);
            $this->makeStep($cellPos, new StepTypeEnum(StepTypeEnum::STEP_TYPE_LEFT_CLICK));
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

    private function processEmptyTarget(Position2D $cellPos): void
    {
        $used = [];
        for ($i = 0; $i < $this->fieldHeight; $i++){
            $usedRow = [];
            for ($j = 0; $j < $this->fieldWidth; $j++) {
                $usedRow[] = 0;
            }
            $used[] = $usedRow;
        }

        $stack = [$cellPos];
        $used[$cellPos->getY()][$cellPos->getX()] = 1;
        while ($stack) {
            $curPos = array_shift($stack);
            $this->getCellByPos($curPos)->setOpened();
            $this->openedCells++;

            $neighborsPos = $this->getNeighborsPos($curPos);
            foreach ($neighborsPos as $neighborPos) {
                $curNeighborCell = $this->getCellByPos($neighborPos);
                if (!$used[$neighborPos->getY()][$neighborPos->getX()]) {
                    if ($curNeighborCell->isEmpty()) {
                        $stack[] = $neighborPos;
                        $used[$neighborPos->getY()][$neighborPos->getX()] = 1;
                    } else if (!$curNeighborCell->isOpened()){
                        $curNeighborCell->setOpened();
                        $this->openedCells++;
                    }
                }
            }
        }
    }

    private function setRandomMine(): Position2D
    {
        $newMinePosX = -1;
        $newMinePosY = -1;
        $isMineSettled = false;
        while (!$isMineSettled) {
            $newMinePosX = rand(0, $this->fieldWidth - 1);
            $newMinePosY = rand(0, $this->fieldHeight - 1);
            /** @var FieldCell $targetCell */
            $targetCell = $this->field[$newMinePosX][$newMinePosY];
            if (!$targetCell->isMine()) {
                $targetCell->setValue(new CellValueEnum(CellValueEnum::CELL_VALUE_MINE));
                $isMineSettled = true;
            }
        }
        return new Position2D($newMinePosX, $newMinePosY);
    }

    private function recalcCellNumber(Position2D $cellPos): void
    {
        $minesAround = 0;
        $neighborsPos = $this->getNeighborsPos($cellPos);
        foreach ($neighborsPos as $neighborPos) {
            if ($this->getCellByPos($neighborPos)->isMine()) {
                $minesAround++;
            }
        }
        $this->getCellByPos($cellPos)->setValue(new CellValueEnum($minesAround));
    }

    /**
     * @return array<Position2D>
     */
    #[Pure] private function getNeighborsPos(Position2D $cellPos): array
    {
        $neighbors = [];
        for ($i = -1; $i < 2; $i++) {
            for ($j = -1; $j < 2; $j++) {
                if ($i === 0 && $j === 0) {
                    continue;
                }
                if (isset($this->field[$cellPos->getY() + $i][$cellPos->getX() + $j])) {
                    $neighbors[] = new Position2D($cellPos->getX() + $j, $cellPos->getY() + $i);
                }
            }
        }
        return $neighbors;
    }

    #[Pure] private function getCellByPos(Position2D $cellPos): FieldCell
    {
        return $this->field[$cellPos->getY()][$cellPos->getX()];
    }
}