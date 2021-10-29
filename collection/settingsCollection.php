<?php
    include_once __DIR__ . "/../models/settings.php";
    class SettingsCollection {
        private $settings = array();

        public static function getAllUserSettings($userid) {
            $instance = new static();
            $instance->getUserSettingsFromUserID($userid);
            return $instance;
        }

        public function getJson() {
            return json_encode($this->settings, JSON_UNESCAPED_UNICODE);
        }

        protected function getUserSettingsFromUserID($userid) {
            $db = new db();
            $query = $db->query("SELECT * from user_settings WHERE user_id = ?", $userid)->fetchAll();
            foreach($query as $setting) {
                $settingObj = new Settings($setting["id"], $setting["user_id"], $setting["name"], $setting["value_bool"], $setting["value_int"], $setting["value_str"]);
                $this->addItem($settingObj);
            }
            $db->close();
        }

        protected function addItem($obj) {
            $this->settings[] = $obj;
        }
    }
?>
