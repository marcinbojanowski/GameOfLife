<?php
namespace GameOfLife;

use GameOfLife\Universe;
use GameOfLife\Rules;

/**
 * Class Game
 * 
 * Game object is responsible for applying game rules for specified number of iterations
 * in predefined universe. Also allows to import universe from file or generate random one.
 * @package GameOfLife
 */
class Game
{
    private $universe;
    private $rules;
    private $iteration;
    private $iterationLimit;

    public function __construct()
    {
        $this->iteration = 0;
        $this->iterationLimit = 100;
        $this->universe =  new Universe();
    }

    public function setRules(Rules $rules)
    {
        $this->rules = $rules;  
    }

    public function setIterationLimit($limit)
    {
        $this->iterationLimit = $limit;
        return $this;
    }

    public function getIteration()
    {
        return $this->iteration;
    }

    public function tick()
    {
        if ($this->iteration >= $this->iterationLimit)
            return false;

        if (!isset($this->rules))
            return false;

        $this->universe = $this->rules->nextEra($this->universe);
        $this->iteration++;

        return true;
    }

    public function getUniverse()
    {
        return $this->universe;
    }

    public function genRandomUniverse($firstCorner, $secondCorner, $seed)
    {
        mt_srand($seed);

        $percent = mt_rand(10, 90); //cell count - from 10% to 90% of available area
        $this->universe = new Universe();

        $fields = 1;
        for ($i = 0; $i < count($firstCorner); $i++) {
            $fields *= max($firstCorner[$i], $secondCorner[$i]) - min($firstCorner[$i], $secondCorner[$i]);
        }
        $cellNumber = ceil($fields * $percent / 100);
        $newCells = 0;
       
        do {
            $coordinate = array();
            for ($i = 0; $i < count($firstCorner); $i++) {
                $coordinate[$i] = mt_rand(
                    min($firstCorner[$i], $secondCorner[$i]), 
                    max($firstCorner[$i], $secondCorner[$i])
                );
            }
            if (!$this->universe->hasObject($coordinate)) {
                $this->universe->addObject($coordinate, new Cell());
                $newCells++;
            }
        } while ($newCells < $cellNumber);

        return $this;
    }

    public function loadUniverseFromFile($filename)
    {
        $this->universe = new Universe();

        $handle = @fopen($filename, "r");
        $y = 0;
        while (($buffer = fgets($handle)) !== false) {
            $x = 0;
            foreach (str_split($buffer) as $char) {
                if ($char === '1' || $char === '#') 
                    $this->universe->addObject(array($y, $x), new Cell());
                $x++;
            }

            $y++;    
        }
        fclose($handle);

        return $this;
    }
  
}
