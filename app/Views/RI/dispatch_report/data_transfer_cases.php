<?php if(isset($result) && count($result) > 0): ?>
    <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align custom-table" cellpadding="20px" cellspacing="10px">
        <thead>
              <tr>
                <th>SNo.</th>
                <th>Check</th>
                <th>Diary No.</th>
                <th>Remarks</th>
            </tr>
</thead>
<tbody>
        <?php 
        $sno = 1;
        foreach($result as $row): 
        ?>
        <tr>
            
            <tr>
                <td><?php echo $sno; ?></td>
                <td><input type="checkbox" class="cl_allot_case" name="chk_cases<?php echo $sno; ?>" id="chk_cases<?php echo $sno; ?>" value="<?php echo $row['diary_no']; ?>"/>
                </td>
                <td><?php echo substr($row['diary_no'], 0, strlen($row['diary_no']) - 4) . '-' . substr($row['diary_no'], -4) ?></td>
                <td><?php echo $row['remarks'] ?></td>
            </tr>
        </tr>
        <?php 
        $sno++; 
        endforeach; ?>
</tbody>
    </table>


    <table width="100%" border="1" class="table_tr_th_w_clr c_vertical_align custom-table" cellpadding="20px" cellspacing="10px">
    <thead>

        <tr>
        <th>SNo.</th>
        <th>Check</th>
        <th>User.</th>
        <th>Alot Cases</th>
        </tr>
        </thead>
<tbody>
        <?php 
        $s_a = 1;
        foreach($emp_id as $row1): 
        ?>
        <tr>
            
           
            <td>
                <?php
                echo $s_a;
                ?>
            </td>
            <td>
                <input type="checkbox" class="cl_users" name="cl_users_m<?php echo $s_a; ?>" id="cl_users_m<?php echo $s_a; ?>" value="<?php echo $row1['empid']; ?>"/>
            </td>
            <td>
                <?php
                echo $row1['name'];
                ?>
            </td>
            <td>
                <input type="text" name="txt_tot_alt_case<?php echo $s_a; ?>" id="txt_tot_alt_case<?php echo $s_a; ?>" size="3"/>
            </td>
        </tr>
       
        <?php 
         $s_a++; 
        endforeach;
        ?>
        </tbody>
    </table>
    <div style="text-align: center;clear: both">
    <input type="button" name="btn_transfer" id="btn_transfer" value="Transfer"/>
</div>
<?php else: ?>
    <div style="text-align: center"><b>No Record Found</b></div>
<?php endif; ?>
<script>
$(document).on('click', '#btn_transfer', function() {
   // transferCases();
});

$(document).on('click', '.cl_users, .cl_allot_case', function() {
    updateUserAllocations();
});

function transferCases() {
    let diary_no = getSelectedCases('.cl_allot_case');
    let users = getSelectedUsers('.cl_users');
    if (diary_no.length === 0) {
        alert("Please select at least one Diary No. to be transferred");
    } else if (users.length === 0) {
        alert("Please select at least one User to transfer the case");
    } else {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        const requestData = {
            diary_no: diary_no,
            users: users,
            txt_to_dt: $('#txt_to_dt').val(),
            ddl_users_nm: $('#ddl_users_nm').val(),
            txt_frm_dt: $('#txt_frm_dt').val(),
            ddl_users: $('#ddl_users').val(),
            CSRF_TOKEN:CSRF_TOKEN_VALUE
        };

        $.ajax({
            url: "<?php echo base_url('RI/DispatchController/transfer_cases_user'); ?>",
            type: 'POST',
            cache: false,
            async: true,
            data: requestData,
            beforeSend: function() {
                $('#dv_load').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            success: function(data) {
                $('#dv_load').html(data);
                get_records();
            },
            error: function(xhr) {
                //updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    }
}

function updateUserAllocations() {
    let casesSelected = getSelectedCases('.cl_allot_case').length;
    let users = getSelectedUsers('.cl_users');
    allocateCasesToUsers(casesSelected, users);
}

function getSelectedCases(selector) {
    let selected = [];
    $(selector).each(function() {
        if ($(this).is(':checked')) {
            selected.push($(this).val());
        }
    });
    return selected;
}

function getSelectedUsers(selector) {
    let users = [];
    $(selector).each(function() {
        if ($(this).is(':checked')) {
            const idSuffix = $(this).attr('id').split('cl_users_m')[1];
            users.push($(this).val() + ',' + $('#txt_tot_alt_case' + idSuffix).val());
        }
    });
    return users;
}

function allocateCasesToUsers(totalCases, users) {
    if (users.length === 0) return;

    const casesPerUser = Math.floor(totalCases / users.length);
    const remainder = totalCases % users.length;

    users.forEach((user, index) => {
        const id = user.split(',')[0];
        const allocation = casesPerUser + (index < remainder ? 1 : 0);
        $('#' + id).val(allocation);
    });
}
    </script>