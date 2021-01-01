<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    header('Content-Type: application/json');
?>

<?php
/**
 * Json api file
 * 
 * Get actions:
 *  mode:
 *      user            DONE
 *      workday         DONE
 *      company         DONE
 *  action
 *      view            DONE USER COMPANY WORKDAY
 *          id:         DONE USER COMPANY WORKDAY
 *              id      DONE USER COMPANY WORKDAY
 *              all     DONE USER COMPANY WORKDAY
 *      remove          DONE USER COMPANY
 *          id:         DONE USER COMPANY
 *              id      DONE USER COMPANY
 * 
 * POST ACTIONS:
 *  update:
 *      updateuser  DONE
 *  create:
 *      createuser  DONE
 */
include_once __DIR__ . "/../collection/userCollection.php";
include_once __DIR__ . "/../collection/companyCollection.php";
include_once __DIR__ . "/../collection/workdayCollection.php";

if (isset($_GET["mode"]) && !empty(trim($_GET["mode"]))) {
    if ($_GET["mode"] == "user") {
        switch ($_GET["action"]) {
            case "view":
                switch ($_GET["id"]) {
                    case "all":
                        echo getAllUsersAsJson();
                        break;
                    default:
                        echo getSingleUserAsJson($_GET["id"]);
                }
                break;
            case "remove":
                switch ($_GET["id"]) {
                    default:
                        echo removeUser($_GET["id"]);
                }
                break;
        }
    } else if ($_GET["mode"] == "company") {
        switch ($_GET["action"]) {
            case "view":
                switch ($_GET["id"]) {
                    case "all":
                        echo getAllCompaniesAsJson();
                        break;
                    default:
                        echo getSingleCompanyAsJson($_GET["id"]);
                }
                break;
            case "viewusers":
                switch ($_GET["id"]) {
                    default:
                        echo getCompanyUsers($_GET["id"]);
                }
                break;
            case "remove":
                switch ($_GET["id"]) {
                    default:
                        echo removeCompany($_GET["id"]);
                }
                break;
        }
    } else if ($_GET["mode"] == "workday") {
        switch ($_GET["action"]) {
            case "view":
                switch ($_GET["id"]) {
                    case "all":
                        if (empty($_GET["userid"])) {
                            echo getAllWorkdaysAsJson();
                            break;
                        } else {
                            echo getAllUserWorkdaysAsJson($_GET["userid"]);
                            break;
                        }
                    case "between":
                        echo getWorkdaysBetweenDates($_GET["start"], $_GET["end"]);         // MIETI MITEN VOISI JÄRJESTELLÄ PAREMMIN GET PARAMETRIT
                        break;
                    default:
                        echo getSingleWorkdayAsJson($_GET["id"]);
                }
                break;
            case "remove":
                echo removeWorkday($_GET["id"]);
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_POST["postfrom"]) {
        case "createuser":
            echo createUser();
            break;
        case "updateuser":
            echo updateUser();
            break;
        case "createcompany":
            echo createCompany();
            break;
        case "updatecompany":
            echo updateCompany();
            break;
        case "createworkday":
            echo createWorkday();
            break;
        case "updateworkday":
            echo updateWorkday();
            break;
    }
}

/**
 * Returns all workdays from session user id
 */
function getAllWorkdaysAsJson() {
    $collection = workdayCollection::getAllWorkdaysFromSessionID();
    return $collection->getJson();
}

// TODO permission check
function getAllUserWorkdaysAsJson($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }

    $collection = workdayCollection::getAllWorkdaysFromUserID(trim($id));
    return $collection->getJson();
}

/**
 * $start and $end should be in mysql date format YYYY-MM-DD
 */

function getWorkdaysBetweenDates($start, $end) {
    if (empty($start) || empty($end)) {
        return json_encode(array("error" => "Alku- tai lopetuspvm on tyhjä."), JSON_UNESCAPED_UNICODE);
    }

    $collection = workdayCollection::getWorkdaysBetweenDates($_SESSION["id"],$start, $end);
    return $collection->getJson();
}

/**
 * Allow employee only get workdays which belongs to him
 * Allow employer only get workdays which belongs to him or his employees
 */

