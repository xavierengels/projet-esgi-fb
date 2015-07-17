


<?php
session_start();
include('config.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

ini_set('display_errors', 1);
error_reporting('e_all');

  include('pages/header.php');
  include('pages/menu.php');
  ?>


<div>
<center><img src="/images/concourstotal.jpg"></center>
</div>

<br><br>

<div>
<a href="<?=$loginUrl?>/participer.php" class="btn btn-block btn-lg btn-default">Je Participe</a>
<a href="plusrecents.php" class="btn btn-block btn-lg btn-default">Je Vote</a>
</div>

<?php include('pages/footer.php'); ?>

