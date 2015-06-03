


<?php
require_once('facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;;

ini_set('display_errors', 1);
error_reporting('e_all');
session_start();
const APPID = "449000611931438";
const APPSECRET = "4081c73247e8a9729dc939b5fe6565c6";
FacebookSession::setDefaultApplication(APPID, APPSECRET);
$helper = new FacebookRedirectLoginHelper('https://projet-esgi-fb.herokuapp.com/');


$user =  'blnwydiaqtvkyp';
$pass =  'yODIF2ML7nUOjWl-jBPkS54hHw';
try {
    $dbh = new PDO("pgsql:ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;dbname=d7fa01u2c92h52;host=$host", $username, $password );
    foreach($dbh->query('SELECT * from list') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
  $session = new FacebookSession($_SESSION['fb_token']);

}
else
{
     $session = $helper->getSessionFromRedirect();

}

?>

<html>
<head>
    <script src="https://projet-esgi-fb.herokuapp.com/jquery-2.1.4.min.js"></script>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>

</head>
<div id="fb-root"></div>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3&appId=449000611931438";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>




<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

  <?php
  if($session)
  {
     $token = (string) $session->getAccessToken();
     $_SESSION['fb_token'] = $token;
  }
  else
  {
  
  }

  ?>
<div id="status">
</div>

<nav id="nav">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container">



                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="index.php">Acceuil</a></li>
                    <li role="presentation"><a href="participer.php">Participer</a></li>
                    <li role="presentation"><a href="plusrecents.php">Recents</a></li>
                    <li role="presentation"><a href="lesmeilleurs.php">Populaire</a></li>

                </ul>

            </div>
        </div>
    </div>
</nav>





  <div class="fb-like" data-href="https://www.facebook.com/concoursmariageprojetesgi/app_449000611931438" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
   <form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
    <input type="file" name="source" id="source" /><br />
    <input type="submit" name="submit" value="Envoyer" />
  </form>


</body>


     <?php

     if($session) {

  try {

    // Upload to a user's profile. The photo will be in the
    // first album in the profile. You can also upload to
    // a specific album by using /ALBUM_ID as the path     
    $response = (new FacebookRequest(
      $session, 'POST', '/me/photos', array(
        'source' => new CURLFile('path/to/file.name', 'image/png'),
        'message' => 'User provided message'
      )
    ))->execute()->getGraphObject();

    // If you're not using PHP 5.5 or later, change the file reference to:
    // 'source' => '@/path/to/file.name'

    echo "Posted with id: " . $response->getProperty('id');

  } catch(FacebookRequestException $e) {

    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();

  }   

}

if($session) {
    try {
      $_SESSION['fb_token'] = (string) $session->getAccessToken();
        $request_user = new FacebookRequest( $session,"GET","/me");
        $request_user_executed = $request_user->execute();
        $user = $request_user_executed->getGraphObject(GraphUser::className());
        echo "Bonjour ".$user->getName();
    } catch(FacebookRequestException $e) {
      echo "error";
        echo "Exception occured, code: " . $e->getCode();
        echo " with message: " . $e->getMessage();
  }   
}
else
{
  echo "session ??";
  $loginUrl = $helper->getLoginUrl();
   echo "<a href='".$loginUrl."'>Se connecter</a>";
}




?>




<script>

//  window.fbAsyncInit = function() {
//    FB.init({
//      appId      : '449000611931438',
//      xfbml      : true,
//      version    : 'v2.3'
//    });
//  };


  /*(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/fr_FR/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);       
   }(document, 'script', 'facebook-jssdk'));*/
</script>

</html>
