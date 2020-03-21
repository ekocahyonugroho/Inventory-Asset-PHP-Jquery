<?php

##################################
###       GENERAL ACTIONS      ###
##################################

// AUTHENTICATION
if(isset($_POST['signin'])) signIn($_POST['email'],$_POST['password']);

if(isset($_POST['resetConfirmation'])) resetConfirmation($_POST['email']);
if(isset($_POST['resetPassword'])) resetPassword($_POST['resetkey'],$_POST['password']);


// SIGN OUT
if ($route == "signout") signOut($liu['id']);



?>
