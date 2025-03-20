
<?php
$attribute = array('class' => 'form-horizontal ', 'name' => 'case_trap_form', 'id' => 'case_trap_form', 'autocomplete' => 'off');
echo form_open(base_url('#'), $attribute);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                REPORT OF WORK DONE OF DA
                <span style="color: #35b9cd">
                <?php
                $sec='';
                $dcmis_usertype=session()->get('login')['usertype'];
                if($dcmis_usertype==1){
                    echo "FOR ALL DA";
                }
                else{
                    echo "FOR SECTION ";
                    if(!empty($sections)){
                        foreach ($sections as $row){
                            $sec .= ','.$row['section_name'];
                        }
                        $sec = ltrim($sec,',');
                        echo $sec;
                    }
                    else
                        echo "YOUR SECTION NOT FOUND";
                }
                ?>
                    </span>
<br/>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="From" class="col-sm-5 col-form-label">FOR THE DATE OF</label>
                            <div class="col-sm-7">
                                <input type="date"  class="form-control" id="date" name="date" placeholder="Select Date" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group row">
                            <label for="To" class="col-sm-5 col-form-label">Select Type</label>
                            <div class="col-sm-7">
                                <select name="ddl_all_blank" id="ddl_all_blank" class="form-control" required>
                                    <option value="1">All</option>
                                    <option value="2">Blank</option>
                                    <option value="3">Atleast One</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group row">
                            <button type="submit" name="case_trap_search" id="case_trap_search" class="case_trap_search btn btn-primary" value="Search"> Search </button>
                      </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<?= form_close() ?>


<div id="result_data"></div>
</div>
</div>
</div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<script>
    $('#case_trap_form').on('submit', function() {
        var validateFlag = true;
        var form_data = $(this).serialize();
        var diary_no = $("#diary_no").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Reports/Filing/Work_done'); ?>",
            data: form_data,
            beforeSend: function() {
                $('.case_trap_search').val('Please wait...');
                $('.case_trap_search').prop('disabled', true);
            },
            success: function(data) {
                $('.case_trap_search').prop('disabled', false);
                $('.case_trap_search').val('Search');
                $("#result_data").html(data);

                updateCSRFToken();
            },
            error: function() {
                updateCSRFToken();
            }

        });
        return false;
    });
</script>

