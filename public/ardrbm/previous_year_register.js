function f() {
  // alert("hello");
  // $('#direct_contempt').hide();
}

class FunctionQueue {
  constructor() {
    this.queue = [];
    this.isProcessing = false;
  }

  add(fn) {
    
    console.log(fn);

    return new Promise((resolve, reject) => {
      this.queue.push({ fn, resolve, reject });
      this.processQueue();
    });
  }

  wait(ms, data) {
      // console.log('Starting task:', data, ms);
      return new Promise(resolve => setTimeout(resolve, ms, data));
  }

  async processQueue() {
    if (this.isProcessing || this.queue.length === 0) return;
    
    this.isProcessing = true;
    const { fn, resolve, reject } = this.queue.shift();
    
    try {
      
      let sleep = await this.wait(100, this.queue.length);

      const result = await fn();
      resolve(result);
    } catch (error) {
      reject(error);
    }
    
    this.isProcessing = false;
    this.processQueue();
  }
}

// Define Process Queue
const process_queue = new FunctionQueue();

async function get_report() {

  await updateCSRFTokenSync();

  var d_no = document.getElementById("diary_number").value;
  var d_yr = document.getElementById("diary_year").value;
  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  // alert('hello');
  $.ajax({
    url: base_url + "/ARDRBM/IA/get_lower_report",
    cache: false,
    async: true,
    data: { d_no: d_no, d_yr: d_yr, old_registration: "Y", CSRF_TOKEN: CSRF_TOKEN_VALUE },
    beforeSend: function () {
      $("#dv_res1").html(
        '<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>'
      );
    },
    type: "POST",
    success: function (data, status) {
      //   alert("success");
      //  $('#direct_contempt').hide();

      $("#dv_res1").html(data);
      var casetype_id = $("#hd_casetype_id").val();
      if (
        casetype_id == "5" ||
        casetype_id == "6" ||
        casetype_id == "17" ||
        casetype_id == "24" ||
        casetype_id == "32" ||
        casetype_id == "33" ||
        casetype_id == "34" ||
        casetype_id == "35" ||
        casetype_id == "40"
      ) {
        //find_and_set_da(d_no,d_yr);
      }
    },
    error: function (xhr) {
      alert("Error: " + xhr.status + " " + xhr.statusText);
    },
  });
  //
}

