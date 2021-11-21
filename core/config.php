<?php

return [
    'autoloaderPriorities' => [
        'packages',
        'routes',
        'core',
        '',
    ],
    'gameComplexities' => [
        'easy' => [
            'fieldHeight' => 9,
            'fieldWidth' => 9,
            'minesCount' => 10,
        ],
        'medium' => [
            'fieldHeight' => 16,
            'fieldWidth' => 16,
            'minesCount' => 40,
        ],
        'hard' => [
            'fieldHeight' => 16,
            'fieldWidth' => 30,
            'minesCount' => 100,
        ]
    ]
];
