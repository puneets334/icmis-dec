<?=view('header') ?>
<style>
                fieldset{
                   padding:5px; background-color:#F5FAFF; border:1px solid #0083FF; 
                }
                legend{
                    background-color:#E2F1FF; width:100%; text-align:center; border:1px solid #0083FF; font-weight: bold;
                }
                .table3, .subct2, .subct3, .subct4{
                    display:none;
                }
                .toggle_btn{
                    text-align: left; color: #00cc99; font-size:18px; font-weight: bold; cursor: pointer;
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
                                <h3 class="card-title">Regiastrar In Chamber</h3>
                            </div>
                           
                        </div>
                    </div>
                     <div class="row">
                         <div class="col-md-12">
                             <div class="card">
                               <div class="card-body">
                              <?php if(!empty($row_avl)){ ?>
                                <?php echo csrf_field(); ?>
                                <div style="text-align: center;">
                                    <?php
                                    $active_casetype_id = $row_avl['active_casetype_id'];
                                    if ($row_avl['c_status'] == "P") {
                                        $c_status = "<span style='color:blue; font-weight: bold;'>Pending</span>";
                                    } else {
                                        $c_status = "<span style='color:red; font-weight: bold;'>Disposed</span>";
                                    }
                                    if ($row_avl['reg_no_display']) {
                                        echo "Case No. : " . $row_avl['reg_no_display'] . "<br>";
                                    } else {
                                        echo "Case No. : Registrered<br>";
                                    }
                                    echo "Diary No. : " . substr_replace($row_avl['diary_no'], '-', -4, 0) . " " . $c_status . "<br>";
                                    if ($row_avl['pno'] == 2) {
                                        $pet_name = $row_avl['pet_name'] . " AND ANR.";
                                    } else if ($row_avl['pno'] > 2) {
                                        $pet_name = $row_avl['pet_name'] . " AND ORS.";
                                    } else {
                                        $pet_name = $row_avl['pet_name'];
                                    }
                                    if ($row_avl['rno'] == 2) {
                                        $res_name = $row_avl['res_name'] . " AND ANR.";
                                    } else if ($row_avl['rno'] > 2) {
                                        $res_name = $row_avl['res_name'] . " AND ORS.";
                                    } else {
                                        $res_name = $row_avl['res_name'];
                                    }
                                    echo $pet_name . " <b>Vs</b>. " . $res_name;

                                   
                                        if(isset($getMainheadInfo['mainhead']) && ($getMainheadInfo['mainhead'] == 'F')){
                                            echo "<br><span style='color: darkred; font-weight: bold;'>Regular Hearing</span>";

                                        }
                                
                                        if(isset($getStageNameInfo['mainhead']) && ($getStageNameInfo['mainhead'] == 'M')){
                                            //echo "<br><span style='color: darkred; font-weight: bold;'>Misc. Hearing</span> - ".$row_avl5['stagename'];
                                            echo "<br><span style='color: darkred; font-weight: bold;'>Misc. Hearing</span> - ".$getStageNameInfo['stagename'];

                                        }
                                    
                                    if($getCategoryInfo >0 ){
                                        foreach($getCategoryInfo as $row){
                                            $retn = $row["sub_name1"];
                                            if($row["sub_name2"])
                                                $retn .= " - ".$row["sub_name2"];
                                            if($row["sub_name3"])
                                                $retn .= " - ".$row["sub_name3"];
                                            if($row["sub_name4"])
                                                $retn .= " - ".$row["sub_name4"];
                                            echo "<br><span style='color:blue; font-weight: bold;'>Category - category_sc_old".$retn." </span>";
                                        }
                                    }
                                    else{
                                        echo "<br><span style='color:red; font-weight: bold;'>Category - Not Mentioned</span>";
                                    }
                                    ?>
                                    <input name="valid_dno" type="hidden" id="valid_dno" value="<?php echo $row_avl['diary_no']; ?>">
                                   </div>
                                </div>
                                  <?php }else{ 
                                    echo "Record Not Available";?>

                                    <?php }?>

                                      <?php  if(!empty($alreadyInPool)){
                                         echo "<div style='text-align: center; color:red; font-size:28px;'>Already In Pool</div>";
                                        }else{ ?>
                                         <div style="width: 100%; padding-bottom:1px; background-color: #B5BBFF; text-align: center; ">
                                            <input type="button" name="data_update" id="data_update" value="SAVE" class="btn btn-primary">
                                            <span id="data_update_result"></span>
                                            <!--AND REQUIRED UPDATION-->
                                        </div>
                                      <?php } ?>
                                      <div id="di_rslt_sucs" style="text-align: center;"></div>
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
$(document).ready(function(){
    $("#data_update").click(function(){

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var valid_dno=$("#valid_dno").val();
        $("#data_update").hide();
        $.ajax({
            url: '<?php echo base_url('Listing/Pool/registrar_create_pool_get_response');?>',
            cache: false,
            async: true,
            data: {valid_dno:valid_dno,CSRF_TOKEN:CSRF_TOKEN_VALUE},
            beforeSend:function(){
                $("#di_rslt_sucs").html(
                    "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>"
                );
            },
            type: 'POST',
            success: function(data, status) {
                $('#di_rslt_sucs').html("");
                if(data.message == 1) {
                    $('#data_update_result').css({"color": "green"});
                    $('#data_update_result').html("Saved Successfully.");
                } else {
                    $('#data_update_result').css({"color": "red"});
                    $('#data_update_result').html("Error! Not Saved");
                    $("#data_update").show();
                }
                

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });


    });
});


function isEmpty(xx){
    var yy = xx.value.replace(/^\s*/, "");
    if(yy == ""){xx.focus();return true;}
    return false;
}
function isDate(txtDate)
{
    var currVal = txtDate;
    if(currVal == '')
        return false;

    //Declare Regex
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
    var dtArray = currVal.match(rxDatePattern); // is format OK?

    if (dtArray == null)
        return false;

    //Checks for mm/dd/yyyy format.
    dtDay= dtArray[1];
    dtMonth = dtArray[3];
    dtYear = dtArray[5];

    if (dtMonth < 1 || dtMonth > 12)
        return false;
    else if (dtDay < 1 || dtDay> 31)
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
        return false;
    else if (dtMonth == 2)
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap))
            return false;
    }
    return true;
}

</script>