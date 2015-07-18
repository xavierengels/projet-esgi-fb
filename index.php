


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

  ?>


<div>
<center><img src="/images/concourstotal.jpg"></center>
</div>

<br><br>
<?php

function getAlbums($session, $id){
    $request = new FacebookRequest($session, 'GET', '/' . $id . '/albums');
    $response = $request->execute();
    $albums = $response->getGraphObject();

    return $albums;
}
//si la session exite on recupère les info de l'utlisateur
if($session) {
    try {

        $_SESSION['fb_token'] = (string) $session->getAccessToken();
        $request_user = new FacebookRequest( $session,"GET","/me");
        $request_user_executed = $request_user->execute();
        $user_permissions = (new FacebookRequest($session, 'GET', '/me/permissions'))->execute()->getGraphObject(GraphUser::className())->asArray();

        //check publish stream permission
        $found_permission = false;

        foreach($user_permissions as $key => $val){
            if($val->permission == 'user_photos'){
                $found_permission = true;

            }
        }
        if($found_permission){
            $request = new FacebookRequest($session, "GET", "/me");
            $response = $request->execute();
            $user = $response->getGraphObject(GraphUser::className());
            $idUser = $user->getId();
            echo "Bonjour ".$user->getName();
            $albums = getAlbums($session, 'me');
            if($_POST['show_photos'] == '1') {
             ?>   <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">

                <?php
                for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                    $album = $albums->getProperty('data')->getProperty($i);
                    $request = new FacebookRequest($session, 'GET', '/' . $album->getProperty('id') . '/photos');
                    $response = $request->execute();
                    $photos = $response->getGraphObject();
                    $photos = $photos->getPropertyAsArray('data');

                    if($_POST['album_id']==$album->getProperty('id')) {
                        foreach ($photos as $picture) {
                            echo ('<input type="image" name="icone" src="' . $picture->getProperty('picture') . '" alt="" ><input name="nom" value=' . $picture->getProperty('picture') . ' type="radio"></input></input>'."</br>");

                        }
                    }
                }?>

                <button id="select_photos" name="select_photos" value="1" type="submit" class="btn btn-primary">Select</button>
        </form>
<?php
            }
            if($_POST['select_photos'] == '1') {
                echo "POST !!!";
                $image =  $_POST['nom'];
                try {
                    $user =  'blnwydiaqtvkyp';
                    $pass =  'yODIF2ML7nUOjWl-jBPkS54hHw';
                    $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", $user, $pass);


                    $qry = $dbh->prepare("SELECT user_name,user_photo from liste;");
                    $qry->execute();
                    $liste = $qry->fetchAll();

                    foreach($liste as $key => $valListe)
                    {   //on vérifie que l'utilisateur n'a pas deja poster une photo avec son id
                        echo $valListe['user_name'];
                        echo $idUser;
                        if($valListe['user_name']!=$idUser)
                        {
                            $qryInsert = $dbh->prepare("INSERT INTO liste (user_name,user_photo) VALUES (:user_name,:user_photo)");
                            $qryInsert->execute(array(
                                ':user_name' => $idUser,
                                ':user_photo' => $image
                            ));
                        }


                    }
                    $dbh = null;
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }
            }


            if($_POST['show_photo_concour'] == '1') {
                echo "POST !!!";


                try {
                    echo "test";
                    $user =  'blnwydiaqtvkyp';
                    $pass =  'yODIF2ML7nUOjWl-jBPkS54hHw';
                    $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", $user, $pass);

                    $qry = $dbh->prepare("SELECT user_name,user_photo from liste;");
                    $qry->execute();
                    $liste = $qry->fetchAll();
                    //   print_r($liste);
                    foreach($liste as $key => $valListe)
                    {
                        if($valListe['user_name']==$idUser)
                        {
                            echo 'Votre photo pour le jeu concour est : "."<img src="'.$valListe['user_photo'].'" alt="" >';
                        }


                    }

                    $dbh = null;
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }
                ?>
                <button id="update_photos" name="update_photos" value="1" type="submit" class="btn btn-primary">Modifier votre photo</button>
            <?php
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

        Voir votre photo du concour
        </br>
        <button id="show_photo_concour" name="show_photo_concour" value="1" type="submit" class="btn btn-primary">Voir votre photo du concour</button>
        </form>
        <?php



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


   // echo "<a href='".$loginUrl."'>Se connecter</a>";
    ?>
    <div>
<a href="<?=$loginUrl?>" class="btn btn-block btn-lg btn-default">Je Participe</a>
<a href="plusrecents.php" class="btn btn-block btn-lg btn-default">Je Vote</a>
</div>
<?php
}



include('pages/footer.php');
?>