async function generate_registration() {

    var num = document.getElementById("num").value; // input from the user in case lowercourt details are not updated.

    // alert(num);
    await updateCSRFTokenSync();

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var chk_clk = 0;
    $reg_chk = 0;
    //var casetype_check=$('#casetype').val();
    //var casetype_check=$('#casetype').is(':checked')
    //alert($('#hd_casetype_id').val());
    $case_type = $("#hd_casetype_id").val();
    var hd_casetype_id = $("#hd_casetype_id").val();
    if (!$("#casetype").is(":checked")) {
      alert("Please Check Case Type");
      $("#casetype").focus();
      exit();
    }

    /*    if(!$('#direct').is(':checked')){
             alert("Please accept the confirmation!!");
             $('#direct').focus();
       */

    if (
      hd_casetype_id == "5" ||
      hd_casetype_id == "27" ||
      hd_casetype_id == "20" ||
      hd_casetype_id == "19" ||
      hd_casetype_id == "6" ||
      hd_casetype_id == "17" ||
      hd_casetype_id == "24" ||
      hd_casetype_id == "32" ||
      hd_casetype_id == "33" ||
      hd_casetype_id == "34" ||
      hd_casetype_id == "35" ||
      hd_casetype_id == "40" ||
      hd_casetype_id == "41"
    ) {
      $reg_chk = 1;
    }
    //  alert($case_type);

    if (!$("#regnocount").is(":checked") && $reg_chk == 0) {
      alert("Please Confirm total registration no. to be generated");
      $("#regnocount").focus();
      exit();
    }
    var txt_order_dt = $("#txt_order_dt").val();

    if (txt_order_dt == "") {
      alert("Please enter registeration order date");
      $("#txt_order_dt").focus();
      exit();
    } else {
      //alert(txt_order_dt);
      compareDate(txt_order_dt);
    }
    var reg_for_year = 0;
    if ($("#previous_year").val() == "") {
      alert("Please Select registration year");
      $("#previous_year").focus();
      return;
    } else {
      reg_for_year = $("#previous_year").val();

      var year = txt_order_dt.substring(6, 10);

      if (reg_for_year != year) {
        alert(
          "Registration year and Registration order date year should be same"
        );
        $("#previous_year").focus();
        return;
      }
    }
    var hd_casetype_id = $("#hd_casetype_id").val();
    //      if(hd_casetype_id==7 || hd_casetype_id==8 || hd_casetype_id==9 || hd_casetype_id==10 ||
    //              hd_casetype_id==25 || hd_casetype_id==26 ||  hd_casetype_id==19 || hd_casetype_id==20
    //              ||  hd_casetype_id==11 || hd_casetype_id==12)
    if (
      hd_casetype_id == 7 ||
      hd_casetype_id == 8 ||
      hd_casetype_id == 11 ||
      hd_casetype_id == 12 ||
      hd_casetype_id == 19 ||
      hd_casetype_id == 20
    ) {
      chk_clk = 1;
    } else {
      $(".cl_chk_jug_clnged").each(function () {
        if ($(this).is(":checked")) {
          chk_clk = 1;
        }
      });
    }
    if (chk_clk == 0) {
      alert("Atleast one judgement should be challenged before registration");
    } else {
      var confirmation = confirm("Are you sure you want to register case");

      if (confirmation == false) {
        return false;
      } else {
        $("#btn_generate").attr("disabled", true);
        var d_no = document.getElementById("diary_number").value;
        var d_yr = document.getElementById("diary_year").value;

        var fn_val = "";
        $(".cl_chk_jug_clnged").each(function () {
          var chk_jug_clnged = $(this).attr("id");
          var sp_jug_clnged = chk_jug_clnged.split("chk_jug_clnged");
          var hd_lower_id = $("#hd_lower_id" + sp_jug_clnged[1]).val();
          var ck_chd = "N";
          //       if(hd_casetype_id==7 || hd_casetype_id==8 || hd_casetype_id==9 || hd_casetype_id==10 ||
          //               hd_casetype_id==25 || hd_casetype_id==26 ||  hd_casetype_id==19 || hd_casetype_id==20
          //               ||  hd_casetype_id==11 || hd_casetype_id==12)
          if (
            hd_casetype_id == 7 ||
            hd_casetype_id == 8 ||
            hd_casetype_id == 11 ||
            hd_casetype_id == 12
          ) {
            ck_chd = "Y";
          } else {
            if ($(this).is(":checked")) {
              ck_chd = "Y";
            }
          }
          if (fn_val == "") {
            fn_val = hd_lower_id + "!" + ck_chd;
          } else {
            fn_val = fn_val + "@" + hd_lower_id + "!" + ck_chd;
          }
        });
        // var txt_order_dt = $('#txt_order_dt').val();
        $.ajax({
          url: base_url + "/Judicial/Registration/register_case",
          cache: false,
          async: true,
          data: {
            d_no: d_no,
            d_yr: d_yr,
            fn_val: fn_val,
            hd_casetype_id: hd_casetype_id,
            txt_order_dt: txt_order_dt,
            num: num,
            reg_for_year: reg_for_year,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
          },
          beforeSend: function () {
            $("#dv_load").html(
              '<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>'
            );
          },
          type: "POST",
          success: function (data, status) {
            $("#dv_res1").html(data);
            if (
              hd_casetype_id == 9 ||
              hd_casetype_id == 10 ||
              hd_casetype_id == 19 ||
              hd_casetype_id == 20 ||
              hd_casetype_id == 25 ||
              hd_casetype_id == 26 ||
              hd_casetype_id == 39
            ) {
              process_queue.add(() => call_listing(d_no, d_yr));
              process_queue.add(() => find_and_set_da(d_no, d_yr));

              // call_listing(d_no, d_yr);
              // find_and_set_da(d_no, d_yr);
            } else {
              process_queue.add(() => check_if_listed(d_no, d_yr));
              // check_if_listed(d_no, d_yr);
            }
          },
          error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
          },
        });
      }
    }
  }

