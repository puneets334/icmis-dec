<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">CauseList Info</h3>
                            </div>
                            <?= view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $action = '';
                        $attrebute = 'id="push-form"';
                        echo form_open($action, $attrebute);
                        csrf_token();
                        ?>
                        <div class="box-body">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label for="court_no">Court No</label>
                                    <select name="courtNo" id="courtNo" class="form-control">
                                        <option value="1">CJI</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="21">R1</option>
                                        <option value="22">R2</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="item_no">Item No</label>
                                    <input type="text" class="form-control" id="itemNo" required name="itemNo">
                                </div>
                                <div class="col-sm-2 mt-4">
                                    <div class="box-footer mt-3">
                                        <button type="submit" id="view" name="view" class="btn btn-block btn-primary">View</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>

                        <?php

                        if (isset($result_array)) {
                            if (count($result_array) > 0) {
                        ?>
                                <table class="table table-striped table-hover ">
                                    <thead>

                                        <tr>
                                            <th>Case<br />Number</th>
                                            <th>Cause Title</th>
                                            <th>Advocates</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $i = 0;
                                        foreach ($result_array as $result) {
                                        ?>
                                            <tr>
                                                <td><?php echo $result['reg_no_display']; ?></td>
                                                <td><?php echo $result['cause_title']; ?></td>
                                                <td><?php echo str_replace(',', '<br/>', $result['advocates']); ?></td>
                                                <td><?php echo $result['remark']; ?></td>
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                        <?php
                            } else {
                                echo "No Report Found";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>