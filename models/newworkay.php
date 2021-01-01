<?php
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

    public $total_time;

    public $explanation;

    public $created_time;

    public $custom_created_time;

    public $modified_time;

    public $custom_modified_time;

    public $modified_user_id;

    public $error;

    public function __construct($id = null, $user_id = null, $date = null, $custom_date = null, $start_time = null, $custom_start_time = null, $html_start_time = null, $end_time = null, $custom_end_time = null, $html_end_time = null, $break = null, $total_time = null, $explanation = null, $created_time = null, $custom_created_time = null, $modified_time = null, $custom_modified_time = null, $modified_user_id = null, $error = null)
    {
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

    
}
?>