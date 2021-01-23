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
        <?php include "scripts/include/head.php"; ?>

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
        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/index.js"></script>
    </body>
</html>