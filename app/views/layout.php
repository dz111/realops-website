<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RealOps Sydney 2014</title>

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
            <li class="active"><a href="/">Home</a></li>
            <li><a href="/main/schedule">Book a Flight</a></li>
            <li><a href="#">Pilot Briefing</a></li>
            <li><a href="#">Scenery and Charts</a></li>
            <li><a href="#">ATC Information</a></li>
            <li><a href="#">Contact Us</a></li>
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
                <li><a href="/main/schedule">Flight schedules</a></li>
                <li><a href="#">Pilot briefing</a></li>
                <li><a href="#">Discussion forum</a></li>
                <li><a href="#">Flight plan submission</a></li>
              </ul>
            </div><!-- /.panel-body -->
          </div><!-- /.panel-default -->
          <div class="panel panel-default" id="realops-login-panel">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
              <form role="form">
                <div class="form-group">
                  <input type="text" class="form-control" id="realops-login-id" placeholder="VATSIM ID" />
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" id="realops-login-password" placeholder="Password" />
                </div>
                <button type="submit" class="btn btn-default">Login</button>
              </form>
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
