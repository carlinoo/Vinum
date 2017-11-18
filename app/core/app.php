<?php

  // This is the inital APP. This will take care of the controllers and actions, and it will reroute to where it is supposed to be
  class App {

    // In case the controller and action are not passed as paramaters
    protected $controller = "home";
    protected $action = "index";
    protected $params = [];

    // Constructor that parses the url and gets each item
    public function __construct() {
      $url = self::parseURL();

      // Check if the controller file exists
      if (file_exists('app/controllers/' . $url[0] . '_controller.php')) {
        $this->controller = $url[0];
        unset($url[0]);
      }

      $_GET['controller'] = $this->controller;

      // Check if the model file exists
      if (file_exists('app/models/' . $this->controller . '.php')) {
        require_once('app/models/' . $this->controller . '.php');
      }

      //We now require the controller passed as a parameter and we create a new object of it
      require_once('app/controllers/' . $this->controller . '_controller.php');
      $this->controller = ucfirst($this->controller) . "Controller";
      $this->controller = new $this->controller;



      // Now we check the action of that controller exists
      if (isset($url[1])) {
        if (method_exists($this->controller, $url[1])) {
          $this->action = $url[1];
          unset($url[1]);
        }
      }

      $_GET['action'] = $this->action;

      // Now we check if there are any params, and if there are, add them to the array $params
      $this->params = $url ? array_values($url) : [];


      // We set the path variables globally
      set_path_variables();

      // We call the controller, with its action and its params
      call_user_func_array([$this->controller, $this->action], $this->params);
    }


    // This function will get all the paramaters of the url of the type /param1/param2/param3... etc
    // and it will return them
    private function parseURL() {
      if (!isset($_GET['url'])) {
        return NULL;
      }

      // We explode the values of the $url
      $route = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

      // We get the route, and we explode it and we filter the empty values
      $routes = array_values(array_filter(explode('/', get_route($route))));

      // Check if the route is empty
      if (empty($routes)) {
        return NULL;
      }

      // We add the parameters to the array that are located in the $route[] starting from the 3rd element
      for ($i = 2; $i < count($route); $i++) {
        array_push($routes, $route[$i]);
      }

      return $routes;
    }
  }

 ?>
