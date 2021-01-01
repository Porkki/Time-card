<?php
    include_once __DIR__ . "/../models/workday.php";
    class workdayCollection {
        private $workdays = array();

        public static function getAllWorkdaysFromSessionID() {
            $instance = new static();
            $instance->_getAllWorkdaysFromUserID($_SESSION["id"]);
            return $instance;
        }

        public static function getAllWorkdaysFromUserID($id) {
            $instance = new static();
            $instance->_getAllWorkdaysFromUserID($id);
            return $instance;
        }

        // YYYY-MM-DD format required
        public static function getWorkdaysBetweenDates($id, $start, $end) {
            $instance = new static();
            $instance->_getAllWorkdaysFromUserID($id, $start, $end);
            return $instance;
        }

        public function getJson() {
            return json_encode($this->workdays, JSON_UNESCAPED_UNICODE);
        }

        protected function _getAllWorkdaysFromUserID($id, $start=null, $end=null) {
            $db = new db();
            if ($start == null && $end == null) {
                $query = $db->query("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS html_start_time, 
                                DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS html_end_time,
                                DATE_FORMAT(start_time, '%e.%c.%y %H:%i') AS custom_start_time,
                                DATE_FORMAT(end_time, '%e.%c.%y %H:%i') AS custom_end_time,
                                DATE_FORMAT(created_time, '%e.%c.%y %H:%i') AS custom_created_time,
                                DATE_FORMAT(modified_time, '%e.%c.%y %H:%i') AS custom_modified_time,
                                DATE_FORMAT(break_time, '%H:%i') AS custom_break_time,
                                DATE_FORMAT(date, '%d.%m.%Y') AS custom_date 
                                FROM workday WHERE user_id = ? ORDER BY date", $id)->fetchAll();
            } else {
                $query = $db->query("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS html_start_time, 
                                DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS html_end_time,
                                DATE_FORMAT(start_time, '%e.%c.%y %H:%i') AS custom_start_time,
                                DATE_FORMAT(end_time, '%e.%c.%y %H:%i') AS custom_end_time,
                                DATE_FORMAT(created_time, '%e.%c.%y %H:%i') AS custom_created_time,
                                DATE_FORMAT(modified_time, '%e.%c.%y %H:%i') AS custom_modified_time,
                                DATE_FORMAT(break_time, '%H:%i') AS custom_break_time,
                                DATE_FORMAT(date, '%d.%m.%Y') AS custom_date 
                                FROM workday WHERE user_id = ? AND date BETWEEN CAST(? AS DATE) AND CAST(? AS DATE) ORDER BY date", $id, $start, $end)->fetchAll();
            }
            
            foreach($query as $workday) {
                $workdayObj = new Workday($workday["id"],$workday["user_id"],$workday["date"],$workday["custom_date"],$workday["start_time"],$workday["custom_start_time"],$workday["html_start_time"],
                $workday["end_time"],$workday["custom_end_time"],$workday["html_end_time"],$workday["custom_break_time"],$workday["total_time"],$workday["explanation"],$workday["created_time"],
                $workday["custom_created_time"],$workday["modified_time"],$workday["custom_modified_time"],$workday["modified_user_id"]);
                $this->_addItem($workdayObj);
            }

            $db->close();
        }

        protected function _addItem($obj) {
            $this->workdays[] = $obj;
        }
    }
?>