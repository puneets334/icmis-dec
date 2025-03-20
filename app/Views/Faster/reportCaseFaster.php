<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Report For Faster Cases</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/buttons.dataTables.min.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="content-fluid">
    <!-- Main content -->
    <section class="content " >
        <div class="row col-md-12">
            <h2 class="card-header bg-info text-white font-weight-bolder text-left">Report For Faster Cases</h2>
        </div>
        <form name="report" id="report">
            <div class="row" >
                <div class="form-group col-sm-4">
                    <label for="causelistDate">Cause List Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" autocomplete="off" class="form-control" name="causelistDate" id="causelistDate" value="<?=date('d-m-Y')?>">
                        <span id="error_causelistDate"></span>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label for="pJudge">Court No.</label>
                    <select class="form-control" id="courtNo" name="courtNo" placeholder="courtNo" >
                        <option value="0">Select Court No.</option>
                        <?php
                        for($i=1;$i<=17;$i++) {
                        ?>
                        <option value="<?= $i?>"><?= "Court No. ".$i?></option>
                        <?php
                        }
                        ?>
                        <option value="21">Registrar Court No. 1</option>
                        <option value="22">Registrar Court No. 2</option>
                    </select>
                    <span id="error_courtNo"></span>
                </div>

                <div class="form-group col-sm-2">
                    <label>&nbsp;</label>
                    <button type="button" id="getReport" class="btn btn-success form-control"">Get Report</button>
                </div>
            </div>
        </form>
        <div class="row" id="loader_image"></div>


        <!-------------Result Section ------------>

            <div class="row col-md-12" id="reportFasterCases" style="display: none;">
                    <h2 class="card-header bg-info text-white font-weight-bolder text-left" id="searchHeader">Report For Faster Cases</h2>
                <div class="well">
                    <table id="reportFasterCasesTable" class="table table-striped table-hover display">
                        <thead>
                        <tr>
                            <th width="1%">Sno.</th>
                            <th width="15%">Case No</th>
                            <th width="25%">Cause Title</th>
                            <th>Court Type</th>
                            <th>Court</th>
                            <th>Judge Name</th>
                            <th>Mainhead</th>
                            <th>Main/Supp.</th>
                            <th>Created By/On</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
        </div>

    </section>
