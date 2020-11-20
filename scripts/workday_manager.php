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
    $workdayObj = new stdClass();
    // Get list of user's workdays from userid
    if (isset($_GET["userid"]) && !empty(trim($_GET["userid"])) && is_numeric(trim($_GET["userid"]))) {
        if ($_SESSION["class"] == "employer" || $_SESSION["class"] == "admin") {
            /*
            * No need to check here if employer is actually user employer, because when modifyworkday.php calls viewid method from this file
            * it handles the checking of does the user has rights to modify workday which he tries to modify.
            * Example: Employer can modify his employees workdays and hes own but not other users
            * Employee can only modify his own workdays and admin can do everything of course
            */
            $userid = trim($_GET["userid"]);
            if ($stmt = $con->prepare("SELECT *,
            DATE_FORMAT(start_time, '%e.%c.%y %H:%i') AS custom_start_time,
            DATE_FORMAT(end_time, '%e.%c.%y %H:%i') AS custom_end_time,
            DATE_FORMAT(created_time, '%e.%c.%y %H:%i') AS custom_created_time,
            DATE_FORMAT(modified_time, '%e.%c.%y %H:%i') AS custom_modified_time,
            DATE_FORMAT(break_time, '%H:%i') AS custom_break_time,
            DATE_FORMAT(date, '%d.%m.%Y') AS custom_dateformat
            from workday WHERE user_id = ? ORDER BY date")) {
                $stmt->bind_param("i", $param_id);
                $param_id = $userid;
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
        } else {
            $workdayObj->error = "Sinulla ei ole oikeuksia suorittaa kyseistä toimenpidettä.";
            echo json_encode($workdayObj);
        }
    } else if (isset($_GET["remove"]) && !empty(trim($_GET["remove"])) && is_numeric(trim($_GET["remove"]))) {
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
                $stmt->close();
            }
        } else if ($_SESSION["class"] == "employer") {
            //user is employer, TODO do check if employer is actual user employer and make possible to remove workdays
        } else if ($_SESSION["class"] == "admin") {
            if ($stmt = $con->prepare("DELETE FROM workday WHERE id = ?")) {
                $stmt->bind_param("i", $removeid);
                if($stmt->execute()){
                    header("location: ../workday.php?result=done");
                    exit();
                } else {
                    header("location: ../workday.php?result=unsuccesful");
                }
                $stmt->close();
            }
        }

    } else if (isset($_GET["viewid"]) && !empty(trim($_GET["viewid"])) && is_numeric(trim($_GET["viewid"]))) {
        $viewid = trim($_GET["viewid"]);
        if ($_SESSION["class"] == "employee") {
            // Check if workday belongs to user who is trying to modify it
            if ($stmt = $con->prepare("SELECT user_id FROM workday WHERE id = ?")) {
                $stmt->bind_param("i", $viewid);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $workdayuserid = $result->fetch_array(MYSQLI_ASSOC);
                        $workdayuseridINT = $workdayuserid["user_id"];

                        // See if user ids match and echo workday data
                        if ($_SESSION["id"] == $workdayuseridINT) {
                            // Generating specific DATE_FORMAT straigt from mysql for HTML datetime-local.value format
                            if ($stmt2 = $con->prepare("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS custom_start_time, 
                            DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS custom_end_time
                            FROM workday WHERE id = ?")) {
                                $stmt2->bind_param("i", $viewid);
                                if ($stmt2->execute()) {
                                    $result2 = $stmt2->get_result();
                                    if ($result2->num_rows == 1) {
                                        while ($row = $result2->fetch_array(MYSQLI_ASSOC)) {
                                            array_push($return_arr,$row);
                                        }
                                        echo json_encode($return_arr);
                                    }
                                }
                                $stmt2->close();
                            } else {
                                // Unsuccesful mysqli
                            }
                        } else {
                            // User tring to modify/view workday which does not belong to him
                            // Eg. manually editing viewid GET attribute from link, so malicious activity
                            // TODO: Log this event
                        }
                    } else {
                        // workday not found
                    }
                }
                $stmt->close();
            }
        } else if ($_SESSION["class"] == "employer") {
            // Get employers' company id
            if ($stmt = $con->prepare("SELECT user_company_id from users WHERE id = ?")) {
                $stmt->bind_param("i", $param_id);
                $param_id = trim($_SESSION["id"]);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        $user_company_id = $result->fetch_array(MYSQLI_ASSOC);
                        $employer_companyid = $user_company_id["user_company_id"];
                        // Get user_id from workday which we are tring to modify to see if we have rights to do it
                        if ($stmt2 = $con->prepare("SELECT user_id from workday where id = ?")) {
                            $stmt2->bind_param("i", $viewid);
                            if ($stmt2->execute()) {
                                $result2 = $stmt2->get_result();
                                if ($result2->num_rows == 1) {
                                    $workdaydata_array = $result2->fetch_array(MYSQLI_ASSOC);
                                    $workday_owner_userid = $workdaydata_array["user_id"];
                                    // Get user company id from users table to compare result
                                    if ($stmt3 = $con->prepare("SELECT user_company_id from users where id = ?")) {
                                        $stmt3->bind_param("i", $workday_owner_userid);
                                        if ($stmt3->execute()) {
                                            $result3 = $stmt3->get_result();
                                            if ($result3->num_rows == 1) {
                                                $userdata_array = $result3->fetch_array(MYSQLI_ASSOC);
                                                if ($userdata_array["user_company_id"] == $employer_companyid) {
                                                    // They have same company id
                                                    if ($stmt4 = $con->prepare("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS custom_start_time, DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS custom_end_time FROM workday WHERE id = ?")) {
                                                        $stmt4->bind_param("i", $viewid);
                                                        if($stmt4->execute()) {
                                                            $result4 = $stmt4->get_result();
                                                            if ($result4->num_rows == 1) {
                                                                while ($row = $result4->fetch_array(MYSQLI_ASSOC)) {
                                                                    array_push($return_arr,$row);
                                                                }
                                                                echo json_encode($return_arr);
                                                            } else {
                                                                // No workday found
                                                            }
                                                        }
                                                        $stmt4->close();
                                                    }
                                                } else {
                                                    $workdayObj->error = "Sinulla ei ole oikeuksia muokata kyseistä työpäivää.";
                                                    echo json_encode($workdayObj);
                                                }
                                            }
                                        }
                                        $stmt3->close();
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
        } else if ($_SESSION["class"] == "admin") {
            // Generating specific DATE_FORMAT from mysql for HTML datetime-local.value format
            if ($stmt = $con->prepare("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS custom_start_time, DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS custom_end_time FROM workday WHERE id = ?")) {
                $stmt->bind_param("i", $viewid);
                if($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                            array_push($return_arr,$row);
                        }
                        echo json_encode($return_arr);
                    } else {
                        // No workday found
                    }
                } 
            $stmt->close();
            exit();
            }
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //TODO: DO CHECK IF USER IS ALLOWED TO MODIFY WORKDAY
        if (isset($_POST["date"], $_POST["starttime"], $_POST["endtime"])) {
            if ($stmt = $con->prepare("UPDATE workday SET date=?, start_time=?, end_time=?, break_time=?, total_time=?, explanation=?, modified_user_id=? WHERE id = ?")) {
                $stmt->bind_param("ssssssii", $date_param, $starttime_param, $endtime_param, $breaktime_param, $totaltime_param, $explanation_param, $modified_uid_param, $id);
                
                //Set parameters
                $starttime = trim($_POST["starttime"]);
                $starttimedt = new DateTime($starttime);
                $endtime = trim($_POST["endtime"]);
                $endtimedt = new DateTime($endtime);
                $breaktime = trim($_POST["breaktime"]);
                // Get break time on correct format for DateInterval
                list($h, $m) = sscanf($breaktime, "%d:%d");
                $breaktimedt = new DateInterval(sprintf("PT%dH%dM", $h, $m));

                // https://stackoverflow.com/questions/3108591/calculate-number-of-hours-between-2-dates-in-php
                // Calculating total time in hh:mm format
                $endtimedt->sub($breaktimedt);
                $totaltimeinterval = $endtimedt->diff($starttimedt);
                $hours = $totaltimeinterval->h;
                $hours = $hours + ($totaltimeinterval->days*24);
                $total_time = $hours . ":" . $totaltimeinterval->format("%I:%S");
                // Add breaktimedt back to endtime parameter before inserting to mysql
                $endtimedt->add($breaktimedt);

                // Set up params
                $date_param = trim($_POST["date"]);
                $starttime_param = $starttimedt->format("Y-m-d H:i:s");
                $endtime_param = $endtimedt->format("Y-m-d H:i:s");
                $breaktime_param = $breaktime;
                $totaltime_param = $total_time;
                $explanation_param = trim($_POST["explanation"]);
                $modified_uid_param = trim($_SESSION["id"]);
                $id = trim($_POST["id"]);
                
                $stmt->execute();
                $workdayObj->workday = $date_param;
                echo json_encode($workdayObj);
                $stmt->close();
            } else {
                $workdayObj->error = "Tietokantayhteys epäonnistui.";
                echo json_encode($workdayObj);
            }
        } else {
            $workdayObj->error = "Tarkista, että kaikki kentät on täytetty";
            echo json_encode($workdayObj);
        }
    } else {
        //Send all workday data, todo move this to user id get and put here admin print every workday
        if ($stmt = $con->prepare("SELECT *,
        DATE_FORMAT(start_time, '%e.%c.%y %H:%i') AS custom_start_time,
        DATE_FORMAT(end_time, '%e.%c.%y %H:%i') AS custom_end_time,
        DATE_FORMAT(break_time, '%H:%i') AS custom_break_time,
        DATE_FORMAT(date, '%d.%m.%Y') AS custom_dateformat
        from workday WHERE user_id = ? ORDER BY date")) {
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
    $con->close();

?>