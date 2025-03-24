<div class="row">
    <div class="col-md-12">

            <br>
        <h2 align="center">PIL Updation Between  Between  <?php echo !empty($first_date)?date('d-m-Y',strtotime($first_date)):'';?> to <?php echo !empty($to_date)?date('d-m-Y',strtotime($to_date)):'';?></h2>

        <br>
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
            <table  id="reportTable11" style="width: 100%" class="table table-bordered table-striped datatable_report">
                <?php
                if(!empty($reportType))
                {
                    if($reportType=='C'){
                        ?>
                        <thead>
                        <tr>
                            <th>Worked On</th>
                            <th>User(Emp Id)</th>
                            <th>Total Cases</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                         if(!empty($pil_result)) 
                         {
                            foreach ($pil_result as $result)
                            {
                                //echo date('d-m-Y', strtotime($result['updated_date']));
                               // echo $result['updated_date'];
                                //pr($result);
                                ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($result['updated_date'])); ?></td>
                                    <td><?= $result['name']; ?>(<?= $result['empid']; ?>)</td>
                                    <td><a href="<?=base_url();?>/PIL/PilController/getWorkDone/<?php echo $result['updated_date']?>/<?=$result['adm_updated_by']?>" target="_blank" ><?= $result['total_cases']; ?></a></td>
                                </tr>

                                <?php
                            }
                        }else{
                        ?>
                        <tr><td colspan="100%">No record found...</td></tr>


                        <?php } ?>
                        </tbody>
                        <?php
                    }
                }
                ?>
            </table>
        </div>

        </div>
</div>
<script>
$(function () {
            $(".datatable_report").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,"ordering": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                    { extend: 'colvis',text: 'Show/Hide'}],"processing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        });

</script>
    