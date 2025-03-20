 <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
<?php

$valid_sitting_judges = $judges;
$if_non_sitting_exist = false;
$valid_non_sitting_judges = array();
if (count($sittingJudges) > 0) {
    $valid_sitting_judges = $sittingJudges;
}
if (count($nonSittingJudges) > 0) {
    $valid_non_sitting_judges = $nonSittingJudges;
    $if_non_sitting_exist = true;
}

?>
<div class="row">
    <div class="form-group col-md-3">
        <label for="from[]">Hon'ble Judges</label>
        <select name="from[]" id="undo_redo" class="form-control" size="8" multiple="multiple">
            <?php
            foreach ($valid_sitting_judges as $judge) {
                if ($judge['jtype'] == 'J') {
                    echo '<option style="margin-bottom:10px" value="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <div class="col-md-2">
        <br><br><br>
        <button type="button" id="undo_redo_rightSelected" class="btn btn-primary btn-block">
        <i class="fas fa-chevron-right"></i>
            <!--<i class="glyphicon glyphicon-chevron-right"></i>-->
        </button>
        <button type="button" id="undo_redo_leftSelected" class="btn btn-primary btn-block">
        <i class="fas fa-chevron-left"></i>
            <!--<i class="glyphicon glyphicon-chevron-left"></i></button>-->
    </div>

    <div class="form-group col-md-3">
        <label for="to[]">Not Sitting Hon'ble Judges</label>
        <select name="to[]" id="undo_redo_to" class="form-control" size="8" multiple="multiple">
            <?php
            if ($if_non_sitting_exist) {
                foreach ($valid_non_sitting_judges as $judge) {
                    if ($judge['jtype'] == 'J') {
                        echo '<option style="margin-bottom:10px" value="' . $judge['jcode'] . '">' . $judge['first_name'] . " " . $judge['sur_name'] . '</option>';
                    }
                }
            }
            ?>
        </select>
    </div>
</div>
<div class="form-group col-sm-12">
    <label for="from" class="text-right">&nbsp;</label>
    <button type="button" id="btnSaveAndNext" class="btn btn-success form-control"
        onclick="return check();">SAVE & NEXT
    </button>
</div>
<script>
    $(function() {
        $('#undo_redo').multiselect({
            keepRenderingSort: true,
            sort: true
        });
    });
</script>