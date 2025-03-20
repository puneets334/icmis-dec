    <?php
    if(!empty($pil_result)) {
        ?>
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
                        foreach ($pil_result as $result)
                        {
//                            echo "<pre>";
//                            print_r($result);die;
                            ?>
                            <tr>
                                <td><?= date('d-m-Y', strtotime($result['date'])); ?></td>
                                <td><?= $result['name']; ?>(<?= $result['empid']; ?>)</td>
                                <td><a href="<?=base_url();?>/PIL/PilController/getWorkDone/<?=$result['date']?>/<?=$result['adm_updated_by']?>" target="_blank" ><?= $result['total_cases']; ?></a></td>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
        <?php
    }
    ?>
