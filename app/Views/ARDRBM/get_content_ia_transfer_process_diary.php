
            <div class="row">
                <div class="col-12">
 <?php if (!empty($dno_data)) { $sno = 0; ?>
<input type="hidden" id="diary_number_year_ia" name="diary_number_year_ia" value="<?=$diary_number_year_ia;?>">
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

         <div class="col-sm-4"></div>
         <div class="col-sm-2">
             <label for="btn">&nbsp</label>
             <button type="button" id="search" class="form-control btn btn-primary" onclick="transfer_ia();">Transfer IA
         </div>
     </div>
        <?php } else { ?>
            <div class="alert alert-danger"><strong>No record found!</strong></div>
        <?php }?>

                </div>
            </div>
