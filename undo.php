<?php
require_once 'database.php';

session_start();

$db = getDatabase();
$stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
setState($result[6]);
header('Location: index.php');
