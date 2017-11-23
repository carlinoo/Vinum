# Vinum

### What is Vinum?
Vinum is a new open-source Model View Controller (MVC) Framework. It is designed to make it really fast for developers to build scalable PHP applications while not affecting performance. If you are used to *Ruby on Rails* or *Laravel*, then you will love Vinum.

To make Vinum a rapid development framework, we use __*Convention over Configuration*__. Read more about it below.

Vinum is an alpha version so it is not recommendable to use apart from personal projects or to contribute to the framework.

### How to start?
The first thing you need is to download or clone the repository. For future versions you will be able to just download the executable file and do `bin/vinum new [APP_NAME]` on the command line. A new folder will be generated on the location chosen with the new project.

After this you will need to make some changes lot let the application connect to the database. In `config/variables.php` fill in the details about the database. It will need to look like this:

``` php
<?php
  $_ENV['database_name'] = 'DATABASE NAME';
  $_ENV['database_password'] = 'DATABASE PASSWORD';
  $_ENV['database_host'] = 'DATABASE HOST';
  $_ENV['database_username'] = 'DATABASE USERNAME';
  $_ENV['root_path'] = 'http://localhost/path/to/folder/in/server/index.php';
  $_ENV['root_dir'] = 'http://localhost/path/to/folder/in/server/';
 ?>
```

After this you can go to the file in the root directory `.htaccess` and edit the `RewriteBase /` to something like `RewriteBase /path/to/folder/in/server`. The file should look like this:

```
Options -MultiViews
RewriteEngine On

RewriteBase /path/to/folder/in/server

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]

```

Now you are ready to start developing. You need first to migrate the database. To do so, just do `bin/vinum db:migrate`. This will set up the Database so you can be ready to start. If you now go to the directory of the project folder on the localhost you will see the welcome message. That means everything is working.


### Useful commands
There are a few commands you will need to get familar with in order to develop things faster. If you are familiar with *Ruby on Rails* you will notice how similar they are. Note that some are not available at the moment, but will in future updates.

#### Actions
To start a new app just do `bin/vinum new [APP_NAME]`. This will create a folder in the desired location wil the projects files.

To generate a new controller `bin/vinum generate controller [CONTROLLER_NAME]` or if you prefer you can also do `bin/vinum g controller [CONTROLLER_NAME]`.

To generate a new model you will need `bin/vinum generate model [MODEL_NAME]` or also you can do `bin/vinum g model [MODEL_NAME]`. This will also generate a migration file. Read below about migrations.

If you want to generate a migration use  `bin/vinum generate migration [MIGRATION_NAME]` or alternatively `bin/vinum g migration [MIGRATION_NAME]`. Read about migration below.

To migrate the database you will need to do `bin/vinum db:migrate`.

If you want to generate a model, controller and migration file with the same name then use `bin/vinum generate resource [RESOURCE_NAME]` or `bin/vinum g resource [RESOURCE_NAME]`.

#### Options
If you forget any command, you can simply do `bin/vinum --help` to get all the commands available. 

To show the version, use `bin/vinum --version` or `bin/vinum --v`.

To display the routes to the console then do `bin/vinum`.

### Migrations
Migrations are the way to keep track of all the changes you have done to the database. This way, if you wipe the database by mistake, buy a new computer or work with multiple people on the same project, you will always have the same database structure. Every time you want a new table, change a column or any change, you will have to generate a new migration file via the command line, go to the new file generated and write your SQL code there. After you do so, you can do to the command like and migrate the database using `bin/vinum db:migrate`. 

If everything is successful, you won't see any errors displayed on the screen and the changes will be made. Remember that every time you create a new model or resource, a new migration file will be created and you will need to create a table with the same name as the model.

