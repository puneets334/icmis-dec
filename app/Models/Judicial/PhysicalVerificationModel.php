<?php
namespace App\Models\Judicial;
use CodeIgniter\Model;

class PhysicalVerificationModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }


    public function getreportDetails(){

        $ucode = $_SESSION['login']['usercode'];    /// Replace 685 with $ucode

                
        $sql= "SELECT array_to_string(array_agg(c.short_description || ' - ' || 
                CASE WHEN SPLIT_PART(new_registration_number, '-', 2) = SPLIT_PART(new_registration_number, '-', -1) 
                    THEN SPLIT_PART(new_registration_number, '-', -1) 
                    ELSE SPLIT_PART(new_registration_number, '-', -2) 
                END || ' / ' || mch.new_registration_year || ' Dt. ' || TO_CHAR((order_date), 'DD-MM-YYYY')),',') AS all_regno,
                a.name AS da_name, a.empid AS da_empid, b.section_name AS da_section_name, active_fil_no, m.diary_no AS dno,
                CONCAT(SUBSTRING((m.diary_no::text), 1, LENGTH((m.diary_no::text)) - 4), '/', SUBSTRING((m.diary_no::text), -4)) AS diary,
                p.avaliable_flag AS is_verify, reg_no_display, CONCAT((pet_name), ' Vs ', (res_name)) AS cause_title, (diary_no_rec_date) AS diary_no_rec_date,
                (active_fil_dt) AS active_fil_dt, (m.diary_no) AS min_diary_no, 
                string_agg(distinct vp.name||'(' ||vp.empid||')'||'-'||vs.section_name,',') AS verified_by, rgs.agency_state, rgc.agency_name 
                FROM main m 
                LEFT JOIN main_casetype_history mch ON mch.diary_no = m.diary_no AND mch.is_deleted = 'f' 
                LEFT JOIN master.casetype c ON c.casecode = mch.ref_new_case_type_id 
                LEFT JOIN physical_verify p ON m.diary_no = p.diary_no AND p.display='Y' 
                LEFT JOIN master.users vp ON p.ucode = vp.usercode 
                LEFT JOIN master.usersection vs ON vp.section = vs.id 
                LEFT JOIN master.users a ON m.dacode = a.usercode 
                LEFT JOIN master.usersection b ON b.id = a.section 
                LEFT JOIN master.ref_agency_state rgs ON rgs.cmis_state_id = m.ref_agency_state_id 
                LEFT JOIN master.ref_agency_code rgc ON rgc.id = m.ref_agency_code_id 
                WHERE c_status='P' AND m.dacode = $ucode 
                GROUP BY m.diary_no, a.name, a.empid, b.section_name, active_fil_no, p.avaliable_flag, reg_no_display, rgs.agency_state, rgc.agency_name,  m.pet_name, m.res_name, m.diary_no_rec_date, m.active_fil_dt 
                order by dno";

        // echo $sql; die;
        $result = $this->db->query($sql);
        $result = $result->getResultArray();
       

        // $builder = $this->db->table('main m');
        // $builder->select("c.short_description  ' - '  
        //         CASE
        //             WHEN SPLIT_PART(new_registration_number, '-', 2) = SPLIT_PART(new_registration_number, '-', -1)
        //                 THEN SPLIT_PART(new_registration_number, '-', -1)
        //             ELSE SPLIT_PART(new_registration_number, '-', -2)
        //         END  ' / '  mch.new_registration_year  ' Dt. '  TO_CHAR(order_date, 'DD-MM-YYYY') AS all_regno,
        //         a.name AS da_name,
        //         a.empid AS da_empid,
        //         b.section_name AS da_section_name,
        //         active_fil_no,
        //         m.diary_no AS dno,
        //         CONCAT(SUBSTRING(m.diary_no::text, 1, LENGTH(m.diary_no::text) - 4), '/', SUBSTRING(m.diary_no::text, -4)) AS diary,
        //         p.avaliable_flag AS is_verify,
        //         reg_no_display,
        //         CONCAT(pet_name, ' Vs ', res_name) AS cause_title,
        //         diary_no_rec_date AS diary_no_rec_date,
        //         active_fil_dt AS active_fil_dt,
        //         m.diary_no AS min_diary_no,
        //         vp.name AS verified_by,
        //         vp.empid,
        //         vs.section_name,
        //         rgs.agency_state,
        //         rgc.agency_name", false);
        // $builder->join('main_casetype_history mch', "mch.diary_no = m.diary_no AND mch.is_deleted = 'f'", 'LEFT');
        // $builder->join('master.casetype c', 'c.casecode = mch.ref_new_case_type_id', 'LEFT');
        // $builder->join('physical_verify p', "m.diary_no = p.diary_no AND p.display = 'Y'", 'LEFT');
        // $builder->join('master.users vp', 'p.ucode = vp.usercode', 'LEFT');
        // $builder->join('master.usersection vs', 'vp.section = vs.id', 'LEFT');
        // $builder->join('master.users a', 'm.dacode = a.usercode', 'LEFT');
        // $builder->join('master.usersection b', 'b.id = a.section', 'LEFT');
        // $builder->join('master.ref_agency_state rgs', 'rgs.cmis_state_id = m.ref_agency_state_id', 'LEFT');
        // $builder->join('master.ref_agency_code rgc', 'rgc.id = m.ref_agency_code_id', 'LEFT');
        // $builder->where('c_status', 'P');
        // $builder->where('m.dacode', '685');
        // $builder->groupBy('m.diary_no, a.name, a.empid, b.section_name, active_fil_no,
        //             p.avaliable_flag, reg_no_display, vp.name, vp.empid,
        //             vs.section_name, rgs.agency_state, rgc.agency_name, c.short_description, mch.new_registration_year,
        //             new_registration_number, order_date, m.pet_name, m.res_name, m.diary_no_rec_date, m.active_fil_dt');
        // $builder->orderBy("CASE WHEN active_fil_no = '' THEN 2 ELSE 1 END");
        // $builder->orderBy('EXTRACT(YEAR FROM active_fil_dt)');
        // $builder->orderBy('SUBSTRING(m.diary_no::text, -4)');
        // $builder->orderBy('LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4)::INTEGER');
       
        // // $queryString = $builder->getCompiledSelect();
        // // echo $queryString;
        // // exit();

        // $query = $builder->get();
        // $result = $query1->getResultArray();


        // echo "<pre>";
        // print_r($result); die;

        $dataArr = [];
        if(!empty($result)){
            $dataArr['total_cases'] = count($result);
            $dataArr['result'] = $result;
        }

        return $dataArr;

    }


    public function wrong_updated_get($data){

        $datares = [];

        // $sql = "SELECT  FROM main m
        //     where m.diary_no = '" . $_REQUEST['dno'] . "' and m.c_status = 'P' ";
        // $chk_avl = mysql_query($sql) or die(__LINE__ . '->' . mysql_error());
        $builder1 = $this->db->table("main m");
        $builder1->select("reg_no_display, m.diary_no, diary_no_rec_date, fil_dt, active_fil_dt, fil_dt_fh, mf_active, c_status,
        pet_name, res_name, pno, rno, active_casetype_id,case_grp");
        $builder1->where('m.diary_no', $data['dno']);
        $builder1->where('m.c_status','P');
        $query1 = $builder1->get();
        $chk_avl = $query1->getResultArray();

        if(!empty($chk_avl)){
            $datares['chk_avl'] = $chk_avl[0];


            // $sql5 = "SELECT mainhead FROM heardt h where h.diary_no = '" . $_REQUEST['dno'] . "' and mainhead = 'F' ";
            // $chk_avl5 = mysql_query($sql5) or die(__LINE__ . '->' . mysql_error());
            $builder2 = $this->db->table("heardt h");
            $builder2->select("mainhead");
            $builder2->where('h.diary_no', $data['dno']);
            $builder2->where('mainhead','F');
            $query2 = $builder2->get();
            $chk_avl5 = $query2->getResultArray();
            $datares['chk_avl5'] = !empty($chk_avl) ? $chk_avl[0] : [];
            

            // $sql5 = "SELECT stagename, h.mainhead FROM heardt h inner join subheading s on h.subhead = s.stagecode where h.diary_no = '" . $_REQUEST['dno'] . "' and mainhead = 'M' ";
            // $chk_avl5 = mysql_query($sql5) or die(__LINE__ . '->' . mysql_error());
            $builder3 = $this->db->table("heardt h");
            $builder3->select("stagename, h.mainhead");
            $builder3->join('master.subheading s', 'h.subhead = s.stagecode', 'INNER');
            $builder3->where('h.diary_no', $data['dno']);
            $builder3->where('mainhead','M');
            $query3 = $builder3->get();
            $chk_avl55 = $query3->getResultArray();            
            $datares['chk_avl55'] = !empty($chk_avl55) ? $chk_avl55[0] : [];            


            // $sql6 = "SELECT sub_name1, sub_name2, sub_name3, sub_name4, category_sc_old FROM mul_category mc inner join submaster s on s.id = mc.submaster_id      where mc.diary_no = '" . $_REQUEST['dno'] . "' and mc.display = 'Y' and subcode1 not in (8888,9999)";
            // $res = mysql_query($sql6) or die(mysql_error());
            $builder4 = $this->db->table("mul_category mc");
            $builder4->select("sub_name1, sub_name2, sub_name3, sub_name4, category_sc_old");
            $builder4->join('master.submaster s', 's.id = mc.submaster_id', 'INNER');
            $builder4->where('mc.diary_no', $data['dno']);
            $builder4->where('mc.display','Y');
            $builder4->whereNotIn('subcode1', ['8888', '9999']);
            $query4 = $builder4->get();
            $res4 = $query4->getResultArray();
            $datares['res6'] = $res4;
            


            // $sql7="select id,subcode1,sub_name1  from submaster where (flag_use='S' OR flag_use='L') and display='Y' and match_id!=0 and flag='S' group by subcode1 order by subcode1";
            // $res = mysql_query($sql7) or die(mysql_error());        
            $builder5 = $this->db->table('master.submaster');
            $builder5->select('id, subcode1, sub_name1');
            $builder5->where('flag_use', 'S');
            $builder5->orWhere('flag_use', 'L');
            $builder5->where('display', 'Y');
            $builder5->where('match_id !=', 0);
            $builder5->where('flag', 'S');
            $builder5->groupBy('subcode1, id, sub_name1');
            $builder5->orderBy('subcode1');
            $query5 = $builder5->get();
            $res5 = $query5->getResultArray();
            $datares['res5'] = $res5;
            

            // $sql8 = "select diary_no, id, c.short_description,
            //             SUBSTRING_INDEX(SUBSTRING_INDEX(new_registration_number, '-', 2), '-', -1 ) as split_caseno1,
            //             SUBSTRING_INDEX(new_registration_number, '-', -1) as split_caseno2,
            //             new_registration_year, order_date
            //             from main_casetype_history m
            //             left join casetype c on c.casecode = cast(substring(new_registration_number,1,2) as unsigned)
            //             where m.diary_no='" . $data['dno'] . "'  and m.is_deleted = 'f' order by order_date";
            // $res = mysql_query($sql8) or die(mysql_error());
            // " SELECT diary_no, id, c.short_description, SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', -1) AS split_caseno1, SPLIT_PART(new_registration_number, '-', -1) AS split_caseno2, new_registration_year, order_date FROM main_casetype_history m LEFT JOIN master.casetype c ON c.casecode = CAST(SUBSTRING(new_registration_number FROM 1 FOR 2) AS INTEGER) WHERE m.diary_no = '16892023' AND m.is_deleted = 'f' ORDER BY order_date;"
            $builder6 = $this->db->table("main_casetype_history m");
            $builder6->select("diary_no, id, c.short_description, SPLIT_PART(SPLIT_PART(new_registration_number, '-', 2), '-', -1) AS split_caseno1, SPLIT_PART(new_registration_number, '-', -1) AS split_caseno2, new_registration_year, order_date");
            $builder6->join('master.casetype c', 'c.casecode = CAST(SUBSTRING(new_registration_number FROM 1 FOR 2) AS INTEGER)', 'LEFT');
            $builder6->where('m.diary_no', $data['dno']);
            $builder6->where('m.is_deleted','f');
            $builder6->orderBy('order_date');
            $query6 = $builder6->get();
            $res6 = $query6->getResultArray();
            $datares['res_reg_form'] = $res6;




            // $sql9="select p.sr_no, p.pet_res,p.ind_dep, p.partyname, p.sonof,p.prfhname, p.age,p.sex,p.caste, p.addr1, p.addr2,
            // p.pin, p.state, p.city,p.email, p.contact AS mobile,
            // p.deptcode,s1.Name as state_name,s2.Name as district_name,
            // auto_generated_id
            //   FROM party p left join state s1 on p.state=s1.id_no
            //   left join state s2 on p.city=s2.id_no
            // INNER JOIN main m ON  m.diary_no=p.diary_no  and pflag='P' and pet_res in ('P','R') where m.diary_no='" . $_REQUEST['dno'] . "'  order by p.pet_res,p.sr_no";
            // $result_party = mysql_query($sql9) or die("Errror: " . __LINE__ . mysql_error() . " party");
            // $builder7 = $this->db->table('party p');
            // $builder7->select('p.sr_no, p.pet_res, p.ind_dep, p.partyname, p.sonof, p.prfhname, p.age, p.sex, p.caste, p.addr1, p.addr2, p.pin, p.state, p.city, p.email, p.contact AS mobile, p.deptcode, s1.Name as state_name, s2.Name as district_name, auto_generated_id');
            // $builder7->join('master.state s1', 'p.state=s1.id_no::text', 'left');
            // $builder7->join('master.state s2', 'p.city=s2.id_no::text', 'left');
            // $builder7->join('main m', "m.diary_no = p.diary_no AND p.pflag = 'P' AND p.pet_res IN ('P', 'R')", 'inner');
            // $builder7->where('m.diary_no', $data['dno']);
            // $builder7->orderBy('p.pet_res, p.sr_no');
            // // $queryString = $builder7->getCompiledSelect();
            // // echo $queryString;
            // // exit();
            // $query7 = $builder7->get();
            // $result_party = $query7->getResultArray();
            $dno= $data['dno'];
            $sql11 = "SELECT p.sr_no, p.pet_res,p.ind_dep, p.partyname, p.sonof,p.prfhname, p.age,p.sex,p.caste, p.addr1, p.addr2,
                    p.pin, p.state, p.city,p.email, p.contact AS mobile, p.deptcode,s1.name as state_name,s2.name as district_name,
                    auto_generated_id
                    FROM party p 
                        left join master.state s1 on p.state::text = s1.id_no::text
                        left join master.state s2 on p.city::text = s2.id_no::text
                        INNER JOIN main m ON  m.diary_no=p.diary_no  and pflag='P' and pet_res in ('P','R') 
                where m.diary_no= $dno  order by p.pet_res,p.sr_no";
            $result_party = $this->db->query($sql11);
            $result_party = $result_party->getResultArray();
            $datares['result_party'] = $result_party;


            // $sql_listing_status = "select * from heardt h inner join main m on h.diary_no=m.diary_no where m.c_status='P' and h.diary_no='" . $_REQUEST['dno'] . "'";
            // $result_listing_status = mysql_query($sql_listing_status) or die("Errror: " . __LINE__ . mysql_error() . " party");
            $builder8 = $this->db->table('heardt h');
            $builder8->select('*');
            $builder8->join('main m', 'h.diary_no = m.diary_no ', 'inner');
            $builder8->where('h.diary_no', $data['dno']);
            $builder8->where('m.c_status', 'P');
            $query8 = $builder8->get();
            $result_listing_status = $query8->getResultArray();
            $datares['result_listing_status'] = $result_listing_status;


            // $sql_rgo_default = "select * from rgo_default where fil_no='" . $_REQUEST['dno'] . "'";
            // $result_rgo_default = mysql_query($sql_rgo_default);
            $builder9 = $this->db->table("rgo_default");
            $builder9->select("*");
            $builder9->where('fil_no', $data['dno']);
            $query9 = $builder9->get();
            $result_rgo_default = $query9->getResultArray();
            $datares['result_rgo_default'] = $result_rgo_default;
        

            // $sql_disp = "select * from dispose where diary_no = '" . $_REQUEST['dno'] . "' ";
            // $results_disp = mysql_query($sql_disp) or die("Errror: " . __LINE__ . mysql_error() . $sql_disp);
            $builder10 = $this->db->table("dispose");
            $builder10->select("*");
            $builder10->where('diary_no', $data['dno']);
            $query10 = $builder10->get();
            $results_disp = $query10->getResultArray();
            $datares['results_disp'] = $results_disp;
            

            // $sql_ian = "select a.docnum,a.docyear,a.ent_dt,b.docdesc, a.other1
            //     from docdetails a, docmaster b where a.doccode=b.doccode and a.doccode1=b.doccode1 and iastat = 'P' and
            //     a.diary_no = '" . $_REQUEST['dno'] . "' and a.doccode=8 and a.display='Y' and b.display='Y' order by ent_dt";
            // $results_ian = mysql_query($sql_ian) or die("Errror: " . __LINE__ . mysql_error() . $sql_ian);
            $builder11 = $this->db->table('docdetails a');
            $builder11->select('a.docnum, a.docyear, a.ent_dt, b.docdesc, a.other1');
            $builder11->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'inner');
            $builder11->where('a.iastat', 'P');
            $builder11->where('a.diary_no', $data['dno']);
            $builder11->where('a.doccode', 8);
            $builder11->where('a.display', 'Y');
            $builder11->where('b.display', 'Y');
            $builder11->orderBy('a.ent_dt');
            $query11 = $builder11->get();
            $results_ian = $query11->getResultArray();
            $datares['results_ian'] = $results_ian;


            // $act = "SELECT a.act, group_concat( b.section ) section, act_name FROM act_main a LEFT JOIN act_section b ON a.id = b.act_id JOIN act_master c ON c.id = a.act WHERE diary_no = '". $_REQUEST['dno']."' AND a.display = 'Y' AND b.display = 'Y' AND c.display = 'Y' GROUP BY act";
            // $act = mysql_query($act) or die("Error: " . __LINE__ . mysql_error());       
            $builder12 = $this->db->table('act_main a');
            $builder12->select("a.act, STRING_AGG(b.section, ', ') AS section, c.act_name");
            $builder12->join('master.act_section b', 'a.id = b.act_id', 'left');
            $builder12->join('master.act_master c', 'c.id = a.act', 'inner');
            $builder12->where('a.diary_no', $data['dno']);
            $builder12->where('a.display', 'Y');
            $builder12->where('b.display', 'Y');
            $builder12->where('c.display', 'Y');
            $builder12->groupBy('a.act, c.act_name');
            // $queryString = $builder12->getCompiledSelect();
            // echo $queryString;
            // exit();
            $query12 = $builder12->get();
            $act = $query12->getResultArray();
            $datares['act'] = $act;


            // $sql8="select * from act_master where display='Y' and act_name is not null and act_name!='' order by act_name";
            // $res = mysql_query($sql8) or die(mysql_error());
            $builder13 = $this->db->table("master.act_master");
            $builder13->select("*");
            $builder13->where('display', 'Y');
            $builder13->where('act_name is NOT NULL', NULL, FALSE);
            $builder13->where('act_name !=', '');
            $builder13->orderBy('act_name');
            $query13 = $builder13->get();
            $res13 = $query13->getResultArray();
            $datares['res13'] = $res13;



            /////  get_earlier_court.php //////            
            $builder14 = $this->db->table('lowerct a');
            $builder14->select("
                lct_dec_dt,
                lct_judge_name,
                lctjudname2,
                lctjudname3,
                l_dist,
                ct_code,
                l_state,
                name,
                brief_desc AS desc1,
                sub_law AS usec2,
                lct_judge_desg,
                CASE
                    WHEN ct_code = 3 AND l_state = 490506 THEN
                        (SELECT court_name
                        FROM master.state s
                        LEFT JOIN master.delhi_district_court d ON s.state_code = d.state_code AND s.district_code = d.district_code
                        WHERE s.id_no = a.l_dist AND s.display = 'Y')
                    WHEN ct_code = 3 THEN
                        (SELECT name
                        FROM master.state s
                        WHERE s.id_no = a.l_dist AND s.display = 'Y')
                    ELSE
                        (SELECT agency_name
                        FROM master.ref_agency_code c
                        WHERE c.cmis_state_id = a.l_state AND c.id = a.l_dist AND c.is_deleted = 'f')
                END AS agency_name,
                crimeno,
                crimeyear,
                polstncode,
                (SELECT policestndesc
                FROM master.police p
                WHERE p.policestncd = a.polstncode AND p.display = 'Y' AND p.cmis_state_id = a.l_state AND p.cmis_district_id = a.l_dist
                    AND a.crimeno != '' AND a.crimeno != '0') AS policestndesc,
                authdesc,
                l_inddep,
                l_orgname,
                l_ordchno,
                l_iopb,
                l_iopbn,
                l_org,
                lct_casetype,
                lct_caseno,
                lct_caseyear,
                CASE
                    WHEN ct_code = 4 THEN
                        (SELECT skey
                        FROM master.casetype ct
                        WHERE ct.display = 'Y' AND ct.casecode = a.lct_casetype)
                    ELSE
                        (SELECT type_sname
                        FROM master.lc_hc_casetype d
                        WHERE d.lccasecode = a.lct_casetype AND d.display = 'Y')
                END AS type_sname,
                a.lower_court_id,
                is_order_challenged,
                full_interim_flag,
                judgement_covered_in,
                vehicle_code,
                vehicle_no,
                code,
                post_name,
                cnr_no,
                ref_court,
                ref_case_type,
                ref_case_no,
                ref_case_year,
                ref_state,
                ref_district,
                gov_not_state_id,
                gov_not_case_type,
                gov_not_case_no,
                gov_not_case_year,
                gov_not_date,
                relied_court,
                relied_case_type,
                relied_case_no,
                relied_case_year,
                relied_state,
                relied_district,
                transfer_case_type,
                transfer_case_no,
                transfer_case_year,
                transfer_state,
                transfer_district,
                transfer_court
            ");
            $builder14->join('master.state b', "a.l_state = b.id_no AND b.display = 'Y'", 'left');
            $builder14->join('main e', 'e.diary_no = a.diary_no', 'inner');
            $builder14->join('master.authority f', "f.authcode = a.l_iopb AND f.display = 'Y'", 'left');
            $builder14->join('master.rto h', "h.id = a.vehicle_code AND h.display = 'Y'", 'left');
            $builder14->join('master.post_t i', "i.post_code = a.lct_judge_desg AND i.display = 'Y'", 'left');
            $builder14->join('relied_details rd', "rd.lowerct_id = a.lower_court_id AND rd.display = 'Y'", 'left');
            $builder14->join('transfer_to_details t_t', "t_t.lowerct_id = a.lower_court_id AND t_t.display = 'Y'", 'left');
            $builder14->where('a.diary_no', $data['dno']);
            $builder14->where('lw_display', 'Y');
            $builder14->orderBy('a.lower_court_id');

            $query14 = $builder14->get();
            $res14 = $query14->getResultArray();
            if(!empty($res14)){
                $datrs14 = [];
                // echo "<pre>";
                // print_r($res14); die;
                foreach ($res14 as $key => $row5) {
                    // echo "<pre>"; print_r($row5); die;
                    // $chk_lower_jud="Select judge_id from lowerct_judges where lct_display='Y' and lowerct_id='".$row5['lower_court_id']."'";
                    // $chk_lower_jud=mysql_query($chk_lower_jud) or die("Error: ".__LINE__.mysql_error());
                    $builder1 = $this->db->table("lowerct_judges");
                    $builder1->select("judge_id");
                    $builder1->where('lct_display', 'Y');
                    $builder1->where('lowerct_id', $row5['lower_court_id']);
                    $query1 = $builder1->get();
                    $chk_lower_jud = $query1->getResultArray();

                    $jud_name='';
                    $jud_id='';
                    if(count($chk_lower_jud)>0){
                        
                        foreach ($chk_lower_jud as $row) {
                            // echo "<pre>"; print_r($row); die;
                            // while ($row = mysql_fetch_array($chk_lower_jud)){
                            if($row5['ct_code']=='4'){
                                // $jud_name_l="Select first_name,sur_name from judge where display='Y' and jcode='$row[judge_id]'";
                                // $jud_name_l=mysql_query($jud_name_l) or die("Error: ".__LINE__.mysql_error());
                                $builder2 = $this->db->table("judge");
                                $builder2->select("first_name,sur_name");
                                $builder2->where('display', 'Y');
                                $builder2->where('jcode', $row['judge_id']);
                                $query2 = $builder2->get();
                                $jud_name_l = $query2->getResultArray();
                            }else{
                                // $jud_name_l="Select first_name,sur_name from org_lower_court_judges where  is_deleted='f' and id='$row[judge_id]'";
                                // $jud_name_l=mysql_query($jud_name_l) or die("Error: ".__LINE__.mysql_error());
                                $builder2 = $this->db->table("master.org_lower_court_judges");
                                $builder2->select("first_name,sur_name");
                                $builder2->where('is_deleted', 'f');
                                $builder2->where('id', $row['judge_id']);
                                $query2 = $builder2->get();
                                $jud_name_l = $query2->getResultArray();
                            }
                           
                            if(!empty($jud_name_l)){
                                $res_jud_name_l= $jud_name_l[0];
                                if($jud_name==''){
                                    $jud_name .= $res_jud_name_l['first_name'].' '.$res_jud_name_l['sur_name'];
                                    // $jud_id= $row['judge_id'];
                                }else{
                                    $jud_name .= $jud_name.', '.$res_jud_name_l['first_name'].' '.$res_jud_name_l['sur_name'];
                                    // $jud_id = $jud_id.','.$row['judge_id'];
                                } 
                            }                                                       
                        }
                    }
                    // $output.= $jud_name;
                    // $datrs14['jud_name'] = $jud_name;

                    $ref_court = '';
                    $ref_state = '';
                    $ref_district = '';
                    $ref_case_type = '';
                    if($row5['ref_court']!=0){
                        // $r_court="Select court_name from m_from_court where id='$row5[ref_court]' and display='Y'";
                        // $r_court=mysql_query($r_court) or die("Error: ".__LINE__.mysql_error());
                        // $res_court=mysql_result($r_court,0);
                        $builder3 = $this->db->table("master.m_from_court");
                        $builder3->select("court_name");
                        $builder3->where('display', 'Y');
                        $builder3->where('id', $row5['ref_court']);
                        $query3 = $builder3->get();
                        $res_court = $query3->getResultArray();
                        if(!empty($res_court)){
                            $res_court = $res_court[0]['court_name'];
                        }else{
                            $res_court = '-';
                        }
                        $ref_court = $res_court;

                        // $r_state="Select name from state where id_no='$row5[ref_state]' and display='Y'";
                        // $r_state=mysql_query($r_state) or die("Error: ".__LINE__.mysql_error());
                        // $res_state=mysql_result($r_state,0);                        
                        $builder4 = $this->db->table("master.state");
                        $builder4->select("name");
                        $builder4->where('display', 'Y');
                        $builder4->where('id_no', $row5['ref_state']);
                        $query4 = $builder4->get();
                        $res_state = $query4->getResultArray();
                        if(!empty($res_state)){
                            $res_state = $res_state[0]['name'];
                        }else{
                            $res_state = '-';
                        }
                        $ref_state = $res_state;

                        if($row5['ref_court']=='3'){
                            // $r_district="Select name from state where id_no='$row5[ref_district]' and display='Y' ";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.state");
                            $builder5->select("name");
                            $builder5->where('display', 'Y');
                            $builder5->where('id_no', $row5['ref_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['name'];
                            }else{
                                $res_district = '-';
                            }
                            $ref_district = $res_district;
                        }else{
                            // $r_district="SELECT agency_name FROM ref_agency_code c WHERE c.id='$row5[ref_district]' AND is_deleted = 'f'";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.ref_agency_code");
                            $builder5->select("agency_name");
                            $builder5->where('is_deleted', 'f');
                            $builder5->where('id', $row5['ref_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['agency_name'];
                            }else{
                                $res_district = '-';
                            }
                            $ref_district = $res_district;
                        }
                        
                        $case_type='';
                        if($row5['ref_court']=='4'){
                            // $case_type="SELECT skey FROM casetype  WHERE display = 'Y' AND casecode = '$row5[ref_case_type]'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.casetype");
                            $builder6->select("skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('casecode', $row5['ref_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $ref_case_type = $r_case_type;
                        }else{
                            // $case_type="SELECT type_sname skey FROM lc_hc_casetype  WHERE lccasecode = '$row5[ref_case_type]' AND display = 'Y'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.lc_hc_casetype");
                            $builder6->select("type_sname skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('lccasecode', $row5['ref_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $ref_case_type = $r_case_type;
                        }                   
                    }

                    $relied_court = '';
                    $relied_state = '';
                    $relied_district = '';
                    $relied_case_type = '';
                    if($row5['relied_court']!=0){
                        // $r_court="Select court_name from m_from_court where id='$row5[relied_court]' and display='Y'";
                        // $r_court=mysql_query($r_court) or die("Error: ".__LINE__.mysql_error());
                        // $res_court=mysql_result($r_court,0);
                        $builder3 = $this->db->table("master.m_from_court");
                        $builder3->select("court_name");
                        $builder3->where('display', 'Y');
                        $builder3->where('id', $row5['relied_court']);
                        $query3 = $builder3->get();
                        $res_court = $query3->getResultArray();
                        if(!empty($res_court)){
                            $res_court = $res_court[0]['court_name'];
                        }else{
                            $res_court = '-';
                        }
                        $relied_court = $res_court;

                        // $r_state="Select name from state where id_no='$row5[relied_state]' and display='Y'";
                        // $r_state=mysql_query($r_state) or die("Error: ".__LINE__.mysql_error());
                        // $res_state=mysql_result($r_state,0);
                        $builder4 = $this->db->table("master.state");
                        $builder4->select("name");
                        $builder4->where('display', 'Y');
                        $builder4->where('id_no', $row5['relied_state']);
                        $query4 = $builder4->get();
                        $res_state = $query4->getResultArray();
                        if(!empty($res_state)){
                            $res_state = $res_state[0]['name'];
                        }else{
                            $res_state = '-';
                        }
                        $relied_state = $res_state;

                        if($row5['relied_court']=='3'){
                            // $r_district="Select Name from state where id_no='$row5[relied_district]' and display='Y' ";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.state");
                            $builder5->select("name");
                            $builder5->where('display', 'Y');
                            $builder5->where('id_no', $row5['relied_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['name'];
                            }else{
                                $res_district = '-';
                            }
                            $relied_district = $res_district;
                        }else{
                            // $r_district="SELECT agency_name FROM ref_agency_code c WHERE c.id='$row5[relied_district]' AND is_deleted = 'f'";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.ref_agency_code");
                            $builder5->select("agency_name");
                            $builder5->where('is_deleted', 'f');
                            $builder5->where('id', $row5['relied_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['agency_name'];
                            }else{
                                $res_district = '-';
                            }
                            $relied_district = $res_district;
                        }

                        $case_type='';
                        if($row5['relied_court']=='4'){
                            // $case_type="SELECT skey FROM casetype  WHERE display = 'Y' AND casecode = '$row5[relied_case_type]'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.casetype");
                            $builder6->select("skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('casecode', $row5['relied_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $relied_case_type = $r_case_type;
                        }else{
                            // $case_type="SELECT type_sname skey FROM lc_hc_casetype  WHERE lccasecode = '$row5[relied_case_type]' AND display = 'Y'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.lc_hc_casetype");
                            $builder6->select("type_sname skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('lccasecode', $row5['relied_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $relied_case_type = $r_case_type;
                        }
                    }

                    $transfer_court = '';
                    $transfer_state = '';
                    $transfer_district = '';
                    $transfer_case_type = '';
                    if($row5['transfer_state']!=NULL && $row5['transfer_state']!=0 && $row5['transfer_state']!=''){
                        // $r_court="Select court_name from m_from_court where id='$row5[transfer_court]' and display='Y'";
                        // $r_court=mysql_query($r_court) or die("Error: ".__LINE__.mysql_error());
                        // $res_court=mysql_result($r_court,0);
                        $builder3 = $this->db->table("master.m_from_court");
                        $builder3->select("court_name");
                        $builder3->where('display', 'Y');
                        $builder3->where('id', $row5['transfer_court']);
                        $query3 = $builder3->get();
                        $res_court = $query3->getResultArray();
                        if(!empty($res_court)){
                            $res_court = $res_court[0]['court_name'];
                        }else{
                            $res_court = '-';
                        }
                        $transfer_court = $res_court;

                        // $r_state="Select Name from state where id_no='$row5[transfer_state]' and display='Y'";
                        // $r_state=mysql_query($r_state) or die("Error: ".__LINE__.mysql_error());
                        // $res_state=mysql_result($r_state,0);
                        $builder4 = $this->db->table("master.state");
                        $builder4->select("name");
                        $builder4->where('display', 'Y');
                        $builder4->where('id_no', $row5['transfer_state']);
                        $query4 = $builder4->get();
                        $res_state = $query4->getResultArray();
                        if(!empty($res_state)){
                            $res_state = $res_state[0]['name'];
                        }else{
                            $res_state = '-';
                        }
                        $transfer_state = $res_state;

                        if($row5['transfer_court']=='3'){
                            // $r_district="Select Name from state where id_no='$row5[transfer_district]' and display='Y' ";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.state");
                            $builder5->select("name");
                            $builder5->where('display', 'Y');
                            $builder5->where('id_no', $row5['transfer_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['name'];
                            }else{
                                $res_district = '-';
                            }
                            $transfer_district = $res_district;
                        }else{
                            // $r_district="SELECT agency_name FROM ref_agency_code c WHERE c.id='$row5[transfer_district]' AND is_deleted = 'f'";
                            // $r_district=mysql_query($r_district) or die("Error: ".__LINE__.mysql_error());
                            // $res_district=mysql_result($r_district,0);
                            $builder5 = $this->db->table("master.ref_agency_code");
                            $builder5->select("agency_name");
                            $builder5->where('is_deleted', 'f');
                            $builder5->where('id', $row5['transfer_district']);
                            $query5 = $builder5->get();
                            $res_district = $query5->getResultArray();
                            if(!empty($res_district)){
                                $res_district = $res_district[0]['agency_name'];
                            }else{
                                $res_district = '-';
                            }
                            $transfer_district = $res_district;
                        }
                        
                        $case_type='';
                        if($row5['transfer_court']=='4'){
                            // $case_type="SELECT skey FROM casetype  WHERE display = 'Y' AND casecode = '$row5[transfer_case_type]'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.casetype");
                            $builder6->select("skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('casecode', $row5['transfer_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $transfer_case_type = $r_case_type;
                        }else{
                            // $case_type="SELECT type_sname skey FROM lc_hc_casetype  WHERE lccasecode = '$row5[transfer_case_type]' AND display = 'Y'";
                            // $case_type=mysql_query($case_type) or die("Error: ".__LINE__.mysql_error());
                            // $r_case_type=mysql_result($case_type,0);
                            $builder6 = $this->db->table("master.lc_hc_casetype");
                            $builder6->select("type_sname skey");
                            $builder6->where('display', 'Y');
                            $builder6->where('lccasecode', $row5['transfer_case_type']);
                            $query6 = $builder6->get();
                            $r_case_type = $query6->getResultArray();
                            if(!empty($r_case_type)){
                                $r_case_type = $r_case_type[0]['skey'];
                            }else{
                                $r_case_type = '-';
                            }
                            $transfer_case_type = $r_case_type;
                        }
                        
                    }

                    $gov_not_state_id = '';
                    $gov_not_case_type = '';
                    $gov_not_case_no = '';
                    $gov_not_case_year = '';
                    $gov_not_date = '';
                    if($row5['gov_not_state_id']!=0){
                        // $r_gov="Select Name from state where id_no='$row5[gov_not_state_id]' and display='Y'";
                        // $r_gov=mysql_query($r_gov) or die("Error: ".__LINE__.mysql_error());
                        // $res_r_gov=mysql_result($r_gov,0);
                        $builder4 = $this->db->table("master.state");
                        $builder4->select("name");
                        $builder4->where('display', 'Y');
                        $builder4->where('id_no', $row5['gov_not_state_id']);
                        $query4 = $builder4->get();
                        $res_state = $query4->getResultArray();
                        if(!empty($res_state)){
                            $res_state = $res_state[0]['name'];
                        }else{
                            $res_state = '-';
                        }
                        $gov_not_state_id = $res_state;

                        if($row5['gov_not_case_type']==''){
                            $gov_not_case_type = '';
                        } else { 
                            $gov_not_case_type = $row5['gov_not_case_type']; 
                        }
                       
                        if($row5['gov_not_case_no']==0){
                            $gov_not_case_no = '';
                        } else {
                            $gov_not_case_no = $row5['gov_not_case_no']; 
                        }
                       
                        if($row5['gov_not_case_year']==0){
                            $gov_not_case_year = '';
                        } else { 
                            $gov_not_case_year = $row5['gov_not_case_year']; 
                        }
                        
                        if($row5['gov_not_date']=='0000-00-00'){
                            $gov_not_date = '-';
                        } else {
                            $gov_not_date = date('d-m-Y',strtotime($row5['gov_not_date'])) ;
                        }

                    }
                                  
                    $l_inddep = '';
                    if($row5['l_inddep']=='D1'){
                        $l_inddep = "State Department";
                    }else if($row5['l_inddep']=='D2'){
                        $l_inddep = "Central Department";
                    }else if($row5['l_inddep']=='D3'){
                        $l_inddep = "Other Organisation";
                    }else if($row5['l_inddep']=='X'){
                        $l_inddep = "Xtra";
                    }

                    // $datrs14['p_id_nm'] = $row5['p_id_nm'].'-';
                    $l_inddepx = '';
                    if($row5['l_inddep']=='X') {
                        $l_inddepx = $row5['l_iopbn']; 
                    } else { 
                        $l_inddepx = $row5['authdesc']; 
                    }
                    
                    $is_order_challenged = '';
                    if($row5['is_order_challenged']=='Y'){
                        $is_order_challenged = "Yes";
                    }else if($row5['is_order_challenged']=='N'){
                        $is_order_challenged = "No";
                    }

                    $full_interim_flag = '';
                    if($row5['full_interim_flag']=='I'){
                        $full_interim_flag = 'Interim';
                    }else  if($row5['full_interim_flag']=='F'){
                        $full_interim_flag = 'Final';
                    }else{
                        $full_interim_flag = '-';
                    }                    
                      
                    $ct_code = '';
                    if($row5['ct_code']=='4'){
                        $ct_code = "Supreme Court";
                    } else  if($row5['ct_code']=='1'){
                        $ct_code = "High Court";
                    } else  if($row5['ct_code']=='3'){
                        $ct_code = "District Court";
                    } else  if($row5['ct_code']=='2'){
                        $ct_code = "Other";
                    } else  if($row5['ct_code']=='5'){
                        $ct_code = "State Agency";
                    }
        
                    $lct_dec_dt = '';
                    if($row5['lct_dec_dt']=='1970-01-01' || $row5['lct_dec_dt']=='0000-00-00' ){
                        $lct_dec_dt = '';
                    } else { 
                        $lct_dec_dt = $row5['lct_dec_dt']!= '' ? date('d-m-Y',strtotime($row5['lct_dec_dt'])) : '';
                    }

                    $cnr_no = '';
                    if( $row5['cnr_no']!='') {
                        $cnr_no = ' / '; 
                    }else{
                        $cnr_no = $row5['cnr_no'];
                    }

                    // $datrs14['jud_name'] = $jud_name;
                    // $datrs14['transfer_case_no'] = $row5['transfer_case_no'];
                    // $datrs14['transfer_case_year'] = $row5['transfer_case_year'];
                    // $datrs14['relied_case_no'] = $row5['relied_case_no'];
                    // $datrs14['relied_case_year'] = $row5['relied_case_year'];
                    // $datrs14['ref_case_no'] = $row5['ref_case_no'];    
                    // $datrs14['ref_case_year'] = $row5['ref_case_year']; 
                    // $datrs14['auth_org'] = $row5['l_orgname'].'/'.$row5['l_ordchno'];
                    // $datrs14['judgement_covered_in']= $row5['judgement_covered_in'];
                    // $datrs14['code_vehicle_no']= $row5['code'] .' '.$row5['vehicle_no'];
                    // $datrs14['name']= $row5['name'];
                    // $datrs14['agency_name']=$row5['agency_name'];
                    // $datrs14['type_sname_lct_caseno']= $row5['type_sname'].'-'.$row5['lct_caseno'].'-'.$row5['lct_caseyear'];
                    // $datrs14['policestndesc'] = $row5['policestndesc'];                    
                    // $datrs14['crime_desc'] = $row5['crimeno'].' / '.$row5['crimeyear'];
                    // $datrs14['post_name']= $row5['post_name'];

                    $datrs14[] = [
                        'cnr_no' => $cnr_no,
                        'lct_dec_dt' => $lct_dec_dt,
                        'ct_code' => $ct_code,
                        'full_interim_flag' => $full_interim_flag,
                        'is_order_challenged' => $is_order_challenged,
                        'l_inddepx' => $l_inddepx,
                        'l_inddep' => $l_inddep,
                        'gov_not_state_id' => $gov_not_state_id,
                        'gov_not_case_type' => $gov_not_case_type,
                        'gov_not_case_no' => $gov_not_case_no,
                        'gov_not_case_year' => $gov_not_case_year,
                        'gov_not_date' => $gov_not_date,
                        'transfer_court' => $transfer_court,
                        'transfer_state' => $transfer_state,
                        'transfer_district' => $transfer_district,
                        'transfer_case_type' => $transfer_case_type,
                        'relied_court' => $relied_court,
                        'relied_state' => $relied_state,
                        'relied_district' => $relied_district,
                        'relied_case_type' => $relied_case_type,
                        'ref_court' => $ref_court,
                        'ref_state' => $ref_state,
                        'ref_district' => $ref_district,
                        'ref_case_type' => $ref_case_type,
                        'jud_name' => $jud_name,
                        'transfer_case_no' => $row5['transfer_case_no'],
                        'transfer_case_year' => $row5['transfer_case_year'],
                        'relied_case_no' => $row5['relied_case_no'],
                        'relied_case_year' => $row5['relied_case_year'],
                        'ref_case_no' => $row5['ref_case_no'],
                        'ref_case_year' => $row5['ref_case_year'],
                        'auth_org' => $row5['l_orgname'].'/'.$row5['l_ordchno'],
                        'judgement_covered_in'=> $row5['judgement_covered_in'],
                        'code_vehicle_no'=> $row5['code'] .' '.$row5['vehicle_no'],
                        'name' => $row5['name'],
                        'agency_name' => $row5['agency_name'],
                        'type_sname_lct_caseno' => $row5['type_sname'].'-'.$row5['lct_caseno'].'-'.$row5['lct_caseyear'],
                        'policestndesc' => $row5['policestndesc'],                   
                        'crime_desc' => $row5['crimeno'].' / '.$row5['crimeyear'],
                        'post_name' => $row5['post_name'] 
                    ];
                    
                }
                $datares['get_earlier_court'] = $datrs14;
            }


        }else{
            $datares['chk_avl'] = "Record Not Available";
        }
        
        // echo "<pre>";
        // print_r($datares['get_earlier_court']); die;
        return $datares;

    }

    public function get_sections_by_act($data){
        $act = !empty($data['act_id']) ? $data['act_id'] : NULL;
        $data = array();
        if(isset($act) && !empty($act)){
            // $sql = "select act_id as id,section from act_section where act_id=$act and display='Y' and section !='' and section is not null";
            // $res = mysql_query($sql) or die(mysql_error());
            $builder1 = $this->db->table("master.act_section");
            $builder1->select("act_id as id,section");
            $builder1->where('act_id', '846'); //$act
            $builder1->where('display','Y');
            $builder1->where('section !=','');
            $builder1->where('section is NOT NULL', NULL, FALSE);
            $query1 = $builder1->get();
            $res = $query1->getResultArray();

            if (count($res) > 0) {
                $options ='';
                $options .='<option value="0" onclick="return selectDeselectAll(true)">Select Section</option>';
                foreach ($res as $result) {
                    $options .='<option value="'.$result['id'].'#'.$result['section'].'">'.$result['section'].'</option>';
                }
                echo $options;
            }
        }

    }

    public function get_sub_category_by_main_catId($data){
        $mainCategory = !empty($data['mainCategory']) ? $data['mainCategory'] : NULL;
        $data = array();
        if(isset($mainCategory) && !empty($mainCategory)){
            // $sql = "select id, subcode1,category_sc_old,sub_name1,sub_name4,
            //         case when (category_sc_old is not null and category_sc_old!='' and category_sc_old!=0)
            //         then concat('',category_sc_old,'#-#',sub_name4)
            //         else concat('',concat(subcode1,'',subcode2),'#-#',sub_name4)
            //         end as dsc from submaster where subcode1= $mainCategory AND id_sc_old!=0  and flag='s' and flag_use in('S','L') GROUP BY id,subcode1,category_sc_old, sub_name1,sub_name4";
            // $res = mysql_query($sql) or die(mysql_error());

            $builder1 = $this->db->table('master.submaster');
            $builder1->select("id, subcode1, category_sc_old, sub_name1, sub_name4,
                CASE
                    WHEN category_sc_old IS NOT NULL AND category_sc_old != '' AND category_sc_old != '0' THEN
                        CONCAT('', category_sc_old, '#-#', sub_name4)
                    ELSE
                        CONCAT('', CONCAT(subcode1, '', subcode2), '#-#', sub_name4)
                END AS dsc", false);
            $builder1->where('subcode1', $mainCategory);
            $builder1->where('id_sc_old !=', 0);
            $builder1->where('flag', 's');
            $builder1->whereIn('flag_use', ['S', 'L']);
            $builder1->groupBy('id, subcode1, category_sc_old, sub_name1, sub_name4');

            // $compiledQuery = $builder1->getCompiledSelect();
            // pr($compiledQuery);

            $query1 = $builder1->get();

            $res = $query1->getResultArray();

            if (count($res) > 0) {
                $options ='';
                $options .='<option value="0" onclick="return selectDeselectAll(true)">Select Subject Category</option>';
                foreach ($res as $result) {
                    $options .='<option value="'.$result['id'].'">'.$result['dsc'].'</option>';
                }
            }
        }
        echo $options;

    }

    public function physical_verification_data_updation($data){

        $form_data =json_decode($data['form_data'],true);
         //echo "<pre>"; print_r($form_data); die;
        $main_condition=$diary_no=$diary_date=$leave_grant_date=$case_group=$party_ids=$history_backup=$history_ids=$subject_category=$act=$section='';
        $orderdt_m_list=[];
        $gender_m_list=[];
        $age_m_list=[];
        $ucode =  $_SESSION['login']['usercode'];
        $ip_address=$_SERVER['REMOTE_ADDR'];
        // echo "<pre>"; print_r($form_data);die;
        foreach ($form_data as $form_element){
            $counter=0;
            if($form_element['name']=='valid_dno'){
                $diary_no=$form_element['value'];
            }else if($form_element['name']=='ddt'){
                $diary_date=$form_element['value'];
            }else if($form_element['name']=='fhdt'){
                $leave_grant_date=$form_element['value'];
            }else if($form_element['name']=='case_group'){
                $case_group=$form_element['value'];
            }else if($form_element['name']=='categoryCode'){
                $subject_category=$form_element['value'];
            }else if($form_element['name']=='section') {
                $act_array = $form_element['value'];
                $act_values = explode('#', $act_array);
                $act = $act_values[0];
                $section=$act_values[1];
            }else{
                $form_element1='';
                $form_element1=explode("_",$form_element['name']);
                $orderdt_count=0;
                
                if($form_element1[0]=='orderdt') {
                    $orderdt_list=array_merge($form_element1,array('table_id'=>$form_element1[1],'value'=>$form_element['value']));
                    array_push($orderdt_m_list,$orderdt_list);
                    $history_ids .= $form_element1[1] . ',';
                    $orderdt_count=$orderdt_count+1;
                }            
            }
        }

        if($diary_date != '') 
        {
            $ddt = date("Y-m-d", strtotime($diary_date));
            // $ddt = explode("-", $diary_date);
            // // print_r($ddt); die;
            // $ddt = $ddt[2] . "-" . $ddt[1] . "-" . $ddt[0];
        }

        if($leave_grant_date != '') 
        {
            $leave_date = date("Y-m-d", strtotime($leave_grant_date));
            $reg_year = date("Y", strtotime($leave_grant_date));
            // $leave_date= explode("/", $leave_grant_date);
            // $reg_year=$leave_date[2];
            // $leave_date = $leave_date[2] . "-" . $leave_date[1] . "-" . $leave_date[0];
        }

        
        $history_ids=rtrim($history_ids,',');

        // $sql_main='select * from main where diary_no='.$diary_no;
        // $result_main=mysql_query($sql_main);
        // $row_main=mysql_fetch_array($result_main);

        $builder1 = $this->db->table("main");
        $builder1->select("*");
        $builder1->where('diary_no', $diary_no); 
        // Compile and print the query


        $query1 = $builder1->get();
        
        $row_main = $query1->getResultArray();
        if(!empty($row_main)){
            $row_main = $row_main[0];
        }


        // $sql_main_backup="insert into main_backup_data_correction select * from main where diary_no=".$diary_no;
        // $result_backup_main=mysql_query($sql_main_backup);
      

        // $builder5 = $this->db->table('main_backup_data_correction');
        // $builder5->select('*');
        // $builder5->where('diary_no', $diary_no);
        // $result_backup_main = $builder5->insertFrom('main');

        $builder5 = $this->db->table('main');
        $builder5->select('*');
        $builder5->where('diary_no', $diary_no);
        $rlts = $builder5->get()->getResultArray();
        if (!empty($rlts)) {
            $result_backup_main = $this->db->table('main_backup_data_correction')->insertBatch($rlts);
        }

        
        if(!empty($history_ids)) {
            // $sql_history = 'select * from main_casetype_history where id in(' . $history_ids . ')';
            // $result_history=mysql_query($sql_history);
            // $row_history=mysql_fetch_array($result_history);
            $history_ids = explode(',',$history_ids); 
            
            // $builder4 = $this->db->table("main_casetype_history");
            // $builder4->select("*");
            // $builder4->whereIn('id',$history_ids);
            // $query4 = $builder4->get();
            // $row_history = $query4->getResultArray();
            // if(!empty($row_history)){
            //     $row_history = $row_history[0];
            // }

            // $sql_history_backup='insert into main_casetype_history_backup_data_correction select * from main_casetype_history where id in('.$history_ids.')';
            // $result_backup_history=mysql_query($sql_history_backup);
            // $builder6 = $this->db->table('main_casetype_history_backup_data_correction');
            // $builder6->select('*');
            // $builder6->whereIn('id', $history_ids);
            // $result_backup_history = $builder6->insertFrom('main_casetype_history');

            $builder6 = $this->db->table('main_casetype_history');
            $builder6->select('*');
            $builder6->whereIn('id', $history_ids);
            $rlts = $builder6->get()->getResultArray();
            if (!empty($rlts)) {
                $result_backup_history = $this->db->table('main_casetype_history_backup_data_correction')->insertBatch($rlts);
                if(!empty($result_backup_history)){
                    $history_backup=1;
                }else{
                    $history_backup=0;
                }
            }            
        }else{
            $history_backup=1;
        }

        if(!empty($result_backup_main) && $history_backup==1){
            $dataUp = [];
            if(!empty($ddt) && date($row_main['diary_no_rec_date'])!=$ddt){
                // $main_condition.="diary_no_rec_date = '$ddt'".',';
                $dataUp[] = [
                    'diary_no_rec_date' => $ddt
                ];
            }
            if(!empty($case_group) && $row_main['case_grp']!=$case_group){
                // $main_condition.=" case_grp='$case_group'".',';
                $dataUp[] = [
                    'case_grp' => $case_group
                ];
            }
            if(!empty($leave_date)){
                if($row_main['fil_dt_fh'] != null || $row_main['fil_dt_fh'] != ''){
                    if(date($row_main['fil_dt_fh'])!=$leave_date){
                        // $main_condition.=" fil_dt_fh = '$leave_date', reg_year_fh = $reg_year".",";
                        $dataUp[] = [
                            'fil_dt_fh' => $leave_date,
                            'reg_year_fh' => $reg_year
                        ];
                    }
                }
                
            }

            $dataUp = !empty($dataUp) ? array_merge(...$dataUp) : [];
            // echo "<pre>"; print_r($dataUp); die;

            $builder3 = $this->db->table("main");
            $builder3->where('diary_no', $diary_no);
            $builder3->update($dataUp);
            // $main_condition=rtrim($main_condition,',');
            // $sql_main_update="update main set $main_condition where diary_no = '$diary_no'";
            // mysql_query($sql_main_update);
        
            // echo "<pre>"; print_r($orderdt_m_list); die;
            foreach($orderdt_m_list as $case_history)
            {
                $order_date = explode("-", $case_history['value']);
                $order_date = $order_date[2] . "-" . $order_date[1] . "-" . $order_date[0];
                // $sql_history_update="update main_casetype_history set order_date='".$order_date ."' where id=".$case_history['table_id'];
                // mysql_query($sql_history_update);
                $dataUp1 = [
                    'order_date' => $order_date
                ];
                $builder7 = $this->db->table("main_casetype_history");
                $builder7->where('id', $case_history['table_id']);
                $builder7->update($dataUp1);
            }

            if(!empty($subject_category))
            {
                // $sql_update_category="Update mul_category set display='N' where diary_no='$dairy_no' and display='Y'";
                // mysql_query($sql_update_category) or die(mysql_error());
                $dataUp2 = [
                    'display' => 'N'
                ];
                $builder7 = $this->db->table("mul_category");
                $builder7->where('diary_no', $diary_no);
                $builder7->where('display', 'Y');
                $builder7->update($dataUp2);

                // $sql_insert_category="insert into mul_category(diary_no,submaster_id,display,mul_cat_user_code,e_date) values ('$diary_no',$subject_category,'Y',$ucode,now())";
                // mysql_query($sql_insert_category);
                $insert_category_data = [                    
                    'diary_no' => $diary_no,
                    'submaster_id' => $subject_category, 
                    'display' => 'Y',
                    'od_cat' => 1,
                    'mul_cat_user_code' => $ucode,
                    'e_date' => 'NOW()',
                ];
                $builder8 = $this->db->table("mul_category");
                $builder8->insert($insert_category_data);

            }
            if(!empty($act)){
                //INSERT INTO act_main(act,entdt,user,diary_no)

                // $sql_act="insert into act_main(act,entdt,user,diary_no) VALUES($act,now(),$ucode,'$dairy_no');";
                // mysql_query($sql_act);    
                $insert_act_data = [                    
                    'act' => $act,
                    'entdt' => 'NOW()', 
                    'user' => $ucode,
                    'diary_no' => $diary_no
                ];
                $builder9 = $this->db->table("act_main");
                $builder9->insert($insert_act_data); 
                $act_id = $this->db->insertID(); 

                // $sql_section="insert into act_section(act_id, section, entdt, user, display) values($act,$section,now(),'$ucode','Y');";
                // mysql_query($sql_section);
                $insert_section_data = [                    
                    'act_id' => $act_id,
                    'section' => $section, 
                    'entdt' => 'NOW()',
                    'user' => $ucode,
                    'display' => 'Y',
                ];
                $builder10 = $this->db->table("master.act_section");
                $builder10->insert($insert_section_data);
            }



            // $sql_verify_update="update physical_verify set display = 'N' where diary_no = '$diary_no'";
            // mysql_query($sql_verify_update) or die(mysql_error());
            $dataUp4 = [
                'display' => 'N'
            ];
            $builder11 = $this->db->table("physical_verify");
            $builder11->where('diary_no', $diary_no);
            $builder11->update($dataUp4);

            // $sql_physical_verify="insert into physical_verify (diary_no, ent_dt, ucode,avaliable_flag,display,ip_address) values ('$diary_no',NOW(),$ucode,'Y','Y','$ip_address')";
            // mysql_query($sql_physical_verify) or die(mysql_error());
            $insert_physical_verify = [    
                'diary_no' => $diary_no,
                'ent_dt' => 'NOW()',
                'ucode' => $ucode,
                'avaliable_flag' => 'Y',
                'display' => 'Y',
                'ip_address' => $ip_address
            ];
            $builder12 = $this->db->table("physical_verify");
            $builder12->insert($insert_physical_verify);


            return 1;


        }
    }

    public function wrong_updated_get_response($data){

        $ucode =  $_SESSION['login']['usercode'];
        $diary_no = $data['valid_dno'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if (!empty($diary_no)) {
            // $sql_verify_update="update physical_verify set display = 'N' where diary_no = '$diary_no'";
            // mysql_query($sql_verify_update) or die(mysql_error());
            $dataUp = [
                'display' => 'N'
            ];
            $builder3 = $this->db->table("physical_verify");
            $builder3->where('diary_no', $diary_no);
            $builder3->update($dataUp);

            // $sql = "insert into physical_verify (diary_no, ent_dt, ucode,avaliable_flag,display,ip_address) values ('$diary_no',NOW(),$ucode,'N','Y','$ip_address')";
            // $result = mysql_query($sql) or die(mysql_error());
            $insert_physical_verify = [    
                'diary_no' => $diary_no,
                'ent_dt' => 'NOW()',
                'ucode' => $ucode,
                'avaliable_flag' => 'N',
                'display' => 'Y',
                'ip_address' => $ip_address,
                'create_modify' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            ];
            $builder12 = $this->db->table("physical_verify");
            $result = $builder12->insert($insert_physical_verify);

            if ($result >= 1){
                return  'Diary No : '.$diary_no.' Updated successfully as not with you';
            }else{
                return 'Error in updation as not with you';
            }
        }

    }
    



}