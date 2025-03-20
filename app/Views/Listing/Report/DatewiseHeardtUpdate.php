 <?=view('header') ?>
 <style>
    @media print {
    body {
        margin: 0; /* Remove body margins */
    }
    #printable { /* Target the printable element */
        width: 100%; /* Ensure full width */
        overflow: visible; /* Prevent overflow */
    }
    table {
        width: 100% !important; /* Force table to be full width */
        border-collapse: collapse;  /* Collapse borders for better layout */
    }
    table, th, td {
        border: 1px solid black; /* Add borders for printing */
        padding: 5px;
    }
    th, td {
        min-width: 50px; /* Set a minimum width for columns */
        width: auto; /* Let columns adjust width */
        word-wrap: break-word; /* Prevents long text from overflowing */
    }
    /* Example: if you need to force landscape mode */
    @page {
        size: landscape;
    }
}
 </style>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                         <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">  Heard Entry Details</h3>
                                </div>

                                
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            $action = base_url() . '/Listing/Report/showHeardt';
                            $attribute = 'id="push-form" method="post"';
                            echo form_open($action, $attribute);
                            csrf_field();
                            ?>
                            <div class="row">
								<div class="col-md-4">
                                <label for="from" class="col-sm-2_">From Date</label>
                                <div class="col-sm-2_">
                                    <input type="text" id="fromDate" name="fromDate" class="form-control dtp" required placeholder="From Date">
                                </div>
								</div>
								<div class="col-md-4">
                                <label for="to" class="col-sm-2_">To Date</label>
                                <div class="col-sm-2_">
                                    <input type="text" class="form-control dtp" id="toDate" required name="toDate" placeholder="To Date">
                                </div>
								</div>
								
								<div class="col-md-4 mt-4">
								 <button type="submit"  id="view" name="view" onclick="return check(); " class="btn btn-block_ btn-primary">View</button>
                                <button type="button"  id="print" name="print" onclick="printDiv('printable')" class="btn btn-block_ btn-warning">Print</button>

								
								</div>
								
                            </div>
                            
                            <?php echo form_close(); ?>
                        </div>

                        <div id="printable">
                            <?php
                            $total_mod2 = 0;
                            $total_mod4 = 0;
                            $total_mod5 = 0;
                            $total_mod7 = 0;
                            $total_mod8 = 0;
                            $total_mod9 = 0;
                            $total_mod10 = 0;
                            $total_mod12 = 0;
                            $total_mod14 = 0;
                            $total_mod16 = 0;
                            $total_mod17 = 0;
                            $total_mod18 = 0;
                            $total_mod20 = 0;
                            $total_mod21 = 0;
                            $total_mod22 = 0;
                            $total_mod23 = 0;
                            $total_mod24 = 0;
                            $total_mod99 = 0;
                            $total_total = 0;

                            if (isset($listing_result) && sizeof($listing_result) > 0) {

                            ?>
							<h1>
                                                <center><u>Datewise Module Wise Heardt Updation Report</u></center>
                                            </h1>
							<div class="table-responsive">
                                <table class="table table-striped table-hover custom-table">
                                    <thead>                                        
                                        <tr>
                                            <th>Updated Date</th>
                                            <th>CALL LISTING PROGRAM</th>
                                            <th>LIST BEFORE PROGRAM</th>
                                            <th>Dealing Assistant</th>
                                            <th>Cause List Allocation Module</th>
                                            <th>Transfer Module</th>
                                            <th>Transfer Pool Module</th>
                                            <th>Update Heardt</th>
                                            <th>Case Drop Before Printing</th>
                                            <th>Loose Doc Proposal</th>
                                            <th>Motion Auto Cause List</th>
                                            <th>Mention Memo</th>
                                            <th>Cron Proposal</th>
                                            <th>Special Case List</th>
                                            <th>Retired Coram Removed</th>
                                            <th>Tentative Date fixed by Cron S</th>
                                            <th>Vacation Cases Listing</th>
                                            <th>Transfer as it is</th>
                                            <th>Others</th>
                                            <th><b>Total</b></th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php

                                        foreach ($listing_result as $result) {
                                        ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($result['ent_dt'])); ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=2&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod2']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=4&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod4']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=5&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod5']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=7&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod7']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=8&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod8']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=9&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod9']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=10&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod10']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=12&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod12']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=14&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod14']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=16&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod16']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=17&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod17']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=18&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod18']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=20&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod20']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=21&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod21']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=22&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod22']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=23&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod23']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=24&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod24']; ?></td>
                                                <td><a target="_blank" href="<?php echo base_url() ?>/Listing/Report/showUsers?mod=99&date=<?php echo $result['ent_dt']; ?>"><?php echo $result['mod99']; ?></td>
                                                <td><b><?php echo $result['total']; ?></b></td>

                                            </tr>

                                        <?php
                                            $total_mod2 += $result['mod2'];
                                            $total_mod4 += $result['mod4'];
                                            $total_mod5 += $result['mod5'];
                                            $total_mod7 += $result['mod7'];
                                            $total_mod8 += $result['mod8'];
                                            $total_mod9 += $result['mod9'];
                                            $total_mod10 += $result['mod10'];
                                            $total_mod12 += $result['mod12'];
                                            $total_mod14 += $result['mod14'];
                                            $total_mod16 += $result['mod16'];
                                            $total_mod17 += $result['mod17'];
                                            $total_mod18 += $result['mod18'];
                                            $total_mod20 += $result['mod20'];
                                            $total_mod21 += $result['mod21'];
                                            $total_mod22 += $result['mod22'];
                                            $total_mod23 += $result['mod23'];
                                            $total_mod24 += $result['mod24'];
                                            $total_mod99 += $result['mod99'];
                                            $total_total += $result['total'];
                                        }
                                        ?>
                                        <tr style="font-weight: bold;">
                                            <td>Total</td>
                                            <td><?php echo $total_mod2; ?></td>
                                            <td><?php echo $total_mod4; ?></td>
                                            <td><?php echo $total_mod4; ?></td>
                                            <td><?php echo $total_mod7; ?></td>
                                            <td><?php echo $total_mod8; ?></td>
                                            <td><?php echo $total_mod9; ?></td>
                                            <td><?php echo $total_mod10; ?></td>
                                            <td><?php echo $total_mod12; ?></td>
                                            <td><?php echo $total_mod14; ?></td>
                                            <td><?php echo $total_mod16; ?></td>
                                            <td><?php echo $total_mod17; ?></td>
                                            <td><?php echo $total_mod18; ?></td>
                                            <td><?php echo $total_mod20; ?></td>
                                            <td><?php echo $total_mod21; ?></td>
                                            <td><?php echo $total_mod22; ?></td>
                                            <td><?php echo $total_mod23; ?></td>
                                            <td><?php echo $total_mod24; ?></td>
                                            <td><?php echo $total_mod99; ?></td>
                                            <td><?php echo $total_total; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
								</div>

                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php
                            }
?>

<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function check() {
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;
        date1 = new Date(fromDate.split('-')[2], fromDate.split('-')[1] - 1, fromDate.split('-')[0]);
        date2 = new Date(toDate.split('-')[2], toDate.split('-')[1] - 1, toDate.split('-')[0]);
        if (date1 > date2) {
            alert("To Date must be greater than From date");

            return false;
        }
        return true;
    }

    function printDiv()
{      
    let printElement = document.getElementById('printable');
    var printWindow = window.open('', 'PRINT');
    printWindow.document.write(document.documentElement.innerHTML);
    setTimeout(() => { // Needed for large documents
      printWindow.document.title = "SC : CMIS";
      printWindow.document.body.style.margin = '0 0';
      printWindow.document.body.innerHTML = printElement.outerHTML;
      printWindow.document.close(); // necessary for IE >= 10
      printWindow.focus(); // necessary for IE >= 10*/
      printWindow.print();
      printWindow.close();
    }, 1000)
}
</script>
 