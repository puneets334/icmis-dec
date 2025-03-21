<?php

namespace App\Models\RI;

use CodeIgniter\Model;

class RIModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function showRIData()
    {
        $builder = $this->db->table('ec_postal_received ep');
        $builder->select(["ep.id,concat(ep.diary_no,'/',ep.diary_year) as postal_diary_no,ep.postal_no,ep.postal_date, ep.sender_name,rpt.postal_type_description,
ep.address,ep.postal_addressee, ep.ec_case_id,
(case when dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) 
else case when dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') 
from master.users where usercode=ept.dispatched_to) 
else case when dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ep.postal_addressee end end end) as address_to"]);
        $builder->join('master.ref_postal_type rpt', 'ep.ref_postal_type_id=rpt.id', 'inner');
        $builder->join('ec_postal_transactions ept', 'ep.id=ept.ec_postal_received_id', 'left');
        $builder->where("(ept.is_active='t' or ept.is_active is null) and ep.is_ad_card=0");
        $builder->orderBy('ep.id', 'DESC');
        $builder->limit(30);

        $query = $builder->get();
        // $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            //            echo "<pre>";
            //            print_r($result);
            //            die;
            return $result;
        } else {
            return false;
        }
    }

    public function getReceiptId($diaryNo, $diaryYear)
    {
        $builder = $this->db->table('ec_postal_received');
        $builder->select('id');
        $builder->where('diary_no', $diaryNo);
        $builder->where('diary_year', $diaryYear);
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;
        if (!empty($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getJudge()
    {
        // Building the query
        $builder = $this->db->table('master.judge');
        $builder->select('*');
        $builder->where('display', 'Y');
        $builder->where('jtype', "J");
        $builder->where('is_retired', "N");
        $builder->orderBy('jtype', 'ASC');
        $builder->orderBy('judge_seniority', 'ASC');

        // Getting the result from the builder
        $query = $builder->get();  // This returns a result object

        // Use the result object to get data as an array
        $result = $query->getResultArray();

        // Check the number of rows returned
        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    //     public function getProcessIdDetails($processId, $processYear)
    //     {

    //         $builder = $this->db->table('ec_postal_dispatch epd');
    //         $builder->select([
    //                 'epd.is_case',
    //                 'epd.is_with_process_id',
    //                 'epd.reference_number',
    //                 'epd.id AS ec_postal_dispatch_id',
    //                 'epd.process_id',
    //                 'epd.process_id_year',
    //                 "CASE WHEN m.reg_no_display IS NOT NULL THEN m.reg_no_display ELSE CONCAT(LEFT(CAST(m.diary_no AS TEXT),4), RIGHT(CAST(m.diary_no AS TEXT),4)) END AS case_no",
    //                 'epd.diary_no',
    //                 'epd.send_to_name',
    //                 'epd.send_to_address',
    //                 'tn.name AS doc_type',
    //                 's.name AS state_name',
    //                 'd.name AS district_name',
    //                 'epd.pincode',
    //                 'epd.tal_state',
    //                 'epd.tal_district',
    //                 'us.section_name',
    //                 'epd.serial_number',
    //                 'epd.ref_postal_type_id',
    //                 'epd.postal_charges',
    //                 'epd.weight',
    //                 'epd.waybill_number',
    //                 'epd.usersection_id',
    //                 '(SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section',
    //                 '(SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage',
    //                 '(SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type',
    //                 'epd.serve_remarks',
    //                 'rpt.postal_type_description'
    //             ]);
    //         $builder->join('main m', 'epd.diary_no = m.diary_no', 'left');
    //         $builder->join('master.tw_notice tn', 'epd.tw_notice_id = tn.id', 'left');
    //         $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
    //         $builder->join('master.ref_postal_type rpt', 'epd.ref_postal_type_id = rpt.id', 'left');
    //         $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
    //         $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');
    //         $builder->where('epd.process_id', $processId);
    //         $builder->where('epd.process_id_year', $processYear);
    //         $builder->where('epd.ref_letter_status_id', '2');
    //         $builder->orderBy('epd.ref_postal_type_id');
    //         $builder->orderBy('epd.serial_number');

    //         $query = $builder->get();
    //         //$query=$this->db->getLastQuery();echo (string) $query;exit();
    //         $result = $query->getResultArray();

    //         if($query->getNumRows()>=1)
    //         {
    //             echo gettype($result);
    // //            die;
    //             return $result;
    //         }else{
    //             return false;
    //         }

    //     }
    public function getProcessIdDetails($processId, $processYear)
    { //echo $processYear; die();
        $builder = $this->db->table('ec_postal_dispatch epd');
        $builder->select([
            'epd.is_case',
            'epd.is_with_process_id',
            'epd.reference_number',
            'epd.id AS ec_postal_dispatch_id',
            'epd.process_id',
            'epd.process_id_year',
            "CASE WHEN m.reg_no_display IS NOT NULL THEN m.reg_no_display ELSE CONCAT(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4)) END AS case_no",
            'epd.diary_no',
            'epd.send_to_name',
            'epd.send_to_address',
            'tn.name AS doc_type',
            's.name AS state_name',
            'd.name AS district_name',
            'epd.pincode',
            'epd.tal_state',
            'epd.tal_district',
            'us.section_name',
            'epd.serial_number',
            'epd.ref_postal_type_id',
            'epd.postal_charges',
            'epd.weight',
            'epd.waybill_number',
            'epd.usersection_id',
            '(SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section',
            '(SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage',
            '(SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type',
            'epd.serve_remarks',
            'rpt.postal_type_description'
        ]);

        // $builder->join('main m', 'epd.diary_no = m.diary_no' );
        // $builder->join('master.tw_notice tn', 'epd.tw_notice_id = tn.id' );
        // $builder->join('master.usersection us', 'epd.usersection_id = us.id',);
        // $builder->join('master.ref_postal_type rpt', 'epd.ref_postal_type_id = rpt.id');
        // $builder->join('master.state s', 's.id_no = epd.tal_state');
        // $builder->join('master.state d', 'd.id_no = epd.tal_district');
        $builder->join('main m', 'epd.diary_no = m.diary_no', 'LEFT');
        $builder->join('master.tw_notice tn', 'epd.tw_notice_id = tn.id', 'LEFT');
        $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'LEFT');
        $builder->join('master.ref_postal_type rpt', 'epd.ref_postal_type_id = rpt.id', 'LEFT');
        $builder->join('master.state s', 's.id_no = epd.tal_state', 'LEFT');
        $builder->join('master.state d', 'd.id_no = epd.tal_district', 'LEFT');

        $builder->where('epd.process_id', $processId);
        $builder->where('epd.process_id_year', $processYear);
        $builder->where('epd.ref_letter_status_id', '2');
        $builder->orderBy('epd.ref_postal_type_id');
        $builder->orderBy('epd.serial_number');

        //echo $builder->getCompiledSelect();
        //die;

        $query = $builder->get();
        $result = $query->getResultArray();
        // return !empty($result) ? $result : false;
        // return $result;
        //print_r($result);die();



        return $result;
        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';




        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }
    ### menu 2
    public function getDataToDispatchFrmRI($data)
    {
        // print_r($data);die;
        if ($data['status'] == 0) {
            $wherePostalDispatch = "epd.id IS NULL";
        } elseif ($data['status'] == 8888) {
            $wherePostalDispatch = "epd.ref_letter_status_id IN (3,6)";
        } elseif ($data['status'] == 9999) {
            $wherePostalDispatch = "epd.ref_letter_status_id IN (4,5)";
        } else {
            $wherePostalDispatch = "epd.ref_letter_status_id=" . (int)$data['status']; // Cast to integer to prevent SQL injection
        }
        $whereDateRange = '';
        if (isset($data['searchBy'])) {
            if ($data['searchBy'] == 's') {
                if (!empty($data['fromDate']) && !empty($data['toDate'])) {
                    $fromDate = date('Y-m-d', strtotime($data['fromDate']));
                    $toDate = date('Y-m-d', strtotime($data['toDate']));
                    if ($data['dealingSection'] != 0) {
                        $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate' AND epd.usersection_id = " . (int)$data['dealingSection'];
                    } else {
                        $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate'";
                    }
                }
            } elseif ($data['searchBy'] == 'c' || $data['searchBy'] == 'd') {
                $fetchedDiaryNo = $this->getSearchDiary($data['searchBy'], $data['caseType'], $data['caseNo'], $data['caseYear'], $data['diaryNumber'], $data['diaryYear']);
                $whereDateRange = " AND epd.diary_no=" . (int)$fetchedDiaryNo;
            } elseif ($data['searchBy'] == 'p') {
                $whereDateRange = " AND epd.process_id=" . (int)$data['processId'] . " AND process_id_year=" . (int)$data['processYear'];
            }

            if ($data['dispatchMode'] != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id=" . (int)$data['dispatchMode'];
            }
        } else {
            if (!empty($data['fromDate']) && !empty($data['toDate'])) {
                $fromDate = date('Y-m-d', strtotime($data['fromDate']));
                $toDate = date('Y-m-d', strtotime($data['toDate']));
                if ($data['dealingSection'] != 0) {
                    $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate' AND epd.usersection_id=" . (int)$data['dealingSection'];
                } else {
                    $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate'";
                }
            }
            if ($data['dispatchMode'] != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id=" . (int)$data['dispatchMode'];
            }
        }
        $sql = "SELECT 
        epd.is_case, 
        epd.is_with_process_id, 
        epd.reference_number, 
        epd.id AS ec_postal_dispatch_id, 
        epd.process_id, 
        epd.process_id_year,
        CASE 
            WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') THEN 
                CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) 
            ELSE 
                m.reg_no_display 
        END AS case_no,
        epd.diary_no, 
        epd.send_to_name, 
        epd.send_to_address, 
        tn.name AS doc_type, 
        s.name AS state_name,
        d.name AS district_name, 
        epd.pincode, 
        epd.tal_state, 
        epd.tal_district,
        (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) AS sent_by,
        epdt.updated_on AS sent_on, 
        us.section_name, 
        epd.serial_number,
        epd.ref_postal_type_id, 
        epd.postal_charges, 
        epd.weight, 
        epd.waybill_number, 
        epd.usersection_id,
        (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
        (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
        (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type, 
        epd.serve_remarks
        FROM ec_postal_dispatch epd
        INNER JOIN ec_postal_dispatch_transactions epdt ON epd.id = epdt.ec_postal_dispatch_id AND epd.ref_letter_status_id = epdt.ref_letter_status_id
        LEFT JOIN main m ON epd.diary_no = m.diary_no
        LEFT JOIN master.tw_notice tn ON epd.tw_notice_id = tn.id
        LEFT JOIN master.usersection us ON epd.usersection_id = us.id
        LEFT JOIN master.state s ON s.id_no = epd.tal_state
        LEFT JOIN master.state d ON d.id_no = epd.tal_district
         WHERE $wherePostalDispatch $whereDateRange 
         ORDER BY epd.ref_postal_type_id, epd.serial_number ";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';
    }
    ////menu3 komal
    function getLettersWithProcessId($fromDate, $toDate, $usercode, $status = 0)
    {
        //echo $fromDate. $usercode;die;
        $wherePostalDispatch = "";
        $removeConnected = "";
        if ($status == 0) {
            $wherePostalDispatch = " and epd.id is null";
        } else {
            $wherePostalDispatch = " and epd.ref_letter_status_id=" . $status;
        }
        if ($status == 2) {
            $removeConnected = " and epdcl.id is not null";
        }

        $sql = "SELECT 
            (SELECT 
                CASE 
                    WHEN del_type = 'O' THEN 5 
                    WHEN del_type = 'R' THEN 7 
                    ELSE NULL 
                END 
             FROM tw_o_r 
             WHERE tw_org_id = ttd.id 
               AND display = 'Y' 
             ORDER BY id DESC 
             LIMIT 1
            ) AS ref_postal_type_id,
            ttd.id AS tw_tal_del_id,
            ttd.process_id,
            EXTRACT(YEAR FROM ttd.rec_dt) AS process_id_year,
            CONCAT(
                COALESCE(m.reg_no_display, ''), ' @ ', 
                CASE 
                    WHEN LENGTH(m.diary_no::TEXT) > 4 
                    THEN SUBSTRING(m.diary_no::TEXT FROM 1 FOR LENGTH(m.diary_no::TEXT) - 4) || '/' || 
                        SUBSTRING(m.diary_no::TEXT FROM LENGTH(m.diary_no::TEXT) - 3 FOR 4)
                    ELSE m.diary_no::TEXT
                END
            ) AS case_no,
            ttd.diary_no,
            ttd.name,
            ttd.address,
            tn.name AS doc_type,
            s.name AS state_name,
            d.name AS district_name,
            tpd.pin_code,
            ttd.tal_state,
            ttd.tal_district
        FROM tw_tal_del ttd
        INNER JOIN master.tw_notice tn ON cast(ttd.nt_type as INTEGER) = tn.id
        LEFT JOIN main m ON ttd.diary_no = m.diary_no
        LEFT JOIN master.state s ON s.id_no = ttd.tal_state
        LEFT JOIN master.state d ON d.id_no = ttd.tal_district
        LEFT JOIN ec_postal_dispatch epd ON epd.tw_tal_del_id = ttd.id
        LEFT JOIN ec_postal_dispatch_connected_letters epdcl ON epd.id = epdcl.ec_postal_dispatch_id
        LEFT JOIN master.tw_pin_code tpd ON d.State_code = tpd.state_code 
                                 AND d.District_code = tpd.district_code
        WHERE DATE(ttd.published_on) BETWEEN ? AND ? 
          AND ttd.published_by = ? 
          AND (epdcl.is_deleted = ? OR epdcl.is_deleted IS NULL) 
          AND ttd.display = ? 
          $wherePostalDispatch 
          $removeConnected
        ORDER BY ttd.published_on";

        $query = $this->db->query($sql, array($fromDate, $toDate, $usercode, 0, 'Y'));

        // Return the result as an array
        return $query->getResultArray();
    }
    ///menu 3 insert
    public function dispatchToRIWithProcessId($id, $dispatchMode, $usersection, $usercode)
    {
        //print_r($dispatchMode); die();
        $pincode = 0;
        $insertQuery = "
        INSERT INTO ec_postal_dispatch (
            is_with_process_id,
            is_case,
            tw_notice_id,
            diary_no,
            tw_tal_del_id,
            process_id,
            process_id_year,
            send_to_name,
            send_to_address,
            tal_state,
            tal_district,
            pincode,
            ref_letter_status_id,
            ref_postal_type_id,
            usersection_id,
            usercode,
            updated_on
        )
        SELECT 
            1,
            1,
            CAST(ttd.nt_type AS bigint),
            ttd.diary_no,
            ttd.id,
            ttd.process_id,
            EXTRACT(YEAR FROM ttd.rec_dt),
            ttd.name,
            ttd.address,
            ttd.tal_state,
            ttd.tal_district,
            ?,  
            1,
            ?,  
            ?,  
            ?, 
            NOW()
        FROM 
            tw_tal_del ttd 
        WHERE 
            ttd.id = ?
    ";

        $this->db->transStart();  // Start transaction

        $this->db->query($insertQuery, [$pincode, $dispatchMode, $usersection, $usercode, $id]);

        $this->db->transComplete();
        $ecPostalDispatchId = $this->db->insertID();
        if (isset($ecPostalDispatchId) && $ecPostalDispatchId > 0) {
            // Prepare the data for the next insertion
            $dataToInsert = [
                'ec_postal_dispatch_id' => $ecPostalDispatchId,
                'ref_letter_status_id' => 1,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('ec_postal_dispatch_transactions')->insert($dataToInsert);
        }
    }
    public function getMainLetterDetails($ecPostalDispatchId)
    {
        //        echo gettype($ecPostalDispatchId)."+++++".$ecPostalDispatchId."<br>";
        //        die;
        $ecPostalDispatchId = intval($ecPostalDispatchId);
        //        echo gettype($ecPostalDispatchId).">>>".$ecPostalDispatchId;
        //        die;
        $builder = $this->db->table('ec_postal_dispatch epd');
        $builder->select('epd.id,epd.process_id,epd.process_id_year,epd.send_to_name,epd.send_to_address,epd.ref_letter_status_id,epdct.id as connected_id');
        $builder->join('ec_postal_dispatch_connected_letters epdct', 'epd.id=epdct.ec_postal_dispatch_id', 'left');
        $builder->where('epd.id', $ecPostalDispatchId);
        $builder->where("epdct.is_deleted=0 or epdct.is_deleted is null");
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;
        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    public function addConnectedLetter($ecPostalDispatchId, $ecMainId, $usercode)
    {
        $dataForConnect = array(
            'ec_postal_dispatch_id' => $ecPostalDispatchId,
            'ec_postal_dispatch_id_main' => $ecMainId,
            'usercode' => $usercode,
            'updated_on' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_by' => $usercode,
            'updated_by_ip' => getClientIP()
        );
        //        $this->db->insert('ec_postal_dispatch_connected_letters', $dataForConnect);
        $builder = $this->db->table('ec_postal_dispatch_connected_letters');
        //        $query = $builder->insert($dataForConnect);
        $query = $builder->set($dataForConnect)->getCompiledInsert('ec_pil_group_file');
        echo $query;
        die;
        //        return $query;
    }

    public function getSection()
    {
        $builder = $this->db->table('master.usersection');
        $builder->select('*');
        $builder->where('display', 'Y');
        $builder->orderBy('section_name', 'ASC');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return 'false';
        }
    }

    public  function getOfficers()
    {
        $builder = $this->db->table('master.users u');

        $builder->select('usercode, empid, name, ut.type_name');
        $builder->join('master.usertype ut', 'u.usertype = ut.id', 'INNER');
        $builder->groupStart();
        $builder->like('type_name', 'Secretary General', 'both', '!', true);
        $builder->orLike('type_name', 'Registrar', 'both', '!', true);
        $builder->orLike('type_name', 'Additional Registrar', 'both', '!', true);
        $builder->orLike('type_name', 'Deputy Registrar', 'both', '!', true);
        $builder->orLike('type_name', 'Assistant Registrar', 'both', '!', true);
        $builder->orLike('type_name', 'AR-Cum-PS', 'both', '!', true);
        $builder->orLike('type_name', 'Assistant Editor', 'both', '!', true);
        $builder->orLike('type_name', 'Branch Officer', 'both', '!', true);
        $builder->orLike('type_name', 'OSD', 'both', '!', true);
        $builder->groupEnd();
        $builder->orderBy('name');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return 'false';
        }
    }

    #menu4
    public function getCaseType()
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('casecode, skey, casename,short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode!=', '9999');
        $builder->orderBy('casecode');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }
    public function getServeType($type, $serveStage = 0)
    {
        $builder = $this->db->table('master.tw_serve');
        $builder->where('display', 'Y');
        if ($type == 1) {
            $builder->orderBy('serve_stage', 'ASC');
            $builder->where('serve_type', 0);
        } elseif ($type == 2) {
            $builder->orderBy('serve_type', 'ASC');
            $builder->where('serve_stage', $serveStage);
            $builder->where('serve_type != ', 0);
        }

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return 'false';
        }
    }
    ####menu5
    public function getReceiptMode()
    {
        $builder = $this->db->table('master.ref_postal_type');
        $builder->select('*');
        $builder->where('id !=', '9999');
        $builder->orderBy('postal_type_code', 'ASC');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }
    #####menu 5 dropdown
    public function getRIUserList()
    {
        $builder = $this->db->table('master.users');
        $builder->select("usercode, CONCAT(name, ' (', empid, ')') as name");
        $builder->whereIn('empid', [1, 4284, 4454, 4471, 1713, 4955, 4000, 1637]);
        $builder->orderBy('name');

        $query = $builder->get();
        $result = $query->getResultArray();

        return !empty($result) ? $result : false;
    }
    ######menu5 report
    public function getAddressSlipData($post)
    {
        extract($post);
        $result = [];
        foreach ($to as $index => $user) {
            $builder = $this->db->table('ec_postal_dispatch epd');
            $builder->select("
                epd.is_case, 
                epd.is_with_process_id, 
                epd.reference_number, 
                epd.id as ec_postal_dispatch_id, 
                epd.process_id, 
                epd.process_id_year,
                CASE 
                    WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') 
                    THEN CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS VARCHAR), LENGTH(CAST(m.diary_no AS VARCHAR)) - 4), '/', SUBSTRING(CAST(m.diary_no AS VARCHAR), -4)) 
                    ELSE m.reg_no_display 
                END AS case_no,
                epd.diary_no, 
                epd.send_to_name, 
                epd.send_to_address, 
                tn.name AS doc_type, 
                s.name AS state_name,
                d.name AS district_name, 
                epd.pincode, 
                epd.tal_state, 
                epd.tal_district, 
                us.section_name, 
                epd.serial_number,
                epd.ref_postal_type_id, 
                epd.postal_charges, 
                epd.weight, 
                epd.waybill_number, 
                epd.usersection_id, 
                epdt.updated_on AS receive_time,
                $index AS orderbycolumn
            ");

            $builder->join('ec_postal_dispatch_transactions epdt', 'epd.id = epdt.ec_postal_dispatch_id', 'inner');
            $builder->join('main m', 'epd.diary_no = m.diary_no', 'left');
            $builder->join('master.tw_notice tn', 'epd.tw_notice_id = tn.id', 'left');
            $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
            $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
            $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');

            //  date commnt temprry
            //$builder->where('DATE(epdt.updated_on)', date('Y-m-d', strtotime($receivedDate)));
            //$builder->where('epdt.ref_letter_status_id', 2);
            $builder->where('epdt.ref_letter_status_id', 1);
            // $builder->where('epdt.usercode', $user);
            $builder->where('epdt.usercode', 1);

            $builder->where('epd.ref_postal_type_id', $dispatchMode);
            // Print the compiled SQL query
            //echo $builder->getCompiledSelect();die();
            $query = $builder->get();
            $userResult = $query->getResultArray();
            // Append results to the final array
            if (!empty($userResult)) {
                $result = array_merge($result, $userResult);
            }
        }

        // Order by 'orderbycolumn' and 'receive_time'
        usort($result, function ($a, $b) {
            return $a['orderbycolumn'] <=> $b['orderbycolumn'] ?: strtotime($a['receive_time']) <=> strtotime($b['receive_time']);
        });
        return !empty($result) ? $result : false;
    }
    ### menu 6
    public function getDataToreDispatchFrmRI($data)
    {
        // print_r($data);die;
        if ($data['status'] == 0) {
            $wherePostalDispatch = "epd.id IS NULL";
        } elseif ($data['status'] == 8888) {
            $wherePostalDispatch = "epd.ref_letter_status_id IN (3,6)";
        } elseif ($data['status'] == 9999) {
            $wherePostalDispatch = "epd.ref_letter_status_id IN (4,5)";
        } else {
            $wherePostalDispatch = "epd.ref_letter_status_id=" . (int)$data['status']; // Cast to integer to prevent SQL injection
        }
        $whereDateRange = '';
        if (isset($data['searchBy'])) {
            if ($data['searchBy'] == 's') {
                if (!empty($data['fromDate']) && !empty($data['toDate'])) {
                    $fromDate = date('Y-m-d', strtotime($data['fromDate']));
                    $toDate = date('Y-m-d', strtotime($data['toDate']));
                    if ($data['dealingSection'] != 0) {
                        $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate' AND epd.usersection_id = " . (int)$data['dealingSection'];
                    } else {
                        $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate'";
                    }
                }
            } elseif ($data['searchBy'] == 'c' || $data['searchBy'] == 'd') {
                $fetchedDiaryNo = $this->getSearchDiary($data['searchBy'], $data['caseType'], $data['caseNo'], $data['caseYear'], $data['diaryNumber'], $data['diaryYear']);
                $whereDateRange = " AND epd.diary_no=" . (int)$fetchedDiaryNo;
            } elseif ($data['searchBy'] == 'p') {
                $whereDateRange = " AND epd.process_id=" . (int)$data['processId'] . " AND process_id_year=" . (int)$data['processYear'];
            }

            if ($data['dispatchMode'] != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id=" . (int)$data['dispatchMode'];
            }
        } else {
            if (!empty($data['fromDate']) && !empty($data['toDate'])) {
                $fromDate = date('Y-m-d', strtotime($data['fromDate']));
                $toDate = date('Y-m-d', strtotime($data['toDate']));
                if ($data['dealingSection'] != 0) {
                    $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate' AND epd.usersection_id=" . (int)$data['dealingSection'];
                } else {
                    $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '$fromDate' AND '$toDate'";
                }
            }
            if ($data['dispatchMode'] != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id=" . (int)$data['dispatchMode'];
            }
        }
        $sql = "SELECT 
          epd.is_case, 
          epd.is_with_process_id, 
          epd.reference_number, 
          epd.id AS ec_postal_dispatch_id, 
          epd.process_id, 
          epd.process_id_year,
          CASE 
              WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') THEN 
                  CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) 
              ELSE 
                  m.reg_no_display 
          END AS case_no,
          epd.diary_no, 
          epd.send_to_name, 
          epd.send_to_address, 
          tn.name AS doc_type, 
          s.name AS state_name,
          d.name AS district_name, 
          epd.pincode, 
          epd.tal_state, 
          epd.tal_district,
          (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) AS sent_by,
          epdt.updated_on AS sent_on, 
          us.section_name, 
          epd.serial_number,
          epd.ref_postal_type_id, 
          epd.postal_charges, 
          epd.weight, 
          epd.waybill_number, 
          epd.usersection_id,
          (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
          (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
          (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type, 
          epd.serve_remarks
          FROM ec_postal_dispatch epd
          INNER JOIN ec_postal_dispatch_transactions epdt ON epd.id = epdt.ec_postal_dispatch_id AND epd.ref_letter_status_id = epdt.ref_letter_status_id
          LEFT JOIN main m ON epd.diary_no = m.diary_no
          LEFT JOIN master.tw_notice tn ON epd.tw_notice_id = tn.id
          LEFT JOIN master.usersection us ON epd.usersection_id = us.id
          LEFT JOIN master.state s ON s.id_no = epd.tal_state
          LEFT JOIN master.state d ON d.id_no = epd.tal_district
         WHERE $wherePostalDispatch $whereDateRange 
         ORDER BY epd.ref_postal_type_id, epd.serial_number";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if (!$query) {
            echo "Query Error: " . $this->db->error();
        } else {
            $result = $query->getResultArray();
            return $result;
            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';
        }
    }
    public function getReceiptDataById($ecReceiptId)
    {
        $builder = $this->db->table('ec_postal_received');
        $builder->select('ec_postal_transactions.*,ec_postal_received.*,ec_postal_transactions.id as ec_postal_transactions_id,ec_postal_transactions.is_deleted as ec_postal_transactions_is_deleted');
        $builder->where('ec_postal_received.id', $ecReceiptId);
        $builder->where('ec_postal_received.is_deleted', 'f');
        $builder->where('ec_postal_transactions.is_active', 't');
        $builder->join('ec_postal_transactions', 'ec_postal_received.id = ec_postal_transactions.ec_postal_received_id', 'left');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return 'false';
        }
    }

    function getLastDiaryNumber($diaryYear)
    {
        $builder = $this->db->table('ec_postal_received');
        $builder->selectMax('diary_no');
        $builder->where('diary_year', $diaryYear);
        $query = $builder->get();

        //        $query=$this->db->getLastQuery();
        //        echo (string) $query;exit();
        $result = $query->getResultArray();
        //echo $query->getNumRows();die;
        if ($result != null) {
            return $result[0];
        } else {
            return [];
        }
    }

    function getSearchDiary($searchType, $caseTypeId = null, $caseNo = null, $caseYear = null, $diaryNo = null, $diaryYear = null)
    {
        $builder = null;

        if ($searchType == 'c') {
            $builder = $this->db->table('main_casetype_history');

            $builder->distinct()
                ->select('diary_no')
                ->where('CAST(NULLIF(SPLIT_PART(new_registration_number, \'-\', 1), \'\') AS INTEGER) =', $caseTypeId)
                ->where("$caseNo BETWEEN 
                        CAST(NULLIF(SPLIT_PART(new_registration_number, '-', 2), '') AS INTEGER)
                        AND CAST(NULLIF(SPLIT_PART(new_registration_number, '-', 3), '') AS INTEGER)")
                ->where('new_registration_year', $caseYear)
                ->where('is_deleted', 'f'); // If 'is_deleted' is boolean, use false without quotes

            //pr($builder->getCompiledSelect());
        } elseif ($searchType == 'd') {
            // Initialize the query builder for the 'main' table
            $builder = $this->db->table('main');

            // Set distinct method and select columns
            $builder->distinct();
            $builder->select('diary_no');

            // Use LEFT() and RIGHT() instead of SUBSTR()
            $builder->where("LEFT(CAST(diary_no AS TEXT), LENGTH(CAST(diary_no AS TEXT)) - 4) =", $diaryNo);
            $builder->where("RIGHT(CAST(diary_no AS TEXT), 4) =", $diaryYear);
        }
        //pr($builder->getCompiledSelect());
        // Check if $builder is initialized correctly before executing the query

        if ($builder !== null) {
            // Execute the query and get the results
            $query = $builder->get();
            $result = $query->getRowArray();
          
            if (!empty($result)  && count($result) >= 1) {
                return $result['diary_no'];
            } else {
                return 'false';
            }
        } else {
            // Handle the case when $builder is not initialized (unexpected searchType)
            return 'Invalid search type or builder is not initialized properly';
        }
    }
    ##### menu 4insert 
    function saveLetterData($dataToInsert)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ec_postal_dispatch');
        if (empty($dataToInsert)) {
            throw new \Exception('No data to insert');
        }
        $builder->insert($dataToInsert);
        $ecPostalReceiptId = $db->insertID();
        $dataForDispatchTransactions = [
            'ec_postal_dispatch_id' => $ecPostalReceiptId,
            'ref_letter_status_id' => $dataToInsert['ref_letter_status_id'],
            'usercode' => $dataToInsert['usercode'],
            'updated_on' => date('Y-m-d H:i:s')
        ];

        $builder = $db->table('ec_postal_dispatch_transactions');
        $builder->insert($dataForDispatchTransactions);
        return $db->affectedRows();
    }
    ###menu 6 insert
    // function updateLetterStatus($id, $letterStatus, $usercode, $mode = 0, $amount = 0, $weight = 0, $barcode = "")
    // {
    //     pr($letterStatus); pr($usercode); pr($mode); pr($amount); pr($weight); pr($barcode); pr($isAcknowledgeable);die;
    //     $isAcknowledgeable = 0;
    //     if ($mode == 7 || $mode == 8 || $mode == 12) {
    //         $isAcknowledgeable = 1;
    //     }
    //     if ($letterStatus == 2) {
    //         $serialNumber = $this->getMaxSerialNumber($mode);
    //         $dataForDispatch = array(
    //             'ref_letter_status_id' => $letterStatus,
    //             'usercode' => $usercode,
    //             'updated_on' => date('Y-m-d H:i:s'),
    //             'ref_postal_type_id' => $mode,
    //             'postal_charges' => $amount,
    //             'weight' => $weight,
    //             'waybill_number' => $barcode,
    //             'is_acknowledgeable' => $isAcknowledgeable,
    //             'serial_number' => $serialNumber
    //         );
    //     } else {
    //         $dataForDispatch = array(
    //             'ref_letter_status_id' => $letterStatus,
    //             'usercode' => $usercode,
    //             'updated_on' => date('Y-m-d H:i:s'),
    //             'ref_postal_type_id' => $mode,
    //             'postal_charges' => $amount,
    //             'weight' => $weight,
    //             'waybill_number' => $barcode,
    //             'is_acknowledgeable' => $isAcknowledgeable
    //         );
    //     }
    //     $this->transferDispatchDataToLogtable($id);//For taking backup of existing data

    //     $this->db->where('id', $id)->update('ec_postal_dispatch', $dataForDispatch);
    //     //$this->db->affected_rows();

    //     if ($this->db->affected_rows() > 0) {
    //         $dataForDispatchTransactions = array(
    //             'ec_postal_dispatch_id' => $id,
    //             'ref_letter_status_id' => $letterStatus,
    //             'usercode' => $usercode,
    //             'updated_on' => date('Y-m-d H:i:s')
    //         );
    //         $this->db->insert('ec_postal_dispatch_transactions', $dataForDispatchTransactions);
    //     }
    //     //For updating connected letters as dispatched
    //     if ($letterStatus == 3 || $letterStatus == 6) {
    //         $this->updateConnectedLetterStatus($id, $letterStatus, $usercode, $mode, $amount, $weight, $barcode);
    //     }
    // }

    function updateLetterStatus($id, $letterStatus, $usercode, $mode = 0, $amount = 0, $weight = 0, $barcode = "")
    {
        $db = \Config\Database::connect();
        $isAcknowledgeable = ($mode == 7 || $mode == 8 || $mode == 12) ? 1 : 0;
        if ($letterStatus == 2) {
            $serialNumber = $this->getMaxSerialNumber($mode);
            $dataForDispatch = [
                'ref_letter_status_id' => $letterStatus,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'ref_postal_type_id' => $mode,
                'postal_charges' => $amount,
                'weight' => $weight,
                'waybill_number' => $barcode,
                'is_acknowledgeable' => $isAcknowledgeable,
                'serial_number' => $serialNumber
            ];
        } else {
            $dataForDispatch = [
                'ref_letter_status_id' => $letterStatus,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'ref_postal_type_id' => $mode,
                'postal_charges' => $amount,
                'weight' => $weight,
                'waybill_number' => $barcode,
                'is_acknowledgeable' => $isAcknowledgeable
            ];
        }
        // $this->transferDispatchDataToLogtable($id);
        // Update the ec_postal_dispatch table
        $builder = $db->table('ec_postal_dispatch');
        $builder->where('id', $id);
        //$builder->set($dataForDispatch)->getCompiledUpdate();
        $builder->update($dataForDispatch);
        die;
        if ($db->affectedRows() > 0) {
            $dataForDispatchTransactions = [
                'ec_postal_dispatch_id' => $id,
                'ref_letter_status_id' => $letterStatus,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s')
            ];

            // Insert into ec_postal_dispatch_transactions
            $builder = $db->table('ec_postal_dispatch_transactions'); // Reassign the table for insertion
            $builder->insert($dataForDispatchTransactions);
        }

        // For updating connected letters as dispatched
        if ($letterStatus == 3 || $letterStatus == 6) {
            $this->updateConnectedLetterStatus($id, $letterStatus, $usercode, $mode, $amount, $weight, $barcode);
        }
    }
    public function getMaxSerialNumber($dispatchMode)
    {
        $db = \Config\Database::connect(); // Connect to the database
        $builder = $db->table('ec_postal_dispatch'); // Use the main table

        // Join with the transactions table
        $builder->select('max(epd.serial_number) as last_serial_number')
            ->join('ec_postal_dispatch_transactions epdt', 'epd.id = epdt.ec_postal_dispatch_id')
            ->where('epdt.ref_letter_status_id', 2)
            ->where('date(epdt.updated_on)', date('Y-m-d'))
            ->where('epd.ref_postal_type_id', $dispatchMode);

        $query = $builder->get(); // Execute the query

        $row = $query->getRow(); // Get the first row of the result

        // Determine the final value
        if (is_null($row->last_serial_number) || $row->last_serial_number == 0) {
            return 1; // If no serial number exists, return 1
        } else {
            return $row->last_serial_number + 1; // Otherwise, return the incremented value
        }
    }
    function updateConnectedLetterStatus($id, $letterStatus, $usercode, $mode = 0, $amount = 0, $weight = 0, $barcode = "")
    {
        $isAcknowledgeable = 0;
        if ($mode == 7 || $mode == 8 || $mode == 12) {
            $isAcknowledgeable = 1;
        }
        $connectedCases = $this->getConnectedLetterDetails($id);
        foreach ($connectedCases as $case) {
            $dataForDispatch = array(
                'ref_letter_status_id' => $letterStatus,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'ref_postal_type_id' => $mode,
                'postal_charges' => $amount,
                'weight' => $weight,
                'waybill_number' => $barcode,
                'is_acknowledgeable' => $isAcknowledgeable
            );
            $this->transferDispatchDataToLogtable($case['id']); //For taking backup of existing data

            $this->db->where('id', $case['id'])->update('ec_postal_dispatch', $dataForDispatch);
            //$this->db->affected_rows();

            if ($this->db->affected_rows() > 0) {
                $dataForDispatchTransactions = array(
                    'ec_postal_dispatch_id' => $case['id'],
                    'ref_letter_status_id' => $letterStatus,
                    'usercode' => $usercode,
                    'updated_on' => date('Y-m-d H:i:s')
                );
                $this->db->insert('ec_postal_dispatch_transactions', $dataForDispatchTransactions);
            }
        }
    }

    function transferReceiptDataToLogtable($receiptid)
    {
        $db = \Config\Database::connect();

        // Step 1: Select data from ec_postal_received where id = $receiptid
        $builder = $db->table('ec_postal_received');
        $builder->select('*');
        $builder->where('id', $receiptid);
        $postalReceivedData = $builder->get()->getResultArray();
        // pr($postalReceivedData);`
        // Check if any data is returned
        if (empty($postalReceivedData)) {
            throw new \Exception('No data found for the given receipt ID');
        }

        // Step 2: Insert the fetched data into ec_postal_received_log
        $logBuilder = $db->table('ec_postal_received_log');
        $logBuilder->insertBatch($postalReceivedData);
        
        // Check if the insert was successful
        if ($db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function enteredDakToDispatchInRIWithProcessId($post)
    {
        extract($post);

        $wherePostalDispatch = $whereDateRange = "";

        if ($status == 0) {
            $wherePostalDispatch = " epd.id IS NULL";
        } elseif ($status == 8888) {
            $wherePostalDispatch = " epd.ref_letter_status_id IN (3,6)";
        } elseif ($status == 9999) {
            $wherePostalDispatch = " epd.ref_letter_status_id IN (4,5)";
        } else {
            $wherePostalDispatch = " epd.ref_letter_status_id = " . $status;
        }

        if (isset($searchBy)) {
            if ($searchBy == 's') {
                if (!empty($fromDate) && !empty($toDate)) {
                    $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '" . date('Y-m-d', strtotime($fromDate)) . "' 
                                  AND '" . date('Y-m-d', strtotime($toDate)) . "'";
                    if ($dealingSection != 0) {
                        $whereDateRange .= " AND epd.usersection_id = $dealingSection";
                    }
                }
            } elseif ($searchBy == 'c' || $searchBy == 'd') {
                $fetchedDiaryNo = $this->getSearchDiary($searchBy, $caseType ?? '', $caseNo ?? '', $caseYear ?? '', $diaryNumber ?? '', $diaryYear ?? '');

                $whereDateRange = " AND epd.diary_no = " . $fetchedDiaryNo;
            } elseif ($searchBy == 'p') {
                $whereDateRange = " AND epd.process_id = $processId AND epd.process_id_year = " . $processYear;
            }

            if (!empty($dispatchMode) && $dispatchMode != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id = $dispatchMode";
            }
        } else {
            if (!empty($fromDate) && !empty($toDate)) {
                $whereDateRange = " AND DATE(epdt.updated_on) BETWEEN '" . date('Y-m-d', strtotime($fromDate)) . "' 
                              AND '" . date('Y-m-d', strtotime($toDate)) . "'";
                if ($dealingSection != 0) {
                    $whereDateRange .= " AND epd.usersection_id = $dealingSection";
                }
            }
            if (!empty($dispatchMode) && $dispatchMode != 0) {
                $whereDateRange .= " AND epd.ref_postal_type_id = $dispatchMode";
            }
        }

        $sql = "SELECT 
                epd.is_case, epd.is_with_process_id, epd.reference_number, 
                epd.id AS ec_postal_dispatch_id, epd.process_id, epd.process_id_year,
                CASE 
                    WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') 
                    THEN 'Diary No. ' || 
                         SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) || '/' || 
                         SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4)
                    ELSE m.reg_no_display 
                END AS case_no,
                epd.diary_no, epd.send_to_name, epd.send_to_address, 
                tn.name AS doc_type, s.name AS state_name, d.name AS district_name, 
                epd.pincode, epd.tal_state, epd.tal_district,
                (SELECT name || '(' || empid || ')' FROM master.users WHERE usercode = epdt.usercode) AS sent_by,
                epdt.updated_on AS sent_on, us.section_name, epd.serial_number,
                epd.ref_postal_type_id, epd.postal_charges, epd.weight, epd.waybill_number, epd.usersection_id,
                (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
                (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
                (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type, epd.serve_remarks             
            FROM ec_postal_dispatch epd 
            INNER JOIN ec_postal_dispatch_transactions epdt 
                ON epd.id = epdt.ec_postal_dispatch_id 
                AND epd.ref_letter_status_id = epdt.ref_letter_status_id   
            LEFT JOIN main m ON epd.diary_no = m.diary_no
            LEFT JOIN master.tw_notice tn ON epd.tw_notice_id = tn.id
            LEFT JOIN master.usersection us ON epd.usersection_id = us.id
            LEFT JOIN master.state s ON s.id_no = epd.tal_state 
            LEFT JOIN master.state d ON d.id_no = epd.tal_district     
            WHERE $wherePostalDispatch $whereDateRange 
            ORDER BY epd.ref_postal_type_id, epd.serial_number";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }



    /* function enteredDakToDispatchInRIWithProcessId_old($post)
    {
        extract($post);

        $builder = $this->db->table('ec_postal_dispatch epd');

        // Select statement
        $builder->select("
         epd.is_case, 
         epd.is_with_process_id, 
         epd.reference_number, 
         epd.id as ec_postal_dispatch_id, 
         epd.process_id, 
         epd.process_id_year,
         
         CASE 
              WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') THEN 
                  CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) 
              ELSE 
                  m.reg_no_display      
         END as case_no,
         epd.diary_no, 
         epd.send_to_name, 
         epd.send_to_address, 
         tn.name as doc_type, 
         s.name as state_name, 
         d.name as district_name, 
         epd.pincode, 
         epd.tal_state, 
         epd.tal_district,
         (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) as sent_by,
         epdt.updated_on as sent_on, 
         us.section_name, 
         epd.serial_number,
         epd.ref_postal_type_id, 
         epd.postal_charges, 
         epd.weight, 
         epd.waybill_number, 
         epd.usersection_id,
         (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) as send_to_section,
         (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) as serve_stage,
         (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) as serve_type, 
         epd.serve_remarks
     ");

        // Join tables
        $builder->join('ec_postal_dispatch_transactions epdt', 'epd.id = epdt.ec_postal_dispatch_id AND epd.ref_letter_status_id = epdt.ref_letter_status_id', 'inner');
        $builder->join('main m', 'epd.diary_no = m.diary_no', 'left');
        $builder->join('master.tw_notice tn', 'epd.tw_notice = tn.id', 'left');
        $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
        $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
        $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');
        ###this need to uncomment
        // Apply where conditions for postal dispatch status
        // if ($status == 0) {
        //     $builder->where('epd.id IS NULL');
        // } elseif ($status == 8888) {
        //     $builder->whereIn('epd.ref_letter_status_id', [3, 6]);
        // } elseif ($status == 9999) {
        //     $builder->whereIn('epd.ref_letter_status_id', [4, 5]);
        // } else {
        //     $builder->where('epd.ref_letter_status_id', $status);
        // }

        // Apply search conditions
        // if (isset($searchBy)) {
        //     if ($searchBy == 's') {
        //         if ($fromDate != '' && $toDate != '') {
        //             $builder->where("DATE(epdt.updated_on) BETWEEN '" . date('Y-m-d', strtotime($fromDate)) . "' AND '" . date('Y-m-d', strtotime($toDate)) . "'");
        //             if ($dealingSection != 0) {
        //                 $builder->where('epd.usersection_id', $dealingSection);
        //             }
        //         }
        //     } elseif ($searchBy == 'c' || $searchBy == 'd') {
        //         $fetchedDiaryNo = $this->getSearchDiary($caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear, $searchBy);
        //         $builder->where('epd.diary_no', $fetchedDiaryNo);
        //     } elseif ($searchBy == 'p') {
        //         $builder->where('epd.process_id', $processId)
        //                 ->where('process_id_year', $processYear);
        //     }
        // } else {
        //     if ($fromDate != '' && $toDate != '') {
        //         $builder->where("DATE(epdt.updated_on) BETWEEN '" . date('Y-m-d', strtotime($fromDate)) . "' AND '" . date('Y-m-d', strtotime($toDate)) . "'");
        //         if ($dealingSection != 0) {
        //             $builder->where('epd.usersection_id', $dealingSection);
        //         }
        //     }
        // }

        // Apply dispatch mode condition
        //  if ($dispatchMode != 0) {
        //      $builder->where('epd.ref_postal_type_id', $dispatchMode);
        //  }

        ###this need to uncomment

        // Order by statement
        $builder->orderBy('epd.ref_postal_type_id', 'ASC')
            ->orderBy('epd.serial_number', 'ASC');
        // Print the compiled SQL query
        //echo $builder->getCompiledSelect();die();

        // Execute the query
        $query = $builder->get();

        return $query->getResultArray();
    } */

    public function date_formatter($date, $format)
    {
        if ($date != null) {
            return date($format, strtotime($date));
        } else
            return null;
    }

    ##menu 8

    function get_dispatch_data($data)
    {
        $refNo     = $data['refNo'] ?? '';
        $prYear    = $data['prYear'] ?? '';
        $processId = $data['processId'] ?? '';
        $diaryYear = $data['diaryYear'] ?? '';
        $diaryNo   =  $data['diaryNo'] ?? '';
        $caseYear  = $data['caseYear'] ?? '';
        $caseNo    = $data['caseNo'] ?? '';
        $caseType  = $data['caseType'] ?? '';

        $fromStoRI = !empty($data['fromStoRI']) ? $this->date_formatter($data['fromStoRI'], 'Y-m-d') : '';
        $toStoRI = !empty($data['toStoRI']) ? $this->date_formatter($data['toStoRI'], 'Y-m-d') : '';
        $fromRItoS = !empty($data['fromRItoS']) ? $this->date_formatter($data['fromRItoS'], 'Y-m-d') : '';
        $toRItoS = !empty($data['toRItoS']) ? $this->date_formatter($data['toRItoS'], 'Y-m-d') : '';
        $fromRItoR = !empty($data['fromRItoR']) ? $this->date_formatter($data['fromRItoR'], 'Y-m-d') : '';
        $toRItoR = !empty($data['toRItoR']) ? $this->date_formatter($data['toRItoR'], 'Y-m-d') : '';
        // $fromStoRI = $this->date_formatter($fromStoRI ?? '', 'Y-m-d');
        // $toStoRI = $this->date_formatter($toStoRI ?? '', 'Y-m-d');
        // $fromRItoS = $this->date_formatter($fromRItoS ?? '', 'Y-m-d');
        // $toRItoS = $this->date_formatter($toRItoS ?? '', 'Y-m-d');
        // $fromRItoR = $this->date_formatter($fromRItoR ?? '', 'Y-m-d');
        // $toRItoR = $this->date_formatter($toRItoR ?? '', 'Y-m-d');

        $usercondition = '';
        $dateq = '';
        $searchBy = 'c';

        // Build query conditions
        if ($data['diaryNo'] != '') {
            $diaryNo = $data['diaryNo'] . $data['diaryYear'];
            $usercondition = "epd.diary_no='" . $diaryNo . "'";
        }

        if ($caseNo != '') {
            $diaryNo = $this->getSearchDiary($searchBy, $caseType, $caseNo, $caseYear, $diaryNo, $diaryYear);
            if ($diaryNo != '')
                $usercondition = "epd.diary_no='" . $diaryNo . "'";
        }

        if ($processId != '') {
            $subq = "epd.process_id='" . $processId . "' and epd.process_id_year='" . $prYear . "'";
            if ($usercondition == '') {
                $usercondition = $subq;
            } else {
                $usercondition = $usercondition . " and " . $subq;
            }
        }
        if ($refNo != '') {
            $subq = "epd.reference_number='" . $refNo . "'";
            if ($usercondition == '') {
                $usercondition = $subq;
            } else {
                $usercondition = $usercondition . " and " . $subq;
            }
        }

        $usercode = "epdt.usercode";
        $updated_on = "epdt.updated_on";

        if ($fromRItoS == '' && $fromStoRI == '' && $fromRItoR == '') {
            $ref_let_status = "ec_postal_dispatch_transactions epdt on
             epd.id=epdt.ec_postal_dispatch_id ";
        }

        if ($fromStoRI != '') {
            $subq = "(epdt.ref_letter_status_id = 1 and date(epdt.updated_on) between '" . $fromStoRI . "' and '" . $toStoRI . "')";

            if ($dateq == '') {
                $dateq = $subq;
                $ref_let_status = "ec_postal_dispatch_transactions epdt on epd.id=epdt.ec_postal_dispatch_id ";
            }
        }
        if ($fromRItoS != '') {
            $subq = "(epdt1.ref_letter_status_id = 2 and date(epdt1.updated_on) between '" . $fromRItoS . "' and '" . $toRItoS . "')";

            if ($dateq == '') {
                $dateq = $subq;
                $ref_let_status = "ec_postal_dispatch_transactions epdt1 on epd.id=epdt1.ec_postal_dispatch_id ";
                $usercode = "epdt1.usercode";
                $updated_on = "epdt1.updated_on";
            } else {
                $dateq = $dateq . " and " . $subq;
                $ref_let_status = $ref_let_status . " left join ec_postal_dispatch_transactions epdt1 on epd.id=epdt1.ec_postal_dispatch_id ";
            }
        }

        if ($fromRItoR != '') {
            $subq = "(epdt2.ref_letter_status_id = 3 and date(epdt2.updated_on) between '" . $fromRItoR . "' and '" . $toRItoR . "')";

            if ($dateq == '') {
                $dateq = $subq;
                $ref_let_status = "ec_postal_dispatch_transactions epdt2 on epd.id=epdt2.ec_postal_dispatch_id ";
                $usercode = "epdt2.usercode";
                $updated_on = "epdt2.updated_on";
            } else {
                $dateq = $dateq . " and " . $subq;
                $ref_let_status = $ref_let_status . " left join ec_postal_dispatch_transactions epdt2 on epd.id=epdt2.ec_postal_dispatch_id ";
            }
        }

        if (($fromStoRI != '' and $fromRItoS != '') or ($fromRItoS != '' and $fromRItoR != '') or ($fromRItoR != '' and $fromStoRI != '')) {
            $dateq = "(" . $dateq . ")";
            if ($usercondition == '') {
                $usercondition = $dateq;
            } else {
                $usercondition = $usercondition . ' and ' . $dateq;
            }
        } else if ($fromStoRI != '' or $fromRItoS != '' or $fromRItoR != '') {
            if ($usercondition == '') {
                $usercondition = $dateq;
            } else {
                $usercondition = $usercondition . ' and ' . $dateq;
            }
        }
        // Final SQL query
        $sql = "SELECT rls.description AS current_status,
                epd.is_case,
                epd.is_with_process_id,
                epd.reference_number,
                epd.id AS ec_postal_dispatch_id,
                epd.process_id,
                epd.process_id_year,
                COALESCE(m.reg_no_display,
                         CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/',
                                SUBSTRING(CAST(m.diary_no AS TEXT), -4))) AS case_no,
                epd.diary_no,
                epd.send_to_name,
                epd.send_to_address,
                tn.name AS doc_type,
                s.name AS state_name,
                d.name AS district_name,
                epd.pincode,
                epd.tal_state,
                epd.tal_district,
                (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = $usercode) AS sent_by,
                $updated_on as sent_on,
                us.section_name,
                epd.serial_number,
                epd.ref_postal_type_id,
                epd.postal_charges,
                epd.weight,
                epd.waybill_number,
                epd.usersection_id,
                (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
                (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
                (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type,
                epd.serve_remarks,
                rpt.postal_type_description
                FROM ec_postal_dispatch epd INNER join $ref_let_status
                LEFT JOIN main m ON epd.diary_no = m.diary_no
                LEFT JOIN master.tw_notice tn ON epd.tw_notice_id = tn.id
                LEFT JOIN master.usersection us ON epd.usersection_id = us.id
                LEFT JOIN master.ref_letter_status rls ON epd.ref_letter_status_id = rls.id
                LEFT JOIN master.ref_postal_type rpt ON epd.ref_postal_type_id = rpt.id
                LEFT JOIN master.state s ON s.id_no = epd.tal_state
                LEFT JOIN master.state d ON d.id_no = epd.tal_district
                where $usercondition
                ORDER BY epd.ref_postal_type_id, epd.serial_number";

        ###use this line above order by  WHERE epd.diary_no = '432024'

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if (!$query) {
            echo "Query Error: " . $this->db->error();
        } else {
            $result = $query->getResultArray();
            return $result;
            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';
        }
    }

    #menu 999  and 10
    function getDateWiseActionFromSection($fromDate, $toDate, $status, $usercode)
    {
        //14:BRANCH OFFICER,9:ASSISTANT  REGISTRAR,6:DY. REGISTRAR,4:ADDL. REGISTRAR,3:REGISTRAR,2:SECRETARY GENERAL
        //echo $fromDate.'dasdas'; exit;
        $senior_designation = array(2, 3, 4, 6, 9, 14);
        $userDetails = $this->getUserDetails($usercode);
        //echo $usersection=$userDetails->section;
        $userCondition = "";
        $stausCondition = "";
        if ($userDetails->section == 68 || $userDetails->section == 71) {
            $userCondition = "date(epdt.updated_on) between '$fromDate' and '$toDate' and epdt.ref_letter_status_id=$status";
        } else {
            if (in_array($userDetails->usertype, $senior_designation)) {
                $userCondition = "epd.usersection_id=$userDetails->section and date(epdt.updated_on) between '$fromDate' and '$toDate' and epdt.ref_letter_status_id=$status";
            } else {
                $userCondition = "epdt.usercode=$usercode and date(epdt.updated_on) between '$fromDate' and '$toDate' and epdt.ref_letter_status_id=$status";
            }
        }

        $sql = "SELECT 
                    rls.description AS current_status,
                    epd.is_case,
                    epd.is_with_process_id,
                    epd.reference_number,
                    epd.id AS ec_postal_dispatch_id,
                    epd.process_id,
                    epd.process_id_year,
                    COALESCE(m.reg_no_display, CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4))) AS case_no,
                    epd.diary_no,
                    epd.send_to_name,
                    epd.send_to_address,
                    tn.name AS doc_type,
                    s.name AS state_name,
                    d.name AS district_name,
                    epd.pincode,
                    epd.tal_state,
                    epd.tal_district,
                    (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) AS sent_by,
                    epdt.updated_on AS sent_on,
                    us.section_name,
                    epd.serial_number,
                    epd.ref_postal_type_id,
                    epd.postal_charges,
                    epd.weight,
                    epd.waybill_number,
                    epd.usersection_id,
                    (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
                    (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
                    (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type,
                    epd.serve_remarks,
                    rpt.postal_type_description
                FROM 
                    ec_postal_dispatch epd
                INNER JOIN 
                    ec_postal_dispatch_transactions epdt ON epd.id = epdt.ec_postal_dispatch_id
                LEFT JOIN 
                    main m ON epd.diary_no = m.diary_no
                LEFT JOIN 
                    master.tw_notice tn ON epd.tw_notice_id = tn.id
                LEFT JOIN 
                    master.usersection us ON epd.usersection_id = us.id
                LEFT JOIN 
                    master.ref_letter_status rls ON epd.ref_letter_status_id = rls.id
                LEFT JOIN 
                    master.ref_postal_type rpt ON epd.ref_postal_type_id = rpt.id
                LEFT JOIN 
                    master.state s ON s.id_no = epd.tal_state
                LEFT JOIN 
                    master.state d ON d.id_no = epd.tal_district
               WHERE $userCondition
                ORDER BY 
                    epd.ref_postal_type_id, epd.serial_number";


        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        if (!$query) {
            echo "Query Error: " . $this->db->error();
        } else {
            $result = $query->getResultArray();
            return $result;
        }
    }
    ##### menu 11 

    function getDispatchQueryData($post)
    {
        //pr($post);exit;

        $searchBy =  $post['searchBy'];
        $processId = $post['processId'];
        $processYear = $post['processYear'];
        // extract($post);
        $condition = "";
        $conditionArray = [];

        switch ($searchBy) {
            case 1: //For Process Id
                $condition = " where epd.process_id=? and epd.process_id_year=?";
                $conditionArray = array($processId, $processYear);
                break;
            case 2: //For Diary Number
                $condition = " where epd.diary_no=?";
                $conditionArray = array($diaryNumber . '' . $diaryYear);
                break;
            case 3: //For Case Number
                $fetchedDiaryNo = $this->getSearchDiary('c', $caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear);
                $condition = " where epd.diary_no=?";
                $conditionArray = array($fetchedDiaryNo);
                break;
            case 4: //For Receipient Name
                $condition = " where epd.send_to_name like ?";
                $conditionArray = array('%' . $freeText . '%');
                break;
            case 5: //For Receipient Address
                $condition = " where epd.send_to_address like ?";
                $conditionArray = array('%' . $freeText . '%');
                break;
            case 6: //For Reference Number
                $condition = " where epd.reference_number like ?";
                $conditionArray = array('%' . $freeText . '%');
                break;
            case 7: //For Waybill Number
                $condition = " where epd.waybill_number=?";
                $conditionArray = array($wayBillNumber);
                break;
        }

        //$condition = ""; // Define your condition here, e.g., "WHERE epd.id = 12345"

        $sql = "SELECT 
                    rls.description AS current_status,
                    epd.is_case,
                    epd.is_with_process_id,
                    epd.reference_number,
                    epd.id AS ec_postal_dispatch_id,
                    epd.process_id,
                    epd.process_id_year,
                    COALESCE(m.reg_no_display, CONCAT(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT) FROM -4))) AS case_no,
                    epd.diary_no,
                    epd.send_to_name,
                    epd.send_to_address,
                    tn.name AS doc_type,
                    s.name AS state_name,
                    d.name AS district_name,
                    epd.pincode,
                    epd.tal_state,
                    epd.tal_district,
                    (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) AS sent_by,
                    epdt.updated_on AS sent_on,
                    us.section_name,
                    epd.serial_number,
                    epd.ref_postal_type_id,
                    epd.postal_charges,
                    epd.weight,
                    epd.waybill_number,
                    epd.usersection_id,
                    (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section,
                    (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage,
                    (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type,
                    epd.serve_remarks,
                    rpt.postal_type_description
                FROM 
                    ec_postal_dispatch epd
                LEFT JOIN 
                    ec_postal_dispatch_transactions epdt ON epd.id = epdt.ec_postal_dispatch_id
                LEFT JOIN 
                    main m ON epd.diary_no = m.diary_no
                LEFT JOIN 
                    master.tw_notice tn ON epd.tw_notice_id = tn.id
                LEFT JOIN 
                    master.usersection us ON epd.usersection_id = us.id
                LEFT JOIN 
                    master.ref_letter_status rls ON epd.ref_letter_status_id = rls.id
                LEFT JOIN 
                    master.ref_postal_type rpt ON epd.ref_postal_type_id = rpt.id
                LEFT JOIN 
                    master.state s ON s.id_no = epd.tal_state
                LEFT JOIN 
                    master.state d ON d.id_no = epd.tal_district
                $condition
                ORDER BY 
                    epd.updated_on DESC";

        // Execute the query
        $query = $this->db->query($sql, $conditionArray);

        // Check for errors
        if (!$query) {
            echo "Query Error: " . $this->db->error();
        } else {
            $result = $query->getResultArray();
            return $result;
        }
    }

    ####menu 21
    function enteredDakToDispatchInRIWithProcessId21($data)
    {

        //  pr($data['fromDate']);die;
        //extract($post);

        $builder = $this->db->table('ec_postal_dispatch epd');

        // Select statement
        $builder->select("
            epd.is_case, 
            epd.is_with_process_id, 
            epd.reference_number, 
            epd.id as ec_postal_dispatch_id, 
            epd.process_id, 
            epd.process_id_year,
            
            CASE 
                WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') THEN 
                    CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) 
                ELSE 
                    m.reg_no_display      
            END as case_no,
            epd.diary_no, 
            epd.send_to_name, 
            epd.send_to_address, 
            tn.name as doc_type, 
            s.name as state_name, 
            d.name as district_name, 
            epd.pincode, 
            epd.tal_state, 
            epd.tal_district,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) as sent_by,
            epdt.updated_on as sent_on, 
            us.section_name, 
            epd.serial_number,
            epd.ref_postal_type_id, 
            epd.postal_charges, 
            epd.weight, 
            epd.waybill_number, 
            epd.usersection_id,
            (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) as send_to_section,
            (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) as serve_stage,
            (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) as serve_type, 
            epd.serve_remarks
        ");

        // Join tables
        $builder->join('ec_postal_dispatch_transactions epdt', 'epd.id = epdt.ec_postal_dispatch_id AND epd.ref_letter_status_id = epdt.ref_letter_status_id', 'inner');
        $builder->join('main m', 'epd.diary_no = m.diary_no', 'left');
        $builder->join('master.tw_notice tn', 'epd.tw_notice_id  = tn.id', 'left');
        $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
        $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
        $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');
        ###this need to uncomment

        if (!empty($data['dispatchMode']))
            $dispatchMode = $data['dispatchMode'];
        else $dispatchMode = "";
        $wherePostalDispatch = "";
        $whereDateRange = "";
        if ($data['status'] == 0) {
            $wherePostalDispatch = " epd.id is null";
        } elseif ($data['status'] == 4) { //For Dispatched or Re-dispatched
            $wherePostalDispatch = " epd.ref_letter_status_id in (3,6)";
        } elseif ($data['status'] == 9999) {
            $wherePostalDispatch = " epd.ref_letter_status_id in (4,5)";
        } else {
            $wherePostalDispatch = " epd.ref_letter_status_id=" . $status;
        }
        if (isset($searchBy)) {
            if ($searchBy == 's') {
                if ($data['fromDate'] != '' && $data['toDate'] != '') {
                    if ($dealingSection != 0) {
                        $whereDateRange = " and date(epdt.updated_on) between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "' and epd.usersection_id=$dealingSection";
                    } else {
                        $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "'";
                    }
                }
            } else if ($searchBy == 'c' || $searchBy == 'd') {
                $fetchedDiaryNo = $this->RIModel->getSearchDiary($searchBy, $caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear);
                $whereDateRange = " and epd.diary_no=" . $fetchedDiaryNo;
            } else if ($searchBy == 'p') {
                $whereDateRange = " and epd.process_id=$processId and process_id_year=" . $processYear;
            }

            if ($dispatchMode != 0 && $dispatchMode != '') {

                $whereDateRange .= " and epd.ref_postal_type_id=$dispatchMode";
            }
        } else {
            if ($data['fromDate'] != '' && $data['toDate'] != '') {

                $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "'";
            }
        }

        //        epd.ref_letter_status_id in (4,5)>> and epd.process_id=12345 and process_id_year=2024 and epd.ref_postal_type_id=

        ###this need to uncomment

        // Order by statement
        $builder->orderBy('epd.ref_postal_type_id', 'ASC')
            ->orderBy('epd.serial_number', 'ASC');
        // Print the compiled SQL query
        // echo $builder->getCompiledSelect();die();

        // Execute the query
        $query = $builder->get();

        return $query->getResultArray();
    }
    function enteredDakToDispatchInRIWithProcessId22($data)
    {

        //  pr($data['fromDate']);die;
        //extract($post);

        $builder = $this->db->table('ec_postal_dispatch epd');

        // Select statement
        $builder->select("
            epd.is_case, 
            epd.is_with_process_id, 
            epd.reference_number, 
            epd.id as ec_postal_dispatch_id, 
            epd.process_id, 
            epd.process_id_year,
            
            CASE 
                WHEN (m.reg_no_display IS NULL OR m.reg_no_display = '') THEN 
                    CONCAT('Diary No. ', LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', SUBSTRING(CAST(m.diary_no AS TEXT), -4)) 
                ELSE 
                    m.reg_no_display      
            END as case_no,
            epd.diary_no, 
            epd.send_to_name, 
            epd.send_to_address, 
            tn.name as doc_type, 
            s.name as state_name, 
            d.name as district_name, 
            epd.pincode, 
            epd.tal_state, 
            epd.tal_district,
            (SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epdt.usercode) as sent_by,
            epdt.updated_on as sent_on, 
            us.section_name, 
            epd.serial_number,
            epd.ref_postal_type_id, 
            epd.postal_charges, 
            epd.weight, 
            epd.waybill_number, 
            epd.usersection_id,
            (SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) as send_to_section,
            (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) as serve_stage,
            (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) as serve_type, 
            epd.serve_remarks
        ");

        // Join tables
        $builder->join('ec_postal_dispatch_transactions epdt', 'epd.id = epdt.ec_postal_dispatch_id AND epd.ref_letter_status_id = epdt.ref_letter_status_id', 'inner');
        $builder->join('main m', 'epd.diary_no = m.diary_no', 'left');
        $builder->join('master.tw_notice tn', 'epd.tw_notice_id  = tn.id', 'left');
        $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
        $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
        $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');
        ###this need to uncomment

        if (!empty($data['dispatchMode']))
            $dispatchMode = $data['dispatchMode'];
        else $dispatchMode = "";
        $wherePostalDispatch = "";
        $whereDateRange = "";
        if ($data['status'] == 0) {
            $wherePostalDispatch = " epd.id is null";
        } elseif ($data['status'] == 8888) { //For Dispatched or Re-dispatched
            $wherePostalDispatch = " epd.ref_letter_status_id in (3,6)";
        } elseif ($data['status'] == 9999) {
            $wherePostalDispatch = " epd.ref_letter_status_id in (4,5)";
        } else {
            $wherePostalDispatch = " epd.ref_letter_status_id=" . $data['status'];
        }
        if (isset($searchBy)) {
            if ($searchBy == 's') {
                if ($data['fromDate'] != '' && $data['toDate'] != '') {
                    if ($dealingSection != 0) {
                        $whereDateRange = " and date(epdt.updated_on) between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "' and epd.usersection_id=$dealingSection";
                    } else {
                        $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "'";
                    }
                }
            } else if ($searchBy == 'c' || $searchBy == 'd') {
                $fetchedDiaryNo = $this->RIModel->getSearchDiary($searchBy, $caseType, $caseNo, $caseYear, $diaryNumber, $diaryYear);
                $whereDateRange = " and epd.diary_no=" . $fetchedDiaryNo;
            } else if ($searchBy == 'p') {
                $whereDateRange = " and epd.process_id=$processId and process_id_year=" . $processYear;
            }

            if ($dispatchMode != 0 && $dispatchMode != '') {

                $whereDateRange .= " and epd.ref_postal_type_id=$dispatchMode";
            }
        } else {
            if ($data['fromDate'] != '' && $data['toDate'] != '') {

                $whereDateRange = " and date(epdt.updated_on)  between '" . date('Y-m-d', strtotime($data['fromDate'])) . "' and '" . date('Y-m-d', strtotime($data['toDate'])) . "'";
            }
        }

        //        epd.ref_letter_status_id in (4,5)>> and epd.process_id=12345 and process_id_year=2024 and epd.ref_postal_type_id=

        ###this need to uncomment

        // Order by statement
        $builder->orderBy('epd.ref_postal_type_id', 'ASC')
            ->orderBy('epd.serial_number', 'ASC');
        // Print the compiled SQL query
        // echo $builder->getCompiledSelect();die();

        // Execute the query
        $query = $builder->get();

        return $query->getResultArray();
    }


    function getLetterStatus()
    {
        // $this->db->order_by('id', 'ASC');
        // $this->db->where('display', 'Y');
        // $query = $this->db->get('ref_letter_status');
        // return $query->result_array();
        $query = $this->db->table('master.ref_letter_status')
            ->where('display', 'Y')
            ->orderBy('id', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    // function getUserDetails($usercode)
    // {
    //     $queryString = "select ut.type_name,u.* from master.users u inner join master.usertype ut on u.usertype=ut.id where u.usercode=? and u.display=?";
    //     $query = $this->db->query($queryString, array($usercode, 'Y'));
    //     $res = $query->result();  // this returns an object of all results
    //    echo $row = $res[0]; exit;
    //    return $row;
    // }

    public function getUserDetails($usercode)
    {
        // CI 4 query with the query builder
        $queryString = "SELECT ut.type_name, u.* 
                        FROM master.users u 
                        INNER JOIN master.usertype ut ON u.usertype = ut.id 
                        WHERE u.usercode = :usercode: 
                        AND u.display = :display:";

        // Running the query with query bindings
        $query = $this->db->query($queryString, [
            'usercode' => $usercode,
            'display'  => 'Y'
        ]);
        $res = $query->getResult();
        if (!empty($res)) {
            $row = $res[0];
            return $row;
        }

        return null; // Return null if no result found
    }




    function saveReceiptData($query, $actionType = "")
    {
        
        if ($actionType == "i") {
            $this->db->query($query);
            return $this->db->insert_id();
        } else {
            $result = $this->db->query($query);
            return $result;
        }
    }

    public function getNoticeAdLtrDetails($txt_frmdate, $txt_todate, $ddlOR, $ucode, $u_cond)
    {
        $sql = "SELECT d.id,a.diary_no, process_id, a.name, 
        CASE 
        WHEN (send_to_type ~ '^[0-9]+$' AND send_to_type::INTEGER IN (2, 3)) 
        THEN address 
        WHEN (send_to_type ~ '^[0-9]+$' AND send_to_type::INTEGER = 1) 
        THEN bb.caddress 
        ELSE '' 
    END AS address,  b.name nt_typ, del_type, 
      tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
      sendto_district,sendto_state,nt_type,tal_state,tal_district,dispatch_id,dispatch_dt,station,weight,stamp,
      barcode,dis_remark,dispatch_user_id
        FROM tw_tal_del a
        JOIN master.tw_notice b ON a.nt_type = b.id::text
        JOIN tw_o_r c ON c.tw_org_id = a.id
        JOIN tw_comp_not d ON d.tw_o_r_id = c.id
        join main m on a.diary_no=m.diary_no 
        left join master.bar bb on bb.bar_id=d.tw_sn_to
        left join master.state s on s.id_no=tal_state 
        WHERE  a.display = 'Y'
        AND print =1
        AND b.display = 'Y'
        AND c.display = 'Y'
        AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt is not null  
        and date(dispatch_dt)  between '$txt_frmdate' and '$txt_todate'  $ddlOR and copy_type='0' $u_cond order by dispatch_id";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    #menu update dispatch

    function getNoticeDispatchUpdate($process_id, $pid_year)
    {
        $sql = "SELECT d.id,a.diary_no, process_id, EXTRACT(YEAR FROM rec_dt) AS pid_year , a.name, case when (send_to_type='' or send_to_type::integer = 3 or send_to_type::integer = 2 ) then address when (send_to_type::integer = 1) then bb.caddress else '' END AS address, b.name nt_typ, del_type, 
          tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
          sendto_district,sendto_state,nt_type,tal_state,st.Name as st_name,tal_district,ds.Name as ds_name,s.State_code,d.barcode,
          concat(m.reg_no_display, '@ D.No.', SUBSTR(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4) ,'/', SUBSTR(m.diary_no::text, - 4)) As CaseNo,
          d.dispatch_user_id,d.dispatch_dt,u.name as emp_name,u.empid                    
        FROM tw_tal_del a
        left join master.state st on st.id_no=a.tal_state and st.display='Y'
        left join master.state ds on ds.id_no=a.tal_district and ds.display='Y'
        JOIN master.tw_notice b ON a.nt_type = b.id::text
        JOIN tw_o_r c ON c.tw_org_id = a.id
        JOIN tw_comp_not d ON d.tw_o_r_id = c.id
        left join master.bar bb on bb.bar_id=d.tw_sn_to
        join main m on a.diary_no=m.diary_no
        inner join master.users u on u.usercode=d.dispatch_user_id AND (u.display = 'Y' or u.display is null)
        left join master.state s on s.id_no=tal_state 
        WHERE  a.display = 'Y'
        AND print =1
        AND b.display = 'Y'
        AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt is not null
        AND c.display = 'Y' and a.process_id=$process_id and EXTRACT(YEAR FROM rec_dt)='$pid_year'";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    function update_barcode($id, $process_id, $pid_year, $barcode)
    {
        $ent_by = session()->get('login')['usercode'];
        $sql = "UPDATE tw_comp_not SET barcode= '$barcode', bc_update_on=now(), bc_update_by='$ent_by' WHERE id='$id'";
        $this->db->query($sql);
        if ($this->db->affectedRows() > 0)
            return true;
        else
            return false;
    }

    public function delete_Record($id, $process_id, $pid_year, $barcode)
    {
        $ent_by = session()->get('login')['usercode'];
        //$ent_by=1;
        $sql_insert = "INSERT INTO tw_comp_not_history (tw_comp_not_id, tw_o_r_id, tw_sn_to, 
        sendto_state, sendto_district, copy_type, send_to_type, serve, ser_type, ser_date, 
        ser_dt_ent_dt,  ack_user_id, dis_da_dt,da_rec_dt,ack_id, remark, l_ljs_rem, l_hjs_rem, 
        l_ljs_p_d,  l_hjs_p_d, l_ljs_pt, l_hjs_pt, t_ljs_p_d, t_hjs_p_d, t_ljs_rem, t_hjs_rem, 
        station, weight,stamp, dis_remark, dispatch_user_id, dispatch_dt, dispatch_id, barcode, 
        m_d, send_mail_dt, display,update_by,p_id,pid_year) 
        (SELECT id, tw_o_r_id, tw_sn_to, sendto_state, sendto_district, copy_type,send_to_type, serve, 
        ser_type, ser_date, ser_dt_ent_dt, ack_user_id, dis_da_dt, da_rec_dt, ack_id, remark, 
        l_ljs_rem, l_hjs_rem, l_ljs_p_d, l_hjs_p_d, l_ljs_pt, l_hjs_pt, t_ljs_p_d, t_hjs_p_d, 
        t_ljs_rem, t_hjs_rem, station, weight, stamp, dis_remark, dispatch_user_id, dispatch_dt, 
        dispatch_id, barcode, m_d, send_mail_dt, display,$ent_by,$process_id,$pid_year FROM tw_comp_not WHERE id='$id');";
        $this->db->query($sql_insert);
        if ($this->db->affectedRows() >= 1) {
            $sql = "UPDATE tw_comp_not SET station='0', weight='0', stamp='0', dispatch_user_id='0', dispatch_dt = NULL, dispatch_id='0', barcode = NULL WHERE id='$id'";
            $this->db->query($sql);
            if ($this->db->affectedRows() >= 1)
                return true;
        } else
            return false;
    }

    function update_notice_dispatch($pro_yr, $ddlOR)
    {
        $sql = "SELECT d.id,a.diary_no, process_id, a.name, case when (send_to_type='' or send_to_type::integer=3 or send_to_type::integer=2 ) then address when (send_to_type::integer=1) then bb.      caddress else '' END AS address, b.name nt_typ, del_type, 
            tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
            sendto_district,sendto_state,nt_type,tal_state,tal_district,s.State_code,fil_no
        FROM tw_tal_del a
        JOIN master.tw_notice b ON a.nt_type = b.id::text
        JOIN tw_o_r c ON c.tw_org_id = a.id
        JOIN tw_comp_not d ON d.tw_o_r_id = c.id
        left join master.bar bb on bb.bar_id=d.tw_sn_to
        join main m on a.diary_no=m.diary_no
        left join master.state s on s.id_no=tal_state 
        WHERE  a.display = 'Y'
        AND print =1
        AND b.display = 'Y'
        AND c.display = 'Y'
        AND d.display = 'Y' and dispatch_id =0 and dispatch_dt is null 
        and $pro_yr and del_type='$ddlOR'";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    function getDataToack($pro_yr)
    {        
        

        $sql = "SELECT 
        d.id, 
        a.diary_no, 
        process_id, 
        a.name, 
        address, 
        b.name AS nt_typ, 
        del_type, 
        tw_sn_to, 
        copy_type, 
        send_to_type, 
        fixed_for, 
        rec_dt, 
        office_notice_rpt, 
        reg_no_display, 
        sendto_district, 
        sendto_state, 
        nt_type, 
        tal_state, 
        tal_district, 
        dispatch_id, 
        dispatch_dt, 
        station, 
        weight, 
        stamp, 
        barcode, 
        dis_remark, 
        dispatch_user_id 
        FROM 
        tw_tal_del a 
        LEFT JOIN 
        master.tw_notice b ON a.nt_type::int = b.id 
        LEFT JOIN 
        tw_o_r c ON c.tw_org_id = a.id 
        LEFT JOIN 
        tw_comp_not d ON d.tw_o_r_id = c.id 
        LEFT JOIN 
        main m ON a.diary_no = m.diary_no
        WHERE  a.display = 'Y'
        AND print =1
        AND b.display = 'Y'
        AND c.display = 'Y'
        AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt IS NOT NULL and ser_dt_ent_dt IS NULL
        $pro_yr order by dispatch_id
        ";

        $query = $this->db->query($sql);

        // Get and return the result as an array
        $result =   $query->getResultArray();
        return  $result;

        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';
    }

    public function lowerCourtOr($diary_no)
{
    // Get active_casetype_id
    $builder = $this->db->table('main')
                        ->select('active_casetype_id')
                        ->where('diary_no', $diary_no)
                        ->get();

    $res_chk_casetype = $builder->getRowArray()['active_casetype_id'] ?? null;

    $is_order_challenged = '';
    if (!in_array($res_chk_casetype, [25, 26, 7, 8])) {
        $is_order_challenged = " AND is_order_challenged = 'Y' ";
    }

    $sql = "
        SELECT 
            a.lct_dec_dt, 
            a.l_dist, 
            a.ct_code, 
            a.l_state, 
            b.name, 
            CASE 
                WHEN a.ct_code = 3 THEN 
                    (SELECT name FROM state s WHERE s.id_no = a.l_dist AND display = 'Y')
                ELSE 
                    (SELECT CONCAT(agency_name, ', ', address) 
                     FROM ref_agency_code c 
                     WHERE c.cmis_state_id = a.l_state 
                     AND c.id = a.l_dist 
                     AND is_deleted = FALSE)
            END AS agency_name, 
            a.crimeno, 
            a.crimeyear, 
            a.polstncode, 
            (SELECT policestndesc FROM police p 
             WHERE p.policestncd = a.polstncode 
             AND p.display = 'Y' 
             AND p.cmis_state_id = a.l_state 
             AND p.cmis_district_id = a.l_dist) AS policestndesc, 
            a.lct_casetype, 
            a.lct_caseno, 
            a.lct_caseyear, 
            CASE 
                WHEN a.ct_code = 4 THEN 
                    (SELECT short_description FROM casetype ct WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype) 
                ELSE 
                    (SELECT type_sname FROM lc_hc_casetype d WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y') 
            END AS type_sname, 
            a.lower_court_id, 
            a.is_order_challenged, 
            a.full_interim_flag, 
            a.judgement_covered_in, 
            a.lct_judge_desg 
        FROM lowerct a 
        LEFT JOIN state b ON a.l_state = b.id_no AND b.display = 'Y' 
        JOIN main e ON e.diary_no = a.diary_no 
        WHERE a.diary_no = ? 
        AND lw_display = 'Y' 
        {$is_order_challenged}
        ORDER BY a.lower_court_id";

    $query = $this->db->query($sql, [$diary_no]);

    return $query->getResultArray(); // Return an array of results
}


public function getModePath($hd_talw_id)
{
    $sql = "SELECT mode_path 
            FROM tw_o_r 
            WHERE id = (
                SELECT tw_o_r_id 
                FROM tw_comp_not 
                WHERE id = ? 
                AND display = 'Y'
            ) 
            AND display = 'Y'";

    $query = $this->db->query($sql, [$hd_talw_id]);

    return $query->getRowArray(); // Returns a single row as an associative array
}



    function post_dispatch_report()
    {
        // $sql = "SELECT 
        //         a.*, 
        //         b.s 
        //         FROM (
        //         SELECT 
        //             d.id, 
        //             a.diary_no, 
        //             a.process_id, 
        //             a.name, 
        //             a.address, 
        //             b.name AS nt_typ, 
        //             del_type,
        //             tw_sn_to, 
        //             copy_type, 
        //             send_to_type, 
        //             a.fixed_for, 
        //             a.rec_dt, 
        //             a.office_notice_rpt, 
        //         reg_no_display,
        //             sendto_district,
        //             sendto_state,
        //             a.nt_type,
        //             a.tal_state,
        //             a.tal_district,
        //             dispatch_id,
        //             dispatch_dt,
        //             station,
        //             weight,
        //             stamp,
        //         barcode,
        //             dis_remark,
        //             dispatch_user_id
        //         FROM 
        //             tw_tal_del a
        //         JOIN 
        //             master.tw_notice b ON a.nt_type::int = b.id
        //         JOIN 
        //             tw_o_r c ON c.tw_org_id = a.id
        //         JOIN 
        //             tw_comp_not d ON d.tw_o_r_id = c.id
        //         JOIN 
        //             main m ON a.diary_no = m.diary_no 
        //         $nature
        //         WHERE  
        //             a.display = 'Y'
        //             AND a.print = 1
        //             AND b.display = 'Y'
        //             AND c.display = 'Y'
        //             AND d.display = 'Y' 
        //             AND dispatch_id != 0 
        //             AND dispatch_dt is not null
        //             AND DATE(dispatch_dt) BETWEEN $1 AND $2 
        //             $ddlOR $state $district $casetype 
        //         ORDER BY 
        //             dispatch_id
        //         ) a 
        //         JOIN (
        //         SELECT 
        //             COUNT(d.dispatch_id) AS s,
        //             dispatch_id 
        //         FROM 
        //             tw_tal_del a
        //         JOIN 
        //             master.tw_notice b ON a.nt_type::int = b.id
        //         JOIN 
        //             tw_o_r c ON c.tw_org_id = a.id
        //         JOIN 
        //             tw_comp_not d ON d.tw_o_r_id = c.id
        //         JOIN 
        //             main m ON a.diary_no = m.diary_no 
        //         $nature
        //         WHERE  
        //             a.display = 'Y'
        //             AND a.print = 1
        //             AND b.display = 'Y'
        //             AND c.display = 'Y'
        //             AND d.display = 'Y' 
        //             AND dispatch_id != 0 
        //             AND dispatch_dt is not null
        //             AND DATE(dispatch_dt) BETWEEN $1 AND $2 
        //             $ddlOR $state $district $casetype 
        //         GROUP BY  
        //             dispatch_id
        //         ) b ON a.dispatch_id = b.dispatch_id";

        //above is actual 
        $sql = "SELECT 
        a.*, 
        b.s 
        FROM (
        SELECT 
            d.id, 
            a.diary_no, 
            a.process_id, 
            a.name, 
            a.address, 
        
            del_type,
            tw_sn_to, 
            copy_type, 
            send_to_type, 
            a.fixed_for, 
            a.rec_dt, 
            a.office_notice_rpt, 
        reg_no_display,
            sendto_district,
            sendto_state,
            a.nt_type,
            a.tal_state,
            a.tal_district,
            dispatch_id,
            dispatch_dt,
            station,
            weight,
            stamp,
        barcode,
            dis_remark,
            dispatch_user_id
        FROM 
            tw_tal_del a
        LEFT JOIN 
            tw_o_r c ON c.tw_org_id = a.id
        LEFT JOIN 
            tw_comp_not d ON d.tw_o_r_id = c.id
        LEFT JOIN 
            main m ON a.diary_no = m.diary_no 
        ORDER BY 
            dispatch_id
        ) a 
        LEFT JOIN (
        SELECT 
            COUNT(d.dispatch_id) AS s,
            dispatch_id 
        FROM 
            tw_tal_del a
        LEFT JOIN 
            master.tw_notice b ON a.nt_type::int = b.id
        LEFT  JOIN 
            tw_o_r c ON c.tw_org_id = a.id
        LEFT JOIN 
            tw_comp_not d ON d.tw_o_r_id = c.id
        LEFT JOIN 
            main m ON a.diary_no = m.diary_no 
        GROUP BY  
            dispatch_id
        ) b ON a.dispatch_id = b.dispatch_id";

        $query = $this->db->query($sql);

        // Get and return the result as an array
        $result =   $query->getResultArray();
        return  $result;

        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';
    }

    function get_fil_trap_users()
    {
        $sql = "SELECT distinct type_name,c.id FROM master.users a join 
       fil_trap_users b on a.usercode=b.usercode and b.display='Y' 
       join master.usertype c on c.id=b.usertype and (c.display='Y' or c.display='E') 
       WHERE section='19' and a.display='Y'  order by c.id";
        $query = $this->db->query($sql);

        $result =   $query->getResultArray();
        return  $result;

        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';

    }

    function get_users_for_case_reansfer($idd)
    {

        $sql = " select EMPID, NAME,
                SECTION,
                DISPLAY from (SELECT DISTINCT 
                U.EMPID,
                U.NAME,
                U.SECTION,
                U.DISPLAY,
                CASE 
                    WHEN U.SECTION = 19 THEN 1
                    WHEN U.SECTION = 77 THEN 2
                    ELSE 3 
                END AS section_order
                FROM 
                master.USERS U
                left JOIN 
                FIL_TRAP_USERS T_U ON U.USERCODE = T_U.USERCODE
                WHERE T_U.USERTYPE = '$idd'
                ORDER BY 
                section_order DESC,
                U.NAME)p ";
        $query = $this->db->query($sql);

        $result =   $query->getResultArray();
        return  $result;

        //  echo '<pre>'; 
        //  print_r($result);
        //  echo '</pre>';

    }

    public function getuser_for_transfer_case_alloted($ddl_users, $txt_frm_dt, $txt_to_dt, $ddl_users_nm)
    {


        $remarks = '';
        if ($ddl_users == '103') {
            $remarks = " AND (remarks='DE -> SCR' or remarks = 'AOR -> SCR' or remarks = 'FDR -> SCR' or remarks = 'SCR -> FDR' or remarks = 'FDR -> AOR')";
        } else  if ($ddl_users == '102') {
            $remarks = " AND (remarks='FIL -> DE')";
        } else  if ($ddl_users == '105') {
            $remarks = " AND (remarks='SCR -> CAT')";
        } else  if ($ddl_users == '1066') {
            $remarks = " AND (remarks='CAT -> TAG')";
        } else  if ($ddl_users == '1077') {
            $remarks = " AND (remarks='SCN -> IB-Ex' or remarks='TAG -> IB-Ex' or remarks='CAT -> IB-Ex')";
        } else  if ($ddl_users == '108') {
            $remarks = " AND (remarks='AOR -> FDR' or remarks='SCR -> FDR')";
        }

        // if($ddl_users!='101' and $ddl_users!='103') {
        //    echo  $sql = "SELECT diary_no, disp_dt, d_to_empid, remarks, r_by_empid, rece_dt 
        //             FROM fil_trap 
        //             WHERE d_to_empid = '$ddl_users_nm'
        //             AND remarks LIKE '%$remarks%' 
        //             AND disp_dt::date BETWEEN '$txt_frm_dt' AND '$txt_to_dt'";
        // }
        //  else
        if ($ddl_users == '103') {
            echo   $sql = "SELECT f.diary_no, disp_dt, d_to_empid, remarks, r_by_empid, rece_dt 
                            FROM fil_trap f 
                            LEFT JOIN main b ON f.diary_no = b.diary_no 
                            WHERE d_to_empid = '$ddl_users_nm' 
                            AND remarks LIKE '%$remarks%' 
                            AND disp_dt::date BETWEEN '$txt_frm_dt' AND '$txt_to_dt' 
                            AND comp_dt is null
                            AND b.c_status = 'P'";
        } else {
            $sql = "SELECT ec.diary_no, 
                    ec.created_at AS disp_dt, 
                    ec.created_by AS d_to_empid, 
                    'SCeFM Cases' AS remarks, 
                    '' AS r_by_empid, 
                    '' AS rece_dt 
                FROM main m 
                JOIN efiled_cases ec ON m.diary_no = ec.diary_no 
                AND ec.efiled_type = 'new_case' 
                AND ec.display = 'Y' 
                LEFT JOIN efiled_cases_transfer_status ects ON ects.diary_no = m.diary_no 
                LEFT JOIN fil_trap ft ON ft.diary_no = m.diary_no 
                JOIN master.users u ON u.usercode = ec.created_by 
                WHERE ects.diary_no IS NULL 
                AND m.c_status = 'P' 
                AND ft.diary_no IS NULL 
               
            
            ";
            //  -- AND ec.created_at::date > '2024-11-11' 
            //  -- AND empid = '$ddl_users_nm' 
        }

        $query = $this->db->query($sql);

        $result =   $query->getResultArray();
        return $result;

        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';

    }

    function getemp_id_for_transfer_case_alloted($ddl_users, $ddl_users_nm)
    {
        $sql = "Select u.empid,u.name from master.users u JOIN fil_trap_users t_u ON u.usercode = t_u.usercode 
            WHERE t_u.usertype = '$ddl_users' AND t_u.display = 'Y' and u.display='Y' and attend='P' and u.empid!='$ddl_users_nm' 
             order by u.name";
        $query = $this->db->query($sql);

        $result =   $query->getResultArray();
        return $result;
    }

    function updateEcPostalTransactions($dataForInsert, $dataForUpdate, $id)
    {
      
        $data = array(
            'action_taken_on' => date('Y-m-d H:i:s'),
            'last_updated_on' => date('Y-m-d H:i:s'),
            'create_modify' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP()
        );

        $builder = $this->db->table('ec_postal_transactions');

        // Update existing records
        $builder->where('is_active', 't');
        $builder->where('ec_postal_received_id', $id);
        $builder->update($dataForUpdate);

        // Insert new record
        $builder->insert($dataForInsert);  
    }


    function getReceiptDateWise($fromDate, $toDate)
    {
        //        echo $fromDate.">>>>".$toDate;
        //        die;
        $builder = $this->db->table('ec_postal_received ecpd');
        $builder->select('ecpd.id,ecpd.ec_case_id,sender_name,address, concat(ecpd.diary_no,\'/\',ecpd.diary_year) as diary, ecpd.remarks, ecpd.subject');
        $builder->select('case when postal_no is not null then postal_no else (case when letter_no is not null then letter_no end) end as postal_number');
        $builder->select('case when postal_date is not null then postal_date else (case when letter_date is not null then letter_date end) end as postal_date');
        $builder->select('(select postal_type_description from master.ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type');
        $builder->select("(case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) 
		else case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) 
		else case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) 
		else ecpd.postal_addressee end end end) as address_to");
        $builder->select("ec.diary_no as diary_number, ec.reg_no_display, (SELECT CONCAT(name, ' (', empid, ')') FROM master.users WHERE usercode = adm_updated_by) as received_by, ecpd.received_on");
        $builder->join('main ec', 'ecpd.ec_case_id = ec.diary_no', 'left');
        $builder->join('ec_postal_transactions ept', 'ecpd.id = ept.ec_postal_received_id', 'left');
        $builder->where("(ept.is_active='t' OR ept.is_active IS NULL)");
        $builder->where("DATE(ecpd.received_on) BETWEEN '$fromDate' AND '$toDate'");
        $builder->where('ecpd.is_deleted', 'f');
        $builder->orderBy('ecpd.diary_no');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();
        //        echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function getCompleteDetail($ecPostalReceivedId)
    {

        $builder = $this->db->table('ec_postal_received ep');
        $builder->select("id,(select postal_type_description from master.ref_postal_type where id=ref_postal_type_id) as postal_type,is_openable,
            postal_no,postal_date,letter_no,letter_date,subject,is_original_record,sender_name,address,
            (select state_name from master.ref_state where id=ref_state_id) as state,concat(ep.diary_no,'/',ep.diary_year) as ri_diary_number,pil_diary_number,remarks,
            (select concat(name ,'(',empid,')') from master.users where usercode=adm_updated_by) as received_by,received_on ,
            case when m.reg_no_display is not null and m.diary_no is not null then concat(m.reg_no_display,' @ ',concat(left((cast(m.diary_no as text)),-4),' / ',right((cast(m.diary_no as text)),4))) 
else '' end as case_no");
        $builder->join('main m', 'ep.ec_case_id=m.diary_no', 'left');
        $builder->where('id', $ecPostalReceivedId);
        $query = $builder->get();

        //        $query=$this->db->getLastQuery();
        //        echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function getTransactions($ecPostalReceivedId)
    {

        $builder = $this->db->table('ec_postal_transactions');
        $builder->select("(case when dispatched_to_user_type='s' then (select section_name from master.usersection where id=dispatched_to) else 
        case when dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=dispatched_to) else 
        case when dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=dispatched_to) else '' end end end) as address_to
        ,(select concat(name ,'(',empid,')') from master.users where usercode=dispatched_by) as dispatched_by,dispatched_on,
        case when action_taken=1 then 'Received' else case when action_taken=2 then 'Returned' else case when action_taken=3 then 'Forwarded' else '' end end end as action_taken,action_taken_on,
        (select concat(name ,'(',empid,')') from master.users where usercode=action_taken_by) as action_taken_by,is_active,return_reason");
        $builder->where('ec_postal_received_id', $ecPostalReceivedId);
        $builder->where('is_deleted', 'f');
        $builder->orderBy('last_updated_on');
        $query = $builder->get();

        //        $query=$this->db->getLastQuery();
        //        echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function getDispatchData($whereCondition, $receiptModeCondition, $fromDate, $toDate)
    {

        //        ept.dispatched_to_user_type='j' and ecpd.dispatched_to=254
        //echo $whereCondition.">>>".$receiptModeCondition;
        $builder = $this->db->table('ec_postal_received ecpd');
        $builder->select("ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
        ecpd.subject,(case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end) as postal_number,
        (case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end) as postal_date,
      (select postal_type_description from master.ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
      (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
        case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
        case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to,        
      ec.diary_no as diary_number,ec.reg_no_display,
     (select concat(name ,'(',empid,')') from master.users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,ept.action_taken");

        $builder->join('main ec', 'ecpd.ec_case_id=ec.diary_no', 'LEFT');
        $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_received_id', 'LEFT');
        $builder->where("(ept.is_active='t' or ept.is_active is null) and date(ecpd.received_on)  between '$fromDate' and '$toDate' and ecpd.is_deleted='f' ");
        if ($whereCondition != null) {
            $builder->where("$whereCondition");
        }
        if ($receiptModeCondition != null) {
            $builder->where("$receiptModeCondition");
        }
        //        $builder->where("$whereCondition");
        //        $builder->where("$receiptModeCondition");
        $builder->where("(ept.action_taken is null or ept.action_taken!=1)");
        $builder->orderBy('ecpd.diary_no');
        $query = $builder->get();
        //
        //        $query=$this->db->getLastQuery();
        //        echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function updateServeStaus($id, $letterStatus, $usercode, $serveStage, $serveType, $remarks)
    {
        $dataForDispatch = array(
            'ref_letter_status_id' => $letterStatus,
            'usercode' => $usercode,
            'updated_on' => date('Y-m-d H:i:s'),
            'serve_stage' => $serveStage,
            'tw_serve_id' => $serveType,
            'serve_remarks' => $remarks

            // 'updated_by' => $usercode,
            // 'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('ec_postal_dispatch');

        // Use the where clause properly
        $builder->where('id', $id);
        $builder->update($dataForDispatch);

        if ($this->db->affectedRows() > 0) {
            echo "hi";
            $dataForDispatchTransactions = array(
                'ec_postal_dispatch_id' => $id,
                'ref_letter_status_id' => $letterStatus,
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s')
                // 'create_modify' => date("Y-m-d H:i:s"),
                // 'updated_by' => $usercode,
                // 'updated_by_ip' => getClientIP()
            );

            // Insert into the transactions table
            $this->db->table('ec_postal_dispatch_transactions')->insert($dataForDispatchTransactions);
            // echo $builder->set($dataForDispatchTransactions)->getCompiledInsert(); 
        }
    }

    function getUserDetail($usercode)
    {

        $builder = $this->db->table('master.users u');
        $builder->select('ut.type_name,u.*');
        $builder->join('master.usertype ut', 'u.usertype=ut.id', 'inner');
        $builder->where('u.usercode', $usercode);
        $builder->where('u.display', 'Y');

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }

        //        $queryString = "select ut.type_name,u.* from users u inner join usertype ut on u.usertype=ut.id where u.usercode=? and u.display=?";
        //        $query = $this->db->query($queryString, array($usercode, 'Y'));
        //        $res = $query->result();  // this returns an object of all results
        //        $row = $res[0];
        //        return $row;
    }


    function getReceivedByConcernDateWise($fromDate, $toDate, $usercode, $section, $reportType = 0)
    {
        $whereCondition = "ept.action_taken in (1,2)";
        if ($reportType == 1) {
            $whereCondition = "ept.action_taken=1";
        } else if ($reportType == 2) {
            $whereCondition = "ept.action_taken=2";
        }
        if ($usercode == 1) {
            //            $sql = "select ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
            //                ecpd.subject,case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end as postal_number,
            //                case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end as postal_date,
            //                (select postal_type_description from ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
            //               (case when ept.dispatched_to_user_type='s' then (select section_name from usersection where id=ept.dispatched_to) else
            //                case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from users where usercode=ept.dispatched_to) else
            //                case when ept.dispatched_to_user_type = 'j' then (select jname from judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
            //                ,ec.diary_no as diary_number,ec.reg_no_display,
            //                (select concat(name ,'(',empid,')') from users where usercode=dispatched_by) as dispatched_by,dispatched_on,
            //                (select concat(name ,'(',empid,')') from users where usercode=action_taken_by) as action_taken_by,action_taken_on,
            //                case when action_taken=1 then 'Received' else case when action_taken=2 then 'Returned' else '' end end as action_taken,
            //                (select concat(name ,'(',empid,')') from users where usercode=adm_updated_by) as received_by,ecpd.received_on
            //
            //                from ec_postal_received ecpd
            //                left join main ec on ecpd.ec_case_id=ec.diary_no
            //                left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_received_id
            //                inner join users u on u.usercode=ept.action_taken_by
            //                inner join usersection us on us.id=u.section
            //                where  ecpd.is_deleted=? and date(ept.action_taken_on) between ? and ?
            //                 $whereCondition order by ecpd.diary_no";
            //            $query = $this->db->query($sql, array('f', $fromDate, $toDate));
            //            return $query->result_array();

            $builder = $this->db->table('ec_postal_received ecpd');
            $builder->select("ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
                ecpd.subject,(case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end) as postal_number,
                (case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end) as postal_date,
                (select postal_type_description from master.ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
               (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
                case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
                case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
                ,ec.diary_no as diary_number,ec.reg_no_display,
                (select concat(name ,'(',empid,')') from master.users where usercode=dispatched_by) as dispatched_by,dispatched_on,
                (select concat(name ,'(',empid,')') from master.users where usercode=action_taken_by) as action_taken_by,action_taken_on,
                case when action_taken=1 then 'Received' else case when action_taken=2 then 'Returned' else '' end end as action_taken,
                (select concat(name ,'(',empid,')') from master.users where usercode=adm_updated_by) as received_by,ecpd.received_on");

            $builder->join('main ec', 'ecpd.ec_case_id=ec.diary_no', 'LEFT');
            $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_received_id', 'LEFT');
            $builder->join('master.users u', 'u.usercode=ept.action_taken_by', 'INNER');
            $builder->join('master.usersection us', 'us.id=u.section', 'INNER');
            $builder->where("(ecpd.is_deleted='f' and date(ept.action_taken_on) between '$fromDate' and '$toDate')");
            $builder->where("$whereCondition");
            $builder->orderBy("ecpd.diary_no");
        } else {
            //            $sql = "select ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
            //                ecpd.subject,case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end as postal_number,
            //                case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end as postal_date,
            //                (select postal_type_description from ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
            //               (case when ept.dispatched_to_user_type='s' then (select section_name from usersection where id=ept.dispatched_to) else
            //                case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from users where usercode=ept.dispatched_to) else
            //                case when ept.dispatched_to_user_type = 'j' then (select jname from judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
            //                ,ec.diary_no as diary_number,ec.reg_no_display,
            //                (select concat(name ,'(',empid,')') from users where usercode=dispatched_by) as dispatched_by,dispatched_on,
            //                (select concat(name ,'(',empid,')') from users where usercode=action_taken_by) as action_taken_by,action_taken_on,
            //                case when action_taken=1 then 'Received' else case when action_taken=2 then 'Returned' else '' end end as action_taken,
            //                (select concat(name ,'(',empid,')') from users where usercode=adm_updated_by) as received_by,ecpd.received_on
            //
            //                from ec_postal_received ecpd
            //                left join main ec on ecpd.ec_case_id=ec.diary_no
            //                left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_received_id
            //                inner join users u on u.usercode=ept.action_taken_by
            //                inner join usersection us on us.id=u.section
            //                where ecpd.is_deleted=? and date(ept.action_taken_on) between ? and ?
            //                 and (action_taken_by=? or us.id=?) $whereCondition order by ecpd.diary_no";

            $builder = $this->db->table('ec_postal_received ecpd');
            $builder->select("ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
                ecpd.subject,(case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end) as postal_number,
               (case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end) as postal_date,
                (select postal_type_description from master.ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
               (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
                case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
                case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
                ,ec.diary_no as diary_number,ec.reg_no_display,
                (select concat(name ,'(',empid,')') from master.users where usercode=dispatched_by) as dispatched_by,dispatched_on,
                (select concat(name ,'(',empid,')') from master.users where usercode=action_taken_by) as action_taken_by,action_taken_on,
                case when action_taken=1 then 'Received' else case when action_taken=2 then 'Returned' else '' end end as action_taken,
                (select concat(name ,'(',empid,')') from master.users where usercode=adm_updated_by) as received_by,ecpd.received_on");

            $builder->join('main ec', 'ecpd.ec_case_id=ec.diary_no', 'LEFT');
            $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_received_id', 'LEFT');
            $builder->join('master.users u', 'u.usercode=ept.action_taken_by', 'INNER');
            $builder->join('master.usersection us', 'us.id=u.section', 'INNER');
            $builder->where("(ecpd.is_deleted='f' and date(ept.action_taken_on) between '$fromDate' and '$toDate')");
            $builder->where("(action_taken_by=$usercode or us.id=$section)");
            $builder->where("$whereCondition");
            $builder->orderBy("ecpd.diary_no");
        }

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function get_received_data($usercondition)
    {

        if ($usercondition != '') {
            $builder = $this->db->table('ec_postal_received epr');
            $builder->select('*');
            $builder->where("$usercondition");
            $query = $builder->get();
            //        $query=$this->db->getLastQuery();echo (string) $query;exit();
            $result = $query->getResultArray();

            if ($query->getNumRows() >= 1) {
                return $result;
            }
        } else {
            return false;
        }
    }

    function getDakDataForReceive($usercode, $section, $status = "", $actionType = "", $fromDate = "", $toDate = "")
    // {

    //     $whereCondition = "";
    //     $dateCondition = "";
    //     if ($status == 'P') {
    //         $whereCondition = "(ept.action_taken is null or ept.action_taken=0 or CAST(ept.action_taken_by as text)='')";
    //     } elseif ($status = 'R') {
    //         if ($actionType == 1) {
    //             $whereCondition = "ept.action_taken=$actionType and (ept.action_taken_on is not null or ept.action_taken_on!='')";
    //         }
    //     }
    //     if ($fromDate != "" && $toDate != "") {
    //         $dateCondition = "and date(ept.action_taken_on) between $fromDate and $toDate";
    //     }
    //     $sql = "";
    //     if ($usercode == 1) {
    //       //            echo "FFF";
    //         $builder = $this->db->table('ec_postal_received ecpd');
    //         $builder->select("ecpd.is_ad_card,ecpd.ec_postal_dispatch_id,epd.is_with_process_id,epd.process_id,epd.process_id_year,epd.reference_number,                
    //             (select name from master.tw_serve where serve_stage=epd.serve_stage and serve_type=0) as serve_stage,
    //           (select name from master.tw_serve where id=epd.tw_serve_id) as serve_type,epd.serve_remarks, 
    //             ept.id as ec_postal_transactions_id,ecpd.id,ecpd.ec_case_id,ecpd.sender_name,ecpd.address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
    //                 ecpd.subject,(case when ecpd.postal_no is not null then ecpd.postal_no else case when ecpd.letter_no is not null then ecpd.letter_no end end) as postal_number,
    //                 (case when ecpd.postal_date is not null then ecpd.postal_date else case when ecpd.letter_date is not null then ecpd.letter_date end end) as postal_date,
    //               (select postal_type_description from master.ref_postal_type rpt where rpt.id=ecpd.ref_postal_type_id) as postal_type,
    //               (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
    //               case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
    //               case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
    //               ,ec.diary_no as diary_number,ec.reg_no_display,
    //              (select concat(name ,'(',empid,')') from master.users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
    //              (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,ept.is_forwarded,
    //    (case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' 
    //        else case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority");

    //         $builder->join('main ec', 'ecpd.ec_case_id=ec.diary_no', 'LEFT');
    //         $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_received_id', 'LEFT');
    //         $builder->join('ec_postal_dispatch epd', 'ecpd.ec_postal_dispatch_id=epd.id', 'LEFT');

    //         $builder->where("(ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted='f' and ept.dispatched_by is not null and CAST(ept.dispatched_by as text) !=''");
    //         $builder->where("$whereCondition $dateCondition");

    //         $builder->orderBy("ecpd.diary_no");

    //     } else {
    //         //echo "EEE";
    //         $builder = $this->db->table('ec_postal_received ecpd');
    //         $builder->select("ecpd.is_ad_card,ecpd.ec_postal_dispatch_id,epd.is_with_process_id,epd.process_id,epd.process_id_year,epd.reference_number,
    //     (select name from master.tw_serve where serve_stage=epd.serve_stage and serve_type=0) as serve_stage,
    //     (select name from master.tw_serve where id=epd.tw_serve_id) as serve_type,epd.serve_remarks,
    //     ept.id as ec_postal_transactions_id,ecpd.id,ecpd.ec_case_id,ecpd.sender_name,ecpd.address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
    //     ecpd.subject,(case when ecpd.postal_no is not null then ecpd.postal_no else case when ecpd.letter_no is not null then ecpd.letter_no end end) as postal_number,
    //     (case when ecpd.postal_date is not null then ecpd.postal_date else case when ecpd.letter_date is not null then ecpd.letter_date end end) as postal_date,
    //     (select postal_type_description from master.ref_postal_type rpt where rpt.id=ecpd.ref_postal_type_id) as postal_type,
    //     (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
    //     case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
    //     case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
    //     ,ec.diary_no as diary_number,ec.reg_no_display,
    //     (select concat(name ,'(',empid,')') from master.users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
    //     (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,ept.is_forwarded,
    //     (case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' else case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority ");

    //         $builder->join('main ec', 'ecpd.ec_case_id=ec.diary_no', 'LEFT');
    //         $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_received_id', 'LEFT');
    //         $builder->join('ec_postal_dispatch epd', 'ecpd.ec_postal_dispatch_id=epd.id', 'LEFT');

    //         $builder->where("(ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted='f' and ept.dispatched_by is not null and CAST(ept.dispatched_by as text) !=''");
    //         $builder->where("$whereCondition $dateCondition");
    //         $builder->where("((ept.dispatched_to_user_type='s' and ept.dispatched_to=$section) or (ept.dispatched_to_user_type='o' and ept.dispatched_to=$usercode)) ");
    //         $builder->orderBy("ecpd.diary_no");

    //     }

    //     $query = $builder->get();
    //     //        $query=$this->db->getLastQuery();echo (string) $query;exit();
    //             $result = $query->getResultArray();
    //     //        echo "<pre>";
    //     //        print_r($result);
    //     //        die;

    //     if($query->getNumRows()>=1)
    //     {
    //         return $result;
    //     }else{
    //         return false;
    //     }

    // }

    {
        $sql = "SELECT 
                ecpd.is_ad_card, 
                ecpd.ec_postal_dispatch_id, 
                epd.is_with_process_id, 
                epd.process_id, 
                epd.process_id_year, 
                epd.reference_number, 
                (SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage, 
                (SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type, 
                epd.serve_remarks, 
                ept.id AS ec_postal_transactions_id, 
                ecpd.id, 
                ecpd.ec_case_id, 
                ecpd.sender_name, 
                ecpd.address, 
                CONCAT(ecpd.diary_no, '/', ecpd.diary_year) AS diary, 
                ecpd.remarks, 
                ecpd.subject, 
                (CASE 
                    WHEN ecpd.postal_no IS NOT NULL THEN ecpd.postal_no 
                    ELSE CASE 
                    WHEN ecpd.letter_no IS NOT NULL THEN ecpd.letter_no 
                    END 
                END) AS postal_number, 
                (CASE 
                    WHEN ecpd.postal_date IS NOT NULL THEN ecpd.postal_date 
                    ELSE CASE 
                    WHEN ecpd.letter_date IS NOT NULL THEN ecpd.letter_date 
                    END 
                END) AS postal_date, 
                (SELECT postal_type_description FROM master.ref_postal_type rpt WHERE rpt.id = ecpd.ref_postal_type_id) AS postal_type, 
                (CASE 
                    WHEN ept.dispatched_to_user_type = 's' THEN (SELECT section_name FROM master.usersection WHERE id = ept.dispatched_to) 
                    WHEN ept.dispatched_to_user_type = 'o' THEN (SELECT CONCAT(name, ' (', empid, ')') FROM master.users WHERE usercode = ept.dispatched_to) 
                    WHEN ept.dispatched_to_user_type = 'j' THEN (SELECT jname FROM master.judge WHERE jcode = ept.dispatched_to) 
                    ELSE ecpd.postal_addressee 
                END) AS address_to, 
                ec.diary_no AS diary_number, 
                ec.reg_no_display, 
                (SELECT CONCAT(name, ' (', empid, ')') FROM master.users WHERE usercode = ept.dispatched_by) AS dispatched_by, 
                ept.dispatched_on, 
                (SELECT CONCAT(name, ' (', empid, ')') FROM master.users WHERE usercode = ept.action_taken_by) AS action_taken_by, 
                ept.action_taken_on, 
                ept.action_taken, 
                ept.is_forwarded, 
                (CASE 
                    WHEN ept.letterPriority = 0 THEN 'Normal' 
                    WHEN ept.letterPriority = 1 THEN 'Urgent But Not Important' 
                    WHEN ept.letterPriority = 2 THEN 'Important But Not Urgent' 
                    WHEN ept.letterPriority = 3 THEN 'Urgent And Important' 
                END) AS letterPriority 
                FROM 
                ec_postal_received ecpd 
                LEFT JOIN 
                main ec ON ecpd.ec_case_id = ec.diary_no 
                LEFT JOIN 
                ec_postal_transactions ept ON ecpd.id = ept.ec_postal_received_id 
                LEFT JOIN 
                ec_postal_dispatch epd ON ecpd.ec_postal_dispatch_id = epd.id 
               
                ORDER BY 
                ecpd.diary_no";
        $query  = $this->db->query($sql);
        $result =   $query->getResultArray();
        return  $result;

        // echo '<pre>'; 
        // print_r($result);
        // echo '</pre>';
    }

    public function get_notice_ack_report($txtFromDate)

    // {  // pr($txtFromDate);
    //    echo  $sql = "SELECT aa.*, bb.s 
    //             FROM (
    //             SELECT 
    //                 d.id,
    //                 a.diary_no,
    //                 process_id,
    //                 a.name,
    //                 address,
    //                 b.name AS nt_typ,
    //                 del_type,
    //                 tw_sn_to,
    //                 copy_type,
    //                 send_to_type,
    //                 fixed_for,
    //                 rec_dt,
    //                 office_notice_rpt,
    //                 reg_no_display,
    //                 sendto_district,
    //                 sendto_state,
    //                 nt_type,
    //                 tal_state,
    //                 tal_district,
    //                 dispatch_id,
    //                 dispatch_dt,
    //                 station,
    //                 weight,
    //                 stamp,
    //                 barcode,
    //                 dis_remark,
    //                 dispatch_user_id,
    //                 d.ack_id,
    //                 d.ack_user_id,
    //                 d.serve,
    //                 d.ser_type,
    //                 d.ser_date,
    //                 d.ser_dt_ent_dt,
    //                 da_rec_dt,
    //                 tentative_section(m.diary_no) AS section
    //             FROM 
    //                 tw_tal_del a
    //             LEFT JOIN 
    //                 master.tw_notice b ON a.nt_type::int = b.id
    //             LEFT JOIN 
    //                 tw_o_r c ON c.tw_org_id = a.id
    //             LEFT JOIN 
    //                 tw_comp_not d ON d.tw_o_r_id = c.id
    //            LEFT  JOIN 
    //                 main m ON a.diary_no = m.diary_no
    //             -- WHERE  
    //             --     a.display = 'Y'
    //             --     AND print = 1
    //             --     $serveCondition
    //             --     AND b.display = 'Y'
    //             --     AND c.display = 'Y'
    //             --     AND d.display = 'Y' 
    //             --     AND dispatch_id != 0 
    //             --     AND dispatch_dt is not null
    //             --     AND ser_dt_ent_dt is not null
    //             --     AND ser_dt_ent_dt::date BETWEEN '$from_date' AND '$todate' 
    //             --     $condition  
    //             ORDER BY 
    //                 ack_id, ser_dt_ent_dt
    //             ) aa 
    //            LEFT JOIN (
    //             SELECT 
    //                 COUNT(a.id) AS s,
    //                 diary_no,
    //                 rec_dt,
    //                 ack_id 
    //             FROM 
    //                 tw_tal_del a  
    //            LEFT JOIN 
    //                 tw_o_r c ON c.tw_org_id = a.id
    //            LEFT JOIN 
    //                 tw_comp_not d ON d.tw_o_r_id = c.id
    //             -- WHERE 
    //             --     print = '1'
    //             --     $serveCondition
    //             --     AND a.display = 'Y'  
    //             --     AND c.display = 'Y' 
    //             --     AND d.display = 'Y'
    //             --     AND ser_dt_ent_dt::date BETWEEN '$from_date' AND '$todate' 
    //             --     AND dispatch_id != 0 
    //             --     AND dispatch_dt is not null
    //             --     AND ser_dt_ent_dt is not null 
    //             GROUP BY 
    //                 diary_no, ack_id,rec_dt
    //             ) bb ON aa.diary_no = bb.diary_no AND aa.ack_id = bb.ack_id";
    //              $query  = $this->db->query($sql);
    //              $result =   $query->getResultArray();
    //             // return  $result;

    //         echo '<pre>'; 
    //         print_r($result);
    //         echo '</pre>';
    // }
    {  // pr($txtFromDate);
        $sql = "SELECT aa.*, bb.s 
                 FROM (
                 SELECT 
                     d.id,
                     a.diary_no,
                     process_id,
                     a.name,
                     address,
                     b.name AS nt_typ,
                     del_type,
                     tw_sn_to,
                     copy_type,
                     send_to_type,
                     fixed_for,
                     rec_dt,
                     office_notice_rpt,
                     reg_no_display,
                     sendto_district,
                     sendto_state,
                     nt_type,
                     tal_state,
                     tal_district,
                     dispatch_id,
                     dispatch_dt,
                     station,
                     weight,
                     stamp,
                     barcode,
                     dis_remark,
                     dispatch_user_id,
                     d.ack_id,
                     d.ack_user_id,
                     d.serve,
                     d.ser_type,
                     d.ser_date,
                     d.ser_dt_ent_dt,
                     da_rec_dt,
                     tentative_section(m.diary_no) AS section
                 FROM 
                     tw_tal_del a
                 LEFT JOIN 
                     master.tw_notice b ON a.nt_type::int = b.id
                 LEFT JOIN 
                     tw_o_r c ON c.tw_org_id = a.id
                 LEFT JOIN 
                     tw_comp_not d ON d.tw_o_r_id = c.id
                LEFT  JOIN 
                     main m ON a.diary_no = m.diary_no
               
                 ORDER BY 
                     ack_id, ser_dt_ent_dt
                 ) aa 
                LEFT JOIN (
                 SELECT 
                     COUNT(a.id) AS s,
                     diary_no,
                     rec_dt,
                     ack_id 
                 FROM 
                     tw_tal_del a  
                LEFT JOIN 
                     tw_o_r c ON c.tw_org_id = a.id
                LEFT JOIN 
                     tw_comp_not d ON d.tw_o_r_id = c.id
               
                 GROUP BY 
                     diary_no, ack_id,rec_dt
                 ) bb ON aa.diary_no = bb.diary_no AND aa.ack_id = bb.ack_id";
        $query  = $this->db->query($sql);
        $result =   $query->getResultArray();
        return  $result;

        //  echo '<pre>'; 
        //  print_r($result);
        //  echo '</pre>';
    }

    function getInitiatedDakDataForReceive($usercode, $section, $status = "", $actionType = "", $fromDate = "", $toDate = "")
    {
        //echo $usercode.'||'.$section;

        $whereCondition = "";
        $dateCondition = "";
        if ($status == 'P') {
            $whereCondition = " (ept.action_taken is null or ept.action_taken=0 or cast(ept.action_taken_by as text)='')";
        } elseif ($status = 'R') {
            if ($actionType == 1) {
                $whereCondition = "and ept.action_taken=$actionType and (ept.action_taken_on is not null or ept.action_taken_on!='')";
            }
        }
        if ($fromDate != "" && $toDate != "") {
            $dateCondition = " and date(ept.action_taken_on) between $fromDate and $toDate";
        }
        $sql = "";
        $usercode = 2;

        if ($usercode == 1) {

            //            $sql = "select ecpd.id, ecpd.letter_no as letter_number,
            //                    ecpd.letter_subject as subject, ept.id as ec_postal_transactions_id, (case when ept.dispatched_to_user_type='s' then (select section_name from usersection where id=ept.dispatched_to) else
            //                  case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from users where usercode=ept.dispatched_to) else
            //                  case when ept.dispatched_to_user_type = 'j' then (select jname from judge where jcode=ept.dispatched_to) end end end) as address_to,
            //                 (select concat(name ,'(',empid,')') from users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
            //                 (select concat(name ,'(',empid,')') from users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,ept.is_forwarded,(case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' else case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority
            //                  from ec_postal_user_initiated_letter ecpd
            //                  left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_user_initiated_letter_id
            //                  where (ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted=? and ept.dispatched_by is not null and ept.dispatched_by !=''
            //                  $whereCondition    $dateCondition
            //                  order by ecpd.id DESC ";

            $builder = $this->db->table('ec_postal_user_initiated_letter ecpd');
            $builder->select("ecpd.id, ecpd.letter_no as letter_number,
                    ecpd.letter_subject as subject, ept.id as ec_postal_transactions_id, (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
                  case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
                  case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) end end end) as address_to,
                 (select concat(name ,'(',empid,')') from master.users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
                 (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,
                 ept.is_forwarded,
                 (case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' else 
                 case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority ");


            $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_user_initiated_letter_id', 'LEFT');
            $builder->where("(ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted='f' and ept.dispatched_by is not null and CAST(ept.dispatched_by as text) !=''");
            $builder->where("$whereCondition $dateCondition");
            $builder->orderBy("ecpd.id", "DESC");

            //            $query = $this->db->query($sql, array('f'));
            //            if ($query->num_rows() >= 1) {
            //                return $query->result_array();
            //            }
        } else {
            //            $sql = "select ecpd.id, ecpd.letter_no as letter_number,
            //                    ecpd.letter_subject as subject, ept.id as ec_postal_transactions_id, (case when ept.dispatched_to_user_type='s' then (select section_name from usersection where id=ept.dispatched_to) else
            //                  case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from users where usercode=ept.dispatched_to) else
            //                  case when ept.dispatched_to_user_type = 'j' then (select jname from judge where jcode=ept.dispatched_to) end end end) as address_to,
            //                 (select concat(name ,'(',empid,')') from users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
            //                 (select concat(name ,'(',empid,')') from users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,
            //       ept.is_forwarded,
            //       (case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' else
            //           case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority
            //
            //             from ec_postal_user_initiated_letter ecpd
            //                  left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_user_initiated_letter_id
            //                  where (ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted=? and ept.dispatched_by is not null and ept.dispatched_by !=''
            //                  $whereCondition    $dateCondition
            //                   and ((ept.dispatched_to_user_type='s' and ept.dispatched_to=?) or (ept.dispatched_to_user_type='o' and ept.dispatched_to=?))
            //                  order by ecpd.id DESC ";

            $builder = $this->db->table('ec_postal_user_initiated_letter ecpd');
            $builder->select("ecpd.id, ecpd.letter_no as letter_number,
                    ecpd.letter_subject as subject, ept.id as ec_postal_transactions_id, 
                    (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
                  case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
                  case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) end end end) as address_to,
                 (select concat(name ,'(',empid,')') from master.users where usercode=ept.dispatched_by) as dispatched_by,ept.dispatched_on,
                 (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.action_taken,
       ept.is_forwarded,
       (case when ept.letterPriority='0' THEN 'Normal' else case when ept.letterPriority='1' THEN 'Urgent But Not Important' else 
           case  when ept.letterPriority='2' THEN 'Important But Not Urgent' else case when ept.letterPriority='3' THEN 'Urgent And Important' END END END END) as letterPriority ");
            $builder->join('ec_postal_transactions ept', 'ecpd.id=ept.ec_postal_user_initiated_letter_id', 'LEFT');
            $builder->where("(ept.is_active='t' or ept.is_active is null) and ecpd.is_deleted='f' and ept.dispatched_by is not null and cast(ept.dispatched_by as text) !=''");
            $builder->where("$whereCondition $dateCondition");
            $builder->where("((ept.dispatched_to_user_type='s' and ept.dispatched_to=$section) or (ept.dispatched_to_user_type='o' and ept.dispatched_to=$usercode))");
            $builder->orderBy("ecpd.id", "DESC");
        }

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();
        //        echo "<pre>";
        //        print_r($result);
        //        die;

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }

    function getOfficersListBySection($dealingSection)
    {

        $builder = $this->db->table('master.usersection us');
        $builder->select('nm.registrar, nm.additional_registrar, nm.deputy_registrar, nm.assistant_registrar, nm.branch_officer');
        $builder->join('master.notice_mapping nm', 'us.id=nm.section_id', 'INNER');
        $builder->where("nm.section_id", $dealingSection);
        $builder->where("us.display", 'Y');

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }
    }

    function getSectionNameBySection($section)
    {
        $builder = $this->db->table('master.usersection us');
        $builder->select('section_name');
        $builder->where('id', $section);

        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }

        //        $this->db->where('id', $section);
        //        $query = $this->db->get('usersection');
        //        $res = $query->result_array();
        //        $sectionName = $res[0]['section_name'];
        //        return $sectionName;
    }

    function getSecretaryGeneral()
    {
        $builder = $this->db->table('master.users');
        $builder->select('empid');
        $builder->where("usertype.type_name like '%SECRETARY GENERAL%'");
        $builder->join('master.usertype', 'users.usertype = usertype.id', 'INNER');
        $query = $builder->get();
        $query = $this->db->getLastQuery();
        echo (string) $query;
        exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }
    }



    function getImagesForTransactionId($id)
    {
        $builder = $this->db->table('ec_forward_letter_postal_transactions eflpt');
        $builder->select('efli.id,efli.file_display_name,efli.file_path,efli.file_name ');
        $builder->where("eflpt.transactions_id", $id);
        $builder->join('ec_forward_letter_images efli', 'eflpt.image_id = efli.id', 'INNER');
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }
    }

    function getDakTransactionDetails($ecPostalTransaction_id)
    {
        $builder = $this->db->table('ec_postal_transactions');
        $builder->select('*');
        $builder->where("id", $ecPostalTransaction_id);
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }
    }

    function doReceiveDakForSection($id, $actionType, $usercode, $returnReason, $letterPriority, $officer)
    {
        // 288934#301218#0#
        $id = explode('#', $id);
        //        echo "<pre>";print_r($id);die;
        //        Array
        //        (
        //            [0] => 288934
        //            [1] => 301218
        //            [2] => 0
        //            [3] =>
        //        )
        $ecPostalTransaction_id = $id[1];
        $isADCard = $id[2];
        $ecPostalDispatchId = $id[3];
        if ($actionType == 1) {
            $ecPostalReceivedId = $id[0];
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('ec_postal_transactions');
            $builder->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f');
            $builder->update($data);
        } elseif ($actionType == 3) {
            $ecPostalReceivedId = $id[0];
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on'    => date("Y-m-d H:i:s"),
                'updated_by'    => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('ec_postal_transactions');
            $builder->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f');
            $builder->update($data);
            //For - receive_then_forward_Case
            $this->saveForwardedDak($ecPostalReceivedId, "", $letterPriority, $officer);
        } elseif ($actionType == 2) {
            $ecPostalReceivedId = $id[0];
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'return_reason' => $returnReason,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'is_active' => 'f',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $builder = $this->db->table('ec_postal_transactions');
            $builder->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f');
            $builder->update($data);
            //echo $this->db->last_query();

            $data = array(
                'ec_postal_received_id' => $ecPostalReceivedId,
                'dispatched_to_user_type' => 's',
                'dispatched_to' => 68, //Id of R&I section in usersection table
                'dispatched_by' => $usercode,
                'dispatched_on' => date('Y-m-d H:i:s'),
                'is_active' => 't',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $this->db->insert('ec_postal_transactions', $data);
            //echo $this->db->last_query();
        }
        if ($isADCard == 1) {
            //Update ref_letter_status_id in ec_postal_dispatch
            $dataForDispatch = array(
                'ref_letter_status_id' => 8, // 8 for AD/Letter Received by Section/Concerned
                'usercode' => $usercode,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()
            );
            $this->db->where('id', $ecPostalDispatchId)->update('ec_postal_dispatch', $dataForDispatch);

            if ($this->db->affected_rows() > 0) {
                // Enter a row with status of AD send to Section in ec_postal_dispatch_transaction
                $dataForDispatchTransactions = array(
                    'ec_postal_dispatch_id' => $ecPostalDispatchId,
                    'ref_letter_status_id' => 8,
                    'usercode' => $usercode,
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => $usercode,
                    'updated_by_ip' => getClientIP()
                );
                $this->db->insert('ec_postal_dispatch_transactions', $dataForDispatchTransactions);
            }
        }
        return $this->db->affectedRows();
    }

    function doReceiveForwardableDakForSection($id, $actionType, $usercode, $returnReason, $ec_postal_user_initiated_letter_id, $dispatchedBy, $letterPriority, $officer)
    {
        $id = explode('#', $id);
        //            echo "<pre>";
        //            print_r($id);
        //            die;
        $ecPostalTransaction_id = $id[1];
        $isADCard = $id[2];
        $ecPostalDispatchId = $id[3];
        $ecPostalReceivedId = 0;
        if (!($ec_postal_user_initiated_letter_id > 0)) {
            $ecPostalReceivedId = $id[0];
        }
        //        echo $ecPostalReceivedId;
        //        die;
        //        echo $actionType.">>";
        //        die;
        if ($actionType == 1) {
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()

            );
            if ($ec_postal_user_initiated_letter_id > 0) {

                //$this->db->where('ec_postal_user_initiated_letter_id', $ec_postal_user_initiated_letter_id)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);

                $builder = $this->db->table('ec_postal_transactions');
                $builder->where('ec_postal_user_initiated_letter_id', $ec_postal_user_initiated_letter_id);
                $builder->where('is_active', 't');
                $builder->where('is_deleted', 'f');
                $query = $builder->update($data);
            } else {
                //                $this->db->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);
                $builder = $this->db->table('ec_postal_transactions');
                $builder->where('ec_postal_received_id', $ecPostalReceivedId);
                $builder->where('is_active', 't');
                $builder->where('is_deleted', 'f');
                $query = $builder->update($data);
            }
        } elseif ($actionType == 3) {
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'is_active' => 'f',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            );
            if ($ec_postal_user_initiated_letter_id > 0) {
                //$this->db->where('ec_postal_user_initiated_letter_id', $ec_postal_user_initiated_letter_id)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);
                $builder = $this->db->table('ec_postal_transactions');
                $builder->where('ec_postal_user_initiated_letter_id', $ec_postal_user_initiated_letter_id);
                $builder->where('is_active', 't');
                $builder->where('is_deleted', 'f');
                $query = $builder->update($data);
            } else {
                //                $this->db->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);
                $builder = $this->db->table('ec_postal_transactions');
                $builder->where('ec_postal_received_id', $ecPostalReceivedId);
                $builder->where('is_active', 't');
                $builder->where('is_deleted', 'f');
                $query = $builder->update($data);
            }
            // echo $ecPostalReceivedId." ".$ec_postal_user_initiated_letter_id." ".$letterPriority." ".$officer; exit;
            //For - receive_then_forward_Case
            $forwardedDakInsertId = $this->saveForwardedDak($ecPostalReceivedId, $ec_postal_user_initiated_letter_id, $letterPriority, $officer);
            if ($forwardedDakInsertId > 0) {
                $this->updateForwardedImages($ecPostalTransaction_id, $forwardedDakInsertId);
            }
        } elseif ($actionType == 2) {
            $data = array(
                'action_taken' => $actionType,
                'action_taken_by' => $usercode,
                'return_reason' => $returnReason,
                'action_taken_on' => date('Y-m-d H:i:s'),
                'last_updated_on' => date('Y-m-d H:i:s'),
                'is_active' => 'f',
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            );

            $dispatched_to_user_type = 'o';
            $dispatchersCode = $dispatchedBy;

            if ($ec_postal_user_initiated_letter_id > 0) {
                $this->db->where('ec_postal_user_initiated_letter_id', $ec_postal_user_initiated_letter_id)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);

                $data = array(
                    'ec_postal_user_initiated_letter_id' => $ec_postal_user_initiated_letter_id,
                    'dispatched_to_user_type' => $dispatched_to_user_type,
                    'dispatched_to' => $dispatchersCode,
                    'dispatched_by' => $usercode,
                    'dispatched_on' => date('Y-m-d H:i:s'),
                    'is_active' => 't',
                    'is_forwarded' => 't',
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()
                );
            } else {
                $this->db->where('ec_postal_received_id', $ecPostalReceivedId)->where('is_active', 't')->where('is_deleted', 'f')->update('ec_postal_transactions', $data);

                $data = array(
                    'ec_postal_received_id' => $ecPostalReceivedId,
                    'dispatched_to_user_type' => $dispatched_to_user_type,
                    'dispatched_to' => $dispatchersCode,
                    'dispatched_by' => $usercode,
                    'dispatched_on' => date('Y-m-d H:i:s'),
                    'is_active' => 't',
                    'is_forwarded' => 't',
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()
                );
            }

            $this->db->insert('ec_postal_transactions', $data);
            $insertId = $this->db->insert_id();
            if ($insertId > 0) {
                $this->updateForwardedImages($ecPostalTransaction_id, $insertId);
            }
            //echo $this->db->last_query();
        }
        if ($isADCard == 1) {
            //Update ref_letter_status_id in ec_postal_dispatch
            $dataForDispatch = array(
                'ref_letter_status_id' => 8, // 8 for AD/Letter Received by Section/Concerned
                'usercode' => $usercode,
                'updated_on' => date('Y-m-d H:i:s'),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP()
            );
            //            $this->db->where('id', $ecPostalDispatchId)->update('ec_postal_dispatch', $dataForDispatch);
            $builder = $this->db->table('ec_postal_dispatch');
            $builder->where('id', $ecPostalDispatchId);
            $query = $builder->update($dataForDispatch);
            //            if($query) {
            //                return 1;
            //            }else
            //            {
            //                return 0;
            //            }
            if ($query) {
                // Enter a row with status of AD send to Section in ec_postal_dispatch_transaction
                $dataForDispatchTransactions = array(
                    'ec_postal_dispatch_id' => $ecPostalDispatchId,
                    'ref_letter_status_id' => 8,
                    'usercode' => $usercode,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()
                );
                $builder = $this->db->table('ec_postal_dispatch_transactions');

                $query = $builder->insert($dataForDispatchTransactions);
            }
        }
        //        return $this->db->affected_rows();
    }


    function getReceivedBySectionDak($ids)
    {
        //print_r($ids);
        $ecPostalReceivedId = [];
        $ecPostalTransactionId = [];
        foreach ($ids as $id) {
            $id = explode('#', $id);
            $ecPostalReceivedId[] = $id[0];
            $ecPostalTransactionId[] = $id[1];
        }
        $sql = "select ecpd.id,ecpd.ec_case_id,sender_name,address,concat(ecpd.diary_no,'/',ecpd.diary_year) as diary,ecpd.remarks,
              ecpd.subject,case when postal_no is not null then postal_no else case when letter_no is not null then letter_no end end as postal_number,
              case when postal_date is not null then postal_date else case when letter_date is not null then letter_date end end as postal_date,
              (select postal_type_description from master.ref_postal_type rpt where rpt.id=ref_postal_type_id) as postal_type,
              (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
              case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
              case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) else ecpd.postal_addressee end end end) as address_to
              ,ec.diary_no as diary_number,ec.reg_no_display,
              (select concat(name ,'(',empid,')') from master.users where usercode=dispatched_by) as dispatched_by,dispatched_on,
              (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.is_forwarded,
              case when ept.action_taken=1 then 'Received' else case when ept.action_taken=2 then 'Returned' else case when ept.action_taken=3 then 'Forwarded' else '' end end end as action_taken,ept.return_reason
              from ec_postal_received ecpd left join main ec on ecpd.ec_case_id=ec.diary_no
              left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_received_id
              where ept.id in (" . implode(',', $ecPostalTransactionId) . ") and ecpd.is_deleted=? order by ecpd.diary_no";
        $query = $this->db->query($sql, array('f'));
        //        $this->db->last_query();exit;
        if ($query->getNumRows() >= 1) {
            //            print_r($query->getResultArray());die;
            $result = $query->getResultArray();
            return $result;
        } else {
            return 0;
        }
    }

    function getInitiatedReceivedBySectionDak($ids)
    {
        //print_r($ids);
        $ecPostalReceivedId = [];
        $ecPostalTransactionId = [];
        foreach ($ids as $id) {
            $id = explode('#', $id);
            $ecPostalReceivedId[] = $id[0];
            $ecPostalTransactionId[] = $id[1];
        }
        $sql = "select ecpd.id,ecpd.letter_no,
              ecpd.letter_subject,
              (case when ept.dispatched_to_user_type='s' then (select section_name from master.usersection where id=ept.dispatched_to) else 
              case when ept.dispatched_to_user_type = 'o' then (select concat(name,' (',empid,') ') from master.users where usercode=ept.dispatched_to) else 
              case when ept.dispatched_to_user_type = 'j' then (select jname from master.judge where jcode=ept.dispatched_to) end end end) as address_to,
              (select concat(name ,'(',empid,')') from master.users where usercode=dispatched_by) as dispatched_by,dispatched_on,
              (select concat(name ,'(',empid,')') from master.users where usercode=ept.action_taken_by) as action_taken_by,ept.action_taken_on,ept.is_forwarded,
              case when ept.action_taken=1 then 'Received' else case when ept.action_taken=2 then 'Returned' else case when ept.action_taken=3 then 'Forwarded' else '' end end end as action_taken,ept.return_reason
              from ec_postal_user_initiated_letter ecpd left join ec_postal_transactions ept on ecpd.id=ept.ec_postal_user_initiated_letter_id
              where ept.id in (" . implode(',', $ecPostalTransactionId) . ") and ecpd.is_deleted=? order by ecpd.id DESC";
        $query = $this->db->query($sql, array('f'));
        //        echo $query->getNumRows();die;
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return 0;
        }
    }

    function updateForwardedImages($ecPostalTransaction_id, $forwardedDakInsertId)
    {

        $builder = $this->db->table('ec_forward_letter_postal_transactions');
        $builder->select('image_id');
        $builder->where("transactions_id", $ecPostalTransaction_id);
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();

        if ($query->getNumRows() >= 1) {
            $data["imageids"] = $query->getResultArray();

            foreach ($data["imageids"] as $datum) {
                $image_id = $datum['image_id'];
                $columnInsertData = array(
                    'transactions_id' => $forwardedDakInsertId,
                    'image_id' => $image_id,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'create_modify' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP()

                );

                $builder = $this->db->table('ec_forward_letter_postal_transactions');

                $query = $builder->insert($columnInsertData);
            }
        } else {
            return [];
        }
    }


    function saveForwardedDak($ecPostalReceivedId, $initiatedDakInsertId, $letterPriority, $officer)
    {

        $usercode = session()->get('login')['usercode'];
        $query = "";

        $dispatched_to_user_type = 'o';
        $dispatched_to = $officer;

        $dispatched_on = date('Y-m-d H:i:s');

        if ($ecPostalReceivedId != "") {
            $queryUpdateInactive = "update ec_postal_transactions set is_active='f' where ec_postal_received_id=$ecPostalReceivedId";
            $this->db->query($queryUpdateInactive);

            $query = "insert into ec_postal_transactions set ec_postal_received_id=$ecPostalReceivedId, dispatched_to_user_type='" . $dispatched_to_user_type . "',
        dispatched_to=$dispatched_to, dispatched_by=$usercode, dispatched_on='" . $dispatched_on . "', is_forwarded='t', is_active='t',  letterPriority='" . $letterPriority . "'";
        } elseif ($initiatedDakInsertId != "") {
            $queryUpdateInactive = "update ec_postal_transactions set is_active='f' where ec_postal_user_initiated_letter_id=$initiatedDakInsertId";
            $this->db->query($queryUpdateInactive);

            $query = "insert into ec_postal_transactions set ec_postal_user_initiated_letter_id=$initiatedDakInsertId, dispatched_to_user_type='" . $dispatched_to_user_type . "',
        dispatched_to=$dispatched_to, dispatched_by=$usercode, dispatched_on='" . $dispatched_on . "', is_forwarded='t', is_active='t', letterPriority='" . $letterPriority . "'";
        }

        $this->sendForwardedLetterIntimationSMS($letterPriority, $officer);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    function sendForwardedLetterIntimationSMS($receivingUser, $letterPriority = 0)
    {
        $toMobile = '';
        $priorityText = '';
        if ($letterPriority == 0) {
            $priorityText = 'A letter';
        } elseif ($letterPriority == 1) {
            $priorityText = 'An Urgent But Not Important letter';
        } elseif ($letterPriority == 2) {
            $priorityText = 'An Important But Not Urgent letter';
        } elseif ($letterPriority == 3) {
            $priorityText = 'An Urgent And Important letter';
        }
        $SMSText = $priorityText . ' has been forwarded to you internally. Please login to RnI section in ICMIS to check for details';

        $receiving_employee_details_url = 'http://10.25.78.92:81/services/employee_details.php?employeeId=' . $receivingUser;
        $json = file_get_contents($receiving_employee_details_url);
        $obj = json_decode($json, true);

        $tmpArr = array();
        foreach ($obj as $sub) {
            $tmpArr[] = $sub['mobileNumbers'];
        }

        $mobile_numbers = implode(',', $tmpArr);

        if ($mobile_numbers == null or $mobile_numbers == '') {
            return;
        } else {
            $toMobile = $mobile_numbers;
        }
        //$toMobile = "8860251148";
        $sms_text = rawurlencode($SMSText);
        //$sms_url = 'http://10.25.78.60/eAdminSCI/a-push-sms-gw?mobileNos=' . $toMobile . '&message=' . $sms_text . '&typeId=30&myUserId=NIC001001&myAccessId=root&authCode=sdjkfgbsjh$1232_12nmnh';

        $sms_url = 'http://10.25.78.60/eAdminSCI/a-push-sms-gw?mobileNos=' . $toMobile . '&message=' . $sms_text . '&typeId=30&myUserId=NIC001001&myAccessId=root';

        $sms_response = file_get_contents($sms_url);
        $json = json_decode($sms_response);
        if ($json->{'responseFlag'} == "success") {
            //echo 'Success: Forwarded letter intimation SMS sent.';
            $this->insert_ForwardedSMSLogs($toMobile, $sms_text, $receivingUser, 'Success');
        } else {
            //echo 'Error:  Forwarded letter intimation SMS could not be sent.';
            $this->insert_ForwardedSMSLogs($toMobile, $sms_text, $receivingUser, 'Error');
        }
    }

    function insert_SMSLogs($toMobile, $smsText, $userId, $sendStatus)
    {
        $updatedFromSystem = $_SERVER['REMOTE_ADDR'];
        $sql2 = "insert into paper_book_sms_log(mobile,msg,send_by,send_date_time,ip_address,send_status)
                  values ('$toMobile','" . rawurldecode($smsText) . "',$userId,now(),'$updatedFromSystem','$sendStatus')";
        $this->db->query($sql2);
        $NoRowAffected = $this->db->affected_rows();
        return $NoRowAffected;
    }



    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> END Received DAK REPORT FUNCTION<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */


    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> ECOPY FUNCTION<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<  */

    function get_envelope_data()
    {
        $builder = $this->db->table('post_bar_code_mapping p');
        $builder->select('p.envelope_weight, p.barcode, p.consumed_on, c.application_receipt, c.postal_fee, name, mobile, address, application_number_display, crn, email');
        $builder->join('copying_order_issuing_application_new c', 'p.copying_application_id = c.id', 'INNER');
        $builder->join('post_envelope_movement e', "e.barcode = p.barcode and e.display = 'Y'", 'LEFT');
        $builder->where("e.id is null and p.is_consumed = '1' and p.is_deleted = '0'");
        $builder->orderBy('p.ent_time');
        $query = $builder->get();
        // $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return false;
        }
    }


    function envelope_receive_data($barcode, $usercode, $section)
    {
        if (strlen(trim($_POST['barcode'])) >= 12) {

            $data = array(
                'barcode' => $barcode,
                'received_section' => $usercode,
                'received_by' => $section,
                'received_on' => date('Y-m-d H:i:s'),
                'updated_on' => date('Y-m-d H:i:s'),
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_by' => $usercode,
                'updated_by_ip' => getClientIP()

            );
            $builder = $this->db->table('post_envelope_movement');
            $query = $builder->insert($data);
            //            $query = $builder->set($data)->getCompiledInsert('post_envelope_movement');
            //            echo $query;
            //            die;
            //            return $query;
            //        $query=$this->db->getLastQuery();echo (string) $query;exit();
            //            $result = $query->getResultArray();

            if ($query) {
                $return_arr = array("status" => "success");
            } else {
                $return_arr = array("status" => "Error:Not Saved");
            }
        } else {
            $return_arr = array("status" => "Error:Valid Barcode Required");
        }
        // Encoding array in JSON format
        return $return_arr;
    }


    function envelope_report_data($from_date, $to_date)
    {
        $builder = $this->db->table('post_bar_code_mapping p');
        $builder->select('p.envelope_weight, p.barcode, p.consumed_on, c.application_receipt, c.postal_fee, c.name, c.mobile, c.address, c.application_number_display,
         c.crn, c.email, u.name as username, u.empid, e.received_on');
        $builder->join('copying_order_issuing_application_new c', 'p.copying_application_id = c.id', 'INNER');
        $builder->join('post_envelope_movement e', "e.barcode = p.barcode and e.display = 'Y'", 'INNER');
        $builder->join('master.users u', 'u.usercode = e.received_by', 'inner');
        $builder->where("p.is_consumed = '1' and p.is_deleted = '0' and date(e.received_on) between '$from_date' and '$to_date' ");
        $builder->orderBy("e.received_on");
        $query = $builder->get();
        //        $query=$this->db->getLastQuery();echo (string) $query;exit();
        $result = $query->getResultArray();

        if ($query->getNumRows() >= 1) {
            return $result;
        } else {
            return [];
        }
    }

    public function get_rep_ack_tal_data($serveCondition, $from_date, $todate, $condition)
    {
        $sql = "select aa.*,bb.s from (SELECT d.id,a.diary_no, process_id, a.name, address, b.name nt_typ, del_type, 
                tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
                sendto_district,sendto_state,nt_type,tal_state,tal_district,dispatch_id,dispatch_dt,station,weight,stamp,
                barcode,dis_remark,dispatch_user_id,d.ack_id,d.ack_user_id,d.serve,d.ser_type,d.ser_date,d.ser_dt_ent_dt,
                da_rec_dt,tentative_section(m.diary_no) as section
                FROM tw_tal_del a
                JOIN master.tw_notice b ON a.nt_type = b.id::text
                JOIN tw_o_r c ON c.tw_org_id = a.id
                JOIN tw_comp_not d ON d.tw_o_r_id = c.id
                join main m on a.diary_no=m.diary_no 
                WHERE  a.display = 'Y'
                AND print =1
                $serveCondition
                AND b.display = 'Y'
                AND c.display = 'Y'
                AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt is not null and ser_dt_ent_dt is not null
                AND TO_CHAR(ser_dt_ent_dt, 'YYYY-MM-DD')  between '$from_date' and '$todate' $condition  order by ack_id,ser_dt_ent_dt) aa join 
                    (select count(a.id) s,diary_no,
                rec_dt,ack_id from tw_tal_del a  JOIN tw_o_r c ON c.tw_org_id = a.id
                JOIN tw_comp_not d ON d.tw_o_r_id = c.id 
                WHERE print='1' 
                 $serveCondition
                and a.display='Y'  AND c.display = 'Y' AND d.display = 'Y' 
                 AND TO_CHAR(ser_dt_ent_dt, 'YYYY-MM-DD')  between 
               '$from_date' and '$todate' and dispatch_id!=0 and dispatch_dt is not null
                    and ser_dt_ent_dt is not null
                    group by diary_no,ack_id,a.rec_dt) bb on aa.diary_no=bb.diary_no and aa.ack_id=bb.ack_id";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    function get_address_state_list()
    {
        $builder = $this->db->table("master.state");
        $builder->select("id_no as cmis_state_id, name as agency_state,state_code", false);
        $builder->WHERE('district_code =0 AND sub_dist_code =0 AND village_code =0 AND display = \'Y\' AND sci_state_id !=0', NULL, false);
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $result = $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_dispatch_rep_data($nature, $txt_frmdate, $txt_todate, $ddlOR, $state, $district, $casetype)
    {
        $sql = "select a.*,b.s from (SELECT d.id,a.diary_no, process_id, a.name, address, b.name nt_typ, del_type, 
                tw_sn_to, copy_type, send_to_type, fixed_for, rec_dt, office_notice_rpt,reg_no_display,
                sendto_district,sendto_state,nt_type,tal_state,tal_district,dispatch_id,dispatch_dt,station,weight,stamp,
                barcode,dis_remark,dispatch_user_id FROM tw_tal_del a JOIN master.tw_notice b ON a.nt_type = b.id :: text JOIN tw_o_r c ON c.tw_org_id = a.id
                JOIN tw_comp_not d ON d.tw_o_r_id = c.id
                join main m on a.diary_no=m.diary_no $nature
                WHERE  a.display = 'Y'
                AND print =1
                AND b.display = 'Y'
                AND c.display = 'Y'
                AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt is not null  
                and date(dispatch_dt) between '$txt_frmdate' and '$txt_todate' $ddlOR $state $district $casetype order by dispatch_id) a join (select count(d.dispatch_id) s,dispatch_id FROM tw_tal_del a
                JOIN master.tw_notice b ON a.nt_type = b.id::text
                JOIN tw_o_r c ON c.tw_org_id = a.id
                JOIN tw_comp_not d ON d.tw_o_r_id = c.id
                join main m on a.diary_no=m.diary_no $nature
                WHERE  a.display = 'Y'
                AND print =1
                AND b.display = 'Y'
                AND c.display = 'Y'
                AND d.display = 'Y' and dispatch_id!=0 and dispatch_dt is not null  
                and date(dispatch_dt) between '$txt_frmdate' and '$txt_todate' $ddlOR $state $district $casetype group by  dispatch_id)  b
                on a.dispatch_id=b.dispatch_id";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }


    public function getRICompleteDetail($id)
{
    $builder = $this->db->table('ec_postal_dispatch epd');
    
    $builder->select([
        'rls.description as current_status',
        'epd.is_case',
        'epd.is_with_process_id',
        'epd.reference_number',
        'epd.id as ec_postal_dispatch_id',
        'epd.process_id',
        'epd.process_id_year',
        "COALESCE(m.reg_no_display, CONCAT(SUBSTRING(CAST(m.diary_no AS TEXT), 1, LENGTH(CAST(m.diary_no AS TEXT)) - 4), '/', RIGHT(CAST(m.diary_no AS TEXT), 4))) AS case_no",
        'epd.diary_no',
        'epd.send_to_name',
        'epd.send_to_address',
        'tn.name as doc_type',
        's.name as state_name',
        'd.name as district_name',
        'epd.pincode',
        'epd.tal_state',
        'epd.tal_district',
        "(SELECT CONCAT(name, '(', empid, ')') FROM master.users WHERE usercode = epd.usercode) AS last_updated_by",
        'epd.updated_on as last_updated_on',
        'us.section_name',
        'epd.serial_number',
        'epd.ref_postal_type_id',
        'epd.postal_charges',
        'epd.weight',
        'epd.waybill_number',
        'epd.usersection_id',
        "(SELECT section_name FROM master.usersection WHERE id = epd.usersection_id) AS send_to_section",
        "(SELECT name FROM master.tw_serve WHERE serve_stage = epd.serve_stage AND serve_type = 0) AS serve_stage",
        "(SELECT name FROM master.tw_serve WHERE id = epd.tw_serve_id) AS serve_type",
        'epd.serve_remarks',
        'rpt.postal_type_description'
    ]);

    // Left joins
    $builder->join('main m', 'CAST(epd.diary_no AS BIGINT) = m.diary_no', 'left');
    $builder->join('master.tw_notice tn', 'epd.tw_notice_id = tn.id', 'left');
    $builder->join('master.usersection us', 'epd.usersection_id = us.id', 'left');
    $builder->join('master.ref_letter_status rls', 'epd.ref_letter_status_id = rls.id', 'left');
    $builder->join('master.ref_postal_type rpt', 'epd.ref_postal_type_id = rpt.id', 'left');
    $builder->join('master.state s', 's.id_no = epd.tal_state', 'left');
    $builder->join('master.state d', 'd.id_no = epd.tal_district', 'left');

    // Where condition
    $builder->where('epd.id', $id);

    // Fetch and return the result
    $query = $builder->get();
    return $query->getRow();
}



    public function getDispatchTransactions($id)
    {
        $builder = $this->db->table('ec_postal_dispatch_transactions epdt');
        
        $builder->select([
            'rls.description as letter_stage',
            'u.name',
            'u.empid',
            'epdt.updated_on',
            'us.section_name',
            'epdt.remarks'
        ]);

        // Joins
        $builder->join('master.ref_letter_status rls', 'epdt.ref_letter_status_id = rls.id', 'inner');
        $builder->join('master.users u', 'epdt.usercode = u.usercode', 'inner');
        $builder->join('master.usersection us', 'u.section = us.id', 'left');

        // Where condition
        $builder->where('epdt.ec_postal_dispatch_id', $id);

        // Order by
        $builder->orderBy('epdt.ref_letter_status_id', 'ASC');

        // Execute and return results
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function getDispatchDetails($tot_id)
{
    $builder = $this->db->table('tw_tal_del a');
    
    $builder->select([
        'd.id',
        'a.diary_no',
        'process_id',
        'a.name',
        'address',
        'b.name AS nt_typ',
        'del_type',
        'tw_sn_to',
        'copy_type',
        'send_to_type',
        'fixed_for',
        'rec_dt',
        'office_notice_rpt',
        'reg_no_display',
        'sendto_district',
        'sendto_state',
        'nt_type',
        'tal_state',
        'tal_district',
        'dispatch_id',
        "DATE(dispatch_dt) AS dispatch_dt",
        'weight',
        'stamp',
        'barcode',
        'dis_remark',
        'station'
    ]);

    // Joining Tables
    $builder->join('master.tw_notice b', 'a.nt_type = b.id');
    $builder->join('tw_o_r c', 'c.tw_org_id = a.id');
    $builder->join('tw_comp_not d', 'd.tw_o_r_id = c.id');
    $builder->join('main m', 'a.diary_no = m.diary_no');

    // Where Conditions
    $builder->where('a.display', 'Y');
    $builder->where('b.display', 'Y');
    $builder->where('c.display', 'Y');
    $builder->where('d.display', 'Y');
    $builder->where('dispatch_id !=', 0);
    $builder->where("dispatch_dt IS NOT NULL");
    $builder->where('d.id', $tot_id); 

    // Execute Query
    $query = $builder->get();

    return $query->getResultArray();
}





}