<?php

namespace App\Controllers\Reports\Filing;
use App\Controllers\BaseController;
use App\Models\Reports\Filing\HighcourtModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Filing\Model_diary;
use App\Models\Entities\fil_trap_a;

class Highcourt extends BaseController
{
    public $Dropdown_list_model;
    public $Model_diary;
    function __construct()
    {
        //ini_set('memory_limit','750M'); // This also needs to be increased in some cases. )
        ini_set('memory_limit', '-1');            
        $HighcourtModel = new HighcourtModel();
    }
	
	
	public function caveat_search()
	{
		$data= array();
		$data['$_REQUEST'] = $_REQUEST;
		return view('Reports/highcourt/caveat_search',$data);
	} 

    public function include_caveat()
    {
        $ddl_court='';
        $txt_order_date='';
        $ddl_bench='';
        $ddl_st_agncy='';
        $ddl_ref_case_type='';
        $txt_ref_caseno='';
        $ddl_ref_caseyr='';
        
        $ddl_court_t='';
        $txt_order_date_t='';
        $ddl_bench_t='';
        $ddl_st_agncy_t='';
        $ddl_ref_case_type_t='';
        $txt_ref_caseno_t='';
        $ddl_ref_caseyr_t='';

        if($_REQUEST['u_t']=='1')
        {
      
            
            if($_REQUEST['ddl_court']!='')
            {
                $ddl_court=" ct_code = '$_REQUEST[ddl_court]'";
                $ddl_court_t="  a.ct_code = b.ct_code";
            }
            if($_REQUEST['txt_order_date']!='')
            {
                $_REQUEST['txt_order_date']=date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
                $txt_order_date=" and lct_dec_dt = '$_REQUEST[txt_order_date]'";
                $txt_order_date_t=" AND a.lct_dec_dt = b.lct_dec_dt";
            }
            if($_REQUEST['ddl_bench']!='')
            {
                $ddl_bench=" and  l_dist = '$_REQUEST[ddl_bench]'";
                $ddl_bench_t=" AND a.l_dist = b.l_dist";
            }
            if($_REQUEST['ddl_st_agncy']!='')
            {
                $ddl_st_agncy=" and  l_state = '$_REQUEST[ddl_st_agncy]'";
                $ddl_st_agncy_t=" AND a.l_state = b.l_state";
            }
            if($_REQUEST['ddl_ref_case_type']!='')
            {
                $ddl_ref_case_type=" and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
                $ddl_ref_case_type_t=" and a.lct_casetype=b.lct_casetype";
            }
            if($_REQUEST['txt_ref_caseno']!='')
            {
                $txt_ref_caseno=" and  trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
        //        $txt_ref_caseno_t=" and a.lct_caseno=b.lct_caseno";
                $txt_ref_caseno_t=" and trim(leading '0' from a.lct_caseno)=trim(leading '0' from b.lct_caseno)";
            }
                if($_REQUEST['ddl_ref_caseyr']!='')
                {
                $ddl_ref_caseyr=" and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
                $ddl_ref_caseyr_t=" and a.lct_caseyear=b.lct_caseyear";
                }
                $fst=intval($_REQUEST['nw_hd_fst']);
            $inc_val=intval($_REQUEST['inc_val']);
        }
        $sql = "SELECT 
        a.caveat_no, 
        b.lct_dec_dt, 
        b.l_dist, 
        b.l_state, 
        b.lct_casetype, 
        b.lct_caseno, 
        b.lct_caseyear, 
        b.ct_code, 
        name,
    
        CASE 
            WHEN b.ct_code = 3 THEN (
                SELECT name 
                FROM master.state s 
                WHERE s.id_no = b.l_dist 
                  AND display = 'Y'
            )
            ELSE (
                SELECT agency_name 
                FROM master.ref_agency_code c 
                WHERE c.cmis_state_id = b.l_state 
                  AND c.id = b.l_dist 
                  AND is_deleted = 'f'
            )
        END AS agency_name,
    
        CASE 
            WHEN b.ct_code = 4 THEN (
                SELECT skey 
                FROM master.casetype ct 
                WHERE ct.display = 'Y' 
                  AND ct.casecode = b.lct_casetype
            )
            ELSE (
                SELECT type_sname 
                FROM master.lc_hc_casetype d 
                WHERE d.lccasecode = b.lct_casetype 
                  AND d.display = 'Y'
            )
        END AS type_sname,
    
        SUBSTRING(m.fil_no FROM 4) AS fil_no, 
        EXTRACT(YEAR FROM m.fil_dt) AS fil_dt, 
        short_description, 
        court_name, 
        m.pet_name, 
        m.res_name, 
        b.diary_no,
        link_dt,
        active_fil_no,
        active_fil_dt
    
    FROM (
        SELECT DISTINCT 
            caveat_no, 
            b.lct_dec_dt, 
            b.l_dist, 
            b.l_state, 
            b.lct_casetype, 
            b.lct_caseno, 
            b.lct_caseyear, 
            b.caveat_no AS c_diary, 
            b.ct_code
        FROM caveat_lowerct b
         WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
          AND  b.lct_dec_dt IS NOT NULL
          AND  b.lw_display = 'Y'
    ) a
    JOIN lowerct b ON $ddl_court_t $txt_order_date_t $ddl_bench_t $ddl_st_agncy_t $ddl_ref_case_type_t $txt_ref_caseno_t $ddl_ref_caseyr_t
        AND b.lw_display = 'Y'
        AND b.is_order_challenged = 'Y'
    LEFT JOIN master.state c ON b.l_state = c.id_no AND c.display = 'Y'
    LEFT JOIN caveat d ON d.caveat_no = a.caveat_no
    LEFT JOIN master.m_from_court f ON f.id = a.ct_code AND f.display = 'Y'
    JOIN main m ON m.diary_no = b.diary_no
    LEFT JOIN caveat_diary_matching cdm ON cdm.diary_no = b.diary_no 
        AND cdm.display = 'Y' 
        AND cdm.caveat_no = a.caveat_no 
    LEFT JOIN master.casetype e 
        ON e.casecode = CAST(NULLIF(SUBSTRING(m.fil_no FROM 1 FOR 2), '') AS BIGINT) 
        AND e.display = 'Y'
    
    ORDER BY diary_no 
    LIMIT $inc_val OFFSET $fst;
    ";                  
$query = $this->db->query($sql);

$result = $query->getResultArray();

if (!empty($result)) 
        {
        
            if($_REQUEST['u_t']==0)
                                $s_no=1;
                                else if($_REQUEST['u_t']==1)
                                $s_no=$_REQUEST['inc_tot_pg'];
            ?>
        <table width="100%" class="table custom-table table_tr_th_w_clr c_vertical_align">
            <thead>
                <tr>
                <th>
                    S.No.
                </th>
                
            
                <th>
                    Diary No.
                </th>
                <th>
                    Registration No.
                </th>
                <th>
                    Petitioner<br/>Vs<br/>Respondent
                </th>
                <th>
                    From Court
                </th>
                <th>
                    State
                </th>
                <th>
                    Bench
                </th>
                <th>
                    Case No.
                </th>
                <th>
                Judgement Date
                </th>
                <th>
                Caveat
                </th>
            </tr>
            </thead>
        <?php
        
            foreach ($result as $row)
            {
                ?>
            <tr>
        <td>
                    <?php echo  $s_no; ?>
                </td>
                <td>
                    <?php echo substr($row['diary_no'],0,-4).'-'.  substr($row['diary_no'],-4); ?>
                </td>
                <td>
                <?php  
                $active_fil_no='';
                $active_fil_dt='';
                if($row['active_fil_no']!='')
                    $active_fil_no= '-'.intval(substr($row['active_fil_no'],3));
                if($row['active_fil_dt']!='')
                    $active_fil_dt= (!empty($row['active_fil_dt'])) ?  '/'.date('Y',strtotime($row['active_fil_dt'])) : '/';

                echo $row['short_description'].$active_fil_no.$active_fil_dt;?>
                </td>
                <td>
                    <?php 
                
                echo $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];
                    ?>
                </td>
                <td>
                    <?php echo $row['court_name']; ?>
                </td>
                <td>
                <?php 
                    echo $row['name'];
                    ?>
                </td>
                <td>
                <?php 
                    echo $row['agency_name'];
                    ?>
                </td>
                <td>
                    <?php 
                    echo  $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
                    ?>
                </td>
                <td>
                    <?php echo (!empty($row['lct_dec_dt'])) ?  date('d-m-Y',strtotime($row['lct_dec_dt'])) : ''; ?>
                </td>
                <td>
                
                    <?php echo  substr($row['caveat_no'],0,-4).'-'.  substr($row['caveat_no'],-4); ?>
                
                </td>
        <!--        <td>
            <?php echo $row['link_dt']; ?>
                </td>-->
                </tr>
            <?php
            $s_no++;
            }
            ?>
            </table>

            <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />    

        <?php
        }
        else 
        {
            ?>
        <div class="cl_center"><b>No Record Found.</b></div>
        <?php
        }
    }




	
	public function get_bench()
	{
		 if($_REQUEST['ddl_court']==3)
			{

				if( $_REQUEST['ddl_st_agncy']=='490506')
					$sql="SELECT id_no id, court_name as agency_name,Name districtname 
					FROM state s join delhi_district_court d on s.state_code=d.state_code and s.district_code=d.district_code WHERE s.State_code = (SELECT State_code FROM state WHERE 
						id_no =  '$_REQUEST[ddl_st_agncy]' AND display = 'Y' ) AND s.display = 'Y' AND s.Sub_Dist_code =0 AND s.Village_code =0 AND s.District_code !=0 order by trim(Name)";
				else
					$sql="SELECT id_no id, Name agency_name FROM master.state WHERE State_code = (SELECT State_code FROM master.state WHERE 
				id_no =  '$_REQUEST[ddl_st_agncy]' AND display = 'Y' ) AND display = 'Y'
				  AND Sub_Dist_code =0 AND Village_code =0 AND District_code !=0 order by trim(Name)";
			}
			if($_REQUEST['ddl_court']=='1')
			{
				//$_REQUEST[ddl_court]=2;
				$sql="SELECT id, agency_name, short_agency_name FROM master.ref_agency_code WHERE is_deleted = 'f' AND agency_or_court  IN ('1') AND 
			cmis_state_id = '$_REQUEST[ddl_st_agncy]'  order by trim(agency_name)";
			}
			if($_REQUEST['ddl_court']=='4')
			{
				//$_REQUEST[ddl_court]=2;
				$sql="SELECT id, agency_name, short_agency_name FROM master.ref_agency_code WHERE is_deleted = 'f' AND agency_or_court ='$_REQUEST[ddl_court]' AND 
			cmis_state_id = '$_REQUEST[ddl_st_agncy]'  order by trim(agency_name)";
			}
			if($_REQUEST['ddl_court']=='5')
			{
				//$_REQUEST[ddl_court]=2;
				$sql="SELECT id, agency_name, short_agency_name FROM master.ref_agency_code WHERE is_deleted = 'f' AND agency_or_court  IN ('2,5,6') AND 
			cmis_state_id = '$_REQUEST[ddl_st_agncy]'  order by trim(agency_name)";
			}
		$query = $this->db->query($sql);
		 
		$result = $query->getResultArray();
		 
		$option = '<option value="">Select</option>';
		 
		if($_REQUEST['ddl_court']==3)
		{ if( $_REQUEST['ddl_st_agncy']=='490506') {
			if(!empty($result))
			{
				foreach ($result as $row ) {			 
					$option .= '<option value="'.$row['id'].'">'.$row['agency_name'] . '(' . $row['districtname'] . ')'.'</option>';
				} 
			}
		}
		else
			if(!empty($result))
			{
				foreach ($result as $row) {			 
					$option .= '<option value="'.$row['id'].'">'.$row['agency_name'].'</option>';
				}
			}
		}
		else
		{
			if(!empty($result))
			{
				foreach ($result as $row) 
				{ 
				$option .= '<option value="'.$row['id'].'">'.$row['agency_name'].'::'.$row['short_agency_name'].'</option>';
				}
			}
		}
		return $option;
}



