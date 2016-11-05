<?php

namespace GameOfLife;

header('Content-type: image/gif');

require_once('bootstrap.php');

if (isset($_GET['type'])) {

    $game = new Game();
    $game->setIterationLimit(300);
    $game->setRules(new ConwayRules());
    
    switch ($_GET['type']) {
        case 'gun':
            $topLeft = array(0, 0);
            $bottomRight = array(37, 37);
            $game->loadUniverseFromFile('data/gun.txt');
            break;

        case 'smile':
            $topLeft = array(-7,-7);
            $bottomRight = array(12,12);
            $game->loadUniverseFromFile('data/smile.txt');
            break;

        default:
            if (isset($_GET['seed']))
                $seed = $_GET['seed'];
            else 
                $seed = mt_rand();

            $topLeft = array(0, 0);
            $bottomRight = array(37, 37);
            $game->genRandomUniverse($topLeft, $bottomRight, $seed);
    }
    
    $renderer = new Renderer_Gif();
    $renderer->setViewPort($topLeft, $bottomRight);
    
    echo $renderer->render($game);
}
else
{
    $img = imagecreate(100, 30);
    $bg = imagecolorallocate($img, 255, 255, 255);
    $textcolor = imagecolorallocate($img, 255, 0, 0);
    imagestring($img, 5, 0, 0, 'Error!', $textcolor);
    imagegif($img);
    imagedestroy($img);
}