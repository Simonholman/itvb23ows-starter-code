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
        $tile = $board[$from][len($board[$from])-1];

        $this->assertFalse(isInvalidMove($player, $board, $from, $to, $tile));
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

    public function testMoveToUsedTiles() {
        $board = ["0,0"=>[],"0,1"=>[[1,"Q"],[1,"B"]],"0,-1"=>[[0,"B"]],"0,2"=>[],"0,-2"=>[[0,"B"]],"0,3"=>[[1,"B"]],"0,4"=>[[0,"Q"]]];
        $player = 1;
        $hand = [["Q"=>0,"B"=>0,"S"=>2,"A"=>3,"G"=>3],["Q"=>0,"B"=>0,"S"=>2,"A"=>3,"G"=>3]];
        $to = "0,2";

        $this->assertFalse(isInvalidPlay($player, $board, $hand[$player], $to, 'S'));
    }

    public function testCanPass() {
        $board = [
            "0,0" => [[0, "Q"]],
            "0,1" => [[1, 'A']],
            "0,-1" => [[1, 'A']],
            "1,0" => [[1, 'A']],
            "-1,0" => [[1, 'A']],
            "-1,1" => [[1, 'A']]
        ];
        $hand = [0 => [], 1=>[]];
        $player = 0;

        $this->assertTrue(canPass($player, $board, $hand));

        $board = [
            "0,0" => [[0, 'Q']],
            "0,1" => [[1, 'A']],
            "0,-1" => [[1, 'A']],
            "-1,0" => [[1, 'A']],
            "-1,1" => [[1, 'A']]
        ];
        $hand = [
            0 => ["Q" => 0,"B" => 0, "S" => 0, "A" => 0, "G" => 0],
            1 => ["Q" => 0, "B" => 0, "S" => 0, "A" => 0, "G" => 0]
        ];
        $player = 0;

        $this->assertFalse(canPass($player, $board, $hand));

        $board = [
            "0,0" => [[0, 'Q']],
            "0,1" => [[1, 'A']],
            "0,-1" => [[1, 'A']],
            "-1,0" => [[1, 'A']],
            "-1,1" => [[1, 'A']]
        ];
        $hand = [
            0 => ["Q" => 0,"B" => 0, "S" => 1, "A" => 0, "G" => 0],
            1 => ["Q" => 0, "B" => 0, "S" => 0, "A" => 0, "G" => 0]
        ];
        $player = 0;

        $this->assertFalse(canPass($player, $board, $hand));
    }

    public function testGrasshopper() {
        $player = 0;
        $from = "0,0";

        $board = [
            "0,0" => [[0,"G"]],
            "0,1" => [[0,"B"]],
            "0,2" => [[1,"Q"]],
        ];

        // d. Een sprinkhaan mag niet naar een bezet veld springen.
        $this->assertTrue(isInvalidMove($player, $board, $from, "0,2", $board[$from][len($board[$from])-1]));

        $board = [
            "0,0" => [[0,"G"]],
            "0,1" => [[0,"B"]],
        ];

        // b. Een sprinkhaan mag zich niet verplaatsen naar het veld waar hij al staat.
        $this->assertTrue(isInvalidMove($player, $board, $from, "0,0", $board[$from][len($board[$from])-1]));
        
        // a. Een sprinkhaan verplaatst zich door in een rechte lijn een sprong te maken naar een veld meteen achter een andere steen in de richting van de sprong.
        $this->assertFalse(isInvalidMove($player, $board, $from, "0,2", $board[$from][len($board[$from])-1]));

        // c. Een sprinkhaan moet over minimaal één steen springen.
        $this->assertTrue(isInvalidMove($player, $board, $from, "-1,0", $board[$from][len($board[$from])-1]));

        $board = [
            "0,0" => [[0,"G"]],
            "0,2" => [[1,"Q"]],
        ];

        // e. Een sprinkhaan mag niet over lege velden springen. Dit betekent dat alle velden tussen de start- en eindpositie bezet moeten zijn.
        $this->assertTrue(isInvalidMove($player, $board, $from, "0,3", $board[$from][len($board[$from])-1]));
    }
}
