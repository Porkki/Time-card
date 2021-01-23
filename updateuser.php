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
        <?php include "scripts/include/head.php"; ?>

        <title>Muokkaa käyttäjää</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
                    <h1>Muokkaa käyttäjää</h1>
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
                                    <option value="employee">Työntekijä</option>
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
                                <input type="text" class="form-control" name="firstname" required>
                            </div>
                            <div class="form-group col">
                                <label for="lastname"><b>Sukunimi</b></label>
                                <input type="text" class="form-control" name="lastname" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="username"><b>Käyttäjätunnus</b></label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group col">
                                <label for="password"><b>Salasana</b></label>
                                <input type="password" class="form-control" name="password" placeholder="Salasana">
                                <input type="hidden" class="form-control" name="id">
                                <input type="hidden" class="form-control" name="postfrom" value="updateuser">
                            </div>
                        </div>
                        <hr>
                        <button type="submit" value="submit" name="submit" class="btn btn-success">Päivitä</button>
                        <a href="modifyuser.php" class="btn btn-danger">Peruuta</a>
                    </form>
                    <hr>
                    <p><b>Huom!</b> jos muutat salasana kenttää niin tällöin myös muuttuu käyttäjän salasana. Jätä kenttä tyhjäksi jos et halua muuttaa käyttäjän salasanaa.</p>
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
                                    Käyttäjä päivitetty onnistuneesti!
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
                                    Käyttäjän päivitys epäonnistui.<br>
                                    <span id="errormessage"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of user action modals -->
                </div>
            </div>
        </div>

        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/updateuser.js"></script>
    </body>
</html>