<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>

<?php

    require_once "../config.php";
    // Declaring empty variables
    $return_arr = Array();

    if (isset($_GET["remove"]) && !empty(trim($_GET["remove"])) && is_numeric(trim($_GET["remove"]))) {
        $removeid = trim($_GET["remove"]);
        if ($_SESSION["class"] == "employee") {
            // Check if workday belongs to user who is trying to remove it
            if ($stmt = $con->prepare("SELECT user_id FROM workday WHERE id = ?")) {
                $stmt->bind_param("i", $removeid);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $workdayuserid = $result->fetch_array(MYSQLI_ASSOC);
                        $workdayuseridINT = $workdayuserid["user_id"];

                        // See if user ids match and remove workday
                        if ($_SESSION["id"] == $workdayuseridINT) {
                            if ($con->query("DELETE FROM workday WHERE id = " . $removeid)) {
                                header("location: ../workday.php?result=done");
                            } else {
                                // Unsuccesful mysqli
                                header("location: ../workday.php?result=unsuccesful");
                            }
                        } else {
                            // User tring to remove workday which does not belong to him
                            // Eg. manually editing remove GET attribute from link, so malicious activity
                            // TODO: Log this event
                            header("location: ../workday.php?result=unsuccesful");
                        }
                    } else {
                        // workday not found
                        header("location: ../workday.php?result=unsuccesful");
                    }
                }
            }
        } else if ($_SESSION["class"] == "employer") {
            //user is employer
        } else if ($_SESSION["class"] == "admin") {
            if ($stmt = $con->prepare("DELETE FROM workday WHERE id = ?")) {
                $stmt->bind_param("i", $removeid);
                if($stmt->execute()){
                    header("location: ../workday.php?result=done");
                    exit();
                } else {
                    header("location: ../workday.php?result=unsuccesful");
                }
            }
        }

    } else {
        //Send all workday data, todo move this to id get and put here admin print every workday
        if ($stmt = $con->prepare("SELECT * from workday WHERE user_id = ? ORDER BY date")) {
            $stmt->bind_param("i", $param_id);
            $param_id = trim($_SESSION["id"]);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($return_arr,$row);
                    }
                    echo json_encode($return_arr);
                }
            }
            $stmt->close();
        }
    }

?>