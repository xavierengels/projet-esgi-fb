


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
    print_r($albumsl);
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
        $user_permissions = (new FacebookRequest($session, 'GET', '/me/permissions'))->execute()->getGraphObject(GraphUser::className())->asArray();

        //check publish stream permission
        $found_permission = false;

        foreach($user_permissions as $key => $val){
            echo $val->permission."</br>";
            if($val->permission == 'user_photos'){
                $found_permission = true;

            }
        }
        if($found_permission){
        $request = new FacebookRequest($session, "GET", "/me");
        $response = $request->execute();
        $user = $response->getGraphObject(GraphUser::className());

        $albums = getAlbums($session, 'me');
        if($_POST['show_photos'] == '1') {
            /*echo "POST !!!".$_POST['album_id'];
            $listPhotos = getPhotos($session, 'me', $_POST['album_id']);
            print_r($listPhotos);
            foreach($listPhotos as $photo){
                echo "<img src='{$photo->getProperty("source")}' />", "<br />";
                echo "??????????????";
            }*/
            //$albums = getAlbums($session, 'me');
          //  print_r($albums);
            for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                $album = $albums->getProperty('data')->getProperty($i);
                $request = new FacebookRequest($session, 'GET', '/' . $album->getProperty('id') . '/photos');
                $response = $request->execute();
                $photos = $response->getGraphObject();
                print_r($photos->getProperty('data') );
               foreach($photos->getProperty('data') as $key => $value)
               {
                   echo $photos->getProperty('data')->getProperty($key);
                   
               }
                /*for ($j = 0; null !== $photos->getProperty('data')->getProperty($j); $j++) {
                    if ($album_id == null || $album_id == $album->getProperty('id')) {
                        $photo[] = $photos->getProperty('data')->getProperty($j);

                    }
                }*/

            }




                    //do your stuff
                }

        }

?>
        <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
            <select name="album_id" id="album_id">
                <?php
                for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                    $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                    $album_name = $albums->getProperty('data')->getProperty($i)->getProperty('name');
                    echo('<option value='.$album_id.'>'.$album_name.'</option>');
                }
                ?>
            </select>
            <button id="show_photos" name="show_photos" value="1" type="submit" class="btn btn-primary">Show</button>
        </form>
    <?php


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


    $loginUrl = $helper->getLoginUrl();


    echo "<a href='".$loginUrl."'>Se connecter</a>";
}
include('pages/footer.php');
?>

