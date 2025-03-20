<?php

$tb_name='';
$caveat_no='';
$ddl_status='';
$ddl_year='';
$parties='';

if($_REQUEST['ddl_diary_caveat']=='D')
{
	 $tb_name=" party";
}
else if($_REQUEST['ddl_diary_caveat']=='C')
{ 
	$tb_name=" caveat_party";
	$caveat_no=" caveat_no";        
}


if($_REQUEST['u_t']=='1')
{
 $txt_name = strtolower($_REQUEST['txt_name']);
 $ddl_party_type=$_REQUEST['ddl_party_type'];
    $parties='';
    if($ddl_party_type=='')
    {
//         $parties=" (pet_name like '%$_REQUEST[txt_name]%' || res_name like '%$_REQUEST[txt_name]%')";
        $parties=" AND (LOWER(partyname) like '%$txt_name%')";
    }
    else  if($ddl_party_type=='P')
    {
//        $parties=" pet_name like '%$_REQUEST[txt_name]%'";
        $parties=" AND (LOWER(partyname) like '%$txt_name%') and pet_res='P'";
    }
     else  if($ddl_party_type=='R')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
           $parties=" AND (LOWER(partyname) like '%$txt_name%') and pet_res='R'";
    }
      else  if($ddl_party_type=='I')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
          $parties=" AND (LOWER(partyname) like '%$txt_name%') and pet_res='I'";
    }
      else  if($ddl_party_type=='N')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
          $parties=" AND (LOWER(partyname) like '%$txt_name%') and pet_res='N'";
    }
//    
    $tb_name='';
    $caveat_no='';
      $ddl_status='';
//      echo $_REQUEST[ddl_diary_caveat].'88888888888888';
    if($_REQUEST['ddl_diary_caveat']=='D')
    {
//        $tb_name=" main";
         $tb_name=" party";
           if($_REQUEST['ddl_status']!='')
    {
        $ddl_status=" join main b on b.diary_no=a.diary_no and c_status='$_REQUEST[ddl_status]'";
    }
     if($_REQUEST['ddl_year']!='')
   {
       $ddl_year=" and substr(party.diary_no,-4)='$_REQUEST[ddl_year]'";
   }
    }
    else if($_REQUEST['ddl_diary_caveat']=='C')
    {
//        $tb_name=" caveat";
         $tb_name=" caveat_party";
        $caveat_no=" caveat_no";
         if($_REQUEST['ddl_year']!='')
   {
       $ddl_year=" and substr(caveat_party.caveat_no,-4)='$_REQUEST[ddl_year]'";
   }
    }
    $fst=intval($_REQUEST['nw_hd_fst']);
    $inc_val=intval($_REQUEST['inc_val']);
}