async function generate_registration_r() {
    //   alert("thisis btn_generate_r");
    //  var num=document.getElementById('num').value;   // input from the user in case lowercourt details are not updated.

    //alert(num);
    await updateCSRFTokenSync();

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    var chk_clk = 0;
    $reg_chk = 0;
    //var casetype_check=$('#casetype').val();
    //var casetype_check=$('#casetype').is(':checked')
    //alert($('#hd_casetype_id').val());
    $case_type = $("#hd_casetype_id").val();
    var hd_casetype_id = $("#hd_casetype_id").val();
    if (!$("#casetype").is(":checked")) {
      alert("Please Check Case Type");
      $("#casetype").focus();
      exit();
    }

    if (
      hd_casetype_id == "5" ||
      hd_casetype_id == "27" ||
      hd_casetype_id == "20" ||
      hd_casetype_id == "19" ||
      hd_casetype_id == "6" ||
      hd_casetype_id == "17" ||
      hd_casetype_id == "24" ||
      hd_casetype_id == "32" ||
      hd_casetype_id == "33" ||
      hd_casetype_id == "34" ||
      hd_casetype_id == "35" ||
      hd_casetype_id == "40" ||
      hd_casetype_id == "41"
    ) {
      $reg_chk = 1;
    }
    //  alert($case_type);

    if (!$("#regnocount").is(":checked") && $reg_chk == 0) {
      alert("Please Confirm total registration no. to be generated");
      $("#regnocount").focus();
      exit();
    }
    var txt_order_dt = $("#txt_order_dt").val();

    if (txt_order_dt == "") {
      alert("Please enter registeration order date");
      $("#txt_order_dt").focus();
      exit();
    } else {
      //alert(txt_order_dt);
      compareDate(txt_order_dt);
    }
    var reg_for_year = 0;
    if ($("#previous_year").val() == "") {
      alert("Please Select registration year");
      $("#previous_year").focus();
      return;
    } else {
      reg_for_year = $("#previous_year").val();

      var year = txt_order_dt.substring(6, 10);

      if (reg_for_year != year) {
        alert(
          "Registration year and Registration order date year should be same"
        );
        $("#previous_year").focus();
        return;
      }
    }
    //var curdate=new Date(date string);
    // if(txt_order_dt<){

    //}
    var hd_casetype_id = $("#hd_casetype_id").val();
    //      if(hd_casetype_id==7 || hd_casetype_id==8 || hd_casetype_id==9 || hd_casetype_id==10 ||
    //              hd_casetype_id==25 || hd_casetype_id==26 ||  hd_casetype_id==19 || hd_casetype_id==20
    //              ||  hd_casetype_id==11 || hd_casetype_id==12)
    if (
      hd_casetype_id == 7 ||
      hd_casetype_id == 8 ||
      hd_casetype_id == 11 ||
      hd_casetype_id == 12 ||
      hd_casetype_id == 19 ||
      hd_casetype_id == 20
    ) {
      chk_clk = 1;
    } else {
      $(".cl_chk_jug_clnged").each(function () {
        if ($(this).is(":checked")) {
          chk_clk = 1;
        }
      });
    }
    if (chk_clk == 0) {
      alert("Atleast one judgement should be challenged before registration");
    } else {
      var confirmation = confirm("Are you sure you want to register case");

      if (confirmation == false) {
        return false;
      } else {
        $("#btn_generate_r").attr("disabled", true);
        var d_no = document.getElementById("diary_number").value;
        var d_yr = document.getElementById("diary_year").value;

        var fn_val = "";
        $(".cl_chk_jug_clnged").each(function () {
          var chk_jug_clnged = $(this).attr("id");
          var sp_jug_clnged = chk_jug_clnged.split("chk_jug_clnged");
          var hd_lower_id = $("#hd_lower_id" + sp_jug_clnged[1]).val();
          var ck_chd = "N";
          //       if(hd_casetype_id==7 || hd_casetype_id==8 || hd_casetype_id==9 || hd_casetype_id==10 ||
          //               hd_casetype_id==25 || hd_casetype_id==26 ||  hd_casetype_id==19 || hd_casetype_id==20
          //               ||  hd_casetype_id==11 || hd_casetype_id==12)
          if (
            hd_casetype_id == 7 ||
            hd_casetype_id == 8 ||
            hd_casetype_id == 11 ||
            hd_casetype_id == 12
          ) {
            ck_chd = "Y";
          } else {
            if ($(this).is(":checked")) {
              ck_chd = "Y";
            }
          }
          if (fn_val == "") {
            fn_val = hd_lower_id + "!" + ck_chd;
          } else {
            fn_val = fn_val + "@" + hd_lower_id + "!" + ck_chd;
          }
        });
        // var txt_order_dt = $('#txt_order_dt').val();
        $.ajax({
          url: base_url + "/Judicial/Registration/register_case",
          cache: false,
          async: true,
          dataType: 'json',
          data: {
            d_no: d_no,
            d_yr: d_yr,
            fn_val: fn_val,
            hd_casetype_id: hd_casetype_id,
            txt_order_dt: txt_order_dt,
            num: 0,
            reg_for_year: reg_for_year,
            CSRF_TOKEN: CSRF_TOKEN_VALUE
          },
          beforeSend: function () {
            $("#dv_load").html(
              '<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>'
            );
          },
          type: "POST",
          success: function (data, status) {

            // console.log(data);

            $("#dv_res1").html('<div id="result-registration-info" style="text-align: center"></div>');

            if (data.registration != undefined)
              $("#result-registration-info").append("<br /><h3>" + data.registration + "</h3>");

            if (data.track_inserted != undefined)
              $("#result-registration-info").append("<br />" + data.track_inserted);

            if (data.err_msg != undefined)
              $("#result-registration-info").append("<br />" + data.err_msg);


            if (
              hd_casetype_id == 9 ||
              hd_casetype_id == 10 ||
              hd_casetype_id == 19 ||
              hd_casetype_id == 20 ||
              hd_casetype_id == 25 ||
              hd_casetype_id == 26 ||
              hd_casetype_id == 39
            ) {
              process_queue.add(() => call_listing(d_no, d_yr));
              process_queue.add(() => find_and_set_da(d_no, d_yr));
              // call_listing(d_no, d_yr);
              // find_and_set_da(d_no, d_yr);
            } else {
              process_queue.add(() => check_if_listed(d_no, d_yr));
              // check_if_listed(d_no, d_yr);
            }
          },
          error: function (xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
          },
        });
      }
    }
  }

