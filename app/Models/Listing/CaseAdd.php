<?php

namespace App\Models\Listing;

use CodeIgniter\Model;

class CaseAdd extends Model
{
    protected $table = 'main';
    protected $primaryKey = 'diary_no';
    protected $allowedFields = ['diary_no', 'active_fil_no', 'fil_no', 'fil_no_old', 'pet_name', 'res_name', 'res_name_old', 'pet_adv_id', 'res_adv_id', 'actcode', 'claim_amt', 'bench', 'fixed', 'c_status', 'fil_dt', 'active_fil_dt', 'case_pages', 'relief', 'usercode', 'last_usercode', 'dacode', 'old_dacode', 'old_da_ec_case', 'last_dt', 'conn_key', 'case_grp', 'lastorder', 'fixeddet', 'bailno', 'prevno', 'head_code', 'scr_user', 'scr_time', 'scr_type', 'prevno_fildt', 'ack_id', 'ack_rec_dt', 'admitted', 'outside', 'diary_no_rec_date', 'diary_user_id', 'ref_agency_state_id', 'ref_agency_state_id_old', 'ref_agency_code_id', 'ref_agency_code_id_old', 'from_court', 'is_undertaking', 'undertaking_doc_type', 'undertaking_reason', 'casetype_id', 'active_casetype_id', 'padvt', 'radvt', 'total_court_fee', 'court_fee', 'valuation', 'case_status_id', 'brief_description', 'nature', 'fil_no_fh', 'fil_no_fh_old', 'fil_dt_fh', 'mf_active', 'active_reg_year', 'reg_year_mh', 'reg_year_fh', 'reg_no_display', 'pno', 'rno', 'if_sclsc', 'section_id', 'unreg_fil_dt', 'refiling_attempt', 'last_return_to_adv', 'create_modify', 'pet_name_hindi', 'hindi_timestamp', 'res_name_hindi', 'updated_by_ip', 'updated_by', 'updated_on', 'listorder', 'next_dt'];





    // Start Add_case_info.php page query 
   

