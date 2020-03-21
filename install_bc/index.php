<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>onTrack Installer</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="../template/assets/icon.png"/>
        <link rel="apple-touch-icon image_src" href="../template/assets/icon-large.png"/>
        <link href="../template/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
		<link href="../template/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    </head>


  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>on</b>Track Installer
      </div><!-- /.login-logo -->
      <div class="login-box-body">
          <?php if(isset($status)): ?>
                  <div class="row"><div class='col-md-12'><div class="alert alert-<?php echo $status['type']; ?> alert-auto" role="alert"><?php echo $status['message']; ?></div></div></div>
          <?php endif; ?>

        <form action="check.php" method="post">
          <p class="login-box-msg">Database Settings</p>


          <div class="form-group has-feedback">
            <input type="text" name="dbserver" class="form-control" placeholder="Database Server" value="localhost" required/>
          </div>
          <div class="form-group has-feedback">
            <input type="text" name="dbname" class="form-control" placeholder="Database Name" required/>
          </div>
          <div class="form-group has-feedback">
            <input type="text" name="dbuser" class="form-control" placeholder="Database User" required/>
          </div>
          <div class="form-group has-feedback">
            <input type="text" name="dbpassword" class="form-control" placeholder="Database Password"/>
          </div>

          <p class="login-box-msg">onTrack Admin</p>

          <div class="form-group has-feedback">
            <input type="text" name="name" class="form-control" placeholder="Admin Name" required/>
          </div>
          <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password" required/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

          <div class="row">
            <div class="col-xs-8">

            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Continue</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->



    <!-- jQuery 2.1.3 -->
    <script src="../template/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

  </body>


</html>
