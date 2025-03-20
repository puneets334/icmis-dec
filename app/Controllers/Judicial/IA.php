<?php
namespace App\Controllers\Judicial;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Judicial\Model_IA;

class IA extends BaseController
{
    public $Dropdown_list_model;
    public $Model_IA;

    function __construct(){
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->Model_IA = new Model_IA();
    }
    /*Start Judicial IA UPDATE*/
    public function index(){
        return view('Judicial/IA_view');
    }
    /*end Judicial IA UPDATE*/

    public function get_search(){

    }

    public function get_content_list()
    {        
        $usercode = session()->get('login')['usercode'];
        $diary_no = session()->get('filing_details')['diary_no'];

        /*$t_fil_no=get_case_nos($diary_no,'&nbsp;&nbsp;');
        echo 't_fil_no='.$t_fil_no;exit();*/
		
        $row_fl = $this->Model_IA->get_diary_details($diary_no);
		//pr($row_fl);
        if (!empty($row_fl)){
            if($usercode!=$row_fl['dacode'] && $usercode!=1){
                $users = is_data_from_table('master.users', ['usercode' => $usercode, 'display' => 'Y'],'section','R');
                if (!empty($users)){
                    $usersection= $users['section'];
                    if($usersection!=62 and $usersection!=81 and $usersection!=11){
                        echo "<center> <p><font class='text-danger'>Only DA can Update IA</font></p> </center>";exit();
                    }
                }
            }
        }else{
            echo "<center> <p><font class='text-danger'>SORRY, NO RECORD FOUND !!!</font></p> </center>";exit();
        }
		
        $data['short_description']='';
        if (!empty($row_fl['casetype_id'])){
            $get_short_description = $this->Model_IA->get_short_description($row_fl['casetype_id']);
            if (!empty($get_short_description)){$data['short_description']=$get_short_description['short_description']; }
        }

		
        $result = $this->Model_IA->get_party_details($diary_no);
     
        $data['row_fl'] = $row_fl; 
        $data['IArec'] = $this->Model_IA->getIArec();   //pr($data['IArec']);
        $data['getPartyName'] = $this->Model_IA->getPartyName($diary_no);
        $data['result'] = $result;
        $data['diary_no'] = $diary_no;
        $data['dno_data'] = $_SESSION['filing_details']; 
        $data['act_main'] = $this->Model_IA->getActMain($diary_no);
        $data['docdetails'] = $this->Model_IA->get_docdetails($diary_no); 
        //$data['ia_res'] = $is_docdetails;
		//pr($data['docdetails']);

       return  $get_view_result= view('Judicial/IA_get_content',$data);
        //echo $get_view_result;exit();
        // echo "3@@@Diary No. or Case No. doesn't exist .";exit();
    }
	
	public function update_tot_det()
	{
		$data['$_REQUEST'] = $_REQUEST;
		return view('Judicial/update_tot_det',$data);
	}
	
	public function update_ia()
	{

        $request = \Config\Services::request();
		$ucode = session()->get('login')['usercode'];
		$sno='';

        // Get the necessary parameters from the request
        $diaryno = $request->getPost('diaryno');
        $hd_IANAme = $request->getPost('hd_IANAme');
        $hd_counts = $request->getPost('hd_counts');
        $hd_year = $request->getPost('hd_year');
        $ddlIASTAT = $request->getPost('ddlIASTAT');
        $m_doc1 = $request->getPost('m_doc1');
        $m_descss = $request->getPost('m_descss');
        $txtRematk = $request->getPost('txtRematk');
        $strtotal = $request->getPost('strtotal');
        $txt_order_dt = $request->getPost('txt_order_dt');

        // Retrieve the data from docdetails based on the conditions
        $batchData = $this->db->table('docdetails')
            ->select('diary_no, doccode, doccode1, docnum, docyear, filedby, docfee, other1, iastat, forresp, feemode, fee1, fee2, usercode, ent_dt, display, remark, lst_mdf, lst_user, j1, j2, j3, party, advocate_id, verified, verified_by, verified_on, sc_ia_sta_code, sc_ref_code_id, sc_application_no, no_of_copy, sc_old_doc_code, docd_id, verified_remarks, dispose_date, last_modified_by, disposal_remark, is_efiled, updated_by, updated_on, updated_by_ip')
            ->where('diary_no', $diaryno)
            ->where('doccode', '8')
            ->where('doccode1', $hd_IANAme)
            ->where('docnum', $hd_counts)
            ->where('docyear', $hd_year)
            ->get()
            ->getResultArray();

        if(!empty($batchData)) {
            // Insert the data into docdetails_history using insertBatch
            $this->db->table('docdetails_history')->insertBatch($batchData);
        }

        // If the status is 'D'
        if ($ddlIASTAT == 'D') {
            $data = [
                'doccode1' => $m_doc1,
                'other1' => $m_descss,
                'iastat' => $ddlIASTAT,
                'last_modified_by' => $ucode,
                'disposal_remark' => $txtRematk,
                'party' => $strtotal,
                'dispose_date' => $txt_order_dt,
                'lst_mdf' => date('Y-m-d H:i:s')
            ];

            $this->db->table('docdetails')
                ->set($data)
                ->where('diary_no', $diaryno)
                ->where('doccode', '8')
                ->where('doccode1', $hd_IANAme)
                ->where('docnum', $hd_counts)
                ->where('docyear', $hd_year)
                ->update();
        }

        if ($ddlIASTAT == 'P') {
            $data = [
                'iastat' => $ddlIASTAT,
                'last_modified_by' => null,
                'disposal_remark' => null,
                'dispose_date' => null,
                'lst_mdf' => date('Y-m-d H:i:s')
            ];

            $this->db->table('docdetails')
                ->set($data)
                ->where('diary_no', $diaryno)
                ->where('doccode', '8')
                ->where('doccode1', $hd_IANAme)
                ->where('docnum', $hd_counts)
                ->where('docyear', $hd_year)
                ->update();
        }

		if ($this->db->affectedRows() > 0) {
			$sno = 1; // Query succeeded
		} else {
            $sno = 0; // Query failed
		}
		echo $sno;
	}


}