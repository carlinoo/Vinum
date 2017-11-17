<?php
  class HomeController extends ApplicationController {


    function index() {
      model('book');
      //echo json_encode(Book::all());
      //var_dump($GLOBALS);
    }
  }

 ?>
