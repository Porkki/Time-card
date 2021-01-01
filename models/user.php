<?php
    include_once __DIR__ . "/../bin/db.php";
    class User {
        public $id;
        public $username;
        public $class;
        public $firstname;
        public $lastname;
        public $user_company_id;
        public $error;
        
        private $password;
        // https://stackoverflow.com/questions/1699796/best-way-to-do-multiple-constructors-in-php
        public function __construct($id = null, $username = null, $class = null, $firstname = null, $lastname = null, $user_company_id = null, $error = null, $password = null) {
            $this->id = $id;
            $this->username = $username;
            $this->class = $class;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->user_company_id = $user_company_id;
            $this->error = $error;
            if (!empty($password)) {
                $this->password = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        /**
        * Creates new instance of User from id
        *
        * @param integer $id
        *   ID of the user in database
        * @return User
        *   Returns User object
        */
        public static function withID($id) {
            $instance = new static();
            $instance->loadByID($id);
            return $instance;
        }

        /**
        * 
        * Creates new instance of User from username and password
        *
        * @param string $username
        *   Username
        * @param string $password
        *   Password in plaintext
        * @return User
        *   Returns User object
        */
        public static function withUsernameAndPassword($username, $password) {
            $instance = new static();
            $instance->loadByUsernameAndPassword($username, $password);
            return $instance;
        }

        /**
         * Creates new instance of User from username
         * 
         * @param string $username
         *  Username of desired user to fetch from database
         * @return User
         *  Returns User object
         */
        public static function withUsername($username) {
            $instance = new static();
            $instance->loadByUsername($username);
            return $instance;
        }

        /**
         * Set password (usually for update to database)
         * 
         * @param string $password
         *  New password in plaintext
         */
        public function setPassword($password) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

        /**
        * Removes user from database with ID
        *
        * @param integer $id
        *   ID of the user in database
        * @return boolean
        *   Returns true if remove was succesful
        */
        public static function removeID($id) {
            $instance = new static();
            if ($instance->remove($id)) {
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
            return $this->update($this->id,$this->username,$this->password,$this->class,$this->firstname,$this->lastname,$this->user_company_id);
        }

        /**
        * Removes current user instance from database
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
         * Creates new database record from current instance of user
         * 
         * @return boolean
         *  Returns true if creation to database was succesful
         */
        public function createInstancetoDB() {
            return $this->create($this->username,$this->password,$this->class,$this->firstname,$this->lastname,$this->user_company_id);
        }

        // https://www.brainbell.com/tutorials/php/Static_Methods.html

        protected function loadByID($id) {
            $db = new db();
            $row = $db->query("SELECT id, username, class, firstname, lastname, user_company_id from users WHERE id = ?", $id)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Syötetyllä ID:llä ei löydy käyttäjää.";
            }
            $db->close();
        }

        protected function loadByUsernameAndPassword($username, $password) {
            $db = new db();
            $row = $db->query("SELECT id, username, password, firstname, lastname, class, user_company_id FROM users WHERE username = ?", $username)->fetchArray();
            if (!empty($row)) {
                if (password_verify($password, $row["password"])) {
                    $this->fill($row);
                } else {
                    $this->error = "Tarkista käyttäjätunnus ja/tai salasana.";
                }
            } else {
                $this->error = "Tarkista käyttäjätunnus ja/tai salasana.";
            }
            $db->close();
        }

        protected function loadByUsername($username) {
            $db = new db();
            $row = $db->query("SELECT id, username, password, firstname, lastname, class, user_company_id FROM users WHERE username = ?", $username)->fetchArray();
            if (!empty($row)) {
                $this->fill($row);
            } else {
                $this->error = "Käyttäjänimeä ei ole olemassa.";
            }
            $db->close();
        }

        protected function fill(array $row) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = null;
            $this->class = $row['class'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->user_company_id = $row['user_company_id'];
        }

        protected function update($id, $username, $password, $class, $firstname, $lastname, $user_company_id) {
            $db = new db();
            $update = $db->query("UPDATE users SET username=?, password=IFNULL(?, password), class=?, firstname=?, lastname=?, user_company_id=? WHERE id = ?",
                                    $username, $password, $class, $firstname, $lastname, $user_company_id, $id);
            if ($update) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function create($username, $password, $class, $firstname, $lastname, $user_company_id) {
            $db = new db();
            $create = $db->query("INSERT INTO users (username, password, class, firstname, lastname, user_company_id) VALUES (?, ?, ?, ?, ?, ?)",
                                    $username, $password, $class, $firstname, $lastname, $user_company_id);
            if ($create) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }

        protected function remove($id) {
            $db = new db();
            $remove = $db->query("DELETE FROM users WHERE id = ?", $id);
            if ($remove) {
                return true;
            } else {
                return false;
            }
            $db->close();
        }
        
    }
?>