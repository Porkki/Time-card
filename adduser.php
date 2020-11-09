<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] == "employee"){
        header("location: login.php");
        exit;
    }
?>

<?php
    // TODO MOVE THIS LOGIC TO JSON
    // Yritysten nimien haku lomakkeeseen
    require_once "config.php";
    $business = "";
    // Show employer only own bussiness so user cant create new accounts to company that he does not manage
    if ($_SESSION["class"] == "employer") {
        if ($stmt = $con->prepare("SELECT user_company_id from users WHERE id = ?")) {
            $stmt->bind_param("i", $param_id);
            $param_id = trim($_SESSION["id"]);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $user_company_id = $result->fetch_array(MYSQLI_ASSOC);
                    $companyid = $user_company_id["user_company_id"];
                    $companyresult = $con->query('SELECT id, company_name FROM company WHERE id = ' . $companyid);
                    
                    if ($companyresult->num_rows > 0) {
                        while ($row = $companyresult->fetch_assoc()) {
                            $business .= '<option value = "'.$row['id'].'">'.$row['company_name'].'</option>';
                        }
                    }
                }
            }
            $stmt->close();
        }
    } else {
        $result = $con->query('SELECT id, company_name FROM company ORDER BY company_name');

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $business .= '<option value = "'.$row['id'].'">'.$row['company_name'].'</option>';
            }
        }
    }
    $con->close();
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                                    <?php echo $business; ?>
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
                                    <span id="errormessage"></span>.
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>

<script>
$(document).ready(function() {
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "adduser_script.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var username = data.username;
                var error = data.error;
                if (username) {
                    document.getElementsByName("firstname")[0].value = "";
                    document.getElementsByName("lastname")[0].value = "";
                    document.getElementsByName("username")[0].value = "";
                    document.getElementsByName("password")[0].value = "";
                    $('#doneModal').modal('show');
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});
</script>
