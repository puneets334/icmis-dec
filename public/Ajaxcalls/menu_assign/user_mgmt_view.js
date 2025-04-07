$(document).ready(function () {
  $("#department").change(function () {
    var val = $("#department").val();
    if (val == "PAPS") $("#for_judge_select").css("display", "block");
    else {
      $("#for_judge_select").css("display", "none");
      $("#judges_id").val("");
    }

    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=1",
      data: {
        deptname: val,
        cur_user_type:
          $(
            "#cur_user_type"
          ).val() /*,auth:$("#authority").val(),auth_name:$("#auth_name").val()*/,
      },
    })
      .done(function (msg) {
        //alert(msg)
        var msgg = msg.indexOf("RESET");
        $("#section").html(msg);
        if (msgg >= 0) {
          if ($("#cur_user_type").val() == "1")
            $("#designation").html("<option value='ALL'>ALL</option>");
          else $("#designation").html("<option value='0'>SELECT</option>");
        } else $("#designation").html("<option value='ALL'>ALL</option>");
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  });

  $("#section").change(function () {
    var val = $("#section").val();
    if ($("#department").val() == "PAPS")
      $("#for_judge_select").css("display", "block");
    else {
      $("#for_judge_select").css("display", "none");
      $("#judges_id").val("");
    }

    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=2",
      data: {
        deptname: $("#department").val(),
        section: val,
        cur_user_type:
          $(
            "#cur_user_type"
          ).val() /*,auth:$("#authority").val(),auth_name:$("#auth_name").val()*/,
      },
    })
      .done(function (msg) {
        //alert(msg);
        $("#designation").html(msg);
      })
      .fail(function () {
        alert("Error, Please Contact Server Room");
      });
  });

  $("#designation").change(function () {
    var value = $("#designation").val();

    if (value != "ALL") {
      $("#for_judge_select").css("display", "none");
      $("#judges_id").val("");
    } else {
      if ($("#department").val() == "PAPS")
        $("#for_judge_select").css("display", "block");
      else $("#for_judge_select").css("display", "none");
    }
  });

  $("#btnShow").click(function () {
    var orderjud = "N";
    var CSRF_TOKEN = "CSRF_TOKEN";
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    if ($("#orderjud")) {
      if ($("#orderjud").is(":checked")) orderjud = "Y";
    }
    var calcda = 0;
    if ($("#calc-da-code").is(":checked")) calcda = 1;

    alert($("#designation").val());
    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/view_user_information",
      beforeSend: function (xhr) {
        $("#result_main_um").html(
          "<div style='margin:0 auto;margin-top:20px;width:25%'><img src='" + base_url + "/images/load.gif'></div>"
        );
      },
      data: {
        /*auth:$("#authority").val(),auth_name:$("#auth_name").val(),auth_sel_name:$("#auth_name option:selected").text(),*/ dept: $(
          "#department"
        ).val(),
        sec: $("#section").val(),
        desg: $("#designation").val(),
        cur_user_type: $("#cur_user_type").val(),
        jud_sel: $("#judges_id").val() /*,view_sta:$("#view_sta").val()*/,
        orderjud: orderjud,
        calcda: calcda,
        CSRF_TOKEN: CSRF_TOKEN_VALUE
      },
    })
      .done(function (msg) {
        $("#result_main_um").html(msg);
      })
      .fail(function () {
        alert("Error, Please Contact Server Room");
      });
  });

  $("#btn_allot").click(function () {
    //alert('dfg');
  });
});

$(document).on("click", "[id^='cl_manage_f']", function () {
  var num = this.id.split("cl_manage_f");
  $("#dv_sh_hd").css("display", "block");
  $("#dv_fixedFor_P").css("display", "block");
  $.ajax({
    type: "GET",
    url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=3",
    beforeSend: function (xhr) {
      $("#sar").html(
        "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
      );
    },
    data: { userid: num[1] },
  })
    .done(function (msg) {
      $("#sar").html(msg);
    })
    .fail(function () {
      $("#sar").html(
        "<div style='margin:0 auto;margin-top:20px;text-align:center'>ERROR, PLEASE CONTACT SERVER ROOM</div>"
      );
    });
});

