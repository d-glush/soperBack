<?php

namespace save_result;

use game\SaveResultRouteResponse;
use Route\Route;
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
        $userService = new UserService(mysqli_connect(
                'localhost',
                'root',
                '',
                'soperdb',
                3306)
        );

        $login = $_POST['POST_KEY_LOGIN'];
        $passwordDecoded = $_POST['POST_KEY_PASSWORD'];
        $password = crypt($passwordDecoded, static::SALT);

        $user = $userService->getUserByLogin($login);
        $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_WRONG_PASSWORD);
        if (!$user) {
            $newUser = new User(['login'=>$login, 'password'=>$password]);
            if ($passwordDecoded === '') {
                $userService->addUserOnlyLogin($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_ONLY_LOGIN);
            } else {
                $userService->addUser($newUser);
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_CREATED_USER_FULL);
            }
        } else {
            if (!hash_equals($password, $user->getPassword())) {
                return new SaveResultRouteResponse($saveStatus);
            } else {
                $saveStatus = new SaveStatusEnum(SaveStatusEnum::SAVE_STATUS_RESULT_SAVED);
            }
        }

        $resultService->addResult($_SESSION data)

        return ((new SaveResultRouteResponse())
            ->setStatus($saveStatus))
    }
}