function getSingleWorkdayAsJson($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }
    $workdayObject = Workday::withID(trim($id));
    if (!empty($workdayObject->error)) {
        return json_encode(array("error" => "Syötetyllä ID:llä ei löydy työpäivää."), JSON_UNESCAPED_UNICODE);
    }
    if ($_SESSION["class"] == "employee") {
        if ($workdayObject->user_id == $_SESSION["id"]) {
            return json_encode($workdayObject);
        } else {
            // Log this
            return json_encode(array("error" => "Et voi muokata kyseistä työpäivää."), JSON_UNESCAPED_UNICODE);
        }
    } else if ($_SESSION["class"] == "employer") {
        $workdayObjectOwner = User::withID($workdayObject->user_id);
        $employerUserObject = User::withID($_SESSION["id"]);
        if ($workdayObjectOwner->user_company_id == $employerUserObject->user_company_id) {
            return json_encode($workdayObject);
        } else {
            // Log this
            return json_encode(array("error" => "Työpäivä ei kuulu sinun työntekijälle."), JSON_UNESCAPED_UNICODE);
        }
    } else if ($_SESSION["class"] == "admin") {
        return json_encode($workdayObject);
    }
}

/**
 * If user has employer class then return only company that is owned by him
 */
function getAllCompaniesAsJson() {
    if ($_SESSION["class"] == "employer") {
        $employerUserObject = User::withID($_SESSION["id"]);
        $companyObject = Company::withID($employerUserObject->user_company_id);
        return json_encode($companyObject, JSON_UNESCAPED_UNICODE);
    } else if ($_SESSION["class"] == "admin") {
        $collection = CompanyCollection::getAllCompanies();
        return $collection->getJson();
    }
}

/**
 * Only admin can get data from id
 */
function getSingleCompanyAsJson($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }
    if ($_SESSION["class"] == "admin") {
        $companyObject = Company::withID(trim($id));
        return json_encode($companyObject, JSON_UNESCAPED_UNICODE);
    }
}
/**
 * Only admin can get data from id
 * Return count of employees in company, used in modify company page
 */
function getCompanyUsers($companyid) {
    if ($_SESSION["class"] == "admin") {
        $collection = userCollection::getAllCompanyUsers($companyid);
        return json_encode(array("count" => count($collection)), JSON_UNESCAPED_UNICODE);
    }
}

/**
 * If user has employer class then return only employees that are under his company
 */
function getAllUsersAsJson() {
    if ($_SESSION["class"] == "employer") {
        $employerUserObject = User::withID($_SESSION["id"]);
        $collection = userCollection::getAllCompanyUsers($employerUserObject->user_company_id);
        return $collection->getJson();
    } else if ($_SESSION["class"] == "admin") {
        $collection = userCollection::getAllUsers();
        return $collection->getJson();
    }
}
/**
 * Allow employer only see users that have same company id as he do
 */
