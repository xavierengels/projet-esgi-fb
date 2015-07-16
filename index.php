


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
function getAlbums($session, $id){
    $request = new FacebookRequest($session, 'GET', '/' . $id . '/albums');
    $response = $request->execute();
    $albums = $response->getGraphObject();

    return $albums;
}
// Si $album_id est null, affiche les photos de tous les albums
function getPhotos($session, $id_user, $album_id) {

    $albums = getAlbums($session, $id_user);
    for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
        $album = $albums->getProperty('data')->getProperty($i);
        $request = new FacebookRequest($session, 'GET', '/'.$album->getProperty('id').'/photos');
        $response = $request->execute();
        $photos = $response->getGraphObject();
        for ($j = 0; null !== $photos->getProperty('data')->getProperty($j); $j++) {
            if($album_id == null || $album_id == $album->getProperty('id')){
                $photo[] = $photos->getProperty('data')->getProperty($j);
            }
        }
    }
    return $photo;
}
//si la session exite on recupère les info de l'utlisateur
if($session) {
    try {
        $_SESSION['fb_token'] = (string) $session->getAccessToken();
        $request_user = new FacebookRequest( $session,"GET","/me");
        $request_user_executed = $request_user->execute();
        /*$user = $request_user_executed->getGraphObject(GraphUser::className());
        $request = new FacebookRequest( $session,"GET","/me/photos");
        $response = $request->execute();*/
        $request = new FacebookRequest($session, "GET", "/me");
        $response = $request->execute();
        $user = $response->getGraphObject(GraphUser::className());

        $albums = getAlbums($session, 'me');
?>
        <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
      <input id="photo" name="photo" class="input-file" type="file">
      <select name="album_id" id="album_id">
          <?php
            for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                $album_name = $albums->getProperty('data')->getProperty($i)->getProperty('name');
                echo('<option value='.$album_id.'>'.$album_name.'</option>');
            }
          ?>
          <option value='-1'>Nouvel Album</option>
      </select>
      <input id="new_album_name" name="new_album_name" class="input-file" type="text">
      <button id="submit_upload_photo" name="submit_upload_photo" value="1" type="submit" class="btn btn-primary">Upload</button>
    </form>
    <?php

        //$albums = $response->getGraphObject();
        //print_r($albums);
       // $album_data =  $albums->getProperty('data');
       // print_r($album_data);
      //  $photos = json_decode($response->getRawResponse(), true);


        /*print_r($album_data->asArray());
        $request = new FacebookRequest($session, 'GET', '/'.$album->getProperty('id').'/photos');
        $response = $request->execute();
        $photos = $response->getGraphObject();
        print_r($photos);*/

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
    echo "<a href='".$loginUrl."'>Se connecter</a>";
}
include('pages/footer.php');
?>

