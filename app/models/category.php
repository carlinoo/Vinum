<?php
  class Category extends Application {


    function has_many() {
      return ['books'];
    }
  }


 ?>
