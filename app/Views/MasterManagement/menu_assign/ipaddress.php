<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
#reportTable1_filter{
    padding-right: 84%
}
       
.dataTables_filter{
    display: table;
}
div.dt-buttons {
    margin-bottom: -38px;
    margin-right: -80%;
}
div.dataTables_wrapper {
    position: relative;
    margin-top: 24px;
}

.dataTables_info{
    margin-top: 34px;
}
#grid_paginate{
    margin-top: 25px;
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
                                <h3 class="card-title">Master Management >> Case Block for Loose Doc</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div">
                                    <div class="d-block text-center">




                                     <!-- Main content -->
                                     <section class="content_">
                                    <div class="box-heading">
                                        <div class="box-title" style="background-color: #537881;"><b><h2 style="background-color: #537881; color: white; text-align: center; font-size: 20px;font-weight: bold">List of IP Address in Courts</h2></b></div>
                                    </div>

                                    <div class="box box-info">

                                        <form id="form1" class="form-horizontal" method="post" action="" >
                                            <div class="card-body">
                                                <div class="row" style="justify-content: center;">
                                                    <div class="col-2">
                                                        <label for="html">Physical Court</label>
                                                    </div>
                                                    <div class="col-1">
                                                    <input type="radio" name="court" value="physical" onclick="showdiv('phcourt');" checked>
                                                    </div>
                                                    <div class="col-3">
                                                        <label for="css">Virtual Court</label>
                                                    </div>
                                                    <div class="col-1">
                                                    <input type="radio" name="court" value="virtual" onclick="showdiv('vrcourt');">
                                                    </div>
                                                    <div class="col-6">
                                                        
                                                    </div>
                                               
                                                </div>
                                                <hr>
                                                <?php if(isset($user)){ ?>
                                                    <input type="hidden" id="custId" name="custId" value="<?php echo $user; ?>">
                                                <?php } ?>
                                                <div id="phcourt" class="phcourt">
                                                <div class="row" style="justify-content: center;">   
                                                    <label for="court_number" class="control-label col-md-4  requiredField">Select Court<span class="asteriskField"></span> </label>
                                                    <div class="control-label col-md-8">

                                                        <select class="select form-control" id="court_number" name="court_number">
                                                            <option value=""> ----select Court----</option>
                                                            <?php
                                                            $value= "<p id='result'>"."</p>";
                                                            for ($x = 1; $x <= 17; $x++) {
                                                                echo "<option value='$x'>"."Court No.".$x."</option>";
                                                                echo $value;
                                                            }
                                                            ?>
                                                            <option value="21"> Registrar Court 1</option>
                                                            <option value="22"> Registrar Court 2</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                </div>
                                               
                                                <div id="vrcourt" class="vrcourt" style="display: none">
                                                <div class="row" style="justify-content: center;">    
                                                    <label for="virtual_court_number" class="control-label col-md-4  requiredField">Select Virtual Court<span class="asteriskField"></span> </label>
                                                    <div class="control-label col-md-8">

                                                        <select class="select form-control" id="virtual_court_number" name="virtual_court_number">
                                                            <option value=""> ----select Court----</option>
                                                            <?php
                                                            $value= "<p id='result'>"."</p>";
                                                            for ($x = 31; $x <= 47; $x++) {
                                                                echo "<option value='$x'>"."Virtual Court No.".($x-30)."</option>";
                                                                echo $value;
                                                            }
                                                            ?>
                                                            <option value="61"> Registrar Court 1</option>
                                                            <option value="62"> Registrar Court 2</option>
                                                            <option value="65"> Registrar Court 3</option>


                                                        </select>
                                                    </div>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="row">
                                                    <label for="ip_address" class="control-label col-md-4  requiredField">Enter IP Address:<span class="asteriskField"></span> </label>
                                                    <div class="control-label col-md-8">
                                                        <input type="text" class="input-md textinput form-control" id="ip_address" name="ip_address" placeholder="xxx.xxx.xxx.xxx">
                                                    </div>
                                                </div>

                                                                
                                                <div class="row mt-2" style="justify-content: center;">
                                                    <div class="col-2">
                    <!--                                <input type="button" id="save" name="save" value="Save Details" class="btn btn btn-primary"  onclick="deletedata(3);"/>-->
                                                    <input type="button" id="update" name="activate" value="Activate" class="btn btn btn-primary" onclick="deletedata(2);"/>
                                                    </div>
                                                    <div class="col-2">
                                                    <input type="button" id="delete" name="deactivate" value="Deactivate" class="btn btn btn-primary" disabled="true" onclick="deletedata(1);"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="box box-info mt-4">
                                        <?php
                                        if(isset($ip_list)) {

                                            ?>
                                            <caption >
                                                <h2 style="background-color: #537881; color: white; text-align: center; font-size: 20px;font-weight: bold">
                                                    IP Address in Courts as on <?php echo date('d-m-Y h:m:s A')?>
                                                </h2>
                                            </caption>
                                            <table id="grid" class="table table-striped table-hover">

                                                <thead>
                                                <tr style="color:#a94442">
                                                    <th style="width: 5%;" rowspan='1'>SNo.</th>
                                                    <th style="width: 5%;" rowspan='1'>Court No.</th>
                                                    <th style="width: 10%;" rowspan='1'>IP Address</th>
                                                    <th style="width: 30%;" rowspan='1'>IP Entered By</th>
                                                    <th style="width: 30%;" rowspan='1'>IP Entered On</th>
                                                    <th style="width: 10%;" rowspan='1'>IP Entered By IP</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $s_no = 1;
                                                $court=0;
                                                $virtualcourt=0;
                                                foreach ($ip_list as $result) {
                                                    
                                                    ?>
                                                    <tr>
                                                        <td><?= $s_no; ?></td>
                                                        <td><?php
                                                            if($result['court_no']<=17){ echo $result['court_no'];}
                                                            if($result['court_no']>=31 && $result['court_no']<=47){ echo "Virtual Court ".($result['court_no']-30);}
                                                            if($result['court_no']==21 || $result['court_no']==22){ echo "Registrar Court ".($result['court_no']-20);}
                                                            if($result['court_no']==61 || $result['court_no']==62){ echo "Virtual Registrar Court ".($result['court_no']-60);}


                                                            ?></td>
                                                        <td><?php echo $result['ip_address']; ?></td>
                                                        <td><?php echo $result['entered_by']; ?></td>
                                                        <td><?php  
                                                            if (isset($result['entered_on']) && !empty($result['entered_on'])) {
                                                                $newformat = date('d-m-Y', strtotime($result['entered_on']));
                                                                if ($newformat == '30-11-0001') {
                                                                    echo "";
                                                                } else {
                                                                    echo $newformat;
                                                                }
                                                            } else {
                                                                echo "";  
                                                            }
                                                            ?></td>
                                                        <td><?php echo $result['entered_ip']; ?></td>
                                                    </tr>
                                                    <?php
                                                    $s_no++;
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </section>




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


<script>
    
  
 $(document).ready(function() {
$('#grid').DataTable( {
    /* dom: 'Bfrtip',
     buttons: [
         'excelHtml5',
         'pdfHtml5'
     ]*/

    "bProcessing"   :   true,
    "pageLength": 25,

    dom: 'Bfrtip',
    buttons: [
        'csv',
        'excel',
        {
            extend: 'print',
            customize: function ( win ) {
                $(win.document.body)
                    .css('font-size', '10pt');

                $(win.document.body).find( 'table' )
                    .addClass( 'compact' )
                    .css('text-align', 'center');

                $(win.document.body).find('table').addClass('display').css('margin', '5px');
                $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                $(win.document.body).find('h1').css('text-align', 'center');
            }
        }
    ]

});
});


function showdiv(id) {
if (id == 'vrcourt') {
    document.getElementById('phcourt').style.display = "none";
    document.getElementById(id).style.display='block';

}
else
{
    document.getElementById('vrcourt').style.display = "none";
    document.getElementById(id).style.display = "block";
//document.getElementById('post').value = null;
}
}


$('#court_number,#virtual_court_number').change(function () {
    var selectedValue = $(this).val();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    $.ajax({
        url: "<?=base_url('/MasterManagement/IPController/get_ip'); ?>",
        data:{selectedValue:selectedValue} ,
        cache: false,
        type: 'POST',
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
            },
        success: function(data) {
            updateCSRFToken();
            if(data!=null && data!="") {
                ip_address.value = data;
            //     document.getElementById("save").disabled = true;
            //     document.getElementById("update").disabled = false;
                document.getElementById("delete").disabled = false;
            //
            //
            }
            else{
                updateCSRFToken();
                document.getElementById("delete").disabled = true;
            //     document.getElementById("update").disabled = true;
            //     document.getElementById("save").disabled = false;
            //
            }
        },
        error: function () {
            updateCSRFToken();
            alert('ERROR');
        }
    });
    });


 
var ipv4_address = $('#ip_address');
ipv4_address.inputmask({
alias: "ip",
greedy:  false//The initial mask shown will be "" instead of "-____".
});

function reload() {
location.reload();
}


function deletedata(x){
var myform = document.getElementById("form1");
var fd = new FormData(myform);
var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
//alert(fd);
if(x==1) {
    $.ajax({
        url: "<?=base_url();?>/MasterManagement/IPController/delete_ip",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
            },
        success: function (data) {
            updateCSRFToken();
            const x = data;
            alert(x);
            reload();

        },
        error: function () {
            alert('Enter All Fields');
            updateCSRFToken();
        }
    });
}
else if(x==2)
{
    updateCSRFToken();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: "<?=base_url();?>/MasterManagement/IPController/update_ip",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
            },

        success: function (data) {
            updateCSRFToken();
            const x = data;
            alert(x);
            reload();

        },
        error: function () {
            updateCSRFToken();
            alert('Enter All Fields');
        }
    });
}
else if(x==3)
{

    updateCSRFToken();
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: "<?=base_url();?>index.php/IP_Controller/save_ip",
        data: fd,
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        headers: {
            'X-CSRF-Token': CSRF_TOKEN_VALUE  
            },
        success: function (data) {
            updateCSRFToken();
            const x = data;
            alert(x);
            reload();

        },
        error: function () {
            updateCSRFToken();
            alert('Enter All Fields');
        }
    });
}
}

</script>
