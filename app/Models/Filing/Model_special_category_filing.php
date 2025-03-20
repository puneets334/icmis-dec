<?php
namespace App\Models\Filing;
use CodeIgniter\Model;
class Model_special_category_filing extends Model{
    protected $table      = 'special_category_filing';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'diary_no', 'ref_special_category_filing_id', 'display', 'updated_by', 'updated_on', 'create_modify', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

}
