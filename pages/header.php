<html>
<head>
    <script src="https://projet-esgi-fb.herokuapp.com/jquery-2.1.4.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://projet-esgi-fb.herokuapp.com/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '449000611931438',
                cookie      :true,
                xfbml      : true,
                version    : 'v2.3'
            });
        };
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/fr_FR/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>


</head>
<body>