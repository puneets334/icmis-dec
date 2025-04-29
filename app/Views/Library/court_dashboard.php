<?= view('header'); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Upload Old Judgments</h3>
                            </div>

                            
                        </div>
                    </div>
                    <?= view('Library/court_notification'); ?><br>
                    <div class="card-body">
                    
                        <input type="hidden" name="court_username" id="court_username" value="<?php  echo  $_SESSION['username']; 
                                                                                                ?>">
                        <input type="hidden" name="userrole_id" id="userrole_id" value="<?php  echo $_SESSION['role_id']; 
                                                                                        ?>">
                        <input type="hidden" name="court_number" id="court_number" class="form-control" value="<?php echo $_SESSION['court_number']; 
                                                                                                                ?>">
                        <div class="col-sm-12 " id="">
                            <center>
                                <h3><?php  echo $_SESSION['court_number']; 
                                    ?>
                                    &nbsp;(BENCH:&nbsp;<?php echo $_SESSION['court_bench'] ?? ''; 
                                                        ?>)</h3>
                            </center>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tblRequistion">
                                    <thead>
                                        <tr>
                                            <th>Item No.</th>
                                            <th>Priority</th>
                                            <th align="center">Requisition</th>
                                            <th>Remarks</th>
                                            <th>Current Status</th>
                                            <th>Section Name</th>
                                            <th>Comments</th>
                                            <th>Receiving Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="statusData">
                                        <?php
                                        $cnt = 1;
                                        if (count($result) > 0) {
                                            foreach ($result as $res) {                                                
                                                if (
                                                    $res['current_status'] == "closed" || $res['current_status'] == "cancel"
                                                    || $res['current_status'] == "received"
                                                ) {
                                                    $bgcolor = "#E5E4E2";
                                                } else {
                                                    $bgcolor = "";
                                                }
                                                if ($res['urgent'] == "Yes") {
                                                    $urgentVal = "<span class='badge bg-danger'>HIGH</span>";
                                                } else {
                                                    $urgentVal = "NO";
                                                }
                                        ?>
                                                <tr style="background-color:<?= $bgcolor ?>">

                                                    <td>

                                                        <?php
                                                        if (
                                                            $res['current_status'] != "closed" && $res['current_status'] != "cancel"
                                                            && $res['current_status'] != "received"
                                                        ) { ?>
                                                            <a href="javascript:void(0)"
                                                                onclick="getQueryData(<?php echo $res['id']; ?>);"><b><?php echo ($res['itemno']); ?></b></a>
                                                        <?php
                                                        } else {
                                                            echo ($res['itemNo']);
                                                        }
                                                        ?>

                                                    </td>
                                                    <td><?php echo $urgentVal; ?></td>
                                                    
                                                    <td></td>

                                                    <td><?php
                                                        // pr($res);
                                                        echo htmlentities(trim($res['remark2'] ?? ''));
                                                        ?></td>
                                                    <td align="center">
                                                        <?php
                                                        if ($res['current_status'] == "pending") {
                                                            $btnVal = '<button type="button" class="btn btn-danger">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == 'Interaction') {
                                                            $btnVal = '<button type="button" class="btn btn-dark">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == "received") {
                                                            $btnVal = '<button type="button" class="btn btn-primary">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == "Sent") {
                                                            $btnVal = '<button type="button" class="btn btn-info">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == 'attending') {
                                                            $btnVal = '<button type="button" class="btn btn-warning">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == 'closed') {
                                                            $btnVal = '<button type="button" class="btn btn-success">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        if ($res['current_status'] == 'cancel') {
                                                            $btnVal = '<button type="button" class="btn btn-secondary">' . strtoupper($res['current_status']) . '</button>';
                                                        }
                                                        
                                                        echo $btnVal;
                                                        ?></td>
                                                    <td><?php echo ucwords($res['section']);
                                                        ?></td>
                                                    <td align='center'><?php if ($res['id']) {
                                                                        ?>
                                                            <a href="#" onclick="openWin(<?php echo $res['id']
                                                                                            ?>);"><button type="button"
                                                                    class="btn btn-warning">View
                                                                </button></a><?php  }
                                                                                ?>

                                                    </td>
                                                    <td align="center">
                                                        <?php


                                                        if ($res['current_status'] != "received" && $res['current_status'] != 'cancel' && $res['current_status'] != 'closed') {
                                                        ?>
                                                            <input type="checkbox" id="status_recive" name="status_r" onclick="changeRstatus('<?php echo $res["id"];
                                                                                                                                                ?>')" value="<?php echo $res["id"];
                                                                                                        ?>" class="cbCheck">
                                                            <font size="2" color="#303030">Click here to update the status</font>
                                                        <?php  } else {
                                                            echo strtoupper($res['current_status']);
                                                        ?>
                                                            <input type="checkbox" checked disabled="" id="status_recive"
                                                                name="status_r" value="<?php echo $res["id"]; ?>"
                                                                class="cbCheck"><strong>Received</strong>

                                                        <?php  }
                                                        ?>

                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal" id="modelWindow" tabindex="-1" aria-labelledby="modelWindowLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg vertical-align-center">
            <div class="modal-content">
                <div class="modal-header" style="position: relative;border-bottom: 1px solid #ccc;">

                    <h5 class="modal-title">Requisition Form</h5> <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="padding-top: 25px !important; text-align: left;">


                    <form action="" name="frmrequistionAsst" id="frmrequistionAsst">
                    <?= csrf_field() ?>    
                        <input type="hidden" name="court_username" id="court_username" value="<?php  echo $_SESSION['username']; ?>">

                        <input type="hidden" name="court_number" id="court_number" class="form-control" value="<?php  echo $_SESSION['court_number']; ?>">

                        <input type="hidden" name="userIp" id="userIp" value="<?php  echo $_SERVER['REMOTE_ADDR']; ?>">
                        <input type="hidden" name="court_bench" id="court_bench" value="<?php  echo $_SESSION['court_bench'] ?? '' ?>"> 

                        <input type="hidden" name="user_type" id="user_type" value="1">

                        <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>">

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="remark">List Date: <span style="color:red;">*</span></label>
                                <select id="dtd" name="dtd" class="form-control">
                                      <option value="">Select List Date</option>  
                                      <?php foreach ($dataDropdown as $res)  {?>
                                    <option value="<?php echo $res['next_dt'];?>" ><?php echo date("d-m-Y", strtotime($res['next_dt']));?></option>
                                <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="section">Section<span style="color:red;">*</span></label>
                                <select id="section" name="section" class="form-control">
                                <option value="">Select Section</option>
                                <?php
                                foreach ($librarySection as $result) {
                                    ?>
                                    <option value="<?php echo $result['library_section_name'] ?>"><?php echo $result['library_section_name'] ?></option>
                                <?php } ?>
                            </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="remark">Court No<span style="color:red;">*</span></label>
                                <select name="court_no" id="court_no" class="form-control">
                                    <option value="">Select court</option>
                                    <?php
                                    $courtArr = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '21', '22'];
                                    foreach ($courtArr as $courtVal):
                                    ?>
                                        <option value="<?php echo $courtVal; ?>"><?php echo $courtVal; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="remark">Cause List Item No<span style="color:red;">*</span></label>
                               <input type="text" id="itemNo" maxlength="9"  name="itemNo" onkeyup="getCaseNo(this.value)" class="form-control" >

                            </div>
                            <div class="col-sm-6" style="padding: 35px 10px 20px;">
                                <label for="remark">Urgently Needed<span
                                        style="color:red;">*</span></label>&nbsp;&nbsp;
                                <input type="radio" name="urgent" id="urgentY" value="Yes">&nbsp;Yes
                                &nbsp;&nbsp;
                                <input type="radio" name="urgent" id="urgentN" value="No" checked>&nbsp;No
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="dynamicDetails" style="display:none; padding: 15px;">
                                <strong> Case No: </strong> <span id="case_no"></span><br>
                                <strong>Pet. / Res. Name: </strong> <span id="pet_res_name"></span><br>
                                <strong>List Date: </strong> <span id="itm_date"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="adv_name">Advocate Name: </label>
                                <input type="text" class="form-control adv_name" name="adv_name" id="adv_name" placeholder="Advocate Name" value="">
                            </div>

                            <div class="col-sm-4">
                                <label for="appearing_for"> Appearing For: </label>
                                <select name="appearing_for" id="appearing_for" class="form-control">
                                    <option value="">Select Appearing for</option>
                                    <option value="Petitioner">Petitioner</option>
                                    <option value="Respondent">Respondent</option>
                                    <option value="Impleader">Impleader</option>
                                    <option value="Intervenor">Intervenor</option>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="party_sno">Party S.No.: </label>
                                <input type="text" class="form-control party_sno" name="party_sno" id="party_sno"
                                    placeholder="Party Serial Number" value="">
                            </div>
                        </div>

                       

                        <div id="recvRemark" style="display:none">
                            <a href="#" id="myAnchor" target="_blank" style="float:right;display:none"><i
                                    class="fas fa-paperclip"></i>View Attachment</a><br>
                            <label for="section">Status</label>
                            <input type="hidden" name="requestId" id="requestId" value="">
                            <select id="asstStatus" name="asstStatus" class="form-control">
                                <option value="">Select Status</option>
                                <option value="Interaction">Interaction</option>
                                <option value="received">Received</option>
                                <option value="cancel">Cancel</option>
                            </select>
                            <label for="remark" id="">Comment</label>
                            <textarea id="asstRemark" name="asstRemark" placeholder="Write something.."
                                style="height:103px" class="form-control"></textarea>
                            <label for="remark" id="">Upload</label>
                            <input class="coupon_question" type="checkbox" id="" name="" value="1"
                                onchange="showInsuploadDiv();" /><br>
                            <div class="custom-file" id="intraction" style="display:none">
                                <input type="file" class="custom-file-input" id="intractionImg"
                                    name="intractionImg">
                                <label class="custom-file-label" for="customFile"></label>
                            </div><br><br>
                            <button type="button" class="btn btn-success" onclick="requestformValidation();">Submit
                                Comment</button>
                        </div><br>




                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="other_relevent_material">Remarks</label>
                                    <input type="text" class="form-control other_relevent_material"
                                        name="other_relevent_material[]" id="other_relevent_material"
                                        placeholder="Remarks" value="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="upload_document">Upload document (max 100mb)</label>
                                    <div id="newRow">
                                        <div class="input-group mt-1" id="inputFormRow">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input upload_document"
                                                    name="upload_document[]" id="upload_document"
                                                    accept="application/pdf">
                                                <label class="custom-file-label" for="upload_document">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 pt-4">
                                <div class="form-group pt-1">
                                    <span class="ml-2">
                                        <button id="addRowOther" type="button" class="btn btn-info"><i
                                                class="fas fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="newRowOther"></div>


                        <input type="hidden" name="diary_no" id="diary_no">

                        <button type="button" class="btn btn-success" id="addQuery"
                            onclick="formValidation();">Submit Requisition</button>
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                    </form>


                </div>
                <div class="modal-footer">
                    <div class="alert alert-danger w-50" role="alert" id="errorMsg"
                        style="display:none; margin: 0 auto;"></div>
                    <div class="alert alert-success w-50" role="alert" id="successMsg"
                        style="display:none; margin: 0 auto;"></div>

                    
                </div>
            </div>
        </div>
    </div>
</section>
<!-- <script src="<?php //echo base_url(); ?>/plugins/jquery/jquery.min.js"></script> -->
<script src="<?php echo base_url();?>/requisition/requistion.js">   </script>
<script>
    $(document).ready(async function() {
        await updateCSRFTokenSync();

        $("#dtd").val($("#dtd option:first").val());
        let role = '<?= $_SESSION['role_id'] ?>'

        if (role == 5) {
            $('.card-body').hide()
            $('#modelWindow').modal('show');
        } else {
            $('#court_no').val('<?= $_SESSION['court_number'] ?>')
            $('#court_no').attr('disabled', true)
        }
        setInterval(function() {
            var CSRF_TOKEN = 'CSRF_TOKEN';
		    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var username = $("#court_username").val();
            var court_number = $("#court_number").val();

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Library/Requisition/frmusrLogin'); ?>',
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    mode: "getAutoRefresh",
                    username: username,
                    court_number: court_number
                },
                dataType: 'json',
                cache: false,
                error: function() {
                    updateCSRFToken();
                    console.log("error");
                },
                success: function(response) {
                    updateCSRFToken();
                    $("#statusData").html(response.html);
                },
            });
        }, 5000);  
        
        $("#addRowOther").click(function() {
            let x = Math.floor(Math.random() * 100);
            var html = '';
            html += '<div class="row" id="inputFormRow" >';

            html += '<div class="col-sm-6">';
            html += '<div class="form-group">';
            html +=
                '<input type="text" class="form-control form-control other_relevent_material" name="other_relevent_material[]" id="other_relevent_material" placeholder="Remarks">';
            html += '</div>';
            html += '</div>';

            html += '<div class="col-sm-4">';
            html += '<div class="input-group mt-1" >'
            html += '<div class="custom-file">'
            html +=
                '<input type="file" class="custom-file-input upload_document" name="upload_document[]" id="upload_document' +
                x + '" accept="application/pdf">'
            html += '<label class="custom-file-label" for="upload_document">Choose file</label>'
            html += '</div>'
            html += '</div>';
            html += '</div>';


            html += '<div class="col-sm-2">';
            html +=
                '<span class="ml-2"><button id="removeRowOther" type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button></span>';
            html += '</div>';

            html += '</div>';

            $('#newRowOther').append(html);
            $('.custom-file-input.upload_document').change(function() {
                let cid = $(this)[0].files[0].name
                let attrId = $(this)[0].id
                console.log('cid: ', attrId)
                $('#' + attrId).next('label').text(cid);
            })

        });
        $(document).on('click', '#removeRowOther', function() {
            $(this).closest('#inputFormRow').remove();
        });


        $('.custom-file-input.upload_document').change(function() {
            let cid = $(this)[0].files[0].name
            let attrId = $(this)[0].id
            $('#' + attrId).next('label').text(cid);
        })
    });
