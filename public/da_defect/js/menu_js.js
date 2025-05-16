//$(document).ready(function(){
//alert("dsdsdsds");
function get_sp_cl(idd_s,id_val) {
//    $('.sp_cl').click(function(){
   var hd_mn_menu_nm=$('#hd_mn_menu_nm').val(idd_s);     
//      var idd=$(this).attr('id');
var sp_ddd=idd_s.split('mn_menu');
var idd=$('#hd_sp_cl_s'+sp_ddd[1]).val();
//alert(idd);
      $('#hd_mn_menu').val(idd);
    
        $.ajax({
        url:'/supreme_court/get_sub_menu.php',
        cache:false,
        async:true,
     beforeSend:function(){
          $('#dv_sub_menu').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
        },
        data:{idd:idd},
        type:'GET',
        success:function(data,status){
       
        $('#dv_sub_menu').html(data);
        
        get_sp_sp_cl('submenu_0');
        },
        error:function(xhr){
//          alert("dfdfdf");
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
            }
            
            

//$(document).on('click','.sp_sub_mn',function()
function get_sp_sp_cl(idd_s) 
{
//alert("sdsdsds");
//        var idd=$(this).attr('id');
  var hd_sub_menu_nm=$('#hd_sub_menu_nm').val(idd_s);   
  
var sp_ddd=idd_s.split('submenu_');
var idd=$('#hd_sp_submenu_s'+sp_ddd[1]).val();
//alert(idd);
           $('#hd_sub_menu').val(idd);
        $.ajax({
        url:'/supreme_court/get_sub_sub_menu.php',
        cache:false,
        async:true,
     beforeSend:function(){
        $('#dv_sub_sub_menu').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
        },
        data:{idd:idd},
        type:'GET',
        success:function(data,status){
//         alert(data);
        $('#dv_sub_sub_menu').html(data);
        get_main_con('sp_submenu_sub_s0');
        
        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
            };
//             });
            function get_main_con(idd_s)
{
//     var idd=$(this).attr('id');
//alert(u_r_l);
  var hd_sub_sub_menu_nm=$('#hd_sub_sub_menu_nm').val(idd_s);   
var sp_ddd=idd_s.split('sp_submenu_sub_s');
var idd=$('#hd_sp_submenu_sub_s'+sp_ddd[1]).val();
var u_r_l=$('#hd_url'+sp_ddd[1]).val();
//alert(u_r_l);
         $('#hd_sub_sub_menu').val(idd);
        $.ajax({
        url:'/supreme_court/get_content.php',
        cache:false,
        async:true,
     beforeSend:function(){
        $('#dv_content').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
        },
        data:{idd:idd},
        type:'GET',
        success:function(data,status){
//         alert(data);
        $('#dv_content').html(data);
        var hd_mn_menu=$('#hd_mn_menu').val();
var hd_sub_menu=$('#hd_sub_menu').val();
var hd_sub_sub_menu=$('#hd_sub_sub_menu').val();

 var x=$('#hd_mn_menu_nm').val();
var y=$('#hd_sub_menu_nm').val();
var z=$('#hd_sub_sub_menu_nm').val();


var enc_s=btoa("hd_mn_menu="+hd_mn_menu+"&hd_sub_menu="+hd_sub_menu+"&hd_sub_sub_menu="+hd_sub_sub_menu+"&x="+x+"&y="+y+"&z="+z);
//var enc_s=btoa("hd_mn_menu="+hd_mn_menu+"&hd_sub_menu="+hd_sub_menu+"&hd_sub_sub_menu="+hd_sub_sub_menu);
//rdrt_page(u_r_l,hd_mn_menu,hd_sub_menu,hd_sub_sub_menu);
//window.location.href='/supreme_court/'+u_r_l+"?hd_mn_menu="+hd_mn_menu+"&hd_sub_menu="+hd_sub_menu+"&hd_sub_sub_menu="+hd_sub_sub_menu;
 window.location.href='/supreme_court/'+u_r_l+"?a="+enc_s;       
        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
}

//function rdrt_page(u_r_l,hd_mn_menu,hd_sub_menu,hd_sub_sub_menu)
//{
//     $.ajax({
//        url:'/supreme_court/redirect.php',
//        cache:false,
//        async:true,
//     beforeSend:function(){
//        $('#dv_sub_sub_menu').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
//        },
//        data:{idd:idd},
//        type:'GET',
//        success:function(data,status){
////         alert(data);
//        $('#dv_sub_sub_menu').html(data);
//        get_main_con('sp_submenu_sub_s0');
//        
//        },
//        error:function(xhr){
//            alert("Error: "+xhr.status+" "+xhr.statusText);
//        }
//        
//    });
//}
//  function check_session()
//  {
//      
//  }

function sh_hd_slide()
{
    var btn_slide=$('#btn_slide').val();
    if(btn_slide=='-')
        {
            $('#dv_sub_menu').css('display','none');
              $('#dv_sub_sub_menu').css('display','none');
              $('#btn_slide').val('+');
             $('#dv_sub_menu').css('width','0px');
              $('#dv_sub_menu').css('height','0px');
              $('#dv_bt_slide').css('width','0px');
              $('#dv_content1').css('width','100%');
              $('#dv_content1').css("clear",'both');
        }
        else 
            {
                 if(btn_slide=='+')
        {
          
            $('#dv_sub_menu').css('display','block');
              $('#dv_sub_sub_menu').css('display','block');
             
              $('#btn_slide').val('-');
             $('#dv_sub_menu').css('width','15%');
              $('#dv_sub_menu').css('height','520px');
               $('#dv_bt_slide').css('width','15%');
              $('#dv_content1').css('width','85%');
//                $('#dv_content1').removeAttr("clear");
  $('#dv_content1').css("clear",'none');
        }
            }
}

/*
$(document).ready(function () {
    if(document.getElementById('communication')){
    document.getElementById('communication').style.width = 'auto';
    document.getElementById('communication').style.height = ' 500px';
    document.getElementById('communication').style.overflow = 'scroll';
    document.getElementById('communication').style.marginLeft = '180px';
    document.getElementById('communication').style.marginRight = '180px';
    document.getElementById('communication').style.marginBottom = '30px';
    document.getElementById('communication').style.marginTop = '100px';

    document.getElementById('dv_fixedFor_P_comm').style.marginTop = '3px';
    if ($('img').attr('src') == "../images/close_btn.png") {
        $('img').attr('src', "images/close_btn.png");
    }
            $.ajax({
        url:'/supreme_court/extra/get_msg.php',
        cache:false,
        async:true,
     beforeSend:function(){
        $('#communication').html('<table widht="100%" align="center"><tr><td>Loading...</td></tr></table>');
        },
        data:{},
        type:'GET',
        success:function(data,status){
if(data!='No Message'){
        document.getElementById('dv_sh_hd_comm').style.display = 'block';
    document.getElementById('dv_fixedFor_P_comm').style.display = 'block';
        $('#communication').html(data);
    }

        },
        error:function(xhr){
            alert("Error: "+xhr.status+" "+xhr.statusText);
        }
        
    });
    }
});
*/
function closeData_comm()
{
    document.getElementById('communication').scrollTop = 0;
    document.getElementById('dv_fixedFor_P_comm').style.display = "none";
    document.getElementById('dv_sh_hd_comm').style.display = "none";
} 
