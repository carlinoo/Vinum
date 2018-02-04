<?php

  class BookController extends ApplicationController {

    function index() {

      $books = Book::where('id = ?', 1);

      // var_dump(Book::first()->reservations);

      // var_dump($books->does_exist());
      // $books->update_attributes();
      // $books = Book::where('reserved = false');
      // var_dump($books);
      // var_dump($books->has(Book::find(1)));

      self::render('json', Book::first());
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
