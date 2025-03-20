<?php

namespace App\Controllers\Extension;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use CodeIgniter\Model;
use App\Models\Extension\NoticesModel;

class Notices extends BaseController
{
    public $session;
    public $notices_model;

    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $this->notices_model = new NoticesModel;
    }

    public function index()
    {
        return view('Extension/Notices/generate');
    }

    public function generated()
    {
        // Load the database connection
        $db = \Config\Database::connect();
        $data['noticesModel'] = $this->notices_model;
        $data['d_no'] = $this->request->getPost('d_no');
        $data['d_yr'] = $this->request->getPost('d_yr');
        $data['fno'] = $this->request->getPost('fno');
        $data['ct'] = $this->request->getPost('ct');
        $data['cn'] = $this->request->getPost('cn');
        $data['cy'] = $this->request->getPost('cy');
        $data['chk_status'] = $this->request->getPost('chk_status');

        return view('Extension/Notices/generate_view', $data);
    }

    public function getCityName()
    {
        $str = $this->request->getVar('str');
        if (empty($str)) {
           echo '<option value="0">None</option>';
        } else {          
            $result = $this->notices_model->getCitiesName($str);
            foreach($result as $row){
                echo '<option value="' . htmlspecialchars($row['id_no'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</option>';
            }
        }
    }

    public function get_dynamic_cst()
    {
        return view('Extension/Notices/generate');
    }

    public function reprint()
    {
        return view('Extension/Notices/rep_rec_tal');
    }
	
	public function get_rep_rec_tal()
    {
		$ucode= $_SESSION['login']['usercode'];
		 $user_code = "";
		if($ucode!=1)
		{
			 $user_code=" and a.user_id='$ucode'";
		}
		$txtFromDate=date('Y-m-d',  strtotime($_REQUEST['txtFromDate']));
		$txtToDate=date('Y-m-d',  strtotime($_REQUEST['txtToDate']));

  /*$sql = "
SELECT aa.*, bb.s 
FROM (
    SELECT a.diary_no, process_id, a.name, address, b.name nt_type, del_type, tw_sn_to, copy_type, send_to_type, 
           fixed_for, rec_dt, office_notice_rpt, reg_no_display, sendto_district, sendto_state, notice_path, 
           published_by, user_id, dispatch_dt
    FROM tw_tal_del a
    JOIN master.tw_notice b ON CAST(a.nt_type AS INTEGER) = b.id
    JOIN tw_o_r c ON c.tw_org_id = a.id
    JOIN tw_comp_not d ON d.tw_o_r_id = c.id
    JOIN main m ON a.diary_no = m.diary_no
    WHERE rec_dt BETWEEN '$txtFromDate' AND '$txtToDate'
      AND a.display = 'Y' $user_code
      AND print = 1
      AND b.display = 'Y'
      AND c.display = 'Y'
      AND d.display = 'Y'
) aa 
JOIN (
    SELECT COUNT(a.diary_no) s, a.diary_no, a.rec_dt, notice_path 
    FROM tw_tal_del a
    JOIN master.tw_notice b ON CAST(a.nt_type AS INTEGER) = b.id
    JOIN tw_o_r c ON c.tw_org_id = a.id
    JOIN tw_comp_not d ON d.tw_o_r_id = c.id
    JOIN main m ON a.diary_no = m.diary_no
    WHERE rec_dt BETWEEN '$txtFromDate' AND '$txtToDate'
      AND a.display = 'Y' $user_code
      AND print = 1
      AND b.display = 'Y'
      AND c.display = 'Y'
      AND d.display = 'Y'
    GROUP BY a.diary_no, a.rec_dt, notice_path
) bb ON aa.diary_no = bb.diary_no 
     AND aa.rec_dt = bb.rec_dt 
     AND aa.notice_path = bb.notice_path 
ORDER BY aa.diary_no, aa.rec_dt, aa.notice_path;
"; 
	$query = $this->db->query($sql); 
	$data['serve_status'] = $query->getResultArray(); */

    $data['serve_status'] = $this->notices_model->getNoticeDetails($txtFromDate, $txtToDate, $user_code);
 
        return view('Extension/Notices/get_rep_rec_tal',$data);
    }


    public function pocriminal1()
    {
        $data['_REQUEST'] = $_REQUEST;
        $data['noticesModel'] = $this->notices_model;
        return view('Extension/Notices/pocriminal1',$data);
    }

    public function notice_back()
    {
        $dis_co_nm = '';
        $diary_no = $_REQUEST['fil_no'];
        $year_s = substr($diary_no, -4);
        $no_s = substr($diary_no, 0, strlen($diary_no) - 4);
        $rec_dt = $_REQUEST['dt'];
        $fil_nm=trim($_REQUEST['fil_nm']);
        
        $x=explode('../pdf_notices/',$fil_nm);        
        $notice_path=trim($x[1],' ');
         
        $result2= "update tw_tal_del set display='N' where diary_no=$diary_no and rec_dt='$rec_dt' and notice_path='$notice_path'";
        echo $query = $this->db->query($result2); 
        die;
    }


    public function save_content()
    {
            $year=substr( $_REQUEST['fil_no'] , -4 );
            $diary_no=substr( $_REQUEST['fil_no'], 0, strlen( $_REQUEST['fil_no'] ) -4 );
            $ucode= $_SESSION['login']['usercode'];            

            $user_ip = getClientIP();
 

            $master_path='../';

            $path='pdf_notices';
            chdir($master_path);
            if(!file_exists($path))
                mkdir ($path,0755,true);
            chdir($path);
            if(!file_exists($year))
                mkdir ($year);
            chdir($year);

            if(!file_exists($diary_no))
                mkdir ($diary_no);
            chdir($diary_no);
 
            if($_REQUEST['z_chk_status']==1)
            {
                $not_path = str_replace('../pdf_notices/','',$_REQUEST['hd_active_filez']);
                $file_name=explode('/',$_REQUEST['hd_active_filez']);
                $file_name1=$file_name[4];            
                $sql=$this->db->query("Update  tw_tal_del set print='1',published_by=$ucode, userip='$user_ip',published_on=now() where diary_no='$_REQUEST[fil_no]' and rec_dt='$_REQUEST[dt]' and display='Y' and notice_path='$not_path'");
                $gh=fopen($file_name1, 'w');
                fwrite($gh, $_REQUEST['str']);
                fclose($gh);
            }
            else
            {
                $fil_nm=$_REQUEST['fil_no'].'_'.$_REQUEST['dt'].time().".html";

                $fil_nm1=$year."/".$diary_no."/".$fil_nm;                
                $sql=$this->db->query("Update  tw_tal_del set print='1',notice_path='$fil_nm1',published_by=$ucode, userip='$user_ip',published_on=now() where diary_no='$_REQUEST[fil_no]' and rec_dt='$_REQUEST[dt]' and display='Y' and print=0");
                $gh=fopen($fil_nm, 'w');
                fwrite($gh, $_REQUEST['str']);
                fclose($gh);
            }
    }


    public function save_pdf_html()
    {
        $cks_ids=urldecode($_REQUEST['cks_ids']);
        $id_d=$_REQUEST['id_d'];
  
        $year=substr( $_REQUEST['fil_no'] , -4 );
            $diary_no=substr( $_REQUEST['fil_no'], 0, strlen( $_REQUEST['fil_no'] ) -4 ); 

        $master_path='../';
       
        $path='pdf_notices';
        chdir($master_path);
        if(!file_exists($path))
            mkdir ($path,0755,true);
        chdir($path);
        if(!file_exists($year))
            mkdir ($year);
        chdir($year);

        if(!file_exists($diary_no))
            mkdir ($diary_no);
        chdir($diary_no);


        $ex_id_d=explode('~!@#$', $cks_ids);

        $ex_cks_ids=explode(',', $id_d);

        for ($index = 0; $index < count($ex_cks_ids); $index++) 
        {
 
        $hd_full_data= $ex_id_d[$index];
        $hd_full_data=  str_replace('face="Times New Roman"',"style=\"font-family: 'Times New Roman'\"",$hd_full_data) ;
        $hd_full_data=  str_replace("face=\"'Kruti Dev 010'\"","style=\"font-family: 'Kruti Dev 010'\"",$hd_full_data) ; 
        $hd_full_data="<!DOCTYPE html><html> <head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'></head> <body> ".$hd_full_data." </body> </html>";
        

        $er= $ex_cks_ids[$index].'.html';
        $gh=fopen($er, 'w');
        fwrite($gh, $hd_full_data);
        fclose($gh);

        $new_name=$ex_cks_ids[$index].'.pdf';
        $new_name1=$ex_cks_ids[$index].'_cy.pdf';
      
        if(file_exists($er))
            {
        
                exec ('html2pdf -s Legal  /home/notices/'.$year.'/'.$diary_no.'/'.$er.' '.$new_name , $output, $return);
                if ($return) 
                            {
                                $rt=0;

                            }
                            else
                {
                    $rt=1;
                }

                if($rt==1)
                {
                    $c_date=date('Y-m-d');                 

                    $res_chk_notice_pnt =  $this->notices_model->getIndividualMultiple($_REQUEST['fil_no'], $c_date);                   

                    $path=$year.'/'.$diary_no.'/'.$_REQUEST['fil_no'].'_'.$c_date.'.html';
                    $upd_main_no="Update tw_tal_del set notice_path='$path' where diary_no='$_REQUEST[fil_no]'  and rec_dt='$c_date' and display='Y' and print=0";
                    $upd_main_no=  $this->db->query($upd_main_no);
                    
                    $ex_explode=  explode('_', $er);
                    $ex_mode= explode('.html', $ex_explode[2]);
                    
                
                    
                    if($res_chk_notice_pnt=='1')
                    {                        
                        $ex_explode=  $ex_explode[0];
                        $ex_mode=  $ex_mode[0];
                        $sel_mod = is_data_from_table('tw_o_r',  " tw_org_id='$ex_explode' and del_type='$ex_mode'  and display='Y' "," id ",'');
                        $sel_mod = !empty($sel_mod) ?  $sel_mod['id'] : '';
                    }
                    else  if($res_chk_notice_pnt=='2')
                    {
                        $ext_ids='';                        
                        $sel_letter =  $this->notices_model->getLetterIds($_REQUEST['fil_no']);
                        if(!empty($sel_letter))
                        {
                            $ids='';
                            foreach($sel_letter as $row) {
                                if($ids=='')
                                    $ids=$row['id'];
                                else 
                                    $ids=$ids.','.$row['id'];                                
                                    $row_id = $row['id'];
                                    $sel_mod_ff = is_data_from_table('tw_o_r',  " tw_org_id='$row_id'  and display='Y' "," id ",'A');

                                    $er_ss_s=$year.'/'.$diary_no.'/'.$er;
                                    if(!empty($sel_mod_ff))
                                    {
                                        foreach ($sel_mod_ff as $row1) {
                                            $upd_mod_ff=$this->db->query("Update tw_o_r set mode_path='$er_ss_s' where id='$row1[id]'");                                           
                                        }
                                    }
                            }
                        }
                        if($ids!='')
                        {
                            $ext_ids=" and b.id not in($ids)";
                        }
                       
                        $r_get_diary_modes =  $this->notices_model->getDiaryModes($ex_explode[0], $ex_mode[0], $ext_ids);
                            
                        if(!empty($r_get_diary_modes))
                        {
                            $sel_mod_result =  $this->notices_model->getSelectedMode($r_get_diary_modes['diary_no'], $r_get_diary_modes['rec_dt'], $r_get_diary_modes['del_type']);
                            $sel_mod=  !(Empty($sel_mod_result)) ?  $sel_mod_result['id'] : '';
                        }else
                        {
                            $sel_mod = '';
                        }
                    }
                
                        $er_ss=$year.'/'.$diary_no.'/'.$er;
                        if (!empty($sel_mod))
                        {                    
                            $upd_mod=$this->db->query("Update tw_o_r set mode_path='$er_ss' where id='$sel_mod'");                                       
                        
                        }
                }
                
            }
        }
    }


    public function publish_record()
    {
        $fil_no = $_REQUEST['fil_no'];
        $dt = date('Y-m-d', strtotime($_REQUEST['dt']));
        $hd_off_notice = $_REQUEST['hd_off_notice'];
        $res_chk_data = $this->notices_model->checkDataCount($fil_no, $dt, $hd_off_notice);
        if($res_chk_data > 0)
        {
            $sql=$this->db->query("Update  tw_tal_del set web_status ='1' where diary_no='$_REQUEST[fil_no]' and 
                rec_dt='$_REQUEST[dt]' and display='Y' and office_notice_rpt='$_REQUEST[hd_off_notice]'");
            if(!empty($sql))
            {
                echo "Data Publish Successfully"; 
            }
            else 
            {
                echo "Data is not Publish, Please contact to Computer cell!!";
            }
        }
        else 
        {
            echo "Please save data before Publish";
        }
    }

    public function draft_record()
    {

    }
	
	
}
