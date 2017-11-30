<?php

  class BookController extends ApplicationController {

    function index() {

      echo "<br><br>";
      $books = Book::where('reserved = false')->order();
      var_dump($books);

      echo "<br><br><br>";
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
