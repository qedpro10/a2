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
     public function getMaxScore($word) {
         $highScore=[0,0,0];  //word score, x-position, y-position
         $score=$this->getMinScore($word)[0];
         $highScore[0] = $score;
         $wordArray = str_split(strtoupper($word));
         for ($j=0; $j<=count($this->board[0])/2+1; $j++) {
             for ($i=0; $i<=count($this->board[$j])-count($wordArray); $i++) {
                 $score = $this->getScoreByPosition($word, $i, $j);
                 if ($score > $highScore[0]) {
                     $highScore[0] = $score;
                     $highScore[1] = $i;
                     $highScore[2] = $j;
                 }
             }
             //Tools::dump($highScore);
             //echo "highScore ($highScore[1], $highScore[2]) with score: $highScore[0] <br>";
         }
         //Tools::dump($highScore);
         return $highScore;
     }

     // get the word base score
     public function getMinScore($word) {
         $wordArray = str_split(strtoupper($word));
         $minScore = 0;
         foreach ($wordArray as $letter => $element) {
             $minScore += $this->tiles[$element];
         }
         return [$minScore, 4, 7];
     }

     // calculates the word score based on the position the scrabble board
     // taking into account the occurence of double, triple, letter & word tiles
     public function getScoreByPosition($word, $x, $y) {
         $wordArray = str_split(strtoupper($word));
         $score = 0;
         $bTws = false;
         $bDws = false;
         // calculate the word score based on the position on the board
         // for now assume horizontal
         for ($j=0; $j<count($wordArray); $j++) {
             // validate the position pos[x,y]

             // calculate the letter score
             switch ($this->board[$x+$j][$y]) {
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
         //echo "calcScoreByPosition with ($x, $y) with score: $score <br>";
         return $score;
         // set the position
     }

     // creates the table of tiles for the word that is placed on the board
     // x, y denote the square where the first letter is placed
     // need to translate that into a position on the rendered board
     public function tileSetup($word, $x, $y, $vertical) {
         $wordArray = str_split(strtoupper($word));
         $xy=[$x, $y];
         if($vertical == true) {
             $xy = $this->htov($x, $y);
         }

         //echo "xy= (" .$xy[0] ."," . $xy[1] .")";
         $tileHtml = '<table id="tileLayout">' . "\r\n";
         $tileHtml .= "<tr>\r\n";
         foreach ($wordArray as $tile => $element) {
             if($element != "") {
                 $pos = $this->positionTranslate($xy, $tile, $vertical);
                 $tileHtml .= "<td><div id='letterTile' style='position:relative;left:$pos[0];top:$pos[1];'>$element</div></td>\r\n";
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
        $tableHtml = "<table id='scrabbleLayout'>\r\n";
        for ($i=0; $i<count($this->board); $i++) {
           $tableHtml .= "<tr>\r\n";
           foreach ($this->board[$i] as $square => $element) {
              if($element != "") {
                 $boardTileClass = strtolower($element);
                 $tableHtml .= "<td><div id='boardTile' class='$boardTileClass'></div></td>\r\n";
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
} # End of Class
