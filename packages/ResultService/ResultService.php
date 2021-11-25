<?php

namespace UserService;

use Field\ComplexityEnum;
use mysqli;
use ResultService\Result;

class ResultService {
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array<Result>|bool
     */
    public function getTop(ComplexityEnum $complexity, int $limit, $offset): array|bool
    {
        $result = $this->connection->query("SELECT * FROM result ORDER BY ;");
        if ($result && $result->num_rows !== 0) {
            return new User($result->fetch_assoc());
        }
        return false;
    }

    public function addResult(Result $result): bool
    {
        $userId = $result->getUserId();
        $complexity = $result->getComplexity();
        $date = $result->getDate()->format('Y-m-d H:i:s');
        $gameTime = $result->getGameTime();
        $stepsCount = $result->getStepsCount();
        return $this->connection->query(
            "INSERT INTO result 
                        (user_id, complexity, date, game_time, steps_count) 
                    VALUES 
                        ('$userId', '$complexity', '$date', '$gameTime', '$stepsCount');"
        );
    }
}