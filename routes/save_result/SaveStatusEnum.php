<?php

namespace save_result;

use Enum\Enum;

class SaveStatusEnum extends Enum
{
    public CONST SAVE_STATUS_RESULT_SAVED = 0;
    public CONST SAVE_STATUS_CREATED_USER_FULL = 1;
    public CONST SAVE_STATUS_CREATED_ONLY_LOGIN = 2;
    public CONST SAVE_STATUS_ADDED_PASSWORD = 3;
    public CONST SAVE_STATUS_WRONG_PASSWORD = 401;

    public function getPossibleValues(): array
    {
        return [
            SaveStatusEnum::SAVE_STATUS_CREATED_USER_FULL => 'SAVE_STATUS_CREATED_USER_FULL',
            SaveStatusEnum::SAVE_STATUS_CREATED_ONLY_LOGIN => 'SAVE_STATUS_CREATED_ONLY_LOGIN',
            SaveStatusEnum::SAVE_STATUS_ADDED_PASSWORD => 'SAVE_STATUS_ADDED_PASSWORD',
            SaveStatusEnum::SAVE_STATUS_WRONG_PASSWORD => 'SAVE_STATUS_WRONG_PASSWORD',
            SaveStatusEnum::SAVE_STATUS_RESULT_SAVED => 'SAVE_STATUS_RESULT_SAVED',
        ];
    }
}