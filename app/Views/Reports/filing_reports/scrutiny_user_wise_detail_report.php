  <!-- DataTables -->
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/admin.min.css'); ?>">
<body class="hold-transition sidebar-mini">

<div class="wrapper" >
                <div class="content-wrapper">
<br><br>
                    <div class="container">

                        <!-- Main content -->
                        <section class="content">

                            <?php
                            if(isset($case_result) && sizeof($case_result)>0 && is_array($case_result))  {
                            ?>
                            <div class="box-footer">
                                <form>
                                    <button type="submit"  style="width:15%;float:left" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                </form>
                            </div>
                            <div id="printable" class="box box-danger">

                                <table id="ReportVec" class="query_builder_report table table-bordered table-striped" align="center">
                                    <thead>
                                    <?php $name1=str_replace('_',' ',$name);?>
                                    <h3 style="text-align: center;"> Defect entered by <?php echo $name1;?>  on  <?php echo date('d-m-Y', strtotime($on_date));?></h3>
                                    <tr>
                                        <th rowspan='2'>SNo.</th>
                                        <th rowspan='2'>Diary No.</th>
                                        <th rowspan='2'>Case Type</th>
                                        <th rowspan='2'>Cause Title</th>
                                        <th rowspan='2'>Filing Date</th>
                                        <th rowspan='2'>No. of Defects</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    $total_diary=0;
                                    foreach ($case_result as $result)
                                    :$i++; //print_r($result);exit;
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $result['diaryno'];?></td>
                                            <td><?php echo $result['casetype'];?></td>
                                            <td><?php echo $result['causetitle'];?></td>
                                            <td><?php echo date('d-m-Y', strtotime($result['filingdate']));?></td>
                                            <td><?php echo $result['total_defect_count'];?></td>

                                        </tr>
                                        <?php
                                        //$total_diary+=$result['Total'];
                                    endforeach;
                                    ?>

                                    </tbody>
                                    <tfoot></tfoot>
                                </table>

                                <?php } ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
                                
            <!-- Report Div End -->
            <script src="<?php echo base_url('assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>

            <script>
                $(function () {
$("#ReportVec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});

function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
            </script>


</body>
</html>
