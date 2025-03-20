// JavaScript Document
 
 
//
$(document).on('keydown mouseenter mouseleave','.textonly', function(e){
    only_text(e);
});

$(document).on('keypress mouseenter mouseleave','.numbersonly', function(e){
    only_number(e);
});
function only_number(e)
{
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
        return false;
    }
}

function only_text(e)
{
	//$(this).parents('div .invalid-feedback').html('');
    var key = e.keyCode;

    if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90) || (key == 9)) ) {
        e.preventDefault();
    }
}
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 
    && (charCode < 48 || charCode > 57))
     return false;

  return true;
}
  
$(document).on('keyup','.removeStartSpaces',function(){
	var string = $(this).val();
	if(string.length > 0){
		var trimStr = string.trimStart();
		return $(this).val(trimStr);
	}
});


$(document).on('keypress mouseenter mouseleave','.alphanumeric', function(e){
	var code = e.which;
	if (!(code == 32) && // space
	!(code > 47 && code < 58) && // numeric (0-9)
	!(code > 64 && code < 91) && // upper alpha (A-Z)
	!(code > 96 && code < 123)) { // lower alpha (a-z)
		e.preventDefault();
	}		
});  


$(document).on('keypress mouseenter mouseleave','.alphanumericnospace', function(e){
	var code = e.which;
	if (!(code > 47 && code < 58) && // numeric (0-9)
	!(code > 64 && code < 91) && // upper alpha (A-Z)
	!(code > 96 && code < 123)) { // lower alpha (a-z)
		e.preventDefault();
	}		
});  


$(document).on('keydown mouseenter mouseleave','.textonlynospace', function(e){
    var key = e.keyCode;

    if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90) || (key == 9)) ) {
        e.preventDefault();
    }
});


$(document).on('keydown mouseenter mouseleave','.textonlyaddress_old', function(e){
	var code = e.which;
	console.log(code);
	if (!(code == 32) && // space
	!(code == 9) && // slash
	!(code == 191) && // slash
	!(code == 188) && // comma 
	!(code == 189) && // minus
	!(code == 8) && // backspace
	!(code > 47 && code < 58) && // numeric (0-9)
	!(code > 95 && code < 106) && // numeric (0-9)
	!(code > 64 && code < 91) && // upper alpha (A-Z)
	!(code > 96 && code < 123)) { // lower alpha (a-z)
		e.preventDefault();
	}	
	
    if ((code == 188 && e.shiftKey) || (code == 189 && e.shiftKey) || (code == 191 && e.shiftKey) || ( (code > 50 && e.shiftKey) && (code < 57 && e.shiftKey)) || (code == 49 && e.shiftKey) || (code == 110)|| (code == 106)|| (code == 107) ) {
        e.preventDefault();
    }	
});


$(document).on('keydown mouseenter mouseleave','.textonlyaddress', function(e){
	var code = e.which;
	//console.log(code);
	
    if (
		(code == 50 && e.shiftKey) ||
		(code == 51 && e.shiftKey) ||
		(code == 52 && e.shiftKey) ||
		(code == 53 && e.shiftKey) ||
		(code == 54 && e.shiftKey) ||
		(code == 56 && e.shiftKey) ||
		(code == 186 && e.shiftKey) ||
		(code == 222 && e.shiftKey) ||
		(code == 106) 
	) {
        e.preventDefault();
    }	
	
});



$(document).on('keypress mouseenter mouseleave','.decimalonly', function(event){
   return isNumber(event, this);
});

function isNumber(evt, element) {
var charCode = (evt.which) ? evt.which : event.keyCode
	if (
		(charCode != 45 || $(element).val().indexOf('-') != -1) &&      // â€œ-â€ CHECK MINUS, AND ONLY ONE.
		(charCode != 46 || $(element).val().indexOf('.') != -1) &&      // â€œ.â€ CHECK DOT, AND ONLY ONE.
		(charCode < 48 || charCode > 57))
		return false;

	return true;
}