public function get_lc_casetype()
{
		$corttyp=$_REQUEST['corttyp'];
		$ddl_st_agncy=$_REQUEST['ddl_st_agncy'];

	 
		if($_REQUEST['cl_hc_dc']==5)
		{
		 
			 $sqlc="SELECT lccasecode,type_sname lccasename FROM master.lc_hc_casetype WHERE display = 'Y' AND cmis_state_id = '$ddl_st_agncy'
								 AND ref_agency_code_id !=0  and  type_sname!='' order by type_sname";
		}
		else if($_REQUEST['cl_hc_dc']==4)
		{
			$sqlc="SELECT  casecode lccasecode,skey lccasename,casename FROM master.casetype WHERE display = 'Y'  order by skey";

		}
		else 
		{
		 
		 $sqlc="SELECT lccasecode, type_sname lccasename, lccasename casename FROM master.lc_hc_casetype WHERE display = 'Y' AND cmis_state_id = '$ddl_st_agncy'
								   AND corttyp = '$corttyp' and  type_sname!='' order by lccasename";
		}
		$option =  "<option value=''>Select</option>";
		
		$query = $this->db->query($sqlc);
	 
		$result = $query->getResultArray();
		if(!empty($result))
		{				
			foreach($result as $rowc)   {
				$option .= '<option value="'.$rowc['lccasecode'].'" title="'.$rowc['casename'].'">'.$rowc['lccasename'].'</option>';
			}
		}
	if($_REQUEST['cl_hc_dc']==4){
		$option .= "<option value='50' title='WRIT NOTIFICATION NO.'>".'WNN'."</option>";
		$option .= "<option value='51' title='ARBITRATION REFERENCE NO.'>".'ARN'."</option>";

	}
	return $option;
	die;
}


