<?= view('header') ?>
<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Case Alloted</h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form method="POST">
                                            <?= csrf_field() ?>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="">User Type</label>
                                                        <select name="ddl_users" id="ddl_users" class="form-control">
                                                            <?php if ($usercode == '1' || $r_section == '30' || $r_usertype == '4') : ?>
                                                                <option value="">Select</option>
                                                            <?php endif; ?>

                                                            <?php foreach ($fil_trap_users as $user) : ?>

                                                                <option value="<?= $user['id'] ?>"><?= $user['type_name'] ?></option>
                                                            <?php endforeach; ?>

                                                            <?php if ($usercode == '1' || $r_section == '30' || $r_usertype == '4' || $r_section == '20') : ?>
                                                                <option value="9796">Scaning</option>
                                                            <?php elseif ($usercode == '9796') : ?>
                                                                <option value="9796">Scaning</option>
                                                            <?php elseif ($r_user_type == '107') : ?>
                                                                <option value="107">IB-Extention</option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">From Date</label>
                                                        <input type="text" name="txt_frm_dt" id="txt_frm_dt" class="dtp form-control" maxlength="10" size="9" value="<?= date('d-m-Y') ?>" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">To Date</label>
                                                        <input type="text" name="txt_to_dt" id="txt_to_dt" class="dtp form-control" maxlength="10" size="9" value="<?= date('d-m-Y') ?>" />
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <input type="button" name="btn_submit" id="btn_submit" value="Submit" class="btn btn-primary mt-5" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="dv_data">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $("#reportTable1").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf", ]

        });

        $(document).on('click', '#btn_submit', function()
        {
            let ddl_users = $('#ddl_users').val();
            let txt_frm_dt = $('#txt_frm_dt').val();
            let txt_to_dt = $('#txt_to_dt').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if (ddl_users == '')
            {
                alert("Please Select User Type");
            }
            else
            {
                $.ajax({
                    url: '<?php echo base_url('Listing/Report/get_filing'); ?>',
                    cache: false,
                    async: true,
                    beforeSend: function() {
                        $('#dv_data').html('<table width="100%" style="margin: 0 auto;"><tr><td style="text-align: center;"><img src="../../images/load.gif"/></td></tr></table>');
                    },
                    data: {
                        ddl_users: ddl_users,
                        txt_frm_dt: txt_frm_dt,
                        txt_to_dt: txt_to_dt,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    type: 'POST',
                    success: function(data, status) {
                        $('#dv_data').html(data);
                        updateCSRFToken();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                        updateCSRFToken();
                    }
                });
            }
                
              
        });
    });


    function get_rec(str)
    {
        let sp_split = str.split('_');
        let r_sp_split = sp_split[1];
        let l_sp_split = sp_split[0];
        let hd_nm_id = $('#hd_nm_id' + sp_split[1]).val();
        let txt_frm_dt = $('#txt_frm_dt').val();
        let txt_to_dt = $('#txt_to_dt').val();
        let ddl_users = $('#ddl_users').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       
        // Fetch CSRF token from the server
        $.ajax({
                    url: '<?php echo base_url('Listing/Report/get_fil_record'); ?>',
                    cache: false,
                    async: true,
                    beforeSend: function()
                    {
                        $('#ggg').html('<table width="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
                    },
                    data: {
                        hd_nm_id: hd_nm_id,
                        r_sp_split: r_sp_split,
                        l_sp_split: l_sp_split,
                        txt_frm_dt: txt_frm_dt,
                        txt_to_dt: txt_to_dt,
                        ddl_users: ddl_users,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    },
                    type: 'POST',
                    success: function(data, status) {
                        updateCSRFToken();
                        $('#ggg').html(data);
                        $('input[name="' + csrfName + '"]').val(data.newToken);
                    },
                    error: function(xhr) {
                        updateCSRFToken();
                        alert("Error: " + xhr.status + " " + xhr.statusText);
                    }
                });
            
    }
</script>