<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="card-title">Click on Button if Court Remark in Misc. and Regular is Completely Done.</h3>
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="text-center">
                            <div class="text-center" style="font-size: 13px;">
                                <table style="text-align:center;">
                                    <tr>
                                        <td style="text-align:center;">
                                            <?php field_action_btn1(); ?>
                                        </td>
                                    </tr>
                                </table>
                                <div id="res_loader"></div>
                            </div>
                            <div id="dv_res1"></div>
                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).on("click", "#btn1", function() {
        var r = confirm("Are you sure want to Run Cron");
        if (r == true) {
            txt = "Cron Service Started...";
            $('#dv_res1').html(txt);
            list_dt = "";
            $.ajax({
                url: '<?php echo base_url('Listing/report/cron_p_get'); ?>',
                cache: false,
                async: true,
                data: {
                    list_dt: list_dt
                },
                beforeSend: function() {
                    $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'GET',
                success: function(data, status) {
                    $('#dv_res1').html(data);
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        } else {
            txt = "Cron Serice Not Started Due to You Clicked Cancel Button!";
            $('#dv_res1').html(txt);
        }
    });
</script>