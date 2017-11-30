<?php

  class BookController extends ApplicationController {

    function index() {

      $books = Book::where('reserved = false');
      // $books.count();

      // $b = new FlowingCollection();
      // $b['dog'] = 'sdf';
      // var_dump($b);
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
