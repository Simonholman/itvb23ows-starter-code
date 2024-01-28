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

function isImpossibleMove($player, $board, $hand, $from) {
    return !isset($board[$from]) ||
        $board[$from][count($board[$from])-1][0] != $player ||
        $hand['Q'];
}

function isInvalidMove($player, $board, $hand, $from, $to, $tile) {
    if (!hasNeighBour($to, $board)) {
        return true;
    }
    else {
        $all = array_keys($board);
        $queue = [array_shift($all)];
        while ($queue) {
            $next = explode(',', array_shift($queue));
            foreach ($GLOBALS['OFFSETS'] as $pq) {
                list($p, $q) = $pq;
                $p += $next[0];
                $q += $next[1];
                if (in_array("$p,$q", $all)) {
                    $queue[] = "$p,$q";
                    $all = array_diff($all, ["$p,$q"]);
                }
            }
        }
        if ($all) {
            return true;
        } else {
            if ($from == $to) {
                return true;
            }
            elseif (isset($board[$to]) && $tile[1] != "B") {
                return true;
            }
            elseif ($tile[1] != "Q" && $tile[1] != "B") {
                if (!slide($board, $from, $to)) {
                    return true;
                }
            }
        }
    }
    return false;
}

if (isImpossibleMove($player, $board, $hand, $from)) {
    $_SESSION['error'] = 'Impossible move';
}
else {
    $tile = array_pop($board[$from]);
    $invalid = isInvalidMove($player, $board, $hand, $from, $to, $tile);
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
