<?php

namespace JAS;

//require('Tools.php');
//use DWA\Tools;

class Scrabble {

    /* Properties */

    private $board;
    private $tiles;

    /*
     * Constructor
     */
    public function __construct() {
        // Create a table of the alphabet tiles
        // Define the alphabet tiles with their score
        $tilesJson = file_get_contents('tiles.json');
        $this->tiles = json_decode($tilesJson, $assoc=true);
        //Tools::dump($this->tiles);
        // scrabble board is 15x15 - start at upper left (0,0)
        // Define the board as a 2D string array
        // the string value indicates the following
        // '' = emply
        // 'DL' = double letter score,
        // 'DW' = double word score, etc
        $boardJson = file_get_contents('boardDefinition.json');
        $this->board = json_decode($boardJson, $assoc=true);
         //$boardLayoutHtml = $this->boardSetup($board);
    }

    // move the word around the board to find the max score
    // return the score and the position on the board (in an array)
    public function getMaxScore($word, $bingo = 'no') {
        $highScore=[0,0,0, ""];  //word score, x-position, y-position
        $score=$this->getMinScore($word)[0];
        $highScore[0] = $score;
        $wordArray = str_split(strtoupper($word));
        for ($j=0; $j<=count($this->board[0])/2+1; $j++) {
            for ($i=0; $i<=count($this->board[$j])-count($wordArray); $i++) {
                $score = $this->getScoreByPosition($word, $i, $j, false, $bingo);
                if ($score > $highScore[0]) {
                    $highScore[0] = $score;
                    $highScore[1] = $i;
                    $highScore[2] = $j;
                }
            }
            //Tools::dump($highScore);
            //echo "highScore ($highScore[1], $highScore[2]) with score: $highScore[0] <br>";
        }
        if ((strlen($word) == 7) && $bingo == 'yes') {
            $bScore = $highScore[0] + 50;
            $highScore[3] = "  / " . $bScore . " w/Bingo";
        }
        //Tools::dump($highScore);
        return $highScore;
    }

    // get the word base score
    public function getMinScore($word, $bingo = 'no') {
        $wordArray = str_split(strtoupper($word));
        $minScore = [0, 4, 7, ''];
        foreach ($wordArray as $letter => $element) {
            $minScore[0] += $this->tiles[$element];
        }
        if ((strlen($word) == 7) && $bingo == 'yes') {
            $bScore = $minScore[0] + 50;
            $minScore[3] = "  / " . $bScore . " w/Bingo";
        }
        return $minScore;
    }

    // calculates the word score based on the position the scrabble board
    // taking into account the occurence of double, triple, letter & word tiles
    public function getScoreByPosition($word, $x, $y, $vert, $bingo='no') {
        $wordArray = str_split(strtoupper($word));
        $score = 0;
        $bTws = false;
        $bDws = false;
        $h=1;
        $v=0;
        if($vert) {
            $h=0;
            $v=1;
        }
        // calculate the word score based on the position on the board
        for ($j=0; $j<count($wordArray); $j++) {
            // validate the position pos[x,y]

            // calculate the letter score
            switch ($this->board[$x+$j*$h][$y+$j*$v]) {
                case "TWS":
                    $bTws = true;
                    $score += $this->tiles[$wordArray[$j]];
                    break;
                case "DWS":
                    $bDws = true;
                    $score += $this->tiles[$wordArray[$j]];
                    break;
                case "TLS":
                    $score += ($this->tiles[$wordArray[$j]] * 3);
                    break;
                case "DLS":
                    $score += ($this->tiles[$wordArray[$j]] * 2);
                    break;
                default:
                    $score += $this->tiles[$wordArray[$j]];
                    break;
            }
        }
        if ($bTws == true) {
            $score *= 3;
        }
        else if ($bDws == true) {
            $score *= 2;
        }

        if ((strlen($word) == 7) && $bingo == 'yes') {
            $score += 50;
        }
        //echo "calcScoreByPosition with ($x, $y) with score: $score <br>";
        return $score;
        // set the position
    }

