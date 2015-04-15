var cData = [{
    "category": "Room 101",
    "column-1": 8,
    "color": "#FF0F00"
}, {
    "category": "Room 102",
    "column-1": 6,
    "color": "#FF6600"
}, {
    "category": "Room 103",
    "column-1": 2,
    "color": "#FF9E01"
}];

var cChart = AmCharts.makeChart("cChart", {
    "type": "serial",
    "theme": "none",
    "pathToImages": "http://cdn.amcharts.com/lib/3/images/",
    "categoryField": "category",
    "startDuration": 1,
    "categoryAxis": {
        "gridPosition": "start"
    },
    "trendLines": [],
    "graphs": [{
        "balloonText": "[[category]]:[[value]]",
        "fillColorsField": "color",
        "fillAlphas": 0.9,
        "id": "AmGraph-1",
        "title": "graph 1",
        "type": "column",
        "valueField": "column-1"
    }],
    "guides": [],
    "valueAxes": [{
        "id": "ValueAxis-1",
        "title": "Current Occupancy"
    }],
    "allLabels": [],
    "balloon": {},
    "titles": [{
        "id": "Title-1",
        "size": 15,
        "text": "Current Occupancy in Floor 1"
    }],
    "dataProvider": cData,
    "amExport": {}
});

for (index in cData) {
    cData[index]["color"] = "#FF0625";
}


var hData = [{}];

var hChart = AmCharts.makeChart("hChart", {
    "type": "serial",
    "theme": "none",
    "pathToImages": "http://cdn.amcharts.com/lib/3/images/",
    "categoryField": "category",
    "startDuration": 1,
    "categoryAxis": {
        "gridPosition": "start"
    },
    "trendLines": [],
    "graphs": [{
        "balloonText": "[[category]]:[[value]]",
        "fillColorsField": "color",
        "fillAlphas": 0.9,
        "id": "AmGraph-1",
        "title": "graph 1",
        "type": "column",
        "valueField": "column-1"
    }],
    "guides": [],
    "valueAxes": [{
        "id": "ValueAxis-1",
        "title": "Current Occupancy"
    }],
    "allLabels": [],
    "balloon": {},
    "titles": [{
        "id": "Title-1",
        "size": 15,
        "text": "Current Occupancy in Floor 1"
    }],
    "dataProvider": hData,
    "amExport": {}
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


//Clock 
(function() {
    function checkTime(i) {
        return (i < 10) ? "0" + i : i;
    }

    function startTime() {
        var today = new Date(),
            h = checkTime(today.getHours()),
            m = checkTime(today.getMinutes()),
            s = checkTime(today.getSeconds());
        document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout(function() {
            startTime()
        }, 500);
    }
    startTime();
})();

//Clock Canvas
var canvas = document.getElementById("clock");
canvas.width = 200;
canvas.height = 200;
var ctx = canvas.getContext("2d");
var radius = canvas.height / 2;
ctx.translate(radius, radius);
radius = radius * 0.90;
setInterval(drawClock, 1000);

function drawClock() {
    drawFace(ctx, radius);
    drawNumbers(ctx, radius);
    drawTime(ctx, radius);
}

function drawFace(ctx, radius) {
    var grad;
    ctx.beginPath();
    ctx.arc(0, 0, radius, 0, 2 * Math.PI);
    ctx.fillStyle = 'white';
    ctx.fill();
    grad = ctx.createRadialGradient(0, 0, radius * 0.95, 0, 0, radius * 1.05);
    grad.addColorStop(0, '#333');
    grad.addColorStop(0.5, 'white');
    grad.addColorStop(1, '#333');
    ctx.strokeStyle = grad;
    ctx.lineWidth = radius * 0.1;
    ctx.stroke();
    ctx.beginPath();
    ctx.arc(0, 0, radius * 0.1, 0, 2 * Math.PI);
    ctx.fillStyle = '#333';
    ctx.fill();
}

function drawNumbers(ctx, radius) {
    var ang;
    var num;
    ctx.font = radius * 0.15 + "px arial";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    for (num = 1; num < 13; num++) {
        ang = num * Math.PI / 6;
        ctx.rotate(ang);
        ctx.translate(0, -radius * 0.85);
        ctx.rotate(-ang);
        ctx.fillText(num.toString(), 0, 0);
        ctx.rotate(ang);
        ctx.translate(0, radius * 0.85);
        ctx.rotate(-ang);
    }
}

function drawTime(ctx, radius) {
    var now = new Date();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    //hour
    hour = hour % 12;
    hour = (hour * Math.PI / 6) +
        (minute * Math.PI / (6 * 60)) +
        (second * Math.PI / (360 * 60));
    drawHand(ctx, hour, radius * 0.5, radius * 0.07);
    //minute
    minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
    drawHand(ctx, minute, radius * 0.8, radius * 0.07);
    // second
    second = (second * Math.PI / 30);
    drawHand(ctx, second, radius * 0.9, radius * 0.02);
}

function drawHand(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0, 0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
}


//current number indicator
var redColor = [252,91,63];
var orangeColor = [255,165,0];
var greenColor = [111,213,127];

// Interpolate value between two colors.
// Value is number from 0-1. 0 Means color A, 0.5 middle etc.
function interpolateColor(rgbA, rgbB, value) {
    if (value > 0.4 && value < 0.6) {
      value = value + 0.4;
    }
    var rDiff = rgbA[0] - rgbB[0];
    var gDiff = rgbA[1] - rgbB[1];
    var bDiff = rgbA[2] - rgbB[2];
    value = 1 - value;
    return [
        rgbB[0] + rDiff * value,
        rgbB[1] + gDiff * value,
        rgbB[2] + bDiff * value
    ];
}

function rgbArrayToString(rgb) {
    return 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
}

function barColor(progress) {
    var circleValue = circle.value();
    if (circleValue < 0.4) {
        if (progress < 0.4) {
            return interpolateColor(redColor, redColor, progress);
        } else if (progress > 0.6) {
            return interpolateColor(redColor, greenColor, progress);
        } else {
            return interpolateColor(redColor, orangeColor, progress);
        }
    } else if (circleValue > 0.6) {
        if (progress < 0.4) {
            return interpolateColor(redColor, greenColor, progress);
        } else if (progress > 0.6) {
            return interpolateColor(greenColor, greenColor, progress);
        } else {
            return interpolateColor(orangeColor, greenColor, progress);
        }
    } else if (progress < 0.4) {
        return interpolateColor(redColor, orangeColor, progress);
    } else if (progress > 0.6) {
        return interpolateColor(orangeColor, greenColor, progress);
    } else {
        return interpolateColor(orangeColor, orangeColor, progress);
    }

}

var element = document.getElementById('current-status');
var circle = new ProgressBar.Circle(element, {
    color: startColor,
    trailColor: '#eee',
    trailWidth: 1,
    duration: 2000,
    easing: 'linear',
    strokeWidth: 5,

    // Set default step function for all animate calls
    step: function(state, circle) {
        circle.path.setAttribute('stroke', state.color);
    }
});

var progress = 1;
var startColor = rgbArrayToString(barColor(circle.value()));
var endColor = rgbArrayToString(barColor(progress));
circle.animate(progress, {
    from: {
        color: startColor
    },
    to: {
        color: endColor
    }
});