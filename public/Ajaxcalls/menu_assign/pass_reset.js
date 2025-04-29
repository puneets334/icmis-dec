function onlynumbers(evt) {
  evt = evt ? evt : window.event;
  var charCode = evt.which ? evt.which : evt.keyCode;
  //alert(charCode);
  if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
    return true;
  }
  return false;
}
function limitInputLength(el) {
  if (el.value.length > 9) {
    el.value = el.value.slice(0, 9); // Truncate extra characters
  }
}

function getUserInfo__() {
    var CSRF_TOKEN = "CSRF_TOKEN";
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  $.ajax({
    type: "GET",
    url: base_url + "/MasterManagement/UserManagement/userpass_manage",
    data: { mat: 1, ucode: $("#u_pass_um").val(),CSRF_TOKEN: CSRF_TOKEN_VALUE },
  })
    .done(function (msg) {
      //alert(msg);
      $("#hmm_result").html(msg);
    })
    .fail(function () {
      alert("ERROR, Please Contact Server Room");
    });
}

function resetMyPass_um() {
    var CSRF_TOKEN = "CSRF_TOKEN";
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
  if (confirm("Are You Sure To RESET") == true) {
    $.ajax({
      type: "GET",
      url: base_url + "/MasterManagement/UserManagement/userpass_manage",
      data: { mat: 2, ucode: $("#hd_id_for_userpass_reset").val(),CSRF_TOKEN: CSRF_TOKEN_VALUE },
    })
      .done(function (msg) {
        if (msg == "1") {
          $("#2result").html(
            "<span style='color:green'>PASSWORD RESET SUCCESSFULLY, NOW EMPLOYEE ID IS YOUR PASSWORD</span>"
          );
        } else {
          $("#2result").html(
            "<span style='color:red'>ERROR, " + msg + "</span>"
          );
        }
      })
      .fail(function () {
        alert("ERROR, Please Contact Server Room");
      });
  }
}
