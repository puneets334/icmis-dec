function getItalic() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("Italic", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}
function getBold() {
  document.execCommand("styleWithCSS", false, null);

  document.execCommand("Bold", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}
function getUnderline() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("Underline", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}

/*

              function getFS(str)
            {
              
            
//document.getElementById('hd_f_size').value=str+'pt';
 
 var fontElements = document.getElementsByTagName("font");
    document.execCommand('styleWithCSS', false, null);     

 for (var i = 0, len = fontElements.length; i < len; ++i) {

      if (fontElements[i].size == "7") {
          
         fontElements[i].removeAttribute("style");
           
        }
//           }
    }
    document.execCommand("fontSize", false, '7');
    for (var i = 0, len = fontElements.length; i < len; ++i) {
//       if(document.getSelection())
//           {
      // fontElements[i].style.fontSize = str+"pt";
      if (fontElements[i].size == "7") {
          
         fontElements[i].removeAttribute("style");
            fontElements[i].style.fontSize = str+"pt";
        }
//           }
    }
  
               document.getElementById('noticecontent').focus();
             
            }
*/

function getFS(str) {
  document.execCommand("fontSize", false, str);
  document.getElementById("noticecontent").focus();
}

function get_l_s(str) {
  var fontElements = document.getElementsByTagName("font");
  for (var i = 0, len = fontElements.length; i < len; ++i) {
    if (fontElements[i].size == "7") {
      fontElements[i].removeAttribute("style");
      //            fontElements[i].style.lineHeight = str;
    }
  }
  document.execCommand("fontSize", false, "7");

  for (var i = 0, len = fontElements.length; i < len; ++i) {
    if (fontElements[i].size == "7") {
      fontElements[i].removeAttribute("style");
      fontElements[i].style.lineHeight = str;
    }
  }
}

function jus_cen() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("JustifyCenter", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}
function jus_left() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("JustifyLeft", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}
function jus_right() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("JustifyRight", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}
function jus_full() {
  document.execCommand("styleWithCSS", false, null);
  //              alert( document.getSelection());
  document.execCommand("JustifyFull", false, null);
  document.getElementById("noticecontent").focus();
  checkStat();
}

function get_intent() {
  //
  document.execCommand("indent", false, null);
  document.getElementById("noticecontent").focus();
  //document.execCommand('formatBlock', false, '<h1>');
}

function nb(e) {
  //alert(e);
  //          var quote1 = window.getSelection().focusOffset;
  //               alert(quote1);
  //if (range)
  //  range.insertNode(elementWhichYouWantToAddToContentEditable);
  var key;
  if (window.e) {
    key = e.keyCode;
  } else if (e.which) {
    key = e.which;
  }

  if (e.keyCode == "9") {
    document.getElementById("hd_tab").value =
      "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    document.execCommand(
      "insertHTML",
      false,
      "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" +
        document.getSelection()
    );
    return false;
  } else if (e.which == "102") {
    var sel = document.getSelection();
    document.getElementById("btnFind").value = sel;
  } else if (e.ctrlKey && e.which == "115") {
    //alert("cxcxcxc");
    var prtContent = document.getElementById("noticecontent");
    save_content(
      encodeURIComponent(
        '<style type="text/css" >   @page {size:auto; margin: 345px 70px 100px 175px; }  table { page-break-inside : avoid } .sp_caseno { text-align:center;display:block; }  .dv_c_co_u div,.dv_c_co_u table,.dv_c_co_u th {font-size:17px;} </style>' +
          prtContent.innerHTML
      ),
      1
    );
    return false;
  }
}
var chk_no = "";
function ent_dt(e) {
  var key;
  if (window.e) {
    key = e.keyCode;
  } else if (e.which) {
    key = e.which;
  }
  if (key == "13") {
    //document.execCommand("insertHTML", false, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+ document.getSelection());
    //chk_no=1;
  }
}

