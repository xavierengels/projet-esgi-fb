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
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
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
                    $qry = $dbh->prepare("INSERT INTO liste (user_name, id_photo) VALUES (:user_name, :id_photo)");
                    print_r($qry);
                    $qry->execute(array(
                        ':user_name' => 'marcounet',
                        ':id_photo' => 1
                    ));
                    print_r($qry);
                    foreach($dbh->query('SELECT * from liste') as $row) {
                        print_r($row);
                    }
                    $dbh = null;
                } catch (PDOException $e) {
                    print "Erreur !: " . $e->getMessage() . "<br/>";
                    die();
                }

                if($session) {

                    try {

                        // Upload to a user's profile. The photo will be in the
                        // first album in the profile. You can also upload to
                        // a specific album by using /ALBUM_ID as the path
                        $response = (new FacebookRequest(
                            $session, 'POST', '/me/photos', array(
                                'source' => new CURLFile('path/to/file.name', 'image/png'),
                                'message' => 'User provided message'
                            )
                        ))->execute()->getGraphObject();

                        // If you're not using PHP 5.5 or later, change the file reference to:
                        // 'source' => '@/path/to/file.name'

                        echo "Posted with id: " . $response->getProperty('id');

                    } catch(FacebookRequestException $e) {

                        echo "Exception occured, code: " . $e->getCode();
                        echo " with message: " . $e->getMessage();

                    }

                }?>
            </div>
        </div>
    </div>
</nav>
</body>
</html>
