<?php
  session_start();

  // We require the $_ENV variables and the connection to the database. We also require the initializers functions
  require_once('init.php');


?>
<html>
  <head>
    <title>RainbowBooks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo path("public/assets/javascripts/add-ons/jquery.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo path("public/assets/javascripts/add-ons/materialize.min.js"); ?>"></script>
    <link rel="stylesheet" href="<?php echo path("public/assets/stylesheets/add-ons/materialize.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo path("public/assets/stylesheets/add-ons/loader.css"); ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php add_assets(); ?>
    <link rel="shortcut icon" href="">

  </head>

  <body>
    <!-- This is the loader of the website -->
    <div id="loader" class="loader loader-default"></div>

    <?php require_once('app/views/layout/layout.php'); ?>


    <?php render(); ?>


    <?php require_once('app/views/layout/footer.php'); ?>

  </body>
</html>
