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
                                <h3 class="card-title">FDR >> Search</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">


                                    <section class="content">
                                        <div class="box-body">
                                            <?php
                                            $attributes = array("class" => "form-horizontal", "id" => "fdrSearch", "name" => "fdrSearch");
                                            echo form_open("CashAccounts/Fdr/fdr_search_result", $attributes);
                                            ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Section Wise Search</legend>
                                                        <div class="input-group">
                                                            <select class="form-control" id="section" name="section">
                                                                <option value="0">Select Section</option>
                                                                <?php foreach ($sections as $section): ?>
                                                                    <option value="<?= htmlspecialchars($section['id'], ENT_QUOTES) ?>"><?= htmlspecialchars($section['section_name'], ENT_QUOTES) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Bank Wise Search</legend>
                                                        <div class="input-group">
                                                            <select class="form-control" id="bank" name="bank">
                                                                <option value="0">Select Bank</option>
                                                                <?php foreach ($banks as $bank): ?>
                                                                    <option value="<?= htmlspecialchars($bank['id'], ENT_QUOTES) ?>"><?= htmlspecialchars($bank['bank_name'], ENT_QUOTES) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <br />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Disposed Matters Search</legend>
                                                        <div class="input-group">
                                                            <select class="form-control" id="disposedCase" name="disposedCase">
                                                                <option value="0">Disposed Cases</option>
                                                                <?php if (is_array($DiscaseTypes) && !empty($DiscaseTypes)): ?>
                                                                    <?php foreach ($DiscaseTypes as $DiscaseType): ?>
                                                                        <option value="<?= htmlspecialchars($DiscaseType['diary_no'], ENT_QUOTES) ?>"><?= htmlspecialchars(@$DiscaseType['reg_no'], ENT_QUOTES) ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Type Search</legend>
                                                        <div class="input-group">
                                                            <select class="form-control" id="type" name="type">
                                                                <option value="0">Select FDR/BG</option>
                                                                <option value="1">Fixed Deposit</option>
                                                                <option value="2">Bank Guarantee</option>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <br />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Deposit Date wise Search</legend>
                                                        <div class="input-group">
                                                            <input class="form-control datepicker" id="depositDate" name="depositDate" placeholder="Deposit Date" type="date">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Maturity Date wise Search</legend>
                                                        <div class="input-group">
                                                            <input class="form-control datepicker" id="maturityDate" name="maturityDate" placeholder="Maturity/Expiry Date" type="date">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="glyphicon glyphicon-calendar"></i></span>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <br />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Case wise Search</legend>
                                                        <div class="input-group">
                                                            <select class="form-control" id="caseType" name="caseType">
                                                                <option value="0">Select Case Type</option>
                                                                <?php foreach ($caseTypes as $caseType): ?>
                                                                    <option value="<?= htmlspecialchars($caseType['id'], ENT_QUOTES) ?>"><?= htmlspecialchars($caseType['description'], ENT_QUOTES) ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <br />
                                                        <div class="input-group">
                                                            <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="text" autocomplete="off">
                                                        </div>
                                                        <br />
                                                        <div class="input-group">
                                                            <select class="form-control" id="caseYear" name="caseYear">
                                                                <option value="0">Year</option>
                                                                <?php for ($i = date("Y"); $i >= 1950; $i--): ?>
                                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset class="fieldset">
                                                        <legend class="legend">Tenure wise Search</legend>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Days</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="days" id="days" autocomplete="off">
                                                        </div>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Month</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="month" id="month" autocomplete="off">
                                                        </div>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Year</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="year" id="year" autocomplete="off">
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <br />

                                            <div class="form-group">
                                                <div class="col-xs-12">
                                                    <button type="submit" name="btn_search" value="search" class="btn btn-primary btn-block">Search <span class="glyphicon glyphicon-search"></span></button>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </section>

                                    <!-- /.content -->





                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?=base_url()?>assets/plugins/daterangepicker/moment.min.js"></script>
<script src="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker.js"></script>

<script>
    $('#depositDate,#maturityDate').daterangepicker({
        autoUpdateInput: false,
        "autoApply": true,
        "showDropdowns": true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    $('#depositDate,#maturityDate').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    });
    $('#depositDate,#maturityDate').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>