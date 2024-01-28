<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b) {
    $a = explode(',', $a);
    $b = explode(',', $b);
    return
        $a[0] == $b[0] && abs($a[1] - $b[1]) == 1 ||
        $a[1] == $b[1] && abs($a[0] - $b[0]) == 1 ||
        $a[0] + $a[1] == $b[0] + $b[1];
}

function hasNeighBour($a, $board) {
    foreach (array_keys($board) as $b) {
        if (isNeighbour($a, $b)) {
            return true;
        }
    }
}

function neighboursAreSameColor($player, $a, $board) {
    foreach ($board as $b => $st) {
        if (!$st) {
            continue;
        }
        $c = $st[count($st) - 1][0];
        if ($c != $player && isNeighbour($a, $b)) {
            return false;
        }
    }
    return true;
}

function len($tile) {
    return $tile ? count($tile) : 0;
}

function slide($board, $from, $to) {
    if (!hasNeighBour($to, $board) || !isNeighbour($from, $to)) {
        return false;
    }
    $b = explode(',', $to);
    $common = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        if (isNeighbour($from, $p.",".$q)) {
            $common[] = $p.",".$q;
        }
    }
    if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) {
        return false;
    }
    return min(len($board[$common[0]]), len($board[$common[1]])) <= max(len($board[$from]), len($board[$to]));
}

function isInvalidPlay($player, $board, $hand, $to, $piece=false) {
    $invalid = isset($board[$to]) ||
        count($board) && !hasNeighBour($to, $board) ||
        array_sum($hand) < 11 && !neighboursAreSameColor($player, $to, $board);
    if (!$invalid && $piece) $invalid = array_sum($hand) <= 8 && $hand['Q'] && $piece != 'Q';
    return $invalid;
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

function isImpossibleMove($player, $board, $hand, $from) {
    return !isset($board[$from]) ||
        $board[$from][count($board[$from])-1][0] != $player ||
        $hand['Q'];
}