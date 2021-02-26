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

        <title>Muokkaa yritystä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
                    <h1>Muokkaa yritystä</h1>
                    <hr>
                    <form id="target" enctype="multipart/form-data" method="POST" class="row g-3">
                        <div class="col-md-6">
                            <?php
                            $id = $_GET["id"];
                            $companylogoPath = "img/company_logos/$id.png";

                            if (file_exists($companylogoPath)) {
                                echo "<img class='img-fluid' src='$companylogoPath'>";
                            } else {
                                echo "<h1 class='text-center'>Logoa ei löydy</h1>";
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <label for="company_logo"><b>Valitse uusi logo</b></label><br>
                            <input type="file" name="company_logo" accept="image/png">
                            <br>
                            <small id="company_logoHelp" class="form-text text-muted">Vain .png muotoiset</small><br>
                            <button type="button" class="btn btn-danger" id="remove_logo">Poista logo</button>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="company_name"><b>Yrityksen nimi</b></label>
                            <input type="text" class="form-control" name="company_name" placeholder="Esimerkki Oy" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ytunnus"><b>Y-tunnus</b></label>
                            <input type="text" class="form-control" name="ytunnus" placeholder="1234567-1" required>
                        </div>

                        <div class="col-12">
                            <label for="company_address"><b>Osoite</b></label>
                            <input type="text" class="form-control" name="company_address" placeholder="Yrityspolku 1" required>
                        </div>

                        <div class="col-md-6">
                            <label for="company_postcode"><b>Postinumero</b></label>
                            <input type="text" class="form-control" name="company_postcode" placeholder="12345" required>
                        </div>
                        <div class="col-md-6">
                            <label for="company_area"><b>Paikkakunta</b></label>
                            <input type="text" class="form-control" name="company_area" placeholder="Helsinki" required>
                        </div>

                        <div class="col-12">
                            <label for="is_client"><b>Voimassa oleva asiakkuus</b></label>
                            <select class="form-control" name="is_client">
                                <option value=1>Kyllä</option>
                                <option value=0>Ei</option>
                            </select>
                            <input type="hidden" class="form-control" name="id">
                            <input type="hidden" class="form-control" name="postfrom" value="updatecompany">
                        </div>
                        <div class="col-12">
                            <a href="modifycompany.php" class="btn btn-secondary">Peruuta</a>
                            <button type="submit" value="submit" name="submit" class="btn btn-success">Päivitä</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
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
                        Yritys päivitetty onnistuneesti!
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
                        Yrityksen päivitys epäonnistui.<br>
                        <span id="errormessage"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of user action modals -->

        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/updatecompany.js"></script>
    </body>
</html>