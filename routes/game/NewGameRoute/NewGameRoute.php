<?php

namespace game\NewGameRoute;

use ConfigService;
use Field\Field;
use Field\GameStatusEnum;
use game\GameRoute;
use game\GameRouteResponse;
use Response\Response;
use Route\Route;

class NewGameRoute implements Route
{
    private const POST_NAME_FIELD_HEIGHT = 'fieldHeight';
    private const POST_NAME_FIELD_WIDTH = 'fieldWidth';
    private const POST_NAME_FIELD_MINES_COUNT = 'minesCount';
    private const POST_NAME_FIELD_COMPLEXITY = 'complexity';

    private const ERROR_WRONG_COMPLEXITY = 'wrong game complexity';

    public function __construct(string $queryPath)
    {

    }

    public function process(): Response
    {
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD] = null;
        $response = new GameRouteResponse();
        $gameField = new Field();
        $gameSettings = json_decode($_POST['gameSettings']);

        if ($gameSettings->{NewGameRoute::POST_NAME_FIELD_COMPLEXITY} === 'custom'){
            $fieldHeight = $gameSettings->{NewGameRoute::POST_NAME_FIELD_HEIGHT};
            $fieldWidth = $gameSettings->{NewGameRoute::POST_NAME_FIELD_WIDTH};
            $minesCount = $gameSettings->{NewGameRoute::POST_NAME_FIELD_MINES_COUNT};
        } else {
            $complexity = $gameSettings->{NewGameRoute::POST_NAME_FIELD_COMPLEXITY};
            $configService = new ConfigService();
            $complexities = $configService->getGameComplexities();
            if (!isset($complexities[$complexity])) {
                return $response->setError(NewGameRoute::ERROR_WRONG_COMPLEXITY);
            }
            $fieldSettings = $complexities[$complexity];
            $fieldHeight = $fieldSettings['fieldHeight'];
            $fieldWidth = $fieldSettings['fieldWidth'];
            $minesCount = $fieldSettings['minesCount'];
        }

        $field = $gameField->generate($fieldHeight,$fieldWidth,$minesCount);

        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD] = $field;
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_WIDTH] = $fieldWidth;
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_HEIGHT] = $fieldHeight;
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_MINES_COUNT] = $minesCount;
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_MINES_COUNT] = 0;
        $_SESSION[GameRoute::SESSION_KEY_GAME_FIELD_OPENED_CELLS_COUNT] = 0;
        $_SESSION[GameRoute::SESSION_KEY_GAME_STATUS] = GameStatusEnum::GAME_STATUS_PROCESS;

        $response
            ->setField($field)
            ->setFieldHeight($fieldHeight)
            ->setFieldWidth($fieldWidth)
            ->setMinesCount($minesCount)
            ->setOpenedMinesCount(0)
            ->setOpenedCells(0)
            ->setGameStatus(new GameStatusEnum(GameStatusEnum::GAME_STATUS_PROCESS));
        return $response;
    }



}