function getSingleUserAsJson($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }
    $userObject = User::withID(trim($id));
    if ($_SESSION["class"] == "employer") {
        $employerUserObject = User::withID($_SESSION["id"]);
        if (empty($employerUserObject->error) && empty($userObject->error)) {
            if ($employerUserObject->user_company_id == $userObject->user_company_id) {
                return json_encode($userObject, JSON_UNESCAPED_UNICODE);
            } else {
                // LOG THIS
                return json_encode(array("error" => "Sinulla ei ole oikeuksia katsoa kyseisen käyttäjän tietoja."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array("error" => "Syötetyllä ID:llä ei löydy käyttäjää."), JSON_UNESCAPED_UNICODE);
        }
    } else if ($_SESSION["class"] == "admin") {
        return json_encode($userObject, JSON_UNESCAPED_UNICODE);
    }
}

function createUser() {
    if ($_SESSION["class"] == "employee") {
        // LOG THIS
        return json_encode(array("error" => "Sinulla ei ole oikeuksia luoda uusia käyttäjiä."), JSON_UNESCAPED_UNICODE);
    }
    if (!isset($_POST["username"], $_POST["password"], $_POST["firstname"], $_POST["lastname"], $_POST["class"], $_POST["user_company_id"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }
    // Check if desired username is already in use
    $checkUserObject = User::withUsername(trim($_POST["username"]));
    if (empty($checkUserObject->error)) {
        return json_encode(array("error" => "Kyseinen käyttäjätunnus on jo käytössä."), JSON_UNESCAPED_UNICODE);
    }

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $class = trim($_POST["class"]);
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $user_company_id = trim($_POST["user_company_id"]);

    $newUserObject = new User(null,$username,$class,$firstname,$lastname,$user_company_id,null,$password);
    if ($newUserObject->createInstancetoDB()) {
        return json_encode($newUserObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Uuden käyttäjän luonti epäonnistui."), JSON_UNESCAPED_UNICODE);
    }
}

function updateUser() {
    if (!isset($_POST["username"], $_POST["firstname"], $_POST["lastname"], $_POST["class"], $_POST["user_company_id"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }

    $modifyUserObject = User::withID(trim($_POST["id"]));
    if (!empty($modifyUserObject->error)) {
        return json_encode($modifyUserObject, JSON_UNESCAPED_UNICODE);
    }

    $modifyUserObject->username = trim($_POST["username"]);
    if (!empty($_POST["password"])) {
        $modifyUserObject->setPassword($password);
    }
    $modifyUserObject->class = trim($_POST["class"]);
    $modifyUserObject->firstname = trim($_POST["firstname"]);
    $modifyUserObject->lastname = trim($_POST["lastname"]);
    $modifyUserObject->user_company_id = trim($_POST["user_company_id"]);

    if ($modifyUserObject->updateInstanceToDB()) {
        return json_encode($modifyUserObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Käyttäjän päivitys epäonnistui."), JSON_UNESCAPED_UNICODE);
    }

}

/**
 * If user has employer class then make check to determine if user/employee has same company id
 * So employer cannot remove other companies users with modifying the get url
 */
function removeUser($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }
    if ($_SESSION["class"] == "employee") {
        // LOG THIS
        return json_encode(array("error" => "Sinulla ei ole oikeuksia poistaa käyttäjiä."), JSON_UNESCAPED_UNICODE);
    }

    $userWhoIsBeingRemovedObject = User::withID(trim($id));

    if (!empty($userWhoIsBeingRemovedObject->error)) {
        return json_encode(array("error" => "Syötetyllä ID:llä ei löydy käyttäjää."), JSON_UNESCAPED_UNICODE);
    }

    if ($_SESSION["class"] == "employer") {
        $employerUserObject = User::withID($_SESSION["id"]);
        // Check if user object has loaded correctly
        if ($employerUserObject->user_company_id == $userWhoIsBeingRemovedObject->user_company_id) {
            if ($userWhoIsBeingRemovedObject->removeInstance()) {
                return json_encode(array("message" => "Käyttäjä poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            // LOG THIS
            return json_encode(array("error" => "Sinulla ei ole oikeuksia poistaa kyseistä käyttäjää."), JSON_UNESCAPED_UNICODE);
        }
    } else if ($_SESSION["class"] == "admin") {
        if ($userWhoIsBeingRemovedObject->removeInstance()) {
            echo json_encode(array("message" => "Käyttäjä poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
        }
    }
}

/**
 * Only admins can interact with company modify/removal/creation
 */

function createCompany() {
    if ($_SESSION["class"] == "employee" || $_SESSION["class"] == "employer") {
        // LOG THIS
        return json_encode(array("error" => "Sinulla ei ole oikeuksia luoda yritystä."), JSON_UNESCAPED_UNICODE);
    }
    if (!isset($_POST["company_name"], $_POST["ytunnus"], $_POST["company_address"], $_POST["company_postcode"], $_POST["company_area"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }

    // Check that same company does not exist already
    $checkCompanyObject = Company::withName(trim($_POST["company_name"]));
    if (empty($checkCompanyObject->error)) {
        return json_encode(array("error" => "Yritys on jo olemassa!"), JSON_UNESCAPED_UNICODE);
    }

    $company_name = trim($_POST["company_name"]);
    $ytunnus = trim($_POST["ytunnus"]);
    $company_address = trim($_POST["company_address"]);
    $company_postcode = trim($_POST["company_postcode"]);
    $company_area = trim($_POST["company_area"]);
    $created_user_id = trim($_SESSION["id"]);
    $is_client = trim($_POST["is_client"]);

    $newCompanyObject = new Company(null, $company_name, $ytunnus, $company_address, $company_postcode, $company_area, $created_user_id, $is_client);

    if ($newCompanyObject->createInstancetoDB()) {
        return json_encode($newCompanyObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Uuden yrityksen luonti epäonnistui."), JSON_UNESCAPED_UNICODE);
    }

}

function updateCompany() {
    if ($_SESSION["class"] == "employee" || $_SESSION["class"] == "employer") {
        // LOG THIS
        return json_encode(array("error" => "Sinulla ei ole oikeuksia päivittää yritystä."), JSON_UNESCAPED_UNICODE);
    }
    if (!isset($_POST["company_name"], $_POST["ytunnus"], $_POST["company_address"], $_POST["company_postcode"], $_POST["company_area"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }

    $modifyCompanyObject = Company::withID(trim($_POST["id"]));
    if (!empty($modifyCompanyObject->error)) {
        return json_encode($modifyCompanyObject, JSON_UNESCAPED_UNICODE);
    }

    $modifyCompanyObject->name = trim($_POST["company_name"]);
    $modifyCompanyObject->ytunnus = trim($_POST["ytunnus"]);
    $modifyCompanyObject->address = trim($_POST["company_address"]);
    $modifyCompanyObject->postcode = trim($_POST["company_postcode"]);
    $modifyCompanyObject->area = trim($_POST["company_area"]);
    $modifyCompanyObject->is_client = trim($_POST["is_client"]);

    if ($modifyCompanyObject->updateInstanceToDB()) {
        return json_encode($modifyCompanyObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Yrityksen päivitys epäonnistui."), JSON_UNESCAPED_UNICODE);
    }
}

function removeCompany($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }
    if ($_SESSION["class"] == "employee" || $_SESSION["class"] == "employer") {
        // LOG THIS
        return json_encode(array("error" => "Sinulla ei ole oikeuksia poistaa yritystä."), JSON_UNESCAPED_UNICODE);
    }
    $companyObject = Company::withID(trim($id));
    if (empty($companyObject->error)) {
        if ($companyObject->removeInstance()) {
            echo json_encode(array("message" => "Yritys poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode($companyObject, JSON_UNESCAPED_UNICODE);
    }
}

function createWorkday() {
    if (!isset($_POST["date"], $_POST["starttime"], $_POST["endtime"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }
    $starttimedt = new DateTime(trim($_POST["starttime"]));
    $endtimedt = new DateTime(trim($_POST["endtime"]));
    // Get break time on correct format for DateInterval
    list($h, $m) = sscanf(trim($_POST["breaktime"]), "%d:%d");
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

    $newWorkdayObject = new Workday();
    $newWorkdayObject->user_id = $_SESSION["id"];
    $newWorkdayObject->date = trim($_POST["date"]);
    $newWorkdayObject->start_time = $starttimedt->format("Y-m-d H:i:s");
    $newWorkdayObject->end_time = $endtimedt->format("Y-m-d H:i:s");
    $newWorkdayObject->html_break = trim($_POST["breaktime"]);
    $newWorkdayObject->total_time = $total_time;
    $newWorkdayObject->explanation = trim($_POST["explanation"]);

    if ($newWorkdayObject->createInstancetoDB()) {
        return json_encode($newWorkdayObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Uuden työpäivän luonti epäonnistui."), JSON_UNESCAPED_UNICODE);
    }

}

function updateWorkday() {
    if (!isset($_POST["date"], $_POST["starttime"], $_POST["endtime"])) {
        return json_encode(array("error" => "Tarkista, että kaikki kentät on täytetty."), JSON_UNESCAPED_UNICODE);
    }

    $modifyWorkdayObject = Workday::withID(trim($_POST["id"]));
    if (!empty($modifyWorkdayObject->error)) {
        return json_encode($modifyWorkdayObject, JSON_UNESCAPED_UNICODE);
    }

    $modifyWorkdayObject->date = trim($_POST["date"]);
    $modifyWorkdayObject->SetTimesFromPost($_POST["starttime"],$_POST["endtime"],$_POST["breaktime"]);
    $modifyWorkdayObject->explanation = trim($_POST["explanation"]);
    $modifyWorkdayObject->modified_user_id = trim($_SESSION["id"]);

    if ($modifyWorkdayObject->updateInstanceToDB()) {
        return json_encode($modifyWorkdayObject, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array("error" => "Työpäivän päivitys epäonnistui."), JSON_UNESCAPED_UNICODE);
    }
}

function removeWorkday($id) {
    if (!is_numeric($id)) {
        return json_encode(array("error" => "Syötetty ID ei ole numero."), JSON_UNESCAPED_UNICODE);
    }

    $workdayObject = Workday::withID(trim($id));

    if (!empty($workdayObject->error)) {
        return json_encode(array("error" => "Syötetyllä ID:llä ei löydy työpäivää."), JSON_UNESCAPED_UNICODE);
    }

    // Check does workday belong to user who is trying to remove it
    if ($_SESSION["class"] == "employee") {
        if ($workdayObject->user_id == $_SESSION["id"]) {
            if ($workdayObject->removeInstance()) {
                return json_encode(array("message" => "Työpäivä poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
            }
        }
    } else if ($_SESSION["class"] == "employer") {
        $workdayObjectOwner = User::withID($workdayObject->user_id);
        $employerUserObject = User::withID($_SESSION["id"]);

        if ($workdayObjectOwner->user_company_id == $employerUserObject->user_company_id) {
            if ($workdayObject->removeInstance()) {
                return json_encode(array("message" => "Työpäivä poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(array("error" => "Työpäivän poisto epäonnistui."), JSON_UNESCAPED_UNICODE);
            }
        } else {
            // LOG THIS
            return json_encode(array("error" => "Sinulla ei ole oikeuksia poistaa kyseistä työpäivää."), JSON_UNESCAPED_UNICODE);
        }
    } else if ($_SESSION["class"] == "admin") {
        if ($workdayObject->removeInstance()) {
            return json_encode(array("message" => "Työpäivä poistettu onnistuneesti."), JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array("error" => "Työpäivän poisto epäonnistui."), JSON_UNESCAPED_UNICODE);
        }
    }
}
?>