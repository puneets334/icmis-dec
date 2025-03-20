<?php
    if (!empty($receivedData))
    {
        ?>
<div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
    <table  id="reportTable1" class="table table-bordered table-striped datatable_report">

            <thead>
            <tr>
                <th style="width:4%;">SNo.</th>
                <th style="width:6%">Diary No.</th>
                <th style="width:20%;">Postal Number</th>
                <th style="width:15%;">Postal Date</th>
                <th style="width:20%;">Sender Name</th>
                <th style="width:20%;">Address</th>
                <th style="width:10%;">Updated On</th>
                <th style="width: 10%;">Received On</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $s_no = 1;
            foreach ($receivedData as $data) {
                $dno = $data['diary_no'].'/'.$data['diary_year'];
                ?>
                <tr>
                    <td><?=$s_no++;?></td>
                    <td><a href="<?=base_url();?>/RI/ReceiptController/completeDetail/<?=$data['id']?>" target="_blank"><?=$dno?></td>
                    <td><?=$data['postal_no']?></td>
                    <td><?= date("m-d-Y", strtotime($data['postal_date'])); ?></td>
                    <td><?= $data['sender_name'] ?></td>
                    <td><?= $data['address']?></td>
                    <td><?= $data['updated_on']?></td>
                    <td><?= $data['received_on'] ?></td>
                </tr>
                <?php

//                die;
            }
            //            ?>
            </tbody>
        </table>
</div>
        <?php
    } else {
        ?>
        <div class="form-group col-sm-12">
            <h4 class="text-danger" style="margin-left: 40%;">No Record Found!!</h4>
        </div>

    <?php }
//}

?>
<script>

    $(function () {
        $(".datatable_report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

    });

</script>
