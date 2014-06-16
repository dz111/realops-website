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
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/realops.css">
<?php
if (isset($menu) && $menu == 'index') { ?>
    <link rel="stylesheet" href="/css/header.css">
<?php
} ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="modal fade" id="realops-login-info" tabindex="-1" role="dialog" aria-labelledby="realops-login-info-label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="realops-login-info-label">Login with VATSIM SSO</h4>
          </div>
          <div class="modal-body">
            <p><strong>You are about to be redirected to the VATSIM Single Sign On service to login.</strong></p>
            <p>The VATSIM Single Sign On service allows you to log into the RealOps Sydney event website without having to fill out a registration form.</p>
            <p>We will <strong>not</strong> have access to your VATSIM password.</p>
            <p>Once you log in, we will have one-time access to some information about you, including your name, email address and some publicly-available VATSIM-related information such as your ATC rating. We will use this information for the purpose of administering this event and promoting future runnings of this event. This information will be removed from our servers by December 2015.</p>
            <p><strong>New to VATSIM?</strong> <a href="http://www.vatsim.net/about-vatsim/members/joinvatsim/">Sign up today!</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <a href="<?=Route::link('login')?>?return_url=<?=util::current_url_enc()?>" type="button" class="btn btn-primary">Continue to VATSIM SSO</a>
          </div>
        </div>
      </div>
    </div>
    <div class="wrapper">
      <header>
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
<?php
if (isset($menu) && $menu == 'index') { ?>
      <div id="realops-calltoaction">
        <a href="<?=Route::link('schedule')?>" class="btn btn-primary btn-lg">Book your flight now!</a>
      </div>
      <div id="realops-background-shade"></div>
      <div id="realops-logo"></div>
<?php
} ?>
      </header>
      <div class="container" id="realops-container">
        <div class="row">
          <div class="col-sm-3">
            <div class="panel panel-default" id="realops-login-panel">
              <div class="panel-heading">User Control Panel</div>
              <div class="panel-body">
<?php
if (Auth::check()) {?>
                <p>Welcome, <?=Auth::user()->name?></p>
                <p><a href="<?=Route::link('schedule/user')?>">My flights</a></p>
<?php
  if (Auth::user()->admin) { ?>
                <p><a href="<?=Route::link('admin')?>">Admin panel</a></p>
<?php
  } ?>
                <p><a href="<?=Route::link('logout')?>?return_url=<?=util::current_url_enc()?>">Logout</a></p>
<?php
} else { ?>
                <p><a href="<?=Route::link('login')?>?return_url=<?=util::current_url_enc()?>" data-toggle="modal" data-target="#realops-login-info">
                  Login with VATSIM
                </a></p>
<?php
} ?>
              </div><!-- /.panel-body -->
            </div><!-- /#realops-login-panel -->
            <div class="panel panel-default hidden-xs">
              <div class="panel-heading">Quick links</div>
              <div class="panel-body">
                <ul class="list-unstyled">
                  <li><a href="<?=Route::link('schedule')?>">Flight schedules</a></li>
                  <li><a href="<?=Route::link('pilots')?>">Pilot briefing</a></li>
                  <li><a href="http://vatpac.org/forums/showthread.php?16113-RealOps-Sydney-2014" target="_blank">Discussion forum</a></li>
                  <li><a href="http://www.vatsim.net/fp/" target="_blank">Flight plan submission</a></li>
                </ul>
              </div><!-- /.panel-body -->
            </div><!-- /.panel-default -->
          </div><!-- /.col-sm-3 -->
          <article class="col-sm-8 panel panel-default" id="realops-body">
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
          </article>
        </div><!-- /.row -->
      </div><!-- /.container -->
    </div><!-- /.wrapper -->
    <footer>
      <p>Website by &copy; David Zhong 2014<br />Photographs courtesy of Sydney Airport Corporation Limited</p>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="/js/realops.js"></script>
  </body>
</html>
