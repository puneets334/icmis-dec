<?php 
$c_date=date('Y-m-d');
 $ex_explode=  explode('#', $chk_bench);
   $chk_status=0;
    for ($index = 0; $index < count($ex_explode); $index++) 
	{
         $ex_in_exp=  explode('!', $ex_explode[$index]);

 
	if(!empty($ex_in_exp[3]))
	{
		$court_fee_cat = $CaveatModel->getMCourtFee($lst_case,$ex_in_exp[3]);
		
          if(!empty($court_fee_cat))
          {
               $r_court_fee_cat= $court_fee_cat;
               if($r_court_fee_cat['flag']=='V')
               {
                   $chk_status=1;
                 ?>
			<input type="hidden" name="hd_m_court_fee_id" id="hd_m_court_fee_id" value="<?php echo $r_court_fee_cat['id']; ?>"/>
			<?php
                   break;
               }
          }
	}
 } 
 ?>
<input type="hidden" name="hd_chk_status" id="hd_chk_status" value="<?php echo $chk_status; ?>"/>
 