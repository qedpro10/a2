<?php
require('Form.php');
require('Scrabble.php');
require('Tools.php');

use JAS\Scrabble;
use DWA\Form;
use DWA\Tools;

$sBoard = new Scrabble('includes/tiles.json', 'includes/boardDefinition.json');
$form = new Form($_GET);

$boardHtml = $sBoard->getBoardLayout();

$errors=[];


$bingo = $form->isChosen('bingo');
$orientation = $form->get('orientation');

$vertical = false;
if ($orientation == 'vertical') {
    $vertical = true;
}

$word = $form->get('word', '');

$xPosition = $form->get('xpos', 'any');
$yPosition = $form->get('ypos', 'any');
//Tools::dump($xPosition);
//Tools::dump($yPosition);

$yourTileHtml = "";
$minScoreBingo = "";
$maxScoreBingo = "";
$yourScoreBingo = "";

if($form->isSubmitted()) {

    $errors = $form->validate(
        [
            // word is required, needs at least 1 vowel, only letters, length 2-7
            'word' => 'required|alpha|vowel:1|maxlength:7|minlength:2',
        ]
    );


    if ($xPosition == 'any') {
        // pick a random x based on word size and whether or not vertical is selected
        if ($vertical) {
            $x = rand(0, 14);
        }
        else {
            $x = rand(0, 14-strlen($word)+1);
        }
    }
    else {
        $x = $xPosition;
    }

    if ($yPosition == 'any') {
        // pick a random x based on word sze and whether or not vertical is selected
        if ($vertical) {
            $y = rand(0, 14-strlen($word)+1);
        }
        else {
            $y = rand(0, 14);
        }
    }
    else {
        $y = $yPosition;
    }

    // check word placement for user selection position
    $posOk = $sBoard->checkWordPlacement($word, $xPosition, $yPosition, $vertical);
    //Tools::dump($posOk);
    if (!$posOk) {
        $v = $vertical ? " vertically" : "";
        // internal to external position conversion
        $x = $xPosition == "any" ? "any" : $xPosition+1;
        $y = $yPosition == "any" ? "any" : $yPosition+1;
        array_push($errors, "Word cannot fit$v on board at ($x, $y)");
    }

    if(!$errors) {
        // get the min/max scores
        $maxWordScore = $sBoard->getMaxScore($word, $vertical);
        $maxWordScore = $sBoard->convertToHtmlPos($maxWordScore);

        $minWordScore = $sBoard->getMinScore($word, $vertical);
        $minWordScore = $sBoard->convertToHtmlPos($minWordScore);

        $yourScore = $sBoard->getScoreByPosition($word, $x, $y, $vertical);
        $yourScore = $sBoard->convertToHtmlPos($yourScore);

        if ($bingo) {
            $minScoreBingo = $sBoard->getBingoScore($word, $minWordScore[0]);
            $maxScoreBingo = $sBoard->getBingoScore($word, $maxWordScore[0]);
            $yourScoreBingo = $sBoard->getBingoScore($word, $yourScore[0]);
        }


        //echo "x,y=" .$x ."," .$y;
        $yourTileHtml = $sBoard->tileSetup($word, $x, $y, $vertical);
    }
}
