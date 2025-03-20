<?php

namespace App\Models\MasterManagement;
use CodeIgniter\Model;

class KeywordModel extends Model
{
    protected $table = 'master.ref_keyword';
    protected $primaryKey = 'id';
    protected $allowedFields = ['keyword_code', 'keyword_description', 'updated_by', 'updated_on', 'is_deleted'];
}
  
