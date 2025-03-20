<?= $this->extend('header') ?>
<?= $this->section('content') ?>
<style>
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Filing >> Diary Search</h3>
                            </div>
                            <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php  //echo $_SESSION["captcha"];
                                    $attribute = array('name' => 'diary_search', 'id' => 'diary_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('Filing/Earliercourt/earliercourt/'), $attribute);
                                    ?>
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Diary No</label>
                                                <input type="number" class="form-control" id="diary_no" name="diary_no" placeholder="Enter Diary No" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Diary Year</label>
                                                <?php $year = 1950;
                                                $current_year = date('Y');
                                                ?>
                                                <select name="diary_year" class="custom-select rounded-0">
                                                    <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                        <option><?php echo $x; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <br>
                                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                    <?php form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>