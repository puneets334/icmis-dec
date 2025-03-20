<?= view('header') ?>
<?php $uri = current_url(true); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Send For Faster</h3>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <?php
                            $attribute = array('class' => 'form-horizontal appearance_search_form', 'name' => 'appearance_search_form', 'id' => 'appearance_search_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                            echo form_open(base_url('FasterController/getListedInfo'), $attribute);
                            ?>
                            <div class="row">
                                <!-- <div class="form-group col-sm-4">
                                    <label for="causelistDate">Cause List Date</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>users_history
                                        </div>
                                        <input type="text" autocomplete="off" class="form-control" name="causelistDate" id="causelistDate" value="<?= date('d-m-Y') ?>">
                                    </div>
                                </div> -->

                                <div class="col-md-3">
                                    <label for="causelistDate">Cause List Date</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="causelistDate" id="causelistDate" value="<?= date('d-m-Y') ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="pJudge">Court No.</label>
                                    <div class="form-group">
                                        <select class="form-select" id="courtNo" name="courtNo" placeholder="courtNo">
                                            <option value="">Select Court No.</option>
                                            <?php
                                            for ($i = 1; $i <= 17; $i++) {
                                            ?>
                                                <option value="<?= $i ?>"><?= "Court No. " . $i ?></option>
                                            <?php
                                            }
                                            ?>
                                            <option value="21">Registrar Court No. 1</option>
                                            <option value="22">Registrar Court No. 2</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-2 mt-4">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btnGetCases" class="btn btn-success form-control">Get Cases</button>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="row" id="divCasesForUploading"></div>
                                </div>
                            </div> -->
                            <?= form_close() ?>
                        </div>
                        <div class="col-md-12" id="div_result">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function ()
    {
        $("#causelistDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    function confirmBeforeAdd() {
        var choice = confirm('Do you really want to List The Matter.....?');
        if (choice === true) {
            return true;
        }
        return false;
    }


    function chkall(e) {
        var elm = e.name;
        if (document.getElementById(elm).checked) {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk" && !($(this).is(':disabled'))) {
                    this.checked = true;
                }
            });
        } else {
            $('input[type=checkbox]').each(function() {
                if ($(this).attr("name") == "chk") {
                    this.checked = false;
                }
            });
        }
    }
    $(document).on("click", "#sendForFaster", function(e) {
        e.preventDefault();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var diaryArr = [];
        var diary_no = '',
            conn_key = '',
            brd_slno = '',
            courtno = '',
            judges = '',
            next_dt = '',
            mainhead = '',
            roster_id = '',
            main_supp_flag = '',
            board_type = '';
        $('input[type=checkbox]').each(function() {


            if ($(this).attr('name') == "chk" && $(this).is(':checked')) {
                var tmp = {};
                diary_no = $(this).data('diary_no');
                conn_key = $(this).data('conn_key');
                brd_slno = $(this).data('brd_slno');
                courtno = $(this).data('courtno');
                judges = $(this).data('judges');
                next_dt = $(this).data('next_dt');
                mainhead = $(this).data('mainhead');
                roster_id = $(this).data('roster_id');
                main_supp_flag = $(this).data('main_supp_flag');
                board_type = $(this).data('board_type');
                tmp.diary_no = diary_no;
                tmp.conn_key = conn_key;
                tmp.brd_slno = brd_slno;
                tmp.courtno = courtno;
                tmp.judges = judges;
                tmp.next_dt = next_dt;
                tmp.mainhead = mainhead;
                tmp.roster_id = roster_id;
                tmp.main_supp_flag = main_supp_flag;
                tmp.board_type = board_type;

                diaryArr.push(tmp);
            }
        });

        if (diaryArr.length == 0) {
            swal({
                title: "Error!",
                text: "Atleast one case should be selected",
                icon: "error",
                button: "error!"
            });
            return false;
        } else {
            var postData = {};
            postData.diaryArr = diaryArr;
            $.ajax({
                url: "<?php echo base_url('index.php/FasterController/addCaseForFaster'); ?>",
                type: 'POST',
                data: {
                    postData: postData,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                cache: false,
                async: true,
                dataType: "json",
                beforeSend: function() {
                    $("#sendForFaster").html('Processing <i class="fas fa-sync fa-spin"></i>');
                },
                success: function(res) {
                    updateCSRFToken(); 
                    $("#sendForFaster").html('Send For Faster');
                    if (res.status == 'error') {
                        swal({
                            title: "Error!",
                            text: res.message,
                            icon: "error",
                            button: "error!"
                        });
                        return false;
                    } else if (res.status == 'success') {
                        // if(res.actionArr){
                        //     var actionArr = res.actionArr;
                        //     if(actionArr.length >0){
                        //         for(var i=0;i<actionArr.length;i++){
                        //             var diary_no = actionArr[i]['diary_no'];
                        //             var message = actionArr[i]['message'];
                        //              $("#diaryno_"+diary_no).text(message);
                        //         }
                        //     }
                        // }
                        swal({
                            title: "Success!",
                            text: res.entryCount + " Case(s) Added Successfully. Total Error: " + res.errorCount,
                            icon: "success",
                            button: "success!"
                        });
                    }
                },
                error: function(xhr) {
                    updateCSRFToken(); 
                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });


    $(document).on("click", "#modifyFaster", function(e) {
        e.preventDefault();
        var diaryArr = [];
        var diary_no = '',
            next_dt = '';
        $('input[type=checkbox]').each(function() {
            if ($(this).attr('name') == "chk" && $(this).is(':checked')) {
                var tmp = {};
                diary_no = $(this).data('diary_no');
                judges = $(this).data('judges');
                next_dt = $(this).data('next_dt');
                tmp.diary_no = diary_no;
                tmp.next_dt = next_dt;
                diaryArr.push(tmp);
            }
        });

        if (diaryArr.length == 0) {
            swal({
                title: "Error!",
                text: "Atleast one case should be selected",
                icon: "error",
                button: "error!"
            });
            return false;
        } else {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();

            var postData = {};
            postData.diaryArr = diaryArr;
            $.ajax({
                url: "<?php echo base_url('index.php/FasterController/modifyCaseForFaster'); ?>",
                type: 'POST',
                data: {
                    postData: postData,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                cache: false,
                async: true,
                dataType: "json",
                beforeSend: function() {
                    $("#modifyFaster").html('Processing <i class="fas fa-sync fa-spin"></i>');
                },
                success: function(res) {
                    updateCSRFToken(); 
                  
                    $("#modifyFaster").html('Modify');
                    if (res.status == 'error') {
                        swal({
                            title: "Error!",
                            text: res.message,
                            icon: "error",
                            button: "error!"
                        });
                        return false;
                    } else if (res.status == 'success') {
                        // if(res.actionArr){
                        //     var actionArr = res.actionArr;
                        //     if(actionArr.length >0){
                        //         for(var i=0;i<actionArr.length;i++){
                        //             var diary_no = actionArr[i]['diary_no'];
                        //             var message = actionArr[i]['message'];
                        //              $("#diaryno_"+diary_no).text(message);
                        //         }
                        //     }
                        // }
                        swal({
                            title: "Success!",
                            text: res.updateCount + " Case(s) Updated Successfully. Total Error: " + res.errorCount,
                            icon: "success",
                            button: "success!"
                        });
                    }
                },
                error: function(xhr) {
                    updateCSRFToken(); 
                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });

    $(document).on('click', '#btnGetCases', function() {

        let causelistDate = $('#causelistDate').val();
        let courtNo = $('#courtNo').val();
        
        if (causelistDate == '' || causelistDate == null) {
            alert('Please select cause list date');
            return false;
        } else if (courtNo == '' || courtNo == null) {
            alert('Please select court number');
            return false;
        }

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            url: base_url + '/FasterController/getListedInfo',
            cache: false,
            async: true,
            beforeSend: function() {
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },           
            data: {
                causelistDate: causelistDate,
                courtNo: courtNo,
                CSRF_TOKEN : CSRF_TOKEN_VALUE,
            },             
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();                
                $('#div_result').html(data);
            },
            error: function(xhr) {
                updateCSRFToken();
                $('#div_result').html('');
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });


    });
</script>