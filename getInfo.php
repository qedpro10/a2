<?php
require('tools.php');

// theme
$words = (isset($_GET['words'])) ? $_GET['words'] : 2;

// special char checkbox
$special = (isset($_GET['special'])) ? true : false;

// range
$capital = (isset($_GET['range'])) ? true : false;

// feeling lucky
$number = (isset($_GET['lucky'])) ? true : false;

// charValue
$words = (isset($_GET['charlist'])) ? $_GET['clarlist'] : 2;



$filter = (isset($_GET['filter'])) ? $_GET['filter'] : '';


// this loads the book page on initial render
if ($filter == '' )
   return $books;

// this removes all the books based on the filter
foreach($books as $title => $book) {
   if($title != $filter) {
      unset($books[$title]);
   }
}

foreach($books as $title => $book) {

   if($caseInsensitive) {
      $match = strtolower($title) == strtolower($filter);
   }
   else {
      $match = $title == $filter;
   }
   if (!$match) {
      unset($books[$title]);
   }
}