$(document).on("click", "input[name=add-da-for]", function () {
  var rkds_cst = $("#r_csty").val();
  var rkds_frmyr = $("#r_frmyr").val();
  var rkds_toyr = $("#r_toyr").val();
  var rkds_frmno = $("#r_frmno").val();
  var rkds_tono = $("#r_tono").val();
  var rkds_state = $("#state").val();
  var rkds_c_t = $("#da_c_t").val();

  if (rkds_frmyr == "0000" || rkds_toyr == "0000") {
    alert("From Year or To Year can not zero");
    return false;
  }

  if (rkds_frmno == "" || rkds_frmno == 0) {
    alert("From Number Can not zero or blank");
    $("#r_frmno").focus();
    return false;
  }

  if (rkds_tono == "" || rkds_tono == 0) {
    alert("To Number Can not zero or blank");
    $("#r_tono").focus();
    return false;
  }

  if (isNaN(rkds_frmno)) {
    alert("Entered Value is Not a Number");
    $("#r_frmno").focus();
    return false;
  }

  if (isNaN(rkds_tono)) {
    alert("Entered Value is Not a Number");
    $("#r_tono").focus();
    return false;
  }

  if (rkds_frmyr > rkds_toyr) {
    alert("From Year Could Not Greater than To Year");
    $("#r_frmyr").focus();
    return false;
  }

  if (parseInt(rkds_frmno) > parseInt(rkds_tono)) {
    alert("From No Could Not Greater than To No");
    $("#r_frmno").focus();
    return false;
  }

  if (rkds_state == "") {
    alert("Please Select State");
    $("#state").focus();
    return false;
  }

  if (rkds_c_t == "") {
    alert("Please Select Type");
    $("#da_c_t").focus();
    return false;
  }

  var rkds_id =
    rkds_cst +
    "_" +
    rkds_frmno +
    "_" +
    rkds_frmyr +
    "_" +
    rkds_tono +
    "_" +
    rkds_toyr +
    "_" +
    rkds_state +
    "_" +
    rkds_c_t;
  if (rkds_cst == "") rkds_id = "";

  var maked_cont =
    "<div class='cl_chk_case' id='r_r_" +
    rkds_id +
    "'>" +
    $("#r_csty option:selected").text() +
    "-" +
    rkds_frmno +
    "-" +
    rkds_frmyr +
    "-" +
    rkds_tono +
    "-" +
    rkds_toyr +
    "-" +
    $("#state option:selected").text() +
    "-" +
    $("#da_c_t option:selected").text() +
    "\
<img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='./close-button.gif' onclick=removeCase_rkds('" +
    rkds_id +
    "')></div>";

  var total = $("#rkds_cases").val().split(",");
  var index_val = total.indexOf(rkds_id);

  if (index_val < 0 && rkds_id != "") {
    $("#rkds_cases").val($("#rkds_cases").val() + "," + rkds_id);
    $("#r_type_area").append(maked_cont);
  }
});

$(document).on("click", "input[name=al-rkd-case]", function () {
  var rkds_code = $("#hd_usercode").val();
  $("#sp_close").css("display", "none");
  $.ajax({
    type: "GET",
    url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=17",
    data: { total: $("#rkds_cases").val(), da_code: rkds_code },
  })
    .done(function (msg) {
      //alert(msg);
      $("#rkds_block").html(msg);
      $("#sp_close").css("display", "inline");
      $("#dv_fixedFor_P").css("display", "none");
      $("#dv_sh_hd").css("display", "none");
      $.ajax({
        type: "POST",
        url: "./view_user_information.php",
        beforeSend: function (xhr) {
          $("#result_main_um").html(
            "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
          );
        },
        data: {
          auth: $("#authority").val(),
          auth_name: $("#auth_name").val(),
          dept: $("#department").val(),
          sec: $("#section").val(),
          desg: $("#designation").val(),
          cur_user_type: $("#cur_user_type").val(),
        },
      })
        .done(function (msg) {
          $("#result_main_um").html(msg);
        })
        .fail(function () {
          alert("Error, Please Contact Server Room");
        });
    })
    .fail(function () {
      $("#sp_close").css("display", "inline");
      $("#rkds_block").html("Error, Please Contact Server Room");
    });
});

