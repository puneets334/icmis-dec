//Function Autocomplete start pet post------------------------------------------------

$(function() {
    $("#pet_post").autocomplete({
        source: "new_filing_autocomp_post.php",
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("change","#ddl_court, #ddl_st_agncy, #ddl_bench",function(){
    var changeData = $(this).val();
    var formData = {};
    formData.changeData= changeData;
    if(changeData){
        $.ajax({
            url: 'diary_entry_session.php',
            type: 'POST',
            cache: false,
            async: false,
            data: JSON.stringify(formData),
            dataType:'json',
            contentType: 'application/json',
            success : function(res) {
                if(res){
                    console.log(res);
                }
            }

        });
    }
});


$(document).on("keyup","#padvno",function(){
    //alert($('#padvt').val());
    if($('#padvt').val()=='A') {
        $("#padvno").autocomplete({
            source: "../loosedoc/get_aor_name.php",
            width: 450,
            matchContains: true,
            minLength: 1,
            selectFirst: true,
            autoFocus: false,
            select: function (event, ui) {
                //event.preventDefault();
                // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
                getAdvocate_for_main($('#padvt'),'P');
            },
            focus: function (event, ui) {
                //event.preventDefault();
                // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
                getAdvocate_for_main($('#padvt'),'P');
            }
        });
    }
    else
        getAdvocate_for_main(this.id,'P');
});
$(document).on("keyup","#radvno",function(){
    if($('#padvt').val()=='A') {
    $("#radvno").autocomplete({
        source: "../loosedoc/get_aor_name.php",
        width: 450,
        matchContains: true,
        minLength: 1,
        selectFirst: true,
        autoFocus: false,
        select: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#radvt'),'R');
        },
        focus: function(event, ui) {
            //event.preventDefault();
            // $("#padvname").val( (ui.item.label).substring((ui.item.label).indexOf("-")+1)  );
            getAdvocate_for_main($('#radvt'),'R');
        }
    });
    }
    else
        getAdvocate_for_main(this.id,'R');
});
$(document).on("blur","#pet_post",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_post").val(htht[1]);
        $("#pet_post_code").val(htht[0]);
    }
});


$(function() {
    $("#pet_statename").autocomplete({
        source: "../addentry/get_only_state_name.php",
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("blur","#pet_statename",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_statename").val(htht[1]);
        $("#pet_statename_hd").val(htht[0]);
    }
});


$(function() {
    $("#res_statename").autocomplete({
        source: "../addentry/get_only_state_name.php",
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("blur","#res_statename",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_statename").val(htht[1]);
        $("#res_statename_hd").val(htht[0]);
    }
});

//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet deptt------------------------------------------------
/*$(function() {
 $("#pet_deptt").autocomplete({
 source: "new_filing_autocomp_deptt.php",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });*/

$(document).on("focus","#pet_deptt",function(){
    $("#pet_deptt").autocomplete({
        source:"new_filing_autocomp_deptt.php?type="+$("#selpt").val(),
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("blur","#pet_deptt",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#pet_deptt").val(htht[1]);
        $("#pet_deptt_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet post------------------------------------------------
$(function() {
    $("#res_post").autocomplete({
        source: "new_filing_autocomp_post.php",
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("blur","#res_post",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_post").val(htht[1]);
        $("#res_post_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet deptt------------------------------------------------
/*$(function() {
 $("#res_deptt").autocomplete({
 source: "new_filing_autocomp_deptt.php",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });*/

$(document).on("focus","#res_deptt",function(){
    $("#res_deptt").autocomplete({
        source:"new_filing_autocomp_deptt.php?type="+$("#selrt").val(),
        width: 450,
        matchContains: true,
        minChars: 1,
        selectFirst: false,
        autoFocus: true
    });
});

$(document).on("blur","#res_deptt",function(){
    if(this.value.indexOf('~') != '-1'){
        var htht = this.value.split('~');
        $("#res_deptt").val(htht[1]);
        $("#res_deptt_code").val(htht[0]);
    }
});
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet address------------------------------------------------
/*$(function() {
 $("#paddi").autocomplete({
 source: "new_filing_ac.php?ctrl=a",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });
 $(function() {
 $("#paddd").autocomplete({
 source: "new_filing_ac.php?ctrl=a",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });*/
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start pet place/city------------------------------------------------
/*$(function() {
 $("#pcityi").autocomplete({
 source: "new_filing_ac.php?ctrl=p",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });
 $(function() {
 $("#pcityd").autocomplete({
 source: "new_filing_ac.php?ctrl=p",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });*/
//Function Autocomplete end--------------------------------------------------

//Function Autocomplete start res address------------------------------------------------
/*$(function() {
 $("#raddi").autocomplete({
 source: "new_filing_ac.php?ctrl=a",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });
 $(function() {
 $("#raddd").autocomplete({
 source: "new_filing_ac.php?ctrl=a",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });
 //Function Autocomplete end--------------------------------------------------

 //Function Autocomplete start res place/city------------------------------------------------
 $(function() {
 $("#rcityi").autocomplete({
 source: "new_filing_ac.php?ctrl=p",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });
 $(function() {
 $("#rcityd").autocomplete({
 source: "new_filing_ac.php?ctrl=p",
 width: 450,
 matchContains: true,
 minChars: 1,
 selectFirst: false,
 autoFocus: true
 });
 });*/
//Function Autocomplete end--------------------------------------------------


function setCountry_state_dis(id,value){
    var string1 = id.split('_cont');
    if(value != "96"){
        $("#sel"+string1[0]+'st'+string1[1]).prop("disabled",true);
        $("#sel"+string1[0]+'dis'+string1[1]).prop("disabled",true);
        $("#sel"+string1[0]+'st'+string1[1]).val("");
        $("#sel"+string1[0]+'dis'+string1[1]).val("");
    }
    else{
        $("#sel"+string1[0]+'st'+string1[1]).removeProp("disabled");
        $("#sel"+string1[0]+'dis'+string1[1]).removeProp("disabled");
    }
}

function copy_address()
{
   
 var  type= (document.getElementById('selpt').value);
  
    if(type=='I') 
      {
       
    if(document.getElementById('copy').checked==true)
       {
          var add=document.getElementById('paddi').value;    
          var city=document.getElementById('pcityi').value;
          var pin=document.getElementById('ppini').value;
          var selpsti=document.getElementById('selpsti').value;
          var selpdisi=document.getElementById('selpdisi').value.trim();
          
          if(add=='' || city=='' || pin==''|| selpsti == '' || selpdisi=='' )
            {
              alert("Enter Full Address Details of the Petitioner");
              return;
            }


           get_sc_District('R', 'selrsti', selpsti,selpdisi);
            get_sc_District('R', 'selrstd', selpsti,selpdisi);
           
           document.getElementById('raddi').value= add;
           document.getElementById('rcityi').value= city;
           document.getElementById('rpini').value= pin ;
           document.getElementById('selrsti').value= selpsti;
           document.getElementById('selrdisi').value= selpdisi;
           document.getElementById('raddd').value= add;
           document.getElementById('rcityd').value= city;
           document.getElementById('rpind').value= pin ;
           document.getElementById('selrstd').value= selpsti;
           document.getElementById('selrdisd').value= selpdisi;


        }   // if address is to be copied
   
   
   if(document.getElementById('copy').checked==false)
     {
       
        document.getElementById('raddi').value= '';
        document.getElementById('rcityi').value='';
        document.getElementById('rpini').value= '' ;
        document.getElementById('selrsti').value= '';
        document.getElementById('selrdisi').value= '';
       document.getElementById('raddd').value= '';
        document.getElementById('rcityd').value='';
        document.getElementById('rpind').value= '' ;
        document.getElementById('selrstd').value= '';
        document.getElementById('selrdisd').value= '';
        }
   
  }
   
   
  else
      
    {  // code for other organisations except Individual
        
        //alert(" OTHER THAN INDIVIDUAL IS SELECTED");
        
         var add=document.getElementById('paddd').value;    
          var city=document.getElementById('pcityd').value;
          var pin=document.getElementById('ppind').value;
          var selpsti=document.getElementById('selpstd').value;
          var selpdisi=document.getElementById('selpdisd').value.trim();
          dis_id="selrsti";
        if(add=='' || city=='' || pin==''|| selpsti == '' || selpdisi=='' )
            {
              alert("Enter Full Address Details of the Petitioner");
              return;
            }
   get_sc_District('R', 'selrsti', selpsti,selpdisi);
        get_sc_District('R', 'selrstd', selpsti,selpdisi);
           document.getElementById('raddd').value= add;
           document.getElementById('rcityd').value= city;
           document.getElementById('rpind').value= pin ;
           document.getElementById('selrstd').value= selpsti;
           document.getElementById('selrdisd').value= selpdisi;
           
            document.getElementById('raddi').value= add;
           document.getElementById('rcityi').value= city;
           document.getElementById('rpini').value= pin ;
           document.getElementById('selrsti').value= selpsti;
           document.getElementById('selrdisi').value= selpdisi;

   if(document.getElementById('copy').checked==false)
     {
       
        document.getElementById('raddd').value= '';
        document.getElementById('rcityd').value='';
        document.getElementById('rpind').value= '' ;
        document.getElementById('selrstd').value= '';
        document.getElementById('selrdisd').value= '';
        document.getElementById('raddd').value= '';
        document.getElementById('rcityd').value='';
        document.getElementById('rpind').value= '' ;
        document.getElementById('selrstd').value= '';
        document.getElementById('selrdisd').value= '';
      }
      }
          // if address is to be copied
        
    }   
        
        
    

function setSex(value,id){
    var newstring1 = id.split('sel');
    var newstring = newstring1[1].split('rel');
    if(value == 'S' || value == 'F')
        $("#"+newstring[0]+"sex").val("M");
    else if(value == 'D' || value == 'W' || value == 'M')
        $("#"+newstring[0]+"sex").val("F");
    else if(value == '')
        $("#"+newstring[0]+"sex").val("");
}

function chkM(val)
{
    if (val == '52')
        document.getElementById('mcrc_rw').style.display = 'table-row';
    else
        document.getElementById('mcrc_rw').style.display = 'none';
}

function changeAdvocate(id, val)
{
    if (id == 'padvt')
    {
        $('#ddl_pet_adv_state').val('');
        $('#padvno').val('');
        $('#padvyr').val('');
        $('#padvname').val('');
        $('#padvmob').val('');
        $('#padvemail').val('');
        if(val == 'S')
        {
            document.getElementById('padvno').style.display = 'inline';
            document.getElementById('padvno_').style.display = 'inline';
            document.getElementById('padvyr').style.display = 'inline';
            document.getElementById('padvyr_').style.display = 'inline';
            document.getElementById('padvmob').style.display = "inline";
            document.getElementById('padvmob_').style.display = "inline";
            document.getElementById('padvemail').style.display = 'inline';
            document.getElementById('padvemail_').style.display = 'inline';
            document.getElementById('padvname').disabled = true;
            document.getElementById('padvname').value = '';
            document.getElementById('padvmob').value = '';
            document.getElementById('padvemail').value = '';
            $('#padvyr').attr('disabled',false);
            $('#ddl_pet_adv_state').attr('disabled',false);
            $('#is_ac').attr('disabled',false);
            $('#is_ac').attr('checked',false);
        }
        if(val == 'N')
        {
            document.getElementById('padvno').style.display = 'inline';
            document.getElementById('padvno_').style.display = 'inline';
            document.getElementById('padvyr').style.display = 'inline';
            document.getElementById('padvyr_').style.display = 'inline';
            document.getElementById('padvmob').style.display = "inline";
            document.getElementById('padvmob_').style.display = "inline";
            document.getElementById('padvemail').style.display = 'inline';
            document.getElementById('padvemail_').style.display = 'inline';
            document.getElementById('padvname').disabled = true;
            document.getElementById('padvname').value = '';
            document.getElementById('padvmob').value = '';
            document.getElementById('padvemail').value = '';
            $('#padvyr').attr('disabled',false);
            $('#ddl_pet_adv_state').attr('disabled',false);
            $('#is_ac').attr('disabled',false);
            $('#is_ac').prop('checked',true);
        }
        else if(val=='A')
        {
            $('#ddl_pet_adv_state').val('');
            $('#ddl_pet_adv_state').attr('disabled',true);
            $('#padvno').val('');
            $('#padvyr').val('');
            $('#padvno').attr('disabled',false);
            $('#padvyr').attr('disabled',true);
            document.getElementById('padvno').style.display='inline';
            document.getElementById('padvno_').style.display='inline';
            document.getElementById('padvyr').style.display='inline';
            document.getElementById('padvyr_').style.display='inline';
            document.getElementById('padvmob').style.display='inline';
            document.getElementById('padvmob_').style.display='inline';
            document.getElementById('padvemail').style.display='inline';
            document.getElementById('padvemail_').style.display='inline';
            $('#is_ac').attr('disabled',false);
            $('#is_ac').attr('checked',false);
        }
        else if (val != 'S')
        {
            if(val=='SS'){
                document.getElementById('padvno').style.display = 'none';
                document.getElementById('padvno_').style.display = 'none';
                document.getElementById('padvno').value = '';
                document.getElementById('padvyr').style.display = 'none';
                document.getElementById('padvyr_').style.display = 'none';
                document.getElementById('padvyr').value = '';
                document.getElementById('padvname').disabled = false;
                document.getElementById('padvmob').style.display='none';
                document.getElementById('padvmob_').style.display='none';
                document.getElementById('padvemail').style.display='none';
                document.getElementById('padvemail_').style.display='none';
                $('#is_ac').attr('disabled',true);
                $('#is_ac').attr('checked',false);

                if (document.getElementById('selpt').value == 'I')
                    document.getElementById('padvname').value = document.getElementById('pet_name').value + " (SELF)";
            }
            /*else if(val=='C'){
             $('#padvno').prop('disabled',true);
             $('#padvyr').prop('disabled',true);
             $('#padvname').prop('disabled',true);
             document.getElementById('padvno').value = '7777';
             document.getElementById('padvyr').value = '2014';
             //document.getElementById('padvname').disabled = false;
             //document.getElementById('padvname').value = "Assistant Solicitor General";
             document.getElementById('padvname').value = "ATTORNEY GENERAL";
             }
             /*document.getElementById('padvmob').value = '';
             document.getElementById('padvmob').style.display = "none";
             document.getElementById('padvmob_').style.display = "none";
             document.getElementById('padvemail').value = '';
             document.getElementById('padvemail').style.display = 'none';
             document.getElementById('padvemail_').style.display = 'none';*/
            if (val == 'C')
            {

            }
            else if (val == 'SS')
            {

            }
            $('#ddl_pet_adv_state').attr('disabled',true);
        }
    }
    else if (id == 'radvt')
    {
        $('#ddl_res_adv_state').val('');
        $('#radvno').val('');
        $('#radvyr').val('');
        $('#radvname').val('');
        $('#radvmob').val('');
        $('#radvemail').val('');
        if (val == 'S')
        {
            document.getElementById('radvno').style.display = 'inline';
            document.getElementById('radvno_').style.display = 'inline';
            document.getElementById('radvyr').style.display = 'inline';
            document.getElementById('radvyr_').style.display = 'inline';
            document.getElementById('radvname').disabled = true;
            document.getElementById('radvname').value = '';
            document.getElementById('radvmob').value = '';
            document.getElementById('radvmob').style.display = 'inline';
            document.getElementById('radvmob_').style.display = 'inline';
            document.getElementById('radvemail').value = '';
            document.getElementById('radvemail').style.display = 'inline';
            document.getElementById('radvemail_').style.display = 'inline';
            $('#radvyr').attr('disabled',false);
            $('#ddl_res_adv_state').attr('disabled',false);
        }
        if(val == 'N')
        {
            document.getElementById('radvno').style.display = 'inline';
            document.getElementById('radvno_').style.display = 'inline';
            document.getElementById('radvyr').style.display = 'inline';
            document.getElementById('radvyr_').style.display = 'inline';
            document.getElementById('radvname').disabled = true;
            document.getElementById('radvname').value = '';
            document.getElementById('radvmob').value = '';
            document.getElementById('radvmob').style.display = 'inline';
            document.getElementById('radvmob_').style.display = 'inline';
            document.getElementById('radvemail').value = '';
            document.getElementById('radvemail').style.display = 'inline';
            document.getElementById('radvemail_').style.display = 'inline';
            $('#radvyr').attr('disabled',false);
            $('#ddl_res_adv_state').attr('disabled',false);
            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').prop('checked',true);

        }
        else if(val=='A')
        {
            $('#ddl_res_adv_state').val('');
            $('#ddl_res_adv_state').attr('disabled',true);
            $('#radvno').val('');
            $('#radvyr').val('');
            $('#radvno').attr('disabled',false);
            $('#radvyr').attr('disabled',true);
            document.getElementById('radvno').style.display='inline';
            document.getElementById('radvno_').style.display='inline';
            document.getElementById('radvyr').style.display='inline';
            document.getElementById('radvyr_').style.display='inline';
            document.getElementById('radvmob').style.display='inline';
            document.getElementById('radvmob_').style.display='inline';
            document.getElementById('radvemail').style.display='inline';
            document.getElementById('radvemail_').style.display='inline';
            $('#ris_ac').attr('disabled',false);
            $('#ris_ac').attr('checked',false);
        }
        else if (val != 'S')
        {
            if(val=='SS'){
                document.getElementById('radvno').style.display = 'none';
                document.getElementById('radvno_').style.display = 'none';
                document.getElementById('radvno').value = '';
                document.getElementById('radvyr').style.display = 'none';
                document.getElementById('radvyr_').style.display = 'none';
                document.getElementById('radvmob').style.display='none';
                document.getElementById('radvmob_').style.display='none';
                document.getElementById('radvemail').style.display='none';
                document.getElementById('radvemail_').style.display='none';
                document.getElementById('radvyr').value = '';
                document.getElementById('radvname').disabled = false;
                if (document.getElementById('selrt').value == 'I')
                    document.getElementById('radvname').value = document.getElementById('res_name').value + " (SELF)";
            }
            /*else if(val=='C'){
             $('#radvno').prop('disabled',true);
             $('#radvyr').prop('disabled',true);
             $('#radvname').prop('disabled',true);
             document.getElementById('radvno').value = '7777';
             document.getElementById('radvyr').value = '2014';
             //document.getElementById('radvname').disabled = false;
             //document.getElementById('radvname').value = "Assistant Solicitor General";
             document.getElementById('radvname').value = "ATTORNEY GENERAL";
             }

             document.getElementById('radvmob').value = '';
             document.getElementById('radvmob').style.display = 'none';
             document.getElementById('radvmob_').style.display = 'none';
             document.getElementById('radvemail').value = '';
             document.getElementById('radvemail').style.display = 'none';
             document.getElementById('radvemail_').style.display = 'none';*/
            if (val == 'C')
            {

            }
            else if (val == 'SS')
            {

            }
            $('#ddl_res_adv_state').attr('disabled',true);
        }
    }
}


function activate_main(id)
{
    if (id == "selpt")
    {
        if (document.getElementById(id).value == "I")
        {
            document.getElementById('pet_post').value = "";
            document.getElementById('pet_deptt').value = "";
            document.getElementById('pet_statename').value = "";
            document.getElementById('paddd').value = "";
            document.getElementById('pcityd').value = "";
            document.getElementById('ppind').value = "";
            document.getElementById('selpdisd').value = "";
            document.getElementById('selpstd').value = "23";
            document.getElementById('pmobd').value = "";
            document.getElementById('pemaild').value = "";
            document.getElementById('for_I_p').style.display = 'block';
            document.getElementById('for_D_p').style.display = 'none';
            //$('#state_department_in_pet').val("");
        }
        else if (document.getElementById(id).value != "I")
        {
            document.getElementById('pet_name').value = "";
            document.getElementById('selprel').value = "";
            document.getElementById('prel').value = "";
            document.getElementById('psex').value = "";
            document.getElementById('page').value = "";
            document.getElementById('pocc').value = "";
            document.getElementById('paddi').value = "";
            document.getElementById('pcityi').value = "";
            document.getElementById('ppini').value = "";
            document.getElementById('selpdisi').value = "";
            document.getElementById('selpsti').value = "23";
            document.getElementById('pmobi').value = "";
            document.getElementById('pemaili').value = "";
            document.getElementById('for_I_p').style.display = 'none';
            document.getElementById('for_D_p').style.display = 'block';
            if(document.getElementById(id).value == "D3"){
                document.getElementById('for_D_p_sn1').style.display = 'none';
                document.getElementById('for_D_p_sn2').style.display = 'none';
            }
            else{
                document.getElementById('for_D_p_sn1').style.display = 'table-cell';
                document.getElementById('for_D_p_sn2').style.display = 'table-cell';
            }
            /*if (document.getElementById(id).value == 'D1')
             $('.state_p').css('display', 'table-cell');
             else
             {
             $('.state_p').css('display', 'none');
             $('#state_department_in_pet').val("");
             }*/
        }
    }
    else if (id == "selrt")
    {
        if (document.getElementById(id).value == "I")
        {
            document.getElementById('res_post').value = "";
            document.getElementById('res_deptt').value = "";
            document.getElementById('res_statename').value = "";
            document.getElementById('raddd').value = "";
            document.getElementById('rcityd').value = "";
            document.getElementById('rpind').value = "";
            document.getElementById('selrdisd').value = "";
            document.getElementById('selrstd').value = "23";
            document.getElementById('rmobd').value = "";
            document.getElementById('remaild').value = "";
            document.getElementById('for_I_r').style.display = 'block';
            document.getElementById('for_D_r').style.display = 'none';
            $('#state_department_in_res').val("");
        }
        else if (document.getElementById(id).value != "I")
        {
            document.getElementById('res_name').value = "";
            document.getElementById('selrrel').value = "";
            document.getElementById('rrel').value = "";
            document.getElementById('rsex').value = "";
            document.getElementById('rage').value = "";
            document.getElementById('rocc').value = "";
            document.getElementById('raddi').value = "";
            document.getElementById('rcityi').value = "";
            document.getElementById('rpini').value = "";
            document.getElementById('selrdisi').value = "";
            document.getElementById('selrsti').value = "23";
            document.getElementById('rmobi').value = "";
            document.getElementById('remaili').value = "";
            document.getElementById('for_I_r').style.display = 'none';
            document.getElementById('for_D_r').style.display = 'block';

            if(document.getElementById(id).value == "D3"){
                document.getElementById('for_D_r_sn1').style.display = 'none';
                document.getElementById('for_D_r_sn2').style.display = 'none';
            }
            else{
                document.getElementById('for_D_r_sn1').style.display = 'table-cell';
                document.getElementById('for_D_r_sn2').style.display = 'table-cell';
            }
            /*if (document.getElementById(id).value == 'D1')
             $('.state_r').css('display', 'table-cell');
             else
             {
             $('.state_r').css('display', 'none');
             $('#state_department_in_res').val("");
             }*/
        }
    }
}

function getAdvocate_for_main(id,flag)
{


    if (flag == 'P' )
    {
        var ddl_pet_adv_state=$('#ddl_pet_adv_state').val();
        var padvt=$('#padvt').val();
        var ddl_pet_adv_state=$('#ddl_pet_adv_state').val();
        var ddl_pet_adv_no=$('#padvno').val();
        var ddl_pet_adv_yr=$('#padvyr').val();
        var ddl_pet_adv_isac='N';

        if(ddl_pet_adv_no==''){
            document.getElementById("padvname").value='';
            document.getElementById("padvmob").value='';
            document.getElementById("padvemail").value='';
        }
//alert(document.getElementById("is_ac").checked );
        //alert(document.getElementById("padvt").value );
        if(document.getElementById("is_ac").checked == true)
            ddl_pet_adv_isac='Y';
        if(document.getElementById("is_ac").checked == true && document.getElementById("padvt").value=='N' && id=='padvyr'){
            if(document.getElementById("ddl_pet_adv_state").value==''){
                alert('Please Select State');
                document.getElementById("ddl_pet_adv_state").focus();
                return false;
            }
            if(document.getElementById("padvno").value==''){
                alert('Please enter Enrollment No.');
                document.getElementById("padvno").focus();
                return false;
            }
            if(document.getElementById("padvyr").value==''){
                alert('Please enter Enrollment Year');
                document.getElementById("padvyr").focus();
                return false;
            }

        }

        if(document.getElementById("padvno").value==''  && padvt=='A'){
            alert('Please enter AOR Code');
         //   document.getElementById("padvno").value='oo';
            document.getElementById("padvno").focus();
            return false;
        }

        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange = function()
        {
            //alert(id);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var vcal = xmlhttp.responseText;
                //alert(vcal);
                //document.getElementById('padvname').value=vcal;
                if (vcal != 0)
                {
                    vcal = vcal.split('~');

                    document.getElementById('padvname').value = vcal[0];
                    alert(document.getElementById('padvno').value);
                    if(document.getElementById('padvno').value==799){
                        document.getElementById('padvmob').value = document.getElementById('pmobi').value;
                        document.getElementById('padvemail').value =  document.getElementById('pemaili').value;
                    }
                    else {
                        document.getElementById('padvmob').value = vcal[1];
                        document.getElementById('padvemail').value = vcal[2];
                    }
                    $('#hd_p_barid').val(vcal[3]);
                }
                else {
                    document.getElementById('padvname').value = "";
                    document.getElementById('padvmob').value ="";
                    document.getElementById('padvemail').value = "";
                }
            }
        }

            var url = "get_adv_name.php" + "?advno=" + document.getElementById('padvno').value + "&advyr=" +
                document.getElementById('padvyr').value + "&ddl_pet_adv_state=" + ddl_pet_adv_state + "&flag=" + flag + '&padvt=' + padvt + '&is_ac=' + ddl_pet_adv_isac;

            xmlhttp.open("GET", url, false);
            xmlhttp.send(null);

    }
    else if (flag == 'R')
    {
        var ddl_res_adv_state=$('#ddl_res_adv_state').val();
        var radvt=$('#radvt').val();

        var ddl_res_adv_state=$('#ddl_res_adv_state').val();
        var ddl_res_adv_no=$('#radvno').val();
        var ddl_res_adv_yr=$('#radvyr').val();
        var ddl_pet_adv_isac='N';
        if(ddl_res_adv_no==''){
            document.getElementById("radvname").value='';
            document.getElementById("radvmob").value='';
            document.getElementById("radvemail").value='';
        }
//alert(document.getElementById("is_ac").checked );
        //alert(document.getElementById("padvt").value );
        if(document.getElementById("ris_ac").checked == true)
            ddl_pet_adv_isac='Y';
        if(document.getElementById("ris_ac").checked == true && document.getElementById("radvt").value=='N' && id=='radvyr'){
            if(document.getElementById("ddl_res_adv_state").value==''){
                alert('Please Select State');
                document.getElementById("ddl_res_adv_state").focus();
                return false;
            }
            if(document.getElementById("radvno").value==''){
                alert('Please enter Enrollment No.');
                document.getElementById("radvno").focus();
                return false;
            }
            if(document.getElementById("radvyr").value==''){
                alert('Please enter Enrollment Year');
                document.getElementById("radvyr").focus();
                return false;
            }

        }

        if(document.getElementById("radvno").value==''  && radvt=='A'){
            alert('Please enter AOR Code');
            //   document.getElementById("padvno").value='oo';
            document.getElementById("radvno").focus();
            return false;
        }


        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var vcal = xmlhttp.responseText;

                if (vcal != 0)
                {
                    vcal = vcal.split('~');
                    document.getElementById('radvname').value = vcal[0];
                    if(document.getElementById('radvno').value==800){
                        document.getElementById('radvmob').value = document.getElementById('pmobi').value;
                        document.getElementById('radvemail').value =  document.getElementById('pemaili').value;
                    }
                    else {
                        document.getElementById('radvmob').value = vcal[1];
                        document.getElementById('radvemail').value = vcal[2];
                    }
                    $('#hd_r_barid').val(vcal[3]);
                }
                else{
                    document.getElementById('radvname').value = "";
                    document.getElementById('radvmob').value ="";
                    document.getElementById('radvemail').value = "";
                }


            }
        }
        var url = "get_adv_name.php" + "?advno=" + document.getElementById('radvno').value + "&advyr=" +
            document.getElementById('radvyr').value+"&ddl_res_adv_state="+ddl_res_adv_state+"&flag="+flag+'&radvt='+radvt;
        xmlhttp.open("GET", url, false);
        xmlhttp.send(null);
    }
}


/*
function getAdvocate_for_main_nonaor(flag)
{
//   alert(flag);

    if (flag == 'P')
    {
        var ddl_pet_adv_state=$('#ddl_pet_adv_state').val();
        var ddl_pet_adv_no=$('#padvno').val();
        var ddl_pet_adv_yr=$('#padvyr').val();
        var ddl_pet_adv_isac='N';
        alert($('#is_ac').val());
        if($('#is_ac').val()==true)
            ddl_pet_adv_isac='Y';
        if(ddl_pet_adv_isac=='Y'){
            if($('#ddl_pet_adv_state').val()==''){
                alert('Please Select State');
                $('#ddl_pet_adv_state').focus();
                return false;
            }
            if($('#padvno').val()==''){
                alert('Please enter Enrollment No.');
                $('#padvno').focus();
                return false;
            }
            if($('#padvyr').val()==''){
                alert('Please enter Enrollment Year');
                $('#padvyr').focus();
                return false;
            }

        }
        //var padvt=$('#padvt').val();

        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var vcal = xmlhttp.responseText;
                //alert(vcal);
                //document.getElementById('padvname').value=vcal;
                if (vcal != 0)
                {
                    vcal = vcal.split('~');
                    document.getElementById('padvname').value = vcal[0];
                    document.getElementById('padvmob').value = vcal[1];
                    document.getElementById('padvemail').value = vcal[2];
                    $('#hd_p_barid').val(vcal[3]);
                }
                else {
                    document.getElementById('padvname').value = "";
                    document.getElementById('padvmob').value ="";
                    document.getElementById('padvemail').value = "";
                }
            }
        }
        var url = "get_adv_name.php"+"?advno=" + document.getElementById('padvno').value + "&advyr=" +
            document.getElementById('padvyr').value+"&ddl_pet_adv_state="+ddl_pet_adv_state+"&flag="+flag+'&padvt='+padvt+'&is_ac='+ddl_pet_adv_isac;

        xmlhttp.open("GET", url, false);
        xmlhttp.send(null);
    }
    else if (flag == 'R')
    {
        var ddl_res_adv_state=$('#ddl_res_adv_state').val();
        var radvt=$('#radvt').val();
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var vcal = xmlhttp.responseText;

                if (vcal != 0)
                {
                    vcal = vcal.split('~');
                    document.getElementById('radvname').value = vcal[0];
                    document.getElementById('radvmob').value = vcal[1];
                    document.getElementById('radvemail').value = vcal[2];
                    $('#hd_r_barid').val(vcal[3]);
                }
                else{
                    document.getElementById('radvname').value = "";
                    document.getElementById('radvmob').value ="";
                    document.getElementById('radvemail').value = "";
                }
            }
        }
        var url = "get_adv_name.php" + "?advno=" + document.getElementById('radvno').value + "&advyr=" +
            document.getElementById('radvyr').value+"&ddl_res_adv_state="+ddl_res_adv_state+"&flag="+flag+'&radvt='+radvt;
        xmlhttp.open("GET", url, false);
        xmlhttp.send(null);
    }
}*/

function get_a_d_code(id)
{
    var id2 = id.split("_");
    //alert(id2[1]);
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById(id + "_code").value = xmlhttp.responseText;
        }
    }
    var url = "new_filing_autocomp_" + id2[1] + ".php?falagofpost=code&val=" + document.getElementById(id).value;
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}

function onlynumbers(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8 || charCode == 37 || charCode == 39 ) {
        return true;
    }
    return false;
}

function onlynumbersadv(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 48 && charCode <= 57) || (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122)
        || charCode == 9 || charCode == 8 || charCode == 45) {
        return true;
    }
    return false;
}

function remove_apos(value,id){
    var string = value.replace("'","");
    string = string.replace("#","No");
    string = string.replace("&","and");
    $("#"+id).val(string);
}

function onlyalpha(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode == 9 || charCode == 8 ||
        charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64 || charCode == 37 || charCode == 39) {
        return true;
    }
    return false;
}

function onlyalphab(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
    if ((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || (charCode >= 48 && charCode <= 57)
        || charCode == 9 || charCode == 8 || charCode == 127 || charCode == 32 || charCode == 46 || charCode == 47 || charCode == 64
        || charCode == 40 || charCode == 41 || charCode == 37 || charCode == 39) {
        return true;
    }
    return false;
}

function noinput(evt)
{
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    //alert(charCode);
//    if (charCode==9) {
//    return true;
//    }
    return false;
}

function call_save_main(st_status)
{

    // alert("As per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI), Registry is directed not to accept any application or petition on behalf of Suraj India Trust or Mr. Rajiv Daiya");
// alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))");

alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
    " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");

    var ct=document.getElementById('ddl_nature_sci').value;
    var cno=document.getElementById('no').value;
    var cyr=document.getElementById('t_h_cyt').value;
    var section=document.getElementById('section').value;
    if(section=='')
     {
        alert("Section Cannot be left blank");
      document.getElementById('section').disabled=false;
        return;
     }

    var data=ct+cno+cyr;

    var dd=document.getElementById('diary_no').value;
    var dyr=document.getElementById('dyr').value;

    //alert(" diary_no and year"+dd+dyr);

    //alert(data);

    var if_sclsc=0;
    if($("#if_sclsc").is(":checked"))
        if_sclsc=1;
    //alert(if_sclsc);

    var if_efil=0;
    if($("#if_efil").is(":checked"))
        if_efil=1;

    //    var case_type = document.getElementById('selct');
    var chk_ext_entry=0;
    var ext_address='';

    $('.cl_add_P').each(function(){
        var idd= $(this).attr('id');
        var sp_idd=idd.split('txt_addressP');
        var txt_addressP=$('#txt_addressP'+sp_idd[1]).val();
        var txt_counrtyP=$('#txt_counrtyP'+sp_idd[1]).val();
        var txt_stateP=$('#txt_stateP'+sp_idd[1]).val();
        var txt_districtP=$('#txt_districtP'+sp_idd[1]).val();
        if(txt_addressP!='')
        {
            if (txt_counrtyP == '')
            {
                alert("Please Select Country");
                $('#txt_counrtyP' + sp_idd[1]).focus();
                chk_ext_entry = 1;
                return false;
            }
            if (txt_stateP == '' && txt_counrtyP == '96')
            {
                alert("Please Select State");
                $('#txt_stateP' + sp_idd[1]).focus();
                chk_ext_entry = 1;
                return false;
            }
            if (txt_districtP == '' && txt_counrtyP == '96')
            {
                alert("Please Select District");
                $('#txt_districtP' + sp_idd[1]).focus();
                chk_ext_entry = 1;
                return false;
            }
            if (ext_address == '')
                ext_address = txt_addressP + '~' + txt_counrtyP + '~' + txt_stateP + '~' + txt_districtP;
            else
                ext_address = ext_address + '^' + txt_addressP + '~' + txt_counrtyP + '~' + txt_stateP + '~' + txt_districtP;
        }
    });
    if (chk_ext_entry == 1)
        return false;
    else
    {
        var ext_address_r = '';
        var chk_ext_entry_r = 0;
        $('.cl_add_R').each(function () {

            var idd = $(this).attr('id');
            var sp_idd = idd.split('txt_addressR');
            var txt_addressP = $('#txt_addressR' + sp_idd[1]).val();
            var txt_counrtyP = $('#txt_counrtyR' + sp_idd[1]).val();
            var txt_stateP = $('#txt_stateR' + sp_idd[1]).val();
            var txt_districtP = $('#txt_districtR' + sp_idd[1]).val();
            if (txt_addressP != '')
            {
                if (txt_counrtyP == '')
                {
                    alert("Please Select Country");
                    $('#txt_counrtyR' + sp_idd[1]).focus();
                    chk_ext_entry_r = 1;
                    return false;
                }
                if (txt_stateP == '' && txt_counrtyP == '96')
                {
                    alert("Please Select State");
                    $('#txt_stateR' + sp_idd[1]).focus();
                    chk_ext_entry_r = 1;
                    return false;
                }
                if (txt_districtP == '' && txt_counrtyP == '96')
                {
                    alert("Please Select District");
                    $('#txt_districtR' + sp_idd[1]).focus();
                    chk_ext_entry_r = 1;
                    return false;
                }
                if (ext_address_r == '')
                    ext_address_r = txt_addressP + '~' + txt_counrtyP + '~' + txt_stateP + '~' + txt_districtP;
                else
                    ext_address_r = ext_address_r + '^' + txt_addressP + '~' + txt_counrtyP + '~' + txt_stateP + '~' + txt_districtP;
            }
        });
    }
//alert(ext_address+'$$'+ext_address_r);
    if( chk_ext_entry_r==1)
        return false;

    var pet_type = document.getElementById('selpt').value;
    var pet_name, pet_rel, pet_rel_name, pet_sex, pet_age, pet_post, pet_deptt, pet_add, pcity, pdis, pst, tpet,pcont;

    var res_type = document.getElementById('selrt').value;
    var res_name, res_rel, res_rel_name, res_sex, res_age, res_post, res_deptt, res_add, rcity, rdis, rst, tres,rcont;

//    if(case_type.value=='-1')
//    {
//        alert('Please Select Case Type');case_type.focus();return false;
//    }
//var chk_undertaking='N';
    var ddl_st_agncy = $('#ddl_st_agncy').val();
    var ddl_bench = $('#ddl_bench').val();
    var ddl_court = $('#ddl_court').val();
    var txt_doc_signed = $('#txt_doc_signed').val();
    var hd_current_date = $('#hd_current_date').val();
    var type_special_a = $('#type_special').val();
//var ddl_doc_u=$('#ddl_doc_u').val();
//var txt_undertakig=$('#txt_undertakig').val();
    var ddl_nature = $('#ddl_nature').val();
    var ddl_pet_adv_state = $('#ddl_pet_adv_state').val();
    var ddl_res_adv_state = $('#ddl_res_adv_state').val();
    if (ddl_court == '')
    {
        alert("Please select Court");
        $('#ddl_court').focus();
        return false;
    }
    if (ddl_st_agncy == '')
    {
        alert("Please select State");
        $('#ddl_st_agncy').focus();
        return false;
    }
    if (ddl_bench == '')
    {
        alert("Please select Bench");
        $('#ddl_bench').focus();
        return false;
    }
    if (type_special_a == '6' && (txt_doc_signed == '' || txt_doc_signed.length < 10))
    {
        alert("Please enter Date of document signed by jailer");
        $('#txt_doc_signed').focus();
        return false;
    }
    if (type_special_a == '6' && txt_doc_signed != '' && txt_doc_signed.length == 10)
    {
        var dt1 = parseInt(txt_doc_signed.substring(0, 2), 10);
        var mon1 = parseInt(txt_doc_signed.substring(3, 5), 10) - 1;
        var yr1 = parseInt(txt_doc_signed.substring(6, 10), 10);
        var date1 = new Date(yr1, mon1, dt1);
        var dt2 = parseInt(hd_current_date.substring(0, 2), 10);
        var mon2 = parseInt(hd_current_date.substring(3, 5), 10) - 1;
        var yr2 = parseInt(hd_current_date.substring(6, 10), 10);
        var date2 = new Date(yr2, mon2, dt2);
//    alert(date1+','+date2);
        if (date1 > date2) {
            alert(" Date of document signed by jailer should be less than current date");
            return false;
        }
    }
//if($('#chk_undertaking').is(':checked') && ddl_doc_u=='')
//    {
//        alert("Please select reason of Undertaking");
//        $('#txt_undertakig').focus();
//         return false;
//    }
//if($('#chk_undertaking').is(':checked') && ddl_doc_u=='10' && txt_undertakig=='')
//    {
//        alert("Please enter reason of Undertaking");
//        $('#txt_undertakig').focus();
//         return false;
//    }
    if(ddl_nature=='')
    {
        alert("Please select Case Type");
        return false;
    }
    txt_sclsc_no='';
    ddl_sclsc_yr='';
    if(if_sclsc==1)
    {
        var txt_sclsc_no=$('#txt_sclsc_no').val();
        var ddl_sclsc_yr=$('#ddl_sclsc_yr').val();
        if(txt_sclsc_no.trim()=='')
        {
            alert("Please enter SCLSC No.");
            $('#txt_sclsc_no').focus();
            return false;
        }
        if(ddl_sclsc_yr=='')
        {
            alert("Please enter SCLSC Year");
            $('#ddl_sclsc_yr').focus();
            return false;
        }
    }
    txt_efil_no='';
    ddl_efil_yr='';
    if(if_efil==1)
    {
        var txt_efil_no=$('#txt_efil_no').val();
        var ddl_efil_yr=$('#ddl_efil_yr').val();
        if(txt_efil_no.trim()=='')
        {
            alert("Please enter Efil Ack No.");
            $('#txt_efil_no').focus();
            return false;
        }
        if(ddl_efil_yr=='')
        {
            alert("Please enter Efiling Year");
            $('#ddl_efil_yr').focus();
            return false;
        }
    }
//    if($('#chk_undertaking').is(':checked'))
//    {
//        chk_undertaking='Y';
//    }

    var hd_mn='';
    var cs_tp='';
    var txtFNo='';
    var txtYear='';
    if($('#hd_mn').length && $('#cs_tp').length && $('#txtFNo').length && $('#txtYear').length)
    {
        hd_mn=$('#hd_mn').val();
        cs_tp=$('#cs_tp').val();
        txtFNo=$('#txtFNo').val();
        txtYear=$('#txtYear').val();
    }

    if (pet_type == "I")
    {
        pet_name = document.getElementById('pet_name');
        pet_rel = document.getElementById('selprel');
        pet_rel_name = document.getElementById('prel');
        pet_sex = document.getElementById('psex');
        pet_age = document.getElementById('page');
        pet_add = document.getElementById('paddi');
        pcity = document.getElementById('pcityi');
        pdis = document.getElementById('selpdisi');
        pst = document.getElementById('selpsti');
        pcont = document.getElementById('p_conti');
        tpet = document.getElementById('p_noi');
        if (pet_name.value == '')
        {
            alert('Please Enter Petitioner Name');
            pet_name.focus();
            return false;
        }
        /*if (pet_rel.value == '')
         {
         alert('Please Select Petitioner Relation');
         pet_rel.focus();
         return false;
         }
         if (pet_rel_name.value == '')
         {
         alert('Please Enter Petitioner Father/Husband Name');
         pet_rel_name.focus();
         return false;
         }
         if (pet_sex.value == '')
         {
         alert('Please Select Petitioner Gender');
         pet_sex.focus();
         return false;
         }*/
//        if(pet_age.value=='')
//        {
//            alert('Please Enter Petitioner Age');pet_age.focus();return false;
//        }
        if (pet_add.value == '')
        {
            alert('Please Enter Petitioner Address');
            pet_add.focus();
            return false;
        }
        if (pcity.value == '')
        {
            alert('Please Enter Petitioner City');
            pcity.focus();
            return false;
        }
        if(pcont.value=='96'){
            if (pst.value == '')
            {
                alert('Please Select Petitioner State');
                pst.focus();
                return false;
            }
            if (pdis.value == '')
            {
                alert('Please Select Petitioner District');
                pdis.focus();
                return false;
            }
        }
        if(pcont.value=="")
        {
            alert('Please Enter Petitioner Country');pcont.focus();return false;
        }
        if (document.getElementById('pemaili').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('pemaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaili').focus();
                return false;
            }
        }
        if (tpet.value == '' || tpet.value == 0)
        {
            alert('Total Pet(s) could not be null or zero');
            tpet.focus();
            return false;
        }
    }
    var pet_cause_title1=0; var pet_cause_title2=0; var pet_cause_title3=0;
    if (pet_type != "I")
    {
        pet_post = document.getElementById('pet_post');
        pet_deptt = document.getElementById('pet_deptt');
        pet_add = document.getElementById('paddd');
        pcity = document.getElementById('pcityd');
        pdis = document.getElementById('selpdisd');
        pst = document.getElementById('selpstd');
        pcont = document.getElementById('p_contd');
        tpet = document.getElementById('p_nod');
        if($("#selpt").val()!='D3' && $("#pet_causetitle1").is(':checked')){
            pet_cause_title1=1;
            if($("#pet_statename").val() == '')
            {
                alert('Please Enter Petitioner Department State Name');
                $("#pet_statename").focus();
                return false;
            }
        }
        if($("#pet_causetitle2").is(':checked')){
            pet_cause_title2=1;
            if (pet_deptt.value == '')
            {
                alert('Please Enter Petitioner Department');
                pet_deptt.focus();
                return false;
            }
        }
        if($("#pet_causetitle3").is(':checked')){
            pet_cause_title3=1;
            if (pet_post.value == '')
            {
                alert('Please Enter Petitioner Post');
                pet_post.focus();
                return false;
            }
        }
        if(pet_cause_title1==0 && pet_cause_title2==0 && pet_cause_title3==0){
            alert('Select atleast One Cause Title for Petitioner');
            return false;
        }

        if (pet_add.value == '')
        {
            alert('Please Enter Petitioner Address');
            pet_add.focus();
            return false;
        }
        if (pcity.value == '')
        {
            alert('Please Enter Petitioner City');
            pcity.focus();
            return false;
        }

        if(pcont.value=='96'){
            if (pst.value == '')
            {
                alert('Please Select Petitioner State');
                pst.focus();
                return false;
            }
            if (pdis.value == '')
            {
                alert('Please Select Petitioner District');
                pdis.focus();
                return false;
            }
        }
        if(pcont.value=="")
        {
            alert('Please Enter Petitioner Country');pcont.focus();return false;
        }
        if (document.getElementById('pemaild').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('pemaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaild').focus();
                return false;
            }
        }
        if (tpet.value == '' || tpet.value == 0)
        {
            alert('Total Pet(s) could not be null or zero');
            tpet.focus();
            return false;
        }
    }


    if (res_type == "I")
    {
        res_name = document.getElementById('res_name');
        res_rel = document.getElementById('selrrel');
        res_rel_name = document.getElementById('rrel');
        res_sex = document.getElementById('rsex');
        res_age = document.getElementById('rage');
        res_add = document.getElementById('raddi');
        rcity = document.getElementById('rcityi');
        rdis = document.getElementById('selrdisi');
        rst = document.getElementById('selrsti');
        rcont = document.getElementById('r_conti');
        tres = document.getElementById('r_noi');
        if (res_name.value == '')
        {
            alert('Please Enter Respondent Name');
            res_name.focus();
            return false;
        }
        /*if (res_rel.value == '')
         {
         alert('Please Select Respondent Relation');
         res_rel.focus();
         return false;
         }
         if (res_rel_name.value == '')
         {
         alert('Please Enter Respondent Father/Husband Name');
         res_rel_name.focus();
         return false;
         }
         if (res_sex.value == '')
         {
         alert('Please Select Respondent Gender');
         res_sex.focus();
         return false;
         }*/
//        if(res_age.value=='')
//        {
//            alert('Please Enter Respondent Age');res_age.focus();return false;
//        }
        if (res_add.value == '')
        {
            alert('Please Enter Respondent Address');
            res_add.focus();
            return false;
        }
        if (rcity.value == '')
        {
            alert('Please Enter Respondent City');
            rcity.focus();
            return false;
        }
        if(rcont.value=='96'){
            if (rst.value == '')
            {
                alert('Please Select Respondent State');
                rst.focus();
                return false;
            }
            if (rdis.value == '')
            {
                alert('Please Select Respondent District');
                rdis.focus();
                return false;
            }
        }
        if(rcont.value=="")
        {
            alert('Please Enter Respondent Country');rcont.focus();return false;
        }
        if (document.getElementById('remaili').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('remaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaili').focus();
                return false;
            }
        }
        if (tres.value == '' || tres.value == 0)
        {
            alert('Total Res(s) could not be null or zero');
            tres.focus();
            return false;
        }
    }
    var res_cause_title1=0; var res_cause_title2=0; var res_cause_title3=0;
    if (res_type != "I")
    {
        res_post = document.getElementById('res_post');
        res_deptt = document.getElementById('res_deptt');
        res_add = document.getElementById('raddd');
        rcity = document.getElementById('rcityd');
        rdis = document.getElementById('selrdisd');
        rst = document.getElementById('selrstd');
        rcont = document.getElementById('r_contd');
        tres = document.getElementById('r_nod');
        if($("#selrt").val()!='D3' && $("#res_causetitle1").is(':checked')){
            res_cause_title1=1;
            if($("#res_statename").val() == '')
            {
                alert('Please Enter Respondent Department State Name');
                $("#res_statename").focus();
                return false;
            }
        }
        if($("#res_causetitle2").is(':checked')){
            res_cause_title2=1;
            if (res_deptt.value == '')
            {
                alert('Please Enter Respondent Department');
                res_deptt.focus();
                return false;
            }
        }
        if($("#res_causetitle3").is(':checked')){
            res_cause_title3=1;
            if (res_post.value == '')
            {
                alert('Please Enter Respondent Post');
                res_post.focus();
                return false;
            }
        }
        if(res_cause_title1==0 && res_cause_title2==0 && res_cause_title3==0){
            alert('Select atleast One Cause Title for Respondent');
            return false;
        }

        if (res_add.value == '')
        {
            alert('Please Enter Respondent Address');
            res_add.focus();
            return false;
        }
        if (rcity.value == '')
        {
            alert('Please Enter Respondent City');
            rcity.focus();
            return false;
        }
        if(rcont.value=='96'){
            if (rst.value == '')
            {
                alert('Please Select Respondent State');
                rst.focus();
                return false;
            }
            if (rdis.value == '')
            {
                alert('Please Select Respondent District');
                rdis.focus();
                return false;
            }
        }
        if(rcont.value=="")
        {
            alert('Please Enter Respondent Country');rcont.focus();return false;
        }
        if (document.getElementById('remaild').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('remaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaild').focus();
                return false;
            }
        }
        if (tres.value == '' || tres.value == 0)
        {
            alert('Total Res(s) could not be null or zero');
            tres.focus();
            return false;
        }
    }

//    if(document.getElementById('padvname').value==''||document.getElementById('padvname').value==0)
//    {
//        alert('Please Enter Petitioner Advocate No and Year Properly');
//        document.getElementById('padvno').focus();return false;
//    }
//    if(document.getElementById('radvname').value==''||document.getElementById('radvname').value==0)
//    {
//        alert('Please Enter Respondent Advocate No and Year Properly');
//        document.getElementById('radvno').focus();return false;
//    }

   /* if (document.getElementById('padvemail').value != '')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (document.getElementById('padvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('padvemail').focus();
            return false;
        }
    }*/
    if (document.getElementById('radvemail').value != '')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (document.getElementById('radvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('radvemail').focus();
            return false;
        }
    }
    if(( document.getElementById('padvno').value=='' || document.getElementById('padvname').value=='') && document.getElementById('padvt').value!='SS'){
        if ( document.getElementById('padvno').value == '')
        {
            alert("Please enter AOR code");
            document.getElementById('padvno').focus();
            return false;
        }
        if ( document.getElementById('padvname').value == '')
        {
            alert("Please enter valid AOR code");
            document.getElementById('padvno').focus();
            return false;
        }
    }
    document.getElementById('svbtn').disabled = 'true';
    var hd_r_barid=$('#hd_r_barid').val();
    var hd_p_barid= $('#hd_p_barid').val();

    $('.cl_add_P').each(function () {
        var idd = $(this).attr('id');
        var sp_idd = idd.split('txt_addressP');
        var txt_addressP = $('#txt_addressP' + sp_idd[1]).val();
        var txt_counrtyP = $('#txt_counrtyP' + sp_idd[1]).val();
        var txt_stateP = $('#txt_stateP' + sp_idd[1]).val();
        var txt_districtP = $('#txt_districtP' + sp_idd[1]).val();
        if (txt_addressP != '')
        {
            if (txt_counrtyP == '')
            {
                alert("Please Select Country");
                $('#txt_counrtyP' + sp_idd[1]).focus();
                return false;
            }
        }
    });

    if(document.getElementById("is_ac").checked == true)
        ddl_pet_adv_isac='Y';
    else
        ddl_pet_adv_isac='N';
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    xmlhttp.onreadystatechange = function()
    {
        //debugger;
        if (xmlhttp.readyState == 4 )
        {
            if(xmlhttp.status == 200) {
                var res = xmlhttp.responseText;
                //alert(res);
                //alert(res.indexOf( 'Duplicate entry'));
                if (res.indexOf( 'Duplicate entry')>0){
                    alert('Duplicate entry.. Pleas click on Generate button again !!! ');
                    $('#svbtn').attr("disabled", false);
                }
                else{
                    //alert(res);
                    res = res.split('!~!');
                    //alert(res[1]);
                    //call_fullReset_main();
                    //$('#svbtn').attr("disabled", false);
                    document.getElementById('show_fil').innerHTML = res[1];
                }
            }

        }
    }
//    var url = "save_new_filing.php?controller=I"  + "&st_status=" + st_status+
//            "&ddl_st_agncy="+ddl_st_agncy+"&ddl_bench="+ddl_bench+"&hd_mn="+hd_mn+"&cs_tp="+cs_tp+"&txtFNo="+txtFNo+"&txtYear="+txtYear+
//            "&ddl_court="+ddl_court+"&chk_undertaking="+chk_undertaking+"&txt_undertakig="+txt_undertakig+"&ddl_nature="+ddl_nature+"&ddl_doc_u="+
//            ddl_doc_u+"&ddl_pet_adv_state="+ddl_pet_adv_state+"&ddl_res_adv_state="+ddl_res_adv_state;
//alert(ext_address);
    var url = "save_new_filing.php?controller=I"  + "&st_status=" + st_status+
        "&ddl_st_agncy="+ddl_st_agncy+"&ddl_bench="+ddl_bench+"&hd_mn="+hd_mn+"&cs_tp="+cs_tp+"&txtFNo="+txtFNo+"&txtYear="+txtYear+
        "&ddl_court="+ddl_court+"&ddl_nature="+ddl_nature+"&ddl_pet_adv_state="+ddl_pet_adv_state+
        "&ddl_res_adv_state="+ddl_res_adv_state+"&hd_r_barid="+hd_r_barid+"&hd_p_barid="+hd_p_barid+"&ext_address="+ext_address+"&ext_address_r="+ext_address_r+
        "&txt_doc_signed="+txt_doc_signed+'&txt_sclsc_no='+txt_sclsc_no+'&ddl_sclsc_yr='+ddl_sclsc_yr+'&section='+section+'&dd='+dd+'&dyr='+dyr;

    if (pet_type == "I")
        url = url + "&pname=" + pet_name.value + "&pet_rel=" + pet_rel.value + "&pet_rel_name=" + pet_rel_name.value + "&p_sex=" + pet_sex.value
            + "&p_age=" + pet_age.value + "&pocc=" + document.getElementById('pocc').value + "&pp=" + document.getElementById('ppini').value
            + "&pmob=" + document.getElementById('pmobi').value + "&pemail=" + document.getElementById('pemaili').value;
    if (pet_type != "I")
        url = url + "&pet_post=" + pet_post.value + "&pet_deptt=" + pet_deptt.value + "&pp=" + document.getElementById('ppind').value
            + "&pmob=" + document.getElementById('pmobd').value + "&pemail=" + document.getElementById('pemaild').value
            +"&pet_statename=" + document.getElementById('pet_statename').value+"&pet_statename_hd=" + document.getElementById('pet_statename_hd').value;

    url = url + "&padd=" + pet_add.value + "&pcity=" + pcity.value + "&pdis=" + pdis.value + "&pst=" + pst.value + "&p_type=" + pet_type+ "&p_cont=" + pcont.value;


    if (res_type == "I")
        url = url + "&rname=" + res_name.value + "&res_rel=" + res_rel.value + "&res_rel_name=" + res_rel_name.value + "&r_sex=" + res_sex.value
            + "&r_age=" + res_age.value + "&rocc=" + document.getElementById('rocc').value + "&rp=" + document.getElementById('rpini').value
            + "&rmob=" + document.getElementById('rmobi').value + "&remail=" + document.getElementById('remaili').value;
    if (res_type != "I")
        url = url + "&res_post=" + res_post.value + "&res_deptt=" + res_deptt.value + "&rp=" + document.getElementById('rpind').value
            + "&rmob=" + document.getElementById('rmobd').value + "&remail=" + document.getElementById('remaild').value
            +"&res_statename=" + document.getElementById('res_statename').value+"&res_statename_hd=" + document.getElementById('res_statename_hd').value;

    url = url + "&radd=" + res_add.value + "&rcity=" + rcity.value + "&rdis=" + rdis.value + "&rst=" + rst.value + "&r_type=" + res_type+ "&r_cont=" + rcont.value;

    if (document.getElementById('padvt').value == 'S')
        url = url + "&padvno=" + document.getElementById('padvno').value + "&padvyr=" + document.getElementById('padvyr').value
            + "&padvmob=" + document.getElementById('padvmob').value + "&padvemail=" + document.getElementById('padvemail').value;

    if (document.getElementById('padvt').value == 'C')
        url = url + "&padvno=" + document.getElementById('padvno').value + "&padvyr=" + document.getElementById('padvyr').value;

    url = url + "&padvname=" + document.getElementById('padvname').value;

    if (document.getElementById('radvt').value == 'S')
        url = url + "&radvno=" + document.getElementById('radvno').value + "&radvyr=" + document.getElementById('radvyr').value
            + "&radvmob=" + document.getElementById('radvmob').value + "&radvemail=" + document.getElementById('radvemail').value;

    if (document.getElementById('radvt').value == 'C')
        url = url + "&radvno=" + document.getElementById('radvno').value + "&radvyr=" + document.getElementById('radvyr').value;

    url = url + "&radvname=" + document.getElementById('radvname').value;

    url = url + "&padtype=" + document.getElementById('padvt').value + "&radtype=" + document.getElementById('radvt').value;

    url = url + "&pp_code=" + document.getElementById('pet_post_code').value + "&rp_code=" + document.getElementById('res_post_code').value
        + "&t_pet=" + tpet.value + "&t_res=" + tres.value
        + "&type_special=" + document.getElementById('type_special').value
        +"&pd_code=" + document.getElementById('pet_deptt_code').value + "&rd_code=" + document.getElementById('res_deptt_code').value;

    url = url+"&p_cause_t1="+pet_cause_title1+"&p_cause_t2="+pet_cause_title2+"&p_cause_t3="+pet_cause_title3
        +"&r_cause_t1="+res_cause_title1+"&r_cause_t2="+res_cause_title2+"&r_cause_t3="+res_cause_title3;


    url = url+"&if_sclsc="+if_sclsc+"&ct="+ct+"&cno="+cno+"&cyr="+cyr+"&section="+section+"&case_doc="+document.getElementById('case_doc').value;

    if(document.getElementById("is_ac").checked == true)
        url = url+"&is_ac="+'Y';
    else
        url = url+"&is_ac="+'';
    if(document.getElementById("ris_ac").checked == true)
        url = url+"&ris_ac="+'Y';
    else
        url = url+"&ris_ac="+'';

    if(if_efil) {
        url=url+'&if_efil='+if_efil+'&txt_efil_no=' + txt_efil_no + '&ddl_efil_yr=' + ddl_efil_yr ;
    }

    /*var state_department_in_pet = $("#state_department_in_pet").val().split('->');
     var state_department_in_res = $("#state_department_in_res").val().split('->');

     url = url + "&pd_code=" + state_department_in_pet[0] + "&rd_code=" + state_department_in_res[0];*/

//alert(document.getElementById('case_doc').value);
  // alert(url);
    url = encodeURI(url);
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}

function call_fullReset_main()
{
    $("#lct_casetype").hide();
    $("#dv_case_no").hide();
    $("#dv_parties").hide();
    $("#dv_sc_parties").hide();

    document.getElementById('ddl_court').value = "";
    document.getElementById('ddl_st_agncy').value = "";
    document.getElementById('ddl_bench').value = "";
    if (if_sclsc.checked == true){
        document.getElementById("if_sclsc").checked = false;
        document.getElementById('txt_sclsc_no').value = "";
        document.getElementById('ddl_sclsc_yr').value = "";
    }
    if(document.getElementById('type_special').value == "6"){
        document.getElementById('txt_doc_signed').value = "";
        $('#sp_doc_signed').css('display','none');
    }
    if ( document.getElementById("is_ac").checked == true){
        document.getElementById("is_ac").checked = false;
    }
    if (if_efil.checked == true){
        document.getElementById("if_efil").checked = false;
        document.getElementById('txt_efil_no').value = "";
        document.getElementById('ddl_efil_yr').value = "";
    }
    document.getElementById('ddl_pet_adv_state').value = "";
    document.getElementById('padvt').value = "";

    //--------ddl_pet_adv_state;

   /* document.getElementById('cs_tp').value = "";
    document.getElementById('txtFNo').value = "";
    document.getElementById('txtYear').value = "";*/

    document.getElementById('ddl_nature').value = "";
    document.getElementById('section').value = "";
    document.getElementById('type_special').value = "";

    document.getElementById('case_doc').value = "";
    //alert(document.getElementById('selpt').value);
    document.getElementById('copy').checked=false;

    if (document.getElementById('selpt').value == 'I')
    {

        document.getElementById('pet_name').value = "";
        document.getElementById('selprel').value = "";
        document.getElementById('prel').value = "";
        document.getElementById('psex').value = "";
        document.getElementById('page').value = "";
        document.getElementById('pocc').value = "";
        document.getElementById('paddi').value = "";
        document.getElementById('pcityi').value = "";
        document.getElementById('ppini').value = "";
        document.getElementById('selpdisi').value="";
        document.getElementById('selpsti').value = "";
        document.getElementById('pmobi').value = "";
        document.getElementById('pemaili').value = "";
        document.getElementById('p_noi').value = "1";
    }
    else if (document.getElementById('selpt').value != 'I')
    {
        document.getElementById('pet_post').value = "";
        document.getElementById('pet_deptt').value = "";
        document.getElementById('pet_statename').value = "";
        document.getElementById('paddd').value = "";
        document.getElementById('pcityd').value = "";
        document.getElementById('ppind').value = "";
        document.getElementById('selpdisd').value = "";
        document.getElementById('selpstd').value = "";
        document.getElementById('pmobd').value = "";
        document.getElementById('pemaild').value = "";
        document.getElementById('p_nod').value = "1";
    }
    if (document.getElementById('selrt').value == 'I')
    {
        document.getElementById('res_name').value = "";
        document.getElementById('selrrel').value = "";
        document.getElementById('rrel').value = "";
        document.getElementById('rsex').value = "";
        document.getElementById('rage').value = "";
        document.getElementById('rocc').value = "";
        document.getElementById('raddi').value = "";
        document.getElementById('rcityi').value = "";
        document.getElementById('rpini').value = "";
        document.getElementById('selrdisi').value = "";
        document.getElementById('selrsti').value = "23";
        document.getElementById('rmobi').value = "";
        document.getElementById('remaili').value = "";
        document.getElementById('r_noi').value = "1";
    }
    else if (document.getElementById('selrt').value != 'I')
    {
        document.getElementById('res_post').value = "";
        document.getElementById('res_deptt').value = "";
        document.getElementById('res_statename').value = "";
        document.getElementById('raddd').value = "";
        document.getElementById('rcityd').value = "";
        document.getElementById('rpind').value = "";
        document.getElementById('selrdisd').value = "";
        document.getElementById('selrstd').value = "23";
        document.getElementById('rmobd').value = "";
        document.getElementById('remaild').value = "";
        document.getElementById('r_nod').value = "1";
    }
    document.getElementById('selpt').value = 'I';
    document.getElementById('selrt').value = 'I';
//    document.getElementById('selct').value='-1'
//    document.getElementById('case_doc').value='';
    document.getElementById('padvno').value = "";
    document.getElementById('padvyr').value = "";
    document.getElementById('padvname').value = "";
    document.getElementById('padvmob').value = "";
    document.getElementById('padvemail').value = "";
    document.getElementById('radvno').value = "";
    document.getElementById('radvyr').value = "";
    document.getElementById('radvname').value = "";
    document.getElementById('radvmob').value = "";
    document.getElementById('radvemail').value = "";
    document.getElementById('for_I_p').style.display = 'block';
    document.getElementById('for_D_p').style.display = 'none';
    document.getElementById('for_I_r').style.display = 'block';
    document.getElementById('for_D_r').style.display = 'none';
}

function chk_cat_low(lst_case, txtcaseno, txtyear, fil_no)
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            // var txtAdeshika="txtAdeshika"+rowCount;
            document.getElementById('hdd_low_cc').innerHTML = xmlhttp.responseText;
            var s_op = '';
            var ccl = document.getElementById('hd_low_ca').value;
            if (ccl <= 0)
            {
                s_op = 'spsubsubmenu_1';
            }
            else if (ccl > 0)
            {
                s_op = 'spsubsubmenu_2';
            }
            h1_bak('spsubmenu_2', s_op, lst_case, txtcaseno, txtyear, fil_no, '', '1');
        }
    }
// xmlhttp.open("GET","getReport51-II.php?seljudgename="+seljudgename+"&frm="+frm+"&toDate="+toDate,true);
    xmlhttp.open("GET", "get_chk_cat_low.php?fil_no_bak_cc=" + fil_no, true);
    xmlhttp.send(null);
}

function check_for_right_selection(id)
{
    var input_string = $("#" + id).val().split('->');
    if (!isNaN(input_string[0]))
    {
        $("#" + id).focus();
        $("#" + id).get(0).setSelectionRange(0, 0);
    }
    else
    {
        alert("Proper Department was Not Selected, the Box will gone Empty");
        $("#" + id).val("");
    }
}

function getDistrict(side, id, val,hd_city) {
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            //alert(this.responseText);

            if (side == 'P') {
                if (id == 'selpsti')
                {
                    document.getElementById('selpdisi').innerHTML = xmlhttp.responseText;
                    document.getElementById('selpdisi').value=hd_city;
                }
                else if (id == 'selpstd')
                {
                    document.getElementById('selpdisd').innerHTML = xmlhttp.responseText;
                    document.getElementById('selpdisd').value=hd_city;
                }
            }
            else if (side == 'R') {
                if (id == 'selrsti')
                {
                    document.getElementById('selrdisi').innerHTML = xmlhttp.responseText;
                    document.getElementById('selrdisi').value=hd_city;
                }
                else if (id == 'selrstd')
                {
                    document.getElementById('selrdisd').innerHTML = xmlhttp.responseText;
                    document.getElementById('selrdisd').value=hd_city;
                }
            }
        }
    }
    xmlhttp.open("GET", "get_district.php?state=" + val, true);
    xmlhttp.send(null);
}

