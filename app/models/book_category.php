<?php

  class Book_Category extends Application {
    function has_one() {
      return ['book', 'category'];
    }

  }


 ?>
