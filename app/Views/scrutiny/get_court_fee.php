<?php
 
    $c_date=date('Y-m-d');
    $dairy_no=$d_no.$d_yr;
    
    $chk_status=0;
    if($lst_case=='7')
    {
        /* $jud_challanged="Select count(*) from lowerct a join transfer_to_details b on a.lower_court_id=b.lowerct_id
          where diary_no = '$dairy_no'  and lw_display='Y' and display='Y' and transfer_state!=0"; */
		 $jud_challanged = $CaveatModel->getLowerCTBy7Count($dairy_no);
    }
    else 
    {
     /* $jud_challanged="Select count(lower_court_id) from lowerct where diary_no='$dairy_no' and lw_display='Y' and is_order_challenged='Y'"; */
	 $jud_challanged = $CaveatModel->getLowerCTCount($dairy_no);
    }
    //$jud_challanged=mysql_query($jud_challanged) or die("Error: ".__LINE__.mysql_error());
    //$res_jud_challanged=mysql_result($jud_challanged,0);
    
	$res_jud_challanged= $jud_challanged;
    $ex_explode=  explode('#', $chk_bench);
    for ($index = 0; $index < count($ex_explode); $index++) 
{
    $tot_fee=0;
        $ex_in_exp=  explode('!', $ex_explode[$index]);
 
        
         /* $court_fee_cat="Select court_fee,flag,security_deposit from m_court_fee where display='Y' and casetype_id='$lst_case'  
   and submaster_id='$ex_in_exp[3]' and case_law='0' AND ((
  '$c_date'  BETWEEN from_date
    AND to_date
  ) or (from_date<= '$c_date' and to_date= '0000-00-00'))";
  $court_fee_cat=mysql_query($court_fee_cat) or die("Error: ".__LINE__.mysql_error()); */
  
		$r_court_fee_cat = $CaveatModel->getCourtFeeCat($lst_case,$ex_in_exp);  
  
          if(!empty($r_court_fee_cat))
          {
              $chk_status=1;
              //$r_court_fee_cat=mysql_fetch_array($court_fee_cat);
                if($r_court_fee_cat['flag']=='S')
                {
                   $tot_fee= $r_court_fee_cat['court_fee'];
                   break;
                }
               else  if($r_court_fee_cat['flag']=='T')
                {
                   $tot_fee= $r_court_fee_cat['court_fee'];
                   break;
                }
                else   if($r_court_fee_cat['flag']=='')
                {
                     $tot_fee= $r_court_fee_cat['court_fee'];
                }
            
    }
}
if($chk_status==0)
{
      for ($index = 0; $index < count($ex_explode); $index++) 
{
            $ex_in_exp=  explode('!', $ex_explode[$index]);
			
           /*  $court_fee_cat1="Select court_fee,flag,security_deposit from m_court_fee where display='Y' and casetype_id='0'  
     and submaster_id='$ex_in_exp[3]' and case_law='0' AND ((
  '$c_date'  BETWEEN from_date
    AND to_date
  ) or (from_date<= '$c_date' and to_date= '0000-00-00'))";
  $court_fee_cat1=mysql_query($court_fee_cat1) or die("Error: ".__LINE__.mysql_error()); */
  
		$r_court_fee_cat = $CaveatModel->getCourtFeeCat1($ex_in_exp);  
		
          if(!empty($r_court_fee_cat))
          {
              $chk_status=1;
             // $r_court_fee_cat=mysql_fetch_array($court_fee_cat1);
               
                     $tot_fee= $r_court_fee_cat['court_fee'];
                
            
    }
}
}
if($res_jud_challanged!=0)
echo $tot_fee*$res_jud_challanged;
else 
 echo $tot_fee;
 
