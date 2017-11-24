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
There are a few commands you will need to get familiar with in order to develop things faster. If you are familiar with *Ruby on Rails* you will notice how similar they are. Note that some are not available at the moment, but will in future updates.

##### Actions
To start a new app just do `bin/vinum new [APP_NAME]`. This will create a folder in the desired location wil the projects files.

To generate a new controller `bin/vinum generate controller [CONTROLLER_NAME]` or if you prefer you can also do `bin/vinum g controller [CONTROLLER_NAME]`.

To generate a new model you will need `bin/vinum generate model [MODEL_NAME]` or also you can do `bin/vinum g model [MODEL_NAME]`. This will also generate a migration file. Read below about migrations.

If you want to generate a migration use  `bin/vinum generate migration [MIGRATION_NAME]` or alternatively `bin/vinum g migration [MIGRATION_NAME]`. Read about migration below.

To migrate the database you will need to do `bin/vinum db:migrate`.

If you want to generate a model, controller and migration file with the same name then use `bin/vinum generate resource [RESOURCE_NAME]` or `bin/vinum g resource [RESOURCE_NAME]`.

##### Options
If you forget any command, you can simply do `bin/vinum --help` to get all the commands available. 

To show the version, use `bin/vinum --version` or `bin/vinum --v`.

To display the routes to the console then do `bin/vinum`.

### App Architecture
The architecture of the application is quite complex. In the `app` folder you have:

* `controllers` : All controllers will look like `[CONTROLLER_NAME]_controller.php`. Here all the actions will be specified. To read more about controllers go to the controllers section.

* `models`: All models will look like `[MODEL_NAME].php`. This will be the main class of a database table. Read more about models on the models section.

* `views`: In this folder you will have folders that match the controller names with files inside each that match the action in the same controller. Read more about Views on the views section.

Any file included by default inside these folders is needed to make the application work. The rest of the folders in the `app` folder are just files you won't need to worry about. In the `config` folder you will have the following:

* `routes.json`: This file will include all the routes for your application. Every time you create a new view, controller or action you will need to specify how the routing will behave. This file is used to make 'pretty' links. Read more about routes in the Routes section.

In the `db` folder you have:

* `migrations`: This folder will contain all the migrations for the database. Read more about migrations in the migrations section.

In the `lib` folder you will have the following:

* `flavours`: This folder will contain any external PHP libraries. This is not developed at the moment.

In the `public` folder you will have all the Javascript, CSS, Fonts and Images needed for the project.

### Convention
Vinum makes development really easy, fast and easy if the development convention is followed. This is very similar to *Ruby on Rails* conventions.

* **File names:** When creating a new object, let say Book for example, you will need to keep everything the same. This means calling the model `book.php`, the controller `book_controller.php`, the database table will be called `Book` and the view folder will be called `book`. Also, if you need a page to show all the books from the database and you call that action inside `book_controller.php` something like `all_books`, then you are recommended to call a file inside `app/views/book` called `all_books.php`.

* **Database Primary Keys:** To make the development very fast, code readable and maintainable, you want to use a primary key for all database tables called `id`. This does not mean you cannot have more primary keys. 

*  **Database Associations:** If the `Book` table has a category which references to a table called `Category`, then you will need the foreign key on the table `Book` table called `category_id`. This comes very handy when retrieving information about a model and all their associations as objects, instead of as just raw data.

### Migrations
Migrations are the way to keep track of all the changes you have done to the database. This way, if you wipe the database by mistake, buy a new computer or work with multiple people on the same project, you will always have the same database structure. Every time you want a new table, change a column or any change, you will have to generate a new migration file via the command line, go to the new file generated and write your SQL code there. After you do so, you can go to the command like and migrate the database using `bin/vinum db:migrate`. 

If everything is successful, you won't see any errors displayed on the screen and the changes will be made. Remember that every time you create a new model or resource, a new migration file will be created and you will need to create a table with the same name as the model.

### Models
The models are the classes of your applications. You will write any methods there to manipulate information or do any sort of stuff. The file will look something like this assuming you are creating a `Book` model.

``` php
<?php

  class Book extends Application {
    
    // method to reserve a book
    public function reserve() {
	    $this->reserved = true;
	    $this.save
    }

  }

 ?>
```
All models will inherit from `Application`, which itself will inhert from `Vinum`. This is useful to give you certain functionality talked about below. If you want to add any generic methods to all models, you will do that in the `Application` model. 

##### Methods
I am going to assume there is model called `Book`. If you use the right convention talked about previously, you will get functionality like:
``` php
// Get all book objects from the database in an array
$all_books = Book::all();

// Get a specify book passing the id
$book = Book::find(id);

// Get books with certain conditions
$books = Book::where("reserved = false");

// Get the number of all Books in the database
$number_of_books = Book::count();

// Also, get the number of books with certain condition
$number_of_reserved_books = Book::count("reserved = true");

// To update attributes of a book object
$book->update_attributes(array("reserved" => 1));

// Get the last book
$last_book = Book::last();

// Get the first book
$first_book = Book::first();

// Delete a book from the database
$book->destroy();

// Save a new or updated object to the database
$book->reserved = true;
$book->save_record();


```

### Controllers

### Views

### Routes

### Rendering Information
