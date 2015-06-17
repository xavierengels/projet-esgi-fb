<?php
/**
 * Created by PhpStorm.
 * User: xavierengels
 * Date: 17/06/15
 * Time: 12:15
 */

require_once('facebook-php-sdk-v4-4.0-dev/autoload.php');
$appId =  "449000611931438";
$appSecret = "4081c73247e8a9729dc939b5fe6565c6";
$facebook =  new Facebook(array(
   'appId' => $appId,
    'appSecret' => $appSecret,
    'fileUpload' => true,
    'cookie' => true
));
$fbuser =  $facebook->getUser();
/*const APPID = "449000611931438";
const APPSECRET = "4081c73247e8a9729dc939b5fe6565c6";
FacebookSession::setDefaultApplication(APPID, APPSECRET);
$helper = new FacebookRedirectLoginHelper('https://projet-esgi-fb.herokuapp.com/');*/