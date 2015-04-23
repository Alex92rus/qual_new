var chart;
// create chart
AmCharts.ready(function() {
    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    
    // load the data
    var chartData = new Array();
    loadJSON('../include/graph_history_error_door_usage.php', function(result) {
        chart.dataProvider = result;
        chart.validateData();
    });
    
    chart.pathToImages = "http://www.amcharts.com/lib/images/";
    chart.dataProvider = chartData;
    chart.categoryField = "doorId";
    chart.angle = 30;
    chart.depth3D = 15;
    //chart.dataDateFormat = "YYYY-MM-DD";
    // GRAPHS
    var graph1 = new AmCharts.AmGraph();
    graph1.valueField = "corrections";
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

$(function() {
    $("#startdate").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#enddate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#enddate").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#startdate").datepicker("option", "maxDate", selectedDate);
        }
    });
});