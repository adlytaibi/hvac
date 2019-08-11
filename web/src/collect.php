<?php
include_once("db.php");
if (isset($thermostat)) {
  $temp = 0;
  $otemp = 0;
  $humidity = 0;
  $tmode = 0;
  $fmode = 0;
  $override = 0;
  $hold = 0;
  $t_heat = 0;
  $t_cool = 0;
  $program_mode = 0;
  $tstate = 0;
  $t_type_post = 0;
  $debug = False;
  $otemp = 0;
  
  # Get Temperature in Celcius from weather canada
  try {
    $weather_url = 'https://weather.gc.ca/rss/city/on-118_e.xml';
    list($output,$status) = apicall($weather_url,'weathercanada');
    $xml=simplexml_load_string($output);
    if (is_object($xml)) {
      for ($i = 0; $i <= 5; $i++) {
        preg_match('/Current Conditions:/', $xml->entry[$i]->title,$matches);
        if (sizeof($matches)) {
          $currcond = $xml->entry[$i]->title;
          preg_match_all('!\d+.\d+!', $currcond, $matches);
          if (sizeof($matches)) { $otemp = $matches[0][0]; }
          break;
        }
      }
    }
  } catch (Exception $e) {
    $otemp = 0;
  }
  
  $ts =  time();
  # Get Humidity
  list($output,$status) = apicall($thermostat, "/tstat/humidity");
  $jout = json_decode($output);
  if (is_object($jout)) {
    if (property_exists($jout, 'humidity')) {
      $humidity = $jout->{'humidity'};
    }
  }
  # Get Other stats
  list($output,$status) = apicall($thermostat, "/tstat");
  $jout = json_decode($output);
  if (is_object($jout)) {
    if (property_exists($jout, 'temp')) {
      $temp = $jout->{'temp'};
      $tmode = $jout->{'tmode'};
      $fmode = $jout->{'fmode'};
      $override = $jout->{'override'};
      $hold = $jout->{'hold'};
      $t_heat = 0;
      $t_cool = 0;
      $tstate = $jout->{'tstate'};
      if ($tstate == 1) { $t_cool = $jout->{'t_heat'}; }
      if ($tstate == 2) { $t_cool = $jout->{'t_cool'}; }
      $program_mode = $jout->{'program_mode'};
      $fstate = $jout->{'fstate'};
      $t_type_post = $jout->{'t_type_post'};
      $sql = "insert into thermostat (ts, temp, otemp, humidity, tmode, fmode, override, hold, t_heat, t_cool, program_mode, tstate, t_type_post) values ($ts, $temp, $otemp, $humidity, $tmode, $fmode, $override, $hold, $t_heat, $t_cool, $program_mode, $tstate, $t_type_post)";
      ($debug)?print($sql):dbinsert($mydb,$sql);
    }
  }
}
?>
