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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/flatly/css/bootstrap.min.css">
</head>

<body>
   <h1>Assignment #2. Scrabble Board</h1>

   <form method='GET' name='scrabble' action='index.php'>

      <label for='word'>Enter the word</label>
      <input type='text' name='word' minLength="2" maxLength="9" required="true"><br>

      <label for='optimize'>Look for optimal score</label>
      <input type='checkbox' name="optimize" <?php if ($bingo) echo 'CHECKED' ?>><br>

      <label for='bingo'>Include 50 point bingo?</label>
      <input type="radio" name="bingo" value="Yes">Yes</input>
      <input type="radio" name="bingo" value="No">No</input><br><br>

      <input type='submit' class="btn btn-success btn-med" value='Calculate'>
   </form>

</body>

</html>
