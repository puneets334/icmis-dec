<?= view('header'); ?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Filing Trap >> Incomplete Matters </h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                            <!--<div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-pencil" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <?//= view('Filing/filing_breadcrumb'); 
                    ?>
                    <!-- /.card-header -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff;">
                                    <h4 class="basic_heading"> File Trap Details </h4>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">                                         
                                        <div id="dv_content1">
                                              <h5 class="text-center">SORRY!!!, NO RECORD FOUND</h5>                                             
                                        </div>
                                    </div>
                                    
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    function efiling_number(efiling_number) {
        var url = "http://10.25.78.48:81/efiling_search/DefaultController/?efiling_number=" + efiling_number;
        window.open(url, '_blank');
    }
</script>
<script type="text/javascript">
    // function myFunction() {
    //     document.getElementById("demo").innerHTML = "Hello World";
    // }

    function get_list(value1) {
        var str = value1;

        $.ajax({
            url: "<?php echo base_url('Filing/FileTrap/getMatters'); ?>",
            type: "GET",
            data: {
                q: str
            },
            success: function(response) {
                $("#txtHint").html(response);
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + status + " - " + error);
            }
        });
    }

    // function get_list(value1) {

    //     var str = value1;
    //     var xmlhttp = new XMLHttpRequest();
    //     xmlhttp.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //             document.getElementById("txtHint").innerHTML = this.responseText;
    //         }
    //     };
    //     xmlhttp.open("GET", "get_matters.php?q=" + str, true);
    //     xmlhttp.send();

    // }

    function recieve_file(iss) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var idd = iss.split('rece');
        $(this).attr('disabled', true);
        var type = 'R';
        $.ajax({
                type: 'POST',
                url: "<?= base_url('Filing/FileTrap/receive') ?>",
                beforeSend: function(xhr) {
                    $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                },
                data: {
                    id: idd[1],
                    value: type,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                }
            })
            .done(function(msg) {
                $("#result1").html('');
                updateCSRFToken();
                //$("#result").html(msg);
                // alert(msg);
                get_list(document.getElementById('type_report').value);
                //document.getElementBYId('d').innerHTML=msg;
                // return;
            })
            .fail(function() {
                $("#result1").html('');
                updateCSRFToken();
                alert("ERROR, Please Contact Server Room");
            });
    }
</script>
<script>
    $(document).ready(function() {

        $("[id^='rece']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var idd = $(this).attr('id').split('rece');
            //alert('received');
            //alert(idd);return;
            // $(this).attr('disabled',true);
            var type = 'R';
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Filing/FileTrap/receive') ?>",
                    beforeSend: function(xhr) {
                        $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    $("#result1").html('');
                    updateCSRFToken();
                    //$("#result").html(msg);
                    alert(msg);

                    window.location.reload();
                    return;
                })
                .fail(function() {
                    $("#result1").html('');
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });

        $("[id^='comp']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var c = confirm("Are You Sure You Want to Dispatch");
            if (c == true) {
                var idd = $(this).attr('id').split('comp');
                $(this).attr('disabled', true);
                var type = 'C';
                var nature = idd[2];
                //alert('id='+idd[1] + ' type='+type +' nature='+nature);// return false;
                $.ajax({
                        type: 'POST',
                        url: "<?= base_url('Filing/FileTrap/receive') ?>",
                        beforeSend: function(xhr) {
                            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                        },
                        data: {
                            id: idd[1],
                            value: type,
                            nature: nature,
                            CSRF_TOKEN: CSRF_TOKEN_VALUE
                        }
                    })
                    .done(function(msg) {
                        $("#result1").html('');
                        updateCSRFToken();
                        //$("#result").html(msg);
                        alert(msg);
                        window.location.reload();
                        return;
                    })
                    .fail(function() {
                        $("#result1").html('');
                        updateCSRFToken();
                        alert("ERROR, Please Contact Server Room");
                    });
            }
        });

        $("[id^='tag']").click(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var tag = 'Y';
            var idd = $(this).attr('id').split('tag');
            $(this).attr('disabled', true);
            var type = 'C';
            $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Filing/FileTrap/receive') ?>",
                    beforeSend: function(xhr) {
                        $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../../images/load.gif'></div>");
                    },
                    data: {
                        id: idd[1],
                        value: type,
                        tag: tag,
                        CSRF_TOKEN: CSRF_TOKEN_VALUE
                    }
                })
                .done(function(msg) {
                    updateCSRFToken();
                    //$("#result").html(msg);
                    alert(msg);
                    // window.location.reload();
                    //return;
                })
                .fail(function() {
                    updateCSRFToken();
                    alert("ERROR, Please Contact Server Room");
                });
        });
    });

    $(document).on("click", "#print1", function() {
        var prtContent = $("#dv_content1").html();
        var temp_str = prtContent;
        var WinPrint = window.open('', '', 'left=10,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
</script>