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
 * This file handles getting single or all company data, updating it and removing it.
 * 
 * TODO: 4.11.20 
 * Myös ylipäätänsä loggaus kaikille toiminnoille, kuka teki, milloin teki ja missä teki (ip osoite)
 * If company is removed or company has stopped the service, update all users which are listed under it to inactive
*/
    require_once "../config.php";

    // Declaring empty variables
    $return_arr = Array();
    $companyObj = new stdClass();

    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        if ($stmt = $con->prepare("SELECT * from company WHERE id = ?")) {
            $stmt->bind_param("i", $param_id);
            $param_id = trim($_GET["id"]);

            // Fetching company data from database and echoing it out to json
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($return_arr,$row);
                    }
                    echo json_encode($return_arr);
                } else {
                    // No company found
                }
            }
            $stmt->close();
            exit();
        }
    } else if (isset($_GET["remove"]) && !empty(trim($_GET["remove"])) && $_SESSION["class"] == "admin") {
        // Only admins can remove companies
        // Prepare delete statement
        // https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php
        if ($stmt = $con->prepare("DELETE FROM company WHERE id = ?")) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = trim($_GET["remove"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records deleted successfully. Redirect to landing page
                header("location: ../modifycompany.php?result=done");
                exit();
            } else {
                header("location: ../modifycompany.php?result=unsuccesful");
            }
        }

        // Close statement
        $stmt->close();
    } if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["class"] == "admin") {
        if (isset($_POST["company_name"], $_POST["ytunnus"], $_POST["company_address"], $_POST["company_postcode"], $_POST["company_area"])) {
            if ($stmt = $con->prepare("UPDATE company SET company_name=?, ytunnus=?, company_address=?, company_postcode=?, company_area=?, is_client=? WHERE id = ?")) {
                $stmt->bind_param("sssssii", $company_name, $ytunnus, $company_address, $company_postcode, $company_area, $is_client, $id);
                
                //Set parameters
                $company_name = trim($_POST["company_name"]);
                $ytunnus = trim($_POST["ytunnus"]);
                $company_address = trim($_POST["company_address"]);
                $company_postcode = trim($_POST["company_postcode"]);
                $company_area = trim($_POST["company_area"]);
                $is_client = trim($_POST["is_client"]);
                $id = trim($_POST["id"]);

                $stmt->execute();
                $companyObj->companyname = $company_name;
                echo json_encode($companyObj);
                $stmt->close();
            } else {
                $companyObj->error = "Tietokantayhteys epäonnistui.";
                echo json_encode($companyObj);
            }
        } else {
            $companyObj->error = "Tarkista, että kaikki kentät on täytetty";
            echo json_encode($companyObj);
        }
    } else {
        // Show employer only own bussiness so user cant create new accounts to company that he does not manage
        if ($_SESSION["class"] == "employer") {
            // Get current user company id
            if ($stmt = $con->prepare("SELECT user_company_id from users WHERE id = ?")) {
                $stmt->bind_param("i", $param_id);
                $param_id = trim($_SESSION["id"]);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $user_company_id = $result->fetch_array(MYSQLI_ASSOC);
                        $companyid = $user_company_id["user_company_id"];
                        // Get company data from id
                        if ($stmt2 = $con->prepare("SELECT * from company where id = ?")) {
                            $stmt2->bind_param("i", $companyid);
                            if ($stmt2->execute()) {
                                $result2 = $stmt2->get_result();
                                if ($result2->num_rows == 1) {
                                    while ($row = $result2->fetch_array(MYSQLI_ASSOC)) {
                                        array_push($return_arr,$row);
                                    }
                                    echo json_encode($return_arr);
                                }
                            }
                        }
                    }
                }
                $stmt->close();
            }
        } else {
            // Else user is admin so we can send all data
            $result = $con->query("SELECT * from company ORDER BY company_name");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    array_push($return_arr,$row);
                }
            
                echo json_encode($return_arr);
            }
        }
    }
    $con->close();
    
?>