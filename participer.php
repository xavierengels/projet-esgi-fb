<?php
include('config.php');
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;
ini_set('display_errors', 1);
error_reporting('e_all');
session_start();
$session = $_SESSION['fb_token'];
include('pages/header.php');
include('pages/menu.php');



echo "test session : ".$session;
$request = new FacebookRequest(
    $session,
    'GET',
    '/me/photos'
);
$response = $request->execute();
$graphObject = $response->getGraphObject();
    print_r($graphObject);
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

                if($session) {
                   if($_POST){
                    try {
                        $img = realpath($_FILES["source"]["tmp_name"]);
                        // allow uploads
                        $facebook->setFileUploadSupport("http://" . $_SERVER['SERVER_NAME']);
                        // add a status message
                        $photo = $facebook->api('/me/photos', 'POST',
                            array(
                                'source' => '@' . $img,
                                'message' => 'This photo was uploaded via www.WebSpeaks.in'
                            )
                        );

                    } catch(FacebookRequestException $e) {

                        echo "Exception occured, code: " . $e->getCode();
                        echo " with message: " . $e->getMessage();

                    }
                   }
                }else
                {
                    echo "Aucune session";
                }
                include('pages/footer.php');
                ?>

