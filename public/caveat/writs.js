function insert_advocate()
{

    var cavno=document.getElementById('cavno').value;
    var cavyr=document.getElementById('cavyr').value;
    var advocate_id=document.getElementById('aorcode').value;
    var remarks=document.getElementById('remarks').value;
 if(remarks=='')
 {
     alert("Remarks cannot be empty");
     return;
 }
   // alert(advocate_id);
    var caveatno=cavno+cavyr;
    if(cavno =='')
    {
        alert("Enter Diary  no.");
        document.getElementById('cavno').focus();
        return;
    }
    if(cavyr=='')
    {
        alert("Enter Diary year");
        document.getElementById('cavyr').focus();
        return;
    }
    var result = confirm("Are you sure to add caveator name?");
    if (result) {
        $('#button').attr('disabled',true);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                //   alert(this.responseText);
                document.getElementById('txtHint').innerHTML = this.responseText;
                $('#button').attr('disabled',false);

            }
        };
        var url = base_url+"/Filing/Caveat/add_adv_caveat?d1=" + caveatno +'&d2='+advocate_id +'&remarks='+remarks;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
}

		
function check(id) {
 
    $('#txtHint').html('');
    $('#div_result').html('');
    $('#button').attr('disabled',false);
    if (id == 1) {
        var cav_no = document.getElementById('cavno').value;
        var cav_yr = document.getElementById('cavyr').value;
        if(cav_no =='')
        {
            alert("Enter Diary no.");
            document.getElementById('cavno').focus();
            return;
        }
        if(cav_yr=='')
        {
            alert("Enter Diary year");
            document.getElementById('cavyr').focus();
            return;
        }
		var CSRF_TOKEN = 'CSRF_TOKEN';
            var csrf = $("input[name='CSRF_TOKEN']").val();
        $.ajax({
            url: base_url+'/Filing/Diary/get_diary_info',
			method: 'POST',
            cache: false,
            async: true,
            data: {CSRF_TOKEN:csrf,cav_no: cav_no, cav_yr: cav_yr},
			
           
            success: function (data) {
				updateCSRFToken();
                if(data=='Case not found!!'){
                    $('#button').attr('disabled',true);
                }
                else {
                    $('#div_result').html(data);
                    if(document.getElementById('hd_renew').value>0){
                        $('#button').attr('disabled',true);
                    }
                }

            },
            error: function (xhr) {
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }

        });
    }
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