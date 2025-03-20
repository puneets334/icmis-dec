<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php if(!empty($defectiveMattersNotListed)):?>
                <table  id="ReportFileTrap" class="table table-bordered table-striped">
                    <thead><tr>
                        <th>#</th>
                        <th>Diary<br/>Number</th>
                        <th>Cause Title</th>
                        <th>Filing<br/>Date</th>
                        <th>Defects <br/>Notified On</th>
                        <th>No. of <br/>Delay(in Days)</th>
                        <th>Allotted to</th>
                        <th>Tentative Section</th>
                    </tr></thead><tbody>
                    <?php $sno = 1; foreach($defectiveMattersNotListed as $row):?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $row->diary_no."/".$row->diary_year;?></td>
                            <td><?php echo $row->title;?></td>
                            <td><?php echo date('d-m-Y',strtotime($row->diary_date));?></td>
                            <td><?php echo date('d-m-Y',strtotime($row->save_dt));?></td>
                            <td><?php echo $row->diff;?></td>
                            <td><?php echo $row->name."(".$row->empid.")<br/>".$row->section_name;?></td>
                            <td><?php echo $row->tentative_section;?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: { echo "Record Not Found"; } endif; ?>
            <!-- end of fileTrap -->
        </div>
        <script>
            $(function () {
                $("#ReportFileTrap").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });
        </script>
