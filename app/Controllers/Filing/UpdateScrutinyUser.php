<?php

namespace App\Controllers\Filing;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Filing\ScrutinyUserModel;
use App\Models\Filing\Model_fil_trap;


class UpdateScrutinyUser extends BaseController
{

    // function __construct()
    //   {   
    //       if(empty(session()->get('filing_details')['diary_no'])){
    //           header('Location:'.base_url('Filing/Diary/search'));exit();
    //       }else{
    //           $this->diary_no = session()->get('filing_details')['diary_no'];
    //       }
    //   }

    public function index()
    {
        return view('Filing/update_scrutiny_user');
    }

    public function GetCauseTitle()
    {
        // pr($_REQUEST);
        $model = new ScrutinyUserModel();
        $dairy_no = $this->request->getPost('d_no') . $this->request->getPost('d_yr');

        $data['result'] = $model->getCauseTitle($dairy_no);

        //   pr($data['result']);
        if (!empty($data['result'])) {

            foreach ($data['result'] as $row) {

                echo "<div class='text-center'></br><font style='text-align: center;font-size: 15px;color: red'>Cause Title: </font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>" . $row['pet_name'] . "</font></br>";
                echo "<font style='text-align: center;font-size: 15px;color: blue'>VS</font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>" . $row['res_name'] . "</font></br></div>";
            }
        } else {
            echo "<font style='text-align: center;font-size: 14px;color: black'>Case not found</font>";
        }
    }



    public function update_info()
    {

        $model = new Model_fil_trap();
        $fdno = $this->request->getPost('d1');
        $rowchk = $model->getCheckQuery($fdno);

        if (empty($rowchk)) {
            return "<div class='text-center'>Data not found</div>";
        }

        if ($rowchk['c_status'] == 'D') {
            echo "<div class='text-center'>DISPOSED OFF MATTER</div>";
            exit();
        }
        if ($rowchk['remarks'] != "FDR -> AOR") {
            echo "<div class='text-center'>Current remarks is " . $rowchk['remarks'] . ", cannot update data</div>";
            exit();
        }
        if ($rowchk['dno'] == 'not scefm') {
            echo "<div class='text-center'>Not SC-Efm matter</div>";
            exit();
        }
        $diary_no = $rowchk['diary_no'];
        $uid = $model->getUidByDiaryNo($diary_no);
    }
}
