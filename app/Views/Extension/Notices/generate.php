<?= view('header') ?>

<style type="text/css">
    .al_left {
        text-align: left;
    }

    .cl_add_cst,
    .sp_aex {
        color: blue;
    }

    .cl_add_cst:hover,
    .sp_aex:hover {
        cursor: pointer;
    }
    .cl_center {
        text-align: center;
    }
    fieldset {
    display: block;
    min-inline-size: min-content;
    margin-inline: 2px;
    border-width: 2px;
    border-style: groove;
    border-color: threedface;
    border-image: initial;
    padding-block: 0.35em 0.625em;
    padding-inline: 0.75em;
}
legend {
    width: auto;
}
b, strong, dt, th {
    font-weight: bold !important;
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
                                <h3 class="card-title">Office Report >> Diary Search</h3>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom_action_menu">
                                    <a href="<?= base_url('Extension/OfficeReport'); ?>"><button class="btn btn-primary btn-sm" type="button"><i class="fa fa-pencil	" aria-hidden="true"></i></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                    <?php if (session()->getFlashdata('error')) { ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php } else if (session("message_error")) { ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata("message_error") ?>
                                        </div>
                                    <?php } else { ?>
                                        <br />
                                    <?php } ?>

                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                                    <?php echo component_html(); ?>


                                    <center> <button type="button" class="btn btn-primary" id="sub" onclick="getDetails()">Submit</button></center>
                                    <?php form_close(); ?>

                                    <center><span id="loader"></span> </center>
                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
                                </div>
                            </div>


                            <div id="div_results"></div>
                            <input type="hidden" name="hd_fil_no_x" id="hd_fil_no_x" />
                            <input type="hidden" name="hd_recdt" id="hd_recdt" />
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script type="text/javascript" src="<?= base_url('filing/generate.js') ?>"></script>
<script>
    $(document).on("focus", ".dtp", function() {

        $('.dtp').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050'
        });
    });

    function CheckedAll_R() {

        var rCheckbox = document.getElementById('all_r');
        // console.log(rCheckbox);return false;

        rCheckbox.addEventListener('change', function() {
            selectElements('R', this.checked);
        });

        function selectElements(className, checked) {
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++) {
                // elements[i].checked = checked; // Checkbox ko select ya deselect karein
                // let clickEvent = new Event('click');
                // elements[i].dispatchEvent(clickEvent);
                elements[i].click();

            }
        }
    }


    function CheckedAll_P() {

        var pCheckbox = document.getElementById('all_p');
        // console.log(rCheckbox);return false;
        pCheckbox.addEventListener('change', function() {
            selectElements('P', this.checked);
        });

        function selectElements(className, checked) {
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++) {
                // elements[i].checked = checked; // Checkbox ko select ya deselect karein
                elements[i].click();
            }
        }
    }
</script>