<!-- Menu -->
<div class="icon-bar large-vertical medium-vertical five-up left" id="sidemenu">
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'bp.php')) { echo 'active'; } ?>" href="bp.php">
    <img src="../lib/foundation/img/fi-home.svg" >
    <label>Bldin Pln</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'report.php')) { echo 'active'; } ?>" href="report.php">
    <img src="../lib/foundation/img/fi-bookmark.svg" >
    <label>Rprts</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'maintenance.php')) { echo 'active'; } ?>" href="maintenance.php">
    <img src="../lib/foundation/img/fi-info.svg" >
    <label>Mtnances</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'errorhandling.php')) { echo 'active'; } ?>" href="errorhandling.php">
    <img src="../lib/foundation/img/fi-mail.svg" >
    <label>Err Hdl</label>
  </a>
</div>