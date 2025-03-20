<?php
extract($_POST);
// pr($_POST);
$sno = 0;
if(isset($freezeProcess) && !empty($freezeProcess)) {
?>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th style="width: 10%">SNo.</th>
                <th style="width: 20%">Court No.</th>
                <th style="width: 30%">Action</th>
            </tr>
        </thead>
        <?php
        echo "<tr><td class='p-3' colspan='3' style='font-size:15px;'>";
        echo '<span class="font-weight-bolder">List Type : </span><span class="badge badge-secondary">Weekly List</span>, ';
        echo '<span class="font-weight-bolder">Weekly No.: <span class="badge badge-secondary">'.$freezeProcess[0]['max_weekly_no'].'</span>, ';
        echo '<span class="font-weight-bolder">Weekly Year : <span class="badge badge-secondary">'.$freezeProcess[0]['max_weekly_year'].'</span>';
        echo  '</td></tr>';
        foreach($weekelyProcess as $data1) {
            $i = $data1['courtno'];
            ?>
            <tr>
                <td><?=++$sno;?></td>
                <td>
                    <?php
                    if($i > 60){
                        echo "VC ".($i-60);
                    } else if($i > 30){
                        echo "VC ".($i-30);
                    } else{
                        echo "Court No. ".$i;
                    }
                    ?>
                </td>
                <td id="d_<?=$i?>">
                    <?php
                    $hybrid_data = $hybrid_model->getHybridData($list_type, $i, $freezeProcess[0]['max_weekly_no'], $freezeProcess[0]['max_weekly_year']);

                    if(count($hybrid_data) == 0) {
                        ?>
                        <div class="form-group mt-1">
                            <button data-max_to_dt="<?=$freezeProcess[0]['max_to_dt']?>" data-max_weekly_no="<?=$freezeProcess[0]['max_weekly_no']?>" data-max_weekly_year="<?=$freezeProcess[0]['max_weekly_year']?>" data-list_type_id="<?=$list_type?>" data-court_number="<?=$i?>" class='btn btn-info save_action btn-block' type='button' name='save_action' >Freeze</button>
                        </div>
                        <?php
                    } else {
                        $data = $hybrid_data[0];
                        ?>
                        <div class="form-group mt-1 freeze_id_<?= $data['id'] ?>">
                            <button data-freeze_id="<?= $data['id'] ?>" data-court_number="<?=$i?>" class='btn btn-danger delete_action btn-block' type='button' name='delete_action'>Already Freezed! Want To Unfreeze?</button>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php
} else {
    ?>
    <div class="text-danger font-weight-bolder">
        List Not Available to Freeze.
    </div>
<?php } ?>