
<div class="row">
  <form id="search_form" action="<?php echo path('book/index'); ?>" method="get">
    <div class="col s12 m5 l4 offset-m1 offset-l2">
      <div class="card search-card">
        <input type="text" placeholder="Search by Title or Author" name="search_input" value="<?php if(isset($search_input)) { echo $search_input; } ?>" id="search_input" class="browser-default search_input"></input>
        <button class="right" id="search_button" ><i class="material-icons">search</i></button>
      </div>
    </div>
    <div class="col s12 m5 l4">
      <div class="card select-card">
        <select onchange="$('#search_form').submit();" id="category" name="category" class="browser-default">
          <option value="">All Categories</option>

          <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->description; ?>" <?php if (isset($_GET['category']) && $_GET['category'] == $category->description) { echo 'selected'; } ?>><?php echo $category->description; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </form>
</div>

<div class="row" id='all_books'>

  <?php foreach ($books as $book): ?>
    <div class="col s6 m6 l3">
      <div class="card book-card">
        <div class="card-image">
            <img class="activator waves-effect waves-block waves-light" src="<?php echo image_path('book-cover.svg'); ?>">

          </div>

          <div class="card-content">
            <span class="card-title truncate"><?php echo $book->title; ?></span>
            <p><?php echo $book->author; ?></p>
            <p>Edition <?php echo $book->edition; ?></p>
            <p><?php echo $book->year; ?></p>
          </div>

          <div class="card-action right-align">
            <?php if (!$book->is_reserved()) { ?>
              <a class="btn" href='<?php echo path('book/reserve/' . $book->id . ''); ?>'>Book</a>
            <?php } else { ?>
              <a class="btn-flat not-clickable">Unavailable</a>
            <?php } ?>
          </div>

          <div class="card-reveal">
            <span class="card-title grey-text text-darken-4"><?php echo $book->title; ?><i class="material-icons right">close</i></span>
            <p class=" grey-text text-darken-4"><b>Author:</b> <?php echo $book->author; ?></p>
            <p class=" grey-text text-darken-4"><b>Edition</b> <?php echo $book->edition; ?></p>
            <p class=" grey-text text-darken-4"><b>Year:</b> <?php echo $book->year; ?></p>
            <p class=" grey-text text-darken-4"><b>ISBN:</b> <?php echo $book->ISBN; ?></p>
            <p class=" grey-text text-darken-4"><b>Category:</b> <?php echo $book->category->description; ?></p>
          </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<ul class="pagination center-align">
  <li class="waves-effect <?php echo_if('disabled', !$previous_page); ?>"><a class="<?php echo_if('clickable', $next_page); ?>" onclick="replace_url_param('page', Number(getURLParameter('page')) - 1);"><i class="material-icons">chevron_left</i></a></li>
  <?php
  for($i = 1; $i < $number_of_pages + 1; $i++) {
    echo "<li class='" . return_if('active', $i == $page) . "'><a class='clickable' onclick=\"replace_url_param('page', $i);\">" . $i . "</a></li>";
  }
  ?>
  <li class="waves-effect <?php echo_if('disabled', !$next_page); ?>"><a class="<?php echo_if('clickable', $next_page); ?>" onclick="replace_url_param('page', Number(getURLParameter('page')) + 1);"><i class="material-icons">chevron_right</i></a></li>
  </ul>
