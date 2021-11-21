<?php

namespace Response;

interface Response
{
    public function getJson(): string;
    public function getError(): string;
    public function setError(string $error): self;
}