    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($dreports)):?>
                <table id="ReportVec" class="query_builder_report table table-bordered table-striped">
                <thead>
                                        <h3 style="text-align: center;">User wise Defect Report on <?php echo date("d-m-Y", strtotime($on_date)); ?></h3>
                                        <tr>
                                            <th rowspan='2'>SNo.</th>
                                            <th rowspan='2'>Scrutiny User</th>
                                            <th rowspan='2'>No. of files</th>
                                        </tr>
                        
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [diary_no] => 224272014 [dacode] => 726 [section] => 20 [section_name] => II [short_description] => SLP(Crl) No. [fil_no] => -005984-005992 [fil_dt] => 2014 [pet_name] => JALADI MOSES AND ORS. ETC. [res_name] => POLURU PRASADA REDDY AND ORS. ETC. )
                    $total=0;
                    $total_diary=0;
                    foreach($dreports as $row): //print_r($row); exit; 
                    //$diary_no = $row['diary_no'];?>
                    <tr>
                    <td><?php echo $sno++;?></td>
                    <td><?php echo $row['name'];?></td>
                    <td><a target="_blank" href="<?php echo base_url() ?>/Reports/Filing/Filing_Reports/scrutiny_user_wise_detail_report/<?= $row['user_code'];?>/<?=$row['save_date'];?>/<?=str_replace(array(' ','.'),'_',$row['name']);?>"> <?=$row['total'];?></a></td>
                    </tr>
                    
                    <?php $total_diary+=$row['total']; endforeach; ?>
                    <tr style="font-weight: bold;"><td colspan="2">Total</td><td><?= $total_diary?></tr>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
        <script>

            
$(function () {
$("#ReportVec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>

                                       