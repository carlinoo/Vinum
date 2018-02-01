<?php

  class Reservation extends Application {
    function belongs_to() {
      return ['book'];
    }
  }

 ?>
