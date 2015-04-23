<div id="error-list">
  <ul>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'errors_alldoors.php')) { echo 'active'; } ?>">
      <a href="errors_alldoors.php">
        <img src="../assets/images/img-trans.png" >
        <label>All doors</label>
      </a>
    </li>
     <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'errors_period.php')) { echo 'active'; } ?>">
      <a href="errors_period.php">
        <img src="../assets/images/img-trans.png" >
        <label>Period</label>
      </a>
    </li>

  </ul>
</div>

