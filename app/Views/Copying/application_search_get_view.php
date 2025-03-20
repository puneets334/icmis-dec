<?php if (!empty($all_application_search)) { ?>


    <div class="col-12 m-0 p-0">
        <div class="list-group list-group-mine">
            <div class="row">
                <?php $srno = 1;
                foreach ($all_application_search as $row) {

                    //print_r($all_docs_array[$row['id']]);

                    if (!empty($all_docs_array[$row['id']])) {

                        $row_docs = $all_docs_array[$row['id']];
                        $user_verification_items = "";
                        if (!empty($user_verification_details[$row['id']])) {
                            $user_verification_items = $user_verification_details[$row['id']];
                        }

                        $case_no = "";
                        if ($row['reg_no'] != '') {
                            $case_no = $row['reg_no'];
                        }
                        $case_no .= ' DNo. ' . substr($row['diary'], 0, -4) . '-' . substr($row['diary'], -4);
                        if ($row['case_status'] == 'P') {
                            $case_status = 'Pending';
                        } else {
                            $case_status = 'Disposed';
                        }

                ?>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">

                                    <div style="border-radius: 15px 15px 15px 15px;" class="item_no list-group-item p-2 m-1">
                                        <div class="row">
                                            <nav class="navbar ">

                                                <div class="col-md-2" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="rounded-circle bg-danger p-1" style="">
                                                        <span class="p-1 text-white font-weight-bold">
                                                            <?= $srno++; ?>
                                                        </span></span>

                                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="<?= $row['mobile'] ?>"><i class="fas fa-mobile-alt"></i> <?= $row['mobile'] ?></a>
                                                        <a class="dropdown-item" href="<?= $row['email'] ?>"><i class="fas fa-envelope-square"></i> <?= $row['email'] ?></a>
                                                        <a class="dropdown-item" href="<?= $row['address'] ?>"><i class="fas fa-map-marker-alt"></i> <?= $row['address'] ?></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">Application No.: <span class="font-weight-bold text-gray"><?= $row['application_number_display']; ?></span></div>
                                                <div class="col-md-5">Date: <span class="font-weight-bold text-gray"><?= date("d-m-Y", strtotime($row['application_receipt'])); ?></span></div>

                                            </nav>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-5">CRN: <span class="font-weight-bold text-gray"><?= $row['crn']; ?></span></div>
                                            <?php
                                            //if($row['delivery_mode']!=3){
                                            ?>
                                            <div class="col-md-5">Fee: <span class="font-weight-bold text-gray">Rs. <?= $row['court_fee']; ?></span>
                                                <?= $row['allowed_request'] == 'free_copy' ? '<span class="text-danger">Ist Bail Order</span>' : '' ?>
                                            </div>
                                            <?php
                                            //}

                                            ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-5">Status: <span class="font-weight-bold <?= $case_status == 'Disposed' ? 'text-danger' : 'text-success' ?>"><?= $case_status ?></span></div>

                                            <div class="col-md-5">Delivery: <span class="font-weight-bold text-gray"><?php
                                                                                                                        if ($row['delivery_mode'] == 1) {
                                                                                                                            echo "Post" . "(Rs " . $row['postal_fee'] . ")";
                                                                                                                        }
                                                                                                                        if ($row['delivery_mode'] == 2) {
                                                                                                                            echo "Counter";
                                                                                                                        }
                                                                                                                        if ($row['delivery_mode'] == 3) {
                                                                                                                            echo "Email";
                                                                                                                        }
                                                                                                                        ?></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">Case No.: <span class="font-weight-bold text-gray"><?= $case_no; ?></span></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">Applicant Name: <span class="font-weight-bold text-gray"><?= $row['name']; ?></span>
                                                <span class="ml-1 text-success font-weight-bold">
                                                    <?php
                                                    if ($row['filed_by'] == 1) {
                                                        echo "(AOR)";
                                                    }
                                                    if ($row['filed_by'] == 6) {
                                                        echo "(Auth. By AOR)";
                                                    }
                                                    if ($row['filed_by'] == 2) {
                                                        echo "(Party)";
                                                    }
                                                    if ($row['filed_by'] == 3) {
                                                        echo "(Arguing Counsel)";
                                                    }
                                                    if ($row['filed_by'] == 4) {
                                                        echo "(Third Party)";
                                                    }
                                                    ?>
                                                </span>
                                            </div>

                                        </div>

                                        <?php if ($row['filed_by'] == 2 || $row['filed_by'] == 3 || $row['filed_by'] == 4) { ?>
                                            <div class="row ml-1 pl-1">
                                                <div class="col-md-11"><u>User Verification:</u></div>
                                            </div>
                                            <?php
                                            $id_proof_asset = 'NO';
                                            $photo_asset = 'NO';
                                            $video_asset = 'NO';
                                            $affidavit_asset = 'NO';
                                            $party_asset = 'NO';
                                            $appearing_counsel_asset = 'NO';
                                            if (!empty($user_verification_items)) {
                                                foreach ($user_verification_items as $itemkey => $data_asset) {
                                                    if ($data_asset['asset_type'] == 1) { //id proof
                                                        $id_proof_asset = 'Yes';
                                               
                                                  //  echo $data_asset['asset_type'] . '<br>';

                                            ?>
                                            <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>' data-id_proof_masterid='<?= $data_asset['id_proof_type'] ?>' data-attached_path='<?= $data_asset['file_path']; ?>' data-mobile='<?= $row['mobile']; ?>' data-email='<?= $row['email']; ?>'>ID Proof <?= $data_asset['id_name']; ?>
                                                </a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    if ($data_asset['asset_type'] == 2) { //Photo
                                        $photo_asset = 'Yes';
                                    ?>
                                        <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>' data-id_proof_masterid='0' data-attached_path='<?= $data_asset['file_path']; ?>'>Photo</a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    if ($data_asset['asset_type'] == 3) { //video
                                        $video_asset = 'Yes';
                                    ?>
                                        <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>' data-id_proof_masterid='0' data-attached_path='<?= $data_asset['file_path']; ?>'>Video</a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    <?php
                                    }

                                    if ($data_asset['asset_type'] == 4) { //affidavit for 3rd party
                                        $affidavit_asset = 'Yes';
                                    ?>
                                        <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>' data-id_proof_masterid='0' data-attached_path='<?= $data_asset['file_path']; ?>'>Affidavit</a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    if ($data_asset['asset_type'] == 5) { //party
                                        $party_asset = 'Yes';
                                    ?>
                                        <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>'>Party in Case</a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    <?php

                                    }
                                    if ($data_asset['asset_type'] == 6) { //Appearing counsel
                                        $appearing_counsel_asset = 'Yes';
                                    ?>
                                        <div class="row ml-1 pl-1">
                                            <div class="col-md-12">
                                                <a href='#' class='uploaded_attachments' data-asset_type='<?= $data_asset['asset_type'] ?>'>Appeared in Case</a>
                                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </div>
                                        </div>
                            <?php
                                    }?>
                                        <?php }
                                            }
                                        } ?>
                                        <?php foreach ($row_docs as $row1) { ?>
                                            <div class="row m-1 p-1 border-top application_document">
                                                <div class="col-md-6"><?= $row1['order_name']; ?> <?= $row1['order_date'] != '1970-01-01 00:00:00' ? date("d-m-Y", strtotime($row1['order_date'])) : ''; ?>
                                                </div>
                                                <div class="col-md-2">Set:<?= $row1['number_of_copies']; ?></div>
                                                <div class="col-md-2">Page:<?= $row1['order_type'] != 37 ? $row1['number_of_pages_in_pdf'] : 'N.A.'; ?></div>
                                                <div class="col-md-2">

                                                    <?php

                                                    if ($row1['order_type'] == 37) {
                                                    ?>


                                                        <p class="m-1">
                                                            <a id="pdf_link_download" class="btn btn-secondary pt-0 pb-0" type='button' target="_blank" href="<?= $row1['path'] ?>">
                                                                <i class="fas fa-download bg-black"></i>
                                                            </a>
                                                        <p class="m-0">
                                                            <input type="button" class="btn btn-success" data-source="zip_file" value="Send" id="send_to_applicant" data-application_id_id="<?= $row['id'] . '_' . $row1['id']; ?>" data-document_id='<?= $row1['id']; ?>' data-send_to_path="<?= $row1['path']; ?>" />
                                                        </p>






                                                        </p>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <span class="p-2">
                                                            <input id="pdf_link" class="btn btn-secondary pt-0 pb-0" type='button' value="View" data-crn="<?= $row['crn']; ?>" data-application_id_id="<?= $row['id'] . '_' . $row1['id']; ?>" data-application_no="<?= $row['application_number_display']; ?>" data-path="<?= $row1['path']; ?>" data-court_fee="<?= $row['court_fee']; ?>" data-number_of_pages_in_pdf="<?= $row1['number_of_pages_in_pdf']; ?>" data-applicant_name="<?= $row['name']; ?>" data-delivery_mode="<?= $row['delivery_mode'] ?>" />
                                                        </span>
                                                    <?php
                                                    }

                                                    //}
                                                    ?>

                                                </div>
                                            </div>
                                        <?php } ?>



                                    </div>

                                    <!-- Looping
                     -->
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
<?php } ?>