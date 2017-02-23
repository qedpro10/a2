<?php require('getInfo.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Scrabble Calculator</title>
    <meta name="description" content="">
    <meta name="author" content="Jen Smith">
    <link rel="icon" type="images/png" href="images/favicon.ico">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" href="css/scrabble.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col col-md-12">
                <h1>Scrabble Calculator</h1>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-5">
                <h2>Enter Selection</h2>
                <form method='GET' action='index.php'>
                    <p>
                        <label for='word'>Enter the word: </label>
                        <input type='text' name='word' required id='word' value='<?=$form->prefill('word', '')?>'> (Required*)
                    </p>
                    <label>Choose a position (x,y): </label>
                    <select name='xpos'>
                        <option value="any"<?php if($form->prefill('xpos', '') == 'any') echo 'SELECTED'?>>any</option>
                        <option value="0"  <?php if($form->prefill('xpos', '') == '0')  echo 'SELECTED'?> >1</option>
                        <option value="1"  <?php if($form->prefill('xpos', '') == '1')  echo 'SELECTED'?>>2</option>
                        <option value="2"  <?php if($form->prefill('xpos', '') == '2')  echo 'SELECTED'?>>3</option>
                        <option value="3"  <?php if($form->prefill('xpos', '') == '3')  echo 'SELECTED'?>>4</option>
                        <option value="4"  <?php if($form->prefill('xpos', '') == '4')  echo 'SELECTED'?>>5</option>
                        <option value="5"  <?php if($form->prefill('xpos', '') == '5')  echo 'SELECTED'?>>6</option>
                        <option value="6"  <?php if($form->prefill('xpos', '') == '6')  echo 'SELECTED'?>>7</option>
                        <option value="7"  <?php if($form->prefill('xpos', '') == '7')  echo 'SELECTED'?>>8</option>
                        <option value="8"  <?php if($form->prefill('xpos', '') == '8')  echo 'SELECTED'?>>9</option>
                        <option value="9"  <?php if($form->prefill('xpos', '') == '9')  echo 'SELECTED'?>>10</option>
                        <option value="10" <?php if($form->prefill('xpos', '') == '10') echo 'SELECTED'?>>11</option>
                        <option value="11" <?php if($form->prefill('xpos', '') == '11') echo 'SELECTED'?>>12</option>
                        <option value="12" <?php if($form->prefill('xpos', '') == '12') echo 'SELECTED'?>>13</option>
                        <option value="13" <?php if($form->prefill('xpos', '') == '13') echo 'SELECTED'?>>14</option>
                        <option value="14" <?php if($form->prefill('xpos', '') == '14') echo 'SELECTED'?>>15</option>
                    </select>
                    <select name='ypos'>
                        <option value="any"<?php if($form->prefill('ypos', '') == 'any') echo 'SELECTED'?>>any</option>
                        <option value="0"  <?php if($form->prefill('ypos', '') == '0')  echo 'SELECTED'?> >1</option>
                        <option value="1"  <?php if($form->prefill('ypos', '') == '1')  echo 'SELECTED'?>>2</option>
                        <option value="2"  <?php if($form->prefill('ypos', '') == '2')  echo 'SELECTED'?>>3</option>
                        <option value="3"  <?php if($form->prefill('ypos', '') == '3')  echo 'SELECTED'?>>4</option>
                        <option value="4"  <?php if($form->prefill('ypos', '') == '4')  echo 'SELECTED'?>>5</option>
                        <option value="5"  <?php if($form->prefill('ypos', '') == '5')  echo 'SELECTED'?>>6</option>
                        <option value="6"  <?php if($form->prefill('ypos', '') == '6')  echo 'SELECTED'?>>7</option>
                        <option value="7"  <?php if($form->prefill('ypos', '') == '7')  echo 'SELECTED'?>>8</option>
                        <option value="8"  <?php if($form->prefill('ypos', '') == '8')  echo 'SELECTED'?>>9</option>
                        <option value="9"  <?php if($form->prefill('ypos', '') == '9')  echo 'SELECTED'?>>10</option>
                        <option value="10" <?php if($form->prefill('ypos', '') == '10') echo 'SELECTED'?>>11</option>
                        <option value="11" <?php if($form->prefill('ypos', '') == '11') echo 'SELECTED'?>>12</option>
                        <option value="12" <?php if($form->prefill('ypos', '') == '12') echo 'SELECTED'?>>13</option>
                        <option value="13" <?php if($form->prefill('ypos', '') == '13') echo 'SELECTED'?>>14</option>
                        <option value="14" <?php if($form->prefill('ypos', '') == '14') echo 'SELECTED'?>>15</option>
                    </select>

                    <fieldset class='radios'>
                        <label>Word orientation: </label>
                        <label><input type='radio' name='orientation' value='horizontal' <?php if($form->prefill('orientation', 'horizontal') == 'horizontal') echo 'CHECKED' ?>> Horizontal</label>
                        <label><input type='radio' name='orientation' value='vertical' <?php if($form->prefill('orientation', '')=='vertical') echo 'CHECKED' ?>> Vertical</label>
                    </fieldset>

                    <p>
                        <label>Include 50 point Bingo: </label>
                        <input type='checkbox' name="bingo" <?php if($form->prefill('bingo', '') == 'on') echo 'CHECKED' ?>>
                    </p>

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
                        <p>Minimum Score at (<?=$minWordScore[1]?>, <?=$minWordScore[2]?>):  <?=$minWordScore[0]?> <?=$minScoreBingo?></p>
                        <p>Maximum Score at (<?=$maxWordScore[1]?>, <?=$maxWordScore[2]?>):  <?=$maxWordScore[0]?> <?=$maxScoreBingo?></p>
                        <p>Position Score at (<?=$yourScore[1]?>, <?=$yourScore[2]?>):  <?=$yourScore[0]?> <?=$yourScoreBingo?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col col-md-7">
                <?=$boardHtml; ?>
                <?=$yourTileHtml; ?>
            </div>
        </div>
    </div>
</body>

</html>
