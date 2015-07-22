<?php

session_start();

include('config.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookCanvasLoginHelper;



FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);

$helper = new FacebookRedirectLoginHelper('https://esgi-fb.herokuapp.com/callback.php');

// Now you have the session
$session = $helper->getSessionFromRedirect();
$_SESSION['fb_token'] = $session->getToken();
print_r($session);
if($session) {
    header("Location: https://esgi-fb.herokuapp.com/index.php");
} else {
    header("Location: https://esgi-fb.herokuapp.com");
}