</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/dataTables.bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.colVis.min.js"></script>
<script>

    $(function () {
        $("#causelistDate").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    $(document).on("click","#getReport",function(e){
        e.preventDefault();
        var causelistDate = $("#causelistDate").val();
        var courtNo = $("select#courtNo option:selected").val();
        var image_url = "<?php echo base_url('assets/images/load.gif');?>";
        $('#reportFasterCasesTable').dataTable().fnClearTable();
        $('#reportFasterCasesTable').dataTable().fnDraw();
        $("#reportFasterCasesTable").DataTable().clear();
        if(causelistDate == ''){
            $("#causelistDate").focus();
            $("#causelistDate").css({'border-color':'red'});
            $('#error_causelistDate').html('<p style="color: red;">Please select cause list date</p>');
            alert("Please select cause list date");
            return false;
        }
        // else  if(courtNo == '' || courtNo == '0'){
        //     $("#courtNo").focus();
        //     $("#courtNo").css({'border-color':'red'});
        //     $('#error_courtNo').html('<p style="color: red;">Please select courtNo</p>');
        //     alert("Please select courtNo");
        //     return false;
        // }
        else{
            var postData = {};
            postData.causelistDate = causelistDate;
            postData.courtNo = courtNo;
            $.ajax({
                url: "<?php echo base_url('index.php/FasterController/getReport');?>",
                type: 'POST',
                data: postData,
                cache: false,
                async:true,
                dataType: "json",
                beforeSend:function(){
                    $("#getReport").html('Processing <i class="fas fa-sync fa-spin"></i>');
                    $('#loader_image').html('<table widht="100%" align="center"><tr><td><img src="'+image_url+'"/></td></tr></table>');
                },
                success:function (res) {
                    $("#reportFasterCases").show();
                    $("#getReport").html('Get Report');
                    $('#loader_image').html('');
                    var currentDateTime = getDateTime();
                    $("#searchHeader").html('');
                    if(courtNo && courtNo !='0'){
                        $("#searchHeader").html('Report For Faster Cases Listing Date '+ causelistDate +' And Court No '+courtNo+' As On Dated '+currentDateTime);
                    }
                    else{
                        $("#searchHeader").html('Report For Faster Cases Listing Date '+ causelistDate +' As On Dated '+currentDateTime);
                    }
                    var length = res.length;
                    for(var i = 0; i < length; i++) {
                        var result =  res[i];
                        var court_name ='';
                        if(result.court_no == '31'){
                            court_name =  '1 (Virtual Court)';
                        }
                        else if(result.court_no == '32'){
                            court_name =  '2 (Virtual Court)';
                        }
                        else if(result.court_no == '33'){
                            court_name =  '3 (Virtual Court)';
                        }
                        else if(result.court_no == '34'){
                            court_name =  '4 (Virtual Court)';
                        }
                        else if(result.court_no == '35'){
                            court_name =  '5 (Virtual Court)';
                        }
                        else if(result.court_no == '36'){
                            court_name =  '6 (Virtual Court)';
                        }
                        else if(result.court_no == '37'){
                            court_name =  '7 (Virtual Court)';
                        }
                        else if(result.court_no == '38'){
                            court_name =  '8 (Virtual Court)';
                        }
                        else if(result.court_no == '39'){
                            court_name =  '9 (Virtual Court)';
                        }
                        else if(result.court_no == '40'){
                            court_name =  '10 (Virtual Court)';
                        }
                        else if(result.court_no == '41'){
                            court_name =  '11 (Virtual Court)';
                        }
                        else if(result.court_no == '42'){
                            court_name =  '12 (Virtual Court)';
                        }
                        else if(result.court_no == '43'){
                            court_name =  '13 (Virtual Court)';
                        }
                        else if(result.court_no == '44'){
                            court_name =  '14 (Virtual Court)';
                        }
                        else if(result.court_no == '45'){
                            court_name =  '15 (Virtual Court)';
                        }
                        else if(result.court_no == '46'){
                            court_name =  '16 (Virtual Court)';
                        }
                        else if(result.court_no == '47'){
                            court_name =  '17 (Virtual Court)';
                        }
                        else if(result.court_no == '21'){
                            court_name =  '1 (Registrar)';
                        }
                        else if(result.court_no == '22'){
                            court_name =  '2 (Registrar)';
                        }
                        else if(result.court_no == '61'){
                            court_name =  '1 (Registrar Virtual Court)';
                        }
                        else if(result.court_no == '62'){
                            court_name =  '2 (Registrar Virtual Court)';
                        }
                        else{
                            court_name =  result.court_no;
                        }
                        var caseno = '';
                        if(result.reg_no_display && result.diary_no){
                            caseno += result.reg_no_display + ' @ '+ result.diary_no;
                        }
                        else if(result.reg_no_display){
                            caseno += result.reg_no_display ;
                        }
                        $('#reportFasterCasesTable').dataTable().fnAddData( [
                            i+1,
                            caseno,
                            result.cause_title,
                            result.board_type,
                            court_name,
                            result.judge_name,
                            result.mainhead,
                            result.main_supp_flag,
                            result.name+'<br>'+ result.entry_date
                        ]);
                    }
                },
                error: function (xhr) {
                    $("#getReport").html('Get Report');
                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
        }
    });
    $(document).ready(function() {
        var title = function () {
            return $("#searchHeader").text();
        }
        var columnArr = [0,1,2,3,4,5,6,7,8];
        var table = $('#reportFasterCasesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title:title,
                    exportOptions: {
                        columns: columnArr,
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title:title,
                    exportOptions: {
                        columns: columnArr,
                        stripHtml: true
                    }
                } ,
                {
                    extend: 'colvis',
                    columns: ':gt(0)',
                    columns: ':gt(1)',
                    columns: ':gt(2)',
                    columns: ':gt(3)'
                }
            ],
            columnDefs: [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            retrieve: true,
            autoFill: true,
            destroy: true,
            order: [[ 1, 'asc' ]],
            "rowCallback": function (nRow, aData, iDisplayIndex) {
                var oSettings = this.fnSettings ();
                $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                return nRow;
            }
        });
        table.on('order.dt search.dt', function () {
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
                table.cell(cell).invalidate('dom');
            });
        }).draw();

    });
    function getDateTime() {
        var now     = new Date();
        var year    = now.getFullYear();
        var month   = now.getMonth()+1;
        var day     = now.getDate();
        var hour    = now.getHours();
        var minute  = now.getMinutes();
        var second  = now.getSeconds();
        var amPm = ( hour >= 12 ) ? 'PM' : 'AM';
        if(month.toString().length == 1) {
            month = '0'+month;
        }
        if(day.toString().length == 1) {
            day = '0'+day;
        }
        if(hour.toString().length == 1) {
            hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
            minute = '0'+minute;
        }
        if(second.toString().length == 1) {
            second = '0'+second;
        }
        var dateTime = day+'-'+month+'-'+year+' '+hour+':'+minute+':'+second + ' '+amPm;
        return dateTime;
    }

</script>

</body>
</html>