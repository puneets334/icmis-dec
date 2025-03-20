<style>
    #example_filter label {
    margin-right: 50px;  
}
</style> 
<table id="example" class="display" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Case No @ Diary No.</th>
            <th>Cause Title</th>
            <th>Consent</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $psrno = "1";
        $srNo = 0;
        $r = [];
        if(!empty($consent_report)) {
            foreach ($consent_report as $r) {

                if ($r['diary_no'] == $r['conn_key'] or $r['conn_key'] == 0) {
                    $print_brdslno = $psrno;
                    $print_srno = $psrno;
                    $con_no = "0";
                    $is_connected = "";
                } else if ($r['main_or_connected'] == 1) {
                    $print_brdslno = "&nbsp;" . $print_srno . "." . ++$con_no;
                    $is_connected = "<span style='color:red;'>Connected</span><br/>";
                }
            ?>
                <tr>
                    <td><?= $print_brdslno; ?>
                        <?php
                        if ($is_connected != '') {
                            //$print_srno = "";
                        } else {
                            $print_srno = $print_srno;
                            $psrno++;
                        }
                        ?>
                    </td>
                    <td>
                        <?= $r['case_no']; ?><br>
                        <?= $is_connected; ?>
                    </td>
                    <td> <?= sprintf('%s',  $r['cause_title']); ?> </td>
                    <td>
                        <?= $r['final_consent']; ?>
                    </td>
                </tr>
            <?php } 
        } else {?>
        <tr>
            <td><?= "No Data Found"; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "bSort": false,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'print',
                title: '<?= $tital; ?>',
                customize: function(win) {
                    $(win.document.body).css('font-size', '12pt')
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                    $(win.document.body).find('h1').css('font-size', '15pt');
                    $(win.document.body).find('h1').css('text-align', 'center');
                }
            }]
        });
    });
</script>