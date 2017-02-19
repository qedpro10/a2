<?php
require('Form.php');
require("Scrabble.php");


use JAS\Scrabble;


$sBoard = new Scrabble();
$form = new DWA\Form($_GET);

$boardHtml = $sBoard->getBoardLayout();

$errors=[];

if($form->isSubmitted()) {

    $minonly = $form->isChosen('minonly', false);
    $bingo = $form->get('bingo');
    $word = $form->get('word', $default='');

    $errors = $form->validate(
        [
            'word' => 'required',
        ]
    );

    if($errors) {

    }
    else {
        if ($word != "") {
            // get the min/max scores
            $maxWordScore = $sBoard->getMaxScore($word);
            $minWordScore = $sBoard->getMinScore($word);

            // create the tile overlay
            $tileHtml = $sBoard->tileSetup($word, $maxWordScore[1], $maxWordScore[2]);
        }
        else {
            $tileHtml = "";
        }
    }
}
else {
    $word = '';
    $tileHtml = '';
}

// input Values
//$haveResults = true;