    public function getDiaryNo($ct, $cn, $cy)
    {
       
        if (empty($ct) || empty($cn) || empty($cy)) {
            return 0;
        }

      

        $builder = $this->db->table('main');
        $builder->select("
            SUBSTRING(CAST(diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(diary_no AS TEXT)) - 4) as dn,
            RIGHT(CAST(diary_no AS TEXT), 4) as dy
        ");

        $builder->groupStart()
            ->where("SPLIT_PART(fil_no, '-', 1)", $ct)
            ->where("$cn BETWEEN CAST(SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1) AS INTEGER) AND CAST(SPLIT_PART(fil_no, '-', -1) AS INTEGER)")
            ->where("(CASE WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM fil_dt) = $cy ELSE reg_year_mh = $cy END)")
            ->groupEnd();
        $builder->orGroupStart()
            ->where("SPLIT_PART(fil_no_fh, '-', 1)", $ct)
            ->where("$cn BETWEEN CAST(SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', -1) AS INTEGER) AND CAST(SPLIT_PART(fil_no_fh, '-', -1) AS INTEGER)")
            ->where("(CASE WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM fil_dt_fh) = $cy ELSE reg_year_fh = $cy END)")
            ->groupEnd();

        $query = $builder->get();


        $result = $query->getRowArray();
        //return $result ? $result['dn'] . $result['dy'] : 0;
        return $result ;
    }
    public function list_regular_advance_weekly($diary_numbers_string1)
    {
        $diary_numbers_string2 = "197182011,259842011,273192011,110932012,420602012,97462013,97482013,225172013,318802013,346242013,386342013,149092014,186952001,214472009,215522009,299222009,317552009,12812010,38722010,96222010,100952010,154412010,222512010,226562010,267642010, 281422010,324062010,338602010,348772010,349212010,360492010,408292010,7692011,11132011,14462011,59632011,124772011,137502011,199342011,251072011,334302011,371922011,22902012,24012012,67032012,67842012,76392012,81242012,172792012,194052012,212732012,226112012,237472012,273632012,354482012,393122012,41242013,62262013,75292013,83682013, 110152013,110972013,119922013,130182013,136242013,140032013,174082013,185402013,203322013,274352013,277112013,292902013";
        $jail_diary_nos = explode(",", $diary_numbers_string2);
        $sql = "SELECT DISTINCT tentative_section(m.diary_no) AS section_name, ci.message AS gate_info, 
                CASE WHEN POSITION(m.diary_no::text IN '$diary_numbers_string2') > 0 THEN 2 ELSE 3 END AS heading_priority, h.*, m.lastorder, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id,
                m.ref_agency_state_id, m.reg_no_display, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.fil_no, m.conn_key AS main_key, m.fil_dt, m.fil_no_fh, m.reg_year_fh AS fil_year_f, m.mf_active, m.pet_name, m.res_name, pno,
                rno, m.diary_no_rec_date, l.purpose, s.category_sc_old,POSITION(m.diary_no::TEXT IN trim('$diary_numbers_string1', '{}')) AS order_position 
                FROM main m 
                INNER JOIN heardt h ON h.diary_no = m.diary_no 
                INNER JOIN master.listing_purpose l ON l.code = h.listorder 
                INNER JOIN vacation_advance_list va ON va.diary_no = m.diary_no 
                LEFT JOIN ( SELECT n.conn_key, COUNT(*) AS total_connected FROM main m_sub 
                INNER JOIN heardt h_sub ON m_sub.diary_no = h_sub.diary_no 
                INNER JOIN main n ON m_sub.diary_no::TEXT = n.conn_key::TEXT 
                WHERE n.diary_no::TEXT != n.conn_key::TEXT AND m_sub.c_status = 'P' 
                GROUP BY n.conn_key ) AS aa ON m.diary_no::TEXT = aa.conn_key::TEXT 
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' 
                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no AND mc.display = 'Y' 
                LEFT JOIN master.submaster s ON mc.submaster_id = s.id AND s.flag = 's' AND s.display = 'Y' AND (s.category_sc_old IS NOT NULL AND s.category_sc_old != '') 
                LEFT JOIN case_info ci ON ci.diary_no = h.diary_no AND ci.display = 'Y' 
                WHERE (m.diary_no::TEXT = m.conn_key::TEXT OR m.conn_key::TEXT = '0' OR m.conn_key IS NULL) 
                AND va.vacation_list_year = '2024' 
                AND m.diary_no = ANY(string_to_array(trim('$diary_numbers_string1', '{}'), ',')::int[]) 
                GROUP BY h.diary_no,m.diary_no, ci.message, h.*, m.lastorder, m.active_fil_no, m.active_reg_year, m.casetype_id, m.active_casetype_id, m.ref_agency_state_id, m.reg_no_display, m.fil_dt, m.fil_no, m.conn_key, m.fil_no_fh, m.reg_year_fh, 
                m.mf_active, m.pet_name, m.res_name, pno, rno, m.diary_no_rec_date, l.purpose, s.category_sc_old 
                ORDER BY order_position"; 
                // echo $sql;
                // die();

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }


    public function get_regular_advance_weekly()
    {
        $diaryNumbersString = "312752016,166442016,262922015,36192014,61912017,188102017,174492017,36992018, 85752018,8792018,4342018,7422018,300912018,349382018,300252018,179862018,284722013, 383252013,133402014,199892014,199902014,214772014,329832014,353582014,425392014, 129262015,202362015,207442015,251192015,259222015,354252015,84112015,84592015, 100442016,116532016,130742016,223112016,392712016,31922018,281122019,284452019,301282019,307302019,308232019,5732020,89042021,138152022,354782022,333392022,112122019,23822021,25072011,187222011,275962011,120562012,178392011,402582012,272922021,210052017,210022017,258392023,297482023,199662017,210022018,289152015,17422022,48352022,356792023,58242018,313292011,61052013,10712014,420602012,37992201,416892017,74502018,390722013,304532022,394372014,381862014,65492015,229282015,75162017,181622018,198422018,210582018,266202018,397752016,110392009,260282011,244902011,44552012,26552014,319412014,319222014,54772015,386672014,203702013,333162019,13312019,153832019,391472018,459102019,191072016,4832014,22392021,226062018,226102018,210232018,56812020,73712020,435952019,296262023,464692023,407422011,5362012,199682012,53112013,189742013,234532013,322912013,27522014, 209882014,415132014,150452015,187642015,240642015,257762016,267262016,40792017,224872017,229822017,230922017,213802018,257822018,468262018,381252019,151802020,201172020,41852021,222912023,222672017,226432021,292952021,257782019,253422018,323462019,137562021,192682021,224312021,278552021,135902022,266232022,92082021,131152011,408522011,162422013,60762014,193162015,403362018,248782019,251392019,312302019,186552020,115322021,138882021,144942021,179962021,190512021,198542021,218082021,278542021,278532021,281782021,284472021,316362021,52292022,53582022,54652022,80432022,152102022,298412022,15862023,17262021,15472020,77782020,120922021,157932022,245952012,56012011,64152013,310222014,238572011,42172018,443332018,75742023,323132023,337032019,309112018,42252018,174382018,325652018,282272018,53422019,325702018,62412019,282842018,30572019,171962018,389962018,136222021,203332022,153462022,396002014,101222015,116282015,416722015,149572015,164522016,330382017,280722015,53362019,108882017,147672008,149422019,102292012,91662010, 315082013,389672013,292122014,135332015,343752015,91752016,373572016,257522015,133082016,201992015,374262016,362182017,21602018,217232016,127732018,233052018,239582018,312022018,364192018,393262016,89922018,105082018,119722018,304992018,361472018,438702018,283482019,292392019,240822019,140362020,227222022,60362017,430472016,319242016,253872017,167622017,234712014,374252017,228282021,454632018,341702022,416812022,259102023,34022020,662022,722022,15482022,33272022,42272022,61732022,197392022,217352022,3162023,286952023,336292023,358522023,392932023,464972023,479192023,483412023,491922023,21772024,348182022,377832022,382332022, 382912022,411542022,415552022,120112023,128352023,259102013,34022020,320072013,146432021,234062021,312982012,261322019,160012011,4162013,722022,397522009,124112012,198632016,370622018,404272019,398952019,455812019,125692020,448212019,96332018,225242021,324462021,248172021,129132022,446192019,246302021,108362021,111452021,171842022,409052022,376682022,375882018,143762022,394462014,228512017,237152021,360742016,269922018,362992016,39782019,39782019,185572019,324632019,252202020,125892023,125742023,170002020,23572018,41252021,46242021,46752015,54012021,60952012,79322014,87682018,97482013,111542019,125642021,125782022,149092014,150162013,155802021,158392013,168012019,170002020,173052020,185952020,193442018,195322021,198872010,204402021,225172013,227202019,259842011,265702021,273192011,277592013,296762018,317052014,324442013,340852017,343092019,344042013,344082013,350722017,370592017,371852017,386752017,394392014,398792013,87682018,125782022,237382021,265702021,30382018,170112023,174852019,336232022,376342022,116172023,31052024,72162024,141842023,181102019,186142022,290392021,405192018,1732020,36732017,87952017,97462013,143342020,182352010,249382022,265732022,278982018,318802013,323482016,346242013,347252022,358442019,374172016,385302019,446332018,286992019,58942022,384722019,335552017,117892023,221172023,368612014,386342013,170132013,195262016,47202024,188752023,312722022,485392023,503182023,235612022,43202024,112422021,191732023,197182011,71182019,85962021,88472021,215662016,114752017,311922015,1972021,32192021,250352020,322712013,139952021,158272021,2932021,59782022,309832022,196342023,236042023,200752023,232362023,505792023,499182023,499182023,2222024,220292020,252642020,235752021,210352018,34672018,517372023,18832021,192952018,33952018,33992018,91142020,125232012,180612012,325162012,337912012,116612013,127832013,207792013,411392013,127032014,133102014,13802014,304392014,340602014,342532014,345302014,343472014,342522014,350972014,257232015,345302014,51142014,235592015,401182015,1252017,184962022,20072023,363532019,185232019,250392019,91332011,331602010,90582010,90662010,62082012,267702010,44632015,223952015,26192019,271002015,87972019,453312018,183922019,183812019,352922019,38572016,409642014,86602016,269762010,359892010,60332011,52522011,307152011,282032018,39662017,39672017,227162019,315832019,227512019,355902019,152102021,25032023,282262020,291052021,151852020,318412023,394022023,283682020,4662023,66132024,171182010,401502017,320682017,70172018,135152017,83772018,420152018,442542018,417512018,452032018,480882018,172772023,154992023,121182023,136442023,9812023,172122015,227202012,30552013,414992014,12562015,183382015,484782023,90252015,96372015,115532021,385372010,10532012,71472012,110932012,229982014,386982015,277022018,302432018,355542018,444592018,444612018,456752018,36332019,295932019,332112019,69662020,220802020,46892022,214712021,212342020,245152022,232432019,253622022,115532021,42532021,321772023,493772023,39012024,28512024,54142024,63292024,185782022,129072015,134542015,137772015,156882015,172412015,356832018,31742022,325892021,371292022,192332023,298072023,19582024,43912024,61212014,85762021,75482022,270642022,322712022,65382023,361892022,86582023,216112023,381482023,143762022,274262020,274382021";
        $builder = $this->db->table('main m');

        $builder->select("
            CASE
                WHEN POSITION(CAST(m.diary_no AS TEXT) IN '$diaryNumbersString') > 0 THEN 2
                ELSE 3
            END AS heading_priority,
            h.*,
            m.active_fil_no,
            m.active_reg_year,
            m.casetype_id,
            m.active_casetype_id,
            m.ref_agency_state_id,
            m.reg_no_display,
            EXTRACT(YEAR FROM m.fil_dt) AS fil_year,
            m.fil_no,
            m.conn_key AS main_key,
            m.fil_dt,
            m.fil_no_fh,
            m.reg_year_fh AS fil_year_f,
            m.mf_active,
            m.pet_name,
            m.res_name,
            m.pno,
            m.rno,
            m.diary_no_rec_date,
            CAST(RIGHT(CAST(m.diary_no AS TEXT), 4) AS INTEGER) AS order_right,
            CAST(LEFT(CAST(m.diary_no AS TEXT), LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) AS order_left
        ");

        $builder->join('heardt h', 'h.diary_no = m.diary_no', 'inner');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder', 'inner');
        $builder->join('vacation_advance_list va', 'va.diary_no = m.diary_no', 'inner');
        $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left');

        $builder->where("cast(m.diary_no as TEXT) = m.conn_key::TEXT OR cast(m.conn_key as TEXT) = '0' OR cast(m.conn_key as TEXT) IS NULL");
        $builder->where('va.vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE)');
        $builder->where('va.is_deleted', 'f');

        $builder->groupBy('m.diary_no, h.diary_no');

        $builder->orderBy('heading_priority, order_right, order_left');
        //$builder->limit(10);
        // echo $builder->getCompiledSelect();
        // die();
        $query = $builder->get();
        $result = $query->getResultArray();
        $data = [];
        $sno = 1;
        foreach ($result as $row) {
            $row['sno'] = $sno++;
            $data[] = $row;
        }

        return json_encode($data);
    }

   
    public function getCaseDetails($dno)
    {

        if (empty($dno)) {
            return null;
        }
        $builder = $this->db->table('main a');
        $builder->select("aa.next_dt as advance_list_date,a.diary_no_rec_date,a.fil_dt,a.lastorder, a.pet_name, 
            a.res_name,a.c_status,b.listorder,b.next_dt,b.mainhead,b.subhead,b.clno,b.brd_slno,b.roster_id, 
            b.judges,b.board_type,b.main_supp_flag,b.tentative_cl_dt,b.sitting_judges,c.remark,b.is_nmd ,case_grp side");

        $builder->join('heardt b', 'a.diary_no  = b.diary_no ', 'left');
        $builder->join('brdrem c', 'CAST(a.diary_no AS BIGINT) = CAST(c.diary_no AS BIGINT)', 'left');
        $builder->join('advance_allocated aa', 'CAST(b.diary_no AS BIGINT) = CAST(aa.diary_no AS BIGINT) AND b.next_dt = aa.next_dt', 'left');
        $builder->where('b.diary_no ', $dno);
        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        return $query->getRowArray();
    }

    public function getSubName($dno)
    {
        
        if (empty($dno)) {
            return [];
        }

        $builder = $this->db->table('mul_category a');
        $builder->select('submaster_id, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'a.submaster_id = b.id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('CAST(a.diary_no AS BIGINT)', $dno);
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result ?: [];
    }



    public function getShortDesc($fil_no_fh)
    {
        if (empty($fil_no_fh)) {
            $fil_no_fh = 0;
        }

        $builder = $this->db->table('master.casetype');
        $builder->select('short_description')
            ->where('casecode', (int) substr($fil_no_fh, 0, 2));
        $result = $builder->get()->getRowArray();

        return $result;
    }
    public function getConnKey($dno)
    {
        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no')
            ->where('conn_key', $dno)
            ->orWhere('diary_no', $dno);
        $result = $builder->get()->getRowArray();
        return $result;
    }
    public function getJname($dno)
    {
       
        $sql = "SELECT jcode,
                STRING_AGG(jname, ' ') AS jname,
                h.diary_no,
                'C' AS notbef,
                ent_dt,
                res_add
            FROM heardt h
            JOIN master.judge j 
            ON j.jcode::text = ANY(string_to_array(h.coram, ',')::text[])
            LEFT JOIN master.not_before_reason 
            ON list_before_remark = res_id
            WHERE h.diary_no =$dno
            GROUP BY h.diary_no, jcode, ent_dt, res_add
            UNION ALL
            SELECT jcode,
                jname,
                diary_no::bigint,
                not_before.notbef,
                ent_dt,
                not_before_reason.res_add
            FROM not_before
            LEFT JOIN master.judge j 
            ON jcode = j1
            LEFT JOIN master.not_before_reason 
            ON not_before.res_id = not_before_reason.res_id
            WHERE diary_no ='$dno'";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

   

    public function getJname_builder($dno)
    {
        $db = \Config\Database::connect();
        $builder1 = $db->table('heardt h')
            ->select("j.jcode, STRING_AGG(j.jname, ' ') AS jname, h.diary_no::TEXT, 'C' AS notbef, h.ent_dt, n.res_add")
            ->join('master.judge j', "j.jcode = ANY(string_to_array(h.coram, ',')::integer[])", 'inner')  // Cast to integer array
            ->join('master.not_before_reason n', 'h.list_before_remark = n.res_id', 'left')
            ->where('h.diary_no', '$dno')
            ->groupBy('h.diary_no, j.jcode, n.res_add');
        $builder2 = $db->table('not_before n')
            ->select("j.jcode, j.jname, n.diary_no::TEXT, n.notbef, n.ent_dt, r.res_add")
            ->join('master.judge j', 'j.jcode = n.j1', 'l eft')
            ->join('master.not_before_reason r', 'n.res_id = r.res_id', 'left')
            ->where('n.diary_no', '$dno');
        $sql = "({$builder1->getCompiledSelect()}) UNION ({$builder2->getCompiledSelect()})";
          $query = $db->query($sql);
        $results = $query->getResultArray();
        return $results;
    }
    public function getAdvanceDate($next_dt)
    {
        // Remove vkg
        //$next_dt = '2025-01-29';


        $builder = $this->db->table('advance_allocated aa');
        $builder->distinct();
        $builder->select('aa.next_dt');
        $builder->join('advance_cl_printed acp', 'aa.next_dt = acp.next_dt AND acp.display = \'Y\'', 'left');
        $builder->where('aa.next_dt', $next_dt);
        $builder->where('acp.id IS NULL');
        $builder->orderBy('aa.next_dt');
        $query = $builder->get();
        return $query->getResultArray();
    }



    // End Add_case_info.php page query 

    public function getHearingDetails($dno)
    {
        //res_add
        if (empty($dno)) {
            return [];
        }
        $builder = $this->db->table('heardt h');
        $builder->select("j.jcode,STRING_AGG(j.jname, ' ') as jname, CAST(h.diary_no AS VARCHAR) as diary_no,'C' as notbef, h.ent_dt,nbr.res_add");
        $builder->join('master.judge j', "h.coram SIMILAR TO '%' || j.jcode || '%'");
        $builder->join('master.not_before_reason nbr', 'h.list_before_remark = nbr.res_id', 'left');
        $builder->where('CAST(h.diary_no AS BIGINT)', $dno);
        $builder->groupBy(['h.diary_no', 'h.ent_dt', 'nbr.res_add', 'j.jcode']);

        $query1 = $builder->getCompiledSelect();
        $builder2 = $this->db->table('not_before nb');
        $builder2->select("j.jcode,j.jname,CAST(nb.diary_no AS VARCHAR) as diary_no,nb.notbef,nb.ent_dt,nbr.res_add");
        $builder2->join('master.judge j', 'nb.j1 = j.jcode', 'left');
        $builder2->join('master.not_before_reason nbr', 'nb.res_id = nbr.res_id', 'left');
        $builder2->where('CAST(nb.diary_no AS BIGINT)', $dno);

        $query2 = $builder2->getCompiledSelect();
        $finalQuery = "($query1) UNION ALL ($query2)";

        return $this->db->query($finalQuery)->getResultArray();
    }



    // start Add case save funtions methods 

    public function getIsnmd($next_dt)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('master.sc_working_days');
        $builder->select('is_nmd');
        $builder->where('working_date', $next_dt); //vkg
        $builder->where('is_holiday', 0);
        $builder->where('display', 'Y');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }

    public function getJudge()
    {
        $sql = "SELECT STRING_AGG(CASE WHEN p3 = 0 THEN CONCAT(p1, ',', p2) ELSE CONCAT(p1, ',', p2, ',', p3) END, ',') AS jcode 
                                FROM (SELECT j.jcode, j.judge_seniority FROM master.judge j WHERE j.is_retired = 'N' AND j.display = 'Y' AND j.jtype = 'J' 
                                    ORDER BY j.judge_seniority ASC 
                                    LIMIT 4
                                ) t
                                INNER JOIN judge_group jg ON jg.p1 = t.jcode 
                                WHERE jg.to_dt IS NULL
                                AND jg.display = 'Y' 
                                GROUP BY t.judge_seniority  
                                ORDER BY t.judge_seniority";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function isNumCheckOne($q_next_dt)
    {
        $sql = "SELECT jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit,
                        (SELECT CASE WHEN SNo = 1 THEN 15 ELSE 10 END AS old_limit  FROM ( SELECT ROW_NUMBER() OVER (ORDER BY working_date) AS SNo, s.* 
                            FROM master.sc_working_days s 
                            WHERE EXTRACT(WEEK FROM working_date) = EXTRACT(WEEK FROM '$q_next_dt'::date)
                            AND is_holiday = 0 AND is_nmd = 1 AND display = 'Y' 
                            AND EXTRACT(YEAR FROM working_date) = EXTRACT(YEAR FROM '$q_next_dt'::date)
                            ORDER BY working_date
                        ) a 
                        WHERE working_date = '$q_next_dt'
                        ) AS old_limit, 
                        COALESCE(listed, 0) AS listed 
                        FROM judge_group jg 
                        LEFT JOIN master.judge j ON j.jcode = jg.p1
                        LEFT JOIN (
                            SELECT h.j1, COUNT(h.diary_no) AS listed 
                            FROM advance_allocated h 
                        LEFT JOIN main m ON h.diary_no::bigint = m.diary_no::bigint 
                            LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                            WHERE d.diary_no IS NULL AND h.next_dt = '$q_next_dt'                                          
                            AND h.board_type = 'J' AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)  
                            AND (m.diary_no ::bigint = m.conn_key ::bigint OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                            GROUP BY h.j1
                        ) b ON b.j1 = jg.p1
                        WHERE j.is_retired != 'Y' AND jg.to_dt IS NULL AND jg.display = 'Y' 
                        GROUP BY jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, old_limit, listed,j.judge_seniority
                        ORDER BY j.judge_seniority";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    // public function isNumCheckOne_builder($q_next_dt)
    // {
    //     // First subquery for old_limit
    //     $subQuery = $this->db->table('master.sc_working_days s')
    //         ->select("CASE WHEN SNo = 1 THEN 15 ELSE 10 END AS old_limit")
    //         ->select("ROW_NUMBER() OVER (ORDER BY working_date) AS SNo")
    //         ->where('EXTRACT(WEEK FROM working_date)', "EXTRACT(WEEK FROM '$q_next_dt'::date)", false)
    //         ->where('is_holiday', 0)
    //         ->where('is_nmd', 1)
    //         ->where('display', 'Y')
    //         ->where('EXTRACT(YEAR FROM working_date)', "EXTRACT(YEAR FROM '$q_next_dt'::date)", false)
    //         ->orderBy('working_date')
    //         ->groupStart()
    //         ->where('working_date', $q_next_dt)
    //         ->groupEnd();
    
    //     // Get the compiled select query of the first subquery
    //     $compiledSubQuery = $subQuery->getCompiledSelect();
    
    //     // Second subquery for "listed"
    //     $subQuery2 = $this->db->table('advance_allocated h')
    //     ->select('h.j1, COUNT(h.diary_no) AS listed')
    //     ->join('main m', 'h.diary_no = m.diary_no', 'left') // Corrected the join condition
    //     ->join('advanced_drop_note d', 'd.diary_no = h.diary_no AND d.cl_date = h.next_dt', 'left')
    //     ->where('d.diary_no', null)
    //     ->where('h.next_dt', $q_next_dt)
    //     ->where('h.board_type', 'J')
    //     ->groupStart()
    //     ->whereIn('h.main_supp_flag', [1, 2])
    //     ->groupEnd()
    //     ->groupStart()
    //     ->where('m.diary_no = m.conn_key') // This condition compares columns directly
    //     ->orWhere('m.conn_key', '')
    //     ->orWhere('m.conn_key', null)
    //     ->orWhere('m.conn_key', '0')
    //     ->groupEnd()
    //     ->groupBy('h.j1');
    
    //     // Get the compiled select query of the second subquery
    //     $compiledSubQuery2 = $subQuery2->getCompiledSelect();
    
    //     // Main query
    //     $query = $this->db->table('judge_group jg')
    //         ->select('jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit')
    //         ->select("($compiledSubQuery) AS old_limit")
    //         ->select("COALESCE(b.listed, 0) AS listed")
    //         ->join('master.judge j', 'j.jcode = jg.p1', 'left')
    //         ->join("($compiledSubQuery2) b", 'b.j1 = jg.p1', 'left')
    //         ->where('j.is_retired !=', 'Y')
    //         ->where('jg.to_dt !=', '1828-02-01')
    //         ->where('jg.display', 'Y')
    //         ->groupBy('jg.p1, jg.p2, jg.p3, j.abbreviation, jg.fresh_limit, old_limit, listed, j.judge_seniority')
    //         ->orderBy('j.judge_seniority')
    //         ->get();
    
    //     // Return result as array
    //     return $query->getResultArray();
    // }

    public function isNumCheckZero($q_next_dt)
    {
        $sql = "SELECT jg.p1,jg.p2,jg.p3,j.abbreviation,jg.fresh_limit,jg.old_limit,COALESCE(listed, 0) AS listed
                                        FROM
                                        judge_group jg
                                        LEFT JOIN master.judge j ON j.jcode = jg.p1
                                            LEFT JOIN (SELECT h.j1,
                                            COUNT(h.diary_no) AS listed
                                        FROM
                                        advance_allocated h
                                        LEFT JOIN main m ON h.diary_no::bigint = m.diary_no::bigint 
                                        LEFT JOIN advanced_drop_note d ON d.diary_no::bigint = h.diary_no::bigint AND d.cl_date = h.next_dt
                                        WHERE
                                            d.diary_no IS NULL
                                           AND h.next_dt = '$q_next_dt'
                                            AND h.board_type = 'J'
                                            AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2)
                                            AND (m.diary_no::bigint = m.conn_key::bigint OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0')
                                        GROUP BY
                                            h.j1) b ON b.j1 = jg.p1
                                        WHERE
                                        j.is_retired != 'Y'
                                        AND jg.to_dt IS NULL
                                        AND jg.display = 'Y'
                                         GROUP BY jg.p1,jg.p2,jg.p3,j.abbreviation,jg.fresh_limit,jg.old_limit,b.listed,j.judge_seniority
                                        ORDER BY
                                        j.judge_seniority";

        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }

    public function getIsPerson($q_next_dt, $presiing_judge_str, $diary_number)
    {
       
       
        //remove vkg
        $sql = "SELECT 
                    'NO' AS is_prepon, 
                    CASE 
                        WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) 
                            AND h.subhead NOT IN (813, 814)) 
                        THEN 1 ELSE 2 
                    END AS pre_notice,
                    t.rid, t.cat, dd.doccode1, a.advocate_id, submaster_id, 
                    m.conn_key AS main_key, l.priority, h.*,
                    aa2.diary_no AS old_advance_no,
                    CASE 
                        WHEN submaster_id IN (343, 15, 16, 17, 18, 19, 20, 21, 22, 23, 341, 353, 157, 158, 159, 160, 161, 162, 163, 166, 173, 175, 176, 322, 222) 
                        THEN 'Yes' ELSE 'No' 
                    END AS is_short_cat
                FROM main m
                LEFT JOIN heardt h ON h.diary_no = m.diary_no
                LEFT JOIN master.listing_purpose l ON l.code = h.listorder
                LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                LEFT JOIN mul_category mc ON mc.diary_no = m.diary_no
                LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no 
                    AND dd.iastat = 'P' AND dd.doccode = 8 
                    AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
                LEFT JOIN advocate a ON a.diary_no = m.diary_no 
                    AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                    AND a.display = 'Y'
                LEFT JOIN advance_allocated aa2 ON aa2.diary_no::bigint = h.diary_no::bigint AND aa2.board_type = 'J'
                LEFT JOIN advance_allocated aa ON aa.diary_no::bigint = h.diary_no::bigint 
                    AND aa.next_dt = '$q_next_dt' 
                    AND aa.board_type = 'J'
                LEFT JOIN case_remarks_multiple c ON c.diary_no::bigint = m.diary_no::bigint 
                    AND c.r_head IN (1, 3, 62, 181, 182, 183, 184)
                INNER JOIN (
                    SELECT STRING_AGG(DISTINCT r.j1::TEXT, ', ') AS rid, 
                        r.submaster_id AS cat 
                    FROM master.judge_category r
                    WHERE r.j1 IN ($presiing_judge_str ) 
                    AND r.to_dt IS NULL  -- No equivalent of '0000-00-00', use NULL
                    AND r.display = 'Y'
                    GROUP BY r.submaster_id
                ) t ON mc.submaster_id = t.cat
                WHERE rd.fil_no IS NULL 
                    AND aa.diary_no IS NULL
                    AND m.active_casetype_id NOT IN (9, 10, 25, 26)
                    AND mc.display = 'Y' 
                    AND mc.submaster_id NOT IN (0, 239, 240, 241, 242, 243, 911, 912, 914) 
                    AND mc.submaster_id IS NOT NULL
                    AND m.c_status = 'P' 
                    
                    AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS INTEGER) 
                    OR NULLIF(m.conn_key, '') IS NULL 
                    OR m.conn_key = '0')

                    AND h.main_supp_flag = 0
                    AND h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)
                    AND h.mainhead = 'M' 
                    AND h.next_dt IS NOT NULL  -- Instead of checking '0000-00-00'
                    AND h.roster_id = 0 
                    AND h.brd_slno = 0 
                    AND h.board_type = 'J'
                    AND h.next_dt = '$q_next_dt'  -- remove
                    AND h.listorder > 0 
                    AND h.listorder <> 32 
                AND m.diary_no = $diary_number -- remove
                GROUP BY m.diary_no, t.rid, t.cat, dd.doccode1, a.advocate_id, submaster_id, 
                        m.conn_key, l.priority, h.*, aa2.diary_no,c.diary_no,h.subhead,h.diary_no
                        "; // Remove limit
                        
                        
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getCoram($coram)
    {
        $sql_crm = "SELECT STRING_AGG(jcode::text,',' ORDER BY judge_seniority) AS new_coram 
                    FROM master.judge 
                    WHERE is_retired = 'N' 
                    AND display = 'Y' 
                    AND jtype = 'J' 
                    AND jcode::text IN (SELECT TRIM(unnest(string_to_array('" . $coram . "', ',')))::text)";
        $rs_crm = $this->db->query($sql_crm);
        $row_coram = $rs_crm->getRowArray();
        return $row_coram;
    }


    // End Add case save funtions methods  



    // START SIngle judge function vkg

    public function getDiaryNoSingleJudge($ct, $cn, $cy)
    {
        $sql = "SELECT 
                SUBSTRING(diary_no::text FROM 1 FOR LENGTH(diary_no::text) - 4) AS dn, 
                SUBSTRING(diary_no::text FROM LENGTH(diary_no::text) - 3 FOR 4) AS dy
            FROM main
            WHERE(SPLIT_PART(fil_no, '-', 1) = '$ct' 
                    AND '$cn' BETWEEN SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', -1)
                                        AND SPLIT_PART(fil_no, '-', -1)
                    AND CASE 
                            WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM fil_dt) = $cy
                            ELSE reg_year_mh = $cy
                        END
                )
                OR 
                (
                    SPLIT_PART(fil_no_fh, '-', 1) = '$ct' 
                    AND $cn BETWEEN SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', -1)::INTEGER 
                                        AND SPLIT_PART(fil_no_fh, '-', -1)::INTEGER 
                    AND CASE 
                            WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM fil_dt_fh) = $cy
                            ELSE reg_year_fh = $cy
                        END
                )";

        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result ? $result['dn'] . $result['dy'] : 0;
    }

    public function getCaseDetailsSingleJudge($dno)
    {
        $sql = "SELECT aa.next_dt as advance_list_date, aa.from_dt, aa.to_dt, a.diary_no_rec_date,a.fil_dt,a.lastorder,
                        a.pet_name, a.res_name,a.c_status,b.listorder,b.next_dt,b.mainhead,b.subhead,b.clno,b.brd_slno,b.roster_id,b.judges,
                        b.board_type,b.main_supp_flag,b.listorder,b.tentative_cl_dt,sitting_judges,c.remark,case_grp side, b.is_nmd
                        FROM main a
                        LEFT JOIN heardt b ON a.diary_no=b.diary_no
                        LEFT JOIN brdrem c ON a.diary_no=c.diary_no::bigint  
                        left join advance_single_judge_allocated aa on b.diary_no=aa.diary_no and b.next_dt = aa.next_dt 
                        and aa.to_dt < CURRENT_DATE
                        WHERE a.diary_no='$dno'";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }
    public function getCaseTypeSingleJudge($dno)
    {
        $sql = "SELECT 
                fil_no,
                fil_dt,
                fil_no_fh,
                fil_dt_fh,
                short_description,
                CASE 
                    WHEN reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) 
                    ELSE reg_year_mh 
                END AS m_year,
                CASE 
                    WHEN reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) 
                    ELSE reg_year_fh 
                END AS f_year
            FROM 
                main a 
            LEFT JOIN 
                master.casetype b ON CAST(SUBSTR(fil_no, 1, 2) AS integer) = casecode 
            WHERE 
                diary_no = '492023'";
        $query = $this->db->query($sql);
        //echo $this->db->getLastQuery(); die;
        $result = $query->getRowArray();
        return $result;
    }

