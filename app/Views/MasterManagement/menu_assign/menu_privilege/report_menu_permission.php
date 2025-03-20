<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">

<style> 
 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> Menu Privilege</h3>
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
                                     <form method="post" action="#">
                                        <div class="card mt-5">
                                            <div class="card-header">
                                            <h2 class="text-center">Report Regarding Menu Permissions</h2>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4 mt-4">
                                                    <label for="ddl_mn_menu">Main Menu</label>
                                                    <select name="ddl_mn_menu" id="ddl_mn_menu" class="form-control e1">
                                                        <option value="">--Select Option--</option>
                                                        <?php foreach($main_menu as $key=>$row)  {
                                                          echo '<option value="'.$row['id'].'">'.$row['menu_nm'].'</option>';
                                                            }   ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 align-self-end">
                                                    <button type="button" class="btn btn-primary" onclick="save_sub_menu();">Submit</button>
                                                </div>
                                            </div>

                                            <div class="text-center mb-3 mt-4"><strong>OR</strong></div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="ddl_mn_menu_3">Second Menu</label>
                                                    <select name="ddl_mn_menu_3" id="ddl_mn_menu_3" class="form-control e1" onchange="get_sub_menu_3(this.value)">
                                                        <option value="">--Select--</option>
                                                        <?php foreach($main_menu as $key=>$row)  {
                                                          echo '<option value="'.$row['id'].'">'.$row['menu_nm'].'</option>';
                                                            }  
                                                        ?>
                                                       
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="ddl_sub_menu_3 mt-3">Sub Menu</label>
                                                    <select name="ddl_sub_menu_3" id="ddl_sub_menu_3" class="form-control e1">
                                                        <option value="">Select</option>
                                                      
                                                    </select>
                                                </div>
                                                <div class="col-md-2 align-self-end">
                                                    <button type="button" class="btn btn-primary" onclick="save_sub_menu_3();">Submit</button>
                                                </div>
                                            </div>

                                            <div class="text-center mb-3 mt-4"><strong>OR</strong></div>

                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="ddl_mn_menu_per">Third Menu</label>
                                                    <select name="ddl_mn_menu_per" id="ddl_mn_menu_per" class="form-control e1" onchange="get_sub_menu_4(this.value)">
                                                        <option value="">--Select--</option>
                                                        <?php foreach($main_menu as $key=>$row)  {
                                                          echo '<option value="'.$row['id'].'">'.$row['menu_nm'].'</option>';
                                                            }  
                                                        ?>
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="ddl_sub_menu_5">Sub Menu</label>
                                                    <select name="ddl_sub_menu_5" id="ddl_sub_menu_5" class="form-control e1" onchange="get_sub_sub_menu_4(this.value)">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="ddl_sub_sub_menu_5">Sub Sub Menu</label>
                                                    <select name="ddl_sub_sub_menu_5" id="ddl_sub_sub_menu_5" class="form-control e1">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-3 mt-4">
                                                <button type="button" class="btn btn-primary" onclick="save_sub_menu_4();">Submit</button>
                                                </div>
                                            </div>

                                                                                  
                                        <div class="card" style="align-items: center;">   
                                             
                                         <div class="col-6">       
                                            <div id="result"></div>
                                        </div>
                                        </div>
                                    </div>
                                    </form>


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
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>/js/select2.min.js"></script>
  <!-- Include Select2 and DataTables JS/CSS -->
 
<script>

$(document).ready(function () {
    $(".e1").select2();
});

    
  function save_sub_menu()
  {
             
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var ddl_mn_menu=$('#ddl_mn_menu').val();
         // alert(" the main menu is "+ddl_mn_menu);
        // var txt_sub_menu=$('#txt_sub_menu').val(); 
        //var ddl_order=$('#ddl_order').val();
        if(ddl_mn_menu=='')
            {
                 alert("Please select main menu");
            }
            else 
        {
        $('#whole_page_loader').show(); 
        $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/RepMenuPermission',
        cache:false,
        async:true,
        beforeSend:function(){
        $('#dv_sub_mn').html('<table width="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
        },
        data:{ddl_mn_menu:ddl_mn_menu},
        type:'POST',
        headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
        success:function(data,status)
        {          
            $('#whole_page_loader').hide();          
            updateCSRFToken()
            $('#result').html(data);
            // $('#dv_res_x').html('');
           // $('#btn_sub_menu').attr("disabled",false);
          //  $('#txt_sub_menu').val('');
          //  $('#ddl_mn_menu').val('');
          
          
        },
        error:function(xhr){
            $('#whole_page_loader').hide();     
            updateCSRFToken()
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
                }
         }

function revokethis(id) {
    $('#whole_page_loader').show(); 
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            $('#whole_page_loader').hide(); 
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (response.status === 200) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            } else {
                alert("Request failed. Status: " + this.status);
            }
        }
    };
    xmlhttp.open("GET", "<?=base_url()?>/MasterManagement/Menu_assign/RevokeMainMenu?q=" + id, true);
    xmlhttp.send();
}

