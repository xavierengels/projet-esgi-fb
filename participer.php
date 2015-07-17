<?php
session_start();
include('config.php');
require('facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookCanvasLoginHelper;
ini_set('display_errors', 1);
error_reporting('e_all');


include('pages/header.php');
include('pages/menu.php');

FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);
if(isset($_SESSION['fb_token'])) {
  //  echo $_SESSION['fb_token'];
    $session = new FacebookSession($_SESSION['fb_token']);
    ECHO $session;
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

