<?php
// Renderuje jedną stronę do stdout (CLI). Użycie: php render.php index.php [id=5]
// Każda strona w osobnym procesie, bo strony definiują funkcje i stałe.
chdir(dirname(__DIR__));
$strona = $argv[1];
foreach (array_slice($argv, 2) as $para) {
    [$k, $v] = explode('=', $para, 2);
    $_GET[$k] = $v;
}
$_SERVER['REQUEST_METHOD'] = 'GET';
require getcwd() . '/' . $strona;
