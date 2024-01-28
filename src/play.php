<?php
require_once 'util.php';
require_once 'database.php';
session_start();

$piece = $_POST['piece'];
$to = $_POST['to'];

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

function canPlay($player, $board, $hand, $piece, $to) {
    return !(!$hand[$piece] ||
        isInvalidPlay($player, $board, $hand, $to, $piece));
}

if (!canPlay($player, $board, $hand, $piece, $to)) {
    $_SESSION['error'] = 'Invalid play';
} else {
    $_SESSION['board'][$to] = [[$_SESSION['player'], $piece]];
    $_SESSION['hand'][$player][$piece]--;
    $_SESSION['player'] = 1 - $_SESSION['player'];
    $db = getDatabase();
    $stmt = $db->prepare(
        'insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "play", ?, ?, ?, ?)'
    );
    $stmt->bind_param('issis', $_SESSION['game_id'], $piece, $to, $_SESSION['last_move'], getState());
    $stmt->execute();
    $_SESSION['last_move'] = $db->insert_id;
}

header('Location: index.php');
