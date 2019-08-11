debug = false;
var  dtoday = new Date();
$('#prevm').hide();
$('#prevw').hide();
$('#prevd').hide();
$('#dayminus').hide();
$('#dayplus').hide();
$(document).on('click', 'input:button[id^="prevm"]', function (event) {
  $('#prevm').hide();
  $('#prevw').hide(); 
  $('#prevd').hide(); 
  $('#dayminus').hide();
  $('#dayplus').hide();
  mechart('monthly', 'YYYY MMM');
});
$(document).on('click', 'input:button[id^="prevw"]', function (event) {
  $('#prevw').hide(); 
  $('#prevd').hide(); 
  $('#dayminus').hide();
  $('#dayplus').hide();
  mechart('weekly', 'GGGG[W]WW', document.getElementById('xprevw').value);
});
$(document).on('click', 'input:button[id^="prevd"]', function (event) {
  $('#prevd').hide(); 
  $('#dayminus').hide();
  $('#dayplus').hide();
  mechart('daily', 'YYYY-M-D', document.getElementById('xprevd').value);
});
$(document).on('click', 'input:button[id^="dayminus"]', function (event) {
  prevday = moment(prevday).subtract(1,'days')._d;
  document.getElementById('dayminus').value=prevday.toLocaleDateString();
  document.getElementById('xdayminus').value=prevday;
  $('#dayminus').show();
  nextday = moment(nextday).subtract(1,'days')._d;
  document.getElementById('dayplus').value=nextday.toLocaleDateString();
  document.getElementById('xdayplus').value=nextday;
  if ( !moment(nextday).isAfter(dtoday,'day') ) { $('#dayplus').show(); }
  mechart('hourly', 'HH:mm', document.getElementById('xdayminus').value);
});
$(document).on('click', 'button:button[id^="today"]', function (event) {
  prevday = moment(dtoday).subtract(1,'days')._d;
  document.getElementById('dayminus').value=prevday.toLocaleDateString();
  document.getElementById('xdayminus').value=prevday;
  nextday = moment(dtoday).add(1,'days')._d;
  document.getElementById('dayplus').value=nextday.toLocaleDateString();
  document.getElementById('xdayplus').value=nextday;
  $('#dayminus').show();
  $('#dayplus').hide();
  mechart('hourly', 'HH:mm', dtoday);
});
$(document).on('click', 'input:button[id^="dayplus"]', function (event) {
  nextday = moment(nextday).add(1,'days')._d;
  document.getElementById('dayplus').value=nextday.toLocaleDateString();
  document.getElementById('xdayplus').value=nextday;
  $('#dayplus').show();
  prevday = moment(prevday).add(1,'days')._d;
  document.getElementById('dayminus').value=prevday.toLocaleDateString();
  document.getElementById('xdayminus').value=prevday;
  if ( moment(nextday).isAfter(dtoday,'day') ) { $('#dayplus').hide(); }
  mechart('hourly', 'HH:mm', document.getElementById('xdayplus').value);
});
$(document).on('click', 'button:button[id*="day"]', function (event) {
  // From today going back
  document.getElementById('prevm').value='Monthly';
  document.getElementById('xprevm').value=dtoday;
  $('#prevm').show();
  document.getElementById('prevw').value='Weekly';
  document.getElementById('xprevw').value=dtoday;
  $('#prevw').show();
  document.getElementById('prevd').value='Daily';
  document.getElementById('xprevd').value=dtoday;
  $('#prevd').show();
});
function closeNav() {
  $('.collapse').collapse('hide');
}
function showValue(newValue) {
  document.getElementById('range').innerHTML=newValue;
}
