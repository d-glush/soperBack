<?php

namespace scoreboard;

use Route\Route;

class ScoreBoardRoute implements Route
{
    private const SALT = '$2a$07$asdasdasdasdasdasdasdasda$';

    private const POST_KEY_LOGIN = 'login';
    private const POST_KEY_PASSWORD = 'password';

    public function __construct(string $queryPath)
    {
    }

    public function process(): SaveResultRouteResponse
    {
        $connection = mysqli_connect(
            'localhost',
            'root',
            '',
            'soperdb',
            3306
        );
        $userService = new UserService($connection);

        $login = $_POST[SaveResultRoute::POST_KEY_LOGIN];
        var_dump($login);
        $passwordDecoded = $_POST[SaveResultRoute::POST_KEY_PASSWORD];
        var_dump($passwordDecoded);
        var_dump(static::SALT);
        $password = crypt($passwordDecoded, static::SALT);
        var_dump($password);

        $user = $userService->getUserByLogin($login);
        $saveStatus = null;
        if ($user === false) {
            $newUser = new User(['login'=>$login, 'password'=>$password]);
            if ($passwordDecoded === '') {
                $userId = $userService->addUserOnlyLogin($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_ONLY_LOGIN);
            } else {
                $userId = $userService->addUser($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_USER_FULL);
            }
        } else {
            if (!hash_equals($password, $user->getPassword())) {
                return new SaveResultRouteResponse(new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_WRONG_PASSWORD));
            } else {
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_RESULT_SAVED);
                $userId = $user->getId();
            }
        }

        $timeStart = $_SESSION[GameRoute::SESSION_KEY_GAME_FINISH_TIME];
        $timeFinish = $_SESSION[GameRoute::SESSION_KEY_GAME_FINISH_TIME];
        $gameTime = (((int)$timeFinish->format('U'))*1000 + (int)$timeFinish->format('v')) -
            (((int)$timeStart->format('U'))*1000 + (int)$timeStart->format('v'));
        $result = new Result([
            "user_id" => $userId,
            "complexity" => $_SESSION[GameRoute::SESSION_KEY_GAME_COMPLEXITY],
            "date" => $_SESSION[GameRoute::SESSION_KEY_GAME_FINISH_TIME]->format('Y-m-d H:i:s'),
            "game_time" => $gameTime,
            "steps_count" => $_SESSION[GameRoute::SESSION_KEY_GAME_STEPS_COUNT],
        ]);
        $resultService = new ResultService($connection);
        $resultService->addResult($result);

        return new SaveResultRouteResponse($saveStatus);
    }
}