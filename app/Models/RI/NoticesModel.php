<?php
namespace App\Models\RI;
use CodeIgniter\Model;

class NoticesModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }

    


}