<?php
namespace App\Models\Judicial;

use CodeIgniter\Model;

class LeaveGrantDisposeModel extends Model{

    public function __construct(){
        parent::__construct();
        $this->db = db_connect();
    }



    function get_judges($jcodes){
        $jnames="";
        if ($jcodes!=''){
            $t_jc=explode(",",$jcodes);
            for($i=0;$i<count($t_jc);$i++){
                // $sql11a = "SELECT jname  FROM  judge where jcode= ".$t_jc[$i];
                // $t11a = mysql_query($sql11a);
                $builder = $this->db->table('master.judge');
                $builder->select('jname');
                $builder->where('jcode', $t_jc[$i]);
                $query = $builder->get();
                $t11a = $query->getResultArray();

            if (count($t11a) > 0) {
                foreach ($t11a as $row11a) {
                    // while ($row11a = mysql_fetch_array($t11a)) {
                    if($jnames==''){
                        $jnames.=$row11a["jname"];
                    }else {
                        if($i==(count($t_jc)-1)){
                            $jnames.=" and ".$row11a["jname"];
                        }else{
                            $jnames.=", ".$row11a["jname"];
                        }
                    }
                        
                }
            }
        }
        }
        return $jnames;
    }

    function get_case_nos($dn,$separator,$rby=''){
    
        $t_fil_no='';
      
        $builder = $this->db->table('main m');
        $builder->select("casetype_id,
            CONCAT(
                active_fil_no,
                ':',
                CASE
                    WHEN active_reg_year = 0 OR active_fil_dt > '2017-05-10'::date THEN EXTRACT(YEAR FROM active_fil_dt)::VARCHAR
                    ELSE active_reg_year::VARCHAR
                END,
                ':',
                TO_CHAR(active_fil_dt, 'DD-MM-YYYY')
            ) ad,
            CASE
                WHEN fil_no_fh != active_fil_no AND fil_no_fh != fil_no AND fil_no_fh != '' THEN CONCAT(
                    fil_no_fh,
                    ':',
                    CASE
                        WHEN reg_year_fh = 0 OR fil_dt_fh > '2017-05-10'::date THEN EXTRACT(YEAR FROM fil_dt_fh)::VARCHAR
                        ELSE reg_year_fh::VARCHAR
                    END,
                    ':',
                    TO_CHAR(fil_dt_fh, 'DD-MM-YYYY')
                )
                ELSE ''
            END rd,
            CASE
                WHEN fil_no != active_fil_no AND fil_no_fh != fil_no AND fil_no != '' THEN CONCAT(
                    fil_no,
                    ':',
                    CASE
                        WHEN reg_year_mh = 0 OR fil_dt > '2017-05-10'::date THEN EXTRACT(YEAR FROM fil_dt)::VARCHAR
                        ELSE reg_year_mh::VARCHAR
                    END,
                    ':',
                    TO_CHAR(fil_dt, 'DD-MM-YYYY')
                )
                ELSE ''
            END md", false);
        $builder->where('diary_no', $dn);
        $query = $builder->get();
        $result_main = $query->getResultArray();

        $cases="";
        if(count($result_main)>0){
            $row_main= $result_main[0];
            if($row_main['ad']!=''){
                $t_m_y=explode(':',$row_main['ad']);
                if($t_m_y[0]!=''){
                    $cases.=$t_m_y[0].",";
                    $t_m1=substr($t_m_y[0],0,2);
                    $t_m2=substr($t_m_y[0],3,6);
                    $t_m21=substr($t_m_y[0],10,6);
                    $t_m3=$t_m_y[1];
                    $t_m4=$t_m_y[2];
                    // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='".$t_m1."' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    // $row=mysql_fetch_array($sql_ct_type);
                    $builder1 = $this->db->table('master.casetype');
                    $builder1->select('short_description,cs_m_f');
                    $builder1->where('casecode', $t_m1);
                    $builder1->where('display', 'Y');
                    $query1 = $builder1->get();
                    $row = $query1->getResultArray();
                    $row = $row[0];
                    $res_ct_typ = $row['short_description'];   
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if($t_m2==$t_m21){
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }else{
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }
                }
            }
            if($row_main['rd']!=''){
                $t_m_y=explode(':',$row_main['rd']);
                if($t_m_y[0]!=''){
                    $cases.=$t_m_y[0].",";
                    $t_m1=substr($t_m_y[0],0,2);
                    $t_m2=substr($t_m_y[0],3,6);
                    $t_m21=substr($t_m_y[0],10,6);
                    $t_m3=$t_m_y[1];
                    $t_m4=$t_m_y[2];
                    // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='".$t_m1."' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    // $row=mysql_fetch_array($sql_ct_type);
                    $builder1 = $this->db->table('master.casetype');
                    $builder1->select('short_description,cs_m_f');
                    $builder1->where('casecode', $t_m1);
                    $builder1->where('display', 'Y');
                    $query1 = $builder1->get();
                    $row = $query1->getResultArray();
                    $row = $row[0];
                    $res_ct_typ = $row['short_description'];   
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if($t_m2==$t_m21){
                        $t_fil_no.='<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }else{
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }
                }
            }
            if($row_main['md']!=''){                
                $t_m_y=explode(':',$row_main['md']);
                if($t_m_y[0]!=''){
                    $cases.=$t_m_y[0].",";
                    $t_m1=substr($t_m_y[0],0,2);
                    $t_m2=substr($t_m_y[0],3,6);
                    $t_m21=substr($t_m_y[0],10,6);
                    $t_m3=$t_m_y[1];
                    $t_m4=$t_m_y[2];
                    // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='".$t_m1."' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                    // $row=mysql_fetch_array($sql_ct_type);
                    $builder1 = $this->db->table('master.casetype');
                    $builder1->select('short_description,cs_m_f');
                    $builder1->where('casecode', $t_m1);
                    $builder1->where('display', 'Y');
                    $query1 = $builder1->get();
                    $row = $query1->getResultArray();
                    $row = $row[0];
                    $res_ct_typ = $row['short_description'];   
                    $res_ct_typ_mf = $row['cs_m_f'];
                    if($t_m2==$t_m21){
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }else{
                        $t_fil_no.= '<font color="#043fff" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                    }
                }
            }
        }
        // $sql_mc_h="SELECT t.oldno,
        // GROUP_CONCAT(DISTINCT CONCAT(t.new_registration_number,':',t.new_registration_year,':',DATE_FORMAT(t.order_date,'%d-%m-%Y')) ORDER BY t.order_date,t.id ) AS newno FROM
        // (SELECT @rowid:=@rowid+1 AS rowid,`main_casetype_history`.*, IF(@rowid=1,IF(old_registration_number='' OR old_registration_number IS NULL,'',CONCAT(old_registration_number,':',old_registration_year,':',DATE_FORMAT(order_date,'%d-%m-%Y'))),'') AS oldno 
        // FROM `main_casetype_history`, (SELECT @rowid:=0) AS init
        // WHERE `diary_no` = ".$dn." AND is_deleted='f'
        // ORDER BY `main_casetype_history`.`order_date`,id ) t GROUP BY t.diary_no";
        $builder2 = $this->db->table('main_casetype_history');
        $builder2->select('t.oldno');
        $builder2->select("STRING_AGG(DISTINCT CONCAT(t.new_registration_number, ':', t.new_registration_year, ':', TO_CHAR(t.order_date, 'DD-MM-YYYY')), ' ') AS newno");
        $subQuery = $this->db->table('main_casetype_history')
            ->select('ROW_NUMBER() OVER (ORDER BY order_date, id) AS rowid', false)
            ->select('*')
            ->select("CASE
                            WHEN ROW_NUMBER() OVER (ORDER BY order_date, id) = 1
                            THEN COALESCE(NULLIF(old_registration_number, ''), CONCAT(old_registration_number, ':', old_registration_year, ':', TO_CHAR(order_date, 'DD-MM-YYYY')))
                            ELSE ''
                        END AS oldno", false)
            ->where('diary_no', '16892023')
            ->where('is_deleted', 'f')
            ->getCompiledSelect();
        $builder2->from("($subQuery) t");
        $builder2->groupBy(['t.diary_no', 't.oldno']);
    
        // $queryString = $builder2->getCompiledSelect();
        // echo $queryString;
        // exit();

        $query2 = $builder2->get();
        $result_mc_h = $query2->getResultArray(); 
        // $result_mc_h = mysql_query($sql_mc_h) or die(mysql_error().$sql_mc_h);
        if(count($result_mc_h)>0){
            $cnt=0;
            foreach ($result_mc_h as $row_mc_h) {
                // while($row_mc_h=mysql_fetch_array($result_mc_h)){
                if($row_mc_h['oldno']!=''){
                    $t_m=explode(',',$row_mc_h['oldno']);        
                    $t_m_y=explode(':',$t_m[0]);
                    $pos = strpos($cases, $t_m_y[0]);            
                    if ($pos === false) {
                        $cnt++;
                        if($cnt%2==0){
                            $bgcolor="#ff0015";
                        }else{
                            $bgcolor="#ff01c8";
                        }
                        $cases.=$t_m_y[0].",";
                        $t_m1=substr($t_m_y[0],0,2);
                        $t_m2=substr($t_m_y[0],3,6);
                        $t_m21=substr($t_m_y[0],10,6);
                        $t_m3=$t_m_y[1];
                        $t_m4=$t_m_y[2];
                        // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='".$t_m1."' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                        // $row=mysql_fetch_array($sql_ct_type);
                        $builder1 = $this->db->table('master.casetype');
                        $builder1->select('short_description,cs_m_f');
                        $builder1->where('casecode', $t_m1);
                        $builder1->where('display', 'Y');
                        $query1 = $builder1->get();
                        $row = $query1->getResultArray();
                        $row = $row[0];
                        $res_ct_typ = $row['short_description'];   
                        $res_ct_typ_mf = $row['cs_m_f'];
                        if($t_m2==$t_m21){
                            $t_fil_no.= '<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                        }else{
                            $t_fil_no.= '<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                        }
                    } 
                }
                $t_chk="";        
                if($row_mc_h['newno']!=''){
                    $t_m=explode(',',$row_mc_h['newno']);
                    for ($i = 0; $i < count($t_m); $i++) {
                        $t_m_y=explode(':',$t_m[$i]);
                        $pos = strpos($cases, $t_m_y[0]);
                        if ($pos === false) {
                            $cases.=$t_m_y[0].",";
                            $t_m1=substr($t_m_y[0],0,2);
                            $t_m2=substr($t_m_y[0],3,6);
                            $t_m21=substr($t_m_y[0],10,6);
                            $t_m3=$t_m_y[1];
                            $t_m4=$t_m_y[2];  
                            $t_fn=$t_m_y[0];
                            if($t_chk!=$t_fn){
                                $cnt++;
                                if($cnt%2==0){
                                    $bgcolor="#ff0015";
                                }else{
                                    $bgcolor="#ff01c8";
                                }
                                // $sql_ct_type = mysql_query("Select short_description,cs_m_f from casetype where casecode='".$t_m1."' and display='Y'") or die("Error" . __LINE__ . mysql_error());
                                // $row=mysql_fetch_array($sql_ct_type);
                                $builder1 = $this->db->table('master.casetype');
                                $builder1->select('short_description,cs_m_f');
                                $builder1->where('casecode', $t_m1);
                                $builder1->where('display', 'Y');
                                $query1 = $builder1->get();
                                $row = $query1->getResultArray();
                                $row = $row[0];
                                $res_ct_typ = $row['short_description'];   
                                $res_ct_typ_mf = $row['cs_m_f'];
                                if($t_m2==$t_m21)
                                $t_fil_no.='<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                                else
                                $t_fil_no.='<font color="'.$bgcolor.'" style=" white-space: nowrap;">'.$res_ct_typ." ".$t_m2.' - '. $t_m21 .' / '.$t_m3.'</font>'.$separator."(Reg.Dt.".$t_m4.")<br>";
                            }
                            $t_chk=$t_fn;        
                        }
                    }
                }        
            }
        }
    
        if(trim($t_fil_no)==''){
            // $sql12=   "SELECT short_description from casetype where casecode='".$row_main['casetype_id']."'";
            // $results12 = mysql_query($sql12) or die(mysql_error()." SQL:".$sql12);
            $builder1 = $this->db->table('master.casetype');
            $builder1->select('short_description');
            $builder1->where('casecode', $row_main['casetype_id']);
            $query1 = $builder1->get();
            $results12 = $query1->getResultArray();
            if (count($results12) > 0) {
                $row_12 = $results12[0]; 
                $t_fil_no=$row_12['short_description'];
            }
        }                    
                
        return $t_fil_no;   
    }
    
    function get_case_remarks($dn,$cldate,$jcodes,$clno){
        
        // $sql_cr="select h.cat_head_id,
        //         c.cl_date,
        //         c.jcodes,
        //         c.status,
        //         GROUP_CONCAT(
                // CONCAT(h.head, if(c.head_content!='', concat(' [', c.head_content, ']'),'')) SEPARATOR ', '
        //         ) AS crem,
        //         GROUP_CONCAT(
        //         CONCAT(c.r_head, '|', c.head_content, '^^') SEPARATOR ''
        //         ) AS caseval,c.mainhead,c.clno FROM
        //         case_remarks_multiple c inner join
        //         case_remarks_head h on c.r_head=h.sno
        //     WHERE c.diary_no = ".$dn." 
        //         AND c.cl_date = '".$cldate."' 
        //             AND c.jcodes='".$jcodes."' 
        //                 AND c.clno='".$clno."' 
        //     GROUP BY c.cl_date ORDER BY h.priority";
        // $result_cr = mysql_query($sql_cr) or die("Errror: " . __LINE__ . mysql_error());
        $query = $this->db->table('case_remarks_multiple c');
        $query->select([
            'h.cat_head_id',
            'c.cl_date',
            'c.jcodes',
            'c.status',
            "STRING_AGG(CONCAT(h.head, CASE WHEN c.head_content != '' THEN CONCAT(' [', c.head_content, ']') ELSE '' END), ', ') AS crem",
            "STRING_AGG(CONCAT(c.r_head, '|', c.head_content, '^^'), '') AS caseval",
            'c.mainhead',
            'c.clno'
        ]);
        $query->join('master.case_remarks_head h', 'c.r_head = h.sno');
        $query->where('c.diary_no', $dn);
        $query->where('c.cl_date', $cldate);
        $query->where('c.jcodes', $jcodes);
        $query->where('c.clno', $clno);
        $query->groupBy(['c.cl_date', 'h.cat_head_id', 'c.jcodes', 'c.status', 'c.mainhead', 'c.clno', 'h.priority']);
        $query->orderBy('h.priority');
        $buildQuery = $query->get();
        $result_cr = $buildQuery->getResultArray();

        $cval="";
        if (count($result_cr) > 0) {
            $row_cr = $result_cr[0];
            $crem=$row_cr['crem'];
        }else {
            $crem='';  
        }
        return $crem;
    }

    function get_real_diaryno($dn){
        $real_diary_no="";
        if($dn!=""){
            $real_diary_no=substr($dn, 0, -4)."/".substr($dn, -4);
        }
        return $real_diary_no;
    }

    function get_last_listing($dn){
        $check_for_lg="";
        $output="";
        // $sql_listing="Select a.*,section_name,b.name from heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$dn."' "; 
        $builder = $this->db->table('heardt a');
        $builder->select('a.*, section_name, b.name');
        $builder->join('master.users b', 'a.usercode = b.usercode', 'left');
        $builder->join('master.usersection c', 'b.section = c.id', 'left');
        $builder->where('a.diary_no', $dn);
        $query = $builder->get();
        $result_listing = $query->getResultArray();
        // $result_listing = mysql_query($sql_listing) or die("Errror: " . __LINE__ . mysql_error());
        if (count($result_listing) > 0){
            $row_listing = $result_listing[0];
            if($row_listing['judges']!='' && $row_listing['judges']!='0' && $row_listing['clno'] > 0 && $row_listing['brd_slno'] > 0 && $row_listing['roster_id'] > 0){
                if(strtotime($row_listing['next_dt'])>strtotime(date('Y-m-d'))){
                    $check_for_case_is_listed_after_current_date="LISTED";
                }else{
                    $output.="<b>".$row_listing['next_dt']."</b><br>";
                    $bt=$row_listing['board_type'];
                    if($bt=='J'){
                        $bt='Judge';}
                    else if($bt=='C'){
                        $bt='Chamber';}
                    else if($bt=='R'){
                        $bt='Registry'; }
                    else{
                        $bt='';}
                    $output.=$bt."<br>";
                    if($row_listing['main_supp_flag']=="1" or $row_listing['main_supp_flag']=="2"){
                        $output.="<b>".$this->get_judges($row_listing['judges'])."</b><br>";
                    }
                    if($row_listing['judges']!='' and $row_listing['judges']!='0'){
                        $cr = $this->get_case_remarks($row_listing['diary_no'],$row_listing['next_dt'],$row_listing['judges'],$row_listing['brd_slno']);
                        if (strpos($cr, 'Leave Granted') !== false){
                            $cr = str_replace("Leave Granted", "<font color=red>Leave Granted</font>", $cr);
                            $check_for_lg="found";
                        }                    
                    } else{
                        $cr = "";
                    }
                    $output.=$cr."<br>";
                }                
            }
        }
        if($output==''){
            $output1="";
            $sql_listing1="Select a.*,section_name,b.name from last_heardt a LEFT JOIN users b ON a.usercode=b.usercode LEFT JOIN usersection c ON b.section=c.id where diary_no='".$dn."' and next_dt!='0000-00-00' and a.bench_flag='' order by ent_dt DESC"; 
            // $result_listing1 = mysql_query($sql_listing1) or die("Errror: " . __LINE__ . mysql_error());
            $builder1 = $this->db->table('last_heardt a');
            $builder1->select('a.*, section_name, b.name');
            $builder1->join('master.users b', 'a.usercode = b.usercode', 'left');
            $builder1->join('master.usersection c', 'b.section = c.id', 'left');
            $builder1->where('a.diary_no', $dn);
            $builder1->where('CAST(a.next_dt AS CHAR(10)) !=', '0000-00-00');
            $builder1->where('a.bench_flag', '');
            $builder1->orderBy('ent_dt', 'DESC');
            $query1 = $builder1->get();
            $result_listing1 = $query1->getResultArray();

            if (count($result_listing1) > 0){
                foreach ($result_listing1 as $row_listing1) {
                    // while ($row_listing1 = mysql_fetch_array($result_listing1)) {
                    if($output1=='' and $row_listing1['clno']!=0 and $row_listing1['brd_slno']!=0 and ($row_listing1['main_supp_flag']=="1" or $row_listing1['main_supp_flag']=="2") and $row_listing1['judges']!='' and $row_listing1['judges']!='0'){

                        $output1.="<b>".$row_listing1['next_dt']."</b><br>";
                        $bt=$row_listing1['board_type'];
                        if($bt=='J'){
                            $bt='Judge';}
                        else if($bt=='C'){
                            $bt='Chamber';}
                        else if($bt=='R'){
                            $bt='Registry'; }
                        else{
                            $bt='';}
                        $output1.=$bt."<br>";
                        if($row_listing1['main_supp_flag']=="1" or $row_listing1['main_supp_flag']=="2"){
                            $output1.="<b>".$this->get_judges($row_listing1['judges'])."</b><br>";
                        }
                        if($row_listing1['judges']!='' and $row_listing1['judges']!='0'){
                            $cr = $this->get_case_remarks($row_listing1['diary_no'],$row_listing1['next_dt'],$row_listing1['judges'],$row_listing1['brd_slno']);
                            if (strpos($cr, 'Leave Granted') !== false){
                                $cr = str_replace("Leave Granted", "<font color=red>Leave Granted</font>", $cr);
                                $check_for_lg="found";
                            }                        
                        } else{
                            $cr = "";
                        }
                        $output1.=$cr."<br>";                        
                    }

                }
            }
        }
        return $output.$output1."|#|".$check_for_lg;
    }

    function get_listing_dates($diaryno){
        $output="";
        // $sql_list="Select b.diary_no, b.conn_key,a.next_dt,list,main_supp_flag,Case when a.board_type='J' Then 'Court' When a.board_type='C' Then 'Chamber' WHEN a.board_type='R' Then 'Registrar' END as bt  from heardt a left join conct b on a.diary_no=b.diary_no where a.diary_no='".$diaryno."' ";
        //  $result_list = mysql_query($sql_list) or die("Errror: " . __LINE__ . mysql_error());
        $builder = $this->db->table('heardt a');
        $builder->select(['b.diary_no', 'b.conn_key', 'a.next_dt', 'list', 'main_supp_flag']);
        $builder->join('conct b', 'a.diary_no = b.diary_no', 'left');
        $builder->where('a.diary_no', $diaryno);
        $query = $builder->get();
        $result_list = $query->getResultArray();

        if(count($result_list) > 0){
        $row = $result_list[0];
        if($row['main_supp_flag']==0){
            $t_rnr=" <br><font color=green>(Ready)</font>";
        }
        if($row['main_supp_flag']==3){
            $t_rnr=" <br><font color=blue>(Not Ready)</font>";
        }
        if($row['list']=='N' && $row['diary_no'] != $row['conn_key']){
            $list="<br><font color='red'>[NOT TO BE LISTED]</font>";
        }
        else if($row['list']=='Y')
            $list="<br><font color='green'>[LISTED]</font>";
            $ucode =  $_SESSION['login']['usercode'];
            // $sql_display = "select display_flag, always_allowed_users from case_status_flag where date(to_date)='0000-00-00' and flag_name='tentative_listing_date'";
            // $result_sql_display = mysql_query($sql_display) or die(mysql_error()." SQL:".$sql_display);
            $builder2 = $this->db->table('master.case_status_flag');
            $builder2->select(['display_flag', 'always_allowed_users']);
            $builder2->where('CAST(to_date AS CHAR(10))', '0000-00-00');
            $builder2->where('flag_name', 'tentative_listing_date');
            $query2 = $builder2->get();
            $result_sql_display = $query2->getResultArray();
            if(!empty($result_sql_display)){
                $result_array = $result_sql_display[0];
                if($result_array['display_flag']==1 || in_array($ucode, explode(',', $result_array['always_allowed_users']))){
                    $output=  date('d-m-Y', strtotime($row['next_dt']))."|#|".$row['bt'].$t_rnr.$list;
                }else{
                    $output=  "|#|".$row['bt'].$t_rnr.$list;
                }
            }
            
        } 
        return $output;
    }


    function get_bunch_cases($dn){
        $check_for_cb="";
        $me2 = array();
        $html_bunch_cases = "";
        $chk_for_main='';
        $connchks = '';
        if($dn!=""){
            // $sql_p1 = "SELECT conn_key FROM main WHERE (diary_no='".$dn."')";
            // $result_p1 = mysql_query($sql_p1) or die(mysql_error());
            $builder1 = $this->db->table('main');
            $builder1->select("conn_key");   
            $builder1->where('diary_no', $dn);
            $query1 = $builder1->get();
            $result_p1 = $query1->getResultArray();

            if(count($result_p1) > 0){
                $conn_key= $result_p1[0]['conn_key'];
                // $sql_p = "SELECT diary_no,if(conn_key=diary_no, 'M',conn_type) as c_type,list FROM conct WHERE (conn_key='".$conn_key."') ORDER BY if(diary_no='".$conn_key."',0,1),c_type DESC";
                // $result_p = mysql_query($sql_p) or die(mysql_error());
                $builder2 = $this->db->table('conct');
                $builder2->select([
                    'diary_no',
                    'list',
                    "CASE WHEN conn_key = diary_no THEN 'M' ELSE conn_type END AS c_type"
                ]);
                $builder2->where('conn_key', $conn_key);
                $builder2->orderBy("CASE WHEN diary_no = '$conn_key' THEN 0 ELSE 1 END", 'ASC');
                $builder2->orderBy('c_type', 'DESC');
                $query2 = $builder2->get();
                $result_p = $query2->getResultArray();
                // print_r($result_p); die;
                if(count($result_p) > 0){
                    foreach ($result_p as $row) {
                        // while ($row = mysql_fetch_array($result_p)) {
                        if($chk_for_main=='' && $row['c_type']!='M'){
                            $me2[$conn_key]['diary_no'] = $conn_key;
                            $me2[$conn_key]['c_type'] = 'M';
                            $me2[$conn_key]['list'] = $row['list'];
                            $chk_for_main='over';
                        }
                        $me2[$row['diary_no']]['diary_no'] = $row['diary_no'];
                        $me2[$row['diary_no']]['c_type'] = $row['c_type'];
                        $me2[$row['diary_no']]['list'] = $row['list'];
                    }
                }else{
                    $me2[$dn]['diary_no'] = $dn;
                    $me2[$dn]['c_type'] = '';
                    $me2[$dn]['list'] = '';
                    $chk_for_main='over';        
                }
                $sn=0;$ttl_checked=0; 
                $html_bunch_cases .= '<table class="table_tr_th_w_clr c_vertical_align" width="100%">';
                $html_bunch_cases .= "<tr><td></td><td align='center' width='30px'><b>S.N.</b></td><td><b>Diary No./ Case Nos.</b></td><td><b>Petitioner Vs. Respondant</b></td><td><b>Last listing details</b></td><td><b>Case Status & Proposed for</b></td></td><td>Reg. in M</td><td>Reg. in F</td><td><b>DA</b></td></tr> "; 

                foreach ($me2 as $row => $link) {
                    $sn++;
                    $main_details= $this->get_main_details($link['diary_no'], 'diary_no,pet_name,res_name,c_status,fil_no_fh');

                    if(is_array($main_details)){ 
                        foreach ($main_details as $rowm => $linkm) {
                            $t_pname=$linkm['pet_name'];
                            $t_rname=$linkm['res_name'];
                            if($linkm['c_status']=='P'){
                                $t_status="<font color=blue>".$linkm['c_status']."</font>";
                            }else{
                                $t_status="<font color=red>".$linkm['c_status']."</font>";  
                            }
                            if($link["list"]=='Y'){
                                $t_link="<font color=blue>".$link["list"]."</font>";
                            }else{
                                $t_link="<font color=red>".$link["list"]."</font>";
                            }  
                            $t_fil_no_fh=$linkm['fil_no_fh'];
                            if ($link["list"] == "Y" and $link['c_type'] != "M"){
                                $chked = "checked";
                                $ttl_checked++;
                            }else{
                                $chked = "";
                            }
                            if ($linkm['c_status'] == "D"){
                                $chked = " disabled=disabled";
                            }
                        }
                    }

                    $t_conn_type="";
                    if ($link['c_type'] == "M"){
                        $t_conn_type="(<font color=red>Main Case</font>)";    
                    }
                    if ($link['c_type'] == "C"){
                        $t_conn_type="(<font color=blue>Connected</font>)";    
                    }
                    if ($link['c_type'] == "L"){
                        $t_conn_type="(<font color=green>Linked</font>)";    
                    }
                    //DA NAME START FOR CONNECTED
                    $da_name_conn = "";
                    // $sql_da_conn = "SELECT dacode, name,section_name FROM main a LEFT JOIN users b ON dacode = b.usercode LEFT JOIN usersection us ON b.section=us.id WHERE diary_no = '".$link['diary_no']."' and dacode!=0 ";
                    // $results_da_conn = mysql_query($sql_da_conn) or die(mysql_error().$sql_da_conn);
                    $builder3 = $this->db->table('main a');
                    $builder3->select(['dacode', 'name', 'section_name']);
                    $builder3->join('master.users b', 'a.dacode = b.usercode', 'LEFT');
                    $builder3->join('master.usersection us', 'b.section = us.id', 'LEFT');
                    $builder3->where('a.diary_no', $link['diary_no']);
                    $builder3->where('a.dacode !=', 0);
                    $query3 = $builder3->get();
                    $results_da_conn = $query3->getResultArray();
                    if (count($results_da_conn) > 0) {
                        $row_da_conn = $results_da_conn[0];
                        $da_name_conn = "<font color='blue' style='font-size:10px;'>" . $row_da_conn["name"] . "</font><br>";
                        if ($row_da_conn["dacode"] != ""){
                            $da_name_conn.="[<font color='red' style='font-size:10px;'>" . $row_da_conn["section_name"] . "</font>]";
                        }                            
                    }             
                    //DA NAME ENDS FOR CONNECTED
                    $t_current_proposed=str_replace('|#|','<br>', $this->get_listing_dates($link['diary_no']));     
                    $t_list= $this->get_last_listing($link['diary_no']);
                    $t_list1= explode('|#|',$t_list);
                    if($t_list1[1]!='' and $linkm['c_status'] != "D"){
                        $check_for_cb="yes";
                        $check_for_reg= get_cases_mf($link['diary_no']); 
                        //var_dump($check_for_reg);
                        $t_var=explode(",",$check_for_reg);
                        if($t_var[0]=='yes'){
                            $t_print="(D-Stage)";
                        }else{
                            $t_print="";
                        }

                        $t_lc=0;
                        $t_lcid=0;
                        $sql_l = "SELECT count(*),GROUP_CONCAT(lower_court_id) as lcid FROM `lowerct` WHERE `diary_no` =".$link['diary_no']. " and lw_display='Y' and is_order_challenged='Y' group by diary_no";
                        $resultsl = mysql_query($sql_l) or die(mysql_error().$sql_l);
                        if (mysql_affected_rows() > 0) {
                            $row_l = mysql_fetch_array($resultsl);
                            $t_lc=   $row_l[0];
                            $t_lcid=$row_l[1];
                        }

                        $html_bunch_cases .= "<tr><td><input type='checkbox' name='chkbtn".$link['diary_no']."' id='chkbtn".$link['diary_no']."' value='".$link['diary_no']."$$".$t_lc."##".$t_lcid."$$".$t_var[5]."'/></td><td align='center' width='30px'>".$sn."</td><td align=center><b>".'<a data-animation="fade" data-reveal-id="myModal" onclick="return call_fcs(' . substr($link['diary_no'],0,-4) . ','. substr($link['diary_no'],-4).',\'\',\'\',\'\');" href="#">'.$this->get_real_diaryno($link['diary_no'])."</a></b> ".$t_conn_type."<br>".$this->get_case_nos($link['diary_no'],'&nbsp;&nbsp;')."</td><td>".$t_pname." Vs. ".$t_rname."</td><td>".$t_list1[0]."</td><td>".$t_status."<br>".$t_current_proposed."</td><td>".$t_var[1].$t_print."</td><td>".$t_var[3].$t_print."</td><td>".$da_name_conn."</td></tr>";
                    }else{
                        $html_bunch_cases .= "<tr><td></td><td align='center' width='30px'>".$sn."</td><td align=center><b>".'<a data-animation="fade" data-reveal-id="myModal" onclick="return call_fcs(' . substr($link['diary_no'],0,-4) . ','. substr($link['diary_no'],-4).',\'\',\'\',\'\');" href="#">'.$this->get_real_diaryno($link['diary_no'])."</a></b> ".$t_conn_type."<br>".$this->get_case_nos($link['diary_no'],'&nbsp;&nbsp;')."</td><td>".$t_pname." Vs. ".$t_rname."</td><td>".$t_list1[0]."</td><td>".$t_status."<br>".$t_current_proposed."</td><td></td><td></td><td>".$da_name_conn."</td></tr>";
                    }
                        
                
                    if ($link['c_type'] != "M"){
                        if($t_fil_no_fh==''){
                            $t_check='<div class="fh_error" style="display:none;"><font color="red">Case is not registered in Regular Hearing</font></div>';
                        }
                        else{
                            $t_check='';
                        }
                    
                        $connchks.="<tr><td align='center'>";
                        if ($linkm['c_status'] != "D"){
                            $connchks.="<input type='checkbox' name='ccchk" . $link['diary_no'] . "' id='ccchk" . $link['diary_no'] . "' value='" . $link['diary_no'] . "' " . $chked . " >";
                        }
                        $connchks.="</td><td>D.No. : " . $this->get_real_diaryno($link['diary_no'])."<br>".$this->get_case_nos($link['diary_no'],'&nbsp;&nbsp;') . "</td><td>" . $t_pname." Vs. ".$t_rname.$t_check. "</td><td align='center'>" . $t_status."</br>".$t_current_proposed. "</td><td></td></tr>";
                    }
                }
                $html_bunch_cases .= "</table>";
                $connchks.="</table>";
            }
            if($check_for_cb=="yes"){
                $html_bunch_cases .= '<table width="100%" border="1" style="border-collapse: collapse">';
                if($cldate==""){
                    $cldate=date('d-m-Y');
                }

                $html_bunch_cases .= '<tr>
                <td align="center"><b><font size="+1">Cause List/Order Date : </font></b>&nbsp;<input class="dtp" type="text" name="cldate" id="cldate" value="'.$cldate.'" size="12" readonly="readonly"></td>
                <td align="center" rowspan="4"><b><font size="+1">Coram : </font></b>&nbsp;
                    <select size="1" name="djudge" id="djudge">';

                $sql2 = "SELECT jcode AS jcode, case when (jname like '%CHIEF JUSTICE%' OR jname like '%Registrar%') THEN concat(trim(jname),' (', first_name,' ',sur_name,' )') ELSE trim(jname) END AS jname FROM judge WHERE display = 'Y'  AND jtype IN('J','R')  ORDER BY if(is_retired='N',0,1),jtype,judge_seniority";
                $results2=mysql_query($sql2) or die(mysql_error());
                $tjud1=$tjud2=$tjud3=$tjud4=$tjud5="";$cljudge1='';$cljudge2='';$cljudge3='';$cljudge4='';$cljudge5='';
                if(mysql_affected_rows()>0) {
                    $djcnt=0;
                    while($row2=mysql_fetch_array($results2)) {
                        if($cljudge1==$row2["jcode"]){
                            $html_bunch_cases .= '<option value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'" selected>'.str_replace("\\","",$row2["jname"]).'</option>';
                        }else{
                            $html_bunch_cases .= '<option value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'">'.str_replace("\\","",$row2["jname"]).'</option>';				
                        }
                        if($cljudge1==$row2["jcode"]){
                            $djcnt++;
                        $tjud1= '<input type="checkbox"  id="hd_chk_jd1" onclick="getDone_upd_cat(this.id);" checked="true" value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'"/>&nbsp;<font color=yellow><b>'.str_replace("\\","",$row2["jname"]).'</b></font>';}
                        if($cljudge2==$row2["jcode"]){
                            $djcnt++;
                        $tjud2= '<input type="checkbox"  id="hd_chk_jd2" onclick="getDone_upd_cat(this.id);" checked="true" value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'"/>&nbsp;<font color=yellow><b>'.str_replace("\\","",$row2["jname"]).'</b></font>';   }
                        if($cljudge3==$row2["jcode"]){
                            $djcnt++;
                        $tjud3= '<input type="checkbox"  id="hd_chk_jd3" onclick="getDone_upd_cat(this.id);" checked="true" value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'"/>&nbsp;<font color=yellow><b>'.str_replace("\\","",$row2["jname"]).'</b></font>';}
                        if($cljudge4==$row2["jcode"]){
                            $djcnt++;
                        $tjud4= '<input type="checkbox"  id="hd_chk_jd4" onclick="getDone_upd_cat(this.id);" checked="true" value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'"/>&nbsp;<font color=yellow><b>'.str_replace("\\","",$row2["jname"]).'</b></font>';}
                        if($cljudge5==$row2["jcode"]){
                            $djcnt++;
                            $tjud5= '<input type="checkbox"  id="hd_chk_jd5" onclick="getDone_upd_cat(this.id);" checked="true" value="'.$row2["jcode"].'||'.str_replace("\\","",$row2["jname"]).'"/>&nbsp;<font color=yellow><b>'.str_replace("\\","",$row2["jname"]).'</b></font>';}
                    }
                }     

                $html_bunch_cases .= '</select><br><br>
                    <input type="hidden" name="djcnt" id="djcnt" value="'.$djcnt.'"/>
                    <input type="button" name="addjudge" id="addjudge" value="Add" onclick="getSlide();"/>
                </td>
                <td rowspan="4" id="judgelist">
                <table id="tb_new" width="100%" style="text-align:left;">';

                if($tjud1!="") $html_bunch_cases .= "<tr id='hd_chk_jd_row1'><td>".$tjud1."</td></tr>";
                if($tjud2!="") $html_bunch_cases .= "<tr id='hd_chk_jd_row2'><td>".$tjud2."</td></tr>";
                if($tjud3!="") $html_bunch_cases .= "<tr id='hd_chk_jd_row3'><td>".$tjud3."</td></tr>";
                if($tjud4!="") $html_bunch_cases .= "<tr id='hd_chk_jd_row4'><td>".$tjud4."</td></tr>";
                if($tjud5!="") $html_bunch_cases .= "<tr id='hd_chk_jd_row5'><td>".$tjud5."</td></tr>";
                $html_bunch_cases .= '</table>
                </td>
                </tr>

                <tr>
                <td align="center"><b><font  size="+1">Disposal/Hearing Date : </font></b>&nbsp;<input class="dtp" type="text" name="hdate" id="hdate" value="'.$cldate.'" size="12" readonly="readonly"></td>
                </tr>
                <tr>
                <td align="center">';
                $html_bunch_cases .= '<input class="cls_chkd" name="chkd41" id="chkd41" value="41|Leave granted"  type="checkbox"><label class="lblclass" for="chkd41">Leave granted</label>';
                $html_bunch_cases .= '<br><input class="cls_chkd" name="chkd178" id="chkd178" value="178|Leave Granted &amp; Allowed"  type="checkbox"><label class="lblclass" for="chkd178">Leave Granted &amp; Allowed</label>';
                $html_bunch_cases .= '<br><input class="cls_chkd" name="chkd176" id="chkd176" value="176|Leave Granted &amp; Disposed off"  type="checkbox"><label class="lblclass" for="chkd176">Leave Granted &amp; Disposed off</label>';
                $html_bunch_cases .= '<br><input class="cls_chkd" name="chkd177" id="chkd177" value="177|Leave Granted &amp; Dismissed" type="checkbox"><label class="lblclass" for="chkd177">Leave Granted &amp; Dismissed</label>';
                $html_bunch_cases .= '</td>
                </tr>
                </table>';    
                
                
                
                $html_bunch_cases .= '<p align="center"><input type="button" name="disp" id="disp" value="Dispose selected Leave Granted Cases" onclick="save_rec();"/></p>';
            }
            //echo $connchks;
            return $html_bunch_cases ; 
        }
    }

    function get_main_details($dn,$fields){
        $data_array = array();    
        // $sql = mysql_query("Select * from main where diary_no='".$dn."'") or die('Error: ' . __LINE__ . mysql_error());
        $builder1 = $this->db->table('main');
        $builder1->select("diary_no,pet_name,res_name,c_status,fil_no_fh");   
        $builder1->where('diary_no', $dn);
        $query1 = $builder1->get();
        $result_p = $query1->getResultArray();
        if (count($result_p) > 0) {
            foreach ($result_p as $row) {
                // while($row = mysql_fetch_assoc($sql)){
                foreach($row as $key => $value) {
                    $data_array[$row['diary_no']][$key] = $value;
                }
            }
        }
        return $data_array;
    }

    function get_casedesc($data){

        // $ucode =  $_SESSION['login']['usercode'];

        $dataArr = [];

        if($data['d_no']!='' && $data['d_yr']!=''){
            // $sql = mysql_query("Select diary_no,conn_key,pet_name,res_name,fil_dt,reg_year_mh, reg_year_fh, YEAR(fil_dt) as filyr, fil_no,fil_no_fh, actcode, pet_adv_id, res_adv_id, lastorder, c_status, if(fil_no!='',SUBSTRING_INDEX(fil_no, '-', 1),'') as ct1, 
            //     if(fil_no!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1 ),'') as crf1, if(fil_no!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no, '-', 2), '-', -1),'') as crl1, if(fil_no_fh!='',SUBSTRING_INDEX(fil_no_fh, '-', 1),'') as ct2, 
            //     if(fil_no_fh!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1 ),'') as crf2, if(fil_no_fh!='',SUBSTRING_INDEX(SUBSTRING_INDEX(fil_no_fh, '-', 2), '-', -1),'') as crl2 from main where substr( diary_no, 1, length( diary_no ) -4 )='$data[d_no]' and substr( diary_no , -4 )='$data[d_yr]'") or die('Error: ' . __LINE__ . mysql_error());
            $builder =  $this->db->table('main');
            $builder->select([
                    'diary_no',
                    'conn_key',
                    'pet_name',
                    'res_name',
                    'fil_dt',
                    'reg_year_mh',
                    'reg_year_fh',
                    'EXTRACT(YEAR FROM fil_dt) AS filyr',
                    'fil_no',
                    'fil_no_fh',
                    'actcode',
                    'pet_adv_id',
                    'res_adv_id',
                    'lastorder',
                    'c_status',
                    "CASE WHEN fil_no != '' THEN SPLIT_PART(fil_no, '-', 1) ELSE '' END AS ct1",
                    "CASE WHEN fil_no != '' THEN SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', 1) ELSE '' END AS crf1",
                    "CASE WHEN fil_no != '' THEN SPLIT_PART(SPLIT_PART(fil_no, '-', 2), '-', 1) ELSE '' END AS crl1",
                    "CASE WHEN fil_no_fh != '' THEN SPLIT_PART(fil_no_fh, '-', 1) ELSE '' END AS ct2",
                    "CASE WHEN fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', 1) ELSE '' END AS crf2",
                    "CASE WHEN fil_no_fh != '' THEN SPLIT_PART(SPLIT_PART(fil_no_fh, '-', 2), '-', 1) ELSE '' END AS crl2",
            ]);
            $builder->where("LEFT(diary_no::text, -4)", $data['d_no']);
            $builder->where("RIGHT(diary_no::text, 4)", $data['d_yr']);
            $query = $builder->get();
            $result = $query->getResultArray();
            // echo "<pre>"; print_r($result); die;
            if (count($result) > 0) {
                $in_array_var = array();
                $diary_no = $result[0];     
                if($diary_no['diary_no']!=$diary_no['conn_key'] and $diary_no['conn_key']!=''){
                    $check_for_conn="N";
                }else{
                    $check_for_conn="Y";
                }
                $d_no_yr = $data['d_no'].$data['d_yr'];
                $t_ct = $check_for_final_hearing = $sn = "";
                $tab = $data['tab'];
                $opt= $data['opt'];
                $noerror = true;
                $diaryno =$diary_no['diary_no'];
                $sno=$opt*100;
                $sno++;
                
                $dataArr['get_bunch_cases'] = $this->get_bunch_cases($diaryno); 
                
            }else{ 
                // <!-- <p align=center><font color=red>Case Not Found</font></p> -->
                $dataArr['notfound'] = " <p align=center><font color=red>Case Not Found</font></p>";
             }
             return $dataArr;
        }
    
    }

    
}

    


