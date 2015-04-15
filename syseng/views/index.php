<html>
  <?php include("../layout/head.php");?>
  <body>
    <?php 

    include("../layout/header.php");

    $uname = "";
    $pword = "";
    $errorMessage = "";
    if ((isset($_SESSION['login']) && $_SESSION['login'] != '')) {
      header ("Location: bp.php"); 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST["username"];
    $pword = $_POST["password"];
    if (($uname == "demo") && ($pword == "demo")) {
    $errorMessage = "";
    
    $_SESSION["login"] = 1;
    header("Location: bp.php");
    } else {
    $errorMessage = "You may have entered a wrong user name or password.";
    $_SESSION["login"] = "";
    }
    unset($_POST);
    }
    ?>
    
    <div class="row">
      <div class="large-6 large-centered medium-6 medium-centered small-6 small-centered columns" style="display:table; height:100%;">
        <form action="" method="POST" id="login-form">
          <div class="row">
            <div class="large-12 columns">
              <h2 class="text-graphite" style="text-align:center; padding-bottom:12px;">Admin Login</h2>
            </div>
          </div>
            <div class="row">
              <?php if ($errorMessage != "") { ?>
              <div data-alert class="alert-box alert round large-10 large-push-1 medium-10 medium-push-1 small-10 small-push-1  columns">
                <?php echo $errorMessage; ?>
              </div>
              <?php } ?>
            </div>
            <div class="row">
              <div class="large-12 medium-12 small-12 columns">
                <label class="text-graphite"> Username
                  <input type="text" placeholder="enter user name here" name="username">
                </label>
              </div>
            </div>
            <div class="row">
              <div class="large-12 medium-12 small-12 columns">
                <label class="text-graphite"> Password
                  <input type="password" placeholder="enter user password here" name="password">
                </label>
              </div>
            </div>
            <div class="row">
              <div class="large-12 medium-12 small-12 columns">
                <input type="submit" value="Login">
              </div>
            </div>
        </form>
      </div>
    </div>
    
    <?php include("../layout/footer.php");?>

    
  </body>
</html>