function getFonts(str) {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("FontName", false, str);
  //document.execCommand('focus', false ,null);
  document.getElementById("noticecontent").focus();
  //  return false;
  checkStat();
}

function un_ord_bu() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("insertUnorderedList", false, null);

  document.getElementById("noticecontent").focus();

  checkStat();
}

function ord_bu() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("insertOrderedList", false, null);

  document.getElementById("noticecontent").focus();

  checkStat();
}

function get_supScr() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("superscript", false, null);
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

  var insertUnorderedList = document.queryCommandState("insertUnorderedList");
  var insertOrderedList = document.queryCommandState("insertOrderedList");
  document.getElementById("ddlFS").value = fon_sz;
  if (ital == true)
    document.getElementById("btnItalic").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnItalic").style.backgroundColor = "";

  if (bld == true)
    document.getElementById("btnBold").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnBold").style.backgroundColor = "";

  if (undell == true)
    document.getElementById("btnUnderline").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnUnderline").style.backgroundColor = "";

  if (jc == true)
    document.getElementById("btnJustify").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnJustify").style.backgroundColor = "";

  if (jl == true)
    document.getElementById("btnAliLeft").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnAliLeft").style.backgroundColor = "";

  if (jr == true)
    document.getElementById("btnAliRight").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnAliRight").style.backgroundColor = "";
  if (jf == true)
    document.getElementById("btnFull").style.backgroundColor = "#bbb51f";
  else document.getElementById("btnFull").style.backgroundColor = "";

  if (insertUnorderedList == true)
    document.getElementById("insertUnorderedList").style.backgroundColor =
      "#bbb51f";
  else
    document.getElementById("insertUnorderedList").style.backgroundColor = "";

  if (insertOrderedList == true)
    document.getElementById("insertOrderedList").style.backgroundColor =
      "#bbb51f";
  else document.getElementById("insertOrderedList").style.backgroundColor = "";

  document.getElementById("ddlFontFamily").value = fon_nm;
  //  alert(document.getElementById('ddlFontFamily').value)    ;
  //  document.getElementById('noticecontent').focus();
}

function selectal(ff1) {
  //              document.execCommand('selectAll', false ,null);
  //                document.getElementById('noticecontent').focus();
  document.execCommand("selectAll", false, null);
  document.execCommand("insertHTML", false, ff1);
  document.getElementById("noticecontent").focus();
}
function fin_rep() {
  var selectionContents1 = encodeURIComponent(
    document.getElementById("btnFind").value.toString()
  );

  var txtReplace = document.getElementById("txtReplace").value.toString();

  var noticecontent = encodeURIComponent(
    document.getElementById("noticecontent").innerHTML.toString()
  );

  var query = new RegExp(selectionContents1, "gim");

  var ff = noticecontent.replace(query, txtReplace);
  ff1 = decodeURIComponent(ff);

  document.getElementById("noticecontent").focus();
  selectal(ff1);
}

function tb_create() {
  var tb_r_c =
    "<div id='awesomeDiv' style='width:100%'><table width='50%' border='1' style='border-collapse:collapse;text-align:center;margin-left:auto;margin-right:auto;width:50%' draggable='true'  ondragstart='get_drag()'>";
  var tb_row = document.getElementById("tb_row").value;
  var tb_column = document.getElementById("tb_column").value;
  var wt = 100 / tb_column;
  for (var i = 0; i < tb_row; i++) {
    tb_r_c = tb_r_c + "<tr>";
    for (var j = 0; j < tb_column; j++) {
      tb_r_c =
        tb_r_c +
        "<td style='border-collapse:collapse;width:" +
        wt +
        "%;word-wrap: break-word;'></td>";
    }
    tb_r_c = tb_r_c + "</tr>";
  }
  tb_r_c = tb_r_c + "</table></div>";
  document.execCommand("insertHTML", false, tb_r_c);
}
function gt_redo() {
  document.execCommand("styleWithCSS", false, null);
  document.execCommand("undo", false, null);
}
