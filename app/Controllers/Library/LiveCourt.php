<?php

namespace App\Controllers\Library;

use App\Controllers\BaseController;
use Config\Database;

class LiveCourt extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function LiveCourt_view()
    {
        $sessionData = $this->session->get('login')['usercode'];
        // pr($sessionData);
        // $usercode = $sessionData['login']['usercode'];
        return view('Library/live_court');
    }

    public function gettitles()
    {
        if(!empty($this->request->getPost('courtno'))){
// pr($_POST);
        }


    //     $courtno = $this->request->getPost('courtno');
        
    //     $dtd = date('Y-m-d', strtotime($this->request->getPost('dtd')));

    //   pr( $courtno);
    //   exit;
    //     $db = \Config\Database::connect();

         
    //     $judge_code = $courtno > 0 ? "AND r.courtno = $courtno" : '';

        
    //     $sql = "SELECT r.id, 
    //                    GROUP_CONCAT(DISTINCT j.jcode ORDER BY j.judge_seniority) jcd, 
    //                    GROUP_CONCAT(DISTINCT j.jname ORDER BY j.judge_seniority SEPARATOR ', ') jnm, 
    //                    j.first_name, 
    //                    j.sur_name, 
    //                    title,
    //                    r.courtno, 
    //                    rb.bench_no, 
    //                    mb.abbr, 
    //                    mb.board_type_mb, 
    //                    r.tot_cases, 
    //                    r.frm_time, 
    //                    r.session 
    //             FROM roster r 
    //             LEFT JOIN roster_bench rb ON rb.id = r.bench_id 
    //             LEFT JOIN master_bench mb ON mb.id = rb.bench_id
    //             LEFT JOIN roster_judge rj ON rj.roster_id = r.id 
    //             LEFT JOIN judge j ON j.jcode = rj.judge_id
    //             LEFT JOIN cl_printed cp ON cp.next_dt = '$dtd' AND cp.roster_id = r.id AND cp.display = 'Y' 
    //             WHERE cp.next_dt IS NOT NULL 
    //             AND j.is_retired != 'Y' 
    //             AND j.display = 'Y' 
    //             AND rj.display = 'Y' 
    //             AND rb.display = 'Y' 
    //             AND mb.display = 'Y' 
    //             AND r.display = 'Y' 
    //             $judge_code 
    //             GROUP BY r.id
    //             ORDER BY r.id, j.judge_seniority";

    //     $results = $db->query($sql)->getResultArray();

    //     // Prepare response
    //     if ($results) {
    //         // Format the response data as needed
    //         $output = [];
    //         foreach ($results as $row) {
    //             $court = ($row['courtno'] == 21) ? "Registrar Court No. 1" :
    //                      (($row['courtno'] == 61) ? "Registrar Virtual Court No. 1" :
    //                      (($row['courtno'] == 22) ? "Registrar Court No. 2" :
    //                      (($row['courtno'] == 62) ? "Registrar Virtual Court No. 2" :
    //                      "Court No. " . $row['courtno'])));
                
    //             $judge_name = $row['first_name'] . ' ' . $row['sur_name'] . ', ' . $row['jnm'];
    //             $output[] = [
    //                 'court' => $court,
    //                 'judge_name' => $judge_name,
    //                 'date' => date('l', strtotime($dtd)) . ' The ' . date('jS F, Y', strtotime($dtd))
    //             ];
    //         }
    //         return $this->response->setJSON($output);
    //     } else {
    //         return $this->response->setJSON(['error' => 'No data found.']);
    //     }
    }
    public function get_item_nos(){
        return view('Library/get_item_nos');
    }
}