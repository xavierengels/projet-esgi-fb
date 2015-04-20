

<?php
include_once('conf.php');
ini_set('display_errors', 1);
error_reporting(e_all);
session_start();

FacebookSession::setDefaultApplication('449000611931438', '4081c73247e8a9729dc939b5fe6565c6');
$helper = new FacebookRedirectLoginHelper('https://projet-esgi-fb.herokuapp.com/');
$loginUrl = $helper->getLoginUrl();

if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
	$session = new FacebookSession($_SESSION['fb_token']);
	var_dump($session);
}
else
{
	   $session = $helper->getSessionFromRedirect();
      
}	

?>
<html>
<head>
</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3&appId=699704450158910";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
  <?php
  if($session)
  {
  	 $token = (string) $session->getAccessToken();
     $_SESSION['fb_token'] = $token;
  }
  else
  {
  	 $loginUrl = $helper->getLoginUrl();
     echo "<a href='".$loginUrl."'>Se connecter</a>";
  }
<<<<<<< HEAD

  echo "test";
=======
<?php
    if($session)
    {
        $token = (string) $session->getAccessToken();
        $_SESSION['fb_token'] = $token;
    }
    else
    {
        $loginUrl = $helper->getLoginUrl();
        echo "<a href='".$loginUrl."'>Se connecter</a>";
    }
    echo "test";

      
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
   ?>



=======
      

    
   
if($session) {

    try {

        $user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());

        echo "Name: " . $user_profile->getName();

    } catch(FacebookRequestException $e) {

        echo "Exception occured, code: " . $e->getCode();
        echo " with message: " . $e->getMessage();

    }   

}

if($session) {
    try {
        $user_profile = (new FacebookRequest(
        $session, 'GET', '/me'
        ))->execute()->getGraphObject(GraphUser::className());

        echo "Name: " . $user_profile->getName();
    } catch(FacebookRequestException $e) {
        echo "Exception occured, code: " . $e->getCode();
        echo " with message: " . $e->getMessage();
  }   
}
?>
>>>>>>> 52071a70e39139a344ce123798d0e19101db1542
</body>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '830895360333908',
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

