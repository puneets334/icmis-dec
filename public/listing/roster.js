
$('.multipleselect').select2();

function get_ben_no(str) {
   if (document.getElementById('myerr'))
      document.getElementById('myerr').innerHTML = '';
   if (str == '9') {
      document.getElementById('judge_code').multiple = false;
      document.getElementById('judge_code').style.height = '20px';
   }
   else {
      document.getElementById('judge_code').multiple = 'multiple';
      document.getElementById('judge_code').style.height = '150px';
   }
   //    else if(str=='2')
   //         {
   //          document.getElementById('judge_code').multiple='multiple';
   //          document.getElementById('judge_code').style.height='150px';
   //         }
   //         else if(str=='3')
   //         {
   //          document.getElementById('judge_code').multiple='multiple';
   //          document.getElementById('judge_code').style.height='150px';
   //         }
   //        
   //         else if(str=='5' || str=='6')
   //         {
   //          document.getElementById('judge_code').multiple='multiple';
   //          document.getElementById('judge_code').style.height='150px';
   //         }
   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   //    document.getElementById('td_sel_jud').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('bench_name').innerHTML = xmlhttp.responseText;
         if (document.getElementById('rdn_motion').checked == true || document.getElementById('rdn_final').checked == true || document.getElementById('rdn_lok').checked == true)
            get_head();

         get_jud_det(str);
      }
   }
   xmlhttp.open("GET", base_url + "/Listing/roster/get_benc_no?str=" + str, true);
   xmlhttp.send(null);
}

function get_ben_no_s(str) {
   if (document.getElementById('myerr'))
      document.getElementById('myerr').innerHTML = '';
   if (str == '9') {
      document.getElementById('judge_codes').multiple = false;
      document.getElementById('judge_codes').style.height = '20px';
   }
   else {
      document.getElementById('judge_codes').multiple = 'multiple';
      document.getElementById('judge_codes').style.height = '150px';
   }

   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   //    document.getElementById('td_sel_jud').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('bench_names').innerHTML = xmlhttp.responseText;
         //                  if(document.getElementById('rdn_motion').checked==true || document.getElementById('rdn_final').checked==true || document.getElementById('rdn_lok').checked==true) 
         //                   get_head();
         //               
         //               get_jud_det(str);
      }
   }
   xmlhttp.open("GET", base_url + "/Listing/roster/get_benc_no?str=" + str, true);
   xmlhttp.send(null);
}

function get_jud_det(str) {
   $.ajax({
      url: base_url + "/Listing/roster/get_jud_org",
      type: "GET",
      async: true,
      cache: false,
      data: { str: str },
      success: function (data, status) {
         $('#judge_code').html(data + "<option value='999'>None</option>");
         $('.multipleselect').select2();
      },
      error: function (xhr) {
         alert("Error:" + xhr.status + ' ' + xhr.statusText);
      }
   });
}

function get_head() {
   cnt_data1 = 1;
   document.getElementById('destList').innerHTML = '';
   var m_f = '';
   var ddlBench = document.getElementById('ddlBench').value;
   if (ddlBench == '1')
      ddlBench = 'S';
   else if (ddlBench == '2')
      ddlBench = 'D';
   else if (ddlBench == '3')
      ddlBench = 'F';
   else if (ddlBench == '5')
      ddlBench = 'L';
   else if (ddlBench == '6')
      ddlBench = 'S';
   if (document.getElementById('rdn_motion').checked == true) {

      m_f = '1';
   }
   else if (document.getElementById('rdn_final').checked == true) {
      m_f = '2';
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      m_f = '3';
   }
   else if (document.getElementById('rdn_med').checked == true) {
      m_f = '4';
   }
   document.getElementById('tr_s_hdd').style.display = 'none';
   document.getElementById('dv_mot_record_tr').style.display = 'table-row';
   get_mot_details();
}


function addSrcToDestList(dl, sl, il) {
   destList = eval("window.document.forms[0]." + dl);
   srcList = eval("window.document.forms[0]." + sl);
   var len = destList.length;
   for (var i = 0; i < srcList.length; i++) {
      if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
         //Check if this value already exist in the destList or not
         //if not then add it otherwise do not add it.
         var found = false;
         for (var count = 0; count < len; count++) {
            if (destList.options[count] != null) {
               if (srcList.options[i].text == destList.options[count].text) {
                  found = true;
                  break;
               }
            }
         }
         if (found != true) {
            destList.options[len] = new Option(srcList.options[i].text, srcList.options[i].value);
            len++;
         }
      }
   }

   if (il == '0') {
      var len1 = window.document.forms[0].srcList1.length;
      for (var i = 0; i < srcList.length; i++) {
         if ((srcList.options[i] != null) && (srcList.options[i].selected)) {
            //Check if this value already exist in the destList or not
            //if not then add it otherwise do not add it.
            found = false;
            for (var count = 0; count < len1; count++) {
               if (window.document.forms[0].srcList1.options[count] != null) {
                  if (srcList.options[i].text == window.document.forms[0].srcList1.options[count].text) {
                     found = true;
                     break;
                  }
               }
            }
            if (found != true) {
               window.document.forms[0].srcList1.options[len1] = new Option(srcList.options[i].text, srcList.options[i].value);
               len1++;
            }
         }
      }
   }
}
// Deletes from the destination list.
function deleteFromDestList(dl, il) {
   var sv = "";
   var destList = eval("window.document.forms[0]." + dl);
   var len = destList.options.length;
   for (var i = (len - 1); i >= 0; i--) {
      if ((destList.options[i] != null) && (destList.options[i].selected == true)) {
         sv = destList.options[i].value;
         destList.options[i] = null;
      }
   }
   if (il == '0') {
      var len1 = window.document.forms[0].destList1.options.length;
      for (var i = (len1 - 1); i >= 0; i--) {
         if ((window.document.forms[0].destList1.options[i].value != 9) && (window.document.forms[0].destList1.options[i] != null) && (window.document.forms[0].destList1.options[i].value == sv)) {
            window.document.forms[0].destList1.options[i] = null;
         }
      }
      var len2 = window.document.forms[0].srcList1.options.length;
      for (var i = (len2 - 1); i >= 0; i--) {
         if ((window.document.forms[0].srcList1.options[i].value != 9) && (window.document.forms[0].srcList1.options[i] != null) && (window.document.forms[0].srcList1.options[i].value == sv)) {
            window.document.forms[0].srcList1.options[i] = null;
         }
      }
   }
}

function get_selected_rec() {
   // var ct_list=0;

   var srcList = document.getElementById('srcList');

   for (var ii = 0; ii < srcList.length; ii++) {
      if (srcList[ii].selected == true) {
         var ct_list = srcList[ii].value;
         //   alert(ct_list);
         //              var ct_list_nm=ct_list.options[ct_list.seletedIndex].innerHTML;
         //              alert(ct_list_nm);
         //                       if(ct_list=='')
         //                            {
         //                               ct_list=document.getElementById('srcList').value;
         //                            }
         //                           else
         //                                {
         //                                   ct_list=ct_list+'^'+document.getElementById('srcList').value;
         //                                }

         // alert(ct_list);
         var xmlhttp;
         if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
         }
         else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
         }

         //    document.getElementById('td_sel_jud').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';


         xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
               document.getElementById('destList').innerHTML += xmlhttp.responseText;


            }
         }
         xmlhttp.open("GET", "get_sec_rec.php?ct_list=" + ct_list, true);
         xmlhttp.send(null);
      }
   }
}

$("#sess_no").change(function () {
   if ($("#sess_no").val() == 1) {
      $(".ses_more2, .ses_more3").hide();
   }
   if ($("#sess_no").val() == 2) {
      $(".ses_more2").fadeIn();
      $(".ses_more3").hide();
   }
   if ($("#sess_no").val() == 3) {
      $(".ses_more2").fadeIn();
      $(".ses_more3").fadeIn();
   }
});

$(document).ready(function () {
   $("#btnsave").click(function () {
      var abcd = '';
      if (document.getElementById('ddlBench').value == '') {
         alert("Please Select Bench.");
         return false;
      }
      if (document.getElementById('bench_name').value == '') {

         alert("Please Select Bench No.");
         return false;
      }


      var hd_from_dt = $('#hd_from_dt').val();

      for (var kk = 1; kk <= hd_from_dt; kk++) {
         if ($('#row_del_add' + kk).length) {
            if ($('#from_dt' + kk).length)
               var from_dt = $('#from_dt' + kk).val();

            // if ($('#sess' + kk).length)
               var sess = $('#sess' + kk).val();

            var ddl_hrs       = $('#ddl_hrs' + kk).val();
            var txt_no_cas    = $('#txt_no_case' + kk).val();

            if (from_dt == '') {
               alert("Please enter Date");
               $('#from_dt' + kk).focus();
               return false;
            }
            if (sess == '') {
               alert("Please enter Session");
               $('#sess' + kk).focus();
               return false;
            }
            if (ddl_hrs == '') {
               alert("Please enter Timing");
               $('#ddl_hrs' + kk).focus();
               return false;
            }
            if (ddl_hrs == '') {
               alert("Please enter Timing");
               $('#ddl_hrs' + kk).focus();
               return false;
            }
            // if (txt_no_cas == '') {
            //    alert("Please enter No. Of Cases");
            //    $('#txt_no_case' + kk).focus();
            //    return false;
            // }
         }
      }

      var judge_code_s = document.getElementById('judge_code');
      var ct_jc = 0;
      for (var i = 0; i < judge_code_s.length; i++) {
         if (judge_code_s[i].selected == true) {
            ct_jc++;
         }
      }


      var ddlBench = document.getElementById('ddlBench').value;
      var judge_code = document.getElementById('judge_code').value;

      if (ddlBench == '9' && (judge_code == '0' || judge_code == '')) {

         alert("Please Select Judge Name.");
         return false;
      }


      if ((ddlBench == '1' || ddlBench == '7' || ddlBench == '8') && (ct_jc < 2 || ct_jc > 2)) {
         alert("Please Select two Judge Name");
         return false;
      }
      else if (ddlBench == '2' && (ct_jc < 3 || ct_jc > 3)) {
         alert("Please Select three Judge Name");
         return false;
      }
      else if (ddlBench == '3' && (ct_jc < 5 || ct_jc > 5)) {
         alert("Please Select five Judge Name");
         return false;
      }
      else if (ddlBench == '4' && (ct_jc < 7 || ct_jc > 7)) {
         alert("Please Select seven Judge Name");
         return false;
      }
      else if (ddlBench == '5' && (ct_jc < 9 || ct_jc > 9)) {
         alert("Please Select nine Judge Name");
         return false;
      }
      else if (ddlBench == '6' && (ct_jc < 11 || ct_jc > 11)) {
         alert("Please Select eleven Judge Name");
         return false;
      }


      var ck_m_f = '';
      if (document.getElementById('rdn_motion').checked == false && document.getElementById('rdn_final').checked == false && document.getElementById('rdn_lok').checked == false && document.getElementById('rdn_med').checked == false) {
         //             document.getElementById('myerr').innerHTML="Please Select Heading Motion or Final or Lok Adalat.";
         alert("Please Select Heading Motion or Final or Lok Adalat.");
         return false;
      }

      if (document.getElementById('txt_court_no').value == '') {
         alert("Please Select Court No.");
         return false;
      }

      //    valid_roster();
      //    if(valid_roster() == true) {

      //if(document.getElementById('rdn_motion').checked==true)
      //         {

      //           m_f='1';
      //         }
      //        else  if(document.getElementById('rdn_final').checked==true)
      //         {
      //            m_f='2';
      //         }

      

      //if(document.getElementById('rdn_final').checked==true || document.getElementById('rdn_lok').checked==true || document.getElementById('rdn_med').checked==true)
      //     {
      //var opts = document.getElementById('destList').options;    
      //    for(var i = 0, j = opts.length; i < j; i++) {
      //        if(i == 0)
      //            var abcd = opts[i].value;  
      //        else
      //            var abcd = abcd + "," + opts[i].value;  
      //     }
      //
      //    if(abcd == null || abcd == 0) {
      //        document.getElementById('myerr').innerHTML="Please Select Matter.";
      //        return false;
      //     }
      //     }
      if (document.getElementById('rdn_motion').checked == true || document.getElementById('rdn_final').checked == true || document.getElementById('rdn_lok').checked == true || document.getElementById('rdn_med').checked == true) {
         var ckk_st = 0;
         var hd_tb_new_mo = document.getElementById('hd_tb_new_mo').value;
         for (var z = 1; z < hd_tb_new_mo; z++) {
            if (document.getElementById('hd_chk_add' + z).checked == true) {
               ckk_st = 1;
            }
         }

         if (ckk_st == 0) {
            // document.getElementById('myerr').innerHTML="Please Select either Case Type or Heading or Category.";
            alert("Please Select either Case Type or Heading or Category.");
            return false;
         }
         else {
            for (var zi = 1; zi < hd_tb_new_mo; zi++) {
               if (document.getElementById('hd_chk_add' + zi).checked == true) {
                  var hd_sp_a = document.getElementById('hd_sp_a' + zi).value;
                  var hd_sp_b = document.getElementById('hd_sp_b' + zi).value;
                  var hd_sp_c = document.getElementById('hd_sp_c' + zi).value;
                  //  var hd_sp_d=document.getElementById('hd_sp_d'+zi).value;
                  //  var hd_sp_e=document.getElementById('hd_sp_e'+zi).value;
                  var hd_sp_f = document.getElementById('hd_sp_f' + zi).value;

                  var hd_befote_not = document.getElementById('hd_befote_not' + zi).value;
                  if (abcd == '')
                     abcd = hd_sp_a + '^' + hd_sp_b + '^' + hd_sp_c + '^' + hd_sp_f + '^' + hd_befote_not;
                  else
                     abcd = abcd + '@' + hd_sp_a + '^' + hd_sp_b + '^' + hd_sp_c + '^' + hd_sp_f + '^' + hd_befote_not;
               }
            }
         }
      }

      //    if($("#sess_no").val() == 1) {
      //        var sess_no = "&from_day="+$("#from_day").val()+"&to_day="+$("#to_day").val()+"&sess="+$("#sess").val();
      //     }
      //    if($("#sess_no").val() == 2) {
      //        var sess_no = "&from_day="+$("#from_day").val()+"&to_day="+$("#to_day").val()+"&sess="+$("#sess").val()+"&from_day2="+$("#from_day2").val()+"&to_day2="+$("#to_day2").val()+"&sess2="+$("#sess2").val();
      //     }
      //    if($("#sess_no").val() == 3) {
      //        var sess_no = "&from_day="+$("#from_day").val()+"&to_day="+$("#to_day").val()+"&sess="+$("#sess").val()+"&from_day2="+$("#from_day2").val()+"&to_day2="+$("#to_day2").val()+"&sess2="+$("#sess2").val()+"&from_day3="+$("#from_day3").val()+"&to_day3="+$("#to_day3").val()+"&sess3="+$("#sess3").val();
      //     }
      var rdn_ckk = '';
      if (document.getElementById('rdn_motion').checked == true) {
         rdn_ckk = document.getElementById('rdn_motion').value;
      }
      else if (document.getElementById('rdn_final').checked == true) {
         rdn_ckk = document.getElementById('rdn_final').value;
      }
      else if (document.getElementById('rdn_lok').checked == true) {
         rdn_ckk = document.getElementById('rdn_lok').value;
      }
      else if (document.getElementById('rdn_med').checked == true) {
         rdn_ckk = document.getElementById('rdn_med').value;
      }

      var bench_name_inn = escape(document.getElementById('bench_name').options[document.getElementById('bench_name').selectedIndex].innerHTML);
      //alert(bench_name_inn);
      //    if(valid_roster() == true) {
      var ck_ct_hall = '';
      if ($('#rdn_court').is(':checked')) {
         ck_ct_hall = $('#rdn_court').val();
      }
      else if ($('#rdn_hl').is(':checked')) {
         ck_ct_hall = $('#rdn_hl').val();
      }
      //    alert(ck_ct_hall);

      //var hd_from_dt=$('#hd_from_dt').val();
      var h_m_s_c = '';
      for (var k = 1; k <= hd_from_dt; k++) {

         if ($('#row_del_add' + k).length) {
            if ($('#from_dt' + k).length)
               var from_dt = $('#from_dt' + k).val();

            if ($('#sess' + k).length)
               var sess = $('#sess' + k).val();

            if ($('#ddl_hrs' + k).length)
               var ddl_hrs = $('#ddl_hrs' + k).val();

            if ($('#ddl_min' + k).length)
               var ddl_min = $('#ddl_min' + k).val();

            if ($('#ddl_am_pm' + k).length)
               var ddl_am_pm = $('#ddl_am_pm' + k).val();

            if ($('#txt_no_case' + k).length)
               var txt_no_case = $('#txt_no_case' + k).val();
            if (h_m_s_c == '')
               h_m_s_c = from_dt + '~' + sess + '~' + ddl_hrs + '~' + ddl_min + '~' + ddl_am_pm + '~' + txt_no_case;
            else
               h_m_s_c = h_m_s_c + '#' + from_dt + '~' + sess + '~' + ddl_hrs + '~' + ddl_min + '~' + ddl_am_pm + '~' + txt_no_case;
         }
      }
      //    alert(h_m_s_c);
      var ddlBench_x = document.getElementById('ddlBench').options[document.getElementById('ddlBench').selectedIndex].innerHTML;
      var bench_name_x = document.getElementById('bench_name').options[document.getElementById('bench_name').selectedIndex].innerHTML;
      var txt_court_no = $('#txt_court_no').val();
      var printInBeforeCourt = $('#printInBeforeCourt').val();
      var CSRF_TOKEN = 'CSRF_TOKEN';
      var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
      $.ajax
         ({
            type: "POST",
            url: base_url + "/Listing/roster/roster_save",
            //data: "&bench_name="+$("#bench_name").val()+"&judge_code="+$("#judge_code").val()+"&matter="+$("#destList").val()+"&from_dt="+$("#from_dt").val()+"&sess_no="+$("#sess_no").val()+sess_no,
            //                data: "&bench_name="+$("#bench_name").val()+"&judge_code="+$("#judge_code").val()+"&matter="+abcd+"&from_dt="+$("#from_dt").val()+"&sess_no="+$("#sess_no").val()+sess_no+"&ddlBench="+$("#ddlBench").val()+"&rdn_ckk="+rdn_ckk+"&bench_name_inn="+bench_name_inn+'&ck_ct_hall='+ck_ct_hall+'&ddl_hrs='+ddl_hrs+'&ddl_min='+ddl_min+'&ddl_am_pm='+ddl_am_pm+'&txt_no_case='+txt_no_case+'&h_m_s_c='+h_m_s_c,
            data: "&bench_name=" + $("#bench_name").val() + "&judge_code=" + $("#judge_code").val() + "&matter=" + abcd + "&from_dt=" + $("#from_dt").val() + "&ddlBench=" + $("#ddlBench").val() + "&rdn_ckk=" + rdn_ckk + "&bench_name_inn=" + bench_name_inn + '&ck_ct_hall=' + ck_ct_hall + '&ddl_hrs=' + ddl_hrs + '&ddl_min=' + ddl_min + '&ddl_am_pm=' + ddl_am_pm + '&txt_no_case=' + txt_no_case + '&h_m_s_c=' + h_m_s_c + '&ddlBench_x=' + ddlBench_x + '&bench_name_x=' + bench_name_x + '&txt_court_no=' + txt_court_no + '&printInBeforeCourt=' + printInBeforeCourt + '&CSRF_TOKEN=' + CSRF_TOKEN_VALUE,
            cache: false,
            success: function (data) {
               updateCSRFTokenSync();
               data = data.trim();
               if (data == "Problem to Add Record")
                  alert(data);
               else {
                  //                        alert(data);
                  $('#myerr').html(data);
                  //document.location.reload();
                  // window.location.href="roaster.php";
                  document.getElementById('ddlBench').value = '';
                  document.getElementById('bench_name').value = '';

                  for (var k = 1; k <= hd_from_dt; k++) {
                     if (document.getElementById('from_dt' + k))
                        document.getElementById('from_dt' + k).value = '';
                     if (document.getElementById('sess' + k))
                        document.getElementById('sess' + k).value = '';

                     if ($('#ddl_hrs' + k).length)
                        $('#ddl_hrs' + k).val('');

                     if ($('#ddl_min' + k).length)
                        $('#ddl_min' + k).val('');

                     if ($('#ddl_am_pm' + k).length)
                        $('#ddl_am_pm' + k).val('');

                     if ($('#txt_no_case' + k).length)
                        $('#txt_no_case' + k).val('');
                     if (k > 1) {
                        $('#row_del_add' + k).remove();

                     }
                     if (k == hd_from_dt) {
                        cnt_rows = 3;
                        $('#hd_from_dt').val('1');
                     }
                  }


                  document.getElementById('judge_code').value = '';
                  document.getElementById('judge_code').style.height = '20px';
                  document.getElementById('judge_code').multiple = false;
                  document.getElementById('rdn_motion').checked = false;
                  document.getElementById('rdn_final').checked = false;
                  document.getElementById('srcList').innerHTML = "<option value=''></option>";
                  document.getElementById('srcList').value = '';

                  document.getElementById('destList').innerHTML = '';

                  if (document.getElementById('tr_s_hdd')) {
                     document.getElementById('tr_s_hdd').style.display = 'none';
                  }
                  if (document.getElementById('dv_gdc')) {
                     document.getElementById('dv_gdc').style.display = 'none';
                  }

                  updateCSRFTokenSync();
                  // if (updateCSRFToken()) 
                  // {
                  //    // alert('rrrr');
                  //    var CSRF_TOKEN = 'CSRF_TOKEN';
                  //    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                  //    $.ajax
                  //       ({
                  //          type: "POST",
                  //          url: base_url + "/Listing/roster/get_roster",
                  //          cache: false,
                  //          data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                  //          success: function (data1) {
                  //             updateCSRFTokenSync();
                  //             $(".get_roster").html(data1);

                  //             var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();
                  //             show_hide_dt(hd_hd_show_hide_dt);
                  //          }
                  //       }).fail(function () {
                  //          updateCSRFTokenSync();
                  //          alert("ERROR, Please Contact Server Room");
                  //       });
                  // }

                  $(".get_roster").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
                  // setTimeout(function() { 
                     get_rosterDetails(rdn_ckk); 
                  // }, 5000);
               }
            }
         }).fail(function () {
            updateCSRFTokenSync();
            alert("ERROR, Please Contact Server Room");
         });

      //      }
   });

});

