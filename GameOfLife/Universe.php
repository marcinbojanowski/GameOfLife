<?php
namespace GameOfLife;

/**
 * Class Universe
 *
 * Multidimendisional Universe. Stores objects in hash table,
 * also uses array that stores cached count of neighbors.
 * Univese can store unlimited number of objects.
 * Each object has his own coordinates, but this information is stored 
 * only in this object (object itself doesnt know where is located)
 * @package GameOfLife
 */
class Universe
{
    private $objects = array();
    private $neighborhood = array();
    const DELIMETER = ',';

    protected function getHash($coordinates)
    {
        $hash = implode(self::DELIMETER, $coordinates);
        return $hash;
    }

    protected function decodeHash($hash)
    {
        $coordinates = explode(self::DELIMETER, $hash);
        $coordinates = array_map('intval', $coordinates);
        return $coordinates;
    }

    public function getAllCoordinates()
    {
        $hashes = array_unique(
            array_merge(
                array_keys($this->neighborhood),
                array_keys($this->objects)
            )
        );
        $coordinates = array_map( array(__NAMESPACE__ . '\Universe', 'decodeHash'), $hashes);
        return $coordinates;
    }

    public function getObjectsCoordinates()
    {
        $hashes = array_unique(array_keys($this->objects));
        $coordinates = array_map( array(__NAMESPACE__ . '\Universe', 'decodeHash'), $hashes);
        return $coordinates;
    }

    public function getObjectsCoordinatesBetween($firstCorner, $secondCorner)
    {
        $coordinates = array_filter($this->getObjectsCoordinates(), 
            function($coordinate) use ($firstCorner, $secondCorner) {
                    $test = $this->isCoordinateInArea($coordinate, $firstCorner, $secondCorner);
                    return $test;
                 }
        );
        return $coordinates;
    }
 
    public function isCoordinateInArea($coordinate, $firstCorner, $secondCorner)
    {
        foreach ($coordinate as $component => $value)
        {
            if( $value < min($firstCorner[$component], $secondCorner[$component]) ||
                $value > max($firstCorner[$component], $secondCorner[$component])
            )
            return false;
        }
        return true;
    }

    /**
    *
    * Doing some math here to get all neighbors coordinates in multidimension universe
    * Counts neighborhood size then for each neighbor calculate separately its coordinate components
    */
    public function getNeighborhoodCoordinates($coordinates)
    {
        $neighborhood = array();
        $offsets = array(-1, 0 , 1);
        $offsetsCount = count($offsets);
        $dimensions = count($coordinates);
        $neighborhoodSize = pow($offsetsCount, $dimensions);

        for ($i = 0; $i < $neighborhoodSize; $i++) {
            $newCoordinate = array();
            foreach ($coordinates as $component => $originalValue)
                $newCoordinate[] = $originalValue + $offsets[($i / pow($offsetsCount, $component)) % $offsetsCount];
            
            if ($newCoordinate != $coordinates)
                $neighborhood[] = $newCoordinate;
        }
        
        return $neighborhood;
    }

    public function addObject($coordinates, $object)
    {
        $hash = $this->getHash($coordinates);
        $this->objects[$hash] = $object;
        $this->incrementNeighborhood($coordinates);
        return $this;
    }

    public function hasObject($coordinates)
    {
        $hash = $this->getHash($coordinates);
        return isset($this->objects[$hash]);
    }

    public function getObject($coordinates)
    {   
        $hash = $this->getHash($coordinates);
        if(isset($this->objects[$hash]))
            return $this->objects[$hash];
        else
            return false;
    }

    public function getObjectsCount()
    {
        $count = count($this->objects);
        return $count;
    }

    public function getAllObjects()
    {
        return $this->objects;
    }

    private function incrementNeighborhood($coordinates)
    {
        foreach ($this->getNeighborhoodCoordinates($coordinates) as $neighborCoordinates)
        {
            $hash = $this->getHash($neighborCoordinates);
            if (isset($this->neighborhood[$hash]))
                $this->neighborhood[$hash]++;
            else
                $this->neighborhood[$hash] = 1;
        }
        return $this;
    }

    public function getNeighborsCount($coordinates)
    {
        $hash = $this->getHash($coordinates);
        if (isset($this->neighborhood[$hash]))
            return $this->neighborhood[$hash];
            
        return 0;
    }

    public function compareTwoCoordinatesArrays($first, $second)
    {
        if (count($first) !== count($second))
            return false;

        foreach ($first as $coordinate)
            if (!in_array($coordinate, $second))
                return false;

        return true;
    }

}
