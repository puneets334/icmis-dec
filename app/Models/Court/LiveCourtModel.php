<?php

namespace App\Models\Court;

use CodeIgniter\Model;

class LiveCourtModel extends Model
{
    protected $eservicesdb;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
    }

    public function getCourtNoWithJudgeName($usercode, $icmis_user_jcode)
    {
        $usercode = session()->get('login')['usercode'];
        $icmis_user_jcode = session()->get('login')['jcode'];

        $judge_code = '';
        $select_display_none = '';
        if ($usercode != 1 && $icmis_user_jcode > 0)
        {
            $judge_code = "AND t3.jcode = $icmis_user_jcode";
            $select_display_none = "display:none;";
        }

        $currentDate = date('Y-m-d');

        $sql = "SELECT DISTINCT t1.courtno, CONCAT(t3.jname, ' ', t3.first_name, ' ', t3.sur_name) AS jname, t3.jcode
        FROM master.roster t1
        INNER JOIN master.roster_judge t2 ON t1.id = t2.roster_id
        INNER JOIN master.judge t3 ON t3.jcode = t2.judge_id
        LEFT JOIN cl_printed cp ON cp.next_dt = '$currentDate' AND cp.roster_id = t1.id AND cp.display = 'Y'
        WHERE cp.next_dt IS NOT NULL 
        AND '$currentDate' >= t1.from_date
        AND t3.jtype = 'R' 
        $judge_code
        AND t3.is_retired = 'N'
        AND t1.display = 'Y'
        AND t2.display = 'Y'
        ORDER BY t3.jcode";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return [];
        }
    }

    public function get_cl_date_judges()
    {
        $usercode = session()->get('login')['usercode'];
        $dcmis_section = session()->get('login')['section'];
        $icmis_user_jcode = session()->get('login')['jcode'];
        $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));

        if($_REQUEST['flag'] == 'court')
        {
            if($icmis_user_jcode > 0 and $usercode != 1)
            {
                $judge_code = "and t3.jcode = $icmis_user_jcode";
                $select_display_none = "display:none;";
            }
            else
            {
                $judge_code = '';
                $selectOption = "<option value=''>Select</option>";
            }
        }
        if($_REQUEST['flag'] == 'reader')
        {
            if($dcmis_section == 62)
            {
                $judge_code = "and (t1.courtno = 21 OR t1.courtno = 61 )";
                $select_display_none = "display:none;";
            }
            else if($dcmis_section == 81)
            {
                $judge_code = "and (t1.courtno = 22 OR t1.courtno = 62 )";
                $select_display_none = "display:none;";
            }
            else
            {
                $selectOption = "<option value=''>Select</option>";
            }
        }

        $sql = "SELECT DISTINCT t1.courtno, CONCAT(t3.jname, ' ', t3.first_name, ' ', t3.sur_name) AS jname, t3.jcode
        FROM master.roster t1
        INNER JOIN master.roster_judge t2 ON t1.id = t2.roster_id
        INNER JOIN master.judge t3 ON t3.jcode = t2.judge_id
        LEFT JOIN cl_printed cp ON cp.next_dt = '$dtd' AND cp.roster_id = t1.id AND cp.display = 'Y'
        WHERE cp.next_dt IS NOT NULL 
        AND '$dtd' >= t1.from_date
        AND t3.jtype = 'R' 
        $judge_code
        AND t3.is_retired = 'N'
        AND t1.display = 'Y'
        AND t2.display = 'Y'
        ORDER BY t3.jcode";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1)
        {
            foreach ($query->getResultArray() as $court)
            {
                $selectOption = $selectOption.'<option value="' . $court["courtno"].'">' . str_replace("\\", "", $court["jname"]) . '</option>';
            }
            echo $selectOption;
        }
        else
        {
            echo $selectOption;
        }
    }

    public function get_title()
    {
        $ucode = session()->get('login')['usercode'];
        $icmis_user_jcode = session()->get('login')['jcode'];
        $courtno = $_REQUEST['courtno'];
        $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));
        $judge_code = "and r.courtno = $courtno ";

        if($courtno > 0)
        {
            $sql = "SELECT r.id, 
            (
               SELECT STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) 
               FROM master.judge j 
               JOIN master.roster_judge rj ON j.jcode = rj.judge_id 
               WHERE rj.roster_id = r.id
               GROUP BY rj.roster_id
            ) AS jcd,
            (
               SELECT STRING_AGG(j.jname, ',' ORDER BY j.judge_seniority)
               FROM master.judge j
               JOIN master.roster_judge rj ON j.jcode = rj.judge_id
               WHERE rj.roster_id = r.id
               GROUP BY rj.roster_id
            ) AS jnm,
            j.first_name,
            j.sur_name,
            j.title,
            r.courtno,
            rb.bench_no,
            mb.abbr,
            mb.board_type_mb,
            r.tot_cases,
            r.frm_time,
            r.session
            FROM master.roster r
            LEFT JOIN master.roster_bench rb ON rb.id = r.bench_id
            LEFT JOIN master.master_bench mb ON mb.id = rb.bench_id
            LEFT JOIN master.roster_judge rj ON rj.roster_id = r.id
            LEFT JOIN master.judge j ON j.jcode = rj.judge_id
            LEFT JOIN cl_printed cp ON cp.next_dt = '$dtd' AND cp.roster_id = r.id AND cp.display = 'Y'
            WHERE cp.next_dt IS NOT NULL
                AND j.is_retired != 'Y'
                AND j.display = 'Y'
                AND rj.display = 'Y'
                AND rb.display = 'Y'
                AND mb.display = 'Y'
                AND r.display = 'Y'
                $judge_code
            GROUP BY r.id, j.first_name, j.sur_name, j.title, r.courtno, rb.bench_no, mb.abbr, mb.board_type_mb, r.tot_cases, r.frm_time, r.session, j.judge_seniority
            ORDER BY r.id, j.judge_seniority";

            $query = $this->db->query($sql);
            if ($query->getNumRows() >= 1)
            {
                $result = $query->getResultArray();

                if ($result[0]['courtno'] == 21)
                {
                    $court = "Registrar Court";
                    $judge_name = $result[0]['first_name'] . ' ' . $result[0]['sur_name'] . ', ' . $result[0]['jnm'];
                }
                else if ($result[0]['courtno'] == 61)
                {
                    $court = "Registrar Virtual Court No. 1";
                    $judge_name = $result[0]['first_name'] . ' ' . $result[0]['sur_name'] . ', ' . $result[0]['jnm'];
                }
                else if ($result[0]['courtno'] == 22)
                {
                    $court = "Registrar Court No. 2";
                    $judge_name = $result[0]['first_name'] . ' ' . $result[0]['sur_name'] . ', ' . $result[0]['jnm'];
                }
                else if ($result[0]['courtno'] == 62)
                {
                    $court = "Registrar Virtual Court No. 2";
                    $judge_name = $result[0]['first_name'] . ' ' . $result[0]['sur_name'] . ', ' . $result[0]['jnm'];
                }
                else
                {
                    $court = "Court No. " . $result[0]['courtno'];
                    $judge_name = $result[0]['jnm'];
                }

                $result = "<p style='font-size: 1.2vw; padding-top: 2px;'>" . 
                $court . ' @ ' . $judge_name . 
                "<span style='font-size: 0.7vw; color: #009acd;'>List Of Business For " . 
                date('l', strtotime($_POST['dtd'])) . ' The ' . 
                date('jS F, Y', strtotime($_POST['dtd'])) . 
                "</span></p>";
                return $result;
            }
            else
            {
                // echo 'else';
                // die;
                
                return '';
            }
        }
        else
        {
            return '';
        }
    }

    public function get_item_nos()
    {
        // pr($_REQUEST);
        $ucode = session()->get('login')['usercode'];
        $crt = $_REQUEST['courtno'];
        $dtd = date('Y-m-d', strtotime($_REQUEST['dtd']));
        // $r_status = $_REQUEST['r_status'];
        $r_status = '';

        if($crt > 0)
        {
            $mf = "M";
            $msg = "";
            $tdt = explode("-", $dtd);
            $tdt1 = $tdt[2] . "-" . $tdt[1] . "-" . $tdt[0];
            $printFrm = 0;
            $pr_mf = $mf;
            $sql_t = "";
            $ttt = 0;

            if ($crt != '')
            {
                if ($mf == 'M')
                {
                    $stg = 1;
                }
                else if ($mf == 'F')
                {
                    $stg = 2;
                }

                $t_cn = " AND courtno = '" . $crt . "' AND ( to_date IS NULL OR '" . $dtd . "' BETWEEN from_date AND to_date )";

                $sql_ro = "
                    SELECT DISTINCT rj.roster_id, 
                    mb.board_type_mb,
                    CASE WHEN r.courtno = 0 THEN 9999 ELSE r.courtno END AS court_order,
                    CASE 
                        WHEN mb.board_type_mb = 'J' THEN 1
                        WHEN mb.board_type_mb = 'C' THEN 2
                        WHEN mb.board_type_mb = 'CC' THEN 3
                        WHEN mb.board_type_mb = 'R' THEN 4
                    END AS board_order,
                    rj.judge_id  -- Include judge_id in the select list
                    FROM master.roster_judge rj
                    JOIN master.roster r ON rj.roster_id = r.id
                    JOIN master.roster_bench rb ON rb.id = r.bench_id AND rb.display = 'Y'
                    JOIN master.master_bench mb ON mb.id = rb.bench_id AND mb.display = 'Y'
                    WHERE r.m_f = CAST($stg AS VARCHAR)
                    AND rj.display = 'Y' 
                    AND r.display = 'Y' 
                    $t_cn
                    ORDER BY court_order, board_order, rj.judge_id";

                $query = $this->db->query($sql_ro);
                $result = '';
                if ($query->getNumRows() >= 1)
                {
                    $result_arr = $query->getResultArray();
                    foreach ($result_arr as $res)
                    {
                        if ($result == '')
                        {
                            $result .= $res['roster_id'];
                        }
                        else
                        {
                            $result .= "," . $res['roster_id'];
                        }
                    }
                }

                $whereStatus = "";
                if ($r_status == 'A')
                {
                    $whereStatus = '';
                }
                else if ($r_status == 'P')
                {
                    $whereStatus = " and m.c_status='P'";
                }
                else if ($r_status == 'D')
                {
                    $whereStatus = " and m.c_status='D'";
                }

                //.........Actual Query......//

                $sql_t = "SELECT 
                    SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS case_no,
                    SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4) AS year,
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
                    COALESCE(CAST(cl.next_dt AS TEXT), 'NA') AS brd_prnt,
                    h.roster_id,
                    m.casetype_id,
                    m.case_status_id,
                    short_description, --20
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
                        'Heardt' AS list_status
                        FROM heardt t1 
                        WHERE 
                        t1.next_dt = '$dtd' 
                        AND t1.mainhead = '$mf'
                        AND t1.roster_id = ANY(string_to_array('$result', ',')::int[])
                        AND (t1.main_supp_flag = 1 OR t1.main_supp_flag = 2) 

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
                        'Last_Heardt' AS list_status  
                        FROM last_heardt t2 
                        WHERE 
                        t2.next_dt = '$dtd'
                        AND t2.mainhead = '$mf'
                        AND t2.roster_id = ANY(string_to_array('$result', ',')::int[])
                        AND (t2.main_supp_flag = 1 OR t2.main_supp_flag = 2)
                        AND (t2.bench_flag = '' OR t2.bench_flag IS NULL)

                        UNION  

                        SELECT 
                        t3.diary_no,
                        t3.cl_date AS next_dt,
                        'Judges' AS judges,
                        t3.roster_id,
                        t3.mf AS mainhead,
                        'Board_Type' AS board_type,
                        t3.part AS clno,
                        t3.clno AS brd_slno,
                        'DELETED' AS list_status 
                        FROM drop_note t3 
                        WHERE 
                        t3.cl_date = '$dtd'
                        AND t3.mf = '$mf'
                        AND t3.roster_id = ANY(string_to_array('$result', ',')::int[])
                    ) h 
                    INNER JOIN main m ON h.diary_no = m.diary_no   
                    LEFT JOIN cl_printed cl ON 
                    cl.next_dt = h.next_dt 
                    AND cl.m_f = h.mainhead 
                    AND cl.part = h.clno
                    AND cl.roster_id = h.roster_id 
                    AND cl.display = 'Y'
                    LEFT JOIN master.casetype c ON m.casetype_id = c.casecode
                    LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'   
                    WHERE cl.next_dt IS NOT NULL $whereStatus
                    GROUP BY 
                    h.diary_no, m.diary_no, m.reg_no_display, m.conn_key, h.mainhead, h.judges, h.board_type, 
                    h.next_dt, h.clno, h.brd_slno, m.pet_name, m.res_name, m.c_status, 
                    brd_prnt, h.roster_id, m.casetype_id, m.case_status_id, short_description, list_status,ct.ent_dt
                    ORDER BY 
                    h.roster_id,
                    CASE 
                        WHEN COALESCE(CAST(cl.next_dt AS TEXT), 'NA') = 'NA' THEN 2 
                        ELSE 1 
                    END,
                    h.brd_slno,
                    CASE 
                        WHEN CAST(m.conn_key AS bigint) = m.diary_no THEN '0000-00-00' 
                        ELSE '99' 
                    END,
                    CASE 
                        WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt 
                        ELSE NULL
                    END,
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4) AS INTEGER),
                    CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER)";

                $sql_t_query = $this->db->query($sql_t);

                if ($sql_t_query->getNumRows() >= 1)
                {
                    $result_arr2 = $sql_t_query->getResultArray();

                    $chk_var = 1;
                    $con_no = "";
                    $odd_even = 1;
                    $html = '';
                    foreach ($result_arr2 as $key => $row10)
                    {
                        $t_diary_no = $row10['diary_no'];
                        $t_next_dt = $row10['next_dt'];
                        $t_list_status = $row10['list_status'];
                        $t_reg_no_display = $row10['reg_no_display'];
                        $caseno = $row10["case_no"] . " / " . $row10["year"];
                        if ($row10['diary_no'] == $row10['conn_key'] OR $row10['conn_key'] == 0)
                        {
                            $print_brdslno = $row10['brd_slno'];
                            $con_no = "1";
                        }
                        else
                        {
                            $print_brdslno = $row10["brd_slno"] . "." . $con_no++;
                        }
                        if ($t_list_status == 'DELETED')
                        {
                            $is_deleted = "style='background-color: #ff0000; color:black;'";
                            $is_disable = "disabled";
                        }
                        else
                        {
                            $is_deleted = "";
                            $is_disable = "";
                        }

                        if ($odd_even % 2 == 0)
                        {
                            $style_colr = "list-group-item-info";
                        }
                        else
                        {
                            $style_colr = "list-group-item-primary";
                        }
                        $odd_even++;
                        $display_board_val1 = $crt . ':' . $mf . ':' . $tdt1 . ':' . str_replace(" - ", " ", $caseno) . ':' . str_replace(":", "&nbsp;", str_replace(" & ", " and ", $row10["pet_name"] . ' Vs ' . $row10["res_name"])) . ':' . $row10["brd_slno"];
                        $display_board_val2 = $row10["judges"];

                        $html .= '<div style="padding-bottom: 1px; padding-top: 1px;" class="item_no list-group-item ' . $is_disable . '"
                                    data-displayboardval1="' . $display_board_val1 . '"
                                    data-displayboardval2="' . $display_board_val2 . '"
                                    data-dno="' . $t_diary_no . '"
                                    data-listdt="' . $t_next_dt . '">
                                    <div class="row">
                                        <div class="column_item1">
                                            <span style="font-size: 0.9vw;">' . $print_brdslno . '</span>
                                        </div>
                                        <div class="column_item4">
                                            <span style="font-size: 0.9vw;">';
                        
                        if (!empty($row10['reg_no_display']))
                        {
                            $html .= $row10['reg_no_display'] . ' <br> DNO. ';
                        }
                        else
                        {
                            $html .= $row10['short_description'] . " .. DNO. ";
                        }
                        
                        $html .= substr_replace($row10['diary_no'], '-', -4, 0);
                        
                        $html .= '</span>
                                <br>
                                <span style="font-size: 0.6vw;">
                                    ' . $row10['pet_name'] . ' <font color="#006400">Vs.</font> ' . $row10['res_name'] . '
                                </span>
                                </div>
                            </div>
                        </div>';
                    }
                    // echo $html;
                    return $html;
                }
                else
                {
                    return '';
                }
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }

    public function get_right_panel_data_row2()
    {
        // pr($_REQUEST);
        $ucode = session()->get('login')['usercode'];
        $diary_no = $_REQUEST['diary_no'];
        $list_dt = $_REQUEST['listdt'];

        $sql_o = "Select * from office_report_details o where o.diary_no = $diary_no and o.order_dt = '$list_dt' and o.display='Y' and o.web_status=1";

        $query = $this->db->query($sql_o);

        // Check for results
        if ($query->getNumRows() > 0)
        {
            $row_o = $query->getRowArray();

            $split_or_path = explode("_",$row_o['office_repot_name']);
            $or_gen_dt = date('d-m-Y H:i:s', strtotime($row_o['rec_dt']));
            $or_address = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name']."#zoom=FitV";
            $or_address_for_path = "http://XXXX/supreme_court/officereport/". $split_or_path[1]."/".$split_or_path[0]."/".$row_o['office_repot_name'];
            $path_info = pathinfo($or_address_for_path);
            if($path_info['extension'] == 'html')
            {
                $obj_type = "text/html";
            }
            else
            {
                $obj_type = "application/pdf";
            }

            $html = '<div style="text-align: left; padding:0px;">
                <p style="font-size: 1.2vw; color: #4169E1;">Office Report <span style="color: #D55C21;">(' . $or_gen_dt . ')</span></p>
                <div class="embed-responsive" style="padding-bottom: 97%;">
                    <object class="embed-responsive-item" data="' . $or_address . '" type="' . $obj_type . '" internalinstanceid="9" title="">
                    <p>Your browser isn\'t supporting embedded pdf files. You can download the file
                    <a href="' . $or_address . '">here</a>.</p>
                    </object>
                    </div>
                </div>';

            return $html;
        }
        else
        {
            $html = '<div style="text-align: left">
                <p style="font-size: 1.2vw; color: #4169E1;">Office Report</p>
                <blockquote><p class="text-info" style="font-size: 1.2vw; color:red;">Oops! Office Report Not Found ...</p>
                </blockquote>
            </div>';
            return $html;
        }
    }

    public function get_gist_details()
    {
        $ucode = session()->get('login')['usercode'];
        $diary_no = $_REQUEST['diary_no'];
        $list_dt = $_REQUEST['listdt'];

        $sql_org = "select * from or_gist org where org.diary_no = $diary_no and org.list_dt = '$list_dt' and org.display = 'Y' ";

        $query = $this->db->query($sql_org);

        // Check for results
        if ($query->getNumRows() > 0)
        {
            $row_org = $query->getRowArray();
            $gist_dt = date('d-m-Y H:i:s', strtotime($row_org['ent_dt']));

            $html = '<div style="text-align: left; padding:0px; text-align: justify; ">
                        <p style="font-size: 1.2vw; color: #4169E1;">Summary <span style="color: #D55C21;">('.$gist_dt.')</span></p>
                        <p class="text-info" style="line-height: 180%; white-space: pre-wrap; font-size: 1.2vw; color: red;">'.$row_org['gist_remark'].'</p>
                    </div>';
            return $html;
        }
        else
        {
            $html = '<div style="text-align: left">
                        <p style="font-size: 1.2vw; color: #4169E1;">Summary</p>
                        <blockquote><p class="text-info" style="font-size: 1.2vw; color:red;">Oops! Summery Not Found ...</p>
                        </blockquote>
                    </div>';
            return $html;
        }
    }
}