function get_sc_District(side, id, val,hd_city) {
    
  // alert(side+id+val+hd_city);

   // alert(side+'/'+id+'/'+val+'/'+hd_city);
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
           // alert(xmlhttp.responseText);
           // alert(side);
            if (side == 'P') {
                if (id == 'selpsti')
                {
                    document.getElementById('selpdisi').innerHTML = xmlhttp.responseText;
                    document.getElementById('selpdisi').value=hd_city;
                }
                else if (id == 'selpstd')
                {
                    document.getElementById('selpdisd').innerHTML = xmlhttp.responseText;
                    document.getElementById('selpdisd').value=hd_city;
                }
            }
            else if (side == 'R') {
                if (id == 'selrsti')
                {
                    document.getElementById('selrdisi').innerHTML = xmlhttp.responseText;
                    document.getElementById('selrdisi').value=hd_city;
                }
                else if (id == 'selrstd')
                {
                    document.getElementById('selrdisd').innerHTML = xmlhttp.responseText;
                    document.getElementById('selrdisd').value=hd_city;
                }
            }
        }
    }
    xmlhttp.open("GET", "get_sc_district.php?state=" + val+"&city="+hd_city, true);
    xmlhttp.send(null);
}

function get_case_no()
{
    $('.dv_nw_efi_no').css('display', 'none');

    var txt_p_no = $('#txt_p_no').val();
    var txt_yr = $('#txt_yr').val();
    if (txt_p_no == '' || txt_yr == '')
    {
        if (txt_p_no == '')
        {
            alert("Please enter Provisional No.");
            $('#txt_p_no').focus();
        }
        else if (txt_yr == '')
        {
            alert("Please enter Provisional Year");
            $('#txt_yr').focus();
        }

    }
    else
    {
        call_getDetails(txt_p_no, txt_yr)
//    $.ajax({
//                            url:'get_case_no.php',
//                            type:"GET",
//                            cache:false,
//                            async:true,
//                            beforeSend:function(){
//                                $('#show_fil').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
//                            },
//                            data:{txt_p_no:txt_p_no,txt_yr:txt_yr},
//                            success:function(data,status){
//                          
//                            $('#dv_efiling').html(data);
//                            var hd_f_f_no=$('#hd_f_f_no').val();
//                            if(hd_f_f_no==1)
//                                {
//                                    var hd_res_e_fil=$('#hd_res_e_fil').val();
//                                      call_getDetails(hd_res_e_fil);
//                                        alert(hd_res_e_fil);
//                                }
//                            else  if(hd_f_f_no==0)
//                                {
//                                      $('#show_fil').css('text-align','center');
//                                     $('#show_fil').html('<b>No Record Found</b>');
//                                       
//                                }
//                                
////                                if(data=='No Record Found')
////                                    {
////                                         $('#show_fil').html(data);
////                                    }
////                                    else
////                                        {
////                                            call_getDetails(data)
////                                        }
//                            },
//                            error:function(xhr){
//                                alert("Error: "+xhr.status+' '+xhr.statusText);
//                            }
//                        });
    }
}

