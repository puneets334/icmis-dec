<?=view('header'); ?>
<section class="content " >
    <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-body" >
    <div id="dv_content1"   >
    <form method="post" action="">
    <?= csrf_field() ?>
        <div style="text-align: center">
            <div class="row">
                <div class="col-sm-12">
                    <?php if(isset($_SESSION['success'])){?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?=$_SESSION['success']; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?php if(isset($_SESSION['fail'])){?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?=$_SESSION['fail']; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                    <div  class="col-sm-6 form-group">
                        <label class="text-primary">Search Option : </label>
                        <label class="radio-inline"><input type="radio" name="rdbtn_select" id="radioct">Case Type</label>
                        <label class="radio-inline"><input type="radio" name="rdbtn_select" id="radiodn" checked>Diary No.</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <label for="caseType">Case Type</label>
                    <select  class="form-control" name="caseType" tabindex="1" id="selct" disabled="">
                        <option value="">Select</option>
                        <?php
                            foreach(getCaseType() as $caseType){
                                echo '<option value="' . $caseType['casecode'] . '">'. $caseType['short_description']. '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="caseNo">Case No.</label>
                    <input class="form-control" id="case_no" name="case_no" placeholder="Case Number" type="number" maxlength="5" disabled="">
                </div>
                <div class="col-sm-2">
                    <label for="caseYear">Year</label>
                    <select class="form-control" id="case_yr" name="case_yr" disabled="">
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="caseNo">Diary No.</label>
                    <input class="form-control" id="t_h_cno" name="t_h_cno" placeholder="Diary Number" size="4" value="<?php echo isset($_SESSION['session_diary_no']) ? $_SESSION['session_diary_no'] : ''; ?>">
                </div>
                <div class="col-sm-2">
                    <label for="caseYear">Year</label>
                    <select class="form-control" id="t_h_cyt" name="t_h_cyt">
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="view">&nbsp;</label>
                    <button type="button" name="sub" id="sub" class="btn btn-block btn-primary" onclick="getDetails()">SUBMIT</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="div_results"></div>
            <input type="hidden" name="hd_fil_no_x" id="hd_fil_no_x"/>
            <input type="hidden" name="hd_recdt" id="hd_recdt"/>
        </div>
    </form>
    </div>

    <div id="overlay" style="display:none;">&nbsp;</div>
        </div>
        </div>
        </div>
        </div>
    </div>
</section>
<script src="<?=base_url()?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/js/select2.full.min.js"></script>
<script src="<?=base_url()?>/assets/js/app.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jszip.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.colVis.min.js"></script>
<script src="<?=base_url()?>/assets/js/talwana.js"></script>
<script>
    $(document).on("focus", ".dtp", function() {
        $('.dtp').datepicker({dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true, yearRange: '1950:2050'});
    });
    $("#radiodn").click(function(){
        $("#t_h_cno").prop('disabled',false);
        $("#t_h_cyt").prop('disabled',false);
        $("#selct").prop('disabled',true);
        $("#case_no").prop('disabled',true);
        $("#case_yr").prop('disabled',true);
        $("#selct").val("-1");
        $("#case_no").val("");
        $("#case_yr").val("");
    });

    $("#radioct").click(function(){
        $("#t_h_cno").prop('disabled',true);
        $("#t_h_cyt").prop('disabled',true);
        $("#t_h_cno").val("");
        $("#t_h_cyt").val("");
        $("#selct").prop('disabled',false);
        $("#case_no").prop('disabled',false);
        $("#case_yr").prop('disabled',false);
    });
    function CheckedAll_R()
    {
        var rCheckbox = document.getElementById('all_r');
        rCheckbox.addEventListener('change', function() {
            selectElements('R', this.checked);
        });
        function selectElements(className, checked) {
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++) {
                elements[i].click();
            }
        }
    }

    function CheckedAll_P()
    {
        var pCheckbox = document.getElementById('all_p');
        pCheckbox.addEventListener('change', function() {
            selectElements('P', this.checked);
        });
        function selectElements(className, checked) {
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++) {
                elements[i].click();
            }
        }
    }
</script>




