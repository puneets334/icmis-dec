<?= view('header') ?>

<style>
  div.dataTables_wrapper div.dataTables_filter label {
    display: flex;
    justify-content: end;
  }

  div.dataTables_wrapper div.dataTables_filter label input.form-control {
    width: auto !important;
    padding: 4px;
  }
</style>
<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.css">

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header heading">
            <div class="row">
              <div class="col-sm-10">
                <h3 class="card-title">DYNAMIC REPORT</h3>
              </div>
              <div class="col-sm-2">

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
              <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                  <h4 class="basic_heading">DYNAMIC REPORT</h4>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane">
                      <form name="advanceQuery" id="advanceQuery" action="<?= base_url('DynamicReport/DynamicReport/getResult'); ?>" method="POST" onsubmit="return submitForm();">

                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />

                        <div class="container">
                          <div class="row text-center">
                            <div class="col-12">
                              <h4 class="">Select Search Parameters</h4>
                            </div>
                          </div>

                          <div class="row bg-grey-1 mt-3">
                            <div class="col-3"></div>
                            <div class="col-2">
                              <input type="radio" name="rbtCaseStatus" id="filing" value="f" checked="checked"> Filling
                            </div>
                            <div class="col-2">
                              <input type="radio" name="rbtCaseStatus" id="institution" value="i" checked="checked"> Registration
                            </div>
                            <div class="col-2">
                              <input type="radio" name="rbtCaseStatus" id="pending" value="p"> Pendency
                            </div>
                            <div class="col-2">
                              <input type="radio" name="rbtCaseStatus" id="disposal" value="d"> Disposal
                            </div>
                          </div>

                          <!-- Filing Date Row -->
                          <div class="row mt-3" id="filDate">
                            <div class="col-3">
                              <label for="filingDateFrom">Filing Date:</label>
                            </div>
                            <div class="col-4">
                              <label for="filingDateFrom">From:</label>
                              <input type="text" class="datepick form-control" name="filingDateFrom" id="filingDateFrom" placeholder="Select Filing From date" value="">
                            </div>
                            <div class="col-4">
                              <label for="filingDateTo">To:</label>
                              <input type="text" class="datepick form-control" name="filingDateTo" id="filingDateTo" placeholder="Select Filing To date" value="">
                            </div>
                          </div>

                          <!-- Registration Date Row -->
                          <div class="row mt-3" id="regDate">
                            <div class="col-3">
                              <label for="registrationDateFrom">Registration Date:</label>
                            </div>
                            <div class="col-4">
                              <label for="registrationDateFrom">From:</label>
                              <input type="text" class="form-control datepick" name="registrationDateFrom" id="registrationDateFrom" placeholder="Select Registration From date" value="">
                            </div>
                            <div class="col-4">
                              <label for="registrationDateTo">To:</label>
                              <input type="text" class="form-control datepick" name="registrationDateTo" id="registrationDateTo" placeholder="Select Registration To date" value="">
                            </div>
                          </div>

                          <!-- Pendency Type Row -->
                          <div class="row bg-grey-1 mt-3" id="pendency">
                            <div class="col-3">
                              <label>Pendency Type:</label>
                            </div>
                            <div class="col-9">
                              <input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="R">Registered
                              <input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="UR">Un-Registered
                              <input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="b" checked="checked"> Both
                            </div>
                          </div>

                          <!-- Case Year Row -->
                          <div class="row mt-3 mt-3">
                            <div class="col-3">
                              <label for="caseYear">Case Year:</label>
                            </div>
                            <div class="col-4">
                              <select id="caseYear" class="form-control" name="caseYear">
                                <option value="0">Select</option>
                                <?php for ($year = date('Y'); $year >= 1950; $year--)
                                  echo '<option value="' . $year . '">' . $year . '</option>';
                                ?>
                              </select>
                            </div>
                          </div>

                          <!-- Disposal Date Row -->
                          <div class="row mt-3" id="dispDate">
                            <div class="col-3">
                              <label for="disposalDateFrom">Disposal Date:</label>
                            </div>
                            <div class="col-4">
                              <label for="disposalDateFrom">From:</label>
                              <input type="text" class="datepick form-control" name="disposalDateFrom" id="disposalDateFrom" placeholder="Select Disposal From date" value="">
                            </div>
                            <div class="col-4">
                              <label for="disposalDateTo">To:</label>
                              <input type="text" class="datepick form-control" name="disposalDateTo" id="disposalDateTo" placeholder="Select Disposal To date" value="">
                            </div>
                          </div>

                          <!-- Case Type Row -->
                          <div class="row bg-grey-1 mt-3">
                            <div class="col-3">
                              <label for="rbtCaseType">Case Type:</label>
                            </div>
                            <div class="col-4">
                              <input type="radio" name="rbtCaseType" id="rbtCaseType" value="C" onclick="get_casetype()">Civil
                              <input type="radio" name="rbtCaseType" id="rbtCaseType" value="R" onclick="get_casetype()">Criminal
                              <input type="radio" name="rbtCaseType" id="rbtCaseType" value="b" checked="checked" onclick="get_casetype()"> Both
                            </div>
                            <div class="col-4">
                              <select id="caseType" class="form-control" name="caseType[]" multiple>
                                <option value="0" disabled>Select Multiple</option>
                              </select>
                            </div>
                          </div>

                          <!-- Matter Type Row -->
                          <div class="row bg-grey-1  mt-3">
                            <div class="col-3">
                              <label>Matter Type:</label>
                            </div>
                            <div class="col-9">
                              <input type="radio" name="matterType" id="Admission" value="M"> Admission
                              <input type="radio" name="matterType" id="Regular" value="F"> Regular
                              <input type="radio" name="matterType" id="Both" value="all" checked> Both
                            </div>
                          </div>

                          <!-- Party Name Row -->
                          <div class="row mt-3">
                            <div class="col-3">
                              <label for="respondentName">Party Name:</label>
                            </div>
                            <div class="col-3">
                              <input type="text" name="respondentName" placeholder="Enter full or part of name" id="respondentName" class="form-control" value="">
                              <input type="hidden" name="petitionerName" value="">
                            </div>
                            <div class="col-3">
                              <input type="radio" class="first" name="PorR" value="1">Petitioner
                              <input type="radio" class="first" name="PorR" value="2">Respondent
                              <input type="radio" class="first" name="PorR" value="0" checked="checked">Both
                            </div>
                            <div class="col-3">
                              <label for="caseYear">Filing Year</label>
                              <select id="diaryYear" name="diaryYear" class="form-control">
                                <option value="0">Select</option>
                                <?php
                                for ($year = date('Y'); $year >= 1950; $year--)
                                  echo '<option value="' . $year . '">' . $year . '</option>';
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="row bg-grey-1 mt-3">
                            <div class="col-3 text-left">
                              <label for="subjectCategory">Subject Category:</label>
                            </div>
                            <div class="col-3">
                              <select id="subjectCategory" name="subjectCategory" class="form-control" onchange="get_sub_sub_cat()">
                                <option value="0">All</option>
                                <?php
                                foreach ($MCategories as $MCategory)
                                  echo '<option value="' . $MCategory['subcode1'] . '^' . $MCategory['sub_name1'] . '" ' . (isset($_POST['subjectCategory']) && $_POST['subjectCategory'] == $MCategory['subcode1'] ? 'selected="selected"' : '') . '>' . $MCategory['subcode1'] . ' # ' . $MCategory['sub_name1'] . '</option>';
                                ?>
                              </select>
                            </div>
                            <div class="col-3">
                              <label for="subCategoryCode">Sub Category:</label>
                            </div>
                            <div class="col-3">
                              <select id="subCategoryCode" name="subCategoryCode" class="form-control">
                                <option value="0">All</option>
                              </select>
                            </div>
                          </div>

                          <div class="row mb-3 mt-3">
                            <div class="col-3">
                              <label for="chkJailMatter">Flag:</label>
                            </div>
                            <div class="col-9">
                              <div class="form-check form-check-inline">
                                <input type="checkbox" name="chkJailMatter" id="chkJailMatter" value="1" class="form-check-input">
                                <label class="form-check-label" for="chkJailMatter">Jail Matter</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input type="checkbox" name="chkFDMatter" id="chkFDMatter" value="1" class="form-check-input">
                                <label class="form-check-label" for="chkFDMatter">FD</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="chkLegalAid" id="chkLegalAid" value="1">
                                <label class="form-check-label" for="chkLegalAid">Legal Aid</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkSpecificDate" name="chkSpecificDate" value="1">
                                <label class="form-check-label" for="chkSpecificDate">Specific Date</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="chkPartHeard" name="chkPartHeard" value="1">
                                <label class="form-check-label" for="chkPartHeard">Part Heard</label>
                              </div>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-3 ">
                              <label for="section">Section:</label>
                            </div>
                            <div class="col-2">
                              <select class="form-control" id="section" name="section" onchange="get_da()">
                                <option value="0">All</option>
                                <?php
                                foreach ($Sections as $Section)
                                  echo '<option value="' . $Section['id'] . '^' . $Section['section_name'] . '" ' . (isset($_POST['section']) && $param[5] == $Section['section_name'] ? 'selected="selected"' : '') . '>' . $Section['section_name'] . '</option>';
                                ?>
                              </select>
                            </div>

                            <div class="col-1">
                              <label for="dealingAssistant">Dealing Assistant:</label>
                            </div>
                            <div class="col-2">
                              <select class="form-control" id="dealingAssistant" name="dealingAssistant">
                                <option value="0">All</option>
                              </select>
                            </div>

                            <div class="col-3">
                              <div class="form-check">
                                <label class="form-check-label" for="showDA">Show DA name in result</label>
                                <input class="form-check-input mt-1 ml-1" type="checkbox" name="showDA" id="showDA" value="1">

                              </div>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-3">
                              <label for="agencyState">State:</label>
                            </div>
                            <div class="col-3">
                              <select class="form-control" id="agencyState" name="agencyState" onchange="get_agency()">
                                <option value="0">All</option>
                                <?php
                                foreach ($states as $state)
                                  echo '<option value="' . $state['cmis_state_id'] . '^' . $state['agency_state'] . '" ' . (isset($_POST['agencyState']) && $_POST['agencyState'] == $state['cmis_state_id'] ? 'selected="selected"' : '') . '>' . $state['agency_state'] . '</option>';
                                ?>
                              </select>
                              <input type="hidden" name="agencyState_hidden" id="agencyState_hidden">
                            </div>
                            <div class="col-4">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agency" value="1" onchange="get_agency()">
                                <label class="form-check-label">High Court</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agency" value="2" onchange="get_agency()">
                                <label class="form-check-label">Tribunal</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="agency" value="0" onchange="get_agency()">
                                <label class="form-check-label">Both</label>
                              </div>
                            </div>
                            <div class="col-2">
                              <select class="form-control" id="agencyCode" name="agencyCode">
                                <option value="0">All</option>
                              </select>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-3">
                              <label for="advocate">Advocate (AOR):</label>
                            </div>
                            <div class="col-3">
                              <select class="form-control" id="advocate" name="advocate">
                                <option value="0">All</option>
                                <?php
                                foreach ($aors as $aor)
                                  echo '<option value="' . $aor['bar_id'] . '^' . $aor['name_display'] . '" ' . (isset($_POST['bar_id']) && $_POST['bar_id'] == $aor['bar_id'] ? 'selected="selected"' : '') . '>' . $aor['name_display'] . '</option>';
                                ?>
                              </select>
                            </div>
                            <div class="col-4">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="advPorR" value="1">
                                <label class="form-check-label">Petitioner</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="advPorR" value="2">
                                <label class="form-check-label">Respondent</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="advPorR" value="0" checked="checked">
                                <label class="form-check-label">Both</label>
                              </div>
                            </div>
                          </div>

                          <!-- Hidden Listing Date Field -->
                          <div class="row mb-3" hidden>
                            <div class="col-2 text-left">
                              <label for="listingDate">Listing Date:</label>
                            </div>
                            <div class="col-10">
                              <input type="text" class="form-control datepick" name="listingDate" id="listingDate" placeholder="Select Listing Date" value="">
                            </div>
                          </div>

                          <!-- Hidden Coram Field -->
                          <div class="row mb-3" id="coram" hidden>
                            <div class="col-2 text-left">
                              <label for="coram">Coram:</label>
                            </div>
                            <div class="col-4">
                              <select class="form-control" id="coram" name="coram">
                                <option value="0">Select</option>
                                <?php
                                foreach ($judges as $judge)
                                  echo '<option value="' . $judge['jcode'] . '^' . $judge['jname'] . '">' . $judge['jname'] . '</option>';
                                ?>
                              </select>
                            </div>
                            <div class="col-3">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="rbtCoram" id="Presiding" value="p" checked="checked">
                                <label class="form-check-label" for="Presiding">As Presiding Judge</label>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="rbtCoram" id="Part" value="p1">
                                <label class="form-check-label" for="Part">As Part of Coram</label>
                              </div>
                            </div>
                          </div>

                          <!-- Sort Option Row -->
                          <div class="row mb-3">
                            <div class="col-3">
                              <label for="sort">Sort Option:</label>
                            </div>
                            <div class="col-3">
                              <select id="sort" name="sort" class="form-control">
                                <option value="0^None">Select</option>
                                <option value="1^Diary Number">Diary Number</option>
                                <option value="2^Case Number">Case Number</option>
                                <option value="3^Filing Date">Filing Date</option>
                                <option value="4^Registration Date">Registration Date</option>
                                <option value="5^Section">Section</option>
                                <option value="6^Subject">Subject</option>
                                <option value="7^State">State</option>
                                <option value="8^Case Status">Case Status</option>
                              </select>
                            </div>
                            <div class="col-2">
                              <input type="radio" name="rbtSortOrder" value="asc" checked> Ascending
                            </div>
                            <div class="col-2">
                              <input type="radio" name="rbtSortOrder" value="desc"> Descending
                            </div>
                          </div>

                          <!-- Submit/Reset Buttons Row -->
                          <div class="row">
                            <div class="col-12 text-center">
                              <input type="submit" name="figure" value="Show Figures" class="btn btn-primary">
                              <input type="submit" name="full" value="Show Full Report" class="btn btn-primary">
                              <input type="button" value="Reset" onclick="reset()" class="btn btn-secondary">
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
</section>
<script src="<?= base_url() ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script>
  function printDiv(printable) {
    var printContents = document.getElementById(printable).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
  }

  function reset() {
    document.getElementById('advanceQuery').reset();
  }


  function submitForm() {
    if (advanceQuery.rbtCaseStatus[0].checked == true)

    {
      var fromdate = document.forms["advanceQuery"]["filingDateFrom"].value;
      if (fromdate == null || fromdate == "") {
        alert("Filing From Date must be filled out");
        document.getElementById("filingDateFrom").focus();
        return false;
      }
      var todate = document.forms["advanceQuery"]["filingDateTo"].value;
      if (todate == null || todate == "") {
        alert("Filing To Date must be filled out");
        document.getElementById("filingDateTo").focus();
        return false;
      }
      date1 = new Date(fromdate.split('-')[2], fromdate.split('-')[1] - 1, fromdate.split('-')[0]);
      date2 = new Date(todate.split('-')[2], todate.split('-')[1] - 1, todate.split('-')[0]);
      if (date1 > date2) {
        alert("To Date must be greater than From date");

        return false;
      }
    } else if (advanceQuery.rbtCaseStatus[1].checked == true)

    {
      var fromdate = document.forms["advanceQuery"]["registrationDateFrom"].value;
      if (fromdate == null || fromdate == "") {
        alert("Registration From Date must be filled out");
        document.getElementById("registrationDateFrom").focus();
        return false;
      }
      var todate = document.forms["advanceQuery"]["registrationDateTo"].value;
      if (todate == null || todate == "") {
        alert("Registration To Date must be filled out");
        document.getElementById("registrationDateTo").focus();
        return false;
      }
      date1 = new Date(fromdate.split('-')[2], fromdate.split('-')[1] - 1, fromdate.split('-')[0]);
      date2 = new Date(todate.split('-')[2], todate.split('-')[1] - 1, todate.split('-')[0]);
      if (date1 > date2) {
        alert("To Date must be greater than From date");

        return false;
      }

    }
  }

  $(function() {
    $('.datepick').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
  });
  $(document).ready(function() {
    $("#dispDate").hide();
    $("#pendency").hide();
    $("#pending").click(function() {
      $("#dispDate").hide();
      $("#regDate").show();
      $("#pendency").show();
      // $("#coram").show();

    });
    $("#filing").click(function() {
      $("#dispDate").hide();
      $("#regDate").show();
      $("#pendency").hide();
      // $("#coram").show();
    });
    $("#institution").click(function() {
      $("#dispDate").hide();
      $("#regDate").show();
      $("#pendency").hide();
      // $("#coram").show();
    });
    $("#disposal").click(function() {
      $("#regDate").show();
      $("#dispDate").show();
      $("#pendency").hide();
      //$("#coram").hide();
    });
  });


  function get_sub_sub_cat() {
    var Mcat = $("#subjectCategory option:selected").val();
    let csrfName = $("#csrf_token").attr('name');
    let csrfHash = $("#csrf_token").val();
    Mcat = Mcat.split('^')[0];
    $.ajax({
      url: '<?php echo base_url('DynamicReport/DynamicReport/getSubSubjectCategory/'); ?>',
      type: "POST",
      data: {
        [csrfName]: csrfHash,
        Mcat: Mcat
      },
      cache: false,
      dataType: "json",
      success: function(response) {
        var data = response.data; // Extract the 'data' part of the response

        var options = '<option value="0">All</option>'; // Default option

        // Loop through each item in the data array
        for (var i = 0; i < data.length; i++) {
          options += '<option value="' + data[i].id + '^' + data[i].dsc + '">' + data[i].dsc + '</option>';
        }

        // Append the options to the select element
        $("#subCategoryCode").html(options);

        $("input[name='" + response.csrfTokenName + "']").val(response.csrfHash);
      },
      error: function() {
        alert('Error occurred while fetching data.');
      }
    });

  }


  function get_da() {
    var section = $("#section option:selected").val();
    let csrfName = $("#csrf_token").attr('name');
    let csrfHash = $("#csrf_token").val();

    section = section.split('^')[0];
    $.ajax({
      url: '<?php echo base_url('DynamicReport/DynamicReport/getDa/'); ?>',
      type: "POST",
      data: {
        section: section,
        [csrfName]: csrfHash,
      },
      cache: false,
      dataType: "json",
      success: function(response) {
        var data = response.data;
        var options = '<option value="0">All</option>';

        for (var i = 0; i < data.length; i++) {
          options += '<option value="' + data[i].usercode + '^' + data[i].name + '">' + data[i].name + '</option>';
        }

        $("#dealingAssistant").html(options);
        $("input[name='" + response.csrfTokenName + "']").val(response.csrfHash);
      },
      error: function() {
        alert('ERRO');
      }
    });

  }


  function get_agency() {
    var state = $("#agencyState option:selected").val();
    let csrfName = $("#csrf_token").attr('name');
    let csrfHash = $("#csrf_token").val();

    state = state.split('^')[0];
    var agency = $('input[name="agency"]:checked').val();
    alert(agency);
    
    $.ajax({
      url: '<?php echo base_url('DynamicReport/DynamicReport/get_agency'); ?>',
      type: "POST",
      data: {
        state: state,
        agency: agency,
        [csrfName]: csrfHash
      },
      cache: false,
      dataType: "json",

      success: function(data) {

        console.log(data);
        console.log(data.length);
        var options = '';
        options = '<option value="0">All</option>'
        for (var i = 0; i < data.length; i++) {
          options += '<option value="' + data[i].id + '^' + data[i].agency_name + '">' + data[i].agency_name + '</option>';
        }
        $("#agencyCode").html(options);
      },
      error: function() {
        alert('ERRO');
      }
    });
  }

  function get_casetype() {
    var type = $("input[name='rbtCaseType']:checked").val();
    $.ajax({
      url: '<?= base_url(); ?>index.php/Dynamic_report/get_casetype',
      type: "POST",
      data: {
        type: type
      },
      cache: false,
      dataType: "json",

      success: function(data) {
        console.log(data);
        console.log(data.length);
        var options = '';
        options = '<option value="0">All</option>'
        for (var i = 0; i < data.length; i++) {

          options += '<option value="' + data[i].casecode + '^' + data[i].casename + '">' + data[i].casename + '</option>';

        }
        $("#caseType").html(options);



      },
      error: function() {
        alert('ERRO');
      }
    });
  }
</script>