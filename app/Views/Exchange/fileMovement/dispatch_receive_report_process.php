<div class="col-12 col-sm-12 col-md-12 col-lg-12">
    <?php
        if($res_sq['count'] > 0)
        {
            $fst = 0;
            $inc_val = 10;
            $tot_pg = ceil($res_sq['count'] / $inc_val);
            //$count = 11;
            ?>

                <input type="hidden" name="hd_fst" id="hd_fst" value="<?php echo $fst; ?>" />
                <input type="hidden" name="inc_val" id="inc_val" value="<?php echo $inc_val; ?>"/>
                <input type="hidden" name="inc_tot" id="inc_tot" value="<?php echo $tot_pg; ?>" />

                <input type="hidden" name="inc_count" id="inc_count" value="1" />
                <div class="dv_right" id="dv_le_ri" style="margin-top: 2%">
                    <div style="font-weight: bold;">
                        <span id="sp_frst"><?php echo $fst+1 ?></span> - <span id="sp_last"><?php if($res_sq['count'] < $inc_val) { echo $res_sq['count'];}else{ echo($fst + $inc_val);}?></span> of <span id="sp_nf"><?php echo ($res_sq['count']) ?></span>
                    </div>
                    <?php
                        if($res_sq['count'] > $inc_val) {
                            ?>
                                <input class="btn btn-primary <?= ($res_sq['count'] < $inc_val) ? ' disablePreBtnForFisrtPage' : '' ?>" type="button" name="btn_left" id="btn_left" value="PREV">
                                <input type="button" name="btn_right" id="btn_right" value="NEXT" class="btn btn-primary">
                            <?php
                        }
                    ?>
                </div>
            <?php
        }
        if($res_sq['count'] > 0)
        {
            ?>
                <div id="r_box" style="width: 100%">
                    <?php
                        get_report_limit($rd,$mf,$rur,$ct,$fdt,$tdt,$fst,$inc_val);
                    ?>
                </div>
            <?php
        }
        else
        {
            ?>
                <div style="text-align: center"><b>No Record Found</b></div>
            <?php
        }
    ?>

</div>
<div align="center" style="padding-bottom: 10px;">
    <input name="cmdPrnRqs2" type="button" id="cmdPrnRqs2" onClick="CallPrint('r_box');" value="PRINT" class="btn btn-primary">
</div>