function printDiv(divID) {
    var printButton = document.getElementById('print1');
    printButton.style.display = 'none';
    var divElements = document.getElementById(divID).innerHTML;
    var newWindow = window.open('', '', 'width=800,height=600');
    newWindow.document.write('<style>body { font-family: Arial; }</style>');
    newWindow.document.write('</head><body>');
    newWindow.document.write(divElements);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
    newWindow.close();
    printButton.style.display = 'block';
}


    function save_sub_menu_3()
    {
         
        var ddl_mn_menu=$('#ddl_mn_menu_3').val();
        var ddl_sub_menu_3=$('#ddl_sub_menu_3').val();
        if(ddl_mn_menu=='')
            {
                alert("Please select main menu");
            }
        else if(ddl_sub_menu_3=='')
            {
                alert("Please select sub menu");
                
            }else
         {

        $('#whole_page_loader').show();   
         $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/Rep_Second_Menu',
        beforeSend:function(){
        $('#dv_sub_mn_3').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
        },
        data:{ddl_mn_menu:ddl_mn_menu,ddl_sub_menu_3:ddl_sub_menu_3},
        type:'GET',
        success:function(data,status){
            $('#whole_page_loader').hide();     
            $('#result').html(data);
       // $('#dv_res_x').html('');
        },
        error:function(xhr){
            $('#whole_page_loader').hide();     
            alert("Error: "+xhr.status+" "+xhr.statusText);
             }
        
             });
            }
         }

function get_sub_menu_3(str)
    {   
        $('#whole_page_loader').show();     
        $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/getSubMenus',
        data:{str:str},
        type:'GET',
        success:function(data,status){
            $('#whole_page_loader').hide();     
            $('#ddl_sub_menu_3').html(data);
        },
        error:function(xhr){
            $('#whole_page_loader').hide(); 
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
           
        
    });
    }

    function get_sub_menu_4(str)
     {    
        $('#whole_page_loader').show();    
        $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/getSubMenus',
        data:{str:str},
        type:'GET',
        success:function(data,status){
            $('#whole_page_loader').hide();    
            $('#ddl_sub_menu_5').html(data);

        },
        error:function(xhr){
            $('#whole_page_loader').hide();    
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
    }


    function get_sub_sub_menu_4(str)
    {
        var ddl_mn_menu_per=$('#ddl_mn_menu_per').val();
        $('#whole_page_loader').show();    
        $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/getSubSubMenu',
        data:{str:str,ddl_mn_menu_per:ddl_mn_menu_per},
        type:'GET',
        success:function(data,status){
            $('#whole_page_loader').hide();    
            $('#ddl_sub_sub_menu_5').html(data);

        },
        error:function(xhr){
            $('#whole_page_loader').hide();    
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
    }


function save_sub_menu_4()
{               
          var ddl_mn_menu_per=$('#ddl_mn_menu_per').val();
            var ddl_sub_menu_5=$('#ddl_sub_menu_5').val();
            var ddl_sub_sub_menu_5=$('#ddl_sub_sub_menu_5').val();

             if(ddl_mn_menu_per.trim()=='')
              {
                 alert("Please select main menu");
              }else if(ddl_sub_menu_5.trim()=='')
            {
                alert("Please select sub menu");
            }else if(ddl_sub_sub_menu_5.trim()=='')
            {
                 alert("Please select third menu");
            }else 
             {
        $('#whole_page_loader').show();    
        $.ajax({
        url:'<?=base_url()?>/MasterManagement/Menu_assign/RepThirdMenu',
        beforeSend:function(){
        $('#dv_sub_mn_4').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
        },
        data:{ddl_mn_menu_per:ddl_mn_menu_per,ddl_sub_menu_5:ddl_sub_menu_5,ddl_sub_sub_menu_5:ddl_sub_sub_menu_5},
        type:'GET',
        success:function(data,status){
            $('#whole_page_loader').hide();    
        $('#result').html(data);

        },
        error:function(xhr){
            $('#whole_page_loader').hide();    
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
       });
         }
       }
       
</script>
