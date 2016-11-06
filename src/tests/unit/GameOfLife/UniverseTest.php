<?php
namespace GameOfLife\Test;

use GameOfLife\Universe;

class UniverseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider coordinatesDataProvider
     */
    public function testObjectsCoordinates($coordinates)
    {
        $universe = new Universe();
        
        foreach ($coordinates as $coordinate) {
            $universe->addObject($coordinate, new \stdClass());
        }

        $result = $universe->getObjectsCoordinates();
        $this->assertEquals($coordinates, $result);
    }

    /**
     * @dataProvider coordinatesDataProvider
     */
    public function testHashForCoordinates($coordinates)
    {
        $universe = new Universe();
        foreach ($coordinates as $coordinate) {
            $hash = $this->invokeMethod($universe, 'getHash', array($coordinate));
            $result = $this->invokeMethod($universe, 'decodeHash', array($hash));
            $this->assertEquals($coordinate, $result);
        }

    }

    public function coordinatesDataProvider()
    {
        $coordinates = [
            [ [[1],[-10],[100]] ], //Universe 1
            [ [[1, 2],[-10, -20],[100, 200]] ], // Universe 2
            [ [[1,2,3],[-10,-20, -30],[100, 200, 300]] ], // Universe 3
        ];
        return $coordinates;
    }

    /**
     * @dataProvider coordinatesInAreaDataProvider
     */
    public function testIsCoordinateInArea($coordinate, $firstCorner, $secondCorner, $expectedResult)
    {
        $result = Universe::isCoordinateInArea($coordinate, $firstCorner, $secondCorner);
        $this->assertEquals($result, $expectedResult);
    }

    public function coordinatesInAreaDataProvider()
    {
        $data = [
            [[5], [2], [7], true],
            [[-5], [-2], [-7], true],
            [[1, 1], [0, 0], [2, 2], true],
            [[1, 1], [1, 1], [1, 1], true],
            [[1, 1], [1, 1], [2, 2], true],
            [[1, 1], [2, 2], [0, 0], true],
            [[-2, -2], [-1, -1], [-3, -3], true],
            [[-2, -2, -2], [-1, -1, -1], [-3, -3, -3], true],
            [[0, 0, 0], [-1, -1, -1], [3, 3, 3], true],

            [[1], [2], [3], false],
            [[-1], [-2], [-3], false],
            [[1, 1], [2, 2], [3, 3], false],
            [[-1, -1], [-2, -4], [-3, -5], false],
            [[5, 0, 2], [-1, -1, -2], [3, 3, 1], false],

        ];
        return $data;
    }

    /**
     * @dataProvider neighborhoodCoordinatesDataProvider
     */
    public function testNeighborhoodCoordinates($coordinate, $expectedNeighborhood)
    {
        $neighborhood = Universe::getNeighborhoodCoordinates($coordinate);
        $test = Universe::compareTwoCoordinatesArrays($neighborhood, $expectedNeighborhood);

        $this->assertTrue($test);
    }


    public function neighborhoodCoordinatesDataProvider()
    {
        $data = [
            [ // One-dimension neighborhood
            // Object coordinates:
                [7]

            ,[ // Neighborhood coordinates:
                [6], /* @ */ [8],
            ]],

            // Two-dimension neighborhood
            [ // Object coordinates:
                [1, 1]

            ,[ // Neighborhood coordinates:
                [0, 0], [0, 1], [0, 2],
                [1, 0], /* @ */ [1, 2],
                [2, 0], [2, 1], [2, 2],
            ]],

             // Three-dimension neighborhood
            [ // Object coordinates:
                [1, 1, 1]

            ,[ // Neighborhood coordinates:
                [0, 0, 0], [0, 0, 1], [0, 0, 2],
                [0, 1, 0], [0, 1, 1], [0, 1, 2],
                [0, 2, 0], [0, 2, 1], [0, 2, 2],
                
                [1, 0, 0], [1, 0, 1], [1, 0, 2],
                [1, 1, 0], /* @@@@ */ [1, 1, 2],
                [1, 2, 0], [1, 2, 1], [1, 2, 2],

                [2, 0, 0], [2, 0, 1], [2, 0, 2],
                [2, 1, 0], [2, 1, 1], [2, 1, 2],
                [2, 2, 0], [2, 2, 1], [2, 2, 2],
            ]],
        ];

        return $data;
    }

    /**
     * @dataProvider objectsDataProvider
     */
    public function testNeighborsCount($coordinates, $allObjectsCoordinates, $expectedNeighborhoodCount)
    {
        $universe = new Universe();
        foreach ($allObjectsCoordinates as $objectCoordinates)
            $universe->addObject($objectCoordinates, new \stdClass());

        $result = $universe->getNeighborsCount($coordinates);
        $this->assertEquals($result, $expectedNeighborhoodCount);

    }

    public function objectsDataProvider()
    {
        $data = [
            [ // 1D universe
                 // Coordinates:
                [1]
                ,[ // Objects:
                    [-1], [0], [5], [1] 
                ], 
                // Expected neighbors count:
                1
            ],

            [ // 2D universe
                 // Coordinates:
                [1, 1]
                ,[ // Objects:
                    [0, 1], [0, 2], [5,5] 
                ], 
                // Expected neighbors count:
                2
            ],

            [ // 3D universe
                 // Coordinates:
                [1, 1, 1]
                ,[ // Objects:
                    [1, 1, 1], [1, 0, 1], [1, 2, 1], [1, 0, 2], [1, 5, 5], [0, 0]
                ], 
                // Expected neighbors count:
                3
            ],

        ];

        return $data;
    }

    
    /**
     * Invoke private/protected methods
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }


}