else{


 $ddl_party_type=$_REQUEST['ddl_party_type'];
    $parties='';
	$txt_name = strtolower($_REQUEST['txt_name']);
    if($ddl_party_type=='')
    {
//        $parties=" (pet_name like '%$_REQUEST[txt_name]%' || res_name like '%$_REQUEST[txt_name]%')";
        
        $parties=" AND (LOWER(partyname) like '%$txt_name%')";
    }
    else  if($ddl_party_type=='P')
    {
//        $parties=" pet_name like '%$_REQUEST[txt_name]%'";
         $parties=" AND LOWER(partyname) like '%$txt_name%' and pet_res='P'";
    }
     else  if($ddl_party_type=='R')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
          $parties=" AND LOWER(partyname) like '%$txt_name%' and pet_res='R'";
    }
     else  if($ddl_party_type=='I')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
          $parties=" AND LOWER(partyname) like '%$txt_name%' and pet_res='I'";
    }
      else  if($ddl_party_type=='N')
    {
//        $parties=" res_name like '%$_REQUEST[txt_name]%'";
          $parties=" AND LOWER(partyname) like '%$txt_name%' and pet_res='N'";
    }
    
    $tb_name='';
    $caveat_no='';
     $ddl_status='';
     $ddl_year='';
    if($_REQUEST['ddl_diary_caveat']=='D')
    {
//        $tb_name=" main";
         $tb_name=" party";
          if($_REQUEST['ddl_status']!='')
		{
			$ddl_status=" join main b on b.diary_no=party.diary_no and c_status='$_REQUEST[ddl_status]'";
		}
    
		//echo $_REQUEST['ddl_year'];
		if($_REQUEST['ddl_year']!='')
	   {
		   $ddl_year=" and substr(party.diary_no,-4)= '$_REQUEST[ddl_year]' ";
	   }
    }
    else if($_REQUEST['ddl_diary_caveat']=='C')
    {
//        $tb_name=" caveat";
         $tb_name=" caveat_party";
        $caveat_no=" caveat_no";
         if($_REQUEST['ddl_year']!='')
		   {
			   $ddl_year=" and substr(caveat_party.caveat_no,-4)='$_REQUEST[ddl_year]'";
		   }
    }
	 
}

  if($_REQUEST['ddl_diary_caveat']=='D')
	 $sql="Select  party.diary_no, partyname, pet_res, sr_no, sr_no_show from $tb_name $ddl_status  where 1=1  $parties $ddl_year order by party.diary_no limit $inc_val OFFSET $fst";
  else if($_REQUEST['ddl_diary_caveat']=='C')
      $sql="Select $caveat_no diary_no,partyname,pet_res from $tb_name  where  1=1   $parties $ddl_year order by SUBSTRING(CAST(caveat_no AS TEXT) FROM LENGTH(CAST(caveat_no AS TEXT)) - 3 FOR 4), 
    SUBSTRING(CAST(caveat_no AS TEXT) FROM 1 FOR LENGTH(CAST(caveat_no AS TEXT)) - 4) limit $inc_val OFFSET $fst ";
 //echo $sql;
 //die;
 $db = \Config\Database::connect();
 
  $query = $db->query($sql);
   
  $results = $query->getResultArray();
  
  if(!empty($results) && count($results) >0)
  {
      ?>
	  
<table class="table table_tr_th_w_clr c_vertical_align" width="100%" cellpadding="5" cellspacing="5">
    <tr>
        <th rowspan="2">
            SNo.
        </th>
        <th rowspan="2">
           <?php if($_REQUEST['ddl_diary_caveat']=='D') { ?> Diary No. <?php } else { ?> Caveat No. <?php ;} ?>
        </th>
        <?php  if($_REQUEST['ddl_diary_caveat']=='D') { ?> 
        <th rowspan="2">
           Case No.
        </th>
        <?php } ?>
<!--        <th>
            Petitioner
        </th>
         <th>
            Respondent
        </th>-->
        <th colspan="2">
            Party Details
        </th>
    </tr>
    <tr>
        <th>
            Name
        </th> 
         <th>
           Petitioner/Respondent
        </th>    
    </tr>
    <?php
//    $sno=1;
      if($_REQUEST['u_t']==0)
                        $sno=1;
                        else if($_REQUEST['u_t']==1)
                        $sno=$_REQUEST['inc_tot_pg'];
					$asd =array();
      foreach ($results as $row ) {
          ?>
    <tr>
        <td>
            <?php echo $sno; ?>
        </td>
        <td>
          <?php echo substr( $row['diary_no'], 0, strlen( $row['diary_no'] ) -4 ) ; ?>-<?php echo substr( $row['diary_no'] , -4 );?>
        </td>
        <?php  if($_REQUEST['ddl_diary_caveat']=='D') { ?> 
        <td>
            <?php 
				 
                 $get_case_details=get_case_details($row['diary_no']);			 
				if(!empty($get_case_details) && $get_case_details[7]!='')
				{
					echo $get_case_details[7].' '.substr($get_case_details[0],3).'/'.$get_case_details[1];
				}else {
					echo '-';
				}     
				 
            ?>
        </td>
        <?php } ?>

        <td>
            <?php
            echo $row['partyname'];
            ?>
        </td>
          <td>
            <?php
            $sub_pty='';
             if($_REQUEST['ddl_diary_caveat']=='D')
             {
            if($row['sr_no_show']!=$row['sr_no'])
                $sub_pty=$row['sr_no_show'];
            else 
                 $sub_pty=$row['sr_no'];
            echo $row['pet_res'].'['.$sub_pty.']';
             }
              else if($_REQUEST['ddl_diary_caveat']=='C')
    {
                  echo $row['pet_res']; 
              }
            ?>
        </td>
    </tr>
    <?php
    $sno++;
      } 
	 
    ?>
</table>
 <input type="hidden" name="inc_tot_pg" id="inc_tot_pg" value="<?php echo $sno; ?>" />  
<?php
 //pr($asd);
  }
  else 
  {
      ?>
<div style="text-align: center"><b>No Record Found</b></div>
<?php
  }
  ?>
