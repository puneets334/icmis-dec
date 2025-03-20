<?php

namespace App\Controllers\Plc;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Court\LiveCourtModel;


class LiveCourt extends BaseController
{
    public $LiveCourtModel;

    function __construct()
    {   
        $this->LiveCourtModel = new LiveCourtModel();
    }

    public function index()
    {
        $usercode = session()->get('login')['usercode'];
        $data['usercode'] = $usercode;
        return view('Court/Plc/court_process',$data);
    }

    public function getClDateJudges()
    {
        $courtNos = $this->LiveCourtModel->get_cl_date_judges();
    }
    
    public function getTitle()
    {
        $courtNos = $this->LiveCourtModel->get_title();
        if(!empty($courtNos))
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $courtNos,
                'msg' => 'Data retrieved successfully.'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => false,
                'data' => [],
                'msg' => 'No record found'
            ]);
        }
    }
    
    public function getItemNos()
    {
        $result = $this->LiveCourtModel->get_item_nos();
        if(!empty($result))
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'msg' => 'Data retrieved successfully.'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => false,
                'data' => [],
                'msg' => 'No record found'
            ]);
        }
    }

    public function getRightPanelDataRow2()
    {
        $result = $this->LiveCourtModel->get_right_panel_data_row2();
        if(!empty($result))
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'msg' => 'Data retrieved successfully.'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => false,
                'data' => [],
                'msg' => 'No record found'
            ]);
        }
    }

    public function getGistDetails()
    {
        $result = $this->LiveCourtModel->get_gist_details();
        if(!empty($result))
        {
            return $this->response->setJSON([
                'status' => true,
                'data' => $result,
                'msg' => 'Data retrieved successfully.'
            ]);
        }
        else
        {
            return $this->response->setJSON([
                'status' => false,
                'data' => [],
                'msg' => 'No record found'
            ]);
        }
    }

    public function court_process()
    {
        $usercode = session()->get('login')['usercode'];
        $icmis_user_jcode = session()->get('login')['jcode'];
        if($icmis_user_jcode > 0 and $usercode != 1)
        {
            $select_display_none = "display:none;";
        }
        else
        {
            $select_display_none = "display:block;";
        }
        $courtNos = $this->LiveCourtModel->getCourtNoWithJudgeName($usercode, $icmis_user_jcode);
        $data['usercode'] = $usercode;
        $data['icmis_user_jcode'] = $icmis_user_jcode;
        $data['courtNos'] = $courtNos;
        $data['select_display_none'] = $select_display_none;
        // return view('Plc/court_process',$data);
        return view('Plc/courtProcess',$data);
    }
}