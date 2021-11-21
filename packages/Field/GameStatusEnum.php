<?php

namespace Field;

use Enum\Enum;

class GameStatusEnum extends Enum
{
    public const GAME_STATUS_PROCESS = 0;
    public const GAME_STATUS_WIN = 1;
    public const GAME_STATUS_LOOSE = 2;

    public function getPossibleValues(): array
    {
        return [
            self::GAME_STATUS_PROCESS => 'GAME_STATUS_PROCESS',
            self::GAME_STATUS_WIN => 'GAME_STATUS_WIN',
            self::GAME_STATUS_LOOSE => 'GAME_STATUS_LOOSE'
        ];
    }
}