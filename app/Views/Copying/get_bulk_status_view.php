<div class="card">
    <div class="card-body" >
<?php
if (isset($result) && sizeof($result) > 0) {
    ?>
    <div id="printable" class="card">
        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <h1 class="m-2">Applications Received <?php if(!empty($user_detail)) echo "by " . $user_detail['name'].'('.$user_detail['empid'].') ';else echo "";
                    if($from_date==$to_date) echo " on ".$from_date;else  echo 'from '.$from_date." to ".$to_date; echo " and are Pending";?>
                </h1>
            </tr>
            <tr>
                <th><input type="checkbox" class="chkboxall" name="all" id="all"  onclick="checkallCheckbox();"></th>
                <th>Application Number</th>
                <th>Diary Number</th>
                <th>Applied By</th>
                <th>Received On</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($result as $row) {
                $i++;
                ?>
                <tr>
                    <td><input type="checkbox" class="chkbox" name="chk[]" id=<?php echo "chk" . $row['id']; ?> value=<?php echo $row['id']; ?>></td>
                    <td><?php echo $row['application_number_display']; ?></td>
                    <td><?php echo $row['diary']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['received_on'])); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>

        </table>
    </div>
    <div align="center"><button type="submit" style="width:15%" id="update" onclick="checkCheckbox();" name="update" class="btn btn-block btn-primary">Update</button></div>

    <?php

} else {
    echo '<br/><br/><br/>';
    echo "<font size='18px'; color='red';>No Pending Applications!";
}?>
 </div>
  </div>
