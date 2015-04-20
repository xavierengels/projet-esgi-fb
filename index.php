

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
      

    
   ?>
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

