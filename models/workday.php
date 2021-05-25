<?php
    include_once __DIR__ . "/../bin/db.php";
    class Workday {
        public $id;
        public $user_id;
        public $date;
        public $custom_date;
        public $start_time;
        public $custom_start_time;
        public $html_start_time;
        public $end_time;
        public $custom_end_time;
        public $html_end_time;
        public $break;
        public $html_break;
        public $total_time;
        public $explanation;
        public $created_time;
        public $custom_created_time;
        public $modified_time;
        public $custom_modified_time;
        public $modified_user_id;
        public $error;
        
        public function __construct($id = null, $user_id = null, $date = null, $custom_date = null, $start_time = null, $custom_start_time = null, $html_start_time = null, $end_time = null, $custom_end_time = null, $html_end_time = null, $break = null, $total_time = null, $explanation = null, $created_time = null, $custom_created_time = null, $modified_time = null, $custom_modified_time = null, $modified_user_id = null, $error = null) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
        $this->custom_date = $custom_date;
        $this->start_time = $start_time;
        $this->custom_start_time = $custom_start_time;
        $this->html_start_time = $html_start_time;
        $this->end_time = $end_time;
        $this->custom_end_time = $custom_end_time;
        $this->html_end_time = $html_end_time;
        $this->break = $break;
        $this->total_time = $total_time;
        $this->explanation = $explanation;
        $this->created_time = $created_time;
        $this->custom_created_time = $custom_created_time;
        $this->modified_time = $modified_time;
        $this->custom_modified_time = $custom_modified_time;
        $this->modified_user_id = $modified_user_id;
        $this->error = $error;
        }
        /**
        * Creates new instance of Workday from id
        *
        * @param integer $id
        *   ID of the workday in database
        * @return Workday
        *   Returns Workday object
        */
        public static function withID($id) {
            $instance = new static();
            $instance->loadByID($id);
            return $instance;
        }

        /**
         * Update current instance to database
         * 
         * @return boolean
         *  Returns true if update was succesful
         */
        public function updateInstanceToDB() {
            return $this->update($this->id,$this->date,$this->start_time,$this->end_time,$this->html_break,$this->total_time,$this->explanation,$this->modified_user_id);
        }

        /**
         * Creates new database record from current instance of Workday
         * 
         * @return boolean
         *  Returns true if creation to database was succesful
         */
        public function createInstancetoDB() {
            // If breaktime is empty, set it to 00:00:00
            if (empty($this->html_break)) {
                $this->html_break = "00:00:00";
            }

            // Make sure that end time is greater than start time before creating database record
            $endtimedt = new DateTime($this->end_time);
            $starttimedt = new DateTime($this->start_time);
            if ($endtimedt >= $starttimedt) {
                return $this->create($this->user_id, $this->date, $this->start_time, $this->end_time, $this->html_break, $this->total_time, $this->explanation);
            } else {
                $this->error = "Työpäivän kesto negatiivinen.";
                return false;
            }
        }

        /**
        * Sets start_time, end_time, total_time and break values correctly straight from html form values.
        * Note: Trims input
        * 
        * @param string $post_start
        *   HTML form Post start_time
        * @param string $post_end
        *   HTML form Post end_time
        * @param string $post_break
        *   HTML form Post break_time
        * @return boolean
        *   Returns true if set was succesful
        */
        public function SetTimesFromPost($post_start,$post_end,$post_break) {
            $starttimedt = new DateTime(trim($post_start));
            $endtimedt = new DateTime(trim($post_end));
            // Get break time on correct format for DateInterval
            list($h, $m) = sscanf(trim($post_break), "%d:%d");
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
            if ($endtimedt >= $starttimedt) {
                $this->start_time = $starttimedt->format("Y-m-d H:i:s");
                $this->end_time = $endtimedt->format("Y-m-d H:i:s");
                $this->html_break = $post_break;
                $this->total_time = $total_time;
                return true;
            } else {
                return false;
            }
            
        }

        /**
        * Removes current workday instance from database
        *
        * @return boolean
        *   Returns true if remove was succesful
        */
        public function removeInstance() {
            if ($this->remove($this->id)) {
                return true;
            } else {
                return false;
            }
        }

        protected function loadByID($id) {
            $db = new db();
            $row = $db->query("SELECT *, DATE_FORMAT(start_time, '%Y-%m-%dT%H:%i') AS html_start_time, 
                                DATE_FORMAT(end_time, '%Y-%m-%dT%H:%i') AS html_end_time,
                                DATE_FORMAT(start_time, '%e.%c.%y %H:%i') AS custom_start_time,
                                DATE_FORMAT(end_time, '%e.%c.%y %H:%i') AS custom_end_time,
                                DATE_FORMAT(created_time, '%e.%c.%y %H:%i') AS custom_created_time,
                                DATE_FORMAT(modified_time, '%e.%c.%y %H:%i') AS custom_modified_time,
                                DATE_FORMAT(break_time, '%H:%i') AS custom_break_time,
                                DATE_FORMAT(date, '%d.%m.%Y') AS custom_dateformat 
                                FROM workday WHERE id = ?", $id)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyllä ID:llä ei löydy työpäivää.";
            }
            $db->close();
        }

        protected function fill(array $row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->date = $row['date'];;
            $this->custom_date = $row['custom_dateformat'];
            $this->start_time = $row['start_time'];
            $this->custom_start_time = $row['custom_start_time'];
            $this->html_start_time = $row['html_start_time'];
            $this->end_time = $row['end_time'];
            $this->custom_end_time = $row['custom_end_time'];
            $this->html_end_time = $row['html_end_time'];
            $this->break = $row['custom_break_time'];
            $this->html_break = $row['break_time'];
            $this->total_time = $row['total_time'];
            $this->explanation = $row['explanation'];
            $this->created_time = $row['created_time'];
            $this->custom_created_time = $row['custom_created_time'];
            $this->modified_time = $row['modified_time'];
            $this->custom_modified_time = $row['custom_modified_time'];
            $this->modified_user_id = $row['modified_user_id'];
        }

        protected function create($user_id, $date, $start_time, $end_time, $break_time, $total_time, $explanation) {
            $db = new db();
            $create = $db->query("INSERT INTO workday (user_id, date, start_time, end_time, break_time, total_time, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)",
                                $user_id, $date, $start_time, $end_time, $break_time, $total_time, $explanation);
            if ($create) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function update($id, $date, $start_time, $end_time, $break_time, $total_time, $explanation, $modified_user_id) {
            $db = new db();
            $update = $db->query("UPDATE workday SET date=?, start_time=?, end_time=?, break_time=?, total_time=?, explanation=?, modified_user_id=? WHERE id = ?",
                                $date, $start_time, $end_time, $break_time, $total_time, $explanation, $modified_user_id, $id);
            if ($update) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function remove($id) {
            $db = new db();
            $remove = $db->query("DELETE FROM workday WHERE id = ?", $id);
            if ($remove) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }
    }
?>