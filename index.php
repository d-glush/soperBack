<?php

use game\GameRoute;

require_once 'core/init.php';

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json; charset=UTF-8');
session_start();

$queryPath = $_GET['q'];
$headers = getallheaders();

[$method, $extQueryPath] = explode('/', $queryPath, 2);

switch($method) {
    case 'game':
        $gameRoute = new GameRoute($extQueryPath);
        $result = $gameRoute->process();
        break;
    case 'account':
        $saveResultRoute = new SaveResultRoute($extQueryPath);
        $result = $saveResultRoute->process();
        break;
    default:
        http_response_code(404);
        echo json_encode('wrong method name');
        exit;
}

$response = json_encode($result);
echo $response;
