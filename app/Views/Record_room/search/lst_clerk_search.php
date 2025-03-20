<?= view('header') ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#aorc').focus();


        $("#aorc").change(function() {
            var aorc = $("#aorc").val();
            var dataString = 'tvap=' + aorc;
            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/getclerk_name'); ?>",
                data: dataString,
                cache: false,
                success: function(result) {
                        if (result) {
                            $('#aorn').val(result);
                            $("#aorn").attr("disabled", "disabled");
                            $('#cnf').focus();
                        } else {
                            $('#aorn').val("CLERK CODE " + aorc + " NOT FOUND");
                            $("#aorn").attr("disabled", "disabled");
                            $('#aorc').val(" ");
                            $('#aorc').focus();
                        }
                    }
            });
        });
        $('#case_trap_form').on('submit', function() {
            var aorc = $("#aorc").val();
            var aorn = $("#aorn").val();
            var tvap = '';;

            tvap = aorc + ";" + aorn;

            // Returns successful data submission message when the entered information is stored in database.
            if (!aorc) {
                alert("Please Enter Mandatory Values");
                return false;
            }
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var dataString = 'tvap=' + tvap;

            $.ajax({
                type: "get",
                url: "<?php echo base_url('Record_room/Record/lst_clerk_search1'); ?>",
                data: dataString,
                cache: false,
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




    });
</script>
<link rel="stylesheet" type="text/css" href="<?= base_url('/css/aor.css') ?>">
<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                                <div class="card-header heading">


                                <div class="row">
                                <div class="col-sm-10">
                                <h3 class="card-title">Search &gt;&gt;&nbsp; View Clerk with AOR</h3>
                                </div>

                                </div>
                                </div>


<div class="container-fluid mt-3">
        <div class="panel panel-info">
 
            <div class="panel-body" id="frm">
                <?php
                $attribute = array('class' => 'form-horizontal ', 'name' => 'case_trap_form', 'id' => 'case_trap_form', 'autocomplete' => 'off');
                echo form_open(base_url('#'), $attribute);
                ?> <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-2" for="atitle">Clerk Code *</label>

                        <div class="col-sm-1">
                            <input class="form-control" name="aorc" type="text" id="aorc" placeholder="Code">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" name="aorn" type="text" id="aorn" placeholder="Name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-info" name="case_trap_search" id="case_trap_search" onclick="">
                            <span class="fa fa-plus"></span> Search
                        </button>
                    </div>
                </div>
                <?= form_close() ?>
                <div id="result_data"></div>
            </div>

        </div>
        <div class="panel-footer" id="rslt1"></div>
    </div>
    <div class='table-responsive' id="rslt"></div>

</div>

<br/>
    </div> <!-- card div -->



</div>
<!-- /.col -->
</div>
<!-- /.row -->




</div>
<!-- /.container-fluid -->
</section>
<script>
    $(function() {
        $("#query_builder_report").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>
<div id="div_print">
    <div id="header" style="background-color:White;"></div>
    <div id="footer" style="background-color:White;"></div>
</div>