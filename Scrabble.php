<?php

namespace JAS;


class Scrabble {

    /* Properties */

    private $board;
    private $tiles;

    /*
     * Constructor
     */
    public function __construct($tileFile, $boardFile) {

        /* Create a table of the alphabet tiles
         * defining each letter and the corresponding score
         */
        $tilesJson = file_get_contents($tileFile);
        $this->tiles = json_decode($tilesJson, $assoc=true);

        /* scrabble board is 15x15 - Internally start at upper left (0,0)
         * Define the board as a 2D string array
         * the string value indicates the following
         * '' = emply
         * 'DL' = double letter score,
         * 'DW' = double word score, etc
         */
        $boardJson = file_get_contents($boardFile);
        $this->board = json_decode($boardJson, $assoc=true);

    }

    /* move the word around the board to find the max score
     * return the score and the position on the board (in an array)
     * note that horizontal and vertical will return different positions
     * but will have the same score
     * returns array [score, x-pos, y-pos]
     */
    public function getMaxScore($word, $vertical=false) {

        $highScore=[0,0,0];
        $score=$this->getMinScore($word);
        $highScore = $score;
        $wordArray = str_split(strtoupper($word));

        for ($j=0; $j<=count($this->board[0])/2+1; $j++) {
            for ($i=0; $i<=count($this->board[$j])-count($wordArray); $i++) {
                $score = $this->getScoreByPosition($word, $i, $j, $vertical);
                if ($score[0] > $highScore[0]) {
                    $highScore = $score;
                }
            }
        }
        return $highScore;
    }

    // get the word base score and position
    // returns array [score, x-pos, y-pos]
    public function getMinScore($word, $vertical=false) {

        $wordArray = str_split(strtoupper($word));

        $minScore = [0, 4, 7];
        if ($vertical) {
            $minScore = [0, 7, 4];
        }

        foreach ($wordArray as $letter => $element) {
            $minScore[0] += $this->tiles[$element];
        }

        return $minScore;
    }

    // get the word score based on the position the scrabble board
    // taking into account the occurence of double, triple, letter & word tiles
    // returns array [score, xpos, ypos]
    public function getScoreByPosition($word, $x, $y, $vertical=false) {

        $wordArray = str_split(strtoupper($word));
        $score = 0;

        // storage for final word score multipliers
        $bTws = false;
        $bDws = false;

        // use $h and $v values to increment the board array in either a
        // horizontal or vertical direction
        $h = $vertical ? 0 : 1;
        $v = $vertical ? 1 : 0;

        // calculate the word score based on the position on the board
        for ($j=0; $j<count($wordArray); $j++) {
            /* calculate the letter score
             * since horizontal and vertical makes a difference,
             * need to increment the board either horizontally or vertically
             * based on the boolean
             */
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

        // adjust the score using any found word multipliers
        if ($bTws == true) {
            $score *= 3;
        }
        else if ($bDws == true) {
            $score *= 2;
        }
        return [$score, $x, $y];
    }

    // determines if bingo score is applicable and adds to the
    // given score
    public function getBingoScore($word, $score) {
        if (strlen($word) == 7) {
            $bingoScore = $score + 50;
            return  " - w/Bingo: $bingoScore";
        }
        return "";
    }

    // internall the board is at (0,0) so that arrays are indexed
    // correctly. Externally the origin is at (1,1) cause that's
    // what most people understand
    public function convertToHtmlPos($score) {
        $score[2]++;
        $score[1]++;
        return $score;
    }

    /* 0-based check to see if the work will fit onto the board based on the
     * position specified
     * returns Boolean
     */
    public function checkPlace($word, $x, $y, $vertical = false) {
        if ($vertical) {
            if (($y + strlen($word)) > 15) return false;
            else return true;
        }
        else {
            if (($x + strlen($word)) > 15) return false;
            else return true;
        }
    }

    // creates the table of tiles for the word that is placed on the board
    // x, y denote the square where the first letter is placed
    // need to translate that into a position on the rendered board
    // returns table html
    public function tileSetup($word, $x, $y, $vertical) {

        $wordArray = str_split(strtoupper($word));
        $xy=[$x, $y];

        $tileHtml = '<table id="tileLayout">' . "\r\n";
        $tileHtml .= "<tr>\r\n";

        foreach ($wordArray as $tile => $element) {
            if($element != "") {
                $pos = $this->positionTranslate($xy, $tile, $vertical);
                $tileVal = $this->tiles[$element];
                $tileHtml .= "<td><div id='letterTile' style='position:relative;left:$pos[0];top:$pos[1];'><span>$element</span><span class='number'>$tileVal</span></div></td>\r\n";
                if($vertical) {
                    $xy[1]++;
                }
            }
        }
        $tileHtml .= "</tr>\r\n";
        $tileHtml .= "</table>\r\n";
        return $tileHtml;
    }

    // creates the scrabble board as a table in html
    public function getBoardLayout() {

        $tableHtml = "<table id='scrabbleLayout'>\r\n";

        for ($i=0; $i<count($this->board); $i++) {
            $tableHtml .= "<tr>\r\n";
            foreach ($this->board[$i] as $square => $element) {
                if($element != "") {
                    $blockText = $this->getBlockText($element);
                    $boardTileClass = strtolower($element);
                    $tableHtml .= "<td><div class='boardTile $boardTileClass'><span>$blockText</span></div></td>\r\n";
                }
                else {
                    $tableHtml .= "<td><div class='boardTile blank'></div></td>\r\n";
                }
            }
            $tableHtml .= "</tr>\r\n";
        }

        $tableHtml .= "</table>\r\n";
        return $tableHtml;
    }

    /* nasty function that translates the letterTile to a position on the
     * scrabble board really nightmarish to deal with - i had to muck around
     * with this a lot to get it to look ok.
     * Need to calculate an offset for each letter and also
     * calculate how much to push the square back in the x direction
     * I'm sure there's a better way.  I just don't know it yet.
     */
    private function positionTranslate($xy, $lpos, $vert) {
        $OFFSET = 31;
        if ($vert) {
            $xpos = (5 + $xy[0]*$OFFSET - $lpos*($OFFSET-1)) ."px";
            $ypos = (-469 + $xy[1]*($OFFSET)) . "px";
        }
        else {
            $xpos = (5 + $xy[0]*($OFFSET) +  $lpos*0.75) ."px";
            $ypos = (-469 + $xy[1]*$OFFSET) . "px";
        }

        return [$xpos, $ypos];
    }

    // helper function that sets the text inside the board squares
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
