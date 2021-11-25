<?php

namespace scoreboard;

use Response\Response;

class ScoreBoardRouteResponse implements Response
{
    public array $topEasy;
    public array $topMedium;
    public array $topHard;
    public array $me;
    public string $error;

    public function __construct($topEasy = [], $topMedium = [], $topHard = [], $me = [])
    {
        $this->topEasy = $topEasy;
        $this->topMedium = $topMedium;
        $this->topHard = $topHard;
        $this->me = $me;
    }

    public function getJson(): string
    {
        return json_encode([
        ]);
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): ScoreBoardRouteResponse
    {
        $this->error = $error;
        return $this;
    }
}