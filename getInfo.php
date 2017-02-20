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

$xPosition = $form->get('xpos', 'any');
$yPosition = $form->get('ypos', 'any');
//Tools::dump($xPosition);
//Tools::dump($yPosition);

$maxTileHtml = "";
$minTileHtml = "";
$yourTileHtml = "";

if($form->isSubmitted()) {

    $errors = $form->validate(
        [
            'word' => 'required|alpha|vowel:1|maxlength:7|minlength:2',
        ]
    );

    if (($xPosition != 'any') && ($yPosition != 'any')) {
        // check word placement for user selection position
        $posOk = $sBoard->checkWordPlacement($word, $xPosition, $yPosition, $vertical);
        //Tools::dump($posOk);
        if (!$posOk) {
            array_push($errors, "Word cannot fit on board");
        }
        else {
            $x = $xPosition;
            $y = $yPosition;
        }
    }
    else {
        if ($xPosition == 'any') {
            // pick a random x based on word sze and whether or not vertical is selected
            if ($vertical) {
                $x = rand(0, 14);
            }
            else {
                $x = rand(0, 14-strlen($word));
            }
        }
        else {
            $x = $xPosition;
        }

        if ($xPosition == 'any') {
            // pick a random x based on word sze and whether or not vertical is selected
            if ($vertical) {
                $y = rand(0, 14-strlen($word));
            }
            else {
                $y = rand(0, 14);
            }
        }
        else {
            $y = $yPosition;
        }
    }

    if(!$errors) {
        // get the min/max scores
        $maxWordScore = $sBoard->getMaxScore($word, $bingo);
        $minWordScore = $sBoard->getMinScore($word, $bingo);
        echo "x,y=" .$x ."," .$y;
        $yourScore = $sBoard->getScoreByPosition($word, $x, $y, $vertical);
        $yourTileHtml = $sBoard->tileSetup($word, $x, $y, $vertical);
    }
}
