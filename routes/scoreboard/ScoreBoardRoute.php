<?php

namespace scoreboard;

use Field\ComplexityEnum;
use ResultService\ResultService;
use Route\Route;
use save_result\SaveResultRoute;
use scoreboard\ScoreBoardRouteResponse;
use UserService\UserService;

class ScoreBoardRoute implements Route
{
    private const SALT = '$2a$07$asdasdasdasdasdasdasdasda$';

    private const POST_KEY_LOGIN = 'login';
    private const POST_KEY_PASSWORD = 'password';

    public function __construct(string $queryPath)
    {
    }

    public function process(): ScoreBoardRouteResponse
    {
        $connection = mysqli_connect(
            'localhost',
            'root',
            '',
            'soperdb',
            3306
        );
        $userService = new UserService($connection);
        $login = $_POST[ScoreBoardRoute::POST_KEY_LOGIN];
        $passwordDecoded = $_POST[ScoreBoardRoute::POST_KEY_PASSWORD];
        $password = crypt($passwordDecoded, ScoreBoardRoute::SALT);
        $user = $userService->getUserByLogin($login);
        if ($user === false) {
            return (new ScoreBoardRouteResponse())->setError('wrong password');
        } else {
            if (!hash_equals($password, $user->getPassword())) {
                return (new ScoreBoardRouteResponse())->setError('wrong password');
            } else {
                $userId = $user->getId();
            }
        }

        $resultService = new ResultService($connection);

        $top100Easy = $resultService->getTop(ComplexityEnum::COMPLEXITY_EASY, 100);
        $top100Medium = $resultService->getTop(ComplexityEnum::COMPLEXITY_MEDIUM, 100);
        $top100Hard = $resultService->getTop(ComplexityEnum::COMPLEXITY_HARD, 100);
        $top = array_merge($top100Easy, $top100Medium, $top100Hard);

        $curResultId = $_SESSION[SaveResultRoute::SESSION_KEY_NEW_RESULT_ID];
        $me = $resultService->getResultWithPosition($curResultId);

        return new ScoreBoardRouteResponse($top100Easy, $top100Medium, $top100Hard, $me);
    }
}