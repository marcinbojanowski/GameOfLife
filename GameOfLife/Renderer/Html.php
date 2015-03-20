<?php
namespace GameOfLife;

/**
 * Class Renderer_Html
 *
 * Render game result as set of html divs
 * @package GameOfLife
 */
class Renderer_Html implements Renderer
{
    const FULL_BLOCK = '&#9608;';
    const EMPTY_BLOCK = '&#9617;';
    
    private $topLeft;
    private $bottomRight;

    public function setViewPort($topLeft, $bottomRight)
    {
        $this->topLeft = $topLeft;
        $this->bottomRight = $bottomRight;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function render(Game $game)
    {
        if (is_null($this->topLeft) || is_null($this->bottomRight))
            throw new Exception('You must define viewport');

        $output = '';
        do {
            $output .= $this->renderIteration($game, $this->topLeft, $this->bottomRight);
        } while ($game->tick());

        return $output;
    }

    public function renderIteration(Game $game, $topLeft, $bottomRight )
    {
        $output = '<div style="line-height:100%; letter-spacing:0px;">';
        $output .= 'Iteration: ' . $game->getIteration() . ' ';
        $output .= 'Cells: ' . $game->getUniverse()->getObjectsCount() . ' ';
        $output .= '<br>';
        for ($y = $topLeft[0]; $y <= $bottomRight[0]; $y++) {
            for ($x = $topLeft[1]; $x <= $bottomRight[1]; $x++) {
                if ($game->getUniverse()->hasObject(array($y, $x)))
                    $output .= self::FULL_BLOCK;
                else
                    $output .= self::EMPTY_BLOCK;
            }
            $output .= '<br>';
        }
        $output .= '</div>';
        return $output;
    }

}
