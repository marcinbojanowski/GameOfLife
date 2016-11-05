<?php
namespace GameOfLife\Test;

use GameOfLife\ConwayRules;
use GameOfLife\Universe;
use GameOfLife\Cell;

class ConwayRulesTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->rules = new ConwayRules;
    }

    /**
     * @dataProvider cellDataProvider
     */
    public function testRules($alive, $neighboors, $expectedResult)
    {
        $result = $this->rules->cellNextState($alive, $neighboors);
        $this->assertEquals($result, $expectedResult);
    }

    public function cellDataProvider()
    {
        $data = [
            [0, 0, 0],
            [0, 1, 0],
            [0, 2, 0],
            [0, 3, 1],
            [0, 4, 0],
            [0, 5, 0],
            [0, 6, 0],
            [0, 7, 0],
            [0, 8, 0],

            [1, 0, 0],
            [1, 1, 0],
            [1, 2, 1],
            [1, 3, 1],
            [1, 4, 0],
            [1, 5, 0],
            [1, 6, 0],
            [1, 7, 0],
            [1, 8, 0],
        ];

        return $data;
    }

    /**
     * @dataProvider eraDataProvider
     */
    public function testNextEra($inputCoordinates, $expectedCoordinates)
    {
        $inputUniverse = new Universe();
        foreach ($inputCoordinates as $coordinates)
            $inputUniverse->addObject($coordinates, new Cell());

        $result = $this->rules->nextEra($inputUniverse);

        $outputCoordinates = $result->getObjectsCoordinates();

        $test = Universe::compareTwoCoordinatesArrays($outputCoordinates, $expectedCoordinates);

        $this->assertTrue($test);
    }

    public function eraDataProvider()
    {
        $data = [
            [ // Blinker Universe
                [ // Input coordinates:
                    [1, 0], [1, 1], [1, 2]
                ],[ // Expected coordinates:
                    [0, 1], [1, 1], [2, 1]
                ]
            ],
            [ // Block Universe
                [ // Input coordinates:
                    [2, 2], [2, 3], [3, 2], [3, 3]
                ],[ // Expected coordinates:
                    [2, 2], [2, 3], [3, 2], [3, 3]
                ]
            ],
            [ // Lonely Cell Universe
                [ // Input coordinates:
                    [2, 2]
                ],[ // Expected coordinates:
                    
                ]
            ],
        ];
        return $data;
    }

}