function authChange(val) {
  if (val == 0) {
    $("#auth_name").html("<option value='0'>SELECT</option>");
    $.ajax({
      type: "GET",
      url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=16",
    //   url: $("#hd_folder").val() + "/user_mgmt_multiple.php?key=16",
      data: { aname: "0" },
    })
      .done(function (msg) {
        $("#department").html(msg);
        $("#section").html("<option value='ALL'>ALL</option>");
        $("#designation").html("<option value='ALL'>ALL</option>");
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
    $("#for_judge_select").css("display", "none");
    $("#judges_id").val("");
  } else {
    $.ajax({
      type: "GET",
      url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=12",
    //   url: $("#hd_folder").val() + "/user_mgmt_multiple.php?key=12",
      data: { auth: val },
    })
      .done(function (msg) {
        $("#auth_name").html(msg);
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  }
}

function authChangeName(val) {
  //if(val == 'ALL'){
  $("#for_judge_select").css("display", "none");
  $("#judges_id").val("");
  //}

  $.ajax({
    type: "GET",
    url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=16",
    // url: $("#hd_folder").val() + "/user_mgmt_multiple.php?key=16",
    data: { auth: $("#authority").val(), aname: val },
  })
    .done(function (msg) {
      //(msg);
      $("#department").html(msg);
      $("#section").html("<option value='ALL'>ALL</option>");
      $("#designation").html("<option value='ALL'>ALL</option>");
    })
    .fail(function () {
      alert("ERROR, Please Contact Server Room");
    });
}

function deptChange(val) {}

function judSetter(value) {}

function secChange(val) {}

function showDetails() {
  var orderjud = "N";
  if ($("#orderjud")) {
    if ($("#orderjud").is(":checked")) orderjud = "Y";
  }
  $.ajax({
    type: "POST",
    url: $("#hd_folder").val() + "/view_user_information.php",
    beforeSend: function (xhr) {
      $("#result_main_um").html(
        "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
      );
    },
    data: {
      auth: $("#authority").val(),
      auth_name: $("#auth_name").val(),
      auth_sel_name: $("#auth_name option:selected").text(),
      dept: $("#department").val(),
      sec: $("#section").val(),
      desg: $("#designation").val(),
      cur_user_type: $("#cur_user_type").val(),
      jud_sel: $("#judges_id").val(),
      view_sta: $("#view_sta").val(),
      orderjud: orderjud,
    },
  })
    .done(function (msg) {
      $("#result_main_um").html(msg);
    })
    .fail(function () {
      alert("Error, Please Contact Server Room");
    });
}

function cl_manage_f(id) {}

$(document).on("click", "#sp_close", function () {
  $("#dv_fixedFor_P").css("display", "none");
  $("#dv_sh_hd").css("display", "none");
});

function toSection(val) {
  if (val > 0) {
    $.ajax({
      type: "GET",
      url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=4",
    //   url: $("#hd_folder").val() + "/user_mgmt_multiple.php?key=4",
      data: {
        deptname: $("#tx_to_dept").html(),
        section: val,
        user: $("#the_user").html(),
      },
    })
      .done(function (msg) {
        $("#txto_designation").html(msg);
      })
      .fail(function () {
        alert("Error, Please Contact Server Room");
      });
  } else {
    $("#txto_designation").html("<option value=0>SELECT</option>");
    $("#cur_position").html("");
    $("#btn_allot").css("display", "block");
  }
}

function to_desig(val) {
  if (val > 0) {
    $.ajax({
      type: "GET",
      url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=5",
    //   url: $("#hd_folder").val() + "/user_mgmt_multiple.php?key=5",
      data: { userid: val },
    })
      .done(function (msg) {
        var msg_bytes = msg.split("~");
        if (msg_bytes[0] == 1) {
          $("#cur_position_value").val("1");
          $("#cur_position").html("<div style='color: green'>OPEN</div>");
          $("#btn_allot").css("display", "block");
        } else if (msg_bytes[0] == 0) {
          $("#cur_position_value").val("0");
          $("#cur_position").html(
            "<span style='color: red'>Allotted to-</span> " +
              msg_bytes[1] +
              ", from date- " +
              msg_bytes[2]
          );
          $("#btn_allot").css("display", "none");
        }
      })
      .fail(function () {
        alert("Error, Please Contact Server Room");
      });
  } else {
    $("#cur_position").html("");
    $("#cur_position_value").val("");
    $("#btn_allot").css("display", "block");
  }
}

function setTransfer(value) {
  if (value == 1) {
    if ($("#curunm").html() == "") {
      $("#allotment_block").css("display", "block");
    } else {
      //$("#transfer_block").css("display","block");
      //alert($("#cur_position_value").val());
      if ($("#cur_position_value").val() == "1") {
        $("#btn_allot").css("display", "block");
      } else if ($("#cur_position_value").val() == "0") {
        $("#btn_allot").css("display", "none");
      } else {
        $("#btn_allot").css("display", "block");
      }
    }
  } else {
    $("#transfer_block").css("display", "none");
    $("#btn_allot").css("display", "block");
    $("#checker_block").css("display", "none");
    $("#allotment_block").css("display", "none");
  }
}

function allotFunction() {
  $("#sp_close").css("display", "none");
  var fil_t_d = "";
  var txdis = $("#transfer_block").css("display");
  if (txdis == "block") {
    fil_t_d = $("#fil_trap_desg").val();
  }

  var user_type = $("#change_user_type").val();

  $.ajax({
    type: "GET",
    url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=6",
    data: {
      status: $("#change_user_stat").val(),
      user: $("#hd_usercode").val(),
      fil_t: fil_t_d,
      user_type:
        user_type /*,dept:$("#tx_to_dept").html(),sec:$("#txto_section").val(),
            desg:$("#txto_designation").val(),user:$("#the_user").html(),uname:$("#curunm").html(),empid:$("#hd_emp_id_for_transfer").val(),
            service:$("#hd_service_for_transfer").val(),utype:$("#usertype_based_on_current_user").val()*/,
    },
  })
    .done(function (msg) {
      //alert(msg);
      $("#sar").html(msg);
      $("#sp_close").css("display", "inline");
      $("#dv_fixedFor_P").css("display", "none");
      $("#dv_sh_hd").css("display", "none");
      $.ajax({
        type: "POST",
        url: "./view_user_information.php",
        beforeSend: function (xhr) {
          $("#result_main_um").html(
            "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
          );
        },
        data: {
          /*auth:$("#authority").val(),auth_name:$("#auth_name").val(),*/ dept: $(
            "#department"
          ).val(),
          sec: $("#section").val(),
          desg: $("#designation").val(),
          cur_user_type: $("#cur_user_type").val(),
        },
      })
        .done(function (msg) {
          $("#result_main_um").html(msg);
        })
        .fail(function () {
          alert("Error, Please Contact Server Room");
        });
    })
    .fail(function () {
      $("#sp_close").css("display", "inline");
      $("#sar").html("ERROR, PLEASE CONTACT SERVER ROOM");
    });
}

function press_add() {
  var maked_cont =
    "<div class='cl_chk_case' id='c_c_" +
    $("#c_csty").val() +
    "'>" +
    $("#c_csty option:selected").text() +
    "\
<img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='./close-button.gif' onclick=removeCase('" +
    $("#c_csty").val() +
    "')></div>";

  var total = $("#checker_cases").val().split(",");
  var index_val = total.indexOf($("#c_csty").val());

  if (index_val < 0) {
    $("#checker_cases").val(
      $("#checker_cases").val() + "," + $("#c_csty").val()
    );
    $("#c_type_area").append(maked_cont);
  }
}

function press_add_rkds() {}

function press_add_rkdcmpda() {
  var maked_cont =
    "<div class='cl_chk_case' id='cmp_cmp_" +
    $("#cmp_csty").val() +
    "'>" +
    $("#cmp_csty option:selected").text() +
    "\
<img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='./usermgmt/close-button.gif' onclick=removeCase_rkdcmpda('" +
    $("#cmp_csty").val() +
    "')></div>";

  var total = $("#rkdcmpda_cases").val().split(",");
  var index_val = total.indexOf($("#cmp_csty").val());

  if (index_val < 0) {
    $("#rkdcmpda_cases").val(
      $("#rkdcmpda_cases").val() + "," + $("#cmp_csty").val()
    );
    $("#cmp_type_area").append(maked_cont);
  }
}

function removeCase(val) {
  var total = $("#checker_cases").val().split(",");
  var index_val = total.indexOf(val);
  total.splice(index_val, 1);
  $("#checker_cases").val(total);
  $("#c_c_" + val).remove();
}

function removeCase_rkds(val) {
  var total = $("#rkds_cases").val().split(",");
  var index_val = total.indexOf(val);
  total.splice(index_val, 1);
  $("#rkds_cases").val(total);
  //alert("#r_r_"+val);
  $("#r_r_" + val).remove();
}

function removeCase_rkdcmpda(val) {
  var total = $("#rkdcmpda_cases").val().split(",");
  var index_val = total.indexOf(val);
  total.splice(index_val, 1);
  $("#rkdcmpda_cases").val(total);
  $("#cmp_cmp_" + val).remove();
}

function allotCase() {
  var chk_code = $("#the_user").html();
  $("#sp_close").css("display", "none");
  $.ajax({
    type: "GET",
    url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=7",
    data: {
      total: $("#checker_cases").val(),
      user: $("#hd_ud").val(),
      chk_code: chk_code,
    },
  })
    .done(function (msg) {
      $("#checker_block").html(msg);
      $("#sp_close").css("display", "inline");
      $("#dv_fixedFor_P").css("display", "none");
      $("#dv_sh_hd").css("display", "none");
      $.ajax({
        type: "GET",
        url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/view_user_information",
        // url: $("#hd_folder").val() + "/view_user_information.php",
        beforeSend: function (xhr) {
          $("#result_main_um").html(
            "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
          );
        },
        data: {
          auth: $("#authority").val(),
          auth_name: $("#auth_name").val(),
          dept: $("#department").val(),
          sec: $("#section").val(),
          desg: $("#designation").val(),
          cur_user_type: $("#cur_user_type").val(),
        },
      })
        .done(function (msg) {
          $("#result_main_um").html(msg);
        })
        .fail(function () {
          alert("Error, Please Contact Server Room");
        });
    })
    .fail(function () {
      $("#sp_close").css("display", "inline");
      $("#checker_block").html("Error, Please Contact Server Room");
    });
}

function allotCase_rkds() {}

function allotCase_rkdcmpda() {
  var rkdcmpda_code = $("#the_user").html();
  //alert($("#rkdcmpda_cases").val());
  //return false;
  $("#sp_close").css("display", "none");
  $.ajax({
    type: "GET",
    url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=18",
    data: {
      total: $("#rkdcmpda_cases").val(),
      user: $("#hd_ud").val(),
      rkdcmpda_code: rkdcmpda_code,
    },
  })
    .done(function (msg) {
      $("#rkdcmpda_block").html(msg);
      $("#sp_close").css("display", "inline");
      $("#dv_fixedFor_P").css("display", "none");
      $("#dv_sh_hd").css("display", "none");
      $.ajax({
        type: "GET",
        url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/view_user_information",
        beforeSend: function (xhr) {
          $("#result_main_um").html(
            "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
          );
        },
        data: {
          auth: $("#authority").val(),
          auth_name: $("#auth_name").val(),
          dept: $("#department").val(),
          sec: $("#section").val(),
          desg: $("#designation").val(),
          cur_user_type: $("#cur_user_type").val(),
        },
      })
        .done(function (msg) {
          $("#result_main_um").html(msg);
        })
        .fail(function () {
          alert("Error, Please Contact Server Room");
        });
    })
    .fail(function () {
      $("#sp_close").css("display", "inline");
      $("#rkdcmpda_block").html("Error, Please Contact Server Room");
    });
}

function getEmpINFO() {
  var radio = "";
  if ($("#rdbtn_h").is(":checked") == true) radio = $("#rdbtn_h").val();
  else radio = $("#rdbtn_l").val();

  $.ajax({
    type: "GET",
    url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=8",
    beforeSend: function () {
      $("#waiting").html(
        "<div style='margin:0 auto;margin-top:20px;width:15%;display: none' ><img src='../images/load.jpg'></div>"
      );
    },
    data: { empid: $("#emp_id").val(), service: radio },
  })
    .done(function (msg) {
      if (msg == "0") {
        $("#waiting").html("No Record Found");
      } else {
        $("#waiting").html("");
        $("#allotment_msg_sure").css("display", "block");
        $("#allotment_yes_no").css("display", "block");
        $("#emp_name_from_db").html(msg);
        $("#emp_id").attr("disabled", "true");
      }
    })
    .fail(function () {
      $("#waiting").html("");
      alert("ERROR, Please Contact Server Room");
    });
}

function emp_ID_clear() {
  $("#allotment_msg_sure").css("display", "none");
  $("#allotment_yes_no").css("display", "none");
  $("#emp_name_from_db").html("");
  $("#emp_id").val("");
  $("#emp_id").removeAttr("disabled");
  $("#waiting").html("");
}

function allotUserToDesg() {
  var radio = "";
  if ($("#rdbtn_h").is(":checked") == true) radio = $("#rdbtn_h").val();
  else radio = $("#rdbtn_l").val();

  $("#sp_close").css("display", "none");
  $.ajax({
    type: "GET",
    url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=9",
    beforeSend: function () {
      $("#waiting").html(
        "<div style='margin:0 auto;margin-top:20px;width:15%;display: none' ><img src='../images/load.jpg'></div>"
      );
    },
    data: {
      empid: $("#emp_id").val(),
      empname: $("#emp_name_from_db").html(),
      user: $("#the_user").html(),
      service: radio,
    },
  })
    .done(function (msg) {
      $("#sp_close").css("display", "inline");
      if (msg == "0") {
        $("#waiting").html(
          "With Given Employee ID an unrelieved User Exists!!!"
        );
      } else {
        $("#waiting").html("User Allotted Successfully");
        $.ajax({
          type: "POST",
          url: $("#hd_folder").val() + "/view_user_information.php",
          beforeSend: function (xhr) {
            $("#result_main_um").html(
              "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
            );
          },
          data: {
            auth: $("#authority").val(),
            auth_name: $("#auth_name").val(),
            dept: $("#department").val(),
            sec: $("#section").val(),
            desg: $("#designation").val(),
            cur_user_type: $("#cur_user_type").val(),
          },
        })
          .done(function (msg) {
            $("#result_main_um").html(msg);
          })
          .fail(function () {
            alert("Error, Please Contact Server Room");
          });
      }
    })
    .fail(function () {
      $("#sp_close").css("display", "inline");
      $("#waiting").html("");
      alert("ERROR, Please Contact Server Room");
    });
}

function relieve_user() {
  if (confirm("Are You Sure to Relieve This User?")) {
    $("#sp_close").css("display", "none");
    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=10",
      data: { user: $("#hd_usercode").val() },
    })
      .done(function (msg) {
        if (msg == "1") alert("User Relieved Successfully!!!");
        else alert("!!!Error Occured!!!");

        $.ajax({
          type: "POST",
          url: "./view_user_information.php",
          beforeSend: function (xhr) {
            $("#result_main_um").html(
              "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
            );
          },
          data: {
            /*auth:$("#authority").val(),auth_name:$("#auth_name").val(),*/ dept: $(
              "#department"
            ).val(),
            sec: $("#section").val(),
            desg: $("#designation").val(),
            cur_user_type: $("#cur_user_type").val(),
          },
        })
          .done(function (msg) {
            $("#result_main_um").html(msg);
          })
          .fail(function () {
            alert("Error, Please Contact Server Room");
          });
      })
      .fail(function () {
        $("#sp_close").css("display", "inline");
        $("#waiting").html("");
        alert("ERROR, Please Contact Server Room");
      });
  }
}

function save_judge_info() {
  if (confirm("Are You Sure to Change/Allot This Judge to This User?")) {
    $("#sp_close").css("display", "none");
    $.ajax({
      type: "GET",
      url: $("#hd_folder").val() + base_url + "/MasterManagement/UserManagement/user_mgmt_multiple?key=11",
      data: {
        user: $("#the_user").html(),
        judge: $("#judge_for_user").val(),
        utype: $("#usertype_based_on_current_user").val(),
      },
    })
      .done(function (msg) {
        //alert(msg);
        if (msg == "1")
          alert("Judge Allotted/Removed To/From User Successfully!!!");
        else alert("!!!Error Occured!!!" + msg);

        $.ajax({
          type: "POST",
          url: $("#hd_folder").val() + "/view_user_information.php",
          beforeSend: function (xhr) {
            $("#result_main_um").html(
              "<div style='margin:0 auto;margin-top:20px;width:15%'><img src='../images/load.jpg'></div>"
            );
          },
          data: {
            auth: $("#authority").val(),
            auth_name: $("#auth_name").val(),
            dept: $("#department").val(),
            sec: $("#section").val(),
            desg: $("#designation").val(),
            cur_user_type: $("#cur_user_type").val(),
          },
        })
          .done(function (msg) {
            $("#result_main_um").html(msg);
          })
          .fail(function () {
            alert("Error, Please Contact Server Room");
          });
      })
      .fail(function () {
        $("#sp_close").css("display", "inline");
        $("#waiting").html("");
        alert("ERROR, Please Contact Server Room");
      });
  }
}

function get_print(strid) {
  var prtContent = document.getElementById(strid);
  var WinPrint = window.open(
    "",
    "",
    "letf=100,top=0,width=800,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1"
  );
  //WinPrint.document.write(prtContent.innerHTML);
  var h =
    "<style>.notfor-print{ display: none; } .for-print #enable-in-print{ display: inline; }</style>";
  WinPrint.document.write(h + prtContent.innerHTML);
  //WinPrint.document.getElementById('dv_vcx').style.width='75%';
  //WinPrint.document.getElementById('mainbtl').style.border='thin solid black';
  WinPrint.document.getElementById("mainbtl").border = "1";
  WinPrint.document.getElementById("mainbtl").style =
    "border-collapse:collapse";
  //WinPrint.document.getElementById('td_mn1').style.marginTop='100px';
  //WinPrint.document.getElementById('td_mn1').style.marginRight='90px';
  //WinPrint.document.getElementById('btnPrint').style.display='none';
  WinPrint.document.close();
  WinPrint.focus();
  WinPrint.print();
}
