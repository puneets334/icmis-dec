<?= view('header') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">RECORD ROOM >> Case Allotment</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>


                    <div class="container-fluid">
                        <div class="row">
                            <br>

                            <?= session()->getFlashdata('msg'); ?>
                            <!-- <form class="form-horizontal" id="push-form" method="post" action="<?= base_url('Record_room/FileTrap/receiveDispatchReport'); ?>"> -->
                                <input type="hidden" name="usercode" id="usercode" value="<?= session()->get('login')['usercode']; ?>">
                                <?= csrf_field(); ?>
                                <div class="form-group">
                                    <div class="col-sm-12">

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="col-form-label">Select Allotment Category:</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rptAllotmentType" value="1" checked>
                                                    <label class="form-check-label">Hall wise</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="rptAllotmentType" value="2">
                                                    <label class="form-check-label">Users/DA wise</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="case_type" class="col-form-label">Case Type:</label>
                                                <select class="form-control" id="case_type" name="case_type" tabindex="1">
                                                    <option value="">Select Case Type</option>
                                                    <?php foreach ($case_type as $type): ?>
                                                        <option value="<?= $type['casecode']; ?>"><?= $type['skey'] . ' :: ' . $type['casename']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="caseNOFrom" class="col-form-label">Case No From:</label>
                                                        <input type="text" class="form-control" id="caseNOFrom" name="caseNOFrom" tabindex="1">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="caseNOTo" class="col-form-label">Case No To:</label>
                                                        <input type="text" class="form-control" id="caseNOTo" name="caseNOTo" tabindex="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="fromDate" class="col-form-label">Date From:</label>
                                                <input type="text" id="fromDate" name="fromDate" class="form-control datepick" placeholder="From Date">
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="toDate" class="col-form-label">Date To:</label>
                                                <input type="text" class="form-control datepick" id="toDate" name="toDate" placeholder="To Date">
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="submit" id="btnGetCases" class="btn btn-info btn-block" style="margin-top: 36px;">Get Cases</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <!-- </form> -->
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

        $('a').click(function() {
            var id = $(this).attr('id');

            $.ajax({
                    type: 'POST',
                    url: "<?= base_url() ?>index.php/FileTrap/caseTimeline",
                    beforeSend: function(xhr) {
                        $("#divCaseTimeline").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?= base_url() ?>../images/load.gif'></div>");
                    },
                    data: {
                        diaryNo: id
                    }
                })
                .done(function(result) {

                    $("#divCaseTimeline").html(result);

                })
                .fail(function() {
                    alert("ERROR, Please Contact Server Room");
                });
        });
        $('#reportTable1').DataTable({
            /* dom: 'Bfrtip',
             buttons: [
                 'excelHtml5',
                 'pdfHtml5'
             ]*/

            "bProcessing": true,
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                {
                    extend: 'pdfHtml5',
                    pageSize: 'A3',
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            margin: [0, 0, 0, 5],
                            alignment: 'center',
                            image: ''
                        });
                        doc.watermark = {
                            text: 'SUPREME COURT OF INDIA',
                            color: 'blue',
                            opacity: 0.05
                        }
                    }
                }

            ]

        });
    });
</script>

<?php die; ?>