<?= view('header') ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container-fluid m-0 p-0">
                            <div class="row clearfix mr-1 ml-1 p-0">
                                <div class="col-12 m-0 p-0">
                                    <p id="show_error"></p>
                                    <div class="card">
                                        <div class="card-header bg-info text-white font-weight-bolder"> Judge Category Bulk Transfer </div>
                                        <div class="card-body">
                                            <?php
                                            $attributes = 'class="row g-3"';
                                            // $action = htmlspecialchars($_SERVER['PHP_SELF']);
                                            $action = base_url('Listing/JudgeMaster/transfer_insert_category');
                                            echo form_open($action, $attributes);
                                                echo csrf_field();
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="container well">
                                                        <div class="row">
                                                            <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode'] ?>"/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <!--<label><input type="radio" class="rd_active" name="rd_active" value="A" checked/>Active Category</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                                                                <!--<label><input type="radio" class="rd_active" name="rd_active" value="I"/>In Active Category</label>-->
                                                            </div>
                                                        </div>
                                                        <div id="active">
                                                            <div style="margin-top: 20px" class="row">
                                                                <div class="col-md-6">
                                                                    <label for="judge1">Transfer From</label>
                                                                    <select class="form-control cus-form-ctrl" id="judge" name="judge" placeholder="judge" required="required">
                                                                        <option value="">Select Judge</option>
                                                                        <?php
                                                                        foreach($judge as $j1){
                                                                            echo '<option value="'.$j1['jcode'].'"'.(isset($_POST['judge']) && $_POST['judge'] == $j1['jcode'] ? 'selected="selected"' : ''). '>'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="m_f">Misc/Regular</label><br/>
                                                                    <input type="radio" name="mf" id="mf" class="rd_active" value="M" checked> Miscelleneous &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input type="radio" name="mf" id="mf" class="rd_active" value="F"> Regular &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input type="radio" name="mf" id="mf" class="rd_active" value="B"> Both
                                                                </div>
                                                            </div>
                                                            <div style="margin-top: 20px" class="row">
                                                            <div class="col-md-6">
                                                                    <label for="judge1">Transfer To</label>
                                                                    <select class="form-control cus-form-ctrl" id="judge1" name="judge1" placeholder="Judge" required="required">
                                                                        <option value="">Select Judge</option>
                                                                        <?php
                                                                        foreach($judge as $j1){
                                                                            echo '<option value="'.$j1['jcode'].'"'.(isset($_POST['judge']) && $_POST['judge'] == $j1['jcode'] ? 'selected="selected"' : ''). '>'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- <div id="dt_show" style="margin-top: 20px" class="row" style="">
                                                                <div class="col-md-6">
                                                                    <label for="from_date" id="lbl_from_date">From Date:</label>
                                                                    <input type="text" id="from_date" value="<?php /*if(isset($_POST['from_date'])) echo date("d-m-Y", strtotime(strtr($param[3],'/','-')));*/?>" name="from_date" class="form-control datepick"  placeholder="From Date" required="required">
                                                                </div>
                                                            </div>-->
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3"><button type="submit" id="btn-update" onclick="transfer();" class="btn bg-olive btn-flat pull-right" ><i class="fa fa-save"></i> Submit</button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php echo form_close(); ?>
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
    <!--<input type='hidden' value='<?php /*echo $jcode*/?>' id="jcode" name="jcode">
    <input type='hidden' value='<?php /*echo date('d-m-Y', strtotime($from_dt));*/?>' id="from_dt" name="from_dt">-->
    <script>
        $(document).ready(function() {
            /*if($("input[name=rd_active]:checked").val()=='I')
                $('#dt_show').show();
            else
                $('#dt_show').hide();*/
            $(function () {
                $('.datepick').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
            });
            /*$("input[name='rd_active']").click(function() {
                var searchValue = $(this).val();
                if(searchValue=='I')
                    $('#dt_show').show();
                else
                    $('#dt_show').hide();
            });*/
        });
        function isEmpty(obj) {
            if (obj == null) return true;
            if (obj.length > 0)    return false;
            if (obj.length === 0)  return true;
            if (typeof obj !== "object") return true;
            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }
            return true;
        }
        function validation() {
            var judge_from= $("#judge").prop('selectedIndex');
            var judge_to= $("#judge1").prop('selectedIndex');
            // var val_active = $("input[name=rd_active]:checked").val();
            if(judge_from==judge_to) {
                alert("Both Judges cannot be same.");
                return false;
            }
            /*else if (val_active == '' || typeof val_active === 'undefined') {
                alert("Please select Category");
                $("input[name=rd_active]").focus();
                return false;
            }*/
            else if (judge_from == '') {
                alert("Please select transfer from Judge.");
                $("#judge").focus();
                return false;
            } else if (judge_to == '') {
                alert("Please select transfer to Judge.");
                $("#judge1").focus();
                return false;
            } else
                return true;
        }
        function transfer() {
            if(validation()==true) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var csrf = $("input[name='CSRF_TOKEN']").val();
                var usercode = $('#usercode').val();
                var judge_from = $('#judge :selected').val();
                var judge_to = $('#judge1 :selected').val();
                var mf = $("input[name=mf]:checked").val();
                if (!isEmpty(judge_from) && !isEmpty(judge_to) && !isEmpty(usercode) ) {
                    $.post("<?php echo base_url('Listing/JudgeMaster/transfer_insert_category'); ?>", {
                        CSRF_TOKEN:csrf,
                        judge_from: judge_from,
                        judge_to: judge_to,
                        mf:mf,
                        usercode: usercode
                    }, function (result) {
                        if (!alert(result)) {
                            location.reload();
                            updateCSRFToken();
                        }
                    });
                }
            }
            updateCSRFToken();
        }
    </script>
<?=view('sci_main_footer') ?>