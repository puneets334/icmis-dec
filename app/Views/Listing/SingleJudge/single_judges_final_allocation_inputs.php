<?php

if ($post_data) {
    if ($roster) {

?>

        <form role="form" name="single_judge_final_form" id="single_judge_final_form">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bold mb-0">Roster</h5>
                        <div>
                            <a id="print_action" href="#" title="Print Allocation Table">
                                <i class="fas fa-print"></i> <span class="sr-only">Print</span>
                            </a>
                            <span class="ml-3">Cases in Pool - Fresh <span class="badge badge-info"><?= $fresh_cases_in_pool ?></span>
                                Old <span class="badge badge-info"><?= $case_in_pool ?></span></span>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div id="print_area">
                                                <h4>Single Judge Allocation Listing Date <?= $post_data['next_dt'] ?></h4>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2" class="align-middle">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" id="all_roster" name="all_roster" value="all">
                                                                        <label class="form-check-label" for="all_roster"></label>
                                                                    </div>
                                                                </th>
                                                                <th rowspan="2" class="text-center align-middle">Court No.</th>
                                                                <th rowspan="2" class="text-center align-middle">Hon'ble Judge</th>
                                                                <th rowspan="2" class="text-center align-middle">Day Type</th>
                                                                <th colspan="4" class="text-center align-middle">Listed</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center align-middle">Freshly Filed</th>
                                                                <th class="text-center align-middle">Fixed Date</th>
                                                                <th class="text-center align-middle">Other</th>
                                                                <th class="text-center align-middle">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $total = 0;
                                                            $fixed_date_listed = 0;
                                                            $other_listed = 0;
                                                            $fresh_listed = 0;

                                                            foreach ($roster as $roster) {
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input chk_roster" id="chk_roster" name="chk_roster[]" value="<?= $roster["id"]; ?>">
                                                                            <label class="form-check-label" for="chk_roster"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td><?= $roster["courtno"] ?></td>
                                                                    <td><?= $roster["judge_name"] ?></td>
                                                                    <td><?= $roster["day_type"] ?></td>
                                                                    <?php
                                                                    foreach ($listed_cases_count as $listed) {
                                                                        if ($listed['roster_id'] == $roster['id']) {
                                                                    ?>
                                                                            <td><?php echo $listed['fresh_listed'];
                                                                                $fresh_listed += $listed['fresh_listed']; ?></td>
                                                                            <td><?php echo $listed['fixed_date_listed'];
                                                                                $fixed_date_listed += $listed['fixed_date_listed']; ?></td>
                                                                            <td><?php echo $listed['other_listed'];
                                                                                $other_listed += $listed['other_listed']; ?></td>
                                                                            <td><?php echo $listed['total'];
                                                                                $total += $listed['total']; ?></td>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    if (empty($listed_cases_count)) {
                                                                        ?>
                                                                        <td>0</td>
                                                                        <td>0</td>
                                                                        <td>0</td>
                                                                        <td>0</td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td colspan="4" class="text-right">Total</td>
                                                                <td><?= $fresh_listed ?></td>
                                                                <td><?= $fixed_date_listed ?></td>
                                                                <td><?= $other_listed ?></td>
                                                                <td><?= $total ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-inline">
                                                        <div class="form-check mr-3">
                                                            <input class="form-check-input" type="radio" name="main_supp" id="main_list" value="1">
                                                            <label class="form-check-label" for="main_list">Main List</label>
                                                        </div>
                                                        <div class="form-check mr-3">
                                                            <input class="form-check-input" type="radio" name="main_supp" id="supp_list" value="2">
                                                            <label class="form-check-label" for="supp_list">Supplementary List</label>
                                                        </div>
                                                        <div class="form-group mr-3">
                                                            <label for="part_no" class="mr-2">Part No.<span class="text-danger">*</span></label>
                                                            <input type="number" min="0" max="2000" class="form-control" name="part_no" id="part_no" value="0" autocomplete="off" placeholder="Part No." />
                                                            <span id="error_to_date" class="text-danger"></span>
                                                        </div>
                                                        <div class="form-group mr-3">
                                                            <label for="number_of_cases" class="mr-2">No. of Cases<span class="text-danger">*</span></label>
                                                            <input type="number" min="0" max="500" class="form-control" name="number_of_cases" id="number_of_cases" value="0" autocomplete="off" placeholder="No. of Cases" />
                                                            <span id="error_to_date" class="text-danger"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="btn_allocate" class="mr-2">&nbsp;</label>
                                                            <input type="hidden" class="form-control" name="next_dt_selected" id="next_dt_selected" value="<?= date('Y-m-d', strtotime($post_data['next_dt'])) ?>" />
                                                            <input type="button" name="btn_allocate" id="btn_allocate" value="Allocate" class="btn btn-success" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="chk_lp">Purpose of Listing</label><br>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="all_lp" name="all_lp" value="all">
                                                <label class="form-check-label" for="all_lp">All</label>
                                            </div>
                                            <?php
                                            foreach ($listing_purpose as $lp_data) {
                                            ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input chk_lp" id="chk_lp" name="chk_lp[]" value="<?= $lp_data["code"]; ?>">
                                                    <label class="form-check-label" for="chk_lp"><?= $lp_data['purpose']; ?></label>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


<?php } else {
        echo "Roster Not Found";
    }
} ?>