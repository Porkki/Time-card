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
                    <form id="target" class="row g-3">
                        <div class="col-md-6">
                            <label for="class"><b>Käyttäjäluokka</b></label>
                            <select class=form-select id="class" name="class" required>
                                <?php if ($_SESSION["class"] == "admin") { ?>
                                <option value="admin">Ylläpitäjä</option>
                                <?php } ?>
                                <option value="employer">Työnantaja</option>
                                <option value="employee" selected>Työntekijä</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="user_company_id"><b>Yritys</b></label>
                            <select class="form-select" id="user_company_id" name="user_company_id" required>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="firstname"><b>Etunimi</b></label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastname"><b>Sukunimi</b></label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>

                        <div class="col-md-6">
                            <label for="username"><b>Käyttäjätunnus</b></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password"><b>Salasana</b></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Salasana">
                            <input type="hidden" class="form-control" id="id" name="id">
                            <input type="hidden" class="form-control" name="postfrom" value="updateuser">
                        </div>
                        <div class="col-12">
                            <a href="modifyuser.php" class="btn btn-secondary">Peruuta</a>
                            <button type="submit" value="submit" name="submit" class="btn btn-success">Päivitä</button>
                        </div>
                        
                    </form>
                    <hr>
                    <p><b>Huom!</b> Jos muutat salasana kenttää niin tällöin myös muuttuu käyttäjän salasana. Jätä kenttä tyhjäksi jos et halua muuttaa käyttäjän salasanaa.</p>
                    <!-- Start of user action modals -->
                    <div class="modal fade" id="doneModal" tabindex="-1"  >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                                    <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Käyttäjä päivitetty onnistuneesti!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="unsuccessfulModal" tabindex="-1"  >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="unsuccessfulModalLabel">Ilmoitus</h5>
                                    <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span id="errormessage"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ok</button>
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