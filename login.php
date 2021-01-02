<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

include_once __DIR__ . "./models/user.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["username"], $_POST["password"])) {
        return json_encode(array("error" => "Tarkista käyttäjätunnus ja/tai salasana."), JSON_UNESCAPED_UNICODE);
    }

    $loginUserObject = User::withUsernameAndPassword(trim($_POST["username"]),trim($_POST["password"]));
    if (empty($loginUserObject->error)) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['id'] = $loginUserObject->id;
        $_SESSION["firstname"] = $loginUserObject->firstname;
        $_SESSION["lastname"] = $loginUserObject->lastname;
        $_SESSION["class"] = $loginUserObject->class;   
    }
    echo json_encode($loginUserObject);
}

/*
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include config file
    require_once "config.php";

    if (!isset($_POST["username"], $_POST["password"])) {
        exit("Tarkista käyttäjätunnus ja/tai salasana.");
    }

    if ($stmt = $con->prepare('SELECT id, username, password, firstname, lastname, class, user_company_id FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $username_param);
        $username_param = trim($_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $password, $firstname, $lastname, $class, $user_company_id);
            $stmt->fetch();

            // Verify that company user belongs to is active client
            if ($stmt1 = $con->prepare("SELECT is_client from company WHERE id = ?")) {
                $stmt1->bind_param("i", $param_id);
                $param_id = $user_company_id;
    
                if ($stmt1->execute()) {
                    $result = $stmt1->get_result();
                    if ($result->num_rows == 1) {
                        $is_client_array = $result->fetch_array(MYSQLI_ASSOC);
                        $is_client = $is_client_array["is_client"];
                        if ($is_client == 1) {
                            // Account exists and company it belongs to is active client, now we verify the password.
                            // Note: remember to use password_hash in your registration file to store the hashed passwords.
                            if (password_verify($_POST['password'], $password)) {
                                // Verification success! User has loggedin!
                                // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
                                session_regenerate_id();
                                $_SESSION['loggedin'] = TRUE;
                                $_SESSION['username'] = $_POST['username'];
                                $_SESSION['id'] = $id;
                                $_SESSION["firstname"] = $firstname;
                                $_SESSION["lastname"] = $lastname;
                                $_SESSION["class"] = $class;
                                // Redirect user to welcome page
                                header("location: welcome.php");
                            } else {
                                // Incorrect password
                                $error = 'Tarkista käyttäjätunnus ja/tai salasana.';
                            }
                        } else {
                            $error = 'Yritys ei ole aktiivinen';
                        }
                    } else {
                        $error = 'Tietokanta ongelma';
                    }
                }
                $stmt1->close();
            }

        } else {
            // Incorrect username
            $error = 'Tarkista käyttäjätunnus ja/tai salasana.';
        }

        $stmt->close();
    }
}
*/
?>