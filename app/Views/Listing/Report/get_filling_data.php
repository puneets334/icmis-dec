<style>
    .cl_hover {
    color: blue;
}
    </style>
<div class="table-responsive mt-5">
<table class="table table-striped custom-table" id="reportTable1">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
    <thead>
    <tr> <?php ?>
        <th <?php if ($ddl_users == '103' || $ddl_users == '108') { ?> rowspan="3" <?php } else {  ?> rowspan="2" <?php } ?>>S.No.</th>
        <th <?php if ($ddl_users == '103' || $ddl_users == '108') { ?> rowspan="3" <?php } else {  ?> rowspan="2" <?php } ?>>Employee Name</th>
        <th <?php if ($ddl_users == '103' || $ddl_users == '108') { ?> colspan="6" <?php } else {  ?> colspan="3" <?php } ?>>Total Cases</th>
        <?php
        if ($ddl_users == '103' || $ddl_users == '108') {
        ?>
            <th colspan="2">Total Pendency till <?php echo date('d-m-Y', strtotime(date('d-m-Y'))); ?> from 01-06-2018</th>
        <?php } else if ($ddl_users != '101' && $ddl_users != '109') {
        ?>
            <th rowspan="2">Total Pendency till <?php echo date('d-m-Y'); ?></th>
        <?php }
        if ($ddl_users != '109') {
        ?>
            <th <?php if ($ddl_users == '103' || $ddl_users == '108') { ?> colspan="4" <?php } else { ?> colspan="2" <?php } ?>>
                Total Work Done
            </th>
        <?php } ?>
    </tr>
    <?php
    if ($ddl_users == '103') {
    ?>
        <tr>
            <th colspan="3">Filing</th>
            <th colspan="3">Re-Filing</th>
            <th rowspan="2">Filing</th>
            <th rowspan="2">Re-Filing</th>
            <th colspan="2">Filing</th>
            <th colspan="2">Re-Filing</th>
        </tr>
    <?php } else if ($ddl_users == '108') {
    ?>
        <tr>
            <th colspan="3">Dispatched</th>
            <th colspan="3">Received</th>
            <th rowspan="2">Dispatched</th>
            <th rowspan="2">Received</th>
            <th colspan="2">Dispatched</th>
            <th colspan="2">Received</th>
        </tr>
    <?php
    }
    ?>
    <tr>
        <th>Alloted</th>
        <th>Completed</th>
        <th>Remaining</th>
        <?php
        if ($ddl_users == '103' || $ddl_users == '108') {
        ?>
            <th>Alloted</th>
            <th>Completed</th>
            <th>Remaining</th>
            <th>Received</th>
            <th>Dispatched</th>
        <?php }
        if ($ddl_users != '109') {
        ?>
            <th>Received</th>
            <th>Dispatched</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $sno = 1;
    $tot_alloted = 0;
    $tot_comp = 0;
    $tot_rem = 0;
    $tot_s = 0;
    $tot_ref = 0;
    $tot_ss = 0;
    $tot_ssr = 0;
    $tot_ssrs = 0;
    $tot_sss = 0;
    $tot_allotedr = 0;
    $tot_compr = 0;
    $tot_remr = 0;

    foreach ($sql as $row) {

    ?>
        <tr>
            <td>
                <?php echo $sno;  ?>
            </td>
        

            <td>
            <?php echo $model->getUserName($row['d_to_empid']);?>
        </td>
            <td>
                <?php
                if ($ddl_users == '101' ||  $ddl_users == '109') {
                    $row['s'] = $row['ss'];
                }
                ?>
                <span  id="spallot_<?php echo $sno; ?>" class="cl_hover" <?php if($row['s'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)" <?php } ?>><?php $tot_alloted = $tot_alloted + $row['s'];
                                                                                                    echo $row['s']; ?></span>
            </td>
            <td>
                <span  id="spcomp_<?php echo $sno; ?>" class="cl_hover" <?php if($row['ss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_comp = $tot_comp + $row['ss'];
                                                                                                    echo $row['ss']; ?></span>
            </td>
            <td>
                <span id="spnotcomp_<?php echo $sno; ?>" class="cl_hover" <?php if(($row['s']- $row['ss']) !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_rem = $tot_rem + $row['s'] - $row['ss'];
                                                                                                        echo $row['s'] - $row['ss']; ?></span>
            </td>
            <?php
            if ($ddl_users == '103' || $ddl_users == '108') {

            ?>
                <td>
                    <span id="spallotr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_s'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_allotedr = $tot_allotedr + $row['r_s'];
                                                                                                        echo $row['r_s']; ?></span>
                </td>
                <td>
                    <span id="spcompr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_ss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_compr = $tot_compr + $row['r_ss'];
                                                                                                        echo $row['r_ss']; ?></span>
                </td>
                <td>
                    <span id="spnotcompr_<?php echo $sno; ?>" class="cl_hover" <?php if(($row['r_s'] - $row['r_ss']) !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php
                                                                                                            $tot_remr = $tot_remr + $row['r_s'] - $row['r_ss'];
                                                                                                            echo $row['r_s'] - $row['r_ss'];
                                                                                                            ?></span>
                </td>

                <td>
                    <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_s = $tot_s + $row['sss'];
                                                                                                        echo $row['sss']; ?></span>

                </td>
                <td>
                    <span id="sptotref_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ref = $tot_ref + $row['r_sss'];
                                                                                                        echo $row['r_sss']; ?></span>
                </td>
                <td>
                    <span id="sptotpenr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ssrs = $tot_ssrs + $row['sssss'];
                                                                                                            echo $row['sssss']; ?></span>
                </td>
                <td>
                    <span id="sptwd_<?php echo $sno; ?>" class="cl_hover" <?php if($row['ssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ss = $tot_ss + $row['ssss'];
                                                                                                        echo $row['ssss']; ?></span>
                </td>
                <td>
                    <span id="sptwdr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_sssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_sss = $tot_sss + $row['r_sssss'];
                                                                                                        echo $row['r_sssss']; ?></span>
                </td>
                <td>
                    <span id="sptwdd_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_ssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ssr = $tot_ssr + $row['r_ssss'];
                                                                                                        echo $row['r_ssss']; ?></span>
                </td>
            <?php } else  if ($ddl_users != '101' && $ddl_users != '109') {
            ?>
                <td>
                    <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_s = $tot_s + $row['sss'];
                                                                                                        echo $row['sss']; ?></span>

                </td>
                <td>
                    <span id="sptotpenr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ssrs = $tot_ssrs + $row['sssss'];
                                                                                                            echo $row['sssss']; ?></span>
                </td>
                <td>
                    <span id="sptwd_<?php echo $sno; ?>" class="cl_hover" <?php if($row['ssss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ss = $tot_ss + $row['ssss'];
                                                                                                        echo $row['ssss']; ?></span>
                </td>
            <?php
            }
            ?>
            <input type="hidden" name="hd_nm_id<?php echo $sno; ?>" id="hd_nm_id<?php echo $sno; ?>" value="<?php echo $row['d_to_empid']; ?>" />
        </tr>
    <?php
        $sno++;
    }
    if($ddl_users=='105' || $ddl_users=='108')
    {
        $pend_cat = $model->get_pen_cat($ddl_users,$frm_dt,$to_dt,$chk_da,$chk_users);
        foreach ($pend_cat as $row) {
    ?>
  <tr>
        <th colspan="6">
            
            Cases alloted and pending in <?php if($ddl_users=='105') { ?> CATEGORIZATION SUB-SECTION <?php } 
            else if($ddl_users=='108') { ?> FILING DISPATCH RECEIVE <?php } ?>
        </th>
    </tr>
    
     <tr>
        <td>
            <?php echo $sno;  ?>
        </td>
        <td>
        <?php
                if (!empty($get_usr_nm)) {
                    foreach ($get_usr_nm as $name) {
                        echo $name;
                    }
                } else {
                    echo "No user names found.";
                }
                ?>
        </td>
        <td>
          <span id="spallot_<?php echo $sno; ?>" class="cl_hover" <?php if($row['s'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_alloted=$tot_alloted+$row['s']; echo $row['s']; ?></span>
        </td>
         <td>
            
        </td>
        <td>
           <span id="spnotcomp_<?php echo $sno; ?>" class="cl_hover" <?php if($row['s'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php
          $tot_rem=$tot_rem+$row['s'];  echo $row['s'];
            ?></span>
        </td>
       
     <?php
        if($ddl_users=='108'){ 
        ?>
         <td>
            <span id="spallotr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_s'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_allotedr=$tot_allotedr+$row['r_s']; echo $row['r_s']; ?></span>
        </td>
         <td>
            <span id="spcompr_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_ss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_compr=$tot_compr+$row['r_ss']; echo $row['r_ss']; ?></span>
        </td>
        <td>
            <span id="spnotcompr_<?php echo $sno; ?>" class="cl_hover" <?php if(($row['r_s']-$row['r_ss']) !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php
          $tot_remr=$tot_remr+$row['r_s']-$row['r_ss'];  echo $row['r_s']-$row['r_ss'];
            ?></span>
        </td>
        
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
        <td>
             <span id="sptotref_<?php echo $sno; ?>" class="cl_hover" <?php if($row['r_sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_ref=$tot_ref+$row['r_sss']; echo $row['r_sss']; ?></span>
        </td>
        <?php }
        else {
        ?>
        <td>
            <span id="sptotpen_<?php echo $sno; ?>" class="cl_hover" <?php if($row['sss'] !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php $tot_s=$tot_s+$row['sss']; echo $row['sss']; ?></span>
           
        </td>
      
        <?php } ?>
        <input type="hidden" name="hd_nm_id<?php echo $sno; ?>" id="hd_nm_id<?php echo $sno; ?>" value="<?php echo $row['d_to_empid']; ?>"/>
    </tr>
    <?php
        }
 } ?>

<tr>
        <th colspan="2">Total</th>
        <td><span id="spallot_0" class="cl_hover" <?php if($tot_alloted !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_alloted; ?></span></td>
         <td><span id="spcomp_0" class="cl_hover" <?php if($tot_comp !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_comp; ?></span></td>
         <td><span id="spnotcomp_0" class="cl_hover" <?php if($tot_rem !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_rem; ?></span></td>
           <?php
        if($ddl_users=='103' || $ddl_users=='108'){ 
        ?>
         <td><span id="spallotr_0" class="cl_hover" <?php if($tot_allotedr !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_allotedr; ?></span></td>
           <td> <span id="spcompr_0" class="cl_hover" <?php if($tot_compr !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_compr; ?></span></td>
            <td> <span id="spnotcompr_0" class="cl_hover" <?php if($tot_remr !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_remr; ?></span></td>
         <td> <span id="sptotpen_0" class="cl_hover" <?php if($tot_s !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_s; ?></span></td>
         <td> <span id="sptotref_0" class="cl_hover" <?php if($tot_ref !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ref; ?></span></td>
        
           <td>
             <span id="sptotpenr_0" class="cl_hover" <?php if($tot_ssrs !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ssrs; ?></span>
         </td>
       
          <td>
             <span id="sptwd_0" class="cl_hover" <?php if($tot_ss !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ss; ?></span>
         </td>
         
          <td>
             <span id="sptwdr_0" class="cl_hover" <?php if($tot_sss !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_sss; ?></span>
        </td>
         <td>
            <span id="sptwdd_0" class="cl_hover" <?php if($tot_ssr !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ssr; ?></span>
        </td>
        <?php }
        else  if($ddl_users!='101' && $ddl_users!='109'){ 
            ?>
         <td> <span id="sptotpen_0" class="cl_hover" <?php if($tot_s !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_s; ?></span></td>
         <td><span id="sptotpenr_0" class="cl_hover" <?php if($tot_ssrs !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ssrs; ?></span></td>
           <td>
             <span id="sptwd_0" class="cl_hover" <?php if($tot_ss !== 0){ ?>data-toggle="modal" data-target="#modal-default" onclick="get_rec(this.id)"<?php } ?>><?php echo $tot_ss; ?></span>
         </td>
         <?php
        }
        ?>
       
    </tr>
    </tbody>
</table>
</div>

