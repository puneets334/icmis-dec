<div class="row">
    <div class="col-md-12">
    
    <?php if (count($sectionwise_pendency_arr) > 0) {
        
    ?>
        <h3 style="text-align:center;"><?php //echo date("d-m-Y h:i:s a"); ?></h3>
        <div class="table-responsive">
            <table id="customers" class="table table-striped custom-table">
                <!--<table align="left" width="100%" border="0px;" style=" padding: 10px; font-size:13px; table-layout: fixed;">-->
                <thead>
                    <tr style="background-color: darkgrey;">
                        <th style="width:3%;">kt</th>
                        <th style="width:3%;">Sno.</th>
                        <th style="width:7%;">Section Name</th>
                        <th style="width:8%;">Misc. Total Main</th>
                        <th style="width:8%">Misc. Total Conn</th>
                        <th style="width:8%;">Misc. Total</th>
                        <th style="width:8%;">Final Total Main</th>
                        <th style="width:10%;">Final Total Connected</th>
                        <th style="width:8%;">Final Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($sectionwise_pendency_arr)){
                    $n=1;
                    $mis_total_main_var = 0;
                    $mis_total_conn_var = 0;
                    $mis_total_var = 0;
                    $final_total_main_var = 0;
                    $final_total_conn_var = 0;
                    $final_total_var = 0;
                    foreach ($sectionwise_pendency_arr as $key => $spr) {         
                        if($spr['section'] == null || $spr['section'] == ''){ 
                            $spr['section'] = 'Total';
                        }           
                    ?>
                        <tr>
                            <td>t</td>
                            <td><?php echo $n; ?></td>
                            <td><?php echo @$spr['section']; ?></td>
                            <td><?php echo @$spr['misc_total_main'];$mis_total_main_var +=@$spr['misc_total_main'];?></td>
                            <td><?php echo @$spr['misc_total_conn'];$mis_total_conn_var+=@$spr['misc_total_conn']; ?></td>
                            <td><?php echo @$spr['misc_total'];$mis_total_var+=@$spr['misc_total']; ?></td>
                            <td><?php echo @$spr['final_total_main'];$final_total_main_var+=@$spr['final_total_main']; ?></td>
                            <td><?php echo @$spr['final_total_conn'];$final_total_conn_var+=@$spr['final_total_conn']; ?></td>
                            <td><?php echo @$spr['final_total'];$final_total_var+=@$spr['final_total']; ?></td>
                        </tr>                            
                    <?php $n++; } ?>
                     <tr>
                        <td>-</td>
                        <td><?php echo @$n; ?></td>
                        <td>Total</td>
                        <td><?php echo @$mis_total_main_var; ?></td>
                        <td><?php echo @$mis_total_conn_var; ?></td>
                        <td><?php echo @$mis_total_var; ?></td>
                        <td><?php echo @$final_total_main_var; ?></td>
                        <td><?php echo @$final_total_conn_var; ?></td>
                        <td><?php echo @$final_total_var; ?></td>
                     </tr>   
                <?php        
                 } ?>
                </tbody>
            </table>
        </div>
        <!-- <input name="prnnt1" type="button" id="prnnt1" value="Print"> -->
    <?php
    } else {
        echo "No Recrods Found";
    }
    ?>

    </div>
</div>
<!-- <script>
    $(document).ready(function () {
        const currentDateTime = new Date().toLocaleString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        $("#customers").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: 'Bfrtip',
            bProcessing: true,
            pageLength: 30,
            order: [[1, "asc"]],
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false
                }
            ],
            buttons: [
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    },
                    title: '', // Disable default title
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            text: 'Sectionwise Pending Not Ready Incomplete Matters as on ' + currentDateTime,
                            fontSize: 12,
                            alignment: 'center',
                            margin: [0, 0, 0, 10]
                        });
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    }
                    
                }
            ]
        });
    });
</script> -->

<script>
    $(document).ready(function () {
        // Format date as DD-MM-YYYY HH:MM:SS AM/PM
        function getFormattedDateTime() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const year = now.getFullYear();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const strTime = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;
            return `${day}-${month}-${year} ${strTime}`;
        }

        const reportTitle = 'Sectionwise Pending Not Ready Incomplete Matters as on ' + getFormattedDateTime();

        $("#customers").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            dom: 'Bfrtip',
            bProcessing: true,
            pageLength: 30,
            order: [[1, "asc"]],
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false
                }
            ],
            buttons: [
                {
                    extend: 'excel',
                    title: reportTitle,
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    },
                    title: '', // remove default
                    customize: function (doc) {
                        doc.content.splice(0, 0, {
                            text: reportTitle,
                            fontSize: 12,
                            alignment: 'center',
                            margin: [0, 0, 0, 10]
                        });
                    }
                },
                {
                    extend: 'print',
                    title: reportTitle,
                    exportOptions: {
                        columns: ':visible:not(:first-child)'
                    }
                }
            ]
        });
    });
</script>
