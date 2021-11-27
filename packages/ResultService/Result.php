<?php

namespace ResultService;

use DateTime;

class Result {
    public ?int $id;
    public int $userId;
    public ?string $login;
    public string $complexity;
    public string $date;
    public int $gameTime;
    public int $stepsCount;
    public ?int $position;

    public function __construct(array $dataArray)
    {
        if (isset($dataArray['id'])) {
            $this->id = (int)$dataArray['id'];
        }
        if (isset($dataArray['position'])) {
            $this->id = (int)$dataArray['position'];
        }
        if (isset($dataArray['login'])) {
            $this->login = $dataArray['login'];
        }

        $this->userId = $dataArray['user_id'];
        $this->date = $dataArray['date'];
        $this->gameTime = $dataArray['game_time'];
        $this->stepsCount = $dataArray['steps_count'];
        $this->complexity = $dataArray['complexity'];
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

    public function getLogin(): mixed
    {
        return $this->login;
    }

    public function setLogin(mixed $login): self
    {
        $this->login = $login;
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

    public function getDate(): string
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }
}