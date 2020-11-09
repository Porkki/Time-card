<?php
// Initialize the session
session_start();
$error = "";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include config file
    require_once "config.php";

    if (!isset($_POST["username"], $_POST["password"])) {
        exit("Tarkista käyttäjätunnus ja/tai salasana.");
    }

    if ($stmt = $con->prepare('SELECT id, username, password, firstname, lastname, class FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $password, $firstname, $lastname, $class);
            $stmt->fetch();
            // Account exists, now we verify the password.
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
            // Incorrect username
            $error = 'Tarkista käyttäjätunnus ja/tai salasana.';
        }

        $stmt->close();
    }
}
?>

<html>
<head>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="content animate">
    <div class="imgcontainer">
        <img src="img/logo.png" class="logo">
        <label><h1>Työajanseuranta</h1></label>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="uname"><b>Käyttäjätunnus</b></label>
        <input type="text" placeholder="Syötä käyttäjätunnus" name="username" required>

        <label for="psw"><b>Salasana</b></label>
        <input type="password" placeholder="Syötä salasana" name="password" required>
        <button type="submit">Kirjaudu</button>
    </form>
    <p style="color:red">
    <?php
        if (!empty($error)) {
            echo $error;
        }
    ?>
    </p>
</div>

</body>
</html>