<?php
require('Form.php');

use DWA\Form;

$form = new Form($_GET);

// input Values
$haveResults = true;
$word = strtoupper($form->get('word', ''));
$minonly = $form->isChosen('minonly');
$bingo = $form->get('bingo', 'no');
