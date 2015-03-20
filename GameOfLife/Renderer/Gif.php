<?php
namespace GameOfLife;

/**
 * Class Renderer_Gif
 *
 * Render game result as animated gif
 * @package GameOfLife
 */
class Renderer_Gif implements Renderer
{
    const CELL_SIZE = 10;
    const CELL_MARGIN = 1;
    const TOP_MARGIN = 20;
    const SPEED = 20;

    private $topLeft;
    private $bottomRight;

    public function setViewPort($topLeft, $bottomRight)
    {
        $this->topLeft = $topLeft;
        $this->bottomRight = $bottomRight;
        return $this;
    }

    /**
     * @param Game $game
     * @param mixed $maxRowLimit
     * @param mixed $maxColumnLimit
     * @return Grid
     */
    public function renderIteration(Game $game, $topLeft, $bottomRight )
    {
        $cellSize = self::CELL_SIZE;
        $cellMargin = self::CELL_MARGIN;
        $topMargin = self::TOP_MARGIN;
        
        $width = ($cellSize + 2 * $cellMargin) * (1 + $bottomRight[1] - $topLeft[1]);
        $height = ($cellSize + 2 * $cellMargin) * (1 + $bottomRight[0] - $topLeft[0]) + $topMargin;

        $img = imagecreate($width, $height);
        
        $background = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        
        imagefill($img, 0, 0, $background);
        
        $infoText = 'Iteration:' . $game->getIteration();
        $infoText .= ' Cells:' . $game->getUniverse()->getObjectsCount();
        imagestring($img, 3, 10, 0, $infoText , $black);

        $objectsCoordinates = $game->getUniverse()->getObjectsCoordinatesBetween($topLeft, $bottomRight);
        foreach ($objectsCoordinates as $coordinates) {
            imagefilledrectangle($img, 
                ($coordinates[1] - $topLeft[1]) * ($cellSize + 2 * $cellMargin) + $cellMargin,
                $topMargin + ($coordinates[0] - $topLeft[0]) * ($cellSize + 2 * $cellMargin) + $cellMargin,
                ($coordinates[1] +1 - $topLeft[1]) * ($cellSize + 2 * $cellMargin) - $cellMargin,
                $topMargin + ($coordinates[0] + 1 - $topLeft[0]) * ($cellSize + 2 * $cellMargin) - $cellMargin,
                $black
            );
        }

        ob_start();
        imagegif($img);
        $output = ob_get_contents();
        ob_end_clean();
        imagedestroy($img);

        return $output;
    }

    /**
     * @inheritdoc
     */
    public function render(Game $game)
    {
        if (is_null($this->topLeft) || is_null($this->bottomRight))
            throw new Exception('You must define viewport');

        $gifs = array();
        do {
            $gifs[] = $this->renderIteration($game, $this->topLeft, $this->bottomRight);
        } while ($game->tick());

        $encoder = new \GIFEncoder(
            $gifs, // frames array
            self::SPEED, // speed
            false, // loops
            0, // ?
            -1, -1, -1, // rgb transparency
            0, // ?
            'bin' // source type
        );

        $animation = $encoder->GetAnimation();
        
        return $animation;
    }

}
