<?php
namespace GameOfLife;

/**
 * Class Cell
 *
 * Lightweight cell implementation. Cell only knows whether is alive or not
 * @package GameOfLife
 */
class Cell
{
    const ALIVE = 1;
    const DEAD = 0;

    private $state;  

    public function __construct($state = self::ALIVE)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function isAlive()
    {
        return $this->state === self::ALIVE;
    }

    public function isDead()
    {
        return $this->state === self::DEAD;
    }
}
