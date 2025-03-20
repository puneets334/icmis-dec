<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style>
       .form-style-10 {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .form-label {
            font-weight: bold;
        }
        .datepicker {
            width: 100%;
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
                                <h3 class="card-title">Master Management >> Advocate</h3>
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
                                        <!--<span class="btn btn-danger">Add Menus/ Child</span>-->

                                        <div class="alert alert-success hide" role="alert" id="msgDiv">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong></strong>
                                        </div>


                                        <div id="loginbox" style="margin-top:20px;" class="mainbox">
                                            <div class="panel panel-info" id="addMenusDiv">
                                                <div style="margin-top: 10px" class="panel-body">

                                                    <div class="alert hide"></div>
                                                    <div class="container mt-5">
                                                <form method="post" action="#">
                                                    <div class="form-style-10">
                                                        <div class="text-center mb-4">
                                                            <h3 class="fw-bold">AOR Pending/Disposed Matters Report</h3>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="aor" class="col-sm-3 col-form-label form-label">AOR Name:</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-select formselect" id="aor">
                                                                    <option value="">Select</option>
                                                                    <?php foreach($advocate as $row) { ?>
                                                                        <option value=<?=$row['aor_code']?>><?=$row['aor_code']?>:<?=$row['adv_name']?></option>
                                                                     <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="status" class="col-sm-3 col-form-label form-label">Status:</label>
                                                            <div class="col-sm-6">
                                                                <select class="form-select formselect" id="status">
                                                                    <option value="">All</option>
                                                                    <option value="P">Pending</option>
                                                                    <option value="D">Disposed</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="from_dt1" class="col-sm-3 col-form-label form-label">Filing Date:</label>
                                                            <div class="col-sm-3">
                                                                <input type="text" name="from_dt1" id="from_dt1" class="form-control datepicker" placeholder="From" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text" name="from_dt2" id="from_dt2" class="form-control datepicker" placeholder="To" />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="caseType" class="col-sm-3 col-form-label form-label">Case Type:</label>
                                                            <div class="col-sm-6">
                                                                <select name="caseType" id="caseType" class="form-select">
                                                                    <option value="">ALL CASES</option>
                                                                    <?php foreach($casetype as $r_nature) { ?>
                                                                        <option value="<?php echo $r_nature['casecode']; ?>"><?php echo $r_nature['casename']; ?></option>
                                                                     <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <button type="button" id="btnGetDiaryList" class="btn btn-primary" onclick="fetch_data();">
                                                                Get Records
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="text-center mt-3">
                                                        <img id="image" src="" style="display:none;" alt="Loading">
                                                    </div>
                                                    <div id="record" class="mt-3 Datacenter"></div>
                                                </form>
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
<script>
    // $(function () {
    //     $(".datatablereport_user").DataTable({
    //         "responsive": true, "lengthChange": false, "autoWidth": false,
    //         "buttons": ["copy", "csv", "excel", { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL' },
    //             { extend: 'colvis', text: 'Show/Hide' }], "bProcessing": true, "extend": 'colvis', "text": 'Show/Hide'
    //     }).buttons().container().appendTo('.datatablereport_user_wrapper_dataTable .col-md-6:eq(0)');

    // });
    // $(function () {
    //     $(".datatablereport").DataTable({
    //         "responsive": true, "lengthChange": false, "autoWidth": false,
    //         "buttons": ["copy", "csv", "excel", { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'LEGAL' },
    //             { extend: 'colvis', text: 'Show/Hide' }], "bProcessing": true, "extend": 'colvis', "text": 'Show/Hide'
    //     }).buttons().container().appendTo('.query_builder_wrapper_dataTable .col-md-6:eq(0)');

    // });

    $( function() {
    $( ".datepicker" ).datepicker();
  } );


  $(document).ready(function() {
        $(".formselect").select2();
    });

    function fetch_data()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#record').hide();
        var aor = $('#aor').val();
        var status = $('#status').val();
        var from_dt1 = $('#from_dt1').val();
        var from_dt2 = $('#from_dt2').val();
        var caseType = $('#caseType').val();
        if(aor==''){
            alert("Please select AOR");
            return;
        }
        $.ajax(
            {
                // C:\laragon\www\superct\app\Controllers\MasterManagement\Advocate.php
                type: "POST",
                url: baseURL + "/MasterManagement/Advocate/CasesView",
                data:{
                    aor: aor,
                    status: status,
                    from_dt1: from_dt1,
                    from_dt2: from_dt2,
                    caseType: caseType,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                },

               beforeSend:function(){
                    $("#image").show();
                },
                complete:function(){
                    $('#image').hide();
                },

                success:function(data){
                    $('.Datacenter').html(data);
                    $('#record').show();

                },

                error:function(){
                    alert('Error');
                }

            });
    }

</script>