async function get_rosterDetails(v = 0) {
   var m_f = 0;
   if(v != 0){
      m_f = v;
   }
   await updateCSRFTokenSync();
   var CSRF_TOKEN = 'CSRF_TOKEN';
   var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   
   $.ajax
      ({
         type: "POST",
         beforeSend: function () {
            $(".get_roster").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
         },
         url: base_url + "/Listing/roster/get_roster",
         cache: false,
         data: { m_f: m_f, CSRF_TOKEN: CSRF_TOKEN_VALUE },
         success: function (data) {
            updateCSRFTokenSync();
            $(".get_roster").html(data);
         }
      }).fail(function () {
         updateCSRFTokenSync();
         alert("ERROR, Please Contact Server Room");
      });
}

get_rosterDetails();

function valid_roster() {
   //     if(isEmpty(document.getElementById('ddlBench'))) { document.getElementById('myerr').innerHTML="Please Select Bench.";return false; }
   if (isEmpty(document.getElementById('bench_name'))) {
      //    document.getElementById('myerr').innerHTML="Please Select Bench No.";
      alert("Please Select Bench No.");
      return false;
   }
   // if(isEmpty(document.getElementById('courtno'))) { document.getElementById('myerr').innerHTML="Please Select Court No.";return false; }
   // if(isEmpty(document.getElementById('judge_code'))) { document.getElementById('myerr').innerHTML="Please Select Judge Name.";return false; }
   // if(isEmpty(document.getElementById('srcList'))) { document.getElementById('myerr').innerHTML="Please Select Matters.";return false; }
   // if(isEmpty(document.getElementById('nature'))) { document.getElementById('myerr').innerHTML="Please Select Nature.";return false; }
   var judge_code_s = document.getElementById('judge_code');
   var ct_jc = 0;
   for (var i = 0; i < judge_code_s.length; i++) {
      if (judge_code_s[i].selected == true) {
         ct_jc++;
      }
   }
   // alert(ct_jc);

   var ddlBench = document.getElementById('ddlBench').value;
   var judge_code = document.getElementById('judge_code').value;

   if (ddlBench == '9' && (judge_code == '0' || judge_code == '')) {
      //        document.getElementById('myerr').innerHTML="Please Select Judge Name.";
      alert("Please Select Judge Name.");
      return false;
   }

   //if(document.getElementById('ddlBench').value=='9' && (document.getElementById('judge_code').value=='0' || document.getElementById('judge_code').value==''))
   //     {
   //        document.getElementById('myerr').innerHTML="Please Select Judge Name.";return false;
   //     }
   //    if((document.getElementById('ddlBench').value=='2' && (document.getElementById('judge_code').value=='0' || document.getElementById('judge_code').value=='')) ||
   //    (document.getElementById('ddlBench').value=='5' && (document.getElementById('judge_code').value=='0' || document.getElementById('judge_code').value=='')) ||
   //(document.getElementById('ddlBench').value=='6' && (document.getElementById('judge_code').value=='0' || document.getElementById('judge_code').value=='')))
   //     {
   //       //if(document.getElementById('judge_code').value=='0')
   //        document.getElementById('myerr').innerHTML="Please Select Judge Name.";return false;
   //    
   //     }
   if ((ddlBench == '1' || ddlBench == '7' || ddlBench == '8') && ct_jc < 2) {
      alert("Please Select two Judge Name");
      return false;
   }
   else if (ddlBench == '2' && ct_jc < 3) {
      alert("Please Select three Judge Name");
      return false;
   }
   else if (ddlBench == '3' && ct_jc < 5) {
      alert("Please Select five Judge Name");
      return false;
   }
   else if (ddlBench == '4' && ct_jc < 7) {
      alert("Please Select seven Judge Name");
      return false;
   }
   else if (ddlBench == '5' && ct_jc < 9) {
      alert("Please Select nine Judge Name");
      return false;
   }
   else if (ddlBench == '6' && ct_jc < 11) {
      alert("Please Select eleven Judge Name");
      return false;
   }

   //    if((document.getElementById('ddlBench').value=='2' && ct_jc<2) || 
   //        (document.getElementById('ddlBench').value=='5' && ct_jc<2) )
   //     {
   //
   //                    document.getElementById('myerr').innerHTML="Please Select two Judge Name.";return false;
   //           
   //     }
   var ck_m_f = '';
   if (document.getElementById('rdn_motion').checked == false && document.getElementById('rdn_final').checked == false && document.getElementById('rdn_lok').checked == false && document.getElementById('rdn_med').checked == false) {
      document.getElementById('myerr').innerHTML = "Please Select Heading Motion or Final or Lok Adalat."; return false;
   }


   // if(document.getElementById('destList').innerHTML.trim()=='')
   //                 {
   //                   document.getElementById('myerr').innerHTML="Add atleast one catagory";
   //                   return false;
   //                 }

   //    if(isEmpty(document.getElementById('from_dt'))) { document.getElementById('myerr').innerHTML="Please Enter Effected From Date.";return false; }
   //    if(document.getElementById('from_day').value == '0') { document.getElementById('myerr').innerHTML="Please Select From Day.";return false; }
   //    if(isEmpty(document.getElementById('sess'))) { document.getElementById('myerr').innerHTML="Please Select Session.";return false; }
   //if(isEmpty(document.getElementById('courtno'))) { document.getElementById('myerr').innerHTML="Please Select Court No.";return false; }
   //    if(document.getElementById('sess').value == '2') {
   //        if(document.getElementById('from_day2').value == '0') { document.getElementById('myerr').innerHTML="Please Select From Day.";return false; }
   //        if(isEmpty(document.getElementById('sess2'))) { document.getElementById('myerr').innerHTML="Please Select Session.";return false; }
   //     }
   //    if(document.getElementById('sess').value == '3') {
   //        if(document.getElementById('from_day2').value == '0') { document.getElementById('myerr').innerHTML="Please Select From Day.";return false; }
   //        if(document.getElementById('from_day3').value == '0') { document.getElementById('myerr').innerHTML="Please Select From Day.";return false; }
   //        if(isEmpty(document.getElementById('sess2'))) { document.getElementById('myerr').innerHTML="Please Select Session.";return false; }
   //        if(isEmpty(document.getElementById('sess3'))) { document.getElementById('myerr').innerHTML="Please Select Session.";return false; }
   //     }
   //    if($('#ddl_hrs').val()!='' && ($('#ddl_min').val()=='' || $('#ddl_am_pm').val()==''))
   //         {
   //            if($('#ddl_min').val()=='')
   //                 {
   //                   document.getElementById('myerr').innerHTML="Please Select Minute";
   //                    $('#ddl_min').focus();
   //                 
   //                 }
   //              if($('#ddl_am_pm').val()=='')
   //                 {
   //                    document.getElementById('myerr').innerHTML="Please Select AM or PM";
   //                    $('#ddl_am_pm').focus();
   //                   
   //                 }
   //                   return false;
   //         }
   return true;
}

function isEmpty(xx) {
   var yy = xx.value.replace(/^\s*/, "");
   if (yy == "") { xx.focus(); return true; }
   return false;
}
function isNumeric(xx) {
   strString = xx.value;
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;
   if (strString.length == 0) return false;
   for (i = 0; i < strString.length && blnResult == true; i++) {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1) {
         blnResult = false;
      }
   }
   return blnResult;
}

function printdiv(printpage) {
   var headstr = "<html><head><title></title></head><body>";
   var footstr = "</body>";
   var newstr = document.all.item(printpage).innerHTML;
   var oldstr = document.body.innerHTML;
   document.body.innerHTML = headstr + newstr + footstr;
   window.print();
   document.body.innerHTML = oldstr;
   return false;
}

function open_records(str) {
}


function shuffle() {
   var tot_list = '';
   var tot_list_nm = '';
   if (document.getElementById('destList').innerHTML.trim() == '') {
      alert("Add atleast one catagory");
   }
   else {
      var destList = document.getElementById('destList');
      //  var destList_nm=document.getElementById('destList').options[document.getElementById('destList').selectedIndex].innerHTML;
      for (var h = 0; h < destList.length; h++) {
         if (tot_list == '') {
            tot_list = destList[h].value;
            var des_nm = destList[h].innerHTML;
            des_nm = des_nm.replace(/&nbsp;/g, "");
            tot_list_nm = des_nm;
         }
         else {
            tot_list = tot_list + '^' + destList[h].value;
            var des_nm = destList[h].innerHTML;
            des_nm = des_nm.replace(/&nbsp;/g, "");
            tot_list_nm = tot_list_nm + '^' + des_nm;
         }
      }

      tot_list_nm = escape(tot_list_nm);

      document.getElementById('ggg').style.width = 'auto';
      document.getElementById('ggg').style.height = ' 500px';
      document.getElementById('ggg').style.overflow = 'scroll';
      //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
      document.getElementById('ggg').style.marginLeft = '18px';
      document.getElementById('ggg').style.marginRight = '18px';
      document.getElementById('ggg').style.marginBottom = '25px';
      document.getElementById('ggg').style.marginTop = '40px';
      document.getElementById('dv_sh_hd').style.display = 'block';
      document.getElementById('dv_fixedFor_P').style.display = 'block';
      document.getElementById('dv_fixedFor_P').style.marginTop = '3px';

      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';
      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('ggg').innerHTML = xmlhttp.responseText;
         }
      }
      // xmlhttp.open("GET",hd_folder+"/shuffle.php?tot_list="+tot_list,true);
      // xmlhttp.send(null);

      xmlhttp.open("POST", "shuffle.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.send("tot_list=" + tot_list + "&tot_list_nm=" + tot_list_nm);
   }
}

function closeData() {
   document.getElementById('ggg').scrollTop = 0;
   document.getElementById('dv_fixedFor_P').style.display = "none";
   document.getElementById('dv_sh_hd').style.display = "none";
   //       document.getElementById('sp_close').style.display='none';
}

function reshuffle() {
   var ck_sam_pr = 0;
   var hd_total_cat = document.getElementById('hd_total_cat').value;
   var txtpriroty = new Array();
   for (var i = 1; i < hd_total_cat; i++) {

      txtpriroty[i - 1] = document.getElementById('txtpriroty' + i).value;
   }
   var zz = txtpriroty.length;

   for (var z = 0; z < zz; z++) {
      for (var d = 0; d < zz; d++) {
         if (z != d) {
            if (txtpriroty[z] == txtpriroty[d]) {
               var qq = z + 1;
               var bb = d + 1
               alert("Priority can't be " + txtpriroty[z] + " for Sno. " + qq + " and " + bb);
               ck_sam_pr = 1;
               break;
            }
         }
      }
      if (ck_sam_pr == 1) {
         break;
      }
   }
   if (ck_sam_pr == 0) {

      var txtpriroty_f = new Array();
      for (var aa = 1; aa < hd_total_cat; aa++) {
         txtpriroty_f[aa - 1] = document.getElementById('txtpriroty' + aa).value + '^' +
            document.getElementById('hd_dv_k' + aa).value + '^' + document.getElementById('sp_nm' + aa).innerHTML.trim();
      }
      //  var zz2=txtpriroty_f.length; 
      var zz3 = txtpriroty_f.sort();
      txtpriroty_f = escape(txtpriroty_f);

      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      // document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('destList').innerHTML = xmlhttp.responseText;
            closeData();
         }
      }
      //                xmlhttp.open("GET",hd_folder+"/re_shuffle.php?tot_list="+tot_list,true);
      //                xmlhttp.send(null);
      xmlhttp.open("POST", "re_shuffle.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.send("txtpriroty_f=" + txtpriroty_f);
   }
}

function add_records(str) {
   var str1 = str.split('_');
   var btnroster = document.getElementById('btnroster_' + str1[1]).value;
   document.getElementById('ggg').style.width = 'auto';
   document.getElementById('ggg').style.height = ' 500px';
   document.getElementById('ggg').style.overflow = 'scroll';
   //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
   document.getElementById('ggg').style.marginLeft = '18px';
   document.getElementById('ggg').style.marginRight = '18px';
   document.getElementById('ggg').style.marginBottom = '25px';
   document.getElementById('ggg').style.marginTop = '40px';
   document.getElementById('dv_sh_hd').style.display = 'block';
   document.getElementById('dv_fixedFor_P').style.display = 'block';
   document.getElementById('dv_fixedFor_P').style.marginTop = '3px';

   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   document.getElementById('ggg').innerHTML = '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>';

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('ggg').innerHTML = xmlhttp.responseText;
      }
   }
   xmlhttp.open("GET", "extra_cat?btnroster=" + btnroster, true);
   xmlhttp.send(null);

}

