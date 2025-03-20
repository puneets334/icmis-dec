<?= view('header') ?>
<?= view('partial/diary_search') ?>
<div id="dv_res1"></div>
<?php echo csrf_field(); ?>
<script type="text/javascript">
    function getDetails() 
    {
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
                swal("", "Please Select Casetype", "error");
                $("#case_type_casecode").focus();
                return false;
            }
            if (!regNum.test(csno)) {
                swal("", "Please Fill Case No in Numeric", "error");
                $("#case_number").focus();
                return false;
            }
            if (!regNum.test(csyr)) {
                swal("", "Please Fill Case Year in Numeric", "error");
                $("#case_year").focus();
                return false;
            }
            if (csno == 0) {
                swal("", "Case No Can't be Zero", "error");
                $("#case_number").focus();
                return false;
            }
            if (csyr == 0) {
                swal("", "Case Year Can't be Zero", "error");
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
                swal("", "Please Enter Diary No in Numeric", "error");
                $("#diary_number").focus();
                return false;
            }
            if (!regNum.test(diaryyear)) {
                swal("", "Please Enter Diary Year in Numeric", "error");
                $("#diary_year").focus();
                return false;
            }
            if (diaryno == 0) {
                swal("", "Diary No Can't be Zero", "error");
                $("#diary_number").focus();
                return false;
            }
            if (diaryyear == 0) {
                swal("", "Diary Year Can't be Zero", "error");
                $("#diary_year").focus();
                return false;
            }
        } else {
            swal("", "Please Select Any Option", "error");
            return false;
        }
        
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var form_data = $(this).serialize();
        $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?= base_url('Listing/VacationAdvanceList/addCase'); ?>",
                beforeSend: function(xhr) {
                    $("#dv_res1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='<?php echo base_url('images/load.gif'); ?>'></div>");
                },
                data: {
                    search_type:search_type,
                    diary_number:diaryno,
                    diary_year:diaryyear,
                    case_number:csno,
                    case_type:cstype,
                    case_year:csyr,
                    d_no: diaryno,
                    d_yr: diaryyear,
                    ct: cstype,
                    cn: csno,
                    cy: csyr,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    tab: 'Case Details'
                }
            })
            .done(function(result) {
                updateCSRFToken();
                if(result.redirect != undefined) {
                    window.location = result.redirect;
                    return true;
                }

                if(result.success == 1) {
                    $("#dv_res1").html(result.html);
                } else if(result.error != undefined) {
                    // swal(result.error);
                    $("#error_text").html(result.error);
                    $("#errors").removeClass("d-none").show();
                    $("#dv_res1").html('');
                }

            })
            .fail(function() {
                updateCSRFToken();

                $("#dv_res1").html('');
                swal("ERROR, Please Contact Server Room");
            });
    }
</script>