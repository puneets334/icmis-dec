<?= view('header.php'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3">

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Matters diarized but not listed as on <?php echo date("d-m-Y h:i a"); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <img id="image" src="<?= base_url(); ?>/images/load.gif" style="display: none;">
                        <div id="Matters_diarized"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function load_data() {
        var url = '<?= base_url(); ?>/lwdr/diarized_but_not_listed_process_data'; 
        $.ajax({
            type: "GET",
            url: url,

            beforeSend: function() {
                $('#image').show();
            },

            complete: function() {
                $('#image').hide();
            },

            success: function(data) {
                $('#Matters_diarized').html(data);
            },
            error: function() {
                alert("ERROR");
            }

        });
    }

    $(document).ready(function() {
        load_data();
    });
</script>