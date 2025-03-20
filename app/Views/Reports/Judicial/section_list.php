<style>
    b {
        font-weight: normal
    }

    fieldset {
        margin: 10px 10px 10px
    }
</style>
<center><span style="font-weight: bold; color:#4141E0; text-decoration: underline;">CAUSE LIST SECTION WISE (ONLY PUBLISHED LIST)</span></center>

<form method="post" action="" class="form-inline" id="Sectionlist_form">

    <div class="row gy-3">
        <div class="col-md-3  mt-2">
            Mainhead<br>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <label><input type="radio" name="mainhead" id="mainhead" value="M" title="Miscellaneous" checked="checked">M&nbsp;</label>
                </div>
                <div class="form-check form-check-inline">
                    <label><input type="radio" name="mainhead" id="mainhead" value="F" title="Regular">R&nbsp;</label>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-2">
            <label for="exampleInputEmail1">Listing Dates</label>
            <select class="ele" name="listing_dts" id="listing_dts">
                <option value="0" selected>SELECT</option>
                <?php
                if(!empty($dates_list)){
                    foreach($dates_list as $dates_list){
                ?>
                    <option value="<?php echo $dates_list['next_dt']; ?>"><?php echo date("d-m-Y", strtotime($dates_list['next_dt'])); ?></option>
                <?php
                    }
                } 
                ?>
            </select>
            <!-- <input class="form-control form-text text-muted" type="date" name="listing_dts" id="listing_dts"> -->
        </div>
        <div class="col-md-3 mt-2">
            <label for="" class="d-inline-block ml-3">Board Type</label> 
            <select name="board_type" id="board_type" class="form-control">
                <option value="0">ALL</option>
                <!-- <option value="A">ALL</option> -->
                <option value="J">Court</option>
                <option value="S">Single Judge</option>
                <option value="C">Chamber</option>
                <option value="R">Registrar</option>
            </select>
        </div>
        <div class="col-md-3 mt-2">
            <label for="" class="d-inline-block ml-3">Court No.</label>
            <select name="courtno" id="courtno" class="form-control">
                <option value="0"><b>-ALL-</b></option>
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
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="31">1 (Virtual Court)</option>
                <option value="32">2 (Virtual Court)</option>
                <option value="33">3 (Virtual Court)</option>
                <option value="34">4 (Virtual Court)</option>
                <option value="35">5 (Virtual Court)</option>
                <option value="21">21 (Registrar)</option>
                <option value="22">22 (Registrar)</option>
            </select>
        </div>
        <div class="col-md-3 mt-2">
            <label for="" class="d-inline-block ml-3">Purpose of Listing</label>
            <select class="form-control" name="listing_purpose" id="listing_purpose" style="max-width: 160px;">
                <option value="0" selected="selected">-ALL-</option>
                <option value="4">4. Fixed Date by Court</option>
                <option value="5">5. Mention Memo</option>
                <option value="32">32. FRESH</option>
                <option value="25">25. Freshly Filed Adjourned</option>
                <option value="7">7. Next Week / Week Commencing / C.O.Week</option>
                <option value="8">8. After Week/Month/Vacation</option>
                <option value="24">24. Auto Updated (CMIS)</option>
                <option value="21">21. IA</option>
                <option value="48">48. Not Reached / Adjourned</option>
                <option value="2">2. Administrative Order</option>
                <option value="16">16. Ordinary</option>
                <option value="49">49. Vacation Matter</option>
            </select>
        </div>
        <div class="col-md-3 mt-2">
            <label for="" class="d-inline-block ml-3">Main / Suppl.</label>
            <select class="form-control" name="main_suppl" id="main_suppl">
                <option value="0">-ALL-</option>
                <option value="1">Main</option>
                <option value="2">Suppl.</option>
            </select>
        </div>
        <div class="col-md-3 mt-2">
            <label for="" class="d-inline-block ml-3">Section Name</label>
            <select name="sec_id" id="sec_id" class="form-control">
                <option value="0"><b>-ALL-</b></option>
                <?php foreach ($section as $sec) : ?>
                    <option value="<?php echo $sec->id; ?>"> <?php echo $sec->section_name; ?></option>
                <?php endforeach ?>

            </select>
        </div>
        <div class="col-md-2 mt-2">
        <label for="" class="d-inline-block ml-3">Order By</label>
            <select class="form-control" name="orderby" id="orderby">
                <option value="0">-ALL-</option>
                <option value="1">Court Wise</option>
                <option value="2">Section Wise</option>
            </select>
        </div>

        <div class="col mt-2"><input type="button" name="btn1" id="Sectionlist" value="Submit" class="btn btn-primary float-right"></div>
    </div>


</form>
</div>
</div>

<div id="result_data"></div>

<script>
    $('#Sectionlist').on('click', function() {
        //alert('hi');


        var form_data = $('#Sectionlist_form').serialize();
        if (form_data) { //alert('readt post form');
            //var CSRF_TOKEN = 'CSRF_TOKEN';
            //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('.alert-error').hide();
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('Reports/Judicial/Report/Section_list'); ?>",
                data: form_data,
                beforeSend: function() {
                    $('#Sectionlist').val('Please wait...');
                    $('#Sectionlist').prop('disabled', true);
                },
                success: function(data) {
                    //alert(data);
                    $('#Sectionlist').prop('disabled', false);
                    $('#Sectionlist').val('Submit');
                    $("#result_data").html(data);

                    //updateCSRFToken();
                },
                error: function() {
                    //updateCSRFToken();
                }

            });
            return false;
        }
    });
</script>