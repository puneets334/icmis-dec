<?php

namespace App\Models\Entities;
use CodeIgniter\Model;

class Model_heardt extends Model
{

    protected $table      = 'heardt';
    protected $primaryKey = 'diary_no';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [ 'diary_no', 'conn_key', 'next_dt', 'mainhead', 'subhead', 'clno', 'brd_slno', 'roster_id', 'judges', 'coram', 'board_type', 'usercode', 'ent_dt', 'module_id', 'mainhead_n', 'subhead_n', 'main_supp_flag', 'listorder', 'tentative_cl_dt', 'listed_ia', 'sitting_judges', 'list_before_remark', 'coram_prev', 'is_nmd', 'no_of_time_deleted', 'create_modify', 'updated_on', 'updated_by', 'updated_by_ip'];

    protected $useTimestamps = true;
    protected $createdField  = 'create_modify';
    protected $updatedField  = 'updated_on';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


}