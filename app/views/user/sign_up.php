<div class="valign-wrapper index_home" style="width:100%;min-height:100%;background-color:#bdc3c7;">
  <div class="valign" style="width:100%;">
    <div class="container">
     <div class="row">
        <div class="col s12 m6 offset-m3">
          <div class="card">
            <div class="card-content">
               <span class="card-title black-text center">Sign Up</span>
            </div>

            <form action="<?php echo path('user/sign_up'); ?>" method="post">

              <?php // Show notice if there's an error
                if (isset($notice)) {
                  echo "<h6 style='color: red; margin-bottom: 30px' class='center-align'>$notice</h6>";
                }
                ?>


              <div class="container">
                <div class="input-field">
                  <label for='username' class="activate">Username</label>
                  <input value="<?php if (isset($_POST['username'])) { echo clean($_POST['username']); } ?>" type="text" name="username" id='username'>
                </div>

                <div class="input-field">
                  <label for='password' class="activate">Password</label>
                  <input value="<?php if (isset($_POST['password'])) { echo clean($_POST['password']); } ?>" type="password" name="password" id='password'>
                </div>

                <div class="input-field">
                  <label for='first_name' class="activate">First Name</label>
                  <input value="<?php if (isset($_POST['first_name'])) { echo clean($_POST['first_name']); } ?>" type="text" name="first_name" id='first_name'>
                </div>

                <div class="input-field">
                  <label for='last_name' class="activate">Last Name</label>
                  <input value="<?php if (isset($_POST['last_name'])) { echo clean($_POST['last_name']); } ?>" type="text" name="last_name" id='last_name'>
                </div>

                <div class="input-field">
                  <label for='address_line_one' class="activate">Address Line One</label>
                  <input value="<?php if (isset($_POST['address_line_one'])) { echo clean($_POST['address_line_one']); } ?>" type="text" name="address_line_one" id='address_line_one'>
                </div>

                <div class="input-field">
                  <label for='address_line_two' class="activate">Address Line Two</label>
                  <input value="<?php if (isset($_POST['address_line_two'])) { echo clean($_POST['address_line_two']); } ?>" type="text" name="address_line_two" id='address_line_two'>
                </div>

                <div class="input-field">
                  <label for='city' class="activate">City</label>
                  <input value="<?php if (isset($_POST['city'])) { echo clean($_POST['city']); } ?>" type="text" name="city" id='city'>
                </div>

                <div class="input-field">
                  <label for='telephone' class="activate">Telephone</label>
                  <input value="<?php if (isset($_POST['telephone'])) { echo clean($_POST['telephone']); } ?>" type="text" name="telephone" id='telephone'>
                </div>

                <div class="input-field">
                  <label for='mobile' class="activate">Mobile</label>
                  <input value="<?php if (isset($_POST['mobile'])) { echo clean($_POST['mobile']); } ?>" type="text" name="mobile" id='mobile'>
                </div>
              </div>

              <br>
              <div class="container">
                <button type="submit" class="waves-effect waves-light btn">Register</button>
                <br><br>
                <a href="<?php echo path('user/log_in'); ?>" class="btn-flat">Log In</a>
              </div>
              <br>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
