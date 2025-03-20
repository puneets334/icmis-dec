<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advance List Allocation</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            color: #4141E0;
            font-weight: bold;
        }
        .class_red {
                color: red;
            }
    </style>
</head>
<body>

<div id="prnnt2">
    <fieldset>
        <legend class="header">
            ADVANCE LIST ALLOCATION FOR DATED <?= $cldtMMDDYYYY; ?> (Pre-ponement)
        </legend>
        <table>
            <thead>
                <tr>
                    <th>SrNo.</th>
                    <th>
                        <input type="checkbox" name="chkall" id="chkall" value="ALL" onclick="chkall1(this);">
                    </th>
                    <th>Hon'ble Judge</th>
                    <th>Pre Notice Listed</th>
                    <th>After Notice Listed</th>
                    <th>Total Listed</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($results) && is_array($results)): ?>
                    <?php
                    $srno = 1;
                    $tot_Pre_Notice = 0;
                    $tot_After_Notice = 0;
                    $tot_listed = 0;
                    foreach ($results as $row):
                        // Assume $row['jcd'] is a comma-separated list of IDs used in a query
                        $jcd_p1 = explode(",", $row["jcd"]);
                        $sql1 = "
                            SELECT j1, COUNT(diary_no) listed,
                            SUM(CASE WHEN pre_after_notice = 'Pre_Notice' THEN 1 ELSE 0 END) Pre_Notice,
                            SUM(CASE WHEN pre_after_notice = 'After_Notice' THEN 1 ELSE 0 END) After_Notice  
                            FROM (
                                SELECT DISTINCT h.diary_no, h.j1,
                                CASE WHEN (c.diary_no IS NULL AND (m.fil_no_fh = '' OR m.fil_no_fh IS NULL) AND h.subhead NOT IN (813,814))
                                THEN 'Pre_Notice' ELSE 'After_Notice' END pre_after_notice
                                FROM advance_allocated h 
                                LEFT JOIN main m ON h.diary_no = m.diary_no 
                                LEFT JOIN advanced_drop_note d ON d.diary_no = h.diary_no AND d.cl_date = h.next_dt
                                LEFT JOIN case_remarks_multiple c ON c.diary_no = m.diary_no AND c.r_head IN (1,3,62,181,182,183,184)
                                WHERE d.diary_no IS NULL AND h.next_dt = '$cldt'                                          
                                AND h.j1 = '" . $row["p1"] . "' AND h.board_type = '$board_type'
                                AND (h.main_supp_flag = 1 OR h.main_supp_flag = 2) 
                                AND h.clno = 2
                                AND (m.diary_no = m.conn_key OR m.conn_key = '' OR m.conn_key IS NULL OR m.conn_key = '0') 
                                GROUP BY m.diary_no
                            ) h
                            GROUP BY h.j1
                        ";
                        // Execute SQL query (This should be handled by a model in CI4)
                        $res1 = $this->db->query($sql1);
                        $row1 = $res1->getRow();

                        $tot_Pre_Notice += $row1->Pre_Notice;
                        $tot_After_Notice += $row1->After_Notice;
                        $tot_listed += $row1->listed;
                    ?>
                    <tr>
                        <td><?= $srno++; ?></td>
                        <td>
                            <input type="checkbox" id="chkeeed" name="chk" value="<?= $row["p1"]; ?>">
                            <?= $row['abbreviation']; ?>
                        </td>
                        <td><?= $row1->Pre_Notice; ?></td>
                        <td><?= $row1->After_Notice; ?></td>
                        <td><?= $row1->listed; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight:bold;">
                        <td colspan="3" style="text-align:right;">TOTAL</td>
                        <td><?= $tot_Pre_Notice; ?></td>
                        <td><?= $tot_After_Notice; ?></td>
                        <td><?= $tot_listed; ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;" class="class_red">No Records Found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>

<script>
    function chkall1(source) {
        var checkboxes = document.getElementsByName('chk');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
</script>
<script>
    $(document).on("click", "#prnnt_btn", function() {
            var prtContent = $("#prnnt2").html();
            var temp_str = prtContent;
            var WinPrint = window.open('', '', 'border=1,left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1,autosize=1');
            WinPrint.document.write(temp_str);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
        });
    </script>

</body>
</html>