    public function getFilNoSingleJudge($fil_no_fh)
    {
        $sql = "SELECT short_description 
            FROM casetype 
            WHERE casecode = SUBSTR($fil_no_fh, 1, 2)";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function getCategorySingleJudge($dno)
    {
        if (empty($dno)) {
            return '';
        }

        $builder = $this->db->table('mul_category a');
        $builder->select('submaster_id, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'a.submaster_id = b.id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('CAST(a.diary_no AS BIGINT)', $dno);
        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        return $query->getResultArray();
    }


    public function getConnKeyDnoSingleJudge($dno)
    {
        //remove vkg
        if (empty($dno)) {
            return [];
        }
        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('diary_no', $dno);
        $query = $builder->get();
        $res = $query->getRowArray();
        return $res;
    }

    public function getHearingDetailsSingleJudge($dno)
    {

        if (empty($dno)) {
            return [];
        }

        $sql = "SELECT jcode,STRING_AGG(jname, ' ') AS jname,h.diary_no,'C' AS notbef,ent_dt,res_add 
                FROM 
                    heardt h 
                JOIN 
                    master.judge j ON jcode = ANY(string_to_array(coram, ',')::int[]) 
                LEFT JOIN 
                    master.not_before_reason ON list_before_remark = res_id 
                WHERE 
                    h.diary_no = $dno 
                GROUP BY 
                    h.diary_no,j.jcode,not_before_reason.res_add

                UNION 

                SELECT jcode,jname,diary_no ::bigint,not_before.notbef,ent_dt,not_before_reason.res_add 
                FROM 
                    not_before
                LEFT JOIN 
                    master.judge j ON jcode = j1 
                LEFT JOIN 
                    master.not_before_reason ON not_before.res_id = not_before_reason.res_id 
                WHERE 
                    diary_no = '$dno';";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }
    public function getAdvanceDatesSingleJudge()
    {
        $sql = "SELECT aa.* 
                FROM (
                    SELECT DISTINCT aa.from_dt, aa.to_dt 
                    FROM advance_single_judge_allocated aa 
                    ORDER BY aa.from_dt DESC
                ) aa
                LEFT JOIN single_judge_advance_cl_printed acp 
                ON aa.from_dt = acp.from_dt AND aa.to_dt = acp.to_dt AND acp.is_active = 1
                WHERE acp.id IS NULL 
                LIMIT 1";
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    }



    public function isPrintedCaseSingleJudge($from_dt, $to_dt)
    {
        $builder = $this->db->table('single_judge_advance_cl_printed');
        $query = $builder->select('id')
            ->where('from_dt', $from_dt)
            ->where('to_dt', $to_dt)
            ->where('is_active', 1)
            ->get();
        return $query->getNumRows() > 0 ? 1 : 0;
    }

    public function getWeekNoSingleJudge($from_dt, $to_dt)
    {
        $sql = "SELECT weekly_no, weekly_year 
                FROM 
                    advance_single_judge_allocated 
                WHERE 
                    from_dt = '$from_dt' 
                    AND to_dt = '$to_dt' 
                LIMIT 1";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function insertAdvanceSingleJudgeAllocated($from_dt, $to_dt, $advance_weekly_no, $advance_weekly_year, $q_usercode)
    {
        $sql = "INSERT INTO advance_single_judge_allocated (brd_slno, diary_no, conn_key, next_dt, from_dt, to_dt, subhead, board_type, listorder, weekly_no, weekly_year, usercode)
                    SELECT row_number() OVER () AS brd_slno,c.diary_no,c.main_key::int, c.next_dt, 
                        '$from_dt' AS from_dt, 
                        '$to_dt' AS to_dt,
                        c.subhead, 
                        c.board_type, 
                        c.listorder, 
                        $advance_weekly_no AS weekly_no, 
                        '$advance_weekly_year' AS weekly_year,
                        $q_usercode AS usercode
                    FROM (
                        SELECT h.diary_no,h.main_key, h.next_dt,h.subhead,h.board_type,h.listorder
                        FROM (
                            SELECT 
                                dd.doccode1, 
                                mc.submaster_id, 
                                a.advocate_id, 
                                m.conn_key AS main_key, 
                                l.priority, 
                                h.*
                            FROM 
                                main m
                            INNER JOIN 
                                heardt h ON h.diary_no = m.diary_no
                            INNER JOIN 
                                master.listing_purpose l ON l.code = h.listorder
                            INNER JOIN 
                                mul_category mc ON mc.diary_no = m.diary_no
                            LEFT JOIN 
                                docdetails dd ON dd.diary_no = h.diary_no 
                                AND dd.iastat = 'P' 
                                AND dd.doccode = 8 
                                AND dd.doccode1 IN (7, 66, 29, 56, 57, 28, 103, 133, 3, 309, 73, 99, 40, 48, 72, 71, 27, 124, 2, 16, 41, 49, 102, 118, 131, 211, 309)
                            LEFT JOIN 
                                rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N'
                            LEFT JOIN 
                                advocate a ON a.diary_no = m.diary_no 
                                AND a.advocate_id IN (584, 585, 610, 616, 666, 940) 
                                AND a.display = 'Y'
                            LEFT JOIN 
                                advance_single_judge_allocated aa ON aa.diary_no = h.diary_no 
                                AND aa.from_dt = '$from_dt' 
                                AND aa.to_dt = '$to_dt'
                            WHERE 
                                m.c_status = 'P' 
                                AND (m.diary_no = m.conn_key::int OR m.conn_key = '0' OR m.conn_key = '' OR m.conn_key IS NULL) 
                                AND h.main_supp_flag = 0 
                                AND h.mainhead = 'M' 
                                AND h.roster_id = 0 
                                AND h.brd_slno = 0 
                                AND h.board_type = 'S'
                                AND (h.next_dt <= '$to_dt' OR h.next_dt BETWEEN '$from_dt' AND '$to_dt')
                                AND m.diary_no = 422023
                                AND mc.display = 'Y' 
                                AND rd.fil_no IS NULL 
                                AND aa.diary_no IS NULL
                            GROUP BY 
                                m.diary_no,dd.doccode1,mc.submaster_id,a.advocate_id,l.priority,h.diary_no
                        ) h
                    ) c ";
        $query = $this->db->query($sql);
        return $this->db->affectedRows();
    }
    public function getAdvanceSingleJudgeAllocated($diary_number, $from_dt, $to_dt)
    {


        $sql = "SELECT * 
            FROM advance_single_judge_allocated 
            WHERE diary_no = $diary_number 
            AND from_dt = '$from_dt' 
            AND to_dt = '$to_dt'";
        $query = $this->db->query($sql);
        $result = $query->getRowArray();
        return $result;
    }

    public function updateSingleJudgeAllocated($diary_number, $from_dt, $to_dt)
    {
        $sql = "UPDATE single_judge_advanced_drop_note 
                SET display = 'N' 
                WHERE diary_no = $diary_number 
                AND from_dt = '$from_dt' 
                AND to_dt = '$to_dt'";
        $query = $this->db->query($sql);
        return $this->db->affectedRows();
    }




    //END  SIngle judge function 






    public function getCategory($dno)
    {
        if (empty($dno)) {
            return '';
        }

        $builder = $this->db->table('mul_category a');
        $builder->select('submaster_id, sub_name1, sub_name2, sub_name3, sub_name4');
        $builder->join('master.submaster b', 'a.submaster_id = b.id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('CAST(a.diary_no AS BIGINT)', $dno);
        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        $category = '';
        foreach ($query->getResultArray() as $row) {
            $category .= $row['sub_name1'] . '-' . $row['sub_name2'] . '-' . $row['sub_name3'] . '-' . $row['sub_name4'] . '<br>';
        }
        return $category;
    }



    public function getCaseType($dno)
    {

        if (empty($dno)) {
            return null;
        }

        $builder = $this->db->table('main a');
        $builder->select('fil_no, fil_dt, fil_no_fh, fil_dt_fh, short_description');
        $builder->select("COALESCE(NULLIF(reg_year_mh, 0), EXTRACT(YEAR FROM a.fil_dt)) AS m_year");
        $builder->select("COALESCE(NULLIF(reg_year_fh, 0), EXTRACT(YEAR FROM a.fil_dt_fh)) AS f_year");
        $builder->join('master.casetype b', 'SUBSTR(fil_no, 1, 2) = CAST(b.casecode AS text)', 'left');
        $builder->where('CAST(a.diary_no AS BIGINT)', $dno);

        $query = $builder->get();
        //echo $this->db->getLastQuery();die;
        return $query->getRowArray();
    }



    public function getMainCase($dno)
    {
        if (empty($dno)) {
            return null; // Return null or handle error appropriately
        }

        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('CAST(a.diary_no AS BIGINT)', $dno);

        $query = $builder->get();
        return $query->getRowArray();
    }




    public function getAlreadyEntries($dno)
    {
        $builder = $this->db->table('heardt h');
        $builder->select('j.jcode, STRING_AGG(j.jname, \' \') AS jname, CAST(h.diary_no AS BIGINT) AS diary_no_bigint, \'C\' AS notbef, h.ent_dt, n.res_add');
        $builder->join('master.judge j', 'POSITION(j.jcode::text IN h.coram::text) > 0');
        $builder->join('master.not_before_reason n', 'h.list_before_remark = n.res_id', 'left');
        $builder->where('h.diary_no', $dno);
        $builder->groupBy('j.jcode, CAST(h.diary_no AS BIGINT), h.ent_dt, n.res_add');

        $query1 = $builder->get()->getResultArray();

        $builder = $this->db->table('not_before n');
        $builder->select('j.jcode, j.jname, CAST(n.diary_no AS BIGINT) AS diary_no_bigint, n.notbef, n.ent_dt, n2.res_add');
        $builder->join('master.judge j', 'j.jcode = n.j1');
        $builder->join('master.not_before_reason n2', 'n.res_id = n2.res_id', 'left');
        $builder->where('n.diary_no', $dno);

        $query2 = $builder->get()->getResultArray();

        $result = array_merge($query1, $query2);

        return $result;
    }






    public function getAdvanceList($next_dt)
    {
        if (empty($next_dt)) {
            return [];
        }
        $sql = "SELECT DISTINCT aa.next_dt FROM advance_allocated aa LEFT JOIN advance_cl_printed acp ON aa.next_dt = acp.next_dt AND acp.display = 'Y'
          WHERE aa.next_dt = '$next_dt' AND acp.id IS NULL 
                ORDER BY aa.next_dt";
        $rs_crm = $this->db->query($sql);
        return $rs_crm->getResultArray();
    }





    public function checkDiaryNo_old($dno)
    {
        //pr($dno);

        if (empty($dno)) {
            return 'Diary number is required.';
        }


        $builder = $this->db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('diary_no', $dno);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $main_case = $query->getRowArray();
            // pr($main_case);
            if ($main_case['conn_key'] == $dno) {
                return "This is Main Diary No";
            } else {
                $ifMain = 0;
                $conn_key = $main_case['conn_key'];
                $formatted_conn_key = substr($conn_key, 0, -4) . '/' . substr($conn_key, -4);
                return "This is Connected Diary No, Main Diary No is <span style='color:red'>{$formatted_conn_key}</span>";
            }
        } else {
            return 'No matching records found.';
        }
    }

    public function main_case($dno)
    {
        //remove vkg
        $db = \Config\Database::connect();

        $builder = $db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('diary_no', $dno);
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;
    }
    public function checkDiaryNo($dno)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('conct');
        $builder->select('conn_key, diary_no');
        $builder->where('conn_key', $dno);
        $builder->orWhere('diary_no', $dno);

        $query = $builder->get();
        $result = $query->getResultArray();

        if (count($result) > 0) {
            $mainCase = $result[0];

            if ($mainCase['conn_key'] == $dno) {
                echo "This is Main Diary No";
            } else {
                $ifMain = 0;
                echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . substr($mainCase['conn_key'], 0, -4) . '/' . substr($mainCase['conn_key'], -4) . "</span>";
            }
        }
    }







    public function fetchDiaryFromCase($caseType, $caseNo, $caseYear)
    {
        // Fetch the diary number based on the provided case details
        $query = $this->db->table($this->table)
            ->select("CONCAT(SUBSTRING(diary_no, 1, LENGTH(diary_no) - 4), SUBSTRING(diary_no, -4)) as dno")
            ->where('SUBSTRING_INDEX(fil_no, "-", 1)', $caseType)
            ->where("CAST($caseNo AS UNSIGNED) BETWEEN SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1) 
                AND SUBSTRING_INDEX(fil_no, '-', -1)")
            ->where("(reg_year_mh = 0 AND YEAR(fil_dt) = $caseYear) OR reg_year_mh = $caseYear")
            ->get();

        return $query->getRowArray()['dno'] ?? 0;
    }

    // Function to fetch case details based on diary number
    public function fetchCaseDetails($dno)
    {
        $query = $this->db->table($this->table)
            ->select("m.reg_no_display, pet_name, res_name, pno, rno, h.*")
            ->join('heardt h', 'h.diary_no = m.diary_no', 'left')
            ->where('m.diary_no', $dno)
            ->where('h.next_dt >= CURDATE()')
            ->where('(main_supp_flag = 1 OR main_supp_flag = 2)')
            ->orderBy('next_dt', 'desc')
            ->get();

        return $query->getResultArray();
    }

    public function getCases($filters)
    {
        $builder = $this->db->table($this->table);
        $builder->select('m.*, group_concat(c.diary_no) as child_case');
        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left');
        $builder->join('listing_purpose l', 'l.code = h.listorder', 'left');
        $builder->join('casetype c', 'm.active_casetype_id = c.casecode', 'left');
        $builder->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = \'Y\' and c2.submaster_id != 331 and c2.submaster_id != \'\'', 'left');
        $builder->where("l.display", 'Y');

        // Apply filters
        if (!empty($filters['mainhead'])) {
            $builder->where("m.diary_no", $filters['mainhead']);
        }

        // Apply other conditions similarly based on $filters array

        $builder->groupBy('h.diary_no');
        $builder->orderBy('...'); // Dynamic order by logic

        return $builder->get()->getResultArray();
    }

    public function getCasesAdd($params)
    {
        $return = [];
        $listing_dt = isset($params['list_dt']) ? date("Y-m-d", strtotime($params['list_dt'])) : date("Y-m-d");
        $mainhead = isset($params['mainhead']) ? $params['mainhead'] : '';
        $main_supp = isset($params['main_supp']) ? $params['main_supp'] : '';
        $forFixedDate = isset($params['forFixedDate']) ? $params['forFixedDate'] == 'true' : false;
        $from_yr = isset($params['from_yr']) ? $params['from_yr'] : "0";
        $to_yr = isset($params['to_yr']) ? $params['to_yr'] : "0";
        $is_nmd = isset($params['is_nmd']) ? $params['is_nmd'] : '0';
        $is_nmd = ($is_nmd != '0') ? " AND h.is_nmd = '" . $is_nmd . "'" : '';
        //pr($forFixedDate);
        //$listing_purpose = isset($params['listing_purpose']) ? $params['listing_purpose'] : 'all';
        $bench = isset($params['bench']) ? $params['bench'] : '';
        $pool_adv = isset($params['pool_adv']) ? $params['pool_adv'] : '';


        if ($mainhead == "F") {
            $order_by = "CASE WHEN m.listorder in (4,5,7,8) THEN 
                   if($main_supp = 2,m.next_dt = '$listing_dt', (m.next_dt BETWEEN '$listing_dt' AND
                 ADDDATE('$listing_dt',INTERVAL 7 - DAYOFWEEK('$listing_dt') DAY) OR m.next_dt <= CURDATE()) ) 
                   ELSE
               m.next_dt > '1947-08-15' END, CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";
        } else {
            //$order_by = " IF(date(ia_filing_dt) is not null,1,2),date(ia_filing_dt), CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC , CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";

            $order_by = " CASE WHEN date(ia_filing_dt) IS NOT NULL THEN 1 ELSE 2 END, date(ia_filing_dt), CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) ASC,  CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC";
        }







        $case_from_to_yr = ($from_yr != "0" && $to_yr != "0") ? "AND EXTRACT(YEAR FROM m.diary_no_rec_date) BETWEEN '" . $from_yr . "' AND '" . $to_yr . "'" : '';
        $case_grp = ($params['civil_criminal'] == 'C' || $params['civil_criminal'] == 'R') ? "AND m.case_grp = '" . $params['civil_criminal'] . "'" : '';

        if ($params['bench'] != "A") {
            $bench = "AND h.board_type = '" . $params['bench'] . "'";
        }

        $leftjoin_field = $leftjoin_coram_r = $leftjoin_kword = $leftjoin_docdetl = $leftjoin_act = $leftjoin_section = $advance_allocated_left = $advance_drop_note_left = $sub_cat_arry = $casetype = $subhead_select = $kword_selected = $docdetl_selected = $act_selected = $section_selected = $advance_allocated_qry = $advance_drop_note_qry = $rgo_dft_left = $rgo_dft_qry = $coram_sele_or_null = $reg_unreg = '';

        $get_ia_date = ', NULL as ia_filing_dt';
        if ($mainhead != 'F') {
            $subhead_arry = $this->f_selected_values($params['subhead']);
            if (in_array('817', @explode(',', $subhead_arry))) {
                $get_ia_date = ",(select min(doc.ent_dt) from docdetails doc inner join main mn on doc.diary_no=mn.diary_no
                        left join conct ct on mn.diary_no=ct.conn_key where doc.doccode=8 and doc.doccode1=3 and doc.iastat='P' and doc.display='Y' and (ct.list='Y' or ct.list is null)
                        and (mn.diary_no=m.diary_no or mn.conn_key=m.diary_no) and mn.c_status='P') as ia_filing_dt ";
            }
            if ($subhead_arry != "all") {
                $subhead_select = "AND h.subhead IN ($subhead_arry)";
            }
        }

        $sub_cat =  $this->f_selected_values($params['subject_cat']);
        if ($sub_cat != "all") {
            $sub_cat_arry = "AND c2.submaster_id IN ($sub_cat) ";
        }

        $kword_arry = $this->f_selected_values($params['kword']);
        if ($kword_arry != "all") {
            $leftjoin_kword = "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no and ek.display = 'Y'";
            $kword_selected = "AND keyword_id IN ($kword_arry)";
        }

        $ia_arry = $this->f_selected_values($params['ia']);
        if ($ia_arry != "all") {
            $leftjoin_docdetl = "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no";
            $docdetl_selected = "AND dd.doccode1 IN ($ia_arry)  and dd.iastat = 'P' and dd.display = 'Y' and dd.doccode = '8'";
        }
        $ia_arry = $this->f_selected_values($params['act']);
        if ($ia_arry != "all") {
            $leftjoin_act = "LEFT JOIN act_main at ON at.diary_no = h.diary_no";
            $act_selected = "AND at.display = 'Y' and at.act IN ($ia_arry)";
            if ($params['section']) {
                $leftjoin_section = "LEFT JOIN master.act_section ast ON ast.act_id = at.id";
                $section_selected = "AND ast.section LIKE '" . $params['section'] . "%' AND ast.display = 'Y'";
            }
        }

        $only_regs = "";
        if ($params['reg_unreg'] == 1) {
            $reg_unreg = " OR (m.active_fil_no = '' OR m.active_fil_no IS NULL) "; //with unregistred
        } else {
            $only_regs = " AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }

        $casetype_array = $this->f_selected_values($params['case_type']);
        if ($casetype_array != "all") {
            //COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN (". $this->f_selected_values($params['case_type']) . ") 
            $casetype = "AND (COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN (" . $this->f_selected_values($params['case_type']) . ")  $reg_unreg )";

            //$casetype = "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1) ) IN (" . $this->f_selected_values($params['case_type']) . ") $reg_unreg )";
        }

        if ($forFixedDate == 'true') {
            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND 
               h.next_dt = '$listing_dt'";
        } else {
            $qry_part_list_ornot1 = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND CASE WHEN l.fx_wk = 'F' THEN
                if($main_supp = 2,h.next_dt = '$listing_dt', (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE) )
                ELSE h.next_dt <= '$listing_dt' END ";

            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND ((l.fx_wk = 'F' AND (
    ($main_supp = 2 AND h.next_dt = '$listing_dt') OR
    ($main_supp != 2 AND (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE)))) OR (l.fx_wk != 'F' AND h.next_dt <= '$listing_dt'))";
        }

        $md_name = $params['md_name'];
        $coram_sele = '';

        if ($md_name == "pool" or $md_name == "transfer") {
            $sql_field = "m.*, STRING_AGG(c.diary_no::TEXT, ',') AS child_case";
            //$sql_field2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no, m.active_fil_no, m.ia_filing_dt, m.active_reg_year, m.active_casetype_id, m.reg_no_display, m.short_description,m.fil_no,m.fil_dt, m.fil_year,m.lastorder, m.diary_no_rec_date, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram, m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag, m.listorder, m.tentative_cl_dt, m.listed_ia, m.sitting_judges, m.list_before_remark, m.coram_prev, m.is_nmd, m.no_of_time_deleted, m.updated_by_ip, m.updated_by, m.updated_on,m.create_modify,m.trial011,m.descrip,m.purpose,m.cat1, m.r_coram ORDER BY brd_slno ASC, $order_by";
            if ($md_name == "transfer") {
                $sql_field2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no, m.active_fil_no, m.ia_filing_dt, m.active_reg_year, m.active_casetype_id, m.reg_no_display, m.short_description,m.fil_no,m.fil_dt, m.fil_year,m.lastorder, m.diary_no_rec_date, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram, m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag, m.listorder, m.tentative_cl_dt, m.listed_ia, m.sitting_judges, m.list_before_remark, m.coram_prev, m.is_nmd, m.no_of_time_deleted, m.updated_by_ip, m.updated_by, m.updated_on,m.create_modify,m.trial011,m.descrip,m.purpose,m.cat1 ORDER BY brd_slno ASC, $order_by";

                $part_no = $params['part_no'];
                $roster_judges_id = explode("|", $params['roster_judges_id']);
                $trans_ros_id = $roster_judges_id[1];
                $qry_part_list_ornot = "AND h.roster_id = $trans_ros_id AND h.clno = $part_no AND h.next_dt = '$listing_dt'";
            }
            if ($md_name == "pool") {
                $sql_field2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no, m.active_fil_no, m.ia_filing_dt, m.active_reg_year, m.active_casetype_id, m.reg_no_display, m.short_description,m.fil_no,m.fil_dt, m.fil_year,m.lastorder, m.diary_no_rec_date, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram, m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag, m.listorder, m.tentative_cl_dt, m.listed_ia, m.sitting_judges, m.list_before_remark, m.coram_prev, m.is_nmd, m.no_of_time_deleted, m.updated_by_ip, m.updated_by, m.updated_on,m.create_modify,m.trial011,m.descrip,m.purpose,m.cat1, m.r_coram ORDER BY brd_slno ASC, $order_by";

                //$coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                $leftjoin_field = " cr.jud as r_coram, ";
            }
        } else {
            if ($md_name == "allocation") {
                $chked_jud = rtrim($params['roster_judges_id'], "JG");
                $roster_selected = $chked_jud;
                //pr($roster_selected);
                $explode_rs = explode("JG", $roster_selected);
                for ($i = 0; $i < (count($explode_rs)); $i++) {
                    $explode_rs_jg = explode("|", $explode_rs[$i]);
                    $coram_sele .= $explode_rs_jg[0] . ",";
                }
                if (rtrim($coram_sele, ",") == '') {
                    $cor_slse = "0";
                } else {
                    $cor_slse = rtrim($coram_sele, ",");
                }
                if ($params['bench'] == 'J' or $params['bench'] == 'S') {
                    $coram_sele_or_null = " AND (h.coram IN ('$cor_slse') or h.coram = '0' or h.coram is null or h.coram = '' ) ";
                    //$coram_sele_or_null = '';
                }
                if ($params['bench'] == 'R') {
                    $coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                    $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                    $leftjoin_field = " cr.jud as r_coram, ";
                }
            }
            $sql_field = "count(*) as avl_rc";
            $sql_field2 = "";
        }


        $mul_cat_qry = "";
        if ($params['bench'] == 'J' or $params['bench'] == 'S') {
            $rgo_dft_left = " LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' ";
            $rgo_dft_qry = " AND rd.fil_no IS NULL ";
            $mul_cat_qry = " c2.diary_no IS NOT NULL AND ";
        }
        if ($params['pool_adv'] == 'A') {
            $advance_allocated_left = " LEFT JOIN advance_allocated ad_al ON ad_al.diary_no = h.diary_no AND ad_al.next_dt = '$listing_dt' ";
            $advance_drop_note_left = " LEFT JOIN advanced_drop_note ad_dn ON ad_dn.diary_no = ad_al.diary_no AND ad_dn.cl_date = ad_al.next_dt ";
            $advance_allocated_qry = " AND ad_al.diary_no IS NOT NULL ";
            $advance_drop_note_qry = " AND ad_dn.diary_no IS NULL ";
        }

        //pr($sql_field);

        $listorder = $this->f_selected_values(isset($params['listing_purpose']) ? $params['listing_purpose'] : []);
        $p_listorder =  ((!empty($listorder)) && ($listorder != "all"))  ? "AND h.listorder IN ($listorder)" : '';

        /*$mainheadCondition = is_numeric($mainhead) ?
            "CAST(m.diary_no AS TEXT) = '" . $mainhead . "'" :
            "CAST(m.diary_no AS TEXT) LIKE '" . $mainhead . "%'";*/

        $mainheadCondition = ['h.mainhead' => $mainhead];

        $groupBy = "GROUP BY h.diary_no, m.active_fil_no, m.active_reg_year, m.active_casetype_id, m.reg_no_display, c.short_description, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, l.purpose";
        if (($md_name == "allocation") && ($params['bench'] == 'R')) {
            $groupBy .= " , cr.jud";
        }
        if ($md_name == "pool") {
            $groupBy .= " , cr.jud";
        }

        $qry = "SELECT $sql_field
           FROM (SELECT $leftjoin_field m.active_fil_no $get_ia_date, m.active_reg_year, m.active_casetype_id, m.reg_no_display, c.short_description, m.fil_no, m.fil_dt, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.lastorder, m.diary_no_rec_date, h.*, l.purpose,  STRING_AGG(c2.submaster_id :: TEXT, ',') AS cat1
            FROM main m
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode            
            LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y' and c2.submaster_id != 331 and c2.submaster_id IS NOT NULL      
            $rgo_dft_left
            $leftjoin_coram_r
            $leftjoin_kword    
            $leftjoin_docdetl    
            $leftjoin_act    
            $leftjoin_section 
               $advance_allocated_left
               $advance_drop_note_left
            WHERE $mul_cat_qry l.display = 'Y' $sub_cat_arry $is_nmd $coram_sele_or_null
            $rgo_dft_qry
            
            $p_listorder            
            $case_grp
            $only_regs    
            $casetype   
            $bench    
            $case_from_to_yr  
            $subhead_select    
            $kword_selected  
            $docdetl_selected    
            $act_selected    
            $section_selected    
             $advance_allocated_qry 
             $advance_drop_note_qry
             AND (
             --m.diary_no = m.conn_key:: BIGINT 
             --OR m.conn_key:: BIGINT=0 
             m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT)
             OR m.conn_key = '0'
             OR m.conn_key = '' OR m.conn_key IS NULL) $qry_part_list_ornot AND h.mainhead = '" . $params['mainhead'] . "'             
             $groupBy ) m
            $sql_field2";
        //pr($qry);
        $query = $this->db->query($qry);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
            //pr($return);
        }
        return $return;
    }
    public function getCasesAdd1($params)
    {
        // Prepare the query builder
        $builder = $this->db->table('main m');

        // Ensure parameters are defined
        $listing_dt = isset($params['list_dt']) ? date("Y-m-d", strtotime($params['list_dt'])) : date("Y-m-d");
        $mainhead = isset($params['mainhead']) ? $params['mainhead'] : '';
        $main_supp = isset($params['main_supp']) ? $params['main_supp'] : '';
        $forFixedDate = isset($params['forFixedDate']) ? $params['forFixedDate'] == 'true' : false;
        $from_yr = isset($params['from_yr']) ? $params['from_yr'] : "0";
        $to_yr = isset($params['to_yr']) ? $params['to_yr'] : "0";
        $is_nmd = isset($params['is_nmd']) ? $params['is_nmd'] : '0';
        $is_nmd = ($is_nmd != '0') ? " AND h.is_nmd = '" . $is_nmd . "'" : '';
        //pr($forFixedDate);
        //$listing_purpose = isset($params['listing_purpose']) ? $params['listing_purpose'] : 'all';
        $bench = isset($params['bench']) ? $params['bench'] : '';
        $pool_adv = isset($params['pool_adv']) ? $params['pool_adv'] : '';

        $case_from_to_yr = ($from_yr != "0" && $to_yr != "0") ? "AND EXTRACT(YEAR FROM m.diary_no_rec_date) BETWEEN '" . $from_yr . "' AND '" . $to_yr . "'" : '';
        $case_grp = ($params['civil_criminal'] == 'C' || $params['civil_criminal'] == 'R') ? "AND m.case_grp = '" . $params['civil_criminal'] . "'" : '';

        if ($params['bench'] != "A") {
            $bench = "AND h.board_type = '" . $params['bench'] . "'";
        }


        $get_ia_date = ', NULL as ia_filing_dt';
        if ($mainhead != 'F') {
            $subhead_arry = $this->f_selected_values($params['subhead']);
            if (in_array('817', @explode(',', $subhead_arry))) {
                $get_ia_date = ",(select min(doc.ent_dt) from docdetails doc inner join main mn on doc.diary_no=mn.diary_no
                        left join conct ct on mn.diary_no=ct.conn_key where doc.doccode=8 and doc.doccode1=3 and doc.iastat='P' and doc.display='Y' and (ct.list='Y' or ct.list is null)
                        and (mn.diary_no=m.diary_no or mn.conn_key=m.diary_no) and mn.c_status='P') as ia_filing_dt ";
            }
            if ($subhead_arry != "all") {
                $subhead_select = "AND h.subhead IN ($subhead_arry)";
            }
        }

        $sub_cat =  $this->f_selected_values($params['subject_cat']);
        if ($sub_cat != "all") {
            $sub_cat_arry = "AND c2.submaster_id IN ($sub_cat) ";
        }

        $kword_arry = $this->f_selected_values($params['kword']);
        if ($kword_arry != "all") {
            $leftjoin_kword = "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no and ek.display = 'Y'";
            $kword_selected = "AND keyword_id IN ($kword_arry)";
        }

        $ia_arry = $this->f_selected_values($params['ia']);
        if ($ia_arry != "all") {
            $leftjoin_docdetl = "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no";
            $docdetl_selected = "AND dd.doccode1 IN ($ia_arry)  and dd.iastat = 'P' and dd.display = 'Y' and dd.doccode = '8'";
        }
        $ia_arry = $this->f_selected_values($params['act']);
        if ($ia_arry != "all") {
            $leftjoin_act = "LEFT JOIN act_main at ON at.diary_no = h.diary_no";
            $act_selected = "AND at.display = 'Y' and at.act IN ($ia_arry)";
            if ($params['section']) {
                $leftjoin_section = "LEFT JOIN master.act_section ast ON ast.act_id = at.id";
                $section_selected = "AND ast.section LIKE '" . $params['section'] . "%' AND ast.display = 'Y'";
            }
        }

        $only_regs = "";
        if ($params['reg_unreg'] == 1) {
            $reg_unreg = " OR (m.active_fil_no = '' OR m.active_fil_no IS NULL) "; //with unregistred
        } else {
            $only_regs = " AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }

        $casetype_array = $this->f_selected_values($params['case_type']);
        if ($casetype_array != "all") {
            $casetype = "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1) ) IN (" . $this->f_selected_values($params['case_type']) . ") $reg_unreg )";
        }

        if ($forFixedDate == 'true') {
            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND 
               h.next_dt = '$listing_dt'";
        } else {
            $qry_part_list_ornot1 = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND CASE WHEN l.fx_wk = 'F' THEN
                if($main_supp = 2,h.next_dt = '$listing_dt', (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE) )
                ELSE h.next_dt <= '$listing_dt' END ";

            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND ((l.fx_wk = 'F' AND (
                        ($main_supp = 2 AND h.next_dt = '$listing_dt') OR
                        ($main_supp != 2 AND (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE)))) OR (l.fx_wk != 'F' AND h.next_dt <= '$listing_dt'))";
        }

        $md_name = $params['md_name'];
        $coram_sele = '';
        //pr($md_name);
        if ($md_name == "pool" or $md_name == "transfer") {
            $sql_field = "m.*, group_concat(c.diary_no) as child_case";
            $sql_field2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no ORDER BY brd_slno ASC, $order_by";
            if ($md_name == "transfer") {
                $part_no = $params['part_no'];
                $roster_judges_id = explode("|", $params['roster_judges_id']);
                $trans_ros_id = $roster_judges_id[1];
                $qry_part_list_ornot = "AND h.roster_id = $trans_ros_id AND h.clno = $part_no AND h.next_dt = '$listing_dt'";
            }
            if ($md_name == "pool") {
                //$coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                $leftjoin_field = " cr.jud as r_coram, ";
            }
        } else {
            if ($md_name == "allocation") {
                $chked_jud = rtrim($params['roster_judges_id'], "JG");
                $roster_selected = $chked_jud;
                //pr($roster_selected);
                $explode_rs = explode("JG", $roster_selected);
                for ($i = 0; $i < (count($explode_rs)); $i++) {
                    $explode_rs_jg = explode("|", $explode_rs[$i]);
                    $coram_sele .= $explode_rs_jg[0] . ",";
                }
                if (rtrim($coram_sele, ",") == '') {
                    $cor_slse = "0";
                } else {
                    $cor_slse = rtrim($coram_sele, ",");
                }
                if ($params['bench'] == 'J' or $params['bench'] == 'S') {
                    $coram_sele_or_null = " AND (h.coram IN ($cor_slse) or h.coram = 0 or h.coram is null or h.coram = '' ) ";
                }
                if ($params['bench'] == 'R') {
                    $coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                    $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                    $leftjoin_field = " cr.jud as r_coram, ";
                }
            }
            $sql_field = "count(*) as avl_rc";
            $sql_field2 = "";
        }

        //pr($sql_field);

        $listorder = $this->f_selected_values(isset($params['listing_purpose']) ? $params['listing_purpose'] : []);
        $p_listorder = ($listorder != "all") ? "AND h.listorder IN ($listorder)" : '';

        /*$mainheadCondition = is_numeric($mainhead) ?
            "CAST(m.diary_no AS TEXT) = '" . $mainhead . "'" :
            "CAST(m.diary_no AS TEXT) LIKE '" . $mainhead . "%'";*/

        $mainheadCondition = ['h.mainhead' => $mainhead];


        // Order By Clause
        if ($mainhead == "F") {
            $order_by = "CASE WHEN CAST(m.listorder AS INTEGER) IN (4, 5, 7, 8) THEN 
            COALESCE(CASE WHEN $main_supp = 2 THEN (m.next_dt = '$listing_dt') 
            ELSE (m.next_dt BETWEEN '$listing_dt' AND (DATE '$listing_dt' + INTERVAL '7 days') OR m.next_dt <= CURRENT_DATE) END, FALSE) 
            ELSE m.next_dt > '1947-08-15' END, 
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC, 
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        } else {
            $order_by = "CASE WHEN ia_filing_dt IS NOT NULL THEN 1 ELSE 2 END, 
            ia_filing_dt, 
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM LENGTH(CAST(m.diary_no AS TEXT)) - 3 FOR 4) AS INTEGER) ASC, 
            CAST(SUBSTRING(CAST(m.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(m.diary_no AS TEXT)) - 4) AS INTEGER) ASC";
        }

