<?php
    // Initialize the session
    session_start();
    
    // Check if the user has rights to see the page, if not redirect user back to the login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] != "admin"){
        header("location: login.php");
        exit;
    }
?>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "config.php";
        $companyObj = new stdClass();
        if (!isset($_POST["company_name"], $_POST["ytunnus"], $_POST["company_address"], $_POST["company_postcode"], $_POST["company_area"])) {
            $companyObj->error = "Tarkista, että kaikki kentät on täytetty";
            echo json_encode($companyObj);
        }
        if ($stmt = $con->prepare('SELECT id, company_name FROM company WHERE company_name = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the company_name is a string so we use "s"
            $stmt->bind_param('s', $param_companyname);
            $param_companyname = trim($_POST["company_name"]);
            $stmt->execute();
            // Store the result so we can check if the company exists in the database.
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $companyObj->error = "Kyseinen yritys on jo luotu!";
                echo json_encode($companyObj);
                $stmt->close();
                $con->close();
            } else {
                $stmt->reset();
                if ($stmt = $con->prepare("INSERT INTO company (company_name, ytunnus, company_address, company_postcode, company_area, created_user_id, is_client) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
                    $stmt->bind_param("ssssssi", $company_name, $ytunnus, $company_address, $company_postcode, $company_area, $created_user_id, $is_client);
                    
                    //Set parameters
                    $company_name = $_POST["company_name"];
                    $ytunnus = $_POST["ytunnus"];
                    $company_address = $_POST["company_address"];
                    $company_postcode = $_POST["company_postcode"];
                    $company_area = $_POST["company_area"];
                    $created_user_id = $_SESSION["id"];
                    $is_client = $_POST["is_client"];
                    
                    $companyObj->companyname = $company_name;
                    echo json_encode($companyObj);

                    $stmt->execute();
                    $stmt->close();
                    $con->close();
                } else {
                    $companyObj->error = "Tietokantayhteys epäonnistui, ota yhteys asiakaspalveluun.";
                    echo json_encode($companyObj);
                }
            }
        }
    }
?>