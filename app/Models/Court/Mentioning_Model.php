<?php
namespace App\Models\Court;

use CodeIgniter\Model;

class Mentioning_Model extends Model
{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();

        
        /*if(empty(session()->get('filing_details')['diary_no'])){
            header('Location:'.base_url('Filing/Diary/search'));exit();
        }else{
            $this->diary_no = session()->get('filing_details')['diary_no'];
        }*/
    }

    public function get_case_type_list()
    {
        $builder = $this->db->table("master.casetype");
        $builder->select('casecode, skey, casename,short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode!=', '9999');
        $builder->orderBy('short_description');
        $query = $builder->get();
        $result = $query->getResultArray();
        if($result) {
            return $result;
        } else{
            return 0;
        }

    }
    public function get_display_status_with_date_differnces($tentative_cl_dt)
    {
        $tentative_cl_date_greater_than_today_flag="F";
        $curDate=date('Y-m-d');
        $tentativeCLDate = (!empty($tentative_cl_dt)) ? date('Y-m-d', strtotime($tentative_cl_dt)) : '';
        $datediff=strtotime($tentativeCLDate) - strtotime($curDate);
        $noofdays= round($datediff / (60 * 60 * 24));


        if(strtotime($tentativeCLDate) > strtotime($curDate) )
        {

            if($noofdays<=60 && $noofdays>0){
                //echo "no of days ddd".$noofdays;
                $tentative_cl_date_greater_than_today_flag='T';
            }
        }
        else
        {
            $tentative_cl_date_greater_than_today_flag='F';
        }
        return $tentative_cl_date_greater_than_today_flag;
    }

    function is_diaryno_already_added_today($dno)
    {
        
        
        $db = \Config\Database::connect();

        $builder = $db->table('mention_memo');
    

                $builder->select('diary_no')
                        ->where('diary_no', $dno)
                        ->where('date_of_received', date('Y-m-d'))
                        ->where('date_on_decided', date('Y-m-d'))
                        ->where('display', 'Y');
                
                $query = $builder->get();
                
            
                if ($query->getNumRows() >= 1) {
            
                    session()->setFlashdata('msg', '<div class="alert alert-warning text-center">Mention Memo Already added for this case for the Given Date</div>');
                    return redirect()->to('Mentioning');
                } else {
                    return false;
                }
    }


    function getCaseDetails($diaryNumber=null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('main b');
        $builder->select("
            s.section_name as user_section,
            s.id,
            CASE 
                WHEN length(CAST(b.diary_no AS TEXT)) > 4 
                THEN substr(CAST(b.diary_no AS TEXT), 1, length(CAST(b.diary_no AS TEXT))-4)
                ELSE CAST(b.diary_no AS TEXT)
            END AS diary_no,
            CASE 
                WHEN length(CAST(b.diary_no AS TEXT)) >= 4 
                THEN substr(CAST(b.diary_no AS TEXT), -4)
                ELSE CAST(b.diary_no AS TEXT)
            END AS diary_year,
            to_char(b.diary_no_rec_date, 'YYYY-MM-DD') as diary_date,
            b.c_status,
            a.tentative_cl_dt,
            a.next_dt,
            a.mainhead,
            a.subhead,
            a.brd_slno,
            a.usercode,
            a.ent_dt,
            pet_name,
            res_name,
            active_fil_no,
            b.reg_no_display,
            dacode,
            a.conn_key,
            stagename,
            a.main_supp_flag,
            u.name as alloted_to_da,
           
            u1.name as updated_by,
            a.listorder,
            br1.name as pet_adv_name,
            br2.name as res_adv_name,
            br1.aor_code as pet_aor_code,
            br2.aor_code as res_aor_code,
            sb.sub_name1,
            sb.sub_name4,
            sb.category_sc_old
        ");

        // Joins
        $builder->join('heardt a', 'a.diary_no = b.diary_no', 'left');
        $builder->join('master.subheading c', 'a.subhead = c.stagecode AND c.display = \'Y\'', 'left');
        $builder->join('master.users u', 'u.usercode = b.dacode AND u.display = \'Y\'', 'left');
        $builder->join('master.users u1', 'u1.usercode = a.usercode AND u1.display = \'Y\'', 'left');
        $builder->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left');
        $builder->join('master.listing_purpose lp', 'lp.code = a.listorder AND lp.display = \'Y\'', 'left');
        $builder->join('master.usersection s', 's.id = u.section AND s.display = \'Y\'', 'left');
        $builder->join('master.bar br1', 'b.pet_adv_id = br1.bar_id', 'left');
        $builder->join('master.bar br2', 'b.res_adv_id = br2.bar_id', 'left');
        $builder->join('mul_category mc', 'a.diary_no = mc.diary_no AND mc.display = \'Y\'', 'left');
        $builder->join('master.submaster sb', 'mc.submaster_id = sb.id', 'left');
        $builder->where('b.diary_no', $diaryNumber);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }


    function getCaseDetails1($diaryNumber=null)
    {
        if(!($this->is_diaryno_already_added_today($diaryNumber)))
        {
          
               $db = \Config\Database::connect();

               $builder = $db->table('main b');

            //     $builder->select('
            //     s.section_name as user_section,
            //     s.id,
            //     SUBSTRING(b.diary_no::TEXT FROM 1 FOR LENGTH(b.diary_no::TEXT) - 4) AS diary_no,
            //     SUBSTRING(b.diary_no::TEXT FROM LENGTH(b.diary_no::TEXT) - 3 FOR 4) AS diary_year,
            //     TO_CHAR(b.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date,
            //     b.c_status, b.tentative_cl_dt, b.next_dt,
            //     b.mainhead, b.subhead, b.brd_slno,
            //     a.usercode, a.ent_dt,
            //     b.pet_name AS pet_name, b.res_name AS res_name,
            //     b.active_fil_no, b.reg_no_display, b.dacode,
            //     a.conn_key, b.stagename,
            //     a.main_supp_flag,
            //     u.name AS alloted_to_da, a.descrip,
            //     u1.name AS updated_by,
            //     a.listorder,
            //     br1.name AS pet_adv_name, br2.name AS res_adv_name,
            //     br1.aor_code AS pet_aor_code, br2.aor_code AS res_aor_code,
            //     sb.sub_name1, sb.sub_name4, sb.category_sc_old
            //   ');

                    $builder->select('
                    s.section_name as user_section,
                    s.id,
                    SUBSTRING(b.diary_no::TEXT FROM 1 FOR LENGTH(b.diary_no::TEXT) - 4) AS diary_no,
                    SUBSTRING(b.diary_no::TEXT FROM LENGTH(b.diary_no::TEXT) - 3 FOR 4) AS diary_year,
                    TO_CHAR(b.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date,
                    b.c_status,
                    a.usercode, a.ent_dt,
                    b.pet_name AS pet_name, b.res_name AS res_name,
                    b.active_fil_no, b.reg_no_display, b.dacode,
                    a.conn_key,
                    a.main_supp_flag,
                    u.name AS alloted_to_da,
                    u1.name AS updated_by,
                    a.listorder,
                    br1.name AS pet_adv_name, br2.name AS res_adv_name,
                    br1.aor_code AS pet_aor_code, br2.aor_code AS res_aor_code,
                    sb.sub_name1, sb.sub_name4, sb.category_sc_old
                ');

                // Joins
                $builder->join('public.heardt a', 'a.diary_no = b.diary_no', 'left');
                $builder->join('master.subheading c', 'a.subhead = c.stagecode', 'left');
                $builder->join('master.users u', 'u.usercode = b.dacode', 'left');
                $builder->join('master.users u1', 'u1.usercode = a.usercode', 'left');
                $builder->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left');
                $builder->join('master.listing_purpose lp', 'lp.code = a.listorder', 'left');
                $builder->join('master.usersection s', 's.id = u.section', 'left');
                $builder->join('master.bar br1', 'b.pet_adv_id = br1.bar_id', 'left');
                $builder->join('master.bar br2', 'b.res_adv_id = br2.bar_id', 'left');
                $builder->join('public.mul_category mc', 'a.diary_no = mc.diary_no', 'left');
                $builder->join('master.submaster sb', 'mc.submaster_id = sb.id', 'left');

                // Conditions
                $builder->where('c.display', 'Y');
                $builder->where('u.display', 'Y');
                $builder->where('u1.display', 'Y');
                $builder->where('lp.display', 'Y');
                $builder->where('s.display', 'Y');
                $builder->where('mc.display', 'Y');
                $builder->where('b.diary_no', $diaryNumber);

                // Get the results
                $query = $builder->get();
        
                
                // Return results
                if ($query->getNumRows() >= 1) {
                    return $query->getResultArray();
                } else {
                    return [];
                }

            //     $builder = $db->table('main b');
            //     $builder->select('s.section_name as user_section, s.id,
            //     SUBSTRING(b.diary_no::TEXT FROM 1 FOR LENGTH(b.diary_no::TEXT) - 4) AS diary_no,
            //     SUBSTRING(b.diary_no::TEXT FROM LENGTH(b.diary_no::TEXT) - 3 FOR 4) AS diary_year,
            //     TO_CHAR(b.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date, 
            //     b.c_status, tentative_cl_dt, next_dt, mainhead, subhead, brd_slno, 
            //     a.usercode, ent_dt, pet_name, res_name, active_fil_no, 
            //     b.reg_no_display, dacode, a.conn_key, stagename, main_supp_flag, 
            //     u.name AS alloted_to_da, descrip, u1.name AS updated_by, 
            //     listorder, br1.name AS pet_adv_name, br2.name AS res_adv_name, 
            //     br1.aor_code AS pet_aor_code, br2.aor_code AS res_aor_code, 
            //     sb.sub_name1, sb.sub_name4, sb.category_sc_old');
            //     $builder->join('public.heardt a', 'a.diary_no = b.diary_no', 'left');
            //     $builder->join('master.subheading c', 'a.subhead = c.stagecode', 'left');
            //     $builder->join('master.users u', 'u.usercode = b.dacode', 'left');
            //     $builder->join('master.users u1', 'u1.usercode = a.usercode', 'left');
            //     $builder->join('master.master_main_supp mms', 'mms.id = a.main_supp_flag', 'left');
            //     $builder->join('master.listing_purpose lp', 'lp.code = a.listorder', 'left');
            //     $builder->join('master.usersection s', 's.id = u.section', 'left');
            //     $builder->join('master.bar br1', 'b.pet_adv_id = br1.bar_id', 'left');
            //     $builder->join('master.bar br2', 'b.res_adv_id = br2.bar_id', 'left');
            //     $builder->join('public.mul_category mc', 'a.diary_no = mc.diary_no', 'left');
            //     $builder->join('master.submaster sb', 'mc.submaster_id = sb.id', 'left');
            //     $builder->where("c.display","Y");
            //     $builder->where("u.display","Y");
            //     $builder->where("u1.display","Y");
            //     $builder->where("lp.display","Y");
            //     $builder->where("s.display","Y");
            //     $builder->where("mc.display","Y");
            //     $builder->where('b.diary_no',$diaryNumber);
            //     $query = $builder->get();
            //    // $query=$this->db->getLastQuery();echo (string) $query."<br>";exit;
            //     if($query->getNumRows() >= 1) {
            //         return $result = $query->getResultArray();
            //     }else{
            //         return [];
               // }
 
            }
               

    }

    public function get_case_remarks($dn, $cldate, $jcodes, $clno) {
     
        $db = \Config\Database::connect();
        
     
        $builder = $db->table('public.case_remarks_multiple c');

        $builder->select("
            h.cat_head_id,
            c.cl_date,
            c.jcodes,
            c.status,
            STRING_AGG(
                CONCAT(h.head, CASE WHEN c.head_content != '' THEN CONCAT(' [', c.head_content, ']') ELSE '' END), ', '
            ) AS crem,
            STRING_AGG(
                CONCAT(c.r_head, '|', c.head_content, '^^'), ''
            ) AS caseval,
            c.mainhead,
            c.clno
        ");
        $builder->join('master.case_remarks_head h', 'c.r_head = h.sno', 'inner');
        $builder->where('c.diary_no', $dn);
        $builder->where('c.cl_date', $cldate);
        $builder->where('c.jcodes', $jcodes);
        $builder->where('c.clno', $clno);
        
    
        $builder->groupBy('h.cat_head_id, c.cl_date, c.jcodes, c.status, c.mainhead, c.clno');
        
  
        $query = $builder->get();
        
        $crem = '';
        if ($query->getNumRows() > 0) {
            $result = $query->getRowArray(); 
            $crem = $result['crem'] ?? ''; 
        }
        
        return $crem;
    }

    public function change_date_format($date) {
        if (empty($date) || $date === '0000-00-00') {
            return ''; 
        }
    
        try {
           
            $dateTime = new \DateTime($date);
         
            return $dateTime->format('d-m-Y');
        } catch (\Exception $e) {
         
            return ''; 
        }
    }

    public function get_judges($jcodes) {
        $jnames = "";
    
        if (!empty($jcodes)) {
        
            $t_jc = explode(",", $jcodes);
    
 
            $db = \Config\Database::connect();
            

            $judgeNames = [];
    
            
            $builder = $db->table('master.judge');
            $builder->select('jname');
            $builder->whereIn('jcode', $t_jc);
            $query = $builder->get();
    
           
            if ($query->getNumRows() > 0) {
                $results = $query->getResultArray();
    
            
                $judgeNames = array_column($results, 'jname');
            }
    
            $count = count($judgeNames);
            if ($count > 0) {
                $last = array_pop($judgeNames);
                $jnames = implode(", ", $judgeNames);
                if (!empty($jnames)) {
                    $jnames .= " and ";
                }
                $jnames .= $last;
            }
        }
    
        return $jnames;
    }

    public function get_purpose($purpose_code) {
   
        $purpose = "";
    
        if (!empty($purpose_code)) {
       
            $db = \Config\Database::connect();
            
            $builder = $db->table('master.listing_purpose');
            $builder->select('purpose');
            $builder->where('code', $purpose_code);
            
      
            $query = $builder->get();
    
        
            if ($query->getNumRows() > 0) {
                $row = $query->getRowArray();
                $purpose = $row['purpose'];
            }
        }
    
        return $purpose;
    }


    public function get_stage($stage_code, $mainhead) {
        $stage = "";
    

        if (!empty($stage_code)) {
            $db = \Config\Database::connect();
    
       
            if ($mainhead === "M") {
                $builder = $db->table('master.subheading');
                $builder->select('stagename');
                $builder->where('stagecode', $stage_code);
                $query = $builder->get();
    
                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray();
                    $stage = $row['stagename'];
                }
            }
           
            elseif ($mainhead === "F") {
                $builder = $db->table('master.submaster');
                $builder->where('id', $stage_code);
                $query = $builder->get();
    
       
                if ($query->getNumRows() > 0) {
                    $row = $query->getRowArray();
                    
                    $subcode1 = $row['subcode1'];
                    $subcode2 = $row['subcode2'];
                    $subcode3 = $row['subcode3'];
                    $subcode4 = $row['subcode4'];
    
                    if ($subcode1 > 0 && $subcode2 == 0 && $subcode3 == 0 && $subcode4 == 0) {
                        $stage = $row['sub_name1'];
                    } elseif ($subcode1 > 0 && $subcode2 > 0 && $subcode3 == 0 && $subcode4 == 0) {
                        $stage = $row['sub_name1'] . " : " . $row['sub_name4'];
                    } elseif ($subcode1 > 0 && $subcode2 > 0 && $subcode3 > 0 && $subcode4 == 0) {
                        $stage = $row['sub_name1'] . " : " . $row['sub_name2'] . " : " . $row['sub_name4'];
                    } elseif ($subcode1 > 0 && $subcode2 > 0 && $subcode3 > 0 && $subcode4 > 0) {
                        $stage = $row['sub_name1'] . " : " . $row['sub_name2'] . " : " . $row['sub_name3'] . " : " . $row['sub_name4'];
                    }
                }
            }
        }
    
        return $stage;
    }


    public function get_mainhead($mainhead) {
        return match ($mainhead) {
            'M' => 'Misc.',
            'F' => 'Regular',
            'L' => 'Lok Adalat',
            'S' => 'Mediation',
            default => ''
        };
    }
    
    // Helper function for board type
    public function get_board_type($type) {
        return match ($type) {
            'J' => 'Judge',
            'C' => 'Chamber',
            'R' => 'Registry',
            default => ''
        };
    }

    public function get_listings($diaryno) {
        $output = "";
        $t_table = "";
        $t_mainhead = "";
        $t_mainhead1 = "";
    
       
        $db = \Config\Database::connect();
    
       
        $builder = $db->table('public.heardt a');
        $builder->select('a.*, c.section_name')
                ->join('master.users b', 'a.usercode = b.usercode', 'left')
                ->join('master.usersection c', 'b.section = c.id', 'left')
                ->where('a.diary_no', $diaryno);

        // pr($builder->getCompiledSelect());
        $query_listing = $builder->get();
        $result_listing = $query_listing->getResultArray();
    
      
        $builder1 = $db->table('public.last_heardt a');
        $builder1->select('a.*, c.section_name')
                 ->join('master.users b', 'a.usercode = b.usercode', 'left')
                 ->join('master.usersection c', 'b.section = c.id', 'left')
                 ->where('a.diary_no', $diaryno)
                 ->where('a.next_dt IS NOT NULL')  
                 ->where('a.next_dt !=', '0001-01-01') 
                 ->orderBy('a.ent_dt', 'DESC');
        // pr($builder1->getCompiledSelect());
        $query_listing1 = $builder1->get();
        $result_listing1 = $query_listing1->getResultArray();
    
        if (!empty($result_listing) || !empty($result_listing1)) {
            $t_table = '<table id="example1" class="table table-bordered table-striped">';
            $t_table .= "<thead><tr><th>Listing Date</th><th>Misc./Regular</th><th>Stage</th><th>Purpose</th><th align='center'>Proposed/ List in</th><th align='center'>Judges</th><th>IA</th><th>Remarks</th><th>Updated By</th></tr></thead><tbody>";
    
       
            foreach ($result_listing as $key=> $row_listing) {
                $listed_ia = $row_listing['listed_ia'];
                $t_mainhead = $this->get_mainhead($row_listing['mainhead']);
                $t_stage = $this->get_stage($row_listing['subhead'], $row_listing['mainhead']);
                $next_dt = $row_listing['next_dt'];
                $lo = $row_listing['listorder'];
                $sj = $row_listing['sitting_judges'];
                $bt = $this->get_board_type($row_listing['board_type']);
                $cr = ($row_listing['judges'] && $row_listing['judges'] != '0') 
                      ? $this->get_case_remarks($row_listing['diary_no'], $row_listing['next_dt'], $row_listing['judges'], $row_listing['brd_slno']) 
                      : "";
    
                $ent_dt_display = ($row_listing['ent_dt'] == '' || !$row_listing['ent_dt'])
                    ? ($row_listing['section_name'] ? $row_listing['section_name'] : '')
                    : $row_listing['section_name'] . " ON " . date('d-m-Y h:i:s A', strtotime($row_listing['ent_dt']));
    
                $t_table .= "<tr>
                          
                              <td align='center'>" . $this->change_date_format($row_listing['next_dt']) . "</td>
                              <td>" . $t_mainhead . "</td>
                              <td>" . $t_stage . "</td>
                              <td>" . $this->get_purpose($row_listing['listorder']) . "</td>
                              <td align='center'>" . $bt . "</td>
                              <td align='center'>" . $this->get_judges($row_listing['judges']) . "</td>
                              <td align='center'>" . $row_listing['listed_ia'] . "</td>
                              <td>" . $cr . "</td>
                              <td>" . $ent_dt_display . "</td></tr>";
            }
    
         
            foreach ($result_listing1 as $row_listing1) {
                $t_mainhead1 = $this->get_mainhead($row_listing1['mainhead']);
                $t_stage1 = $this->get_stage($row_listing1['subhead'], $row_listing1['mainhead']);
                $bt1 = $this->get_board_type($row_listing1['board_type']);
                $cr = ($row_listing1['judges'] && $row_listing1['judges'] != '0') 
                      ? $this->get_case_remarks($row_listing1['diary_no'], $row_listing1['next_dt'], $row_listing1['judges'], $row_listing1['brd_slno']) 
                      : "";
                
                $ent_dt_display1 = ($row_listing1['ent_dt'] == '' || !$row_listing1['ent_dt'])
                    ? ($row_listing1['section_name'] ? $row_listing1['section_name'] : '')
                    : $row_listing1['section_name'] . " ON " . date('d-m-Y h:i:s A', strtotime($row_listing1['ent_dt']));
                
                $t_table .= "<tr><td align='center'>" . $this->change_date_format($row_listing1['next_dt']) . "</td>
                              <td>" . $t_mainhead1 . "</td>
                              <td>" . $t_stage1 . "</td>
                              <td>" . $this->get_purpose($row_listing1['listorder']) . "</td>
                              <td align='center'>" . $bt1 . "</td>
                              <td align='center'>" . $this->get_judges($row_listing1['judges']) . "</td>
                              <td align='center'>" . $row_listing1['listed_ia'] . "</td>
                              <td>" . $cr . "</td>
                              <td>" . $ent_dt_display1 . "</td></tr>";
            }
    
            $t_table .= "</tbody></table>";
        }
    
        $output .= $t_table;
    
        return $output;
    }

    



    public function get_main_connected_array($dno)
    {

        $builder = $this->db->table('main m');
        $builder->select('m.diary_no, m.conn_key');
        $builder->select("
            CASE
                WHEN m.conn_key = '0' OR m.conn_key = CAST(m.diary_no AS VARCHAR) THEN 'M'
                WHEN m.conn_key != '0' AND m.conn_key != CAST(m.diary_no AS VARCHAR) THEN 'C'
            END AS mainorconn", false);

        $builder->where('m.diary_no', $dno);
        $query = $builder->get();
        $results = $query->getResultArray();

        $dnos = [];

        if (!empty($results)) {
            if ($results[0]['mainorconn'] == 'M') {
                $dnos[] = $results[0]['diary_no'];
            } else {
                if ($results[0]['conn_key'] != null && $results[0]['conn_key'] != 0) {
                    $dnos[] = $results[0]['conn_key']; 
                }
                $dnos[] = $results[0]['diary_no']; 
            }
        }
        return $dnos;
    }


    function getMiscOrRegular($dno)
    {
        $builder = $this->db->table('public.heardt'); 
        $builder->select('mainhead');
        $builder->where('diary_no', $dno);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }

    }


    public function isDiaryNoAlreadyAdded($dno, $receivedDate, $presentedDate, $mmDecidedDate)
    {
        // Use Query Builder for safer and easier queries
        $builder = $this->builder('public.mention_memo');
        $builder->where('diary_no', $dno)
                ->where('date_of_received', $receivedDate)
                ->where('date_on_decided', $presentedDate)
                ->where('date_for_decided', $mmDecidedDate);
        $query = $builder->get();
        // Check if any rows were returned
        if ($query->getNumRows() >= 1) {
            return true;
        } else {
            return false;
        }
    }


    public function proposal_condition_status(array $dnos_array, string $MorF)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('m.diary_no, h.conn_key, h.judges, h.clno, h.brd_slno, m.c_status, h.mainhead');
        $builder->join('main m', 'h.diary_no = m.diary_no');

        if ($MorF === 'M') {
            $builder->where('h.mainhead', 'M');
        } elseif ($MorF === 'F') {
            $builder->select('m.diary_no, m.c_status, h.mainhead, h.main_supp_flag, h.judges, h.clno, h.brd_slno');
            $builder->where('h.mainhead', 'F');
        }
        $builder->whereIn('m.diary_no', $dnos_array);
        $query = $builder->get();
        $results = $query->getResultArray();
        return !empty($results) ? $results : false;
    }


    public function tentative_date_condition_status(array $dnos_array, string $MorF)
    {

        $builder = $this->db->table('heardt h');
        $builder->join('main m', 'h.diary_no = m.diary_no');
        $builder->select('m.diary_no, h.tentative_cl_dt');
        
        if ($MorF === 'M') {
            $builder->whereIn('m.diary_no', $dnos_array);
        } elseif ($MorF === 'F') {
           
            $builder->select('m.diary_no, h.judges, h.clno, h.brd_slno, m.c_status, h.tentative_cl_dt, h.next_dt');
            $builder->where('m.c_status', 'P');
            $builder->whereIn('m.diary_no', $dnos_array);
        }
        $query = $builder->get();
        $results = $query->getResultArray();
        return !empty($results) ? $results : false;
    }


    function pending_IA_condition($dnos_array,$MorF)
    {
        $all_dno = implode(',', $dnos_array);
        $sql="SELECT DISTINCT a.diary_no
                FROM main a
                JOIN docdetails b ON a.diary_no = b.diary_no
                AND b.display = 'Y'
                WHERE c_status = 'P'
                AND (
                active_fil_no = ''
                OR active_fil_no IS NULL
                )
                AND (
                (
                doccode = '8'
                AND doccode1 = '28'
                ) || ( doccode = '8'
                AND doccode1 = '95' ) || ( doccode = '8'
                AND doccode1 = '214' ) || ( doccode = '8'
                AND doccode1 = '215' )
                )  AND a.diary_no IN($all_dno)";

        $result = $this->db->query($sql);
        if($result -> num_rows() >= 1)
        {
            //return $result->result_array();
            return true;
        }
        else
        {
            return false;
        }
    }


    public function add_new_mentionmemo(array $dno_array = null, string $morF = null, $data )
    {
      
        date_default_timezone_set("Asia/Kolkata");

        $receivedDate = $data['receivedDate'];
        $presentedDate = $data['presentedDate'];
        $mmDecidedDate = $data['mmDecidedDate'];
        $forListType = $data['forListType'];
        $roster_id = $data['roster_id'];
        $item_no = $data['item_no'];
        $remarks = $data['remarks'];
        //$dno = $data['diary_no'];
        $date_of_entry = date('Y-m-d H:i:s');
    
        $insert_status = 'F';
        $insert_count = 0;
        $foreach_count = 0;

        foreach ($dno_array as $dno) {
            $condition = '1=1';
            $num_row2 = 0;
            $num_row3 = 0;
            $conn_key = 0;
            $foreach_count++;

            if ($forListType == 1) {
                $condition .= " AND m_roster_id = $roster_id";
            }

            $builder = $this->db->table('public.mention_memo');
            $builder->select('diary_no');
            $builder->where('diary_no', $dno);
            $builder->where('date_of_received', $receivedDate);
            $builder->where('date_on_decided', $presentedDate);
            $builder->where('date_for_decided', $mmDecidedDate);
            $builder->where($condition);
            $exists = $builder->countAllResults() > 0;
            //pr($exists);
            if ($exists) {
                $message = '<div class="alert alert-danger text-center">Mention Memo of Diary No. is Already Mentioned for the Given Date</div>';
            } else {
         
                $builder1 = $this->db->table('public.heardt');
                $builder1->select('conn_key');
                $builder1->where('diary_no', $dno);
                $connKeyResult = $builder1->get()->getRowArray();
                if ($connKeyResult) {
                    $conn_key = $connKeyResult['conn_key'];
                }
                //pr(session('login')['usercode']);
                //echo $forListType;die;
                if($forListType==1)
                {
                    $dataArray = array(
                        'diary_no' => $dno,
                        'date_of_received' => $receivedDate,
                        'date_on_decided' => $presentedDate,
                        'date_for_decided' => $mmDecidedDate,
                        'result' => 'Y',
                        'date_of_entry' => date('Y-m-d H:i:s'),
                        'display' => 'Y',
                        'user_id' => session('login')['usercode'],
                        'update_time' => date('Y-m-d H:i:s'),
                        'spl_remark' => $remarks,
                        'for_court'=>'J',
                        'm_roster_id'=>$roster_id,
                        'm_brd_slno'=>$item_no,
                        'm_conn_key'=>$conn_key
                    );
                }
                if($forListType==2)
                {
                    $dataArray = array(
                        'diary_no' => $dno,
                        'date_of_received' => $receivedDate,
                        'date_on_decided' => $presentedDate,
                        'date_for_decided' => $mmDecidedDate,
                        'result' => 'Y',
                        'date_of_entry' => date('Y-m-d H:i:s'),
                        'display' => 'Y',
                        'user_id' => session('login')['usercode'],
                        'update_time' => date('Y-m-d H:i:s'),
                        'spl_remark' => $remarks,
                        'for_court'=>'J',
                        'm_conn_key'=>$conn_key
                    );
                }

                $this->db->table('mention_memo')->insert($dataArray);
                $num_row1 = $this->db->affectedRows();
                //pr($num_row1);
                if ($num_row1 >= 1) {

                    $builder = $this->db->table('heardt');
                    $builder->where('diary_no', $dno);
                    $result4 = $builder->get();

                    $cur_date_timestamp = date("Y-m-d", time());
                    
                    if ($mmDecidedDate > $cur_date_timestamp && $forListType == 2) {
                       
                        $builder2 = $this->db->table('public.last_heardt');
                        $builder2->select('main.diary_no, main.conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id,
                            judges, coram, board_type, heardt.usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt, "", main.lastorder, listed_ia, sitting_judges, is_nmd, no_of_time_deleted, list_before_remark');
                        $builder2->join('main', 'heardt.diary_no = main.diary_no');
                        $builder2->where('main.diary_no', $dno);
                        $builder2->where('c_status', 'P');
                        $this->db->query($builder2->getCompiledSelect(), false);
                        $numRow2 = $this->db->affectedRows();
                    } else {
                        $numRow2 = 1; 
                    }

                    if ($numRow2 >= 1) {
                  
                    if ($morF == 'M') {
                        if ($mmDecidedDate > $cur_date_timestamp && $forListType == 2) {
                            $builder3 = $this->db->table('heardt');
                            $builder3->set([
                                'tentative_cl_dt' => $mmDecidedDate,
                                'next_dt' => $mmDecidedDate,
                                'clno' => 0,
                                'brd_slno' => 0,
                                'roster_id' => 0,
                                'judges' => 0,
                                'main_supp_flag' => 0,
                                'listorder' => 5,
                                'board_type' => 'J',
                                'usercode' => session('login')['usercode'],
                                'ent_dt' => 'NOW()', // CodeIgniter's way to handle current timestamp
                                'module_id' => 17
                            ]);
                            $builder3->join('main', 'heardt.diary_no = main.diary_no', 'inner');
                            $builder3->where('heardt.diary_no', $dno);
                            $builder3->where('mainhead', 'M');
                            $builder3->whereIn('judges', [0, null, '']);
                            $builder3->where('clno', 0);
                            $builder3->where('brd_slno', 0);
                            $builder3->where('main.c_status', 'P');
                            
                            $builder3->update();
                            $numRow2 = $this->db->affectedRows();
                        }
                    }
                    
                    if ($morF == 'F') {
                        if ($mmDecidedDate > $cur_date_timestamp && $forListType == 2) {
                            $builder3 = $this->db->table('public.heardt');
                            $builder3->set([
                                'tentative_cl_dt' => $mmDecidedDate,
                                'next_dt' => $mmDecidedDate,
                                'clno' => 0,
                                'brd_slno' => 0,
                                'roster_id' => 0,
                                'main_supp_flag' => 0,
                                'listorder' => 5,
                                'board_type' => 'J',
                                'usercode' => session('login')['usercode'],
                                'ent_dt' => 'NOW()', 
                                'module_id' => 17
                            ]);
                            $builder3->join('public.main', 'heardt.diary_no = main.diary_no', 'inner');
                            $builder3->where('heardt.diary_no', $dno);
                            $builder3->where('mainhead', 'F');
                            $builder3->where('main.c_status', 'P');
                            $builder3->whereNotIn('main_supp_flag', [1, 2]);
                            
                            $builder3->update();
                            $numRow2 = $this->db->affectedRows();
                        }
                    }


                    $InsertHeardtDataArray = array(
                        'diary_no' => $dno,
                        'conn_key' => $conn_key,
                        'next_dt' =>$mmDecidedDate,
                        'mainhead' => 'M',
                        'subhead' => 808,
                        'clno'=>0,
                        'brd_slno'=>0,
                        'roster_id'=>0,
                        'judges'=>0,
                        'coram'=>null,
                        'board_type'=>'J',
                        'usercode'=> session('login')['usercode'],
                        'ent_dt'=>date('Y-m-d H:i:s'),
                        'module_id'=>17,
                        'mainhead_n'=>'M',
                        'subhead_n'=>808,
                        'main_supp_flag'=>0,
                        'listorder'=>5,
                        'tentative_cl_dt'=>$mmDecidedDate,
                        'listed_ia'=>null,
                        'sitting_judges'=>2,
                        'list_before_remark'=>0,
                        'coram_prev'=>0
                    );

                        if ($mmDecidedDate > $cur_date_timestamp) {
                            if ($forListType == 2) {
                                if ($result4->getNumRows() > 0) {
                                    $this->db->query($builder3);
                                    $num_row3 = $this->db->affectedRows();
                                } else {
                                    $this->db->table('heardt')->insert($InsertHeardtDataArray);
                                    $num_row3 = $this->db->affectedRows();
                                }
                            }
                        } else {
                            if ($forListType == 2) {
                                if ($result4->getNumRows() > 0) {
                                    $num_row3 = 1;
                                } else {
                                    $this->db->table('heardt')->insert($InsertHeardtDataArray);
                                    $num_row3 = $this->db->affectedRows();
                                }
                            }
                        }

                        if ($num_row3 >= 1) {
                            $insert_status = 'T';
                            $insert_count++;
                        } else {
                            // Rollback actions can be handled here
                        }
                    }
                }
            }
        }

        $return = [
            'foreach_count' => $foreach_count,
            'insert_count' => $insert_count,
            'insert_status' => $insert_status
        ];
        $return['msg'] = isset($message) ? $message : '';

        return $return;
    }
    
    function listBefore()
    {
        $sql="SELECT jcode,GROUP_CONCAT(jname,' ') jname,h.diary_no,'C' notbef,ent_dt,res_add
                    FROM `heardt` h join judge j on find_in_set(jcode,coram)>0
                    left join not_before_reason on list_before_remark=res_id
                    WHERE h.`diary_no` = $_REQUEST[dno] GROUP BY h.diary_no
                    union
                    SELECT jcode,jname,diary_no,not_before.notbef,ent_dt,not_before_reason.res_add FROM `not_before`
                    left join judge j on jcode=j1
                    left join not_before_reason on not_before.res_id=not_before_reason.res_id
                    WHERE `diary_no` = $_REQUEST[dno] #and not_before.notbef='N' ";

        if($sql -> num_rows() >= 1)
        {
            return $sql->result_array();
        }
        else
        {
            return false;
        }
    }

    public function get_decided_mentioning($dateForDecided)
    {
        $return = [];
        $dateForDecided = date('Y-m-d', strtotime($dateForDecided));
        
        $builder = $this->db->table('mention_memo mm');
        $builder->select("
         mm.diary_no AS diary_nos,
            SUBSTR(mm.diary_no, 1, LENGTH(mm.diary_no) - 4) AS diary_no,
            SUBSTR(mm.diary_no, -4) AS diary_year,
            TO_CHAR(m.diary_no_rec_date, 'YYYY-MM-DD') AS diary_date,
            m.active_fil_no,
            m.pet_name,
            m.res_name,
            m.reg_no_display,
            mm.date_of_received,
            mm.date_on_decided,
            mm.date_for_decided,
            mm.result,
            mm.date_of_entry,
            mm.user_id,
            mm.update_time,
            mm.update_user,
            mm.spl_remark,
            mm.note_remark,
            mm.for_court
    ");
    $builder->join('main m', 'mm.diary_no = CAST(m.diary_no AS TEXT)', 'left');
    $builder->where('mm.display', 'Y');
    $builder->where('mm.date_for_decided', $dateForDecided);
    
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
        $return = $query->getResultArray();
    } else {
        $session = \Config\Services::session();
        $session->setFlashdata('msg', 'No Case Listed for selected Date');
    }
        return $return;
    }

    public function get_onDate_mentioning()
    {
        $request = \Config\Services::request();
        $onDate = $request->getPost('decidedDate');
        $onDate = date('Y-m-d', strtotime($onDate));

        $builder = $this->db->table('mention_memo mm');
        $builder->select('
                mm.diary_no AS diary_nos,
                CASE 
                            WHEN length(CAST(mm.diary_no AS TEXT)) > 4 
                            THEN substr(CAST(mm.diary_no AS TEXT), 1, length(CAST(mm.diary_no AS TEXT))-4)
                            ELSE CAST(mm.diary_no AS TEXT)
                        END AS diary_no,
                SUBSTRING(mm.diary_no, -4) AS diary_year,
                TO_CHAR(m.diary_no_rec_date, \'YYYY-MM-DD\') AS diary_date,
                m.active_fil_no,
                m.pet_name,
                m.res_name,
                m.reg_no_display,
                mm.date_of_received,
                mm.date_on_decided,
                mm.date_for_decided,
                mm.result,
                mm.date_of_entry,
                mm.user_id,
                mm.update_time,
                mm.update_user,
                mm.spl_remark,
                mm.note_remark,
                rst.courtno,
                mm.m_brd_slno,
                CASE
                    WHEN (rst.courtno IS NOT NULL AND mm.m_roster_id IS NOT NULL)
                    THEN \'Oral Mentioning\'
                    ELSE \'Written Mentioning\'
                END AS MentionType,
                e.name AS entryBy,
                mm.m_conn_key,
                CASE
                    WHEN (mm.diary_no <> mm.m_conn_key::text AND mm.m_conn_key <> 0)
                    THEN \'C\'
                    ELSE \'M\'
                END AS main_connected,
                tentative_section(mm.diary_no::BIGINT) AS section,
                sh.stagename
            ');

            // Join statements
            $builder->join('main m', 'mm.diary_no = CAST(m.diary_no AS VARCHAR)', 'left');
            $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
            $builder->join('master.subheading sh', 'h.subhead = sh.stagecode', 'left');
            $builder->join('master.roster rst', 'mm.m_roster_id = rst.id', 'left');
            $builder->join('master.users e', 'e.usercode = mm.user_id', 'left');

            // Where conditions
            $builder->where('mm.display', 'Y');
            $builder->where('DATE(mm.date_of_entry)', $onDate);

            // Order by
            $builder->orderBy('
        CASE
            WHEN (rst.courtno IS NOT NULL AND mm.m_roster_id IS NOT NULL)
            THEN \'Oral Mentioning\'
            ELSE \'Written Mentioning\'
        END', 'DESC');
            $builder->orderBy('rst.courtno');
            $builder->orderBy('mm.m_brd_slno', 'ASC');
            $builder->orderBy('main_connected', 'DESC');

        // Execute the query
        $query = $builder->get();

        //pr($query);
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            $session = \Config\Services::session();
            $session->setFlashdata('msg', 'No Case Listed for selected Date');
            return [];
        }
    }


    // function get_onDate_mentioning()
    // {
    //     $onDate=$_POST['decidedDate'];
    //     $onDate=date('Y-m-d', strtotime($onDate));


    //     $sql=" SELECT
    //                     mm.diary_no AS diary_nos,
    //                     SUBSTR(mm.diary_no,
    //                         1,
    //                         LENGTH(m.diary_no) - 4) AS diary_no,
    //                     SUBSTR(mm.diary_no, - 4) AS diary_year,
    //                     DATE_FORMAT(m.diary_no_rec_date, '%Y-%m-%d') AS diary_date,
    //                     m.active_fil_no,
    //                     m.pet_name,
    //                     m.res_name,
    //                     m.reg_no_display,
    //                     mm.date_of_received,
    //                     mm.date_on_decided,
    //                     mm.date_for_decided,
    //                     mm.result,
    //                     mm.date_of_entry,
    //                     mm.user_id,
    //                     mm.update_time,
    //                     mm.update_user,
    //                     mm.spl_remark,
    //                     mm.note_remark,
    //                     for_court,
    //                     rst.courtno,
    //                     mm.m_brd_slno,
    //                     CASE
    //                         WHEN
    //                             (rst.courtno IS NOT NULL
    //                                 AND mm.m_roster_id IS NOT NULL)
    //                         THEN
    //                             'Oral Mentioning'
    //                         ELSE 'Written Mentioning'
    //                     END AS MentionType,
    //                     e.name AS entryBy,
    //                     mm.m_conn_key,
    //                     CASE
    //                         WHEN
    //                             mm.diary_no <> mm.m_conn_key
    //                                 AND mm.m_conn_key <> 0
    //                         THEN
    //                             'C'
    //                         ELSE 'M'
    //                     END AS main_connected,
    //                     tentative_section(mm.diary_no) as section,
    //                     sh.stagename

    //                 FROM
    //                     mention_memo mm
    //                         LEFT JOIN
    //                     main m ON mm.diary_no = m.diary_no
    //                         left join
    //                     heardt h on m.diary_no=h.diary_no
    //                         left  join
    //                     subheading sh on h.subhead=sh.stagecode
    //                         LEFT JOIN
    //                     roster rst ON mm.m_roster_id = rst.id
    //                         LEFT JOIN
    //                     users e ON e.usercode = mm.user_id
    //                 WHERE
    //                     mm.display = 'Y'
    //                         AND DATE(mm.date_of_entry) = '$onDate'
    //                 ORDER BY MentionType DESC , rst.courtno , m_brd_slno ASC , main_connected DESC";

    //     //echo $sql;
    //     $query = $this->db->query($sql);
    //     if($query -> num_rows() >= 1)
    //     {

    //         return $query->result_array();
    //         // var_dump($result);
    //     }
    //     else
    //     {
    //         $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">No Case Listed for selected Date</div>');
    //         redirect("Mentioning/MentioningReport");
    //     }

    // }
    function delete_mention_metter()
    {
        $diaryNo=$this->input->get('diaryNo');
        $entryDate=$this->input->get('enterDate');
        $presentedDate=$this->input->get('presentedDate');
        $decidedDate=$this->input->get('decidedDate');

        $sql = "update mention_memo set display='N', update_time=date('Y-m-d H:i:s') where diary_no= $diaryNo and date_of_received='$entryDate' and date_on_decided='$presentedDate' and date_for_decided='$decidedDate'";
        if($this->db->query($sql))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    function get_mmData($date, $year, $d_no, $flistype){
            
			$builder = $this->db->table('mention_memo mm');

			$builder->distinct();
			$builder->select('
				r.id AS roster_id,
				session,
				r.courtno,
				mm.diary_no,
				mm.date_on_decided,
				rj.judge_id,
				j.jname,
				j.jcode,
				mm.date_of_received,
				mm.m_brd_slno,
				mm.spl_remark,
				hd.mainhead,
				hd.board_type,
				mm.id,
				mm.date_for_decided,
				mm.pdfname,
				mm.upload_date
			');

			$builder->join('master.roster r', 'mm.m_roster_id = r.id', 'inner');
			$builder->join('master.roster_judge rj', 'r.id = rj.roster_id', 'inner');
			// $builder->join('cl_printed cp', 'r.id=cp.roster_id', 'inner'); // Commented out as in original
			$builder->join('master.roster_bench rb', 'r.bench_id = rb.id', 'inner');
			$builder->join('master.master_bench mb', 'rb.bench_id = mb.id', 'inner');
			$builder->join('master.judge j', 'rj.judge_id = j.jcode', 'inner');
			$builder->join('heardt hd', 'hd.diary_no = CAST(mm.diary_no AS INTEGER)', 'left', false);

			$builder->where('mm.diary_no', $d_no);
			$builder->where("EXTRACT(YEAR FROM mm.date_of_received) =", $date);

			if ($flistype == 1) {
				$builder->where('mm.m_roster_id IS NOT NULL', null, false);
			}

           //echo  $builder->getCompiledSelect();die;

			return $builder->get()->getRowArray();
		
	}
	
	
	function UpdateMmData($data){
		
		  $this->db->transStart(); 

		  $mmCurrentRecord = $this->db->table('mention_memo')
								  ->where('id', $data['id'])
								  ->get()
								  ->getRowArray();

			if (!$mmCurrentRecord) {
				return false; 
			}

		   $historyData = array_merge($mmCurrentRecord, [
				'event_type'        => 'U',
				'ipaddress'         => $_SERVER['REMOTE_ADDR'],
				'update_user'       => $data['session_id_url'],
				'action_perform_on' => date("Y-m-d H:i:s")
			]);

		$inserted = $this->db->table('mention_memo_history')->insert($historyData);

		if ($inserted) {
			$updateData = [
				'date_of_received' => $data['mmReceivedDate'],
				'date_on_decided'  => $data['mmPresentedDate'],
				'date_for_decided' => $data['mmDecidedDate'],
				'spl_remark'       => $data['remarks'],
				'm_brd_slno'       => $data['itemNo'],
				'm_roster_id'      => $data['bench'],
				'update_time'      => date("Y-m-d H:i:s")
			];

			$this->db->table('mention_memo')
			   ->where('id', $data['id'])
			   ->update($updateData);
		}

      $this->db->transComplete(); 

		if ($this->db->transStatus() === false) {
			return false;
		}

		return true; 
   }
   
   
   
	function DeleteMmData($data){
		
		$builder = $this->db->table('mention_memo');
		$mmCurrentRecord = $builder->where('id', $data['id'])->get()->getRowArray();

		if (!$mmCurrentRecord) {
			return false; 
		}

       $auditData = [
			'event_type'        => 'D',
			'ipaddress'         => $_SERVER['REMOTE_ADDR'],
			'update_user'       => $data['session_id_url'],
			'action_perform_on' => date("Y-m-d H:i:s"),
		];

		$oldmmdata = array_merge($mmCurrentRecord, $auditData);

		$this->db->transStart();

		$this->db->table('mention_memo_history')->insert($oldmmdata);

		$this->db->table('mention_memo')->where('id', $data['id'])->delete();

		$this->db->transComplete();

		if ($this->db->transStatus() === false) {
			return false;
		} else {
			return true; 
		}
		
	}

	function getAccessDetails($id){
		$conditions = [
			//'usertype' => 4,
			//'section'  => 11,
			//'display'  => 'Y',
			'usercode' => $id
		];

		return $this->db->table('master.users')
				  ->where($conditions)
				  ->get()
				  ->getResult();
	}
	
    public function advocate_by_diary_number($dno) 
    {    
        $builder = $this->db->table('advocate');
        $builder->select('pet_res_no, adv, advocate_id, pet_res')
            ->where('diary_no', $dno)
            ->where('display', 'Y')
            ->orderBy('pet_res');
        $query = $builder->get();
        $result_advp = $query->getResultArray();
        
        $padvname = $radvname = '';
        foreach ($result_advp as $row_advp) {
            $tmp_advname = "<p>&nbsp;&nbsp;";
            $tmp_advname .= $this->get_advocates($row_advp['advocate_id'], '') . $row_advp['adv'];
            $tmp_advname .= "</p>";

            if ($row_advp['pet_res'] == "P") {
                $padvname .= $tmp_advname;
            }
            if ($row_advp['pet_res'] == "R") {
                $radvname .= $tmp_advname;
            }
        }
        $data['padvname'] = $padvname;
        $data['radvname'] = $radvname;
        return $data;
    }

    private function get_advocates($adv_id, $wen = '')
    {

        $db = \Config\Database::connect();
        $builder = $db->table('master.bar');
        $builder->select('name, enroll_no, EXTRACT(YEAR FROM enroll_date) AS eyear, isdead');
        $builder->where('bar_id', $adv_id);
        $query = $builder->get();
        $t_adv ='';
        if ($query->getNumRows() > 0) {
            $row = $query->getRowArray();
            $t_adv = $row['name'];
            if ($row['isdead'] == 'Y') {
                $t_adv = "<font color='red'>" . $t_adv . " (Dead) </font>";
            }
            if ($wen == 'wen') {
                $t_adv .= " [" . $row['enroll_no'] . "/" . $row['eyear'] . "]";
            }
        }

        return $t_adv;
    }

    public function get_short_description($dno)
    {
        $builder = $this->db->table('main a');
        $builder->select('b.short_description')
                ->join('master.casetype b', 'a.casetype_id = b.casecode', 'left')
                ->where('a.diary_no', $dno);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result['short_description'] ?? '';
    }

    public function get_categories($diaryNo)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select('b.id, category_sc_old, submaster_id, subcode1, subcode2, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'submaster_id = b.id', 'left');
        $builder->where('a.diary_no', $diaryNo);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function check_other_categories($diaryNo)
    {
        return $this->db->table('other_category')
                        ->where('display', 'Y')
                        ->where('diary_no', $diaryNo)
                        ->countAllResults() > 0;
    }

    public function get_new_categories($diaryNo)
    {
        $builder = $this->db->table('mul_category a');
        $builder->select('b.id, category_sc_old, submaster_id, subcode1, subcode2, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'submaster_id = b.id', 'left');
        $builder->where('a.diary_no', $diaryNo);
        $builder->where('a.display', 'Y');
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    public function get_search_details($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.pno, a.rno, a.from_court, pet_name, res_name, c_status, ref_agency_state_id, ref_agency_code_id, active_fil_no, active_reg_year, b.name as agency_state, agency_name, short_description, active_fil_dt, diary_no_rec_date, h.next_dt, roster_id, clno, h.brd_slno, h.board_type, h.main_supp_flag, string_agg(j.jname, \',\') as jname');
        $builder->join('master.state b', 'ref_agency_state_id = b.id_no', 'left');
        $builder->join('master.ref_agency_code c', 'ref_agency_code_id = c.id', 'left');
        $builder->join('master.casetype d', 'active_casetype_id = casecode', 'left');
        $builder->join('heardt h', 'a.diary_no = h.diary_no', 'left');
        $builder->join('master.judge j', "j.jcode = ANY (string_to_array(h.judges, ',')::int[])", 'left');
        $builder->where('a.diary_no', $diary_no);
        $builder->groupBy('a.diary_no,b.name,c.agency_name,d.short_description,h.roster_id,h.clno,h.brd_slno,h.board_type,a.pno,h.main_supp_flag, h.next_dt');;
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_caveat_advocate($diary_no)
    {
        $builder = $this->db->table('caveat_diary_matching a');
        $builder->select('b.*, bar.name');
        $builder->join('caveat_advocate b', 'a.caveat_no = b.caveat_no', 'left');
        $builder->join('master.bar', 'b.advocate_id = bar.bar_id', 'left');
        $builder->join('advocate adv', 'adv.diary_no = a.diary_no AND b.advocate_id = adv.advocate_id');
        $builder->where('adv.display', 'Y');
        $builder->where('a.display', 'Y');
        $builder->where('a.diary_no', $diary_no);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function Proof_of_service($diary_no)
    {   
        $result = "NO";
        $builder = $this->db->table('docdetails');
        $builder->select('docd_id');
        $builder->where('diary_no', $diary_no);
        $builder->where('doccode', 18);
        $builder->where('display', 'Y');
        $exists = $builder->countAllResults() > 0;
        if ($exists) {
            $result = "YES";
        }
        return $result;
    }

    public function ia_pending_details($diary_no)
    {   
        $builder5 = $this->db->table('docdetails a');
        $builder5->select('docnum, docyear, other1, a.doccode1, docdesc, a.ent_dt');
        $builder5->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'left');
        $builder5->where('diary_no', $diary_no);
        $builder5->where('a.doccode', 8);
        $builder5->where('a.display', 'Y');
        $builder5->where('b.display', 'Y');
        $builder5->where('iastat', 'P');
        $builder5->orderBy('a.ent_dt', 'ASC');
        $query = $builder5->get();
        return $query->getResultArray();
    }

    public function get_not_before_details($diary_no)
    {
        $builder1 = $this->db->table('heardt h');
        $builder1->select("jcode, STRING_AGG(jname, ' ') AS jname, h.diary_no, 'C' AS notbef, ent_dt, res_add, coram");
        $builder1->join('master.judge j', "j.jcode = ANY(ARRAY(SELECT unnest(string_to_array(h.coram, ','))::integer))", 'join');
        $builder1->join('master.not_before_reason', 'list_before_remark = res_id', 'left');
        $builder1->where('h.diary_no', $diary_no);
        $builder1->groupBy('h.diary_no, jcode, res_add');
        $query1 = $builder1->getCompiledSelect();
 
        $builder2 = $this->db->table('not_before');
        $builder2->select("CAST(jcode AS bigint), jname, CAST(diary_no AS bigint), not_before.notbef AS notbef, ent_dt, not_before_reason.res_add, '0'");
        $builder2->join('master.judge j', 'jcode = j1', 'left');
        $builder2->join('master.not_before_reason', 'not_before.res_id = not_before_reason.res_id', 'left');
        $builder2->where('not_before.diary_no', $diary_no);
        $query2 = $builder2->getCompiledSelect();
        $finalQuery = "$query1 UNION $query2";
        $results = $this->db->query($finalQuery)->getResultArray();
        
        foreach($results as $index => $row){
            $notbef = $dt = "";
            if ($row['notbef'] == 'N') {
                $notbef = '<span style="color:red">Not before</span>';
            } elseif ($row['notbef'] == 'B') {
                $notbef = 'Before/SPECIAL';
            } elseif ($row['notbef'] == 'C') {
                $notbef = 'Coram';
            }
            $results[$index]['notbef'] = $notbef;
            if($row['notbef'] == 'C'){
                $dt = $this->get_coram_entry_date($diary_no, $row['coram']);
            } else {
                $dt = $row['ent_dt'];
            }
            $results[$index]['ent_dt'] = $dt;
        }
        return $results;
    }

    public function get_coram_entry_date($diary_no, $coram)
    {
        $builder1 = $this->db->table('heardt');
        $builder1->select('coram, ent_dt');
        $builder1->where('diary_no', $diary_no);
        $builder1->where('coram', $coram);
        $query1 = $builder1->getCompiledSelect();

        // Second query
        $builder2 = $this->db->table('last_heardt');
        $builder2->select('coram, ent_dt');
        $builder2->where('diary_no', $diary_no);
        $builder2->where('coram', $coram);
        $query2 = $builder2->getCompiledSelect();
        $finalQuery = "$query1 UNION $query2 ORDER BY ent_dt ASC";
        $result = $this->db->query($finalQuery)->getResultArray();
        return isset($result[0]['ent_dt']) ? $result[0]['ent_dt'] :'';
    }

    public function f_get_ntl_judge($diary_no)
    {
        
        $builder = $this->db->table('advocate a');
        $builder->select('j.abbreviation')
            ->join('master.ntl_judge n', 'a.advocate_id = n.org_advocate_id', 'LEFT')
            ->join('master.judge j', 'j.jcode = n.org_judge_id', 'LEFT')
            ->where('a.diary_no', $diary_no)
            ->where('j.is_retired !=', 'Y')
            ->where('a.display', 'Y')
            ->where('n.display', 'Y')
            ->where('n.org_advocate_id IS NOT NULL')
            ->where('j.jcode IS NOT NULL')
            ->groupBy('j.abbreviation'); // Group by abbreviation

        $result = $builder->get()->getResultArray();
        $results = [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $results[] = nl2br("<font color='red'> AOR N : " . htmlspecialchars($row["abbreviation"]) . "</font>");
            }
        }
        return $results[0]?? '';
    }

    public function f_get_ndept_judge($diary_no){
        $builder = $this->db->table('party a');
        $builder->select('j.abbreviation')
            ->join('master.ntl_judge_dept n', 'a.deptcode = n.dept_id', 'LEFT')
            ->join('master.judge j', 'j.jcode = n.org_judge_id', 'LEFT')
            ->where('n.display', 'Y')
            ->where('a.diary_no', $diary_no)
            ->where('a.pflag !=', 'T')
            ->where('j.is_retired !=', 'Y')
            ->where('a.deptcode IS NOT NULL')
            ->where('j.jcode IS NOT NULL');
        $result = $builder->get()->getResultArray();
        $results = [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $results[] = nl2br("<font color='red'> Dept N : " . htmlspecialchars($row["abbreviation"]) . "</font>");
            }
        }
        return $results[0]?? '';
    }

    function f_get_category_judge($diary_no)
    {
        $subQuery1 = $this->db->table('mul_category c')
            ->select('s.id, s.sub_name1')
            ->join('master.submaster s', 's.id = c.submaster_id')
            ->where('c.diary_no', $diary_no)
            ->where('c.display', 'Y')
            ->where('s.display', 'Y')
            ->getCompiledSelect();
            //pr($subQuery1);
        $subQuery2 = $this->db->table("($subQuery1) a")
            ->select('a.id')
            ->join('master.submaster s', 's.sub_name1 = a.sub_name1')
            ->where('s.flag', 's')
            ->getCompiledSelect();
        //pr($subQuery2);
        $finalQuery = $this->db->table("($subQuery2) b")
            ->select('j.abbreviation')
            ->join('master.ntl_judge_category n', 'n.cat_id = b.id')
            ->join('master.judge j', 'j.jcode = n.org_judge_id', 'LEFT')
            ->where('n.display', 'Y')
            ->where('j.jcode IS NOT NULL')
            ->getCompiledSelect();
            //pr($finalQuery);
        $result = $this->db->query($finalQuery)->getResultArray();
        $results = [];
        if (!empty($result)) {
            foreach ($result as $row) {
                $results[] =nl2br("<font color='red'> Categ. N : " . htmlspecialchars($row["abbreviation"]) . "</font>");
            }
        }
        return $results[0]?? '';
    }
	
	
	function get_diary_details($caseTypeId=null,$caseNo=null,$caseYear=null,$diaryNo=null,$diaryYear=null) {
			$request = \Config\Services::request();
			$optradio = $request->getPost('search_type');
			$sql = "";
			$params = [];

			if ($optradio == 'C') {
					$caseTypeId = $request->getPost('case_type');
					$caseNo = $request->getPost('case_number');
					$caseYear = $request->getPost('case_year');

					// Using Active Record
					$query = $this->db->table('main_casetype_history h')
							->select("h.diary_no, 
									  SUBSTRING(h.diary_no::text, 1, LENGTH(h.diary_no::text) - 4) AS dn, 
									  SUBSTRING(h.diary_no::text, LENGTH(h.diary_no::text) - 3) AS dy")
							->where("split_part(h.new_registration_number, '-', 1)", $caseTypeId)
							->where("{$caseNo} BETWEEN CAST(split_part(h.new_registration_number, '-', 2) AS INTEGER) 
												   AND CAST(split_part(h.new_registration_number, '-', 3) AS INTEGER)")
							->where('h.new_registration_year', $caseYear)
							->where('h.is_deleted', 'f')
							->get();
				}

				if ($optradio == 'D') {
					$diaryNo = $request->getPost('diary_number');
					$diaryYear = $request->getPost('diary_year');
					
					
					$builder = $this->db->table('main');

						$builder->select("
							diary_no,
							LEFT(diary_no::text, LENGTH(diary_no::text) - 4) AS dn,
							RIGHT(diary_no::text, 4) AS dy
						");

						$builder->where("LEFT(diary_no::text, LENGTH(diary_no::text) - 4) = ", $diaryNo);
					    $builder->where("RIGHT(diary_no::text, 4) = ", $diaryYear);

						$query = $builder->get();
				
				}

				if ($query->getNumRows() >= 1) {
					return $query->getResultArray();
				} else {
					return [];
				} 
	}
	
	
	function get_mmData_code($date, $year, $d_no, $flistype){
       
		$builder = $this->db->table('mention_memo mm');
		$builder->select('*');
		$builder->where('mm.diary_no', $d_no);
		$builder->where("EXTRACT(YEAR FROM mm.date_of_received) =", $date);
		//$builder->where('mm.date_of_received', $date);
		$builder->orderBy('mm.update_time', 'DESC');
        return $builder->get()->getRowArray();
    }
	
	function getmain_data($date, $year, $d_no, $flistype){
		
		$builder = $this->db->table('mention_memo mm');
		$builder->select('*');
		$builder->where('mm.diary_no', $d_no.$year);
		return $builder->get()->getRowArray();
		
      }
	  
	  function getuser_details($dacode){
		  $builder = $this->db->table('master.users u');
		  $builder->select('*');
		  $builder->where('u.usercode', $dacode);
		  return $builder->get()->getRowArray();
	  }




}