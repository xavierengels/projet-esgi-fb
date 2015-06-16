


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

<div>
<cenetr><img src="/images/concoursimage.jpg"></cenetr>
</div>
<?php
if($session) {
    try {
        $_SESSION['fb_token'] = (string) $session->getAccessToken();
        $request_user = new FacebookRequest( $session,"GET","/me");
        $request_user_executed = $request_user->execute();
        $user = $request_user_executed->getGraphObject(GraphUser::className());
        echo "Bonjour ".$user->getName();
        ?>
        <div class="fb-like" data-href="https://www.facebook.com/concoursmariageprojetesgi/app_449000611931438" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>

    <?php
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
</body>







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
