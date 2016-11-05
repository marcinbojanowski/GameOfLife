<?php

if (!isset($_GET['type'])) {
    header('Location: index.php');
    exit();
}

$url = array();
$url['type'] = $_GET['type'];

if ($url['type'] == 'random') {
    if (isset($_GET['seed']))
        $url['seed'] = $_GET['seed'];
    else 
        $url['seed'] = mt_rand();
}
?>
<html>
    <head>
        <title>Game of Life</title>
    </head>
    <body>
        <a href="index.php">Go back</a><br>
        <div id="message">Generating...<br></div>
        <img src="gif.php?<?php echo http_build_query($url)?>" alt="Universe" onload="document.getElementById('message').style.display='none';"/>
    </body>
</html>