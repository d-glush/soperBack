<?php

namespace Field;

class Position2D {
    private int $posX;
    private int $posY;

    public function __construct(int $x, int $y)
    {
        $this->posX = $x;
        $this->posY = $y;
    }

    public function getX(): int
    {
        return $this->posX;
    }

    public function getY(): int
    {
        return $this->posY;
    }

    public function setPosX(int $posX): self
    {
        $this->posX = $posX;
        return $this;
    }

    public function setPosY(int $posY): self
    {
        $this->posY = $posY;
        return $this;
    }
}