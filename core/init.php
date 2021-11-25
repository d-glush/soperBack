<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json; charset=UTF-8');

include_once __DIR__ . '/../packages/Field/Field.php';
include_once __DIR__ . '/../packages/Field/FieldCell/FieldCell.php';
include_once __DIR__ . '/Enum/Enum.php';
include_once __DIR__ . '/../packages/Field/GameStatusEnum.php';
include_once __DIR__ . '/../packages/Field/StepTypeEnum.php';
include_once __DIR__ . '/../packages/Field/FieldCell/CellStatusEnum.php';
include_once __DIR__ . '/../packages/Field/FieldCell/CellValueEnum.php';

session_start();

include "config_constants.php";
include_once 'autoloader.php';

