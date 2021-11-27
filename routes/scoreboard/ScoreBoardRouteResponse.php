<?php

namespace scoreboard;

use Response\Response;
use ResultService\Result;

class ScoreBoardRouteResponse implements Response
{
    public array $topEasy;
    public array $topMedium;
    public array $topHard;
    public Result $me;
    public string $error;

    public function __construct(array $topEasy, array $topMedium, array $topHard, Result $me)
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