function Adds_data() {
   //     if(document.getElementById('hd_bvs'))
   //     alert(document.getElementById('hd_bvs').getAttribute('value'));
   var hd_tootal = '';
   var hd_tootal_no = 0;
   //     if( document.getElementById('hd_bvs'))
   //          {
   ////         document.getElementById('hd_bvs').setAttribute('value', parseInt(document.getElementById('hd_bvs').value));
   ////     document.getElementById('hd_bvs').getAttribute('value');
   //       hd_tootal=document.getElementById('hd_bvs').value;
   //       alert("hd_bvs"+hd_tootal);
   //          }
   //     else
   //          {

   hd_tootal = parseInt(document.getElementById('hd_tootal').value);
   //   }
   var srcLists = document.getElementById('srcLists');
   var hd_res_priority = document.getElementById('hd_res_priority').value;
   var ex_res_priority = hd_res_priority.split(',');
   var tot_srcLists = '';
   var ck_dup = 0;
   var ck_dup_ss = 0;
   for (var i = 0; i < srcLists.length; i++) {
      if (srcLists[i].selected == true) {
         for (var z = 0; z < ex_res_priority.length; z++) {
            if (srcLists[i].value == ex_res_priority[z]) {

               alert("Category Already Selected");
               srcLists[i].selected = false;
               ck_dup = 1;
               // continue;
               // break;
            }
            else {
               ck_dup = 0;
            }

         }

         if (ck_dup == 0 && srcLists[i].selected == true) {

            ck_dup_ss = 1;
            document.getElementById('hd_res_priority').setAttribute('value', document.getElementById('hd_res_priority').getAttribute('value') + ',' + srcLists[i].value);
            //      document.getElementById('hd_res_priority').setAttribute('value', document.getElementById('hd_res_priority').value+','+srcLists[i].value);
            hd_tootal_no = hd_tootal_no + 1;
            //                       alert(hd_tootal_no);
            //                       alert(hd_tootal);
            // document.getElementById('hd_tootal').value=hd_tootal+hd_tootal_no;
            document.getElementById('hd_tootal').setAttribute('value', hd_tootal + hd_tootal_no);
            //                          alert("hd_tootal"+document.getElementById('hd_tootal').value);
            if (tot_srcLists == '') {
               srcLists[i].innerHTML = srcLists[i].innerHTML.replace(/&nbsp;/g, "");
               tot_srcLists = srcLists[i].value + '@' + srcLists[i].innerHTML;
            }
            else {
               srcLists[i].innerHTML = srcLists[i].innerHTML.replace(/&nbsp;/g, "");
               tot_srcLists = tot_srcLists + '^' + srcLists[i].value + '@' + srcLists[i].innerHTML;
            }
         }
      }
   }

   if (ck_dup_ss == 1) {
      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('tb_cat_id').innerHTML += xmlhttp.responseText;
            //                        alert(document.getElementById('dv_extra_ress').innerHTML);
            //                         hd_tootal_s=document.getElementById('hd_bvs').value;
            //                        //document.getElementById('tb_cat_id').innerHTML+=document.getElementById('dv_extra_ress').innerHTML;
            //  document.getElementById('hd_tootal').setAttribute('value', parseInt(document.getElementById('hd_bvs').value));

         }
      }
      // xmlhttp.open("GET",hd_folder+"/get_ext_add_records.php?hd_tootal="+hd_tootal+"&tot_srcLists="+tot_srcLists+"&hd_tootal_no="+hd_tootal_no,true);
      //              xmlhttp.send(null);
      xmlhttp.open("POST", "get_ext_add_records.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.send("hd_tootal=" + hd_tootal + "&tot_srcLists=" + tot_srcLists + "&hd_tootal_no=" + hd_tootal_no);

   }
}


function save_ex_party(st_y) {
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }

   var ck_sam_pr = 0;
   var hd_total_cat = document.getElementById('hd_tootal').value;
   var txtpriroty = new Array();
   for (var i = 1; i <= hd_total_cat; i++) {

      txtpriroty[i - 1] = document.getElementById('txtprirotys' + i).value;
   }
   var zz = txtpriroty.length;

   for (var z = 0; z < zz; z++) {
      for (var d = 0; d < zz; d++) {
         if (z != d) {
            if (txtpriroty[z] == txtpriroty[d]) {
               var qq = z + 1;
               var bb = d + 1
               alert("Priority can't be " + txtpriroty[z] + " for Sno. " + qq + " and " + bb);
               ck_sam_pr = 1;
               break;
            }
         }
      }
      if (ck_sam_pr == 1) {
         break;
      }
   }

   if (ck_sam_pr == 0) {
      var tot_cat_ent = '';
      for (var i = 1; i <= hd_total_cat; i++) {
         if (tot_cat_ent == '') {
            // document.getElementById('txtprirotys'+i).setAttribute('value', document.getElementById('txtprirotys'+i).value);
            // alert(document.getElementById('txtprirotys'+i).getAttribute('value'));
            tot_cat_ent = document.getElementById('hd_jud1').value + "^" + document.getElementById('hd_jud2').value
               + '^' + document.getElementById('hd_jud3').value + '^' + document.getElementById('hd_jud4').value
               + '^' + document.getElementById('hd_jud5').value + '^' + document.getElementById('hd_from_date').value
               + '^' + document.getElementById('hd_to_date').value + '^' + document.getElementById('hd_bench_id').value
               + '^' + document.getElementById('hd_bench_no').value + '^' + document.getElementById('hd_ros_id').value
               + '^' + document.getElementById('hd_dv_ks' + i).value + '^' + document.getElementById('txtprirotys' + i).value;
         }
         else {
            //  document.getElementById('txtprirotys'+i).setAttribute('value', document.getElementById('txtprirotys'+i).value);
            //  alert(document.getElementById('txtprirotys'+i).getAttribute('value'));
            tot_cat_ent = tot_cat_ent + '@' + document.getElementById('hd_jud1').value + "^" + document.getElementById('hd_jud2').value
               + '^' + document.getElementById('hd_jud3').value + '^' + document.getElementById('hd_jud4').value
               + '^' + document.getElementById('hd_jud5').value + '^' + document.getElementById('hd_from_date').value
               + '^' + document.getElementById('hd_to_date').value + '^' + document.getElementById('hd_bench_id').value
               + '^' + document.getElementById('hd_bench_no').value + '^' + document.getElementById('hd_ros_id').value
               + '^' + document.getElementById('hd_dv_ks' + i).value + '^' + document.getElementById('txtprirotys' + i).value;
            //  alert(tot_cat_ent);
         }
      }

      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('dv_rss').innerHTML = xmlhttp.responseText;
            if (st_y != 1) {
               //  $(".get_roster").html("<table widht='100%' align='center'><tr><td><img src='preloader.gif'/></td></tr></table>");  
               var CSRF_TOKEN = 'CSRF_TOKEN';
               var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
               $.ajax
                  ({
                     type: "POST",
                     url: base_url + "/Listing/roster/get_roster",
                     cache: false,
                     data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                     success: function (data1) {
                        updateCSRFTokenSync();
                        $(".get_roster").html(data1);
                        closeData();
                        var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();

                        show_hide_dt(hd_hd_show_hide_dt);
                     }
                  }).fail(function () {
                     updateCSRFTokenSync();
                     alert("ERROR, Please Contact Server Room");
                  });
            }
         }
      }
      // 
      xmlhttp.open("POST", "update_add_records.php", false);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.send("tot_cat_ent=" + tot_cat_ent);

   }
}

function delete_records(str) {
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }

   var str1 = str.split('btnDelete');
   var hd_rosterid = document.getElementById('hd_rosterid_' + str1[1]).value;
   var hdcatid = document.getElementById('hdcatid_' + str1[1]).value;
   var spcatname = document.getElementById('spcatname_' + str1[1]).innerHTML.trim();
   var cn = confirm("click Ok to delete category " + spcatname);
   if (cn == true) {
      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }


      document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('ggg').innerHTML = xmlhttp.responseText;
            //   $(".get_roster").html("<table widht='100%' align='center'><tr><td><img src='preloader.gif'/></td></tr></table>");               
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax
               ({
                  type: "POST",
                  url: base_url + "/Listing/roster/get_roster",
                  cache: false,
                  data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                  success: function (data1) {
                     updateCSRFTokenSync();
                     $(".get_roster").html(data1);
                     closeData();
                     var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();

                     show_hide_dt(hd_hd_show_hide_dt);
                  }
               }).fail(function () {
                  updateCSRFTokenSync();
                  alert("ERROR, Please Contact Server Room");
               });

         }
      }
      xmlhttp.open("GET", "delete_record.php?hd_rosterid=" + hd_rosterid + "&hdcatid=" + hdcatid, true);
      xmlhttp.send(null);
   }
}


function delete_recordss(str) {
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }
   var str1 = str.split('btnDelete');
   var hd_rosterid = document.getElementById('hd_rosterid_' + str1[1]).value;
   var hdcatid = document.getElementById('hdcatid_' + str1[1]).value;

   var hd_stage_nature = document.getElementById('hd_stage_nature' + str1[1]).value;
   var hd_case_type = document.getElementById('hd_case_type' + str1[1]).value;
   var hd_cat1 = document.getElementById('hd_cat1' + str1[1]).value;
   var hd_cat2 = document.getElementById('hd_cat2' + str1[1]).value;
   var hd_cat3 = document.getElementById('hd_cat3' + str1[1]).value;
   var spcatname = document.getElementById('spcatname_' + str1[1]).innerHTML.trim();
   var cn = confirm("click Ok to delete category " + spcatname);
   if (cn == true) {
      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }


      document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('ggg').innerHTML = xmlhttp.responseText;

            callRoster();
            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // $.ajax
            //    ({
            //       type: "POST",
            //       url: base_url + "/Listing/roster/get_roster",
            //       cache: false,
            //       data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
            //       success: function (data1) {
            //          updateCSRFTokenSync();
            //          $(".get_roster").html(data1);
            //          closeData();
            //          var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();

            //          show_hide_dt(hd_hd_show_hide_dt);
            //       }
            //    }).fail(function () {
            //       updateCSRFTokenSync();
            //       alert("ERROR, Please Contact Server Room");
            //    });

         }
      }
      xmlhttp.open("GET", "delete_record_mot?hd_rosterid=" + hd_rosterid
         + "&hdcatid=" + hdcatid + "&hd_case_type=" + hd_case_type
         + "&hd_cat1=" + hd_cat1 + "&hd_cat2=" + hd_cat2 + "&hd_cat3=" + hd_cat3 + "&hd_stage_nature=" + hd_stage_nature, true);
      xmlhttp.send(null);
   }
}

function printdiv(str) {
   var prtContent = document.getElementById(str);
   var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
   WinPrint.document.write("<style> .bk_out {  display:none; } </style>" + prtContent.innerHTML);
   // WinPrint.document.getElementsByC('bk_out').style.display='none';
   //WinPrint.document.getElementsByClassName('bk_out').style.display='none';
   //$(".bk_out").hide();
   WinPrint.document.close();
   WinPrint.focus();
   WinPrint.print();
}

function get_cor_cat(str, idd) {
   var m_f = '';
   var ddlBench = document.getElementById('ddlBench').value;
   if (ddlBench == '1')
      ddlBench = 'S';
   else if (ddlBench == '2')
      ddlBench = 'D';
   else if (ddlBench == '3')
      ddlBench = 'F';
   if (document.getElementById('rdn_motion').checked == true) {
      m_f = '1';
   }
   else if (document.getElementById('rdn_final').checked == true) {
      m_f = '2';
   }

   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   //document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('srcList').innerHTML = xmlhttp.responseText;
         for (var zz = 0; zz <= 7; zz++) {
            if (str == 'btnAll' + zz) {
               document.getElementById(str).style.backgroundColor = 'black';
               document.getElementById(str).style.color = 'white';
            }
            else {
               document.getElementById('btnAll' + zz).style.backgroundColor = 'white';
               document.getElementById('btnAll' + zz).style.color = 'black';
            }
         }
      }
   }
   xmlhttp.open("GET", "get_ind_cat.php?idd=" + idd + "&m_f=" + m_f + "&ddlBench=" + ddlBench, true);
   xmlhttp.send(null);
}


function get_cor_cats(str, idd) {
   var btn_final_roster = document.getElementById('btn_final_roster').value;
   // alert(idd);
   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   //document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('srcLists').innerHTML = xmlhttp.responseText;
         for (var zz = 0; zz <= 7; zz++) {
            if (str == 'btnAlls' + zz) {
               document.getElementById(str).style.backgroundColor = 'black';
               document.getElementById(str).style.color = 'white';
            }
            else {
               document.getElementById('btnAlls' + zz).style.backgroundColor = 'white';
               document.getElementById('btnAlls' + zz).style.color = 'black';
            }
         }
      }
   }
   xmlhttp.open("GET", "get_ind_cat_update.php?idd=" + idd + "&btn_final_roster=" + btn_final_roster, true);
   xmlhttp.send(null);
}

function close_records(str) {
   var cn = confirm("click Ok to close roster");
   if (cn == true) {
      var rdn_ckk = '';
      if (document.getElementById('rdn_motion').checked == true) {
         rdn_ckk = document.getElementById('rdn_motion').value;
      }
      else if (document.getElementById('rdn_final').checked == true) {
         rdn_ckk = document.getElementById('rdn_final').value;
      }
      else if (document.getElementById('rdn_lok').checked == true) {
         rdn_ckk = document.getElementById('rdn_lok').value;
      }
      else if (document.getElementById('rdn_med').checked == true) {
         rdn_ckk = document.getElementById('rdn_med').value;
      }
      var str1 = str.split('_');
      var btnroster = document.getElementById('btnroster_' + str1[1]).value;
      var ck_to_dt = ckeck_to_dt(btnroster);
      if (ck_to_dt != 0) {
         var xmlhttp;
         if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
         }
         else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
         }


         //  document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

         xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

               document.getElementById('dv_cl_roster').innerHTML = xmlhttp.responseText;
               var hd_sno_s = document.getElementById('hd_sno_s').value;
               if (hd_sno_s == 1 || hd_sno_s == 2) {
                  alert('Error: Roster not closed');
               }
               else if (hd_sno_s == 0) {
                  alert("Another roster already made on or after closing date");
               }
               else if (hd_sno_s == 3) {
                  alert("Roster closed successfully");
                                    
                  run_rosterDetails(rdn_ckk);
                  closeData();
                  var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();
                  // setTimeout(function() { 
                     show_hide_dt(hd_hd_show_hide_dt); 
                  // }, 6000);
               }
            }
         }
         xmlhttp.open("GET", "close_roaster?btnroster=" + btnroster + "&ck_to_dt=" + ck_to_dt, true);
         xmlhttp.send(null);
      }
   }
}

async function run_rosterDetails(rdn_ckk) {
   await updateCSRFTokenSync();
   var CSRF_TOKEN = 'CSRF_TOKEN';
   var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   $.ajax
      ({
         type: "POST",
         url: base_url + "/Listing/roster/get_roster",
         cache: false,
         data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
         success: function (data1) {
            $('#page_loader').hide();
            updateCSRFTokenSync();
            $(".get_roster").html(data1);
         }
      }).fail(function () {
         $('#page_loader').hide();
         updateCSRFTokenSync();
         alert("ERROR, Please Contact Server Room");
      });
}


function ckeck_to_dt(btnroster) {
   var en_dt = prompt("Enter Date Ex(dd-mm-yyyy)", '');
   var output = "";
   if (en_dt != '') {
      var reg_x = new RegExp('-');
      var gt_tx = reg_x.test(en_dt);
      if (gt_tx == true) {
         var ex_en_dt = en_dt.split('-');
         var dt = ex_en_dt[0];
         var mn = ex_en_dt[1];
         var yr = ex_en_dt[2];
         var dat = new Date(yr, mn, dt);
         var cr_dt = dat.getDate();
         var cr_mon = dat.getMonth();
         var cr_yr = dat.getFullYear();
         if (cr_dt == 'NaN' || cr_mon == 'NaN' || cr_yr == 'NaN') {
            if (cr_dt == 'NaN')
               alert('Date is not valid');
            else if (cr_mon == 'NaN')
               alert('Date is not valid');
            else if (cr_yr == 'NaN')
               alert('Date is not valid');
         }
         else {
            var xmlhttp;
            if (window.XMLHttpRequest) {
               xmlhttp = new XMLHttpRequest();
            }
            else {
               xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function () {
               if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                  document.getElementById('dv_cl_roster').innerHTML = xmlhttp.responseText;

                  var hd_frr_too_dt = document.getElementById('hd_frr_too_dt').value;
                  if (hd_frr_too_dt == 1) {
                     alert("Closing Date Can't be less than allotment date");
                     output = 0;
                     // return false;
                  }
                  else if (hd_frr_too_dt == 2) {
                     // alert(en_dt);
                     output = 1;
                     // return en_dt;
                  }
               }
            }
            xmlhttp.open("GET", "check_fr_to_dt?btnroster=" + btnroster + "&en_dt=" + en_dt, false);
            xmlhttp.send(null);
         }
      }
      else {
         alert("Format should be dd-mm-yyyy");
      }
   }

   // return output;   
   if (output == 1)
      return en_dt;
   else
      return 0;
}