        // Join Tables
        $builder->join('heardt h', 'm.diary_no = h.diary_no', 'left')
            ->join('master.listing_purpose l', 'l.code = h.listorder', 'left')
            ->join('master.casetype c', 'm.active_casetype_id = c.casecode', 'left')
            ->join('mul_category c2', 'c2.diary_no = h.diary_no AND c2.display = \'Y\' AND (c2.submaster_id IS NULL OR c2.submaster_id != 331)', 'left');

        // Additional Conditions
        if ($bench == 'J' || $bench == 'S') {
            $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
                ->where('rd.fil_no IS NULL');
        }

        if ($pool_adv == 'A') {
            $builder->join('advance_allocated ad_al', 'ad_al.diary_no = h.diary_no', 'left')
                ->join('advanced_drop_note ad_dn', 'ad_dn.diary_no = ad_al.diary_no', 'left')
                ->where('ad_al.diary_no IS NOT NULL')
                ->where('ad_dn.diary_no IS NULL');
        }

        // Select Fields
        $builder->select("m.*, STRING_AGG(c2.diary_no::text, ',') as child_case")
            ->where("l.display", 'Y')
            ->where($mainheadCondition)
            ->groupBy('m.diary_no, h.diary_no');
        //->orderBy($order_by);

        // Execute Query
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function f_selected_values($parm1)
    {
        $dld = "";
        if ((count($parm1) > 1) && $parm1[0] == 'all') {
            unset($parm1[0]);
        }

        foreach ($parm1 as $key => $value) {
            $dld .= $value . ",";
        }
        return rtrim($dld, ',');
    }








