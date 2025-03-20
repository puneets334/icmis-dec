<?= view('header') ?>

<style>
    .login-box {
        margin: auto;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">ENTRY</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="login-box-body" style="padding: 20px 40px 20px 40px;">
                                <?php
                                $attributes = array("class" => "form-horizontal", "id" => "caseForm", "name" => "caseForm");
                                echo form_open("CashAccounts/Fdr/continueFdr", $attributes); ?>

                                <div class="row">
                                    <div class="col-md-3">

                                        <div class="form-group has-feedback">
                                            <select class="form-control" id="caseType" name="caseType">
                                                <option>Select Case Type</option>
                                                <?php
                                                foreach ($caseTypes as $caseType)
                                                    echo "<option value='" . $caseType['id'] . "'>" . $caseType['description'] . "</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-feedback">
                                            <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="text" autocomplete="off" required>
                                            <span class="glyphicon glyphicon-file form-control-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-feedback">
                                            <select class="form-control" id="caseYear" name="caseYear">
                                                <option>Year</option>
                                                <?php
                                                for ($i = date("Y"); $i >= 1950; $i--)
                                                    echo "<option value='" . $i . "'>$i</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <!-- /.col -->
                                        <div class="form-group">
                                            <div class="col-xs-">
                                                <button type="submit" name="btn_submit" value="continue" class="btn btn-primary btn-block btn-flat"><span class="glyphicon glyphicon-forward"> Continue</span></button>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </div>
                                </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <?php
                        if (is_array(@$caseInfo) && !empty(@$caseInfo)) {
                        ?>
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Select Case</h3>
                                </div>
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Registration Number</th>
                                                <th>CauseTile</th>
                                                <th>Section</th>
                                            </tr>
                                            <?php
                                            $sNo = 1;
                                            foreach ($caseInfo as $row) {
                                                echo '<tr style="cursor:pointer;" onclick="selectedCase(' . $row['id'] . ')"> <td>' . $sNo++ . '</td> <td>' . $row['registration_number_display'] . ' </td><td>' . $row['petitioner_name'] . ' vs ' . $row['respondent_name'] . '</td><td>' . $row['section_name'] . '</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        <?php
                        }
                        ?>






                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function selectedCase(caseId) {
        window.location = '<?= base_url() ?>Fdr/continueFdr/' + caseId;
    }
</script>