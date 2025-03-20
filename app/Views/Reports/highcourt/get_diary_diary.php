<?php
 $txt_order_date='';
if ($res_sq > 0) {
  $fst = 0;
  $inc_val = 500;
  $tot_pg = ceil($res_sq / $inc_val);
?>
  <input type="hidden" name="hd_fst" id="hd_fst" value="<?php echo $fst; ?>" />
  <input type="hidden" name="inc_val" id="inc_val" value="<?php echo $inc_val; ?>" />
  <input type="hidden" name="inc_tot" id="inc_tot" value="<?php echo $tot_pg; ?>" />

  <input type="hidden" name="inc_count" id="inc_count" value="1" />
  <div class="dv_right" id="dv_le_ri">
  <span id="sp_frst"><?php echo $fst+1 ?></span>-<span id="sp_last"><?php if($res_sq<$inc_val) { echo $res_sq;}else{echo($fst+$inc_val);}?></span> of  <span id="sp_nf"><?php echo ($res_sq) ?></span>
                                                                      
    <?php
    if ($res_sq > $inc_val) {
    ?>
      <input type="button" name="btn_left" id="btn_left" value="<" disabled="true"/>
      <input type="button" name="btn_right" id="btn_right" value=">"/>
    <?php
    }
    ?>
  </div>
<?php
}



if ($res_sq > 0) {
?>
  <div id="dv_include" style="text-align: center;width: 100%">

    <?php

    $_REQUEST['u_t'] = 0;
    include('include_diary_diary.php');

    ?>
  </div>
<?php
} else {
?>
  <div style="text-align: center"><b>No Record Found for  
      <?php
    $ddl_bench_nm='';
    $ddl_ref_case_type_nm='';
      if($_REQUEST['ddl_bench_nm']!='')
          $ddl_bench_nm=$_REQUEST['ddl_bench_nm'];
        if($_REQUEST['ddl_ref_case_type_nm']!='')
          $ddl_ref_case_type_nm=$_REQUEST['ddl_ref_case_type_nm'];
        if($_REQUEST['txt_order_date']!='')
          $txt_order_date=' for order dated '. date('d-m-Y',strtotime($_REQUEST['txt_order_date'])) ;
      ?>
 <?php echo $_REQUEST['ddl_st_agncy_nm'].' '.$ddl_bench_nm; ?> 
 against case no. <?php echo $ddl_ref_case_type_nm.'-'.$_REQUEST['txt_ref_caseno_nm'].'-'.$_REQUEST['ddl_ref_caseyr_nm']  ?> 
 <?php echo $txt_order_date ?></b></div>

<?php
}






?>