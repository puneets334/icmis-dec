<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\Library\AdminusersModel;

class LiveCourt extends BaseController
{
    protected $db;
    public $AdminusersModel;
    public $e_services;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->AdminusersModel = new AdminusersModel();
        $this->e_services = \Config\Database::connect('eservices');
    }

    public function LiveCourt_view()
    {
        $sessionData = $this->session->get('login')['usercode'];
        
        return view('Library/live_court');
    }

    public function get_title()
    {
       
        $courtno= $_GET['courtno'];        
        $judge_code = "and r.courtno = $courtno ";
        $dtd= date('Y-m-d', strtotime($_GET['dtd']));
        if($courtno > 0){      

        $row = $this->AdminusersModel->getRosterData($dtd, $judge_code);
        if (!empty($row)){
           
            if ($row['courtno'] == 21) {
                $court = "Registrar Court No. 1";
                $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
            }
            else if ($row['courtno'] == 61) {
                $court = "Registrar Virtual Court No. 1";
                $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
            }
            else if ($row['courtno'] == 22) {
                $court = "Registrar Court No. 2";
                $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
            }
            else if ($row['courtno'] == 62) {
                $court = "Registrar Virtual Court No. 2";
                $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
            } else {
                $court = "Court No. " . $row['courtno'];
                $judge_name = $row['jnm'];
            }
            ?>
            <p style="font-size: 1.2vw; padding-top: 2px;"><?php echo $court . ' @ ' . $judge_name;
                ?><br>
                <span style="font-size: 0.7vw; color: #009acd; ">List Of Business For <?php echo date('l', strtotime($_GET['dtd'])) . ' The ' . date('jS F, Y', strtotime($_GET['dtd'])); ?></span>
            </p>

            <?php
            ?>
            <?php
        }
        else {
            echo "Error!...";
        }
        }
    }


    public function get_item_nos(){
        $crt = $_REQUEST['courtno'];
        $dtd = $_REQUEST['dtd'];
        $r_status=$_REQUEST['r_status'] ?? '';

        if($crt > 0) {
   
            $mf = "M";
     
            $msg = "";
            $tdt1 = date('Y-m-d',strtotime($dtd));
            $printFrm = 0;

            ///=====Not Show If Cause List Not Print
            $pr_mf = $mf;
            $sql_t = "";
            $ttt = 0;

            if ($crt != '') {
                if ($mf == 'M') {
                    $stg = 1;
                } else if ($mf == 'F') {
                    $stg = 2;
                }                
                //$t_cn = " and `courtno` = '" . $crt . "' AND if(to_date = '' and r.m_f = 2, to_date = '', '" . $tdt1 . "' BETWEEN from_date AND to_date) ";
               
                $t_cn = " AND courtno = '" . $crt . "' AND ( (to_date IS NULL AND r.m_f = '2') OR ('" . $tdt1 . "' BETWEEN from_date AND to_date) )";
                
  
                $sql_ro = $this->AdminusersModel->getRosterJudgeData($t_cn);
                $result = '';
                if(!empty($sql_ro))
                {
                    foreach ($sql_ro as $res) {
                        if ($result == '')
                            $result .= $res['roster_id'];
                        else
                            $result .= "," . $res['roster_id'];
                    }
                }
                $whereStatus = "";
                if ($r_status == 'A') {
                    $whereStatus = '';
                } else if ($r_status == 'P') {
                    $whereStatus = " and m.c_status='P'";
                } else if ($r_status == 'D') {
                    $whereStatus = " and m.c_status='D'";
                }
 
            $results10 = $this->AdminusersModel->getCaseBoardList($tdt1, $result, $whereStatus);
                
            }

            $jc = "";
            $chk_var = 0;
            $not_avail = "";
            if (!empty($results10)) {
                ?>
                <div class="list-group list-group-mine">
                <?php
                $chk_var = 1;
                $con_no = "";
                $odd_even = 1;
                foreach ($results10 as $row10) {
                    $t_diary_no = $row10['diary_no'];
                    $t_next_dt = $row10['next_dt'];
                    $t_list_status = $row10['list_status'];
                    $t_reg_no_display = $row10['reg_no_display'];
                    $caseno = $row10["case_no"] . " / " . $row10["year"];
                    if ($row10['diary_no'] == $row10['conn_key'] OR $row10['conn_key'] == 0) {
                        $print_brdslno = $row10['brd_slno'];
                        $con_no = "1";
                    } else {
                        $print_brdslno = $row10["brd_slno"] . "." . $con_no++;
                    }
                    if ($t_list_status == 'DELETED') {
                        $is_deleted = "style='background-color: #ff0000; color:black;'";
                        $is_disable = "disabled";
                    } else {
                        $is_deleted = "";
                        $is_disable = "";
                    }

                    if ($odd_even % 2 == 0) {
                        /*$style_colr = "#99ddff";*/
                        $style_colr = "list-group-item-info";
                    } else {
                        /*$style_colr = "#b3e6ff";*/
                        $style_colr = "list-group-item-primary";
                    }
                    $odd_even++;
                    $display_board_val1 = $crt . ':' . $mf . ':' . $tdt1 . ':' . str_replace(" - ", " ", $caseno) . ':' . str_replace(":", "&nbsp;", str_replace(" & ", " and ", $row10["pet_name"] . ' Vs ' . $row10["res_name"])) . ':' . $row10["brd_slno"];
                    $display_board_val2 = $row10["judges"];
                   
                $sql_eservice = "select * from library_reference_material where list_date =  '" . $tdt1 . "' and diary_no = '".$row10['diary_no']."' and is_active = 1 limit 1";
                 
                   // $sql_verify1 = $dbo_eservices->prepare($sql_eservice);
                   $sql_verify1 = $this->e_services->query($sql_eservice);
                    $sql_verify1_count = $sql_verify1->getNumRows();
                    if ($sql_verify1_count > 0) {
                        
                        $request_found = '<i class="fa fa-envelope text-danger pull-right m-2"></i>';                         
                    }
                    else{
                        $request_found = '';
                    }
                    ?>

                    <!--style=""-->
                    <div style="padding-bottom: 1px; padding-top: 1px;" class="item_no list-group-item <?= $is_disable; ?>"
                        data-displayboardval1="<?= $display_board_val1; ?>"
                        data-displayboardval2="<?= $display_board_val2; ?>" data-dno="<?= $t_diary_no; ?>"
                        data-listdt="<?= $t_next_dt; ?>">
                        <div class="row"<?= $is_deleted; ?> >

                            <div class="column_item1"><span style="font-size:0.9vw;"><?= $print_brdslno; ?></span></div>
                           
                            <div class="column_item4"><span style="font-size:0.9vw;">
                    <?php
                    echo $request_found;
                    if ($row10['reg_no_display']) {
                        echo $row10['reg_no_display'] . ' <br> DNO. ';
                    } else {
                        echo $row10['short_description'] . " .. DNO. ";
                    }
                    echo substr_replace($row10['diary_no'], '-', -4, 0);
                   
                    echo '</span><br><span style="font-size:0.6vw;">' . $row10['pet_name'] . ' <font color="#006400">Vs.</font> ' . $row10['res_name'] . '</span>';

                    ?>


                            </div>

                        </div>


                    </div>
                    <?php
                }
                ?>
                </div>

                <?php
            } else
                echo 'No Records Found';
            ?>

            <?php
        }
    }

    public function get_gist_details()
    {
        extract($_POST);
        $diary_no = $_POST['diary_no'];
        $list_dt = $_POST['listdt'];
        $data['AdminusersModel'] = $this->AdminusersModel;
        $data['advocate_res'] = is_data_from_table('advocate'," diary_no='$diary_no' and display='Y' ", 'pet_res','');

        return  view('Library/get_gist_details',$data);
    }
}