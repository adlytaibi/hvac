<?php
include_once("db.php");
$debug = False;
if ($debug) { $demomode = 1; }
$ts1 = time();
$ts0 = time() - $demomode * 2592000;
$ts = $ts0;
$thour = $ts;
$tday = $ts;
$theath = 0;
$theatm = 0;
$tcoolh = 0;
$tcoolm = 0;

function trand($ts) {
  # Rabbit out of the hat demo data, don't ask, just go with it
  $month = date('m', $ts);
  $day = date('d', $ts);
  $hour = date('H', $ts);
  $minutes = date('m', $ts);
  $mins = ($minutes+$hour*60)/1440*360-90;             # Time to degree angle offset by 90
  $tdfact = ($month>3 && $month<9)?25:-5;              # Seasonal months factor in Celcius
  $tdhfact = ($hour>20 && $hour<5)?-5:1;               # Seasonal hours factor in Celcius
  $tdvar = random_int(-10,10)/10;                      # Tiny random variation
  $ot = $tdfact+($tdhfact+$tdvar)*sin(deg2rad($mins)); # Totally made up formula of mine
  $tffact = ($month>3 && $month<9)?75:68;              # Seasonal months factor in Fahrenheit
  $tfhfact = ($hour>20 && $hour<5)?-2:2;               # Seasonal hours factor in Fahrenheit
  $it = $tffact+$tfhfact+.1*$ot/2;                     # Inside temperature considered fairly steady
  $deltat = $ot - ($it-32)*5/9;                        # Temperature delta to drive above normal humidity
  $hun = random_int(360,370)/10;
  $hur = random_int(380,390)/10;
  $hu = ($deltat>0)?$hur-$deltat:$hun;
  return array($it, $ot, $hu);
}

function dbqprev($mydb, $sql, $cols) {
  $json = [];
  $data = array();
  $db = new MyDB($mydb);
  $ret = $db->query($sql);
  if(!$ret){
    echo $db->lastErrorMsg();
  } else {
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
      array_push($data, $row);
    }
  }
  $db->close();
  return $data;
}

function rrand($mydb, $ts) {
  $month = date('m', $ts);
  $hour = date('H', $ts);
  $tdfact = ($month>3 && $month<9)?1:26;
  $tdhfact = ($month>3 && $month<9)?($hour>10 && $hour<14)?True:False:True;
  $theath = 0;
  $theatm = 0;
  $tcoolh = 0;
  $tcoolm = 0;
  $tadj = 0;
  $sql = "select ts, otemp-(temp-32)*5/9 as delta from thermostat where ts>=$ts-3600";
  $delta = dbqprev($mydb, $sql, ['delta']);
  if (array_key_exists(1,$delta)) {
    $d1 = $delta[0]['delta'];
    $d2 = $delta[1]['delta'];
    if (abs($d1)-$tdfact>0 && $tdhfact) {
      $tadj = intval(10*(abs($d1)-$tdfact));
      if (abs($d2-$d1)-$tdfact>0) {
        $tadj += intval(10*(abs($d2-$d1)-$tdfact));
      }
    }
  }
  if ($month>3 && $month<9) {
    $tcoolm = abs($tadj);
  } else {
    $theatm = abs($tadj);
  }
  return array($theath, $theatm, $tcoolh, $tcoolm);
}

while ($ts<$ts1) { 
  # Every 30 minutes
  list($temp, $otemp, $humidity) = trand($ts);
  $tmode = 2;
  $fmode = 1;
  $override = 0;
  $hold = 0;
  $t_heat = 0.0;
  $t_cool = 0.0;
  $program_mode = 1;
  $tstate = 0;
  $t_type_post = 0;
  $sql = "insert into thermostat (ts, temp, otemp, humidity, tmode, fmode, override, hold, t_heat, t_cool, program_mode, tstate, t_type_post) values ($ts, $temp, $otemp, $humidity, $tmode, $fmode, $override, $hold, $t_heat, $t_cool, $program_mode, $tstate, $t_type_post)";
  ($debug)?print($sql."\n"):dbinsert($mydb,$sql);
  $ts += 1800; # Demo data doesn't need more increments
  # Hourly runtime saved in cumulative way
  if ($ts-$tday == 86400) {
    $tday = $ts;
    $theath = 0;
    $theatm = 0;
    $tcoolh = 0;
    $tcoolm = 0;
  }
  list($thh, $thm, $tch, $tcm) = rrand($mydb, $ts);
  $theath += $thh;
  $theatm += $thm;
  $tcoolh += $tch;
  $tcoolm += $tcm;
  if ($ts-$thour == 3600) {
    $sql = "insert into runtime (ts, theath, theatm, tcoolh, tcoolm) values ($ts, $theath, $theatm, $tcoolh, $tcoolm)";
    ($debug)?print($sql."\n"):dbinsert($mydb,$sql);
    $thour = $ts;
  }
  if (!$debug) {
    $pct = number_format( ($ts - $ts0) / ($ts1 - $ts0) * 100, 0);
    $btndis = ($pct != 100)?'true':'false';
    $btntxt = ($pct != 100)?"$pct%":$l[$lang][17][1];
    print('<script>parent.$("#pbar").css("width",'. $pct .'+ "%"); parent.$("#demodone").html("'.$btntxt.'"); parent.$("#demodone").prop("disabled", '.$btndis.');</script>');
    ob_flush();
    flush();
  }
}
?>
