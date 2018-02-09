 <?php

	// This migration needs to contain all the Migration statements of the changes you want to do.
	// To learn all you can do in migrations, go to https://github.com/carlinoo/Vinum

 	// To create a table:
	Migration::create_table('dog', function($table) {
    $table->add_column('age', 'int', 'not null');
	});

 ?>
