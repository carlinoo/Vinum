<?php

  model('book');
  model('user');

  class Reservation extends Application {
    public $id;
    public $ISBN;
    public $username;
    public $reserved_at;
    public $book;
    public $owner;


    public function __construct($arr = null) {
      if ($arr != null) {
        $this->id = array_key_exists('id', $arr) ? (int)$arr['id'] : null;
        $this->ISBN = array_key_exists('ISBN', $arr) ? $arr['ISBN'] : null;
        $this->username = array_key_exists('username', $arr) ? $arr['username'] : null;
        $this->reserved_at = array_key_exists('reserved_at', $arr) ? $arr['reserved_at'] : null;
        $this->book = array_key_exists('ISBN', $arr) ? Book::find($arr['ISBN'], 'ISBN') : null;
        $this->reserved_at = array_key_exists('username', $arr) ? User::find($arr['username'], 'username') : null;

      }
    }
  }

 ?>