function deletes_records(str) {
   var cn = confirm("Click Ok to delete roster");
   if (cn == true) {
      $('#page_loader').show();
      var rdn_ckk = '';
      if (document.getElementById('rdn_motion').checked == true) {
         rdn_ckk = document.getElementById('rdn_motion').value;
      }
      else if (document.getElementById('rdn_final').checked == true) {
         rdn_ckk = document.getElementById('rdn_final').value;
      }
      else if (document.getElementById('rdn_lok').checked == true) {
         rdn_ckk = document.getElementById('rdn_lok').value;
      }
      else if (document.getElementById('rdn_med').checked == true) {
         rdn_ckk = document.getElementById('rdn_med').value;
      }
      var str1 = str.split('_');
      var btnroster = document.getElementById('btnroster_' + str1[1]).value;

      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      //  document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('dv_cl_roster').innerHTML = xmlhttp.responseText;
            var hd_sno_s = document.getElementById('hd_sno_ss').value;
            if (hd_sno_s == 1 || hd_sno_s == 2) {
               alert('Error: Roster not deleted');
               $('#page_loader').hide();
            }
            else if (hd_sno_s == 3) {
               alert("Roster deleted successfully");               
               // setTimeout(function() { 
                  run_rosterDetails(rdn_ckk); 
               // }, 3000);
               // setTimeout(function() { 
                  closeData(); 
               // }, 5000);
            }
         }
      }
      xmlhttp.open("GET", "delete_roaster?btnroster=" + btnroster, true);
      xmlhttp.send(null);
   }
}



function get_selecteds_recss() {

   var ct_list = '';
   var ct_lists = '';
   var dt_list = '';

   var srcList = document.getElementById('srcList');
   var destList = document.getElementById('destList');
   for (var jj = 0; jj < destList.length; jj++) {

      if (dt_list == '') {
         dt_list = destList[jj].value;
      }
      else {
         dt_list = dt_list + '^' + destList[jj].value;
      }
   }
   var ex_dt_list = dt_list.split('^');

   for (var ii = 0; ii < srcList.length; ii++) {

      var sc_s = 0;



      if (srcList[ii].selected == true) {


         for (var jj1 = 0; jj1 < ex_dt_list.length; jj1++) {
            if (ex_dt_list[jj1] == srcList[ii].value) {
               if (sc_s == '')
                  sc_s = ex_dt_list[jj1];
               else
                  sc_s = sc_s + ' && ' + ex_dt_list[jj1];
            }
         }

         if (srcList[ii].value != (sc_s)) {
            if (ct_list == '') {
               ct_list = srcList[ii].value;
               ct_lists = srcList[ii].value;
            }
            else {
               ct_list = ct_list + '^' + srcList[ii].value;
               ct_lists = ct_lists + ' && ' + srcList[ii].value;
            }
         }
      }
   }
   if (ct_lists != (sc_s) && ct_lists != '') {
      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      //    document.getElementById('td_sel_jud').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>';


      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('destList').innerHTML += xmlhttp.responseText;


         }
      }
      xmlhttp.open("GET", "get_sec_recs.php?ct_list=" + ct_list, true);
      xmlhttp.send(null);

   }
}

function gets_priority(idd, vl) {
   // var str_replace='';
   // if(document.getElementById('hd_pr_nm_val').value=='')
   document.getElementById('hd_pr_nm_val').value = idd + '^' + vl;
   //      else
   //           {
   //      var str=document.getElementById('hd_pr_nm_val').value;
   //      var patt=/@/g;
   //      var result=patt.test(str);
   //    
   //      if(result==true)
   //           {
   //             var str1= str.split('@');
   //             for(var i=0;i<str1.length;i++)
   //                  {
   //                     var srt2=str1[i].split('^');
   //                      var patt1=new RegExp(srt2[i]);
   //                   //  var patt1='/'+srt2[0]+'/g';
   //                //   alert(srt2[0]);
   //                     var result1=patt1.test(str);
   //                     if(result1==true)
   //                          {
   //                             var pr_i=document.getElementById(srt2[0]).getAttribute('value');
   //                             var pr_i_mer=srt2[0]+'^'+pr_i
   //                        str_replace= str.replace('/'+pr_i_mer+'/gi',srt2[0]+'^'+document.getElementById(srt2[0]).value);
   //                      document.getElementById('hd_pr_nm_val').value=str_replace;  
   //                      alert(document.getElementById('hd_pr_nm_val').value);
   //                    }
   //                   else
   //                        {
   //                          document.getElementById('hd_pr_nm_val').value=document.getElementById('hd_pr_nm_val').value+'@'+idd+'^'+vl;   
   //                       alert("Anshul");
   //                       }
   //                  }
   //                // alert(document.getElementById('hd_pr_nm_val').value);
   //           }
   //          else
   //               {
   //    
   //    document.getElementById('hd_pr_nm_val').value=document.getElementById('hd_pr_nm_val').value+'@'+idd+'^'+vl;
   //    }
   //           }
}

function get_inc() {
   var hd_pr_nm_val = document.getElementById('hd_pr_nm_val').value;
   var ex_hd_pr_nm_val = hd_pr_nm_val.split('^');
   var ex_hd_pr_nm_val_id = ex_hd_pr_nm_val[0];
   var ex_hd_pr_nm_val_num = ex_hd_pr_nm_val[1];
   var hd_total_cat = document.getElementById('hd_total_cat').value;
   for (var i = 1; i < hd_total_cat; i++) {
      //                if(ex_hd_pr_nm_val_id==document.getElementById('txtpriroty'+i).id)
      //                    break;
      if ((parseInt(document.getElementById('txtpriroty' + i).value) >= parseInt(ex_hd_pr_nm_val_num)) && (document.getElementById('txtpriroty' + i).id != ex_hd_pr_nm_val_id)) {
         var add_prr = parseInt((document.getElementById('txtpriroty' + i).value)) + 1;
         document.getElementById('txtpriroty' + i).value = add_prr;
         document.getElementById('txtpriroty' + i).setAttribute('value', document.getElementById('txtpriroty' + i).value);
      }
      //                    else if((document.getElementById('txtpriroty'+i).value<ex_hd_pr_nm_val_num) && (document.getElementById('txtpriroty'+i).id!=ex_hd_pr_nm_val_id))
      //                     {
      //                        var add_prr=parseInt((document.getElementById('txtpriroty'+i).value))+1;
      //                        document.getElementById('txtpriroty'+i).value=add_prr;
      //                     }
   }
   document.getElementById('hd_pr_nm_val').value = '';
}

function get_inc1() {
   var hd_pr_nm_val = document.getElementById('hd_pr_nm_vals').value;
   var ex_hd_pr_nm_val = hd_pr_nm_val.split('^');
   var ex_hd_pr_nm_val_id = ex_hd_pr_nm_val[0];
   var ex_hd_pr_nm_val_num = ex_hd_pr_nm_val[1];
   var hd_total_cat = document.getElementById('hd_tootal').value;
   for (var i = 1; i <= hd_total_cat; i++) {
      //                if(ex_hd_pr_nm_val_id==document.getElementById('txtprirotys'+i).id)
      ////                     {
      ////                   
      //                  break;
      //                     }

      // alert(document.getElementById('txtprirotys'+i).value+'!!'+ex_hd_pr_nm_val_num +document.getElementById('txtprirotys'+i).id+'@@'+ex_hd_pr_nm_val_id);
      //  alert(ex_hd_pr_nm_val_id);
      if ((parseInt(document.getElementById('txtprirotys' + i).value) >= parseInt(ex_hd_pr_nm_val_num.trim())) && (document.getElementById('txtprirotys' + i).id != ex_hd_pr_nm_val_id.trim()))
      //  if((document.getElementById('txtprirotys'+i).getAttribute('value')>=ex_hd_pr_nm_val_num) && (document.getElementById('txtprirotys'+i).id!=ex_hd_pr_nm_val_id))
      {

         var add_prr = parseInt((document.getElementById('txtprirotys' + i).value)) + 1;
         document.getElementById('txtprirotys' + i).value = add_prr;
         document.getElementById('txtprirotys' + i).setAttribute('value', document.getElementById('txtprirotys' + i).value);


      }
      //                    else if((document.getElementById('txtprirotys'+i).value<ex_hd_pr_nm_val_num) && (document.getElementById('txtprirotys'+i).id!=ex_hd_pr_nm_val_id))
      //                     {
      //                        var add_prr=parseInt((document.getElementById('txtprirotys'+i).value))+1;
      //                        document.getElementById('txtprirotys'+i).value=add_prr;
      //                     }

   }

   document.getElementById('hd_pr_nm_vals').value = '';
   // save_ex_party(1);
}

function gets_priority1(idd, vl) {

   document.getElementById('hd_pr_nm_vals').value = idd + '^' + vl;
   document.getElementById(idd).setAttribute('value', vl);
}

function transfer_records(str) {
   var str1 = str.split('_');
   var btnroster = document.getElementById('btnroster_' + str1[1]).value;
   var CSRF_TOKEN = 'CSRF_TOKEN';
   var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   $.ajax
      ({
         type: "POST",
         url: "check_listed_cases",
         cache: false,
         data: { btnroster: btnroster, CSRF_TOKEN:CSRF_TOKEN_VALUE },
         success: function (data1) {
            updateCSRFTokenSync();
            if (data1 == 2) {
               var btnbench_id = document.getElementById('btnbench_id' + str1[1]).value;

               document.getElementById('ggg').style.width = 'auto';
               document.getElementById('ggg').style.height = ' 500px';
               document.getElementById('ggg').style.overflow = 'scroll';

               document.getElementById('ggg').style.marginLeft = '18px';
               document.getElementById('ggg').style.marginRight = '18px';
               document.getElementById('ggg').style.marginBottom = '25px';
               document.getElementById('ggg').style.marginTop = '40px';
               document.getElementById('dv_sh_hd').style.display = 'block';
               document.getElementById('dv_fixedFor_P').style.display = 'block';
               document.getElementById('dv_fixedFor_P').style.marginTop = '3px';

               var xmlhttp;
               if (window.XMLHttpRequest) {
                  xmlhttp = new XMLHttpRequest();
               }
               else {
                  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
               }

               xmlhttp.onreadystatechange = function () {
                  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                     document.getElementById('ggg').innerHTML = xmlhttp.responseText;
                  }
               }
               xmlhttp.open("GET", "get_tra_ros_det?btnbench_id=" + btnbench_id + "&str1=" + str1[1] + '&btnroster=' + btnroster, true);
               xmlhttp.send(null);
            }
            else {
               alert("Can't transfer roster because case already listed for this roster");
            }
         }
      }).fail(function () {
         updateCSRFTokenSync();
         alert("ERROR, Please Contact Server Room");
      });
}


function trans_final(str) {
   // var str1=str.split('_');
   var sel_ros = 0;
   var hd_s_d_ben = document.getElementById('hd_s_d_ben').value;
   var bench_names = document.getElementById('bench_names').value;
   var judge_codes = document.getElementById('judge_codes').value;
   var txt_no_cases = $('#txt_no_cases').val();
   var txt_court_nos = $('#txt_court_nos').val();
   // var from_days=document.getElementById('from_days').value;
   // var to_days=document.getElementById('to_days').value;
   var sesss = document.getElementById('sesss').value;
   var judges_c = document.getElementById('judge_codes');
   // var sess_nos=document.getElementById('sess_nos').value;

   // var from_day2s=document.getElementById('from_day2s').value;
   // var to_day2s=document.getElementById('to_day2s').value;
   // var sess2s=document.getElementById('sess2s').value;
     
   // var from_day3s=document.getElementById('from_day2s').value;
   // var to_day3s=document.getElementById('to_day2s').value;
   // var sess3s=document.getElementById('sess2s').value;

   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }
   for (var z = 0; z < judges_c.length; z++) {
      if (judges_c[z].selected == true) {
         sel_ros++;
      }
   }

   if (bench_names == '' || sesss == '' || (hd_s_d_ben == 1 && judge_codes == 0) || (hd_s_d_ben == 2 && sel_ros < 2) || (hd_s_d_ben == 5 && sel_ros < 2) || ($('#ddl_hrss').val() != '' && ($('#ddl_mins').val() == '' || $('#ddl_am_pms').val() == ''))) {
      if (bench_names == '')
         alert("Please Select Bench No");

      else if (sesss == '')
         alert("Please Select Session");
      else if (hd_s_d_ben == 1 && judge_codes == 0)
         alert("Please Select Judge Name");
      else if (hd_s_d_ben == 2 && sel_ros < 2)
         alert("Please Select two Judge Name");
      else if (hd_s_d_ben == 5 && sel_ros < 2)
         alert("Please Select two Judge Name");
      else if ($('#ddl_mins').val() == '') {
         alert('Please Select Minute');
         $('#ddl_mins').focus();
      }
      else if ($('#ddl_am_pms').val() == '') {
         alert("Please Select AM or PM");
         $('#ddl_am_pms').focus();
      }
   }
   else {
      var j1_j2 = '';

      var ck_ct_hall = '';
      if ($('#rdn_courts').is(':checked')) {
         ck_ct_hall = $('#rdn_courts').val();
      }
      else if ($('#rdn_hls').is(':checked')) {
         ck_ct_hall = $('#rdn_hls').val();
      }

      var ddl_hrs = $('#ddl_hrss').val();
      var ddl_min = $('#ddl_mins').val();
      var ddl_am_pm = $('#ddl_am_pms').val();

      var btnroster = document.getElementById('btnroster_' + str).value;
      var bench_name_inn = escape(document.getElementById('bench_names').options[document.getElementById('bench_names').selectedIndex].innerHTML);
      // var sp_bnch_nm = $('#sp_bnch_nm').html();
      var sp_bnch_nm = $('#sp_bnch_nm').val();

      for (var z1 = 0; z1 < judges_c.length; z1++) {
         if (judges_c[z1].selected == true) {
            if (j1_j2 == '') {
               j1_j2 = judges_c[z1].value;
            }
            else {
               j1_j2 = j1_j2 + ',' + judges_c[z1].value;
            }
         }
      }

      var xmlhttp;
      if (window.XMLHttpRequest) {
         xmlhttp = new XMLHttpRequest();
      }
      else {
         xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }

      //  document.getElementById('ggg').innerHTML = '<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>'; 

      xmlhttp.onreadystatechange = function () {
         if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            document.getElementById('dv_cl_roster').innerHTML = xmlhttp.responseText;
            
            var hd_sno_s = document.getElementById('hd_sno_ss').value;
            if (hd_sno_s == 1 || hd_sno_s == 2) {
               alert('Error: Roster not Transfered');
            }
            else if (hd_sno_s == 4) {
               var hd_error = $('#hd_error').val();
               alert(hd_error);
            }
            else if (hd_sno_s == 3) {
               alert("Roster Transfered successfully");
               var CSRF_TOKEN = 'CSRF_TOKEN';
               var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
               $.ajax
                  ({
                     type: "POST",
                     url: base_url + "/Listing/roster/get_roster",
                     cache: false,
                     data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
                     success: function (data1) {
                        updateCSRFTokenSync();
                        $(".get_roster").html(data1);
                        closeData();
                        //  var hd_hd_show_hide_dt=$('#hd_hd_show_hide_dt_s').val();
                        //  show_hide_dt(hd_hd_show_hide_dt);
                     }
                  }).fail(function () {
                     updateCSRFTokenSync();
                     alert("ERROR, Please Contact Server Room");
                  });
            }


         }
      }
      xmlhttp.open("GET", "transfer_roaster?btnroster=" + btnroster + "&bench_names=" + bench_names + "&j1_j2=" + j1_j2 + "&sesss=" + sesss + "&hd_s_d_ben=" + hd_s_d_ben + "&bench_name_inn=" + bench_name_inn + '&ck_ct_hall=' + ck_ct_hall + '&ddl_hrs=' + ddl_hrs + '&ddl_min=' + ddl_min + '&ddl_am_pm=' + ddl_am_pm + '&txt_no_cases=' + txt_no_cases +'&sp_bnch_nm=' + sp_bnch_nm + '&txt_court_nos=' + txt_court_nos, true);
      xmlhttp.send(null);
   }
}

function get_hd_sh_dt() {

   if ($("#sess_nos").val() == 1) {
      $(".ses_more2s, .ses_more3s").hide();
   }
   if ($("#sess_nos").val() == 2) {
      $(".ses_more2s").fadeIn();
      $(".ses_more3s").hide();
   }
   if ($("#sess_nos").val() == 3) {
      $(".ses_more2s").fadeIn();
      $(".ses_more3s").fadeIn();
   }
}

function get_ts_real() {
   var hd_fn_sn = document.getElementById('hd_fn_sn').value;
   trans_final(hd_fn_sn);
}

