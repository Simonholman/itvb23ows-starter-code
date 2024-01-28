<?php

use PHPUnit\Framework\TestCase;
require_once 'util.php';

class UnitTest extends TestCase
{
    public function testIsValidMove()
    {
        // Tests for dropdown bug
        $board = ["0,0"=>[[0,"Q"]],"0,1"=>[[1,"Q"]],"0,-1"=>[[0,"B"]]];
        $player = 1;
        $hand = [["Q"=>0,"B"=>1,"S"=>2,"A"=>3,"G"=>3],["Q"=>0,"B"=>2,"S"=>2,"A"=>3,"G"=>3]];
        $to = ["0,0", "0,2", "2,2"];

        $this->assertEquals(true, isInvalidPlay($player, $board, $hand[$player], $to[0]));
        $this->assertEquals(false, isInvalidPlay($player, $board, $hand[$player], $to[1]));
        $this->assertEquals(true, isInvalidPlay($player, $board, $hand[$player], $to[2]));
    }
}
