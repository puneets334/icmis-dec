<?php

namespace App\Controllers\Court\CourtReportNHS;
use CodeIgniter\Model;
use App\Controllers\BaseController;
use App\Models\Casetype;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class CourtReportPaperless extends BaseController
{
    public $model;
    public $diary_no;
    public $qrlib;
    public $Fpdf;

    function __construct()
    {   
        $this->model = new CourtMasterModel();
        $this->qrlib = new Qrlib();
        $this->Fpdf = new Fpdf();

        //   if(empty(session()->get('filing_details')['diary_no'])){
        //     header('Location:'.base_url('Filing/Diary/search'));exit();
        // }else{
        //     $this->diary_no = session()->get('filing_details')['diary_no'];
        // }
    
    }

    public function index()
    {

        return view('Court/CourtReportNHS/paperless');
    }

    public function get_cl_date_judges(){

        $judge_code = '';
        $selectOption = ''; 
        $select_display_none = ''; 

        $flag = $_REQUEST['flag'] ?? null;

        $ucode = $session->get('dcmis_user_idd');
        $icmis_user_jcode = $session->get('icmis_user_jcode');
        $dcmis_section = $session->get('dcmis_section'); 

        if ($flag === 'court') {
                if ($icmis_user_jcode > 0 && $ucode != 1) {
                    $judge_code = "AND judge.jcode = $icmis_user_jcode";
                    $select_display_none = "display:none;";
                } else {
                    $selectOption = "<option value=''>select</option>";
                }
            } elseif ($flag === 'reader') {
                if ($dcmis_section == 62) {
                    $judge_code = "AND (roster.courtno = 21 OR roster.courtno = 61)";
                    $select_display_none = "display:none;";
                } elseif ($dcmis_section == 81) {
                    $judge_code = "AND (roster.courtno = 22 OR roster.courtno = 62)";
                    $select_display_none = "display:none;";
                } else {
                    $selectOption = "<option value=''>select</option>";
                }
            }

            $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));
            $this->db->distinct('roster.courtno');
            $this->db->select("CONCAT_WS(judge.jname,' ', judge.first_name, judge.sur_name) AS jname");
            $this->db->from('roster,*');
            $this->db->join('roster_judge', 'roster.id = roster_judge.roster_id');
            $this->db->join('judge', 'judge.jcode = roster_judge.judge_id');
            $this->db->join('cl_printed','cl_printed.roster_id = roster.id','left');
            $this->db->where('cl_printed.next_dt','N');
            $this->db->where('cp.next_dt is NOT NULL', NULL, FALSE);
            $this->db->where('roster.from_date','>=', $dtd);
            $this->db->where('roster.to_date', '0000-00-00');
            $this->db->where('judge.jtype','R'.$judge_code);
            $this->db->where('judge.is_retired','N');
            $this->db->where('roster.display','Y');
            $this->db->where('judge.display','Y');
            $this->db->where('cl_printed.display','Y');
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->result();
                    foreach($result as $val){
                        echo '<option value="' . $result["courtno"].'">' . str_replace("\\", "", $result["jname"]) . '</option>';
                    }
            }
            else
            {
                return 0;
            }

        }

       public function get_title(){
           
        $model = new CourReportPaperLessModel();

        $dtd = $this->request->getPost('dtd');
        $courtno = $this->request->getPost('courtno');
        
        $dtd = date('Y-m-d', strtotime($dtd));
    
        $row = $model->getJudgeDetails($dtd, $courtno);

        $court = '';
        $judge_name = '';

        if ($row) {
          
            switch ($row['courtno']) {
                case 21:
                    $court = "Registrar Court No. 1";
                    break;
                case 61:
                    $court = "Registrar Virtual Court No. 1";
                    break;
                case 22:
                    $court = "Registrar Court No. 2";
                    break;
                case 62:
                    $court = "Registrar Virtual Court No. 2";
                    break;
                default:
                    $court = "Court No. " . $row['courtno'];
                    break;
            }
            $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];

            return $judge_name;
        }

    
    }

    public function get_item_nos(){

        $crt = $request->getPost('courtno');
        $dtd = $request->getPost('dtd');
        $mf = 'M'; 
        $r_status = $request->getPost('r_status');

        if ($crt > 0) {
            $model = new CourReportPaperLessModel();

            $rosterIds = $model->getRosterIds($crt, $dtd, $mf);

    
            $caseDetails = $model->getCaseDetails($dtd, $rosterIds, $r_status);

            return $caseDetails;

        } else {
           
            throw new \Exception('Invalid court number');
        }
    }
    

}