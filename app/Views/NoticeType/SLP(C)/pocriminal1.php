
<?php
include ('../extra/lg_out_script.php');
{
     
    include("../includes/db_inc.php");
  
    require_once ("function_template.php");
$dis_co_nm='';
  $dairy_no=$_REQUEST[fil_no];
 $year_s=substr( $dairy_no , -4 );
    $no_s=substr( $dairy_no, 0, strlen( $dairy_no ) -4 ); 
function send_to()
{
     $sql_Send=  mysql_query("Select id,desg from  tw_send_to where display='Y'") or die("Error: ".__LINE__.mysql_error());

                   while ($row2 = mysql_fetch_array($sql_Send))
                       {
                      $send_too[]=$row2['id'].'^'.$row2['desg'].'^'.$row2['s'];
                  
                       }
                       return $send_too;
}

function state_name($str)
{
    $state_nm="Select Name from state where id_no='$str' and display='Y'";
    $state_nm=mysql_query($state_nm) or die("Error : ".__LINE__.mysql_error());
    $res_state=mysql_result($state_nm,0);
    return $res_state;
}

function send_to_nm($str)
{
    $send_to="Select desg from  tw_send_to where id='$str' and display='Y'";
    $send_to=mysql_query($send_to) or die("Error : ".__LINE__.mysql_error());
    $res_send_to=mysql_result($send_to,0);
     return $res_send_to;
}

function send_to_advocate($str,$pet_res,$sr_no)
{
    $res_side='';
   
//    if($pet_res!='' &&  $sr_no!=0)
       if($pet_res!='')
    {
//        $res_side=' for '.$pet_res.'['.$sr_no.']';
        $res_side=' for '.$pet_res;
    }
    $send_to="Select name,title,caddress from  bar where bar_id='$str'";
    $send_to=mysql_query($send_to) or die("Error : ".__LINE__.mysql_error());
//    $res_send_to=mysql_result($send_to,0);
    $res_send_to=  mysql_fetch_array($send_to);
     return $res_send_to[title].' '. $res_send_to[name].' (Adv.)'.$res_side.'<br/>'. $res_send_to[caddress];
}

function send_to_court($str)
{
    $send_to="SELECT  Name,
IF (
ct_code =3, (

SELECT Name
FROM state s
WHERE s.id_no = a.l_dist
AND display = 'Y'
), (

SELECT concat(agency_name,', ',address)  agency_name
FROM ref_agency_code c
WHERE c.cmis_state_id = a.l_state
AND c.id = a.l_dist
AND is_deleted = 'f'
)
)agency_name
FROM lowerct a
LEFT JOIN state b ON a.l_state = b.id_no
AND b.display = 'Y'

JOIN main e ON e.diary_no = a.diary_no
WHERE a.lower_court_id = '$str'
AND lw_display = 'Y'
#AND c_status = 'P'
AND is_order_challenged = 'Y'";
    $send_to=mysql_query($send_to) or die("Error : ".__LINE__.mysql_error());
//    $res_send_to=mysql_result($send_to,0);
    $res_send_to=  mysql_fetch_array($send_to);
     return $res_send_to[agency_name].' '. $res_send_to[Name];
}

 $fil_det="Select active_casetype_id casetype_id,active_fil_no fil_no,short_description,casename,
    active_fil_dt fil_dt,pet_name,res_name,pet_adv_id,lastorder,date(diary_no_rec_date) diary_no_rec_date,
    pno,rno,casename from main a join casetype b on a.active_casetype_id=b.casecode where diary_no='$dairy_no' and display='Y'";
$fil_det=mysql_query($fil_det) or die("Error: ".__LINE__.mysql_error());
$res_fil_det=mysql_fetch_array($fil_det);
 $case_range=substr($res_fil_det['fil_no'],3);
 $ex_case_range=  explode('-', $case_range);
 $c_range='';
 $chk_range='';
 for ($index1 = 0; $index1 < count($ex_case_range); $index1++) {
     if($c_range=='')
   $c_range =  intval ($ex_case_range[$index1]);
     else
     {
          if($chk_range!=intval ($ex_case_range[$index1]))
         $c_range =$c_range.'-'.intval ($ex_case_range[$index1]);
     }
     $chk_range=intval ($ex_case_range[$index1]);
 }
 if($c_range!='')
 $case_range=$c_range;
 else 
    $case_range=  intval ($case_range) ;
$reg_year=date('Y',strtotime($res_fil_det['fil_dt']));
$last_order=$res_fil_det['lastorder'];
$ex=  explode("Ord dt:",$last_order); 
$last_order_dt= date('d-m-Y',strtotime($ex[1])); 
$pno='';
$rno='';
if($res_fil_det['pno']!=0)
{
if($res_fil_det['pno']==2)
   $pno=" and another";
else if($res_fil_det['pno']>2)
   $pno=" and others";
}
if($res_fil_det['rno']!=0)
{
if($res_fil_det['rno']==2)
   $rno=" and another";
else if($res_fil_det['rno']>2)
   $rno=" and others";
}



$sql_letter=mysql_query("Select count(a.id)  from  tw_tal_del a join tw_notice b on a.nt_type=b.id and b.display='Y' and war_notice='L' where diary_no='$dairy_no' and  	
                      rec_dt='$_REQUEST[dt]' and print='0' and a.display='Y'")or die("Error: ".__LINE__.mysql_error());
 $r_sql_letter=  mysql_result($sql_letter, 0);



$sql_res=mysql_query("Select a.id,process_id,a.name,nt_type,sr_no,pet_res,address,amount,amt_wor,date_format(rec_dt,'%Y') rec_dt,
    fixed_for,sub_tal,tal_state,tal_district,individual_multiple,concat(pet_res,'[',sr_no,']') p_sno  from  tw_tal_del a where diary_no='$dairy_no' and  	
                      rec_dt='$_REQUEST[dt]' and print='0' and a.display='Y' order by process_id,pet_res,sr_no")or die("Error: ".__LINE__.mysql_error());
if(mysql_num_rows($sql_res)>0)
{
$xzaq11_ben='';
$send_too=array();
//$sen_cp_to=send_to();



$sql_res_rw=mysql_num_rows($sql_res);

 if($r_sql_letter>0)
 {
     $sql_res_rw=$sql_res_rw-$r_sql_letter;
 }
    $v_cx=0;
$res_nms='';
$get_fx_fo_dt='';
$ct_ntt_type='';
  $ck_pbb=0;   
  $ck_mul_ind=0;
  $tot_copy='';
  $c_sno=1;
 $chk_copy_type=array();
$chk_del_type=array();
$chk_mul_letter='';
while ($row=mysql_fetch_array($sql_res))
    {
 
    $ck_mul_ind=$ck_mul_ind+1;
    $state_nm_m=state_name($row['tal_state']);
     $district_nm_m=state_name($row['tal_district']);
     $fixed_for=date('d-m-Y',strtotime($row['fixed_for']));
     
    $del_type_s="Select group_concat(del_type SEPARATOR '') from tw_o_r where tw_org_id='$row[id]' and display='Y' group by tw_org_id";
  $del_type_s=mysql_query($del_type_s)or die("Error: ".__LINE__.mysql_error());
  $del_type=mysql_result($del_type_s,0);
  
  
    ?>

<?php
    
 
    $chk_pet_res='';
    if($row['pet_res']=='P')
         $chk_pet_res='Petitioner';
     else if($row['pet_res']=='R')
         $chk_pet_res='Respondent'; 
     else
         $chk_pet_res='';
     
    

    $nt_type=  explode(',', $row['nt_type']);
             $res_ct='';
  
       
     for ($index = 0; $index < count($nt_type); $index++)
     {
  $n_ind_d=$nt_type[$index];

//$del_type=$row['del_type'];
$del_type_ct=strlen($del_type);
$ct_del_type=0;
for($dtc=0;$dtc<$del_type_ct;$dtc++)
{
$address_m='';

    $send_to_det="SELECT tw_sn_to, sendto_state, sendto_district,send_to_type FROM tw_o_r a JOIN tw_comp_not b ON a.id = b.tw_o_r_id WHERE a.display = 'Y'
AND b.display = 'Y' AND tw_org_id = '$row[id]'  AND copy_type =0 AND tw_sn_to !=0 and del_type='$del_type[$dtc]'";
$send_to_det=mysql_query($send_to_det) or die("Error: ".__LINE__.mysql_error());

 $mul_del_ty=0;
 
//
//      if($row['individual_multiple']==2)
//    {
       
//      if($ck_mul_ind!=$sql_res_rw)
//      {
//       if($ct_del_type==0)
//           $mul_del_ty=1;
//      
//        for ($index1 = 0; $index1 < count($chk_copy_type); $index1++) {
//            if(($del_type[$dtc])!=$chk_copy_type[$index1])
//            {
//               $mul_del_ty=1;
//            }
//        }
//         $chk_copy_type[]=$del_type[$dtc];
//    }
//    echo $del_type[$dtc].'<br/>';
//     if(($row['individual_multiple']==1) || ($row['individual_multiple']==2 && $mul_del_ty==1) )
// 
//     {
     if(mysql_num_rows($send_to_det)>0)
{
   
   
    
    
    
    $rw_sn_type=mysql_fetch_array($send_to_det);
    $state_nm=state_name($rw_sn_type['sendto_state']);
     $district_nm=state_name($rw_sn_type['sendto_district']);
     $tw_sendto_type='';
     if($rw_sn_type['send_to_type']=='2')
     {
   $tw_sn_to=  send_to_nm($rw_sn_type['tw_sn_to']);
   $tw_sendto_type=2;
     }
     else  if($rw_sn_type['send_to_type']=='1')
     {

//   $tw_sn_to=  send_to_advocate($rw_sn_type['tw_sn_to'],$row['pet_res'],$row['sr_no']);
          $tw_sn_to=  send_to_advocate($rw_sn_type['tw_sn_to'],$row['p_sno']);
   $tw_sendto_type=1;
     }
      else  if($rw_sn_type['send_to_type']=='3')
     {

  $tw_sn_to=  send_to_court($rw_sn_type['tw_sn_to']);
   $tw_sendto_type=3;
     }
     
}
else 
{
       $state_nm=$state_nm_m;
     $district_nm=$district_nm_m;
   $tw_sn_to= $row['name'].' '.$row['p_sno'];
   $address_m=$row['address'];
}
// }
 
 $tot_copy_send_to='';
   $send_copy_to_det="SELECT tw_sn_to, sendto_state, sendto_district,send_to_type FROM tw_o_r a JOIN tw_comp_not b ON a.id = b.tw_o_r_id WHERE a.display = 'Y'
AND b.display = 'Y' AND tw_org_id = '$row[id]'  AND copy_type =1 AND tw_sn_to !=0 and del_type='$del_type[$dtc]'";
$send_copy_to_det=mysql_query($send_copy_to_det) or die("Error: ".__LINE__.mysql_error());
if(mysql_num_rows($send_copy_to_det)>0)
{
$cnt_snd_cpy=0;
    while ($rw_send_copy_to_det = mysql_fetch_array($send_copy_to_det)) 
     {
   
  
        if($row['individual_multiple']==1 ||  $ck_mul_ind>$sql_res_rw)   
    {

            if($rw_send_copy_to_det['send_to_type']=='1')
  $advocate_nm= send_to_advocate($rw_send_copy_to_det['tw_sn_to']);
 else if($rw_send_copy_to_det['send_to_type']=='2')
    $advocate_nm=  send_to_nm($rw_send_copy_to_det['tw_sn_to']);
  else  if($rw_send_copy_to_det['send_to_type']=='3')
     {
      $advocate_nm=  send_to_court($rw_send_copy_to_det['tw_sn_to']);
 
     }
 
 
 
    $state_nm_c=state_name($rw_send_copy_to_det['sendto_state']);
     $district_nm_c=state_name($rw_send_copy_to_det['sendto_district']);
     if($tot_copy_send_to=='')
         $tot_copy_send_to=$advocate_nm.'!'.$state_nm_c.'!'.$district_nm_c;
     else
          $tot_copy_send_to=$tot_copy_send_to.'@'.$advocate_nm.'!'.$state_nm_c.'!'.$district_nm_c;
    }
     
     
   $cnt_snd_cpy++;   
   }
//   var_dump($chk_int_rec);
//   echo $tot_copy_send_to;
}
   
if($row['individual_multiple']==2)
{
//echo $tw_sn_to."<br/><br/><br/><br/>";
//continue;
  
//     for ($index1 = 0; $index1 < count($chk_del_type); $index1++) {
////         echo $chk_del_type[$index1];   
//       
//         if($del_type[$dtc]==$chk_del_type[$index1])
//            {
//               $mul_del_ty=1;
//            }
//             $chk_del_type[]=$del_type[$dtc];
//             echo $mul_del_ty;
//        }
//         $chk_del_type[]=$del_type[$dtc]; 
//        if($mul_del_ty==1)
        {
    
        }
//        $ct_del_type++;

//echo $ck_mul_ind.'##'.$sql_res_rw;
if($ck_mul_ind!=$sql_res_rw && $ck_mul_ind<=$sql_res_rw)
{
//    $tot_records=$tot_records.'<br/><br/>';
  
    continue;
}
 
//echo 'gg'.$tot_copy;

}
    ?>
<div id="<?php echo $row['id'] ?>_<?php echo $n_ind_d; ?>_<?php echo $del_type[$dtc]; ?>" class="ind_no_w_vc" style="position: relative;background-image: url('/var/www/html/supreme_court/images/scilogo.png');background-position: center;background-repeat: no-repeat;padding-left: 2px;padding-right: 2px;position: relative;<?php if($ck_pbb!=0) { ?> page-break-before:always; <?php ;} ?>">
   
 <?php
 if($row['individual_multiple']==2 && $ck_mul_ind<=$sql_res_rw)
{
    $mul_send_tp="SELECT tw_sn_to, sendto_state, sendto_district, send_to_type,tal_state,tal_district,z.name,
      address,
group_concat(concat(process_id,'/',year(rec_dt)) order by pet_res, sr_no,process_id separator ', ' ) process_id,     
#, group_concat(process_id order by pet_res, sr_no separator ', ' ) process_id,
year(rec_dt) rec_dt,group_concat(concat(pet_res,'[',sr_no,']') order by pet_res, sr_no,process_id separator ', ') p_sno
,pet_res, sr_no,del_type
FROM tw_tal_del z
JOIN tw_o_r a ON z.id = a.tw_org_id
JOIN tw_comp_not b ON a.id = b.tw_o_r_id
join tw_notice tn on tn.id=z.nt_type and tn.display='Y' and  	
war_notice!='L'
WHERE a.display = 'Y'
AND z.display = 'Y'

AND diary_no = '$dairy_no'
AND rec_dt = '$_REQUEST[dt]'
AND print = '0'
AND b.display = 'Y'
AND copy_type =0
AND del_type = '$del_type[$dtc]' group by if(tw_sn_to!=0,(CONCAT(send_to_type,tw_sn_to)),z.id)  order by pet_res, sr_no,process_id";
 $mul_send_tp=  mysql_query($mul_send_tp) or die("Error: ".__LINE__.mysql_error());
 if(mysql_num_rows($mul_send_tp)>0)
 {
    $tot_records='';
   $cnt_first_rec=0;
   $ind_org=0;
   $ss_no=1;
     while ($row1 = mysql_fetch_array($mul_send_tp)) {
        
         if($n_ind_d=='12')
         {
             $check_party="Select ind_dep from party where diary_no='$dairy_no' 
                    and pet_res='$row1['pet_res']' and pflag='P' and sr_no='$row1['sr_no']'";
             $check_party=  mysql_query($check_party) or die("Error: ".__LINE__.mysql_error());
             $res_check_party=  mysql_result($check_party, 0);
             if($res_check_party!='I')
             {
                 $ind_org=1;
             }
         }
         
         
         if($row1['tw_sn_to']!=0)
         {
             $state_nm=state_name($row1['sendto_state']);
     $district_nm=state_name($row1['sendto_district']);
     $tw_sendto_type='';
     if($row1['send_to_type']=='2')
     {
   $tw_sn_to=  send_to_nm($row1['tw_sn_to']);
   $tw_sendto_type=2;
     }
     else  if($row1['send_to_type']=='1')
     {

//   $tw_sn_to=  send_to_advocate($row1['tw_sn_to'],$row1['pet_res'],$row1['sr_no']);
         $tw_sn_to=  send_to_advocate($row1['tw_sn_to'],$row1['p_sno']);
   $tw_sendto_type=1;
  
     }
      else  if($row1['send_to_type']=='3')
     {

   $tw_sn_to=  send_to_court($row1['tw_sn_to']);
   $tw_sendto_type=3;
     }
     $address_m='';
         }
         else 
         {
             $state_nm_m=state_name($row1['tal_state']);
     $district_nm_m=state_name($row1['tal_district']);
	 
	  $state_nm=$state_nm_m;
     $district_nm=$district_nm_m;
//   $tw_sn_to= $row1['name'].' '.$row1['pet_res'].'['.$row1['sr_no'].']' ;
 $tw_sn_to= $row1['name'];
   $address_m=$row1['address'];
         }
        
         $address_c='';
 if($address_m!='')
     $address_c='<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;clear:both" > <b> 
       <font style="font-size: 14.5px" >  <b style="font-size: 14.5px">'. $address_m. '</b>, </font></b></p>';
$margin_t='';
 if($cnt_first_rec==0)
     $margin_t="0px";
 else
      $margin_t="10px";
 
 $tot_records=$tot_records.'<p style="color: #000000;margin:'. $margin_t.' 0px 0px 0px;padding: 0px 2px 0px 42px;width: 50%;float:left;clear:both;" >
        <b> <font style="font-size: 14.5px" >'.$tw_sn_to.'</b>, </p>';

// $tot_records=$tot_records.'<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 30%;float:right" >
//        <b> <font style="font-size: 14.5px" >'.$row1['pet_res'].'['.$row1['sr_no'].']'.'</b></p>';
 
 
$tot_records=$tot_records.'<p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 30%;float:right" >
        <b> <font style="font-size: 14.5px" >PId: '.$row1['process_id'].' for '.$row1['pet_res'].'['.$row1['sr_no'].']'.'</b></p>';

$tot_records=$tot_records.$address_c;
$tot_records=$tot_records.' <p style="color: #000000;margin: 0px;padding: 0px 2px 0px 42px;width: 50%;float: left;clear:both"><b> <font  style="font-size: 14.5px">   District- <b style="font-size: 14.5px">' .$district_nm.','.$state_nm.'</b></font></b>
    </p>';   
$cnt_first_rec++;    
  $ex_del_type=$row1['del_type'];
  
  $ss_no++;
}

 }

  $mul_send_tp1="SELECT distinct tw_sn_to, sendto_state, sendto_district, send_to_type,del_type
FROM tw_tal_del z
JOIN tw_o_r a ON z.id = a.tw_org_id
JOIN tw_comp_not b ON a.id = b.tw_o_r_id
join tw_notice tn on tn.id=z.nt_type and tn.display='Y' and  	
war_notice!='L'
WHERE a.display = 'Y'
AND z.display = 'Y'

AND diary_no = '$dairy_no'
AND rec_dt = '$_REQUEST[dt]'
AND print = '0'
AND b.display = 'Y'
AND copy_type =1
AND del_type = '$del_type[$dtc]'";
 $mul_send_tp1=  mysql_query($mul_send_tp1) or die("Error: ".__LINE__.mysql_error());
 if(mysql_num_rows($mul_send_tp1)>0)
 {
 
     while ($row11 = mysql_fetch_array($mul_send_tp1)) {
        if($row11['send_to_type']=='1')
  $advocate_nm1= send_to_advocate($row11['tw_sn_to']);
 else if($row11['send_to_type']=='2')
    $advocate_nm1=  send_to_nm($row11['tw_sn_to']);
  else if($row11['send_to_type']=='3')
    $advocate_nm1=  send_to_court($row11['tw_sn_to']);
  
    $state_nm_c=state_name($row11['sendto_state']);
     $district_nm_c=state_name($row11['sendto_district']);
     if($tot_copy_send_to=='')
         $tot_copy_send_to=$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c;
     else
          $tot_copy_send_to=$tot_copy_send_to.'@'.$advocate_nm1.'!'.$state_nm_c.'!'.$district_nm_c;
    
     
     }
 }
 
 if($tot_copy_send_to!='')
{
    $c_sno=1;
   $tot_copy='';
  $ex_c_s_t=explode('@',$tot_copy_send_to);
    $tot_copy=$tot_copy.'<div style="margin-left: 30px"><table>';
            for ($index = 0; $index < count($ex_c_s_t); $index++) {
                 $ex_explode=explode('!',$ex_c_s_t[$index]);
                  $tot_copy=$tot_copy.'<tr>
                  <td style="font-size: 13px;vertical-align: top">'.$c_sno;
                   $tot_copy=$tot_copy.'</td>
                    <td >
                        <div style="font-size: 14.5px">';
                    $ex_exp=  explode('~', $ex_explode[0]);
                        $tot_copy=$tot_copy. $ex_exp[1].' '. ucwords(strtolower($ex_exp[0])).' '.ucwords(strtolower($ex_exp[2]));
                         $tot_copy=$tot_copy.'</div>
                        <div style="font-size: 14.5px">'.ucwords(strtolower($ex_explode[2])).', '.ucwords(strtolower($ex_explode[1])).'</div></td>
              </tr>';
               $c_sno++;   
               
             }  $tot_copy=$tot_copy.'</table></div>';
          
}
else 
{
    $tot_copy='';
}
 if($ex_del_type=='H')
           {
             $tot_copy=$tot_copy.'<p style="font-size: 14.5px">
    (*Copies of dasti notice are enclosed herewith. You are requested to file affidavit of service forthwith.)
</p>';
           }
}
 
   
    $v_cx++;
    $fn_del_type= $del_type[$dtc];


     switch ($n_ind_d)
{
	
    
    case 1:
		include ('NoticeType/SLP(C)/returnable_notice.php');
	break;
	
	case 2:
		include ('NoticeType/SLP(Crl)/2.php');
	break;
	
	case 3:
		include ('office_report/criminal/after_notice.php');
	break;
    case 4:
		include ('NoticeType/SLP(Crl)/4.php');
	break;
     case 5:
		include ('NoticeType/SLP(Crl)/5.php');
	break;
     case 6:
		include ('NoticeType/SLP(Crl)/6.php');
	break;
 case 7:
		include ('NoticeType/SLP(C)/7.php');
	break;	
case 8:
		include ('NoticeType/SLP(C)/8.php');
	break;	
    case 9:
		include ('NoticeType/SLP(C)/9.php');
	break;	
      case 10:
		include ('NoticeType/SLP(C)/10.php');
	break;	
      case 11:
		include ('NoticeType/SLP(C)/11.php');
	break;	
     case 12:
		include ('NoticeType/SLP(C)/12.php');
	break;
     case 13:
		include ('NoticeType/SLP(C)/13.php');
	break;
    case 14:
		include ('NoticeType/SLP(C)/14.php');
	break;
     case 15:
		include ('NoticeType/SLP(C)/15.php');
	break;
      case 16:
		include ('NoticeType/SLP(C)/16.php');
	break;
    case 17:
		include ('NoticeType/SLP(C)/17.php');
	break;
     case 18:
		include ('NoticeType/SLP(C)/18.php');
	break;
      case 19:
		include ('NoticeType/SLP(C)/18.php');
	break;
     case 20:
		include ('NoticeType/SLP(C)/20.php');
	break;
      case 21:
		include ('NoticeType/SLP(Crl)/21.php');
	break;
      case 22:
		include ('NoticeType/SLP(Crl)/22.php');
	break;
    case 23:
		include ('NoticeType/SLP(Crl)/23.php');
	break;
     case 24:
		include ('NoticeType/SLP(Crl)/24.php');
	break;
     case 25:
		include ('NoticeType/SLP(Crl)/25.php');
	break;
     case 26:
		include ('NoticeType/SLP(Crl)/26.php');
	break;
    case 27:
		include ('NoticeType/SLP(Crl)/27.php');
	break;	
     case 28:
		include ('NoticeType/SLP(Crl)/28.php');
	break;
    case 29:
		include ('NoticeType/SLP(Crl)/29.php');
	break;
     case 30:
		include ('NoticeType/SLP(Crl)/30.php');
	break;
     case 31:
		include ('NoticeType/SLP(Crl)/31.php');
	break;
      case 32:
		include ('NoticeType/SLP(Crl)/32.php');
	break;
     case 33:
		include ('NoticeType/SLP(Crl)/33.php');
	break;
     case 34:
		include ('NoticeType/SLP(Crl)/34.php');
	break;
     case 35:
		include ('NoticeType/SLP(Crl)/35.php');
	break;
     case 36:
		include ('NoticeType/SLP(Crl)/36.php');
	break;
     case 37:
		include ('NoticeType/SLP(Crl)/37.php');
	break;
     case 38:
		include ('NoticeType/SLP(Crl)/38.php');
	break;
      case 39:
		include ('NoticeType/SLP(Crl)/39.php');
	break;
      case 40:
		include ('NoticeType/SLP(C)/40.php');
	break; 
      case 41:
		include ('NoticeType/SLP(C)/41.php');
	break;     
      case 42:
		include ('NoticeType/SLP(C)/42.php');
	break;   
    case 43:
		include ('NoticeType/SLP(Crl)/43.php');
	break; 
    case 44:
		include ('NoticeType/SLP(C)/44.php');
	break;  
    case 45:
		include ('NoticeType/SLP(C)/45.php');
	break;  
    case 46:
		include ('NoticeType/SLP(C)/46.php');
	break;  
    case 47:
		include ('NoticeType/SLP(C)/47.php');
	break; 
    case 48:
		include ('NoticeType/SLP(C)/48.php');
	break; 
    case 49:
		include ('NoticeType/SLP(C)/49.php');
	break;
     case 50:
		include ('NoticeType/SLP(C)/12.php');
	break;  
    default:
	break;
   }
  
   ?>
      <div style="clear: both" id="qr_<?php echo $row['id'] ?>_<?php echo $n_ind_d; ?>_<?php echo $del_type[$dtc]; ?>"></div>
</div>
    <?php
    $ck_pbb++;
    $ct_del_type++;
  
    if($ck_mul_ind==$sql_res_rw)
    {
        break;
    }
}
   
// if(($_REQUEST['case_type']=='MCRC' && ($nt_type[$index]=='18' || $nt_type[$index]=='83' || $nt_type[$index]=='87')) || $_REQUEST['case_type']=='CONCR')
//    {
//       $ct_ntt_type=1;
//       if($nt_type[$index]=='87' || $nt_type[$index]=='83' ||  $nt_type[$index]=='18')
//           $row['cp_sn_to']= $row['send_to'];
//      
//       if($res_nms=='')
//       {
//           $res_nms=$row['sr_no'].'^'.$row['del_type'].'^'.$row['cp_sn_to'].'^'.$row['pet_res'].'^'.$nt_type[$index];
//           $get_fx_fo_dt=$row['fixed_for'];
//       }
// else
//     {
//           $res_nms=$res_nms.'@'.$row['sr_no'].'^'.$row['del_type'].'^'.$row['cp_sn_to'].'^'.$row['pet_res'].'^'.$nt_type[$index];
//       }
//       
//    }
//    else if(($_REQUEST['case_type']=='MA' && ($nt_type[$index]=='18' || $nt_type[$index]=='66' || $nt_type[$index]=='65' || $nt_type[$index]=='67' || $nt_type[$index]=='68'  || $nt_type[$index]=='87' || $nt_type[$index]=='97')) )
//    {
//         $ct_ntt_type=1;
//           if($nt_type[$index]=='68' || $nt_type[$index]=='87' || $nt_type[$index]=='97')
//           $row['cp_sn_to']= $row['send_to'];
//             if($res_nms=='')
//       {
//           $res_nms=$row['sr_no'].'^'.$row['del_type'].'^'.$row['cp_sn_to'].'^'.$row['pet_res'].'^'.$nt_type[$index].'^'.$row['name'];
//           $get_fx_fo_dt=$row['fixed_for'];
//       }
// else
//     {
//           $res_nms=$res_nms.'@'.$row['sr_no'].'^'.$row['del_type'].'^'.$row['cp_sn_to'].'^'.$row['pet_res'].'^'.$nt_type[$index].'^'.$row['name'];
//       }
//         
//    }

}
   
   ?>
<!-- </div>   -->
    <?php
    
    }
 
//    if(($_REQUEST['case_type']=='MCRC' || $_REQUEST['case_type']=='CONCR') && $ct_ntt_type==1)
//    {
//        
//        include ('NoticeType/mcrc/cover_letter.php');
//    }
//    else  if($_REQUEST['case_type']=='MA' && $ct_ntt_type==1)
//    {
//         include ('NoticeType/ma/ma_cover_letter.php');
//    }
    
    ?>
<input type="hidden" name="hd_tot_po" id="hd_tot_po" value="<?php echo $v_cx; ?>"/>
<?php
}
 else
 {
     $fil_nm="../pdf_notices/".$year_s."/".$no_s."/".$_REQUEST[fil_no].'_'.$_REQUEST['dt'].".html";
    $ds=fopen($fil_nm, 'r');
   $b_z= fread($ds, filesize($fil_nm) );
    fclose($ds);
    echo utf8_encode($b_z);
}
?>

<?php

}
?>






