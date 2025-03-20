<?= view('header') ?>
<style>
    * {
    /* font-size: 13px; */
    font-family: verdana;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"> ADD CASE CONSENT FOR HEARING MODE </h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <!-- Main content start -->
                    <div class="col-md-12">
                        <div class="card-body">

                            <div id="show_fil"></div>
                            <input type="hidden" id="side_hd" value="<?php echo isset($details['side']) ? $details['side'] : ''; ?>">
                            <input type="hidden" id="usercode" name="usercode" value="<?=$usercode?>">
                            <table align="center" width="100%">
                                <tr align="center" style="color:blue">
                                    <th style="font-weight:bold;"><?php
                                        echo "Case No.-";
                                        if(isset($fil_details['fil_no']) && ($fil_details['fil_no'] !='' || $fil_details['fil_no'] !=NULL)){
                                            echo '[M]'.$fil_details['short_description'].SUBSTR($fil_details['fil_no'],3).'/'.$fil_details['m_year'];
                                        }
                                        if(isset($short_description_by_casecode)){
                                            echo ',[R]'.$short_description_by_casecode.SUBSTR($fil_details['fil_no_fh'],3).'/'.$fil_details['f_year'];
                                        }
                                        //echo ", Diary No: ".substr($diary_number['dno'],0,-4).'/'.substr($_REQUEST['dno'],-4);
                                        echo ", Diary No: ". $diary_number.' / '.$diary_year;

                                        // navigate_diary($_REQUEST['dno']);
                                        ?>
                                    </th>
                                </tr>
                            </table>
                            <table align="center" id="tb_clr" cellspacing="3" cellpadding="2">
                            <?php if(isset($details['c_status']) && $details['c_status']=='D') { ?>
                                <tr align="center"><th colspan="4" style="color:red; font-weight:bold;">The Case is Disposed!!!</th></tr>
                            <?php } ?>       
                           
                                <tr align="center">
                                    <th colspan="4" style="color:blue; font-weight:bold;">
                                    <?php echo (!empty($details['pet_name']) && !empty($details['res_name'])) ? $details['pet_name']."<span style='color:black'> - Vs - </span>".$details['res_name'] : ''; ?>

                                    </th>
                                </tr>
                                
                                <?php 
                                $category = '';
                                foreach($multiple_category as $row_cate){
                                    $category .= $row_cate['sub_name1'].'-'.$row_cate['sub_name2'].'-'.$row_cate['sub_name3'].'-'.$row_cate['sub_name4'].'<br>';
                                }?>
                                
                                <tr align="center"><th colspan="4"><strong><i>Category:</i> <span style="font-size:14px;color:brown"><?php echo $category; ?></span></strong></th></tr>

                                <tr>
                                    <th colspan="4" style="text-align: center;font-size: 14px; font-weight:bold;">
                                        <?php
                                            if(!empty($main_case)) {
                                                if($main_case['conn_key'] == $dno){
                                                    echo "This is Main Diary No";
                                                    ?>
                                
                                                    <?php
                                                } else {
                                                    echo "This is Connected Diary No, Main Diary No is <span style='color:red'>" . substr($main_case['conn_key'], 0, -4) . '/' . substr($main_case['conn_key'], -4) . "</span>";
                                                    $dno = $main_case['conn_key'];
                                                    ?>
                                                    <?php
                                                }
                                            }
                                        ?>
                                        <input type="hidden" id="fil_hd" value="<?php echo $dno; ?>">
                                     </th>
                                </tr>
                                <tr><td>Filing Date:</td>
                                    <td>
                                        <?php if(!empty($details['diary_no_rec_date'])) echo date('d-M-Y',strtotime($details['diary_no_rec_date'])).' on '.date('h:i A',strtotime($details['diary_no_rec_date'])); else echo '--';?>
                                    </td>
                                    <td>Registration Date:</td><td><?php if(!empty($details['fil_dt'])) echo date('d-M-Y',strtotime($details['fil_dt'])).' on '.date('h:i A',strtotime($details['fil_dt'])); else echo '--';?></td>
                                </tr>
                                <tr><td>Last Order:</td><td>
                                    <?php echo !empty($details['lastorder']) ? $details['lastorder'] : '--'; ?>
                                </td></tr>
                                <?php
                                    if(isset($details['c_status']) && $details['c_status'] == 'P') {
                                        if($details['mainhead'] == 'M'){
                                            $mainhead_content = "<span style='color:blue;'>Misc. Stage</span>";
                                        } else{
                                            $mainhead_content = "<span style='color:red;'>Regular Stage</span>";
                                        }
                                    ?>     
                                     <?= csrf_field() ?>           
                                <tr><td colspan="4"></td></tr>
                                <tr>
                                <tr align="center"><th colspan="4"><?=$mainhead_content;?> <input type="button" value="Add in consent for physical hearing" name="savebutton" id="savebutton" />
                                </tr>
                                <?php } ?>
                            </table>    
                        </div>
                    </div><!-- Main content end -->
                </div> <!-- /.card -->
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<script src="<?php echo base_url('listing/physical_hearing/addCase.js'); ?>"></script>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });
</script>