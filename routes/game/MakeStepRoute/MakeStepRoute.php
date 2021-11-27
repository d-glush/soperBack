<?php

namespace game\MakeStepRoute;

use DateTime;
use Field\Field;
use Field\GameStatusEnum;
use Field\Position2D;
use Field\StepTypeEnum;
use game\GameRoute;
use game\GameRouteResponse;
use LoggerService\LoggerService;
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
        $stepsCount = $_SESSION[GameRoute::SESSION_KEY_GAME_STEPS_COUNT];

        $field = new Field($fieldData, $fieldMinesCount, $fieldOpenedMinesCount, $fieldOpenedCellsCount, $gameStatus, $stepsCount);
        $stepX = $_POST[MakeStepRoute::POST_KEY_STEP_X];
        $stepY = $_POST[MakeStepRoute::POST_KEY_STEP_Y];
        $stepType = new StepTypeEnum($_POST[MakeStepRoute::POST_KEY_STEP_TYPE]);

        LoggerService::log(DEFAULT_LOG_PATH, "Делает ход: X=$stepX, Y==$stepY");
        $field->makeStep(new Position2D($stepX, $stepY), $stepType, true);

        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD] = $field->getField();
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_MINES_COUNT] = $field->getOpenedMinesCount();
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_CELLS_COUNT] = $field->getOpenedCells();
        $_SESSION[GameRoute::SESSION_KEY_GAME_STATUS] = $field->getGameStatus()->getValue();
        $_SESSION[GameRoute::SESSION_KEY_GAME_STEPS_COUNT] = $field->getStepsCnt();

        if ($field->getGameStatus()->getValue() === GameStatusEnum::GAME_STATUS_WIN) {
            $_SESSION[GameRoute::SESSION_KEY_GAME_FINISH_TIME] = new DateTime();
        }

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