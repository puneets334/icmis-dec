$(document).ready(function () {
    $(document).on('click', '#sub', function () {
		 
        var t_h_cno = $('#t_h_cno').val();
        var t_h_cyt = $('#t_h_cyt').val();
		 var CSRF_TOKEN = 'CSRF_TOKEN';
        var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: base_url + '/Filing/Defect/getReport',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: csrf,d_no: t_h_cno, d_yr: t_h_cyt},
            beforeSend: function () {
                $("#sub").prop("disabled",true);
                $('#div_result').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            },
            type: 'POST',
            success: function (data, status) {
                $('#div_result').html(data);
            },
            complete: function () {
				updateCSRFToken();
                $("#sub").prop("disabled",false);
            },
            
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });


    $(document).on('click','#btn_pnt',function(){
        var prtContent = document.getElementById('dv_print');
        var WinPrint = window.open('','','letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');

        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        document.getElementById('result').style.display= 'block';
        WinPrint.close();
    });

        });
