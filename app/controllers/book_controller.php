<?php

  class BookController extends ApplicationController {

    function index() {
      self::render();
    }

    function json() {
      self::render('json', Book::all());
    }
  }

 ?>
