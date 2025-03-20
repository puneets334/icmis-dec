<div class="table-responsive">
    <?php
    if ($results) { ?>
        
        <div class="font-weight-bold text-center mt-26">
            Cases Having No Subject Category
            <?php
            $output = '';
            if ($mainhead === 'M') {
                $output .= " For Misc. Hearing";
            } elseif ($mainhead === 'F') {
                $output .= " For Regular Hearing";
            }

            if ($board_type === 'J') {
                $output .= " before Court";
            } elseif ($board_type === 'C') {
                $output .= " before Chamber";
            } elseif ($board_type === 'R') {
                $output .= " before Registrar";
            } ?>



        </div>
        <div class="font-weight-bold text-center">
            <?php echo $output; ?>
        </div>
        
        <table id="example1" class="table table-striped custom-table">
            <thead>
                <tr>
                    <th width="5%">SNo.</th>
                    <th width="15%">Reg No/Diary</th>
                    <th width="25%">Petitioner / Respondent</th>
                    <th width="25%">Heading</th>
                    <th width="15%">Purpose</th>
                    <th width="15%">Section/DA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 1;
                foreach ($results as $row) {
                    $sno1 = $sno % 2;
                    $purpose = $row['purpose'];
                    $stagename = $row['stagename'];
                    if ($row['pno'] == 2) {
                        $pet_name = $row['pet_name'] . " AND ANR.";
                    } else if ($row['pno'] > 2) {
                        $pet_name = $row['pet_name'] . " AND ORS.";
                    } else {
                        $pet_name = $row['pet_name'];
                    }
                    if ($row['rno'] == 2) {
                        $res_name = $row['res_name'] . " AND ANR.";
                    } else if ($row['rno'] > 2) {
                        $res_name = $row['res_name'] . " AND ORS.";
                    } else {
                        $res_name = $row['res_name'];
                    }


                    if ($row['reg_no_display']) {
                        $comlete_fil_no_prt = $row['reg_no_display'] . " @ " . substr_replace($row['diary_no'], '-', -4, 0);
                    } else {
                        $comlete_fil_no_prt = substr_replace($row['diary_no'], '-', -4, 0);
                    }
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $comlete_fil_no_prt; ?></td>
                        <td><?php echo $pet_name . "<br/>Vs<br/>" . $res_name; ?></td>
                        <td><?php echo $row['stagename']; ?></td>
                        <td><?php echo $purpose; ?></td>
                        <td><?php echo $row['section_name'] . '<br>' . $row['da_name']; ?></td>
                    </tr>
                <?php
                    $sno++;
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
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bProcessing": true,
        "searching": false,
        "buttons": [
            {
                extend: 'print',
                text: 'Print',
                className: 'btn-primary quick-btn',
                customize: function(win) {
                    $(win.document.body).find('h1').remove();
                }
            }
        ]
    });
</script>