<?php

  class BookController extends ApplicationController {

    function index() {
      self::render();

      $b = new Book(array("son" => "father",
                          "category_id" => "1"));

      var_dump($b);

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
