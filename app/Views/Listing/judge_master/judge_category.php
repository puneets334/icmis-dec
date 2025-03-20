<?php
use App\Models\Listing\JudgeMasterModel;
$Judge_Master_model = new JudgeMasterModel();
?>
<?= view('header') ?>
<style>
    #categoryCode {
        height: auto !important;
    }
</style>
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
                                        <div class="card-header bg-info text-white font-weight-bolder"> Judge Category </div>
                                        <div class="card-body">
                                            <?php
                                            $attributes = 'id="judge_category" class="row g-3"';
                                            $action = base_url('Listing/JudgeMaster/insert_judge_category');
                                            echo form_open($action, $attributes);
                                                // echo csrf_field();
                                                ?>
                                                <div class="col-md-12">
                                                    <div class="well">
                                                        <div class="row">
                                                            <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode'] ?>"/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="judge1">Judge</label>
                                                                <select class="form-control cus-form-ctrl" id="judge" name="judge" placeholder="judge" required>
                                                                    <option value="" selected disabled>Select Judge</option>
                                                                    <?php
                                                                    foreach($judge as $j1) {
                                                                        if ($judge[0]==$j1['jcode']) {
                                                                            echo '<option value="'.$j1['jcode'].'" selected="selected">'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                        } else {
                                                                            echo '<option value="'.$j1['jcode'].'" >'.$j1['jcode'].' - '.$j1['jname'].'</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                            <label for="from" >From Date</label>
                                                                <input type="text" id="fromDate" name="fromDate" class="form-control cus-form-ctrl datepick" placeholder="From Date" autocomplete="off" required >
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-sm-6" id="mainsubjectCategory">
                                                                <label for="category" id="lbl_McategoryCode" class="col-sm-6">Select Main Subject Category:</label>
                                                                <select class="form-control cus-form-ctrl col-sm-6" id="McategoryCode" name="McategoryCode" onchange= "get_sub_sub_cat()" required placeholder="Main Subject Category">
                                                                    <option value="" selected disabled>--Select Main Category--</option>
                                                                    <?php
                                                                    $MCategories = $Judge_Master_model->getMainSubjectCategory();
                                                                    if(!empty($MCategories)) {
                                                                        foreach($MCategories as $MCategory) {
                                                                            echo '<option value="' . $MCategory['subcode1']. '" ' . ( isset( $_POST['McategoryCode'] ) && $_POST['McategoryCode'] == $MCategory['subcode1'] ? 'selected="selected"' : '' ) . '>' . $MCategory['subcode1'].'- '.$MCategory['sub_name1'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6" id="subjectCategory">
                                                                <label for="category" id="lbl_categoryCode" class="col-sm-6">Select Sub Subject Category:</label>
                                                                <select  class="form-control cus-form-ctrl" multiple id="categoryCode" name="categoryCode[]"  placeholder="Subject Category" required >
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label for="mf" id="mf" class="col-sm-6">Misc/Regular</label>
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="M" checked required > Miscelleneous &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="mf" id="mf" class="rd_active" value="F" required > Regular
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3">
                                                                <button type="submit" id="btn-update" class="btn bg-olive btn-flat pull-right" onclick="update();"><i class="fa fa-save"></i> Update</button>
                                                            </div>
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
    <script>
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
        $(function () {
            window.setInterval(function(){
                $('.datepick').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose:true
                });
            },1000);
        });
        function get_sub_sub_cat() { // Call to ajax function
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
            var Mcat =$("#McategoryCode option:selected").val();
            $.ajax
            ({
                url: '<?php echo base_url('Listing/JudgeMaster/get_Sub_Subject_Category');?>',
                type: "POST",
                data: {CSRF_TOKEN:csrf,Mcat:Mcat},
                cache: false,
                dataType:"json",
                success: function(data)
                {
                    var options = '';
                    options='<option value="0" onclick="return selectAll(\'categoryCode\', true)">Select All</option>'
                    options+='<option value="0" onclick="return selectAll(\'categoryCode\', false)">Deselect All</option>'
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].dsc + '</option>';
                    }
                    $("#categoryCode").html(options);
                    updateCSRFToken();
                },
                error: function () {
                    alert('ERRO');
                }
            });
            updateCSRFToken();
        }
        function selectAll(id, isSelected) {
            var selectObj=document.getElementById(id);
            var options=selectObj.options;
            for(var i=0; i<options.length; i++) {
                if(options[i].value==0)
                    options[i].selected=false;
                else
                    options[i].selected=isSelected;
            }
        }
        function getSelectedValue() {
            var result = [];
            var options = document.getElementById('categoryCode');
            var opt;
            for (var i=0, iLen=options.length; i<iLen; i++) {
                opt = options[i];
                if (opt.selected) {
                    result.push(opt.value);
                }
            }
            return result;
        }


        $('#judge_category').on('submit', function(e){
            e.preventDefault();
            $.post("<?php echo base_url('Listing/JudgeMaster/insert_judge_category'); ?>", 
                $(this).serialize(), function (result) {
                if(!alert(result))
                {
                    location.reload();
                    updateCSRFToken();
                }
            });
            updateCSRFToken();
        });
    </script>
<?//=view('sci_main_footer') ?>