    // 0-based check to see if the work will fit ont the board based on the
    // position specified
    public function checkWordPlacement($word, $x, $y, $vertical = false) {
        if ($vertical) {
            if (($y + strlen($word)) >= 15) return false;
            else return true;
        }
        else {
            if (($x + strlen($word)) >= 15) return false;
            else return true;
        }
    }
    // creates the table of tiles for the word that is placed on the board
    // x, y denote the square where the first letter is placed
    // need to translate that into a position on the rendered board
    public function tileSetup($word, $x, $y, $vertical) {
        $wordArray = str_split(strtoupper($word));
        $xy=[$x, $y];
        //if($vertical == true) {
        //    $xy = $this->htov($x, $y);
        //}

        //echo "xy= (" .$xy[0] ."," . $xy[1] .")";
        $tileHtml = '<table id="tileLayout">' . "\r\n";
        $tileHtml .= "<tr>\r\n";
        foreach ($wordArray as $tile => $element) {
            if($element != "") {
                $pos = $this->positionTranslate($xy, $tile, $vertical);
                $tileVal = $this->tiles[$element];
                $tileHtml .= "<td><div id='letterTile' style='position:relative;left:$pos[0];top:$pos[1];'><span>$element</span><span class='number'>$tileVal</span></div></td>\r\n";
                if($vertical==true) {
                    $xy[1]++;
                }
            }
        }
        $tileHtml .= "</tr>\r\n";
        $tileHtml .= "</table>\r\n";
        return $tileHtml;
    }

    // change the horizontal posiiton to the equivalent vertical position on the board
    private function htov($x, $y) {
        return [$y, $x];
    }

    // nasty function that translates the letterTile to a position on the scrabble board
    // really nightmarish to deal with
    // in the x direction it places automatically
    // in the y direction you need to calculate an offset for each letter and also
    // calculate how much to push the square back in the x direction
    // I'm sure there's a better way.  I just don't know it yet.
    private function positionTranslate($xy, $lpos, $vert) {
        if ($vert) {
            $xpos = (5 + ($xy[0]*31) - $lpos*31) ."px";
            $ypos = (-469 + $xy[1]*31) . "px";
        }
        else {
            $xpos = (5 + $xy[0]*31) ."px";
            $ypos = (-469 + $xy[1]*31) . "px";
        }

        //echo "xpos $xpos  ypos $ypos <br>";
        return [$xpos, $ypos];
    }

    // creates the scrabble board as a table in html
    public function getBoardLayout() {
        $tableHtml = "<table id='scrabbleLayout' width='474' height='474'>\r\n";
        for ($i=0; $i<count($this->board); $i++) {
            $tableHtml .= "<tr>\r\n";
            foreach ($this->board[$i] as $square => $element) {
                if($element != "") {
                    $blockText = $this->getBlockText($element);
                    $boardTileClass = strtolower($element);
                    $tableHtml .= "<td><div id='boardTile' class='$boardTileClass'><span>$blockText</span></div></td>\r\n";
                }
                else {
                    $tableHtml .= "<td><div id='boardTile' class='blank'></div></td>\r\n";
                }
            }
            $tableHtml .= "</tr>\r\n";
        }
        $tableHtml .= "</table>\r\n";
        return $tableHtml;
    }

    // helper function that gets the text inside the board squares
    private function getBlockText($element) {
        // calculate the letter score
        switch ($element) {
            case "TWS":
                return "TRIPLE<BR>WORD<BR>SCORE";
            case "DWS":
                return "DOUBLE<BR>WORD<BR>SCORE";
            case "TLS":
                return "TRIPLE<BR>LETTER<BR>SCORE";
            case "DLS":
                return "DOUBLE<BR>LETTER<BR>SCORE";
            default:
                return "";
        }
    }
} # End of Class
