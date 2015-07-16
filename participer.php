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

if($session) {
    echo "session : ".$session;
    try {


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

