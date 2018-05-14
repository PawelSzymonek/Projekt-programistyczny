<?php

if(!isset($_SESSION['logged'])){
    die("Musisz się zalogować!");
}

$um = new GameManager;
$res = $um->checkWorkStatus($_SESSION['uid']);

ModuleLoader::load('open');

echo '<div id="wrapper">';

ModuleLoader::load('navbar');

ModuleLoader::load('sklep');

ModuleLoader::load('footer');

echo '</div>';

ModuleLoader::load('js');

echo '</body></html>';

?>