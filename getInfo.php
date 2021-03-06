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

$yourTileHtml = "";
$minScoreBingo = "";
$maxScoreBingo = "";
$yourScoreBingo = "";

if($form->isSubmitted()) {

    $errors = $form->validate(
        [
            // word is required, needs at least 1 vowel,
            // only letters, length 2-7
            'word' => 'required|minlength:2|maxlength:7|alpha|vowel:1',
            'orientation' => 'radio',
            'bingo' => 'checkbox',
            'xpos' => 'position',
            'ypos' => 'position',
        ]
    );

    // the Forms will do the appropriate checks for length and non-alpha
    // however they can't check for placement errors
    // No need to check placement until all the initial errors are resolved
    if (empty($errors)) {

        if ($xPosition == 'any') {
            // pick a random x based on word size and whether or
            // not vertical is selected
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
            // pick a random x based on word size and whether
            // or not vertical is selected
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
        $posOk = $sBoard->checkPlace($word, $xPosition, $yPosition, $vertical);

        if (!$posOk) {
            $v = $vertical ? " vertically" : " horizontally";
            // internal to external position conversion
            $x = $xPosition == "any" ? "any" : $xPosition+1;
            $y = $yPosition == "any" ? "any" : $yPosition+1;
            array_push($errors, "Word cannot fit$v on board at ($x, $y)");
        }

        if(empty($errors)) {
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

            $yourTileHtml = $sBoard->tileSetup($word, $x, $y, $vertical);
        }
    }
}
