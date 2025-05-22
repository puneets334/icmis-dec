<style>
.cust-table thead th{background:none;}
.cust-table thead th:first-child,.cust-table thead th:last-child,.cust-table tbody td:last-child
{border-radius: 0px;}
.cust-table thead th,.cust-table tbody td,.cust-table tbody tr th { 
    border-right: #999 1px solid;
}
.cust-table tbody td:first-child,.cust-table tbody tr th:first-child {
    border-left: #000 1px solid;
    border-radius: 0px;
}   
/* .custom-table tbody td:last-child,.custom-table tbody tr th:last-child {
    border-bottom: #000 1px solid;
}   */
.cust-table tbody tr:last-child th{
    border-bottom: #000 1px solid;

}
</style>
<?= view('header') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Regular Vacation Advance Weekly</h3>
                            </div>
                           
                        </div>
                    </div>
                    <?php
                    echo form_open();
                    csrf_token();
                    ?>
                     <div id="dv_content1" class="container mt-4">
                        <h4>
                        Partial Court Working Days Advance Weekly
                        </h4>
                        <div>
                        <input type="radio" name="mainhead" id="mainhead1" value="M" title="Miscellaneous" <?= $mainhead == 'M' ? 'checked' : '' ?>>Miscellaneous</input>
                        <input type="radio" name="mainhead" id="mainhead2" value="F" title="Regular" <?= $mainhead == 'F' ? 'checked' : '' ?>>Regular</input>
                            <input type="button" name="btn1" id="getDataButton" value="Get Data" class="btn btn-primary" />
                            
                        </div>
                        <br><br>
                        <div id="dataTable"></div>
                        <div class="footer">
                        <input type="button" name="btn1" id="footerButton" value="Generate List" class="btn btn-primary" />
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</section>
        
<script>
 $('.footer').hide(); 
$(document).on("click","#getDataButton",function(){
   get_cl_1();
});  

async function get_cl_1(){
    $('.footer').hide();
    await updateCSRFTokenSync();
    var mainhead = $('input[type=radio][name=mainhead]:checked').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
      $.ajax({
            url: 'get_regular_advance_weekly',
            cache: false,
            async: true,
            data: {mainhead: mainhead,CSRF_TOKEN: CSRF_TOKEN_VALUE,action: 'getVacationAdvanceList'},
            beforeSend:function(){
               $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(response, status) {  
                
                //updateCSRFToken();             
                var data = JSON.parse(response);
                console.log("hdfhjdsh" + data); 
                // var table = '<table border="1">';
                if (mainhead == 'M') {
                    var table = '<h2 style="text-align: center;"> Miscellaneous Stage Cases</h2><table class ="cust-table" border="1">';
                } else {
                    var table = '<h2 style="text-align: center;"> Regular Stage Cases</h2><table class ="cust-table" border="1">';
                }
                table += '<tr>';
                table += '<th style="min-width:90px;background-color:#0069d9;color:#fff;">S.No</th><th style="background-color:#0069d9;color:#fff;">Case No & Diary No</th><th style="background-color:#0069d9;color:#fff;">Causetitle</th>';
                table += '</tr>';
                data.forEach(function(row) {
                    var regDiary = row.reg_no_display + ' & ' + row.diary_no;
                    var petVsRes = row.pet_name + ' VS ' + row.res_name;
                    table += '<tr>';
                    table += '<td><input type="checkbox" class="case-checkbox" data-diary-no="' + row.diary_no + '">' + row.sno + '</td>';
                    table += '<td>' + regDiary + '</td>';
                    table += '<td>' + petVsRes + '</td>';
                    table += '</tr>';
                });
                table += '</table>';
                $('#dataTable').html(table);
                $('.footer').show();      
            },
            error: function(xhr) {
                //updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
       // updateCSRFToken();
}
        
$(document).ready(function() {
   
    $('#footerButton').on('click', async function() {
        await updateCSRFTokenSync();
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var checkedDiaryNos = [];
        var mainhead = $('input[type=radio][name=mainhead]:checked').val();
        $('.case-checkbox:checked').each(function() {
            checkedDiaryNos.push($(this).data('diary-no'));
        });

        if (checkedDiaryNos.length === 0) {
        alert("Please select at least one diary number."); // Display an alert
        return; // Stop the function execution
        }


        var form = $('<form>', {
            'method': 'POST',
            'action': 'list_regular_advance_weekly',
            'target': '_blank'
        });

        form.append($('<input>', {
        'type': 'hidden',
        'name': 'mainhead',
        'value': mainhead
        }));
        form.append($('<input>', {
        'type': 'hidden',
        'name': 'CSRF_TOKEN',
        'value': CSRF_TOKEN_VALUE
        }));

        checkedDiaryNos.forEach(function(diaryNo) {
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'diaryNos[]',
                'value': diaryNo
            }));
        });

        form.appendTo('body').submit();
    });

    $(document).on('click', '.case-checkbox', function() {
        if ($(this).is(':checked')) {
            $(this).attr('disabled', true);
        }
    });
    


});
</script> 


