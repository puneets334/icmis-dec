<?php
namespace App\Models\Judicial;
use CodeIgniter\Model;

class Mentioning_Model extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    function get_case_type_list()
    {
        // $sql="SELECT casecode, skey, casename,short_description FROM casetype WHERE display = 'Y' AND casecode!=9999 ORDER BY short_description";

        // $query = $this->db->query($sql);
        // if($query -> num_rows() >= 1)
        // {
        //     return $query->result_array();
        // }
        // else
        // {
        //     return false;
        // }

        $builder = $this->db->table('master.casetype');

        // Apply WHERE conditions and ordering
        $builder->select('casecode, skey, casename, short_description');
        $builder->where('display', 'Y');
        $builder->where('casecode !=', 9999);
        $builder->orderBy('short_description');

        $query = $builder->get();

        if($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    function get_display_status_with_date_differnces($tentative_cl_dt)
    {
        $tentative_cl_date_greater_than_today_flag="F";
        $curDate=date('d-m-Y');
        $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
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

    function get_diary_details($caseTypeId=null,$caseNo=null,$caseYear=null,$diaryNo=null,$diaryYear=null)
    {
        
        if ($_POST['optradio'] == 1)
        {
            $sql = "SELECT h.diary_no,
                SUBSTRING(CAST(h.diary_no AS TEXT), 1, LENGTH(CAST(h.diary_no AS TEXT)) - 4) AS dn,
                SUBSTRING(CAST(h.diary_no AS TEXT), LENGTH(CAST(h.diary_no AS TEXT)) - 3, 4) AS dy
                FROM main_casetype_history h
                WHERE 
                (NULLIF(SPLIT_PART(h.new_registration_number, '-', 1), '') IS NOT NULL
                AND NULLIF(SPLIT_PART(h.new_registration_number, '-', 2), '') IS NOT NULL
                AND NULLIF(SPLIT_PART(h.new_registration_number, '-', 3), '') IS NOT NULL
                AND CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 1), '') AS INTEGER) = ?
                AND CAST(? AS INTEGER) BETWEEN 
                CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 2), '') AS INTEGER)
                AND CAST(NULLIF(SPLIT_PART(h.new_registration_number, '-', 3), '') AS INTEGER)
                AND CAST(h.new_registration_year AS INTEGER) = ?)
                AND h.is_deleted = 'f'";

            $query = $this->db->query($sql, [$caseTypeId, $caseNo, $caseYear]);
        }

        if ($_POST['optradio'] == 2) {
            $diaryNo = $_POST['diaryNumber'];
            $diaryYear = $_POST['diaryYear'];

            $sql = "SELECT diary_no, 
                SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) AS dn, 
                RIGHT(CAST(diary_no AS TEXT), 4) AS dy 
                FROM main 
                WHERE SUBSTRING(CAST(diary_no AS TEXT), 1, GREATEST(LENGTH(CAST(diary_no AS TEXT)) - 4, 0)) = :diaryNo: 
                AND RIGHT(CAST(diary_no AS TEXT), 4) = :diaryYear:";

            $query = $this->db->query($sql, [
                'diaryNo'   => $diaryNo,
                'diaryYear' => $diaryYear
            ]);
        }

        if ($query->getNumRows() >= 1)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    function is_diaryno_already_added_today($dno)
    {
        // $sql = "select diary_no from mention_memo where diary_no=$dno and date_of_received=CURDATE() and date_on_decided=CURDATE()and display='Y'";

        // $query = $this->db->query($sql);
        // if($query -> num_rows() >= 1)
        // {
        //     $this->session->set_flashdata('msg', '<div class="alert alert-warning text-center">Mention Memo Already added for this case for the Given Date</div>');
        //     redirect("Mentioning");
        // }
        // else
        // {
        //     return false;
        // }

        $sql = "SELECT diary_no 
                FROM mention_memo 
                WHERE diary_no = :dno: 
                AND date_of_received = CURRENT_DATE 
                AND date_on_decided = CURRENT_DATE 
                AND display = 'Y'";

        $query = $this->db->query($sql, ['dno' => $dno]);

        if ($query->getNumRows() >= 1)
        {
            session()->setFlashdata('msg', '<div class="alert alert-warning text-center">Mention Memo Already added for this case for the Given Date</div>');
            return redirect()->to('Mentioning');
        }
        else
        {
            return false;
        }
    }

    function getCaseDetails($diaryNumber=null)
    {
        if(!($this->is_diaryno_already_added_today($diaryNumber)))
        {
            //echo 'ForDiary'.$diaryNumber;
            // $sql="SELECT s.section_name as user_section,s.id,substr(b.diary_no,1,length(b.diary_no)-4) AS diary_no,
            //                 substr(b.diary_no,-4) as diary_year,
            //                 date_format(b.diary_no_rec_date, '%Y-%m-%d') as diary_date,b.c_status,tentative_cl_dt,
            //                 next_dt, mainhead, subhead, brd_slno, a.usercode, ent_dt, pet_name, res_name,
            //                 active_fil_no, b.reg_no_display,dacode, a.conn_key, stagename, main_supp_flag, u.name alloted_to_da,
            //                 descrip, u1.name updated_by, listorder,
            //                 br1.name as pet_adv_name,br2.name as res_adv_name,
            //                 br1.aor_code as pet_aor_code,br2.aor_code as res_aor_code,
            //                 sb.sub_name1, sb.sub_name4,sb.category_sc_old
            //                 FROM main b
            //                 left outer JOIN heardt a ON a.diary_no = b.diary_no
            //                 LEFT outer  JOIN subheading c ON a.subhead = c.stagecode
            //                 AND c.display = 'Y'
            //                 LEFT outer JOIN users u ON u.usercode = b.dacode
            //                 AND u.display = 'Y'
            //                 LEFT outer JOIN users u1 ON u1.usercode = a.usercode
            //                 AND u1.display = 'Y'
            //                 LEFT outer JOIN master_main_supp mms ON mms.id = a.main_supp_flag
            //                 left outer JOIN listing_purpose lp ON lp.code = a.listorder AND lp.display = 'Y'
            //                 left outer join usersection s on s.id=u.section and s.display='Y'
            //                 left outer join bar br1 on b.pet_adv_id=br1.bar_id
            //                 left outer join bar br2 on b.res_adv_id=br2.bar_id
            //                 left outer join mul_category mc on a.diary_no=mc.diary_no and mc.display='Y'
            //                 left outer join submaster sb on mc.submaster_id=sb.id
            //                 WHERE b.diary_no=$diaryNumber";

            // $query = $this->db->query($sql);
            // if($query -> num_rows() >= 1)
            // {
            //     return $query->result_array();
            // }
            // else
            // {
            //     return false;
            // }

            $sql = "SELECT s.section_name AS user_section,
                    s.id,
                    SUBSTRING(CAST(b.diary_no AS TEXT) FROM 1 FOR LENGTH(CAST(b.diary_no AS TEXT)) - 4) AS diary_no,
                    SUBSTRING(CAST(b.diary_no AS TEXT) FROM LENGTH(CAST(b.diary_no AS TEXT)) - 3 FOR 4) AS diary_year,
                    TO_CHAR(b.diary_no_rec_date, 'YYYY-MM-DD') AS diary_date,
                    b.c_status,
                    b.pet_name,
                    b.res_name,
                    b.active_fil_no,
                    b.reg_no_display,
                    b.dacode,
                    b.tentative_cl_dt,
                    b.next_dt,
                    b.mainhead,
                    b.subhead,
                    b.brd_slno,
                    b.ent_dt,
                    b.stagename,
                    a.descrip,
                    a.usercode,
                    a.conn_key,
                    a.main_supp_flag,
                    a.listorder,
                    u.name AS alloted_to_da,
                    
                    u1.name AS updated_by,
                    
                    br1.name AS pet_adv_name,
                    br2.name AS res_adv_name,
                    br1.aor_code AS pet_aor_code,
                    br2.aor_code AS res_aor_code,
                    sb.sub_name1,
                    sb.sub_name4,
                    sb.category_sc_old
                    FROM main b
                    LEFT OUTER JOIN heardt a ON a.diary_no = b.diary_no
                    LEFT OUTER JOIN master.subheading c ON a.subhead = c.stagecode AND c.display = 'Y'
                    LEFT OUTER JOIN master.users u ON u.usercode = b.dacode AND u.display = 'Y'
                    LEFT OUTER JOIN master.users u1 ON u1.usercode = a.usercode AND u1.display = 'Y'
                    LEFT OUTER JOIN master.master_main_supp mms ON mms.id = a.main_supp_flag
                    LEFT OUTER JOIN master.listing_purpose lp ON lp.code = a.listorder AND lp.display = 'Y'
                    LEFT OUTER JOIN master.usersection s ON s.id = u.section AND s.display = 'Y'
                    LEFT OUTER JOIN master.bar br1 ON b.pet_adv_id = br1.bar_id
                    LEFT OUTER JOIN master.bar br2 ON b.res_adv_id = br2.bar_id
                    LEFT OUTER JOIN mul_category mc ON a.diary_no = mc.diary_no AND mc.display = 'Y'
                    LEFT OUTER JOIN master.submaster sb ON mc.submaster_id = sb.id
                    WHERE b.diary_no = :diaryNumber:";

            // Execute the query using query builder and bind parameters
            $query = $this->db->query($sql, ['diaryNumber' => $diaryNumber]);

            // Check if rows are returned
            if ($query->getNumRows() >= 1)
            {
                return $query->getResultArray();
            }
            else
            {
                return false;
            }
        }
    }
    
    function get_case_remarks($dn,$cldate,$jcodes,$clno){
        $sql="select h.cat_head_id,
        c.cl_date,
        c.jcodes,
        c.status,
        GROUP_CONCAT(
          CONCAT(h.head, if(c.head_content!='', concat(' [', c.head_content, ']'),'')) SEPARATOR ', '
        ) AS crem,
        GROUP_CONCAT(
          CONCAT(c.r_head, '|', c.head_content, '^^') SEPARATOR ''
        ) AS caseval,c.mainhead,c.clno FROM
        case_remarks_multiple c inner join
        case_remarks_head h on c.r_head=h.sno
      WHERE c.diary_no = ".$dn."
        AND c.cl_date = '".$cldate."'
            AND c.jcodes='".$jcodes."'
                AND c.clno='".$clno."'
      GROUP BY c.cl_date ORDER BY h.priority";
        $query = $this->db->query($sql);
        $crem="";
        if($query -> num_rows() >= 1)
        {
            $t_results=$query->result_array();
            foreach ($t_results as $row)
            {
                $crem=$t_results[0]['crem'];
            }
        }

        return $crem;
    }
    function change_date_format($date){
        if($date=="" or $date=="0000-00-00")
            $date="";
        else
            $date=date('d-m-Y', strtotime($date));
        return $date;
    }
    function get_judges($jcodes)
    {
        $jnames="";
        if ($jcodes!='')
        {
            $t_jc=explode(",",$jcodes);
            for($i=0;$i<count($t_jc);$i++)
            {
                $sql = "SELECT jname  FROM  judge where jcode= ".$t_jc[$i];
                $query = $this->db->query($sql);
                if($query -> num_rows() >= 1)
                {
                    $results=$query->result_array();
                    foreach ($results as $row11a)
                    {
                        if($jnames=='')
                            $jnames.=$row11a["jname"];
                        else {
                            if($i==(count($t_jc)-1))
                                $jnames.=" and ".$row11a["jname"];
                            else
                                $jnames.=", ".$row11a["jname"];
                        }

                    }

                }
            }
        }
        return $jnames;
    }
    function get_purpose($purpose_code){
        $purpose="";
        if($purpose_code!="")
        {
            $sql = "SELECT purpose FROM listing_purpose WHERE code='".$purpose_code."'";
            $result = $this->db->query($sql);
            if($result -> num_rows() >= 1)
            {
                $result_p=$result->result_array();
                foreach ($result_p as $row)
                {
                    $purpose=$row['purpose'];
                }
            }

        }
        return $purpose;
    }
    function get_stage($stage_code,$mainhead){
        $stage="";
        if($stage_code!="")
        {
            if($mainhead=="M"){
                $sql = "SELECT stagename FROM subheading WHERE stagecode='".$stage_code."'";
                $result = $this->db->query($sql);
                if($result -> num_rows() >= 1)
                {
                    $result_p=$result->result_array();
                }

                $stage=$result_p[0]['stagename'];
            }
            if($mainhead=="F"){
                $sql_p = "SELECT * FROM submaster WHERE id='".$stage_code."'";
                $result1 = $this->db->query($sql_p);
                if($result1 -> num_rows() >= 1)
                {
                    $result1_p=$result1->result_array();
                }
                foreach ($result1_p as $row_p)
                {
                    if($row_p['subcode1']>0 and $row_p['subcode2']==0 and $row_p['subcode3']==0 and $row_p['subcode4']==0)
                        $stage=  $row_p['sub_name1'];
                    elseif($row_p['subcode1']>0 and $row_p['subcode2']>0 and $row_p['subcode3']==0 and $row_p['subcode4']==0)
                        $stage=  $row_p['sub_name1']." : ".$row_p['sub_name4'];
                    elseif($row_p['subcode1']>0 and $row_p['subcode2']>0 and $row_p['subcode3']>0 and $row_p['subcode4']==0)
                        $stage=  $row_p['sub_name1']." : ".$row_p['sub_name2']." : ".$row_p['sub_name4'];
                    elseif($row_p['subcode1']>0 and $row_p['subcode2']>0 and $row_p['subcode3']>0 and $row_p['subcode4']>0)
                        $stage=  $row_p['sub_name1']." : ".$row_p['sub_name2']." : ".$row_p['sub_name3']." : ".$row_p['sub_name4'];
                }
            }
        }
        return $stage;
    }


