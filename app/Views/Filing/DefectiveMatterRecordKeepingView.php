<?= view('header') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header heading">
            <div class="row">
              <div class="col-sm-10">
                <h3 class="card-title">Filing Trap</h3>
              </div>
              <div class="col-sm-2">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">Defective Matter Record Keeping Form</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" id="ucode" name="ucode" value="<?php echo $_SESSION['login']['usercode'];?>">
                        <div class="row">
                          <div class="col-md-3">
                            <label for="dno"><b>Diary No.</b></label>
                            <input type="text" id="dno" name="dno" maxlength="10" class="form-control" size="10" onblur="getSearchResult();" onkeypress="return isNumber(event)" />
                          </div>

                          <div class="col-md-3">
                            <label for="dyr"><b>Diary Year</b></label>
                            <td>
                            <?php $year = 1950;
                            $current_year = date('Y');
                            ?>
                            <select name="dyr" id="dyr" class="custom-select rounded-0" onchange="getSearchResult();">
                                <option value="">--Select--</option>
                                <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                    <option <?php echo ($x == $current_year) ? 'selected' : ''; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                                <?php } ?>
                            </select>  
                            
                            <!-- <input type="text" id="dyr" name="dyr" maxlength="4" class="form-control" size="4" value="<?php //echo date('Y'); ?>" onkeypress="return isNumber(event)" /> -->
                          </div>

                          <div class="col-md-3">
                            <label for="section"><b>Section</b></label>
                            <input type="text" id="section" maxlength="10" size="10" class="form-control" readonly />
                          </div>

                          <div class="col-md-3">
                            <label for="courtfee"><b>Court Fees</b></label>
                            <input type="text" id="courtfee" maxlength="10" size="10" class="form-control" readonly />
                          </div>

                          <div class="col-md-3">
                            <label for="notfdate"><b>Date of Notification</b></label>
                            <input type="text" id="notfdate" maxlength="10" size="10" class="form-control" readonly />
                          </div>

                          <div class="col-md-3">
                            <label for="rackno"><b>Rack No.</b></label>
                            <input type="text" id="rackno" maxlength="10" size="10" class="form-control" onkeypress="return isNumber(event)" />
                          </div>

                          <div class="col-md-3">
                            <label for="shelfno"><b>Shelf No.</b></label>
                            <input type="text" id="shelfno" maxlength="10" size="10" class="form-control" onkeypress="return isNumber(event)" />
                          </div>
                          <div class="col-12 text-center">
                            <button type="button" id="save" class="btn btn-primary mt-5" onclick="call_save_main()">Save</button>
                          </div>
                        </div>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

  function getSearchResult()
  {
    var diaryno=$('#dno').val();
    var diaryyear=$('#dyr').val();

    if($('#dno').val()==''){
        alert("Please enter diary no.");
        //$('#dno').focus();
        return false;
    }

    if($('#dyr').val()==''){
        alert("Please select diary year!!");
        $('#dyr').focus();
        return false;
    }
   
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var vcal = xmlhttp.responseText;
            if (vcal != '0' && vcal !='1')
            {
              console.log(vcal);
                vcal = vcal.split('~');
                document.getElementById('section').value = vcal[0];
                document.getElementById('courtfee').value = vcal[1];
                document.getElementById('notfdate').value = vcal[2];

            }
            if(vcal=='1'){
                alert("Record already exist!!!!!");
                //document.getElementById('dno').focus();
                document.getElementById('section').value = "";
                document.getElementById('courtfee').value ="";
                document.getElementById('notfdate').value = "";
                document.getElementById('rackno').value ="";
                document.getElementById('shelfno').value = "";
                return false;
            }
            if(vcal=='0'){
                document.getElementById('section').value = "";
                document.getElementById('courtfee').value ="";
                document.getElementById('notfdate').value = "";
                document.getElementById('rackno').value ="";
                document.getElementById('shelfno').value = "";
                alert("No record found!!!!!");
            }
        }
    }
    var url = "<?php echo base_url('Filing/DefectiveMatter/GetMatterInfo'); ?>?module=add"+"&dno=" + diaryno + "&dyr=" + diaryyear;
    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}

function call_save_main(){
    var dno = $('#dno').val();
    var dyr = $('#dyr').val();
    var rackno = $('#rackno').val();
    var shelfno = $('#shelfno').val();
    var section = $('#section').val();
    var courtfee = $('#courtfee').val();
    var notfdate = $('#notfdate').val();
    let ucode = $('#ucode').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


    if (dno == '')
    {
        alert("Please enter diary no.");
       // $('#dno').focus();
        return false;
    }
    if (dyr == '')
    {
        alert("Please enter diary year");
        //$('#dyr').focus();
        return false;
    }

    if (section == '')
    {
        alert("Section cannot be blank!! Please enter valid Diary no.");
        $('#section').focus();
        return false;
    }
    if (courtfee == '')
    {
        alert("Section cannot be blank!! Please enter valid Diary no.");
        $('#courtfee').focus();
        return false;
    }
    if (notfdate == '')
    {
        alert("Section cannot be blank!! Please enter valid Diary no.");
        $('#notfdate').focus();
        return false;
    }
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
            var vcal = xmlhttp.responseText;
            alert(vcal);
            //document.getElementById('dno').focus();
            document.getElementById('dno').value = "";
            document.getElementById('section').value = "";
            document.getElementById('courtfee').value ="";
            document.getElementById('notfdate').value = "";
            document.getElementById('rackno').value ="";
            document.getElementById('shelfno').value = "";
        }
    }

    var url = "<?php echo base_url('Filing/DefectiveMatter/saveRecord');?>?controller=I"+"&dno=" + dno+dyr+ "&section=" + section+ "&courtfee=" + courtfee+ "&notfdate=" + notfdate+ "&rackno=" + rackno+ "&shelfno=" + shelfno + "&CSRF_TOKEN=" + CSRF_TOKEN_VALUE + "&ucode=" +ucode;

    xmlhttp.open("GET", url, false);
    xmlhttp.send(null);
}
</script>