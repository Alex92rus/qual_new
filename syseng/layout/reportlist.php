<div id="report-list">
  <ul>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report.php')) { echo 'active'; } ?>">
      <a href="report.php">
        <img src="../assets/images/img-trans.png" >
        <label>Current</label>
      </a>
    </li>
     <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_pastmoment.php')) { echo 'active'; } ?>">
      <a href="report_pastmoment.php">
        <img src="../assets/images/img-trans.png" >
        <label>Past Moment</label>
      </a>
    </li>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_convex.php')) { echo 'active'; } ?>">
      <a href="report_convex.php">
        <img src="../assets/images/img-trans.png" >
        <label>Convex</label>
      </a>
    </li>

    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_convex_weekly.php')) { echo 'active'; } ?>">
      <a href="report_convex_weekly.php">
        <img src="../assets/images/img-trans.png" >
        <label>Convex Weekly</label>
      </a>
    </li>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_history_room.php')) { echo 'active'; } ?>">
      <a href="report_history_room.php">
        <img src="../assets/images/img-trans.png" >
        <label>History</label>
      </a>
    </li>

    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_room_business.php')) { echo 'active'; } ?>">
      <a href="report_room_business.php">
        <img src="../assets/images/img-trans.png" >
        <label>Room Business</label>
      </a>
    </li>

    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_door_inspect.php')) { echo 'active'; } ?>">
      <a href="report_door_inspect.php">
        <img src="../assets/images/img-trans.png" >
        <label>Inspect A Door</label>
      </a>
    </li>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_door_usage.php')) { echo 'active'; } ?>">
      <a href="report_door_usage.php">
        <img src="../assets/images/img-trans.png" >
        <label>Door Usage</label>
      </a>
    </li>


  </ul>
</div>

