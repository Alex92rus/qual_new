<?PHP
    $isLoggedIn = false;
    session_start();
    if (!(isset($_SESSION['login']) && $_SESSION['login'] != '') && !(strpos($_SERVER['REQUEST_URI'],"index.php")))   {
      header ("Location: index.php");
    } else {
    $isLoggedIn = true;
    }
    ?>

<nav class="top-bar" data-topbar role="navigation" data-options="sticky_on: [large,medium,small]">
  <ul class="title-area">
    <li class="name">
      <h1><a href="<?php if ($isLoggedIn) { echo "bp.php"; } else {echo "index.php";} ?>"><img src="../assets/images/logo.png" style="  height: 80%; margin-top: 3px;"></a>
      </h1>
    </li>
  </ul>
  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li><a href="<?php if ($isLoggedIn) { echo "bp.php"; } else {echo "index.php";} ?>">Home</a></li>
      <li><a href="about.php" >About</a></li>
      <li><a href="contact.php">Contact</a></li>
      <li><a href="help.php">Help</a></li>
      <li><a href="http://qualoccupy.freeforums.net">Forum</a></li>
      <?php if ($isLoggedIn) { echo '<li><a href="logout.php"> Logout </a></li>'; } ?>
    </ul>
  </section>
</nav>