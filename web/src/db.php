<?php
include_once('utils.php');
date_default_timezone_set('America/Toronto');
$mydb = dirname(__FILE__).'/db/hvac.db';
$config = dirname(__FILE__).'/db/config.php';
if (extension_loaded('sqlite3')) {
  class MyDB extends SQLite3 {
    private $mydb;
    function __construct($mydb) {
      $this->open($mydb);
    }
  }

  if (!file_exists($mydb)) {
    include_once('languages.php');
    if (is_writable(dirname(__FILE__).'/db')) {
      $db = new MyDB($mydb);
      if(!$db) {
        echo $db->lastErrorMsg();
      }
      $dbschema = "create table thermostat (ts int primary key not null, temp real, otemp real, humidity int, tmode int, fmode int, override int, hold int, t_heat real, t_cool real, program_mode int, tstate int, t_type_post int);";
      $dbschema .= "create table runtime (ts int primary key not null, theath int, theatm int, tcoolh int, tcoolm int);";
      $ret = $db->exec($dbschema);
      if(!$ret){
        echo $db->lastErrorMsg();
      }
      $db->close();
    } else {
      $msg = '<div class="alert alert-danger" role="alert">'.$l[$lang][5][0].' '.dirname(__FILE__).'/db '.$l[$lang][5][1].' "'. exec('whoami') .'" '.$l[$lang][5][2].'</div>';
    }
  }

  function dbinsert($mydb, $sql) {
    $db = new MyDB($mydb);
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }
  
  function FtoC($tempf) {
    return number_format(($tempf - 32)*5/9,2);
  }
  
  function dbselect($mydb, $sql, $l, $lang) {
    include_once('languages.php');
    $json = [];
    $data = array();
    $db = new MyDB($mydb);
    $ret = $db->query($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    } else {
      $prevcool = 0;
      $countcool = 0;
      $prevheat = 0;
      $countheat = 0;
      while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
        $data = array("date" => $row['ts']);
        $data[$l[$lang][1][0]] = FtoC($row['temp']);
        $data[$l[$lang][1][1]] = number_format($row['otemp'],2);
        $data[$l[$lang][1][2]] = number_format($row['humidity'],2);
        if ( array_key_exists('tstate',$row) ) {
          if ($row['tstate'] == 1) { $data['Heat'] = FtoC($row['t_heat']); }
          if ($row['tstate'] == 2) { $data['Cool'] = FtoC($row['t_cool']); }
        }
        if ( array_key_exists('tcoolh',$row) && array_key_exists('tcoolm',$row) ) {
          $countcool = $row['tcoolh'] * 60 + $row['tcoolm'];
          $data[$l[$lang][1][3]] = ($countcool>0)?$countcool - $prevcool:0;
          $prevcool = $countcool;
        }
        if ( array_key_exists('theath',$row) && array_key_exists('theatm',$row) ) {
          $countheat = $row['theath'] * 60 + $row['theatm'];
          $data[$l[$lang][1][3]] = ($countheat>0)?$countheat - $prevheat:0;
          $prevheat = $countheat;
        }
        array_push($json, $data);
      }
    }
    $db->close();
    return json_encode($json);;
  }
}

?>
