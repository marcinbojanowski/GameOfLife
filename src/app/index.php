<?php

namespace GameOfLife;

require_once('bootstrap.php');

use GameOfLife\Game;
use GameOfLife\ConwayRules;
use GameOfLife\Renderer_Html;

if (!extension_loaded('gd') || !function_exists('gd_info'))
    die('Script requires GD library');

$topLeft = array(0, 0);
$bottomRight = array(37, 37);

$renderer = new Renderer_Html();
$renderer->setViewPort($topLeft, $bottomRight);

$rules = new ConwayRules();
$seed = mt_rand();

$gameRandom = new Game();
$gameRandom->setRules($rules);
$gameRandom->genRandomUniverse($topLeft, $bottomRight, $seed);
$gameRandom->setIterationLimit(0);

$gameGun = new Game();
$gameGun->setRules($rules);
$gameGun->loadUniverseFromFile('data/gun.txt');
$gameGun->setIterationLimit(0);

?>
<html>
    <head>
        <title>Game of Life</title>
    </head>
    <body>
        <div style='float:left; cursor:pointer' onClick="document.location='universe.php?type=random&seed=<?php echo $seed?>';">
            <strong>Random Universe</strong><br>
            <?php echo $renderer->render($gameRandom); ?>
        </div>

        <div style='float:left; margin:20px; text-align:center'>
            <h1>Game of life</h1>
            <h2>Pick the universe</h2>
            &lt;--- OR --&gt;
            <br><br>
            Author:<br>
            Marcin Bojanowski <span onClick="document.location='universe.php?type=smile';">:)</span>
        </div>

        <div style='float:left; cursor:pointer' onClick="document.location='universe.php?type=gun';">
            <strong>Gosper's Glider Gun</strong><br>
            <?php echo $renderer->render($gameGun); ?>
        </div>
    </body>
</html>