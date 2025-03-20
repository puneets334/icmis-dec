<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>

    /* .radio-inline{
        display: list-item;
    padding: inherit;
    position: absolute;
} */

.mf
{
    margin-left: 60px;
}

</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="card-title">Master Management >> Master</h3>
                    </div>
                    <div class="col-sm-2"> </div>
                </div>
            </div>
            <br /><br />
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12"> <!-- Right Part -->
                            <div class="form-div">
                                <div class="d-block text-center">


                                     <!-- Main content -->                                                
                                    <div class="box box-info">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="well">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <!--<label><input type="radio" class="rd_active" name="rd_active" value="A" checked/>Active Category</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                                                            <!--<label><input type="radio" class="rd_active" name="rd_active" value="I"/>In Active Category</label>-->
                                                        </div>
                                                    </div>
                                                    <div id="active">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <input type="hidden" name="usercode" id="usercode" value="<?= $usercode ?>"/>
                                                                <label for="judge">Transfer From</label>
                                                                <select class="form-control" id="judge" name="judge" required>
                                                                    <option value="" selected disabled>Select Judge</option>
                                                                    <?php
                                                                    foreach ($judge as $j1) {
                                                                        echo '<option value="' . $j1['jcode'] . '"' . (isset($_POST['judge']) && $_POST['judge'] == $j1['jcode'] ? ' selected' : '') . '>' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <h5>Misc/Regular</h5><br/>
                                                                <input type="radio" name="mf" id="misc" class="mf rd_active" value="M" <?= (isset($matters) && ($matters != '')) ? (($matters == 'M') ? 'checked' : '') : 'checked'; ?>  required > <span for="misc">Miscellaneous</span> 
                                                                <input type="radio" name="mf" id="regl" class="mf rd_active" value="F" <?= (isset($matters) && ($matters == 'F')) ? 'checked' : ''; ?> > Regular 
                                                                <input type="radio" name="mf" id="both" class="mf rd_active" value="B" <?= (isset($matters) && ($matters == 'B')) ? 'checked' : ''; ?> > Both
                                                            </div>
                                                        </div>                                                        
                                                        <div class="row mt-5">
                                                            <div class="col-sm-6">
                                                                <label for="judge1">Transfer To</label>
                                                                <select class="form-control" id="judge1" name="judge1" required>
                                                                    <option value="" selected disabled>Select Judge</option>
                                                                    <?php
                                                                    foreach ($judge as $j1) {
                                                                        echo '<option value="' . $j1['jcode'] . '"' . (isset($_POST['judge1']) && $_POST['judge1'] == $j1['jcode'] ? ' selected' : '') . '>' . $j1['jcode'] . ' - ' . $j1['jname'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br />
                                                        <div class="row">
                                                            <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3">
                                                                <button type="submit" id="btn-update" onclick="transfer();" class="btn btn-primary">
                                                                    <i class="fa fa-save"></i> Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br />
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
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript">

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

    function validation()
    {
        var judge_from= $("#judge").prop('selectedIndex');
        var judge_to= $("#judge1").prop('selectedIndex');
       // var val_active = $("input[name=rd_active]:checked").val();
        if(judge_from==judge_to)
        {
            alert("Both Judges cannot be same.");
            return false;
        }
        else if (judge_from == '') {
            alert("Please select transfer from Judge.");
            $("#judge").focus();
            return false;
        }
        else if (judge_to == '') {
            alert("Please select transfer to Judge.");
            $("#judge1").focus();
            return false;
        }
        else
            return true;
    }


    function transfer() {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        if (validation() == true) {
            var usercode = $('#usercode').val();
            var judge_from = $('#judge :selected').val();
            var judge_to = $('#judge1 :selected').val();
            var mf = $("input[name=mf]:checked").val();
        

            if (!isEmpty(judge_from) && !isEmpty(judge_to) && !isEmpty(usercode)) {
                $.ajax({
                    url: "<?=base_url();?>/MasterManagement/MasterController/transfer_insert_category",
                    type: "POST",
                    data: {
                        judge_from: judge_from,
                        judge_to: judge_to,
                        mf: mf,
                        usercode: usercode
                    },
                    headers: {
                        'X-CSRF-Token': CSRF_TOKEN_VALUE
                    },
                    success: function (result) {
                        console.log(result)
                        updateCSRFToken()
                        if (!alert(result)) {
                            location.reload();
                        }
                    },
                    error: function (xhr, status, error) {
                        updateCSRFToken()
                        console.error("Error: " + error);
                        // location.reload();
                        alert("An error occurred. Please try again.");
                    }
                });
            }
        }
    }


</script>
