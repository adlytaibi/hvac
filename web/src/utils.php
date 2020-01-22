<?php
include_once('languages.php');
$msg = '';
$prereq = '';
$donotpass = '';
date_default_timezone_set('America/Toronto');
$mydb = dirname(__FILE__).'/db/hvac.db';
$config = dirname(__FILE__).'/db/config.php';
$demo = dirname(__FILE__).'/db/demo.php';

function alertmsg($msg,$type) {
  $colour = 'primary';
  switch($type) {
    case 'ok':
      $colour = 'success';
      break;
    case 'bad':
      $colour = 'danger';
      break;
  }
  return "<div class='row justify-content-md-center'><div class='alert alert-$colour' role='alert'>$msg.</div></div>";
}

function prechecks($l,$lang) {
  $pass = '';
  $notpass = '';
  # Filesystem check
  $user = exec('whoami');
  if (is_writable(dirname(__FILE__).'/db')) {
    $pass .= alertmsg(dirname(__FILE__)."/db ".$l[$lang][6][0]." '$user'",'ok');
  } else {
    $notpass .= alertmsg(dirname(__FILE__)."/db ".$l[$lang][6][1]." '$user'",'bad');
  }
  # Modules check
  $sqlite = extension_loaded('sqlite3');
  if ($sqlite) {
    $pass .= alertmsg($l[$lang][7][0],'ok');
  } else {
    $notpass .= alertmsg($l[$lang][7][1],'bad');
  }
  $curl = extension_loaded('curl');
  if ($curl) {
    $pass .= alertmsg($l[$lang][8][0],'ok');
  } else {
    $notpass .= alertmsg($l[$lang][8][1],'bad');
  }
  $simplexml = extension_loaded('simplexml');
  if ($simplexml) {
    $pass .= alertmsg($l[$lang][9][0],'ok');
  } else {
    $notpass .= alertmsg($l[$lang][9][1],'bad');
  }
  return array($pass,$notpass);
}

function apicall($ip, $uri) {
  $ch = curl_init();
  if ($uri == 'weathercanada') {
    curl_setopt($ch, CURLOPT_URL, "${ip}");
  } else {
    curl_setopt($ch, CURLOPT_URL, "http://${ip}${uri}");
  }
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  $output = curl_exec($ch);
  $status = curl_errno($ch);
  curl_close($ch);
  return array($output,$status);
}

function thermoform($l,$lang) {
  $form = '<!-- Thermostat form -->
          <button type="button" name="btn" class="btn btn-primary btn-md" data-toggle="modal" data-target="#setting"><i class="fa fa-cog"></i></button>
<form action=. method=post>
  <div class="modal fade" id="setting" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="Setting">'.$l[$lang][11][0].'</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa fa-close"></i></span>
          </button>
        </div>
        <div class="modal-body">
          <input name="thermostat" class="form-control input-sm chat-input" value="" placeholder="'.$l[$lang][11][1].'" pattern="^((([a-zA-Z]|[a-zA-Z][a-zA-Z0-9-]*[a-zA-Z0-9]).)*([A-Za-z]|[A-Za-z][A-Za-z0-9-]*[A-Za-z0-9])|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))$" required />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">'.$l[$lang][11][2].'</button>
          <button type="submit" name="btn" class="btn btn-primary" value="save">'.$l[$lang][11][3].'</button>
        </div>
      </div>
    </div>
  </div>
</form>';
  return $form;
}

list($prereq, $donotpass) = prechecks($l,$lang);
$msg = $donotpass;

if (!$donotpass) {
  if (!file_exists($config)) {
    if (!file_exists($demo)) {
      $messageform = '<div class="row justify-content-md-center"><div class="alert alert-primary" role="alert">'.$l[$lang][10]. thermoform($l,$lang) .'</div></div>';
      if ($_POST) {
        if ($_POST['btn']=='save') {
          $ipname = $_POST['thermostat'];
          list($output, $status) = apicall($ipname,'/tstat/model');
          if ($status == 0) {
            $jout = json_decode($output);
            if (is_object($jout)) {
              if (property_exists($jout, 'model')) {
                $ipconf = fopen($config, "w") or print "<div class='alert alert-warning' role='alert'>".$l[$lang][12]."</div>";
                fwrite($ipconf, "<?php \$thermostat = '${ipname}'; ?>");
                fclose($ipconf);
                header('location: .');
              } else {
                $msg = $messageform."<div class='row justify-content-md-center'><div class='alert alert-danger' role='alert'>".$l[$lang][13]."</div></div>";
              }
            } else {
              $msg = $messageform."<div class='row justify-content-md-center'><div class='alert alert-danger' role='alert'>".$l[$lang][14]."</div></div>";
            }
          } else {
            $msg = $messageform."<div class='row justify-content-md-center'><div class='alert alert-danger' role='alert'>curl error: $status</div></div>";
          }
       }
      } else {
        $msg = $messageform;
      }
    }
  } else {
    include_once($config);
  }
}