/*
    upto 03.10.2019
    function get_listings($diaryno) {
        $output = "";
        $t_table="";
        $t_mainhead="";
        //Listing Start
        $sql_listing="Select a.*,section_name from heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$diaryno."' ";
        $result_listing =  $this->db->query($sql_listing);
        $sql_listing1="Select a.*,section_name from last_heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$diaryno."' and next_dt!='0000-00-00' order by ent_dt DESC";
        $result_listing1 =  $this->db->query($sql_listing1);
        $subhead="";$next_dt="";$lo="";$sj="";$bt="";
        if ($result_listing -> num_rows() > 0 or $result_listing1 -> num_rows() > 0) {

            $result_listings= $result_listing->result_array();
            $result_listings1= $result_listing1->result_array();

            $t_table = '<table border=1px; cellspacing=5px; cellpadding=10px;class="table_tr_th_w_clr c_vertical_align" width="100%">';
            $t_table.="<tbody><tr><td align='center' width='12%'><b>Listing Date</b></td><td><b>Misc./Regular</b></td><td><b>Stage</b></td><td><b>Purpose</b></td><td align='center'><b>Proposed/ List in</b></td><td align='center'><b>Judges</b></td><td><b>IA</b></td><td><b>Remarks</b></td><th>Updated By</th></tr>";
            foreach ($result_listings as $row_listing)
            {
                $listed_ia=$row_listing['listed_ia'];
                if($row_listing['mainhead']=="M")
                    $t_mainhead="Misc.";
                if($row_listing['mainhead']=="F")
                    $t_mainhead="Regular" ;
                if($row_listing['mainhead']=="L")
                    $t_mainhead="Lok Adalat" ;
                if($row_listing['mainhead']=="S")
                    $t_mainhead="Mediation" ;
                $t_stage="";
                $subhead=$row_listing['subhead'];
                if($row_listing['mainhead']=="M"){
                    $t_stage=$this->get_stage($row_listing['subhead'],'M');
                }
                if($row_listing['mainhead']=="F"){
                    $t_stage=$this->get_stage($row_listing['subhead'],'F');
                }

                $next_dt=$row_listing['next_dt'];
                $lo=$row_listing['listorder'];
                $sj=$row_listing['sitting_judges'];
                $bt=$row_listing['board_type'];
                if($bt=='J')
                    $bt='Judge';
                else if($bt=='C')
                    $bt='Chamber';
                else if($bt=='R')
                    $bt='Registry';
                else
                    $bt='';
                if($row_listing['judges']!='' and $row_listing['judges']!='0'){
                    $cr = $this->get_case_remarks($row_listing['diary_no'],$row_listing['next_dt'],$row_listing['judges'],$row_listing['brd_slno']);
                } else{
                    $cr = "";
                }
                $t_table.="<tr><td align='center'>".$this->change_date_format($row_listing['next_dt'])."</td><td>".$t_mainhead."</td><td>".$t_stage."</td>"
                    . "<td>".$this->get_purpose($row_listing['listorder'])."</td><td align='center'>".$bt."</td><td align='center'>".$this->get_judges($row_listing['judges'])."</td>"
                    . "<td align='center'>".$row_listing['listed_ia']."</td><td>".$cr."</td>";
                if($row_listing['ent_dt']=='0000-00-00 00:00:00' || $row_listing['ent_dt']=='' || $row_listing['ent_dt']==NULL){
                    if($row_listing['section_name']=='' || $row_listing['section_name']==NULL)
                        $t_table.="<td></td></tr>";
                    else
                        $t_table.="<td>$row_listing[section_name]</td></tr>";
                }
                else
                    $t_table.="<td>$row_listing[section_name] ON ".date('d-m-Y h:i:s A',strtotime($row_listing['ent_dt']))."</td></tr>";
            }
            foreach ($result_listings1 as $row_listing1)
            {
                if($row_listing1['mainhead']=="M")
                    $t_mainhead1="Misc.";
                if($row_listing1['mainhead']=="F")
                    $t_mainhead1="Regular" ;
                if($row_listing1['mainhead']=="L")
                    $t_mainhead1="Lok Adalat" ;
                if($row_listing1['mainhead']=="S")
                    $t_mainhead1="Mediation" ;
                $t_stage1="";
                if($row_listing1['mainhead']=="M"){
                    $t_stage1=$this->get_stage($row_listing1['subhead'],'M');
                }
                if($row_listing1['mainhead']=="F"){
                    $t_stage1=$this->get_stage($row_listing1['subhead'],'F');
                }
                $bt1=$row_listing1['board_type'];
                if($bt1=='J')
                    $bt1='Judge';
                else if($bt1=='C')
                    $bt1='Chamber';
                else if($bt1=='R')
                    $bt1='Registry';
                else
                    $bt1='';
                if($row_listing1['judges']!='' and $row_listing1['judges']!='0'){
                    $cr = $this->get_case_remarks($row_listing1['diary_no'],$row_listing1['next_dt'],$row_listing1['judges'],$row_listing1['brd_slno']);
                } else{
                    $cr = "";
                }
                $t_table.="<tr><td align='center'>".$this->change_date_format($row_listing1['next_dt'])."</td><td>".$t_mainhead1."</td><td>".$t_stage1."</td>"
                    . "<td>".$this->get_purpose($row_listing1['listorder'])."</td><td align='center'>".$bt1."</td><td align='center'>".$this->get_judges($row_listing1['judges'])."</td>"
                    . "<td align='center'>".$row_listing1['listed_ia']."</td><td>".$cr."</td>";
                if($row_listing1['ent_dt']=='0000-00-00 00:00:00' || $row_listing1['ent_dt']=='' || $row_listing1['ent_dt']==NULL){
                    if($row_listing1['section_name']=='' || $row_listing1['section_name']==NULL)
                        $t_table.="<td></td></tr>";
                    else
                        $t_table.="<td>$row_listing1[section_name]</td></tr>";
                }
                else
                    $t_table.="<td>$row_listing1[section_name] ON ".date('d-m-Y h:i:s A',strtotime($row_listing1['ent_dt']))."</td></tr></tbody>";
            }
            $t_table.="</table>";
        }
        $output.=$t_table;
//Listing End
        return $output;
    }
*/

