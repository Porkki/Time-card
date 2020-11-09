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

/*
 * This file handles getting single or all user data, updating it and removing it.
 * 
 * TODO: 4.11.20 Tee loggaus jonnekkin kun yritys yrittää poistaa käyttäjää joka ei kuulu heille,
 * Tarkoittaa siis sitä että linkin remove atribuuttia yritetään muokata käsin koska käyttäjä listaus sivustolla ei pitäisi näkyä kuin yrityksen omia käyttäjiä
 * Myös ylipäätänsä loggaus kaikille toiminnoille, kuka teki, milloin teki ja missä teki (ip osoite)
*/
    // Fetching userdata to form
    require_once "../config.php";

    // Declaring empty variables
    $return_arr = Array();
    $userObj = new stdClass();
    // Print user from id data
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        if ($stmt = $con->prepare("SELECT id, username, class, firstname, lastname, user_company_id from users WHERE id = ?")) {
            $stmt->bind_param("i", $param_id);
            $param_id = trim($_GET["id"]);

            // Fetching user data from database and echoing it out to json
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($return_arr,$row);
                    }
                    echo json_encode($return_arr);
                }
            }
            $stmt->close();
        }
    } else if (isset($_GET["remove"]) && !empty(trim($_GET["remove"]))) {
        // User removing
        // Make sure that userid that we are removing belongs to the company who tries to remove it
        if ($_SESSION["class"] == "employer") {
            // Get current users company id
            if ($stmt = $con->prepare("SELECT user_company_id from users WHERE id = ?")) {
                $stmt->bind_param("i", $param_id);
                $param_id = trim($_SESSION["id"]);
    
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $user_company_id = $result->fetch_array(MYSQLI_ASSOC);
                        $companyid = $user_company_id["user_company_id"];
                        // Get user company id from user we are removing
                        if ($stmt2 = $con->prepare("SELECT user_company_id from users where id = ?")) {
                            $stmt2->bind_param("i", $param_removeid);
                            $param_removeid = trim($_GET["remove"]);
                            if ($stmt2->execute()) {
                                $result2 = $stmt2->get_result();
                                if ($result2->num_rows == 1) {
                                    $userdata_array = $result2->fetch_array(MYSQLI_ASSOC);
                                    if ($userdata_array["user_company_id"] == $companyid) {
                                        // If user company id matches with employers company id then we can remove the user
                                        if ($con->query("DELETE FROM users WHERE id = " . $param_removeid)) {
                                            header("location: ../modifyuser.php?result=done");
                                        } else {
                                            // Unsuccesful mysqli
                                            header("location: ../modifyuser.php?result=unsuccesful");
                                        }
                                    } else {
                                        // User is not in same company
                                        header("location: ../modifyuser.php?result=unsuccesful");
                                    }
                                } else {
                                    // User not found
                                    header("location: ../modifyuser.php?result=unsuccesful");
                                }
                            }
                            $stmt2->close();
                        }
                    }
                }
                $stmt->close();
            }
        } else {
            // Else user is admin so no need to check rights
            // Prepare delete statement
            if ($stmt = $con->prepare("DELETE FROM users WHERE id = ?")) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("i", $param_id);

                // Set parameters
                $param_id = trim($_GET["remove"]);

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Records deleted successfully. Redirect to landing page
                    header("location: ../modifyuser.php?result=done");
                } else{
                    header("location: ../modifyuser.php?result=unsuccesful");
                }
            }
            // Close statement
            $stmt->close();
        }
        
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Account updating
        if (isset($_POST["username"], $_POST["firstname"], $_POST["lastname"], $_POST["class"], $_POST["user_company_id"])) {
            if ($stmt = $con->prepare("UPDATE users SET username=?, password=IFNULL(?, password), class=?, firstname=?, lastname=?, user_company_id=? WHERE id = ?")) {
                $stmt->bind_param("sssssii", $username, $password, $class, $firstname, $lastname, $user_company_id, $id);
                // Set parameters
                $username = trim($_POST["username"]);
                // If there is no new password set then set password to null so mysql statement can skip it
                if (empty($_POST["password"])) {
                    $password = NULL;
                } else {
                    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                }
                $class = trim($_POST["class"]);
                $firstname = trim($_POST["firstname"]);
                $lastname = trim($_POST["lastname"]);
                $user_company_id = trim($_POST["user_company_id"]);
                $id = trim($_POST["id"]);

                $stmt->execute();

                //Json for modal popup
                $userObj->username = $username;
                echo json_encode($userObj);
                $stmt->close();
            }
        }  else {
            $userObj->error = "Tarkista että kaikki kentät on täytetty oikein";
            echo json_encode($userObj);
        }

    } else if ($_SESSION["class"] == "employer") {
        // Show employers only their own company data
        if ($stmt = $con->prepare("SELECT user_company_id from users WHERE id = ?")) {
            $stmt->bind_param("i", $param_id);
            $param_id = trim($_SESSION["id"]);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $user_company_id = $result->fetch_array(MYSQLI_ASSOC);
                    $companyresult = $con->query("SELECT id, username, firstname, lastname, user_company_id FROM users where user_company_id = " . $user_company_id["user_company_id"]);
                    if ($companyresult->num_rows > 0) {
                        while ($row = $companyresult->fetch_array(MYSQLI_ASSOC)) {
                            array_push($return_arr,$row);
                        }
                        echo json_encode($return_arr);
                    }
                }
            }
            $stmt->close();
        }
    } else {
        // Print whole database for admin usage
        $result = $con->query("SELECT id, username, firstname, lastname, user_company_id FROM users ORDER BY lastname");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                array_push($return_arr,$row);
            }

            echo json_encode($return_arr);
        }
    }
    $con->close();
?>