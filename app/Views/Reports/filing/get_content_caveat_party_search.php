<div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">
    <?php if(!empty($ReportsofcaveatPartySearch)) {?>
        <table  id="query_builder_report" class="query_builder_report table table-bordered table-striped">
            <thead>
            <tr>
                <th>SNo. </th>
                <th>Caveat No.</th>
                <th>Cause Title /Party Details </th>
                <th>Petitioner/Respondent </th>
            </tr>
            </thead><tbody>
            <?php
            $sno = 1;
            foreach($ReportsofcaveatPartySearch as $row):?>
                <tr>
                    <td><?= $sno++ ?></td>
                    <td><?= $row->diary_no;?></td>
                    <td><?= $row->partyname;?></td>
                    <td><?= $row->pet_res; ?></td>

                </tr>
            <?php endforeach; ?>
            </tbody>


        </table>
    <?php }else {?>
        <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
    <?php }?>

</div>
<script>

    $(function () {
        $("#query_builder_report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });


</script>


