<?php
include_once("db.php");
if (isset($thermostat)) {
  $theath = 0; $theatm =0; $tcoolh = 0; $tcoolm = 0;
  $ts =  time();
  list($output,$status) = apicall($thermostat, "/tstat/datalog");;
  $debug = False;
  $jout = json_decode($output);
  if (is_object($jout)) {
    if (property_exists($jout, 'today')) {
      $theath = $jout->{'today'}->{'heat_runtime'}->{'hour'};
      $theatm = $jout->{'today'}->{'heat_runtime'}->{'minute'};
      $tcoolh = $jout->{'today'}->{'cool_runtime'}->{'hour'};
      $tcoolm = $jout->{'today'}->{'cool_runtime'}->{'minute'};
      $sql = "insert into runtime (ts, theath, theatm, tcoolh, tcoolm) values ($ts, $theath, $theatm, $tcoolh, $tcoolm)";
      ($debug)?print($sql):dbinsert($mydb,$sql);
    }
  }
}
?>
