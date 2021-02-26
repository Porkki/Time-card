<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

include_once __DIR__ . "/../../models/user.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["oldpassword"], $_POST["newpassword"], $_POST["retypepassword"])) {
        echo json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
        exit;
    }

    $userObject = User::withIDLoadPassword($_SESSION["id"]);

    if (!$userObject->checkPassword(trim($_POST["oldpassword"]))) {
        echo json_encode(array("error" => "Vanha salasana ei ole oikein."), JSON_UNESCAPED_UNICODE);
        exit;
    }

    $newpw = trim($_POST["newpassword"]);
    $retypepw = trim($_POST["retypepassword"]);

    if ($newpw == $retypepw) {
        if ($userObject->setPassword($newpw)) {
            if ($userObject->updateInstanceToDB()) {
                echo json_encode(array("message" => "Salasana päivitetty"), JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode(array("error" => "Salasanan täytyy olla yli 8 kirjainta pitkä."), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(array("error" => "Uudet salasanan ei täsmää."), JSON_UNESCAPED_UNICODE);
    }

}
?>