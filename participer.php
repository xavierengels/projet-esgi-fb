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
$session = $_SESSION['fb_token'];
include('pages/header.php');
include('pages/menu.php');
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
                    $request = new FacebookRequest($session, 'GET', '/' . $album->getProperty('id') . '/photos?fields=picture&limit=5');
                    $response = $request->execute();
                    $photos = $response->getGraphObject();
                    $photos = $photos->getPropertyAsArray('data');
                    // print_r($photos->getProperty('data')  );

                    foreach($photos as $picture) {

                        echo '<img src="'.$picture->getProperty('picture').'" alt="" />';
                    }

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

?>


                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <input type="file" name="source" id="source" /><br />
                    <input type="submit" name="submit" value="Envoyer" />
                </form>

                <?php

                try {
                    $user =  'blnwydiaqtvkyp';
                    $pass =  'yODIF2ML7nUOjWl-jBPkS54hHw';
                    $dbh = new PDO("pgsql:host=ec2-54-247-118-153.eu-west-1.compute.amazonaws.com;port=5432;dbname=d7fa01u2c92h52", $user, $pass);
                    /*$q = $dbh->prepare("select column_name, data_type, character_maximum_length
                                           from INFORMATION_SCHEMA.COLUMNS
                                        where table_name = 'liste'");
                    $q->execute();
                    $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
                    print_r($table_fields);*/

                    /*$qry = $dbh->prepare("INSERT INTO liste (user_name) VALUES (:user_name)");
                    $qry->execute(array(
                        ':user_name' => 'marcounet'
                    ));*/


                    /*$qry = $dbh->prepare("SELECT * from liste;");
                    $qry->execute();
                    $noms = $qry->fetchAll();
                    print_r($noms);*/

                    $dbh = null;
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }

                include('pages/footer.php');
                ?>

