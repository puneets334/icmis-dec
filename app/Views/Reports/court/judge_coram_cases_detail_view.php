<?php
$uri = current_url(true);
?>
<?= view('header') ?>

    <div class="card">
        <div class="card-body" >
            <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <?php
                 if(isset($judge_coram_result) && sizeof($judge_coram_result)>0 ):
                    ?>
                    <table  id="ReportFileTrap" class="table table-bordered table-striped">
                        <thead>
                        <h3 style="text-align: center;"><?php echo $title;?></h3>
                        <tr>
                            <th>Sno.</th>
                            <th>Reg./Diary</th>
                            <th>Cause Title</th>
                            <th>Sub. Category</th>
                            <th>Purpose of Listing</th>
                            <th>Coram</th>
                            <th>Last Order</th>
                            <th>Remark</th>
                            <th>Section/DA</th>
                        </tr></thead><tbody>
                        <?php  $i = 0;
                        $total = 0;
                        foreach ($judge_coram_result as $row) {
                            $i++;?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td>
                                    <?php
                                    $coram = $row['coram'];
                                    if ($row['reg_no_display'] == "") {
                                        $comlete_fil_no_prt = "Diary No. " . substr_replace($row['diary_no'], '-', -4, 0);
                                    } else {
                                        $comlete_fil_no_prt = $row['reg_no_display'] . " @ " . substr_replace($row['diary_no'], '-', -4, 0);
                                    }
                                    //   }
                                    echo $comlete_fil_no_prt;

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $row['pet_name'] . " <b>Vs.</b>" . $row['res_name'];
                                    ?>
                                </td>

                                <td <?php if (empty($row['cat1']) or $row['cat1'] == 331) { ?> style="background-color: #ff1e2c;" <?php } ?>> <?php if ($row['cat1']) {
                                        f_get_cat_diary_basis($row['cat1']);
                                    } ?></td>

                                <td><?php echo $row['purpose']; ?></td>

                                <td><?php if ($coram != 0) {
                                        echo f_get_judge_names_inshort($coram);
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php echo $row['lastorder']; ?>
                                </td>
                                <td>
                                    <?php
                                    f_get_ntl_judge($row['diary_no']);
                                    f_get_ndept_judge($row['diary_no']);
                                    f_get_category_judge($row['diary_no']);
                                    f_get_not_before($row['diary_no']);
                                    $rgo_default = f_cl_rgo_default($row['diary_no']);
                                    if ($rgo_default != 0) {
                                        echo "<br/>Not to list till dispose of $rgo_default";
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    f_get_section_name_fdno($row['diary_no']);
                                    f_get_user_name_fdno($row['diary_no']);
                                    ?></td>
                            </tr>
                        <?php  } ?>
                        </tbody>
                    </table>
                <?php  endif; ?>
                <!-- end of fileTrap -->
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var title = "<?php echo $title;?>";
            $("#ReportFileTrap").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL', title: title},{extend: 'print', title: title },
                    { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
            }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

        });
    </script>
<?=view('sci_main_footer') ?>