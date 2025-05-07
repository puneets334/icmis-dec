<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use App\Models\Filing\AdvocateModel;
use App\Models\Casetype;
use App\Models\Listing\PreviousAdvance;

class PreviousAdvanceList extends BaseController
{

    public $diary_no;
    public $Casetype;
    public $PreviousAdvance;
    public $request;

    function __construct()
    {
        $this->Casetype = new Casetype();
        $this->PreviousAdvance = new PreviousAdvance();
    }
    public function prev_advance_list_all()
    {
        return view('Listing/advance_list/prev_advance_list_all');
    }


    public function prev_advance_list_all_get()
    {
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        $list_dt = $request->getPost('list_dt');
        if (!$list_dt) {
            return $response->setBody("<p style='text-align: center; font-weight: bold; color:red;'>Invalid Date Provided</p>");
        }

        $listing_date = date('Y-m-d', strtotime($list_dt));
        $base_path = 'judgment/cl/advance/';
        $date_folder = $base_path . $listing_date;
        
        $file_path = $date_folder . '/M_J_ALL.html';
        
        // if (!is_dir($date_folder)) {
        //     mkdir($date_folder, 0777, true);
        // }
        if (!file_exists($file_path)) {
            file_put_contents($file_path, '<div align="center" id="getlogo" style="font-size: 12px;" class="mb-5">
                            <img src="judgment/cl/scilogo.png" width="50px" height="80px"><br>
                            <span style="text-align: center;font-weight: 600;font-size: 14px;font-family: verdana;" align="center">
                                SUPREME COURT OF INDIA
                            </span>
                        </div><p>ALL COURTS PREVIOUS ADVANCE LIST MODULE</p>');
            return $response->setBody("<p style='text-align: center; font-weight: bold; color:red;'>Sorry, Advance list not available for dated " . esc($list_dt) . "</p>");
        }
        $logopath = base_url('judgment/cl/scilogo.png');
        $content = file_get_contents($file_path);
        $updated_content = str_replace("judgment/cl/scilogo.png", $logopath, $content);
        return $response->setBody($updated_content);
    }
    
    public function getAdvanceList()
    {
        $request = \Config\Services::request();
        $list_dt = $request->getPost('list_dt');
        $board_type = $request->getPost('board_type');


        $data = $this->PreviousAdvance->getAdvanceList($list_dt, $board_type);

        return $this->response->setJSON($data);
    }

    public function savePrint()
    {
        $request = \Config\Services::request();
        $list_dt = $request->getPost('list_dt');
        $mainhead = $request->getPost('mainhead');
        $board_type = $request->getPost('board_type');
        $main_suppl = $request->getPost('main_suppl');
        $prtContent = $request->getPost('prtContent');
        $result = $this->PreviousAdvance->savePrint($list_dt, $mainhead, $board_type, $main_suppl, $prtContent);
        return $this->response->setJSON($result);
    }
}
