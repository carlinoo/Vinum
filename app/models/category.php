<?php

  class Category extends Application {
    public $id;
    public $description;

    public function __construct($arr = null) {
      if ($arr != null) {
        $this->id = $arr['id'];
        $this->description = $arr['description'];
      }
    }
  }


 ?>
