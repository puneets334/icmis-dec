<?php

namespace App\Models\ManagementReport;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class ClPrintOfficeReportModel extends Model
{
    protected $db;
	
    public function __construct(){
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    

}