<?= view('header.php'); ?>
<style>
    #image {
        margin-left: 25%;
    }

    #display {
        margin-left: 5%;
    }


    input[type=submit] {
        margin: 8px 0 0 4%;
    }

    #di {
        border-radius: 5px;
        padding: 20px;

    }

    select {
        width: 10%;
        margin-left: 20px;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Section Wise Unverified Matters</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="push-form">
                            <?= csrf_field(); ?>
                            <div class="box-body col-12">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <label for="section" id="d">Select Section</label>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <select id="section" class="form-control" name="section">
                                            <?php
                                            if (count($section_name) >= 1) {
                                                foreach ($section_name as $data) {
                                                    echo '<option value="' . $data['section_name'] . '">' . $data['section_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-3 mt-1">
                                        <input type="button" value="Submit" id="submit" class="btn btn-primary" onclick="fetch_data()">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <img id="image" src="<?= base_url(); ?>/images/load.gif" style="display: none;">
                        <div id="unverified_data"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function fetch_data() {

        var section = document.getElementById("section").value;
        var url = '<?= base_url(); ?>/lwdr/sectionwise_unverified_matters_data';
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#unverified_data').html('');
        $('#submit').attr('disabled', true);
        $.ajax({
            type: "GET",
            url: url,
            data: {
                section: section,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            beforeSend: function() {
                $('#image').show();
            },
            complete: function() {
                updateCSRFToken();
                $('#image').hide();
                $('#submit').attr('disabled', false);
            },
            success: function(data) {
                $('#unverified_data').html(data);
            },
            error: function() {
                alert("ERROR");
            }
        });
    }
</script>