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
$session = (string) $_SESSION['fb_token'];
include('pages/header.php');
include('pages/menu.php');
FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
$helper = new FacebookRedirectLoginHelper(FB_URL_SITE);
if(isset($_SESSION) && isset($_SESSION['fb_token']))
{
    $session = new FacebookSession($_SESSION['fb_token']);

}
else
{
    $session = $helper->getSessionFromRedirect();

}
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
        echo "session : ".$session."</br>";


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

