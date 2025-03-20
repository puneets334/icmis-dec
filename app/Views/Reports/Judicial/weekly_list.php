<form method="post" action="" id="weekly_list_form">
               

<div class="container">
<div class="col-md-12" style="text-align: center">
<span style="font-weight: bold; color:#4141E0; text-decoration: underline;">WEEKLY CAUSE LIST SECTION WISE</span>

<div class="row g-3">
    
  <div class="col-md-3  mt-2">

<select name="dp_wk" id="dp_wk" class="form-control">    
<option value="--_--">Weekly Date /
           Week Commencing Dt. </option></select>

</div>

        <div class="col-md-3  mt-2">
        <select class="form-control" name="courtno" id="courtno">
            <option value="0"><b>Court No.</b></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>

        </select>
</div>
    
<div class="col-md-3  mt-2">
        <select class="form-control" name="listing_purpose" id="listing_purpose">
                <option value="all" selected="selected"><b>Purpose of Listing</b></option>
        <option value="4">4. Fixed Date by Court</option><option value="5">5. Mention Memo</option><option value="32">32. FRESH</option><option value="25">25. Freshly Filed Adjourned</option><option value="7">7. Next Week / Week Commencing / C.O.Week</option><option value="8">8. After Week/Month/Vacation</option><option value="24">24. Auto Updated (CMIS)</option><option value="21">21. IA</option><option value="48">48. Not Reached / Adjourned</option><option value="2">2. Administrative Order</option><option value="16">16. Ordinary</option><option value="49">49. Vacation Matter</option> 
        </select>
</div>
  
    <!-- <legend>Action</legend> -->
    <div class="col-md-3  mt-2">
   <select class="form-control" name="main_suppl" id="main_suppl">                            
                            <option value="0"> <b>Main/Suppl.</b> </option>
                            <option value="1">Main</option>
                            <option value="2">Suppl.</option>            
                            </select>

</div>

<div class="col-md-3  mt-2">
<select name="sec_id" id="sec_id" class="form-control">
                            <option value="0"><b>Section Name</b></option>
                            <?php foreach($section as $sec) :?>
                            <option value="<?php echo $sec->id; ?>" > <?php echo $sec->section_name; ?></option>
                            <?php endforeach ?>
              
        </select>
</div>

<div class="col-md-3  mt-2">
              <select class="form-control" name="orderby" id="orderby">
            <option value="0"><b>Order By</b></option>
            <option value="1">Court Wise</option>
            <!-- <option value="2">Section Wise</option>             -->
        </select>
</div>

<div class="col mt-2">
        <input type="button" name="weekly_list" id="weekly_list" class="btn btn-primary float-right" value="Submit">
</div>

</div>
</div>
</div>       
</form>

<br>
         <div id="result_data"></div>

<script>
    $('#weekly_list').on('click', function () {
        //alert('hi');


            var form_data = $('#weekly_list_form').serialize();
            if(form_data){ //alert('readt post form');
                //var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Judicial/Report/weekly_list'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#weekly_list').val('Please wait...');
                        $('#weekly_list').prop('disabled', true);
                    },
                    success: function (data) {
                        //alert(data);
                        $('#weekly_list').prop('disabled', false);
                        $('#weekly_list').val('Submit');
                        $("#result_data").html(data);

                        //updateCSRFToken();
                    },
                    error: function () {
                        //updateCSRFToken();
                    }

                });
                return false;
            }
    });

</script>



<!-- DEBUG-VIEW ENDED 1 APPPATH/Views/Reports/court/gist_module_search_view.php -->
</div>

   </div>