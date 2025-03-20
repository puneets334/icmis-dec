<?php 
namespace App\Models\Listing;

use CodeIgniter\Model;

class PreviousAdvance extends Model
{
    protected $table = 'advance_list'; // Use your actual table name
    protected $primaryKey = 'id';

    public function getAdvanceList($list_dt, $board_type)
    {
        $file_path = "/home/judgment/cl/advance/" . date('Y-m-d', strtotime($list_dt)) . "/M_J_ALL.html";
        if (!file_exists($file_path)) {
            return "<p style='text-align: center; font-weight: bold; color:red;'>Sorry, Advance list not available for dated " . $list_dt . "</p>";
        } else {
            $content = file_get_contents($file_path);
            return str_replace("/home/judgment/cl/scilogo.png", "scilogo.png", $content);
        }
    }

    public function savePrint($list_dt, $mainhead, $board_type, $main_suppl, $prtContent)
    {
        
        $data = [
            'list_dt' => $list_dt,
            'mainhead' => $mainhead,
            'board_type' => $board_type,
            'main_suppl' => $main_suppl,
            'prtContent' => $prtContent
        ];

        // Assume you have a 'print_save' table
        return $this->insert($data);
    }
}