function print_records(str) {

   var str1 = str.split('_');
   var prtContent = document.getElementById('trPrint_' + str1[1]);
   var WinPrint = window.open('', '', 'letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
   WinPrint.document.write("<style> .bk_out {  display:none; }  </style>" + prtContent.innerHTML);
   // WinPrint.document.write("<style> #btnAdd_0 {  display:none; } </style>" + prtContent.innerHTML);
   // WinPrint.document.getElementsById('btnAdd_0').style.display='none';
   // WinPrint.document.getElementsByClassName('bk_out').style.display='none';
   // $(".bk_out").hide();
   WinPrint.document.close();
   WinPrint.focus();
   WinPrint.print();
}

async function get_mot_details() {
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }

   await updateCSRFTokenSync();
   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   $(".get_roster").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('dv_mot_record').innerHTML = xmlhttp.responseText;
         //  var  tr_mt_de= document.getElementById('tr_mt_de');
         //  $("#td_ctt").insertAfter("#tr_mt_de");
         //  tr_mt_de.insertBefore(document.getElementById('td_ctt'),tr_mt_de.childNodes[2]);
         var CSRF_TOKEN = 'CSRF_TOKEN';
         var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
         $.ajax
            ({
               type: "POST",
               url: base_url + "/Listing/roster/get_roster",
               cache: false,
               data: { m_f: rdn_ckk, CSRF_TOKEN: CSRF_TOKEN_VALUE },
               success: function (data1) {
                  updateCSRFTokenSync();
                  $(".get_roster").html(data1);
                  $(".select2-search__field").attr("placeholder", "Select");
                  closeData();
               }
            }).fail(function () {
               updateCSRFTokenSync();
               alert("ERROR, Please Contact Server Room");
            });
      }
   }
   xmlhttp.open("GET", base_url + "/Listing/roster/get_mot_details?rdn_ckk=" + rdn_ckk, true);
   xmlhttp.send(null);
}

$('.select2-search__field').on('keyup change', function(e){
   $(".select2-search__field").attr("placeholder", "Select");
});

function getcat(subjectId) {

   var xmlhttp;
   if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

         document.getElementById('cat').innerHTML = xmlhttp.responseText;
         document.getElementById('subcat').innerHTML = "<option value=''>Select</option>";
      }
   }

   xmlhttp.open("GET", base_url + "/Listing/roster/getcat_ans?subjectId=" + subjectId, true);
   xmlhttp.send(null);


}

function getcats(subjectId) {

   var xmlhttp;
   if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

         document.getElementById('cats').innerHTML = xmlhttp.responseText;
         document.getElementById('subcats').innerHTML = "<option value=''>Select</option>";
      }
   }

   xmlhttp.open("GET", base_url + "/Listing/roster/getcat_ans?subjectId=" + subjectId, true);
   xmlhttp.send(null);


}

function getsubcat(subjectId, catId) {

   var xmlhttp;
   if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         // var txtAdeshika="txtAdeshika"+rowCount;
         document.getElementById('subcat').innerHTML = xmlhttp.responseText;

      }
   }
   // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
   xmlhttp.open("GET", base_url + "/Listing/roster/getsubcat_ans?subject=" + subjectId + "&cat=" + catId, true);
   xmlhttp.send(null);



}


