
        <div class="row ">
            <div class="col-md-4">
                <div class="form-group row">
                    <label  class="col-sm-5 col-form-label">Court Type <span class="text-red">*</span> :</label>
                    <div class="col-sm-7">
                        <select name="ddl_court" id="ddl_court" class="form-control">
                            <option value="">Select State</option>
                            <?php foreach ($court_type_list as $row) {?>
                                <option value="<?php echo $row['id'] ?>" <?php if($row['id']=='1') { ?> selected="selected" <?php } ?>><?php echo $row['court_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label  class="col-sm-5 col-form-label">State <span class="text-red">*</span> :</label>
                    <div class="col-sm-7">
                        <select name="ddl_st_agncy" id="ddl_st_agncy" class="form-control">
                            <option value="">Select State</option>
                            <?php
                            foreach ($state as $row) {
                                if (isset($row['cmis_state_id'])){
                                    echo'<option value="' . sanitize(($row['cmis_state_id'])) . '">' . sanitize(strtoupper($row['state_name'])) . '</option>';
                                }
                            }
                            ?>
                            <option value="">None</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label  class="col-sm-5 col-form-label">Bench <?php if ($component_type!='H'){?><span class="text-red">*</span><?php }?>:</label>
                    <div class="col-sm-7">
                        <select name="ddl_bench" id="ddl_bench" class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">Case Type<?php if ($component_type!='H'){?><span class="text-red">*</span><?php }?>:</label>
                    <div class="col-sm-7">
                        <select name="ddl_nature" id="ddl_nature" class="form-control">
                            <option value="">Select case type</option>

                        </select>
                    </div>

                </div>
            </div>

             <?php if ($component_type=='H'){?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="caveat_number" class="col-sm-5 col-form-label">Case No</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="txt_ref_caseno" name="txt_ref_caseno" placeholder="Case No" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                    </div>
                    <div class="col-sm-3">
                        <?php $year = 1950; $current_year = date('Y'); ?>
                        <select name="ddl_ref_caseyr" id="ddl_ref_caseyr" class="form-control" style="border: 1px solid red;padding: 0px;">
                            <option value="">Year*</option>
                            <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                <option><?php echo $x; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="txt_order_date" class="col-sm-5 col-form-label">Order Date</label>
                    <div class="col-sm-7">
                        <input type="date" class="form-control" id="txt_order_date" name="txt_order_date" >
                    </div>
                </div>
            </div>
            <?php }?>
        </div>

    <script>
        $(document).ready(function() {
            $(document).on('change', '#ddl_court', function() {

                var idd= $(this).val();

                if(idd=='4')
                {
                    $('#ddl_st_agncy').val('490506');

                    get_benches('1');
                }
            });
            $(document).on('change', '#ddl_st_agncy,#ddl_court', function() {
                get_benches('0');
                get_casetype();
            });
            $(document).on('change', '#ddl_bench', function() {
                get_casetype();
            });
        });
        function  get_casetype(){
            var ddl_court=$('#ddl_court').val();
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: '<?php echo base_url('Common/Ajaxcalls/get_lc_hc_casetype'); ?>',
                cache: false,
                async: true,
                data: {ddl_court:ddl_court,ddl_st_agncy:ddl_st_agncy,CSRF_TOKEN: CSRF_TOKEN_VALUE},
                type: 'GET',
                success: function(data, status) {
                    $('#ddl_nature').html(data);
                    updateCSRFToken();
                },
                error: function() {
                    updateCSRFToken();
                }

            });

        }
        function get_benches(str)
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var ddl_st_agncy = $('#ddl_st_agncy').val();
            var ddl_court=$('#ddl_court').val();
            if(ddl_st_agncy!='' && ddl_court!='')

            {

                $.ajax({
                    url: '<?php echo base_url('Common/Ajaxcalls/get_bench'); ?>',
                    cache: false,
                    async: true,
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: ddl_st_agncy, court_type: ddl_court},
                    type: 'GET',
                    success: function(data, status) {

                        $('#ddl_bench').html(data);
                        if(str==1)
                        {
                            $('#ddl_bench').val('10000');
                            $('#ddl_st_agncy').attr('disabled',true);
                        }
                        else
                        {
                            $('#ddl_bench').val('');
                            $('#ddl_st_agncy').attr('disabled',false);
                        }
                        updateCSRFToken();
                    },
                    error: function() {
                        updateCSRFToken();
                    }

                });
            }
        }
    </script>