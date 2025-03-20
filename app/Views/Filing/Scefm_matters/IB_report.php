<?=view('header'); ?>
 
    <style>

        #blink_text{
            animation-name:blink;
            width:280px;
            animation-duration:2s;
            animation-timing-function:ease-in;
            animation-iteration-count:Infinite;
        }

        @keyframes blink{
            0%{color:red;}
            50%{color:white;}
            100%{color:red;}
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
                                <h3 class="card-title">Filing >> Diary Search</h3>
                            </div>
                           <?=view('Filing/filing_filter_buttons'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <span class="alert-danger"><?=\Config\Services::validation()->listErrors()?></span>

                                    <?php if(session()->getFlashdata('error')){ ?>
                                        <div class="alert alert-danger text-white ">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?= session()->getFlashdata('error')?>
                                        </div>
                                    <?php } else if(session("message_error")){ ?>
                                        <div class="alert alert-danger">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <?=session()->getFlashdata("message_error")?>
                                        </div>
                                    <?php }else{?>
                                        <br/>
                                    <?php }?>


                                    <?= csrf_field('csrf_field') ?>

                                    <center>
                                        <div onclick="get_transfer_case()" class="text-success btn btn-outline-primary">View</div>
                                        <div id="loader"></div> </center>

                                    <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <script>
        function updateCSRFTokenNF() {  $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) { $('#csrf_field').val(result.CSRF_TOKEN_VALUE); }); }
        get_transfer_case();
        function get_transfer_case() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('#csrf_field').val();
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('Filing/Scefm_matters/get_all_matters_ib'); ?>",
                data: {CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    $("#loader").html('');
                    updateCSRFTokenNF();
                    $("#loader").html(data);
                },
                error: function() {
                    updateCSRFTokenNF();
                    alert('Something went wrong! please contact computer cell');
                }
            });

        }
        function efiling_number(efiling_number) {
            var link = document.createElement("a")
            link.href = "<?php echo E_FILING_URL ?>/efiling_search/DefaultController/?efiling_number="+efiling_number
            link.target = "_blank"
            link.click() 
        }
        function isEmpty(obj) {
            if (obj == null) return true;
            if (obj.length > 0)    return false;
            if (obj.length === 0)  return true;
            if (typeof obj !== "object") return true;

            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }

            return true;
        }
        function update_case(casetype,diary_no){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('#csrf_field').val();
            $.ajax({
                    type: 'POST',
                    url: "<?=base_url('Filing/Scefm_matters/transfer_case')?>",
                    data: {case_type:casetype, diary_no:diary_no,CSRF_TOKEN:CSRF_TOKEN_VALUE},
                    success:function(result){
                        updateCSRFTokenNF();
                        var index = result.indexOf("#");  // Gets the first index where a space occours
                        var id = result.slice(0, index); // Gets the first part
                        var text = result.slice(index + 1);
                        if(id==1)
                        {
                            //alert("Transferred Successfully");
                            alert(text);
                           // location.reload();
                            get_transfer_case();
                        }
                        else if(id==2)
                        {
                            alert(text);
                            get_transfer_case();
                            //alert("Error! Contact Computer Cell!")
                        }
                    },
                    error: function() {
                        updateCSRFTokenNF();
                        alert('Something went wrong! please contact computer cell');
                    }
                }
               	)
        }

        function showButton(diary_no)
        {
            var diary;var party;
            if (document.getElementById('diary_modified_'+diary_no).checked) {
                diary = document.getElementById('diary_modified_' + diary_no).value;
            }
            if (document.getElementById('party_modified_'+diary_no).checked) {
                party = document.getElementById('party_modified_' + diary_no).value;
            }
            if(diary=='Y' && party=='Y')
            {
                document.getElementById('transfer_'+diary_no).style.display='block';
            }
            else {
                document.getElementById('transfer_'+diary_no).style.display='none';
            }
        }
    </script>
 <?=view('sci_main_footer');?>