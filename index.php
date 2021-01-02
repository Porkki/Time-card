<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- FontAwesome -->
        <link href="css/all.css" rel="stylesheet">

        <title>Kirjaudu sisään</title>
    </head>
    <body>
        <div class="container w-auto m-auto mx-auto bg-white box-shadow p-3 animate">
            <img src="img/logo.png" class="mx-auto img-fluid d-block">
            <h1 class="text-center">Työajanseuranta</h1>
            <form id="login" method="POST">
                <div class="form-group">
                    <label for="uname"><b>Käyttäjätunnus</b></label>
                    <input type="text" class="form-control" placeholder="Syötä käyttäjätunnus" name="username" required>
                </div>
                <div class="form-group">
                    <label for="psw"><b>Salasana</b></label>
                    <input type="password" class="form-control" placeholder="Syötä salasana" name="password" required>
                </div>
                <button type="submit" value="submit" name="submit" class="btn btn-success w-100">Kirjaudu</button>
            </form>
        </div>
        <div class="modal fade" id="unsuccessfulModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unsuccessfulModalLabel">Kirjautuminen epäonnistui</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span id="errormessage"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <!-- Page function scripts -->
        <script src="scripts/js/index.js"></script>
    </body>
</html>