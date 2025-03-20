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
<?php 
// if(session_status() == PHP_SESSION_NONE or session_id() == '')
// session_start();
// $ucode = $_SESSION['dcmis_user_idd'];
// function get_client_ip() {
//     $ipaddress = '';
//     if (getenv('HTTP_CLIENT_IP'))
//         $ipaddress = getenv('HTTP_CLIENT_IP');
//     else if(getenv('HTTP_X_FORWARDED_FOR'))
//         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//     else if(getenv('HTTP_X_FORWARDED'))
//         $ipaddress = getenv('HTTP_X_FORWARDED');
//     else if(getenv('HTTP_FORWARDED_FOR'))
//         $ipaddress = getenv('HTTP_FORWARDED_FOR');
//     else if(getenv('HTTP_FORWARDED'))
//        $ipaddress = getenv('HTTP_FORWARDED');
//     else if(getenv('REMOTE_ADDR'))
//         $ipaddress = getenv('REMOTE_ADDR');
//     else
//         $ipaddress = 'UNKNOWN';
//     return $ipaddress;
// }
// $ip_address=get_client_ip();

?>
            <form method="post" action="#">
              <div id="dv_content1"   >
                  <fieldset style="width: 40%;float: left;text-align: center">
                      <legend>Main Menu</legend>
                      <div>
                          <input type="text" name="txt_mn_menu" id="txt_mn_menu"/>
                          <br/><br/>
                          <input type="button" name="btn_mn_menu" id="btn_mn_menu" value="Submit" onclick="save_mn_menu();"/>
                      </div>
                      <div id="dv_mn_mn"></div>
                  </fieldset>
                   <fieldset style="width:40%;float: left;">
                      <legend>Second Menu</legend>
                      <div>
                          <?php
                          //$mn_menu="Select id,menu_nm from menu where display='Y'";
                          //$mn_menu_s=  mysql_query($mn_menu) or die("Error: ".__LINE__.  mysql_error());

                          $mn_menu_s = is_data_from_table('master.menu', " display='Y' ", 'id,menu_nm', 'A');
                          ?>
                          <table width="100%" cellpadding="5" cellspacing="5">
                              <tr>
                                  <th>
                                   Main Menu   
                                  </th>
                                  <td>
                                       <select name="ddl_mn_menu" id="ddl_mn_menu">
                              <option value="">Select</option>
                              <?php
                           foreach ($mn_menu_s as $row)
                              {
                                     ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['menu_nm']; ?></option>
                              <?php
                               }
                              ?>
                          </select>
                                  </td>
                              </tr>
                              <tr>
                                  <th>
                                      Sub Menu
                                  </th>
                                  <td>
                                   <input type="text" name="txt_sub_menu" id="txt_sub_menu"/>    
                                  </td>
                              </tr>
                              <tr>
                                  <th>
                                      Order
                                  </th>
                                  <td>
                                      <select name="ddl_order" id="ddl_order">
                                          <option value="">Select</option>
                                        <?php
                                        for($i=1;$i<=200;$i++)
                                        {
                                            ?>
                                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                          <?php
                                        }
                                        ?>
                                       </select>
                                  </td>
                              </tr>
                              <tr>
                                  <th colspan="2">
                                     <input type="button" name="btn_sub_menu" id="btn_sub_menu" value="Submit" onclick="save_sub_menu();"/>  
                                  </th>
                              </tr>
                          </table>
                        
                  
                         
                      </div>
                      <div id="dv_sub_mn"></div>
                  </fieldset>
                  
                  
                   <fieldset style="width: 40%;float: left;clear: both">
                      <legend>Third Menu</legend>
                      <div>
                          <?php
                          //$mn_menu="Select id,menu_nm from menu where display='Y'";
                          //$mn_menu_s=  mysql_query($mn_menu) or die("Error: ".__LINE__.  mysql_error());
                          //$mn_menu_s = is_data_from_table('menu', " display='Y' ", 'id,menu_nm', 'A');
                          ?>
                          <table width="100%" cellpadding="5" cellspacing="5">
                              <tr>
                                  <th>
                                   Main Menu   
                                  </th>
                                  <td>
                                       <select name="ddl_mn_menu_3" id="ddl_mn_menu_3" onchange="get_sub_menu_3(this.value)">
                              <option value="">Select</option>
                              <?php
                           foreach ($mn_menu_s as $row)
                              {
                                     ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['menu_nm']; ?></option>
                              <?php
                               }
                              ?>
                          </select>
                                  </td>
                              </tr>
                                 <tr>
                                  <th>
                                   Second Menu   
                                  </th>
                                  <td>
                                    
                                      <select name="ddl_sub_menu_3" id="ddl_sub_menu_3" >
                              <option value="">Select</option>
                              <?php
                           foreach ($mn_menu_s as $row)
                              {
                                     ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['menu_nm']; ?></option>
                              <?php
                               }
                              ?>
                          </select>
                                  </td>
                              </tr>
                              <tr>
                                  <th>
                                      Third Menu
                                  </th>
                                  <td>
                                   <input type="text" name="txt_sub_sub_menu_3" id="txt_sub_sub_menu_3"/>    
                                  </td>
                              </tr>
                              
                               <tr>
                                  <th>
                                     URL
                                  </th>
                                  <td>
                                   <input type="text" name="txt_url" id="txt_url"/>    
                                  </td>
                              </tr>


                              <tr>
                                  <th colspan="2">
                                     <input type="button" name="btn_sub_menu_3" id="btn_sub_menu_3" value="Submit" onclick="save_sub_menu_3();"/>  
                                  </th>
                              </tr>
                          </table>
                        
                  
                         
                      </div>
                      <div id="dv_sub_mn_3"></div>
                  </fieldset>
                  
                  <fieldset style="width: 40%;float: left">
                      <legend>Main Menu Permission</legend>
                      <?php
                      //$mn_menu="Select id,menu_nm from menu where display='Y'";
                         // $mn_menu_s=  mysql_query($mn_menu) or die("Error: ".__LINE__.  mysql_error());
                      ?>
                      <table>
                        <tr>
                                  <th>
                                   Main Menu   
                                  </th>
                                  <td>
                                       <select name="ddl_mn_menu_per" id="ddl_mn_menu_per" onchange="get_sub_menu_4(this.value)">
                              <option value="">Select</option>
                              <?php
                           foreach ($mn_menu_s as $row)
                              {
                                     ?>
                              <option value="<?php echo $row['id']; ?>"><?php echo $row['menu_nm']; ?></option>
                              <?php
                               }
                              ?>
                          </select>
                                  </td>
                              </tr>
                              
                              <tr>
                                  <th>
                                   Second Menu   
                                  </th>
                                  <td>
                                    
                                      <select name="ddl_sub_menu_5" id="ddl_sub_menu_5" onchange="get_sub_sub_menu_4(this.value)">
                              <option value="">Select</option>
                             
                          </select>
                                  </td>
                              </tr>
                              
                              <tr>
                                  <th>
                                   Third Menu   
                                  </th>
                                  <td>
                                    
                                      <select name="ddl_sub_sub_menu_5" id="ddl_sub_sub_menu_5" >
                              <option value="">Select</option>
                             
                          </select>
                                  </td>
                              </tr>
                               <tr>
                                  <th>
                                     Permission
                                  </th>
                                  <td>
                                   <input type="text" name="txt_mn_per" id="txt_mn_per"/>    
                                  </td>
                              </tr>
                              
                              <tr>
                                  <th colspan="2">
                                     <input type="button" name="btn_sub_menu_4" id="btn_sub_menu_4" value="Submit" onclick="save_sub_menu_4();"/>  
                                  </th>
                              </tr>
                              </table>
                       <div id="dv_sub_mn_4"></div>
                  </fieldset>
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

