<?php
    include_once __DIR__ . "/../models/user.php";
    class userCollection implements Countable {
        private $users = array();

        public static function getAllUsers() {
            $instance = new static();
            $instance->getUsers();
            return $instance;
        }

        public static function getAllCompanyUsers($company_id) {
            $instance = new static();
            $instance->getCompanyUsers($company_id);
            return $instance;
        }

        public function getJson() {
            return json_encode($this->users, JSON_UNESCAPED_UNICODE);
        }

        public function count($mode = 'COUNT_NORMAL') {
            return count($this->users);
        }  

        protected function getUsers() {
            $db = new db();
            $userquery = $db->query("SELECT u.id,u.username,u.class,u.firstname,u.lastname,u.user_company_id,c.company_name from users u 
                                    LEFT JOIN company c on u.user_company_id = c.id GROUP BY u.id")->fetchAll();
            foreach($userquery as $user) {
                $userObj = new User($user["id"],$user["username"],$user["class"],$user["firstname"],$user["lastname"],$user["company_name"]);
                $this->addItem($userObj);
            }
            $db->close();
        }
        protected function getCompanyUsers($company_id) {
            $db = new db();
            $userquery = $db->query("SELECT * from users WHERE user_company_id=?", $company_id)->fetchAll();
            foreach($userquery as $user) {
                $userObj = new User($user["id"],$user["username"],$user["class"],$user["firstname"],$user["lastname"],$user["user_company_id"]);
                $this->addItem($userObj);
            }
            $db->close();
        }

        protected function addItem($obj) {
            $this->users[] = $obj;
        }
    }

    /*
    $collection = userCollection::getAllUsers();
    echo '<pre>' . var_export($collection, true) . '</pre>';
    $collection->getJson();
    */

?>