public function get_caveat_search()
{
	$ddl_court='';
    $txt_order_date='';
    $ddl_bench='';
    $ddl_st_agncy='';
    $ddl_ref_case_type='';
    $txt_ref_caseno='';
    $ddl_ref_caseyr='';
    
    $ddl_court_t='';
    $txt_order_date_t='';
    $ddl_bench_t='';
    $ddl_st_agncy_t='';
    $ddl_ref_case_type_t='';
    $txt_ref_caseno_t='';
    $ddl_ref_caseyr_t='';
    
   

    
    if($_REQUEST['ddl_court']!='')
    {
        $ddl_court=" ct_code = '$_REQUEST[ddl_court]'";
        $ddl_court_t="  a.ct_code = b.ct_code";
    }
    if($_REQUEST['txt_order_date']!='')
    {
      $_REQUEST['txt_order_date']=date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
         $txt_order_date=" and lct_dec_dt = '$_REQUEST[txt_order_date]'";
        $txt_order_date_t=" AND a.lct_dec_dt = b.lct_dec_dt";
    }
    if($_REQUEST['ddl_bench']!='')
    {
        $ddl_bench=" and  l_dist = '$_REQUEST[ddl_bench]'";
        $ddl_bench_t=" AND a.l_dist = b.l_dist";
    }
    if($_REQUEST['ddl_st_agncy']!='')
    {
        $ddl_st_agncy=" and  l_state = '$_REQUEST[ddl_st_agncy]'";
        $ddl_st_agncy_t=" AND a.l_state = b.l_state";
    }
     if($_REQUEST['ddl_ref_case_type']!='')
     {
        $ddl_ref_case_type=" and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
        $ddl_ref_case_type_t=" and a.lct_casetype=b.lct_casetype";
     }
       if($_REQUEST['txt_ref_caseno']!='')
       {
        $txt_ref_caseno=" and  trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
//        $txt_ref_caseno_t=" and a.lct_caseno=b.lct_caseno";
        $txt_ref_caseno_t=" and trim(leading '0' from a.lct_caseno)=trim(leading '0' from b.lct_caseno)";
       }
        if($_REQUEST['ddl_ref_caseyr']!='')
        {
        $ddl_ref_caseyr=" and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
        $ddl_ref_caseyr_t=" and a.lct_caseyear=b.lct_caseyear";
        }
    
       $cnt_rec="select count(b.lower_court_id ) from (

		SELECT DISTINCT caveat_no, b.lct_dec_dt, b.l_dist, b.l_state, b.lct_casetype, b.lct_caseno, b.lct_caseyear, b.caveat_no c_diary, b.ct_code
		FROM caveat_lowerct b
		WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
		AND b.lct_dec_dt IS NOT NULL
		AND b.lw_display = 'Y'
		)a
		JOIN lowerct b ON $ddl_court_t $txt_order_date_t $ddl_bench_t $ddl_st_agncy_t $ddl_ref_case_type_t $txt_ref_caseno_t $ddl_ref_caseyr_t
		AND b.lw_display = 'Y'
		AND b.is_order_challenged = 'Y'";
		
		$query = $this->db->query($cnt_rec);
	 
		$result = $query->getRowArray();
		 
		$data['$_REQUEST'] = $_REQUEST;
		$data['res_sq'] = $result['count'];
		return view('Reports/highcourt/get_caveat_search',$data);
}



