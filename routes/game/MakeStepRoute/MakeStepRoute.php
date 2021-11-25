<?php

namespace game\MakeStepRoute;

use Field\Field;
use Field\GameStatusEnum;
use Field\Position2D;
use Field\StepTypeEnum;
use game\GameRoute;
use game\GameRouteResponse;
use Logger\Logger;
use Route\Route;

class MakeStepRoute implements Route
{
    private const POST_KEY_STEP_X = 'x';
    private const POST_KEY_STEP_Y = 'y';
    private const POST_KEY_STEP_TYPE = 'type';

    public function __construct(string $queryPath)
    {
    }

    public function process(): GameRouteResponse
    {
        $fieldData = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD];
        $fieldWidth = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_WIDTH];
        $fieldHeight = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_HEIGHT];
        $fieldMinesCount = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_MINES_COUNT];
        $fieldOpenedMinesCount = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_MINES_COUNT];
        $fieldOpenedCellsCount = $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_CELLS_COUNT];
        $gameStatus = new GameStatusEnum($_SESSION[GameRoute::SESSION_KEY_GAME_STATUS]);

        $field = new Field($fieldData, $fieldMinesCount, $fieldOpenedMinesCount, $fieldOpenedCellsCount, $gameStatus);
        $stepX = $_POST[MakeStepRoute::POST_KEY_STEP_X];
        $stepY = $_POST[MakeStepRoute::POST_KEY_STEP_Y];
        $stepType = new StepTypeEnum($_POST[MakeStepRoute::POST_KEY_STEP_TYPE]);

        Logger::log(DEFAULT_LOG_PATH, "Делает ход: X=$stepX, Y==$stepY");
        $field->makeStep(new Position2D($stepX, $stepY), $stepType);

        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD] = $field->getField();
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_MINES_COUNT] = $field->getOpenedMinesCount();
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_CELLS_COUNT] = $field->getOpenedCells();
        $_SESSION[GameRoute::SESSION_KEY_GAME_STATUS] = $field->getGameStatus()->getValue();

        return ((new GameRouteResponse())
            ->setField($field->getField())
            ->setFieldHeight($fieldHeight)
            ->setFieldWidth($fieldWidth)
            ->setMinesCount($fieldMinesCount)
            ->setOpenedCells($field->getOpenedCells())
            ->setOpenedMinesCount($field->getOpenedMinesCount())
            ->setGameStatus($field->getGameStatus()));
    }
}