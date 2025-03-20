$(document).ready(function () {
  $(document).on("change", "#ddl_nature", function () {
    var ddl_nature = $(this).val();
    //       alert(ddl_nature);
    get_report_type(ddl_nature);
  });

  $(document).on("click", "#sub", function () {
    get_report();
  });
  $(document).on("click", "#btnPrintable", function () {
    save_off_report();
  });

  $(document).on("click", ".cl_chk_cnt_case", function () {
    var idd = $(this).attr("id");
    var sp_idd = idd.split("chk_cnt_case");

    var cnt_checked_case = 0;
    $(".cl_chk_cnt_case").each(function () {
      if ($(this).is(":checked")) {
        cnt_checked_case++;
      }
    });
    if ($(this).is(":checked")) {
      var sp_dname = $("#sp_dname" + sp_idd[1]).html();

      $("#append_data").append(
        '<p id="dvytr_' +
          sp_idd[1] +
          '" style="margin-top:0px;text-align:center;">' +
          sp_dname +
          "</p>"
      );
    } else if ($(this).is(":not(:checked)")) {
      $("#dvytr_" + sp_idd[1]).remove();
    }
  });
  $(document).on("click", "#btn_publish", function () {
    var t_h_cno = $("#t_h_cno").val();
    var t_h_cyt = $("#t_h_cyt").val();
    var hd_or_id = $("#hd_or_id").val();

    var sp_listed_on = $("#ddl_ord_date").val();

    var hd_next_dt = $("#hd_next_dt").val();
    var ddl_rt = $("#ddl_rt").val();
    var connected_case = "";
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $(".cl_chk_cnt_case").each(function () {
      if ($(this).is(":checked")) {
        if (connected_case == "") connected_case = $(this).val();
        else connected_case = connected_case + "," + $(this).val();
      }
    });

    $.ajax({
      url: baseURL + "/Filing/OfficeReport/publish_office_report",
      cache: false,
      async: true,
      data: {
        d_no: t_h_cno,
        d_yr: t_h_cyt,
        hd_or_id: hd_or_id,
        sp_listed_on: sp_listed_on,
        hd_next_dt: hd_next_dt,
        ddl_rt: ddl_rt,
        connected_case: connected_case
      },
      headers: {
        'X-CSRF-Token': CSRF_TOKEN_VALUE  
         },

      type: "POST",
      success: function (data, status) {
        updateCSRFToken();
        if (data == 1) {
          alert("Record Publish Successfully");
        } else if (data == 2) {
          alert("Please save data before Publishing record");
        } else {
          alert("Problem in publishing Record");
        }
      },
      error: function (xhr) {
        updateCSRFToken();
        alert("Error: " + xhr.status + " " + xhr.statusText);
      },
    });
  });
});

function get_report_type(ddl_nature) {
  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  $.ajax({
    url: baseURL + "/Filing/OfficeReport/get_report_type",
    cache: false,
    async: true,
    data: { ddl_nature: ddl_nature },
    headers: {
      'X-CSRF-Token': CSRF_TOKEN_VALUE  
       },
    type: "POST",
    
    success: function (data, status) {
      updateCSRFToken();
      $("#ddl_rt").html(data);
    },
    error: function (xhr) {
      updateCSRFToken();
      alert("Error: " + xhr.status + " " + xhr.statusText);
      
    },
  });
}

function get_report() {
  var t_h_cno = $("#t_h_cno").val();
  var t_h_cyt = $("#t_h_cyt").val();
  // var t_h_cyt=document.getElementById('dyr').value;
  //  alert(t_h_cyt);

  var ddl_rt = $("#ddl_rt").val();
  var ddl_nature = $("#ddl_nature").val();
  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  if (t_h_cno.trim() == "") {
    alert("Please enter Diary No.");
    $("#t_h_cno").focus();
    return false;
  }
  if (ddl_nature.trim() == "") {
    alert("Please Select Nature ");
    $("#ddl_nature").focus();
    return false;
  }
  // condition for nature and type of notice//
  if (ddl_rt.trim() == "") {
    alert("Please Select Type of office report you want to generate");
    $("#ddl_rt").focus();
    return false;
  }
  if (t_h_cyt.trim() == "") {
    alert("Please enter Diary Year");
    $("#t_h_cyt").focus();
    return false;
  }

  $.ajax({
    url: baseURL + "/Filing/OfficeReport/get_office_report",
    //cache: false,
    //async: true,
    data: { d_no: t_h_cno, d_yr: t_h_cyt, ddl_rt: ddl_rt,CSRF_TOKEN: CSRF_TOKEN_VALUE },
    headers: {
      'X-CSRF-Token': CSRF_TOKEN_VALUE  
       },
    beforeSend: function () {
      $("#div_result").html(
        '<div style="margin:0 auto;margin-top:20px;width:15%"><img src="' + base_url + '/images/load.gif"/></div>'
      );
    },
    type: "POST",
    success: function (data) {
      updateCSRFToken();
      $("#div_result").html(data);
      $("#btn_publish").css("display", "inline");
	  setTimeout(function () {
		get_ia(t_h_cno,t_h_cyt);
	   },2000);
    },
    error: function (xhr) {
      updateCSRFToken();
      alert("Error: " + xhr.status + " " + xhr.statusText);
	  setTimeout(function () {
		get_ia(t_h_cno,t_h_cyt);
	   },2000);
    },
  });



  return;
}


