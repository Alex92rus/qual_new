<div id="report-list">
  <ul>
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report.php')) { echo 'active'; } ?>">
      <a href="report.php">
        <img src="../assets/images/img-trans.png" >
        <label>Current Occupancy</label>
      </a>
    </li>
     <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_pastoccupancy.php')) { echo 'active'; } ?>">
      <a href="report_pastoccupancy.php">
        <img src="../assets/images/img-trans.png" >
        <label>Past Occupancy</label>
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
    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_transitions_room.php')) { echo 'active'; } ?>">
      <a href="report_transitions_room.php">
        <img src="../assets/images/img-trans.png" >
        <label>Transitions for Room</label>
      </a>
    </li>

    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_room_movements.php')) { echo 'active'; } ?>">
      <a href="report_room_movements.php">
        <img src="../assets/images/img-trans.png" >
        <label>Movements in Room</label>
      </a>
    </li>

    <li class="<?php if (strpos($_SERVER['REQUEST_URI'],'report_door_inspect.php')) { echo 'active'; } ?>">
      <a href="report_door_inspect.php">
        <img src="../assets/images/img-trans.png" >
        <label>Movements through Door</label>
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

