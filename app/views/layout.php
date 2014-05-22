<?php
if (isset($menu)) {
  $GLOBALS['menu'] = $menu;
}

function menu_active($item) {
  global $menu;
  if (isset($menu) && $menu == $item) {
    echo ' class="active"';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$GLOBALS['sitename']?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/realops14.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default navbar-static-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#realops-navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div><!-- /.navbar-header -->
        <div class="collapse navbar-collapse" id="realops-navbar">
          <ul class="nav navbar-nav navbar-right">
            <li<?php menu_active('index') ?>><a href="<?=Route::link('index')?>">Home</a></li>
            <li<?php menu_active('sked') ?>><a href="<?=Route::link('schedule')?>">Book a Flight</a></li>
            <li<?php menu_active('pilots') ?>><a href="<?=Route::link('pilots')?>">Pilot Briefing</a></li>
            <li<?php menu_active('charts') ?>><a href="<?=Route::link('charts')?>">Scenery and Charts</a></li>
            <li<?php menu_active('atc') ?>><a href="<?=Route::link('atc')?>">ATC Information</a></li>
            <li<?php menu_active('contact') ?>><a href="<?=Route::link('contact')?>">Contact Us</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>
    <div class="jumbotron">
      <div class="container">
        <h1>05 July 2014</h1>
        <p>Save the date - It's about to get real!</p>
        <p>
          <a class="btn btn-primary btn-lg" role="button" href="http://www.vatpac.org">Learn more about VATPAC</a>
        </p>
      </div><!-- /.container -->
    </div><!-- /.jumbotron -->
    <div class="container">
      <div class="row">
        <div class="col-sm-3">
          <div class="panel panel-default hidden-xs">
            <div class="panel-heading">Quick links</div>
            <div class="panel-body">
              <ul class="list-unstyled">
                <li><a href="<?=Route::link('schedules')?>">Flight schedules</a></li>
                <li><a href="<?=Route::link('pilots')?>">Pilot briefing</a></li>
                <li><a href="#" target="_blank">Discussion forum</a></li>
                <li><a href="http://www.vatsim.net/fp/" target="_blank">Flight plan submission</a></li>
              </ul>
            </div><!-- /.panel-body -->
          </div><!-- /.panel-default -->
          <div class="panel panel-default" id="realops-login-panel">
            <div class="panel-heading">User Control Panel</div>
            <div class="panel-body">
              <?php include(APP_PATH . "inc/usercontrol.php") ?>
            </div><!-- /.panel-body -->
          </div><!-- /#realops-login-panel -->
        </div><!-- /.col-sm-3 -->
        <div class="col-sm-8">
<?php
if (isset($body)) {
  if (is_array($body)) {
    foreach ($body as $line) {
      echo $line . "\n";
    }
  } else {
    echo $body;
  }
}
?>
        </div><!-- /.col-sm-8 -->
      </div><!-- /.row -->
    </div><!-- /.container -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>
