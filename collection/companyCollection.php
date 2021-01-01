<?php
    include_once __DIR__ . "/../models/company.php";
    class CompanyCollection {
        private $companies = array();

        public static function getAllCompanies() {
            $instance = new static();
            $instance->getCompanies();
            return $instance;
        }

        public function getJson() {
            return json_encode($this->companies, JSON_UNESCAPED_UNICODE);
        }

        protected function getCompanies() {
            $db = new db();
            $query = $db->query("SELECT company.*, o.username, COUNT(e.user_company_id) as total_employees from company 
                                LEFT JOIN users e on company.id = e.user_company_id 
                                LEFT JOIN users o on company.created_user_id = o.id GROUP BY company.id")->fetchAll();
            foreach($query as $company) {
                $companyObj = new Company($company["id"], $company["company_name"], $company["ytunnus"], $company["company_address"], $company["company_postcode"], $company["company_area"], $company["username"], $company["is_client"], $company["total_employees"]);
                $this->addItem($companyObj);
            }
            $db->close();
        }

        protected function addItem($obj) {
            $this->companies[] = $obj;
        }
    }

    /*
    $collection = userCollection::getAllUsers();
    echo '<pre>' . var_export($collection, true) . '</pre>';
    $collection->getJson();
    */
