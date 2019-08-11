<?php
Header("content-type: application/x-javascript");
?>
var cchart=undefined;
function mechart(title, fmt, dt) {
  if(cchart!==undefined) {cchart.destroy();}
  function metitle(title) {
    // Hourly is the raw untouch data regardless to percentile
    if (title=='hourly') {
      title = 'Temperature - '+title.charAt(0).toUpperCase() + title.slice(1);
    } else {
      title = 'Temperature - '+title.charAt(0).toUpperCase() + title.slice(1) + ' (average)';
    }
    return title;
  }
  function xscale(title) {
    switch(title) {
      case 'monthly': unit = 'month'; break;
      case 'weekly': unit = 'week'; break;
      case 'daily': unit = 'day'; break;
      case 'hourly': unit = 'minute'; break;
      default: unit = 'day';
    }
    return unit;
  }
  var jsonData = $.ajax({
    url: 'data.php',
    dataType: 'json',
    data: { freq: title, d: dt },
    type: 'POST',
  }).done(function (results) {
    var labels = [], datatemp=[], dataotem=[]; datahumi=[]; datarunt=[];
    results.forEach(function(e) {
      labels.push(e.date);
      datatemp.push(parseFloat(e.Temperature));
      dataotem.push(parseFloat(e.Outside));
      datahumi.push(parseFloat(e.Humidity));
      datarunt.push(parseFloat(e.Runtime));
    });
    var config = {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
           label: 'Temperature',
           fill: false,
           type: 'line',
           data: datatemp,
           borderColor: 'rgb(54, 162, 235)',
           backgroundColor: 'rgb(54, 162, 235)',
           yAxisID: "celcius",
        }, {
           label: 'Outside',
           fill: false,
           type: 'line',
           data: dataotem,
           borderColor: 'rgb(50, 205, 50)',
           backgroundColor: 'rgb(50, 205, 50)',
           yAxisID: "celcius",
        }, {
           label: 'Humidity',
           fill: false,
           type: 'line',
           data: datahumi,
           borderColor: 'rgb(255, 159, 64)',
           backgroundColor: 'rgb(255, 159, 64)',
           yAxisID: "percent",
        }, {
           label: 'Runtime',
           fill: false,
           type: 'bar',
           data: datarunt,
           borderColor: 'rgb(153, 102, 255)',
           backgroundColor: 'rgb(153, 102, 255)',
           yAxisID: "minutes",
        }]
     },
     options: {
         responsive: true,
         maintainAspectRatio: false,
         hoverMode: 'index',
         stacked: false,
         title:{
             display: true,
             text: metitle(title)
         },
         legend: {
             position: 'bottom'
         },
         tooltips: {
             mode: 'index',
             intersect: false
         },
         scales: {
             xAxes: [{
               type: "time",
               time: {
                 unit: xscale(title),
                 tooltipFormat: fmt,
                 displayFormats: {
                   month: fmt,
                   week: fmt,
                   day: fmt,
                   minute: fmt
                 }
               }
             }],
             yAxes: [{
                 type: "linear",
                 display: true,
                 scaleLabel: { display: true, labelString: "Celcius" },
                 position: "left",
                 id: "celcius",
             }, {
                 type: "linear",
                 display: true,
                 scaleLabel: { display: true, labelString: "% moisture" },
                 position: "right",
                 id: "percent",
                 gridLines: {
                     drawOnChartArea: false,
                 },
             }, {
                 type: "linear",
                 display: true,
                 scaleLabel: { display: true, labelString: "minutes" },
                 position: "right",
                 id: "minutes",
                 gridLines: {
                     drawOnChartArea: false,
                 },
             }],
         },
      },
    };
    var ctx = document.getElementById("chart").getContext("2d");
    cchart = new Chart(ctx, config);
    document.getElementById('chart').onclick = function(evt){
      var activePoints = cchart.getElementsAtEvent(evt);
      if (activePoints.length>0) {
        var firstPoint = activePoints[0];
        var label = new Date(cchart.data.labels[firstPoint._index]);
        if (title=='monthly') {
          document.getElementById('prevm').value='Monthly';
          document.getElementById('xprevm').value=label;
          $('#prevm').show();
          mechart('weekly', 'GGGG[W]WW', label);
        }
        if (title=='weekly') {
          document.getElementById('prevw').value='Weekly';
          document.getElementById('xprevw').value=label;
          $('#prevw').show();
          mechart('daily', 'YYYY-M-D', label);
        }
        if (title=='daily') {
          document.getElementById('prevd').value='Daily';
          document.getElementById('xprevd').value=label;
          $('#prevd').show();
          // Previous day
          prevday = moment(label).subtract(1,'days')._d;
          document.getElementById('dayminus').value=prevday.toLocaleDateString();
          document.getElementById('xdayminus').value=prevday;
          $('#dayminus').show();
          // Today
          document.getElementById('xtoday').value=dtoday;
          $('#today').show();
          // Nextday day
          nextday = moment(label).add(1,'days')._d;
          document.getElementById('dayplus').value=nextday.toLocaleDateString();
          document.getElementById('xdayplus').value=nextday;
          $('#dayplus').show();
          if ( moment(nextday).isAfter(dtoday,'day') ) { $('#dayplus').hide(); }
          mechart('hourly', 'HH:mm', label);
        }
      }
    }
  });
}

mechart('monthly', 'YYYY MMM');

