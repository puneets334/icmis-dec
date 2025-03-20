<?php
#C:\xampp\htdocs\supremecourt_core\Copying\application\controllers\Recall.php
namespace App\Controllers\Judicial;

use App\Controllers\BaseController;
use App\Models\Judicial\RecallModel;

class Recall extends BaseController
{
    public $RecallModel;

    function __construct()
    {
        // ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        // ini_set('memory_limit', '-1');

        $this->RecallModel = new RecallModel();

        // $this->request = \Config\Services::request();

        // $this->session = session();
        // $this->session->set('dcmis_user_idd', session()->get('login')['usercode']);
    }

    // public function get_session($session)
    // {
    //     $this->session->set_userdata('dcmis_user_idd', $session);
    //     $this->index();
    // }

    public function index()
    {
        $data['app_name'] = "Restoration";
        $data['usercode'] = session()->get('login')['usercode'];
        $data['case_type'] = $this->RecallModel->case_types();
        
        return view('Judicial/RecallUpdateCase', $data);
    }

    public function get_details()
    {
        $request = \Config\Services::request();

        $case_type = $request->getPost('case_type');
        $case_number = $request->getPost('case_number');
        $case_year = $request->getPost('case_year');
        $diary_number = $request->getPost('diary_number');
        $diary_year = $request->getPost('diary_year');
        $usercode = $request->getPost('usercode');
        
        $data_array = [];

        if ((isset($case_type) && isset($case_year) && isset($case_number)) || (isset($diary_number) && isset($diary_year))) 
        {
            $data_array['case_detail'] = $this->RecallModel->get_case_details($case_type, $case_number, $case_year, $diary_number, $diary_year);

            $case_detail = $data_array['case_detail'];
        
            if (isset($case_detail[0]['case_diary'])) 
            {
                $recall_detail = $this->RecallModel->checkForRecall($case_detail[0]['case_diary'], $usercode);
            
                if ($recall_detail == 0)
                    $data_array['status'] = "Dismissal";
                if ($recall_detail == 1)
                    $data_array['status'] = "Allowed";
                elseif (is_array($recall_detail))
                    $data_array['status'] = $recall_detail;
            }
        }

        echo json_encode($data_array);
    }

    public function recall_case()
    {
        $request = \Config\Services::request();

        $data['app_name'] = "Recall";
        $case_diary = $request->getPost('case_diary');
        $usercode = $request->getPost('usercode');
        $reason = $request->getPost('reason');
        $reason_option = $request->getPost('reason_option');

        if (isset($case_diary, $reason_option)) 
        {
            $result = $this->RecallModel->update_case($case_diary, $usercode, $reason, $reason_option);
            echo $result;
        }
    }
}
