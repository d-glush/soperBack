<?php

namespace ResultService;

use mysqli;

class ResultService {
    private mysqli $connection;

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
            "SELECT result.id as id, user.id as user_id, user.login as login, complexity, date, game_time, steps_count
            FROM result JOIN user on user.id=result.user_id
            WHERE complexity='$complexity'
            ORDER BY game_time ASC
            $limitString;");

        $resultArray = [];
        while ($currUserData = $result->fetch_assoc()) {
            $resultArray[] = new Result($currUserData);
        }

        return $resultArray;
    }

    public function addResult(Result $result): int
    {
        $userId = $result->getUserId();
        $complexity = $result->getComplexity();
        $date = $result->getDate();
        $gameTime = $result->getGameTime();
        $stepsCount = $result->getStepsCount();
        $this->connection->query(
            "INSERT INTO result 
                        (user_id, complexity, date, game_time, steps_count) 
                    VALUES 
                        ('$userId', '$complexity', '$date', '$gameTime', '$stepsCount');"
        );
        return mysqli_insert_id($this->connection);
    }

    public function getResultWithPosition(int $resultId): Result
    {
        $this->connection->query("CALL p_get_result_position_by_id($resultId, @position);");
        $result = $this->connection->query("SELECT @position");
        $row = $result->fetch_assoc();
        $resultPosition = $row['@position'];

        $resultObject = $this->getResultById($resultId);
        return ($resultObject->setPosition($resultPosition));
    }

    public function getResultById(int $resultId): Result
    {
        $result = $this->connection->query("SELECT * FROM result WHERE id=$resultId;");
        $row = $result->fetch_assoc();
        return new Result($row);
    }
}