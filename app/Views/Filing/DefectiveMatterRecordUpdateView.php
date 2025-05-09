<?= view('header'); ?>
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
                  <h4 class="basic_heading">Defective Matter Record Update Form</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form method="POST">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
                        <div class="row">
                          <div class="col-md-3">
                            <label for="dno"><b>Diary No.</b></label>
                            <input type="text" id="dno" maxlength="10" size="10" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="dyr"><b>Diary Year</b></label>
                            <input type="text" id="dyr" maxlength="4" size="4" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="section"><b>Section</b></label>
                            <input type="text" id="section" maxlength="10" size="10" readonly class="form-control" />
                          </div>

                          <div class="col-md-3">
                            <label for="courtfee"><b>Court Fees</b></label>
                            <input type="text" id="courtfee" maxlength="10" size="10" readonly class="form-control" />
                          </div>

                          <div class="col-md-3 mt-3">
                            <label for="notfdate"><b>Date of Notification</b></label>
                            <input type="text" id="notfdate" maxlength="10" size="10" readonly class="form-control" />
                          </div>

                          <div class="col-md-3 mt-3">
                            <label for="rackno"><b>Rack No.</b></label>
                            <input type="text" id="rackno" maxlength="10" size="10" onkeypress="return isNumber(event)" class="form-control" />
                          </div>

                          <div class="col-md-3 mt-3">
                            <label for="shelfno"><b>Shelf No.</b></label>
                            <input type="text" id="shelfno" maxlength="10" size="10" onkeypress="return isNumber(event)" class="form-control" />
                          </div>
                          <input type="hidden" id="a_id" name="a_id" />
                          <div class="col-12 text-center">
                            <input type="button" name="update" value="Update" onclick="update_data()" class="btn btn-primary mt-5" />
                            <input type="button" name="delete" value="Delete" onclick="delete_data()" class="btn btn-primary mt-5" />
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
  var dataLoaded = false;

  $(document).on("blur", "#dyr", function() {
    var diaryno = $('#dno').val();
    var diaryyear = $('#dyr').val();
    dataLoaded = false; // Reset flag when year changes

    if (diaryno == '') {
      alert("Please enter diary no.");
      $('#dno').focus();
      return false;
    }
    if (diaryyear == '') {
      alert("Please Enter Year.");
      $('#dno').focus();
      return false;
    }

    $.ajax({
      url: "<?php echo base_url('Filing/FileTrap/GetMatterInfo'); ?>",
      type: "GET",
      data: {
        module: 'update',
        dno: diaryno,
        dyr: diaryyear
      },
      success: function(response) {
        if (response != 0) {
          var vcal = response.split('~');
          $('#section').val(vcal[0]);
          $('#courtfee').val(vcal[1]);
          $('#notfdate').val(vcal[2]);
          $('#rackno').val(vcal[3]);
          $('#shelfno').val(vcal[4]);
          $('#a_id').val(vcal[5]);
          dataLoaded = true; // Set flag to true on successful load
        } else {
          clearFields();
          alert("No record found!!!!!");
          location.reload();
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error: " + status + ": " + error);
        dataLoaded = false;
      }
    });
  });

  function update_data() {
    // Check if data was loaded successfully
    if (!dataLoaded) {
      alert("Please first enter the  diary year.");
      return false;
    }

    // Rest of your existing validation and AJAX code
    var dno = $('#dno').val();
    var dyr = $('#dyr').val();
    var rackno = $('#rackno').val();
    var shelfno = $('#shelfno').val();
    var section = $('#section').val();
    var courtfee = $('#courtfee').val();
    var notfdate = $('#notfdate').val();
    var id = $('#a_id').val();

    // Input validation
    if (dno == '') {
      alert("Please enter diary no.");
      $('#dno').focus();
      return false;
    }
    if (dyr == '') {
      alert("Please enter diary year");
      $('#dyr').focus();
      return false;
    }
    if (rackno == '') {
      alert("Please enter rack no.");
      $('#rackno').focus();
      return false;
    }
    if (shelfno == '') {
      alert("Please enter shelf no.");
      $('#shelfno').focus();
      return false;
    }
    if (section == '') {
      alert("Section cannot be blank!! Please enter valid Diary no.");
      $('#section').focus();
      return false;
    }
    if (courtfee == '') {
      alert("Court fee cannot be blank!! Please enter valid Diary no.");
      $('#courtfee').focus();
      return false;
    }
    if (notfdate == '') {
      alert("Notification date cannot be blank!! Please enter valid Diary no.");
      $('#notfdate').focus();
      return false;
    }

    $.ajax({
      url: "<?php echo base_url('Filing/FileTrap/SaveRecord'); ?>",
      type: "GET",
      data: {
        controller: 'U',
        dno: dno + dyr,
        section: section,
        courtfee: courtfee,
        notfdate: notfdate,
        rackno: rackno,
        shelfno: shelfno,
        id: id
      },
      success: function(response) {
        if (response == 1) {
          alert("Record Updated Successfully");
          location.reload();
        } else {
          alert(response);
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error: " + status + ": " + error);
      }
    });
  }

  function clearFields() {
    $('#section').val("");
    $('#courtfee').val("");
    $('#notfdate').val("");
    $('#rackno').val("");
    $('#shelfno').val("");
    $('#a_id').val("");
  }


  function delete_data() {
    var dno = $('#dno').val();
    var dyr = $('#dyr').val();
    var rackno = $('#rackno').val();
    var shelfno = $('#shelfno').val();
    var section = $('#section').val();
    var courtfee = $('#courtfee').val();
    var notfdate = $('#notfdate').val();
    var id = $('#a_id').val();

    // Input validation
    if (dno === '') {
      alert("Please enter diary no.");
      $('#dno').focus();
      return false;
    }
    if (dyr === '') {
      alert("Please enter diary year");
      $('#dyr').focus();
      return false;
    }
    if (rackno === '') {
      alert("Please enter rack no.");
      $('#rackno').focus();
      return false;
    }
    if (shelfno === '') {
      alert("Please enter shelf no.");
      $('#shelfno').focus();
      return false;
    }
    if (section === '') {
      alert("Section cannot be blank!! Please enter a valid Diary no.");
      $('#section').focus();
      return false;
    }
    if (courtfee === '') {
      alert("Court fee cannot be blank!! Please enter a valid Diary no.");
      $('#courtfee').focus();
      return false;
    }
    if (notfdate === '') {
      alert("Notification date cannot be blank!! Please enter a valid Diary no.");
      $('#notfdate').focus();
      return false;
    }

    $.ajax({
      url: "<?php echo base_url('Filing/FileTrap/deleteRecord'); ?>",
      type: "GET",
      data: {
        controller: 'D',
        dno: dno + dyr,
        section: section,
        courtfee: courtfee,
        notfdate: notfdate,
        rackno: rackno,
        shelfno: shelfno,
        id: id
      },
      success: function(response) {
        if (response.trim() === "1") {
          alert("Record Deleted Successfully");
          $('#dno').val("").focus();
          $('#section').val("");
          $('#courtfee').val("");
          $('#notfdate').val("");
          $('#rackno').val("");
          $('#shelfno').val("");
          $('#a_id').val("");
        } else {
          alert(response);
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error: " + status + ": " + error);
        alert("An error occurred while processing your request.");
      }
    });
  }
</script>