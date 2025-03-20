<?php

namespace App\Controllers\Filing;
// use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\CaveatModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;


class Caveat extends BaseController
{
    // protected $session;
    public $Dropdown_list_model;
    public $efiling_webservices;
    public $highcourt_webservices;
    public $CaveatModel;
	protected $diary_no;

    function __construct()
    {
        $this->Dropdown_list_model= new Dropdown_list_model();
        $this->CaveatModel = new CaveatModel();
		if (empty(session()->get('filing_details')['diary_no'])) {
            $uri = current_url(true);
           	//$getUrl = $uri->getSegment(3).'-'.$uri->getSegment(4);
			$getUrl = str_replace('/', '-', $uri->getPath());
            header('Location:'.base_url('Filing/Diary/search?page_url='.base64_encode($getUrl)));exit();
            exit();
        } else {
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    }

 
    public function index(){
       
        $caveat_no = $this->diary_no;
		 
		$data['aor_list'] = $this->CaveatModel->getAorDetails();
		$data['is_renewed'] = $this->CaveatModel->checkIfRenew($caveat_no);
		//$data
        //$data['formAction'] = 'Filing/Party/index/';
		// pr($data);
        return view('Filing/caveat/add_caveator_writ',$data);        
    }
	
	 public function  add_adv_caveat()
	{
		 // Get request parameters
			 
			$cav_no = $_REQUEST['d1'];
			$bar_id = $_REQUEST['d2'];
			$remarks = $_REQUEST['remarks'];

			// Assuming user session is already started and user ID is stored in session
			$session = session();
			$ucode = $session->get('dcmis_user_idd');
			$year = date('Y');

			// Prepare the data to be inserted
			$data = [
				'diary_no' => $cav_no,
				'adv_type' => 'A',
				'pet_res' => 'R',
				'advocate_id' => $bar_id,
				'usercode' => $ucode,
				'ent_dt' => date('Y-m-d H:i:s'),
				'display' => 'Y',
				'stateadv' => 'N',
				'aor_state' => 'A',
				'adv' => '[caveat]',
				'old_adv' => NULL,
				'writ_adv_remarks' => $remarks,
			];
 
			// Insert data into the advocate table
			$builder = $this->db->table('advocate');
			$insert = $builder->insert($data);

			if ($insert) {
				echo "CAVEATOR NAME ADDED SUCCESSFULLY IN DIARY NO- " . $cav_no;
			} else {
				echo "Error: Unable to add caveator name.";
			}
	}
	
	
	public function checker_ans_caveat()
	{
		$caveat_no = $this->diary_no;
		 
		$data['aor_list'] = $this->CaveatModel->getAorDetails();
		$data['is_renewed'] = $this->CaveatModel->checkIfRenew($caveat_no);
		//$data
        //$data['formAction'] = 'Filing/Party/index/';
		// pr($data);
        return view('Filing/caveat/checker_ans_caveat',$data); 
	}
	
	
	public function get_detail_for_checker_caveat()
	{
		//$caveat_no = $this->diary_no;
		$caveat_no=$_REQUEST['d_no'].$_REQUEST['d_yr'];
		$ct_cat=0;
		$c_date=date('Y-m-d');
		
		$_SESSION['caveat_d_no'] = $_REQUEST['d_no'];
		$_SESSION['caveat_d_yr'] = $_REQUEST['d_yr'];
		$_SESSION['caveat_no'] = $caveat_no;
		 
		$data['CaveatModel'] = $this->CaveatModel;
		
		$data['caveat_list'] = $this->CaveatModel->getCaveatList($caveat_no);
		 
			echo view('Filing/caveat/tpl_get_detail_for_checker_caveat',$data); 
		 
	}
	
	public function getCategories()
	{		 
		 $data['CaveatModel'] = $this->CaveatModel;
		 $data['hd_diary_nos'] = $_REQUEST['hd_diary_nos'];
		$data['id_val'] = $_REQUEST['id_val'];		 
		echo view('Filing/caveat/get_categories',$data); 
	}
	
	public function chkValuation()
	{		 
		 $data['CaveatModel'] = $this->CaveatModel;
		 $data['chk_bench'] = $_REQUEST['chk_bench'];
		$data['lst_case'] = $_REQUEST['lst_case'];		 
		echo view('scrutiny/chk_valuation',$data); 
	}
	
	public function getCourtFee()
	{	
		$data['CaveatModel'] = $this->CaveatModel;
		$data['chk_bench'] = $_REQUEST['chk_bench'];
		$data['lst_case'] = $_REQUEST['lst_case'];		 
		$data['d_no'] = $_REQUEST['d_no'];		 
		$data['d_yr'] = $_REQUEST['d_yr'];		 
		echo view('scrutiny/chk_valuation',$data); 
	}
	
	public function getActDetail()
	{	
		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['handler'] = $_REQUEST['handler'];
		$data['fil_no'] = $_REQUEST['fil_no'] ?? '';
		$data['act'] = $_REQUEST['act'] ?? '';
		$data['sec_1'] = $_REQUEST['sec_1'] ?? '';
		$data['sec_2'] = $_REQUEST['sec_2'] ?? '';
		$data['sec_3'] = $_REQUEST['sec_3'] ?? '';
		$data['sec_4'] = $_REQUEST['sec_4'] ?? '';
		$data['d_yr'] = $_REQUEST['d_yr'] ?? '';
		 	 
		echo view('scrutiny/get_act_detail',$data); 
	}
	
	public function getSearchKeyword()
	{		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['txt_src_key'] = $_REQUEST['txt_src_key'];		 
		 	 
		echo view('scrutiny/search_keyword',$data); 
	}
	
	public function getSelKeyword()
	{		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['v_val'] = $_REQUEST['v_val'];		 
		$data['sp_k_des'] = $_REQUEST['sp_k_des'];		 
		$data['hd_max_keyword'] = $_REQUEST['hd_max_keyword'];		 
		 	 
		echo view('scrutiny/get_sel_keyword',$data); 
	}
	
	
	public function delMulCat()
	{
		//print_r($_REQUEST);
		//exit;
		if (($_REQUEST['total_old_cat'] == 1) && ($_REQUEST['total_new_cat'] == 1)) {

			$dairy_no = $_REQUEST[t_h_cno] . $_REQUEST[t_h_cyr];
			$ex_a = explode('^', $_REQUEST['hd_sp_a_rem']);
			$ex_b = explode('^', $_REQUEST['hd_sp_b_rem']);
			$ex_c = explode('^', $_REQUEST['hd_sp_c_rem']);

			$ex_d = explode('^', $_REQUEST['hd_sp_d_id']);
			for ($index = 0; $index <= count($ex_a); $index++) {


				//$sql_ck_del=mysql_query("SELECT count(*)   FROM  mul_category  WHERE diary_no='$dairy_no' 
				//                     and cat='$ex_a[$index]' and subcat='$ex_b[$index]'
				//                     and subcat1='$ex_c[$index]' and display='Y' and id='$ex_d[$index]'");

				$sql_ck_del = mysql_query("SELECT count(*)   FROM  mul_category  WHERE diary_no='$dairy_no' 
						  and display='Y' and submaster_id='$ex_d[$index]'");
				$result_del = mysql_result($sql_ck_del, 0);
				if ($result_del != 0) {
					$sq_upd = mysql_query("Update mul_category set display='N',updated_on=now(),updated_by='$_SESSION[dcmis_user_idd]' where diary_no='$dairy_no' 
						  and submaster_id='$ex_d[$index]'") or die("Error: " . __LINE__ . mysql_error());
				}
			}
		}
	}
	
	
	public function insertMulCat()
	{
		
		
		if (($_REQUEST['total_old_cat'] == 1)) {


			$total_new_category = $_REQUEST['total_new_cat'];
			$old_submaster_id = $_REQUEST['hd_sp_d'];

			$sectionsCategory = [12,13,14,64];
			$logged_in_usersection = $_SESSION['dcmis_section'];

			if (in_array($logged_in_usersection, $sectionsCategory) || ((!in_array($logged_in_usersection, $sectionsCategory))  && ($_REQUEST['total_new_cat'] == 1))) {

				$dairy_no = $_REQUEST['t_h_cno'] . $_REQUEST['t_h_cyr'];
				$ucode = $_SESSION['dcmis_user_idd'];
				// $other_cat_rem=$_REQUEST[other_cat];
				$other_cat_rem = trim($_REQUEST['other_cat'], ' ');

				$verify_req_page = $_REQUEST['verify_req_page'];
				$hd_sp_d_new = $_REQUEST['hd_sp_d_new'];

				$sq_upd = mysql_query("Update mul_category set display='N',updated_on=now(),updated_by='$ucode' where diary_no='$dairy_no' ") or die("Error: " . __LINE__ . mysql_error());
				$sql =  mysql_query("Insert Into  mul_category (od_cat,diary_no,submaster_id,mul_cat_user_code,new_submaster_id,updated_on,updated_by) values 
			('$_REQUEST[ytq1]','$dairy_no','$_REQUEST[hd_sp_d]','$ucode','$hd_sp_d_new',now(),'$ucode')")
					or die("Error: " . __LINE__ . mysql_error());

				$_REQUEST['hd_sp_d'] = (int)$_REQUEST['hd_sp_d'];
				//echo "entered category is".$_REQUEST[hd_sp_d];

				if (($_REQUEST['hd_sp_d'] == 349) || ($_REQUEST['hd_sp_d'] == 118) || ($_REQUEST['hd_sp_d'] == 119) || ($_REQUEST['hd_sp_d'] == 120) || ($_REQUEST['hd_sp_d'] == 121) || ($_REQUEST['hd_sp_d'] == 122) || ($_REQUEST['hd_sp_d'] == 123) || ($_REQUEST['hd_sp_d'] == 124) || ($_REQUEST['hd_sp_d'] == 125) || ($_REQUEST['hd_sp_d'] == 126) || ($_REQUEST['hd_sp_d'] == 127) || ($_REQUEST['hd_sp_d'] == 128) || ($_REQUEST['hd_sp_d'] == 129) || ($_REQUEST['hd_sp_d'] == 130) || ($_REQUEST['hd_sp_d'] == 131) || ($_REQUEST['hd_sp_d'] == 132) || ($_REQUEST['hd_sp_d'] == 133) || ($_REQUEST['hd_sp_d'] == 318) || ($_REQUEST['hd_sp_d'] == 332) || ($_REQUEST['hd_sp_d'] == 567) || ($_REQUEST['hd_sp_d'] == 568) || ($_REQUEST['hd_sp_d'] == 569) || ($_REQUEST['hd_sp_d'] == 570) || ($_REQUEST['hd_sp_d'] == 571) || ($_REQUEST['hd_sp_d'] == 572) || ($_REQUEST['hd_sp_d'] == 573) || ($_REQUEST['hd_sp_d'] == 574) || ($_REQUEST['hd_sp_d'] == 575) || ($_REQUEST['hd_sp_d'] == 576) || ($_REQUEST['hd_sp_d'] == 577) || ($_REQUEST['hd_sp_d'] == 578) || ($_REQUEST['hd_sp_d'] == 579) || ($_REQUEST['hd_sp_d'] == 580) || ($_REQUEST['hd_sp_d'] == 581) || ($_REQUEST['hd_sp_d'] == 582)) {

					$sql_update_main = "update main set section_id=32 where diary_no=$dairy_no and (casetype_id in(5,6) or active_casetype_id in(5,6) )";
					$rs_update_main = mysql_query($sql_update_main);
				}
				//}

				if ($verify_req_page == 'Y') {

					//other category
					$other_category = array('10', '20', '46', '75', '87', '101', '115', '129', '141', '151', '163', '182', '201', '215', '227', '250', '259', '262', '270', '276', '289', '295', '300', '304', '311');
					if (in_array($_REQUEST[hd_sp_d], $other_category) && $other_cat_rem != '') {
						$check_dno_qr = "select * from other_category where diary_no='$dairy_no' and display='Y'";
						$check_dno_rs = mysql_query($check_dno_qr)   or die("Error: " . __LINE__ . mysql_error());
						if (mysql_num_rows($check_dno_rs) > 0) {
							//echo "update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'";
							$update_other_cat = mysql_query("update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'");


							$insert_other_cat = mysql_query("Insert Into  other_category (diary_no,submaster_id,remarks,ent_user,ent_datetime,display) values 
			('$dairy_no','$_REQUEST[hd_sp_d]','$other_cat_rem','$ucode',now(),'Y')")
								or die("Error: " . __LINE__ . mysql_error());
						} else {

							$insert_other_cat = mysql_query("Insert Into  other_category (diary_no,submaster_id,remarks,ent_user,ent_datetime,display) values 
			('$dairy_no','$_REQUEST[hd_sp_d]','$other_cat_rem','$ucode',now(),'Y')")
								or die("Error: " . __LINE__ . mysql_error());
						}
					} else {
						$check_dno_qr = "select * from other_category where diary_no='$dairy_no' and display='Y'";
						$check_dno_rs = mysql_query($check_dno_qr)   or die("Error: " . __LINE__ . mysql_error());
						if (mysql_num_rows($check_dno_rs) > 0) {
							//echo "update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'";
							$update_other_cat = mysql_query("update other_category set display='N',upd_user='$ucode',upd_datetime=now() where diary_no='$dairy_no' and display='Y'");
						}
					}
				}
			}
		}
	}
	
	
	public function add_extra_cav_adv()
	{	
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		//$data['diary_no'] = $filing_details['diary_no']; 	 
		 	 
		echo view('Filing/extracaveatadv/add_extra_cav_adv',$data); 
	}
	
	
	
	public function cav_adv_fetch_parties_first()
	{	
		$_SESSION['caveat_d_no'] = $_REQUEST['dno'];
        $_SESSION['caveat_d_yr'] = $_REQUEST['dyr'];		
        $_SESSION['caveat_no'] = $_SESSION['caveat_d_no'].$_SESSION['caveat_d_yr'];
 		
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['caveat_no'] = $_SESSION['caveat_no'];  	 
		 	 
		echo view('Filing/extracaveatadv/cav_adv_fetch_parties_first',$data); 
	}


	public function cav_adv_fetch_parties()
	{
		//pr($_POST);
		//$_SESSION['caveat_d_no'] = $_REQUEST['dno'];
        //$_SESSION['caveat_d_yr'] = $_REQUEST['dyr'];		
        $_SESSION['caveat_no'] = $_SESSION['caveat_d_no'].$_SESSION['caveat_d_yr'];
 		
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['caveat_no'] = $_SESSION['caveat_no'];  	 
		 	 
		echo view('Filing/extracaveatadv/cav_adv_fetch_parties',$data); 
	}

	public function get_adv_name_aor()
	{
		//$sql = "select name,mobile,email from bar where aor_code='$_REQUEST[aorcode]' ";
		//$result = mysql_query($sql) or die(__LINE__.'->'.mysql_error());
		$row = is_data_from_table('master.bar'," aor_code='$_REQUEST[aorcode]' ",' name,mobile,email ','');
		
		if(!empty($row))
		{			 
			echo $row['name'].'~'.$row['mobile'].'~'.$row['email'];
		}
		else
			echo " ~0~ ";

			die;
	}


	
	public function cav_save_advnew()
	{	
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 	

		$caveat_no = $_SESSION['caveat_d_no'].$_SESSION['caveat_d_yr'];
		
		$pet = explode('||', rtrim('||'.$_REQUEST['pet'],"||"));
		$res = explode('||', rtrim('||'.$_REQUEST['res'],"||"));

		for($i=0;$i<sizeof($pet);$i++)
		{
			if($i==0)
				continue;
			 
			$p_adv = explode('~', $pet[$i]);
			 
			 
			$p_adv[4] = rtrim($p_adv[4],',');
			$p_adv[4] = rtrim($p_adv[4],'-');
			 
			$p_adv_for = explode(',', $p_adv[4]);
			if (!empty($p_adv_for)) 
			{
				$count = count($p_adv_for);
				for($j=0;$j< $count; $j++)
				{
					$p_adv0 = $p_adv[0];
					//$advocate_id = is_data_from_table('master.bar'," aor_code= $p_adv0 AND isdead='N' ",'bar_id','')['bar_id'];
					$advocate_id = "SELECT bar_id FROM bar WHERE aor_code=$p_adv[0] AND isdead='N'";
					$query = $this->db->query($query);
					$advocate_id = $query->getRowArray()['bar_id'];
					if(empty($advocate_id)){
						echo "AOR- $p_adv[0] NOT FOUND";
						continue;
					}
					
					if(is_numeric($p_adv_for[$j]))
					{
						
						
						
						//$chk_rs = mysql_query($chk) or die(__LINE__.'->'.mysql_error());  
						
						$p_adv_for = $p_adv_for[$j];
						$p_adv7 = $p_adv[7];

						$pet_res_no = $p_adv_for[$j];
						
						// $chk_rs = is_data_from_table('caveat_advocate'," caveat_no = $caveat_no AND advocate_id=$advocate_id
						// and pet_res='P' and pet_res_no=$pet_res_no and display='Y' and stateadv='$p_adv7' ",'advocate_id','')['advocate_id'];

						$query = "select advocate_id from caveat_advocate where caveat_no = $caveat_no and advocate_id=$advocate_id
						and pet_res='P' and pet_res_no=$p_adv_for[$j] and display='Y' and stateadv='$p_adv[7]'";
						$chk_rs = $this->db->query($query);
						
						if(empty($chk_rs) || ($p_adv[0]=='9999' && $p_adv[1]=='2014'))
						{
							
							
							if($p_adv_for[$j]!=0){
								
								$adv_name = '[P-'.$p_adv_for[$j].']';
							}
							else{
								//$adv_name = trim($p_adv[1]);
								$adv_name = '';
							}
							
							if($p_adv[5]!='N')
								$adv_name .= '['.$p_adv[5].']';
							
							if($p_adv[6]=='AG')
								$adv_name .= '[AG]';
							
							if($p_adv[7]=='P')
								$adv_name .= '[Pr]';
							else if($p_adv[7]=='G')
								$adv_name .= '[Gr]';
							

								$in_ps = "insert into caveat_advocate(diary_no,adv_type,pet_res,pet_res_no,advocate_id,adv,usercode,ent_dt,stateadv) 
					values('$_REQUEST[dno]','A','P',$p_adv_for[$j],$advocate_id,'$adv_name',$ucode,NOW(),'$p_adv[7]')";

								$this->db->query($in_ps);
							
						}
					}
					
				}
			}
		}

		for($i=0;$i<sizeof($res);$i++)
		{
			if($i==0)
				continue;
			$r_adv = explode('~', $res[$i]);
			//echo $p_adv[5];
			$r_adv[4] = rtrim($r_adv[4],',');
			$r_adv[4] = rtrim($r_adv[4],'-');
			//echo '<br>after rtrim'.$p_adv[5];
			$r_adv_for = explode(',', $r_adv[4]);
			$count = count($r_adv_for);
			for($j=0;$j< $count;$j++)
			{
				/* $advocate_id = "SELECT bar_id FROM bar WHERE aor_code=$r_adv[0] AND isdead='N'";
				$advocate_id = mysql_query($advocate_id) or die(__LINE__.'->'.mysql_error());
				 */
				 $r_adv0 = $r_adv[0];
				//$advocate_id = is_data_from_table('master.bar'," aor_code= $r_adv0 AND isdead='N' ",'bar_id','')['bar_id'];

				$advocate_id = "SELECT bar_id FROM bar WHERE aor_code=$r_adv[0] AND isdead='N'";
				$query = $this->db->query($query);
				$advocate_id = $query->getRowArray()['bar_id'];
				
				if(empty($advocate_id )){
					echo "AOR- $r_adv[0] NOT FOUND";
					continue;
				}
				//$advocate_id = mysql_result($advocate_id,0);
				
				if(is_numeric($r_adv_for[$j]))
				{
					/* $chk = "select advocate_id from caveat_advocate where $fil_no_diary and advocate_id=$advocate_id
					and pet_res='R' and pet_res_no=$r_adv_for[$j] and display='Y' and stateadv='$r_adv[7]'";
					$chk_rs = mysql_query($chk) or die(__LINE__.'->'.mysql_error()); */
					
					$r_adv_for = $r_adv_for[$j];
					$r_adv7 = $r_adv[7];
					$pet_res_no = $r_adv_for[$j];

					// $chk_rs = is_data_from_table('caveat_advocate'," caveat_no = $caveat_no AND advocate_id=$advocate_id
					// and pet_res='R' and pet_res_no=$pet_res_no and display='Y' and stateadv='$r_adv7' ",'advocate_id','')['advocate_id'];
					
					$chk = "select advocate_id from caveat_advocate where $fil_no_diary and advocate_id=$advocate_id
					and pet_res='R' and pet_res_no=$r_adv_for[$j] and display='Y' and stateadv='$r_adv[7]'";
					$chk_rs = $this->db->query($chk);

					if(empty($chk_rs) || ($r_adv[0]=='9999' && $r_adv[1]=='2014'))
					{
						if($r_adv_for[$j]!=0){
							//$adv_name = trim($r_adv[1]).'[R-'.$r_adv_for[$j].']';
							$adv_name = '[R-'.$r_adv_for[$j].']';
						}
						else{
							//$adv_name = trim($r_adv[1]);
							$adv_name = '';
						}
						
						if($r_adv[5]!='N')
							$adv_name .= '['.$r_adv[5].']';
						
						if($r_adv[6]=='AG')
							$adv_name .= '[AG]';
						
						if($r_adv[7]=='P')
							$adv_name .= '[Pr]';
						else if($r_adv[7]=='G')
							$adv_name .= '[Gr]';

							
							$in_rs = "insert into caveat_advocate(diary_no,adv_type,pet_res,pet_res_no,advocate_id,adv,usercode,ent_dt,stateadv) 
							values('$_REQUEST[dno]','A','R',$r_adv_for[$j],$advocate_id,'$adv_name',$ucode,NOW(),'$r_adv[7]')";

							$this->db->query($in_rs);

					}
				}
				 
			}
		}
			
		 echo  '<table align="center"><tr><th>Record Inserted Successfully!!!</th></tr></table> '	; 
	 
	}
	
	public function up_extra_cav_adv()
	{		 
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 		 
		 	 
		echo view('Filing/extracaveatadv/up_extra_cav_adv',$data); 
	}
	
	public function cav_adv_fetch_parties_first_up()
	{
		$_SESSION['caveat_d_no'] = $_REQUEST['dno'];
        $_SESSION['caveat_d_yr'] = $_REQUEST['dyr'];		
        $_SESSION['caveat_no'] = $_SESSION['caveat_d_no'].$_SESSION['caveat_d_yr'];
 		
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['caveat_no'] = $_SESSION['caveat_no']; 	 
		 	 
		echo view('Filing/extracaveatadv/cav_adv_fetch_parties_first_up',$data); 
	}
	
	
	public function cav_save_advnew_updated()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 
		
		$p_adv = $_REQUEST['advaor'];
		
		//$advocate_id = "SELECT bar_id FROM bar WHERE aor_code=$_REQUEST[advaor] AND isdead='N'";
		//$advocate_id = mysql_query($advocate_id) or die(__LINE__.'->'.mysql_error());
		$advocate_id = is_data_from_table('master.bar'," aor_code= $p_adv AND isdead='N' ",'bar_id','')['bar_id'];
		
		if($advocate_id != 0){
			//$advocate_id = mysql_result($advocate_id,0);
			
			//$advocate_id_hd = "SELECT bar_id FROM bar WHERE aor_code=$_REQUEST[advaor_hd] AND isdead='N'";
			//$advocate_id_hd = mysql_query($advocate_id_hd) or die(__LINE__.'->'.mysql_error());
			//$advocate_id_hd = mysql_result($advocate_id_hd,0);
			
			$advaor_hd = $_REQUEST['advaor_hd'];
			$advocate_id_hd = is_data_from_table('master.bar'," aor_code= $advaor_hd AND isdead='N' ",'bar_id','')['bar_id'];
			
			
			/*$chk = "select * from caveat_advocate where $fil_no_diary and advocate_id=$advocate_id 
			and pet_res='$if_pet_res' and pet_res_no=$_REQUEST[party] and adv='$_REQUEST[adv_name]' and stateadv='$_REQUEST[stateadv]' and display='Y'"; */
			//$chk_rs = mysql_query($chk) or die(__LINE__.'->'.mysql_error());
			
			$adv_name = $_REQUEST['adv_name'];
			$p_adv = $_REQUEST['stateadv'];
			$p_adv_for = $_REQUEST['party'];
			
			$chk_rs = is_data_from_table('caveat_advocate'," caveat_no = $dairy_no AND advocate_id=$advocate_id
					and pet_res='P' and pet_res_no=$p_adv_for and adv=$adv_name and display='Y' and stateadv=$p_adv ",'*','');
			 
			if(!empty($chk_rs))
			{
				 
				/* $up_rs = "update caveat_advocate set display='N',ent_dt=now(),usercode=$ucode where $fil_no_diary 
				and pet_res='$_REQUEST[val]' and pet_res_no=$_REQUEST[party_hd] and advocate_id='$advocate_id_hd' and display='Y' and stateadv='$_REQUEST[stateadv_hd]'";
				  */
				$up_rs = "UPDATE caveat_advocate 
						  SET display = 'N', ent_dt = CURRENT_TIMESTAMP, usercode = ? 
						  WHERE $fil_no_diary 
						  AND pet_res = ? 
						  AND pet_res_no = ? 
						  AND advocate_id = ? 
						  AND display = 'Y' 
						  AND stateadv = ?";

				$update_result = $this->db->query($up_rs, [
					$ucode, 
					$_REQUEST['val'], 
					$_REQUEST['party_hd'], 
					$_REQUEST['advocate_id_hd'], 
					$_REQUEST['stateadv_hd']
				]);  
				  
				//--------
				if(!empty($update_result))					 
				{
					 
 
					echo '0';
					
					/* $in_rs = "insert into caveat_advocate(caveat_no,adv_type,pet_res,pet_res_no,advocate_id,adv,usercode,ent_dt,stateadv) 
					values('$_REQUEST[dno]','$_REQUEST[advtype]','$if_pet_res',$_REQUEST[party],$advocate_id,'$_REQUEST[adv_name]','$ucode',NOW(),'$_REQUEST[stateadv]')";
					 */
					 $query = "INSERT INTO caveat_advocate (
									  caveat_no, adv_type, pet_res, pet_res_no, 
									  advocate_id, adv, usercode, ent_dt, stateadv
								  ) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)";

						$in_rs = $this->db->query($query, [
							$_REQUEST['dno'], 
							$_REQUEST['advtype'], 
							$if_pet_res, 
							$_REQUEST['party'], 
							$advocate_id, 
							$_REQUEST['adv_name'], 
							$ucode, 
							$_REQUEST['stateadv']
						]);
					if(empty($in_rs))
						die(__LINE__.'->'.mysql_error());
					else
					{
						 
						echo '0';
					}
				}
			}
			else
			{
				echo '0';
			}
		}
		else{
			echo '0';
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
 

    public function set_party_status(){
        if(!empty($_POST['data'])){
            $dataArr = $_POST['data'];
            $data = $this->PartyModel->set_party_status($dataArr);
            echo $data;
        }
    }

    public function save_party_details(){
        if(!empty($_POST['data'])){
            $dataArr = $_POST['data'];
            $data = $this->PartyModel->savepartyDetails($dataArr);
            echo $data;
        }
    }

    public function deleteAction(){
        $dataset = $_POST['data'];
        $data = $this->PartyModel->deleteAction($dataset);
        echo $data;
    }

    public function getUpdateData(){
        $dataset = $_POST['data'];
        $data = $this->PartyModel->getUpdateData($dataset);
        echo $data;
    }

    public function get_cause_title(){
        $dataset = $_POST['data'];
        $data = $this->PartyModel->get_cause_title($dataset);
        echo $data;
    }

    public function copy_party_details(){
        $dataset = $_POST['data'];
        $data = $this->PartyModel->copy_party_details($dataset);
        echo $data;
    }


    public function getDepttList(){
        $dataset = $_POST['deptt'];
        $data = $this->PartyModel->get_only_deptt($dataset);
        echo $data;
    }
	
	
	public function extra_caveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 		 
		 	 
		echo view('Filing/extracaveat/extra_caveat',$data); 
	}
	
	public function get_extracaveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 		 
		 
		 
		echo view('Filing/extracaveat/get_extracaveat',$data);
	}
	public function get_extracaveat_mod()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 		 
		 
		 
		echo view('Filing/extracaveat/get_extracaveat_mod',$data);
	}
	
	public function set_caveat_status()
	{
		$filing_details = session()->get('filing_details');	
		$chk =  $_REQUEST['fno'];
		$val =  $_REQUEST['val'];
		 
			$fil_diary = " caveat_no = $chk  ";
 
		
		$no = is_data_from_table('caveat_party'," $fil_diary AND pet_res='$val' AND pflag='P' ",'MAX(sr_no)','');
		 
		echo $no['max']+1;
	}
	
	public function get_district()
	{
		$state = $_REQUEST['state'];
		/*  $district_q = "SELECT id_no District_code, Name FROM state WHERE 
		State_code =
			(SELECT State_code FROM state WHERE 
				id_no = '$state' AND display = 'Y' )    
		AND District_code != 0 AND Sub_Dist_code = 0 AND Village_code = 0 AND display = 'Y' ORDER BY Name";
		$district_rs = mysql_query($district_q) or die(__LINE__.'->'.mysql_error()); */
		
		$option = '<option value="">Select</option>';
		$option .= '<option value="0">Not Mention</option>';
		if(!empty($state))
		{
		
			$district_rs = is_data_from_table('master.state'," state_code =
				(SELECT state_code FROM master.state WHERE 
					id_no = '$state' AND display = 'Y' )    
			AND district_code != 0 AND sub_dist_code = 0 AND village_code = 0 AND display = 'Y' ORDER BY name ",'id_no, district_code, name','A');
			 
			
			if(!empty($district_rs))
			{			
				foreach($district_rs as $district_row)
				{
					$district_code = $district_row['district_code'];		 
					$option .= '<option value="'.$district_code.'">'.$district_row['name'].'</option>';			 
				}
			}
		} 
		echo $option;
		die;
	}
	
	
	public function save_new_caveat_extraparty()
	{
		
			$ucode =  $_SESSION['login']['usercode'];		
			$filing_details = session()->get('filing_details');		 
			$data['CaveatModel'] = $this->CaveatModel;
			$data['diary_no'] = $filing_details['diary_no']; 

			$_REQUEST['p_post'] = $_REQUEST['p_post'] ?? NULL;
			$_REQUEST['p_deptt'] = $_REQUEST['p_deptt'] ?? NULL;
			$_REQUEST['p_add'] = $_REQUEST['p_add'] ?? NULL;
			$_REQUEST['p_city'] = $_REQUEST['p_city'] ?? NULL;
			$_REQUEST['p_name'] = $_REQUEST['p_name'] ?? NULL;
			$_REQUEST['p_rel_name'] = $_REQUEST['p_rel_name'] ?? NULL;
			$_REQUEST['p_occ'] = $_REQUEST['p_occ'] ?? NULL;
			$_REQUEST['p_caste'] = $_REQUEST['p_caste'] ?? NULL;
			$_REQUEST['p_edu'] = $_REQUEST['p_edu'] ?? NULL;

			//echo $_REQUEST['controller'];
			 
			//pr($_REQUEST);
			if ($_REQUEST['controller'] == 'I') {
					//echo '!~!';
				 
					$dno = $_REQUEST['fno'];

					// Common data
					$common_data = [
						'pet_res' => (!empty($_REQUEST['p_f'])) ? $_REQUEST['p_f'] : NULL,
						'sr_no' => (!empty($_REQUEST['p_no'])) ? $_REQUEST['p_no'] : NULL,
						'ind_dep' => (!empty($_REQUEST['p_type'])) ? $_REQUEST['p_type'] : NULL,
						'email' => (!empty($_REQUEST['p_email'])) ? $_REQUEST['p_email'] : NULL,
						'contact' => (!empty($_REQUEST['p_mob'])) ? $_REQUEST['p_mob'] : NULL,
						'usercode' => $ucode,
						'ent_dt' => date('Y-m-d H:i:s'),  // This will generate current timestamp
						'caveat_no' => $dno,
					];
					
					

					if ($_REQUEST['p_type'] == 'I') {
						// Data for individual type
						$data = array_merge($common_data, [
							'partyname' => (!empty($_REQUEST['p_name'])) ? strtoupper(trim($_REQUEST['p_name'])) : NULL,
							'partysuff' => (!empty($_REQUEST['p_name'])) ?strtoupper(trim($_REQUEST['p_name'])) : NULL,
							'sonof' => (!empty($_REQUEST['p_rel'])) ?$_REQUEST['p_rel'] : NULL,
							'prfhname' => (!empty($_REQUEST['p_rel_name'])) ? strtoupper(trim($_REQUEST['p_rel_name'])) : NULL,
							'age' => (!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] : NULL,
							'sex' => (!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] : NULL,
							'caste' => (!empty($_REQUEST['p_caste'])) ? strtoupper(trim($_REQUEST['p_caste'])) : NULL,
							'addr1' => (!empty($_REQUEST['p_occ'])) ? strtoupper(trim($_REQUEST['p_occ'])) : NULL,
							'education' => (!empty($_REQUEST['p_edu'])) ? strtoupper(trim($_REQUEST['p_edu'])) : NULL,
							'addr2' => (!empty($_REQUEST['p_add'])) ? strtoupper(trim($_REQUEST['p_add'])) : NULL,
							'city' => (!empty($_REQUEST['p_city'])) ? strtoupper(trim($_REQUEST['p_city'])) : NULL,
							'state' => (!empty($_REQUEST['p_st'])) ? $_REQUEST['p_st'] : NULL,
							'dstname' => (!empty($_REQUEST['p_dis'])) ? $_REQUEST['p_dis'] : NULL,
							'pin' => (!empty($_REQUEST['p_pin'])) ? $_REQUEST['p_pin'] : NULL,
							'country' => (!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : NULL,
							'occ_code' => (!empty($_REQUEST['p_occ_code'])) ? $_REQUEST['p_occ_code'] : 0,
							'edu_code' => (!empty($_REQUEST['p_edu_code'])) ? $_REQUEST['p_edu_code'] : 0,
						]);
					} else {
						// Data for non-individual type
						$data = array_merge($common_data, [
							'partyname' => strtoupper(trim($_REQUEST['p_statename'] . ' ' . $_REQUEST['p_deptt'] . ' ' . $_REQUEST['p_post'])),
							'partysuff' => strtoupper(trim($_REQUEST['p_statename'] . ' ' . $_REQUEST['p_deptt'])),
							'addr2' => strtoupper(trim($_REQUEST['p_add'])),
							'city' => strtoupper(trim($_REQUEST['p_city'])),
							'state' =>  (!empty($_REQUEST['p_st'])) ?  $_REQUEST['p_st'] : NULL,
							'dstname' =>  (!empty($_REQUEST['p_dis'])) ?  $_REQUEST['p_dis'] : NULL,
							'pin' =>  (!empty($_REQUEST['p_pin'])) ?  $_REQUEST['p_pin'] : NULL,
							'country' =>  (!empty($_REQUEST['p_cont'])) ?  $_REQUEST['p_cont'] : NULL,
							'authcode' =>  (!empty($_REQUEST['p_code'])) ?  $_REQUEST['p_code'] : 0,
							'deptcode' =>  (!empty($_REQUEST['d_code'])) ?  $_REQUEST['d_code'] : 0,
							'state_in_name' =>  (!empty($_REQUEST['p_statename_hd'])) ?  $_REQUEST['p_statename_hd'] : NULL,
						]);
					}
					 
					// Insert data into the database
					if ($this->db->table('caveat_party')->insert($data)) {
						echo "Party Added Successfully";
					} else {
						// Log error
						log_message('error', 'Error in adding party: ' . json_encode($data));
						echo "Error Adding Party";
					}
				}
			else if ($_REQUEST['controller'] == 'U') {
				//echo '!~!';
				
				$dno = $_REQUEST['fno'];

				if ($_REQUEST['hd_p_f'] == $_REQUEST['p_f']) {
					// If party type is 'I'
					if ($_REQUEST['p_type'] == 'I') {
						$update = "UPDATE caveat_party SET 
							pet_res = :p_f:, 
							ind_dep = :p_type:, 
							partyname = UPPER(TRIM(:p_name:)), 
							partysuff = UPPER(TRIM(:p_name:)), 
							sonof = :p_rel:, 
							prfhname = UPPER(TRIM(:p_rel_name:)), 
							age = :p_age:, 
							sex = :p_sex:, 
							caste = UPPER(TRIM(:p_caste:)), 
							addr1 = UPPER(TRIM(:p_occ:)), 
							education = UPPER(TRIM(:p_edu:)), 
							addr2 = UPPER(TRIM(:p_add:)), 
							dstname = UPPER(TRIM(:p_city:)), 
							state = :p_st:, 
							city = :p_dis:, 
							pin = :p_pin:, 
							country = :p_cont:, 
							email = :p_email:, 
							contact = :p_mob:, 
							usercode = :usercode:, 
							ent_dt = NOW(), 
							pflag = :p_sta:, 
							occ_code = :p_occ_code:, 
							edu_code = :p_edu_code:
						WHERE caveat_no = :dno: 
						  AND pet_res = :hd_p_f: 
						  AND sr_no = :p_no:";
					} 
					// If party type is not 'I'
					else {
						$update = "UPDATE caveat_party SET 
							pet_res = :p_f:, 
							ind_dep = :p_type:, 
							partyname = UPPER(TRIM(:party_name:)), 
							partysuff = UPPER(TRIM(:party_suff:)), 
							addr2 = UPPER(TRIM(:p_add:)), 
							dstname = UPPER(TRIM(:p_city:)), 
							state = :p_st:, 
							city = :p_dis:, 
							pin = :p_pin:, 
							country = :p_cont:, 
							email = :p_email:, 
							contact = :p_mob:, 
							usercode = :usercode:, 
							ent_dt = NOW(), 
							authcode = :p_code:, 
							deptcode = :d_code:, 
							pflag = :p_sta:, 
							state_in_name = :p_statename_hd:
						WHERE caveat_no = :dno: 
						  AND pet_res = :hd_p_f: 
						  AND sr_no = :p_no:";
					}

					// Binding parameters and executing the query
					$params = [
						'p_f' => (!empty($_REQUEST['p_f'])) ? $_REQUEST['p_f'] : NULL,
						'p_type' => (!empty($_REQUEST['p_type'])) ? $_REQUEST['p_type'] : NULL,
						'p_name' => (!empty($_REQUEST['p_name'])) ? $_REQUEST['p_name'] : NULL,
						'p_rel' => (!empty($_REQUEST['p_rel'])) ? $_REQUEST['p_rel'] : NULL,
						'p_rel_name' => (!empty($_REQUEST['p_rel_name'])) ? $_REQUEST['p_rel_name'] : NULL,
						'p_age' => (!empty($_REQUEST['p_age'])) ? $_REQUEST['p_age'] : NULL,
						'p_sex' => (!empty($_REQUEST['p_sex'])) ? $_REQUEST['p_sex'] : NULL,
						'p_caste' => (!empty($_REQUEST['p_caste'])) ? $_REQUEST['p_caste'] : NULL,
						'p_occ' => (!empty($_REQUEST['p_occ'])) ? $_REQUEST['p_occ'] : NULL,
						'p_edu' => (!empty($_REQUEST['p_edu'])) ? $_REQUEST['p_edu'] : NULL,
						'p_add' => (!empty($_REQUEST['p_add'])) ? $_REQUEST['p_add'] : NULL,
						'p_city' => (!empty($_REQUEST['p_city'])) ? $_REQUEST['p_city'] : NULL,
						'p_st' => (!empty($_REQUEST['p_st'])) ? $_REQUEST['p_st'] : NULL,
						'p_dis' => (!empty($_REQUEST['p_dis'])) ? $_REQUEST['p_dis'] : NULL,
						'p_pin' => (!empty($_REQUEST['p_pin'])) ? $_REQUEST['p_pin'] : NULL,
						'p_cont' => (!empty($_REQUEST['p_cont'])) ? $_REQUEST['p_cont'] : NULL,
						'p_email' => (!empty($_REQUEST['p_email'])) ? $_REQUEST['p_email'] : NULL,
						'p_mob' => (!empty($_REQUEST['p_mob'])) ? $_REQUEST['p_mob'] : NULL,
						'usercode' => $ucode,
						'p_sta' => (!empty($_REQUEST['p_sta'])) ? $_REQUEST['p_sta'] : NULL,
						'p_occ_code' => (!empty($_REQUEST['p_occ_code'])) ? $_REQUEST['p_occ_code'] : 0,
						'p_edu_code' => (!empty($_REQUEST['p_edu_code'])) ? $_REQUEST['p_edu_code'] : 0,
						'dno' => $dno,
						'hd_p_f' => (!empty($_REQUEST['hd_p_f'])) ? $_REQUEST['hd_p_f'] : NULL,
						'p_no' => (!empty($_REQUEST['p_no'])) ? $_REQUEST['p_no'] : NULL,
						'p_code' => (!empty($_REQUEST['p_code'])) ? $_REQUEST['p_code'] : 0,
						'd_code' => (!empty($_REQUEST['d_code'])) ? $_REQUEST['d_code'] : 0,
						'p_statename_hd' => (!empty($_REQUEST['p_statename_hd'])) ? $_REQUEST['p_statename_hd'] : NULL,
						'party_name' => (!empty($_REQUEST['p_statename_hd'])) ? $_REQUEST['p_statename'] . ' ' . $_REQUEST['p_deptt'] . ' ' . $_REQUEST['p_post'] :  $_REQUEST['p_name'],
						'party_suff' => (!empty($_REQUEST['p_statename_hd'])) ? $_REQUEST['p_statename'] . ' ' . $_REQUEST['p_deptt'] . ' ' . $_REQUEST['p_post'] :  $_REQUEST['p_name'],
					];

					// Executing the update query
					$this->db->query($update, $params);

				} else {
					// Update pflag to 'T'
					$update = "UPDATE caveat_party SET pflag = 'T' WHERE caveat_no = :dno: AND pet_res = :hd_p_f: AND sr_no = :p_no:";
					$this->db->query($update, ['dno' => $dno, 'hd_p_f' => $_REQUEST['hd_p_f'], 'p_no' => $_REQUEST['p_no']]);

					// Get the max sr_no
					$sql_no = "SELECT MAX(sr_no) AS no FROM caveat_party WHERE caveat_no = :dno: AND pet_res = :p_f:";
					$query = $this->db->query($sql_no, ['dno' => $dno, 'p_f' => $_REQUEST['p_f']]);
					$row = $query->getRow();
					$p_no = $row ? $row->no + 1 : 1;

					// Insert new party
					if ($_REQUEST['p_type'] == 'I') {
						$update = "INSERT INTO caveat_party(pet_res, sr_no, ind_dep, partyname, partysuff, sonof, prfhname, age, sex, caste, addr1, education, addr2, dstname, state, city, pin, country, email, contact, usercode, ent_dt, caveat_no, occ_code, edu_code) 
							VALUES (:p_f:, :p_no:, :p_type:, UPPER(TRIM(:p_name:)), UPPER(TRIM(:p_name:)), :p_rel:, UPPER(TRIM(:p_rel_name:)), :p_age:, :p_sex:, UPPER(TRIM(:p_caste:)), UPPER(TRIM(:p_occ:)), UPPER(TRIM(:p_edu:)), UPPER(TRIM(:p_add:)), UPPER(TRIM(:p_city:)), :p_st:, :p_dis:, :p_pin:, :p_cont:, :p_email:, :p_mob:, :usercode:, NOW(), :dno:, :p_occ_code:, :p_edu_code:)";
					} else {
						$update = "INSERT INTO caveat_party(pet_res, sr_no, ind_dep, partyname, partysuff, addr2, dstname, state, city, pin, country, email, contact, usercode, ent_dt, authcode, deptcode, caveat_no, state_in_name) 
							VALUES (:p_f:, :p_no:, :p_type:, UPPER(TRIM(:party_name:)), UPPER(TRIM(:party_suff:)), UPPER(TRIM(:p_add:)), UPPER(TRIM(:p_city:)), :p_st:, :p_dis:, :p_pin:, :p_cont:, :p_email:, :p_mob:, :usercode:, NOW(), :p_code:, :d_code:, :dno:, :p_statename_hd:)";
					}

					// Binding parameters and executing the query
					$params['p_no'] = $p_no;
					$this->db->query($update, $params);
				}

				if ($this->db->affectedRows() > 0) {
					echo "Party Modified Successfully";
				} else {
					echo "Error Modifying Party";
				}
			}
			// pr($_REQUEST);
			die;

	}
	
	public function get_only_state_name()
	{
		if(isset($_GET["term"]))
		{
			$q = strtolower($_GET["term"]);
			if (!$q) return;

			 
			$sql = "SELECT deptcode,deptname FROM  master.deptt WHERE LOWER(deptname) like '%$q%'";
			/* $result = mysql_query($sql) or die(mysql_error()); */
			$json=array();
			
			$q = strtolower($_GET["term"]);
			// Prepare the query with parameter binding to prevent SQL injection
		/*	$sql = "
				SELECT deptcode, deptname 
				FROM (
					SELECT deptcode, deptname 
					FROM master.deptt 
					WHERE deptname LIKE 'THE UNION TERRITORY%' 
					OR deptname LIKE 'THE STATE OF%' 
					OR deptcode = 2 
				) x 
				WHERE LOWER(deptname) LIKE :query:
			"; */

			 
			$result = $this->db->query($sql, [
				'query' => '%' . $q . '%' // Adding wildcards for partial matches
			]);

			// Fetch the results as an array
			$dept_results = $result->getResultArray();
			
			foreach($dept_results as $row)
			{
				$json[]=array('value'=>$row['deptcode'].'~'.$row['deptname'],
							  'label'=>$row['deptname']);
			}
			echo json_encode($json);
		}
	}
	
	public function new_filing_autocomp_post()
	{
		
	}
	
	public function firse_caveatinfo()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$diary_no = $filing_details['diary_no']; 
		
		$fil_diary = " caveat_no = $_REQUEST[fno] ";
		 
		$html = '<th colspan="2">Caveator Parties</th>';
		 
		/* $p_pet_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[fno] AND pet_res='P' AND pflag='P' ORDER BY sr_no";
		$p_pet_rs = mysql_query($p_pet_q) or die(__LINE__.'->'.mysql_error());
		 */
		$p_pet_rs = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='P' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no','A');
		if(!empty($p_pet_rs))
		{
			foreach($p_pet_rs as $p_pet_row)
			{			 
			$html .= '<tr>';
			$html .=	'<td>'. $p_pet_row['sr_no'].'</td>';
			$html .=	'<td>'.$p_pet_row['partyname'].'</td>';
			$html .= '</tr>';			 
			}
		}
		 
		$html .=	'<th colspan="2">Caveatee Parties</th>    ';
		 
		/* $p_res_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[fno] AND pet_res='R' AND pflag='P' ORDER BY sr_no";
		$p_res_rs = mysql_query($p_res_q) or die(__LINE__.'->'.mysql_error());
		 */
		$p_res_rs = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='R' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no','A');
		if(!empty($p_res_rs))
		{
			foreach($p_res_rs as $p_res_row)
			{
				$html .= '<tr>';
				$html .=	'<td>'. $p_pet_row['sr_no'].'</td>';
				$html .=	'<td>'.$p_pet_row['partyname'].'</td>';
				$html .= '</tr>';
			}
		}			
		echo $html;

		
	}
	
	
	
	public function mod_extracaveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 		 
		 	 
		echo view('Filing/extracaveat/mod_extracaveat',$data); 
	}
	
	public function get_extracaveat_info()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no']; 
			
		$fil_diary = " caveat_no = $filing_details[diary_no] ";

		/* $pet_q = "select partyname,partysuff,sonof,authcode,state_in_name,prfhname,age,sex,caste,addr1,addr2,state,city,pin,email,contact,dstname,country,a.deptcode,education,
			occ_code,edu_code,lowercase_id,deptname 
			from caveat_party a 
			left join deptt b on state_in_name=b.deptcode
			where $fil_diary and pet_res='$_REQUEST[flag]' and sr_no='$_REQUEST[id]' 
			and ind_dep='$_REQUEST[type]' and pflag='P'";
		
		$pet_rs = mysql_query($pet_q) or die(__LINE__.'->'.mysql_error());
		$no = mysql_fetch_array($pet_rs); */
		
		$pet_q = "
			SELECT partyname, partysuff, sonof, authcode, state_in_name, prfhname, age, sex, caste, addr1, addr2, state, city, pin, email, contact, dstname, country, a.deptcode, education,
			occ_code, edu_code, lowercase_id, deptname
			FROM caveat_party a
			LEFT JOIN master.deptt b ON a.state_in_name = b.deptcode
			WHERE $fil_diary
			AND pet_res = :flag:
			AND sr_no = :id:
			AND ind_dep = :type:
			AND pflag = 'P'
		";

		// Execute the query using CodeIgniter's database query method with parameter binding
		$pet_rs = $this->db->query($pet_q, [
			'flag' => $_REQUEST['flag'],
			'id' => $_REQUEST['id'],
			'type' => $_REQUEST['type']
		]);

		// Fetch the result as an array (assuming only one row is expected)
		$no = $pet_rs->getRowArray();

		//$partyname = str_replace($no['partysuff'], '', $no['partyname']);

		echo $no['partyname'].'~'.$no['sonof'].'~'.$no['authcode'].'~'.$no['prfhname'].'~'.$no['age'].'~'.$no['sex']
			.'~'.$no['caste'].'~'.$no['addr1'].'~'.$no['addr2'].'~'.$no['state'].'~'.$no['city'].'~'.$no['pin'].'~'.$no['email']
			.'~'.$no['contact'].'~'.$no['dstname'].'~'.$no['country'].'~'.$no['deptcode'].'~'.$no['education'].'~'.$no['occ_code'].'~'.$no['edu_code']
			.'~'.($no['deptname'].''.$no['partysuff']).'~'.($no['partysuff']. ''. $no['partyname']).'~'.($no['lowercase_id']).'~'.$no['state_in_name'].'~'.$no['deptname'];

		 
	}
	
	public function firse_caveatinfo_mod()
	{
			$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$diary_no = $filing_details['diary_no']; 
		
		$fil_diary = " caveat_no = $_REQUEST[fno] ";
		 
		$html = '<th colspan="2">Caveator Parties</th>';
		 
		/* $p_pet_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[fno] AND pet_res='P' AND pflag='P' ORDER BY sr_no";
		$p_pet_rs = mysql_query($p_pet_q) or die(__LINE__.'->'.mysql_error());
		 */
		$p_pet_rs = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='P' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no,ind_dep','A');
		if(!empty($p_pet_rs))
		{
			foreach($p_pet_rs as $p_pet_row)
			{			 
			$html .= '<tr>';
			$html .=	'<td>';
			if($p_pet_row['sr_no']==1)
			{
				$html .= $p_pet_row['sr_no'];
			}else{
				$html .= '<input type="button" value="'.$p_pet_row['sr_no'].'" name="ExMod_P_'.$p_pet_row['sr_no'].'_'.trim($p_pet_row['ind_dep']).'" style="width:25px;text-align: center" />';
			}
			
			$html .= '</td>';
			$html .=	'<td>'.$p_pet_row['partyname'].'</td>';
			$html .= '</tr>';			 
			}
		}
		 
		$html .=	'<th colspan="2">Caveatee Parties</th>    ';
		 
		/* $p_res_q = "SELECT partyname,sr_no FROM caveat_party WHERE caveat_no=$_REQUEST[fno] AND pet_res='R' AND pflag='P' ORDER BY sr_no";
		$p_res_rs = mysql_query($p_res_q) or die(__LINE__.'->'.mysql_error());
		 */
		$p_res_rs = is_data_from_table('caveat_party'," caveat_no= $diary_no AND pet_res='R' AND pflag='P' ORDER BY sr_no ",'partyname,sr_no,ind_dep','A');
		if(!empty($p_res_rs))
		{
			foreach($p_res_rs as $p_res_row)
			{
				$html .= '<tr>';
				$html .=	'<td>';
				if($p_res_row['sr_no']==1)
				{
					$html .= $p_res_row['sr_no'];
				}else{
					$html .= '<input type="button" value="'.$p_res_row['sr_no'].'" name="ExMod_R_'.$p_res_row['sr_no'].'_'.trim($p_res_row['ind_dep']).'" style="width:25px;text-align: center" />';
				}
			
			$html .= '</td>';
				$html .=	'<td>'.$p_res_row['partyname'].'</td>';
				$html .= '</tr>';
			}
		}			
		echo $html;
	}
	
	
	public function get_today_caveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no'];
		echo view('Filing/caveat_report/get_today_caveat',$data);
	}
	
	
	public function caveat_report()
	{
		 
		$ucode =  $_SESSION['login']['usercode'];
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['dateFrom'] = date('Y-m-d',strtotime($_REQUEST['dateFrom']));
		$data['dateTo'] = date('Y-m-d',strtotime($_REQUEST['dateTo']));
		$data['caseTypeId'] = $_REQUEST['caseTypeId'];
		return view('Filing/caveat_report/caveat_report',$data);
	}
	
	public function getNinetyDaysOldCaveat()
	{
		$ucode =  $_SESSION['login']['usercode'];		
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['diary_no'] = $filing_details['diary_no'];
		echo view('Filing/caveat_report/get_nintydays_caveat',$data);
	}
	
	
	public function caveat_ninedays_report()
	{
		$ucode =  $_SESSION['login']['usercode'];
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['dateFrom'] = $date=  date('Y-m-d',strtotime($_REQUEST['dateFrom']));
		$data['dateTo'] = $date1 = date('Y-m-d',strtotime($_REQUEST['dateTo']));
		$data['caveat_list'] = $this->CaveatModel->Caveat_List_Filed($date, $date1);
		
		echo view('Filing/caveat_report/tpl_caveat_ninedays_report',$data);
		die;
	}


	public function search_cat()
	{
		$ucode =  $_SESSION['login']['usercode'];		 
		$filing_details = session()->get('filing_details');		 
		$data['CaveatModel'] = $this->CaveatModel;
		$data['cl_rdn_supreme'] =  $_REQUEST['cl_rdn_supreme'];
		$data['txt_search'] = $_REQUEST['txt_search'];
		$data['caveat_cat_list'] = $this->CaveatModel->caveat_cat_search($_REQUEST['txt_search']);
		 
		echo view('Filing/caveat/search_cat',$data);
		die;
	}
	

}