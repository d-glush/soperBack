<?php

namespace Route;

use Response\Response;

interface Route
{
    public function __construct(string $queryPath);
    public function process(): Response;
}