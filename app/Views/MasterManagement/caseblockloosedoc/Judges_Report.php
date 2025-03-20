<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
  .card-title {
            text-align: center;
            margin-bottom: 20px;
        }

        th{
            font-weight: bold;
        }

        #datatavles {
            border: 1px solid black;
            border-collapse: collapse;  
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
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
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
                                     <section class="content">
                                    <div class="box-heading">
                                        <div class="box-title" style="background-color: #537881;"><b><h2 style="background-color: #537881; color: white; text-align: center; font-size: 20px;font-weight: bold">List of Judges/Registrar</h2></b></div>
                                    </div>

                                    <div id="printable" class="box box-success">
                                        <div style="margin-top: 30px;margin-left: 15px;" id="row1" class="row">

                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <label for="judges">Select Type:</label><br>
                                            </div>

                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <input type="radio" id="judges" name="judges" value="J">
                                                <label for="judges">Judges</label><br>
                                            </div>
                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <input type="radio" id="judges" name="judges" value="R">
                                                <label for="judges">Registrar</label><br>
                                            </div>
                                        </div>

                                            <hr>

                                        <div style="margin-top: 30px;margin-left: 15px;" id="row2" class="row">

                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <label for="is_retired">Select Status:</label><br>
                                            </div>

                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <input type="radio" id="is_retired" name="is_retired" value="Y">
                                                <label for="is_retired">Retired</label><br>
                                            </div>
                                            <div class="col-sm-1 col-md-3 col-lg-3 ">
                                                <input type="radio" id="is_retired" name="is_retired" value="N">
                                                <label for="judges">Not Retired</label><br>
                                            </div>
                                        </div>

                                    </div>
                                    <div id="printable" class="box box-success">
                                        <table width="100%" id="reportTable1" class="table table-striped table-hover">

                                        </table>
                                    </div>
                                    </section>
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
<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
            <!-- <script src="<?=base_url()?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
            <script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/fastclick/fastclick.js"></script>
            <script src="<?=base_url()?>/assets/js/app.min.js"></script>
            <script src="<?=base_url()?>/assets/js/Reports.js"></script>
            <script src="<?=base_url()?>/assets/jsAlert/dist/sweetalert.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
            <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script> -->


<script>
    
 

    $(document).ready(function() {
        updateCSRFToken();
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                   $.ajax({
                    
                       url: "<?=base_url('MasterManagement/CaseBlockLooseDoc/judges_report_grid'); ?>",
                       data: {judges:judges, is_retired:is_retired} ,
                       cache: false,
                       type: 'POST',
                       headers: {
                                'X-CSRF-Token': CSRF_TOKEN_VALUE  
                                },
                       success: function (data) {
                        updateCSRFToken();
                           $("#reportTable1").html(data);   
                           $('#reportTable1').DataTable({
                               "destroy": true,
                               "bProcessing": true,
                               "pageLength": 25,
                               dom: 'Bfrtip',
                               buttons: [
                                   'pageLength',
                                   'copy',
                                   'csv',
                                   'excel',
                                   'print',
                                   'pdfHtml5'
                               ]
                           });
                       },
                       error: function () {
                           alert('No List Found');
                           updateCSRFToken();
                       }
                   });

               });


                $('#judges, #is_retired').on('change', function(){

                    //var selectedVal = "";
                    var is_retired = ($("input[type='radio'][name='is_retired']:checked")).val();
                    var judges = ($("input[type='radio'][name='judges']:checked")).val();
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    updateCSRFToken();
                    $.ajax({
                        url: "<?=base_url('MasterManagement/CaseBlockLooseDoc/judges_report_grid'); ?>",
                        data: {judges:judges, is_retired:is_retired} ,
                        cache: false,
                        type: 'POST',
                        headers: {
                                'X-CSRF-Token': CSRF_TOKEN_VALUE  
                                },
                        success: function (data) {
                            updateCSRFToken();
                            $("#reportTable1").html(data);
                            $('#reportTable1').DataTable({
                                "destroy": true,
                                "bProcessing": true,
                                "pageLength": 25,
                                dom: 'Bfrtip',
                                buttons: [
                                    'pageLength',
                                    'copy',
                                    'csv',
                                    'excel',
                                    'print',
                                    'pdfHtml5'
                                ]
                            });
                        },
                        error: function () {
                            alert('No List Found');
                            updateCSRFToken();
                        }
                    });

                });
</script>
