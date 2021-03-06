<?php

namespace save_result;

use game\GameRoute;
use ResultService\ResultService;
use ResultService\Result;
use Route\Route;
use UserService\User;
use UserService\UserService;

class SaveResultRoute implements Route
{
    private const SALT = '$2a$07$asdasdasdasdasdasdasdasda$';

    private const POST_KEY_LOGIN = 'login';
    private const POST_KEY_PASSWORD = 'password';

    public const SESSION_KEY_NEW_RESULT_ID = 'new_result_id';

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
            if (!$user->getPassword() && $passwordDecoded) {
                $userService->changePassword($user, $password);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_ADDED_PASSWORD);
            } else if (!hash_equals($password, $user->getPassword())) {
                return new SaveResultRouteResponse(new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_WRONG_PASSWORD));
            } else {
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_RESULT_SAVED);
            }
            $userId = $user->getId();
        }

        $timeStart = $_SESSION[GameRoute::SESSION_KEY_GAME_START_TIME];
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
        $newResultId = $resultService->addResult($result);
        $_SESSION[SaveResultRoute::SESSION_KEY_NEW_RESULT_ID] = $newResultId;

        return new SaveResultRouteResponse($saveStatus);
    }
}