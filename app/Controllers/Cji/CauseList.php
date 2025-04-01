<?php

namespace App\Controllers\Cji;

use App\Controllers\BaseController;
use App\Models\CJI\Cji;

class CauseList extends BaseController
{

    public function index()
    {
        $data['usercode'] = (int)session()->get('login')['usercode'];
        return view('CJI/causelist', $data);
    }

    public function cause_list_process()
    {
        
        // Fetch list date from the request and format it as needed
        $list_date = $this->request->getPost('txt_frm_dt');
        $formatted_list_date = date("Y-m-d", strtotime($list_date));

        
        // Database connection
        $db = \Config\Database::connect();
        $builder = $db->table('master.roster_judge rj');

        // Build the query
        // Build the query
            $builder->distinct()
            ->select("r.m_f, rj.roster_id, r.courtno, STRING_AGG(j.jname, ' ' ORDER BY j.judge_seniority) AS judge_name, mb.board_type_mb, MIN(cl.from_brd_no) AS min_brd_no")
            ->join('master.roster r', 'rj.roster_id = r.id')
            ->join('master.roster_bench rb', "rb.id = r.bench_id AND rb.display = 'Y'")
            ->join('master.master_bench mb', "mb.id = rb.bench_id AND mb.display = 'Y'")
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->join('cl_printed cl', "cl.roster_id = rj.roster_id AND cl.display = 'Y'", 'left')
            ->where('cl.roster_id IS NOT NULL')
            ->ORwhere('rj.roster_id', 2554)
            ->where('rj.display', 'Y')
            ->where('r.display', 'Y')
            ->where('mb.board_type_mb', 'J')
            ->groupStart()
                ->where('r.courtno', '1')
                ->orWhere('r.courtno', '31')
            ->groupEnd()
            ->groupStart()
                ->where("COALESCE(CAST(r.to_date AS date), '9999-12-31')", '9999-12-31');

        if ($formatted_list_date != '1970-01-01') {
            $builder->where("CAST(r.from_date AS date)", $formatted_list_date);
        }

        $builder->orGroupStart()
                ->where("'$formatted_list_date' BETWEEN CAST(r.from_date AS date) AND COALESCE(CAST(r.to_date AS date), '9999-12-31')", null, false)
            ->groupEnd()
            ->groupEnd()
            ->groupBy('rj.roster_id, r.m_f, r.courtno, mb.board_type_mb')
            ->orderBy('rj.roster_id')
            ->orderBy('min_brd_no');
        $builder->limit(10);
        $builder->offset(1890);
        // Execute the query
        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        $rosterResults = $query->getResultArray();
        $title = '';
        $list_day = '';
        //print_r($rosterResults);
        // Iterate over each result and build the title and data for the table
        $total_count_nested = 0 ; 
        foreach ($rosterResults as $res) {
            //pr($res);
            $whereClause = '';  
            if ($formatted_list_date != '1970-01-01') {
                $whereClause = " AND h.next_dt = '$formatted_list_date'";
            }

            $title .= "Daily Cause List for " . $list_date . "<br><br>" . $list_day . "<br><br>Court No. ";

            if ($res['courtno'] > 60) {
                $title .= "VC " . ($res['courtno'] - 60);
            } elseif ($res['courtno'] > 30) {
                $title .= "VC " . ($res['courtno'] - 30);
            } else {
                $title .= $res['courtno'];
            }


            // Second SQL Query (Fetching Case Details)
            $sql = "SELECT 
                    m.reg_no_display,
                    concat(pet_name,' Vs. ',res_name) as Cause_Title,    
                    m.diary_no,    
                    m.conn_key,
                    h.judges,
                    h.mainhead,
                    h.next_dt,
                    CASE 
                        WHEN (sm.category_sc_old IS NOT NULL AND sm.category_sc_old != '' AND sm.category_sc_old != '0')
                        THEN CONCAT(sm.category_sc_old,'-',sm.sub_name1,'-',sm.sub_name4)
                        ELSE CONCAT('(',CONCAT(sm.subcode1,'',sm.subcode2),')',sm.sub_name1,'-',sm.sub_name4)
                    END AS subject_category,
                    h.subhead,
                    s.stagename,
                    h.clno,
                    h.brd_slno,
                    h.tentative_cl_dt,    
                    CASE 
                        WHEN cl.next_dt IS NULL THEN 'NA' 
                            ELSE h.brd_slno::TEXT 
                    END AS brd_prnt,
                    m.c_status,
                    h.roster_id,    
                    m.casetype_id,
                    m.case_status_id
                FROM heardt h
                INNER JOIN main m ON (h.diary_no = m.diary_no
                 $whereClause
                  AND h.roster_id = {$res['roster_id']} AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2))
                LEFT JOIN master.subheading s ON s.stagecode = h.subhead
                LEFT JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                LEFT JOIN master.submaster sm ON sm.id = mc.submaster_id AND sm.display = 'Y'
                LEFT JOIN cl_printed cl ON (cl.next_dt = h.next_dt AND cl.m_f = h.mainhead AND cl.part = h.clno AND cl.main_supp = h.main_supp_flag AND cl.roster_id = h.roster_id AND cl.display = 'Y')
                LEFT JOIN conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
                WHERE cl.next_dt IS NOT NULL
                ORDER BY h.brd_slno, 
                         CASE 
                            WHEN m.conn_key = m.diary_no::TEXT THEN '0000-00-00'
                            ELSE '99' 
                        END ASC 
                         --CASE WHEN ct.ent_dt IS NOT NULL THEN ct.ent_dt ELSE 999 END ASC, 
                         --CAST(SUBSTRING(m.diary_no, -4) AS INTEGER) ASC, 
                         --CAST(LEFT(m.diary_no, LENGTH(m.diary_no) - 4) AS INTEGER) ASC
                         ";
            //echo $sql;
            $caseResults = $db->query($sql)->getResultArray();  // Fetch case details
            $content = '';
            if (count($caseResults) > 0) {
                $total_count_nested += count($caseResults); 
                // Display title and judge name
                $title .= "<br><br>" . str_replace(',', '<br>', $res['judge_name']) . "<br>";

                $content .= "<div class='text-center'><h5>" . ($res['m_f'] == 2 ? 'REGULAR HEARING' : 'MISCELLANEOUS HEARING') . "</h5></div>";
                $content .= "<div class='text-center'><h5>$title</h5></div>";

                $content .= '<div class="col-12 align-center pageCenter">';
                $content .= '<table class="table table-bordered table-responsive" id="tab">
                    <thead>
                        <tr style="background-color:darkgrey;">
                            <th style="width: 10%;">Item No.</th>
                            <th style="width:40%;">Case No./Cause Title</th>
                            <th style="width: 40%;">Subject Category</th>
                            <th style="width:10%;">Last listing info</th>
                        </tr>
                    </thead>
                    <tbody>';

                // Loop through the case results and build the table rows
                $subheading_rep = null;
                $con_no=0;
                foreach ($caseResults as $row) {
                    $subheading = $row["stagename"];
                    $print_brdslno = ($row['diary_no'] == $row['conn_key'] || empty($row['conn_key'])) ? $row['brd_slno'] : "&nbsp;" . $row["brd_slno"] . "." . ++$con_no;
                    $is_connected = empty($row['conn_key']) ? "" : "<br/><span style='color:red;'>Conn.</span>";

                    $case_no = empty($row['reg_no_display']) ? 'Diary No. ' . substr_replace($row['diary_no'], ' of ', -4, 0) : $row['reg_no_display'];

                    // Display subheading only once
                    if ($res['m_f'] == 1 && $subheading != $subheading_rep) {
                        $content .= "<tr><td colspan='4' style='font-weight:bold; padding-top:15px; padding-bottom:15px; text-decoration:underline; text-align:center;'>$subheading</td></tr>";
                        $subheading_rep = $subheading;
                    }

                    // Display case row
                    $content .= "<tr>
                        <td class='align-top'>$print_brdslno $is_connected</td>
                        <td class='align-top'>$case_no<br>{$row['cause_title']}</td>
                        <td class='align-top'>{$row['subject_category']}</td>
                        
                      </tr>";
                }

                $content .= '</tbody></table></div>';
            }

        }
        if($total_count_nested > 0){
            echo json_encode(
                array('solve' => true,
                    'content' => $content,                        
                    ) 
                );die;
        }
        else{
            echo json_encode(
                array('solve' => false,
                    'content' => '',                        
                    ) 
                );die;
        }
        echo $total_count_nested;die;
    }

    public function ropNotUploaded()
    {

        $data['app_name'] = 'ROP Not Uploaded';
        $data['model'] = new Cji();
        $data['judge'] = $data['model']->getJudge();
        // pr($data['judge']);
        return view('CJI/ropNotuploadedCases', $data);
    }


    public function getROPNotUploaded()
    {
        $newCsrfHash = csrf_hash();
        $causelistFromDate = $this->request->getPost('causelistFromDate');
        $causelistToDate = $this->request->getPost('causelistToDate');
        $hdnJudgeName = $this->request->getPost('hdnJudgeName');
        $pJudge = $this->request->getPost('pJudge');


       // echo $causelistFromDate . '/' . $causelistToDate . '/' . $hdnJudgeName . '/'  . $pJudge ;   die(182);

        // Prepare data to be passed to the view
        $data['fromDate'] = $causelistFromDate;
        $data['toDate'] = $causelistToDate;
        $data['judgeName'] = $hdnJudgeName;

        $data['model'] = new Cji();
        $data['caseList'] = $data['model']->ropNotUploaded($causelistFromDate, $causelistToDate, $pJudge);

        // Load the view with the data
        // return view('CourtMaster/ropNotuploadedCases', $data);

        if (count($data['caseList']) > 0) {
            return $this->response->setJSON([
              'success' => true,
              'data' =>  $data['caseList'],
              'csrfHash' => $newCsrfHash,
              'csrfName' => csrf_token()
            ]);
          } else {
            return $this->response->setJSON([
              'success' => false,
              'message' => 'No records found.',
              'csrfHash' => $newCsrfHash,
              'csrfName' => csrf_token()
            ]);
          }
    }
}
