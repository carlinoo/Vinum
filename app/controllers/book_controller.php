<?php

  class BookController extends ApplicationController {

    // This action will display all the books available in the bookstore
    public function index() {

      // Get the page from the URL
      if (!isset($_GET['page']) || (int)$_GET['page'] < 1) {
        $page = 1;
      } else {
        $page = (int)filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT);
      }


      // If they are searching for a specific book
      if (isset($_GET['category']) && isset($_GET['search_input'])) {
        // Create variables to use on the view

        if ($_GET['category'] != '') {
          $category = Category::find(filter_input(INPUT_GET, 'category'), 'description');
          $cat_clause = "category_id = " . (int)$category->id;
        } else {
          $cat_clause = "1 = 1";
        }

        // Check if the search_input is set
        if ($_GET['search_input'] != '') {
          $search_input = filter_input(INPUT_GET, 'search_input');
          $search_clause = "(author LIKE '%" . $search_input . "%' OR title LIKE '%" . $search_input . "%')";
        } else {
          $search_clause = "1 = 1";
        }
      } else {
        $search_clause = "1 = 1";
        $cat_clause = "1 = 1";
      }

      // Add the limits
      $limit = " LIMIT 6 OFFSET " . ($page - 1) * 6;

      // Get the books with category and author or title
      $books = Book::where($cat_clause . " AND " . $search_clause . $limit);
      $number_of_books = Book::count($cat_clause . " AND " . $search_clause);

      $number_of_pages = ceil($number_of_books/6);
      // We set if there is a next_page or if there is a previous_page
      if ($page == 1) {
        $previous_page = false;
      } else {
        $previous_page = true;
      }

      if (($number_of_pages - $page) == 0) {
        $next_page = false;
      } else {
        $next_page = true;
      }


      $categories = Category::all();

      require_once('app/views/book/index.php');
    }



    // This function will reserve a book by the current user
    public function reserve($id = null) {
      model('reservation');

      if ($id == null) {
        redirect_to('book/index');
        return;
      }

      $book = Book::find((int)$id);

      // If there is no book on the database
      if (!$book) {
        redirect_to('book/index');
        return;
      }

      // If the book is reserved already
      if ($book->reserved) {
        redirect_to('book/index');
        return;
      }

      $reservation = Reservation::find($book->ISBN, 'ISBN');

      // If the reservation doesnt exist on the database, create it
      if (!$reservation) {
        $reservation = new Reservation();
      }

      $reservation->ISBN = $book->ISBN;
      $reservation->username = current_user()->username;
      $reservation->reserved_at = date('Y-m-d H:i:s');

      $reservation->save_record('ISBN');

      // Update the reservation on the book object

      $book->reserved = 1;
      $book->update_record('ISBN');

      redirect_to('book/index');
    }




    // This method will unreserve a book
    public function unreserve($id = null) {
      model('reservation');

      if ($id == null) {
        redirect_to('reservation/index');
        return;
      }

      $book = Book::find((int)$id);
      $reservation = Reservation::find($book->ISBN, 'ISBN');

      // If the book is not reserved
      if (!$book->reserved) {

        // If there is a reservation with that book, destroy the reservation
        if ($reservation != null) {
          $reservation->destroy();
        }

        redirect_to('reservation/index');
        return;
      }

      // Unreserve the book and update the record
      $book->reserved = false;
      $book->update_record('ISBN');

      // We delete the reservation
      if ($reservation != null) {
        $reservation->destroy();
      }

      redirect_to('reservation/index');
    }
  }

 ?>
