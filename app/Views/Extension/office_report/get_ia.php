<?php
$year = date('Y');
 
$doc_details = $officeReportModel->getDocDetails($dairy_no);
?>
<div style="margin-top: 10px">
<table class="table table-striped custom-table">
    <tbody id="tb_docdetails">
        <?php
        if (count($doc_details) > 0) {
            $d_sno = 1;
            foreach ($doc_details as $row1) {
        ?>
                <tr>
                    <td>
                        <?php echo $d_sno; ?>
                    </td>
                    <td>
                        <?php echo $row1['docnum']; ?>-<?php echo $row1['docyear']; ?>
                    </td>
                    <td>
                        <?php echo $row1['docdesc'] ?> <?php if ($row1['other1'] != '') {
                                                            echo ' - ' . $row1['other1'];
                                                        } ?>
                    </td>
                    <td>
                        <?php echo date('d-m-Y', strtotime($row1['ent_dt'])); ?>
                    </td>
                    <td>
                        <?php echo $row1['verified']; ?>
                    </td>
                    <td>

                    </td>
                </tr>
            <?php
                $d_sno++;
            }
        } else {
            ?>
            <tr>
                <td colspan="6">
                    <div style="text-align: center">
                        No Information Available
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    </table>
</div>