function call_getDetails(txt_p_no, txt_yr)
{
//    var ct = document.getElementById('selct').value;
//    var cn = document.getElementById('case_no').value;
//    var cy = document.getElementById('case_yr').value;
//    var bench = document.getElementById('bench').value;
//    if(ct=="-1")
//    {
//        alert('Please Select Case Type');document.getElementById('selct').focus();return false;
//    }
//    if(ct.length=='1')
//        ct = '00'+ct;
//    else if(ct.length=='2')
//        ct = '0'+ct;
//    if(cn=="")
//    {
//        alert('Please Enter Case No.');document.getElementById('case_no').focus();return false;
//    }
//    if(cy=="")
//    {
//        alert('Please Enter Case Year');document.getElementById('case_yr').focus();return false;
//    }
//    var fno = bench+ct+cn+cy;
//    var fno =data;
//    var bench=data.substr(0, 2);
//    alert(fno);
//    alert(bench);
//    document.getElementById('fil_hd').value=fno;
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';
    $('#show_fil').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            document.getElementById('show_fil').innerHTML = xmlhttp.responseText;
//           $('#selct').attr('disabled',true);
        }
    }
    var url = "get_filing_mod_efil.php?txt_p_no=" + txt_p_no + "&txt_yr=" + txt_yr;
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}



