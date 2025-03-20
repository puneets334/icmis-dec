<style>
    table.custom-table thead tr th {
    border-left: #000 1px solid;
    border-radius: 0px!important;
    border-top: #000 1px solid;
    border-bottom: #000 1px solid!important;
}
table.custom-table thead tr th:last-child {
    border-right: #000 1px solid;
}
table.custom-table tbody tr td {
    border-left: #000 1px solid;
    border-radius: 0px!important;
    border-bottom: #000 1px solid;
}
table.custom-table tbody tr td:last-child {
    border-right: #000 1px solid;
}
</style>

<div id="prnnt" style="font-size:12px;">
    <table>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center;">
                    SUPREME COURT OF INDIA<br><br>
                    Listing Date : <?= date('d-m-Y', strtotime($list_dt)); ?> (As on <?= date('d-m-Y h:i:s A'); ?>)
                </th>
            </tr>
        </thead>
    </table>
    <table class="table table-striped custom-table" cellpadding="1" cellspacing="0" border="1">
        <thead>
            <tr>
                <th style="width: 10%;text-align:right;">#</th>
                <th style="width: 10%; text-align:right;">Total</th>
                <th style="width: 10%;text-align:right;">Fresh</th>
                <th style="width: 10%;text-align:right;">Old</th>
            </tr>
        </thead>
        <tr>
            <td style="text-align: right; font-weight: bold;">Advance List</td>
            <td style="text-align: right;">
                <a href="<?php echo base_url();?>/Listing/Report/listing_statistics_details?type=AL&listingdate=<?= $list_dt ?>" target="_blank"><?php if(count($getlistingCount) > 0) {echo $getlistingCount[0]['advance_list']; }  else{ echo '0';} ?></a>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">Advance Elimination</td>
            <td style="text-align: right;">
                <a href="<?php echo base_url();?>/Listing/Report/listing_statistics_details?type=AE&listingdate=<?= $list_dt ?>"
                    target="_blank"><?php if(count($getlistingCount) > 0) {echo $getlistingCount[0]['advance_elimination']; }  else{ echo '0';} ?></a>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">Updated After Advance List</td>
            <td style="text-align: right;">
                <a href="<?php echo base_url();?>/Listing/Report/listing_statistics_details?type=AU&listingdate=<?= $list_dt ?>"
                    target="_blank"><?php if(count($getlistingCountAd) > 0) {  echo $getlistingCountAd[0]['total']; } else { echo '0'; } ?></a>
            </td>
            <td style="text-align: right;"><?php if(count($getlistingCountAd) > 0) { echo $getlistingCountAd[0]['fresh']; } else { echo '0'; } ?></td>
            <td style="text-align: right;"><?php if(count($getlistingCountAd) > 0) { echo  $getlistingCountAd[0]['old']; } else { echo '0'; } ?></td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">Allocated in Final List</td>
            <td style="text-align: right;">
                <a href="<?php echo base_url();?>/Listing/Report/listing_statistics_details?type=FL&listingdate=<?= $list_dt ?>"
                    target="_blank"><?php if(count($getlistingCountFinal) > 0) { echo $getlistingCountFinal[0]['total']; } else { echo '0'; } ?></a>
            </td>
            <td style="text-align: right;"><?php if(count($getlistingCountFinal) > 0) { echo $getlistingCountFinal[0]['fresh']; } else { echo '0'; } ?></td>
            <td style="text-align: right;"><?php if(count($getlistingCountFinal) > 0) { echo $getlistingCountFinal[0]['old']; } else { echo '0'; } if(count($getListedFromAdvance) > 0) {echo " (AL ".$getListedFromAdvance[0]['listed_from_advance'].")"; }  else{ echo '0';} ?></td>
        </tr>
        <tr>
                    <td style="text-align: right; font-weight: bold;">Eliminated in Final List</td>
                    <td style="text-align: right;"><a href="listing_statistics_details?type=FE&listingdate=<?=$list_dt?>" target="_blank">
                        <?php if(count($getEliminatedFinalList) > 0) { echo $getEliminatedFinalList[0]['total']; } else { echo '0'; } ?></a></td>
                    <td style="text-align: right;"> <?php if(count($getEliminatedFinalList) > 0) { echo $getEliminatedFinalList[0]['fresh'];  } else { echo '0'; }?></td>
                    <td style="text-align: right;"> <?php if(count($getEliminatedFinalList) > 0) { echo $getEliminatedFinalList[0]['old']; } else { echo '0'; } ?></td>
       </tr>
       <tr>
                    <td style="text-align: right; font-weight: bold;">Updated After Final List</td>
                    <td style="text-align: right;"><a href="listing_statistics_details?type=FU&listingdate=<?=$list_dt?>" target="_blank">
                        <?php if(count($getUpdatedAfterFinalList) > 0) { echo $getUpdatedAfterFinalList[0]['total'];} else { echo '0'; } ?></a></td>
                    <td style="text-align: right;"><?php if(count($getUpdatedAfterFinalList) > 0) { echo $getUpdatedAfterFinalList[0]['fresh']; } else { echo '0'; }?></td>
                    <td style="text-align: right;"><?php if(count($getUpdatedAfterFinalList) > 0) { echo $getUpdatedAfterFinalList[0]['old'];} else { echo '0'; } ?></td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold;">Allocated in Supplementary List</td>
                    <td style="text-align: right;"><a href="listing_statistics_details?type=SL&listingdate=<?=$list_dt?>" target="_blank">
                        <?php if(count($getAllocatedSupplementaryList) > 0) { echo $getAllocatedSupplementaryList[0]['total'];} else { echo '0'; } ?></a></td>
                    <td style="text-align: right;"><?php if(count($getAllocatedSupplementaryList) > 0) { echo $getAllocatedSupplementaryList[0]['fresh'];} else { echo '0'; } ?></td>
                    <td style="text-align: right;"><?php if(count($getAllocatedSupplementaryList) > 0) { echo $getAllocatedSupplementaryList[0]['old']; } else { echo '0'; }?></td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold;">Eliminated in Supplementary List</td>
                    <td style="text-align: right;"><a href="listing_statistics_details?type=SE&listingdate=<?=$list_dt?>" target="_blank">
                        <?php if(count($getEliminatedSupplementaryList) > 0) { echo $getEliminatedSupplementaryList[0]['total'];} else { echo '0'; } ?></td>
                    <td style="text-align: right;"><?php if(count($getEliminatedSupplementaryList) > 0) { echo $getEliminatedSupplementaryList[0]['fresh'];} else { echo '0'; } ?></td>
                    <td style="text-align: right;"><?php if(count($getEliminatedSupplementaryList) > 0) { echo $getEliminatedSupplementaryList[0]['old']; } else { echo '0'; }?></td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold;">Updated After Supplementary List</td>
                    <td style="text-align: right;"><a href="listing_statistics_details?type=SU&listingdate=<?=$list_dt?>" target="_blank">
                        <?php if(count($getUpdatedAfterSupplementaryList) > 0) { echo $getUpdatedAfterSupplementaryList[0]['total']; } else { echo '0'; }?></td>
                    <td style="text-align: right;"> <?php if(count($getUpdatedAfterSupplementaryList) > 0) { echo $getUpdatedAfterSupplementaryList[0]['fresh']; } else { echo '0'; } ?></td>
                    <td style="text-align: right;"> <?php if(count($getUpdatedAfterSupplementaryList) > 0) { echo $getUpdatedAfterSupplementaryList[0]['old']; } else { echo '0'; }?></td>
                </tr>
                
       <?php
    //    echo '<pre>';
    //    print_r($getEliminatedSupplementaryList);
       ?>
        <!-- Add other rows similarly -->
    </table>
</div>
<div class="col-md-12">
    <div class="text-center">
        <input name="prnnt1" type="button" id="prnnt1" value="Print" style="margin-left: 49%;">
    </div>
</div>