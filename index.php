<?php

use game\GameRoute;
use saveResult\SaveResultRoute;

require_once 'core/init.php';

$queryPath = $_GET['q'];
$headers = getallheaders();

[$method, $extQueryPath] = explode('/', $queryPath, 2);

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
        $scoreboardRoute = new ScoreboardRoute($extQueryPath);
        $result = $scoreboardRoute->process();
        break;
    default:
        http_response_code(404);
        echo json_encode('wrong method name');
        exit;
}

$response = json_encode($result);
echo $response;
