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
                                        <div class="card-header bg-info text-white font-weight-bolder"> Judge Category Bulk Close </div>
                                        <div class="card-body">
                                            <?php
                                            $attributes = 'class="row g-3"';
                                            $action = base_url('Listing/JudgeMaster/update_judge_bulkcategory/'.session()->get('login')['usercode'].'');
                                            echo form_open($action, $attributes);
                                                echo csrf_field();
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="well">
                                                        <div class="row">
                                                            <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode'] ?>"/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="judge1">Judge</label>
                                                                <select class="form-control cus-form-ctrl" id="judge" name="judge" placeholder="judge" required="required">
                                                                    <option value="">Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j1){
                                                                        echo '<option value="'.$j1['jcode'].'"'.(isset($_POST['judge']) && $_POST['judge'] == $j1['jcode'] ? 'selected="selected"' : ''). '>'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="m_f">Misc/Regular</label><br/>
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="M" <?= (isset($matters) && ($matters != '')) ? (($matters == 'M') ? 'checked' : '') : 'checked'; ?>  required > Miscelleneous &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="F" <?= (isset($matters) && ($matters == 'F')) ? 'checked' : ''; ?> > Regular &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="B" <?= (isset($matters) && ($matters == 'B')) ? 'checked' : ''; ?> > Both
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3"><button name="submit" type="submit" id="" class="btn bg-olive btn-flat pull-right" ><i class="fa fa-save"></i> Submit</button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                            <div class="card-body">
                                                <?php
                                                if(isset($_POST['submit']) && !empty($_POST)) {
                                                    if(isset($judge_details) && !empty($judge_details) && is_array($judge_details)) 
                                                    {
                                                        foreach ($judge_details as $result)
                                                        {
                                                            $jname      = $result['jname'];
                                                            $jcode      = $result['jcode'];
                                                            $from_dt    = $result['from_dt'];

                                                            if($matters == 'M')
                                                                $mattersList    = "Miscelleneous Matters";
                                                            else if($matters == 'F')
                                                                $mattersList    = "Regular Matters";
                                                            else if($matters == 'B')
                                                                $mattersList    = "All Matters";
                                                        }
                                                        ?>
                                                        <table class="table">
                                                            <thead>
                                                                <tr align="center">
                                                                    <h1 align="center"><?php  echo $jname;?></h1>
                                                                    <h3 align="center"><?php echo '('.$mattersList.')';?></h3>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><label for="toDate">To Date</label> <input type="text" class="form-control datepick" value="" name="toDate" id="toDate" required></td>
                                                                    <td><br><button type="button" id="btn-update" class="btn bg-olive btn-flat" onclick="update();"><i class="fa fa-save"></i> Update</button></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <input type='hidden' value='<?php echo $jcode?>' id="jcode" name="jcode">
                                                        <input type='hidden' value='<?php echo date('d-m-Y', strtotime($from_dt));?>' id="from_dt" name="from_dt">
                                                        <?php
                                                        // }
                                                    } elseif(strcmp('No', $judge_details) == 0) {
                                                        echo "Data not available!";
                                                    }
                                                }                                            
                                            ?>
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
            // $('#reportTable1').DataTable().destroy();
            //$('#reportTable1 tbody').empty();
            //getAllNotices();
            //$("#display").hide();
            $(function () {
                $('.datepick').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
            });
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
        
        async function update() {
            await updateCSRFTokenSync();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var toDate=$('#toDate').val();
            var usercode=$('#usercode').val();
            var judge =$("#jcode").val();
            var from_dt=$("#from_dt").val();
            // var mf = $("input[name=mf]:checked").val();
            var mf='<?php echo $matters; ?>';
            var endDate = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
            var startDate = new Date(from_dt.split('-')[2], from_dt.split('-')[1] - 1, from_dt.split('-')[0]);
            if((startDate!='' && endDate!='') && (startDate>endDate)) {
                alert("From date " + from_dt + " cannot be greater than to date " + toDate);
                return false;
            } else if (!isEmpty(toDate) && !isEmpty(judge) && !isEmpty(usercode)) {
                await $.post("<?php echo base_url('Listing/JudgeMaster/updateprocess_judge_bulkcategory');?>", {
                    CSRF_TOKEN:csrf,
                    judge:judge,
                    toDate: toDate,
                    usercode: usercode,
                    mf:mf
                }, function (result) {
                    console.log(result);
                    if (!alert(result)) {
                        location.reload();
                    }
                }).fail(function() {
                    alert("There is some problem. Please contact Computer-Cell.");
                });
            } else if(toDate == '') {
                alert("Please Enter To Date");
                $( "#toDate" ).focus();
                return false;
            }
        }
    </script>
<?//=view('sci_main_footer') ?>