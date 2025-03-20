<?php

namespace App\Controllers\Filing;
// use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\PartyModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;

class Party extends BaseController
{
    // protected $session;
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $PartyModel;
    public $diary_no;

     
	function __construct()
    {

        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->PartyModel = new PartyModel();

        if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
            // $getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
			$getUrl = $uri->getSegment(1).'-'.$uri->getSegment(2);
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

    public function index()
    {
        if (isset($_SESSION['filing_details'])) {
            return redirect()->to('Filing/Party/partyDetails');
        } else {
            if ($this->request->getMethod() === 'post' && $this->validate([
                'search_type' => ['label' => 'search Type', 'rules' => 'required|min_length[1]|max_length[1]'],
                'diary_number' => ['label' => 'Diary Number', 'rules' => 'required|min_length[1]|max_length[8]'],
                'diary_year' => ['label' => 'Diary Year', 'rules' => 'required|min_length[4]'],
            ])) {
                $search_type = $this->request->getPost('search_type');
                if ($search_type == 'D') {
                    $diary_number = $this->request->getPost('diary_number');
                    $diary_year = $this->request->getPost('diary_year');
                    $diary_no = $diary_number . $diary_year;
                    $get_main_table = $this->Dropdown_list_model->get_diary_details_by_diary_no($diary_no);
                } elseif ($search_type == 'C') {
                    $case_number = $this->request->getPost('case_number');
                    $case_year = $this->request->getPost('case_year');
                    session()->setFlashdata("message_error", 'Data not Fount');
                }

                if ($get_main_table) {
                    $this->session->set(array('filing_details' => $get_main_table));
                    return redirect()->to('Filing/Party/redirect_on_diary_user_type');
                    exit();
                } else {
                    session()->setFlashdata("message_error", 'Data not Fount');
                }
            }
            $data['casetype'] = get_from_table_json('casetype');
            $data['formAction'] = 'Filing/Party/index/';
            return view('Filing/diary_search_party', $data);
        }
    }

	function redirect_on_diary_user_type() {
        if(session()->get('login')) {
            return redirect()->to('Filing/Party/partyDetails');
        }else{
            session()->setFlashdata("message_error", 'Accessing permission denied contact to Computer Cell.');
        }
        return redirect()->to('Filing/Party/partyDetails');
    }

    public function partyDetails()
    {
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $diary_no = $_SESSION['filing_details']['diary_no'];

        $data['diary_no'] = $diary_no;
        $data['party_list'] = $this->PartyModel->getpartyList($diary_no);
        $data['copied_party_list'] = $this->PartyModel->getCopiedpartyList($diary_no);

        $data['lr_list'] = $this->PartyModel->getLRList($diary_no);
        $data['lowercase'] = $this->PartyModel->getlowercase($diary_no);
        $data['occ_list'] = $this->PartyModel->getoccupList();
        $data['edu_list'] = $this->PartyModel->geteduList();
        $data['country_list'] = $this->PartyModel->getcountryList();

        $data['get_only_state_name'] = $this->PartyModel->get_only_state_name();
        $data['get_only_post'] = $this->PartyModel->get_only_post();
        $data['get_petResCaseTitle'] = $this->PartyModel->get_petResCaseTitle($diary_no);
        $data['casetypeDetails'] = $this->PartyModel->casetypeDetails($diary_no);
        $data['casetypeDetail'] = $this->PartyModel->casetypeDetail($diary_no);
        $data['disp_party'] = $this->PartyModel->getDisposeParty($diary_no);

        return view('Filing/party_view', $data);
    }

    public function set_party_status()
    {
        if (!empty($_POST['data'])) {
            $dataArr = $_POST['data'];
            $data = $this->PartyModel->set_party_status($dataArr);
            echo $data;
        }
    }

    public function save_party_details()
    {
        if (!empty($_POST['data'])) {
            $dataArr = $_POST['data'];
            $data = $this->PartyModel->savepartyDetails($dataArr);
            echo $data;
        }
    }

    public function deleteAction()
    {
        $dataset = $_POST['data'];
        $data = $this->PartyModel->deleteAction($dataset);
        echo $data;
    }

    public function getUpdateData()
    {
		// pr(143);
        $dataset = $_POST['data'];
        $data = $this->PartyModel->getUpdateData($dataset);
        echo $data;
    }

    public function get_cause_title()
    {
        $dataset = $_POST['data'];
        $data = $this->PartyModel->get_cause_title($dataset);
        echo $data;
    }

    public function copy_party_details()
    {	
        $dataset = $this->request->getPost('data');
        $data = $this->PartyModel->copy_party_details($dataset);
        echo $data;
    }


    public function getDepttList()
    {
        $dataset = $_POST['deptt'];
        $data = $this->PartyModel->get_only_deptt($dataset);
        echo $data;
    }
	
	
	public function get_party_info()
	{
		$diary_no = $_REQUEST['diaryno'];
		$forparty = $_REQUEST['forparty'];

		$sql = "SELECT 
            auto_generated_id,
            pet_res,
            sr_no,
            partyname,
            remark_del,
            sr_no_show 
        FROM 
            party 
        WHERE 
            pflag IN ('P') 
            AND diary_no = $diary_no 
            AND pet_res = '$forparty'
        ORDER BY 
            pet_res,
            CAST(SPLIT_PART(sr_no_show, '.', 1) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 2) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 3) AS TEXT),
            CAST(SPLIT_PART(sr_no_show, '.', 4) AS TEXT)";

		$query = $this->db->query($sql); 
		$disp_party = $query->getResultArray($query);
		 