function call_save_main_efil(st_status)
{
    var txt_p_no = document.getElementById('txt_p_no').value;
    var txt_yr = document.getElementById('txt_yr').value;

//    var case_type = document.getElementById('selct');

    var pet_type = document.getElementById('selpt').value;
    var pet_name, pet_rel, pet_rel_name, pet_sex, pet_age, pet_post, pet_deptt, pet_add, pcity, pdis, pst, tpet;

    var res_type = document.getElementById('selrt').value;
    var res_name, res_rel, res_rel_name, res_sex, res_age, res_post, res_deptt, res_add, rcity, rdis, rst, tres;

//    if(case_type.value=='-1')
//    {
//        alert('Please Select Case Type');case_type.focus();return false;
//    }
    if (pet_type == "I")
    {
        pet_name = document.getElementById('pet_name');
        pet_rel = document.getElementById('selprel');
        pet_rel_name = document.getElementById('prel');
        pet_sex = document.getElementById('psex');
        pet_age = document.getElementById('page');
        pet_add = document.getElementById('paddi');
        pcity = document.getElementById('pcityi');
        pdis = document.getElementById('selpdisi');
        pst = document.getElementById('selpsti');
        tpet = document.getElementById('p_noi');
        if (pet_name.value == '')
        {
            alert('Please Enter Petitioner Name');
            pet_name.focus();
            return false;
        }
        if (pet_rel.value == '')
        {
            alert('Please Select Petitioner Relation');
            pet_rel.focus();
            return false;
        }
        if (pet_rel_name.value == '')
        {
            alert('Please Enter Petitioner Father/Husband Name');
            pet_rel_name.focus();
            return false;
        }
        if (pet_sex.value == '')
        {
            alert('Please Select Petitioner Sex');
            pet_sex.focus();
            return false;
        }
//        if(pet_age.value=='')
//        {
//            alert('Please Enter Petitioner Age');pet_age.focus();return false;
//        }
        if (pet_add.value == '')
        {
            alert('Please Enter Petitioner Address');
            pet_add.focus();
            return false;
        }
        if (pcity.value == '')
        {
            alert('Please Enter Petitioner City');
            pcity.focus();
            return false;
        }
        if (pdis.value == '')
        {
            alert('Please Select Petitioner District');
            pdis.focus();
            return false;
        }
        if (pst.value == '')
        {
            alert('Please Select Petitioner State');
            pst.focus();
            return false;
        }
        if (document.getElementById('pemaili').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('pemaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaili').focus();
                return false;
            }
        }
        if (tpet.value == '' || tpet.value == 0)
        {
            alert('Total Pet(s) could not be null or zero');
            tpet.focus();
            return false;
        }
    }
    if (pet_type != "I")
    {
        pet_post = document.getElementById('pet_post');
        pet_deptt = document.getElementById('pet_deptt');
        pet_add = document.getElementById('paddd');
        pcity = document.getElementById('pcityd');
        pdis = document.getElementById('selpdisd');
        pst = document.getElementById('selpstd');
        tpet = document.getElementById('p_nod');
        if (pet_post.value == '')
        {
            alert('Please Enter Petitioner Post');
            pet_post.focus();
            return false;
        }
        if (pet_deptt.value == '')
        {
            alert('Please Enter Petitioner Department');
            pet_deptt.focus();
            return false;
        }
        if (pet_add.value == '')
        {
            alert('Please Enter Petitioner Address');
            pet_add.focus();
            return false;
        }
        if (pcity.value == '')
        {
            alert('Please Enter Petitioner City');
            pcity.focus();
            return false;
        }
        if (pdis.value == '')
        {
            alert('Please Select Petitioner District');
            pdis.focus();
            return false;
        }
        if (pst.value == '')
        {
            alert('Please Select Petitioner State');
            pst.focus();
            return false;
        }
        if (document.getElementById('pemaild').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('pemaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('pemaild').focus();
                return false;
            }
        }
        if (tpet.value == '' || tpet.value == 0)
        {
            alert('Total Pet(s) could not be null or zero');
            tpet.focus();
            return false;
        }
    }


    if (res_type == "I")
    {
        res_name = document.getElementById('res_name');
        res_rel = document.getElementById('selrrel');
        res_rel_name = document.getElementById('rrel');
        res_sex = document.getElementById('rsex');
        res_age = document.getElementById('rage');
        res_add = document.getElementById('raddi');
        rcity = document.getElementById('rcityi');
        rdis = document.getElementById('selrdisi');
        rst = document.getElementById('selrsti');
        tres = document.getElementById('r_noi');
        if (res_name.value == '')
        {
            alert('Please Enter Respondent Name');
            res_name.focus();
            return false;
        }
        if (res_rel.value == '')
        {
            alert('Please Select Respondent Relation');
            res_rel.focus();
            return false;
        }
        if (res_rel_name.value == '')
        {
            alert('Please Enter Respondent Father/Husband Name');
            res_rel_name.focus();
            return false;
        }
        if (res_sex.value == '')
        {
            alert('Please Select Respondent Sex');
            res_sex.focus();
            return false;
        }
//        if(res_age.value=='')
//        {
//            alert('Please Enter Respondent Age');res_age.focus();return false;
//        }
        if (res_add.value == '')
        {
            alert('Please Enter Respondent Address');
            res_add.focus();
            return false;
        }
        if (rcity.value == '')
        {
            alert('Please Enter Respondent City');
            rcity.focus();
            return false;
        }
        if (rdis.value == '')
        {
            alert('Please Select Respondent District');
            rdis.focus();
            return false;
        }
        if (rst.value == '')
        {
            alert('Please Select Respondent State');
            rst.focus();
            return false;
        }
        if (document.getElementById('remaili').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('remaili').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaili').focus();
                return false;
            }
        }
        if (tres.value == '' || tres.value == 0)
        {
            alert('Total Res(s) could not be null or zero');
            tres.focus();
            return false;
        }
    }
    if (res_type != "I")
    {
        res_post = document.getElementById('res_post');
        res_deptt = document.getElementById('res_deptt');
        res_add = document.getElementById('raddd');
        rcity = document.getElementById('rcityd');
        rdis = document.getElementById('selrdisd');
        rst = document.getElementById('selrstd');
        tres = document.getElementById('r_nod');
        if (res_post.value == '')
        {
            alert('Please Enter Respondent Post');
            res_post.focus();
            return false;
        }
        if (res_deptt.value == '')
        {
            alert('Please Enter Respondent Department');
            res_deptt.focus();
            return false;
        }
        if (res_add.value == '')
        {
            alert('Please Enter Respondent Address');
            res_add.focus();
            return false;
        }
        if (rcity.value == '')
        {
            alert('Please Enter Respondent City');
            rcity.focus();
            return false;
        }
        if (rdis.value == '')
        {
            alert('Please Select Respondent District');
            rdis.focus();
            return false;
        }
        if (rst.value == '')
        {
            alert('Please Select Respondent State');
            rst.focus();
            return false;
        }
        if (document.getElementById('remaild').value != '')
        {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (document.getElementById('remaild').value.match(mailformat))
            {
                //return true;
            }
            else
            {
                alert('Please enter valid email');
                document.getElementById('remaild').focus();
                return false;
            }
        }
        if (tres.value == '' || tres.value == 0)
        {
            alert('Total Res(s) could not be null or zero');
            tres.focus();
            return false;
        }
    }

