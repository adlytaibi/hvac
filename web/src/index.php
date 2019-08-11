<!DOCTYPE html>
<html>
  <head>
    <title>HVAC</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <link rel='shortcut icon' sizes='16x16 24x24 32x32 40x40 48x48 64x64 96x96 128x128 192x192' href='favicon.ico'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel='stylesheet' href='css/mestyle.css'>
  </head>
  <body>
    <?php include_once('db.php'); ?>
    <?php if (empty($msg)) { ?>
      <div class='outsideWrapper'>
        <div class='insideWrapper'>
          <div class='row justify-content-md-center'>
            <div class='col-sm-12 p-3 chartWrapper'>
              <canvas id='chart'></canvas>
            </div>
          </div> <!-- row chart -->
          <form id=cluster action=. method=post>
              <div class='row justify-content-md-center'>
                <div class='col-sm-<?php print((isset($demomode))?11:12); ?> p-3'>
                  <input class='btn btn-primary p-1 m-1' type='button' id='prevm' value='prevm'>
                  <input type="hidden" id="xprevm" value="" />
                  <input class='btn btn-primary p-1 m-1' type='button' id='prevw' value='prevw'>
                  <input type="hidden" id="xprevw" value="" />
                  <input class='btn btn-primary p-1 m-1' type='button' id='prevd' value='prevd'>
                  <input type="hidden" id="xprevd" value="" />
                  <input class='btn btn-primary p-1 m-1' type='button' id='dayminus' value='dayminus'>
                  <input type="hidden" id="xdayminus" value="" />
                  <button class='btn btn-primary p-1 m-1' id='today' type='button'><img title='Today' src='svg/home.svg' /></button>
                  <input type="hidden" id="xtoday" value="" />
                  <input class='btn btn-primary p-1 m-1' type='button' id='dayplus' value='dayplus'>
                  <input type="hidden" id="xdayplus" value="" />
                </div>
                <?php if (isset($demomode)) { print(cleardemo()); } ?>
              </div> <!-- row button -->
          </form>
        </div> <!-- insideWrapper -->
      </div> <!-- outsideWrapper -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" integrity="sha256-Uv9BNBucvCPipKQ2NS9wYpJmi8DTOEfTA/nH2aoJALw=" crossorigin="anonymous"></script>
      <script src="js/mecode.js"></script>
      <script src='chart.php?type=<?php print 'temp'; ?>'></script>
    <?php } else { ?>
      <div class='outsideWrapper'>
        <div class='insideWrapper'>
          <img src="img/hvac_logo.png" class="rounded-circle mx-auto d-block" alt="HVAC">
          <div class="accordion" id="initialview">
            <div class='card text-center text-white w-75 bg-dark'>
              <div class='card-header' id='prereq'>
                <button class="btn btn-link text-uppercase" type="button" data-toggle="collapse" data-target="#colprereq" aria-expanded="true" aria-controls="colprereq">Pre-requisites checks</button>
              </div>
              <div id="colprereq" class="collapse show" aria-labelledby="prereq" data-parent="#initialview">
                <div class='card-body'>
                  <?php print $prereq; ?>
                </div>
               </div>
            </div> <!-- card -->
            <div class='card text-center text-white w-75 bg-dark'>
              <div class='card-header' id='setup'>
                <button class="btn btn-link text-uppercase" type="button" data-toggle="collapse" data-target="#colsetup" aria-expanded="false" aria-controls="colsetup">Setup</button>
              </div>
              <div id="colsetup" class="collapse show" aria-labelledby="setup" data-parent="#initialview">
                <div class='card-body'>
                  <?php print $msg; ?>
                </div>
              </div>
            </div> <!-- card -->
          </div>
        </div> <!-- insideWrapper -->
      </div> <!-- outsideWrapper -->
    <?php }; ?>
  </body>
</html>
