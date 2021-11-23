<?php

namespace game;

use game\MakeStepRoute\MakeStepRoute;
use game\NewGameRoute\NewGameRoute;
use Response\Response;
use Route\Route;

class GameRoute implements Route
{
    public const SESSION_KEY_GAME_FIELD = 'game_field';
    public const SESSION_KEY_GAME_FIELD_WIDTH = 'game_field_width';
    public const SESSION_KEY_GAME_FIELD_HEIGHT = 'game_field_height';
    public const SESSION_KEY_GAME_FIELD_MINES_COUNT = 'game_field_mines_count';
    public const SESSION_KEY_GAME_FIELD_OPENED_MINES_COUNT = 'game_field_opened_mines_count';
    public const SESSION_KEY_GAME_FIELD_OPENED_CELLS_COUNT = 'game_field_opened_cells_count';
    public const SESSION_KEY_GAME_STATUS = 'game_status';

    public const ERROR_WRONG_METHOD = 'wrong method name';

    private string $queryPath;

    public function __construct(string $queryPath)
    {
        $this->queryPath = $queryPath;
    }

    public function process(): Response
    {
        [$method] = explode('/', $this->queryPath, 2);

        switch($method) {
            case 'start-new-game':
                $newGameRoute = new NewGameRoute('');
                return $newGameRoute->process();
            case 'make-step':
                $makeStepRoute = new MakeStepRoute('');
                return $makeStepRoute->process();
            default:
                return ((new GameRouteResponse())->setError(GameRoute::ERROR_WRONG_METHOD));
        }
    }

}