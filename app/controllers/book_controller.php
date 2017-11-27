<?php

  class BookController extends ApplicationController {

    function index() {
      $book = Book::where();

      try {
        $book = Book::where();

      } catch (Exception $e) {
          echo 'Caught exception: ',  $e->getMessage(), "\n";
      }

      var_dump($book);
      self::render();
    }

    function json() {
      self::render('json', Book::all());
    }

    function display($id) {

      echo "<br><br>";
      var_dump($_GET);

      echo "<br><br>";

    }

  }

 ?>