function getsubcats(subjectId, catId) {

   var xmlhttp;
   if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         // var txtAdeshika="txtAdeshika"+rowCount;
         document.getElementById('subcats').innerHTML = xmlhttp.responseText;

      }
   }
   // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
   xmlhttp.open("GET", base_url + "/Listing/roster/getsubcat_ans?subject=" + subjectId + "&cat=" + catId, true);
   xmlhttp.send(null);



}
var cnt_data1 = 1;
function add_mo_rec() {
   //   var head_g='';
   var hd_tb_new_mo = document.getElementById('hd_tb_new_mo').value
   var ddl_mn_cs_ty = document.getElementById('ddl_mn_cs_ty');
   var srcList_mon = document.getElementById('srcList_mon');
   var m_cat = document.getElementById('m_cat').value;
   var ddl_cas_nature = document.getElementById('ddl_cas_nature').value;
   var sub_sub_cat = $('#sub_sub_cat').val();

   var m_cat_nm = document.getElementById('m_cat').options[document.getElementById('m_cat').selectedIndex].innerHTML;
   var cat_nm = document.getElementById('cat').options[document.getElementById('cat').selectedIndex].innerHTML;
   var subcat_nm = document.getElementById('subcat').options[document.getElementById('subcat').selectedIndex].innerHTML;

   var sub_sub_cat_nm = document.getElementById('sub_sub_cat').options[document.getElementById('sub_sub_cat').selectedIndex].innerHTML;

   var cat = document.getElementById('cat').value;
   var subcat = document.getElementById('subcat').value;

   var r_befote_nt = $('#rdn_before_ck').val();
   var r_befote_nt_name = document.getElementById('rdn_before_ck').options[document.getElementById('rdn_before_ck').selectedIndex].innerHTML;
   //     if($('#rdn_before_ck').is(':checked'))
   //          {
   //             r_befote_nt=$('#rdn_before_ck').val();
   //          }
   //       else if($('#rdn_notbefore_ck').is(':checked'))
   //          {
   //             r_befote_nt=$('#rdn_notbefore_ck').val();
   //          }

   if (m_cat != '' && r_befote_nt == '') {
      // alert(r_befote_nt);
      alert("Please Select Before or Not Before");
   }
   else if (m_cat == '' && r_befote_nt != '') {
      // alert(r_befote_nt);
      alert("Please Select atleast one category");
   }
   //         else if(m_cat!='0' && cat=='' )
   //              {
   //                 alert("Please select sub category");
   //              }
   else {

      var ck_ddl_mn_cs_ty = 0;
      var ck_srcList_mon = 0;

      var main_cat_id = '';
      //      alert(m_cat+'%'+cat+'%'+subcat+'%'+sub_sub_cat);
      if (m_cat != '' && cat == '' && subcat == '' && sub_sub_cat == '')
         main_cat_id = m_cat;
      else if (m_cat != '' && cat != '' && subcat == '' && sub_sub_cat == '')
         main_cat_id = cat;
      else if (m_cat != '' && cat != '' && subcat != '' && sub_sub_cat == '')
         main_cat_id = subcat;
      else if (m_cat != '' && cat != '' && subcat != '' && sub_sub_cat != '')
         main_cat_id = sub_sub_cat;
      //    alert(main_cat_id);
      for (var ii = 0; ii < ddl_mn_cs_ty.length; ii++) {
         if (ddl_mn_cs_ty[ii].selected == true) {
            ck_ddl_mn_cs_ty = 1;
         }
      }

      for (var jj = 0; jj < srcList_mon.length; jj++) {
         if (srcList_mon[jj].selected == true) {
            ck_srcList_mon = 1;
         }
      }

      if (ck_ddl_mn_cs_ty == 1 && ck_srcList_mon == 1) {
         for (var i = 0; i < ddl_mn_cs_ty.length; i++) {

            if (ddl_mn_cs_ty[i].selected == true) {
               //                    alert(ddl_mn_cs_ty.options[ddl_mn_cs_ty.selectedIndex].innerHTML);
               if (ddl_mn_cs_ty[i].value == '') {
                  continue;
               }
               for (var j = 0; j < srcList_mon.length; j++) {
                  if (srcList_mon[j].selected == true) {
                     if (srcList_mon[j].value == '') {
                        continue;
                     }
                     var chk_ex_st = 0;
                     for (var l = 1; l < hd_tb_new_mo; l++) {
                        var hd_sp_a = document.getElementById('hd_sp_a' + l).value;
                        var hd_sp_b = document.getElementById('hd_sp_b' + l).value;
                        var hd_sp_c = document.getElementById('hd_sp_c' + l).value;
                        //                               var hd_sp_d=document.getElementById('hd_sp_d'+l).value;
                        //                               var hd_sp_e=document.getElementById('hd_sp_e'+l).value;
                        var hd_sp_f = document.getElementById('hd_sp_f' + l).value;
                        //                                var hd_sp_g=document.getElementById('hd_sp_g'+l).value;
                        if (hd_sp_a == (ddl_mn_cs_ty[i].value) && hd_sp_b == (srcList_mon[j].value) &&
                           hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                           chk_ex_st = 1;
                        }
                     }
                     if (chk_ex_st == 0) {
                        add_rec_s(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                        cnt_data1++;
                     }
                  }


               }
            }

         }
      }
      else if (ck_ddl_mn_cs_ty == 1 && ck_srcList_mon == 0) {
         j = -1;
         for (var i = 0; i < ddl_mn_cs_ty.length; i++) {

            if (ddl_mn_cs_ty[i].selected == true) {
               if (ddl_mn_cs_ty[i].value == '') {
                  continue;
               }

               var chk_ex_st = 0;
               for (var l = 1; l < hd_tb_new_mo; l++) {
                  var hd_sp_a = document.getElementById('hd_sp_a' + l).value;
                  var hd_sp_b = document.getElementById('hd_sp_b' + l).value;
                  var hd_sp_c = document.getElementById('hd_sp_c' + l).value;
                  //                               var hd_sp_d=document.getElementById('hd_sp_d'+l).value;
                  //                               var hd_sp_e=document.getElementById('hd_sp_e'+l).value;
                  var hd_sp_f = document.getElementById('hd_sp_f' + l).value;
                  //                                var hd_sp_g=document.getElementById('hd_sp_g'+l).value;
                  if (hd_sp_a == (ddl_mn_cs_ty[i].value) && hd_sp_b == 0 &&
                     hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                     chk_ex_st = 1;
                  }
               }
               if (chk_ex_st == 0) {
                  add_rec_s(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                  cnt_data1++;
               }
            }
         }
      }

      else if (ck_ddl_mn_cs_ty == 0 && ck_srcList_mon == 1) {
         i = -1;
         for (var j = 0; j < srcList_mon.length; j++) {
            if (srcList_mon[j].selected == true) {
               if (srcList_mon[j].value == '') {
                  continue;
               }
               var chk_ex_st = 0;
               for (var l = 1; l < hd_tb_new_mo; l++) {
                  var hd_sp_a = document.getElementById('hd_sp_a' + l).value;
                  var hd_sp_b = document.getElementById('hd_sp_b' + l).value;
                  var hd_sp_c = document.getElementById('hd_sp_c' + l).value;
                  //                               var hd_sp_d=document.getElementById('hd_sp_d'+l).value;
                  //                               var hd_sp_e=document.getElementById('hd_sp_e'+l).value;
                  var hd_sp_f = document.getElementById('hd_sp_f' + l).value;
                  //                                   var hd_sp_g=document.getElementById('hd_sp_g'+l).value;
                  if (hd_sp_a == 0 && hd_sp_b == (srcList_mon[j].value) &&
                     hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                     chk_ex_st = 1;
                  }
               }
               if (chk_ex_st == 0) {
                  add_rec_s(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                  cnt_data1++;
               }
            }
         }
      }
      else if (ck_ddl_mn_cs_ty == 0 && ck_srcList_mon == 0) {
         i = -1; j = -1;
         if (m_cat != 0 || ddl_cas_nature != '0') {

            var chk_ex_st = 0;
            for (var l = 1; l < hd_tb_new_mo; l++) {
               var hd_sp_a = document.getElementById('hd_sp_a' + l).value;
               var hd_sp_b = document.getElementById('hd_sp_b' + l).value;
               var hd_sp_c = document.getElementById('hd_sp_c' + l).value;
               //                               var hd_sp_d=document.getElementById('hd_sp_d'+l).value;
               //                               var hd_sp_e=document.getElementById('hd_sp_e'+l).value;
               var hd_sp_f = document.getElementById('hd_sp_f' + l).value;
               //                                    var hd_sp_g=document.getElementById('hd_sp_g'+l).value;
               if (hd_sp_a == 0 && hd_sp_b == 0 &&
                  hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                  chk_ex_st = 1;
               }
            }

            if (chk_ex_st == 0) {

               add_rec_s(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
               cnt_data1++;
            }
         }
      }
      document.getElementById('hd_tb_new_mo').value = cnt_data1;

   }
}



function add_mo_recs() {
   //   var head_g='';
   var hd_tb_new_mo = document.getElementById('hd_tootal').value;
   cnt_data1s = parseInt(hd_tb_new_mo);
   var ddl_mn_cs_ty = document.getElementById('ddl_mn_cs_tys');
   var srcList_mon = document.getElementById('srcList_mons');
   var m_cat = document.getElementById('m_cats').value;
   var ddl_cas_nature = document.getElementById('ddl_cas_natures').value;
   var sub_sub_cat = $('#sub_sub_cats').val();
   var m_cat_nm = document.getElementById('m_cats').options[document.getElementById('m_cats').selectedIndex].innerHTML;
   var cat_nm = document.getElementById('cats').options[document.getElementById('cats').selectedIndex].innerHTML;
   var subcat_nm = document.getElementById('subcats').options[document.getElementById('subcats').selectedIndex].innerHTML;
   var sub_sub_cat_nm = document.getElementById('sub_sub_cats').options[document.getElementById('sub_sub_cats').selectedIndex].innerHTML;
   var cat = document.getElementById('cats').value;
   var subcat = document.getElementById('subcats').value;

   var r_befote_nt = $('#rdn_before_cks').val();
   var r_befote_nt_name = document.getElementById('rdn_before_cks').options[document.getElementById('rdn_before_cks').selectedIndex].innerHTML;
   if (m_cat != '' && r_befote_nt == '') {
      // alert(r_befote_nt);
      alert("Please Select Before or Not Before");
   }
   else if (m_cat == '' && r_befote_nt != '') {
      // alert(r_befote_nt);
      alert("Please Select atleast one category");
   }
   //          else if(m_cat!='0' && cat=='' )
   //          {
   //              // alert(r_befote_nt);
   //             alert("Please Select Sub category");
   //          }
   else {


      var ck_ddl_mn_cs_ty = 0;
      var ck_srcList_mon = 0;
      var main_cat_id = '';
      if (m_cat != '' && cat == '' && subcat == '' && sub_sub_cat == '')
         main_cat_id = m_cat;
      else if (m_cat != '' && cat != '' && subcat == '' && sub_sub_cat == '')
         main_cat_id = cat;
      else if (m_cat != '' && cat != '' && subcat != '' && sub_sub_cat == '')
         main_cat_id = subcat;
      else if (m_cat != '' && cat != '' && subcat != '' && sub_sub_cat != '')
         main_cat_id = sub_sub_cat;
      for (var ii = 0; ii < ddl_mn_cs_ty.length; ii++) {
         if (ddl_mn_cs_ty[ii].selected == true) {
            ck_ddl_mn_cs_ty = 1;
         }
      }

      for (var jj = 0; jj < srcList_mon.length; jj++) {
         if (srcList_mon[jj].selected == true) {
            ck_srcList_mon = 1;
         }
      }

      if (ck_ddl_mn_cs_ty == 1 && ck_srcList_mon == 1) {
         for (var i = 0; i < ddl_mn_cs_ty.length; i++) {

            if (ddl_mn_cs_ty[i].selected == true) {
               //                    alert(ddl_mn_cs_ty.options[ddl_mn_cs_ty.selectedIndex].innerHTML);
               if (ddl_mn_cs_ty[i].value == '') {
                  continue;
               }
               for (var j = 0; j < srcList_mon.length; j++) {
                  if (srcList_mon[j].selected == true) {
                     if (srcList_mon[j].value == '') {
                        continue;
                     }
                     var chk_ex_st = 0;
                     for (var l = 1; l < hd_tb_new_mo; l++) {
                        var hd_sp_a = document.getElementById('hd_sp_as' + l).value;
                        var hd_sp_b = document.getElementById('hd_sp_bs' + l).value;
                        var hd_sp_c = document.getElementById('hd_sp_cs' + l).value;
                        //                               var hd_sp_d=document.getElementById('hd_sp_ds'+l).value;
                        //                               var hd_sp_e=document.getElementById('hd_sp_es'+l).value;
                        var hd_sp_f = document.getElementById('hd_sp_fs' + l).value;
                        //                               if(subcat=='')
                        //                                     subcat=0
                        if (hd_sp_a == (ddl_mn_cs_ty[i].value) && hd_sp_b == (srcList_mon[j].value) &&
                           hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                           chk_ex_st = 1;
                        }
                     }
                     if (chk_ex_st == 0) {
                        add_rec_ss(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                        cnt_data1s++;
                     }
                  }


               }
            }

         }
      }
      else if (ck_ddl_mn_cs_ty == 1 && ck_srcList_mon == 0) {
         j = -1;
         for (var i = 0; i < ddl_mn_cs_ty.length; i++) {

            if (ddl_mn_cs_ty[i].selected == true) {
               if (ddl_mn_cs_ty[i].value == '') {
                  continue;
               }

               var chk_ex_st = 0;
               for (var l = 1; l < hd_tb_new_mo; l++) {
                  var hd_sp_a = document.getElementById('hd_sp_as' + l).value;
                  var hd_sp_b = document.getElementById('hd_sp_bs' + l).value;
                  var hd_sp_c = document.getElementById('hd_sp_cs' + l).value;
                  //                               var hd_sp_d=document.getElementById('hd_sp_ds'+l).value;
                  //                               var hd_sp_e=document.getElementById('hd_sp_es'+l).value;
                  var hd_sp_f = document.getElementById('hd_sp_fs' + l).value;
                  //                               alert(hd_sp_a+'~'+ddl_mn_cs_ty[i].value);
                  //                                  alert(hd_sp_b+'~'+srcList_mon[j].value);
                  //                                   alert(hd_sp_c+'~'+m_cat);
                  //                                    alert(hd_sp_d+'~'+cat);
                  //                                     alert(hd_sp_e+'~'+subcat);
                  //                                       alert(hd_sp_f+'~'+ddl_cas_nature);
                  if (subcat == '')
                     subcat = 0
                  if (hd_sp_a == (ddl_mn_cs_ty[i].value) && hd_sp_b == 0 &&
                     hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                     chk_ex_st = 1;
                  }
               }
               if (chk_ex_st == 0) {
                  add_rec_ss(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                  cnt_data1s++;
               }
            }
         }
      }

      else if (ck_ddl_mn_cs_ty == 0 && ck_srcList_mon == 1) {
         i = -1;
         for (var j = 0; j < srcList_mon.length; j++) {
            if (srcList_mon[j].selected == true) {
               if (srcList_mon[j].value == '') {
                  continue;
               }
               var chk_ex_st = 0;
               for (var l = 1; l < hd_tb_new_mo; l++) {
                  var hd_sp_a = document.getElementById('hd_sp_as' + l).value;
                  var hd_sp_b = document.getElementById('hd_sp_bs' + l).value;
                  var hd_sp_c = document.getElementById('hd_sp_cs' + l).value;
                  //                               var hd_sp_d=document.getElementById('hd_sp_ds'+l).value;
                  //                               var hd_sp_e=document.getElementById('hd_sp_es'+l).value;
                  var hd_sp_f = document.getElementById('hd_sp_fs' + l).value;
                  if (subcat == '')
                     subcat = 0

                  if (hd_sp_a == 0 && hd_sp_b == (srcList_mon[j].value) &&
                     hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                     chk_ex_st = 1;
                  }
               }
               if (chk_ex_st == 0) {
                  add_rec_ss(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
                  cnt_data1s++;
               }
            }
         }
      }
      else if (ck_ddl_mn_cs_ty == 0 && ck_srcList_mon == 0) {
         i = -1; j = -1;
         if (m_cat != 0 || ddl_cas_nature != '0') {

            var chk_ex_st = 0;
            for (var l = 1; l < hd_tb_new_mo; l++) {
               var hd_sp_a = document.getElementById('hd_sp_as' + l).value;
               var hd_sp_b = document.getElementById('hd_sp_bs' + l).value;
               var hd_sp_c = document.getElementById('hd_sp_cs' + l).value;
               //                               var hd_sp_d=document.getElementById('hd_sp_ds'+l).value;
               //                               var hd_sp_e=document.getElementById('hd_sp_es'+l).value;
               var hd_sp_f = document.getElementById('hd_sp_fs' + l).value;

               if (subcat == '')
                  subcat = 0

               //                                  alert(hd_sp_a+'~'+ddl_mn_cs_ty[i].value);
               //                                  alert(hd_sp_b+'~'+srcList_mon[j].value);
               //                                   alert('a'+hd_sp_c+'~'+m_cat);
               //                                    alert('b'+hd_sp_d+'~'+cat);
               //                                     alert('c'+hd_sp_e+'~'+subcat);
               //                                       alert('d'+hd_sp_f+'~'+ddl_cas_nature);

               if (hd_sp_a == 0 && hd_sp_b == 0 &&
                  hd_sp_c == main_cat_id && hd_sp_f == ddl_cas_nature) {
                  chk_ex_st = 1;
               }
            }

            if (chk_ex_st == 0) {

               add_rec_ss(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id);
               cnt_data1s++;
            }
         }
      }
      document.getElementById('hd_tootal').value = cnt_data1s;
   }
}







function add_rec_s(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id) {
   //alert(srcList_mon[j].value);
   var row0 = document.createElement("tr");
   row0.setAttribute('id', 'tr_uo' + cnt_data1);
   var column0 = document.createElement("td");
   var column1 = document.createElement("td");
   var column2 = document.createElement("td");
   var column3 = document.createElement("td");
   var column4 = document.createElement("td");
   var spAddObj = document.getElementById('tb_new_mo');
   var hd_chk_add = document.createElement('input');
   hd_chk_add.setAttribute('type', 'checkbox');
   hd_chk_add.setAttribute('id', 'hd_chk_add' + cnt_data1);
   hd_chk_add.setAttribute('onclick', 'getDone_upd_cat(this.id);');
   hd_chk_add.setAttribute('checked', 'checked');

   var hd_1 = document.createElement('input');
   hd_1.setAttribute('type', 'hidden');
   hd_1.setAttribute('id', 'hd_sp_a' + cnt_data1);

   var hd_2 = document.createElement('input');
   hd_2.setAttribute('type', 'hidden');
   hd_2.setAttribute('id', 'hd_sp_b' + cnt_data1);

   var hd_3 = document.createElement('input');
   hd_3.setAttribute('type', 'hidden');
   hd_3.setAttribute('id', 'hd_sp_c' + cnt_data1);

   //                            var hd_4=document.createElement('input');
   //                            hd_4.setAttribute('type', 'hidden');
   //                            hd_4.setAttribute('id', 'hd_sp_d'+cnt_data1);
   //
   //                            var hd_5=document.createElement('input');
   //                            hd_5.setAttribute('type', 'hidden');
   //                            hd_5.setAttribute('id', 'hd_sp_e'+cnt_data1);

   var hd_6 = document.createElement('input');
   hd_6.setAttribute('type', 'hidden');
   hd_6.setAttribute('id', 'hd_sp_f' + cnt_data1);


   var hd_7 = document.createElement('input');
   hd_7.setAttribute('type', 'hidden');
   hd_7.setAttribute('id', 'hd_befote_not' + cnt_data1);

   //                             var hd_8=document.createElement('input');
   //                            hd_8.setAttribute('type', 'hidden');
   //                            hd_8.setAttribute('id', 'hd_sp_g'+cnt_data1);

   column0.appendChild(hd_chk_add);
   column0.appendChild(hd_1);
   column0.appendChild(hd_2);
   column0.appendChild(hd_3);
   //                            column0.appendChild(hd_4);
   //                            column0.appendChild(hd_5);
   column0.appendChild(hd_6);

   column0.appendChild(hd_7);
   //                                 column0.appendChild(hd_8);
   row0.appendChild(column0);

   var hd_id_txtcnt = document.createElement('span');
   hd_id_txtcnt.setAttribute('id', 'sp_b' + cnt_data1);

   var sp = document.createElement('span');
   sp.setAttribute('id', 'sp_c' + cnt_data1);

   var chkbx = document.createElement('span');
   chkbx.setAttribute('id', 'sp_d' + cnt_data1);

   var chk_ct = document.createElement('span');
   chk_ct.setAttribute('id', 'sp_a' + cnt_data1);

   column1.appendChild(chk_ct);
   row0.appendChild(column1);
   column2.appendChild(hd_id_txtcnt);
   row0.appendChild(column2);
   column3.appendChild(sp);
   row0.appendChild(column3);
   column4.appendChild(chkbx);
   row0.appendChild(column4);
   spAddObj.appendChild(row0);

   if (i == -1) {
      document.getElementById('hd_sp_a' + cnt_data1).value = 0;
      document.getElementById('sp_b' + cnt_data1).innerHTML = '-';
   }
   else {
      document.getElementById('hd_sp_a' + cnt_data1).value = ddl_mn_cs_ty[i].value;
      document.getElementById('sp_b' + cnt_data1).innerHTML = ddl_mn_cs_ty[i].innerHTML;
   }
   if (j == -1) {
      document.getElementById('hd_sp_b' + cnt_data1).value = 0;
      document.getElementById('sp_c' + cnt_data1).innerHTML = '-';
   }
   else {
      document.getElementById('hd_sp_b' + cnt_data1).value = srcList_mon[j].value;
      document.getElementById('sp_c' + cnt_data1).innerHTML = srcList_mon[j].innerHTML;
   }


   document.getElementById('hd_sp_c' + cnt_data1).value = main_cat_id;
   //                document.getElementById('hd_sp_d'+cnt_data1).value=cat;
   //                 document.getElementById('hd_sp_e'+cnt_data1).value=subcat;
   document.getElementById('hd_sp_f' + cnt_data1).value = ddl_cas_nature;

   document.getElementById('hd_befote_not' + cnt_data1).value = r_befote_nt;

   //                 document.getElementById('hd_sp_g'+cnt_data1).value=sub_sub_cat;

   if (m_cat == 0 || cat == 0 || subcat == 0 || sub_sub_cat == 0) {
      if (m_cat == 0)
         m_cat_nm = '-';
      if (cat == 0)
         cat_nm = '-';
      if (subcat == 0)
         subcat_nm = '-';
      if (sub_sub_cat == 0)
         sub_sub_cat_nm = '-';
   }
   if (r_befote_nt_name == 'Select')
      r_befote_nt_name = '';
   document.getElementById('sp_d' + cnt_data1).innerHTML = '<span style=color:red>' + r_befote_nt_name + '</span><br/>' + m_cat_nm + '<br/>' + cat_nm + "<br/>" + subcat_nm + "<br/>" + sub_sub_cat_nm;
   var cs_name = '';
   if (ddl_cas_nature == 'C') {
      cs_name = 'Civil';
   }
   else if (ddl_cas_nature == 'R') {
      cs_name = 'Criminal';
   }
   else if (ddl_cas_nature == 'W') {
      cs_name = 'Writ';
   }
   if (ddl_cas_nature != '')
      document.getElementById('sp_a' + cnt_data1).innerHTML = cs_name;
   else
      document.getElementById('sp_a' + cnt_data1).innerHTML = '-';
}


function add_rec_ss(m_cat, m_cat_nm, cat_nm, subcat_nm, cat, subcat, srcList_mon, j, i, ddl_mn_cs_ty, ddl_cas_nature, r_befote_nt, r_befote_nt_name, sub_sub_cat_nm, sub_sub_cat, main_cat_id) {
   //alert(srcList_mon[j].value);
   var row0 = document.createElement("tr");
   row0.setAttribute('id', 'tr_uos' + cnt_data1s);
   var column0 = document.createElement("td");
   var column1 = document.createElement("td");
   var column2 = document.createElement("td");
   var column3 = document.createElement("td");
   var column4 = document.createElement("td");
   // var column5=document.createElement("td"); 
   // var column6=document.createElement("td"); 
   var spAddObj = document.getElementById('tb_cat_id');
   var hd_chk_add = document.createElement('input');
   hd_chk_add.setAttribute('type', 'checkbox');
   hd_chk_add.setAttribute('id', 'hd_chk_adds' + cnt_data1s);
   hd_chk_add.setAttribute('onclick', 'getDone_upd_cats(this.id);');
   hd_chk_add.setAttribute('checked', 'checked');

   var hd_1 = document.createElement('input');
   hd_1.setAttribute('type', 'hidden');
   hd_1.setAttribute('id', 'hd_sp_as' + cnt_data1s);

   var hd_2 = document.createElement('input');
   hd_2.setAttribute('type', 'hidden');
   hd_2.setAttribute('id', 'hd_sp_bs' + cnt_data1s);

   var hd_3 = document.createElement('input');
   hd_3.setAttribute('type', 'hidden');
   hd_3.setAttribute('id', 'hd_sp_cs' + cnt_data1s);

   // var hd_4=document.createElement('input');
   // hd_4.setAttribute('type', 'hidden');
   // hd_4.setAttribute('id', 'hd_sp_ds'+cnt_data1s);
   // var hd_5=document.createElement('input');
   // hd_5.setAttribute('type', 'hidden');
   // hd_5.setAttribute('id', 'hd_sp_es'+cnt_data1s);

   var hd_6 = document.createElement('input');
   hd_6.setAttribute('type', 'hidden');
   hd_6.setAttribute('id', 'hd_sp_fs' + cnt_data1s);

   var hd_7 = document.createElement('input');
   hd_7.setAttribute('type', 'hidden');
   hd_7.setAttribute('id', 'hd_sp_gs' + cnt_data1s);


   var hd_8 = document.createElement('input');
   hd_8.setAttribute('type', 'hidden');
   hd_8.setAttribute('id', 'hd_befote_nots' + cnt_data1s);

   column0.appendChild(hd_chk_add);
   column0.appendChild(hd_1);
   column0.appendChild(hd_2);
   column0.appendChild(hd_3);
   // column0.appendChild(hd_4);
   // column0.appendChild(hd_5);
   column0.appendChild(hd_6);
   column0.appendChild(hd_7);
   column0.appendChild(hd_8);
   row0.appendChild(column0);

   var hd_id_txtcnt = document.createElement('span');
   hd_id_txtcnt.setAttribute('id', 'sp_bs' + cnt_data1s);

   var sp = document.createElement('span');
   sp.setAttribute('id', 'sp_cs' + cnt_data1s);

   var chkbx = document.createElement('span');
   chkbx.setAttribute('id', 'sp_ds' + cnt_data1s);

   var chk_ct = document.createElement('span');
   chk_ct.setAttribute('id', 'sp_as' + cnt_data1s);

   // var chkbx_e=document.createElement('span');
   // chkbx_e.setAttribute('id', 'sp_es'+cnt_data1s);

   // var chkbx_f=document.createElement('span');
   // chkbx_f.setAttribute('id', 'sp_fs'+cnt_data1s);
                          
   column1.appendChild(chk_ct);
   row0.appendChild(column1);
   column2.appendChild(hd_id_txtcnt);
   row0.appendChild(column2);
   column3.appendChild(sp);
   row0.appendChild(column3);
   column4.appendChild(chkbx);
   // column5.appendChild(chkbx_e);
   // column6.appendChild(chkbx_f);
   row0.appendChild(column4);
   // row0.appendChild(column5);
   // row0.appendChild(column6);
   spAddObj.appendChild(row0);

   if (i == -1) {
      document.getElementById('hd_sp_as' + cnt_data1s).value = 0;
      document.getElementById('sp_bs' + cnt_data1s).innerHTML = '-';
   }
   else {
      document.getElementById('hd_sp_as' + cnt_data1s).value = ddl_mn_cs_ty[i].value;
      document.getElementById('sp_bs' + cnt_data1s).innerHTML = ddl_mn_cs_ty[i].innerHTML;
   }
   if (j == -1) {
      document.getElementById('hd_sp_bs' + cnt_data1s).value = 0;
      document.getElementById('sp_cs' + cnt_data1s).innerHTML = '-';
   }
   else {
      document.getElementById('hd_sp_bs' + cnt_data1s).value = srcList_mon[j].value;
      document.getElementById('sp_cs' + cnt_data1s).innerHTML = srcList_mon[j].innerHTML;
   }

   document.getElementById('hd_sp_cs' + cnt_data1s).value = main_cat_id;
   // document.getElementById('hd_sp_ds'+cnt_data1s).value=cat;
   // document.getElementById('hd_sp_es'+cnt_data1s).value=subcat;
   document.getElementById('hd_sp_fs' + cnt_data1s).value = ddl_cas_nature;
   document.getElementById('hd_sp_gs' + cnt_data1s).value = parseInt(document.getElementById('hd_sp_gs' + (cnt_data1s - 1)).value) + 1;

   document.getElementById('hd_befote_nots' + cnt_data1s).value = r_befote_nt;
   if (m_cat == 0 || cat == 0 || subcat == 0 || sub_sub_cat == 0) {
      if (m_cat == 0)
         m_cat_nm = '-';
      if (cat == 0)
         cat_nm = '-';
      if (subcat == 0)
         subcat_nm = '-';
      if (sub_sub_cat == 0)
         sub_sub_cat_nm = '-';
   }
   if (r_befote_nt_name == 'Select')
      r_befote_nt_name = '';
   document.getElementById('sp_ds' + cnt_data1s).innerHTML = '<span style=color:red>' + r_befote_nt_name + '</span><br/>' + m_cat_nm + '<br/>' + cat_nm + "<br/>" + subcat_nm + "<br/>" + sub_sub_cat_nm;
   // document.getElementById('sp_es'+cnt_data1s).innerHTML=cat_nm;
   // document.getElementById('sp_fs'+cnt_data1s).innerHTML=subcat_nm;
   var cs_name = '';
   if (ddl_cas_nature == 'C') {
      cs_name = 'Civil';
   }
   else if (ddl_cas_nature == 'R') {
      cs_name = 'Criminal';
   }
   else if (ddl_cas_nature == 'W') {
      cs_name = 'Writ';
   }
   //                  alert(cs_name);
   if (ddl_cas_nature != '')
      document.getElementById('sp_as' + cnt_data1s).innerHTML = cs_name;
   else
      document.getElementById('sp_as' + cnt_data1s).innerHTML = '-';
}

function get_nat_type() {
   var ddl_cas_nature = document.getElementById('ddl_cas_nature').value;
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }

   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('ddl_mn_cs_ty').innerHTML = xmlhttp.responseText;
         if (rdn_ckk == 1)
            get_heading_type(ddl_cas_nature);
      }
   }
   xmlhttp.open("GET", base_url + "/Listing/roster/nt_type_get?ddl_cas_nature=" + ddl_cas_nature, true);
   xmlhttp.send(null);
}

function get_nat_types() {
   var ddl_cas_nature = document.getElementById('ddl_cas_natures').value;
   var hd_m_f_l_m = $('#hd_m_f_l_m').val();

   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('ddl_mn_cs_tys').innerHTML = xmlhttp.responseText;
         if (hd_m_f_l_m == 1)
            get_heading_types(ddl_cas_nature);
      }
   }
   xmlhttp.open("GET", base_url + "/Listing/roster/nt_type_get?ddl_cas_nature=" + ddl_cas_nature, true);
   xmlhttp.send(null);
}

function get_heading_type(ddl_cas_nature) {
   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('srcList_mon').innerHTML = xmlhttp.responseText;

      }
   }
   xmlhttp.open("GET", base_url + "/Listing/Roster/get_heading_type?ddl_cas_nature=" + ddl_cas_nature, true);
   xmlhttp.send(null);
}

