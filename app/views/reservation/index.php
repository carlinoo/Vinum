<div class="row">
  <?php foreach ($reservations as $reservation): ?>

  <div class="col s6 m6 l3">
    <div class="card book-card">
      <div class="card-image">
        <img class="activator waves-effect waves-block waves-light" src="<?php echo image_path('book-cover.svg'); ?>">
      </div>

      <div class="card-content">
        <span class="card-title truncate"><?php echo $reservation->book->title; ?></span>
        <p><?php echo $reservation->book->author; ?></p>
        <p>Edition <?php echo $reservation->book->edition; ?></p>
        <p><?php echo $reservation->book->year; ?></p>
      </div>

      <div class="card-action right-align">
        <a href="<?php echo path('book/unreserve/' . $reservation->book->id . ''); ?>" class="btn">Un-Reserve</a>
      </div>

      <div class="card-reveal">
        <span class="card-title grey-text text-darken-4"><?php echo $reservation->book->title; ?><i class="material-icons right">close</i></span>
        <p class=" grey-text text-darken-4"><b>Author:</b> <?php echo $reservation->book->author; ?></p>
        <p class=" grey-text text-darken-4"><b>Edition</b> <?php echo $reservation->book->edition; ?></p>
        <p class=" grey-text text-darken-4"><b>Year:</b> <?php echo $reservation->book->year; ?></p>
        <p class=" grey-text text-darken-4"><b>ISBN:</b> <?php echo $reservation->book->ISBN; ?></p>
        <p class=" grey-text text-darken-4"><b>Category:</b> <?php echo $reservation->book->category->description; ?></p>
      </div>
    </div>
  </div>

  <?php endforeach; ?>

  <?php if (count($reservations) == 0) { ?>
    <div class="container">
      <div class="col s12">
        <br>
        <div class="card">
          <div class="card-content center-align">
            <span class="card-title">You do not have any Reservations at the moment.</span>
          </div>

          <div class="card-action">
            <a href="<?php echo path('book/index'); ?>">Find Books</a>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