//    if(document.getElementById('padvname').value==''||document.getElementById('padvname').value==0)
//    {
//        alert('Please Enter Petitioner Advocate No and Year Properly');
//        document.getElementById('padvno').focus();return false;
//    }
//    if(document.getElementById('radvname').value==''||document.getElementById('radvname').value==0)
//    {
//        alert('Please Enter Respondent Advocate No and Year Properly');
//        document.getElementById('radvno').focus();return false;
//    }

   /* if (document.getElementById('padvemail').value != '')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (document.getElementById('padvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('padvemail').focus();
            return false;
        }
    } */
    if (document.getElementById('radvemail').value != '')
    {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (document.getElementById('radvemail').value.match(mailformat))
        {
            //return true;
        }
        else
        {
            alert('Please enter valid email');
            document.getElementById('radvemail').focus();
            return false;
        }
    }

    document.getElementById('svbtn').disabled = 'true';


    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    //document.getElementById('container').innerHTML = '<table widht="100%" align="center"><tr><td style=color:red><blink>Please Wait<blink></td></tr></table>';

    xmlhttp.onreadystatechange = function()
    {
        //debugger;
        if (xmlhttp.readyState == 4 )
        {
            if(xmlhttp.status == 200) {
                var res = xmlhttp.responseText;
                //alert(res);
                //alert(res.indexOf( 'Duplicate entry'));
                if (res.indexOf( 'Duplicate entry')>0){
                    alert('Duplicate entry.. Pleas click on Generate button again !!! ');
                    $('#svbtn').attr("disabled", false);
                }
                else{
                    //alert(res);
                    res = res.split('!~!');
                    //alert(res[1]);
                    //call_fullReset_main();
                    //$('#svbtn').attr("disabled", false);
                    document.getElementById('show_fil').innerHTML = res[1];
                }
            }

        }
    }
    var url = "save_new_filing.php?controller=I" + '&txt_p_no=' + txt_p_no + '&txt_yr=' + txt_yr + "&st_status=" + st_status;

    if (pet_type == "I")
        url = url + "&pname=" + pet_name.value + "&pet_rel=" + pet_rel.value + "&pet_rel_name=" + pet_rel_name.value + "&p_sex=" + pet_sex.value
            + "&p_age=" + pet_age.value + "&pocc=" + document.getElementById('pocc').value + "&pp=" + document.getElementById('ppini').value
            + "&pmob=" + document.getElementById('pmobi').value + "&pemail=" + document.getElementById('pemaili').value;
    if (pet_type != "I")
        url = url + "&pet_post=" + pet_post.value + "&pet_deptt=" + pet_deptt.value + "&pp=" + document.getElementById('ppind').value
            + "&pmob=" + document.getElementById('pmobd').value + "&pemail=" + document.getElementById('pemaild').value
            +"&pet_statename=" + document.getElementById('pet_statename').value+"&pet_statename_hd=" + document.getElementById('pet_statename_hd').value;

    url = url + "&padd=" + pet_add.value + "&pcity=" + pcity.value + "&pdis=" + pdis.value + "&pst=" + pst.value + "&p_type=" + pet_type;


    if (res_type == "I")
        url = url + "&rname=" + res_name.value + "&res_rel=" + res_rel.value + "&res_rel_name=" + res_rel_name.value + "&r_sex=" + res_sex.value
            + "&r_age=" + res_age.value + "&rocc=" + document.getElementById('rocc').value + "&rp=" + document.getElementById('rpini').value
            + "&rmob=" + document.getElementById('rmobi').value + "&remail=" + document.getElementById('remaili').value;
    if (res_type != "I")
        url = url + "&res_post=" + res_post.value + "&res_deptt=" + res_deptt.value + "&rp=" + document.getElementById('rpind').value
            + "&rmob=" + document.getElementById('rmobd').value + "&remail=" + document.getElementById('remaild').value
            +"&res_statename=" + document.getElementById('res_statename').value+"&res_statename_hd=" + document.getElementById('res_statename_hd').value;

    url = url + "&radd=" + res_add.value + "&rcity=" + rcity.value + "&rdis=" + rdis.value + "&rst=" + rst.value + "&r_type=" + res_type;

    if (document.getElementById('padvt').value == 'S')
        url = url + "&padvno=" + document.getElementById('padvno').value + "&padvyr=" + document.getElementById('padvyr').value
            + "&padvmob=" + document.getElementById('padvmob').value + "&padvemail=" + document.getElementById('padvemail').value;

    url = url + "&padvname=" + document.getElementById('padvname').value;

    if (document.getElementById('radvt').value == 'S')
        url = url + "&radvno=" + document.getElementById('radvno').value + "&radvyr=" + document.getElementById('radvyr').value
            + "&radvmob=" + document.getElementById('radvmob').value + "&radvemail=" + document.getElementById('radvemail').value;

    url = url + "&radvname=" + document.getElementById('radvname').value;

    url = url + "&padtype=" + document.getElementById('padvt').value + "&radtype=" + document.getElementById('radvt').value;

    url = url + "&pp_code=" + document.getElementById('pet_post_code').value + "&rp_code=" + document.getElementById('res_post_code').value
        + "&t_pet=" + tpet.value + "&t_res=" + tres.value
        + "&type_special=" + document.getElementById('type_special').value;

    /*var state_department_in_pet = $("#state_department_in_pet").val().split('->');
     var state_department_in_res = $("#state_department_in_res").val().split('->');

     url = url + "&pd_code=" + state_department_in_pet[0] + "&rd_code=" + state_department_in_res[0];*/

