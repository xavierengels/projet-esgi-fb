


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

FacebookSession::setDefaultApplication('449000611931438', '4081c73247e8a9729dc939b5fe6565c6');
$helper = new FacebookRedirectLoginHelper('https://projet-esgi-fb.herokuapp.com/');


if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
  $session = new FacebookSession($_SESSION['fb_token']);

}
else
{
     $session = $helper->getSessionFromRedirect();


} 
$loginUrl = $helper->getLoginUrl();
  //echo   '<div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false"></div>';

echo "<a href='".$loginUrl."'>Se connecter</a>";
?>
<html>
<head>
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
  <div class="fb-like" data-href="https://www.facebook.com/concoursmariageprojetesgi/app_449000611931438" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
   <form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
    <input type="file" name="source" id="source" /><br />
    <input type="submit" name="submit" value="Envoyer" />
  </form>
   


     <?php 
   



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



function UploadPhoto($session, $files)
{
  try {                  
    $response = (new FacebookRequest(
      $session, 'POST', '/me/photos', array(
        'source' => $files,
        'message' => 'User provided message'
      )
    ))->execute()->getGraphObject();
    echo "Posted with id: " . $response->getProperty('id');
  } catch(FacebookRequestException $e) {
    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();
  } 
}

 if(isset($_FILES) && isset($_FILES['source']))
  {
    print_r($session);
    print_r($_POST);
    UploadPhoto($session, $_POST);
  }
?>



</body>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '449000611931438',
      xfbml      : true,
      version    : 'v2.3'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/fr_FR/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);       
   }(document, 'script', 'facebook-jssdk'));
</script>



</html>