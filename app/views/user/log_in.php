<div class="valign-wrapper index_home" style="width:100%;min-height:100%;background-color:#bdc3c7;">
  <div class="valign" style="width:100%;">
    <div class="container">
     <div class="row">
        <div class="col s12 m6 offset-m3">
          <div class="card">
            <div class="card-content">
               <span class="card-title black-text center">Log In</span>
            </div>

            <form action="<?php echo path('user/log_in'); ?>" method="post">
              <?php // Show notice if there's an error
                if (isset($notice)) {
                  echo "<h6 style='color: red; margin-bottom: 30px' class='center-align'>$notice</h6>";
                }
                ?>
              <div class="container">
                <div class="input-field">
                  <label for='username' class="activate">Username</label>
                  <input type="text" name="username" id='username'>
                </div>

                <div class="input-field">
                  <label for='password' class="activate">Password</label>
                  <input type="password" name="password" id='password'>
                </div>

              </div>
              <br>
              <div class="container">
                <button type="submit" class="waves-effect waves-light btn">Log In</button>
                <br><br>
                <a href="<?php echo path('user/sign_up'); ?>" class="btn-flat">Sign Up</a>
              </div>
              <br>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
