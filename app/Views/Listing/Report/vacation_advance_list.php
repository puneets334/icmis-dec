<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Vacation Advance List &nbsp;as on
                                    <strong><?= date('d-m-Y h:m:s A'); ?></strong>
                                </h3>
                            </div>
                      
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div id="printable" class="box box-danger">
                            <h3 style="text-align: center;">Vacation Advance List as on <strong><?=date('d-m-Y h:m:s A');?></strong></h3>

                                <div class="table-responsive p-3">
                                    <table id="reportTable1" class="table table-striped table-hover">

                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" style="width:4%;">S.No.</th>
                                                <th scope="col">Case No</th>
                                                <th scope="col">Cause Title</th>
                                                <th scope="col">Filing Date</th>
                                                <th scope="col">Section</th>
                                                <th scope="col">Listing Date</th>
                                                <th scope="col">Ready / Not Ready</th>
                                                <th scope="col">Subject Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $s_no = 1;
                                            foreach ($result_array as $result) {
                                            ?>
                                                <tr>
                                                    <td><?= $s_no; ?></td>
                                                    <td><?= $result['case_no']; ?></td>
                                                    <td><?= $result['cause_title']; ?></td>
                                                    <td><?= $result['filing_date']; ?></td>
                                                    <td><?= $result['section']; ?></td>
                                                    <td><?= $result['next_date']; ?></td>
                                                    <td><?= $result['status']; ?></td>
                                                    <td><?= $result['subject_category']; ?></td>
                                                </tr>
                                            <?php
                                                $s_no++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
</section>

<script>
    $(document).ready(function() {

        $(function() {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            }).datepicker("setDate", new Date());;
        });
        var reportTitle = "Vacation Advance List as on <?= date('d-m-Y h:m:s A'); ?>";
        $('#reportTable1').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": [
            {
            extend: 'excelHtml5',
            title: reportTitle
            },
            {
            extend: 'pdfHtml5',
            pageSize: 'A3',
            title: reportTitle
            }
            ]
        

        });
    });
</script>