async function generate_registration_s() {
  await updateCSRFTokenSync();

  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  var d_no = document.getElementById("diary_number").value;
  var d_yr = document.getElementById("diary_year").value;
  var hd_casetype_id = $("#hd_casetype_id").val();
  var txt_order_dt = $("#txt_order_dt").val();
  $reg_chk = 0;
  //var casetype_check=$('#casetype').is(':checked')
  //alert($('#casetype').is(':checked'));
  //alert($('#hd_casetype_id').val());
  $case_type = $("#hd_casetype_id").val();
  if (!$("#casetype").is(":checked")) {
    alert("Please Check Case Type");
    $("#casetype").focus();
    exit();
  }

  if (
    hd_casetype_id == "5" ||
    hd_casetype_id == "27" ||
    hd_casetype_id == "6" ||
    hd_casetype_id == "17" ||
    hd_casetype_id == "24" ||
    hd_casetype_id == "32" ||
    hd_casetype_id == "33" ||
    hd_casetype_id == "34" ||
    hd_casetype_id == "35" ||
    hd_casetype_id == "40" ||
    hd_casetype_id == "41"
  ) {
    $reg_chk = 1;
  }
  if (!$("#regnocount").is(":checked") && $reg_chk == 0) {
    alert("Please Confirm total registration no. to be generated");
    $("#regnocount").focus();
    exit();
  }

  if (txt_order_dt == "") {
    alert("Please enter registeration order date");
    $("#txt_order_dt").focus();
    exit();
  } else {
    //alert(txt_order_dt);
    compareDate(txt_order_dt);
  }

  var reg_for_year = 0;

  if ($("#previous_year").val() == "") {
    alert("Please Select registration year");
    $("#previous_year").focus();
    return;
  } else {
    reg_for_year = $("#previous_year").val();

    var year = txt_order_dt.substring(6, 10);

    if (reg_for_year != year) {
      alert(
        "Registration year and Registration order date year should be same"
      );
      $("#previous_year").focus();
      return;
    }
  }
  var confirmation = confirm("Are you sure you want to register case");

  if (confirmation == false) {
    return false;
  } else {
    $("#btn_generate_s").attr("disabled", true);
    $.ajax({
      url: base_url + "/Judicial/Registration/register_case_supreme",
      cache: false,
      async: true,
      data: {
        d_no: d_no,
        d_yr: d_yr,
        hd_casetype_id: hd_casetype_id,
        txt_order_dt: txt_order_dt,
        reg_for_year: reg_for_year,
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
      beforeSend: function () {
        $("#dv_load").html(
          '<table widht="100%" align="center"><tr><td><img src="../images/load.gif"/></td></tr></table>'
        );
      },
      type: "POST",
      dataType: 'json',
      success: function (data, status) {

        // console.log(data);

        $("#dv_res1").html('<div id="result-registration-info" style="text-align: center"></div>');

        if (data.registration != undefined)
          $("#result-registration-info").append("<br /><h3>" + data.registration + "</h3>");

        if (data.track_inserted != undefined)
          $("#result-registration-info").append("<br />" + data.track_inserted);

        if (data.err_msg != undefined)
          $("#result-registration-info").append("<br />" + data.err_msg);

          
        if (
          hd_casetype_id == 9 ||
          hd_casetype_id == 10 ||
          hd_casetype_id == 19 ||
          hd_casetype_id == 20 ||
          hd_casetype_id == 25 ||
          hd_casetype_id == 26 ||
          hd_casetype_id == 39
        ) {
          process_queue.add(() => call_listing(d_no, d_yr));
          process_queue.add(() => find_and_set_da(d_no, d_yr));

          // call_listing(d_no, d_yr);
          // find_and_set_da(d_no, d_yr);
        } else {
          process_queue.add(() => call_listing(d_no, d_yr));
          // check_if_listed(d_no, d_yr);
        }
      },
      error: function (xhr) {
        alert("Error: " + xhr.status + " " + xhr.statusText);
      },
    });
  }
}

async function find_and_set_da(dirno, diryr) {
  
  await updateCSRFTokenSync();

  return new Promise((resolve, reject) => {

  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  //alert("hello");
  $.ajax({
    type: "POST",
    url: base_url + "/Judicial/Registration/get_and_set_da",
    /*beforeSend: function (xhr) {
            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },*/
    data: { dno: dirno, dyr: diryr, CSRF_TOKEN: CSRF_TOKEN_VALUE },
    })
    .done(function (msg) {
      alert(msg);
      //chk_section(dirno,diryr);
      //        call_listing(dirno,diryr);
    })
    .fail(function () {
      alert("ERROR, Please Contact Server Room");
      
      reject(true);
    });

    resolve(true);
  });    
}

async function check_if_listed(dirno, diryr) {

  await updateCSRFTokenSync();

  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  //var d_no=document.getElementById('t_h_cno').value;
  //var d_yr=document.getElementById('diary_year').value;
  //var dno=d_no+d_yr;
  $.ajax({
    type: "POST",
    url: base_url + "/Judicial/Registration/check_listing",
    /*beforeSend: function (xhr) {
         $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
         },*/
    data: { dno: dirno, dyr: diryr, CSRF_TOKEN: CSRF_TOKEN_VALUE },
  })
    .done(function (msg) {
      if (msg == "listed") {
        // alert("da called");
        process_queue.add(() => updateCSRFTokenSync());
        process_queue.add(() => find_and_set_da(dirno, diryr));

        // find_and_set_da(dirno, diryr);
      } else {
        alert(msg);
      }
      // document.getElementById("ggg").style.width = "auto";
      // document.getElementById("ggg").style.height = " 500px";
      // document.getElementById("ggg").style.overflow = "scroll";
      // //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
      // document.getElementById("ggg").style.marginLeft = "50px";
      // document.getElementById("ggg").style.marginRight = "50px";
      // document.getElementById("ggg").style.marginBottom = "25px";
      // document.getElementById("ggg").style.marginTop = "1px";
      //call_prop_s(dirno,diryr);
    })

    .fail(function () {
      alert("ERROR, Please Contact Server Room");
    });
}

async function call_listing(dirno, diryr) {

  await updateCSRFTokenSync();

  return new Promise((resolve, reject) => {

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

    //var d_no=document.getElementById('t_h_cno').value;
    //var d_yr=document.getElementById('diary_year').value;
    //var dno=d_no+d_yr;
    $.ajax({
      type: "POST",
      url: base_url + "/Judicial/Registration/call_listing",
      /*beforeSend: function (xhr) {
              $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
          },*/
      data: { dno: dirno, dyr: diryr, CSRF_TOKEN: CSRF_TOKEN_VALUE },
    })
      .done(function (msg) {
        alert(msg);
        // document.getElementById("ggg").style.width = "auto";
        // document.getElementById("ggg").style.height = " 500px";
        // document.getElementById("ggg").style.overflow = "scroll";
        // //  margin-left: 50px;margin-right: 50px;margin-bottom: 25px;margin-top: 1px;
        // document.getElementById("ggg").style.marginLeft = "50px";
        // document.getElementById("ggg").style.marginRight = "50px";
        // document.getElementById("ggg").style.marginBottom = "25px";
        // document.getElementById("ggg").style.marginTop = "1px";
        
        process_queue.add(() => call_prop_s(dirno, diryr));

        // const someResult = await call_prop_s(dirno, diryr);
      })
      .fail(function () {        
        alert("ERROR, Please Contact Server Room");
        
        reject(true);
      });

    resolve(true);
  });
}

async function call_prop_s(dirno, diryr) {

  await updateCSRFTokenSync();

  return new Promise((resolve, reject) => {

  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  $.ajax({
    type: "POST",
    url: base_url + "/Judicial/Registration/show_proposal",
    /*beforeSend: function (xhr) {
            $("#result1").html("<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.gif'></div>");
        },*/
    data: { dno: dirno, dyr: diryr, CSRF_TOKEN: CSRF_TOKEN_VALUE },
  })
    .done(function (msg) {
      $('#model-show-proposal').modal({ backdrop: 'static', keyboard: false });
      $('#model-show-proposal').modal('show');
      $('#ggg').html(msg);

      //alert(msg);
      // document.getElementById("dv_fixedFor_P").style.marginTop = "50px";
      // document.getElementById("dv_sh_hd").style.display = "block";
      // document.getElementById("dv_fixedFor_P").style.display = "block";
      // //  document.getElementById('sp_mnb_p').style.width=screen.width/2+'px';
      // document.getElementById("ggg").innerHTML = msg;
      /*document.getElementById('tb_clr').style.backgroundColor = 'white';
            if (document.getElementById('tb_clr_n'))
                document.getElementById('tb_clr_n').style.backgroundColor = 'white';*/
    })
    .fail(function () {
      alert("ERROR, Please Contact Server Room");
      
      reject(true);
    });

    resolve(true);
  });
}

function closeData() {
  
  $('#model-show-proposal').modal('hide');

  // document.getElementById("dv_fixedFor_P").style.display = "none";
  // document.getElementById("dv_sh_hd").style.display = "none";
  // document.getElementById("sp_close").style.display = "none";
}

async function chk_section(dirno, diryr) {

  await updateCSRFTokenSync();

  var CSRF_TOKEN = 'CSRF_TOKEN';
  var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

  var d_no = document.getElementById("diary_number").value;
  var d_yr = document.getElementById("diary_year").value;
  $.ajax({
    url: base_url + "/Judicial/Registration/chk_section",
    cache: false,
    async: true,
    data: { d_no: d_no, d_yr: d_yr, CSRF_TOKEN: CSRF_TOKEN_VALUE },
    type: "POST",
    success: function (data, status) {
      if (data == 0) {
        call_listing(dirno, diryr);
      }
    },
    error: function (xhr) {
      alert("Error: " + xhr.status + " " + xhr.statusText);
    },
  });
}

function compareDate(txt_order_dt) {
  //var disposeDate = document.getElementById('hd_disp_dt').value;
  var date = txt_order_dt.substring(0, 2);
  var month = txt_order_dt.substring(3, 5);
  var year = txt_order_dt.substring(6, 10);

  //alert ('order date'+txt_order_dt);
  //alert('disposed date'+ disposeDate);

  var dateToCompare = new Date(year, month - 1, date);
  var currentDate = new Date();

  if (dateToCompare > currentDate) {
    alert("Registration Order date cannot be greater than Today's Date ");
    $("#txt_order_dt").focus();
    exit();
  }
  /*if(disposeDate!=''){
        var date = disposeDate.substring(0, 2);
        var month = disposeDate.substring(3, 5);
        var year = disposeDate.substring(6, 10);
        var disposeDate = new Date(year, month - 1, date);
//alert ('order date2'+dateToCompare);
//alert('disposed date2'+ disposeDate);

        if (dateToCompare > disposeDate) {
            alert("Registration Order date cannot be greater than matter disposal Date ");
            $('#txt_order_dt').focus();
            exit();
        }
    }*/
}
