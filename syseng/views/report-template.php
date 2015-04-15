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
                          <div id="current-number">123</div>
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
                <legend>Current Occupancy Summary</legend>

                <div class="row">
                  <div class="large-10 large-centered columns">
                    <div id="cChart" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
                  </div>
                </div>
                

                <div class="row">
                      <div class="large-10 large-centered columns">
                        <table style="width:100%;">
                          <tr>
                            <th width="150">Room</th>
                            <th width="150">Occupancy</th>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                        </table>
                      </div>
                    </div>
              </fieldset>
              
            </div>
          </div>
          <div class="row">
            <div class="large-12 medium-12 small-12 columns">
              <fieldset>
                <legend>Historic Occupancy</legend>
                <form action="" method="GET">
                  <div class="row">
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">Start:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="startdate" id="startdate" required>
                        </div>
                      </div>
                    </div>
                    <div class="large-4 columns">
                      <div class="row collapse prefix-radius">
                        <div class="small-3 columns">
                          <span class="prefix">End:</span>
                        </div>
                        <div class="small-9 columns">
                          <input type="text" name="enddate" id="enddate" required>
                        </div>
                      </div>
                    </div>
                    <div class="large-2 columns">
                      <select>
                        <option value="100">Floor 1</option>
                        <option value="101">- Room 101</option>
                        <option value="102">- Room 102</option>
                        <option value="103">- Room 103</option>
                        <option value="104">- Room 104</option>
                        <option value="105">- Room 105</option>
                        <option value="106">- Room 106</option>
                        <option value="107">- Room 107</option>
                        <option value="108">- Room 108</option>
                        <option value="109">- Room 109</option>
                        <option value="110">- Room 110</option>
                        <option value="111">- Room 111</option>
                        <option value="112">- Room 112</option>
                      </select>
                    </div>
                    <div class="large-2 columns">
                      <input type="submit" value="Refresh Table" class="button tiny">
                    </div>
                  </div>
                  <div class="row">
                    <div class="large-12 columns">
                      <div id="hChart" style="width: 100%; height: 400px; background-color: #FFFFFF;" ></div>
                    </div>
                    <div class="row">
                      <div class="large-10 large-centered columns">
                        <table style="width:100%;">
                          <tr>
                            <th width="200">Date</th>
                            <th width="100">Occupancy</th>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                          </tr>
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