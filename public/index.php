<?php
// récupérez l’URI pour App.php
// echo '<pre>';
//     var_dump($_SERVER);
// echo '</pre>';
use core\App;
require_once '../vendor/autoload.php';
$app= new App();
$app->run();

