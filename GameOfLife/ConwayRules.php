<?php
namespace GameOfLife;

use GameOfLife\Universe;

/**
 * Class ComwayRules
 *
 * Implementation of Comway's rules
 * @package GameOfLife
 */
class ConwayRules implements Rules
{
    public function cellNextState($alive, $neighbors)
    {
        if ($alive)
            return ($neighbors >= 2 && $neighbors <= 3);
        else
            return ($neighbors === 3);
    }

    public function nextEra(Universe $currentUniverse)
    {
        $newUniverse = new Universe();

        foreach ($currentUniverse->getAllCoordinates() as $coordinates) {

            if ($currentUniverse->hasObject($coordinates))
                $alive = $currentUniverse->getObject($coordinates)->isAlive();
            else 
                $alive = false;

            $neighborsCount = $currentUniverse->getNeighborsCount($coordinates);

            if ($this->cellNextState($alive, $neighborsCount)) {
                $cell = new Cell();
                $newUniverse->addObject($coordinates, $cell);
            }
        }

        return $newUniverse;
    }
}
