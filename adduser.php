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

        <title>Lisää käyttäjä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
                    <h1>Luo käyttäjä</h1>
                    <hr>
                    <form id="target" class="row g-3">
                        <div class="form-floating col-md-6">
                            <select class=form-select name="class" required>
                                <?php if ($_SESSION["class"] == "admin") { ?>
                                <option value="admin">Ylläpitäjä</option>
                                <?php } ?>
                                <option value="employer">Työnantaja</option>
                                <option value="employee" selected>Työntekijä</option>
                            </select>
                            <label for="class">Käyttäjäluokka</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <select class="form-select" id="user_company_id" name="user_company_id" required>
                            </select>
                            <label for="user_company_id">Yritys</label>
                        </div>

                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" name="firstname" placeholder="Matti" required>
                            <label for="firstname">Etunimi</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" name="lastname" placeholder="Meikäläinen" required>
                            <label for="lastname">Sukunimi</label>
                        </div>

                        <div class="form-floating col-md-6">
                            <input type="text" class="form-control" name="username" placeholder="matti.meikalainen" required>
                            <label for="username">Käyttäjätunnus</label>
                        </div>
                        <div class="form-floating col-md-6">
                            <input type="password" class="form-control" name="password" placeholder="Salasana" required>
                            <label for="password">Salasana</label>
                            <input type="hidden" class="form-control" name="postfrom" value="createuser">
                        </div>
                        <div class="col-12">
                            <button type="submit" value="submit" name="submit" class="btn btn-success">Luo käyttäjä</button>
                        </div>
                    </form>
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
                                    Käyttäjä lisätty onnistuneesti!
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
                                    Käyttäjän lisäys epäonnistui.<br>
                                    <span id="errormessage"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ok</button>
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

        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/adduser.js"></script>
    </body>
</html>
