<?php

namespace App\Models\Copying;

use CodeIgniter\Model;

class Model_vcreport extends Model
{
    protected $db;
    public function __construct(){
        parent::__construct();
        $this->db = \Config\Database::connect('eservices');
    }
	
}
