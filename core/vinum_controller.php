<?php
  // This is the very initial controller
  class VinumController {
    protected $action;
    protected $controller;


    // This action will render a view passed as a parameter
    protected static function render() {
      $number_of_args = func_num_args();
      $argv = func_get_args();

      // If they are sending more than one parameter
      if ($number_of_args > 1) {

        // If they are requesting JSON respose
        if ($argv[0] === 'json' || $argv[0] === 'JSON') {
          header('Content-type: application/json');

          // If the third paramter is the sucess code
          if ($number_of_args > 2 && gettype($argv[2]) === 'integer') {
            // We set the HTTP response code
            http_response_code($argv[2]);
          } else {
            http_response_code(200);
          }

          // We encode in JSON the second parameter
          echo json_encode($argv[1]);
          return;
        }


        // Create all the local variables
        if (gettype($argv[1]) == "array") {
          extract($argv[1]);
        }
      }


      // Load the view depending on what the user sends as a parameter
      if ($number_of_args === 0 || $argv[0] === 'view') {
        require_once('app/views/layout/layout.php');
        require_once('app/views/' . $_GET['controller'] . '/' . $_GET['action'] . '.php');
        require_once('app/views/layout/footer.php');
      } elseif (gettype($argv[0]) == 'string') {
        require_once('app/views/layout/layout.php');
        require_once('app/views/' . $argv[0] . '.php');
        require_once('app/views/layout/footer.php');
      }


    } // end render()




    // This method will be called before a method is called
    public function __call($method, $argv) {
      // we take away '_action' from the method
      $this->action = substr($method, 0, -7);

      if (method_exists($this, $method)) {
        return;
      }

      // Call the before_action method, then the actual action and finally the after_action
      if (method_exists($this, 'before_action')) {
        $this->before_action();
      }

      call_user_func_array([$this, $this->action], $argv);


      if (method_exists($this, 'after_action')) {
        $this->after_action();
      }
    }




    protected function no_render() {
      header('Location: index.php');
    }
  }
 ?>