function get_listings($diaryno) {
        $output = "";
        $t_table="";
        $t_mainhead="";
        $t_mainhead1="";
        //Listing Start
         $sql_listing="Select a.*,section_name from heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$diaryno."' ";
        $result_listing =  $this->db->query($sql_listing);
       $sql_listing1="Select a.*,section_name from last_heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$diaryno."' and next_dt!='0000-00-00' order by ent_dt DESC";
        $result_listing1 =  $this->db->query($sql_listing1);

        $subhead="";$next_dt="";$lo="";$sj="";$bt="";
        if ($result_listing -> num_rows() > 0 or $result_listing1 -> num_rows() > 0) {

            $result_listings= $result_listing->result_array();
            $result_listings1= $result_listing1->result_array();

            $t_table = '<table border=1px; cellspacing=5px; cellpadding=10px;class="table_tr_th_w_clr c_vertical_align" width="100%">';
            $t_table.="<tbody><tr><td align='center' width='12%'><b>Listing Date</b></td><td><b>Misc./Regular</b></td><td><b>Stage</b></td><td><b>Purpose</b></td><td align='center'><b>Proposed/ List in</b></td><td align='center'><b>Judges</b></td><td><b>IA</b></td><td><b>Remarks</b></td><th>Updated By</th></tr>";


            foreach ($result_listings as $row_listing)
            {
                $listed_ia=$row_listing['listed_ia'];
                if($row_listing['mainhead']=="M")
                    $t_mainhead="Misc.";
                if($row_listing['mainhead']=="F")
                    $t_mainhead="Regular" ;
                if($row_listing['mainhead']=="L")
                    $t_mainhead="Lok Adalat" ;
                if($row_listing['mainhead']=="S")
                    $t_mainhead="Mediation" ;
                $t_stage="";
                $subhead=$row_listing['subhead'];
                if($row_listing['mainhead']=="M"){
                    $t_stage=$this->get_stage($row_listing['subhead'],'M');
                }
                if($row_listing['mainhead']=="F"){
                    $t_stage=$this->get_stage($row_listing['subhead'],'F');
                }

                $next_dt=$row_listing['next_dt'];
                $lo=$row_listing['listorder'];
                $sj=$row_listing['sitting_judges'];
                $bt=$row_listing['board_type'];
                if($bt=='J')
                    $bt='Judge';
                else if($bt=='C')
                    $bt='Chamber';
                else if($bt=='R')
                    $bt='Registry';
                else
                    $bt='';
                if($row_listing['judges']!='' and $row_listing['judges']!='0'){
                    $cr = $this->get_case_remarks($row_listing['diary_no'],$row_listing['next_dt'],$row_listing['judges'],$row_listing['brd_slno']);
                } else{
                    $cr = "";
                }
                $t_table.="<tr><td align='center'>".$this->change_date_format($row_listing['next_dt'])."</td><td>".$t_mainhead."</td><td>".$t_stage."</td>"
                    . "<td>".$this->get_purpose($row_listing['listorder'])."</td><td align='center'>".$bt."</td><td align='center'>".$this->get_judges($row_listing['judges'])."</td>"
                    . "<td align='center'>".$row_listing['listed_ia']."</td><td>".$cr."</td>";
                if($row_listing['ent_dt']=='0000-00-00 00:00:00' || $row_listing['ent_dt']=='' || $row_listing['ent_dt']==NULL){
                    if($row_listing['section_name']=='' || $row_listing['section_name']==NULL)
                        $t_table.="<td></td></tr>";
                    else
                        $t_table.="<td>$row_listing[section_name]</td></tr>";
                }
                else
                    $t_table.="<td>$row_listing[section_name] ON ".date('d-m-Y h:i:s A',strtotime($row_listing['ent_dt']))."</td></tr>";
            }


            /*foreach ($result_listings1 as $row_listing1)
            {

                if($row_listing1['mainhead']=="M")
                    $t_mainhead1="Misc.";
                if($row_listing1['mainhead']=="F")
                    $t_mainhead1="Regular" ;
                if($row_listing1['mainhead']=="L")
                    $t_mainhead1="Lok Adalat" ;
                if($row_listing1['mainhead']=="S")
                    $t_mainhead1="Mediation" ;
                $t_stage1="";
                if($row_listing1['mainhead']=="M"){
                    $t_stage1=$this->get_stage($row_listing1['subhead'],'M');
                }
                if($row_listing1['mainhead']=="F"){
                    $t_stage1=$this->get_stage($row_listing1['subhead'],'F');
                }
                $bt1=$row_listing1['board_type'];
                if($bt1=='J')
                    $bt1='Judge';
                else if($bt1=='C')
                    $bt1='Chamber';
                else if($bt1=='R')
                    $bt1='Registry';
                else
                    $bt1='';


                if($row_listing1['judges']!='' and $row_listing1['judges']!='0'){
                    $cr = $this->get_case_remarks($row_listing1['diary_no'],$row_listing1['next_dt'],$row_listing1['judges'],$row_listing1['brd_slno']);
                } else{
                    $cr = "";
                }
                 echo $t_table.="<tr><td align='center'>".$this->change_date_format($row_listing1['next_dt'])."</td><td>".$t_mainhead1."</td><td>".$t_stage1."</td>"
                    . "<td>".$this->get_purpose($row_listing1['listorder'])."</td><td align='center'>".$bt1."</td><td align='center'>".$this->get_judges($row_listing1['judges'])."</td>"
                    . "<td align='center'>".$row_listing1['listed_ia']."</td><td>".$cr."</td>";
                //echo $row_listing1['ent_dt'].'#'.$row_listing1['ent_dt'];

                if($row_listing1['ent_dt']=='0000-00-00 00:00:00' || $row_listing1['ent_dt']=='' || $row_listing1['ent_dt']==NULL){
                    if($row_listing1['section_name']=='' || $row_listing1['section_name']==NULL)
                        $t_table.="<td></td></tr>";
                    else
                        $t_table.="<td>".$row_listing1['section_name']."</td></tr>";
                }
                else
                    $t_table.="<td>".$row_listing1['section_name']." ON ".date('d-m-Y h:i:s A',strtotime($row_listing1['ent_dt']))."</td></tr></tbody>";
            }*/
            $t_table.="</table>";
        }
        $output.=$t_table;
//Listing End
       // echo $output;

        return $output;
    }

