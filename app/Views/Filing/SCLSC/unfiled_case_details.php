<div class="modal-header" style="position: relative;">
    <h4 class="modal-title">Case Details for SCLSC Diary No. <?= $diary_no ?></h4>
    <button type="button" class="close" data-dismiss="modal">×</button>
</div>
<!-- Modal body -->
<div class="modal-body" style="padding-top: 0 !important;">
    <div class="row">
    <?//= csrf_field() ?>
        <?php
    
        if (isset($unfiled_case_details) && $unfiled_case_details) {
        ?>
            <div class="container-fluid">
                <section>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <?php
                            $sno = 1;
                            foreach ($unfiled_case_details as $row) {
                                //echo $row['diary_no'];
                            ?>
                                <tr>
                                    <td>Court</td>
                                    </td>
                                    <td><?= $from_court_name ?></td>
                                </tr>
                                <tr>
                                    <td>State of Court</td>
                                    </td>
                                    <td><?= $agency_state_name ?></td>
                                </tr>
                                <tr>
                                    <td>Bench of Court</td>
                                    </td>
                                    <td><?= @$agency_bench_name[0]['agency_name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Paperbook</td>
                                    </td>
                                    <td><a target="_blank" href="<?= $row['paperbook_url'] ?>">View</a></td>
                                </tr>
                                <tr>
                                    <td>Total No. of Pages in File</td>
                                    </td>
                                    <td><?= $row['case_pages'] ?></td>
                                </tr>
                                <tr>
                                    <td>AOR Name</td>
                                    </td>
                                    <td><?= @$aor_details[0]['title'] . ' ' . @$aor_details[0]['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>AOR Code</td>
                                    </td>
                                    <td><?= @$aor_details[0]['aor_code'] ?></td>
                                </tr>
                                <tr>
                                    <td>Section</td>
                                    </td>
                                    <td><?= $section_name ?></td>
                                </tr>
                                <tr>
                                    <td>Case Type</td>
                                    </td>
                                    <td><?= $caseType ?></td>
                                </tr>
                                <?php foreach ($party as $party): ?>
            <?php
                if ($party['pet_res'] == 'P') {
                    $petitioner_type = "Petitioner";
                } else {
                    $petitioner_type = "Respondent";
                }
            ?>
            <tr>
                <td><?= esc($petitioner_type) ?> Name</td>
                <td><?= esc($party['partyname']) ?></td>
            </tr>
            <tr>
                <td><?= esc($petitioner_type) ?> Address</td>
                <td><?= esc($party['addr2']) ?></td>
            </tr>
            <tr>
    <td><?= esc($petitioner_type) ?> City</td>
    <td><?= esc(trim($party['districtName'] ?? '')) ?></td>
</tr>
<tr>
    <td><?= esc($petitioner_type) ?> District</td>
    <td><?= esc(trim($party['districtName'] ?? '')) ?></td>
</tr>
<tr>
    <td><?= esc($petitioner_type) ?> State</td>
    <td><?= esc(trim($party['stateName'] ?? '')) ?></td>
</tr>

        <?php endforeach; ?>
                                <!--               <tr>
                        <td>AOR Name</td></td><td>Testing</td>
                    </tr>
                    <tr>
                        <td>AOR Mobile</td></td><td>Testing</td>
                    </tr>
                    <tr>
                        <td>AOR E-Mail</td></td><td>Testing</td>
                    </tr>-->
                                </tr>
                            <?php
                            }

                            ?>

                        </tbody>
                    </table>
                </section>


            </div>
        <?php
        } else {
            echo "No Records Found";
        }
        ?>

    </div>
</div>
<!-- Modal footer -->
<div class="modal-footer justify-content-between">
    <button type="button" id="generate_diary_no" class="generate_diary_no btn btn-success" data-diary_no="<?= $diary_no ?>">Generate Diary No.</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
