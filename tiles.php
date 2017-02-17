
<?php
require('Tools.php');

// Create a table of the alphabet tiles
// scrabble board is 15x15 - start at lower left (0,0)
// Define the alphabet tiles with their score
$tilesJson = file_get_contents('tiles.json');
$tiles = json_decode($tilesJson, $assoc=true);
//dump($tiles);

// scrabble board is 15x15 - start at upper left (0,0)
// Define the board as a 2D string array
// the string value indicates the following
// '' = emply
// 'DL' = double letter score,
// 'DW' = double word score, etc
$boardJson = file_get_contents('boardDefinition.json');
$board = json_decode($boardJson, $assoc=true);

//dump($board);
//echo $board[7][7];
// given the work, calculate the max score and position of first letter
function calculateMaxScore($word, $tiles) {
     $wordArray = str_split($word);
     //DWA\Tools::dump($wordArray);
     $score = 0;
     foreach ($wordArray as $letter => $element) {
         $score += $tiles[strtoupper($element)];
     }
     return $score;
    // set the position
}

function calculateScore($word) {
    global $board;
    global $tiles;
     $wordArray = str_split($word);
     $score = [0,0];
     foreach ($wordArray as $letter => $element) {
         $score[0] += $tiles[strtoupper($element)];
     }
     $score[1] = $score[0];
     // now get the max score
     for ($i=0; $i<=count($board[0])-count($wordArray); $i++) {
         $temp = 0;
         $bTws = false;
         $bDws = false;
         for ($j=0; $j<count($wordArray); $j++) {
             switch ($board[0][$j+$i]) {
                case "TWS":
                    $bTws = true;
                    $temp += $tiles[strtoupper($wordArray[$j])];
                    break;
                case "DWS":
                    $bDws = true;
                    $temp += $tiles[strtoupper($wordArray[$j])];
                    break;
                case "TLS":
                    $temp += ($tiles[strtoupper($wordArray[$j])] * 3);
                    break;
                case "DLS":
                    $temp += ($tiles[strtoupper($wordArray[$j])] * 2);
                    break;
                default:
                    $temp += $tiles[strtoupper($wordArray[$j])];
                    break;
            }
        }
        if ($bTws == true) {
            $temp *= 3;
        }
        else if ($bDws == true) {
            $temp *= 2;
        }

        if ($temp > $score[1]) {
            $score[1] = $temp;
        }
    }
    return $score;
    // set the position
}

// theme
$maxScore = 0;
$minScore = 0;
$wordScore = [0,0];
$word = (isset($_GET['word'])) ? $_GET['word'] : "";
if ($word != "") {
    $maxScore = calculateMaxScore($word, $tiles);
}
if ($word != "") {
    $wordScore = calculateScore($word, $tiles);
}


/*
// creates the tiles for the word
function tileSetup($wordStr) {
    $tileHtml = '<table id="tileLayout">' . "\r\n";
    $tileHtml .= "<tr>\r\n";
    foreach ($tileArray as $tile => $element) {
        $tileHtml .= "<td width=30 height=30>$tile";
        if($element != "") {
            $tileClass = $element;
            $tileHtml .= "<div id='tileBlock' class='$tileClass'></div></td>\r\n";
        }
        else {
            $tileHtml .= "<div id='tileBlock' class='blank'></div></td>\r\n";
        }
    }
    $tileHtml .= "</tr>\r\n";
    $tileHtml .= "</table>\r\n";
   return $tileHtml;
}
*/
// creates the scrabble board as a table in html
function boardSetup($tableArray) {
   $tableHtml = '<table id="scrabbleLayout">' . "\r\n";
   for ($i=0; $i<count($tableArray); $i++) {
      $tableHtml .= "<tr>\r\n";
      foreach ($tableArray[$i] as $square => $element) {
         $tableHtml .= "<td width=30 height=30>";
         if($element != "") {
            $tileClass = strtolower($element);
            $tableHtml .= "<div id='tileBlock' class='$tileClass'></div></td>\r\n";
         }
         else {
            $tableHtml .= "<div id='tileBlock' class='blank'></div></td>\r\n";
         }
      }
      $tableHtml .= "</tr>\r\n";
   }
   $tableHtml .= "</table>\r\n";
   return $tableHtml;
}




//dump($board);
