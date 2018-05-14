<?php

if(isset($_SESSION['logged'])) {
    header("Location: statystyki");
}

ModuleLoader::load('open');

echo '<div id="wrapper">';

ModuleLoader::load('navbar');

ModuleLoader::load('home');



ModuleLoader::load('rejestracja');

ModuleLoader::load('logowanie');

echo ' </div></section>';

ModuleLoader::load('footer');

echo '</div>';

ModuleLoader::load('js');

echo '</body></html>';

?>