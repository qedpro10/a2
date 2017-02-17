<?php require('getInfo.php'); ?>
<?php require('tiles.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/tiles.css">
</head>

<body>
   <h1>Scrabble Calculator</h1>

   <form method='GET' name='scrabble' action='index.php'>

      <label for='word'>Enter the word</label>
      <input type='text' name='word' minLength="2" maxLength="7" required="true" value='<?PHP echo $word?>'>
      (2-7 letters)<br>

      <label for='minonly'>Minimum score only</label>
      <input type='checkbox' name="minonly" <?php if ($minonly) echo 'CHECKED' ?>><br>

      <fieldset class='radios'>
          <legend>Include 50 point bingo?</legend>
          <label><input type='radio' name='bingo' value='yes' <?php if($bingo == 'yes') echo 'CHECKED'?>> Yes</label>
          <label><input type='radio' name='bingo' value='no' <?php if($bingo == 'no') echo 'CHECKED'?>> No</label>
      </fieldset>

      <input type='submit' class="btn btn-success btn-med" value='Calculate'>
   </form>

   <?php echo boardSetup($board); ?>

   <?php
   if ($maxScore) {
       echo $maxScore . "<br>";
       echo $wordScore[0] . "<br>";
       echo $wordScore[1] . " <br>";
   }
   ?>
</body>

</html>
