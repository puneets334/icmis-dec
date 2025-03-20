<?
namespace App\Models\Entities;

use CodeIgniter\Model;

class FilTrapHistoryModel  extends Model
{
    protected $table = 'fil_trap_his';
    protected $allowedFields = [
        'diary_no', 'd_by_empid', 'd_to_empid', 'disp_dt', 'remarks', 'r_by_empid',
        'rece_dt', 'comp_dt', 'disp_dt_seq', 'thisdt', 'other', 'scr_lower', 'token_no'
    ];
}
