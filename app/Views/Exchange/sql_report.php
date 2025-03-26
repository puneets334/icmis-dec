<?= view('header') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Listed Cases File Movement</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="mrgB10" id="m">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="w-auto" class="text-left">Transaction Date: From</label>
                                        <input type="text" id="fromDate" name="fromDate" class="form-control dtp" required placeholder="From Date" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dyr" class="text-left">To</label>
                                        <input type="text" id="toDate" name="toDate" class="form-control dtp" required placeholder="To Date" autocomplete="off">
                                    </div>
                                    <div class="col-md-3 pt-4">
                                        <button type="button" class="btn btn-primary" id="save" onclick="check()">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div id="d1" class="mrgT20"></div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    function check() {

        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        var dt = document.getElementById("fromDate").value;
        var dt1 = dt.split("-");
        var dt_new1 = dt1[2] + "-" + dt1[1] + "-" + dt1[0];
        var dt_chk1 = dt1[0] + dt1[1] + dt1[2]; //Date check
        var dt2 = document.getElementById("toDate").value;
        var dt21 = dt2.split("-");
        var dt_new2 = dt21[2] + "-" + dt21[1] + "-" + dt21[0];
        var dt_chk2 = dt21[0] + dt21[1] + dt21[2];
        if (fromDate == "") {
            alert("Enter Recieved date.");
            $("#fromDate").focus();
            return false;
        } else if (toDate == "") {
            alert("Enter Completion date.");
            $("#toDate").focus();
            return false;
        } else if (dt_new1 > dt_new2) {
            alert("Re-enter date.");
            $("#toDate").focus();
            return false;
        } else {
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            //var url = 'Sql_report1_process.php';
            var url = "<?= site_url('Exchange/causeListFileMovement/processReport') ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    FDate: fromDate,
                    TDate: toDate,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                beforeSend: function() {

                },
                success: function(data) {
                    $('#d1').html(data);
                    updateCSRFToken();
                },
                error: function() {
                    alert("ERROR");
                    updateCSRFToken();
                }

            });
        }
    }


    function loadData(fromDate, toDate) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            url: "<?= site_url('Exchange/causeListFileMovement/processReport') ?>",
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                FDate: fromDate,
                TDate: toDate
            },
            // beforeSend: function() {
            //     updateCSRFToken();
            //     $('#data').html('<table width="100%" align="center"><tr><td><img src="<?= base_url('images/load.gif') ?>"/></td></tr></table>');
            // },
            success: function(response) {

                updateCSRFToken();
                if (response.success) {


                    let data = response.data;
                    let tableBody = '';

                    data.forEach((row, index) => {
                        tableBody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${row.transaction_date}</td>
                                <td>${row.s1}</td>
                                <td>${row.s2}</td>
                                <td>${row.s3}</td>
                                <td>${row.s4}</td>
                                <td>${row.s5}</td>
                            </tr>
                        `;
                    });

                    $('#table1 tbody').html(tableBody);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function() {
                alert("An error occurred.");
            }
        });
    }
</script>