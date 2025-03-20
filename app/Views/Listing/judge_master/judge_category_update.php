<?php ini_set('memory_limit', '256M');
 ?>
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
                                        <div class="card-header bg-info text-white font-weight-bolder"> Judge Category Close </div>
                                        <div class="card-body">
                                            <?php
                                            $attributes = 'class="row g-3"';
                                            // $action = htmlspecialchars($_SERVER['PHP_SELF']);
                                            $action = base_url('Listing/JudgeMaster/judgeCategoryUpdate/'.session()->get('login')['usercode'].'');
                                            echo form_open($action, $attributes);
                                                echo csrf_field();
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="well">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="judge1">Judge</label>
                                                                <select class="form-control cus-form-ctrl" id="judge" name="judge" placeholder="judge">
                                                                    <option value="" selected disabled>Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j1){
                                                                        // if ($judge[0]==$j1['jcode']){
                                                                        //     echo '<option value="'.$j1['jcode'].'" selected="selected">'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                        // }
                                                                        // else
                                                                        $sel = isset($_POST['judge']) ? (($j1['jcode'] == $_POST['judge']) ? 'selected' : '') : '';
                                                                        echo '<option value="'.$j1['jcode'].'" '.$sel.' >'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label for="m_f">Misc/Regular</label><br/>
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="M" checked> Miscelleneous &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="F"> Regular &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <!-- <input type="radio" name="mf" id="mf" class="rd_active" value="B"> Both-->
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3"><button type="submit" name="submit" id="btn-update" class="btn bg-olive btn-flat pull-right" ><i class="fa fa-save"></i> Submit</button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php echo form_close(); ?>
                                            <?php
                                            if(isset($_POST['submit']) && !empty($_POST)) {
                                                if(isset($judge_details) && !empty($judge_details) && is_array($judge_details) ) {
                                                    foreach ($judge_details as $result)
                                                        $jname=$result['jname'];
                                                    if($matters=='M')
                                                        $mattersList="Miscelleneous Matters";
                                                    else if($matters=='F')
                                                        $mattersList="Regular Matters";
                                                    else if($matters=='B')
                                                        $mattersList="All Matters";
                                                    ?>
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr align="center">
                                                                <h1 align="center"><?php  echo $jname;?></h1>
                                                                <h3 align="center"><?php echo '('.$mattersList.')';?></h3>
                                                            </tr>
                                                            <tr></tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Subject Category</th>
                                                                <th>Priority</th>
                                                                <th>To Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $i=0;
                                                            foreach ($judge_details as $result) {
                                                                $i++;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $i;?></td>
                                                                    <td hidden id="td_<?php echo $i;?>" name="td_<?php echo $i;?>" ><?php echo $result['id']; ?></td>
                                                                    <td width="50%"><?php echo $result['catg'];?></td>
                                                                    <td><input type="text" value=<?php echo $result['priority']; ?> name="priority_<?php echo $i; ?>" id="priority_<?php echo $i; ?>"></td>
                                                                    <td><input type="text" class="form-control datepick" value="<?php if($result['to_dt']!= NULL) echo date('d-m-Y',strtotime($result['to_dt'])); else echo '';?>" name="toDate_<?php echo $i;?>" id="toDate_<?php echo $i;?>"></td>
                                                                    <td><button type="submit" id="btn-update" class="btn bg-olive btn-flat pull-right" onclick="update(<?php echo $i; ?>);"><i class="fa fa-save"></i> Update</button></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="rows" id="rows" value="<?php echo $i; ?>">
                                                    <?php
                                                } 
                                                else if(strcmp('No',$judge_details) == 0) {
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
        function update($id) 
        {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var priority=$('#priority_'+$id).val();
            var idDb=$('#td_' + $id).text();
            var toDate=$('#toDate_'+$id).val();
            var rows=$('#rows').val();
            var mf='<?php echo $matters;?>';
            if (!isEmpty(priority) && !isEmpty(idDb)) {
                $.post("<?php echo base_url('Listing/JudgeMaster/update_judge_category'); ?>", {
                    CSRF_TOKEN:csrf,
                    priority: priority,
                    id: idDb,
                    toDate: toDate,
                    mf:mf
                }, function (result) {
                    console.log(result)
                    if (!alert(result)) {
                        location.reload();
                        updateCSRFToken();
                    }
                });
            }
            updateCSRFToken();
        }
    </script>
<?=view('sci_main_footer') ?>