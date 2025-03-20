<?= view('header') ?>
<?php  $uri = current_url(true); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="card-title">Appearance List</h3>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            <p id="show_error"></p>
                            <div class="card-body">


                            <?php
                                    $attribute = array('class' => 'form-horizontal','name' => 'component_search', 'id' => 'component_search', 'autocomplete' => 'off');
                                    echo form_open(base_url('#'), $attribute);
                                    ?>
                            <div class="row">
    <div class="col-md-5 diary_section">
        <div class="form-group row">
            <label for="diary_number" class="col-sm-5 col-form-label">List Date</label>
            <div class="col-sm-7">
            <input type="date" class="form-control list_date" placeholder="Date..." >
            </div>
        </div>
    </div>
    <div class="col-md-5 diary_section">
        <div class="form-group row">
            <label for="diary_year" class="col-sm-5 col-form-label">Court No</label>
            <div class="col-sm-7">
            <select class="form-control courtno" aria-describedby="courtno_addon">
                <option value="0">-Select-</option>
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
                <option value="21">21 (Registrar)</option>
                <option value="22">22 (Registrar)</option>
            </select>
            </div>
        </div>
    </div>

    <div class="col-2 pl-4 mb-3">
            <input id="btn_search" name="btn_search" type="button" class="btn btn-success btn-block"
                   value="Search">
        </div>


        <div class="row col-md-12 m-0 p-0" id="result"></div>
</div>

<?php form_close();?>





</div>


                            
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
    $("#btn_search").click(function(){


        $("#result").html(""); $('#show_error').html("");
        var list_date = $(".list_date").val();
        var courtno = $(".courtno").val();

        if (list_date.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select cause list date</strong></div>');
            return false;
        }
        else if (courtno == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please select court number</strong></div>');
            return false;
        }
        else{
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url:'<?php echo base_url('Listing/Appearance/list_process/'); ?>',
                cache: false,
                async: true,
                data: {list_date:list_date,courtno:courtno,CSRF_TOKEN:CSRF_TOKEN_VALUE},
                beforeSend:function(){
                    $('#result').html('<table widht="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
                },
                type: 'POST',
                success: function(data, status) {
                    $("#result").html(data);
                    updateCSRFToken();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                    updateCSRFToken();
                }
            });

        }
    });
    </script>
