    <?php
    $attribute = array('class' => 'form-horizontal','name' => 'frmDispatch', 'id' => 'frmDispatch', 'autocomplete' => 'off');
    echo form_open(base_url('#'), $attribute);
    ?>
    <?php
    if(!empty($receiptData)) {
        ?>
        <div class="form-group col-sm-6 pull-right">
            <label>&nbsp;</label>
            <button type="button" id="btnDispatchTop" name="btnDispatch" class="btn btn-primary pull-right" onclick="return doDispatch();" ><i class="fa fa-fw fa-download"></i>&nbsp;Dispatch Dak</button>
        </div>
        <!--<table id="reportTable1" class="table table-striped table-hover">-->
        <table id="tblDispatchDak" class="table table-striped table-hover">
            <thead>
            <tr>
                <th width="4%">#</th>
                <th width="8%">Diary Number</th>
                <th width="10%">Sent To</th>
                <th width="15%">Postal Type, Number & Date</th>
                <th width="20%">Sender Name & Address</th>
                <th width="10%"><label><input type="checkbox" id="allCheck" name="allCheck" onclick="selectallMe()">Select All</label></th>
            </tr>

            </thead>
            <tbody>
            <?php
            $s_no=1;
            foreach ($receiptData as $case)
            {
                ?>
                <tr>
                    <td><?=$s_no?></td>
                    <td><?=$case['diary']?></td>
                    <td>
                        <?=$case['address_to']?>
                    </td>
                    <td><?php
                        echo $case['postal_type'].'&nbsp;'.$case['postal_number'].'&nbsp;'.date("d-m-Y", strtotime($case['postal_date']));
                        ?>
                    </td>
                    <td><?php
                        echo $case['sender_name'].'&nbsp;'.$case['address'];
                        ?>
                    </td>
                    <?php
                    $diarynumber="";
                    if(!empty($case['diary_number'])){
                        $diarynumber=$case['diary_number'];
                        $diarynumber="Diary No. ".substr($diarynumber, 0, -4)."/".substr($diarynumber, -4)."<br/>".$case['reg_no_display'];;
                    }
                    ?>

                    <td>
                        <?php if(!empty($case['dispatched_on']) && empty($case['action_taken'])){ ?>
                            <?=$case['dispatched_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($case['dispatch_on']))?>
                        <?php }
                        else{?>
                            <input type="checkbox" id="daks" name="daks[]" value="<?=$case['id']?>">
                        <?php  }?>
                    </td>
                </tr>
                <?php
                $s_no++;
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    else{
        ?>
            <br>
        <div class="form-group col-sm-12">
            <h4 class="text-danger">&nbsp;No Record Found!!</h4>
        </div>

    <?php
      }
    ?>
<?php form_close();?>