function get_heading_types(ddl_cas_nature) {
   var xmlhttp;
   if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
   }
   else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }

   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         document.getElementById('srcList_mons').innerHTML = xmlhttp.responseText;

      }
   }
   xmlhttp.open("GET", base_url + "/Listing/Roster/get_heading_type?ddl_cas_nature=" + ddl_cas_nature, true);
   xmlhttp.send(null);
}

async function save_ex_party_sd() {
   var abcd = 0;
   var ex_c = 0;
   var ckk_st = 0;
   var rdn_ckk = '';
   if (document.getElementById('rdn_motion').checked == true) {
      rdn_ckk = document.getElementById('rdn_motion').value;
   }
   else if (document.getElementById('rdn_final').checked == true) {
      rdn_ckk = document.getElementById('rdn_final').value;
   }
   else if (document.getElementById('rdn_lok').checked == true) {
      rdn_ckk = document.getElementById('rdn_lok').value;
   }
   else if (document.getElementById('rdn_med').checked == true) {
      rdn_ckk = document.getElementById('rdn_med').value;
   }

   var hd_tb_new_mo = document.getElementById('hd_tootal').value;
   for (var z = 1; z < hd_tb_new_mo; z++) {
      if (document.getElementById('hd_chk_adds' + z)) {
         if (document.getElementById('hd_chk_adds' + z).checked == true) {
            ckk_st = 1;
            abcd++;
         }
      }
   }

   if (ckk_st == 0) {
      alert("Please check atleast one field");
   }
   else {
      $('#page_loader').show();
      var hd_ros_id = document.getElementById('hd_ros_id').value;

      for (var zi = 1; zi < hd_tb_new_mo; zi++) {
         if (document.getElementById('hd_chk_adds' + zi)) {
            if (document.getElementById('hd_chk_adds' + zi).checked == true) {
               ex_c++;
               var hd_sp_a = document.getElementById('hd_sp_as' + zi).value;
               var hd_sp_b = document.getElementById('hd_sp_bs' + zi).value;
               var hd_sp_c = document.getElementById('hd_sp_cs' + zi).value;
               var hd_sp_f = document.getElementById('hd_sp_fs' + zi).value;
               var hd_sp_g = document.getElementById('hd_sp_gs' + zi).value;
               var hd_befote_not = document.getElementById('hd_befote_nots' + zi).value;

               var xmlhttp;
               if (window.XMLHttpRequest) {
                  $('#page_loader').show();
                  xmlhttp = new XMLHttpRequest();
               }
               else {
                  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
               }

               if (abcd == ex_c) {
                  xmlhttp.onreadystatechange = function () {
                     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById('dv_rss').innerHTML = xmlhttp.responseText;
                        var hd_mn_no = document.getElementById('hd_mn_no').value;
                        if (hd_mn_no == 0) {
                           alert("Data Not Inserted");
                        }
                        else {
                           alert("Data Inserted Successfully");
                           get_rosterDetails();   
                           // setTimeout(function() { 
                              var hd_hd_show_hide_dt = $('#hd_hd_show_hide_dt_s').val();
                              $('#sp_close').click();
                              closeData();                              
                           // }, 3000);

                           // setTimeout(function() {
                              show_hide_dt(hd_hd_show_hide_dt);
                           // }, 8000);
                        }

                     }
                  }
               }
               xmlhttp.open("GET", "save_mot_det?hd_ros_id=" + hd_ros_id + "&hd_sp_a=" + hd_sp_a
                  + "&hd_sp_b=" + hd_sp_b + "&hd_sp_c=" + hd_sp_c + "&hd_sp_f=" + hd_sp_f
                  + "&hd_sp_g=" + hd_sp_g + '&hd_befote_not=' + hd_befote_not, true);
               xmlhttp.send(null);
            }
         }
      }
      $('#page_loader').hide();
   }

}

//function show_hide_dt(str)
// {
//  // alert("kjkjkkjkjkjkj"+str);
//  $('#hd_hd_show_hide_dt_s').val(str);
//  // document.getElementById('hd_hd_show_hide_dt_s').value=str;
// // alert($('#hd_hd_show_hide_dt_s').val());
//  //alert(document.getElementById('hd_hd_show_hide_dt_s').value);
//   var str1=str.split('_');
//   if($('#tb_cat_all_'+str1[1]).css('display')=='none')
//        {
//  $(".cp_spcatall1").hide();
// $('#tb_cat_all_'+str1[1]).show('2000');
//   
//        }
//    else 
//         $(".cp_spcatall1").hide();
// }

async function show_hide_dt(str) {
   var sp_spcatall = str.split('spcatall_');
   var hd_roster_id = $('#hd_roster_id' + sp_spcatall[1]).val();
   var sp_sp_spcatall = sp_spcatall[1];
   await updateCSRFTokenSync();
   var CSRF_TOKEN = 'CSRF_TOKEN';
   var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
   $.ajax
      ({
         type: "POST",
         url: base_url + "/Listing/roster/get_categories",
         cache: false,
         data: { hd_roster_id: hd_roster_id, sp_sp_spcatall: sp_sp_spcatall, CSRF_TOKEN: CSRF_TOKEN_VALUE },
         success: function (data1) {
            updateCSRFTokenSync();
            $(".cp_spcatall1").html('')
            $('#tb_cat_all_' + sp_spcatall[1]).html(data1);

            $('#hd_hd_show_hide_dt_s').val(str);

            var str1 = str.split('_');
            if ($('#tb_cat_all_' + sp_spcatall[1]).css('display') == 'none') {
               $(".cp_spcatall1").hide();
               //    $(".cp_spcatall1").html('');
               $('#tb_cat_all_' + sp_spcatall[1]).show('2000');

            }
            else
               $(".cp_spcatall1").hide();
               updateCSRFTokenSync();
         }
      });

}

function set_min(str, idd) {

   var sp_dt = idd.split('ddl_hrs')
   if (str == '') {

      $('#ddl_am_pm' + sp_dt[1]).val('');
      $('#ddl_am_pm' + sp_dt[1]).attr('disabled', true);
      $('#ddl_min' + sp_dt[1]).attr('disabled', true);
      $('#ddl_min' + sp_dt[1]).val('');


   }
   else {
      $('#ddl_min' + sp_dt[1]).attr('disabled', false);
      $('#ddl_am_pm' + sp_dt[1]).attr('disabled', false);
   }
   var ddl_min = $('#ddl_min' + sp_dt[1]).val();
   if (ddl_min == '' && str != '')
      $('#ddl_min' + sp_dt[1]).val('00');

   if (str >= 9 && str <= 11) {
      $('#ddl_am_pm' + sp_dt[1]).val('AM');
   }
   else {
      if (str != '')
         $('#ddl_am_pm' + sp_dt[1]).val('PM');
   }
}

function set_min_s(str) {
   if (str == '') {

      $('#ddl_am_pms').val('');
      $('#ddl_am_pms').attr('disabled', true);
      $('#ddl_mins').attr('disabled', true);
      $('#ddl_mins').val('');


   }
   else {
      $('#ddl_mins').attr('disabled', false);
      $('#ddl_am_pms').attr('disabled', false);
   }
   var ddl_min = $('#ddl_mins').val();
   if (ddl_min == '' && str != '')
      $('#ddl_mins').val('00');

   if (str >= 9 && str <= 11) {
      $('#ddl_am_pms').val('AM');
   }
   else {
      if (str != '')
         $('#ddl_am_pms').val('PM');
   }
}

function OnlyNumbersTalwana(event, str) {
   var key;
   if (window.event) {
      key = event.keyCode;
   }
   else if (event.which) {
      key = event.which;
   }

   var ln_val = $('#' + str).val().length;
   //alert(ln_val);

   if ((key >= 48 && key <= 57) || key == 8 || ((key == 77 || key == 109) && ln_val == 0) || ((key == 80 || key == 112) && ln_val == 1)) {

      return true;
   }
   else if (key == undefined) {
      //  alert("Anshul");
      return true;
   }
   else {


      return false;

   }

}

$(document).on("focus",".dtps",function(){
   $('.dtps').datepicker({dateFormat: 'dd-mm-yyyy', changeMonth : true,changeYear  : true,yearRange : '1950:2050'
   });
});

$(document).ready(function() {
   $('.dtpn').datepicker({
       format: 'dd-mm-yyyy',
       todayHighlight: true,
       autoclose: true,
       changeMonth: true,
       changeYear: true,
       yearRange: '1950:2050'

   });
});

var cnt_rows = 3;
function ad_textbox() { 
   var from_dt1 = $('#from_dt1').val();
   if(from_dt1 == '')
   {
      alert('Please Select Effected From Date first.')
      $('#from_dt1').focus();
      return false;
   }
   var table = document.getElementById('tb_nms');
   //    var rowcount=table.rows.length;
   var rowcount = cnt_rows + 1;
   cnt_rows = cnt_rows + 1;
   var row1 = table.insertRow(rowcount);

   var cell1 = row1.insertCell(0);
   cell1.setAttribute("class", "border-side-bottom");
   var dv_cr = document.createElement('div');
   //    dv_cr.setAttribute("style", 'margin-top:20px');
   var hd_from_dt = $('#hd_from_dt').val();
   hd_from_dt = parseInt(hd_from_dt) + 1;
   row1.id = "row_del_add" + hd_from_dt;
   var f_d = document.createElement('input');
   f_d.type = 'text';
   f_d.name = "from_dt" + hd_from_dt;
   f_d.id = "from_dt" + hd_from_dt;
   f_d.setAttribute("class", "dtp");
   f_d.setAttribute("maxsize", "10");
   f_d.setAttribute("size", "9");
   //    var dv_add_dts=document.getElementById('dv_add_dts');
   //    dv_add_dts.appendChild(f_d);
   dv_cr.appendChild(f_d);
   $('#dv_add_dts').append(dv_cr);
   cell1.appendChild(dv_cr);

   var cell2 = row1.insertCell(1);
   cell2.setAttribute("class", "border-side-bottom");
   var dv_cr1 = document.createElement('div');
   //    dv_cr1.setAttribute("style", 'margin-top:20px');

   var f_d1 = document.createElement('select');
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   theOptions.setAttribute("disabled", "true");
   f_d1.appendChild(theOptions);







   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Whole Day");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "Whole Day");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Before Lunch");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "Before Lunch");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After Lunch");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After Lunch");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After Regular Bench");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After Regular Bench");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After DB");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After DB");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After SPL. DB");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After SPL. DB");
   f_d1.appendChild(theOptions);

   f_d1.name = "sess" + hd_from_dt;
   f_d1.id = "sess" + hd_from_dt;

   dv_cr1.appendChild(f_d1);
   $('#dv_add_ses').append(dv_cr1);
   cell2.appendChild(dv_cr1);

   var cell3 = row1.insertCell(2);
   //     cell3.setAttribs("colspan", '3');
   cell3.setAttribute("class", "border-side-bottom");
   cell3.id = "cell_" + hd_from_dt;
   $('#cell_' + hd_from_dt).attr("colspan", "2");
   var dv_cr2 = document.createElement('div');
   //    dv_cr2.setAttribute("style", 'margin-top:20px');

   var f_d2 = document.createElement('select');
   f_d2.setAttribute("onchange", "set_min(this.value,this.id)");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   theOptions.setAttribute("disabled", "true");
   f_d2.appendChild(theOptions);

   for (var i = 1; i <= 12; i++) {
      var theOptions = document.createElement("OPTION");
      var theTexts = document.createTextNode(i);
      theOptions.appendChild(theTexts);
      theOptions.setAttribute("value", i);
      f_d2.appendChild(theOptions);
   }

   f_d2.name = "ddl_hrs" + hd_from_dt;
   f_d2.id = "ddl_hrs" + hd_from_dt;
   f_d2.setAttribute("class", "form-control")
   dv_cr2.appendChild(f_d2);
   var dv_cr3 = document.createElement('b');
   dv_cr3.innerHTML = '&nbsp;&nbsp;:&nbsp;&nbsp;';
   dv_cr2.innerHTML += '&nbsp;';
   dv_cr2.appendChild(dv_cr3);
   dv_cr2.innerHTML += '&nbsp;';

   var f_d3 = document.createElement('select');
   f_d3.setAttribute("disabled", "true");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   theOptions.setAttribute("disabled", "true");
   f_d3.appendChild(theOptions);

   for (var j = 0; j <= 60; j++) {

      if (j.toString().length == 1) {
         j = '0' + j;

      }

      var theOptions = document.createElement("OPTION");
      var theTexts = document.createTextNode(j);
      theOptions.appendChild(theTexts);
      theOptions.setAttribute("value", j);
      f_d3.appendChild(theOptions);
   }


   f_d3.name = "ddl_min" + hd_from_dt;
   f_d3.id = "ddl_min" + hd_from_dt;
   f_d3.setAttribute("class", "form-control")
   dv_cr2.appendChild(f_d3);


   var f_d4 = document.createElement('select');
   f_d4.setAttribute("disabled", "true");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   theOptions.setAttribute("disabled", "true");
   f_d4.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("AM");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "AM");
   f_d4.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("PM");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "PM");
   f_d4.appendChild(theOptions);
   dv_cr2.innerHTML += '&nbsp;';

   f_d4.name = "ddl_am_pm" + hd_from_dt;
   f_d4.id = "ddl_am_pm" + hd_from_dt;
   f_d4.setAttribute("class", "form-control")
   dv_cr2.appendChild(f_d4);

   $('#dv_timing').append(dv_cr2);
   cell3.appendChild(dv_cr2);


   var cell4 = row1.insertCell(3);
   var dv_cr5 = document.createElement('div');
   //    dv_cr5.setAttribute("style", 'margin-top:20px');
   cell4.setAttribute("class", "border-side-bottom");
   var f_d5 = document.createElement('input');
   f_d5.type = 'text';
   f_d5.name = "txt_no_case" + hd_from_dt;
   f_d5.id = "txt_no_case" + hd_from_dt;
   f_d5.setAttribute("onkeypress", "return OnlyNumbersTalwana(event,this.id)");
   f_d5.setAttribute("maxlength", "4");
   f_d5.setAttribute("size", "4");
   f_d5.setAttribute("class", "form-control")
   //    var dv_add_dts=document.getElementById('dv_add_dts');
   //    dv_add_dts.appendChild(f_d);
   dv_cr5.appendChild(f_d5);
   $('#dv_txt_no_case').append(dv_cr5);
   cell4.appendChild(dv_cr5);

   var cell5 = row1.insertCell(4);
   var dv_cr6 = document.createElement('div');
   //    dv_cr6.setAttribute("style", 'margin-top:20px');

   var f_d6 = document.createElement('span');
   f_d6.innerHTML = 'Delete';
   f_d6.id = "sp_delete" + hd_from_dt;
   f_d6.setAttribute("class", "del_rec btn-out-dark")
   f_d6.setAttribute("onclick", "del_rec(this.id)");
   dv_cr6.appendChild(f_d6);

   $('#dv_delete').append(dv_cr6);
   cell5.appendChild(dv_cr6);

   row1.appendChild(cell1);
   row1.appendChild(cell2);
   row1.appendChild(cell3);
   row1.appendChild(cell4);
   row1.appendChild(cell5);
   $('#hd_from_dt').val(hd_from_dt);


   var pre_sess = $('#sess' + (hd_from_dt - 1)).val();
   $('#sess' + hd_from_dt).val(pre_sess);

   var pre_ddl_hrs = $('#ddl_hrs' + (hd_from_dt - 1)).val();
   $('#ddl_hrs' + hd_from_dt).val(pre_ddl_hrs);

   var pre_ddl_min = $('#ddl_min' + (hd_from_dt - 1)).val();
   $('#ddl_min' + hd_from_dt).val(pre_ddl_min);
   if (pre_ddl_min != '')
      $('#ddl_min' + hd_from_dt).attr('disabled', false);

   var pre_ddl_am_pm = $('#ddl_am_pm' + (hd_from_dt - 1)).val();
   $('#ddl_am_pm' + hd_from_dt).val(pre_ddl_am_pm);
   if (pre_ddl_am_pm != '')
      $('#ddl_am_pm' + hd_from_dt).attr('disabled', false);

   var pre_txt_no_case = $('#txt_no_case' + (hd_from_dt - 1)).val();
   $('#txt_no_case' + hd_from_dt).val(pre_txt_no_case);

   var pre_from_dt = $('#from_dt' + (hd_from_dt - 1)).val();
   if (pre_from_dt != '') {
      $.ajax({
         url: base_url + "/Listing/Roster/get_next_date",
         type: "GET",
         async: true,
         cache: false,
         data: { pre_from_dt: pre_from_dt },
         success: function (data, status) {

            $('#from_dt' + hd_from_dt).val(data);
         },
         error: function (xhr) {
            alert("Error:" + xhr.status + ' ' + xhr.statusText);
         }
      });
   }
}

