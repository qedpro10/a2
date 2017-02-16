<?php require('getInfo.php'); ?>
<?php require('tiles.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Latest compiled and minified CSS -->
  
  <link rel="stylesheet" href="css/tiles.css">
</head>

<body>
   <h1>Assignment #2. Scrabble Board</h1>

   <form method='GET' name='scrabble' action='index.php'>

      <label for='word'>Enter the word</label>
      <input type='text' name='word' minLength="2" maxLength="7" required="true" value='<?php if(isset($_GET['word'])) echo $_GET['word'] ?>'><br>

      <label for='optimize'>Look for optimal score</label>
      <input type='checkbox' name="optimize" <?php if ($optimize) echo 'CHECKED' ?>><br>

      <label for='bingo'>Include 50 point bingo?</label>
      <input type="radio" name="bingo" value="Yes">Yes
      <input type="radio" name="bingo" value="No">No<br><br>

      <input type='submit' class="btn btn-success btn-med" value='Calculate'>
   </form>

   <?php echo boardSetup($board); ?>


</body>

</html>
