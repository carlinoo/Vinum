<?php
  session_start();

  // We require the $_ENV variables and the connection to the database. We also require the initializers functions
  require_once('core/init.php');

  require __DIR__ . '/vendor/autoload.php';
  // require_once('app/views/layout/layout.php');

?>
<?php initiate(); ?>
