<?php
include_once('db.php');
if (!isset($_SERVER["HTTP_HOST"])) {
  parse_str($argv[1], $_POST);
} else {
  header("Content-Type: application/json", true);
}
if (isset($_POST['freq'])) {
  $freq = $_POST['freq'];
} else {
  $freq = 'monthly';
}
if (isset($_POST['d'])) {
  $dday = substr($_POST['d'],0,33);
} else {
  $dday = '';
}
if ($freq=='monthly') {
  $sql = "select avg(humidity) as humidity, avg(temp) as temp, avg(otemp) as otemp, strftime('%Y-%m-%d %H:%M:%S',datetime(ts, 'unixepoch', 'localtime')) as ts from (select humidity,ts,temp,otemp,strftime('%Y-%m',datetime(ts, 'unixepoch', 'localtime')) rmonth from thermostat) as t group by t.rmonth";
}
if ($freq=='weekly') {
  $dday = date('Y-m',strtotime($dday));
  $sql = "select avg(humidity) as humidity, avg(temp) as temp, avg(otemp) as otemp, strftime('%Y-%m-%d %H:%M:%S',datetime(ts, 'unixepoch', 'localtime')) as ts from (select humidity,ts,temp,otemp,strftime('%YW%W',datetime(ts, 'unixepoch', 'localtime')) rweek, strftime('%Y-%m',datetime(ts, 'unixepoch', 'localtime')) rmonth from thermostat where rmonth='${dday}') as t group by t.rweek";
}
if ($freq=='daily') {
  $year = date('o',strtotime($dday));
  $week = str_pad(date('W',strtotime($dday))-1,2,"0",STR_PAD_LEFT);
  $dday = "${year}W${week}";
  $sql = "select avg(humidity) as humidity, avg(temp) as temp, avg(otemp) as otemp, strftime('%Y-%m-%d %H:%M:%S',datetime(ts, 'unixepoch', 'localtime')) as ts from (select humidity,ts,temp,otemp,strftime('%Y-%m-%d',datetime(ts, 'unixepoch', 'localtime')) rday, strftime('%YW%W',datetime(ts, 'unixepoch', 'localtime')) rweek from thermostat where rweek='${dday}') as t group by t.rday";
}
if ($freq=='hourly') {
  $dday = date('Y-m-d',strtotime($dday));
  $sql = "select humidity, temp, otemp, tstate, t_heat, t_cool, theath, theatm, tcoolh, tcoolm, strftime('%Y-%m-%d %H:%M:%S',datetime(thermostat.ts, 'unixepoch', 'localtime') ) as ts, strftime('%Y-%m-%d',datetime(thermostat.ts, 'unixepoch', 'localtime')) rday, strftime('%Y-%m-%d %H',datetime(thermostat.ts, 'unixepoch', 'localtime')) rhour, strftime('%Y-%m-%d %H',datetime(runtime.ts, 'unixepoch', 'localtime')) rhourrt from thermostat left join runtime on (rhour = rhourrt) where rday='${dday}' group by thermostat.ts";
}
print dbselect($mydb, $sql, $l, $lang);
?>
