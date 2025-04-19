<?= view('header.php'); ?>

<style>
    fieldset
    {
        border: 1px solid #ddd !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;
    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        width: 35%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #ffffff;
    }
</style>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper1" >
    <!-- Full Width Column -->
    <!--<div class="content-wrapper">
        <div class="container">-->
            <!-- Main content -->
            <section class="content">
            <form method="POST" action="section_report">
                <fieldset class="fieldset">
                    <legend class="legend">Specific Search</legend>
                    <div class="row" style="text-align: center;">
                        <div class="col-xs-4">
                            <select name="section" class="form-control">
                                <option value="0">All Sections</option>
                                <?php
                                foreach ($sections as $val){
                                    echo "<option value='".$val['id']."'>" . $val['section_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div><div class="col-xs-8" style="text-align: left;">
                            <input type="submit" class="btn bg-olive btn-flat">
                        </div></div></fieldset>

                <br/>
            </form>
                <?php
                    if(isset($report) && $report!=false){
                ?>
                <div class="box box-success">
                    <div class="box-header with-border " style="text-align: center;">
                        <h3 class="box-title" id="form-title" >LIST OF FIXED DEPOSIT PENDING CASES</h3>
                    </div>

                    <table class="table table-striped table-hover "id="Sec_Report">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Case No</th>
                            <th>Petitioner Name</th>
                            <th>Respondent Name</th>
                            <th>Section</th>
                            <th>FDR/BG No.</th>
                            <th>A/C No.</th>
                            <th>Amount</th>
                            <th>Bank</th>
                            <th>Deposit Date</th>
                            <th>Maturity/Expiry Date</th>
                            <th>Payment Status</th>
                            <th>Rate of Interest</th>
                            <th>Dealing Assistant</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        setlocale(LC_MONETARY, 'en_IN');

                        $sNo = 1;
                        foreach($report as $row){

                            echo "<tr>
                                <td>$sNo</td>   
                                   <td>".$row['case_number_display']."</td>
                                   <td>".$row['petitioner_name']."</td>                  
                                   <td>".$row['respondent_name']."</td>
                                <td>".$row['section_name']."</td>
                                <td>".$row['document_number']."</td>
                                <td>".$row['account_number']."</td>
                                <td>".money_format('%!i', $row['amount'])."</td>
                                <td>".$row['bank_name']."</td>
                                <td>".date('d-m-Y', strtotime($row['deposit_date']))."</td>
                                <td>".date('d-m-Y', strtotime($row['maturity_date']))."</td> 
                                <td>".$row['status']."</td>
                                <td>".$row['roi']."</td>
                                <td>".$row['da']."</td>
                              </tr>";
                            $sNo++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php }
                else if(isset($report))
                    echo "No record found";?>
            </section>
            <!-- /.content -->
        <!--</div>-->
        <!-- /.container -->
    <!--</div>-->
    <!-- /.content-wrapper -->

</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="<?=base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>


<script src="<?=base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<script src="<?=base_url()?>assets/js/demo.js"></script>
<script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>

<script src="<?=base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?=base_url(); ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url(); ?>assets/plugins/datatables/buttons.print.min.js"></script>

<script>

    $('#Sec_Report').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "scrollY": "50vh",
        "scrollX": true,
        "scrollCollapse": true,
        "footerCallback":"",
        dom: 'Blfrtip',
        buttons: [
            'print'
        ]
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose:'true'
    });
</script>
</body>
</html>
