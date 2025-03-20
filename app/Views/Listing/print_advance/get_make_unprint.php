<?php if (!empty($result)) { ?>
    <div class="mrgB20" style="text-align:center;">
        Record Found<br>
        Be Sure before making unprint
        <!--Be sure making unprint will delete all drop notes and header footer notes in this regard.<br/>-->
        <br />
        <div>
            <label style="font-weight: bold; color: red">Select For Removing</label>
            <input type="checkbox" name="dropNote" id="dropNote" value="d" checked> Drop Note
            <input type="checkbox" name="headerFooter" id="headerFooter" value="h" checked> Header/Footer
        </div>
        <br />

        <input name="del_btn" type="button" id="del_btn" value="Make Unprint" class="btn btn-primary">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <!--<input name="del_btn_2" type="button" id="del_btn_2" value="Make Unprint and not to be deleted any thing else" >-->
        <input name="del_cl_id" type="hidden" id="del_cl_id" value="<?php echo $result['id']; ?>">
    </div>
<?php
} else { ?>
    <div class="mrgB20" style="text-align:center;" class="p-4">
        <h3 class="text-danger">No Record Found</h3>
    </div>
<?php } ?>