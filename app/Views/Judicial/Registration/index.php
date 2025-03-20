<?= view('header') ?>
<?= view('partial/diary_search') ?>
<div id="dv_res1"></div>
<?php echo csrf_field(); ?>
<script type="text/javascript">
    async function getDetails() 
    {
        await updateCSRFTokenSync();

        $('.alert').hide();

        document.getElementById("dv_res1").innerHTML = '';
        var search_type, diaryno, diaryyear, cstype, csno, csyr;
        var regNum = new RegExp('^[0-9]+$');

        if ($("#search_type_c").is(':checked')) {
            search_type = 'C';
            cstype = $("#case_type_casecode").val();
            csno = $("#case_number").val();
            csyr = $("#case_year").val();

            if (!regNum.test(cstype)) {
                alert("Please Select Casetype");
                $("#case_type_casecode").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                alert("Please Fill Case No in Numeric");
                $("#case_number").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                alert("Please Fill Case Year in Numeric");
                $("#case_year").focus();
                return false;
            }
            if (csno == 0) {
                alert("Case No Can't be Zero");
                $("#case_number").focus();
                return false;
            }
            if (csyr == 0) {
                alert("Case Year Can't be Zero");
                $("#case_year").focus();
                return false;
            }
            /*if(cstype.length==1)
                cstype = '00'+cstype;
            else if(cstype.length==2)
                cstype = '0'+cstype;*/
        } else if ($("#search_type_d").is(':checked')) {
            search_type = 'D';
            diaryno = $("#diary_number").val();
            diaryyear = $("#diary_year").val();
            if (!regNum.test(diaryno)) {
                alert("Please Enter Diary No in Numeric");
                $("#diary_number").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                alert("Please Enter Diary Year in Numeric");
                $("#diary_year").focus();
                return false;
            }
            if (diaryno == 0) {
                alert("Diary No Can't be Zero");
                $("#diary_number").focus();
                return false;
            }
            if (diaryyear == 0) {
                alert("Diary Year Can't be Zero");
                $("#diary_year").focus();
                return false;
            }
        } else {
            alert("Please Select Any Option");
            return false;
        }
        
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?= base_url('Judicial/Registration/register'); ?>",
                beforeSend: function(xhr) {
                    $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('cgwbspin.gif'); ?>'></div>");
                },
                data: {
                    search_type:search_type,
                    diary_number:diaryno,
                    diary_year:diaryyear,
                    d_no: diaryno,
                    d_yr: diaryyear,
                    case_type: cstype,
                    case_number: csno,
                    case_year: csyr,
                    ct: cstype,
                    cn: csno,
                    cy: csyr,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    tab: 'Case Details'
                }
            })
            .done(function(result) {
                // console.log(result.success);
                // console.log(result.html);

                if(result.redirect != undefined) {
                    window.location = result.redirect;
                    return true;
                }

                if(result.success == 1) {
                    $("#dv_res1").html(result.html);
                } else if(result.error != undefined) {
                    $("#dv_res1").html('');
                    alert(result.error);
                }

            })
            .fail(function() {
                $("#dv_res1").html('');
                alert("ERROR, Please Contact Server Room");
            });
    }
</script>