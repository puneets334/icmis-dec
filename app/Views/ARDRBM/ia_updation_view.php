
<center><h5>Loose Documents - Modification</h5></center>
<?php
$attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
echo form_open(base_url('#'), $attribute);
?>
<?php echo component_html();?>

<input type="hidden" class="form-control" id="redirect_url" name="redirect_url" value="<?=$current_page_url;?>" placeholder="Enter redirect url <?=$current_page_url;?>" >
<center> <button type="submit" class="btn btn-primary" id="submit">Submit</button></center>
<?php form_close();?>
<br/><br/>
<center><span id="loader"></span> </center>
<span class="alert alert-error" style="display: none; color: red;">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<span class="form-response"> </span>
</span>
<div id="record" class="record"></div>



<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.js'); ?>"></script>

<link href="<?php echo base_url('autocomplete/autocomplete.css');?>" rel="stylesheet">
<!--<script src="<?php /*echo base_url('autocomplete/autocomplete.min.js'); */?>"></script>-->
<script src="<?php echo base_url('autocomplete/autocomplete-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('filing/diary_add_filing.js'); ?>"></script>
<script>
    $(document).ready(function() {
        $('#component_search').on('submit', function () {
            var search_type = $("input[name='search_type']:checked").val();
            if (search_type.length == 0) {
                alert("Please select case type");
                validationError = false;
                return false;
            }
            var diary_number = $("#diary_number").val();
            var diary_year =$('#diary_year :selected').val();

            var case_type =$('#case_type :selected').val();
            var case_number = $("#case_number").val();
            var case_year =$('#case_year :selected').val();

            if (search_type=='D') {
                if (diary_number.length == 0) {
                    alert("Please enter diary number");
                    validationError = false;
                    return false;
                }else if (diary_year.length == 0) {
                    alert("Please select diary year");
                    validationError = false;
                    return false;
                }
            }else if (search_type=='C') {

                if (case_type.length == 0) {
                    alert("Please select case type");
                    validationError = false;
                    return false;
                }else if (case_number.length == 0) {
                    alert("Please enter case number");
                    validationError = false;
                    return false;
                }else if (case_year.length == 0) {
                    alert("Please select case year");
                    validationError = false;
                    return false;
                }

            }

            if ($('#component_search').valid()) {
                var validateFlag = true;
                var form_data = $(this).serialize();
                if(validateFlag){
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    $('.alert-error').hide(); $(".form-response").html("");
                    $("#loader").html('');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('Filing/Diary/search'); ?>",
                        data: form_data,
                        beforeSend: function () {
                            $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                        },
                        success: function (data) {
                            $("#loader").html('');
                            updateCSRFToken();
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                get_ia_updation_list(resArr[1]);
                            } else if (resArr[0] == 3) {
                                $('.alert-error').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                            }
                        },
                        error: function() {
                            updateCSRFToken();
                            alert('Something went wrong! please contact computer cell');
                        }
                    });
                    return false;
                }
            } else {
                return false;
            }
        });
    });

    function get_ia_updation_list(url) {

        $('#record').html('');

        var radio = $("input[type='radio'][name='search_type']:checked").val();

        var ia_search = "<?=base_url('ARDRBM/IA/get_ia_updation_list')?>";
        $('#record').html('');
        $.ajax({
            type: "GET",
            url: ia_search,
            data:{radio: radio,option: 1},
            beforeSend: function () {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function (data) {
                $('#loader').html('');
                updateCSRFToken();
                $("#record").html(data);
            },
            error: function () {
                updateCSRFToken();
                //alert('Something went wrong! please contact computer cell');
            }
        });

    }
    function delete_ld(docd_id) {
        var r = confirm("Are you Sure, Record to be Delete.");
        if(r == true) {
            $('#loader').html('');
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('ARDRBM/IA/delete_ia_updation'); ?>",
                data: {type: 'D', docd_id: docd_id},
                beforeSend: function () {
                    $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                success: function (data) {
                    var resArr = data.split('@@@');
                    get_ia_updation_list(resArr[1]);
                    alert(resArr[1]);
                    if (resArr[0] == 1) {
                        ('#loader').html(resArr[1]);

                    } else if (resArr[0] == 3) {
                        ('#loader').html(resArr[1]);
                    }
                }
            });
        }
    }

    function replace_amp(x){
        document.getElementById(x).value=document.getElementById(x).value.trim();
        document.getElementById(x).value=document.getElementById(x).value.replace( '&', ' and ' );
        document.getElementById(x).value=document.getElementById(x).value.replace( "'", "");
        document.getElementById(x).value=document.getElementById(x).value.replace( "#", "No");
    }

    $(document).on("focus","#m_doc1",function(){
        $("#m_doc1").autocomplete({
            source:"<?php echo base_url('ARDRBM/IA/getDoc_type'); ?>",
            width: 450,
            matchContains: true,
            minChars: 1,
            selectFirst: false,

            select: function (event, ui){
                // Set autocomplete element to display the label
                this.value = ui.item.label;
                // Store value in hidden field
                $('#hd_doc_type1').val(ui.item.value);
                // Prevent default behaviour
                showXtraDesc1(ui.item.value);
                if(ui.item.value==19){
                }
                else{
                }
                return false;
            },
            focus: function( event, ui){
                $("#m_doc1").val(ui.item.label);
                return false;
            }
        });
    });


    function showXtraDesc1(d2){

        if(d2>0) {
            if(document.getElementById('m_doc').value==8  && d2==19)
            {
                document.getElementById('m_desc').disabled=false;
                document.getElementById('m_desc').focus();
            }
            else
            {
                document.getElementById('m_desc').disabled=true;
                document.getElementById('m_desc').value='';
            }
        }
        else{
            document.getElementById('m_desc').disabled=true;

        }
    }


    function update_ld(docd_id) {
        $.ajax({
            type: "GET",
            data: {idfull: docd_id},
            url: "<?php echo base_url('ARDRBM/IA/get_ia_updation_content'); ?>",
            beforeSend: function () {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function (data)
            {
                $("#loader").html('');
                $('#sar').html(data);
            }
        });
    }
    function calcelFunct(){
        $('#sar').html('');
    }

    function ia_update_Funct(id){
        alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
            " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");

        var regNum = new RegExp('^[0-9]+$');

        /*if($('#fb').val()==''){
            alert('Please put a value in FiledBy');
            $('#fb').focus();
            return false;
        } */
        if($('#noc').val()==''){
            alert('Please put a value in No of Copy');
            $('#noc').focus();
            return false;
        }
        /*var fr='';
        if($('#fr')){
            if($('#fr').val()==''){
                alert('Please put a value in For Respondent');
                $('#fr').focus();
                return false;
            }
            fr = $('#fr').val();
        } */

        if($("#m_doc").val()=='0'){
            alert("Please Select Document Type");
            $("#m_doc").focus();
            return false;
        }
        if($("#m_doc").val() == 8){
            if($("#m_doc1").val()==''){
                alert("Please Select IA");
                $("#m_doc1").focus();
                return false;
            }
        }
        if($("#aor_code").val() == ''){
            alert("Please enter AOR code");
            $("#aor_code").focus();
            return false;

        }
        if(!regNum.test($("#docnum").val())){
            alert("Please Enter Document No. in Numeric Value");
            $("#docnum").focus();
            return false;
        }

        if(!regNum.test($("#docyr").val())){
            alert("Please Enter Document Year in Numeric Value");
            $("#docyr").focus();
            return false;
        }

        if(!regNum.test($("#df").val())){
            alert("Please Enter Amount in Numeric Value");
            $("#df").focus();
            return false;
        }

        if(!regNum.test($("#noc").val())){
            alert("Please Fill No. of Copies in Numeric");
            $("#noc").focus();
            return false;
        }

        var if_efil=0;
        if($("#if_efil").is(":checked")){
            if_efil=1;
        }

        var idfull=id;
        var fee=$("#df").val();
        var noc=$('#noc').val();
        var rem=$("#remark_ld").val();
        var doccode=$("#m_doc").val();
        var doccode1=$("#hd_doc_type1").val();
        var other1=$("#m_desc").val();
        var docno=$("#docnum").val();
        var docyr=$("#docyr").val();
        var aor=$("#aor_code").val();
        var aor_name=$("#adv_name").text();
        var if_efil=0;
        if($("#if_efil").is(":checked"))
            if_efil=1;
        $.ajax({
            type: "GET",
            data: {type:'U',idfull:idfull, fee:fee,noc:noc,rem:rem,doccode:doccode,doccode1:doccode1,other1:other1,docno:docno,docyr:docyr,aor:aor,aor_name:aor_name,if_efil:if_efil},
            url: "<?php echo base_url('ARDRBM/IA/ia_update'); ?>",
            beforeSend: function () {
                $("#loader").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
            },
            success: function (data)
            {
                alert(data);
                get_ia_updation_list(data);
               /* $("#loader").html('');
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msgsar').html(resArr[1]);
                    setTimeout(function() {
                        window.location.reload();
                    }, 5000);
                } else if (resArr[0] == 3) {
                    $('#msgsar').html(resArr[1]);
                    $('.alert-error').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                }*/

            }
        });
    }
</script>

