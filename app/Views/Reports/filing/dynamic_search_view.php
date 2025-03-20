
<div class="active tab-pane" id="Dynamic_search">
<style type="text/css">
    table.gridtable {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
        table-layout: fixed;

    <!--overflow:hidden; -->
    }
    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable tr {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }
    table.gridtable td {
    <!--border-width: 1px;-->
        padding: 8px;
        /*	border-style: solid;
            border-color: #666666;
            background-color: #ffffff;*/
    }
    a:link {
        text-decoration: none;
        color:black;
    }

    a:visited {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    a:active {
        text-decoration: underline;
    }

    .bg-grey-0{
        /*background-color:#f0f0f0;*/
        background-color: #ffffff;
    }
    .bg-grey-1{
        /*background-color:#e0e0e0;*/
        background-color: #ffffff;
    }
    .bg-grey-2{
        /*background-color:#c0c0c0;*/
        background-color: #ffffff;
    }
    .bg-grey-3{
        /*background-color:#888888;*/
        background-color:#c0c0c0;

    }
    td.heading{
        text-align:left;
        font-weight: bold;
        font-size:14px;
    }
    td.first{
        width:15%;
        text-align:left;
        font-weight: bold;
        font-size:12px;

    }

    td.label{
        width:10%;
        text-align:left;
        font-weight: bold;
        font-size:12px;
        color:black;
        border-right: 1px solid #666666;


    }
    td.second{
        width:25%;
        text-align:left;
    }
    td.third{
        width:15%;
        text-align:right;
        font-weight: bold;
    }
    td.fourth{
        width:12%;
        text-align:left;
    }
    td.fifth {
        width:15%;
        text-align:right;
        font-weight: bold;
    }
    td.sixth{
        width:18%;
        text-align:left;
    }
    .bs-example{
        margin: 0px;
    }
    div.bs-example {
        font-family: verdana,arial,sans-serif;
        font-size:11px;
        color:#333333;
        border-color: #666666;

    }
    h4.panel-title {
        font-size:12px;
        font-weight:bold;

    }
    h2.h2head {
        display: block;
        font-size: 1.5em;
        -webkit-margin-before: 0.83em;
        -webkit-margin-after: 0.83em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
        font-weight: bold;
        color:black;
        text-align:center
    }
    h3.h3head {
        display: block;
        font-size: 1.17em;
        -webkit-margin-before: 1em;
        -webkit-margin-after: 1em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
        font-weight: bold;
        color:black;
        text-align:center
    }
</style>


<?php $attribute = array('class' => 'form-horizontal dak_search_form', 'name' => 'advanceQuery', 'id' => 'advanceQuery', 'autocomplete' => 'off'); 
     echo form_open(base_url('Reports/Filing/Report/dynamic_search'), $attribute);  ?>
<!-- , 'onsubmit' => 'return submitForm()' <form name="advanceQuery" id="advanceQuery" action="http://10.40.186.169/supreme_court/Copying/index.php/Dynamic_report/getResult" method="POST" target="_blank" onsubmit="return submitForm();"> -->    
    <table align="center" style="width:80%;-moz-width:80%; !Important;" border="0" cellpadding="3" cellspacing="0" class="gridtable">
        <tr></tr><tr></tr>
        <thead>
        <caption class="bg-grey-3">
            <h2 class="h2head">SUPREME COURT OF INDIA</h2>
            <h3 class="h3head">DYNAMIC REPORT</h3>
        </caption>
        </thead>
        <tbody>
        <tr class="bg-grey-1">
            <td colspan="5" class="heading" style="text-align:center">Select Search Parameters</td>
        </tr>
        <tr class="bg-grey-1">
            <td></td><td class="first"><input type="radio" name="rbtCaseStatus" id="filing" value="f" checked="checked">Filling</td>
            <td class="first"><input type="radio" name="rbtCaseStatus" id="institution" value="i" checked="checked">Registration</td>
            <td  class="first"><input type="radio" name="rbtCaseStatus" id="pending" value="p"> Pendency</td>
            <td  class="first"><input type="radio" name="rbtCaseStatus" id="disposal" value="d"> Disposal</td>

        </tr>
        <tr id="filDate">
            <td class="label">Filing Date:</td><td colspan="2" class="first" style="text-align:left;"> From:
            <input type="date" class="form-control" id="filingDateFrom" name="filingDateFrom" value="<?php echo !empty($formdata['filingDateFrom']) ? $formdata['filingDateFrom'] : '' ?>" placeholder="From Date">
            <!-- <input type="text" class="datepick" name="filingDateFrom" id = "filingDateFrom" placeholder = "Select Filing From date" value=""> -->
            </td>
            <td colspan="2" class="first" style="text-align:left;">To:
            <input type="date" class="form-control" id="filingDateto" name="filingDateto"  value="<?php if(!empty($formdata['filingDateto'])) echo $formdata['filingDateto'] ?>" placeholder="TO Date">
            </td>
        </tr>
        <tr id="regDate">
            <td class="label">Registration Date:</td><td colspan="2" class="first" style="text-align:left;"> From:
                <!-- <input type="text" class="datepick" name="registrationDateFrom" id = "registrationDateFrom" placeholder = "Select Registration From date" value=""> -->

                <input type="date" class="form-control" id="registrationDateFrom" name="registrationDateFrom" value="<?php echo !empty($formdata['registrationDateFrom']) ? $formdata['registrationDateFrom
                '] : '' ?>" placeholder="From Date">

            </td>
            <td colspan="2" class="first" style="text-align:left;">To:
                <!-- <input type="text" class="datepick" name="registrationDateTo" id = "registrationDateTo" placeholder = "Select Registration To date" value=""> -->
                <input type="date" class="form-control" id="registrationDateTo" name="registrationDateTo" value="<?php echo !empty($formdata['registrationDateTo']) ? $formdata['registrationDateTo
                '] : '' ?>" placeholder="To Date">

            </td>
        </tr>
        <tr class="bg-grey-1" id="pendency">
            <td class="label" style="text-align:left;">Pendency Type:</td>
            <td class="first" colspan="4"><input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="R">Registered
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="rbtPendingOption" id="rbtPendingOption" value="UR">Un-Registered
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="rbtPendingOption" id="rbtPendingOption"  value="b" checked="checked"> Both</td>
        </tr>
        <tr>
            <td class="label">Case Year:</td>
            <td colspan="4">   <select  id="caseYear" name="caseYear" style="width:20%;"  class="form-control">
            <option value="0">Select</option>
                    <?php
                    for($year=date('Y'); $year>=1950; $year--)
                        echo '<option value="'.$year.'">'.$year.'</option>';
                    ?>     </select>
            </td>
        </tr>
        <tr id="dispDate">
            <td class="label">Disposal Date:</td>
            <td colspan="2" class="first" style="text-align:left;">From:
                <input type="date" class="form-control" name="disposalDateFrom" id = "disposalDateFrom" placeholder = "Select Disposal From date" value="">
            </td>
            <td colspan="2" class="first" style="text-align:left;">To:
                <input type="date" class="form-control" name="disposalDateTo" id = "disposalDateTo" placeholder = "Select Disposal To date" value="">
            </td>

        </tr>

          <tr class="bg-grey-1">
            <td class="label" style="text-align:left;">Case Type:</td>
              <td class="first" colspan="2"><input type="radio" name="rbtCaseType" id="rbtCaseType" value="C" onclick="get_casetype()">Civil
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="radio" name="rbtCaseType" id="rbtCaseType" value="R" onclick="get_casetype()">Criminal
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="radio" name="rbtCaseType" id="rbtCaseType"  value="b" checked="checked" onclick="get_casetype()"> Both</td>
              <td colspan="2">
                <select style="width:70%;"  class="form-control" id="caseType" name="caseType[]" multiple>
                    <option value="0" disabled>Select Multiple</option>
                    </select>
                </select>
            </td>
        </tr>
        <tr class="bg-grey-1">
            <td class="label" style="text-align:left;">Matter Type:</td>
            <td class="first"><input type="radio" name="matterType" id="Admission" value="M" >Admission</td>
            <td  class="first"><input type="radio" name="matterType" id="Regular" value="F"> Regular</td>
            <td  class="first" colspan="2"><input type="radio" name="matterType" id="Both" value="all" checked="checked"> Both</td>

        </tr>
        <tr>
            <td class="label" style="text-align:left;">Party Name:</td>

            <td colspan="3" class="first">
                <input type="text" name="respondentName"  class="form-control" placeholder="Enter full or part of name" id = "respondentName" style="width:230px;" value="">
                <input type="hidden" name="petitionerName"  value="">&nbsp;&nbsp;&nbsp;&nbsp;

                <!--</td>

             <td>-->
                <input type="radio" class="first" name="PorR"  class="form-control" value="1">&nbsp;Petitioner &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="PorR"  class="form-control" value="2">&nbsp;Respondent &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="PorR" class="form-control" value="0" checked="checked">&nbsp;Both &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td class="first" ><label for = "caseYear" >Filing Year :</label>
                <select  class="form-control" id="diaryYear" name="diaryYear">
                <option value="0">Select</option>
                    <?php
                    for($year=date('Y'); $year>=1950; $year--)
                        echo '<option value="'.$year.'">'.$year.'</option>';
                    ?>                </select></td>
        </tr>
        <tr>
        <tr class="bg-grey-1">
            <td class="label" style="text-align:left;">Subject Category:</td>
            <td>
                      <select class="form-control" style="width:90%;" id="subjectCategory" name="subjectCategory" onchange= "get_sub_sub_cat()">
                      <option value="0">All</option>
                          <?php
                          if(!empty($MCategories)){
                          foreach($MCategories as $MCategory)
                            echo '<option value="' . $MCategory->subcode1.'^'.$MCategory->sub_name1. '" ' . ( isset( $_POST['subjectCategory'] ) && $_POST['subjectCategory'] == $MCategory['subcode1'] ? 'selected="selected"' : '' ) . '>' . $MCategory->subcode1.' # '.$MCategory->sub_name1. '</option>';
                          }
                          ?>            
                        </select>

                </div>
            </td>
            <td  class="first">Sub Category:</td>
               <td colspan="2">
                    <select class="form-control" id="subCategoryCode" name="subCategoryCode" style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
                        <option value="0">All</option>
                    </select>
                </div>
            </td>
        </tr>

        <tr>
            <td class="label" style="text-align:left;">Flag:</td>
            <td colspan="4" class="first" style="text-align:left;">
                <input type="checkbox" name="chkJailMatter" id="chkJailMatter" value="1"> Jail Matter
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkFDMatter" id="chkFDMatter" value="1"> FD
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkLegalAid" id="chkLegalAid" value="1"> Legal Aid
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkSpecificDate" id="chkSpecificDate" value="1"> Specific Date
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chkPartHeard" id="chkPartHeard" value="1"> Part Heard
            </td>

        </tr>

            <td class="label" style="text-align:left;">Section: </td>
            <td>

                <select class="form-control" style="width:35%;" id="section" name="section" onchange="get_da()">
                <option value="0">All</option>
                    <?php
                    if(!empty($Sections)){
                    foreach($Sections as $Section)
                        echo '<option value="' . $Section->id.'^'.$Section->section_name . '" ' . ( isset( $_POST['section'] ) && $param[5] == $Section['section_name'] ? 'selected="selected"' : '' ) . '>' . $Section->section_name . '</option>';
                    } ?>  </select>
            </td>
            <td class="first">Dealing Assistant:</td>
            <td colspan="1">
            <select class="form-control" id="dealingAssistant" name="dealingAssistant" style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
                <option value="0">All</option>
            </select>
            </td>
        <td class="first">
            <input  type="checkbox" name="showDA" id="showDA" value="1"> Show DA name in result
        </td>
        </tr>
        <tr>
            <td class="label" style="text-align:left;">State:</td>
            <td>
                <div class = "contentdiv1">
                    <select class="form-control" style="width:200px;" id="agencyState" name="agencyState" onchange="get_agency()">
                    <option value="0">All</option>
                        <?php
                        if(!empty($states)){
                        foreach($states as $state)
                            echo '<option value="' . $state->cmis_state_id.'^'.$state->agency_state. '" ' . ( isset( $_POST['agencyState'] ) && $_POST['agencyState'] == $state['cmis_state_id']  ? 'selected="selected"' : '' ) . '>' . $state->agency_state . '</option>';
                         } ?>             </select>
                    <input type="hidden" name="agencyState_hidden" id="agencyState_hidden">
                </div>
            </td>
            <td  class="first" colspan="2"><input type="radio" class="first" name="agency" value="1" onchange="get_agency()">&nbsp;High Court &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="agency" value="2" onchange="get_agency()">&nbsp;Tribunal &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="agency" value="0" onchange="get_agency()">&nbsp;Both &nbsp;</td>
            <td>
                <select class="form-control" id="agencyCode" name="agencyCode"  style=" width: 100px;overflow: hidden;white-space: pre;text-overflow: ellipsis;">
                    <option value="0">All</option>
                </select>
                </div>
            </td>
        </tr>
        <tr>
            <td class="label" style="text-align:left;">
                Advocate(AOR):
            </td>
            <td colspan="4" class="first">
                <select class="form-control" style="width:21%;" id="advocate" name="advocate">
                <option value="0">All</option>
                    <?php
                    if(!empty($aors)){ 
                    foreach($aors as $aor)
                      echo '<option value="' . $aor['bar_id'].'^'.$aor['name_display']. '" ' . ( isset( $_POST['bar_id'] ) && $_POST['bar_id'] == $aor['bar_id'] ? 'selected="selected"' : '' ) . '>' .$aor['name_display'] . '</option>';
                    }
                    ?>     </select>
                <input type="radio" class="first" name="advPorR" value="1">&nbsp;Petitioner &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="advPorR" value="2">&nbsp;Respondent &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" class="first" name="advPorR" value="0" checked="checked">&nbsp;Both &nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr hidden>
            <td class="label" style="text-align:left;">
                Listing Date:
            </td>
            <td colspan="4">
                <input class="form-control"  type="date"  name="listingDate" id = "listingDate" placeholder = "Select Listing Date" value="">
            </td>
        </tr>
        <tr id="coram" hidden>
            <td class="label" style="text-align:left;">Coram:</td>
            <td colspan="2" class="first">
                <select class="form-control" style="width:200px;" id="coram" name="coram">
                <option value="0">select</option>
                    <?php
                    //foreach($judges as $judge)
                        //echo '<option value="' . $judge['jcode'].'^'.$judge['jname']. '">' . $judge['jname'] . '</option>';
                    ?>  </select>
            </td>
            <td class="first"><input type="radio" name="rbtCoram" id="Presiding" value="p" checked="checked">As Presiding Judge</td>
            <td class="first"><input type="radio" name="rbtCoram" id="Part" value="p1" >As Part of Coram</td>
        </tr>
        <tr>
            <td class="label" style="text-align:left;">Sort Option:</td>
            <td colspan="1" class="first">
                <select class="form-control" style="width:35%;" id="sort" name="sort">
                    <option value="0^None">Select</option>
                    <option value="1^Diary Number">Diary Number</option>
                    <option value="2^Case Number">Case Number</option>
                    <option value="3^Filing Date">Filing Date</option>
                    <option value="4^Registration Date">Registration Date</option>
                    <option value="5^Section">Section</option>
                    <option value="6^Subject">Subject</option>
                    <option value="7^State">State</option>
                    <option value="8^Case Status">Case Status</option>
                    <!--<option value="9^Listing Date">Listing Date</option>-->

                </select>
            </td>
            <td><input type="radio" name="rbtSortOrder" id="asc" value="asc" checked="checked">Ascending</td>
            <td><input type="radio" name="rbtSortOrder" id="desc" value="desc">Descending</td>
            <td></td>
        </tr>
        <tr></br>
            <td colspan="5" class="first" style="text-align:center;">
                <br/><input type="button" name="figure" id="figure" class="btn btn-sm btn-primary" value="Show Figures">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit"  name="full" id="full"  class="btn btn-sm btn-primary" value="Show Full Report">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button"  value="Reset"  class="btn btn-sm btn-primary" onclick="reset()">
            </td>

        </tr>
        </tbody>
    </table>
<?= form_close();?>
</div>
<script>
    function reset()
    {
        document.getElementById('advanceQuery').reset();
    }


    function submitForm()
    {
        if(advanceQuery.rbtCaseStatus[0].checked==true)

        { var fromdate = document.forms["advanceQuery"]["filingDateFrom"].value;
            if (fromdate==null || fromdate=="") {
                alert("Filing From Date must be filled out");
                document.getElementById("filingDateFrom").focus();
                return false;
            }
            var todate = document.forms["advanceQuery"]["filingDateTo"].value;
            if (todate==null || todate=="") {
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
        }
        else if(advanceQuery.rbtCaseStatus[1].checked==true)

        {
            var fromdate = document.forms["advanceQuery"]["registrationDateFrom"].value;
            if (fromdate==null || fromdate=="") {
                alert("Registration From Date must be filled out");
                document.getElementById("registrationDateFrom").focus();
                return false;
            }
            var todate = document.forms["advanceQuery"]["registrationDateTo"].value;
            if (todate==null || todate=="") {
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

    $(function () {
        $('.datepick').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
    });
        $(document).ready(function(){
            $("#dispDate").hide();
            $("#pendency").hide();
            $("#pending").click(function(){
                $("#dispDate").hide();
                $("#regDate").show();
                $("#pendency").show();
               // $("#coram").show();

            });
            $("#filing").click(function(){
                $("#dispDate").hide();
                $("#regDate").show();
                $("#pendency").hide();
               // $("#coram").show();
            });
            $("#institution").click(function(){
                $("#dispDate").hide();
                $("#regDate").show();
                $("#pendency").hide();
               // $("#coram").show();
            });
            $("#disposal").click(function(){
                $("#regDate").show();
                $("#dispDate").show();
                $("#pendency").hide();
                //$("#coram").hide();
            });
        });


    function get_sub_sub_cat() { // Call to ajax function
        var Mcat =$("#subjectCategory option:selected").val();
        Mcat=Mcat.split('^')[0];
        $.ajax
        ({
            url: '<?php echo base_url('Reports/Filing/Report/get_Sub_Subject_Category'); ?>',
            type: "GET",
            data: {Mcat:Mcat},
            cache: false,
            dataType:"json",
            success: function(data)
            {
               console.log(data);
               // console.log(data.length);
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].id +'^'+data[i].dsc+ '">' + data[i].dsc + '</option>';

                }
                //console.log(options);
                $("#subCategoryCode").html(options);

            },
            error: function () {
                alert('ERRO');
            }
        });

    }


    function get_da() { // Call to ajax function
        var section =$("#section option:selected").val();
        section=section.split('^')[0];
        $.ajax
        ({
            url: '<?php echo base_url('Reports/Filing/Report/get_da'); ?>',
            type: "GET",
            data: {section:section},
            cache: false,
            dataType:"json",
            success: function(data)
            {

                //console.log(data);
                //console.log(data.length);
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].usercode +'^'+data[i].name+ '">' + data[i].name + '</option>';

                }
                $("#dealingAssistant").html(options);



            },
            error: function () {
                alert('ERRO');
            }
        });

    }


    function get_agency() { // Call to ajax function
        var state = $("#agencyState option:selected").val();
        state=state.split('^')[0];
        var agency=$('input[name="agency"]:checked').val();
        $.ajax
        ({
            url: '<?php echo base_url('Reports/Filing/Report/get_agency'); ?>',
            type: "GET",
            data: {state: state,agency:agency},
            cache: false,
            dataType: "json",

            success: function (data) {

                console.log(data);
                console.log(data.length);
                var options = '';
                options = '<option value="0">All</option>'
                for (var i = 0; i < data.length; i++) {

                    options += '<option value="' + data[i].id +'^'+data[i].agency_name+ '">' + data[i].agency_name + '</option>';

                }
                $("#agencyCode").html(options);


            },
            error: function () {
                alert('ERRO');
            }
        });
    }

        function get_casetype() {
            var type=$("input[name='rbtCaseType']:checked").val();
            console.log(type);
            $.ajax
            ({
                url: '<?php echo base_url('Reports/Filing/Report/get_casetype'); ?>',
                type: "GET",
                data: {type:type},
                cache: false,
                dataType:"json",

                success: function(data)
                {

                    console.log(data);
                    console.log(data.length);
                    var options = '';
                    options = '<option value="0">All</option>'
                    for (var i = 0; i < data.length; i++) {

                        options += '<option value="' + data[i].casecode +'^'+data[i].casename+ '">' + data[i].casename + '</option>';

                    }
                    $("#caseType").html(options);
                },
                error: function () {
                    alert('ERRO');
                }
            });
    }

</script>


 </div>
         <div id="result_data"></div>
      </div>
   </div>
 </div>

<script src="<?php echo base_url('plugins/jquery-validation/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('plugins/jquery-validation/additional-methods.min.js'); ?>"></script>

<script>
    $('#figure').on('click', function () {
        
         //if ($('#refiling_search_form').valid()) {
            //var validateFlag = true;
            var form_data = $("#advanceQuery").serialize();
            var from_date = $("#registrationDateFrom").val();
            var to_date = $("#registrationDateTo").val();
            
            if(from_date == ''){
                alert("Registration From Date must be filled out");
                $("#registrationDateFrom").focus();
                return false;
            }
            else if(to_date == ''){
                alert("Registration To Date must be filled out");
                $("#registrationDateTo").focus();
                return false;
            }


            if(from_date){ //alert('readt post form');
                //alert(from_date);
                //var CSRF_TOKEN = 'CSRF_TOKEN';
                //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                //$('.alert-error').hide();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('Reports/Filing/Report/dynamic_search?figure=figure'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#figure').val('Please wait...');
                        $('#figure').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#figure').prop('disabled', false);
                        $('#figure').val('Show Figures');
                        $("#result_data").html(data);

                        //updateCSRFToken();
                    },
                    error: function () {
                        //updateCSRFToken();
                    }

                });
                return false;
            }
        //  else {
        //      alert("Registration From Date must be filled out");
        //      return false;
        //  }
    });
    
    $('#full').on('click', function () {
        
        //if ($('#refiling_search_form').valid()) {
           //var validateFlag = true;
           var form_data = $("#advanceQuery").serialize();
           var from_date = $("#registrationDateFrom").val();
           var to_date = $("#registrationDateTo").val();
           
           if(from_date == ''){
               alert("Registration From Date must be filled out");
               $("#registrationDateFrom").focus();
               return false;
           }
           else if(to_date == ''){
               alert("Registration To Date must be filled out");
               $("#registrationDateTo").focus();
               return false;
           }


           if(from_date){ //alert('readt post form');
               //alert(from_date);
               //var CSRF_TOKEN = 'CSRF_TOKEN';
               //var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
               //$('.alert-error').hide();
               $.ajax({
                   type: "GET",
                   url: "<?php echo base_url('Reports/Filing/Report/dynamic_search?full=full'); ?>",
                   data: form_data,
                   beforeSend: function () {
                       //$('#figure').val('Please wait...');
                       //$('#figure').prop('disabled', true);
                   },
                   success: function (data) {
                       $('#full').prop('disabled', false);
                       $('#full').val('Show Full Report');
                       $("#result_data").html(data);

                       //updateCSRFToken();
                   },
                   error: function () {
                       //updateCSRFToken();
                   }

               });
               return false;
           }
       //  else {
       //      alert("Registration From Date must be filled out");
       //      return false;
       //  }
   });

</script>


