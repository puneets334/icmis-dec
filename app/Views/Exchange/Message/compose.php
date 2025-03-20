<?= view('header') ?>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/token-input.css">
<style type="text/css">
    .card-header {
    padding: .75rem 0;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="card-title">Compose</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2" style="width: 100% !important;">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <?php if (session()->getFlashdata('msg')): ?>
                                <?= session()->getFlashdata('msg') ?>
                            <?php endif; ?>
                            
                            <?php
                            $attribute = array(
                                'class' => 'form-horizontal appearance_search_form',
                                'id' => 'receiveFileFromDA',
                                'autocomplete' => 'off',
                                'enctype'=>'multipart/form-data',
                                'method' => 'post'
                            );
                            echo form_open(base_url('#'), $attribute);
                            ?>
                            <input type="hidden" name="usercode" id="usercode" value="<?php echo session()->get('login')['usercode']; ?>"/>
                            <div class="col-md-1">
                                <label for="causelistDate">From</label>
                                <input type="hidden" value="<?php echo session()->get('login')['usercode']; ?>" id="msg_frm"><?php echo session()->get('login')['usercode']; ?>
                            </div>
                            <div class="col-md-4">
                                <label for="from" class="text-right">To</label>
                                <input type="hidden" class="receiverName" rows="4" cols="50" id="msg_to">
                                <input class="form-control" type="text" id="userInput" placeholder="Type username or Emp id">
                                <select id="suggestions" class="suggestions select2 form-control" multiple size="5">
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="from" class="text-right">Message</label>
                                <textarea rows="4" cols="50" id="msg"></textarea>
                            </div>
                            <div class="col-md-3 mt-2">
                                <input type="button" value="Send" onclick="send_msg();">
                            </div>
                            <?= form_close()?>
                        </div>
                    </div>
                    <center><span id="loader"></span></center>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="txtHint"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?= base_url() ?>/assets/js/sweetalert-2.1.2.min.js"></script>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/js/jquery.tokeninput.js"></script>
<script>
    $(function()
    {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    $(document).ready(function()
    {
        $('.suggestions').select2({
            placeholder: "Select an option",
            /*allowClear: true*/ // This allows the user to clear the selection
        });
    });

    function send_msg()
    {
        var msg_to = document.getElementById("msg_to").value;
        var msg = encodeURIComponent(document.getElementById("msg").value);
        
        if (msg_to === "" || msg === '')
        {
            if (msg_to === "")
            {
                alert("Please Enter Receiver ID");
            }
            if (msg === '')
            {
                alert("Please Enter Message");
            }
            return false;
        }

        var msg_frm = document.getElementById("msg_frm").value;
        var xmlhttp = new XMLHttpRequest();
        
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                $('#suggestions').hide();
                $('#suggestions').html('');
                $('#userInput').val('');
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
                document.getElementById("msg_to").value = ''; // Clear the input field
                document.getElementById("msg").value = ''; // Optionally clear the message field
            }
        };

        xmlhttp.open("GET", "<?= base_url('Exchange/Message/sendMsg') ?>?msg_frm=" + msg_frm + "&msg_to=" + msg_to + "&msg=" + msg, true);
        xmlhttp.send();
    }

    $(document).ready(function()
    {
        $('#userInput').on('input', function()
        {
            $('#txtHint').html('');
            const query = $(this).val();

            if (query.length < 1)
            {
                $('#suggestions').empty().hide();
                return;
            }

            $.ajax({
                url: '<?= site_url('Exchange/Message/getReceiver') ?>',
                method: 'GET',
                data: { q: query },
                beforeSend: function()
                {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function(data)
                {
                    $("#loader").html('');
                    $('#suggestions').empty();
                    if (data.length > 0)
                    {
                        var options = '';
                        $.each(data, function(index, user) {
                            options += '<option value="' + user.id + '">' + user.name + '</option>';
                        });
                        $('#suggestions').html(options);
                        $('#suggestions').show();
                    }
                    else
                    {
                        $('#msg_to').val('');
                        $('#suggestions').hide();
                    }
                }
            });
        });

        $('#suggestions').on('change', function()
        {
            const selectedValues = $(this).val();
            $('#msg_to').val(selectedValues.join(', '));
        });
    });
</script>