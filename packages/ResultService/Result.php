<?php

namespace ResultService;

use DateTime;

class Result {
    private ?int $id;
    private int $userId;
    private string $complexity;
    private DateTime $date;
    private int $gameTime;
    private int $stepsCount;

    public function __construct(array $dataArray)
    {
        if (isset($dataArray['id'])) {
            $this->id = (int)$dataArray['id'];
        }

        $this->userId = $dataArray['user_id'];
        $this->date = $dataArray['date'];
        $this->gameTime = $dataArray['game_time'];
        $this->stepsCount = $dataArray['steps_count'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(mixed $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getComplexity(): string
    {
        return $this->complexity;
    }

    public function setComplexity(string $complexity): self
    {
        $this->complexity = $complexity;
        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(mixed $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getGameTime(): int
    {
        return $this->gameTime;
    }

    public function setGameTime(mixed $gameTime): self
    {
        $this->gameTime = $gameTime;
        return $this;
    }

    public function getStepsCount(): int
    {
        return $this->stepsCount;
    }

    public function setStepsCount(mixed $stepsCount): self
    {
        $this->stepsCount = $stepsCount;
        return $this;
    }


}