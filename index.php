<?php require('getInfo.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Scrabble Calculator</title>
    <meta name="description" content="">
    <meta name="author" content="Jen Smith">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" href="css/tiles.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col col-md-12">
                <h1>Scrabble Calculator</h1>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-4">
                <h2>Enter Selection</h2>
                <form method='GET' action='index.php'>
                    <label for='word'>Enter the word</label>
                    <input type='text' name='word' required id='word' value='<?=$form->prefill('word', '')?>'>
                    (2-7 letters)<br>

                    <label for='vertical'>Show Vertical</label>
                    <input type='checkbox' name="vertical" <?php if($form->isChosen('vertical')) echo 'CHECKED' ?>><br>

                    <fieldset class='radios'>
                        <label>Include 50 point bingo?</label>
                        <label><input type='radio' name='bingo' value='yes' <?php if($form->get('bingo')=='yes') echo 'CHECKED' ?>> Yes</label>
                        <label><input type='radio' name='bingo' value='no' <?php if($form->prefill('bingo', 'no') == 'no') echo 'CHECKED' ?>> No</label>
                    </fieldset>

                    <label for='xpos'>Choose Your Position (x,y)</label>
                    <select name='xpos'>
                        <option value="any" <?php if($form->get('xpos') == 'any') echo 'selected=selected'?> >any</option>
                        <option value="0"  <?php if($form->get('xpos') == '0') echo 'selected=selected'?> >1</option>
                        <option value="1"  <?php if($form->get('xpos') == '1') echo 'selected=selected'?>>2</option>
                        <option value="2"  <?php if($form->get('xpos') == '2') echo 'selected=selected'?>>3</option>
                        <option value="3"  <?php if($form->get('xpos') == '3') echo 'selected=selected'?>>4</option>
                        <option value="4"  <?php if($form->get('xpos') == '4') echo 'selected=selected'?>>5</option>
                        <option value="5"  <?php if($form->get('xpos') == '5') echo 'selected=selected'?>>6</option>
                        <option value="6"  <?php if($form->get('xpos') == '6') echo 'selected=selected'?>>7</option>
                        <option value="7"  <?php if($form->get('xpos') == '7') echo 'selected=selected'?>>8</option>
                        <option value="8"  <?php if($form->get('xpos') == '8') echo 'selected=selected'?>>9</option>
                        <option value="9"  <?php if($form->get('xpos') == '9') echo 'selected=selected'?>>10</option>
                        <option value="10" <?php if($form->get('xpos') == '10') echo 'selected=selected'?>>11</option>
                        <option value="11" <?php if($form->get('xpos') == '11') echo 'selected=selected'?>>12</option>
                        <option value="12" <?php if($form->get('xpos') == '12') echo 'selected=selected'?>>13</option>
                        <option value="13" <?php if($form->get('xpos') == '13') echo 'selected=selected'?>>14</option>
                        <option value="14" <?php if($form->get('xpos') == '14') echo 'selected=selected'?>>15</option>
                    </select>
                    <select name='ypos'>
                        <option value="any" <?php if($form->get('xpos') == 'any') echo 'selected=selected'?> >any</option>
                        <option value="0"  <?php if($form->get('xpos') == '0')  echo 'selected=selected'?> >1</option>
                        <option value="1"  <?php if($form->get('xpos') == '1')  echo 'selected=selected'?>>2</option>
                        <option value="2"  <?php if($form->get('xpos') == '2')  echo 'selected=selected'?>>3</option>
                        <option value="3"  <?php if($form->get('xpos') == '3')  echo 'selected=selected'?>>4</option>
                        <option value="4"  <?php if($form->get('xpos') == '4')  echo 'selected=selected'?>>5</option>
                        <option value="5"  <?php if($form->get('xpos') == '5')  echo 'selected=selected'?>>6</option>
                        <option value="6"  <?php if($form->get('xpos') == '6')  echo 'selected=selected'?>>7</option>
                        <option value="7"  <?php if($form->get('xpos') == '7')  echo 'selected=selected'?>>8</option>
                        <option value="8"  <?php if($form->get('xpos') == '8')  echo 'selected=selected'?>>9</option>
                        <option value="9"  <?php if($form->get('xpos') == '9') echo 'selected=selected'?>>10</option>
                        <option value="10" <?php if($form->get('xpos') == '10') echo 'selected=selected'?>>11</option>
                        <option value="11" <?php if($form->get('xpos') == '11') echo 'selected=selected'?>>12</option>
                        <option value="12" <?php if($form->get('xpos') == '12') echo 'selected=selected'?>>13</option>
                        <option value="13" <?php if($form->get('xpos') == '13') echo 'selected=selected'?>>14</option>
                        <option value="14" <?php if($form->get('xpos') == '14') echo 'selected=selected'?>>15</option>
                    </select>
                    <div class="btn-calc">
                        <input type='submit' class="btn btn-info btn-sm " value='Calculate'>
                    </div>
                </form>
                <div>
                    <h2>Score Summary</h2>
                    <?php if($errors): ?>
                        <div class='alert alert-danger'>
                            <?php foreach($errors as $error): ?>
                                <?=$error?><br>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif($word!=''): ?>
                        <p>Minimum Score: <?=$minWordScore[0]?> <?=$minWordScore[3]?></p>
                        <p>Maximum Score: <?=$maxWordScore[0]?> <?=$maxWordScore[3]?></p>
                        <p>Maximum Score Position: (<?=$maxWordScore[1]?>, <?=$maxWordScore[2]?>)</p>
                        <p>Your Position Score at (<?=$x+1?>, <?=$y+1?>): <?=$yourScore?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col col-md-8">
                <?=$boardHtml; ?>
                <?=$yourTileHtml; ?>
            </div>
        </div>
    </div>
</body>

</html>