function demoform($l,$lang) {
  $form = '<!-- Demonstration form -->
          <button type="button" name="btn" class="btn btn-primary btn-md" data-toggle="modal" data-target="#demo"><i class="fa fa-cog"></i></button>
<form action=. method=post>
  <div class="modal fade" id="demo" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="Setting">'.$l[$lang][16][0].'</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa fa-close"></i></span>
          </button>
        </div>
        <div class="modal-body">
          <label for="months">'.$l[$lang][16][1].'</label>
          <input name="months" id="months" type="number" value="6" min="1" max="12">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">'.$l[$lang][16][2].'</button>
          <button id="demobtn" type="submit" name="btn" class="btn btn-primary" value="demo">'.$l[$lang][16][3].'</button>
        </div>
      </div>
    </div>
  </div>
</form>';
  return $form;
}

function demoload($l,$lang) {
  $hcode = '
  <div class="modal fade" id="demoload" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="Setting">'.$l[$lang][17][0].'</h4>
        </div>
        <div class="modal-body">
          <embed id="embed" src="" style="display:none;" />
          <div class="progress"><div id="pbar" class="progress-bar" aria-valuemin="0" aria-valuemax="100"></div></div>
        </div>
        <div class="modal-footer">
          <button id="demodone" type="button" class="btn btn-primary" data-dismiss="modal">...</button>
        </div>
      </div>
    </div>
  </div>
  <script>$("#demoload").modal("show"); $("#embed").attr("src", "demodata.php"); $("#demodone").click(function () { $(location).attr("href","."); });</script>';
  return $hcode;
}

function cleardemo($l,$lang) {
  $clrdemo = "
<div class='col-sm-1'>
<form action=. method=post>
<button type='button' name='btn' class='btn btn-primary btn-md' data-toggle='modal' data-target='#cleardemo'><i class='fa fa-trash'></i></button>
<form action=. method=post>
  <div class='modal fade' id='cleardemo' tabindex='-1' role='dialog' aria-labelledby='basicModal' aria-hidden='true'>
    <div class='modal-dialog modal-sm'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h4 class='modal-title' id='Setting'>".$l[$lang][18][0]."</h4>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'><i class='fa fa-close'></i></span>
          </button>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>".$l[$lang][18][1]."</button>
          <button id='democlrbtn' type='submit' name='btn' class='btn btn-primary' value='clrdemo'>".$l[$lang][18][2]."</button>
        </div>
      </div>
    </div>
  </div>
</form>
</div>
";
  return $clrdemo;
}

if (!$donotpass) {
  if (!file_exists($demo)) {
    if (!file_exists($config)) {
      $messageform = '<div class="row justify-content-md-center"><div class="alert alert-primary" role="alert">'.$l[$lang][15]. demoform($l,$lang) .'</div></div>';
      if ($_POST) {
        if (isset($_POST['btn'])) {
          if ($_POST['btn']=='demo') {
            $months = $_POST['months'];
            $democonf = fopen($demo, "w") or print "<div class='alert alert-warning' role='alert'>".$l[$lang][12]."</div>";
            fwrite($democonf, "<?php \$demomode = '${months}'; ?>");
            fclose($democonf);
            include_once($demo);
            print(demoload($l,$lang));
            $msg = "<div class='alert alert-primary' role='alert'>".$l[$lang][19]."</div>";
          }
        }
      } else {
        $msg .= $messageform;
      }
    }
  } else {
    include_once($demo);
    if (isset($_POST)) {
      if (isset($_POST['btn'])) {
        if ($_POST['btn']=='clrdemo') {
          unlink($demo);
          unlink($mydb);
          header('location: .');
        }
      }
    }
  }
}

?>
