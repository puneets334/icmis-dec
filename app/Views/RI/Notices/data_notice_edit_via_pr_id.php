<?php
if (isset($case_result) && sizeof($case_result) > 0 && is_array($case_result)) {
?>
    <h3 style="text-align: center;"> Process ID Record </h3>
    <div class="table-responsive">
        <table id="reportTable" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th rowspan='1'>SNo.</th>
                    <th rowspan='1'>Case No.</th>
                    <th rowspan='1'>Name & Address</th>
                    <th rowspan='1'>Dispatch By</th>
                    <th rowspan='1'>Barcode</th>
                    <th id="th_edit"><i class="fa fa-edit"></i></th>
                    <th id="th_save" style="display: none"><i class="fa fa-save"></i></th>
                    <th><i class="fa fa-trash"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($case_result as $result) {
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $result['caseno']; ?></td>
                        <td><?php echo $result['name'] . '<br/> R/o. ' . $result['address'] . ', ' . $result['ds_name'] . ', ' . $result['st_name']; ?></td>
                        <td><?php echo $result['emp_name'] . '@Empid ' . $result['empid']; ?></td>
                        <td><input type="text" id="txt_barcode" name="txt_barcode" placeholder="Barcode" disabled value="<?php echo $result['barcode']; ?>" /></td>
                        <td id="td_edit"><a data-toggle="tooltip" title="Edit" id="btn_edit" onclick="get_btn_edit()"><i class="fas fa-edit"></i></a></td>
                        <td id="td_save" style="display: none"><a data-toggle="tooltip" title="Save" id="button_id_save" onclick="update_barcode('<?php echo $result['id']; ?>','<?php echo $result['process_id']; ?>','<?php echo $result['pid_year']; ?>')"><i class="fas fa-save"></i></a></td>

                        <td><a id="button_id_delete" data-toggle="tooltip" title="Delete" onclick="delete_record('<?php echo $result['id']; ?>','<?php echo $result['process_id']; ?>','<?php echo $result['pid_year']; ?>')"><i class="fa fa-trash"></i></a></td>

                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else { ?>
   <span class="text-danger">Record Not Found.</span> 
    </div>
<?php  }?>