public function caveat_to_caveat()
{
	$data= array();
	$data['$_REQUEST'] = $_REQUEST;
	return view('Reports/highcourt/caveat_to_caveat',$data);
}


public function get_caveat_to_caveat()
{
	$ddl_court='';
    $txt_order_date='';
    $ddl_bench='';
    $ddl_st_agncy='';
    $ddl_ref_case_type='';
    $txt_ref_caseno='';
    $ddl_ref_caseyr='';
    
    $ddl_court_t='';
    $txt_order_date_t='';
    $ddl_bench_t='';
    $ddl_st_agncy_t='';
    $ddl_ref_case_type_t='';
    $txt_ref_caseno_t='';
    $ddl_ref_caseyr_t='';
    
   

    
    if($_REQUEST['ddl_court']!='')
    {
        $ddl_court=" ct_code = '$_REQUEST[ddl_court]'";
        $ddl_court_t="  a.ct_code = b.ct_code";
    }
    if($_REQUEST['txt_order_date']!='')
    {
      $_REQUEST['txt_order_date']=date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
         $txt_order_date=" and lct_dec_dt = '$_REQUEST[txt_order_date]'";
        $txt_order_date_t=" AND a.lct_dec_dt = b.lct_dec_dt";
    }
    if($_REQUEST['ddl_bench']!='')
    {
        $ddl_bench=" and  l_dist = '$_REQUEST[ddl_bench]'";
        $ddl_bench_t=" AND a.l_dist = b.l_dist";
    }
    if($_REQUEST['ddl_st_agncy']!='')
    {
        $ddl_st_agncy=" and  l_state = '$_REQUEST[ddl_st_agncy]'";
        $ddl_st_agncy_t=" AND a.l_state = b.l_state";
    }
     if($_REQUEST['ddl_ref_case_type']!='')
     {
        $ddl_ref_case_type=" and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
        $ddl_ref_case_type_t=" and a.lct_casetype=b.lct_casetype";
     }
       if($_REQUEST['txt_ref_caseno']!='')
       {
        $txt_ref_caseno=" and   trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
        $txt_ref_caseno_t=" and  trim(leading '0' from a.lct_caseno)= trim(leading '0' from b.lct_caseno)";
       }
        if($_REQUEST['ddl_ref_caseyr']!='')
        {
        $ddl_ref_caseyr=" and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
        $ddl_ref_caseyr_t=" and a.lct_caseyear=b.lct_caseyear";
        }
		
		$cnt_rec="SELECT count(b.lower_court_id ) 
                    FROM caveat_lowerct b
                    WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
                    AND b.lct_dec_dt IS NOT NULL
                    AND b.lw_display = 'Y'";
		
        // pr($cnt_rec);
	$query = $this->db->query($cnt_rec);
	 
		$result = $query->getRowArray();
		 
		$data['$_REQUEST'] = $_REQUEST;
		$data['res_sq'] = $result['count'];
		return view('Reports/highcourt/get_caveat_to_caveat',$data);
}


