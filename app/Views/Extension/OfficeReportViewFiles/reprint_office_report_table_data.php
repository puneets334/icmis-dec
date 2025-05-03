<style>
    html,
    body {
        height: auto;
    }

    table.dataTable>thead .sorting,
    table.dataTable>thead {
        background-color: #0d48be !important;
        color: #fff !important;
        white-space: nowrap;
    }

    /* td a {
         display:inline-block;
         min-height:100%;
         width:100%;
         color: #0c0c0c;
     }*/
    @media print {
        td a {
            display: inline-block;
            min-height: 100%;
            width: 100%;
            color: #0c0c0c;
        }

        a[href]:after {
            content: none !important;
        }
    }
</style>







<?php
//           if(!empty($fdate)) print_r($fdate);echo">>";
//           if(!empty($tdate)) print_r($tdate);die;die;

if (!empty($table_record_display)) {
    //                                 echo "<pre>";
    //                                  print_r($table_record_display);die;
?>
    <!--                    <h4>LIST OF OFFICE REPORT GENERATED THROUGH ICMIS SOFTWARE</h4>-->
    <div class="table-responsive">
        <table id="datatable_report" class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>Sno.</th>
                    <th>Diary No.</th>
                    <th>Case No.</th>
                    <th>Issue Date</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Office report</th>
                    <th>Summary</th>
                    <th>Discard</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                $rec = '';
                foreach ($table_record_display as $row) {
                    //                    echo "<pre>";
                    //                   print_r($row);
                    //                   die;
                    //                    $rec = date('d-m-Y',strtotime($row['rec_dt']));
                    //                    echo gettype($rec);die;

                ?>
                    <tr>
                        <td><?= $sno; ?></td>
                        <td id="dno<?php echo $sno; ?>"><?= substr($row['diary_no'], 0, -4) . '-' . substr($row['diary_no'], -4); ?></td>
                        <td><?= $row['reg_no_display']; ?></td>
                        <td id="rec<?php echo $sno; ?>"><?= date('d-m-Y', strtotime($row['rec_dt'])); ?></td>
                        <td><?= date('d-m-Y', strtotime($row['order_dt'])) ?></td>
                        <td> <?php
                                if (trim($row['display']) == 'N') {
                                    echo "<span style='color:red'>Deleted</span>";
                                }
                                if ($row['display'] == 'Y') {
                                    if (($row['web_status'] == 1)) {
                                        echo "Uploaded";
                                    } else if (($row['web_status'] == 0)) {
                                        echo "Saved ";
                                    }
                                }
                                ?></td>
                        <td><?php
                            $dd = substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4);
                            $path = "../officereport/" . $dd . "/" . $row['office_repot_name'];
                            //                            echo $path;die;
                            $checkPath = str_contains($path, '.pdf');
                            if ($checkPath) {
                                echo "<a href ='$path' >View</a>";
                            } else {
                                echo "<a href ='$path' >View_html file</a>";
                            }


                            //                           if(!empty($path_name))
                            //                            {
                            //                               print_r($path_name);die;
                            //                               $pathName = explode('>>',$path_name);
                            //                               $path = array_filter($pathName);
                            //                               print_r( $path);
                            //                            }
                            ?></td>
                        <td><?= $row['summary']; ?></td>
                        <td> <?php
                                if ($row['display'] == 'Y') {
                                ?>
                                <input type="button" class="btn-sm btn btn-primary" name="btn_<?php echo $sno; ?>" id="btn_<?php echo $sno; ?>" value="Discard" onclick="discardFunc(this.id)" />
                            <?php
                                } else
                                    echo "<span style='color:red'>Deleted office Report</span>";
                                $sno++;

                            ?>
                        </td>

                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
}
?>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>
<script>
    $(function() {
        $("#datatable_report").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": true,
            pageLength :15,
            "buttons": [
                {
                    extend: 'excel',
                    title: 'Office Report<?php echo date("d-m-Y h:i:s");?>',
                    filename: 'Office-Report<?php echo date("d-m-Y h:i:s");?>'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    // pageSize: 'landscape',
                    title: 'Office Report <?php echo date("d-m-Y h:i:s");?>',
                    filename: 'Office-Report<?php echo date("d-m-Y h:i:s");?>'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#datatable_report_wrapper .col-md-6:eq(0)');

    });

    function discardFunc(id) {
        console.log(id);
        var rec = id.split("_");
        //var d_no= '<?php //echo $_SESSION['filing_details']['diary_no']; 
                        ?>//';

        var rec_dt = document.getElementById('rec' + rec[1]).innerText;
        var text = document.getElementById('dno' + rec[1]).innerText;
        var dno = text.split('-').join('');

        var from_date = $("#txtFromDate").val();
        var to_date = $('#txtToDate').val();

        if (confirm("Do you really want to discard office report  ? ")) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('Extension/OfficeReport/reprint_discard_data'); ?>",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    dno: dno,
                    recdt: rec_dt
                },
                success: function(data) {
                    //updateCSRFToken();
                    alert(data);
                    // location.reload();
                    // updateCSRFToken();
                    callReprintAgain(from_date, to_date, dno);


                },
                error: function(data) {
                    updateCSRFToken();
                    alert(data);

                }

            });
        } else {
            return;
        }


    }

    async function callReprintAgain($fdate, $tdate, dno) {
        await updateCSRFTokenSync();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Extension/OfficeReport/reprint'); ?>",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                fdate: '<?php if (!empty($fdate)) print_r($fdate);
                        else echo ''; ?>',
                tdate: '<?php if (!empty($tdate)) print_r($tdate);
                        else echo ''; ?>',
                dno: dno,

            },
            success: function(data) {
                updateCSRFToken();
                $("#table_display").html(data);
                // location.reload();
            },
            error: function(data) {
                updateCSRFToken();
                alert(data);

            }

        });
    }
</script>