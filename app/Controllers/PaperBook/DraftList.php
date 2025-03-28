<?php

namespace App\Controllers\PaperBook;
use App\Controllers\BaseController;
use App\Models\PaperBook\DraftListModel;

class DraftList extends BaseController
{

    public  $DraftListModel;
    function __construct()
    {
         $this->DraftListModel = new DraftListModel();
    }

    /**
     * To display draft list filter page
     *
     * @return void
     */
    public function index()
    {
        $data['cause_date_list'] = $this->DraftListModel->getCauseListDate();
        return  view('PaperBook/advance_list', $data);
    }


    /**
     * To display draft list results
     *
     * @return void
     */
    public function get_advance_report()
    {
        $ucode = session()->get('login')['usercode'];
        $cl_date = trim($this->request->getGet('cl_date'));
        $list_type = trim($this->request->getGet('list_type'));
        $ma = $this->request->getGet('ma');

        $row_type = $this->DraftListModel->getUserType($ucode);
        $utype = $row_type ? $row_type['usertype'] : null;
       
        $data['serve_status'] = [];
        $data['title'] = '';
        if(!empty($list_type)) {
            $data = $this->DraftListModel->getCaseType($list_type, $ucode, $utype, $ma, $cl_date);
        }
        return  view('PaperBook/get_advance_report', $data);        
    }
}
