
            <div class="row">
                <div class="col-12">
 <?php if (!empty($dno_data)) { $sno = 0; ?>

     <div class="row">
         <div class="col-sm-1"></div>
         <div class="col-sm-8" style="text-align: left !important;">
             <p><b class="pdiv">Diary No. : </b> <?=substr($dno_data['diary_no'], 0, -4).'/'.substr($dno_data['diary_no'],-4);?></p>
             <p><b class="pdiv">Cause Title : </b> <?=$dno_data['pet_name'].'  <b>Vs</b>  '.$dno_data['res_name'];?></p>
             <p><b class="pdiv">Registration No. : </b> <?=$dno_data['reg_no_display'];?></p>
             <p><b class="pdiv">Status. :</b> <?php if($dno_data['c_status']=='D'){echo 'Disposed';}else{echo 'Pending';} ?></p>
         </div>

         <br/>
     </div>
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10" style="text-align: left !important;">
                    <h2 class="page-header">List of disposed I.A.(s)</h2>
                </div>
            </div>
<hr/>
            <div class="row">
                <div class="col-sm-1 pdiv2"></div>
                <div class="col-sm-1 pdiv2">S.No.</div>
                <div class="col-sm-2 pdiv2">Select/Unselect</div>
                <div class="col-sm-2 pdiv2">I.A. No</div>
                <div class="col-sm-5 pdiv2">IA Restoration Remark</div>
            </div>

            <?php
          foreach ($ia_res as $data){
                $sno = $sno + 1;
                ?>
                <br/>

                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1">
                        <p class="pdiv1"><?= $sno ?></p>
                    </div>
                    <div class="col-sm-2">
                        <input type="checkbox" id="chk" name="chk" value="<?= $sno . '-' . $data['docd_id'] ?>" onclick="active_inactive(<?=$sno?>)">
                    </div>
                    <div class="col-sm-2">
                        <p class="pdiv1">I.A. No. <?= $data['ia'] ?></p>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" size="50" placeholder="Enter Remarks for restoration" name="remark<?php echo $sno; ?>" id="remark_<?php echo $sno; ?>" disabled></input>
                    </div>
                </div>

            <?php }
            ?>

            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-2">
                    <label for="btn">&nbsp</label>
                    <button type="button" id="search" class="form-control btn btn-primary" onclick="alive_ia()">Restore
                        marked I.A(s)
                </div>
            </div>


        <?php } else { ?>
            <div class="alert alert-danger">
                <strong>Fail!</strong> No disposed IA(s) found.
            </div>
        <?php }?>

                </div>
            </div>
