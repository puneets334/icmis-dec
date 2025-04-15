<style>
    div.dataTables_wrapper div.dataTables_filter label {
        display: flex;
        justify-content: end;
    }

    div.dataTables_wrapper div.dataTables_filter label input.form-control {
        width: auto !important;
        padding: 4px;
    }
</style>
<div class="table-responsive">
    <?php
    $title = '';
    if ($reports) { ?>
        
        <div class="font-weight-bold text-center mt-26 mrgB10">
            <?php 
                $title = $mainhead_title." Cases Listed/To be listed in future dates : Upto ".$_POST['list_date']." (As on ".date('d-m-Y H:i:s')." )";
                echo $title; 
            ?>
    
        </div>
        <table id="tab" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="10%">SNo.</th>
                    <th width="20%">Case No.</th>
                    <th width="30%">Cause Title</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                $is_connected = $print_srno = "";
                foreach ($reports as $row) {
                   if($row['diary_no'] == $row['conn_key'] OR $row['conn_key'] == null OR $row['conn_key'] == '' OR $row['conn_key'] == 0){
                        $print_srno = $sno;
                        $is_connected = "";
                        $sno++;
                    } else {
                        $is_connected = "<span style='color:red;'>Conn.</span>";
                    }
                   
                    /*if($is_connected != ''){
                        $print_srno = "";
    
                    } else {
                        $print_srno = $print_srno;
                        $sno++;
                    }*/
                ?>
                    <tr>
                       
                        <td><?php echo $print_srno.$is_connected; ?></td>
                        <td><?php echo $row['reg_no_display'].' @ '.$row['diary_no']; ?></td>
                        <td><?php echo $row['pet_name'].' Vs. '.$row['res_name']; ?></td>
                    </tr>
                <?php
                    //$sno++;
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div class="mt-26 red-txt center">No Recrods Found</div>
    <?php
    } ?>
</div>
<script>
    var filename = '<?=$title?>';
    var title = '<?=$title?>';
    $(document).ready(function() {
        $('#tab').DataTable( {
            "bProcessing": true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel', className: 'btn btn-primary quick-btn',
                    filename: filename,
                    title:title,
                    text: 'Export to Excel',
                    autoFilter: true,
                    sheetName: 'Sheet1'

                },

                {
                    extend: 'pdf', className: 'btn btn-primary quick-btn',
                    filename: filename,
                    title: title,
                    pageSize: 'A4',
                    orientation: 'landscape',
                    text: 'Save as Pdf',
                    customize: function(doc) {
                        doc.styles.title = {
                            fontSize: '18',
                            alignment: 'left'
                        }
                    }
                },
                {
                    extend: 'print',className: 'btn btn-primary quick-btn',
                    title: title,
                    pageSize: 'A4',
                    orientation: 'portrait',
                    text: 'Print',
                    autoWidth: false,
                    columnDefs: [{
                        "width": "20px", "targets":[0] }],

                    customize: function ( win ) {
                        $(win.document.body).find('h1').css('font-size', '20px');
                        $(win.document.body).find('h1').css('text-align', 'left');
                        $(win.document.body).find('tab').css('width', 'auto');

                    }
                }
            ],

            paging: true,
            ordering: false,
            info: true,
            searching: true,
        } );

        $('.dt-buttons').removeClass('dt-buttons btn-group');
    } );
</script>