<!-- <script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->

<script type="text/javascript">
     
     function save_mn_menu()
         {
          
         $('#dv_mn_mn').html('');
        var txt_mn_menu=$('#txt_mn_menu').val(); 
       
        if(txt_mn_menu.trim()=='')
            {
                alert("Please enter main menu");
                $('#txt_mn_menu').val('')
                $('#txt_mn_menu').focus();
            }
            else 
                {
                    $.ajax({
                        url: base_url+'/MasterManagement/Entrycontroller/save_main_mn',
                        cache:false,
                        async:true,
                        beforeSend:function(){
                        $('#dv_mn_mn').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                        },
                        data:{txt_mn_menu:txt_mn_menu},
                        type:'GET',
                        success:function(data,status){
                            $('#dv_mn_mn').html(data);
                            //  $('#dv_res_x').html('');
                            $('#btn_mn_menu').attr("disabled",false);
                            $('#txt_mn_menu').val('');
                        },
                        error:function(xhr){
                            alert("Error: "+xhr.status+" "+xhr.statusText);
                        }
                        
                    });
         }
         }
         function save_sub_menu()
         {
             var ddl_mn_menu=$('#ddl_mn_menu').val();
               var txt_sub_menu=$('#txt_sub_menu').val(); 
                var ddl_order=$('#ddl_order').val();
        if(ddl_mn_menu=='')
            {
                 alert("Please select main menu");
            }
        
        else if(txt_sub_menu.trim()=='')
            {
                alert("Please enter sub menu");
                $('#txt_sub_menu').val('')
                $('#txt_sub_menu').focus();
            }
            else 
                {
                      $.ajax({
                        url: base_url+'/MasterManagement/Entrycontroller/save_sub_mn',
                        cache:false,
                        async:true,
                    beforeSend:function(){
                        $('#dv_sub_mn').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                        },
                        data:{ddl_mn_menu:ddl_mn_menu,txt_sub_menu:txt_sub_menu,ddl_order:ddl_order},
                        type:'GET',
                        success:function(data,status){
                            $('#dv_sub_mn').html(data);
                            //            $('#dv_res_x').html('');
                            $('#btn_sub_menu').attr("disabled",false);
                            $('#txt_sub_menu').val('');
                            $('#ddl_mn_menu').val('');
                        },
                        error:function(xhr){
                            alert("Error: "+xhr.status+" "+xhr.statusText);
                        }
                        
                    });
                }
         }
         
         function get_sub_menu_3(str)
         {
            if(str != '')
            { 
                $.ajax({
                    url: base_url+'/MasterManagement/Entrycontroller/get_sub_menus',
                    cache:false,
                    async:true,
                
                    data:{str:str},
                    type:'GET',
                    success:function(data,status){
                        $('#ddl_sub_menu_3').html(data);

                    },
                    error:function(xhr){
                        alert("Error: "+xhr.status+" "+xhr.statusText);
                    }
                    
                });
            }
         }
         
         function save_sub_menu_3()
         {
             var ddl_mn_menu=$('#ddl_mn_menu_3').val();
             var ddl_sub_menu_3=$('#ddl_sub_menu_3').val();
               var txt_sub_menu=$('#txt_sub_sub_menu_3').val(); 
            var txt_url=$('#txt_url').val();
            if(ddl_mn_menu=='')
                {
                    alert("Please select main menu");
                }
            else if(ddl_sub_menu_3=='')
                {
                    alert("Please select sub menu");
                }
            else if(txt_sub_menu.trim()=='')
                {
                    alert("Please enter Third menu");
                    $('#txt_sub_sub_menu_3').val('')
                    $('#txt_sub_sub_menu_3').focus();
                }
            else if(txt_url.trim()=='')
                {
                    alert("Please enter URL");
                    $('#txt_url').val('')
                    $('#txt_url').focus();
                }
                else 
                {
                    $.ajax({
                    url: base_url+'/MasterManagement/Entrycontroller/save_sub_sub_mn',
                    cache:false,
                    async:true,
                beforeSend:function(){
                    $('#dv_sub_mn_3').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                    },
                    data:{ddl_mn_menu:ddl_mn_menu,txt_sub_menu:txt_sub_menu,ddl_sub_menu_3:ddl_sub_menu_3,txt_url:txt_url},
                    type:'GET',
                    success:function(data,status){
                        $('#dv_sub_mn_3').html(data);
                        // $('#dv_res_x').html('');
                        $('#btn_sub_menu_3').attr("disabled",false);
                        $('#ddl_mn_menu_3').val('');
                        $('#ddl_sub_menu_3').val('');
                        $('#txt_sub_sub_menu_3').val('');
                        $('#txt_url').val('');
                    },
                    error:function(xhr){
                        alert("Error: "+xhr.status+" "+xhr.statusText);
                    }
        
    });
                }
         }
         
         function save_sub_menu_4()
         {
             var ddl_mn_menu_per=$('#ddl_mn_menu_per').val();
             var txt_mn_per=$('#txt_mn_per').val();
             var ddl_sub_menu_5=$('#ddl_sub_menu_5').val();
             var ddl_sub_sub_menu_5=$('#ddl_sub_sub_menu_5').val();
             if(ddl_mn_menu_per.trim()=='')
            {
                 alert("Please select main menu");
            }
             else if(txt_mn_per.trim()=='')
            {
                alert("Please enter User Id");
                $('#txt_mn_per').val('')
                $('#txt_mn_per').focus();
            }
             else if(ddl_sub_menu_5.trim()=='')
            {
                 alert("Please select sub menu");
            }
              else if(ddl_sub_sub_menu_5.trim()=='')
            {
                 alert("Please select third menu");
            }
            else 
                {
              $.ajax({
        url: base_url+'/MasterManagement/Entrycontroller/save_mn_menu_per',
        cache:false,
        async:true,
     beforeSend:function(){
        $('#dv_sub_mn_4').html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
        },
        data:{ddl_mn_menu_per:ddl_mn_menu_per,txt_mn_per:txt_mn_per,ddl_sub_menu_5:ddl_sub_menu_5,ddl_sub_sub_menu_5:ddl_sub_sub_menu_5},
        type:'GET',
        success:function(data,status){
            $('#dv_sub_mn_4').html(data);
//            $('#dv_res_x').html('');
$('#btn_sub_menu_4').attr("disabled",false);
//$('#ddl_mn_menu_per').val('');
//$('#ddl_sub_menu_5').val('');
//$('#ddl_sub_sub_menu_5').val('');
//$('#txt_mn_per').val('');

        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
         }
         }
         
         
         function get_sub_menu_4(str)
         {
            if(str != '')
            {
                
                    $.ajax({
                    url: base_url+'/MasterManagement/Entrycontroller/get_sub_menus',
                    cache:false,
                    async:true,
                
                    data:{str:str},
                    type:'GET',
                    success:function(data,status){
                        $('#ddl_sub_menu_5').html(data);

                    },
                    error:function(xhr){
                        alert("Error: "+xhr.status+" "+xhr.statusText);
                    }
                    
                });
            }
         }
         
         function get_sub_sub_menu_4(str)
         {
            var ddl_mn_menu_per=$('#ddl_mn_menu_per').val();
        $.ajax({
        url: base_url+'/MasterManagement/Entrycontroller/get_sub_sub_menus',
        cache:false,
        async:true,
    
        data:{str:str,ddl_mn_menu_per:ddl_mn_menu_per},
        type:'GET',
        success:function(data,status){
            $('#ddl_sub_sub_menu_5').html(data);

        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
         }
         
</script>
