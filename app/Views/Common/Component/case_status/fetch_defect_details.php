<style>
    .styled-table {
        border-collapse: collapse;
        margin: 20px auto;
        width: 90%;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .styled-table thead tr {
        background-color: #007BFF;
        color: white;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
    }

    .styled-table tbody tr {
        background-color: #fff;
        transition: background-color 0.3s ease;
    }

    .styled-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }

    .styled-table tbody tr td:last-child {
        text-align: center;
    }

    .styled-table input[type="checkbox"] {
        cursor: pointer;
    }

    .styled-table button {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .styled-table button:hover {
        background-color: #218838;
    }

    .styled-table th:last-child,
    .styled-table td:last-child {
        width: 100px;
    }

    #hd_print {
        margin: 20px auto;
        display: block;
        padding: 10px 20px;
        background-color: #28a745;
        /* Green background */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #hd_print:hover {}

    #customers th,
    #customers td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
    }

    #customers th {
        background-color: white;
        /* Green background */
        color: black;
        text-transform: uppercase;
        font-weight: bold;
    }

    #customers tr:nth-child(even) {
        /* Light gray for even rows */
    }

    #customers tr:hover {
        /* Light gray on hover */
    }
</style>

<div id="divprint">

    <input type="hidden" name="hd_ci_cri" id="hd_ci_cri" value="<?php echo $cicri ?? ''; ?>" />

    <?php
    
    $output1 = '';
    if (!empty($result)) {
        $output = '<table id="customers" border="1" class="table">';
        $output .= '<tr><th colspan="2" align="center">Case Details</th></tr>';
        $cntt = 1;
       
        foreach ($result as $key => $row) {
            if($key == 0)
            {
                $output1 =  '<h3> List of Defects in' . ' ' . $row['ia_type'] . '' . htmlspecialchars($row['docnum']) . '/' . htmlspecialchars($row['docyear']) . ' - ' . htmlspecialchars($row['docdesc']) .  ' </h3>';
                if ($row) {
                    $output .= '<tr><th>Diary No / Case No</th><td>' . htmlspecialchars(substr($diary_no, 0, -4) . '/' . substr($diary_no, -4)) . ' - ' . htmlspecialchars($row['reg_no_display']) . '</td></tr>';
                    $output .= '<tr><th>Cause Title</th><td>' . htmlspecialchars($row['pet_name']) . ' VS ' . htmlspecialchars($row['res_name']) . '</td></tr>';

                    $output .= '<tr><th>I.A / Doc No.</th><td>' . htmlspecialchars($row['docnum']) . '/' . htmlspecialchars($row['docyear']) . ' - ' . htmlspecialchars($row['docdesc']) . '</td></tr>';
                }

                $output .= '</table>';
                $output .= '<table id="customers" border=1 style ="margin-top:3%">';
                $output .= '<tr><th colspan="6" align="center">Defect Details</th></tr>';
                $output .= '<tr>
                <th>Sr. No.</th>
                            <th>Defects</th>
                            <th>Notified Date</th>
                            <th>Remove Date</th>
                        </tr>';

                
                    $output .= '<tr>';
                    $output .= '<td>' . $key + 1 . '</td>';
                    $output .= '<td>' . htmlspecialchars($row['objdesc']) . '</td>';
                    $output .= '<td>' . date("d-m-Y H:i:s", strtotime($row['save_dt'])) . '</td>';
                    $output .= '<td>';
                    if ($row['rm_dt'] != '') {
                        $output .= date('d-m-Y h:i:s', strtotime($row['rm_dt']));
                    } else {
                        $output .= ' - ';
                    }
                    $output .= '</td>';
                    $output .= '</tr>';
                    //$cntt++;
            }else{
                $output .= '<tr>';
                $output .= '<td>' . $key + 1 . '</td>';
                $output .= '<td>' . htmlspecialchars($row['objdesc']) . '</td>';
                $output .= '<td>' . date("d-m-Y H:i:s", strtotime($row['save_dt'])) . '</td>';
                $output .= '<td>';
                if ($row['rm_dt'] != '') {
                    $output .= date('d-m-Y h:i:s', strtotime($row['rm_dt']));
                } else {
                    $output .= ' - ';
                }
                $output .= '</td>';
                $output .= '</tr>';
            }
        }
        $output .= '</table>';
    }else{
        $output = '<table id="customers" align="center" class="table custom-table" border=1>';
        $output .= '<thead><tr><th colspan="2" align="center">Case Details</th></tr></thead>';
        $output .= '<tbody><tr><td>No Record found..</td></tr></tbody>';
        $output .= '</table>';
    }

    echo $output1;
    echo $output;
    ?>

</div>
<div align="center">
    <input type="button" name="hd_print" id="hd_print" value="Print Report" onclick="print_data()" />
</div>
<script>
    function print_data() {
        var prtContent = document.getElementById('divprint');
        var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
        WinPrint.document.write(prtContent.outerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    }
</script>