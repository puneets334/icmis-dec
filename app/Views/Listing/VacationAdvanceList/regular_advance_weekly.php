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
                            Regular Vacation Advance Weekly
                        </h4>
                        <div>
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

function get_cl_1(){
    $('.footer').hide();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
      $.ajax({
            url: 'get_regular_advance_weekly',
            cache: false,
            async: true,
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,action: 'getVacationAdvanceList'},
            beforeSend:function(){
               $('#dv_res1').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(response, status) {  
                
                updateCSRFToken();             
                var data = JSON.parse(response);
                console.log("hdfhjdsh" + data); 
                var table = '<table border="1">';
                table += '<tr>';
                table += '<th style="min-width:90px;">S.No</th><th>Case No & Diary No</th><th>Causetitle</th>';
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
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        updateCSRFToken();
}
        
$(document).ready(function() {
   
    $('#footerButton').on('click', function() {
        var CSRF_TOKEN_VALUE = $("input[name='CSRF_TOKEN']").val();
        var checkedDiaryNos = [];
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


