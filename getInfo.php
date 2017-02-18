<?php
require('Form.php');
require("Scrabble.php");

use DWA\Form;
use JAS\Scrabble;


$sBoard = new Scrabble();

$form = new Form($_GET);

if($form->isSubmitted()) {

    $errors = $form->validate(
        [
            'text' => 'required',
        ]
    );
}

$boardHtml = $sBoard->getBoardLayout();
$tileHtml = "";

// input Values
$haveResults = true;
$minonly = $form->isChosen('minonly');
$bingo = $form->get('bingo', 'no');
$word = strtoupper($form->get('word', ''));

if ($word != "") {
    // get the min/max scores
    $maxWordScore = $sBoard->getMaxScore($word);
    $minWordScore = $sBoard->getMinScore($word);
    // create the tile overlay
    $tileHTML = $sBoard->tileSetup($word, $maxWordScore[1], $maxWordScore[2]);
}
else {
    $tileHtml = "";
}
