<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['submit']))) {
  if (isset($_POST['startdate'])) { $startdate = $_POST['startdate'];  }
  if (isset($_POST['enddate'])) { $enddate = $_POST['enddate'];  }
  if (isset($_POST['referroom'])) { 
    $referroom = $_POST['referroom']; 
    $roomId = $referroom; 
  } 
 
} else {


  $ds = new datetime();
  $startdate = $ds->format('Y-m-d H:i:s');
  $enddate = $ds->format('Y-m-d H:i:s'); 
  $referroom = "NotSet";
  unset( $history ); // Graph array for convex
}

include('../include/get_occ.php');

 if(!isset($pdo)) { include('../include/dbconnectPDO.php'); }
 if(!isset($roomList)) { include('../include/get_rooms.php'); }



if(!isset($pdo)) { include('../include/dbconnectPDO.php'); }
$sql = "SELECT `DefinedAt`, `IntervalInSeconds`, `WeeksToCount` FROM `convexdefinitions` ORDER BY 1 DESC LIMIT 1";
foreach ($pdo->query($sql, PDO::FETCH_ASSOC) as $row) {
  $IntervalInSeconds = $row['IntervalInSeconds'];
  $WeeksToCount = $row['WeeksToCount'];
}
$graphInterval = "H";
if ($IntervalInSeconds/60 < 60) { $graphInterval = "mm"; }

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
                <legend>Occupancy per intervals of day</legend>
                <form name="roomhistory" id="roomhistory" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                  <div class="row">
                    <div class="large-8 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">Start:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="startdate" id="startdate" required value="<?php if (isset($startdate)) { echo $startdate; } ?>">

                        </div>
                      </div>
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">End DateTime:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="enddate" id="enddate"  value="<?php if (isset($enddate)) { echo $enddate; } ?>">
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
                </form>

                <div class="row">
                  <div class="large-10 large-centered columns">
                    <div id="chartdiv" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
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
    <!-- Graph -->


    <script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/none.js"></script>




    <script>

var chartData = <?php include('../include/graph_convex.php');?>;

var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "none",
    "dataProvider":   chartData,
    "valueAxes": [{
        "axisAlpha": 0,
        "dashLength": 1,
        "position": "left",
        "title": "Occupants convex for Room "
    }],
    "graphs": [{
        "id": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": true,
        "valueField": "fromValue",
        "fillAlphas": 0
    }, {
        "fillAlphas": 0.2,
        "fillToGraph": "fromGraph",
        "lineAlpha": 0,
        "showBalloon": true,
        "valueField": "toValue"
    }, {
        "valueField": "value",
        "fillAlphas": 0
    }],
     "chartCursor": {
      "zoomable":true,
         "fullWidth":true,
         "cursorAlpha":0.1,
         "categoryBalloonEnabled":true
     },
    "dataDateTimeFormat": "YYYY-MM-DDTHH:mm:ss",
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "axisAlpha": 0,
           "minPeriod": "mm",
         "minHorizontalGap":1,
        "gridAlpha": 0,
        "tickLength": 0,
         "twoLineMode":true,
         "dateFormats":[ {
            period: 'ss',
            format: 'ss'
        }, {
            period: 'mm',
            format: 'mm'
        }, {
            period: 'hh',
            format: 'HH'
        }, {
            period: 'DD',
            format: 'DD'
        }, {
            period: 'WW',
            format: 'DD'
        }, {
            period: 'MM',
            format: 'MMM'
        }, {
            period: 'YYYY',
            format: 'YYYY'
        }]
    },
    "exportConfig": {
        "menuTop": "20px",
        "menuRight": "20px",
        "menuItems": [{
            "icon": 'http://www.amcharts.com/lib/3/images/export.png',
            "format": 'png'
        }]
    }
});

    </script>



 

  </body>
</html>