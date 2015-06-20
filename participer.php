<?php
require_once('facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;
ini_set('display_errors', 1);
error_reporting('e_all');
session_start();
$session = $_SESSION['fb_token'];
$appId = "449000611931438";
$appId = "4081c73247e8a9729dc939b5fe6565c6";
$facebook = new Facebook(array(
    'appId'  => $appId,	//your facebook application id
    'secret' => $secret,	//your facebook secret code
    'cookie' => true
));
/*$config = array(
    'appId' => '449000611931438',
    'secret' => '4081c73247e8a9729dc939b5fe6565c6',
    'fileUpload' => true,
);

$facebook = new Facebook($config);
$user_id = $facebook->getUser();*/



echo "test session : ".$session;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="https://projet-esgi-fb.herokuapp.com/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document sans nom</title>
</head>

<body>
<nav id="nav">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container">



                <ul class="nav nav-pills">

                    <li role="presentation"><a href="index.php">Acceuil</a></li>
                    <li role="presentation" class="active"><a href="participer.php">Participer</a></li>
                    <li role="presentation"><a href="plusrecents.php">Recents</a></li>
                    <li role="presentation"><a href="lesmeilleurs.php">Populaire</a></li>

                </ul>
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
                }?>
            </div>
        </div>
    </div>
</nav>
</body>
</html>
