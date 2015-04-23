<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['submit']))) {
  if (isset($_POST['startdate'])) { $startdate = $_POST['startdate'];  }
 
} else {
  $ds = new datetime();
  $startdate = $ds->format('Y-m-d H:i:s');
}

//var_dump($date_to);

if(!isset($pdo)) { include('../include/dbconnectPDO.php'); }
if(!isset($roomList)) { include('../include/get_rooms.php'); }
include('../include/get_currentstate_inpast.php');
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
                <legend>Occupancy Summary at given time</legend>
                <form name="roomhistory" id="roomhistory" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="row">
                    <div class="large-8 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">At DateTime:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="startdate" id="startdate" required value="<?php if (isset($startdate)) { echo $startdate; } ?>">

                        </div>
                      </div>
                    </div>

                    <div class="large-2 columns">
                      <input type="submit" name="submit" value="Refresh Table" class="button tiny">
                    </div>
                  </div>
                </form>

                <div class="row">
                  <div class="large-10 large-centered columns">
                    <div id="cChart" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
                  </div>
                </div>
                

                <div class="row">
                      <div class="large-10 large-centered columns">
                        <table style="width:100%;">
                          <tr>
                            <th width="150">For DataTime: </th>
                            <th width="300"><?php echo $startdate; ?></th>
                          </tr>
                          <tr>
                            <th width="150">Room</th>
                            <th width="150">Occupancy</th>
                            <th width="150">Confidence</th>
                      
                          </tr>
                          <?php
                            $nr = 1;
                            while($nr <= $rn) {
                              echo "<tr>".
                                  "<td>".$rooms[$nr]['RoomId']."</td>"."<td>".$rooms[$nr]['NumberOfPeople']."</td>".
                                  "<td>".$rooms[$nr]['Confidence']."</td>".
                                "</tr>";
                              $nr++ ;
                            }
                          ?>
                        </table>
                      </div>
                </div>
              </fieldset>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include("../layout/reportlist.php"); ?>
    <?php include("../layout/footer.php");?>
    <script type="text/javascript" src="../assets/js/progressbar.js"></script>
    <script type="text/javascript" src="../lib/amcharts/amcharts.js"></script>
    <script type="text/javascript" src="../lib/amcharts/serial.js"></script>
    <script type="text/javascript" src="../assets/js/report.js"></script>

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
        //var chartData = new AmCharts.loadJSON('../include/graph_data_cs_past.php');
        var chartData = <?php include('../include/graph_data_cs_past.php');?>;

        // SERIAL CHART
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
      graph1.valueField = "NumberOfPeople";
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
      chart.write("cChart");
    });
    </script>



 

  </body>
</html>