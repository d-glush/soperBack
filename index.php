<?php

use game\GameRoute;
use LoggerService\LoggerService;
use save_result\SaveResultRoute;

require_once 'core/init.php';

LoggerService::logStart();

$queryPath = $_GET['q'];
$headers = getallheaders();

if (strpos ($queryPath, '/')) {
    [$method, $extQueryPath] = explode('/', $queryPath, 2);
} else {
    $method = $queryPath;
    $extQueryPath = '';
}

switch($method) {
    case 'game':
        $gameRoute = new GameRoute($extQueryPath);
        $result = $gameRoute->process();
        break;
    case 'save_result':
        $saveResultRoute = new SaveResultRoute($extQueryPath);
        $result = $saveResultRoute->process();
        break;
    case 'scoreboard':
        $scoreBoardRoute = new ScoreBoardRoute($extQueryPath);
    default:
        http_response_code(404);
        echo json_encode('wrong method name');
        exit;
}

$response = json_encode($result);
echo $response;

LoggerService::logEnd();
