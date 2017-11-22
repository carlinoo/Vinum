<?php
  // This is the very initial controller
  class VinumController {
    // This action will render a view passed as a parameter
    protected static function render() {
      $number_of_args = func_num_args();

      if ($number_of_args == 0 || func_get_arg(0) === 'view') {
        // render the view
        require_once('app/views/layout/layout.php');
        require_once('app/views/' . $_GET['controller'] . '/' . $_GET['action'] . '.php');
        require_once('app/views/layout/footer.php');
        return;
      }


      // If the function looks like render('nothing'); -> the user doesnt want to load anything
      if (func_get_arg(0) === 'nothing') {
        return;
      }

      // If they are trying to respond with JSON
      if (func_get_arg(0) === 'json' && $number_of_args > 1) {
        header('Content-type: application/json');

        // If the third paramter is the sucess code
        if ($number_of_args > 2 && gettype(func_get_arg(2)) === 'integer') {
          // We set the HTTP response code
          http_response_code(func_get_arg(2));
        } else {
          http_response_code(200);
        }

        // We encode in JSON the second parameter
        echo json_encode(func_get_arg(1));
        return;
      }

    } // end render()




    // This method will be called before a method is called
    public function __call($method, $argv) {
      // we take away '_action' from the method
      $actual_method = substr($method, 0, -7);

      if (method_exists($this, $method)) {
        return;
      }

      // Call the before_action method, then the actual action and finally the after_action
      if (method_exists($this, 'before_action')) {
        self::before_action();
      }

      call_user_func_array([$this, $actual_method], $argv);

      if (method_exists($this, 'after_action')) {
        self::after_action();
      }
    }




    protected function no_render() {
      header('Location: index.php');
    }
  }
 ?>