public function include_caveat_caveat()
{
     
    $ddl_court='';
        $txt_order_date='';
        $ddl_bench='';
        $ddl_st_agncy='';
        $ddl_ref_case_type='';
        $txt_ref_caseno='';
        $ddl_ref_caseyr='';
        
        $ddl_court_t='';
        $txt_order_date_t='';
        $ddl_bench_t='';
        $ddl_st_agncy_t='';
        $ddl_ref_case_type_t='';
        $txt_ref_caseno_t='';
        $ddl_ref_caseyr_t='';
        
    $cur_date=date('Y-m-d');

    if($_REQUEST['u_t']=='1')
    {
     
        
        if($_REQUEST['ddl_court']!='')
        {
            $ddl_court=" ct_code = '$_REQUEST[ddl_court]'";
            $ddl_court_t="  a.ct_code = b.ct_code";
        }
        if($_REQUEST['txt_order_date']!='')
        {
            $_REQUEST['txt_order_date']=date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
            $txt_order_date="   lct_dec_dt = '$_REQUEST[txt_order_date]'";
            $txt_order_date_t="   a.lct_dec_dt = b.lct_dec_dt";
        }
        if($_REQUEST['ddl_bench']!='')
        {
            $ddl_bench="    l_dist = '$_REQUEST[ddl_bench]'";
            $ddl_bench_t="   a.l_dist = b.l_dist";
        }
        if($_REQUEST['ddl_st_agncy']!='')
        {
            $ddl_st_agncy="    l_state = '$_REQUEST[ddl_st_agncy]'";
            $ddl_st_agncy_t="   a.l_state = b.l_state";
        }
        if($_REQUEST['ddl_ref_case_type']!='')
        {
            $ddl_ref_case_type="    lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
            $ddl_ref_case_type_t="   a.lct_casetype=b.lct_casetype";
        }
        if($_REQUEST['txt_ref_caseno']!='')
        {
            $txt_ref_caseno="    lct_caseno = '$_REQUEST[txt_ref_caseno]'";
            $txt_ref_caseno_t="   a.lct_caseno=b.lct_caseno";
        }
            if($_REQUEST['ddl_ref_caseyr']!='')
            {
            $ddl_ref_caseyr="    lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
            $ddl_ref_caseyr_t="   a.lct_caseyear=b.lct_caseyear";
            }
            $fst=intval($_REQUEST['nw_hd_fst']);
        $inc_val=intval($_REQUEST['inc_val']);
    }
    
           
            $HighcourtModel = new HighcourtModel();
            $sql_result =  $HighcourtModel->getCaveatDetails($ddl_court, $txt_order_date, $ddl_bench, $ddl_st_agncy, $ddl_ref_case_type, $txt_ref_caseno, $ddl_ref_caseyr, $fst, $inc_val);
            
            if(!empty($sql_result))
            {
            
                if($_REQUEST['u_t']==0)
                    $s_no=1;
                else if($_REQUEST['u_t']==1)
                    $s_no=$_REQUEST['inc_tot_pg'];
                ?>
            <table width="100%" class="table custom-table table_tr_th_w_clr c_vertical_align">
                <thead>
                <tr>
                    <th>
                        S.No.
                    </th>
                    <th>
                        Caveat No. /<br/>Receiving Date
                    </th>
                    
                    <th>
                        Petitioner<br/>Vs<br/>Respondent
                    </th>
                    <th>
                        Advocate
                    </th>
                    <th>
                        From Court
                    </th>
                    <th>
                        State
                    </th>
                    <th>
                        Bench
                    </th>
                    <th>
                        Case No.
                    </th>
                        <th>
                    Judgement Date
                    </th>
                    <th>
                    Status
                    </th>
                </tr>
            </thead>
            <?php             
                foreach ($sql_result as $row)
                {
                    ?>
                <tr>
                <td>
                    <?php echo  $s_no; ?>
                    </td>
                    <td>
                        <?php echo substr($row['caveat_no'],0,-4).'-'.  substr($row['caveat_no'],-4); ?>
                        <span style="color: red"><?php echo $caveat_date= date('d-m-Y',strtotime($row['diary_no_rec_date']));?></span>
                    </td>
            
                    <td>
                        <?php 
                    
                    echo $row['pet_name'].'<br/>Vs<br/>'.$row['res_name'];
                        ?>
                    </td>
                    <td>
                        <?php
                       
                        $caveat_adv = $HighcourtModel->getCaveatAdvocate($row['caveat_no']);
                        if(!empty($caveat_adv))
                        {
                            $tot_advocate='';
                            foreach($caveat_adv as $row1) {
                                if($tot_advocate=='')
                                    $tot_advocate=$row1['aor_code'].'- '.$row1['name'];
                                else 
                                    $tot_advocate=$tot_advocate.', '.$row1['aor_code'].'- '.$row1['name']; 
                            }
                            echo $tot_advocate;
                        }
                        else 
                        {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $row['court_name']; ?>
                    </td>
                    <td>
                        <?php 
                        echo $row['name'];
                        ?>
                    </td>
                    <td>
                    <?php 
                        echo $row['agency_name'];
                        ?>
                    </td>
                    <td>
                        <?php 
                        echo  $row['type_sname'].'-'.$row['lct_caseno'].'-'.$row['lct_caseyear'];
                        ?>
                    </td>
                    <td>
                        <?php echo date('d-m-Y',strtotime($row['lct_dec_dt'])); ?>
                    </td>
                    <td>
                        <?php
                        $date1=date_create($caveat_date);
                        $date2=date_create($cur_date);
                        $diff=date_diff($date1,$date2);
                        $date_diff= $diff->format("%R%a days");
                        $rep_date_diff= intval(str_replace('+','', $date_diff));
                        if($rep_date_diff<=90)
                        {
                            ?>
                                    <span style="color: green">Active</span>
                                    <?php
                        }
                        else 
                        {
                            ?>
                                    <span style="color: red">Expired</span>
                                    <?php
                        }
                        ?>
                        
                    </td>
             
                    </tr>
                <?php
                $s_no++;
                }
                ?>
                </table>
            
                <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />    
            
            <?php
            }
            else 
            {
                ?>
                <div class="cl_center"><b>No Record Found.</b></div>
            <?php
            }
}

public function diary_diary()
{
	$data= array();
	$data['$_REQUEST'] = $_REQUEST;
	return view('Reports/highcourt/diary_diary',$data);
}

public function include_diary_diary()
{
    $HighcourtModel = new HighcourtModel();
        if ($_REQUEST['u_t'] == '1') {

            $ddl_court = '';
            $txt_order_date = '';
            $ddl_bench = '';
            $ddl_st_agncy = '';
            $ddl_ref_case_type = '';
            $txt_ref_caseno = '';
            $ddl_ref_caseyr = '';
        
            $ddl_court_t = '';
            $txt_order_date_t = '';
            $ddl_bench_t = '';
            $ddl_st_agncy_t = '';
            $ddl_ref_case_type_t = '';
            $txt_ref_caseno_t = '';
            $ddl_ref_caseyr_t = '';
        
        
        
        
            if ($_REQUEST['ddl_court'] != '') {
            $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
            $ddl_court_t = "  a.ct_code = b.ct_code";
            }
            if ($_REQUEST['txt_order_date'] != '') {
            $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
            $txt_order_date = " and lct_dec_dt = '$_REQUEST[txt_order_date]'";
            $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
            }
            if ($_REQUEST['ddl_bench'] != '') {
            $ddl_bench = " and  l_dist = '$_REQUEST[ddl_bench]'";
            $ddl_bench_t = " AND a.l_dist = b.l_dist";
            }
            if ($_REQUEST['ddl_st_agncy'] != '') {
            $ddl_st_agncy = " and  l_state = '$_REQUEST[ddl_st_agncy]'";
            $ddl_st_agncy_t = " AND a.l_state = b.l_state";
            }
            if ($_REQUEST['ddl_ref_case_type'] != '') {
            $ddl_ref_case_type = " and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
            $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
            }
            if ($_REQUEST['txt_ref_caseno'] != '') {
            $txt_ref_caseno = " and  lct_caseno = '$_REQUEST[txt_ref_caseno]'";
            $txt_ref_caseno_t = " and a.lct_caseno=b.lct_caseno";
            }
            if ($_REQUEST['ddl_ref_caseyr'] != '') {
            $ddl_ref_caseyr = " and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
            $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
            }
            $fst = intval($_REQUEST['nw_hd_fst']);
            $inc_val = intval($_REQUEST['inc_val']);
        } else {
            $ddl_court = '';
            $txt_order_date = '';
            $ddl_bench = '';
            $ddl_st_agncy = '';
            $ddl_ref_case_type = '';
            $txt_ref_caseno = '';
            $ddl_ref_caseyr = '';
        
            $ddl_court_t = '';
            $txt_order_date_t = '';
            $ddl_bench_t = '';
            $ddl_st_agncy_t = '';
            $ddl_ref_case_type_t = '';
            $txt_ref_caseno_t = '';
            $ddl_ref_caseyr_t = '';
        
        
            if ($_REQUEST['ddl_court'] != '') {
            $ddl_court = " ct_code = '$_REQUEST[ddl_court]'";
            $ddl_court_t = "  a.ct_code = b.ct_code";
            }
            if ($_REQUEST['txt_order_date'] != '') {
            $_REQUEST['txt_order_date'] = date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
            $txt_order_date = " AND lct_dec_dt = '$_REQUEST[txt_order_date]'";
            $txt_order_date_t = " AND a.lct_dec_dt = b.lct_dec_dt";
            }
            if ($_REQUEST['ddl_bench'] != '') {
            $ddl_bench = "  AND l_dist = '$_REQUEST[ddl_bench]'";
            $ddl_bench_t = " AND a.l_dist = b.l_dist";
            }
            if ($_REQUEST['ddl_st_agncy'] != '') {
            $ddl_st_agncy = "  AND l_state = '$_REQUEST[ddl_st_agncy]'";
            $ddl_st_agncy_t = " AND a.l_state = b.l_state";
            }
            if ($_REQUEST['ddl_ref_case_type'] != '') {
            $ddl_ref_case_type = "  AND lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
            $ddl_ref_case_type_t = " and a.lct_casetype=b.lct_casetype";
            }
            if ($_REQUEST['txt_ref_caseno'] != '') {
            $txt_ref_caseno = "  AND  trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
            $txt_ref_caseno_t = " and  trim(leading '0' from a.lct_caseno)= trim(leading '0' from b.lct_caseno)";
            }
            if ($_REQUEST['ddl_ref_caseyr'] != '') {
            $ddl_ref_caseyr = " AND  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
            $ddl_ref_caseyr_t = " and a.lct_caseyear=b.lct_caseyear";
            }
        }
        
        
        
        
        $filters = [
            'ddl_court' => $ddl_court,
            'txt_order_date' => $txt_order_date,
            'ddl_bench' =>  $ddl_bench,
            'ddl_st_agncy' => $ddl_st_agncy,
            'ddl_ref_case_type' => $ddl_ref_case_type,
            'txt_ref_caseno' => $txt_ref_caseno,
            'ddl_ref_caseyr' => $ddl_ref_caseyr,
            'inc_val' => $inc_val, // Number of records per page
            'fst' => $fst // Offset for pagination
        ];
        
        $result = $HighcourtModel->getCaseData($filters);
        
        if (!empty($result)) {
        
            if ($_REQUEST['u_t'] == 0)
            $s_no = 1;
            else if ($_REQUEST['u_t'] == 1)
            $s_no = $_REQUEST['inc_tot_pg'];
        ?>
        
            <div class="table-responsive">
            <table id="customers" class="table table-striped custom-table">
                <thead>
                <tr>
                    <th>
                    S.No.
                    </th>
                    <th>
                    Diary No.
                    </th>
                    <th>
                    Petitioner<br />Vs<br />Respondent
                    </th>
                    <th>
                    From Court
                    </th>
                    <th>
                    State
                    </th>
                    <th>
                    Bench
                    </th>
                    <th>
                    Case No.
                    </th>
                    <th>
                    Judgement Date
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($result as $row) {
                ?>
                    <tr>
                    <td>
                        <?php echo  $s_no; ?>
                    </td>
                    <td>
                        <?php echo  substr($row['diary_no'], 0, -4) . '-' .  substr($row['diary_no'], -4); ?>
                    </td>
        
                    <td>
                        <?php
        
                        echo $row['pet_name'] . '<br/>Vs<br/>' . $row['res_name'];
                        ?>
                    </td>
                    <td>
                        <?php echo $row['court_name']; ?>
                    </td>
                    <td>
                        <?php
                        echo $row['name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $row['agency_name'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo  $row['type_sname'] . '-' . $row['lct_caseno'] . '-' . $row['lct_caseyear'];
                        ?>
                    </td>
                    <td>
                        <?php echo date('d-m-Y', strtotime($row['lct_dec_dt'])); ?>
                    </td>
        
                    </tr>
                <?php
                    $s_no++;
                }
                ?>
                </tbody>
            </table>
            <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $s_no; ?>" />
            <?php
        } else {
            ?>
            <div class="cl_center"><b>No Record Found.</b></div>
            <?php
        }
}



public function get_diary_diary()
{
	$ddl_court='';
    $txt_order_date='';
    $ddl_bench='';
    $ddl_st_agncy='';
    $ddl_ref_case_type='';
    $txt_ref_caseno='';
    $ddl_ref_caseyr='';
    
    $ddl_court_t='';
    $txt_order_date_t='';
    $ddl_bench_t='';
    $ddl_st_agncy_t='';
    $ddl_ref_case_type_t='';
    $txt_ref_caseno_t='';
    $ddl_ref_caseyr_t='';
   
    
    if($_REQUEST['ddl_court']!='')
    {
        $ddl_court=" ct_code = '$_REQUEST[ddl_court]'";
        $ddl_court_t="  a.ct_code = b.ct_code";
    }
    if($_REQUEST['txt_order_date']!='')
    {
      $_REQUEST['txt_order_date']=date('Y-m-d',  strtotime($_REQUEST['txt_order_date']));
         $txt_order_date=" and lct_dec_dt = '$_REQUEST[txt_order_date]'";
        $txt_order_date_t=" AND a.lct_dec_dt = b.lct_dec_dt";
    }
    if($_REQUEST['ddl_bench']!='')
    {
        $ddl_bench=" and  l_dist = '$_REQUEST[ddl_bench]'";
        $ddl_bench_t=" AND a.l_dist = b.l_dist";
    }
    if($_REQUEST['ddl_st_agncy']!='')
    {
        $ddl_st_agncy=" and  l_state = '$_REQUEST[ddl_st_agncy]'";
        $ddl_st_agncy_t=" AND a.l_state = b.l_state";
    }
     if($_REQUEST['ddl_ref_case_type']!='')
     {
        $ddl_ref_case_type=" and  lct_casetype = '$_REQUEST[ddl_ref_case_type]'";
        $ddl_ref_case_type_t=" and a.lct_casetype=b.lct_casetype";
     }
       if($_REQUEST['txt_ref_caseno']!='')
       {
        $txt_ref_caseno=" and   trim(leading '0' from lct_caseno) = '$_REQUEST[txt_ref_caseno]'";
        $txt_ref_caseno_t=" and  trim(leading '0' from a.lct_caseno)= trim(leading '0' from b.lct_caseno)";
       }
        if($_REQUEST['ddl_ref_caseyr']!='')
        {
        $ddl_ref_caseyr=" and  lct_caseyear = '$_REQUEST[ddl_ref_caseyr]'";
        $ddl_ref_caseyr_t=" and a.lct_caseyear=b.lct_caseyear";
        }
		
		 $cnt_rec="SELECT count(b.lower_court_id ) 
                    FROM lowerct b
                    WHERE $ddl_court $txt_order_date $ddl_bench $ddl_st_agncy $ddl_ref_case_type $txt_ref_caseno $ddl_ref_caseyr
                    AND b.lct_dec_dt IS NOT NULL
                    AND b.lw_display = 'Y'";
		
		
		$query = $this->db->query($cnt_rec);
	 
		$result = $query->getRowArray();
		 
		$data['$_REQUEST'] = $_REQUEST;
		$data['res_sq'] =   $result['count'];
        $data['HighcourtModel'] = new HighcourtModel();
		return view('Reports/highcourt/get_diary_diary',$data);
}
	
	
	
	
	
	
	
	
	
	
	
	
}
