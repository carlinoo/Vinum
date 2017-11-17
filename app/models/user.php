<?php


  class User extends Application {
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $address_line_one;
    public $address_line_two;
    public $city;
    public $telephone;
    public $mobile;


    public function __construct($arr = null) {
      if ($arr != null) {
        $this->id = array_key_exists('id', $arr) ? (int)$arr['id'] : null;
        $this->username = array_key_exists('username', $arr) ? $arr['username'] : null;
        $this->password = array_key_exists('password', $arr) ? $arr['password'] : null;
        $this->first_name = array_key_exists('first_name', $arr) ? $arr['first_name'] : null;
        $this->last_name = array_key_exists('last_name', $arr) ? $arr['last_name'] : null;
        $this->address_line_one = array_key_exists('address_line_one', $arr) ? $arr['address_line_one'] : null;
        $this->address_line_two = array_key_exists('address_line_two', $arr) ? $arr['address_line_two'] : null;
        $this->city = array_key_exists('city', $arr) ? $arr['city'] : null;
        $this->telephone = array_key_exists('telephone', $arr) ? $arr['telephone'] : null;
        $this->mobile = array_key_exists('mobile', $arr) ? $arr['mobile'] : null;
      }
    }


    // This function will register a user
    public function register() {

      // If it is not a valid password
      if (!$this->valid_password()) {
        return "Invalid Password. It must be six characters";
      }

      // If the mobile is valid
      if (!$this->valid_mobile()) {
        return "Mobile Number is Invalid. It must be 10 numeric digits";
      }

      // If there is a user with that username
      if (User::find($this->username, 'username') !== false) {
        return "Username already taken";
      }

      // Now we create the user on the database
      if ($this->create_record()) {
        echo "<br><br>Record Created";
        // We update the sync the object with the database
        if ($this->update_object('username')) {
          return true;
          echo "<br><br>Object Updated";
        }
      }

      return "Sorry, an error has ocurred. Try again later";
    }




    // This method will return check if the user credentials ae valid to log in
    // If they are, i will update the values of that user
    public function log_in() {

      // Check if the user exists on the database
      if (!$this->does_exist('username')) {
        return "Username does not exist";
      }

      $user = User::find($this->username, 'username');

      // If password matches
      if ($user->password !== $this->password) {
        return "Sorry, password does not match";
      }

      return true;
    }





  // ******************************* //
  // ******* PRIVATE METHODS ******* //
  // ******************************* //

  // This will return wheather a password is valid
  private function valid_password() {
    return (strlen($this->password) == 6);
  }



  // This will return wheather a password is valid
  private function valid_mobile() {
    return (strlen($this->mobile) == 10 && is_numeric($this->mobile));
  }


  }

 ?>
