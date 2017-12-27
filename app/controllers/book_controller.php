<?php

  class BookController extends ApplicationController {

    function index() {

      $books = Book::where('id = ?', 27);

      // var_dump(Book::first()->category);

      // var_dump($books->does_exist());
      // $books->update_attributes();
      // $books = Book::where('reserved = false');
      // var_dump($books);
      // var_dump($books->has(Book::find(1)));

      $info = WebRest::CallAPI('http://wordify.cloudthon.com/english/singular/hellos');

      var_dump($info);

      // self::render('json', Book::first()->reservation);
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
