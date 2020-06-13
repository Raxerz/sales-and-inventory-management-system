<html>
<head>
      <script src="../plugins/sweetalert/sweetalert.min.js"></script>
</head>
<?php include 'header_login.php';?>

  <body class="login">
      <?php if($_REQUEST['error']==1){
      ?>
      <script type='text/javascript'>swal('Invalid!','Please enter the correct username and password','error');</script>
    <?php }
  ?>
    <div>


      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method = "POST" action = "login.php">
              <h1>Administrator Login</h1>
              <div>
                <input type="text" name = "username" class="form-control" placeholder="Username" required="true" />
              </div>
              <div>
                <input type="password" name = "password" class="form-control" placeholder="Password" required="true" />
              </div>
              <div>
                <button  class="btn btn-block btn-warning" name = "login"> Log in</button>

              </div>

              <div class="clearfix"></div>

              <div class="separator">


                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Sales and Inventory System </h1>
                  <p>©2020 All Rights Reserved Rajath</p>
                </div>
              </div>
            </form>
          </section>
        </div>


      </div>
    </div>
  </body>
</html>
