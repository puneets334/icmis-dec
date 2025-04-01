<link rel="stylesheet" href="css/bootstrap.min.css" >
<link rel="stylesheet" href="../css/datatables.min.css">
<link rel="stylesheet" href="../css/buttons.datatables.min.css">
<link href="../plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../plugins/font-awesome/css/font-awesome.css" >
<script src="js/jquery.dataTables.min.js"></script>
<script src="../plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="../plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
<script src="../plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="../js/pdfmake.min.js"></script>
<script src="../plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="../plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="../plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<style>


    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
    }

    /* Modal Content */
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        width: 96%;
    }

    /* Add Animation */
    @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }

    @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }

    /* The Close Button */
    .close {
        color:red;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal-header {
        padding: 2px 16px;
        /*background-color: #5cb85c;
        color: white;*/
    }

    .modal-body {padding: 2px 16px;}

    .modal-footer {
        padding: 2px 16px;
        /*background-color: #5cb85c;
        color: white;*/
    }
</style>
<?php  
if($_POST['doc_action'] == 'send_to_section_report'){
$crn=$_POST['crn'];
$crmresult=$copyRequestModel->getCrmList();
if (!empty($crmresult)) {
$crm_list=array();
 foreach($crmresult as $row) {
$crm_list[] = $row;
}
}

//echo '<pre>';print_r($crm_list);
?>
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title print-title">Case Movement Report [ C.R.N : <?php echo $crn;?> ]</h4>
        <input type="hidden" name="columns" id="columns" value="[0,1,2,3,4,5,6]">
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <div class="modal-content">

            <table id="reportTable2" class="table table-striped table-hover display">
                <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Document</th>
                    <th>Sent By</th>
                    <th>Sent On</th>
                    <th>From Section</th>
                    <th>To Section</th>
                    <th>Remark</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sno = 1;
                foreach($crm_list as $row){ ?>
                    <tr>
                        <td><?=$sno++?></td>
                        <td><?=$row['order_name']."<br>".date("d-m-Y", strtotime($row['order_date'])) ;?></td>
                        <td><?php echo $row['sended_by']; ?></td>
                        <td><?=date("d-m-Y H:i:s", strtotime($row['from_section_sent_on'])) ?></td>
                        <td><?=$row['from_section_name'];?></td>
                        <td><?=$row['to_section_name'];?></td>
                        <td><?php echo $row['remark']; ?></td>

                    </tr>
                    <?php }?>
                </tbody>
                </tbody>
            </table>
        </div>
        </div>
    </div>




<?php }else if($_POST['doc_action'] == 'uploaded_previous_pdf_files'){

   $GET_SERVER_IP= "http://".$_SERVER['SERVER_NAME'];
    $crn=$_POST['crn'];
    $crm_list=$copyRequestModel->getuploaded_previous_pdf_files();
    
   //echo '<pre>';print_r($crm_list);
    ?>
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title print-title">Merge/Split Log : [ C.R.N : <?php echo $crn;?> ] </h4>
        <input type="hidden" name="columns" id="columns" value="[0,1,2,3,4,5]">
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
        <div class="modal-content">

            <table id="reportTable2" class="table table-striped table-hover display">
                <thead>
                <tr>
                    <th width="10%">S.No.</th>
                    <th width="30%">Document</th>
                    <th width="20%">Created By</th>
                    <th width="20%">Change Date</th>
                    <th width="20%">Original Pdf</th>
                </tr>
                <tbody>
                <?php $sno=1; foreach($crm_list as $row){
                    ?>
                    <tr>
                        <td><?=$sno++?></td>
                        <td><?=$row['order_name']."<br>".date("d-m-Y", strtotime($row['order_date'])) ;?></td>
                        <td> <?=$row['created_by'];?></td>
                        <td> <?=date("d-m-Y H:i:s", strtotime($row['created_on'])) ?></td>
                        <td> <a href="<?php echo $GET_SERVER_IP . '/'.$row['path'];?>" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Modal footer -->

<?php }else if($_POST['doc_action'] == 'list_DAA'){ ?>
<!-- Modal content -->
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title print-title">Documents Available in ICMIS in Diary No. : <?=substr($_POST['diary_no'], 0, -4).'-'.substr($_POST['diary_no'], -4);;?> </h4>
        <input type="hidden" name="columns" id="columns" value="[0,1,2]">
        <button type="button" class="close" data-dismiss="modal">×</button>
    </div>
    <div class="modal-body">
    <div class="modal-content">


        <?php


            $condition=$_POST['diary_no'];
            $OLD_ROP=$old_rop_db_name; //$old_rop_db_name = 'rop_text_web_ecp';


            $jud_orderResult=$copyRequestModel->getDataList();
           // $jud_order = "Select  concat('ropor/rop/all/',pno,'.pdf') pdfname,orderDate orderdate from $OLD_ROP.old_rop limit 6";
            
            if (!empty($jud_orderResult)) {
                foreach($jud_orderResult as $row_rop) {
                    $data_list[] = $row_rop;
                    //$path = "/home/judgment/" . $row_rop[pdfname];
                }
            }
            //echo "<pre>"; print_r($data_list);exit();
            ?>
    <table id="reportTable2" class="table table-striped table-hover display">
        <thead>
        <tr>
            <th width="3%">S.No.</th>
            <th width="13%">Judgement Order</th>
            <th width="13%">Date</th>
            <th width="10%">View Document</th>
        </tr>
        <tbody>
        <?php $i=1; foreach($data_list as $row){ ?>
            <tr>
                <td><?=$i++;?></td>
                <td><?php echo $row['judgement_order'];?></td>
                <td><?php echo date('d-m-Y',strtotime($row['orderdate'])); ?></td>
                <td> <a href="<?php echo GET_SERVER_IP . '/'.$row['pdfname'];?>" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    </div>
    </div>
</div>

    <!-- Modal footer -->


<?php }  
else{ 
    echo "wrong flag";
} 
?>

<!--start datatable script-->
<script>
    $(document).ready(function() {
        var title = function () { return $('.print-title').text(); };
        var columns = function () { return $('#columns').val(); };
        $('#reportTable2').DataTable( {

            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    title: title,
                    exportOptions: {
                        columns: columns,
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title:title,
                    exportOptions: {
                        columns: columns,
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title:title,
                    exportOptions: {
                        columns: columns,
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title:title,
                    exportOptions: {
                        columns: columns,
                        stripHtml: true
                    }
                }
            ]
        } );
    } );

</script>

<!--end datatable script-->