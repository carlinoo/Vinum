# Vinum

### What is Vinum?
Vinum is a new open-source Model View Controller (MVC) Framework. It is designed to make it really fast for developers to build scalable PHP applications while not affecting performance. If you are used to *Ruby on Rails* or *Laravel*, then you will love Vinum.

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

If you forget any coomand, you can simply do `bin/vinum --help` to get all the commands available. 

