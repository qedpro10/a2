<?php require('getInfo.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Scrabble Calculaor</title>
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
               <input type='text' name='word' required id='word' value='<?=$form->prefill('word')?>'>
               (2-7 letters)<br>

               <label for='minonly'>Minimum score only</label>
               <input type='checkbox' name="minonly" <?php if($form->isChosen('minonly')) echo 'CHECKED' ?>><br>

               <fieldset class='radios'>
                   <label>Include 50 point bingo?</label>
                   <label><input type='radio' name='bingo' value='yes' <?php if($form->isChosen('bingo')=='yes') echo 'CHECKED' ?>> Yes</label>
                   <label><input type='radio' name='bingo' value='no' <?php if($form->isChosen('bingo')=='no') echo 'CHECKED' ?>> No</label>
               </fieldset>
               <div class="btn-calc">
                   <input type='submit' class="btn btn-success btn-sm " value='Calculate'>
               </div>
           </form>
           <div>
               <h2>Score Summary</h2>
                  <?php if ($word != ""): ?>
                      <p>Minimum Score: <?=$minWordScore[0]?></p>
                      <p>Maximum Score: <?=$maxWordScore[0]?></p>
                      <p>Maximum Score Position: (<?=$maxWordScore[1]?>, <?=$maxWordScore[2]?>)</p>
                  <?php endif; ?>
              </div>
           </div>

           <div class="col col-md-8">
               <?=$boardHtml; ?>
           </div>
       </div>

       <div class="row">
           <div class="col col-md-12">
               <?=$tileHtml; ?>
           </div>
       </div>
   </div>
</body>

</html>
