<?= view('header.php'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Cases not listed before any bench greater than 90 days</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-horizontal" id="push-form" method="post">
                            <?= csrf_field(); ?>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="section" class="col-form-label">Select Section:</label>
                                            <select class="form-control sel_sec" id="section" name="section" onchange="get_DA()" required>
                                                <option value="0">Select</option>
                                                <?php foreach ($section_name as $Section): ?>
                                                    <option value="<?= $Section['id']; ?>" <?= (isset($_POST['section']) && $_POST['section'] == $Section['id']) ? 'selected' : ''; ?>>
                                                        <?= $Section['section_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="slc_da" class="col-form-label">Select DA:</label>
                                            <select class="form-control slc_da" id="slc_da" name="slc_da">
                                                <option value="0">All</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <div class="form-group">
                                            <button type="submit" id="view" name="view" class="btn btn-primary mt-4">View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <img id="image" src="<?= base_url(); ?>/images/load.gif" style="display: none;">
                        <div id="table_Result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function get_DA() {
        var secId = $("#section option:selected").val();
        if (secId == "0") {
            alert("Please select a section.");
            return;
        }

        $.ajax({
            url: '<?= base_url('lwdr/get_DA_sectionwise'); ?>',
            type: "GET",
            data: {
                secId: secId
            },
            cache: false,
            dataType: "json",
            beforeSend: function() {
                $("#image").show();
            },
            complete: function() {
                $("#image").hide();
            },
            success: function(data) {
                // console.log(data);
                var options = '<option value="0">All</option>';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i].usercode + '">' + data[i].name + '</option>';
                }
                $("#slc_da").html(options);
            },
            error: function() {
                alert('Error occurred while fetching data.');
            }
        });
    }

    $('#push-form').on('submit', function(e) {

        var section = $('.sel_sec').val();
        var slc_da = $('.slc_da').val();

        if (section == '') {
            alert("Please Select Section");
            return false;
        }
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?= base_url(); ?>/lwdr/cases_notbefore_bench_90days_data',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#table_Result').html(response);
            },
            beforeSend: function() {
                $('#table_Result').html('');
                $('#view').attr('disabled', true);
                $('#image').show();
            },
            complete: function() {
                $('#view').attr('disabled', false);
                $('#image').hide();
                updateCSRFToken();
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while submitting the form.');
            }
        });
    });
</script>