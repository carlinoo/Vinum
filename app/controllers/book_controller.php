<?php

  class BookController extends ApplicationController {

    function index() {
      self::render();
    }

    function json() {
      self::render('json', Book::all());
    }

    function display($id) {
      echo "book/display";
      echo "/$id";
    }
  }

 ?>
