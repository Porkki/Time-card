<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] == "employee"){
        header("location: index.php");
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

        <title>Lisää käyttäjä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Luo käyttäjä</h1>
                    <hr>
                    <form id="target" method="POST">
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="class"><b>Käyttäjäluokka</b></label>
                                <select class=form-control name="class" required>
                                    <?php if ($_SESSION["class"] == "admin") { ?>
                                    <option value="admin">Ylläpitäjä</option>
                                    <?php } ?>
                                    <option value="employer">Työnantaja</option>
                                    <option value="employee" selected>Työntekijä</option>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label for="user_company_id"><b>Yritys</b></label>
                                <select class="form-control" id="user_company_id" name="user_company_id" required>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="firstname"><b>Etunimi</b></label>
                                <input type="text" class="form-control" name="firstname" placeholder="Matti" required>
                            </div>
                            <div class="form-group col">
                                <label for="lastname"><b>Sukunimi</b></label>
                                <input type="text" class="form-control" name="lastname" placeholder="Meikäläinen" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="username"><b>Käyttäjätunnus</b></label>
                                <input type="text" class="form-control" name="username" placeholder="matti.meikalainen" required>
                            </div>
                            <div class="form-group col">
                                <label for="password"><b>Salasana</b></label>
                                <input type="password" class="form-control" name="password" placeholder="Salasana" required>
                                <input type="hidden" class="form-control" name="postfrom" value="createuser">
                            </div>
                        </div>
                        <hr>
                        <button type="submit" value="submit" name="submit" class="btn btn-success">Luo käyttäjä</button>
                    </form>
                    <!-- Start of user action modals -->
                    <div class="modal fade" id="doneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Käyttäjä lisätty onnistuneesti!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="unsuccessfulModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="unsuccessfulModalLabel">Ilmoitus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Käyttäjän lisäys epäonnistui.<br>
                                    <span id="errormessage"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of user action modals -->
                    <p style="color:red">
                        <?php
                            if (!empty($error)) {
                                echo $error;
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <!-- Page function scripts -->
        <script src="scripts/js/adduser.js"></script>
    </body>
</html>
