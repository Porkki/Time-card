<?php
    include_once __DIR__ . "/../bin/db.php";
    class Company {
        public $id;
        public $name;
        public $ytunnus;
        public $address;
        public $postcode;
        public $area;
        public $created_user_id;
        public $is_client;
        public $worker_count;
        public $error;

        public function __construct($id = null, $name = null, $ytunnus = null, $address = null, $postcode = null, $area = null, $created_user_id = null, $is_client = null, $worker_count = null, $error = null) {
            $this->id = $id;
            $this->name = $name;
            $this->ytunnus = $ytunnus;
            $this->address = $address;
            $this->postcode = $postcode;
            $this->area = $area;
            $this->created_user_id = $created_user_id;
            $this->is_client = $is_client;
            $this->worker_count = $worker_count;
            $this->error = $error;
        }
        /**
        * Creates new instance of Company from id
        *
        * @param integer $id
        *   ID of the company in database
        * @return Company
        *   Returns Company object
        */
        public static function withID($id) {
            $instance = new static();
            $instance->loadByID($id);
            return $instance;
        }

        /**
        * Creates new instance of Company from name
        *
        * @param string $name
        *   ID of the company in database
        * @return Company
        *   Returns Company object
        */
        public static function withName($name) {
            $instance = new static();
            $instance->loadByName($name);
            return $instance;
        }

        /**
         * Creates new database record from current instance of Company
         * 
         * @return boolean
         *  Returns true if creation to database was succesful
         */
        public function createInstancetoDB() {
            return $this->create($this->name, $this->ytunnus, $this->address, $this->postcode, $this->area, $this->created_user_id, $this->is_client);
        }

        /**
        * Removes current Company instance from database
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
            return $this->update($this->id,$this->name,$this->ytunnus,$this->address,$this->postcode,$this->area,$this->is_client);
        }

        protected function loadByID($id) {
            $db = new db();
            $row = $db->query("SELECT * from company WHERE id = ?", $id)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyllä ID:llä ei löydy yritystä.";
            }
            $db->close();
        }

        protected function loadByName($name) {
            $db = new db();
            $row = $db->query("SELECT * from company WHERE company_name = ?", $name)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyllä nimellä ei löydy yritystä.";
            }
            $db->close();
        }

        protected function create($name, $ytunnus, $address, $postcode, $area, $created_user_id, $is_client) {
            $db = new db();
            $create = $db->query("INSERT INTO company (company_name, ytunnus, company_address, company_postcode, company_area, created_user_id, is_client) VALUES (?, ?, ?, ?, ?, ?, ?)",
                                    $name, $ytunnus, $address, $postcode, $area, $created_user_id, $is_client);
            if ($create) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function remove($id) {
            $db = new db();
            $remove = $db->query("DELETE FROM company WHERE id = ?", $id);
            if ($remove) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function update($id, $name, $ytunnus, $address, $postcode, $area, $is_client) {
            $db = new db();
            $update = $db->query("UPDATE company SET company_name=?, ytunnus=?, company_address=?, company_postcode=?, company_area=?, is_client=? WHERE id = ?",
                                    $name, $ytunnus, $address, $postcode, $area, $is_client, $id);
            if ($update) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function fill(array $row) {
            $this->id = $row['id'];
            $this->name = $row['company_name'];
            $this->ytunnus = $row['ytunnus'];
            $this->address = $row['company_address'];
            $this->postcode = $row['company_postcode'];
            $this->area = $row['company_area'];
            $this->created_user_id = $row['created_user_id'];
            $this->is_client = $row['is_client'];
        }
    }
?>