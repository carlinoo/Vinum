<?php

  class Book extends Application {

    // function has_one() {
    //   return ['category'];
    // }

    function has_many() {
      // return ['reservations', 'categories'];
      return ['reservation', 'category'];
    }

    // function belongs_to() {
    //
    // }

    // This method will reserve a Book
    public function reserve_book_by($user) {
      $this->update_attributes(array("reserved" => 1));
    }



    // This method will return wheather a book is reserved
    public function is_reserved() {
      return ((boolean)$this->reserved);
    }



  }

 ?>