function get_main_connected_array($dno)
    {
        $sql="select m.diary_no,m.conn_key,
                case when m.conn_key = 0 or m.conn_key=m.diary_no then 'M'
                     when  m.conn_key != 0 and m.conn_key!=m.diary_no
                then 'C'  end as mainorconn
                from main m where m.diary_no=$dno";
        //echo $sql;
        $result = $this->db->query($sql);


        if($result -> num_rows() >= 1)
        {
            $results=$result->result_array();
            if($results[0]['mainorconn']=='M')
            {
                $dnos[]=$results[0]['diary_no'];
            }
            else
            {
                //echo "connected case";

                if($results[0]['conn_key']!=null || $results[0]['conn_key']!=0)
                {
                    $dnos[]=$results[0]['conn_key']; //main case diary no
                }
                $dnos[]=$results[0]['diary_no']; // connected case diary no save into array
            }
            //var_dump($dnos);
        }
        return $dnos;

    }
    function getMiscOrRegular($dno)
    {

        $sql = "select mainhead from heardt where diary_no=$dno";

        $result = $this->db->query($sql);
        if($result -> num_rows() >= 1)
        {
            return $result->result_array();
        }
        else
        {
            return false;
        }

    }
    function is_diaryno_already_added_in_mentionmemo($dno,$receivedDate,$presentedDate,$mmDecidedDate)
    {
        $sql = "select diary_no from mention_memo where diary_no=$dno and
        date_of_received='$receivedDate' and date_on_decided='$presentedDate' and date_for_decided='$mmDecidedDate'";


        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    function proposal_condition_status($dnos_array,$MorF)
    {
        //var_dump($dnos_array);
        //var_dump($dnos_array);
        $all_dno = implode(',', $dnos_array);

        if($MorF=='M')
        {
            $sql="select m.diary_no,h.conn_key,h.judges,h.clno,h.brd_slno,m.c_status,
                h.mainhead from heardt h
                inner join main m on h.diary_no=m.diary_no
                WHERE  h.mainhead='M' AND
                m.diary_no in ($all_dno)";
            $result = $this->db->query($sql);
            if($result -> num_rows() >= 1)
            {
                return $result->result_array();
            }
            else
            {
                return false;
            }
        }
        if($MorF=='F')
        {
            //judges,h.clno,h.brd_slno,m.c_status,
            $sql="select m.diary_no,m.c_status,h.mainhead,h.main_supp_flag,judges,h.clno,h.brd_slno,m.c_status from heardt h
                inner join main m on h.diary_no=m.diary_no
                WHERE h.mainhead='F' AND
                m.diary_no in ($all_dno)";
            $result = $this->db->query($sql);
            if($result -> num_rows() >= 1)
            {
                return $result->result_array();
            }
            else
            {
                return false;
            }
        }
    }
    function tentative_date_condition_status($dnos_array,$MorF)
    {
        $all_dno = implode(',', $dnos_array);

        if($MorF=='M')
        {
            $sql="select m.diary_no,h.tentative_cl_dt
                 from heardt h
                inner join main m on h.diary_no=m.diary_no
                WHERE   m.diary_no in ($all_dno)";

            $result = $this->db->query($sql);
            if($result -> num_rows() >= 1)
            {
                return $result->result_array();
            }
            else
            {
                return false;
            }
        }
        if($MorF=='F')
        {
            $sql="select m.diary_no,judges,h.clno,h.brd_slno,m.c_status,h.tentative_cl_dt,h.next_dt from heardt h
                inner join main m on h.diary_no=m.diary_no
                WHERE m.diary_no in ($all_dno) and m.c_status='P'";
            $result = $this->db->query($sql);
            if($result -> num_rows() >= 1)
            {
                return $result->result_array();
            }
            else
            {
                return false;
            }
        }
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
    function add_new_mentionmemo($dno_array=null,$MorF=null)
    {

        date_default_timezone_set("Asia/Kolkata");
        $receivedDate=$_POST['mmReceivedDate'];
        $receivedDate=date('Y-m-d', strtotime($receivedDate));
        $presentedDate=$_POST['mmPresentedDate'];
        $presentedDate=date('Y-m-d', strtotime($presentedDate));
        $mmDecidedDate=$_POST['mmDecidedDate'];
        $mmDecidedDate=date('Y-m-d', strtotime($mmDecidedDate));
        //$order=$_POST['order'];

        $forListType=$_POST['forListType'];
        $roster_id=$_POST['bench'];
        $item_no=$_POST['itemNo'];

        //$remarks=mysqli_real_escape_string($db,urldecode($_POST['remarks']));
        $remarks=$_POST['remarks'];
        $date_of_entry=date('Y-m-d H:i:s');

        $dno=$this->session->userdata('diaryNumber');

        //check dno already added for the date
        $dn_array=$this->get_main_connected_array($dno);


        $insert_status='F';
        $insert_count=0;
        $foreach_count=0;

        /*foreach($dno_array as $dno)
        {
            $condition=" and 1=1";
            if($forListType==1)
                $condition=$condition." and m_roster_id=$roster_id";

            echo $query = "select diary_no from mention_memo where diary_no=$dno and date_of_received='$receivedDate' and date_on_decided='$presentedDate' and date_for_decided='$mmDecidedDate' $condition";
        }
        exit();*/
        foreach($dno_array as $dno)
        {
            $condition=" and 1=1";

            $num_row2=0;
            $num_row3=0;
            $conn_key=0;
            $foreach_count++;

            if($forListType==1)
                $condition=$condition." and m_roster_id=$roster_id";

            $query = "select diary_no from mention_memo where diary_no=$dno and date_of_received='$receivedDate' and date_on_decided='$presentedDate' and date_for_decided='$mmDecidedDate' $condition";

            $result1 = $this->db->query($query);


            if($result1->num_rows() > 0)
            {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Mention Memo of Diary No.is Already Mentioned for the Given Date</div>');
                redirect("Mentioning");
            }
            else
            {

                $query1="select conn_key from heardt where diary_no=$dno";
                $result2 = $this->db->query($query1);

                if($result2->num_rows() > 0)
                {
                    $result2=$result2->result_array();
                    $conn_key=$result2[0]['conn_key'];
                    // echo $conn_key;
                }

                // array for inserting into mention memo table
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
                        'user_id' => $this->session->userdata('dcmis_user_idd'),
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
                        'user_id' => $this->session->userdata('dcmis_user_idd'),
                        'update_time' => date('Y-m-d H:i:s'),
                        'spl_remark' => $remarks,
                        'for_court'=>'J',
                        'm_conn_key'=>$conn_key
                    );
                }

                $this->db->insert('mention_memo', $dataArray);
                //echo $this->db->last_query();



                $insert_id1 = $this->db->insert_id();
                $num_row1 = $this->db->affected_rows();
                if($num_row1>=1)
                {

                    $query15="select * from heardt where diary_no=$dno";
                    $result4 = $this->db->query($query15);

                    date_default_timezone_set("Asia/Kolkata");//set you countary name from below timezone list
                    $cur_date_timestamp = date("Y-m-d", time());
                    if($mmDecidedDate>$cur_date_timestamp && $forListType==2)
                    {
                        $sql1 = " INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id,
                            judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt, bench_flag, lastorder, listed_ia,
                            sitting_judges,is_nmd, no_of_time_deleted, list_before_remark)
                            (SELECT main.diary_no, main.conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id,
                            judges, coram, board_type, heardt.usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt,'',main.lastorder, listed_ia, sitting_judges, is_nmd, no_of_time_deleted,list_before_remark
                            FROM heardt JOIN main ON heardt.diary_no = main.diary_no
                            WHERE main.diary_no = $dno  AND c_status = 'P')";
                    }
                    /*
                    else
                    {
                        if($mmDecidedDate>$cur_date_timestamp)
                        {
                            $sql1 = " INSERT INTO last_heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id,
                            judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt, bench_flag, lastorder, listed_ia,
                            sitting_judges, list_before_remark, coram_del_res)
                            (SELECT main.diary_no, main.conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id,
                            judges, coram, board_type, main.usercode, ent_dt, module_id, mainhead_n, subhead_n,
                            main_supp_flag, listorder, tentative_cl_dt,'','', listed_ia, sitting_judges, list_before_remark,concat('Oral_Mentioning',date('Y-m-d H:i:s'))
                            FROM heardt JOIN main ON heardt.diary_no = main.diary_no
                            WHERE main.diary_no = $dno AND heardt.diary_no = $dno and (judges=0 or judges='')
                            and heardt.clno=0 and heardt.brd_slno=0 and heardt.mainhead='M' AND c_status = 'P')";
                        }
                    }*/
                    if($mmDecidedDate>$cur_date_timestamp && $forListType==2)
                    {
                        if($result4->num_rows() > 0)
                        {
                            $this->db->query($sql1);
                            $insert_id2 = $this->db->insert_id();
                            $num_row2 = $this->db->affected_rows();
                        }
                        else
                        {
                            $num_row2=1;
                        }
                    }
                    else
                    {
                        $num_row2=1; // if present data and decided date are same in that case no change done in heartdt and last_heardt
                    }

                    if($num_row2>=1)
                    {
                        $sql2='';
                        if($MorF=='M')
                        {
                            if($mmDecidedDate>$cur_date_timestamp && $forListType==2)
                            {
                                //For Mentioning List
                                $sql2 = "UPDATE heardt,main
                                    SET tentative_cl_dt='$mmDecidedDate', next_dt='$mmDecidedDate', clno=0, brd_slno=0,
                                    roster_id=0, judges=0, main_supp_flag=0, listorder=5, board_type='J',
                                    heardt.usercode=".$this->session->userdata('dcmis_user_idd').", ent_dt=NOW(), module_id=17
                                    WHERE heardt.diary_no='$dno' and mainhead='M' and (judges=0 or judges is null or judges='') and clno=0 and brd_slno=0 and main.c_status='P' ";
                            }
                            /*else
                            {

                                // ORAL Mentioning with future fixed date remarks
                                if($mmDecidedDate>$cur_date_timestamp && $forListType==1)
                                {
                                   $sql2 = "UPDATE heardt,main  SET
                                 tentative_cl_dt='$mmDecidedDate', next_dt='$mmDecidedDate', clno=0, brd_slno=0,
                                 roster_id=0, judges=0 , main_supp_flag=0, listorder=5, board_type='J',
                                 heardt.usercode=".$this->session->userdata('dcmis_user_idd').", ent_dt=NOW(), module_id=17
                                WHERE heardt.diary_no='$dno' and mainhead='M' and main.c_status='P'";
                                }
                            }*/
                        }
                        if($MorF=='F')
                        {
                            if($mmDecidedDate>$cur_date_timestamp && $forListType==2)
                            {
                                //For Mentioning List
                                $sql2 = "UPDATE heardt,main
                                SET
                                 tentative_cl_dt='$mmDecidedDate', next_dt='$mmDecidedDate', clno=0, brd_slno=0,
                                 roster_id=0, main_supp_flag=0, listorder=5, board_type='J',
                                 heardt.usercode=".$this->session->userdata('dcmis_user_idd').", ent_dt=NOW(), module_id=17
                                WHERE heardt.diary_no='$dno' and mainhead='F' and main.c_status='P' and main_supp_flag not in (1,2)";

                            }
                            /*else
                            {
                                if($mmDecidedDate>$cur_date_timestamp && $forListType==1)
                                {
                                    $sql2 = "UPDATE heardt,main
                                    SET
                                     clno=0, brd_slno=0,
                                     roster_id=0, main_supp_flag=0, listorder=5, board_type='J',
                                     heardt.usercode=".$this->session->userdata('dcmis_user_idd').", ent_dt=NOW(), module_id=17
                                    WHERE heardt.diary_no='$dno' and mainhead='F' and main.c_status='P' and main_supp_flag not in (1,2) ";

                                }
                            }*/
                        }
                        //echo $sql2;

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
                            'usercode'=>$this->session->userdata('dcmis_user_idd'),
                            'ent_dt'=>date('Y-m-d H:i:s'),
                            'module_id'=>17,
                            'mainhead_n'=>'M',
                            'subhead_n'=>808,
                            'main_supp_flag'=>0,
                            'listorder'=>5,
                            'tentative_cl_dt'=>$mmDecidedDate,
                            'listed_ia'=>null,
                            'sitting_judges'=>2,
                            'list_before_remark'=>null,
                            'coram_prev'=>0
                        );
                        /*$updated_by_user=$this->session->userdata('dcmis_user_idd');
                        echo $sql3="insert into heardt (diary_no, conn_key, next_dt, mainhead, subhead, clno, brd_slno, roster_id, judges, coram, board_type, usercode, ent_dt, module_id, mainhead_n, subhead_n, main_supp_flag, listorder, tentative_cl_dt, listed_ia, sitting_judges, list_before_remark, coram_prev)
                                                 ($dno,$conn_key,'$mmDecidedDate','M',808,0,0,0,0,NULL,'J',$updated_by_user,NOW(),17,'M',808,0,5,'$mmDecidedDate',NULL,NULL,NULL,NULL)";
                        exit(0);*/

                        if($mmDecidedDate>$cur_date_timestamp)
                        {
                            if($forListType==2) {
                                if ($result4->num_rows() > 0) {
                                    $this->db->query($sql2);
                                    //$insert_id3 = $this->db->insert_id();
                                    $num_row3 = $this->db->affected_rows();
                                } else {

                                    $this->db->insert('heardt', $InsertHeardtDataArray);
                                    //$this->db->query($sql3);
                                    $insert_id2 = $this->db->insert_id();
                                    //echo  $this->db->query('SELECT LAST_INSERT_ID() AS REC_ID;')->row()->REC_ID;
                                    $num_row3 = $this->db->affected_rows();

                                }
                            }
                        }
                        else
                        {
                            if($forListType==2) {
                                if ($result4->num_rows() > 0) {
                                    $num_row3 = 1; // if present data and decided date are same in that case no change done in heartdt and last_heardt
                                } else {
                                    $this->db->insert('heardt', $InsertHeardtDataArray);
                                    //$this->db->query($sql3);
                                    $insert_id2 = $this->db->insert_id();
                                    $num_row3 = $this->db->affected_rows();
                                }
                            }
                        }

                        if($num_row3>=1)
                        {
                            $insert_status = 'T';
                            $insert_count++;
                        }
                        else
                        {
                            //roallback sql2 and $sql 1
                        }

                    }
                }
            }
        }
        return array(
            'foreach_count' => $foreach_count,
            'insert_count' => $insert_count,
            'insert_status'=>$insert_status
        );
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

    function get_decided_mentioning()
    {
        $dateForDecided=$_POST['decidedDate'];
        $dateForDecided=date('Y-m-d', strtotime($dateForDecided));
        //echo $dateForDecided;
        $sql="select mm.diary_no as diary_nos,substr(mm.diary_no,1,length(m.diary_no)-4) AS diary_no, substr(mm.diary_no,-4)
                as diary_year, date_format(m.diary_no_rec_date, '%Y-%m-%d')
                 as diary_date, m.active_fil_no,m.pet_name,m.res_name,m.reg_no_display,
                 mm.date_of_received, mm.date_on_decided, mm.date_for_decided, mm.result,
                 mm.date_of_entry, mm.user_id, mm.update_time, mm.update_user, mm.spl_remark,mm.note_remark,
                 for_court from mention_memo mm left join main m on mm.diary_no=m.diary_no
                 where mm.display='Y' and mm.date_for_decided='$dateForDecided'";
        //echo $sql;
        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {

            return $query->result_array();
            // var_dump($result);
        }
        else
        {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">No Case Listed for selected Date</div>');
            redirect("Mentioning/MentioningReport");
        }

    }

    function get_onDate_mentioning()
    {
        $onDate=$_POST['decidedDate'];
        $onDate=date('Y-m-d', strtotime($onDate));
        //echo $dateForDecided;
        /*$sql="select mm.diary_no as diary_nos,substr(mm.diary_no,1,length(m.diary_no)-4) AS diary_no, substr(mm.diary_no,-4)
                as diary_year, date_format(m.diary_no_rec_date, '%Y-%m-%d')
                 as diary_date, m.active_fil_no,m.pet_name,m.res_name,m.reg_no_display,
                 mm.date_of_received, mm.date_on_decided, mm.date_for_decided, mm.result,
                 mm.date_of_entry, mm.user_id, mm.update_time, mm.update_user, mm.spl_remark,mm.note_remark,
                 for_court,rst.courtno, mm.m_brd_slno,
                 CASE When (rst.courtno IS NOT NULL AND mm.m_roster_id IS NOT NULL) THEN 'Oral Mentioning'  else 'Written Mentioning' END AS MentionType,
                 e.name as entryBy
                 from mention_memo mm
                 left join main m on mm.diary_no=m.diary_no
                 left join roster rst ON mm.m_roster_id=rst.id
                 LEFT JOIN users e ON e.usercode = mm.user_id
                 where mm.display='Y' and date(mm.date_of_entry) ='$onDate'
                 order by MentionType desc,rst.courtno asc
                 ";*/

        $sql=" SELECT
                        mm.diary_no AS diary_nos,
                        SUBSTR(mm.diary_no,
                            1,
                            LENGTH(m.diary_no) - 4) AS diary_no,
                        SUBSTR(mm.diary_no, - 4) AS diary_year,
                        DATE_FORMAT(m.diary_no_rec_date, '%Y-%m-%d') AS diary_date,
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
                        for_court,
                        rst.courtno,
                        mm.m_brd_slno,
                        CASE
                            WHEN
                                (rst.courtno IS NOT NULL
                                    AND mm.m_roster_id IS NOT NULL)
                            THEN
                                'Oral Mentioning'
                            ELSE 'Written Mentioning'
                        END AS MentionType,
                        e.name AS entryBy,
                        mm.m_conn_key,
                        CASE
                            WHEN
                                mm.diary_no <> mm.m_conn_key
                                    AND mm.m_conn_key <> 0
                            THEN
                                'C'
                            ELSE 'M'
                        END AS main_connected,
                        tentative_section(mm.diary_no) as section,
                        sh.stagename

                    FROM
                        mention_memo mm
                            LEFT JOIN
                        main m ON mm.diary_no = m.diary_no
                            left join
                        heardt h on m.diary_no=h.diary_no
                            left  join
                        subheading sh on h.subhead=sh.stagecode
                            LEFT JOIN
                        roster rst ON mm.m_roster_id = rst.id
                            LEFT JOIN
                        users e ON e.usercode = mm.user_id
                    WHERE
                        mm.display = 'Y'
                            AND DATE(mm.date_of_entry) = '$onDate'
                    ORDER BY MentionType DESC , rst.courtno , m_brd_slno ASC , main_connected DESC";

        //echo $sql;
        $query = $this->db->query($sql);
        if($query -> num_rows() >= 1)
        {

            return $query->result_array();
            // var_dump($result);
        }
        else
        {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">No Case Listed for selected Date</div>');
            redirect("Mentioning/MentioningReport");
        }

    }
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

		$this->db->distinct();
		$this->db->select('r.id roster_id, session, r.courtno, mm.diary_no, mm.date_on_decided, rj.judge_id, j.jname, j.jcode, mm.date_of_received,
                             mm.m_brd_slno, mm.spl_remark, `hd`.`mainhead`,`hd`.`board_type`,mm.diary_no, mm.id, mm.date_for_decided, mm.pdfname,
                             mm.upload_date');
		$this->db->from('mention_memo mm');
		$this->db->join('roster r', 'mm.m_roster_id = r.id', 'inner');
		$this->db->join('roster_judge rj', 'r.id = rj.roster_id', 'inner');
		//$this->db->join('cl_printed cp', 'r.id=cp.roster_id');
		$this->db->join('roster_bench rb', 'r.bench_id=rb.id', 'inner');
		$this->db->join('master_bench mb', 'rb.bench_id=mb.id', 'inner');
		$this->db->join('judge j', 'rj.judge_id = j.jcode', 'inner');
		$this->db->join('heardt hd', 'hd.diary_no  = mm.diary_no', 'left');
		$this->db->WHERE('mm.diary_no', $d_no.$year);
		$this->db->WHERE('mm.date_of_received', $date);

		if($flistype == 1){
			$where = "mm.m_roster_id is not NULL";
			$this->db->WHERE($where);
		}
		//->WHERE('rj.judge_id', 254)
		return $this->db->get()->row();
		//echo $this->db->last_query();
	}
	function UpdateMmData($data){
		$this->db->trans_start(); //trans start

		$mmCurrentRecord = $this->db->select('*')->from('mention_memo')->where('id',$data['id'])->get()->row_array();

		$array = array(
			'event_type' => 'U',
			'ipaddress' => $_SERVER['REMOTE_ADDR'],
			'update_user' => $data['session_id_url'],
			'action_perform_on' => date("Y-m-d H:i:s"),
		);

		$oldmmdata = array_merge($mmCurrentRecord,$array);


		if($this->db->insert('mention_memo_history', $oldmmdata)){
			//echo $this->db->last_query(); exit;
			$array = array(
				'date_of_received' => $data['mmReceivedDate'],
				'date_on_decided' => $data['mmPresentedDate'],
				'date_for_decided' =>$data['mmDecidedDate'],
				'spl_remark' => $data['remarks'],
				'm_brd_slno' => $data['itemNo'],
				'm_roster_id' => $data['bench'],
				'update_time' => date("Y-m-d H:i:s")
			);

			//print_r($oldmmdata); exit;
			$this->db->set($array)->where('id', $data['id'])->update('mention_memo');
			//echo $this->db->last_query(); exit;

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
				return true;
			}

		}

	}
	function DeleteMmData($data){
		$mmCurrentRecord = $this->db->select('*')->from('mention_memo')->where('id',$data['id'])->get()->row_array();

		$array = array(
			'event_type' => 'D',
			'ipaddress' => $_SERVER['REMOTE_ADDR'],
			'update_user' => $data['session_id_url'],
			'action_perform_on' => date("Y-m-d H:i:s"),
		);

		$oldmmdata = array_merge($mmCurrentRecord,$array);
		$this->db->trans_start(); //trans start
		if($this->db->insert('mention_memo_history', $oldmmdata)){
			$this->db->where('id', $data['id'])->delete('mention_memo');

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
				return true;
			}

		}
	}

	function getAccessDetails($id){
		$array = array('usertype'=>4,'section'=>11, 'display'=>'Y', 'usercode'=>$id);
		return $this->db->get_where('users',$array)->result();
		//$this->db->get('users');
	}
	


   }
