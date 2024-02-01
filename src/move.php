<?php
namespace move;
require_once 'util.php';
require_once 'database.php';
session_start();

$from = $_POST['from'];
$to = $_POST['to'];

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];
unset($_SESSION['error']);

if (isImpossibleMove($player, $board, $hand, $from)) {
    $_SESSION['error'] = 'Impossible move';
}
else {
    $tile = array_pop($board[$from]);
    $invalid = isInvalidMove($player, $board, $from, $to, $tile);
    if ($invalid) {
        $_SESSION['error'] = 'Invalid move';
        if (isset($board[$from])) {
            array_push($board[$from], $tile);
        }
        else {
            $board[$from] = [$tile];
        }
    } else {
        if (isset($board[$to])) {
            array_push($board[$to], $tile);
        }
        else {
            $board[$to] = [$tile];
        }
        $_SESSION['player'] = 1 - $_SESSION['player'];

        $db = getDatabase();
        $stmt = $db->prepare(
            'insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "move", ?, ?, ?, ?)'
        );
        $stmt->bind_param('issis', $_SESSION['game_id'], $from, $to, $_SESSION['last_move'], getState());
        $stmt->execute();
        $_SESSION['last_move'] = $db->insert_id;
    }
    $_SESSION['board'] = $board;
}

header('Location: index.php');
