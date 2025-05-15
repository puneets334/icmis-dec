<table style="margin-left: auto; margin-right: auto; border-collapse: collapse; width: 100%;" class="table table-striped custom-table_ " border="1" cellspacing="3" cellpadding="3">

    <?php
    $sno = 1;
    foreach ($partydetails1 as $row) :
    ?>
        <tr>
            <td><strong>Cause Title</strong></td>
            <td><?php echo $row['pet_name'];
                if ($row['pno'] == 2) echo " <span style='color:#72bcd4'> AND ANR</span>" . " ";
                else if ($row['pno'] > 2) echo " <span style='color:#72bcd4'> AND ORS</span>" . " "; ?>
                <b>VS</b>
                <?php echo ' ' . $row['res_name'];
                if ($row['rno'] == 2) echo " <span style='color:#72bcd4'> AND ANR</span>";
                else if ($row['rno'] > 2) echo " <span style='color:#72bcd4'> AND ORS</span>";

                ?>
            </td>
        </tr>
        <tr>
            <td><strong>Diary No.</strong></td>
            <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?>
                <?php echo ", <b>Nature:</b> " . $getnature->short_description; ?></td>


        </tr>
        <tr>
            <td><strong>Receive Date</strong></td>
            <td> <?php
                    if (isset($row['diary_no_rec_date'])) {
                        $date = new DateTime($row['diary_no_rec_date']);
                        echo $date->format('d-m-Y h:i:s A');
                    } else {
                        echo '';
                    }
                    ?></td>
        </tr>
        <tr>
            <td><strong>Case No.</strong></td>
            <td><?php echo $getnature->short_description . substr($row['active_fil_no'], 3) . '/' . $row['active_reg_year']; ?></td>
        </tr>
        <tr>
            <td><strong>Registration Date</strong></td>
            <td><?php if ($row['active_fil_dt'] != '0000-00-00 00:00:00') echo date('d-m-Y h:i:s A', strtotime($row['active_fil_dt'])); ?></td>

        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td><?= isset($row['c_status']) ? ($row['c_status'] == 'P' ? 'Pending' : ($row['c_status'] == 'D' ? 'Disposed' : '')) : ''; ?></td>
        </tr>
        <tr>
            <td><strong>Pet. Advocate(s)</strong></td>
            <td>
                <?php foreach ($res_adv as $name3) :
                ?>
                    <?= $name3 ?><br>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td><strong>Resp. Advocate(s)</strong></td>
            <td>
                <?php if (empty($advocate_names)) : ?>
                    <strong style="color: blue;"> Not found </strong>
                <?php else : ?>
                    <?php echo implode(', ', $advocate_names); ?>
                <?php endif; ?>

            </td>
        </tr>
        <tr>
        <tr>
            <td><strong>State and Bench</strong></td>
            <td>
                <?php echo $getbench->agency_state;
                if ($getbench->from_court != 3) echo ' = ' . $getbench->agency_name; ?>
            </td>

        </tr>

        </tr>
        <tr>
            <td><strong>Category</strong></td>
            <td>
                <button class="btn btn-primary editButton" data-toggle="modal" data-target="#myModal1">Edit</button>
                <?php foreach ($cat as $cat_details) : ?>
                    <?= $cat_details->category_sc_old . ' - ' . $cat_details->sub_name1 . '  ' . $cat_details->sub_name4   ?>
                <?php endforeach; ?>
            </td>
        </tr>


        <tr>
            <td><strong>Listing Date</strong></td>
            <td><?php if ($getbench->next_dt >= date('Y-m-d')) {
                    if ($getbench->main_supp_flag == 1 || $getbench->main_supp_flag == 2) {
                        if ($getbench->roster_id > 0 && $getbench->clno > 0) {
                            echo "Listed On " . date('d-m-Y', strtotime($getbench->next_dt)) . ' Before ';
                            if ($getbench->board_type == 'J')
                                echo "Hon' Coram";
                            else if ($getbench->board_type == 'C')
                                echo "Chamber";
                            else if ($getbench->board_type == 'R')
                                echo "Registrar";

                            echo " " .isset( $getbench->jname) ? $getbench->jname : '';
                        }
                    } else {
                        echo "Not Listed";
                    }
                } ?></td>
        </tr>
        <tr>
            <td><strong>Caveator Adv(s)</strong></td>
            <td>
                <?php if (empty($advocate_names)) : ?>
                    <strong style="color: blue;"> Not found </strong>
                <?php else : ?>
                    <?php echo implode(', ', $advocate_names); ?>
                <?php endif; ?>

            </td>
        </tr>
        <tr>
            <td><strong>Proof of Service</strong></td>
            <td><?= (($proof) > 0) ? "YES" : "NO"; ?></td>
        </tr>
        <tr>
            <td><strong>Pending IA(s)</strong></td>
            <td>
                <?php foreach ($ia as $detail) : ?>
                    <?= '<strong>' . $detail->docnum . '</strong>' . ' /  ' . '<strong>' . $detail->docyear . '</strong>'  . '  ' . $detail->docdesc . '  ' . '<strong>' . date('d-m-Y', strtotime($detail->ent_dt)) . '</strong>'; ?><br>
                <?php endforeach; ?>
            </td>
        </tr>


        <tr>
             
            <td colspan="100%"> <button class="btn btn-primary editButton" data-toggle="modal" data-target="#myModal">Edit</button>

                <?= $Url_Coram ?>
            </td>

             
        </tr>

        <tr>
            <td><strong>Tagged Matters</strong></td>
            <td><?php echo (!empty($connected_output)) ? $connected_output : '<p align="center_"><b style="color:red">CONNECTED MATTERS NOT FOUND</b></p>'; ?></td>
        </tr>
    <?php
        $sno++;
    endforeach;
    ?>

</table>
<!-- <button class="btn btn-primary" onclick="redirectToCategory('category')">Edit</button> -->

<script>
    function redirectToCategory(category) {

        var baseUrl = "<?= site_url('/Filing/Category'); ?>";
        var url = baseUrl + "?dummyParameter=" + category;
    }
</script>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Coram Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="limitationData">
                <?= $editcoram ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Category Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="limitationData1">
                <form>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <?= $category ?>
                </form>

            </div>
        </div>
    </div>
</div>