		$option = '';
		if (!empty($disp_party)) {		 
			foreach ($disp_party as $row) {	 		
				$option .=	'<option value="'.$row['auto_generated_id'].'">'.$row['pet_res'].$row['sr_no_show'].' - '.$row['partyname'].'</option>';				 
			}
		}
		return $option;	
	}

	
	public function dispose_selected_party()
	{
		
		 
		$party= $_REQUEST['partyid'];
		$partyid = explode('?',$party);
	 
		$diaryno = $_REQUEST['diaryno'];
		$dis_flag= $_REQUEST['dispby'];
		$resremark=$_REQUEST['restremark'];
		$forparty=$_REQUEST['forparty'];
		$spartyid='';

		//check existing cause title
		 
		$srl_no = $this->PartyModel->getSRNo($diaryno,$forparty);	 
		$srl_no = $srl_no['sr_no'];

		if($dis_flag=='O' || $dis_flag=='D') {

			for($i=0;$i<sizeof($partyid);$i++){
				if(sizeof($partyid)==1)
					$spartyid=$partyid[$i];
				else
					$spartyid=$spartyid.','.$partyid[$i];

			}

			$spartyid=trim($spartyid,',');
			$pet_res = '';
			$sr_no_show = '';
			$partyname = "";
			$ucode =  $_SESSION['login']['usercode'];
			 
			$query = $this->PartyModel->updateDispose($dis_flag,$ucode,$diaryno,$spartyid,$resremark);
			if (!$query)
				die('Error in query!!');
			else {
				echo "Party Disposed successfully!!! \n";

			}
		}
	  
		// update cause title

		 
		$partyname = $this->PartyModel->getSRNo($diaryno,$forparty);
		$party_name_new = $partyname['partyname'];
		$srl_no_new=$partyname['sr_no'];

		if ($forparty == 'P' && $srl_no!=$srl_no_new) {

			$causetitle = "update main set pet_name='$party_name_new',last_usercode='$ucode',last_dt=NOW() where diary_no= '$diaryno' and c_status='P' ";
			$querycausetitle = $this->db->query($causetitle);
			if (!$querycausetitle)
				die('Error in query!!');
			else
				echo "Cause title updated!!!! \n";
		}
		if ($forparty == 'R' && $srl_no!=$srl_no_new) {
			$causetitle = "update main set res_name='$party_name_new',last_usercode='$ucode',last_dt=NOW() where diary_no= '$diaryno' and c_status='P' ";
			$causetitle = $this->db->query($causetitle);
			if (!($causetitle))
				die('Error in query!!');
			
			echo "Cause title updated!!!! \n";
		}
	}
	
	
	public function restore_dispose_party()
	{
		$ucode =  $_SESSION['login']['usercode'];
		$partyid = $_REQUEST['partyid'];
		$diaryno = $_REQUEST['diaryno'];
		$resremark=' ; '.$_REQUEST['restremark'];

		$pet_res = '';
		$sr_no_show = '';
		$partyname = "";

		/* $disp_party = "select diary_no,pet_res,sr_no,partyname,remark_del,sr_no_show from party where auto_generated_id='$partyid' and diary_no='$diaryno'";
		$disp_party = mysql_query($disp_party) or die(__LINE__ . '->' . mysql_error());
		 */
		$disp_party = is_data_from_table('party', " auto_generated_id = $partyid and diary_no = $diaryno ", 'diary_no,pet_res,sr_no,partyname,remark_del,sr_no_show', $row = '');
		//pr($disp_party);
		if (!empty($disp_party)) {
			//$row = mysql_fetch_array($disp_party);

			$pet_res = $disp_party['pet_res'];
			$sr_no=$disp_party['sr_no'];
			$sr_no_show = $disp_party['sr_no_show'];
			$partyname = $disp_party['partyname'];
			if($sr_no!=$sr_no_show)
				$sr_no='';
		}

	//Restore disposed party

		$restore = "update party set pflag='P',last_usercode='$ucode',last_dt=NOW(),remark_del=concat(remark_del,'$resremark') where diary_no= '$diaryno' and auto_generated_id='$partyid' ";
		$restore = $this->db->query($restore);
		if (!($restore))
			die('Error in query!!');
		else {
			echo "Party restored successfully!!! \n";
			 
			$srl_no = $this->PartyModel->getSRNo($diaryno,$partyid);
			$srl_no = $srl_no['sr_no'] ?? '';


			if ($pet_res == 'P' && $sr_no == $srl_no) {

				$causetitle = "update main set pet_name='$partyname',last_usercode='$ucode',last_dt=NOW() where diary_no= '$diaryno' and c_status='P' ";
				$causetitle = $this->db->query($causetitle);
				if (!$causetitle)
					die('Error in query!!');
				else
					echo "Cause title updated!!!! \n";
			}
			if ($pet_res == 'R' && $sr_no_show == $srl_no) {
				 $causetitle = "update main set res_name='$partyname',last_usercode='$ucode',last_dt=NOW() where diary_no= '$diaryno' and c_status='P' ";
				$causetitle = $this->db->query($causetitle);
					if (!($causetitle))
						die('Error in query!!');
			
				echo "Cause title updated!!!! \n";
			}

		}
	}
	
	
	public function update_dispose_party()
	{
		$diary_no = $_SESSION['filing_details']['diary_no'];
        $data['diary_no'] = $diary_no;
		$data['casetype'] = $this->PartyModel->casetypeDetail($diary_no);
		$data['select_for_lrs_rs'] = $this->PartyModel->getLRList($diary_no);
		$data['lowercase'] = $this->PartyModel->getlowercase($diary_no);
		$data['p_pet_rs'] = $this->PartyModel->getPartyDetails($diary_no,'P');
		$data['p_res_rs'] = $this->PartyModel->getPartyDetails($diary_no,'R');
		$data['p_imp_rs'] = $this->PartyModel->getPartyDetails($diary_no,'I');
		$data['p_int_rs'] = $this->PartyModel->getPartyDetails($diary_no,'N');
		$data['result_p'] = $this->PartyModel->getPartyDetails_new($diary_no,'P');
		$data['result_r'] = $this->PartyModel->getPartyDetails_new($diary_no,'R');
		
		return view('Judicial/addentry/get_extraparty_mod', $data);
	}
	
	
	public function save_new_filing_extraparty()
	{
		// pr($_REQUEST);
		$ucode=  $_SESSION['login']['usercode'];
		//$p = $this->request->getGet('p_post');
		
		//$_REQUEST['p_post'] = htmlentities(mysql_real_escape_string($_GET['p_post']));
		/*$_REQUEST['p_deptt'] = htmlentities(mysql_real_escape_string($_GET['p_deptt']));
		$_REQUEST['p_add'] = htmlentities(mysql_real_escape_string($_GET['p_add']));
		$_REQUEST['p_city'] = htmlentities(mysql_real_escape_string($_GET['p_city']));
		$_REQUEST['p_name'] = htmlentities(mysql_real_escape_string($_GET['p_name']));
		$_REQUEST['p_rel_name'] = htmlentities(mysql_real_escape_string($_GET['p_rel_name']));
		$_REQUEST['p_occ'] = htmlentities(mysql_real_escape_string($_GET['p_occ']));
		$_REQUEST['p_caste'] = htmlentities(mysql_real_escape_string($_GET['p_caste']));
		$_REQUEST['p_edu'] $_GET['p_post']= htmlentities(mysql_real_escape_string($_GET['p_edu']));

		$_REQUEST['s_causetitle'] = htmlentities(mysql_real_escape_string($_GET['s_causetitle']));
		$_REQUEST['d_causetitle'] = htmlentities(mysql_real_escape_string($_GET['d_causetitle']));
		$_REQUEST['p_causetitle'] = htmlentities(mysql_real_escape_string($_GET['p_causetitle']));*/

		$_REQUEST['p_post'] = isset($_GET['p_post']) ? htmlentities($_GET['p_post']) : '';
		$_REQUEST['p_deptt'] = isset($_GET['p_post']) ? htmlentities($_GET['p_deptt']) : '';
		$_REQUEST['p_add'] = isset($_GET['p_post']) ? htmlentities($_GET['p_add']) : '';
		$_REQUEST['p_city'] = isset($_GET['p_post']) ? htmlentities($_GET['p_city']) : '';
		$_REQUEST['p_name'] = isset($_GET['p_name']) ? htmlentities($_GET['p_name']) : '';
		$_REQUEST['p_rel_name'] = isset($_GET['p_rel_name']) ? htmlentities($_GET['p_rel_name']) : '';
		$_REQUEST['p_occ'] = isset($_GET['p_occ']) ? htmlentities($_GET['p_occ']) : '';
		$_REQUEST['p_caste'] = isset($_GET['p_caste']) ? htmlentities($_GET['p_caste']) : '';
		$_REQUEST['p_edu'] = isset($_GET['p_edu']) ? htmlentities($_GET['p_edu']) : '';
		$_REQUEST['s_causetitle'] = isset($_GET['s_causetitle']) ? htmlentities($_GET['s_causetitle']) : '';
		$_REQUEST['d_causetitle'] = isset($_GET['d_causetitle']) ? htmlentities($_GET['d_causetitle']) : '';
		$_REQUEST['p_causetitle'] = isset($_GET['p_causetitle']) ? htmlentities($_GET['p_causetitle']) : '';
		 
		if($_REQUEST['controller']=='I')
		{
			echo '!~!';
			//$dno_yr = explode('~', $_REQUEST['fno']);
			$dno = $_REQUEST['fno'];
			//$dyr = $dno_yr[1];
			if(strpos($_REQUEST['p_no'], '.')>0){
				$party_no = explode('.', $_REQUEST['p_no']);
				$party_no = $party_no[0];
			}
			else{
				$party_no = $_REQUEST['p_no'];
			}

			//$chk_if_party = "SELECT diary_no FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag!='T'";
			//$chk_if_party = mysql_query($chk_if_party) or die(__LINE__.'->'.mysql_error());
			
			$chk_if_party = is_data_from_table('party', " diary_no = $dno AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag!='T' ", 'diary_no', $row = '');
			
			if(!empty($chk_if_party)){
				$update_before = "UPDATE party 
				SET sr_no_show=CONCAT(sr_no+1,SUBSTR(sr_no_show,LOCATE('.', sr_no_show))),sr_no=sr_no+1
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no>='$_REQUEST[p_no]' AND pflag!='T' ";
				 $this->db->query($update_before);
			}

			if($party_no==0){
				echo "ERROR, Party No. Can not be Zero";
				exit();
			}

			if($_REQUEST['p_no']==''){
				echo "ERROR, Party No. Can not be Zero";
				exit();
			}

			if($_REQUEST['p_type']!='I'){
			   
					{
						$partyname = '';
					if($_REQUEST['order1']=='S' &&  $_REQUEST['s_causetitle']=='true' )
					{
						$partyname .= "$_REQUEST[p_statename] "; 
					}
					else if($_REQUEST['order1']=='D' && $_REQUEST['d_causetitle']=='true'  )
					{$partyname .= "$_REQUEST[p_deptt] "; }
					else if($_REQUEST['order1']=='P' && $_REQUEST['p_causetitle']=='true' )
					{$partyname .= "$_REQUEST[p_post] "; }

					if($_REQUEST['order2']=='S' && $_REQUEST['s_causetitle']=='true' )
					{$partyname .= "$_REQUEST[p_statename] "; }
					else if($_REQUEST['order2']=='D'  && $_REQUEST['d_causetitle']=='true'  )
					{$partyname .= "$_REQUEST[p_deptt] ";}
					else if($_REQUEST['order2']=='P' &&  $_REQUEST['p_causetitle']=='true'  )
					{ $partyname .= "$_REQUEST[p_post] "; }

					if($_REQUEST['order3']=='S'  &&  $_REQUEST['s_causetitle']=='true' )
					{  $partyname .= "$_REQUEST[p_statename] "; }
					else if($_REQUEST['order3']=='D' &&  $_REQUEST['d_causetitle']=='true'  )
					{   $partyname .= "$_REQUEST[p_deptt] ";}
					else if($_REQUEST['order3']=='P' && $_REQUEST['p_causetitle']=='true' )
					{   $partyname .= "$_REQUEST[p_post] ";}


					if($_REQUEST['s_causetitle']=='false' && $_REQUEST['d_causetitle']=='false' && $_REQUEST['p_causetitle']=='false')
					{
						if ($_REQUEST['order1'] == 'S')
							$partyname .= "$_REQUEST[p_statename] ";
						else if ($_REQUEST['order1'] == 'D')
							$partyname .= "$_REQUEST[p_deptt] ";
						else if ($_REQUEST['order1'] == 'P')
							$partyname .= "$_REQUEST[p_post] ";

						if ($_REQUEST['order2'] == 'S')
							$partyname .= "$_REQUEST[p_statename] ";
						else if ($_REQUEST['order2'] == 'D')
							$partyname .= "$_REQUEST[p_deptt] ";
						else if ($_REQUEST['order2'] == 'P')
							$partyname .= "$_REQUEST[p_post] ";

						if ($_REQUEST['order3'] == 'S')
							$partyname .= "$_REQUEST[p_statename] ";
						else if ($_REQUEST['order3'] == 'D')
							$partyname .= "$_REQUEST[p_deptt] ";
						else if ($_REQUEST['order3'] == 'P')
							$partyname .= "$_REQUEST[p_post] ";
					}
					$partyname = "UPPER(TRIM('$partyname'))";
				}
				/* $chk_order = "SELECT * FROM party_order WHERE USER=$ucode AND o1='$_REQUEST[order1]' 
					AND o2='$_REQUEST[order2]' AND o3='$_REQUEST[order3]' AND display='Y'"; */
				//$chk_order = mysql_query($chk_order) or die(__LINE__.'->'.mysql_error());
				$chk_order = is_data_from_table('party_order', " USER=$ucode AND o1='$_REQUEST[order1]' 
					AND o2='$_REQUEST[order2]' AND o3='$_REQUEST[order3]' AND display='Y' ", '*', $row = 'N');
				
				if($chk_order == 0){
					$update_order = "UPDATE party_order SET display='N' WHERE user=$ucode AND display='Y'";
					//mysql_query($update_order) or die(__LINE__.'->'.mysql_error());
					$this->db->query($update_order);

					$insert_order="INSERT INTO party_order(user,o1,o2,o3,ent_dt) VALUES($ucode,'$_REQUEST[order1]','$_REQUEST[order2]','$_REQUEST[order3]',NOW())";
					//mysql_query($insert_order) or die(__LINE__.'->'.mysql_error());
					$this->db->query($insert_order);
				}
			}



			if($_REQUEST['p_type']=='I') {
				$is_masked = '';
				$partyname;
				if($_REQUEST['p_masked_name']!=''){
					$is_masked='Y';
					$partyname=$_REQUEST['p_masked_name'];
					$partyname = "UPPER(TRIM('$partyname'))";
				}
				else{
					$partyname=$_REQUEST['p_name'];
					$partyname = "UPPER(TRIM('$partyname'))";
				}

					$insert = "INSERT INTO party(pet_res,sr_no,sr_no_show,ind_dep,partyname,partysuff,sonof,prfhname,age,sex,caste,addr1,education,addr2,dstname,state,city,pin,country,
					email,contact,usercode,ent_dt,diary_no,occ_code,edu_code,remark_lrs,cont_pro_info,is_masked) 
					VALUES ('$_REQUEST[p_f]','$party_no','$_REQUEST[p_no]','$_REQUEST[p_type]',$partyname, $partyname,'$_REQUEST[p_rel]',UPPER(TRIM('$_REQUEST[p_rel_name]')),
					'$_REQUEST[p_age]','$_REQUEST[p_sex]',UPPER(TRIM('$_REQUEST[p_caste]')),UPPER(TRIM('$_REQUEST[p_occ]')),UPPER(TRIM('$_REQUEST[p_edu]')),
					UPPER(TRIM('$_REQUEST[p_add]')),UPPER(TRIM('$_REQUEST[p_city]')),'$_REQUEST[p_st]','$_REQUEST[p_dis]','$_REQUEST[p_pin]','$_REQUEST[p_cont]',
					'$_REQUEST[p_email]','$_REQUEST[p_mob]','$ucode',NOW(),'$dno','$_REQUEST[p_occ_code]','$_REQUEST[p_edu_code]','$_REQUEST[remark_lrs]','$_REQUEST[cont_pro_info]','$is_masked')";
					// pr($insert);
					$this->db->query($insert);
			}
			else if($_REQUEST['p_type']!='I')
				$insert = "INSERT INTO party(pet_res,sr_no,sr_no_show,ind_dep,partyname,partysuff,addr1,addr2,dstname,state,city,pin,country,email,contact,usercode,ent_dt,
					authcode,deptcode,diary_no,state_in_name,remark_lrs,cont_pro_info) 
				VALUES ('$_REQUEST[p_f]','$party_no','$_REQUEST[p_no]','$_REQUEST[p_type]',$partyname,UPPER(TRIM('$_REQUEST[p_statename]".' '."$_REQUEST[p_deptt]')),'$_REQUEST[p_post]',
				UPPER(TRIM('$_REQUEST[p_add]')),UPPER(TRIM('$_REQUEST[p_city]')),'$_REQUEST[p_st]','$_REQUEST[p_dis]','$_REQUEST[p_pin]','$_REQUEST[p_cont]',
				'$_REQUEST[p_email]','$_REQUEST[p_mob]','$ucode',NOW(),'$_REQUEST[p_code]','$_REQUEST[d_code]',
				'$dno','$_REQUEST[p_statename_hd]','$_REQUEST[remark_lrs]','$_REQUEST[cont_pro_info]')";
				$insert = $this->db->query($insert);
			//commented on 15-05-2024
			 
			if($insert){
				if($_REQUEST['p_masked_name']!=''){
					/* $srno_qr = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$srno_qr_rs = mysql_query($srno_qr) or die(__LINE__.'->'.mysql_error());
					$srno = mysql_result($srno_qr_rs,0); */
					
					$srno_qr_rs = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$srno = $srno_qr_rs['auto_generated_id'];
					
					$encrypt_partyname=encrypt_partyname($_REQUEST['p_name']);

					$masked_party_qr = "INSERT INTO masked_party_info(diary_no,party_id,party_name,masked_name,ent_by,ent_time) 
				VALUES('$dno', '$srno' , '$encrypt_partyname' , UPPER(TRIM('$_REQUEST[p_masked_name]')),'$ucode',now())";
					//mysql_query($masked_party_qr) or die(__LINE__.'->'.mysql_error());
					$this->db->query($masked_party_qr);

				}

				echo "Party Added Successfully";

			}


			if($_REQUEST['add_add']!='' || ($_REQUEST['lowercase']!='' && $_REQUEST['lowercase']!= NULL)){
				/* $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
				$sel_u_no = mysql_query($sel_u_no) or die(__LINE__.'->'.mysql_error());
				$sel_u_no = mysql_result($sel_u_no,0); */
				
				$sel_u_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
				$sel_u_no = $sel_u_no['auto_generated_id'];
				
				if($_REQUEST['add_add']!=''){
					$_REQUEST['add_add'] = ltrim($_REQUEST['add_add'], '^');
					$add_add = explode('^',$_REQUEST['add_add']);

					for($i=0;$i<sizeof($add_add);$i++){
						$addresses = explode("~", $add_add[$i]);
						$insert = "INSERT INTO party_additional_address(party_id,country,state,district,address) VALUES($sel_u_no,'$addresses[1]','$addresses[2]','$addresses[3]','$addresses[0]')";
						//mysql_query($insert) or die(__LINE__.'->'.mysql_error());
						$this->db->query($insert);
					}
				}
				//echo var_dump($_REQUEST['lowercase']);
				//echo "lowercourts=".$_REQUEST['lowercase'];
				if($_REQUEST['lowercase']!='' && $_REQUEST['lowercase']!= NULL){
					$lowercourts = explode(',', $_REQUEST['lowercase']);
					for($i=0;$i<sizeof($lowercourts);$i++){
						if($lowercourts[$i]=='')
							continue;

						$query_lower = "INSERT INTO party_lowercourt(party_id,lowercase_id) VALUES($sel_u_no,$lowercourts[$i])";
						//mysql_query($query_lower) or die(__LINE__.'->'.mysql_error());
						$this->db->query($query_lower);
					}
				}
			}


			 
		}
		else if($_REQUEST['controller']=='U')
		{
			// pr($_REQUEST);
			$dno = $_REQUEST['fno'];	
			$is_masked='';
			$partyname='';
			if($_REQUEST['p_sta'] == 'O' || $_REQUEST['p_sta'] == 'D'  || $_REQUEST['p_sta'] == 'T') {
				/* $no_of_party = "select * from party where diary_no = '$dno' and pflag='P' and '$_REQUEST[p_f]' not in('I','N') and pet_res='$_REQUEST[p_f]'";

				$no_of_party = mysql_query($no_of_party) or die(__LINE__ . '->' . mysql_error()); */
				
				$no_of_party = is_data_from_table('party', " diary_no = '$dno' and pflag='P' and '$_REQUEST[p_f]' not in('I','N') and pet_res='$_REQUEST[p_f]' ", 'auto_generated_id', $row = 'N');
				
				if ($no_of_party ==1) {
					echo "1";
					exit();
				}
			}
			echo '!~!';
		 
			//$dyr = $dno_yr[1];
			/* $srl_no = "SELECT sr_no FROM party WHERE diary_no = '$dno' AND pet_res='$_REQUEST[hd_p_f]' and pflag='P' order by sr_no,sr_no_show limit 1";
			$srl_no = mysql_query($srl_no) or die(__LINE__.'->'.mysql_error());
			$srl_no = mysql_fetch_array($srl_no); */
			
			$srl_no = is_data_from_table('party', " diary_no = '$dno' AND pet_res='$_REQUEST[hd_p_f]' and pflag='P' order by sr_no,sr_no_show limit 1 ", 'sr_no', $row = '');
			
			$srl_no = isset($srl_no['sr_no']) ? $srl_no['sr_no'] : '';

			if($_REQUEST['hd_p_f']==$_REQUEST['p_f'])
			{
				$set_remark_delete = '';

				if($_REQUEST['p_sta'] == 'O' || $_REQUEST['p_sta'] == 'D')
					$set_remark_delete = " ,remark_del=if(remark_del='' or remark_del is null,'$_REQUEST[remark_del]',concat(remark_del,concat(';','$_REQUEST[remark_del]')) )";

				if($_REQUEST['p_type']!='I'){


				    
						if($_REQUEST['order1']=='S' &&  $_REQUEST['s_causetitle']=='true' )
						{$partyname .= "$_REQUEST[p_statename] ";}
						else if($_REQUEST['order1']=='D' && $_REQUEST['d_causetitle']=='true'  )
						{$partyname .= "$_REQUEST[p_deptt] "; }
						else if($_REQUEST['order1']=='P' && $_REQUEST['p_causetitle']=='true' )
						{$partyname .= "$_REQUEST[p_post] "; }

						if($_REQUEST['order2']=='S' && $_REQUEST['s_causetitle']=='true' )
						{$partyname .= "$_REQUEST[p_statename] "; }
						else if($_REQUEST['order2']=='D'  && $_REQUEST['d_causetitle']=='true'  )
						{$partyname .= "$_REQUEST[p_deptt] "; }
						else if($_REQUEST['order2']=='P' &&  $_REQUEST['p_causetitle']=='true'  )
						{ $partyname .= "$_REQUEST[p_post] "; }

						if($_REQUEST['order3']=='S'  &&  $_REQUEST['s_causetitle']=='true' )
						{  $partyname .= "$_REQUEST[p_statename] "; }
						else if($_REQUEST['order3']=='D' &&  $_REQUEST['d_causetitle']=='true'  )
						{   $partyname .= "$_REQUEST[p_deptt] "; }
						else if($_REQUEST['order3']=='P' && $_REQUEST['p_causetitle']=='true' )
						{   $partyname .= "$_REQUEST[p_post] "; }


					if($_REQUEST['s_causetitle']=='false' && $_REQUEST['d_causetitle']=='false' && $_REQUEST['p_causetitle']=='false' )
					{
					   
						$partyname =$_REQUEST['party_name'];
					}
					if( $_REQUEST['p_type']=='D3' && $_REQUEST['d_causetitle']=='false' && $_REQUEST['p_causetitle']=='false' ) {
						$partyname = $_REQUEST['party_name'];
					}
					$partyname = "UPPER(TRIM('$partyname'))";


				   }
					/* $chk_order = "SELECT * FROM party_order WHERE USER=$ucode AND o1='$_REQUEST[order1]' 
						AND o2='$_REQUEST[order2]' AND o3='$_REQUEST[order3]' AND display='Y'";
					$chk_order = mysql_query($chk_order) or die(__LINE__.'->'.mysql_error()); */
					
					$chk_order = is_data_from_table('party_order', " user=$ucode  AND o1='$_REQUEST[order1]' 
						AND o2='$_REQUEST[order2]' AND o3='$_REQUEST[order3]' AND display='Y' ", '*', $row = 'N');
					if($chk_order == 0){
						$update_order = "UPDATE party_order SET display='N' WHERE user=$ucode AND display='Y'";
						//mysql_query($update_order) or die(__LINE__.'->'.mysql_error());
						$this->db->query($update_order);

						$insert_order="INSERT INTO party_order(user,o1,o2,o3,ent_dt) VALUES($ucode,'$_REQUEST[order1]','$_REQUEST[order2]','$_REQUEST[order3]',NOW())";
						//mysql_query($insert_order) or die(__LINE__.'->'.mysql_error());
						$this->db->query($insert_order);
					}


				if($_REQUEST['p_type']=='I') {

					if($_REQUEST['p_masked_name']!=''){
						$is_masked=", is_masked='Y'";
						$partyname=$_REQUEST['p_masked_name'];
					}
					else{
						$is_masked=", is_masked='N'";
						$partyname=$_REQUEST['p_name'];
					}

					$update = "UPDATE party SET pet_res='$_REQUEST[p_f]',ind_dep='$_REQUEST[p_type]',partyname=UPPER(TRIM('$partyname')),
					partysuff=UPPER(TRIM('$partyname')),sonof='$_REQUEST[p_rel]',prfhname=UPPER(TRIM('$_REQUEST[p_rel_name]')),age='$_REQUEST[p_age]',
					sex='$_REQUEST[p_sex]',caste=UPPER(TRIM('$_REQUEST[p_caste]')),addr1=UPPER(TRIM('$_REQUEST[p_occ]')),education=UPPER(TRIM('$_REQUEST[p_edu]')),addr2=UPPER(TRIM('$_REQUEST[p_add]')),
					dstname=UPPER(TRIM('$_REQUEST[p_city]')),state='$_REQUEST[p_st]',city='$_REQUEST[p_dis]',pin='$_REQUEST[p_pin]',country='$_REQUEST[p_cont]',lowercase_id='$_REQUEST[lowercase]',
					email='$_REQUEST[p_email]',contact='$_REQUEST[p_mob]',usercode='$ucode',pflag='$_REQUEST[p_sta]',occ_code='$_REQUEST[p_occ_code]',edu_code='$_REQUEST[p_edu_code]',
					remark_lrs='$_REQUEST[remark_lrs]',cont_pro_info='$_REQUEST[cont_pro_info]',last_usercode='$ucode',last_dt=NOW() $set_remark_delete $is_masked
					WHERE diary_no = '$dno' AND pet_res='$_REQUEST[hd_p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					//$this->db->query($update);

					/* $srno_qr = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[hd_p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$srno_qr_rs = mysql_query($srno_qr) or die(__LINE__.'->'.mysql_error());
					$srno = mysql_result($srno_qr_rs,0); */
					
					$srno_qr_rs = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[hd_p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$srno = $srno_qr_rs['auto_generated_id'];
					if($_REQUEST['p_masked_name']!=''){
						$encrypt_partyname=encrypt_partyname($_REQUEST['p_name']);


						/* $ifmasked_qr = "SELECT * FROM masked_party_info WHERE diary_no='$dno' AND party_id='$srno' AND display='Y'";
						$ifmasked_rs = mysql_query($ifmasked_qr) or die(__LINE__.'->'.mysql_error()); */
						
						$ifmasked_rs = is_data_from_table('masked_party_info', " diary_no='$dno' AND party_id='$srno' AND display='Y' ", '*', $row = 'N');
						
						if($ifmasked_rs > 0){
								$update_mp="Update masked_party_info set display='N', upd_by='$ucode', upd_time=now() where diary_no='$dno' AND party_id='$srno' AND display='Y'";
								//mysql_query($update_mp) or die(__LINE__.'->'.mysql_error());
								$this->db->query($update_mp);
								
								$masked_party_qr = "INSERT INTO masked_party_info(diary_no,party_id,party_name,masked_name,ent_by,ent_time) 
							  VALUES('$dno', '$srno' , '$encrypt_partyname', UPPER(TRIM('$_REQUEST[p_masked_name]')),'$ucode',now())";
							//mysql_query($masked_party_qr) or die(__LINE__.'->'.mysql_error());
							$this->db->query($masked_party_qr);
						}
						else{
							  $masked_party_qr = "INSERT INTO masked_party_info(diary_no,party_id,party_name,masked_name,ent_by,ent_time) 
							  VALUES('$dno', '$srno' ,'$encrypt_partyname', UPPER(TRIM('$_REQUEST[p_masked_name]')),'$ucode',now())";
							//mysql_query($masked_party_qr) or die(__LINE__.'->'.mysql_error());
							$this->db->query($masked_party_qr);
						}
					}
					else{
						/* $ifmasked_qr = "SELECT * FROM masked_party_info WHERE diary_no='$dno' AND party_id='$srno' AND display='Y'";
						$ifmasked_rs = mysql_query($ifmasked_qr) or die(__LINE__.'->'.mysql_error()); */
						
						$ifmasked_rs = is_data_from_table('masked_party_info', " diary_no='$dno' AND party_id='$srno' AND display='Y' ", '*', $row = 'N');
						if($ifmasked_rs > 0){
							$update_mp="Update masked_party_info set display='N', upd_by='$ucode', upd_time=now() where diary_no='$dno' AND party_id='$srno' AND display='Y'";
							//mysql_query($update_mp) or die(__LINE__.'->'.mysql_error());
							$this->db->query($update_mp);
						}
					}

				}
				else if($_REQUEST['p_type']!='I') {
					$lowercourts_id =  explode(',',$_REQUEST['lowercase']);
					$update = "UPDATE party SET pet_res='$_REQUEST[p_f]', ind_dep='$_REQUEST[p_type]',partyname=$partyname,partysuff=UPPER(TRIM('$_REQUEST[p_statename]" . ' ' . "$_REQUEST[p_deptt]')),
					addr1=upper(trim('$_REQUEST[p_post]')),addr2=UPPER(TRIM('$_REQUEST[p_add]')),dstname=UPPER(TRIM('$_REQUEST[p_city]')),state='$_REQUEST[p_st]',city='$_REQUEST[p_dis]',pin='$_REQUEST[p_pin]',country='$_REQUEST[p_cont]',lowercase_id='$lowercourts_id[0]',
					email='$_REQUEST[p_email]',contact='$_REQUEST[p_mob]',usercode='$ucode',ent_dt=NOW(),authcode='$_REQUEST[p_code]',deptcode='$_REQUEST[d_code]',pflag='$_REQUEST[p_sta]',state_in_name='$_REQUEST[p_statename_hd]',
					remark_lrs='$_REQUEST[remark_lrs]',cont_pro_info='$_REQUEST[cont_pro_info]',last_usercode='$ucode',last_dt=NOW() $set_remark_delete
					where diary_no = '$dno' AND pet_res='$_REQUEST[hd_p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
				}
				if($_REQUEST['p_sta']!='P')
				{
					if(strpos($_REQUEST['p_no'], '.')>0){
						$party_no = explode('.', $_REQUEST['p_no']);
						$party_no = $party_no[0];
					}
					else{
						$party_no = $_REQUEST['p_no'];
					}

					/* $c_a_r = "SELECT adv FROM advocate WHERE diary_no = '$dno' AND pet_res='$_REQUEST[p_f]' AND pet_res_no='$party_no' AND display='Y'";
					$c_a_r = mysql_query($c_a_r) or die(__LINE__.'->'.mysql_error()); //or die(mysql_error());
					 */
					$c_a_r = is_data_from_table('advocate', " diary_no = '$dno' AND pet_res='$_REQUEST[p_f]' AND pet_res_no='$party_no' AND display='Y' ", 'adv', $row = 'N');
					
					if($c_a_r > 0)
					{
						$update_adv = "UPDATE advocate SET display='N',usercode='$ucode',ent_dt=NOW() WHERE diary_no = '$dno' AND pet_res='$_REQUEST[p_f]' AND pet_res_no='$party_no' AND display='Y'";
						//mysql_query($update_adv) or die(__LINE__.'->'.mysql_error());
						$this->db->query($update_adv);
					}
				}
				 

				if($_REQUEST['add_add']!=''){
					//echo "inside add_add";
					/* $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$sel_u_no = mysql_query($sel_u_no) or die(__LINE__.'->'.mysql_error());
					$sel_u_no = mysql_result($sel_u_no,0); */
					
					$sel_u_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$sel_u_no = $sel_u_no['auto_generated_id'];
					$_REQUEST['add_add'] = ltrim($_REQUEST['add_add'], '^');
					$add_add = explode('^',$_REQUEST['add_add']);
					$update_add_aadd = "UPDATE party_additional_address SET display='N' WHERE party_id=$sel_u_no";
					//mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
					$this->db->query($update_add_aadd);
					
					for($i=0;$i<sizeof($add_add);$i++){
						$addresses = explode("~", $add_add[$i]);
						$insert = "INSERT INTO party_additional_address(party_id,country,state,district,address,display) VALUES($sel_u_no,'$addresses[1]','$addresses[2]','$addresses[3]','$addresses[0]','Y')";
						//mysql_query($insert) or die(__LINE__.'->'.mysql_error());
						$this->db->query($insert);
					}
				}
				else if($_REQUEST['add_add']==''){
					//echo "inside add_add deny";
					/* $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$sel_u_no = mysql_query($sel_u_no) or die(__LINE__.'->'.mysql_error());
					$sel_u_no = mysql_result($sel_u_no,0); */
					
					$sel_u_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$sel_u_no = $sel_u_no['auto_generated_id'];
					
					$update_add_aadd = "UPDATE party_additional_address SET display='N' WHERE party_id=$sel_u_no";
					//mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
					$this->db->query($update_add_aadd);
				}

				if($_REQUEST['lowercase']!='' && $_REQUEST['lowercase']!= NULL){

					/* $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$sel_u_no = mysql_query($sel_u_no) or die(__LINE__.'->'.mysql_error());
					$sel_u_no = mysql_result($sel_u_no,0); */
					
					$sel_u_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$sel_u_no = $sel_u_no['auto_generated_id'];

					$update_add_aadd = "UPDATE party_lowercourt SET display='N' WHERE party_id=$sel_u_no";
					//mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
					$this->db->query($update_add_aadd);

					$lowercourts = explode(',', $_REQUEST['lowercase']);
					for($i=0;$i<sizeof($lowercourts);$i++){
						if($lowercourts[$i]=='')
							continue;

						$query_lower = "INSERT INTO party_lowercourt(party_id,lowercase_id) VALUES($sel_u_no,$lowercourts[$i])";
						//mysql_query($query_lower) or die(__LINE__.'->'.mysql_error());
						$this->db->query($query_lower);
					}
				}
				else if($_REQUEST['lowercase']=='' || $_REQUEST['lowercase']== NULL){

					/* $sel_u_no = "SELECT auto_generated_id FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P'";
					$sel_u_no = mysql_query($sel_u_no) or die(__LINE__.'->'.mysql_error());
					$sel_u_no = mysql_result($sel_u_no,0); */
					
					$sel_u_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show='$_REQUEST[p_no]' AND pflag='P' ", 'auto_generated_id', $row = '');
					$sel_u_no = $sel_u_no['auto_generated_id'];
					
					$update_add_aadd = "UPDATE party_lowercourt SET display='N' WHERE party_id=$sel_u_no";
					//mysql_query($update_add_aadd) or die(__LINE__.'->'.mysql_error());
					$this->db->query($update_add_aadd);
				}

				 
			}
			else
			{
				
				 $update = "UPDATE party SET pflag='T',last_usercode='$ucode',last_dt=NOW() WHERE diary_no = '$dno' AND pet_res='$_REQUEST[hd_p_f]' AND sr_no_show='$_REQUEST[p_no]'";
				// pr($update);
				$this->db->query($update);
				/* $sql_no = "SELECT MAX(sr_no) no FROM party WHERE diary_no = '$dno' AND pet_res='$_REQUEST[p_f]' ";
				$sql_no = mysql_query($sql_no) or die(__LINE__.'->'.mysql_error());
				$sql_no = mysql_fetch_array($sql_no); */
				
				$sql_no = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' ", 'MAX(sr_no) no', $row = '');
				//$sql_no = $sel_u_no['auto_generated_id'];
				$p_no = $sql_no['no']+1;

				 
			}
			 

			$updatere = $this->db->query($update);
			// pr(866);
			if(!$updatere)
				die('Error in query!!');
			else
				echo "Party Modified Successfully";

 
				if($_REQUEST['hd_p_f']=='P' &&  $_REQUEST['p_no']==$srl_no  ) {
					// pr(874);
					if($_REQUEST['p_type']=='I')
					   $name="UPPER(TRIM('$partyname'))";
					if($_REQUEST['p_type']!='I')
						 $name=$partyname;

					$causetitle = "update main set pet_name=$name where diary_no= '$dno' and c_status='P' ";
					$this->db->query($causetitle);
					/* if(!mysql_query($causetitle))
						die(__LINE__.'->'.mysql_error()); */
				}
				if($_REQUEST['hd_p_f']=='R' &&  $_REQUEST['p_no']==$srl_no ) {
					// pr(866);
					if($_REQUEST['p_type']=='I')
						 $name="UPPER(TRIM('$partyname'))";
					if($_REQUEST['p_type']!='I')
						$name=$partyname;
						$causetitle = "update main set res_name=$name where diary_no= '$dno' and c_status='P' ";
						$this->db->query($causetitle);
					/* if(!mysql_query($causetitle))
						die(__LINE__.'->'.mysql_error()); */
				}





			if($_REQUEST['p_sta']!='P' &&  $_REQUEST['p_no']==$srl_no ){
				
			   // if($_REQUEST['p_no']=='1'){
					/* $chk_main = "SELECT partyname FROM party WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND pflag='P' ORDER BY sr_no,CAST(sr_no_show AS UNSIGNED)";
					$chk_main = mysql_query($chk_main) or die(__LINE__.'->'.mysql_error()); */
					
					$chk_main = is_data_from_table('party', " diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND pflag='P' ORDER BY sr_no,CAST(sr_no_show AS UNSIGNED) ", 'partyname', $row = '');
					
					if(!empty($chk_main)){
						//$chk_main = mysql_result($chk_main,0);
						if($_REQUEST['p_f']=='P'){
							$update_main = "UPDATE main SET pet_name='$chk_main',last_usercode=$ucode,last_dt=NOW() WHERE diary_no='$dno'";
							//mysql_query($update_main) or die(__LINE__.'->'.mysql_error());
							$this->db->query($update_main);
						}
						else if($_REQUEST['p_f']=='R'){
							$update_main = "UPDATE main SET res_name='$chk_main',last_usercode=$ucode,last_dt=NOW() WHERE diary_no='$dno'";
							//mysql_query($update_main) or die(__LINE__.'->'.mysql_error());
							$this->db->query($update_main);
						}
						echo "<br>CAUSE-TITLE UPDATED";
					}
			   // }
			}
			 $countdot=substr_count($_REQUEST['p_no'], '.');
		//    echo " no of dots are ".$countdot."  ";

			if( strpos( $_REQUEST['p_no'], '.' ) == true &&  ($_REQUEST['p_sta']=='T') && $countdot==1) {
				
		//        echo "LR  ".$_REQUEST[p_no]."   ";
				 $update_further = "UPDATE party   
				 SET sr_no_show= concat(SUBSTRING_INDEX(sr_no_show, '.', 1),'.',SUBSTRING_INDEX(sr_no_show, '.', -1)-1),last_usercode='$ucode',last_dt=NOW()
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show>'$_REQUEST[p_no]' and sr_no=SUBSTRING_INDEX('$_REQUEST[p_no]', '.', 1) AND pflag not in('T','Z') ";
				//mysql_query($update_further) or die(__LINE__.'->'.mysql_error());
				$this->db->query($update_further);
			}
			if( strpos( $_REQUEST['p_no'], '.' ) == true &&  ($_REQUEST['p_sta']=='T') && $countdot==2) {
				
			   // echo "LR  ".$_REQUEST[p_no]."   ";
				$update_further = "UPDATE party 
				SET sr_no_show= concat(SUBSTRING_INDEX(sr_no_show, '.', 2),'.',SUBSTRING_INDEX(sr_no_show, '.', -1)-1),last_usercode='$ucode',last_dt=NOW()
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show>'$_REQUEST[p_no]' and sr_no= SUBSTRING_INDEX('$_REQUEST[p_no]', '.', 1)  AND pflag not in('T','Z') ";
				//mysql_query($update_further) or die(__LINE__.'->'.mysql_error());
				$this->db->query($update_further);
			}
			if( strpos( $_REQUEST['p_no'], '.' ) == true &&  ($_REQUEST['p_sta']=='T') && $countdot==3) {
				
				//echo "LR  ".$_REQUEST[p_no]."   ";
				$update_further = "UPDATE party 
				SET sr_no_show= concat(SUBSTRING_INDEX(sr_no_show, '.', 3),'.',SUBSTRING_INDEX(sr_no_show, '.', -1)-1),last_usercode='$ucode',last_dt=NOW()
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show>'$_REQUEST[p_no]' and sr_no= SUBSTRING_INDEX('$_REQUEST[p_no]', '.', 1) AND pflag not in('T','Z') ";
				//mysql_query($update_further) or die(__LINE__.'->'.mysql_error());
				$this->db->query($update_further);
			}
			if( strpos( $_REQUEST['p_no'], '.' ) == true &&  ($_REQUEST['p_sta']=='T') && $countdot==4) {
				
			   // echo "LR  ".$_REQUEST[p_no]."   ";
				$update_further = "UPDATE party 
				SET sr_no_show= concat(SUBSTRING_INDEX(sr_no_show, '.', 4),'.',SUBSTRING_INDEX(sr_no_show, '.', -1)-1),last_usercode='$ucode',last_dt=NOW()
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no_show>'$_REQUEST[p_no]' and sr_no= SUBSTRING_INDEX('$_REQUEST[p_no]', '.', 1) AND pflag not in('T','Z') ";
				//mysql_query($update_further) or die(__LINE__.'->'.mysql_error());
				$this->db->query($update_further);
			}

			if(strpos( $_REQUEST['p_no'], '.' ) == false && $_REQUEST['p_sta']=='T'){
				
				$update_further = "UPDATE party 
				SET sr_no_show=CONCAT(sr_no-1,SUBSTR(sr_no_show,LOCATE('.', sr_no_show))),sr_no=sr_no-1,last_usercode='$ucode',last_dt=NOW()
				WHERE diary_no='$dno' AND pet_res='$_REQUEST[p_f]' AND sr_no>'$_REQUEST[p_no]' AND pflag not in('T','Z') ";
				//mysql_query($update_further) or die(__LINE__.'->'.mysql_error());
				$this->db->query($update_further);
			}


				//starts here
			if($_REQUEST['p_no']==1)
			{
				
				/* $check_if_fil_user = "SELECT count(*) FROM fil_trap_users a WHERE a.usertype=101 AND a.display='Y'  and usercode='$ucode' ";
				$check_if_fil_user_rs = mysql_query($check_if_fil_user) or die(LINE . '->' . mysql_error()); */
				
				//$if_fil_user = is_data_from_table('fil_trap_users', " a.usertype=101 AND a.display='Y'  and usercode='ucode' ", '*', $row = 'N');
				$if_fil_user = is_data_from_table('fil_trap_users', " usertype=101 AND display='Y'  and usercode=$ucode ", '*', $row = 'N');
				// pr($if_fil_user);
				//$if_fil_user = mysql_result($check_if_fil_user_rs, 0);
				if ($if_fil_user != 0) {
					// pr(987);
					/* $scefm_qr = "select e.diary_no ,t.diary_no, t.party_update_by from efiled_cases e left join efiled_cases_transfer_status t on e.diary_no=t.diary_no where e.diary_no='$dno' ";
					$scefm_rs = mysql_query($scefm_qr) or die("Error: " . __LINE__ . mysql_error());
					$is_scefm = mysql_fetch_array($scefm_rs); */
					
					$is_scefm = $this->PartyModel->getEfiledCases($dno);
					if (!empty($is_scefm) && ($is_scefm['diary_no'] != 0 and $is_scefm['diary_no'] != null)) {
						if ($is_scefm['ects_diary_no'] != 0 and $is_scefm['ects_diary_no'] != null) {
							if ($is_scefm['party_update_by'] == null or $is_scefm['party_update_by'] == '') {
								 $scefm_up_qr = "Update efiled_cases_transfer_status set 
									party_update_by='$ucode',party_update_on=now() where  diary_no='$dno'";
								/* if (!mysql_query($scefm_up_qr)) {
									die("Error: " . __LINE__ . mysql_error());
								} */
								$this->db->query($scefm_up_qr);
							}
						} else {
							 $scefm_in_qr = "Insert Into efiled_cases_transfer_status(diary_no,party_update_by,party_update_on)
							values ('$dno','$ucode',now()) ";
							/* if (!mysql_query($scefm_in_qr)) {
								die("Error: " . __LINE__ . mysql_error());
							} */
							$this->db->query($scefm_in_qr);
						}
					}
				}
			}//ends here
		}
		die;
	}
	
	
	public function get_extraparty_info()
	{
		//$fil_diary = " diary_no = $_REQUEST[fno]";
		
		$fno = $_REQUEST['fno'];
		$flag = $_REQUEST['flag'];
		$id = $_REQUEST['id'];
		$type = $_REQUEST['type'];

		 
		$no = $this->PartyModel->getExtrapartyInfo($fno, $flag, $id, $type);
		//  pr($no);
		if(!empty($no))
			{
				$deptName = $no['deptname'] ?? '';
				$partySuffix = $no['partysuff'] ?? '';

				$deptname_partysuff = $partysuff_partyname ='';
				if(!empty($deptName) && !empty($partySuffix))
				{
					$deptname_partysuff = trim(str_replace($deptName, '', $partySuffix));
				}
				
				if(!empty($no['partysuff']) && !empty($no['partyname']))
				{
					$partysuff_partyname =   trim(str_replace($no['partysuff'], '', $no['partyname']));
				}
				$party_name ='';
				if(!empty($no['party_name']))
				{
					$party_name =  base64_encode($no['party_name']);
				}
				
				
					echo $no['partyname'].'~'.$no['sonof'].'~'.$no['authcode'].'~'.$no['prfhname'].'~'.$no['age'].'~'.$no['sex']
						.'~'.$no['caste'].'~'.$no['addr1'].'~'.$no['addr2'].'~'.$no['state'].'~'.$no['city'].'~'.$no['pin'].'~'.$no['email']
						.'~'.$no['contact'].'~'.$no['dstname'].'~'.$no['country'].'~'.$no['deptcode'].'~'.$no['education'].'~'.$no['occ_code'].'~'.$no['edu_code']
						.'~'.$deptname_partysuff.'~'.$partysuff_partyname.'~'.$no['lowercase_id']
						.'~'.$no['state_in_name'].'~'.$no['deptname'].'~'.$no['remark_lrs'].'~'.$no['add_add'].'~'.$no['auto_generated_id'].'~'.$no['cont_pro_info'].'~'.$party_name;
			}else{
				echo 'no record found';
			}
	}
	
}
