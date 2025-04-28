<?= view('header.php'); ?>

<style>
    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table.dataTable>thead .sorting_disabled,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    table tfoot tr th {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title"><?= $title_head ?? '';?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">


<div id="printable" class="box-body">

    <?php if (is_array($case_result)) {
    ?>
       <table id="reportTable1" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Number.</th>
                    <th>Registered Number</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($case_result as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_no'] . "/" . $result['diary_year']; ?></td>
                        <td><?php echo $result['reg_no_display']; ?></td>
                        <td><?php echo $result['cause']; ?></td>
                        <td><?php echo $result['name']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                }
                ?>
            </tbody>
        </table>

    <?php
    }
    if (is_array($case_result_unregistered)) {
    ?>
        <table id="reportTable1" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Number.</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
                <?php
                $s_no = 1;
                foreach ($case_result_unregistered as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_no'] . "/" . $result['diary_year']; ?></td>
                        <td><?php echo $result['cause']; ?></td>
                        <td><?php echo $result['name']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                }   
                ?>
            </tbody>
        </table>
    <?php
    }
    if (is_array($Unregistered_C_List)) {
        
    ?>
        <table id="reportTable1" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Number.</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
               </tr>
            </thead>
          <tbody>
                <?php
                $s_no = 1;
                foreach ($Unregistered_C_List as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_number'] . "/" . $result['diary_year']; ?></td>
                        <td><?php echo $result['cause']; ?></td>
                        <td><?php echo $result['name']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                }   
                ?>
            </tbody>
        </table>
    <?php
    }

    if (is_array($state_year_result)) {
    ?>
        <h3 style="text-align: center;"> <?php echo $state ?> <?php echo $diary_year ?> PENDING MATTERS OF SECTION <?php echo $sec ?> AS ON:
            <strong></strong> <?php echo date("d-m-Y"); ?>
        </h3>

        <table width="90%" id="reportTable1" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Number</th>
                    <th>Reg. Number</th>
                    <th>State</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($state_year_result as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_number'] . "/" . $result['diary_year']; ?></td>
                        <td><?php echo $result['reg_no_display']; ?></td>
                        <td><?php echo $result['state']; ?></td>
                        <td><?php echo $result['cause']; ?></td>
                        <td><?php echo $result['name']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                }  
                ?>
            </tbody>
        </table>
    <?php
    }


    if (is_array($Registered_C_List)) {
    ?>
       <table id="reportTable1" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>D.Number.</th>
                    <th>Registration No.</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $s_no = 1;
                foreach ($Registered_C_List as $result) {
                ?>
                    <tr>

                        <td><?php echo $s_no; ?></td>
                        <td><?php echo $result['diary_number'] . "/" . $result['diary_year']; ?></td>
                        <td><?php echo $result['reg_no_display']; ?></td>
                        <td><?php echo $result['cause']; ?></td>
                        <td><?php echo $result['name']; ?></td>

                    </tr>
                <?php
                    $s_no++;
                } 
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>

</div>


   </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        var table = $("#reportTable1").DataTable({
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 20,
            "buttons": [
                {
                    extend: 'excel',
                    title: '<?= $title_head ?>',
                    filename: '<?= $title_head ?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'Orientation',
                    pageSize: 'LEGAL',
                    title: '<?= $title_head  ?>',
                    filename: '<?= $title_head ?>'
                }
            ],
            "processing": true,
            "ordering": true,
            "paging": true
        });

        table.buttons().container().appendTo('#reportTable1_wrapper .col-md-6:eq(0)');
    });
</script>