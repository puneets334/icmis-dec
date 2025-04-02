<div class="row">
    <div class="col-md-12">

            <br>
        <h2 align="center">PIL Updation Between  <?php echo !empty($first_date)?date('d-m-Y',strtotime($first_date)):'';?> to <?php echo !empty($to_date)?date('d-m-Y',strtotime($to_date)):'';?></h2>

        <br>
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
            <table  id="reportTable1" style="width: 100%" class="table table-bordered table-striped datatable_report custom-table">
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
 


        $(document).ready(function() {
    $('#reportTable1').DataTable({
        dom: 'Bfrtip',
        "pageLength": 15,
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                title: 'PIL Updation Between  Between  <?php echo !empty($first_date)?date('d-m-Y',strtotime($first_date)):'';?> to <?php echo !empty($to_date)?date('d-m-Y',strtotime($to_date)):'';?>', // Ensuring no unwanted title appears
                customize: function (win) {
                    $(win.document.body).css('text-align', 'center'); // Align all content centrally
                    
                }
            },
            'pageLength'
        ],
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ]
    });
});


 

</script>
    