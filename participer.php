<?php
session_start();
include('config.php');

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookCanvasLoginHelper;
    ini_set('display_errors', 1);
error_reporting('E_ALL');


include('pages/header.php');
include('pages/menu.php');

FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);

$fb = new Facebook\Facebook([/* */]);
$canvasHelper = $fb->getCanvasHelper();

try {
    $accessToken = $canvasHelper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
}

if (isset($accessToken)) {
    // Logged in.
    echo "test";
}






?>




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

