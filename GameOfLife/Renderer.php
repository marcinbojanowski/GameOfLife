<?php
namespace GameOfLife;

/**
 * Interface Renderer
 *
 * Renderer is responsible for rendering game results
 * @package GameOfLife
 */
interface Renderer
{
    public function render(Game $game);
}
