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

if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config.php";
    $workdayObj = new stdClass();

    if (!isset($_POST["date"], $_POST["starttime"], $_POST["endtime"])) {
        $workdayObj->error = "Tarkista että kaikki kentät on täytetty";
        echo json_encode($workdayObj);
    }
    
    if ($stmt = $con->prepare("INSERT INTO workday (user_id, date, start_time, end_time, break_time, total_time, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
        $stmt->bind_param("issssss", $user_id_param, $date_param, $starttime_param, $endtime_param, $breaktime_param, $totaltime_param, $explanation_param);
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
        $user_id_param = $_SESSION["id"];
        $date_param = trim($_POST["date"]);
        $starttime_param = $starttimedt->format("Y-m-d H:i:s");
        $endtime_param = $endtimedt->format("Y-m-d H:i:s");
        $breaktime_param = $breaktime;
        $totaltime_param = $total_time;
        $explanation_param = trim($_POST["explanation"]);

        // Make sure that endtime is greater than start time before executing query
        if ($endtimedt > $starttimedt) {
            $stmt->execute();
        } else {
            $workdayObj->error = "Lopetusaika ei voi olla aikaisemmin kuin aloitusaika.";
            echo json_encode($workdayObj);
            $stmt->close();
            $con->close();
            die();
        }
        $workdayObj->workday = $total_time;
        echo json_encode($workdayObj);
    }
    
}

?>