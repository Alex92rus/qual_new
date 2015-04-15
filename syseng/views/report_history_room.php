<?php
//history of room between 2 date
//include_once('../include/dbconnectPDO.php');
include('../include/get_rooms.php');

if(!isset($history)) {   $history[1]["event_time"] = "NotSet"; $history[1]["transition"] = Null; $history[1]["Confidence"] = Null;}

  //phpinfo(INFO_VARIABLES);

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['submit']))):
  if (isset($_POST['startdate'])) { $startdate = $_POST['startdate']; }
  if (isset($_POST['enddate'])) { $enddate = $_POST['enddate']; }
  if (isset($_POST['referroom'])) { $referroom = $_POST['referroom']; }
 

  include('../include/get_history_room.php');

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
                      <legend>Current Occupancy Status</legend>
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
                <legend>Historic Occupancy</legend>
                <form name="roomhistory" id="roomhistory" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="row">
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">Start:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="startdate" id="startdate" required placeholder="DD/MM/YYYY" value="<?php if (isset($startdate)) { echo $startdate; } ?>">
                        </div>
                      </div>
                    </div>
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">End:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="enddate" id="enddate" required  placeholder="DD/MM/YYYY"  value="<?php if (isset($enddate)) { echo $enddate; } ?>">
                        </div>
                      </div>
                    </div>
                    <div class="large-2 columns">
                      <select name="referroom" id="referroom" required >
                        <?php
                          $nr = 1;
                          while($nr <= $rNum) {
                            echo "<option value='".$roomList[$nr]['RoomId']."'>".$roomList[$nr]['RoomId']."</option>\n";
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
                    <div class="large-12 columns">
                      <div id="hChart" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
                    </div>
                    <div class="row">
                      <div class="large-10 large-centered columns">
                        <table class="table table-striped table-bordered table-condensed hover" style="width:100%;">
<!--                         <table style="width:100%;" class="table table-striped table-bordered table-condensed"> -->
                           <tr>
                            <th width="300" span=2>RoomID:<?php echo isset($referroom)?$referroom:"Choose room"; ?></th>
                          </tr>
                          <tr>
                            <th width="200">Date</th>
                            <th width="100">Occupancy</th>
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

  <!-- cutom functions -->
    <script>
     AmCharts.loadJSON = function(url) {
        // create the request
        if (window.XMLHttpRequest) {
          // IE7+, Firefox, Chrome, Opera, Safari
          var request = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          var request = new ActiveXObject('Microsoft.XMLHTTP');
        }

        // load it
        // the last "false" parameter ensures that our code will wait before the
        // data is loaded
        request.open('GET', url, false);
        request.send();

        // parse adn return the output
        return eval(request.responseText);
      };
    </script>

    <script>
      var chart;

      // create chart
      AmCharts.ready(function() {

        // load the data
        var chartData = AmCharts.loadJSON('../include/graph_data_history.php');

        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.pathToImages = "http://www.amcharts.com/lib/images/";
        chart.dataProvider = chartData;
        chart.categoryField = "nr";
        chart.angle = 30;
        chart.depth3D = 15;
        //chart.dataDateFormat = "YYYY-MM-DD";

        // GRAPHS

        var graph1 = new AmCharts.AmGraph();
        graph1.valueField = "transition";
        //graph1.bullet = "round";
       // graph1.bulletBorderColor = "#FFFFFF";
       // graph1.bulletBorderThickness = 2;
        graph1.lineThickness = 2;
        graph1.lineAlpha = 0.5;
        //graph1.type = "line";
        graph1.type = "column";
        graph1.fillAlphas = 0.8;


        chart.addGraph(graph1);

        // CATEGORY AXIS
        //chart.categoryAxis.parseDates = true;

        // WRITE
        chart.write("hChart");
      });

    </script>

  </body>
</html>