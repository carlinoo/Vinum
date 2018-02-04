<?php
  session_start();

  // We require the $_ENV variables and the connection to the database. We also require the initializers functions
  require_once('core/init.php');
  // require "db/migrate.php";
  // require_once('app/views/layout/layout.php');

  // require_once('core/model/migration.php');
  //
  // Migration::create_table('user', function($table) {
  //   $table->add_column('first_name', 'varchar(100)', 'not null');
  // });

  // Migration::drop_table('user');

?>
<?php initiate(); ?>
