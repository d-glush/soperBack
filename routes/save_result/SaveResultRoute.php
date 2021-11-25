<?php

namespace save_result;

use game\GameRoute;
use game\SaveResultRouteResponse;
use ResultService\Result;
use Route\Route;
use UserService\ResultService;
use UserService\User;
use UserService\UserService;

class SaveResultRoute implements Route
{
    private const SALT = '$MY!_/R.E,AlYYniceSAAAAALTTTT';

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
        $passwordDecoded = $_POST[SaveResultRoute::POST_KEY_PASSWORD];
        $password = crypt($passwordDecoded, static::SALT);

        $user = $userService->getUserByLogin($login);
        $saveStatus = null;
        $userid = null;
        if (!$user) {
            $newUser = new User(['login'=>$login, 'password'=>$password]);
            if ($passwordDecoded === '') {
                $userid = $userService->addUserOnlyLogin($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_ONLY_LOGIN);
            } else {
                $userid = $userService->addUser($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_USER_FULL);
            }
        } else {
            if (!hash_equals($password, $user->getPassword())) {
                return new SaveResultRouteResponse($saveStatus);
            } else {
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_RESULT_SAVED);
                $userid = $user->getId();
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
            "seps_count" => $_SESSION[GameRoute::SESSION_KEY_GAME_STEPS_COUNT],
        ]);
        $resultService = new ResultService($connection);
        $resultService->addResult($result);

        return new SaveResultRouteResponse($saveStatus);
    }
}