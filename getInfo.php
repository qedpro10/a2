<?php
require('Form.php');
require("Scrabble.php");
require("Tools.php");

use JAS\Scrabble;
use DWA\Form;
use DWA\Tools;

$sBoard = new Scrabble();
$form = new Form($_GET);

$boardHtml = $sBoard->getBoardLayout();

$errors=[];


$vertical = $form->isChosen('vertical');
$bingo = $form->get('bingo');

$word = $form->get('word', '');


$maxTileHtml = "";
$minTileHtml = "";

if($form->isSubmitted()) {

    $errors = $form->validate(
        [
            'word' => 'required|alpha|vowel:1|maxlength:7|minlength:2',
        ]
    );

    if($errors) {
        //Tools::dump($errors);
    }
    else {
        if ($word != "") {
            // get the min/max scores
            $maxWordScore = $sBoard->getMaxScore($word);
            $minWordScore = $sBoard->getMinScore($word);

            // create the tile overlay
            $maxTileHtml = $sBoard->tileSetup($word, $maxWordScore[1], $maxWordScore[2], $vertical);
            $minTileHtml = $sBoard->tileSetup($word, $minWordScore[1], $minWordScore[2], $vertical);
        }
        else {
            $maxTileHtml = "";
            $minTileHtml = "";
        }
    }
}