</script>


<script>




    $( document ).ready(function() {


        // Summernote
        $('#btnrequistion').click(function() {
          
            $('#dynamicDetails').hide()
            $('#modelWindow').modal('show');
            $("#recvRemark").hide();0
            $("#addQuery").show();
            $("#queryAdd").hide();

            $("#section").prop('disabled', false);
            $("#itemNo").prop('disabled', false);
            $("#itemNo").prop('disabled', false);
            $('input[name=urgent]').attr("disabled",false);

            $('#remark1').val('').empty();
            $('#itemNo').val('');
            $('#section').val('');
            $('#remark1').summernote('code', '');
        });

        // $('#remark1').summernote();


        $('#remark1').summernote({
            // styleTags: ['h1', 'h2'],
            toolbar: [
                // ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                // ['table', ['table']],
                //['insert', ['link', 'picture', 'video']],
                //['view', ['fullscreen', 'codeview', 'help']]
            ]
        });



    })
    function showuploadDiv(){
        $("#queryAdd").toggle();
    }
    function showInsuploadDiv(){
        $("#intraction").toggle();
    }


    async function getCaseNo(value)
    {
    await updateCSRFTokenSync();

    let dateitm = $('#dtd').find(":selected").val();
    let courtno = $('#court_no').find(":selected").val();
    
    document.getElementById('itemNo').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (!/^\d+$/.test(input)) {
      alert('Please enter numbers only');
      e.preventDefault(); // Prevent form from submitting
    } 
  });

    if(dateitm == '')
    {
        alert('Please select List Date');
        return false;
    }
    if(courtno == '')
    {
        alert('Please select Court No.');
        return false;
    }
    if(value == '')
    {
        alert('Please Enter Cause List Item No.');
        return false;
    }

    
    

    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        type: 'POSt',
        url: '<?php echo base_url('Library/Requisition/frmusrLogin'); ?>',
        data: {mode: "getCaseNo", item_no: value, 'dateitem': dateitm, court_no: courtno, CSRF_TOKEN :CSRF_TOKEN_VALUE},
        dataType: 'json',
       
        error: function () {
            updateCSRFToken();
            console.log("error");
        },
        success: function (response) {
            console.log(response);
            updateCSRFToken();
            console.log(response);
            if(response){
                $('#dynamicDetails').show()
                $('#case_no').html(response.reg_no_display+' @ '+response.diary_no);
                $('#pet_res_name').html(response.pet_name+' Vs '+response.res_name);
                $('#diary_no').val(response.diary_no);
                $('#itm_date').html( response.next_dt.split("-").reverse().join("-") )
            }else{
                $('#dynamicDetails').hide()
                $('#case_no').html('');
                $('#pet_res_name').html('');
                $('#diary_no').val('');
                $('#itm_date').html('')
            }

        },
        error: function () {
            updateCSRFToken();
            // alert("Failure");
        }
    });
}

</script>
