<?php
//history of room between 2 date
//include_once('../include/dbconnectPDO.php');
include('../include/get_doors.php');
include('../include/get_occ.php');

if(!isset($history)) {   $history[1]["event_time"] = "NotSet"; $history[1]["transition"] = Null; }

  //phpinfo(INFO_VARIABLES);

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['submit']))):
  if (isset($_POST['startdate'])) { $startdate = $_POST['startdate']; }
  if (isset($_POST['enddate'])) { $enddate = $_POST['enddate']; }
  if (isset($_POST['referdoor'])) { $referdoor = $_POST['referdoor']; }
 

  include('../include/get_history_door.php');

endif; //form submitted
?>


<html>
  <?php
  include("../layout/head.php");
  ?>
  <body>
    <?php include("../layout/header.php");?>
    <div class="wrapper">
      <?php include("../layout/sidemenu.php");?>
      <div class="row" id="tall-div">
        <div class="large-10 medium-10 small-10 columns">
          <div class="row">
            <div class="large-12 medium-12 small-12 columns">
              <fieldset>
                <legend>Overview</legend>
                <div class="row">
                  <div class="large-7 columns" style="padding-top: 3em; display: inline-table; margin-left: -275px; padding-left: 40%;">
                    <canvas id="clock" style="float:left;"></canvas>
                    <div id="time" style="font-size: 2em;display: table-cell;vertical-align: middle;  padding-left: 1em;"></div>
                  </div>
                  <div class="large-5 columns">
                    <fieldset style="  background-color: transparent;">
                      <legend>Door use for period</legend>
                      <div class="row">
                        <div id="current-status" class="large-3 large-centered columns">
                          <div id="current-number"><?php echo $totOcc ?></div>
                        </div>
                      </div>
                      
                    </fieldset>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>

          <div class="row">
            <div class="large-12 medium-12 small-12 columns">
              <fieldset>
                <legend>Number of people moving through selected door/doors for the selected period of time</legend>
                <form name="roomhistory" id="roomhistory" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="row">
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">Start:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="startdate" id="startdate"  placeholder="DD/MM/YYYY" value="<?php if (isset($startdate)) { echo $startdate; } ?>">
                        </div>
                      </div>
                    </div>
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">End:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="enddate" id="enddate"   placeholder="DD/MM/YYYY"  value="<?php if (isset($enddate)) { echo $enddate; } ?>">
                        </div>
                      </div>
                    </div>
                    <div class="large-2 columns">
                      <select name="referdoor" id="referdoor" required >
                        <?php
                          $nr = 1;
                          while($nr <= $rNum) {
                            echo "<option value='".$doorList[$nr]['doorId']."'>".$doorList[$nr]['doorId']."</option>\n";
                            $nr++ ;
                          }
                        ?>
                      </select>
                    </div>
                    <div class="large-2 columns">
                      <input type="submit" name="submit" value="Refresh Table" class="button tiny">
                    </div>
                  </div>
                  <div class="row">
                    <div class="row">
                      <div class="large-10 large-centered columns">
                        <table class="table table-striped table-bordered table-condensed table-hover" style="width:100%;">
<!--                         <table style="width:100%;" class="table table-striped table-bordered table-condensed"> -->
                           <tr>
                            <th width="300" style="font-size: 1.5em" span=2>DoorID:<?php echo isset($referdoor)?$referdoor." passed for the period: ".$total:"Choose door"; ?></th>
                          </tr>
                          <tr>
                            <th width="200">Date</th>
                            <th width="100">Passed</th>
                          </tr>
                          <?php
                          if(isset($history)) {
                            for($nr=1;$nr<=count($history);$nr++) {
                              $date=$history[$nr]['event_time'];
                              $moved=$history[$nr]['transition'];

                              echo '<tr>'.
                                '<td>'.$date.'</td>'.
                                '<td>'.$moved.'</td>'.
                                '</tr> ';
                            }
                          }

                          ?>
                        </table>
                      </div>
                    </div>
                  </div>
                </form>
              </fieldset>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <?php include("../layout/footer.php");?>
    <script type="text/javascript" src="../assets/js/progressbar.js"></script>
    <script type="text/javascript" src="../lib/amcharts/amcharts.js"></script>
    <script type="text/javascript" src="../lib/amcharts/serial.js"></script>
    <script type="text/javascript" src="../assets/js/report.js"></script>

 
  </body>
</html>