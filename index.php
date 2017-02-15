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
   <h1>Assignment #2. </h1>

   <form method='GET' name='xkcd' action='index.php'>

      <label for='words'>Number of Words</label>
      <input type='number' name='words' min="2" max="6" value="2"><br>



      <input type='checkbox' name="capital" <?php if ($lucky) echo 'CHECKED' ?>>
      <label for='capital'>Uppercase Letter</label><br>

      <input type='checkbox' name="number" <?php if ($lucky) echo 'CHECKED' ?>>
      <label for='capital'>Number</label><br>

      <input type='checkbox' name="special" <?php if ($lucky) echo 'CHECKED' ?>>
      <label for='capital'>Special Character</label><br>

      <label for='charlist'>Special Characters</label>
      <select name='charlist'>
         <option value='@'>@</option>
         <option value='#'>#</option>
         <option value='$'>$</option>
      </select><br>

      <input type='submit' class="btn btn-success btn-med" value='Generate'>
   </form>

</body>

</html>
