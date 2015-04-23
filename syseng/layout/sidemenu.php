<!-- Menu -->
<div class="icon-bar large-vertical medium-vertical five-up left" id="sidemenu">
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'bp.php')) { echo 'active'; } ?>" href="bp.php">
    <img src="../lib/foundation/img/fi-home.svg" >
    <label>Building Plan</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'report')) { echo 'active'; } ?>" href="report.php">
    <img src="../lib/foundation/img/fi-bookmark.svg" >
    <label>Reports</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'maintenance.php')) { echo 'active'; } ?>" href="maintenance.php">
    <img src="../lib/foundation/img/fi-info.svg" >
    <label>Maintnance</label>
  </a>
  <a class="item <?php if (strpos($_SERVER['REQUEST_URI'],'error')) { echo 'active'; } ?>" href="errors_alldoors.php">
    <img src="../lib/foundation/img/fi-mail.svg" >
    <label>Error Handling</label>
  </a>
</div>