    public function getRopOrders($diaryNumbers)
    {
        if (empty($diaryNumbers)) {
            return [];
        }

        return $this->db->table('tempo')
            //->select("diary_no, jm AS pdfname, TO_CHAR(dated, 'YYYY-MM-DD') AS orderdate")
            ->select("diary_no, jm AS pdfname, dated AS orderdate")
            ->whereIn('diary_no', $diaryNumbers)
            ->where('jt', 'rop')
            ->orderBy('dated', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getSingleJudgePool($from_date, $to_date)
    {
        $builder = $this->db->table('main m')
            ->select('COUNT(DISTINCT m.diary_no) AS total')
            ->join('heardt h', 'h.diary_no = m.diary_no')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no')
            ->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = \'N\'', 'left')
            ->join('advance_single_judge_allocated aa', 'aa.diary_no = h.diary_no AND aa.from_dt = \'' . $from_date . '\' AND aa.to_dt = \'' . $to_date . '\'', 'left')
            ->where('aa.diary_no IS NULL')
            ->where('m.c_status', 'P')
            ->groupStart()  // Group the OR conditions
            ->where('m.diary_no = CAST(m.conn_key AS bigint)', null, false)  // Cast m.conn_key to bigint for comparison
            ->orWhere('CAST(m.conn_key AS bigint) =', 0, false)  // Cast m.conn_key to bigint for the comparison to 0
            ->orWhere('m.conn_key IS NULL')
            ->groupEnd()
            ->where('m.active_casetype_id !=', 9)
            ->where('m.active_casetype_id !=', 10)
            ->where('h.subhead NOT IN (801, 817, 818, 819, 820, 848, 849, 850, 854, 0)')
            ->where('m.active_casetype_id NOT IN (25, 26)')
            ->where('h.main_supp_flag', 0)
            ->where('h.mainhead', 'M')
            ->where('h.roster_id', 0)
            ->where('h.brd_slno', 0)
            ->where('h.listorder !=', 32)
            ->where('h.board_type', 'S')
            ->groupStart()  // Handle the next date condition
            ->where('h.next_dt <=', $from_date)
            ->orWhere('h.next_dt BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'')
            ->groupEnd()
            ->where('mc.display', 'Y')
            ->where('rd.fil_no IS NULL')
            ->get();

        $row = $builder->getRow();
        return $row ? $row->total : 0;
    }


    public function getDiaryNumber($ct, $cn, $cy, $searchType)
    {

        if ($searchType == "D") {
            return $this->db->table('main')
                ->select("SUBSTRING(diary_no FROM 1 FOR LENGTH(diary_no) - 4) as dn, 
                          SUBSTRING(diary_no FROM LENGTH(diary_no) - 3 FOR 4) as dy")
                ->where("split_part(fil_no, '-', 1) = ", $ct)
                ->where("$cn BETWEEN CAST(split_part(fil_no, '-', 2) AS INTEGER) 
                         AND CAST(split_part(fil_no, '-', 3) AS INTEGER)")
                ->where("(reg_year_mh = 0 AND EXTRACT(YEAR FROM fil_dt) = $cy) 
                         OR reg_year_mh = $cy")
                ->get()
                ->getRowArray();
        }

        return null;
    }



    public function checkAvailability($dno)
    {
        $builder = $this->db->table('main m');

        $builder->select('m.pet_name, m.res_name, m.pno, m.rno, h.*')
            ->join('advance_single_judge_allocated h', 'h.diary_no = m.diary_no', 'left')
            ->join(
                'single_judge_advanced_drop_note a',
                'a.diary_no = h.diary_no AND a.display = \'R\' AND a.cl_date = h.next_dt',
                'left'
            )
            ->where('a.diary_no IS NULL')
            ->where('m.diary_no', $dno)
            ->groupStart()
            ->where('h.from_dt >=', date('Y-m-d'))
            ->orWhere('h.to_dt <=', date('Y-m-d'))
            ->groupEnd();

        return $builder->get()->getResultArray();
    }






    // Function to check if cause list is printed
    public function isCauseListPrinted($from_dt, $to_dt)
    {
        $sql = "SELECT * FROM single_judge_advance_cl_printed WHERE from_dt = ? AND to_dt = ? AND is_active = 1";
        $result = $this->db->query($sql, [$from_dt, $to_dt]);

        return $result->getNumRows() > 0;
    }

    // Function to check drop note status
    public function checkDropNoteStatus($dno, $brd_slno, $from_dt, $to_dt)
    {
        $sql = "
            SELECT COUNT(diary_no) 
            FROM single_judge_advanced_drop_note 
            WHERE diary_no = ? 
              AND clno = ? 
              AND (display = 'Y' OR display = 'R') 
              AND (from_dt = ? OR to_dt = ?)";

        return $this->db->query($sql, [$dno, $brd_slno, $from_dt, $to_dt])->getRowArray();
    }



    ///

    // public function getDiaryDetails1($diaryNumber, $diaryYear)
    // {
    //     $builder = $this->db->table('main');
    //     $builder->select("SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4) as dn, SUBSTRING(CAST(diary_no AS TEXT), -4) as dy");
    //     $builder->where('SPLIT_PART(fil_no, \'-\', 1) =', $diaryNumber)  

    //            ->where("EXTRACT(YEAR FROM fil_dt) =", $diaryYear, false);
    //          //  pr($builder->getCompiledSelect());
    //     return $builder->get()->getResultArray();
    // }




    public function getDiaryDetails1($data)
    {
        $builder = $this->db->table('main');

        if ($data['search_type'] === 'D') {
            $builder->select("SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4) as dn, SUBSTRING(CAST(diary_no AS TEXT), -4) as dy")
                ->where("SPLIT_PART(fil_no, '-', 1)", $data['diary_number'])
                ->where("EXTRACT(YEAR FROM fil_dt)", $data['diary_year']);
        } elseif ($data['search_type'] === 'C') {
            $builder->select("SUBSTRING(CAST(diary_no AS TEXT), 1, LENGTH(CAST(diary_no AS TEXT)) - 4) as dn, SUBSTRING(CAST(diary_no AS TEXT), -4) as dy")
                ->where("SPLIT_PART(fil_no, '-', 1)", $data['case_type'])
                ->where("CAST(SPLIT_PART(fil_no, '-', 2) AS INT) <=", $data['case_number'])
                ->where("CAST(SPLIT_PART(fil_no, '-', 3) AS INT) >=", $data['case_number'])
                ->where("EXTRACT(YEAR FROM fil_dt)", $data['case_year']);
        }

        return $builder->get()->getRowArray();
    }



    public function getCaseName1($diary_no)
    {
        return $this->select('short_description')
            ->join('master.casetype b', 'b.casecode = main.casetype_id', 'left')
            ->where('main.diary_no', $diary_no)
            ->first();
    }
    public function getAdvanceDates()
    {
        return $this->db->table('advance_single_judge_allocated aa')
            ->select('aa.from_dt, aa.to_dt')
            ->distinct()
            ->join('single_judge_advance_cl_printed acp', 'aa.from_dt = acp.from_dt AND aa.to_dt = acp.to_dt AND acp.is_active = 1', 'left')
            ->where('acp.id IS NULL')
            ->orderBy('aa.from_dt', 'DESC')
            ->limit(1)
            ->get()
            ->getResultArray();
    }


    public function checkHeardTable1($diary_no)
    {

        $builder = $this->db->table('heardt');
        $builder->select('next_dt')
            ->where('diary_no', $diary_no);
        $result = $builder->get()->getRow();


        return $result ? $result : null;
    }

    public function getJudicialSections()
    {
        $builder = $this->db->table('master.usersection');
        return $builder->where('isda', 'Y')
            ->where('display', 'Y')
            ->orderBy('section_name')
            ->get()
            ->getResultArray();
    }


    //Old getUsers Logic Issue data not load
    // public function getUsers()
    // {
    //     return $this->select('u.usercode, u.name, u.empid')
    //         ->from('master.users u')
    //         ->join('master.usersection us', 'us.id = u.section')
    //         ->where('us.isda', 'Y')
    //         ->where('us.display', 'Y')
    //         ->where('u.display', 'Y')
    //         ->orderBy('u.name')
    //         ->findAll();
    // }

    public function getUsers()
    {
        $builder = $this->db->table('master.users u');
        
        return $builder
            ->select('u.usercode, u.name, u.empid')
            ->join('master.usersection us', 'us.id = u.section', 'left')
            ->where('us.isda', 'Y')
            ->where('us.display', 'Y')
            ->where('u.display', 'Y')
            ->orderBy('u.name')
            ->get()
            ->getResultArray();
    }

    public function getCaseDetails1($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.diary_no_rec_date, a.fil_dt, a.lastorder, a.pet_name, a.res_name, a.c_status, b.listorder, b.next_dt')
            ->join('heardt b', 'CAST(a.diary_no AS bigint) = CAST(b.diary_no AS bigint)', 'left') // Cast to bigint
            ->join('brdrem c', 'CAST(a.diary_no AS bigint) = CAST(c.diary_no AS bigint)', 'left') // Cast to bigint
            ->where('a.diary_no', $diary_no);

        return $builder->get()->getRowArray();
    }

    public function isCaseInVacationList($diary_no)
    {
        $builder = $this->db->table('vacation_advance_list');
        $builder->select('diary_no')
            ->where('diary_no', $diary_no)
            ->where('vacation_list_year', date('Y'));

        return $builder->countAllResults() > 0;
    }


    public function getMainCaseData($dno)
    {
        return $this->db->table('conct') // Use the table name property
            ->where('conn_key', $dno)
            ->orWhere('diary_no', $dno)
            ->get()
            ->getRowArray();
    }


    public function isCaseInVacationPool($diaryNo)
    {
        return $this->db->table('vacation_advance_list')
            ->where('diary_no', $diaryNo)
            ->where('vacation_list_year', date('Y'))
            ->countAllResults() > 0;
    }





    public function addCaseToVacationPoolbk($diaryNo, $userCode)
    {
        if ($this->isCaseInVacationPool($diaryNo)) {
            return false; // Case already exists in the vacation pool
        }

        $builder = $this->db->table('main');
        $diaryNumbers = $builder->select('diary_no, conn_key')
            ->whereIn('diary_no', function ($query) use ($diaryNo) {
                $query->select('conn_key')
                    ->from('conct')
                    ->where('conn_key', $diaryNo)
                    ->orWhere('diary_no', $diaryNo)
                    ->where('list', 'Y')
                    ->orWhere('list IS NULL');
            })
            ->orWhere('diary_no', $diaryNo)
            ->get()
            ->getResultArray();

        // Prepare data for insertion
        $data = [];
        foreach ($diaryNumbers as $row) {
            $data[] = [
                'diary_no' => $row['diary_no'],
                'conn_key' => $row['conn_key'],
                'updated_by' => $userCode,
                'updated_on' => date('Y-m-d H:i:s'),
                'vacation_list_year' => (int)date('Y')
            ];
        }

        if (!empty($data)) {
            // Use INSERT ... ON CONFLICT to handle duplicates
            $sql = "INSERT INTO vacation_advance_list (diary_no, conn_key, updated_by, updated_on, vacation_list_year)
                    VALUES ";

            $values = [];
            foreach ($data as $d) {
                $values[] = "('" . implode("', '", $d) . "')";
            }

            $sql .= implode(", ", $values);
            $sql .= " ON CONFLICT (diary_no) DO NOTHING"; // Ensure diary_no has a unique constraint

            return $this->db->query($sql);
        }

        return false; // No records to insert
    }


    // new

    public function getDiaryNumber_1($ct, $cn, $cy)
    {
        $builder = $this->db->table('main');
        $builder->select("substr(CAST(diary_no AS text), 1, length(CAST(diary_no AS text)) - 4) as dn, substr(CAST(diary_no AS text), -4) as dy");
        $builder->where("split_part(fil_no, '-', 1)", $ct);
        $builder->where("CAST($cn AS INTEGER) BETWEEN CAST(split_part(fil_no, '-', 2) AS INTEGER) AND CAST(split_part(fil_no, '-', 3) AS INTEGER)");
        $builder->where("(reg_year_mh = 0 AND EXTRACT(YEAR FROM fil_dt) = $cy) OR reg_year_mh = $cy");
        return $builder->get()->getRowArray();
    }




    public function getCaseDetails_1($dno)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.diary_no_rec_date, a.fil_dt, a.lastorder, a.pet_name, a.res_name, a.c_status, b.listorder, b.next_dt,b.tentative_cl_dt,b.clno,b.brd_slno,b.roster_id,b.judges,b.board_type,b.main_supp_flag,b.mainhead,b.subhead,a.case_grp,c.remark,b.sitting_judges, b.is_nmd');
        $builder->join('heardt b', 'CAST(a.diary_no AS bigint) = CAST(b.diary_no AS bigint)', 'left');
        $builder->join('brdrem c', 'CAST(a.diary_no AS bigint) = CAST(c.diary_no AS bigint)', 'left');
        $builder->where('a.diary_no', $dno);
        return $builder->get()->getRowArray();
    }

    public function getAllMainSupp()
    {
        $builder = $this->db->table('master.master_main_supp');
        $builder->where('display', 'Y');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getLatestReason($diaryNo)
    {
        $subQuery = $this->db->table('update_heardt_reason')
            ->select('diary_no, MAX(id) as id')
            ->where('diary_no', $diaryNo)
            ->groupBy('diary_no');

        $builder = $this->db->table('update_heardt_reason u');
        $builder->select('u.reason')
            ->join("({$subQuery->getCompiledSelect()}) as t", 'u.id = t.id', 'inner');

        $query = $builder->get();
        return $query->getRowArray();
    }






    public function getCaseName($dno)
    {

        $builder = $this->db->table('main a');
        $builder->select('b.short_description,a.fil_no, a.fil_dt, a.fil_no_fh, a.fil_dt_fh,COALESCE(NULLIF(a.reg_year_mh, 0), EXTRACT(YEAR FROM a.fil_dt)) AS m_year,
                          COALESCE(NULLIF(a.reg_year_fh, 0), EXTRACT(YEAR FROM a.fil_dt_fh)) AS f_year');

        $builder->join('master.casetype b', 'b.casecode = a.casetype_id', 'left');
        $builder->where('a.diary_no', $dno);

        return $builder->get()->getRowArray();
    }




    public function getHearingDetails_1($dno)
    {

        $builder = $this->db->table('heardt');
        $builder->select('next_dt');
        $builder->where('diary_no', $dno);

        return $builder->get()->getRowArray();
    }

    public function getCategories($dno)
    {

        $builder = $this->db->table('mul_category a');
        $builder->select('b.sub_name1, b.sub_name2, b.sub_name3, b.sub_name4');
        $builder->join('master.submaster b', 'b.id = a.submaster_id', 'left');
        $builder->where('a.display', 'Y');
        $builder->where('a.diary_no', $dno);

        return $builder->get()->getResultArray();
    }

    public function getMainOrConnectedCase($diary_no)
    {
        $builder = $this->db->table('conct');
        $builder->select('conn_key,diary_no');
        $builder->where('conn_key', $diary_no);
        $builder->orWhere('diary_no', $diary_no);
        return $builder->get()->getRowArray();
    }


    public function getCoramEntries($diary_no)
    {

        $firstPart = $this->db->table('heardt h')
            ->select('jcode, STRING_AGG(jname, \' \') AS jname, CAST(h.diary_no AS TEXT), \'C\' AS notbef, ent_dt, res_add')
            ->join('master.judge j', "position(',' || jcode || ',' in ',' || coram || ',') > 0", 'inner')
            ->join('master.not_before_reason', 'list_before_remark = res_id', 'left')
            ->where('h.diary_no', $diary_no)
            ->groupBy('jcode, h.diary_no, ent_dt, res_add');
        $secondPart = $this->db->table('not_before')
            ->select('jcode, CAST(jname AS TEXT), CAST(diary_no AS TEXT), not_before.notbef, ent_dt, not_before_reason.res_add')
            ->join('master.judge j', 'jcode = j1', 'left')
            ->join('master.not_before_reason', 'not_before.res_id = not_before_reason.res_id', 'left')
            ->where('diary_no', $diary_no);
        $query = $firstPart->getCompiledSelect() . ' UNION ' . $secondPart->getCompiledSelect();

        return $this->db->query($query)->getResultArray();
    }

    public function getCoramEntries1($diary_no)
    {
        // SQL query
        $sql = "
        SELECT 
            j.jcode,
            STRING_AGG(j.jname, ' ') AS jname,
            h.diary_no,
            'C' AS notbef,
            h.ent_dt,
            nbre.res_add
        FROM 
            heardt h
        JOIN 
            master.judge j ON j.jcode = ANY(string_to_array(h.coram, ',')::int[])  -- Casting to integer
        LEFT JOIN 
            master.not_before_reason nbre ON h.list_before_remark = nbre.res_id
        WHERE 
            h.diary_no = $1
        GROUP BY 
            h.diary_no, j.jcode, nbre.res_add, h.ent_dt
    
        UNION
    
        SELECT 
            j.jcode,
            j.jname,
            nb.diary_no,
            nb.notbef,
            nb.ent_dt,
            nbre.res_add 
        FROM 
            master.not_before nb
        LEFT JOIN 
            master.judge j ON j.jcode = nb.j1
        LEFT JOIN 
            master.not_before_reason nbre ON nb.res_id = nbre.res_id
        WHERE 
            nb.diary_no = $1;";

        // Execute the query with parameter binding
        $result = $this->db->query($sql, [$diary_no])->getResultArray();

        return $result;
    }

    public function getJudges1($m_f, $todate, $board_type)
    {
        return $this->db->table('master.roster r')
            ->select('r.m_f, r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) AS jcd, GROUP_CONCAT(CONCAT(j.first_name, \' \', j.sur_name) ORDER BY j.judge_seniority) AS jnm, rb.bench_no, mb.abbr, r.tot_cases, mb.board_type_mb')
            ->join('master.roster_bench rb', 'rb.id = r.bench_id', 'left')
            ->join('master.master_bench mb', 'mb.id = rb.bench_id', 'left')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id', 'left')
            ->join('master.judge j', 'j.jcode = rj.judge_id', 'left')
            ->where('j.is_retired !=', 'Y')
            ->where('j.display', 'Y')
            ->where('rj.display', 'Y')
            ->where('rb.display', 'Y')
            ->where('mb.display', 'Y')
            ->where('r.display', 'Y')
            ->where('1=1' . $m_f . $todate . $board_type)
            // ->groupBy('r.id')
            // ->orderBy('r.id, j.judge_seniority')
            ->get()->getResultArray();
    }

    public function getJudges12($m_f, $todate, $board_type)
    {
        $sql = "
        SELECT 
            r.m_f, 
            r.id, 
            STRING_AGG(j.jcode::text, ',' ORDER BY j.judge_seniority) AS jcd,
            STRING_AGG(CONCAT(j.first_name, ' ', j.sur_name), ' ' ORDER BY j.judge_seniority) AS jnm,
            rb.bench_no, 
            mb.abbr, 
            r.tot_cases, 
            mb.board_type_mb
        FROM 
            master.roster r
        LEFT JOIN 
            master.roster_bench rb ON rb.id = r.bench_id
        LEFT JOIN 
            master.master_bench mb ON mb.id = rb.bench_id
        LEFT JOIN 
            master.roster_judge rj ON rj.roster_id = r.id
        LEFT JOIN 
            master.judge j ON j.jcode = rj.judge_id
        WHERE 
            j.is_retired != 'Y' 
            AND j.display = 'Y' 
            AND rj.display = 'Y' 
            AND rb.display = 'Y' 
            AND mb.display = 'Y'
            AND r.display = 'Y' 
            $m_f 
             $todate
             $board_type 
        GROUP BY 
            r.id, r.m_f, rb.bench_no, mb.abbr, mb.board_type_mb
        ORDER BY 
            r.id, MIN(j.judge_seniority);"; // Use MIN to ensure correct ordering

        return $this->db->query($sql)->getResultArray();
    }









    public function getDetails($diary_no)
    {
        return $this->db->table('details')
            ->where('diary_no', $diary_no)
            ->limit(1)
            ->get()
            ->getFirstRow();
    }

    public function getJudges($diaryNo)
    {
        return $this->db->table('master.roster r')
            ->select('r.m_f, r.id, GROUP_CONCAT(j.jcode ORDER BY j.judge_seniority) AS jcd, GROUP_CONCAT(CONCAT(j.first_name, " ", j.sur_name) ORDER BY j.judge_seniority) AS jnm')
            ->join('master.roster_judge rj', 'rj.roster_id = r.id')
            ->join('master.judge j', 'j.jcode = rj.judge_id')
            ->get()
            ->getResultArray();
    }

    public function getListEntries($diaryNo)
    {
        return $this->db->table('heardt h')
            ->select('jcode, GROUP_CONCAT(jname, " ") AS jname, h.diary_no, "C" AS notbef, ent_dt, res_add')
            ->join('master.judge j', 'FIND_IN_SET(jcode, coram) > 0')
            ->join('master.not_before_reason', 'list_before_remark = res_id', 'left')
            ->where('h.diary_no', $diaryNo)
            //->groupBy('h.diary_no')
            ->get()
            ->getResultArray();
    }
    public function getInterlocutoryApplications($diaryNo)
    {
        return $this->db->table('docdetails a')
            ->select('a.doccode, a.doccode1, docnum, docyear, filedby, other1, iastat, b.docdesc')
            ->join('master.docmaster b', 'a.doccode = b.doccode AND a.doccode1 = b.doccode1', 'left')
            ->where([
                'a.doccode' => '8',
                'a.iastat' => 'P',
                'a.diary_no' => $diaryNo,
                'a.display' => 'Y',
                'b.display' => 'Y'
            ])
            ->orderBy('ent_dt, docyear, docnum')
            ->get()->getResultArray();
    }
    // public function checkIfListIsPrinted($date, $heading, $coram, $session, $mainSuppFlag)
    // {
    //     $query = $this->db->table('cl_printed')
    //         ->where('next_dt', $date)
    //         ->where('next_dt >=', date('Y-m-d'))
    //         ->where('m_f', $heading)
    //         ->where('roster_id', $coram)
    //         ->where('part', $session)
    //         ->where('main_supp', $mainSuppFlag)
    //         ->where('display', 'Y');

    //     return $query->countAllResults() > 0 ? 1 : 0;
    // }

    public function isListPrinted_old($date, $heading, $coram, $session, $mainSuppFlag)
    {
        $builder = $this->db->table('cl_printed');
        $builder->select('id')
            ->where('next_dt', $date)
            ->where('next_dt >=', date('Y-m-d'))
            ->where('m_f', $heading)
            ->where('roster_id', $coram)
            ->where('part', $session)
            ->where('main_supp', $mainSuppFlag)
            ->where('display', 'Y');
        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    public function isListPrinted($date, $heading, $coram, $session, $mainSuppFlag)
    {
        return $this->db->table('cl_printed')
            ->where('next_dt', $date)
            ->where('next_dt >=', date('Y-m-d'))
            ->where('m_f', $heading)
            ->where('roster_id', $coram)
            ->where('part', $session)
            ->where('main_supp', $mainSuppFlag)
            ->where('display', 'Y')
            ->get()
            ->getResultArray();
    }


    public function getDiary()
    {
        return $this->db->query("
            SELECT DISTINCT 
                val.diary_no,
                val.conn_key,
                CASE
                    WHEN (val.diary_no = val.conn_key
                        OR val.conn_key = 0
                        OR val.conn_key::text = ''
                        OR val.conn_key IS NULL)
                    THEN 0
                    ELSE 1
                END AS main_or_connected,
                val.is_fixed,
                CONCAT(m.reg_no_display, ' @ ', 
                    CONCAT(SUBSTRING(val.diary_no::text, 1, LENGTH(val.diary_no::text) - 4), ' / ', 
                        SUBSTRING(val.diary_no::text, -4))) AS case_no,
                TO_CHAR(m.diary_no_rec_date, 'DD-MM-YYYY') AS filing_date,
                CONCAT(COALESCE(m.pet_name, ''), ' Vs. ', COALESCE(m.res_name, '')) AS cause_title,
                val.is_deleted AS declined_by_admin,
                CASE WHEN val.is_fixed = 'Y' THEN 1 ELSE 99 END AS fixed_order,
                CASE 
                    WHEN val.conn_key = 0 OR val.conn_key IS NULL OR val.conn_key::text = '' 
                    OR val.conn_key = val.diary_no THEN val.diary_no 
                    ELSE val.conn_key 
                END AS order_key
            FROM 
                vacation_advance_list val
            INNER JOIN 
                main m ON val.diary_no = m.diary_no
            WHERE 
                vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE)
            ORDER BY 
                fixed_order,
                order_key,
                main_or_connected ASC
        ")->getResultArray();
    }



    public function logVacationAdvances($diaryNo)
    {
        $db = \Config\Database::connect();
        $diaryNosArray = explode(',', $diaryNo);
        $diaryNosArray = array_map('intval', $diaryNosArray);
        $diaryNosString = implode(',', $diaryNosArray);
        $sql = "INSERT INTO vacation_advance_list_log 
                SELECT * 
                FROM vacation_advance_list 
                WHERE vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE) 
                AND diary_no IN ($diaryNosString) 
                AND is_deleted = 'f'";
        return $db->query($sql);
    }


    public function updateVacationAdvances($diaryNos, $userID, $updatedFromIP)
    {
        return $this->db->table('vacation_advance_list')
            ->set([
                'is_deleted' => 't',
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $userID,
                'updated_from_ip' => $updatedFromIP,
            ])
            ->whereIn('diary_no', explode(',', $diaryNos))
            ->where('is_deleted', 'f')
            ->where('vacation_list_year', date('Y'))
            ->update();
    }


    public function getVacationAdvance($diaryNo)
    {
        return $this->db->table('vacation_advance_list')
            ->where('diary_no', $diaryNo)
            ->where('vacation_list_year', date('Y'))
            ->get()
            ->getRow();
    }


    public function getAdvocates($diaryNo)
    {
        $sql = "SELECT 
                STRING_AGG(DISTINCT CONCAT(COALESCE(b.name, ''), 
                '<font color=\"red\" weight=\"bold\">', 
                
                '</font>'), '<br/>') AS advocate
            FROM vacation_advance_list_advocate v 
            INNER JOIN master.bar b ON b.aor_code = v.aor_code
            WHERE v.diary_no = ? 
              AND v.vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE) 
              AND b.if_aor = 'Y' 
              AND isdead = 'N' 
            GROUP BY v.diary_no";

        $result = $this->db->query($sql, [$diaryNo])->getRowArray();

        // Debugging: Log the result to check its contents
        log_message('debug', 'Advocates Query Result: ' . print_r($result, true));

        return $result;
    }








    public function getReportData($filters)
    {

    
        $to_list_date = date('d-m-Y', strtotime($filters['to_list_date']));
        $from_list_date = date('d-m-Y', strtotime($filters['from_list_date']));
        //For Diary Date Shorting
        $to_diary_date = date('d-m-Y', strtotime($filters['to_diary_date']));
        $from_diary_date = date('d-m-Y', strtotime($filters['from_diary_date']));

        $builder = $this->db->table($this->table . ' m')
            //->select('count(DISTINCT m.diary_no) as total_cases, GROUP_CONCAT(DISTINCT m.diary_no, \', \') as dnos')
            ->select('count(distinct m.diary_no) as total_cases, string_agg(DISTINCT m.diary_no::text, \',\') as dnos')
            ->join('heardt h', 'm.diary_no = h.diary_no')
            ->join('mul_category mc', 'mc.diary_no = m.diary_no')
            ->where('m.c_status', 'P');

        // Date Filters
        if (!empty($from_list_date) && !empty($to_list_date) && $from_list_date !== '01-01-1970' && $to_list_date !== '01-01-1970') {
            $builder->where('date(h.next_dt) >=', $from_list_date);
            $builder->where('date(h.next_dt) <=', $to_list_date);
        }
        if (!empty($from_diary_date) && !empty($to_diary_date && $from_list_date !== '01-01-1970' && $to_list_date !== '01-01-1970')) {
            $builder->where('date(m.diary_no_rec_date) >=', $from_diary_date);
            $builder->where('date(m.diary_no_rec_date) <=', $to_diary_date);
        }

        // Additional Filters
        if (!empty($filters['mainhead'])) {
            $builder->where('h.mainhead', $filters['mainhead']);
        }
        if (!empty($filters['board_type'])) {
            $builder->where('h.board_type', $filters['board_type']);
        }

        // Connected Cases
        if (!empty($filters['connected']) && is_array($filters['connected']) && in_array(1, $filters['connected'])) {
            $builder->groupStart()
                ->where('m.conn_key', 'm.diary_no')
                ->orWhere('m.conn_key IS NULL')
                ->orWhere('m.conn_key', '')
                ->orWhere('m.conn_key', 0)
                ->groupEnd();
        }

        // Status Filters
        if (!empty($filters['status'])) {
            $statusQuery = [];
            if (is_array($filters['status'])) {
                if (in_array(1, $filters['status'])) {
                    $statusQuery[] = "h.main_supp_flag = 0";
                }
                if (in_array(2, $filters['status'])) {
                    $statusQuery[] = "(h.main_supp_flag IN (1,2) AND h.next_dt < CURRENT_DATE)";
                }
                if (in_array(3, $filters['status'])) {
                    $statusQuery[] = "h.main_supp_flag = 3";
                }
                if (in_array(4, $filters['status'])) {
                    $statusQuery[] = "(h.main_supp_flag IN (1,2) AND h.next_dt >= CURRENT_DATE)";
                }
                if (!empty($statusQuery)) {
                    $builder->groupStart()
                        ->where(implode(' OR ', $statusQuery))
                        ->groupEnd();
                }
            }
        }

        // Judge Filters
        if (!empty($filters['judge'])) {
            $judgeNot = !empty($filters['judge_exclude']) && $filters['judge_exclude'] == 'y' ? 'NOT ' : '';
            $judgeConditions = [];
            if (is_array($filters['judge'])) {
                foreach ($filters['judge'] as $jcode) {
                    if (!empty($filters['only_presiding']) && $filters['only_presiding'] == 'y') {
                        $judgeConditions[] = "$judgeNot SUBSTRING_INDEX(h.coram, ',', 1) = $jcode";
                    } else {
                        $judgeConditions[] = "$judgeNot FIND_IN_SET($jcode, h.coram) > 0";
                    }
                }
                if (!empty($judgeConditions)) {
                    $builder->groupStart()
                        ->where(implode(' OR ', $judgeConditions))
                        ->groupEnd();
                }
            }
        }

        // Category Filters
        if (!empty($filters['category'])) {
            $categoryNot = !empty($filters['category_exclude']) && $filters['category_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['category'])) {
                $builder->where("mc.submaster_id $categoryNot IN (" . implode(',', $filters['category']) . ") AND mc.display = 'Y'");
            }
        }

        // Case Type Filters
        if (!empty($filters['case_type'])) {
            $caseTypeNot = !empty($filters['case_type_exclude']) && $filters['case_type_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['case_type'])) {
                $builder->where("m.active_casetype_id $caseTypeNot IN (" . implode(',', $filters['case_type']) . ")");
            }
        }

        // Section Filters
        if (!empty($filters['section'])) {
            $sectionNot = !empty($filters['section_exclude']) && $filters['section_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['section'])) {
                $builder->where("m.section_id $sectionNot IN (" . implode(',', $filters['section']) . ")");
            }
        }

        // DA Filters
        if (!empty($filters['da'])) {
            $daNot = !empty($filters['da_exclude']) && $filters['da_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['da'])) {
                $builder->where("m.dacode $daNot IN (" . implode(',', $filters['da']) . ")");
            }
        }

        // Subhead Filters
        if (!empty($filters['subhead'])) {
            $subheadNot = !empty($filters['subhead_exclude']) && $filters['subhead_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['subhead'])) {
                $builder->where("h.subhead $subheadNot IN (" . implode(',', $filters['subhead']) . ")");
            }
        }

        // List Order Filters
        if (!empty($filters['lp'])) {
            $lpNot = !empty($filters['lp_exclude']) && $filters['lp_exclude'] == 'y' ? 'NOT ' : '';
            if (is_array($filters['lp'])) {
                $builder->where("h.listorder $lpNot IN (" . implode(',', $filters['lp']) . ")");
            }
        }

        // Coram by CJI Filters
        if (!empty($filters['coram_by_cji'])) {
            if ($filters['coram_by_cji'] == 'n') {
                $builder->where('h.list_before_remark !=', 11);
            } else {
                $builder->where('h.list_before_remark', 11);
            }
        }

        // Conditional Matter Filters
        if (!empty($filters['conditional_matter'])) {
            $builder->join('rgo_default rd', 'rd.fil_no = h.diary_no AND rd.remove_def = "N"', 'left');
            if ($filters['conditional_matter'] == 'n') {
                $builder->where('rd.fil_no IS NULL');
            } else {
                $builder->where('rd.fil_no IS NOT NULL');
            }
        }

        // Sensitive Cases Filters
        if (!empty($filters['sensitive'])) {
            $builder->join('sensitive_cases sc', 'sc.diary_no = m.diary_no AND sc.display = "Y"', 'left');
            if ($filters['sensitive'] == 'n') {
                $builder->where('sc.diary_no IS NULL');
            } else {
                $builder->where('sc.diary_no IS NOT NULL');
            }
        }

        // CAV Matter and List After Vacation Filters
        if (!empty($filters['cav_matter']) || !empty($filters['list_after_vacation'])) {
            $cavOrListAfterVacation = [];
            if ($filters['list_after_vacation'] == 'n' || $filters['cav_matter'] == 'n') {
                $cavOrListAfterVacation[] = "(m.lastorder = '' OR m.lastorder IS NULL OR m.lastorder != '' OR m.lastorder IS NOT NULL)";
                if ($filters['list_after_vacation'] == 'n') {
                    $cavOrListAfterVacation[] = "m.lastorder NOT LIKE '%after vacation%'";
                }
                if ($filters['cav_matter'] == 'n') {
                    $cavOrListAfterVacation[] = "m.lastorder NOT LIKE '%Heard & Reserved%'";
                }
            }
            if ($filters['list_after_vacation'] == 'y' || $filters['cav_matter'] == 'y') {
                if ($filters['list_after_vacation'] == 'y') {
                    $cavOrListAfterVacation[] = "m.lastorder LIKE '%after vacation%'";
                }
                if ($filters['cav_matter'] == 'y') {
                    $cavOrListAfterVacation[] = "m.lastorder LIKE '%Heard & Reserved%'";
                }
            }
            if (!empty($cavOrListAfterVacation)) {
                $builder->groupStart()
                    ->where(implode(' OR ', $cavOrListAfterVacation))
                    ->groupEnd();
            }
        }

        // Part Heard Filters
        if (!empty($filters['part_heard'])) {
            if ($filters['part_heard'] == 'n') {
                $builder->where('m.part_heard', 'N');
            } else {
                $builder->where('m.part_heard', 'Y');
            }
        }
        $builder->limit(1);
        $subQuery = $this->db->table('main ms')
            ->select('ms.diary_no')
            ->distinct()
            ->where('ms.c_status', 'P')
            ->limit(1000)
            ->getCompiledSelect();
        $subQueryResults = $this->db->query($subQuery)->getResultArray();
        $diaryNos = array_column($subQueryResults, 'diary_no');

        // Use the flat array in whereIn()
        $builder->whereIn('m.diary_no', $diaryNos);

        // echo $builder->getCompiledSelect();
        // die();
        // Finalizing the Query
        $query = $builder->get();
        return $query->getRow();
    }
    

    public function getReportDataUsingCol($filters, $add_columns, $number_of_rows, $sort_by2)
    {
        $select_columns = '';
        $inner_left_join = '';
        $category2_join = '';
        $limit = '';
        $group_by = [];
        if ($number_of_rows > 0) {
            $limit = "LIMIT " . intval($number_of_rows);
        }
    
        if (!empty($add_columns)) {
            if (in_array('case_no_with_dno', $add_columns)) {
                // Using CONCAT() to combine reg_no_display and diary_no
                $select_columns .= "m.diary_no, CONCAT(m.reg_no_display, ' @ ', m.diary_no) AS case_no_with_dno, ";
                $group_by[] = 'case_no_with_dno';
            }
            if (in_array('diary_no', $add_columns)) {
                $select_columns .= "m.diary_no, ";
            }
            if (in_array('reg_no_display', $add_columns)) {
                $select_columns .= "m.reg_no_display, ";
                $group_by[] = 'm.reg_no_display';
            }
            if (in_array('cause_title', $add_columns)) {
                $select_columns .= "CONCAT(m.pet_name, ' Vs. ', m.res_name) AS causetitle, ";
                $group_by[] = 'causetitle';
            }
            if (in_array('coram', $add_columns)) {
                // Replace MySQL GROUP_CONCAT with PostgreSQL STRING_AGG,
                // and use string_to_array() with ANY() instead of FIND_IN_SET()
                $select_columns .= "COALESCE((SELECT STRING_AGG(abbreviation, ', ' ORDER BY judge_seniority) FROM master.judge WHERE is_retired = 'N' AND display = 'Y' AND jcode = ANY(string_to_array(h.coram, ',') :: INTEGER[])), '') AS Coram, ";
                $group_by[] = 'Coram';
            }
            if (in_array('category', $add_columns)) {
                $category2_join = "INNER JOIN mul_category mc ON mc.diary_no = h.diary_no AND mc.display = 'Y'
                                   INNER JOIN master.submaster s ON mc.submaster_id = s.id AND s.display = 'Y'";
                $select_columns .= "CASE WHEN (s.category_sc_old IS NOT NULL AND s.category_sc_old <> '' AND cast(s.category_sc_old as INTEGER) <> 0) 
                                     THEN CONCAT('(', s.category_sc_old, ')', s.sub_name1, '-', s.sub_name4) 
                                     ELSE CONCAT('(', CONCAT(s.subcode1, s.subcode2), ')', s.sub_name1, '-', s.sub_name4) 
                                     END AS CATEGORY, ";
                $group_by[] = 'CATEGORY';
            }
            
            if (in_array('connected_count', $add_columns)) {
                $select_columns .= "COALESCE(cc.total_connected, '0') AS connected_count, ";
                $inner_left_join .= " LEFT JOIN (
                                          SELECT n.conn_key, COUNT(*) AS total_connected 
                                          FROM main m
                                          INNER JOIN heardt h ON m.diary_no = h.diary_no
                                          INNER JOIN main n ON cast(m.diary_no as TEXT) = cast(n.conn_key as TEXT)
                                          WHERE cast(n.diary_no as TEXT) <> cast(n.conn_key as TEXT) AND m.c_status = 'P'
                                          GROUP BY n.conn_key
                                      ) cc ON cast(m.diary_no as TEXT) = cast(cc.conn_key as TEXT)";
                 $group_by[] = 'connected_count';
            }
            
            if (in_array('tentative_date', $add_columns)) {
                $select_columns .= "to_char(h.next_dt, 'DD-MM-YYYY') AS Next_Listing_Dt, ";
                $group_by[] = 'Next_Listing_Dt';
            }
            
            if (in_array('lastorder', $add_columns)) {
                $select_columns .= "m.lastorder, ";
                $group_by[] = 'm.lastorder';
            }
            
            if (in_array('section', $add_columns)) {
                $select_columns .= "tentative_section(m.diary_no) AS SECTION, ";
                $group_by[] = 'SECTION';
            }
            
            if (in_array('da', $add_columns)) {
                $select_columns .= "tentative_da(cast(m.diary_no as integer)) AS DA, ";
                $group_by[] = 'DA';
            }
            
            if (in_array('advocate_name', $add_columns)) {
                $select_columns .= "STRING_AGG(bar.name, ', ') AS Advocate_Name, ";
                $inner_left_join .= " LEFT JOIN advocate ON advocate.diary_no = m.diary_no AND advocate.display = 'Y'
                                       LEFT JOIN master.bar ON bar.bar_id = advocate.advocate_id AND bar.if_aor = 'Y' 
                                                       AND bar.isdead = 'N' AND bar.if_sen = 'N' ";
            }
            
            if (in_array('notice_date', $add_columns)) {
                $select_columns .= "to_char(crm.cl_date, 'DD-MM-YYYY') AS Notice_Date, ";
                $inner_left_join .= " LEFT JOIN case_remarks_multiple crm ON crm.diary_no = m.diary_no 
                                       AND crm.r_head IN (3,62,181,182,183,184,203) ";
                 $group_by[] = 'Notice_Date';
            }
            
            if (in_array('admitted_on', $add_columns)) {
                $select_columns .= "to_char(m.fil_dt_fh, 'DD-MM-YYYY') AS Admitted_On, ";
                $group_by[] = 'Admitted_On';
            }
            
    
            $select_columns = rtrim($select_columns, ', ');
        } else {
            throw new \Exception("Please select at least one column.");
        }
    
        $sort_by_query = '';
        if (!empty($sort_by2)) {
            $sort_by_query = "ORDER BY ";
            foreach ($sort_by2 as $sort_by_value) {
                if ('diary_no' == $sort_by_value) {
                    // Cast diary_no to text first so that RIGHT() and LEFT() work
                    $sort_by_query .= "CAST(RIGHT(CAST(m.diary_no AS text), 4) AS INTEGER) ASC, CAST(LEFT(CAST(m.diary_no AS text), LENGTH(CAST(m.diary_no AS text)) - 4) AS INTEGER) ASC, ";
                }
                if ('section' == $sort_by_value) {
                    $sort_by_query .= "SECTION, ";
                }
                if ('da' == $sort_by_value) {
                    $sort_by_query .= "DA, ";
                }
                if ('category' == $sort_by_value) {
                    $sort_by_query .= "CATEGORY, ";
                }
                if ('coram' == $sort_by_value) {    
                    $sort_by_query .= "Coram, ";//seniority_code
                }
                if ('tentative_date' == $sort_by_value) {
                    $sort_by_query .= "h.next_dt, ";
                    $group_by[] = 'h.next_dt';
                }
            }
            $sort_by_query = rtrim($sort_by_query, ', ');
        } else {
            throw new \Exception("Please select Sort by field.");
        }
        $group_by[] = 'm.diary_no';
        $grp_str = implode(', ', $group_by);
        $sql = "SELECT $select_columns FROM main m
                INNER JOIN heardt h ON m.diary_no = h.diary_no 
                $category2_join
                $inner_left_join
                WHERE m.c_status = 'P' 
                GROUP BY $grp_str 
                $sort_by_query 
                $limit";
        //die();
        $query = $this->db->query($sql);
        $result = $query->getResultArray();

        return $query->getResultArray();
    }
    

    public function getReportDataUsingDiaryNo($diaryNumbersString)
    {
        $builder = $this->db->table('main m');
        
        // Select the necessary columns from 'main' table
        $builder->select(
            'm.diary_no, ' .
            'tentative_section(m.diary_no) as section_name, ' .
            'm.lastorder, ' .
            'm.active_fil_no, ' .
            'm.active_reg_year, ' .
            'm.casetype_id, ' .
            'm.active_casetype_id, ' .
            'm.ref_agency_state_id, ' .
            'm.reg_no_display, ' .
            'EXTRACT(YEAR FROM m.fil_dt) as fil_year, ' .
            'm.fil_no, ' .
            'm.conn_key as main_key, ' .
            'm.fil_dt, ' .
            'm.fil_no_fh, ' .
            'm.reg_year_fh as fil_year_f, ' .
            'm.mf_active, ' .
            'm.pet_name, ' .
            'm.res_name, ' .
            'pno, ' .
            'rno, ' .
            'm.diary_no_rec_date, ' .
            'h.*'
        );

        // Apply any necessary filters
        if (!empty($diaryNumbersString)) {
            $diaryNumbersArray = array_map('trim', array_filter(explode(',', $diaryNumbersString)));
            if (!empty($diaryNumbersArray)) {
                $builder->whereIn('m.diary_no', $diaryNumbersArray);
            }
        }
        $builder->join('heardt h', 'm.diary_no = h.diary_no');
        $builder->join('master.listing_purpose l', 'l.code = h.listorder');
        $builder->join('rgo_default rd', "rd.fil_no = h.diary_no AND rd.remove_def = 'N'");
        $builder->join('mul_category mc', "mc.diary_no= m.diary_no AND mc.display = 'Y'");
        $builder->join('master.submaster s', "mc.submaster_id = s.id and s.flag = 's' and s.display = 'Y' and (s.category_sc_old is not null and s.category_sc_old !='')");
        $builder->join('case_info ci', "ci.diary_no = h.diary_no and ci.display = 'Y'");

        // Order by the desired columns
        $builder->orderBy('CAST(RIGHT(CAST(m.diary_no AS text), 4) AS INTEGER) ASC, CAST(LEFT(CAST(m.diary_no AS text), LENGTH(CAST(m.diary_no AS text)) - 4) AS INTEGER) ASC');
        // echo $builder->getCompiledSelect();
        // die();
        return $builder->get()->getResultArray();
    }




    public function getCases1($listingDts, $listType, $judgeCode, $courtNo)
    {
        $mainheadQuery = '';
        $boardTypeQuery = '';
        $judgeCodeQuery = '';

        if ($listType && $listType != 0) {
            switch ($listType) {
                case 4:
                    $mainheadQuery = "AND h.mainhead = 'M'";
                    $boardTypeQuery = "AND h.board_type = 'J'";
                    break;
                case 3:
                    $mainheadQuery = "AND h.mainhead = 'F'";
                    $boardTypeQuery = "AND h.board_type = 'J'";
                    break;
                case 5:
                    $mainheadQuery = "AND h.mainhead = 'M'";
                    $boardTypeQuery = "AND h.board_type = 'C'";
                    break;
                case 6:
                    $mainheadQuery = "AND h.mainhead = 'M'";
                    $boardTypeQuery = "AND h.board_type = 'R'";
                    break;
                default:
                    throw new \Exception("List Type Not Defined");
            }
        }

        if ($courtNo > 0) {
            if ($courtNo < 20) {
                $judgeCodeQuery = "AND (r.courtno = $courtNo OR r.courtno = " . ($courtNo + 30) . ")";
            } else if ($courtNo >= 21) {
                $judgeCodeQuery = "AND (r.courtno = $courtNo OR r.courtno = " . ($courtNo + 40) . ")";
            }
        } else if ($judgeCode > 0) {
            $judgeCodeQuery = "AND rj.judge_id = $judgeCode";
        }

        // Build the SQL query
        $sql = "SELECT 
  cl.id AS is_printed,
  m.reg_no_display,
  m.pet_name,
  m.res_name,
  h.main_supp_flag,
  h.board_type,
  h.judges,
  h.roster_id,
  h.brd_slno,
  h.clno,
  h.mainhead,
  h.next_dt,
  h.conn_key,
  h.diary_no,
  r.courtno,
  STRING_AGG(advocate_id::text, ',') AS advocate_ids,
  COUNT(DISTINCT advocate_id) AS total_advocates 
FROM 
  main m
INNER JOIN 
  heardt h ON m.diary_no = h.diary_no
LEFT JOIN 
  conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
INNER JOIN 
  master.roster r ON h.roster_id = r.id
INNER JOIN 
  master.roster_judge rj ON rj.roster_id = r.id
INNER JOIN 
  advocate a ON m.diary_no = a.diary_no
INNER JOIN 
  cl_printed cl ON h.next_dt = cl.next_dt 
          AND cl.part = h.clno 
          AND h.roster_id = cl.roster_id 
          AND cl.display = 'Y'
WHERE 
  a.display = 'Y' 
  AND r.display = 'Y' 
  AND rj.display = 'Y' 
  AND m.c_status = 'P' 
 
  AND h.brd_slno > 0
GROUP BY 
  m.diary_no,h.diary_no,
  cl.id,
  m.reg_no_display,
  m.pet_name,
  m.res_name,
  h.main_supp_flag,
  h.board_type,
  h.judges,
  h.roster_id,
  h.brd_slno,
  h.clno,
  h.mainhead,
  h.next_dt,
  h.conn_key,
  r.courtno,ct.ent_dt
ORDER BY 
  r.courtno, 
  h.next_dt,
  h.brd_slno,
  CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END ASC,
  COALESCE(ct.ent_dt::text, '999') ASC,
  CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
  CAST(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC;";



        //     $sql = "SELECT 
        //     cl.id AS is_printed,
        //     m.reg_no_display,
        //     m.pet_name,
        //     m.res_name,
        //     h.main_supp_flag,
        //     h.board_type,
        //     h.judges,
        //     h.roster_id,
        //     h.brd_slno,
        //     h.clno,
        //     h.mainhead,
        //     h.next_dt,
        //     h.conn_key,
        //     h.diary_no,
        //     r.courtno,
        //     STRING_AGG(advocate_id::text, ',') AS advocate_ids,
        //     COUNT(DISTINCT advocate_id) AS total_advocates 
        //   FROM 
        //     main m
        //   INNER JOIN 
        //     heardt h ON m.diary_no = h.diary_no
        //   LEFT JOIN 
        //     conct ct ON m.diary_no = ct.diary_no AND ct.list = 'Y'
        //   INNER JOIN 
        //     master.roster r ON h.roster_id = r.id
        //   INNER JOIN 
        //     master.roster_judge rj ON rj.roster_id = r.id
        //   INNER JOIN 
        //     advocate a ON m.diary_no = a.diary_no
        //   INNER JOIN 
        //     cl_printed cl ON h.next_dt = cl.next_dt 
        //             AND cl.part = h.clno 
        //             AND h.roster_id = cl.roster_id 
        //             AND cl.display = 'Y'
        //   WHERE 
        //     a.display = 'Y' 
        //     AND r.display = 'Y' 
        //     AND rj.display = 'Y' 
        //     AND m.c_status = 'P' 
        //     AND h.next_dt = '$listing_dts' 
        //     $mainhead_query 
        //   $board_type_query 
        //    $judge_code_query 
        //     AND h.brd_slno > 0
        //   GROUP BY 
        //     m.diary_no,h.diary_no,
        //     cl.id,
        //     m.reg_no_display,
        //     m.pet_name,
        //     m.res_name,
        //     h.main_supp_flag,
        //     h.board_type,
        //     h.judges,
        //     h.roster_id,
        //     h.brd_slno,
        //     h.clno,
        //     h.mainhead,
        //     h.next_dt,
        //     h.conn_key,
        //     r.courtno,ct.ent_dt
        //   ORDER BY 
        //     r.courtno, 
        //     h.next_dt,
        //     h.brd_slno,
        //     CASE WHEN h.conn_key = h.diary_no THEN 1 ELSE 99 END ASC,
        //     COALESCE(ct.ent_dt::text, '999') ASC,
        //     CAST(SUBSTRING(m.diary_no::text FROM LENGTH(m.diary_no::text) - 3 FOR 4) AS INTEGER) ASC,
        //     CAST(LEFT(m.diary_no::text, LENGTH(m.diary_no::text) - 4) AS INTEGER) ASC;";

        return $this->db->query($sql)->getResultArray();
    }


    public function getRopDetails($diary_no)
    {
        if (empty($diary_no)) {
            return [];
        }
        $return = [];
        $sql = "SELECT
                    diary_no,jm AS pdfname,dated AS orderdate
                    FROM
                    (SELECT
                    o.diary_no diary_no,
                    o.jm jm,
                    CAST(o.dated AS date) dated,
                    CASE
                    WHEN o.jt = 'rop' THEN 'ROP'
                    WHEN o.jt = 'judgment' THEN 'Judgement'
                    WHEN o.jt = 'or' THEN 'Office Report'
                    END AS jo
                    FROM
                    tempo o
                    WHERE
                    o.diary_no = '" . $diary_no . "' UNION SELECT
                    o.diary_no diary_no,
                    o.pdfname jm,
                    CAST(o.orderdate AS date) dated,
                    CASE
                    WHEN o.type = 'O' THEN 'ROP'
                    WHEN o.type = 'J' THEN 'Judgement'
                    END AS jo
                    FROM
                    ordernet o
                    WHERE
                    o.diary_no = '" . $diary_no . "' UNION SELECT
                    o.dn diary_no,
                    CONCAT('ropor/rop/all/', o.pno, '.pdf') jm,
                     CAST(o.orderDate AS date) dated,
                    'ROP' AS jo
                    FROM
                    rop_text_web.old_rop o
                    WHERE
                    o.dn = '" . $diary_no . "' UNION SELECT
                    o.dn diary_no,
                    CONCAT('judis/', o.filename, '.pdf') jm,
                    CAST(o.juddate AS date) dated,
                    'Judgment' AS jo
                    FROM
                    scordermain o

                    WHERE
                    o.dn = '" . $diary_no . "' UNION SELECT
                    o.dn diary_no,
                    CONCAT('bosir/orderpdf/', o.pno, '.pdf') jm,
                    CAST(o.orderdate AS date) dated,
                    'ROP' AS jo
                    FROM
                    rop_text_web.ordertext o

                    WHERE
                    o.dn = '" . $diary_no . "' AND o.display = 'Y' UNION SELECT
                    o.dn diary_no,
                    CONCAT('bosir/orderpdfold/', o.pno, '.pdf') jm,
                    CAST(o.orderdate AS date) dated,
                    'ROP' AS jo
                    FROM
                    rop_text_web.oldordtext o
                    WHERE
                    o.dn = '" . $diary_no . "') tbl1 WHERE jo='ROP'
                    ORDER BY tbl1.dated DESC";

        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }
        return $return;
    }

    public function getCaseName11($dno)
    {

        $builder = $this->db->table('main a');
        $builder->select('b.short_description,a.fil_no, a.fil_dt, a.fil_no_fh, a.fil_dt_fh,COALESCE(NULLIF(a.reg_year_mh, 0), EXTRACT(YEAR FROM a.fil_dt)) AS m_year,
                          COALESCE(NULLIF(a.reg_year_fh, 0), EXTRACT(YEAR FROM a.fil_dt_fh)) AS f_year');

        $builder->join('master.casetype b', 'b.casecode = a.casetype_id', 'left');
        $builder->where('a.diary_no', $dno);

        return $builder->get()->getRowArray();
    }
    public function getCaseTopDetails($diary_no)
    {
        $builder = $this->db->table('main a');
        $builder->select('a.fil_no, a.fil_dt, a.fil_no_fh, a.fil_dt_fh, b.short_description,');
        $builder->select("CASE WHEN a.reg_year_mh = 0 THEN EXTRACT(YEAR FROM a.fil_dt) ELSE a.reg_year_mh END AS m_year");
        $builder->select("CASE WHEN a.reg_year_fh = 0 THEN EXTRACT(YEAR FROM a.fil_dt_fh) ELSE a.reg_year_fh END AS f_year");
        $builder->join('master.casetype b', "CAST(SUBSTRING(a.fil_no FROM 1 FOR 2) AS INTEGER) = b.casecode", 'left');
        $builder->where('a.diary_no', $diary_no);
        $query = $builder->get();
        $casetype = $query->getRowArray();
        return $casetype ?: [];
    }

    public function getCaseTypeR($fil_no_fh)
    {
        $builder = $this->db->table('master.casetype');
        $builder->select('short_description');
        $builder->where("CAST(SUBSTRING('$fil_no_fh' FROM 1 FOR 2) AS INTEGER) = casecode");
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result ?: [];
    }









    public function addCaseToVacationPool($fil_no, $usercode)
    {
        if ($this->isCaseInVacationPool($fil_no)) {
            return false; // Case already exists in the vacation pool
        }

        $subquery = $this->db->table('conct')
            ->select('conn_key')
            ->where('conn_key', $fil_no)
            ->orWhere('diary_no', $fil_no)
            ->groupStart()
            ->where('list', 'Y')
            ->orWhere('list', null)
            ->groupEnd();

        $rows = $this->db->table('main m')
            ->select('m.diary_no, m.conn_key')
            ->whereIn('m.diary_no', $subquery)
            ->orWhere('m.diary_no', $fil_no)
            ->get()
            ->getResultArray();
        if ($rows) {
            $data = array_map(function ($row) use ($usercode) {
                return [
                    'diary_no' => $row['diary_no'],
                    'conn_key' => $row['conn_key'],
                    'updated_by' => $usercode,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'vacation_list_year' => (int)date('Y'),
                ];
            }, $rows);

            $this->db->table('vacation_advance_list')->insertBatch($data);
            return true;
        }
        return false;
    }

    public function restoreVacationAdvanceListLog($diaryNo)
    {
        $db = \Config\Database::connect();
        $sql = "INSERT INTO vacation_advance_list_log 
                SELECT * 
                FROM vacation_advance_list 
                WHERE vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE) 
                AND diary_no = $diaryNo
                AND is_deleted = 't'";

        return $db->query($sql);
    }

    public function restoreVacationAdvanceList($diaryNo, $userID, $updatedFromIP)
    {
        return $this->db->table('vacation_advance_list')
            ->set([
                'is_deleted' => 'f',
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $userID,
                'updated_from_ip' => $updatedFromIP,
            ])
            ->where('diary_no', $diaryNo)
            ->where('is_deleted', 't')
            ->where('vacation_list_year', date('Y'))
            ->update();
    }

    public function getVacationAdvocates($diaryNo)
    {
        $builder = $this->db->table('vacation_advance_list_advocate v');
        $builder->select("string_agg(
                                DISTINCT CONCAT(
                                    COALESCE(b.name, ''), 
                                    '<font color=\"red\" weight=\"bold\">', 
                                    CASE WHEN v.is_deleted = 't' THEN '(Declined)' ELSE '' END, 
                                    '</font>'
                                ), '<br/>') AS advocate")
            ->join('master.bar b', 'b.aor_code = v.aor_code', 'inner')
            ->where('v.diary_no', $diaryNo)
            ->where('v.vacation_list_year', date('Y'))
            ->where('b.if_aor', 'Y')
            ->where('b.isdead', 'N')
            ->groupBy('v.diary_no');
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result;

        /*$sql = "SELECT 
                STRING_AGG(DISTINCT CONCAT(COALESCE(b.name, ''), 
                '<font color=\"red\" weight=\"bold\">', 
                
                '</font>'), '<br/>') AS advocate
            FROM vacation_advance_list_advocate v 
            INNER JOIN master.bar b ON b.aor_code = v.aor_code
            WHERE v.diary_no = ? 
              AND v.vacation_list_year = EXTRACT(YEAR FROM CURRENT_DATE) 
              AND b.if_aor = 'Y' 
              AND b.isdead = 'N' 
            GROUP BY v.diary_no";
        $result = $this->db->query($sql, [$diaryNo])->getRowArray();*/
        // Debugging: Log the result to check its contents
        //log_message('debug', 'Advocates Query Result: ' . print_r($result, true));
    }


    public function getPoolCases($params)
    {
        $return = [];
        $listing_dt = isset($params['list_dt']) ? date("Y-m-d", strtotime($params['list_dt'])) : date("Y-m-d");
        $mainhead = isset($params['mainhead']) ? $params['mainhead'] : '';
        $main_supp = isset($params['main_supp']) ? $params['main_supp'] : '';
        $forFixedDate = isset($params['forFixedDate']) ? $params['forFixedDate'] == 'true' : false;
        $from_yr = isset($params['from_yr']) ? $params['from_yr'] : "0";
        $to_yr = isset($params['to_yr']) ? $params['to_yr'] : "0";
        $is_nmd = isset($params['is_nmd']) ? $params['is_nmd'] : '0';
        $is_nmd = ($is_nmd != '0') ? " AND h.is_nmd = '" . $is_nmd . "'" : '';
        
        //$listing_purpose = isset($params['listing_purpose']) ? $params['listing_purpose'] : 'all';
        $bench = isset($params['bench']) ? $params['bench'] : '';
        $pool_adv = isset($params['pool_adv']) ? $params['pool_adv'] : '';


        if ($mainhead == "F") {
            $order_by = "CASE WHEN m.listorder in (4,5,7,8) THEN 
                   if($main_supp = 2,m.next_dt = '$listing_dt', (m.next_dt BETWEEN '$listing_dt' AND
                 ADDDATE('$listing_dt',INTERVAL 7 - DAYOFWEEK('$listing_dt') DAY) OR m.next_dt <= CURDATE()) ) 
                   ELSE
               m.next_dt > '1947-08-15' END, CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC, CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";
        } else {
            //$order_by = " IF(date(ia_filing_dt) is not null,1,2),date(ia_filing_dt), CAST(RIGHT(m.diary_no, 4) AS UNSIGNED) ASC , CAST(LEFT(m.diary_no,LENGTH(m.diary_no)-4) AS UNSIGNED) ASC";

            $order_by = " CASE WHEN date(ia_filing_dt) IS NOT NULL THEN 1 ELSE 2 END, date(ia_filing_dt), CAST(RIGHT(m.diary_no::TEXT, 4) AS INTEGER) ASC,  CAST(LEFT(m.diary_no::TEXT, LENGTH(m.diary_no::TEXT) - 4) AS INTEGER) ASC";
        }





        $case_from_to_yr = ($from_yr != "0" && $to_yr != "0") ? "AND EXTRACT(YEAR FROM m.diary_no_rec_date) BETWEEN '" . $from_yr . "' AND '" . $to_yr . "'" : '';
        $case_grp = ($params['civil_criminal'] == 'C' || $params['civil_criminal'] == 'R') ? "AND m.case_grp = '" . $params['civil_criminal'] . "'" : '';

        if ($params['bench'] != "A") {
            $bench = "AND h.board_type = '" . $params['bench'] . "'";
        }

        $leftjoin_field = $leftjoin_coram_r = $leftjoin_kword = $leftjoin_docdetl = $leftjoin_act = $leftjoin_section = $advance_allocated_left = $advance_drop_note_left = $sub_cat_arry = $casetype = $subhead_select = $kword_selected = $docdetl_selected = $act_selected = $section_selected = $advance_allocated_qry = $advance_drop_note_qry = $rgo_dft_left = $rgo_dft_qry = $coram_sele_or_null = $reg_unreg = '';

        $get_ia_date = ', NULL as ia_filing_dt';
        if ($mainhead != 'F') {
            $subhead_arry = $this->f_selected_values($params['subhead']);
            if (in_array('817', @explode(',', $subhead_arry))) {
                $get_ia_date = ",(select min(doc.ent_dt) from docdetails doc inner join main mn on doc.diary_no=mn.diary_no
                        left join conct ct on mn.diary_no=ct.conn_key where doc.doccode=8 and doc.doccode1=3 and doc.iastat='P' and doc.display='Y' and (ct.list='Y' or ct.list is null)
                        and (mn.diary_no=m.diary_no or mn.conn_key=m.diary_no) and mn.c_status='P') as ia_filing_dt ";
            }
            if ($subhead_arry != "all") {
                $subhead_select = "AND h.subhead IN ($subhead_arry)";
            }
        }

        $sub_cat =  $this->f_selected_values($params['subject_cat']);
        if ($sub_cat != "all") {
            $sub_cat_arry = "AND c2.submaster_id IN ($sub_cat) ";
        }

        $kword_arry = $this->f_selected_values($params['kword']);
        if ($kword_arry != "all") {
            $leftjoin_kword = "LEFT JOIN ec_keyword ek ON ek.diary_no = h.diary_no and ek.display = 'Y'";
            $kword_selected = "AND keyword_id IN ($kword_arry)";
        }

        $ia_arry = $this->f_selected_values($params['ia']);
        if ($ia_arry != "all") {
            $leftjoin_docdetl = "LEFT JOIN docdetails dd ON dd.diary_no = h.diary_no";
            $docdetl_selected = "AND dd.doccode1 IN ($ia_arry)  and dd.iastat = 'P' and dd.display = 'Y' and dd.doccode = '8'";
        }
        $ia_arry = $this->f_selected_values($params['act']);
        if ($ia_arry != "all") {
            $leftjoin_act = "LEFT JOIN act_main at ON at.diary_no = h.diary_no";
            $act_selected = "AND at.display = 'Y' and at.act IN ($ia_arry)";
            if ($params['section']) {
                $leftjoin_section = "LEFT JOIN master.act_section ast ON ast.act_id = at.id";
                $section_selected = "AND ast.section LIKE '" . $params['section'] . "%' AND ast.display = 'Y'";
            }
        }

        $only_regs = "";
        if ($params['reg_unreg'] == 1) {
            $reg_unreg = " OR (m.active_fil_no = '' OR m.active_fil_no IS NULL) "; //with unregistred
        } else {
            $only_regs = " AND m.active_fil_no != '' AND m.active_fil_no IS NOT NULL";
        }

        $casetype_array = $this->f_selected_values($params['case_type']);
        if ($casetype_array != "all") {
            //COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN (". $this->f_selected_values($params['case_type']) . ") 
            $casetype = "AND (COALESCE(NULLIF(TRIM(LEADING '0' FROM split_part(m.fil_no, '-', 1)), '')::INTEGER, 0) IN (" . $this->f_selected_values($params['case_type']) . ")  $reg_unreg )";

            //$casetype = "AND (TRIM(LEADING '0' FROM SUBSTRING_INDEX(m.fil_no,'-',1) ) IN (" . $this->f_selected_values($params['case_type']) . ") $reg_unreg )";
        }

        if ($forFixedDate == 'true') {
            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND 
               h.next_dt = '$listing_dt'";
        } else {
            $qry_part_list_ornot1 = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND CASE WHEN l.fx_wk = 'F' THEN
                if($main_supp = 2,h.next_dt = '$listing_dt', (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE) )
                ELSE h.next_dt <= '$listing_dt' END ";

            $qry_part_list_ornot = "AND m.c_status = 'P' AND h.main_supp_flag = '0' AND ((l.fx_wk = 'F' AND (
    ($main_supp = 2 AND h.next_dt = '$listing_dt') OR
    ($main_supp != 2 AND (h.next_dt = '$listing_dt' OR h.next_dt <= CURRENT_DATE)))) OR (l.fx_wk != 'F' AND h.next_dt <= '$listing_dt'))";
        }

        $md_name = $params['md_name'];
        $coram_sele = '';

        if ($md_name == "pool" or $md_name == "transfer") {
            $sql_field = "m.*, STRING_AGG(c.diary_no::TEXT, ',') AS child_case";
            $sql_field2 = "LEFT JOIN conct c ON c.conn_key = m.diary_no AND list = 'Y' GROUP BY m.diary_no, m.active_fil_no, m.ia_filing_dt, m.active_reg_year, m.active_casetype_id, m.reg_no_display, m.short_description,m.fil_no,m.fil_dt, m.fil_year,m.lastorder, m.diary_no_rec_date, m.conn_key, m.next_dt, m.mainhead, m.subhead, m.clno, m.brd_slno, m.roster_id, m.judges, m.coram, m.board_type, m.usercode, m.ent_dt, m.module_id, m.mainhead_n, m.subhead_n, m.main_supp_flag, m.listorder, m.tentative_cl_dt, m.listed_ia, m.sitting_judges, m.list_before_remark, m.coram_prev, m.is_nmd, m.no_of_time_deleted, m.updated_by_ip, m.updated_by, m.updated_on,m.create_modify,m.trial011,m.descrip,m.purpose,m.cat1, m.r_coram ORDER BY brd_slno ASC, $order_by";
            if ($md_name == "transfer") {
                $part_no = $params['part_no'];
                $roster_judges_id = explode("|", $params['roster_judges_id']);
                $trans_ros_id = $roster_judges_id[1];
                $qry_part_list_ornot = "AND h.roster_id = $trans_ros_id AND h.clno = $part_no AND h.next_dt = '$listing_dt'";
            }
            if ($md_name == "pool") {
                //$coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                $leftjoin_field = " cr.jud as r_coram, ";
            }
        } else {
            if ($md_name == "allocation") {
                $chked_jud = rtrim($params['roster_judges_id'], "JG");
                $roster_selected = $chked_jud;
                //pr($roster_selected);
                $explode_rs = explode("JG", $roster_selected);
                for ($i = 0; $i < (count($explode_rs)); $i++) {
                    $explode_rs_jg = explode("|", $explode_rs[$i]);
                    $coram_sele .= $explode_rs_jg[0] . ",";
                }
                if (rtrim($coram_sele, ",") == '') {
                    $cor_slse = "0";
                } else {
                    $cor_slse = rtrim($coram_sele, ",");
                }
                if ($params['bench'] == 'J' or $params['bench'] == 'S') {
                    $coram_sele_or_null = " AND (h.coram IN ('$cor_slse') or h.coram = '0' or h.coram is null or h.coram = '' ) ";
                    //$coram_sele_or_null = '';
                }
                if ($params['bench'] == 'R') {
                    $coram_sele_or_null = " AND (cr.jud IS NULL OR cr.jud IN ($cor_slse)) ";
                    $leftjoin_coram_r = " LEFT JOIN coram cr ON cr.diary_no = h.diary_no AND cr.board_type = 'R' AND cr.to_dt IS NULL AND cr.display = 'Y'";
                    $leftjoin_field = " cr.jud as r_coram, ";
                }
            }
            $sql_field = "count(*) as avl_rc";
            $sql_field2 = "";
        }


        $mul_cat_qry = "";
        if ($params['bench'] == 'J' or $params['bench'] == 'S') {
            $rgo_dft_left = " LEFT JOIN rgo_default rd ON rd.fil_no = h.diary_no AND rd.remove_def = 'N' ";
            $rgo_dft_qry = " AND rd.fil_no IS NULL ";
            $mul_cat_qry = " c2.diary_no IS NOT NULL AND ";
        }
        if ($params['pool_adv'] == 'A') {
            $advance_allocated_left = " LEFT JOIN advance_allocated ad_al ON ad_al.diary_no = h.diary_no AND ad_al.next_dt = '$listing_dt' ";
            $advance_drop_note_left = " LEFT JOIN advanced_drop_note ad_dn ON ad_dn.diary_no = ad_al.diary_no AND ad_dn.cl_date = ad_al.next_dt ";
            $advance_allocated_qry = " AND ad_al.diary_no IS NOT NULL ";
            $advance_drop_note_qry = " AND ad_dn.diary_no IS NULL ";
        }

        //pr($sql_field);

        $listorder = $this->f_selected_values(isset($params['listing_purpose']) ? $params['listing_purpose'] : []);
        $p_listorder =  ((!empty($listorder)) && ($listorder != "all"))  ? "AND h.listorder IN ($listorder)" : '';

        /*$mainheadCondition = is_numeric($mainhead) ?
            "CAST(m.diary_no AS TEXT) = '" . $mainhead . "'" :
            "CAST(m.diary_no AS TEXT) LIKE '" . $mainhead . "%'";*/

        $mainheadCondition = ['h.mainhead' => $mainhead];

        $groupBy = "GROUP BY h.diary_no, m.active_fil_no, m.active_reg_year, m.active_casetype_id, m.reg_no_display, c.short_description, m.fil_no, m.fil_dt, m.lastorder, m.diary_no_rec_date, l.purpose";
        if (($md_name == "allocation") && ($params['bench'] == 'R')) {
            $groupBy .= " , cr.jud";
        }
        if ($md_name == "pool") {
            $groupBy .= " , cr.jud";
        }

        $limit = "LIMIT " . intval(500);
        $qry = "SELECT $sql_field
           FROM (SELECT $leftjoin_field m.active_fil_no $get_ia_date, m.active_reg_year, m.active_casetype_id, m.reg_no_display, c.short_description, m.fil_no, m.fil_dt, EXTRACT(YEAR FROM m.fil_dt) AS fil_year, m.lastorder, m.diary_no_rec_date, h.*, l.purpose,  STRING_AGG(c2.submaster_id :: TEXT, ',') AS cat1
            FROM main m
            LEFT JOIN heardt h ON m.diary_no = h.diary_no 
            LEFT JOIN master.listing_purpose l ON l.code = h.listorder
            LEFT JOIN master.casetype c ON m.active_casetype_id = c.casecode            
            LEFT JOIN mul_category c2 ON c2.diary_no = h.diary_no AND c2.display = 'Y' and c2.submaster_id != 331 and c2.submaster_id IS NOT NULL      
            $rgo_dft_left
            $leftjoin_coram_r
            $leftjoin_kword    
            $leftjoin_docdetl    
            $leftjoin_act    
            $leftjoin_section 
               $advance_allocated_left
               $advance_drop_note_left
            WHERE $mul_cat_qry l.display = 'Y' $sub_cat_arry $is_nmd $coram_sele_or_null
            $rgo_dft_qry
            
            $p_listorder            
            $case_grp
            $only_regs    
            $casetype   
            $bench    
            $case_from_to_yr  
            $subhead_select    
            $kword_selected  
            $docdetl_selected    
            $act_selected    
            $section_selected    
             $advance_allocated_qry 
             $advance_drop_note_qry
             --AND (m.diary_no = m.conn_key:: BIGINT OR m.conn_key:: BIGINT=0 
              AND (m.diary_no = CAST(NULLIF(m.conn_key, '') AS BIGINT) OR m.conn_key='0'
             OR m.conn_key = '' OR m.conn_key IS NULL) $qry_part_list_ornot AND h.mainhead = '" . $params['mainhead'] . "'             
             $groupBy ) m
            $sql_field2
            $limit";
        $query = $this->db->query($qry);
        if ($query->getNumRows() >= 1) {
            $return = $query->getResultArray();
        }
        return $return;
    }


}
