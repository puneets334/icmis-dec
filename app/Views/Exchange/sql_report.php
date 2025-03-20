<?= view('header') ?>


<style>
    #dv_content1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Horizontal center alignment */
        justify-content: center;
        /* Vertical center alignment */
       // height: 20vh;
        /* Full viewport height for centering */
        text-align: center;
    }

    #m {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Horizontal center alignment for inputs */
        gap: 15px;
        /* Space between form elements */
    }

    label {
        font-weight: bold;
    }

    input[type="text"] {
        padding: 10px;
        width: 200px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 15px;
    }

    button:hover {
        background-color: #45a049;
    }

    h3 {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<form method="post" action="<?= site_url('Exchange/causeListFileMovement/processReport') ?>">
    <?= csrf_field() ?>
    <div id="dv_content1">
        <h3>Listed Cases File Movement</h3>
        <div id="m">
            <label for="from" class="text-right">Transaction Date: From</label>
            <input type="date" id="fromDate" name="fromDate" class="" required placeholder="From Date" value="<?= esc($fromDate) ?>">

            <label for="from" class="text-right">To</label>
            <input type="date" id="toDate" name="toDate" class="" required placeholder="To Date" value="<?= esc($toDate) ?>">
          

            <button type="button" id="save" onclick="loadData()">Submit</button>
        </div>
        <br><br>
        <!-- <div class="center" id="d1"></div>
        <div class="data" id="data"> -->
    </div>
    <table id="table1" class="display">
        <thead>
            <tr>
                <th>Sno.</th>
                <th>Transaction Date</th>
                <th>Sent to CM(NSH)</th>
                <th>Received by CM(NSH)</th>
                <th>Refused to receive by CM(NSH)</th>
                <th>Sent Back to Dealing Assistant</th>
                <th>Received by Dealing Assistant</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</form>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
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