<?php  $uri = current_url(true); ?>
<?= view('header') ?>
  <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">Filing >> Scrutiny >> Report >> DAK Report</h3>
                                </div>

                                <div class="col-sm-2">
                                    <div class="custom_action_menu">
                                        <a href="<?= base_url() ?>/Filing/Diary"><button class="btn btn-success btn-sm" type="button"><i class="fa fa-plus-circle" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/search"><button class="btn btn-info btn-sm" type="button"><i class="fa fa-search-plus" aria-hidden="true"></i></button></a>
                                        <a href="<?= base_url() ?>/Filing/Diary/deletion"><button class="btn btn-danger btn-sm" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php
            if(!empty($section_wise_dak_data1)):?>
                <button type="submit"  id="print" name="print" onclick="printDiv('printable')" class="btn btn-block_ btn-primary">Print</button>
                <div id="printable" class="box box-danger">
                <?php $on_date=$for_date ?>
                <h3 style="text-align: center;"> Section wise DAK for  <?php echo date('d-m-Y', strtotime($on_date));?></h3>
                <table  id="ReportFileTrap" class="table table-striped custom-table table-hover dt-responsive">
                    <thead>
                   
                    <tr>
                        <th rowspan='1'>SNo.</th>
                        <th rowspan='1'>Section</th>
                        <th rowspan='1'>Total DAK</th>
                    </tr></thead><tbody>
                    <?php $sno = 1;  $total_dak=0; 
                    foreach($section_wise_dak_data1 as $row):
                        $section = isset($row->section) ? $row->section : '';
                    ?>
                        <tr>
                            <td ><?php echo $sno;?></td>
                            <td><?php echo $section; ?></td>
                            <td>
                                <?php
                                $url= base_url().'/index.php/Reports/Filing/Report/getSectionWiseDAKCaseDetails/'.date('Y-m-d', strtotime($row->dak_date)).'/'.$section;
                                if(!empty($is_excluded_flag)) // for casetype exclude
                                {
                                    $url.='/'.$is_excluded_flag;
                                }
                                if(!empty($section)) // for section
                                {
                                    $url.='/'.$section;
                                }
                                //echo $url;
                                ?>
                                <a target="_self" href="<?=$url;?>"><?=$row->total;?></a>
                            </td>
                        </tr>
                    <?php $sno++; $total_dak+=$row->total; endforeach; ?>
                    <tr style="font-weight: bold;"><td colspan="2">Total</td><td><?= $total_dak?></tr>
                    </tbody>
                </table>
            </div>
            <?php else: { echo "Record Not Found"; } endif; ?>
            <!-- end of fileTrap -->
        </div>
    </div>
</div>


<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
		</div>
</section>   
        <script>
            $(function () {
                $("#ReportFileTrap").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

                });

                function printDiv(printable) {
                     var originalContents = document.body.innerHTML;
                    var printContents = document.getElementById(printable).innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }
        </script>
 <?//=view('sci_main_footer') ?>
