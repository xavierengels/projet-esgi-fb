


<?php
include('config.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

ini_set('display_errors', 1);
error_reporting('e_all');
session_start();

FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
$helper = new FacebookRedirectLoginHelper(FB_URL_SITE);

//récupère les informations de session facebook et associe à la session courante
if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
  $session = new FacebookSession($_SESSION['fb_token']);

}
else
{
     $session = $helper->getSessionFromRedirect();

}

?>


  <?php
  if($session)
  {
     $token = (string) $session->getAccessToken();
     $_SESSION['fb_token'] = $token;
  }
  else
  {
    "Pas encore de session enregistré";
  }
  include('pages/header.php');
  include('pages/menu.php');
  ?>


<div>
<center><img src="/images/concourstotal.jpg"></center>
</div>

<br><br>

<div>
<a href="<?=$loginUrl?>/participer.php" class="btn btn-block btn-lg btn-default">Je Participe</a>
<a href="plusrecents.php" class="btn btn-block btn-lg btn-default">Je Vote</a>
</div>

<?php
//si la session exite on recupère les info de l'utlisateur
if($session) {
    try {
        $_SESSION['fb_token'] = (string) $session->getAccessToken();
        $request_user = new FacebookRequest( $session,"GET","/me");
        $request_user_executed = $request_user->execute();
        $user = $request_user_executed->getGraphObject(GraphUser::className());
        $request = new FacebookRequest( $session,"GET","/me/albums");
        $response = $request->execute();
        $albums = $response->getGraphObject();
        $album_data =  $user_profile->getProperty('data');
        print_r($album_data->asArray());
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
    //recupère l'url de connexion pour le bouton de connexion
    $loginUrl = $helper->getLoginUrl();
    echo "<a href='".$loginUrl."'>Se co nnecter</a>";
}
include('pages/footer.php');
?>

