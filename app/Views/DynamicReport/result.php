<?= view('header') ?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-5">
                    <div id="printable">
                        <center>
                            <h2><b>SUPREME COURT OF INDIA</b></h2>
                            <?php echo " <h4> (Result generated through Dynamic Report on " . date('d-m-Y') . " at " . date('H:i:s A') . " )</h4>"; ?>
                        </center>
                        <fieldset>
                            <legend style="border-bottom: black;"><u>Filtering Criteria</u></legend>
                            <?php echo $criteria ?>
                        </fieldset>
                        <hr style="height:1px;border-width:0;color:black;background-color:grey;">
                        <div id="disp" class=" tableContainer">
                            <?php if (isset($result) && $result != false && $option == "2") { ?>
                                <table id="tblCasesUploadStatus" class="table table-striped table-hover table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Diary No/Case No.</th>
                                            <th>Cause Title</th>
                                            <th>Filing Date</th>
                                            <th>Registration Date</th>
                                            <th><?php if ((int)$showDA == 1) { ?>Dealing Assistant-Section<?php } else { ?>Section <?php } ?></th>
                                            <th>Matter Type</th>
                                            <th>Agency State</th>
                                            <th>Agency Name</th>
                                            <th>Subject</th>
                                            <th>Case Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s_no = 1;
                                        foreach ($result as $case) {
                                            ?>
                                            <tr>
                                                <td data-key="S. No."><?php echo $s_no ?></td>
                                                <td data-key="Diary No/Case No."><?php echo substr($case['diary_no'], 0, -4) . '/' . substr($case['diary_no'], -4) . " - " . $case['reg_no_display']; ?></td>
                                                <td data-key="Cause Title"><?php echo $case['pet_name'] . "<b> VS </b>" . $case['res_name']; ?></td>
                                                <td data-key="Filing Date">
                                                    <?php
                                                    if ($case['diary_no_rec_date'] === "0000-00-00 00:00:00")
                                                        echo "";
                                                    else
                                                        echo  date('d-m-Y', strtotime($case['diary_no_rec_date'])); ?>
                                                </td>
                                                <td data-key="Registration Date">
                                                    <?php
                                                    if ($case['active_fil_dt'] === "0000-00-00 00:00:00")
                                                        echo "";
                                                    else
                                                        echo date('d-m-Y', strtotime($case['active_fil_dt'])); ?></td>
                                                <td data-key="<?php if ((int)$showDA == 1) { ?>Dealing Assistant-Section<?php } else { ?>Section <?php } ?>">
                                                    <?php
                                                    if ((int)$showDA == 1) 
                                                        echo $case['name'] . " (" . $case['empid'] . ") -" . $case['section_name'];
                                                    else 
                                                        echo $case['section_name'];
                                                    ?>
                                                </td>
                                                <td data-key="Matter Type">
                                                    <?php
                                                    if ($case['mf_active'] == "M")
                                                        echo "Miscellaneous";
                                                    elseif ($case['mf_active'] == "F")
                                                        echo "Regular";
                                                    ?>
                                                </td>
                                                <td data-key="Agency State"><?php echo $case['agency_state']; ?></td>
                                                <td data-key="Agency Name"><?php echo $case['agency_name']; ?></td>
                                                <td data-key="Subject"><?php echo $case['subject']; ?></td>
                                                <td data-key="Case Status">
                                                    <?php
                                                    if ($case['c_status'] == "P")
                                                        echo "Pending";
                                                    else
                                                        echo "Disposed <br>(Order date: " . date('d-m-Y', strtotime($case['ord_dt'])) . ")";
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $s_no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } else if ($option == "1") { ?>
                                <div>
                                    <font style="font-size: 18px; font-weight: bold;"> Total number of such matters are : </font>
                                    <font style="font-size:18px;"><?php echo $result; ?></font>
                                    <button style="margin-right: 20px; float:right; width:60px; height:40Px; background-color: lightgrey;" type="submit" id="Print" name="Print" onclick="hideButton();printDiv('printable')"><b>Print</b></button>
                                </div>
                            <?php } else { ?>
                                <center> <span style="color: red;"><b>No Data Found !!</b></span></center>
                            <?php } ?>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $.extend($.fn.dataTable.defaults, {
                                    "buttons": [
                                        $.extend(true, {}, {
                                            extend: 'pdfHtml5',
                                            title: 'Sports Event Report',
                                            filename: 'Sports_Event_Report'
                                        })
                                    ]
                                });
                                $('#tblCasesUploadStatus').DataTable({
                                    //dom: 'Bfrtip',
                                    dom: 'B<"top"lfip>rt<"bottom"ip><"clear">',
                                    paging: true,
                                    "lengthMenu": [5, 10, 20, 50, 100],
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": true,
                                    // "scrollY": "50vh",
                                    "scrollX": true,
                                    "scrollCollapse": true,
                                    "footerCallback": "",
                                    buttons: [{
                                        extend: 'print',
                                        title: '',
                                        footer: '',
                                        header: '',
                                        messageTop: function() {
                                            return '<?php echo "<center><h2><b>Supreme Court Of India</b></h2><h4> (Result generated through Dynamic Report on " . date('d-m-Y') . " at " . date('H:i:s A') . " )</h4></center><br/><u>Conditions Selected</u><br/>" . $criteria; ?>';
                                        },
                                        messageBottom: null
                                    },
                                    {
                                        extend: 'pdf',
                                        title: '',
                                        exportOptions: {
                                            columns: ':visible',
                                        },
                                        orientation: 'landscape',
                                        pageSize: 'A4',
                                        customize: function (doc) {
                                            doc.pageMargins = [10, 10, 10, 10];
                                            doc.content.unshift({
                                                text: 'Supreme Court Of India',
                                                style: 'header'
                                            });
                                            doc.styles = {
                                                header: {
                                                    fontSize: 18,
                                                    bold: true,
                                                    margin: [0, 0, 0, 10]
                                                }
                                            };
                                        }
                                    }
                                    ]
                                });
                            });
                            function hideButton() {
                                document.getElementById('Print').style.visibility = 'hidden';
                            }
                            function printDiv(printable) {
                                var printContents = document.getElementById(printable).innerHTML;
                                document.body.innerHTML = printContents;
                                window.print();
                                document.body.innerHTML = originalContents;
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>