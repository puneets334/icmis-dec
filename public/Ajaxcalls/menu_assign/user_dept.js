$(document).ready(function () {
  $("#btnMain").click(function () {
    var regABC = new RegExp("^[A-z]+$");
    var uflag = $("#uside_flag").val().trim();
    var udeptname = $("#name_udept").val().trim();

    if (!regABC.test(udeptname)) {
      alert("Please Enter User Department Name in Alphabets Only");
      $("#name_udept").focus();
      return false;
    }
    if (!regABC.test(uflag)) {
      alert("Please Enter Userside Flag in Alphabets Only");
      $("#uside_flag").focus();
      return false;
    }
    if ($("#user_mapping").val() == "") {
      alert("Please Select Atleast one User Section to be Bound");
      $("#bounded_utype").focus();
      return false;
    }

    $.ajax({
        type: "GET",
        url: base_url + "/MasterManagement/UserManagement/userdept_manage",
      async: false,
      data: {
        mat: 1,
        func: 1,
        name: udeptname,
        flag: uflag,
        butype: $("#user_mapping").val(),
      },
    })
      .done(function (msg) {
        //alert(msg);
        var msg2 = msg.split("~");
        if (msg2[0] == 1) {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "green");
          $(".add_result").html(msg2[1]);
          $("#name_udept").val("");
          $("#uside_flag").val("");
          $("#user_mapping").val("");
          $("#bounded_utype").val("");
          $("#u_type_area").html("");
          $(".add_result").slideUp(3000);
          $.ajax({
            type: "GET",
            url: base_url + "/MasterManagement/UserManagement/userdept_manage",
            data: { mat: 2 },
          })
            .done(function (msg_new) {
              // var msg3 = msg_new.split("<>><<>><><>");
              // $("#result_main").html(msg3[0]);
              // $("#id_udept").val(msg3[1]);
              location.reload();
            })
            .fail(function () {
              alert("ERROR, Please Contact Server Room");
            });
        } else {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "red");
          $(".add_result").html(msg);
        }
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  });

  $("#btnCan").click(function () {
    $("#hd_id_for_userdept").val("");
    $("#name_udept").val("");
    $("#uside_flag").val("");
    $("#user_mapping").val("");
    $("#bounded_utype").val("");
    $("#u_type_area").html("");
    //$("#btnMain").val("Add New");
    //$("#btnMain").attr("onclick","add_userdept()");
    $("#btnUp").css("display", "none");
    $("#btnMain").css("display", "inline");
    $("#btnCan").css("display", "none");
    $(".add_result").slideUp();
    $.ajax({
        type: "GET",
        url: base_url + "/MasterManagement/UserManagement/userdept_manage",
      data: { mat: 2 },
    })
      .done(function (msg_new) {
        // var msg3 = msg_new.split("<>><<>><><>");
        // $("#result_main").html(msg3[0]);
        // $("#id_udept").val(msg3[1]);
        location.reload();
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  });

  $("#btnUp").click(function () {
    var regABC = new RegExp("^[A-z]+$");
    var uflag = $("#uside_flag").val().trim();
    var udeptname = $("#name_udept").val().trim();

    // if (!regABC.test(udeptname)) {
    //   alert("Please Enter User Department Name in Alphabets Only");
    //   $("#name_udept").focus();
    //   return false;
    // }
    // if (!regABC.test(uflag)) {
    //   alert("Please Enter Userside Flag in Alphabets Only");
    //   $("#uside_flag").focus();
    //   return false;
    // }
    if ($("#user_mapping").val() == "") {
      alert("Please Select Atleast one User Section to be Bound");
      $("#bounded_utype").focus();
      return false;
    }

    $.ajax({
        type: "GET",
        url: base_url + "/MasterManagement/UserManagement/userdept_manage",
      async: false,
      data: {
        mat: 1,
        func: 3,
        name: $("#name_udept").val(),
        flag: $("#uside_flag").val(),
        id: $("#hd_id_for_userdept").val(),
        butype: $("#user_mapping").val(),
      },
    })
      .done(function (msg) {
        //alert(msg);
        var msg2 = msg.split("~");
        if (msg2[0] == 1) {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "green");
          $(".add_result").html(msg2[1]);
          $("#name_udept").val("");
          $("#uside_flag").val("");
          $("#user_mapping").val("");
          $("#bounded_utype").val("");
          $("#u_type_area").html("");
          $(".add_result").slideUp(3000);
          //$("#btnMain").val("Add New");
          //$("#btnMain").attr("onclick","add_userdept()");
          $("#btnCan").css("display", "none");
          $("#btnUp").css("display", "none");
          $("#btnMain").css("display", "inline");
          $.ajax({
            type: "GET",
            url: base_url + "/MasterManagement/UserManagement/userdept_manage",
            data: { mat: 2 },
          })
            .done(function (msg_new) {
              location.reload();
              // var msg3 = msg_new.split("<>><<>><><>");
              // $("#result_main").html(msg3[0]);
              // $("#id_udept").val(msg3[1]);
            })
            .fail(function () {
              alert("ERROR, Please Contact Server Room");
            });
        } else {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "red");
          $(".add_result").html(msg);
        }
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  });
});

$(document).on("click", "[id^='btnEdit']", function () {
  var num = this.id.split("btnEdit");
  $.ajax({
    type: "GET",
    url: base_url + "/MasterManagement/UserManagement/userdept_manage",
    data: { mat: 3, id: num[1] },
  })
    .done(function (msg) {
      var msg2 = msg.split("~");
      console.log(msg2);
      $("#id_udept").val(msg2[0]);
      $("#hd_id_for_userdept").val(msg2[0]);
      $("#name_udept").val(msg2[1]);
      $("#uside_flag").val(msg2[2]);
      //$("#btnMain").val("Update");
      //$("#btnMain").attr("onclick","edit_userdept()");
      //$("#btnCan").css("display","inline");
      $("#btnUp").css("display", "inline");
      $("#btnCan").css("display", "inline");
      $("#btnMain").css("display", "none");
      var utype_all = $("#hd_utype_top_" + num[1])
        .val()
        .split(",");

      for (var i = 0; i < utype_all.length; i++) {
        //alert($("#bounded_utype option[value='"+utype_all[i]+"']").text());
        var maked_cont =
          "<div class='user_map' id='u_u_" +
          utype_all[i] +
          "'>" +
          $("#bounded_utype option[value='" + utype_all[i] + "']").text() +
          "\
            <img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='../usermgmt/close-button.gif' onclick=removeCase('" +
          utype_all[i] +
          "')></div>";

        var total = $("#user_mapping").val().split(",");
        var index_val = total.indexOf(utype_all[i]);

        if (index_val < 0) {
          $("#user_mapping").val($("#user_mapping").val() + "," + utype_all[i]);
          $("#u_type_area").append(maked_cont);
        }
      }
    })
    .fail(function () {
      alert("ERROR, Please Contact Server Room");
    });
});

$(document).on("click", "[id^='btnDelete']", function () {
  var num = this.id.split("btnDelete");
  if (confirm("ARE YOU SURE TO REMOVE THIS USER DEPARTMENT") == true) {
    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/userdept_manage",
      data: { mat: 1, func: 2, id: num[1] },
    })
      .done(function (msg) {
        var msg2 = msg.split("~");
        if (msg2[0] == 1) {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "#90C695");
          $(".add_result").html(msg2[1]);
          $("#name_udept").val("");
          $("#uside_flag").val("");
          $("#user_mapping").val("");
          $("#bounded_utype").val("");
          $("#u_type_area").html("");
          $(".add_result").slideUp(3000);
          $.ajax({
            type: "GET",
            url: base_url + "/MasterManagement/UserManagement/userdept_manage",
            data: { mat: 2 },
          })
            .done(function (msg_new) {
              location.reload();
              // var msg3 = msg_new.split("<>><<>><><>");
              // $("#result_main").html(msg3[0]);
              // $("#id_udept").val(msg3[1]);
            })
            .fail(function () {
              alert("ERROR, Please Contact Server Room");
            });
        } else {
          $(".add_result").css("display", "block");
          $(".add_result").css("color", "red");
          $(".add_result").html(msg);
        }
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  }
});

function press_add() {
  var maked_cont =
    "<div class='user_map' id='u_u_" +
    $("#bounded_utype").val() +
    "'>" +
    $("#bounded_utype option:selected").text() +
    "\
<img style='width:7px;height:7px;margin-top:0px;margin-bottom:4px;cursor:pointer' src='../usermgmt/close-button.gif' onclick=removeCase('" +
    $("#bounded_utype").val() +
    "')></div>";

  var total = $("#user_mapping").val().split(",");
  var index_val = total.indexOf($("#bounded_utype").val());

  if (index_val < 0) {
    $("#user_mapping").val(
      $("#user_mapping").val() + "," + $("#bounded_utype").val()
    );
    $("#u_type_area").append(maked_cont);
  }
}

function removeCase(val) {
  var total = $("#user_mapping").val().split(",");
  var index_val = total.indexOf(val);
  total.splice(index_val, 1);
  $("#user_mapping").val(total);
  $("#u_u_" + val).remove();
}
