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
if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./config.php";
    $userObj = new stdClass();
    if (!isset($_POST["username"], $_POST["password"], $_POST["firstname"], $_POST["lastname"], $_POST["class"])) {
        $userObj->error = "Tarkista että kaikki kentät on täytetty";
        echo json_encode($userObj);
    }
    if ($stmt = $con->prepare('SELECT id, username, password, firstname, lastname, class FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $userObj->error = "Kyseinen käyttäjätunnus on jo käytössä";
            echo json_encode($userObj);
            $stmt->close();
            $con->close();
        } else {
            $stmt->reset();
            if ($stmt = $con->prepare("INSERT INTO users (username, password, class, firstname, lastname, user_company_id) VALUES (?, ?, ?, ?, ?, ?)")) {
                $stmt->bind_param("sssssi", $username, $password, $class, $firstname, $lastname, $user_company_id);

                // Set parameters
                $username = trim($_POST["username"]);
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $class = trim($_POST["class"]);
                $firstname = trim($_POST["firstname"]);
                $lastname = trim($_POST["lastname"]);
                $user_company_id = trim($_POST["user_company_id"]);

                $stmt->execute();
                $stmt->close();
                $con->close();

                //Json for modal popup
                $userObj->username = $username;
                echo json_encode($userObj);

                //header("location: adduser.php");
            } else {
                //Pieleen meni ku jeesuksen pääsiäinen
            }
            }
        }
    }
?>