//    if(case_type.value=='52')
//    {
//        url = url+"&bailno="+document.getElementById('bno').value+"&subcat1=";
//        if(document.getElementById('rbtn4').checked)
//            url = url+"4";
//        else if(document.getElementById('rbtn5').checked)
//            url = url+"5";
//        else
//            url = url+"0";
//    }
    //alert(url);
    url = encodeURI(url);
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}

$(document).ready(function() {

$(document).on('change', '#section', function()
    {
        var section=document.getElementById('section').value;

        //   alert(casetype);
        if(section==40)
        {
            //  alert(" matters filed against supreme court judgement!!");
            $("#lct_casetype").hide();

        }
      

    });


$(document).on('change', '#ddl_nature', function()
         {
              var casetype=document.getElementById('ddl_nature').value;

           //   alert(casetype);
              if(casetype==9 || casetype==10 ||casetype==19||casetype==25||casetype==26 || casetype==20 || casetype==39)
              {
                //  alert(" matters filed against supreme court judgement!!");
                  $("#lct_casetype").show();

              }
             else {
                  $("#lct_casetype").hide();
                  $('#ddl_nature_sci').val('');
                  $('#no').val('');
                  $('#t_h_cyt').val('');
                  $('#diary_no').val('');
                  $('#dyr').val('');

              } 
              // alert("this is onchange function");
              f();

         });

    $(document).on('change', '#ddl_st_agncy,#ddl_court', function() {
        get_benches('0');
    });
    $(document).on('change', '#ddl_bench', function() {
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_bench = $('#ddl_bench').val();
        var ddl_court=$('#ddl_court').val();
        $.ajax({
            url: 'get_case_strc.php',
            cache: false,
            async: true,
            data: {ddl_st_agncy: ddl_st_agncy, ddl_bench: ddl_bench,ddl_court:ddl_court},
            beforeSend: function() {
                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="../images/preloader.gif"/></td></tr></table>');
            },
            type: 'POST',
            success: function(data, status) {

                $('#dv_case_no').html(data);
                $("#dv_case_no").show();


            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });

    $(document).on('click', '.cl_rdn_p', function() {

        var idd = $(this).attr('id');

        var sp_idd = idd.split('rdn_p');
        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var dis_id='';
        if(hd_ind_dep=='I')
            dis_id="selpsti";
        else
            dis_id="selpstd";

        getDistrict('P', dis_id, hd_state,hd_city);

        var rdn_p_r = $('#rdn_p' + sp_idd[1]).val();
//        alert('sdsdsd' + rdn_p_r);
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();

        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
//        var hd_state = $('#hd_state' + sp_idd[1]).val();
//        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
        var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        var hd_authcode=$('#hd_authcode'+ sp_idd[1]).val();
        $('.cl_rdn_p').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
            hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode,hd_authcode);

    });

    $(document).on('click', '.cl_rdn_p1', function() {

        var idd = $(this).attr('id');

        var sp_idd = idd.split('rdn_p');
        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var dis_id='';
        if(hd_ind_dep=='I')
            dis_id="selpsti";
        else
            dis_id="selpstd";

        get_sc_District('P', dis_id, hd_state,hd_city);

        var rdn_p_r = $('#rdn_p' + sp_idd[1]).val();
//        alert('sdsdsd' + rdn_p_r);
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();

        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
//        var hd_state = $('#hd_state' + sp_idd[1]).val();
//        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
        var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        var hd_authcode=$('#hd_authcode'+ sp_idd[1]).val();
        $('.cl_rdn_p').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
            hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode,hd_authcode);

    });




    $(document).on('click', '.cl_rdn_r', function() {
        var idd = $(this).attr('id');
        var sp_idd = idd.split('rdn_r');

        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var dis_id='';
        if(hd_ind_dep=='I')
            dis_id="selrsti";
        else
            dis_id="selrstd";

        get_sc_District('R', dis_id, hd_state,hd_city);

        var rdn_p_r = $('#rdn_r' + sp_idd[1]).val();
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();

        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
//        var hd_state = $('#hd_state' + sp_idd[1]).val();
//        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
        var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        var hd_authcode=$('#hd_authcode'+ sp_idd[1]).val();
        $('.cl_rdn_r').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
            hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode,hd_authcode);
    });



    $(document).on('click', '.cl_rdn_r1', function() {
       // alert("hello");
        var idd = $(this).attr('id');
        var sp_idd = idd.split('rdn_r');

        var hd_state = $('#hd_state' + sp_idd[1]).val();
        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_ind_dep = $('#hd_ind_dep' + sp_idd[1]).val();
        var dis_id='';
        if(hd_ind_dep=='I')
            dis_id="selrsti";
        else
            dis_id="selrstd";
       // alert(dis_id+hd_state+hd_city);

        getDistrict('R', dis_id, hd_state,hd_city);

        var rdn_p_r = $('#rdn_r' + sp_idd[1]).val();
        var f_no = $('#hd_fil_no' + sp_idd[1]).val();
        var hd_pet_res = $('#hd_pet_res' + sp_idd[1]).val();
        var hd_sr_no = $('#hd_sr_no' + sp_idd[1]).val();
        var sp_partyname = $('#sp_partyname' + sp_idd[1]).html();

        var hd_sonof = $('#hd_sonof' + sp_idd[1]).val();
        var hd_prfhname = $('#hd_prfhname' + sp_idd[1]).val();
        var hd_sex = $('#hd_sex' + sp_idd[1]).val();
        var hd_age = $('#hd_age' + sp_idd[1]).val();
        var hd_addr1 = $('#hd_addr1' + sp_idd[1]).val();
        var hd_addr2 = $('#hd_addr2' + sp_idd[1]).val();
        var hd_dstname = $('#hd_dstname' + sp_idd[1]).val();
        var hd_pin = $('#hd_pin' + sp_idd[1]).val();
//        var hd_state = $('#hd_state' + sp_idd[1]).val();
//        var hd_city = $('#hd_city' + sp_idd[1]).val();
        var hd_contact = $('#hd_contact' + sp_idd[1]).val();
        var hd_email = $('#hd_email' + sp_idd[1]).val();
        var hd_deptcode=$('#hd_deptcode'+ sp_idd[1]).val();
        var hd_authcode=$('#hd_authcode'+ sp_idd[1]).val();
        $('.cl_rdn_r').each(function() {
            if (idd == $(this).attr('id'))
                $(this).prop('checked', true);
            else
                $(this).prop('checked', false);
        });
        get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex, hd_age, hd_addr1, hd_addr2,
            hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode,hd_authcode);
    });



    $(document).on('change', '#ddl_court', function() {

        var idd= $(this).val();

        if(idd=='4')
        {
            $('#ddl_st_agncy').val('490506');

            get_benches('1');
        }
    });
//      $(document).on('click','#chk_undertaking',function(){
//       
//          if($(this).is(':checked'))
//              {
////                  $('#txt_undertakig').attr('disabled',false);
//                  $('#ddl_doc_u').attr('disabled',false);
//              }
//              else 
//                  {
////                       $('#txt_undertakig').attr('disabled',true);
//                        $('#ddl_doc_u').attr('disabled',true);
//                  }
//                  $('#txt_undertakig').val(''); 
//                  $('#ddl_doc_u').val('');
//      });

//      $(document).on('change','#ddl_doc_u',function(){
//          var ddl_doc_u=$('#ddl_doc_u').val();
//          if(ddl_doc_u=='10')
//              {
//              $('#txt_undertakig').attr('disabled',false);
//               $('#txt_undertakig').focus();
//              }
//          else 
//             $('#txt_undertakig').attr('disabled',true); 
//          $('#txt_undertakig').val('');
//      });
    $(document).on('change','#ddl_pet_adv_state,#ddl_res_adv_state',function(){
        var idd=  $(this).attr('id');
        if(idd=='ddl_pet_adv_state')
        {
            $('#padvno').val('');
            $('#padvyr').val('');
            $('#padvname').val('');
            $('#padvmob').val('');
            $('#padvemail').val('');
            if( $(this).val()=='')
            {
                $('#padvno').attr('disabled',true);
                $('#padvyr').attr('disabled',true);
                $('#padvname').attr('disabled',true);
                $('#padvmob').attr('disabled',true);
                $('#padvemail').attr('disabled',true);
            }
            else
            {
                $('#padvno').attr('disabled',false);
                $('#padvyr').attr('disabled',false);
                $('#padvname').attr('disabled',false);
                $('#padvmob').attr('disabled',false);
                $('#padvemail').attr('disabled',false);
            }
        }
        else if(idd=='ddl_res_adv_state')
        {
            $('#radvno').val('');
            $('#radvyr').val('');
            $('#radvname').val('');
            $('#radvmob').val('');
            $('#radvemail').val('');
            if( $(this).val()=='')
            {
                $('#radvno').attr('disabled',true);
                $('#radvyr').attr('disabled',true);
                $('#radvname').attr('disabled',true);
                $('#radvmob').attr('disabled',true);
                $('#radvemail').attr('disabled',true);
            }
            else
            {
                $('#radvno').attr('disabled',false);
                $('#radvyr').attr('disabled',false);
                $('#radvname').attr('disabled',false);
                $('#radvmob').attr('disabled',false);
                $('#radvemail').attr('disabled',false);
            }
        }
    });

    $(document).on('click','#ad_address,#ad_address_r',function(){

        var idd=$(this).attr('id');
//          alert(idd);
        var p_r='';
        if(idd=='ad_address')
        {
            $('#ad_address').attr('disabled',true);
            var hd_add_address=$('#hd_add_address').val();
            p_r='P';
        }
        else if(idd=='ad_address_r')
        {
            $('#ad_address_r').attr('disabled',true);
            var hd_add_address=$('#hd_add_address_r').val();
            p_r='R';
        }
        $.ajax({
            url: 'additional_address.php',
            cache: false,
            async: true,
            data: {hd_add_address: hd_add_address,p_r:p_r},

            type: 'POST',
            success: function(data, status) {

                if(idd=='ad_address')
                {
                    $('#dv_add_parties').append(data);
                    $('#hd_add_address').val(parseInt(hd_add_address)+1);
                    $('#ad_address').attr('disabled',false);
                }
                else if(idd=='ad_address_r')
                {
                    $('#dv_add_parties_r').append(data);
                    $('#hd_add_address_r').val(parseInt(hd_add_address)+1);
                    $('#ad_address_r').attr('disabled',false);
                }


            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    });
    $(document).on('change','#type_special',function(){
        var v_val=$(this).val();
        if(v_val==6)
            $('#sp_doc_signed').css('display','inline');
        else
            $('#sp_doc_signed').css('display','none');
        $('#txt_doc_signed').val('');
    });
});

function get_benches(str)
{

    var ddl_st_agncy = $('#ddl_st_agncy').val();
    var ddl_court=$('#ddl_court').val();
    if(ddl_st_agncy!='' && ddl_court!='')

    {

        $.ajax({
            url: 'get_bench.php',
            cache: false,
            async: true,
            data: {ddl_st_agncy: ddl_st_agncy,ddl_court:ddl_court},
//            beforeSend: function () {
//                $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="preloader.gif"/></td></tr></table>');
//            },
            type: 'POST',
            success: function(data, status) {

                $('#ddl_bench').html(data);
                if(str==1)
                {
                    $('#ddl_bench').val('10000');
                    $('#ddl_st_agncy').attr('disabled',true);
                }
                else
                {
                    $('#ddl_bench').val('');
//                               $('#ddl_st_agncy').val('')
                    $('#ddl_st_agncy').attr('disabled',false);
                }

            },
            error: function(xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
}

function getDetails()
{
    //call_fullReset_main();

    var hd_mn = $('#hd_mn').val();
    var cs_tp = $('#cs_tp').val();
    var txtFNo = $('#txtFNo').val();
    var txtYear = $("#txtYear").val();
    $.ajax({
        url: 'get_parties.php',
        cache: false,
        async: true,
        data: {hd_mn: hd_mn, txtFNo: txtFNo, txtYear: txtYear, cs_tp: cs_tp},
        beforeSend: function() {
            $('#dv_ent_z').html('<table widht="100%" align="center"><tr><td><img src="../images/preloader.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {

            $('#dv_parties').html(data);
            $("#dv_parties").show();
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}


function get_sc_Details()
{
   // call_fullReset_main();
    var ct=document.getElementById('ddl_nature_sci').value;
    var cno=document.getElementById('no').value;
    var cyr=document.getElementById('t_h_cyt').value;
    var dno=document.getElementById('diary_no').value;
    var dyr=document.getElementById('dyr').value;



    $.ajax({
        url: 'get_sc_parties.php',
        cache: false,
        async: true,
        data: {ct: ct, cno: cno, cyr: cyr, dno: dno, dyr: dyr},
        beforeSend: function() {
            $('#dv_ent_z').html('<table width="100%" align="center"><tr><td><img src="../images/preloader.gif"/></td></tr></table>');
        },
        type: 'POST',
        success: function(data, status) {
            $('#dv_sc_parties').html(data);
            $("#dv_sc_parties").show();
        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

function com_filingNo()
{
    var txtNo = document.getElementById('txtFNo').value;
    if (txtNo.length == "1")
    {
        txtNo = "0000" + txtNo;
    }
    else if (txtNo.length == "2")
    {
        txtNo = "000" + txtNo;
    }
    else if (txtNo.length == "3")
    {
        txtNo = "00" + txtNo;
    }
    else if (txtNo.length == "4")
    {
        txtNo = "0" + txtNo;
    }
    document.getElementById('txtFNo').value = txtNo;
}

function get_part_detail(f_no, hd_pet_res, hd_sr_no, sp_partyname, rdn_p_r, hd_ind_dep, hd_sonof, hd_prfhname, hd_sex,
                         hd_age, hd_addr1, hd_addr2, hd_dstname, hd_pin, hd_state, hd_city, hd_contact, hd_email,hd_deptcode,hd_authcode)
{

    var sp_hd_deptcode=hd_deptcode.split('->');
    if (rdn_p_r == 'P')
    {
     //  alert("petitioner");
        $('#selpt').val(hd_ind_dep);
        activate_main('selpt');
        if(hd_ind_dep=='I')
        {
            $('#pet_name').val(sp_partyname);

            $('#selprel').val(hd_sonof);
            $('#prel').val(hd_prfhname);
            $('#psex').val(hd_sex);
            $('#page').val(hd_age);
            $('#pocc').val(hd_addr1);
            $('#paddi').val(hd_addr2);
            $('#pcityi').val(hd_dstname);
            $('#ppini').val(hd_pin);
            $('#selpsti').val(hd_state);
            $('#selpdisi').val(hd_city);
            $('#pmobi').val(hd_contact);
            $('#pemaili').val(hd_email);
        }
        else  if(hd_ind_dep=='D1' || hd_ind_dep=='D2' || hd_ind_dep=='D3')
        {
            $('#pet_deptt').val(sp_partyname);
            if(hd_addr1=='')
                hd_addr1=sp_partyname;
            $('#pet_post').val(hd_addr1);
            //if(hd_ind_dep=='D1')  
            //$('#state_department_in_pet').val(hd_deptcode);
            $('#pet_deptt_code').val(sp_hd_deptcode[0]);
            $('#pes_post_code').val(hd_authcode);
            $('#paddd').val(hd_addr2);
            $('#pcityd').val(hd_dstname);
            $('#ppind').val(hd_pin);
            $('#selpstd').val(hd_state);
            $('#selpdisd').val(hd_city);
            $('#pmobd').val(hd_contact);
            $('#pemaild').val(hd_email);
        }
    }
    else if (rdn_p_r == 'R')
    {
    //    alert("respondent");
        $('#selrt').val(hd_ind_dep);
        activate_main('selrt');
        if(hd_ind_dep=='I')
        {
            $('#res_name').val(sp_partyname);

            $('#selrrel').val(hd_sonof);
            $('#rrel').val(hd_prfhname);
            $('#rsex').val(hd_sex);
            $('#rage').val(hd_age);
            $('#rocc').val(hd_addr1);
            $('#raddi').val(hd_addr2);
            $('#rcityi').val(hd_dstname);
            $('#rpini').val(hd_pin);
            //$('#selrstd').val(hd_state);
            $('#selrsti').val(hd_state);
            $('#selrdisi').val(hd_city);
            $('#rmobi').val(hd_contact);
            $('#remaili').val(hd_email);
        }
        else  if(hd_ind_dep=='D1' || hd_ind_dep=='D2' || hd_ind_dep=='D3')
        {
            $('#res_deptt').val(sp_partyname);
            if(hd_addr1=='')
                hd_addr1=sp_partyname;
            $('#res_post').val(hd_addr1);
            if(hd_ind_dep=='D1')
                $('#state_department_in_res').val(hd_deptcode);

            $('#res_deptt_code').val(sp_hd_deptcode[0]);
            $('#res_post_code').val(hd_authcode);

            $('#raddd').val(hd_addr2);
            $('#rcityd').val(hd_dstname);
            $('#rpind').val(hd_pin);
            $('#selrstd').val(hd_state);
            $('#selrdisd').val(hd_city);
            $('#rmobd').val(hd_contact);
            $('#remaild').val(hd_email);
        }
    }
}

function check_country(idd,str_val)
{
    var idd=idd.split('txt_counrty');
    if(str_val=='96')
    {
        $('#txt_state'+idd[1]).attr('disabled',false);
        $('#txt_district'+idd[1]).attr('disabled',false);
    }
    else
    {
        $('#txt_state'+idd[1]).attr('disabled',true);
        $('#txt_district'+idd[1]).attr('disabled',true);
    }
    $('#txt_state'+idd[1]).val('');
    $('#txt_district'+idd[1]).val('');
}
function get_additional_dis(idd,str_val)
{
    var idd=idd.split('txt_state');
    $.ajax({
        url: 'get_district.php',
        cache: false,
        async: true,
        data: {state: str_val},

        type: 'POST',
        success: function(data, status) {

            $('#txt_district'+idd[1]).html(data);

        },
        error: function(xhr) {
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }

    });
}

$(document).ready(function(){

    $("#lct_casetype").hide();
  //  alert("hello");

    $(document).on('click','#if_sclsc',function(){
        if($(this).is(':checked'))
        {
            $('#sp_no_yr').css('display','inline');
        }
        else
        {
            $('#sp_no_yr').css('display','none');
        }
        $('#txt_sclsc_no').val('');
        $('#ddl_sclsc_yr').val('');
    });
    $(document).on('click','#if_efil',function(){
        if($(this).is(':checked'))
        {
            $('#sp_efil').css('display','inline');
        }
        else
        {
            $('#sp_efil').css('display','none');
        }
        $('#txt_efil_no').val('');
        $('#ddl_efil_yr').val('');
    });
});

function f()

{
  //  alert("this is a function for showing tentative section of a matter when diarized");

   var court= document.getElementById('ddl_court').value;
   var state= document.getElementById('ddl_st_agncy').value;
   var bench= document.getElementById('ddl_bench').value;
   var nature=document.getElementById('ddl_nature').value;
   var data = court+"-"+state+"-"+bench+"-"+nature;


    if (window.XMLHttpRequest)
    {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            //alert(this.responseText);

          //    document.getElementById("sec").value = this.responseText;
         document.getElementById('section').value=this.responseText;


        }
    };
var url = "getsection.php?q="+data;


    xmlhttp.open("GET",url,true);
    xmlhttp.send();

}

function f1()
{
//alert(" this is save button");

var  url;

    var ct=document.getElementById('ddl_nature_sci').value;
    var cno=document.getElementById('no').value;
    var cyr=document.getElementById('t_h_cyt').value;
    var data=ct+"/"+cno+"/"+cyr;
    var dno=document.getElementById('diary_no').value;
    var dyr=document.getElementById('dyr').value;

    if((dno !=''))
    {
              // it means diary no is selected
        data1=dno+dyr;
         url = "getdiary_no.php?q="+data1;

    }
    else

        {
          url = "getdiary_no.php?q=" + data;
        }

    if (window.XMLHttpRequest)
    {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
            if (this.readyState == 4 && this.status == 200) {
       //     alert(" section id is ="+this.responseText);

           // document.getElementById('section').value=this.responseText;
            $('#section').val(this.responseText.trim());
             if (this.responseText.trim() == '')
             {
               alert("No Section found in Main Matter !!!");
                 $("#section").removeAttr("disabled");


           }
                get_sc_Details();

        }
    };
   //  var url = "getdiary_no.php?q="+data;
   // alert(" url is ="+url);
    //alert(url);

    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}
