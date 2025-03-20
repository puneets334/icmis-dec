<style type="text/css">
    table, th, td {
      border: 1px solid black;
    }
</style>
<div class="col-12 col-sm-12 col-md-12 col-lg-12">
    <h2 style="text-align: center;text-transform: capitalize;color: black;"> Pending Matters of <?php echo ltrim($heading," and"); ?> as on  <?=date('d/m/Y');?></h2>
    <?php if (count($result) >= 1): ?>
    <div class="table-responsive">
        <table id="tblCasesForReceive" class="table">
            <thead>
                <tr bgcolor="#dcdcdc">
                    <th style="text-align: center;">Sr.No.</th>
                    <th width="8%" style="text-align: left;">Case No.</th>
                    <th>Cause Title</th>
                    <th>Dealing Assistant</th>
                </tr>
            </thead>
            <tbody>
                <?php $sno = 1; ?>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $sno ?></td>
                            <td><?php echo $row['diary_no'] . "/" . $row['diary_year'] . "<br/>" . $row['reg_no_display'] ?></td>
                            <td><?php echo $row['pet_name'] ?><strong> Vs </strong><?= $row['res_name'] ?></td>
                            <td><?php echo $row['name'] . "(" . $row['empid'] . ")/" . $row['section_name'] ?></td>
                        </tr>
                    <?php $sno++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <?php echo '<div class="nofound" style = "color:red; font-weight:bold; text-align: center;">No such Pending Case!</div>' ?>
    <?php endif; ?>
</div>