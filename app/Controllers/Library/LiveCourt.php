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
        // pr($sessionData);
        // $usercode = $sessionData['login']['usercode'];
        return view('Library/live_court');
    }

    public function get_title()
    {
        //$ucode = $this->session->get('login')['usercode'];
        $courtno= $_GET['courtno'];
        //$icmis_user_jcode = $_SESSION['icmis_user_jcode'];

        $judge_code = "and r.courtno = $courtno ";

        $dtd= date('Y-m-d', strtotime($_GET['dtd']));
        if($courtno > 0){
       /* $sql = "SELECT r.id, GROUP_CONCAT(distinct j.jcode ORDER BY j.judge_seniority) jcd, 
        GROUP_CONCAT(distinct j.jname ORDER BY j.judge_seniority SEPARATOR ', ') jnm, j.first_name, j.sur_name, title,
                        r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session FROM roster r 
                        LEFT JOIN roster_bench rb ON rb.id = r.bench_id 
                        LEFT JOIN master_bench mb ON mb.id = rb.bench_id
                        LEFT JOIN roster_judge rj ON rj.roster_id = r.id 
                        LEFT JOIN judge j on j.jcode = rj.judge_id
                        LEFT JOIN cl_printed cp on cp.next_dt = '$dtd' and cp.roster_id = r.id and cp.display = 'Y' 
                        WHERE cp.next_dt is not null and j.is_retired != 'Y' and j.display  = 'Y' 
                        and rj.display = 'Y' and rb.display = 'Y' and mb.display = 'Y' 
                        and r.display = 'Y' $judge_code group by r.id
                        ORDER BY r.id, j.judge_seniority";

        $res = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($res) > 0){
        $row = mysql_fetch_array($res); */

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

                
                $t_cn = "AND courtno = '" . $crt . "' AND ( (to_date IS NULL AND r.m_f = 2) OR ('" . $tdt1 . "' BETWEEN from_date AND to_date) )";
                


         /*   $sql_ro = "SELECT DISTINCT 
    roster_id, board_type_mb
    FROM
    `roster_judge` rj 
    JOIN `roster` r 
        ON rj.roster_id = r.id 
        JOIN `roster_bench` rb ON rb.id=r.bench_id AND rb.display='Y'
        JOIN `master_bench` mb ON mb.id=rb.`bench_id` AND mb.`display`='Y'
    WHERE r.m_f in (1,2) " . $t_cn . "
    AND rj.display = 'Y' 
    AND r.display = 'Y' 
    ORDER BY if(courtno=0,9999,courtno),
    case 
    when board_type_mb='J' then 1
    when board_type_mb='S' then 2
    when board_type_mb='C' then 3
    when board_type_mb='CC' then 4
    when board_type_mb='R' then 5

    END, judge_id ";

                $sql_ro = mysql_query($sql_ro); */


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

            /*    $sql_t = "SELECT 
        SUBSTR(m.diary_no, 1, LENGTH(m.diary_no) - 4) AS case_no,
        SUBSTR(m.diary_no, - 4) AS year,  
        m.diary_no,
        m.reg_no_display,    
        m.conn_key,   
        h.mainhead,
        h.judges,
        h.board_type,
        h.next_dt,   
        h.clno,
        h.brd_slno,
        m.pet_name,
        m.res_name,
        m.c_status,
        IF(
        cl.next_dt IS NULL,
        'NA',
        h.brd_slno
        ) AS brd_prnt,
        h.roster_id,    
        m.casetype_id,
        m.case_status_id,
        short_description,
        list_status
    FROM
        (
        
    SELECT 
    t1.diary_no,
    t1.next_dt,
    t1.judges,
    t1.roster_id,  
    t1.mainhead,
    t1.board_type,
    t1.clno,
    t1.brd_slno,  
    t1.main_supp_flag,
    'Heardt' list_status
    FROM
    heardt t1 
    WHERE 
    t1.next_dt = '" . $tdt1 . "' 
            and t1.mainhead in ('M', 'F')
        AND FIND_IN_SET(t1.roster_id,'" . $result . "') >0
    AND (
        t1.main_supp_flag = 1 
        OR t1.main_supp_flag = 2
    ) 
    UNION
    SELECT 
    t2.diary_no,
    t2.next_dt,
    t2.judges,
    t2.roster_id,  
    t2.mainhead,
    t2.board_type,
    t2.clno,
    t2.brd_slno,  
    t2.main_supp_flag,
    'Last_Heardt' list_status  
    FROM
    last_heardt t2 
    WHERE 
    t2.next_dt = '" . $tdt1 . "' 
            and t2.mainhead in ('M', 'F')
        AND FIND_IN_SET(t2.roster_id,'" . $result . "') >0
    AND (
        t2.main_supp_flag = 1 
        OR t2.main_supp_flag = 2
    ) 
    AND (t2.bench_flag = '' OR t2.bench_flag is null)
    UNION  
    SELECT 
    t3.diary_no,
    t3.cl_date as next_dt,
    'Judges' as judges,
    t3.roster_id,  
    t3.mf as mainhead,
    'Board_Type' board_type,  
    t3.part as clno,
    t3.clno as brd_slno, 
    'Main_supp_flag' main_supp_flag,
    'DELETED' list_status 
    FROM
    drop_note t3 
    WHERE 
    t3.cl_date = '" . $tdt1 . "' 
            and t3.mf in ('M', 'F')
        AND FIND_IN_SET(t3.roster_id,'" . $result . "') >0
        ) h 
        INNER JOIN main m ON h.diary_no = m.diary_no   
        LEFT JOIN cl_printed cl 
        ON (
            cl.next_dt = h.next_dt 
            AND cl.m_f = h.mainhead 
            AND cl.part = h.clno
            AND cl.roster_id = h.roster_id 
            AND cl.display = 'Y'
        )
        left join casetype c on m.casetype_id = c.casecode
            LEFT JOIN conct ct on m.diary_no=ct.diary_no and ct.list='Y'  
        where  cl.next_dt IS NOT NULL $whereStatus
    group by h.diary_no ORDER BY
        IF(brd_prnt = 'NA', 2, 1),
        h.brd_slno,
        IF(m.conn_key = m.diary_no,'0000-00-00',99) ASC,
        if(ct.ent_dt is not null,ct.ent_dt,999) ASC,
        cast(SUBSTRING(m.diary_no,-4) as signed) ASC, cast(LEFT(m.diary_no,length(m.diary_no)-4) as signed ) ASC
        "; */

            $results10 = $this->AdminusersModel->getCaseBoardList($tdt1, $result, $whereStatus);
                
            }

            //$results10 = mysql_query($sql_t) or die(mysql_error());
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
                    include("../menu_assign/config.php");
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
}