<?= view('header') ?>
<style>
    #btnCan,
    #btnUp {
        display: none;
    }
    .button-container {
        display: flex;
        align-items: center;
        /* Aligns items vertically centered */
    }

    .button-container button {
        margin-right: 10px;
        /* Adds space between buttons */
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
                                <h3 class="card-title">Master Management >> Short Url >> Create</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center mt-4">
                        <h2>
                            Please Enter URL to shorten! <span class="text-danger font-weight-bold">&#8628;</span>
                        </h2>
                    </div>    

                    <form method="post">
                        <?= csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <input id="urlinput" type="url" class="form-control-lg" size="60" required
                                    name="url" <?php if (@$url != "") echo "value='" . @$url . "'"; else echo "placeholder='e.g. https://www.google.com'" ?>/> 
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <button id="process_btn" type="button" style="margin-top: 10px" class="btn btn-primary">GO!
                                </button>
                            </div>
                        </div>
                    </form>

                     <div id="result">
                        
                     </div>                   
                      <br/><br/>                      

                </div>
            </div>
        </div>
</section>
<script>
    var image_loader_str = base_url+"/cgwbspin.gif";
    $("#process_btn").click(async function () {
        if ($("#urlinput").val().trim() === '') {
            alert("Please Enter Url");
            $("#urlinput").focus();
            return false;
        }
        var urlinput =  $("#urlinput").val();
        $("#process_btn").prop("disabled", true);                
        $("#result").html("<div class='row'><div class='col-md-12 text-center'><img src='"+ base_url +"/images/load.gif'></div></div>");  
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: base_url + "/MasterManagement/ShortUrlController/process_shortner",
            async: false,
            data:{urlinput:urlinput,CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend: function () {
                $("#result").html(''); 
                $("#process_btn").prop("disabled", true);                
                $("#result").html("<div class='row'><div class='col-md-12 text-center'><img src='"+ base_url +"/images/load.gif'></div></div>");  
            }
        })
        .done(function(res){
            //updateCSRFToken();
            let result = res;
            if(result.solve== true){
                $("#result").html(result.Message);  
                $('#urlinput').val('');                  
            }
            else{
                $("#result").html(result.Message);
            }               
            $("#process_btn").prop("disabled", false);      
            
        })
        .fail(function(){
            //updateCSRFToken();
            $("#process_btn").prop("disabled", false);      
            alert("ERROR, Please Contact Server Room");
        });
    });
    // function copyFunction() {
    //     var copyText = $('#shorturl').attr('href');
    //     alert(copyText);
    //     document.addEventListener('copy', function(e) {
    //         e.clipboardData.setData('text/plain', copyText);
    //         e.preventDefault();
    //     }, true);
    //     document.execCommand('copy');
    // }

    function copyFunction() {
        const copyText = $('#shorturl').attr('href');
        navigator.clipboard.writeText(copyText).then(function () {
            //alert("Copied: " + copyText);
        }).catch(function (err) {
            //alert("Failed to copy: " + err);
        });
    }

</script>