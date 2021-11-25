<?php

namespace ResultService;

use mysqli;

class ResultService {
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array<Result>|bool
     */
    public function getTop(string $complexity, int $limit = null, $offset = null): array|bool
    {
        $limitString = '';
        if ($limit && $offset) {
            $limitString = "LIMIT $offset, $limit";
        } elseif ($limit) {
            $limitString = "LIMIT $limit";
        }

        $result = $this->connection->query(
            "SELECT user.login as login, complexity, date, game_time as gameTime, steps_count as stepsCount
            FROM result JOIN user on user.id=result.user_id
            WHERE complexity='$complexity'
            ORDER BY game_time DESC
            $limitString;");

        $resultArray = [];
        while ($currUserData = $result->fetch_assoc()) {
            $resultArray[] = (object)($currUserData);
        }

        return $resultArray;
    }

    public function addResult(Result $result): bool
    {
        $userId = $result->getUserId();
        $complexity = $result->getComplexity();
        $date = $result->getDate();
        $gameTime = $result->getGameTime();
        $stepsCount = $result->getStepsCount();
        return $this->connection->query(
            "INSERT INTO result 
                        (user_id, complexity, date, game_time, steps_count) 
                    VALUES 
                        ('$userId', '$complexity', '$date', '$gameTime', '$stepsCount');"
        );
    }

    public function getResultWithPosition(int $resultId): object
    {
        $result = $this->connection->multi_query(
            "SET @index = 0;
SET @index = 0;
SELECT login, complexity, date, game_time, steps_count, position FROM(
    SELECT user.login as login, complexity, date, game_time, steps_count, @index := @index + 1 AS position
    FROM result join user on result.user_id = user.id
    ORDER BY game_time DESC) as a
WHERE login='admin'
LIMIT 1;"
        );
        var_dump($result);
        var_dump(mysqli_next_result($this->connection));
        return ((object)($result->fetch_assoc()));
    }
}