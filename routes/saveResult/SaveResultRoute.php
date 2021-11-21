<?php

namespace saveResult;

use game\SaveResultResponce;
use Route\Route;

class SaveResultRoute implements Route
{
    private const POST_KEY_RESULT_LOGIN = 'login';
    private const POST_KEY_RESULT_PASSWORD = 'password';

    public function __construct(string $queryPath)
    {
    }

    public function process(): SaveResultResponce
    {
        $login = $_POST[SaveResultRoute::POST_KEY_RESULT_LOGIN];
        $password = $_POST[SaveResultRoute::POST_KEY_RESULT_PASSWORD];



        $response = new SaveResultResponce();
    }

}