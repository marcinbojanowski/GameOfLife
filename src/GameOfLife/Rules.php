<?php
namespace GameOfLife;

/**
 * Interface Rules
 *
 * Responsible for universe next era calculations
 * @package GameOfLife
 */
interface Rules 
{
    public function nextEra(Universe $currentUniverse);
}
