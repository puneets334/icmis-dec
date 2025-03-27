<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <!-- /.card-header -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Matters Received</h3>
                            </div>
                        </div>
                    </div>
                    <p id="show_error"></p>
                    <div class="card-body">
                    <form method="post">
                        <?= csrf_field() ?>
                        <div id="dv_content1">
                            <div class="mrgB10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="w-auto" class="text-left">Court No</label>
                                        <input type="number" id="courtNo" maxlength="6" class="form-control" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dyr" class="text-left">Date</label>
                                        <input type="text" class="form-control dtp" id="dyr" maxlength="4" value="" autocomplete="off"/>
                                    </div>
                                    <div class="col-md-3 pt-4">
                                        <input type="button" value="Details" id="showbutton" class="btn btn-primary" />
                                    </div>
                                </div>
                            </div>
                            <div id="result" class="mrgT20"></div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    function changediv() {
        $('#courtNo').show();
        $('#Calender').show();
    }

    $("#showbutton").click(function() {
        var url = "<?= site_url('Exchange/CauseListFileMovement/fetchData') ?>";
        var courtNo = $('#courtNo').val();
        var date = $("#dyr").val();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            url: url,
            data: {
                date1: date,
                courtNo: courtNo,
                CSRF_TOKEN: CSRF_TOKEN_VALUE
            },
            beforeSend: function () {
                $('#table').hide();
            },

            complete: function () {
                $('#table').show();

            },

            success: function (response) {
                
                /*if (response.status === 'success') {
                    $('#result').html(response.data);
                } else {
                    $('#result').html(response.message);
                }*/
                updateCSRFToken();
                $('#result').html(response);
            },
            error: function () {
                updateCSRFToken();
                alert("ERROR");
            }

        });
    });


    $(".datepick").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });


</script>