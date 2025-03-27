<?php

namespace App\Models\HybridHearing;

use CodeIgniter\Model;

class ConsentThroughEmailModel extends Model
{

    public function __construct() {
        parent::__construct();
        $this->db = db_connect();
    }

    public function deleteConsent($diary_no, $next_dt, $where) {

    }

    public function createConsent($data) {
        
    }

}