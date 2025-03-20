<?php
if ($post_data) {
?>
    <style>
        .badge1 {
            padding: 2px 9px 2px;
            font-size: 12.025px;
            font-weight: bold;
            white-space: nowrap;
            color: #ffffff;
            background-color: #70b9c5;
            -webkit-border-radius: 9px;
            -moz-border-radius: 9px;
            border-radius: 9px;
        }

        .badge1:hover {
            color: #ffffff;
            text-decoration: none;
            cursor: pointer;
        }

        .badge-error {
            background-color: #b94a48;
        }

        .badge-error:hover {
            background-color: #953b39;
        }

        .badge-warning {
            background-color: #f89406;
        }

        .badge-warning:hover {
            background-color: #c67605;
        }

        .badge-success {
            background-color: ##5cc45e;
        }

        .badge-success:hover {
            background-color: #356635;
        }

        .badge-info {
            background-color: #3a87ad;
        }

        .badge-info:hover {
            background-color: #2d6987;
        }

        .badge-inverse {
            background-color: #333333;
        }

        .badge-inverse:hover {
            background-color: #1a1a1a;
        }
    </style>
    <form role="form" name="single_judge_advance_form" id="single_judge_advance_form">
        <?= csrf_field() ?>

        <input type="hidden" class="form-control" name="from_date_selected" id="from_date_selected" value="<?= date('Y-m-d', strtotime($post_data['from_date'])) ?>" />
        <input type="hidden" class="form-control" name="to_date_selected" id="to_date_selected" value="<?= date('Y-m-d', strtotime($post_data['to_date'])) ?>" />

        <div class="panel-group">
            <div class="panel panel-default">
                <div class="">
                    Allocated <span class="badge1 badge-inverse"><?= $listed_cases ?></span> Available In Pool <span class="badge1 badge-inverse"><?= $case_in_pool ?></span>


                    <div class="box-tools pull-right">
                        <span name="send_to_pool" id="send_to_pool" class="badge1 badge-error">Send to Pool</span>

                    </div>

                </div>
                <div class="panel-body">

                    <div class="row">



                                <div class="col-md-12">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="chk_lp">Purpose of Listing</label>
                                                <br>
                                            <input type="checkbox" id="all_lp" name="all_lp" value="all"> All
                                            <br>
                                            <?php foreach ($listing_purpose as $lp_data): ?>
                                                <?php if ($lp_data['code'] != 32): ?>
                                                    <input type="checkbox" class="chk_lp" id="chk_lp_<?= $lp_data['code']; ?>" name="chk_lp[]" value="<?= $lp_data["code"]; ?>">
                                                    <?= $lp_data['purpose']; ?>
                                                    <br>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>



                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="number_of_cases">No. of Cases to Allocate<span style="color:red;">*</span></label>
                                                    <input type="number" min="0" max="500" class="form-control" name="number_of_cases" id="number_of_cases" value="0" autocomplete="off" placeholder="No. of Cases" />
                                                    <span id="error_to_date"></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="btn_allocate">Action</label>
                                                    <input type="button" name="btn_allocate" id="btn_allocate" value="Allocate" class="form-control btn btn-success" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                    </div>
                </div>

            </div>

        </div>


    </form>

    <script>
    </script>

<?php } ?>