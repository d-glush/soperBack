<?php

namespace game;

use Response\Response;
use save_result\SaveStatusEnum;

class SaveResultRouteResponse implements Response
{
    public int $saveStatus;
    public string $error;

    public function __construct(SaveStatusEnum $saveStatus)
    {
        $this->saveStatus = $saveStatus->getValue();
    }

    public function setSaveStatus(SaveStatusEnum $saveStatus): void
    {
        $this->saveStatus = $saveStatus->getValue();
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

    public function setError(string $error): Response
    {
        $this->error = $error;
        return $this;
    }
}