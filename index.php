
<?php
session_start();
include('config.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookPageTabHelper;
ini_set('display_errors', 1);
error_reporting('e_all');

FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
$helper = new FacebookRedirectLoginHelper(FB_URL_SITE);


function getPermission($session)
{
    $_SESSION['fb_token'] = (string) $session->getAccessToken();
    $user_permissions = (new FacebookRequest($session, 'GET', '/me/permissions'))->execute()->getGraphObject(GraphUser::className())->asArray();
    //check publish stream permission
    $found_permission = false;
    foreach($user_permissions as $key => $val){
        if($val->permission == 'user_photos' ){
            $found_permission = true;
        }
    }
    return $found_permission;
}
//récupère les informations de session facebook et associe à la session courante
if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
    $session = new FacebookSession($_SESSION['fb_token']);
}
else
{
    $session = $helper->getSessionFromRedirect();
    var_dump($session);
}
?>
<?php
if($session)
{
    $token = (string) $session->getAccessToken();
    $_SESSION['fb_token'] = $token;
    //$_SESSION['fb_token'] = $this->session->getToken();

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
function createAlbum($name, $session, $id){
    $albums = getAlbums($session, $id);
    if ($albums) {
        for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
            if ($albums->getProperty('data')->getProperty($i)->getProperty('name') == $name) {
                $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                break;
            } else {
                $album_id = 'blank';
            }
        }
    }
    // if the album is not present, create the album
    if ($album_id == 'blank') {
        $album_data = array('name' => $_POST['new_album_name'], 'message' => $album_description, );
        $new_album = new FacebookRequest ($session, 'POST', '/'.$id.'/albums', $album_data);
        $new_album = $new_album->execute()->getGraphObject("Facebook\GraphUser");
        $album_id = $new_album->getProperty('id');
    }
    return $album_id;
}
function uploadPhoto($session, $id_user){
    if($_POST['album_id'] == -1){
        $album_id = createAlbum($_POST['new_album_name'], $session, $id_user);
    } else{
        $album_id = $_POST['album_id'];
    }
    $curlFile = array('source' => new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type']));
    try {
        $up = new FacebookRequest ($session, 'POST', '/'.$album_id.'/photos', $curlFile);
        $up->execute()->getGraphObject("Facebook\GraphUser");
    } catch (FacebookApiException $e) {
        error_log($e);
    }
}
//si la session exite on recupère les info de l'utlisateur
if(isset($session) && $_POST['vote'] != '1' && $_POST['vote_photos'] != '1'){
    try {
        if(getPermission($session)){
            $request = new FacebookRequest($session, "GET", "/me");
            $response = $request->execute();
            $user = $response->getGraphObject(GraphUser::className());
            $idUser = $user->getId();
            echo "Bonjour ".$user->getName();
            $albums = getAlbums($session, 'me');

                if ($_POST['show_photos'] == '1') {
                    ?>
                    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">

                        <?php
                        for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                            $album = $albums->getProperty('data')->getProperty($i);
                            $request = new FacebookRequest($session, 'GET', '/' . $album->getProperty('id') . '/photos');
                            $response = $request->execute();
                            $photos = $response->getGraphObject();
                            $photos = $photos->getPropertyAsArray('data');
                            if ($_POST['album_id'] == $album->getProperty('id')) {
                                foreach ($photos as $picture) {
                                    echo('<input type="image" name="icone" src="' . $picture->getProperty('picture') . '" alt="" ><input name="nom" value=' . $picture->getProperty('picture') . ' type="radio"></input></input>' . "</br>");
                                }
                            }
                        } ?>

                        <button id="select_photos" name="select_photos" value="1" type="submit" class="btn btn-primary">
                            Select
                        </button>
                    </form>
                <?php
                }
                if ($_POST['select_photos'] == '1') {
                    echo "POST !!!";
                    $image = $_POST['nom'];
                    try {
                        $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", USER, PASS);
                        $qry = $dbh->prepare("SELECT user_name,user_photo from liste;");
                        $qry->execute();
                        $liste = $qry->fetchAll();
                        print_r($liste);
                        $var = $liste;
                        if (empty($var)) {
                            $qryInsert = $dbh->prepare("INSERT INTO liste (user_name,user_photo) VALUES (:user_name,:user_photo)");
                            $qryInsert->execute(array(
                                ':user_name' => $idUser,
                                ':user_photo' => $image
                            ));
                        } else {
                            foreach ($liste as $key => $valListe) {
                                //on vérifie que l'utilisateur n'a pas deja poster une photo avec son id
                                if ($valListe['user_name'] != $idUser) {
                                    $qryInsert = $dbh->prepare("INSERT INTO liste (user_name,user_photo) VALUES (:user_name,:user_photo)");
                                    $qryInsert->execute(array(
                                        ':user_name' => $idUser,
                                        ':user_photo' => $image
                                    ));
                                }
                            }
                        }
                        $dbh = null;
                    } catch (PDOException $e) {
                        print "Erreur !: " . $e->getMessage() . "<br/>";
                        die();
                    }
                }
                if ($_POST['show_photo_concour'] == '1') {
                    try {
                        $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", USER, PASS);
                        $qry = $dbh->prepare("SELECT user_name,user_photo from liste;");
                        $qry->execute();
                        $liste = $qry->fetchAll();
                        //   print_r($liste);
                        foreach ($liste as $key => $valListe) {
                            if ($valListe['user_name'] == $idUser) {
                                echo 'Votre photo pour le jeu concour est : <img src="' . $valListe['user_photo'] . '" alt="" >';
                            }
                        }
                        $dbh = null;
                    } catch (PDOException $e) {
                        print "Erreur !: " . $e->getMessage() . "<br/>";
                        die();
                    }
                    ?>
                    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
                        <button id="update_photos" name="update_photos" value="1" type="submit" class="btn btn-primary">
                            Modifier votre photo
                        </button>
                    </form>
                <?php
                }
                if ($_POST['update_photos'] == '1') {
                    echo '<form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">
                    <select name="album_id" id="album_id">';
                    for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                        $album_id = $albums->getProperty('data')->getProperty($i)->getProperty('id');
                        $album_name = $albums->getProperty('data')->getProperty($i)->getProperty('name');
                        echo('<option value=' . $album_id . '>' . $album_name . '</option>');
                    }
                    echo '</select>
                    <button id="show_photos_update" name="show_photos_update" value="1" type="submit" class="btn btn-primary">Selectionner une autre photo</button>
                </form>';
                }
                if ($_POST['show_photos_update'] == '1') {
                    ?>
                    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">

                        <?php
                        for ($i = 0; null !== $albums->getProperty('data')->getProperty($i); $i++) {
                            $album = $albums->getProperty('data')->getProperty($i);
                            $request = new FacebookRequest($session, 'GET', '/' . $album->getProperty('id') . '/photos');
                            $response = $request->execute();
                            $photos = $response->getGraphObject();
                            $photos = $photos->getPropertyAsArray('data');
                            if ($_POST['album_id'] == $album->getProperty('id')) {
                                foreach ($photos as $picture) {
                                    echo('<input type="image" name="icone" src="' . $picture->getProperty('picture') . '" alt="" ><input name="nom" value=' . $picture->getProperty('picture') . ' type="radio"></input></input>' . "</br>");
                                }
                            }
                        }?>

                        <button id="select_photos_update" name="select_photos_update" value="1" type="submit"
                                class="btn btn-primary">Select
                        </button>
                    </form>
                <?php
                }
                if ($_POST['select_photos_update'] == '1') {
                    echo "post select_photos_update";
                    $image = $_POST['nom'];
                    echo $image;
                    try {
                        $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", USER, PASS);
                        $qry = $dbh->prepare("SELECT user_name,user_photo from liste;");
                        $qry->execute();
                        $liste = $qry->fetchAll();
                        //   print_r($liste);
                        foreach ($liste as $key => $valListe) {
                            if ($valListe['user_name'] == $idUser) {
                                echo $valListe['user_name'];
                                echo 'Votre photo pour le jeu concour est : <img src="' . $valListe['user_photo'] . '" alt="" >';
                                $qryUpdate = $dbh->prepare("UPDATE liste SET user_photo= ?  WHERE user_name = ?");
                                $qryUpdate->execute(array($image, $idUser));
                                print_r($qryUpdate);
                            }
                        }
                        $dbh = null;
                    } catch (PDOException $e) {
                        echo "ERROR";
                        print "Erreur !: " . $e->getMessage() . "<br/>";
                        die();
                    }
                }
                if ($_POST['submit_upload_photo'] == '1') {
                    uploadPhoto($session, 'me');
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
            <button id="show_photos" name="show_photos" value="1" type="submit" class="btn btn-primary">Selectionner une photo parmis vos album</button>


            <button id="show_photo_concour" name="show_photo_concour" value="1" type="submit" class="btn btn-primary">Voir votre photo du concour</button>
        </form>


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
            <button id="submit_upload_photo" name="submit_upload_photo" value="1" type="submit" class="btn btn-primary">Upload une photo dans vos albums</button>
        </form>
        <div class="fb-like" data-href="https://www.facebook.com/concoursmariageprojetesgi/app_449000611931438" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>

    <?php
    } catch(FacebookRequestException $e) {
        echo "error";
        echo "Exception occured, code: " . $e->getCode();
        echo " with message: " . $e->getMessage();
    }
}
else if($_POST['vote']=='1' && isset($session))
{
    echo "VOTE";
    try {
        $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", USER, PASS);
        $qry = $dbh->prepare("SELECT * from liste;");
        $qry->execute();
        $liste = $qry->fetchAll();
        //   print_r($liste);
        echo '<form class="form-horizontal" enctype="multipart/form-data" method="POST" action="index.php">';
        foreach ($liste as $key => $valListe)
        {
            echo'Voter pour une photo : <input type="image" name="icone" src="' .$valListe['user_photo']. '" alt="" ><button  value="1" type="submit" name="vote_photos">Vote</button>' . "</input>";
            echo '<input type="hidden" value="'.$valListe['user_photo'].'" name="image" ></input>';
            echo '<input type="hidden" value="'.$valListe['nb_vote'].'" name="value_nb_vote" ></input>';
            echo '<div>Nombre de vote : '.$valListe['nb_vote'].'</div>';


        }
        echo'</form>';

        $dbh = null;
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}
else
{

   // $permissions = ['user_photos'];
    $params = array('scope' => 'public_profile, user_photos');
    $loginUrl = $helper->getLoginUrl($params);

    //
    // use javaascript api to open dialogue and perform
    // the facebook connect process by inserting the fb:login-button
    ?>

<?php


}

if($_POST['vote_photos'] == '1' && isset($session))
{

    try{
        $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", USER, PASS);
        print_r($_POST);
        $nbVote =$_POST['value_nb_vote']+1;
        $img =  $_POST['image'];
        echo $nbVote;
        echo $img;
        $qry = $dbh->prepare("SELECT * from liste;");
        $qry->execute();
        $liste = $qry->fetchAll();
        foreach ($liste as $key => $valListe) {
            if ($idUser != $valListe['user_name']) {
                $qryUpdate = $dbh->prepare("UPDATE liste SET nb_vote= ?  WHERE user_photo = ?");
                $qryUpdate->execute(array($nbVote, $img));
            }
        }


        $dbh = null;
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}
if($_POST['regle'] == '1' && isset($session))
{

}
    ?>


<form class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?=$loginUrl?>">
    <button id="participe" name="participe" value="1" type="submit"class="btn btn-block btn-lg btn-default">Je Participe</button>
    <button id="vote" name="vote" value="1" type="submit" class="btn btn-block btn-lg btn-default">Je Vote</button>
    <button id="regle" name="regle" value="1" type="submit" class="btn btn-block btn-lg btn-default">Règle du jeu</button>
</form>




<?php

include('pages/footer.php');
?>