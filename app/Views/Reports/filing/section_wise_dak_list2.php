<?php  $uri = current_url(true); ?>
<?= view('header') ?>
 
<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <button type="submit"  id="print" name="print" onclick="printDiv('printable')" class="btn btn-block_ btn-primary">Print</button>
        <a href="<?php echo base_url().'/Reports/Filing/Report/getSectionWiseDakDetails/'.date('Y-m-d', strtotime($for_date))?>">  <span type="button" style="float:right" id="back" name="back" class="btn btn-success">Back</span></a>
            <div id="printable" class="box box-danger">
                <h3 style="text-align: center;"> DAK Report of Section - <?php echo $section;?>    <?php echo date('d-m-Y', strtotime($for_date));?></h3>
                <table  id="ReportFileTrap" class="table table-striped custom-table table-hover dt-responsive">
                    <thead>
                   
                    <tr>
                        <th rowspan='2'>SNo.</th>
                        <th rowspan='2'>Document No.</th>
                        <th rowspan='2'>Doc Description</th>
                        <th rowspan='2'>Diary No</th>
                        <th rowspan='2'>Case No</th>
                        <th rowspan='2'>Cause Title</th>
                        <th rowspan='2'>DA</th>
                    </tr></thead><tbody>
                    <?php 
                    if(!empty($section_wise_dak_data2)){
                        $sno = 1;  $total_dak=0; 
                        foreach($section_wise_dak_data2 as $row):?>
                        <tr>
                            <td><?php echo $sno;?></td>
                            <td><?php echo $row->document;?></td>
                            <td><?php echo $row->docdesc;?></td>
                            <td><?php echo $row->diary_no;?>/<?php echo $row->diary_year;?></td>
                            <td><?php echo $row->case_no;?></td>
                            <td><?php echo $row->cause_title;?></td>
                            <td><?php echo $row->case_da;?></td>
                        </tr>
                    <?php $sno++; endforeach; ?>
                    <?php }else{ ?>
                      <tr><td colspan="100%"> <?php echo "Record Not Found";  ?></td></tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <!-- end of fileTrap -->
        </div>
    </div>
</div>
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
 
