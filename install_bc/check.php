<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require('../vendor/classes/class.medoo.php');

try {
    $database = new medoo([
        "database_type"=>"mysql",
        "database_name"=> $_POST['dbname'],
        "server"=> $_POST['dbserver'],
        "username"=> $_POST['dbuser'],
        "password"=> $_POST['dbpassword'],
        "charset"=>"utf8",
        "port"=>3306
    ]);
    $ok = true;
}
catch(Exception $e) {
    $ok = false;
}
?>
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

          <?php if($ok == true): ?>
                  <div class="row"><div class='col-md-12'><div class="alert alert-success" role="alert">Succesfully conected to database!</div></div></div>
                      <form action="install.php" method="post">
                            <p>
                                <b>Database Server </b><?php echo $_POST['dbserver']; ?><br>
                                <b>Database Name </b><?php echo $_POST['dbname']; ?><br>
                                <b>Database User </b><?php echo $_POST['dbuser']; ?><br>
                                <b>Database Password </b><?php echo $_POST['dbpassword']; ?><br><br>
                                <b>Admin Name </b><?php echo $_POST['name']; ?><br>
                                <b>Admin Email </b><?php echo $_POST['email']; ?><br>
                                <b>Admin Password </b><?php echo $_POST['password']; ?><br>
                            </p>

                            <input type="hidden" name="dbserver" value="<?php echo $_POST['dbserver']; ?>">
                            <input type="hidden" name="dbname" value="<?php echo $_POST['dbname']; ?>">
                            <input type="hidden" name="dbuser" value="<?php echo $_POST['dbuser']; ?>">
                            <input type="hidden" name="dbpassword" value="<?php echo $_POST['dbpassword']; ?>">
                            <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
                            <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                            <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>">

                            <p class="login-box-msg">Click "Install" to start the installation.</p>
                            <div class="row">
                              <div class="col-xs-6"><button type="button" onclick="window.history.back()" class="btn btn-default btn-block btn-flat">Back</button></div><!-- /.col -->
                              <div class="col-xs-6"><button type="submit" class="btn btn-primary btn-block btn-flat">Install</button></div><!-- /.col -->
                            </div>
                      </form>
          <?php endif; ?>

          <?php if($ok == false): ?>
                  <div class="row"><div class='col-md-12'><div class="alert alert-danger" role="alert">Database Error!</div></div></div>

                        <p class="login-box-msg">We were unable to connect to the database. Please go back and correct your database settings.</p>
                        <div class="row">
                          <div class="col-xs-6"><button onclick="window.history.back()" class="btn btn-default btn-block btn-flat">Back</button></div><!-- /.col -->
                          <div class="col-xs-6"></div><!-- /.col -->
                        </div>
          <?php endif; ?>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->



    <!-- jQuery 2.1.3 -->
    <script src="../template/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

  </body>


</html>
