


<?php
include('config.php');
require_once('facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

ini_set('display_errors', 1);
error_reporting('e_all');
session_start();
/*const APPID = "449000611931438";
const APPSECRET = "4081c73247e8a9729dc939b5fe6565c6";
FacebookSession::setDefaultApplication(APPID, APPSECRET);
$helper = new FacebookRedirectLoginHelper('https://projet-esgi-fb.herokuapp.com/');*/
$fb = new Facebook(array(
    'appId'  => APP_ID,
    'secret' => APP_SECRET,
    'cookie' => true,
));
$session = $fb->getSession();
$me = null;
print_r($fb);
// On teste si la session existe
if ($session) {

    try {
        // On récupère l'UID de l'utilisateur Facebook courant
        $uid = $fb->getUser();
        // On récupère les infos de base de l'utilisateur
        $me = $fb->api('/me');
    } catch (FacebookApiException $e) {

        // S'il y'a un problème lors de la récup, perte de session entre temps, suppression des autorisations...

        // On récupère l'URL sur laquelle on devra rediriger l'utilisateur pour le réidentifier sur l'application
        $loginUrl = $fb->getLoginUrl(
            array(
                'canvas'    => 1,
                'fbconnect' => 0
            )
        );
        // On le redirige en JS (header PHP pas possible)
        echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";
        exit();
    }

}
else {
    // Si l'utilisateur n'a pas de session

    // On récupère l'URL sur laquelle on devra rediriger l'utilisateur pour le réidentifier sur l'application
    $loginUrl = $fb->getLoginUrl(
        array(
            'canvas'    => 1,
            'fbconnect' => 0
        )
    );
    // On le redirige en JS (header PHP pas possible)
    echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";
    exit();
}