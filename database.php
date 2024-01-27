<?php
require_once 'database.php';

function getState() {
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

function getDatabase() {
    return new \mysqli('mysql-db', 'root', 'very-secret-password7849357893487953', 'hive');
}
