<?php
    include_once __DIR__ . "/../bin/db.php";
    class Settings {
        public $id;
        public $user_id;
        public $name;
        public $value_bool;
        public $value_int;
        public $value_str;
        public $error;

        public function __construct($id = null, $user_id = null, $name = null, $value_bool = null, $value_int = null, $value_str = null) {
            $this->id = $id;
            $this->user_id = $user_id;
            $this->name = $name;
            $this->value_bool = $value_bool;
            $this->value_int = $value_int;
            $this->value_str = $value_str;
        }
        /**
        * Creates new instance of Settings from id
        *
        * @param integer $id
        *   ID of the settings in database
        * @return Settings
        *   Returns Settings object
        */
        public static function withID($id) {
            $instance = new static();
            $instance->loadByID($id);
            return $instance;
        }

        /**
         * Creates new instance of Settings from user_id and settings name
         * 
         * @param int $userid
         *  Userid of desired user to fetch from database
         * @param string $name
         *  Settings name from db to modify
         * @return User
         *  Returns User object
         */
        public static function withUserIdAndName($userid,$name) {
            $instance = new static();
            $instance->loadByUserIdAndName($userid,$name);
            return $instance;
        }

        /**
         * Creates new database record from current instance of Settings
         * 
         * @return boolean
         *  Returns true if creation to database was succesful
         */
        public function createInstancetoDB() {
            return $this->create($this->user_id, $this->name, $this->value_bool, $this->value_int, $this->value_str);
        }

        /**
        * Removes current Settings instance from database
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

        /**
         * Update current instance to database
         * 
         * @return boolean
         *  Returns true if update was succesful
         */
        public function updateInstanceToDB() {
            return $this->update($this->id,$this->user_id,$this->name,$this->value_bool,$this->value_int,$this->value_str);
        }

        protected function loadByID($id) {
            $db = new db();
            $row = $db->query("SELECT * from user_settings WHERE id = ?", $id)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyllä ID:llä ei löydy asetusta.";
            }
            $db->close();
        }

        protected function loadByUserIdAndName($user_id, $name) {
            $db = new db();
            $row = $db->query("SELECT * from user_settings WHERE user_id = ? and name = ?", $user_id, $name)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyillä parametreilla ei löydy asetusta.";
            }
            $db->close();
        }

        protected function create($user_id, $name, $value_bool, $value_int, $value_str) {
            $db = new db();
            $create = $db->query("INSERT INTO user_settings (user_id,name,value_bool,value_int,value_str) VALUES (?, ?, ?, ?, ?)",
                                    $user_id,$name,$value_bool,$value_int,$value_str);
            if ($create) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function remove($id) {
            $db = new db();
            $remove = $db->query("DELETE FROM user_settings WHERE id = ?", $id);
            if ($remove) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function update($id, $user_id, $name, $value_bool, $value_int, $value_str) {
            $db = new db();
            $update = $db->query("UPDATE user_settings SET user_id=?, name=?, value_bool=?, value_int=?, value_str=? WHERE id = ?",
                                $user_id, $name, $value_bool, $value_int, $value_str, $id);
            if ($update) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function fill(array $row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->name = $row['name'];
            $this->value_bool = $row['value_bool'];
            $this->value_int = $row['value_int'];
            $this->value_str = $row['value_str'];
        }
    }
?>