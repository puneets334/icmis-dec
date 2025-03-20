<?=view('header'); ?>
    <!-- Main content -->
    
    <section class="content " >
    <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-body" >
        <form name="headerForm" action="<?= base_url() ?>/Faster/FasterController/getFasterCaseDetails" method="post">
            <!---------------- Next Section ---------------->
            <?=csrf_field(); ?>
            <div class="row" >
                <input type="hidden" name="usercode" id="usercode" value="<?=$usercode?>">
                <div class="col-sm-12">
                    <div  class="col-sm-6 form-group">
                        <label class="text-primary">Search Option : </label>
                        <label class="radio-inline"><input type="radio" name="optradio" value="C" checked>Case Type</label>
                        <label class="radio-inline"><input type="radio" name="optradio" value="D">Diary No.</label>

                    </div>
                </div>
                <div id="caseTypeWise" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="causelistDateSingle">Causelist Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control date-current" name="causelistDateSingle" id="causelistDateSingle"
                                   placeholder="dd/mm/yyyy" >
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="caseType">Case Type</label>
                        <select  class="form-control" name="caseType" tabindex="1" id="caseType" required>
                            <option value="">Select</option>
                            <?php
                            foreach($caseTypes as $caseType){
                                echo '<option value="' . $caseType['casecode'] . '">'. $caseType['casename'] .'&nbsp;:&nbsp;' .$caseType['skey']. '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="caseNo">Case No.</label>
                        <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                    </div>
                    <div class="col-sm-2">
                        <label for="caseYear">Year</label>
                        <select class="form-control" id="caseYear" name="caseYear" >
                            <?php
                            for($year=date('Y'); $year>=1950; $year--)
                                echo '<option value="'.$year.'">'.$year.'</option>';
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="view">&nbsp;</label>
                        <button type="submit"  id="view" name="view" class="btn btn-block btn-primary">View</button>
                    </div>
                </div>
                </div>
                <div id="diaryNoWise" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="causelistDateSingle">Causelist Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control date-current" name="causelistDateSingle" id="causelistDateSingle"
                                   placeholder="dd/mm/yyyy" >
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="diaryNumber">Diary No.</label>
                        <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                    </div>
                    <div class="col-sm-3">
                        <label for="diaryYear">Diary No.</label>
                        <select class="form-control" id="diaryYear" name="diaryYear" required="required" >
                            <?php
                            for($year=date('Y'); $year>=1950; $year--)
                                echo '<option value="'.$year.'">'.$year.'</option>';
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="view">&nbsp;</label>
                        <button type="submit"  id="view" name="view" class="btn btn-block btn-primary">View</button>
                    </div>

                </div>
                </div>

            </div>
        </form>
        <hr>
        <div class="row">
            <div id="diaryNoWise" class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="causelistDate">Causelist Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control date-current" name="causelistDate" id="causelistDate"
                                placeholder="dd/mm/yyyy" >
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <label for="btnFetchRecords">&nbsp;</label>
                        <button type="button" id="btnFetchRecords" name="btnFetchRecords" class="btn btn-block btn-secondary" onclick="return getListedDetails();"><span class="fa fa-search" aria-hidden="true"></span></button>
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-sm-12 pt-2" id="divFasterCases">
                <h4>Cases Marked For Faster</h4>
                <hr>
                <table id="tblFasterCases" class="table table-striped table-bordered">
                    <thead>
                    <th style="width: 5%">Court/Item</th>
                    <th style="width: 20%">Case Number</th>
                    <th style="width: 30%">Causetitle</th>
                    <th style="width: 5%">Sent to Faster</th>
                    <th style="width: 10%">Orders</th>
                    <th style="width: 20%">Notice</th>
                    <th style="width: 5%">Memo of Party</th>
                    <th style="width: 10%">Faster Status</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        if($msg!=""){
            ?>
            <div class="alert alert-info">
                <?=$msg ?>
            </div>
            <?php
        }
        ?>
        </div>
</div>
</div>
</div>
</div>
    </section>


<script src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/js/select2.full.min.js"></script>
<!--<script src="<?/*=base_url()*/?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?/*=base_url()*/?>assets/plugins/fastclick/fastclick.js"></script>-->
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jszip.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.colVis.min.js"></script>

<!--<script src="<?=base_url()?>assets/js/reader_cl.js"></script>-->

<script>
    $(".alert").delay(4000).slideUp(500, function() {
        $(this).alert('close');
    });



    $(document).ready(function()
    {
        //getListedDetails(true);
        $(".date-current").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        }).datepicker("setDate", "0");

        $('#diaryNumber').prop("disabled",'disabled');
        $('#diaryYear').prop("disabled",'disabled');
        $('#diaryNoWise').hide();


        $("input[name$='optradio']").click(function() {
            var searchValue = $(this).val();
            if(searchValue=='C')
            {
                $('#caseType').removeAttr('disabled');
                $('#caseNo').removeAttr('disabled');
                $('#caseYear').removeAttr('disabled');
                $('#diaryNumber').prop("disabled",'disabled');
                $('#diaryYear').prop("disabled",'disabled');
                $('#diaryNoWise').hide();
                $('#caseTypeWise').show();
            }
            else
            {
                $('#caseType').prop("disabled",'disabled');
                $('#caseNo').prop("disabled",'disabled');
                $('#caseYear').prop("disabled",'disabled');

                $('#caseTypeWise').hide();

                $('#diaryNumber').removeAttr('disabled');
                $('#diaryYear').removeAttr('disabled');

                $('#diaryNoWise').show();
            }
        });
    });

    function confirmBeforeAdd() {
        var choice = confirm('Do you really want to List The Matter.....?');
        if(choice === true) {
            return true;
        }
        return false;
    }

    function getListedDetails(onload=false){
//        $("#tblFasterCases").DataTable().clear().draw();
 //       $("#tblFasterCases").DataTable().destroy();

        causelistDate="";
        if(!onload){
            causelistDate=$('#causelistDate').val();
            if($('#causelistDate').val()==""){
                alert("Please select date!!");
                return false;
            }
        }
        else{
            causelistDate=(new Date()).toISOString().split('T')[0];
        }
        //alert(usercode);
        if(causelistDate!=""){
            $.post("<?=base_url()?>/Faster/FasterController/getCasesMarkedForFaster", {causelistDate: causelistDate},function(result){
                $("#divFasterCases").val(result);
                response = $.parseJSON(result);
                $('#tblFasterCases tbody').empty();
                $.each(response, function(i, item) {
                    //FOR ROP/Signed Order and Judgment
                    var rophtml="";
                    if(item.rop_pdf == null){
                        rophtml="<p>Not uploaded yet! </p>";
                    }
                    else{
                        var orders = item.rop_pdf.split('----');
                        $.each(orders,function(j,order){
                            var orderdetails = order.split('$$');
                            if(orderdetails[0]=='O'){
                                ordertype="ROP";
                            }
                            else if(orderdetails[0]=='J'){
                                ordertype="Judgment";
                            }
                            else if(orderdetails[0]=='S'){
                                ordertype="Signed Order";
                            }
                            rophtml=rophtml+"<a href='<?=ICMIS_ROP_URL?>"+orderdetails[1]+"' target='_blank'>"+ordertype+"</a><hr>";
                        });
                    }

                    //TODO:: Change icmis to supreme_court in generate path before going live
                    //FOR Notice
                    var noticehtml="";
                    if(item.notice_pdf == null){
                        noticehtml="<p>Not found!  <a href='<?=WEB_ROOT?>/supreme_court/notices/talwana.php'>Generate</a></p>";
                    }
                    else{
                        var notices = item.notice_pdf.split('----');
                        $.each(notices,function(k,notice){
                            var noticedetails = notice.split('$$');
                            noticehtml=noticehtml+"<a href='<?=ICMIS_NOTICE_URL?>"+noticedetails[1]+"' target='_blank'>"+noticedetails[0]+"</a><hr>";
                        });
                    }

                    //Memo of party html
                    var mophtml="";
                    if(item.causetitle_pdf == null){
                        mophtml="<p>Not found!  <a href='<?=WEB_ROOT?>/supreme_court/da/cause_title.php'>Generate</a></p>";
                    }
                    else{
                        mophtml="<a href='<?=ICMIS_ROP_URL?>"+item.causetitle_pdf+"' target='_blank'>View</a>";
                    }

                    //Faster Link
                    var fasterlink="";
                    if(item.faster_cases_id == null){
                        fasterlink="<p>Not found!  <a href=\"<?=base_url()?>/Faster/FasterController/startFasterWithId/"+item.diary_no+"/"+causelistDate+"\">Start</a></p>";
                    }
                    else{
                        fasterlink="<p> <a href=\"<?=base_url()?>/Faster/FasterController/startFasterWithId/"+item.diary_no+"/"+causelistDate+"\">"+item.faster_status+" on " +item.transaction_status_date_time+"</a></p>";
                    }

                    //fasterlink="<p>Not found!  <a target=\"_blank\" href=\"<?=base_url()?>index.php/FasterController/startFasterWithId/"+item.diary_no+"/"+causelistDate+"\">Start</a></p>";





                    $('<tr>').append(
                        $('<td>').text(item.court_no+"/"+item.item_number),
                        $('<td>').html(item.reg_no_display+"<br/>"+item.diary_no),
                        $('<td>').text(item.causetitle),
                        $('<td>').text(item.sent_to_faster_time),
                        $('<td>').html(rophtml),
                        $('<td>').html(noticehtml),
                        $('<td>').html(mophtml),
                        $('<td>').html(fasterlink)
                    ).appendTo('#tblFasterCases tbody');
                });







                load_table_row(causelistDate);
                $('#tblFasterCases').DataTable();
            });
        }
    }
    function validateData() {
        if($('#fileROPList').val()==""){
            alert("Please select pdf file to upload!!");
            return false;
        }
    }

    function load_table_row(causelistDate) {
        var title = function () { return 'Cases Marked For Faster List Date '+causelistDate };
        $('#tblFasterCases').DataTable( {
            dom: 'Bfrtip',

            buttons: [
                {
                    extend: 'csv',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6],
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6],
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6],
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6],
                        stripHtml: true
                    }
                },
                {
                    extend: 'colvis',
                    columns: ':gt(0)',
                    columns: ':gt(1)'
                }
            ]
        });
    }


</script>