function get_ia(t_h_cno,t_h_cyt)
{
	  //alert("hello!!. this is javascript");
 var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  $.ajax({
    url: baseURL + "/Filing/OfficeReport/get_ia",
    cache: false,
    async: true,
    data: { d_no: t_h_cno, d_yr: t_h_cyt,CSRF_TOKEN: CSRF_TOKEN_VALUE },
    headers: {
      'X-CSRF-Token': CSRF_TOKEN_VALUE  
       },
    beforeSend: function () {
      // $('#new_ia').html('<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>');
    },
    type: "POST",
    success: function (data, status) {
      updateCSRFToken();
      $("#tb_docdetails1").html(data);
      // $('#btn_publish').css('display','inline');
    },
    error: function (xhr) {
      updateCSRFToken();
      alert("Error: " + xhr.status + " " + xhr.statusText);
    },
  });
}


function save_off_report() {
  var t_h_cno = $("#t_h_cno").val();
  var t_h_cyt = $("#t_h_cyt").val();
  var hd_or_id = $("#hd_or_id").val();
  //      var sp_listed_on=$('#sp_listed_on').html();
  var sp_listed_on = $("#ddl_ord_date").val();
  var ggg = encodeURIComponent($("#ggg").html());
  var hd_next_dt = $("#hd_next_dt").val();
  var ddl_rt = $("#ddl_rt").val();
  var connected_case = "";
  var summary = $("#summary").val();
  $(".cl_chk_cnt_case").each(function () {
    if ($(this).is(":checked")) {
      if (connected_case == "") connected_case = $(this).val();
      else connected_case = connected_case + "," + $(this).val();
    }
  });
  //                alert(connected_case);
  $.ajax({
    url: "save_office_report.php",
    cache: false,
    async: true,
    data: {
      d_no: t_h_cno,
      d_yr: t_h_cyt,
      hd_or_id: hd_or_id,
      sp_listed_on: sp_listed_on,
      ggg: ggg,
      hd_next_dt: hd_next_dt,
      ddl_rt: ddl_rt,
      connected_case: connected_case,
      summary: summary,
    },

    type: "POST",
    success: function (data, status) {
      //              alert(data);
      $("#chk_status").html(data);
      var hd_chk_status = $("#hd_chk_status").val();

      if (hd_chk_status == "1") alert("Record Save Successfully");
      else if (hd_chk_status == "2") alert("Record Update Successfully");
      //                $('#div_result').html(data);
      var prtContent = document.getElementById("ggg");

      var WinPrint = window.open(
        "",
        "",
        "letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1"
      );
      //  WinPrint.document.write(prtContent.innerHTML);
      // WinPrint.document.write($('#pnt_rec').html())
      WinPrint.document.write(
        '<link rel="stylesheet" href="../css/menu_css.css">' +
          "<style>" +
          $("#pnt_rec").html() +
          "</style>" +
          prtContent.innerHTML
      );
      WinPrint.print();
      get_report();
    },
    error: function (xhr) {
      alert("Error: " + xhr.status + " " + xhr.statusText);
    },
  });
}

function draft_record() {
  var prtContent = document.getElementById("ggg");
  var WinPrint = window.open(
    "",
    "",
    "letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1"
  );

  WinPrint.document.write(prtContent.innerHTML);

  // for(var e=0;e<=hd_tot_po;e++)
  //     {
  // if(WinPrint.document.getElementsByTagName("hr")[e])
  //     WinPrint.document.getElementsByTagName("hr")[e].style.display='none';
  //     }

  WinPrint.document.close();
  WinPrint.focus();
  WinPrint.print();
}
