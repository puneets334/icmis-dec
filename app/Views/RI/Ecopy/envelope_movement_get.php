<style>
    html, body {
        height: auto;
    }
    @media print
    {
        td a {
            display:inline-block;
            min-height:100%;
            width:100%;
            color: #0c0c0c;
        }
        a[href]:after {
            content: none !important;
        }
    }
</style>
<?php

if (!empty($envelopeData))
{
//    echo "<pre>";
//    print_r($envelopeData);
//    die;
    ?>
    <table id="reportTable1" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Sno.</th>
            <th>Application Details</th>
            <th>Applicant Details</th>
            <th>Barcode</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $srno = 1;
        foreach ($envelopeData as $row) {

            ?>
            <tr class="row_tr">
                <td><?= $srno++; ?></td>
                <td><?= $row['application_number_display']."<br>CRN:".$row['crn']."<br>SP Charges:".$row['postal_fee']."<br>Weight:".$row['envelope_weight']; ?></td>
                <td><?= $row['name']."<br><u>Address</u>:".$row['address']."<br><u>Mobile</u>:".$row['mobile']."<br><u>Email</u>:".$row['email']; ?></td>
                <td><?= $row['barcode']; ?></td>
                <td class="cell_tr">
                    <input type="button" name="btn_consume"  data-barcode="<?= $row['barcode']; ?>" class="btn_consume btn btn-success" value="Receive">
                </td>
            </tr>
            <?php
        }
      ?>
        </tbody>
    </table>
    <?php
} else {
    ?>
    <div class="form-group col-sm-12">
        <h4 class="text-danger" style="margin-left: 40%;">No Record Found!!</h4>
    </div>

<?php
}
?>

