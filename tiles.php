
<?php
include_once('tools.php');

// Create a table of the alphabet tiles
// scrabble board is 15x15 - start at lower left (0,0)
// Define the alphabet tiles with their score
$tilesJson = file_get_contents('tiles.json');
$tiles = json_decode($tilesJson, $assoc=true);
//dump($tiles);

// scrabble board is 15x15 - start at lower left (0,0)
// Define the board as a 2D string array
// the string value indicates the following
// '' = emply
// 'DL' = double letter score,
// 'DW' = double word score, etc
$boardJson = file_get_contents('boardDefinition.json');
$board = json_decode($boardJson, $assoc=true);

echo $board[7][7];

//dump($board);
