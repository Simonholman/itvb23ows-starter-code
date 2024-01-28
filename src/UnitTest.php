<?php

use PHPUnit\Framework\TestCase;
require_once 'util.php';

class UnitTest extends TestCase
{
    public function testIsValidMove()
    {
        // Tests for bug 1-dropdown
        $board = ["0,0"=>[[0,"Q"]],"0,1"=>[[1,"Q"]],"0,-1"=>[[0,"B"]]];
        $player = 1;
        $hand = [["Q"=>0,"B"=>1,"S"=>2,"A"=>3,"G"=>3],["Q"=>0,"B"=>2,"S"=>2,"A"=>3,"G"=>3]];
        $to = ["0,0", "0,2", "2,2"];

        $this->assertTrue(isInvalidPlay($player, $board, $hand[$player], $to[0]));
        $this->assertFalse(isInvalidPlay($player, $board, $hand[$player], $to[1]));
        $this->assertTrue(isInvalidPlay($player, $board, $hand[$player], $to[2]));
    }

    public function testQueenMove()
    {
        // Tests for 2-bijenkoningin-move
        $board = ["0,0"=>[[0,"Q"]],"1,0"=>[[1,"Q"]]];
        $player = 0;
        $hand = [["Q"=>0,"B"=>2,"S"=>2,"A"=>3,"G"=>3],["Q"=>0,"B"=>2,"S"=>2,"A"=>3,"G"=>3]];
        $from = "0,0";
        $to = "0,1";
        $tile = array_pop($board[$from]);

        $this->assertFalse(isInvalidMove($player, $board, $hand[$player], $from, $to, $tile));
    }

    public function testFourthMoveInvalid()
    {
        // Tests for 3-vierde-zet-invalid
        $board = ["0,0"=>[[0,"B"]],"0,1"=>[[1,"Q"]],"0,-1"=>[[0,"B"]],"0,2"=>[[1,"B"]],"0,-2"=>[[0,"S"]],"0,3"=>[[1,"B"]]];
        $player = 0;
        $hand = [["Q"=>1,"B"=>0,"S"=>1,"A"=>3,"G"=>3],["Q"=>0,"B"=>0,"S"=>2,"A"=>3,"G"=>3]];
        $to = "0,-3";

        $this->assertFalse(isInvalidPlay($player, $board, $hand[$player], $to, "Q"));
        $this->assertTrue(isInvalidPlay($player, $board, $hand[$player], $to, "S"));
        $this->assertTrue(isInvalidPlay($player, $board, $hand[$player], $to, "A"));
        $this->assertTrue(isInvalidPlay($player, $board, $hand[$player], $to, "G"));
    }
}
