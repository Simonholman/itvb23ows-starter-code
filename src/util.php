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

function isInvalidPlay($player, $board, $hand, $to, $piece=false) {
    $invalid = !empty($board[$to]) ||
        count($board) && !hasNeighBour($to, $board) ||
        array_sum($hand) < 11 && !neighboursAreSameColor($player, $to, $board);
    if (!$invalid && $piece) $invalid = array_sum($hand) <= 8 && $hand['Q'] && $piece != 'Q';
    return $invalid;
}

function isInvalidMove($player, $board, $from, $to, $tile) {
    if (!hasNeighBour($to, $board)) {
        return true;
    }
    else {
        $hiveCounter = new HiveCounter($board);
        if (!$hiveCounter->isOneHive($tile, $to)) {
            return true;
        } else {
            if ($from == $to) {
                return true;
            }
            elseif (!empty($board[$to]) && $tile[1] != "B") {
                return true;
            }
            elseif ($tile[1] == "G") {
                return !grasshopper($from, $to, $board);
            }
            elseif ($tile[1] == "A") {
                return true;
            }
            elseif ($tile[1] == "S") {
                return true;
            }
            return !isNeighbour($from, $to);
        }
    }
}

function isImpossibleMove($player, $board, $hand, $from) {
    return empty($board[$from]) ||
        $board[$from][count($board[$from])-1][0] != $player ||
        $hand['Q'];
}

function grasshopper($from, $to, $board) {
    return true;
}

class HiveCounter {

    private $checked = [];
    private $tiles = [];
    private $board = [];

    public function __construct($board) {
        $this->board = $board;
    }

    private function getNeighbours($tile) {
        $neighbours = [];
        list($x, $y) = explode(',', $tile);
        
        foreach ($GLOBALS['OFFSETS'] as $offset) {
            $neighbours[] = ($x + $offset[0]) . ',' . ($y + $offset[1]);
        }
    
        return $neighbours;
    }
    
    public function isOneHive($tile, $to) {
        $boardCopy = json_decode(json_encode($this->board), true);
    
        if (isset($boardCopy[$to])) {
            array_push($boardCopy[$to], $tile);
        } else {
            $boardCopy[$to] = [$tile];
        }
    
        $totalCount = count(array_filter($boardCopy, function ($tiles) {
            return !empty($tiles);
        }));
        $checked = [];
    
        $hiveTiles = [];
        foreach ($boardCopy as $pos => $tiles) {
            if (!empty($tiles)) $hiveTiles[] = $pos;
        }
    
        $hiveSize = $this->getHiveSize(array_keys($boardCopy)[0], 1, $checked, $hiveTiles);
        return $hiveSize == $totalCount;
    }
    
    private function getHiveSize($tile, $size) {
        foreach ($this->getNeighbours($tile) as $neighbour) {
            if (in_array($neighbour, $this->checked) || !in_array($neighbour, $this->tiles)) continue;
            $_SESSION['debug'][] = $neighbour . ' -> ';
            $checked[] = $neighbour;
            $size += $this->getHiveSize($neighbour, $size);
        }
    
        return $size + 1;
    }
}