function del_rec(str) {
   var str1 = str.split('sp_delete');
   $('#row_del_add' + str1[1]).remove();
   cnt_rows = cnt_rows - 1;
}

function updcases(str) {

   var str1 = str.split('btn_ros_cases');
   //    var btnroster=$('#btnroster_'+str1[1]).val();
   var btnroster = $('#hd_roster_id' + str1[1]).val();
   //     alert(btnroster);
   var ros_cases = $('#txt_court_no' + str1[1]).val();

   $.ajax({
      url: "update_ros_cases",
      type: "GET",
      async: true,
      cache: false,
      data: { btnroster: btnroster, ros_cases: ros_cases },
      success: function (data, status) {
         alert(data);

      },
      error: function (xhr) {
         alert("Error:" + xhr.status + ' ' + xhr.statusText);
      }
   });
}

function updatePrintInBeforeCourt(str) {
   var str1 = str.split('btnPrintInBeforeCourt');
   var rosterId = $('#hd_roster_id' + str1[1]).val();
   var printInBeforeCourt = $('#printInBeforeCourt' + str1[1]).val();
   $.ajax({
      url: "update_print_in_before_court",
      type: "GET",
      async: true,
      cache: false,
      data: { rosterId: rosterId, printInBeforeCourt: printInBeforeCourt },
      success: function (data, status) {
         alert(data);
      },
      error: function (xhr) {
         alert("Error:" + xhr.status + ' ' + xhr.statusText);
      }
   });
}

function updttime(str) {

   var str1 = str.split('btn_ros_time');
   //    var btnroster=$('#btnroster_'+str1[1]).val();
   var btnroster = $('#hd_roster_id' + str1[1]).val();
   //     alert(btnroster);
   var ros_cases = $('#txt_rtime' + str1[1]).val();

   $.ajax({
      url: "update_ros_time",
      type: "GET",
      async: true,
      cache: false,
      data: { btnroster: btnroster, ros_cases: ros_cases },
      success: function (data, status) {
         alert(data);

      },
      error: function (xhr) {
         alert("Error:" + xhr.status + ' ' + xhr.statusText);
      }
   });
}
function extend_records(str) {
   cnt_rowsz = 2;
   var str1 = str.split('_');
   var btnroster = $('#btnroster_' + str1[1]).val();

   var sp_bn_r = $('#sp_bn_r' + str1[1]).html();
   //   alert(sp_bn_r);
   document.getElementById('ggg').style.width = 'auto';
   document.getElementById('ggg').style.height = ' 500px';
   document.getElementById('ggg').style.overflow = 'scroll';

   document.getElementById('ggg').style.marginLeft = '15px';
   document.getElementById('ggg').style.marginRight = '15px';
   document.getElementById('ggg').style.marginBottom = '25px';
   document.getElementById('ggg').style.marginTop = '40px';
   document.getElementById('dv_sh_hd').style.display = 'block';
   document.getElementById('dv_fixedFor_P').style.display = 'block';
   document.getElementById('dv_fixedFor_P').style.marginTop = '3px';
   $.ajax({
      url: "extend_roster",
      type: "GET",
      async: true,
      cache: false,
      beforeSend: function () {
         $('#ggg').html('<table widht="100%" align="center"><tr><td><img src="../../listing/roster/preloader.gif"/></td></tr></table>');
      },
      data: { btnroster: btnroster, sp_bn_r: sp_bn_r },
      success: function (data, status) {
         $('#ggg').html(data);

      },
      error: function (xhr) {
         alert("Error:" + xhr.status + ' ' + xhr.statusText);
      }
   });
}


var cnt_rowsz = 2;
function ad_textboxz() {
   var table = document.getElementById('tb_nmsz');
   //    var rowcount=table.rows.length;
   var rowcount = cnt_rowsz + 1;
   cnt_rowsz = cnt_rowsz + 1;
   var row1 = table.insertRow(rowcount);

   var cell1 = row1.insertCell(0);

   var dv_cr = document.createElement('div');
   //    dv_cr.setAttribute("style", 'margin-top:20px');
   var hd_from_dt = $('#hd_from_dtz').val();
   hd_from_dt = parseInt(hd_from_dt) + 1;
   row1.id = "row_del_addz" + hd_from_dt;
   var f_d = document.createElement('input');
   f_d.type = 'text';
   f_d.name = "from_dtz" + hd_from_dt;
   f_d.id = "from_dtz" + hd_from_dt;
   f_d.setAttribute("class", "dtp");
   f_d.setAttribute("maxsize", "10");
   f_d.setAttribute("size", "9");
   //    var dv_add_dts=document.getElementById('dv_add_dts');
   //    dv_add_dts.appendChild(f_d);
   dv_cr.appendChild(f_d);
   $('#dv_add_dtsz').append(dv_cr);
   cell1.appendChild(dv_cr);

   var cell2 = row1.insertCell(1);
   var dv_cr1 = document.createElement('div');
   //    dv_cr1.setAttribute("style", 'margin-top:20px');

   var f_d1 = document.createElement('select');
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   f_d1.appendChild(theOptions);







   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Whole Day");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "Whole Day");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Before Lunch");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "Before Lunch");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After Lunch");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After Lunch");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After Regular Bench");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After Regular Bench");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After DB");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After DB");
   f_d1.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("After SPL. DB");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "After SPL. DB");
   f_d1.appendChild(theOptions);

   f_d1.name = "sessz" + hd_from_dt;
   f_d1.id = "sessz" + hd_from_dt;

   dv_cr1.appendChild(f_d1);
   $('#dv_add_sesz').append(dv_cr1);
   cell2.appendChild(dv_cr1);

   var cell3 = row1.insertCell(2);
   //     cell3.setAttribs("colspan", '3');
   cell3.id = "cellz_" + hd_from_dt;
   $('#cellz_' + hd_from_dt).attr("colspan", "2");
   var dv_cr2 = document.createElement('div');
   //    dv_cr2.setAttribute("style", 'margin-top:20px');

   var f_d2 = document.createElement('select');
   f_d2.setAttribute("onchange", "set_min(this.value,this.id)");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   f_d2.appendChild(theOptions);

   for (var i = 1; i <= 12; i++) {
      var theOptions = document.createElement("OPTION");
      var theTexts = document.createTextNode(i);
      theOptions.appendChild(theTexts);
      theOptions.setAttribute("value", i);
      f_d2.appendChild(theOptions);
   }

   f_d2.name = "ddl_hrsz" + hd_from_dt;
   f_d2.id = "ddl_hrsz" + hd_from_dt;

   dv_cr2.appendChild(f_d2);
   var dv_cr3 = document.createElement('b');
   dv_cr3.innerHTML = '&nbsp;&nbsp;:&nbsp;&nbsp;';
   dv_cr2.innerHTML += '&nbsp;';
   dv_cr2.appendChild(dv_cr3);
   dv_cr2.innerHTML += '&nbsp;';

   var f_d3 = document.createElement('select');
   f_d3.setAttribute("disabled", "true");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   f_d3.appendChild(theOptions);

   for (var j = 0; j <= 60; j++) {

      if (j.toString().length == 1) {
         j = '0' + j;

      }

      var theOptions = document.createElement("OPTION");
      var theTexts = document.createTextNode(j);
      theOptions.appendChild(theTexts);
      theOptions.setAttribute("value", j);
      f_d3.appendChild(theOptions);
   }


   f_d3.name = "ddl_minz" + hd_from_dt;
   f_d3.id = "ddl_minz" + hd_from_dt;

   dv_cr2.appendChild(f_d3);


   var f_d4 = document.createElement('select');
   f_d4.setAttribute("disabled", "true");
   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("Select");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "");
   f_d4.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("AM");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "AM");
   f_d4.appendChild(theOptions);

   var theOptions = document.createElement("OPTION");
   var theTexts = document.createTextNode("PM");
   theOptions.appendChild(theTexts);
   theOptions.setAttribute("value", "PM");
   f_d4.appendChild(theOptions);
   dv_cr2.innerHTML += '&nbsp;';

   f_d4.name = "ddl_am_pmz" + hd_from_dt;
   f_d4.id = "ddl_am_pmz" + hd_from_dt;
   dv_cr2.appendChild(f_d4);

   $('#dv_timingz').append(dv_cr2);
   cell3.appendChild(dv_cr2);


   var cell4 = row1.insertCell(3);
   var dv_cr5 = document.createElement('div');
   //    dv_cr5.setAttribute("style", 'margin-top:20px');

   var f_d5 = document.createElement('input');
   f_d5.type = 'text';
   f_d5.name = "txt_no_casez" + hd_from_dt;
   f_d5.id = "txt_no_casez" + hd_from_dt;
   f_d5.setAttribute("onkeypress", "return OnlyNumbersTalwana(event,this.id)");
   f_d5.setAttribute("maxlength", "4");
   f_d5.setAttribute("size", "4");
   //    var dv_add_dts=document.getElementById('dv_add_dts');
   //    dv_add_dts.appendChild(f_d);
   dv_cr5.appendChild(f_d5);
   $('#dv_txt_no_casez').append(dv_cr5);
   cell4.appendChild(dv_cr5);

   var cell5 = row1.insertCell(4);
   var dv_cr6 = document.createElement('div');
   //    dv_cr6.setAttribute("style", 'margin-top:20px');

   var f_d6 = document.createElement('span');
   f_d6.innerHTML = 'Delete';
   f_d6.id = "sp_deletez" + hd_from_dt;
   f_d6.setAttribute("class", "del_rec")
   f_d6.setAttribute("onclick", "del_recz(this.id)");
   dv_cr6.appendChild(f_d6);

   $('#dv_deletez').append(dv_cr6);
   cell5.appendChild(dv_cr6);

   row1.appendChild(cell1);
   row1.appendChild(cell2);
   row1.appendChild(cell3);
   row1.appendChild(cell4);
   row1.appendChild(cell5);
   $('#hd_from_dtz').val(hd_from_dt);


   var pre_sess = $('#sessz' + (hd_from_dt - 1)).val();
   $('#sessz' + hd_from_dt).val(pre_sess);

   var pre_ddl_hrs = $('#ddl_hrsz' + (hd_from_dt - 1)).val();
   $('#ddl_hrsz' + hd_from_dt).val(pre_ddl_hrs);

   var pre_ddl_min = $('#ddl_minz' + (hd_from_dt - 1)).val();
   $('#ddl_minz' + hd_from_dt).val(pre_ddl_min);
   if (pre_ddl_min != '')
      $('#ddl_minz' + hd_from_dt).attr('disabled', false);

   var pre_ddl_am_pm = $('#ddl_am_pmz' + (hd_from_dt - 1)).val();
   $('#ddl_am_pmz' + hd_from_dt).val(pre_ddl_am_pm);
   if (pre_ddl_am_pm != '')
      $('#ddl_am_pmz' + hd_from_dt).attr('disabled', false);

   var pre_txt_no_case = $('#txt_no_casez' + (hd_from_dt - 1)).val();
   $('#txt_no_casez' + hd_from_dt).val(pre_txt_no_case);

   var pre_from_dt = $('#from_dtz' + (hd_from_dt - 1)).val();
   if (pre_from_dt != '') {
      $.ajax({
         url: "get_next_date",
         type: "GET",
         async: true,
         cache: false,
         data: { pre_from_dt: pre_from_dt },
         success: function (data, status) {

            $('#from_dtz' + hd_from_dt).val(data);
         },
         error: function (xhr) {
            alert("Error:" + xhr.status + ' ' + xhr.statusText);
         }
      });
   }
}

function del_recz(str) {
   var str1 = str.split('sp_deletez');
   $('#row_del_addz' + str1[1]).remove();
   cnt_rowsz = cnt_rowsz - 1;
}

   
   function save_ext_rec() {
      var hd_ex_r_id = $('#hd_ex_r_id').val();

      var hd_from_dt = $('#hd_from_dtz').val();
      var hd_sp_bn_r = $('#hd_sp_bn_r').val();

      for (var kk = 1; kk <= hd_from_dt; kk++) {

         if ($('#row_del_addz' + kk).length) {
            if ($('#from_dtz' + kk).length)
               var from_dt = $('#from_dtz' + kk).val();

            if ($('#sessz' + kk).length)
               var sess = $('#sessz' + kk).val();

            if (from_dt == '') {
               alert("Please enter Date");
               $('#from_dtz' + kk).focus();
               return false;
            }
            else if (sess == '') {
               alert("Please enter Session");
               $('#sessz' + kk).focus();
               return false;
            }

         }
      }

      var h_m_s_c = '';
      for (var k = 1; k <= hd_from_dt; k++) {

         if ($('#row_del_addz' + k).length) {
            if ($('#from_dtz' + k).length)
               var from_dt = $('#from_dtz' + k).val();

            if ($('#sessz' + k).length)
               var sess = $('#sessz' + k).val();

            if ($('#ddl_hrsz' + k).length)
               var ddl_hrs = $('#ddl_hrsz' + k).val();

            if ($('#ddl_minz' + k).length)
               var ddl_min = $('#ddl_minz' + k).val();

            if ($('#ddl_am_pmz' + k).length)
               var ddl_am_pm = $('#ddl_am_pmz' + k).val();

            if ($('#txt_no_casez' + k).length)
               var txt_no_case = $('#txt_no_casez' + k).val();
            if (h_m_s_c == '')
               h_m_s_c = from_dt + '~' + sess + '~' + ddl_hrs + '~' + ddl_min + '~' + ddl_am_pm + '~' + txt_no_case;
            else
               h_m_s_c = h_m_s_c + '#' + from_dt + '~' + sess + '~' + ddl_hrs + '~' + ddl_min + '~' + ddl_am_pm + '~' + txt_no_case;
         }
      }

      $.ajax({
         url: "save_ext_rec",
         type: "GET",
         async: true,
         cache: false,
         data: { hd_ex_r_id: hd_ex_r_id, h_m_s_c: h_m_s_c, hd_sp_bn_r: hd_sp_bn_r },
         success: function (data, status) {

            $('#dv_ext_s').html(data);
            
            $('#ggg').html('');
            $('#dv_sh_hd').hide();
            $('#dv_fixedFor_P').hide();

            $(".get_roster").html('<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>');
            // setTimeout(function() { get_rosterDetails(); }, 5000);
            callRoster();
            

            // var CSRF_TOKEN = 'CSRF_TOKEN';
            // var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            // $.ajax
            //    ({
            //       type: "POST",
            //       url: base_url + "/Listing/roster/get_roster",
            //       cache: false,
            //       data: { m_f: '0', CSRF_TOKEN: CSRF_TOKEN_VALUE },
            //       success: function (data1) {
            //          updateCSRFTokenSync();
            //          $(".get_roster").html(data1);
            //          //                    closeData();
            //          //                   var hd_hd_show_hide_dt=$('#hd_hd_show_hide_dt_s').val();
            //          //                 
            //          //                    show_hide_dt(hd_hd_show_hide_dt);
            //       }
            //    }).fail(function () {
            //       updateCSRFTokenSync();
            //       alert("ERROR, Please Contact Server Room");
            //    });

         },
         error: function (xhr) {
            alert("Error:" + xhr.status + ' ' + xhr.statusText);
         }
      });
   }

   async function callRoster(v = 0){
      await get_rosterDetails(v);
   }


function get_sub_sub_cat(subjectId, idd) {
   var xmlhttp;
   if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
   }
   else { // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }


   xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
         // var txtAdeshika="txtAdeshika"+rowCount;
         if (idd == 'subcat')
            document.getElementById('sub_sub_cat').innerHTML = xmlhttp.responseText;
         else
            document.getElementById('sub_sub_cats').innerHTML = xmlhttp.responseText;


      }
   }
   // xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
   xmlhttp.open("GET", "get_sub_subcat_ans?subject=" + subjectId, true);
   xmlhttp.send(null);
}


function checkStat() {

   var fon_nm = document.queryCommandValue("FontName");
   //document.execCommand('styleWithCSS', false, null);
   var fon_sz = document.queryCommandValue("FontSize");
   var ital = document.queryCommandState("Italic");
   var bld = document.queryCommandState("Bold");
   var undell = document.queryCommandState("Underline");

   var jc = document.queryCommandState("JustifyCenter");

   var jl = document.queryCommandState("JustifyLeft");
   var jr = document.queryCommandState("JustifyRight");
   var jf = document.queryCommandState("JustifyFull");

   document.getElementById('ddlFS').value = fon_sz;
   if (ital == true)
       document.getElementById('btnItalic').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnItalic').style.backgroundColor = '';

   if (bld == true)
       document.getElementById('btnBold').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnBold').style.backgroundColor = '';

   if (undell == true)
       document.getElementById('btnUnderline').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnUnderline').style.backgroundColor = '';

   if (jc == true)
       document.getElementById('btnJustify').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnJustify').style.backgroundColor = '';

   if (jl == true)
       document.getElementById('btnAliLeft').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnAliLeft').style.backgroundColor = '';

   if (jr == true)
       document.getElementById('btnAliRight').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnAliRight').style.backgroundColor = '';
   if (jf == true)
       document.getElementById('btnFull').style.backgroundColor = '#bbb51f';
   else
       document.getElementById('btnFull').style.backgroundColor = '';
   document.getElementById('ddlFontFamily').value = fon_nm;
   //  alert(document.getElementById('ddlFontFamily').value)    ;  
   //  document.getElementById('ggg').focus();
}
