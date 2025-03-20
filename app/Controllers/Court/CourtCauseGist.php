<?php

namespace App\Controllers\Court;
use App\Controllers\BaseController;
use App\Models\Casetype;
use CodeIgniter\Controller;
use App\Models\Court\CourtMasterModel;
use App\Libraries\phpqrcode\Qrlib;
use App\Libraries\Fpdf;

class CourtCauseGist extends BaseController
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

          if(empty(session()->get('filing_details')['diary_no'])){
            header('Location:'.base_url('Filing/Diary/search'));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }
    
    }

    public function index()
    {
        
        return view('Court/CourtGist/index');
    }

    public function get_cause_title()
     {
        $diary_no = $this->diary_no;

        $row = $this->model->getCauseTitle($diary_no);
        if(!empty($row))
        {         
            echo "</br><font style='text-align: center;font-size: 14px;color: red'>Cause Title: </font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>".$row['pet_name']."</font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>VS</font></br>";
                echo "<font style='text-align: center;font-size: 14px;color: blue'>".$row['res_name']."</font></br>";
            
        }
        else
        {
            echo "<font style='text-align: center;font-size: 14px;color: black'>Case not found</font>";
        }
    }

    public function gist_get()
    {
        $sp_listed_on=date('Y-m-d',  strtotime($_REQUEST['ddl_ord_date'])) ;
        $diary_no = $this->diary_no;
         
        $sql_result = is_data_from_table('main', " diary_no=$diary_no ", " c_status ", $row = '');

        if(!empty($sql_result))
        {
            $res_sel = is_data_from_table('office_report_details', " diary_no=$diary_no  and order_dt='$sp_listed_on' and display='Y' ", " summary ", $row = '');
            //pr($res_sel);
            echo $res_sel['summary'] ?? '';        

        }else
        {
            echo "No Record Found";
        }
    }

    public function gist_module_upload_report()
    {
        $ucode= $_SESSION['login']['usercode'];
        $sp_listed_on=date('Y-m-d',  strtotime($_REQUEST['ddl_ord_date'])) ;
        $summary = $_REQUEST['summary'];    
            $diary_no=$this->diary_no ;
            //$sql="Select c_status from main where diary_no='$_REQUEST[fno]'";
            //$sql=  mysql_query($sql) or die("Error: ".__LINE__.mysql_error());

            $res_sql = is_data_from_table('main', " diary_no=$diary_no ", " c_status ", $row = '');
            if(!empty($res_sql))             
            {
               // $sel_rec="Select count(id) as total,id,office_repot_name from office_report_details where diary_no='$dairy_no' and order_dt='$sp_listed_on' and display='Y' ";
               // $sel_rec=mysql_query($sel_rec) or die("Error: ".__LINE__.mysql_error());
                //$res_sel=mysql_fetch_assoc($sel_rec);

                $res_sel = $this->model->getOfficeReportDetails($diary_no, $sp_listed_on);
                
                if(!empty($res_sel) && $res_sel['total'] <= 0)
                {                    
                    //$res_sql=  mysql_result($sql, 0);
                    if($res_sql['c_status'] == 'D')
                    {
                        echo "Case already disposed";
                    }
                    else 
                    {
                        //$check_da="select dacode from main where diary_no='$_REQUEST[fno]'";
                        //$check_da=mysql_query($check_da)or die("Error: ".__LINE__.mysql_error());
                        //$check_da=mysql_result($check_da,0);

                        //$res_sql = is_data_from_table('main', " diary_no=$diary_no ", " dacode ", $row = '');
                        
                        //$check_section="select section from users where usercode='$ucode'";
                        //$check_section=mysql_query($check_section)or die("Error: ".__LINE__.mysql_error());
                        //$check_section=mysql_result($check_section,0);
                        
                        ?>
                            <input type="hidden" name="hd_diary_no" id="hd_diary_no" value="<?php echo  $diary_no; ?>"/>
                        <?php
                
                
                            $office_repot_name = NULL;
                            /* $ins_rec="Insert Into office_report_details (diary_no,rec_dt,rec_user_id,office_repot_name,order_dt,web_status,summary) 
                            values ('$dairy_no',now(),'$ucode','$fil_nm','$sp_listed_on','1','".mysql_real_escape_string($_REQUEST['summary'])."')"; */

                            $ins_rec = $this->model->addOfficeReportDetails($diary_no,$ucode,$office_repot_name,$sp_listed_on,$summary);
                             
                            if(!empty($ins_rec))
                            {
                                echo "Record Save Successfully";
                            }
                            else 
                            {
                                die("Error: Contact to Computer Cell");
                               
                            }
                    
                    }
                }
                else 
                {
                    //$res_sql=  mysql_result($sql, 0);    
                    if($res_sql['c_status'] == 'D')
                    {
                        echo "Case already disposed";
                    }
                    else 
                    {
                       
                        if(!empty($res_sel))
                        {
                            $user_ip = getClientIP();                
                            $upd_rec = $this->db->query("Update office_report_details SET display='N' where id='".$res_sel['id']."' ");                        
                            
                            if($upd_rec)                        
                            {    
                                /*$insert_rec="Insert Into office_report_details (diary_no,rec_dt,rec_user_id,office_repot_name,order_dt,web_status,summary,display,user_ip) 
                            values ('$diary_no',now(),'$ucode','".$res_sel['office_repot_name']."','$sp_listed_on','1', '".$_REQUEST['summary']."','Y','$user_ip')";
                            

                                if($this->db->query($insert_rec)){
                                    echo "Record Updated Successfully";
                                } */

                                    $office_repot_name =  $res_sel['office_repot_name'];
                                    $ins_rec = $this->model->addOfficeReportDetails($diary_no,$ucode,$office_repot_name,$sp_listed_on,$summary);
                             
                                    if(!empty($ins_rec))
                                    {
                                        echo "Record Save Successfully";
                                    }
                                    else 
                                    {
                                        die("Error: Contact to Computer Cell");
                                    
                                    }

                            }else
                            {
                                die("Error: Record is not updated!!");
                            }
                        }else{
                            $office_repot_name = '';
                            // Insert the record                            
                            $ins_rec = $this->model->addOfficeReportDetails($diary_no,$ucode,$office_repot_name,$sp_listed_on,$summary);
                             
                            if(!empty($ins_rec))
                            {
                                echo "Record Save Successfully";
                            }
                            else 
                            {
                                die("Error: Contact to Computer Cell");
                               
                            }
                            
                        }
                
                        
                    }
                }
            }else{
                echo "No Record Found";
            }
    }

}
