<?php

if(empty($mainLetterDetail[0]['id'])){

    foreach ($mainLetterDetail as $mainLetterDetail)
    {
           // echo "<pre>";
    //print_r($mainLetterDetail);die;
   // 


    ?>
  <!--  <form id="connectLetters" method="post">-->
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'connectLetters', 'id' => 'connectLetters', 'autocomplete' => 'off', 'method' => 'POST');
        echo form_open(base_url('#'), $attribute);
        ?>
        <div id="divMainProcessId" class="row">
            <input type="hidden" id="mainLetterId" name="mainLetterId" value="<?=$mainLetterDetail['id'];?>">
            <div class="form-group col-sm-3">
                <h4>Process Id : <?=$mainLetterDetail['process_id']?>/<?=$mainLetterDetail['process_id_year']?></h4>
            </div>
            <div class="form-group col-sm-3">
                <h4>Send To Name : <?=$mainLetterDetail['send_to_name']?></h4>
            </div>
            <div class="form-group col-sm-6">
                <h4>Send To Name : <?=$mainLetterDetail['send_to_address'];?></h4>
            </div>
        </div>
        <br>

        <h4 style="margin-left: 1%;">Search Letter to Connect:</h4><br><br>
        <div id="divConnectProcessId" class="row">
            <div class="form-group col-sm-2">
                <label for="processIdConnected">Process Id</label>
                <input type="number" id="processIdConnected" name="processIdConnected" class="form-control" placeholder="Process Id" value="">
            </div>
            <div class="form-group col-sm-2">
                <label for="processYearConnected">Process Year</label>
                <select id="processYearConnected" name="processYearConnected" class="form-control">
                    <?php
                    for($i=date("Y");$i>1949;$i--){
                        echo "<option value=".$i.">$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-sm-2">
                <label for="from" class="text-right">&nbsp;</label>
                <button type="button" id="btnGetCases" class="btn btn-info form-control" onclick="search();">Add </button>
            </div>
            <div id="actionStatus">

            </div>
        </div>
        <h4 class="text-blue col-sm-12" style="margin-left: 1%;">Connected Letters</h4>
        <div id="divConnectedLetters" class="col-sm-12">

        </div>
   <!-- </form>-->
    <?php form_close(); ?>
    <?php
     }
}
else{ ?>
    <div class="col-sm-6">
        <p class="text-danger"><b>You can't make this letter as main beacause it is already a connected letter.</b></p>
    </div>
    <div class="col-sm-2">

        <a href="<?=base_url()?>/RI/DispatchController/showCreateLetterGroup/<?=$_SESSION['login']['usercode']?>">
            <button type="button" id="btnGoBack" class="btn btn-info form-control">Go Back</button>
        </a>
    </div>
<?php }
?>

<script>
    function search(){
        var processId = $("#processIdConnected").val();
        var processYear = $("#processYearConnected").val();
        if (processId == "") {
            alert("Enter Process Id to Connect.");
            $("#processIdConnected").focus();
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       var mainLetterId = $("#mainLetterId").val();
        $.ajax({
            type: "POST",
            data: {
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                pid: processId,
                pyr :processYear,
                mainLetterId:mainLetterId

            },
            // dataType: 'JSON',
            url: "<?php echo base_url('RI/DispatchController/searchConnectedLetter'); ?>",
            success: function(result) {
                // alert(data);
                // $("#dataProcessId").html(data);
                $("#actionStatus").show();
                $("#actionStatus").html(result);
                setTimeout(function(){
                    $("#actionStatus").hide();
                },4000);

                updateCSRFToken();
            },
            error: function(result) {
                alert(result);
                updateCSRFToken();
            }
        });

        //$.post("<?//=base_url()?>//RI/DispatchController/searchConnectedLetter", $("#connectLetters").serialize(), function (result) {
        //    //alert(result);
        //    $("#actionStatus").show();
        //    $("#actionStatus").html(result);
        //    setTimeout(function(){
        //        $("#actionStatus").hide();
        //    },4000);
        //});
        //var selectedCase=$("#mainLetterId").val();
        //$.post("<?//=base_url()?>//index.php/RIController/getConnectedLetters", {'selectedCase': selectedCase}, function (result) {
        //    $("#divConnectedLetters").html(result);
        //});

    }

</script>



