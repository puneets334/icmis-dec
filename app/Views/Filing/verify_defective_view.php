<?=view('header'); ?>
 

    <style>
        .scrol{
            width: 80%;
            height: 200px;
            overflow: auto;
            margin: 0 auto;
            border: 0.6px dotted #d8d8d8;
        }
    </style>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header heading">

                            <div class="row">
                                <div class="col-sm-10">
                                    <h3 class="card-title">IA / Document Details</h3>
                                </div>
                                <?=view('Filing/filing_filter_buttons'); ?>
                            </div>
                        </div>
                        <?//=view('Filing/filing_breadcrumb'); ?>
                        <!-- /.card-header -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header p-2" style="background-color: #fff;">
                                        <ul class="nav nav-pills inner-comn-tabs">
                                            <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents" >Insert / Modification of Docs.</a></li>
                                            <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>/Filing/Ia_documents/caseBlockList_view" >Case Block for Loose Doc. </a></li>
                                            <li class="nav-item"><a class="nav-link active" href="<?= base_url() ?>/Filing/Ia_documents/verify_defective_view" >Verfify / Defects </a></li>
                                        </ul>
                                        <?php
                                        $attribute = array('class' => 'form-horizontal', 'name' => 'party_view_form', 'id' => 'party_view_form', 'autocomplete' => 'off');
                                        echo form_open('Filing/Ia_documents/save_iaDoc_details', $attribute);
                                        ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">

                                            <div class="active tab-pane" id="verify_defect_tab_panel">
                                                
                                                <!-- Edit table 3 -->
                                                <div class="row mt-5">
                                                    <div class="col-md-12">
                                                        <h3 class="card-title" style="float: none !important; text-align: center;">RECORDS TO BE VERIFY FOR <?= $userDetails_verify != '' ? $userDetails_verify : '' ?> </h3>
                                                        <div class="table-responsive mt-3">
														<table id="example3" class="table table-striped custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No.</th>
                                                                    <th>Action</th>
                                                                    <th>Defect Remarks</th>
                                                                    <th>Document No.</th>
                                                                    <th>Document Type</th>
                                                                    <th>Diary No.</th>
                                                                    <th>Case Nos.</th>
                                                                    <th>Remarks</th>
                                                                    <th>Dispatch By</th>
                                                                    <th>Dispatch Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if(!empty($verify_defective)){
                                                                        foreach ($verify_defective as $sno => $row) { ?>
                                                                            <tr>
                                                                                <td><?= $sno +1 ?></td>
                                                                                <td>
                                                                                    <div class="form-group clearfix">
                                                                                        <div class="icheck-primary d-inline">
                                                                                            <input type="checkbox" name="chk<?php echo $sno+1;?>"  id="chk<?php echo $sno+1;?>" value="<?php echo $row['diary_no'].'-'.$row['doccode'].'-'.$row['doccode1'].'-'.$row['docnum'].'-'.$row['docyear']; ?>"/>
                                                                                            <label for="chk<?php echo $sno+1;?>"></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="tb<?php echo $sno +1;?>"  id="tb<?php echo $sno +1;?>" value="<?php echo $row['verified_remarks']; ?>"/>
                                                                                </td>
                                                                                <td><?= '<span style=color:blue>'.$row['kntgrp'].'</span>- '.$row['docnum'].'/'.$row['docyear'] ?></td>
                                                                                <td><?= $row['docdesc'] ?> <?= ($row['other1'] != '' || $row['other1'] != NULL) ? '-'.$row['other1'] : '' ?> </td>
                                                                                <td><?= $row['real_diaryno'] ?></td>
                                                                                <td><?= $row['casenos_comma'] ?></td>
                                                                                <td><?= $row['remarks']; ?></td>
                                                                                <td><?= $row['user_details']; ?></td>
                                                                                <td><?= $row['disp_dt']; ?></td>
                                                                                
                                                                            </tr>  
                                                                    <?php  }
                                                                    }else{?>
                                                                        <!-- <tr>
                                                                            <td colspan="10" style="text-align: center;font-size: 18px;">No record found.</td>
                                                                        </tr> -->
                                                                    <?php }?>
                                                                    
                                                            </tbody>
                                                        </table>
														</div>
                                                    </div>
                                                </div>


                                                <div class="row mt-3 text-center verAction">
                                                    <div class="col-md-12">
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="vr" name="radio_verified" value="V" >
                                                                <label for="vr">Verified</label>
                                                            </div>&nbsp;&nbsp;
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="def_r" name="radio_verified" value="R">
                                                                <label for="def_r">Defective</label>
                                                            </div>&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-4 mt-2 text-center verAction">
                                                    <span class="btn btn-primary" id="btnrece" onclick="verifyFunction()">Verify </span>
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


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <script>
        function updateCSRFToken() {
            $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }

        function replace_amp(x){
            document.getElementById(x).value=document.getElementById(x).value.trim();
            document.getElementById(x).value=document.getElementById(x).value.replace( '&', ' and ' );
            document.getElementById(x).value=document.getElementById(x).value.replace( "'", "");
            document.getElementById(x).value=document.getElementById(x).value.replace( "#", "No");
        }
        

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })

        function getDocTypeDetails(){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            let dno = $("#hdfno").val()
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, dno},
                url: "<?php echo base_url('Filing/Ia_documents/getDoc_type1'); ?>",
                success: function (data) {

                    if(data != ''){
                        data = JSON.parse(data)
                        // console.log(data)
                        let html = ''
                        data.forEach(el => {
                            html += '<option value="'+el.value+'">'+el.label+'</option>'
                        })
                        $('#m_doc1').append(html)
                    }
                    updateCSRFToken();
                },
                error: function () {
                    $('#m_doc1').append('')
                    updateCSRFToken();
                }
            }); 
        }

        function getRemarksList(txt){
            updateCSRFToken()

            setTimeout(() => {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, txt},
                    url: "<?php echo base_url('Filing/Ia_documents/getRemarksList'); ?>",
                    success: function (data) {

                        if(data != ''){
                            data = JSON.parse(data)
                            // console.log(data)
                            let html = ''
                            data.forEach(el => {
                                html += '<option value="'+el.remark_data+'">'
                            })
                            $('#docRemarks').append(html)
                        }else{
                            $('#docRemarks').html('')
                        }
                        updateCSRFToken();
                    },
                    error: function () {
                        $('#docRemarks').html('')
                        updateCSRFToken();
                    }
                });    
            }, 300);
            
        }

        $(document).ready(function() {

            let verify_dataArr = '<?= json_encode($verify_defective, true); ?>'
            let verifyData = JSON.parse(verify_dataArr)
            // console.log("verify_dataArr:: ", verifyData )
            if(verifyData.length == 0){
                $('.verAction').hide()
            }


            $("#example1").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $("#example2").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
            $("#example3").DataTable({
              "responsive": true, "lengthChange": false, "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

            $('#get_court_details').append('<hr />');
            $('.aftersubm').append('<hr />');
            $('.befSubmt').prepend('<hr />');

            $('input[type=radio][name=radio_filed_by]').change(function() {
                updateCSRFToken();
                if (this.value == 'A') {
                    $('.filed_Aor').show()
                    $('.filed_Res').hide()
                    $('.filed_Pet').hide()
                } else if (this.value == 'P') {
                    $('.filed_Pet').show()
                    $('.filed_Res').hide()
                    $('.filed_Aor').hide()
                } else if (this.value == 'R'){
                    $('.filed_Res').show()
                    $('.filed_Pet').hide()
                    $('.filed_Aor').hide()

                }

                if(this.value == 'P' || this.value == 'R'){
                      
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    let type = this.value
                    let obj = { dno: $('#hdfno').val(), type } 
                    $.ajax({
                        type: "POST",
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, data : obj},
                        url: "<?php echo base_url('Filing/Ia_documents/getPetResList'); ?>",
                        success: function (data) {
                            if(data != ''){
                                data = JSON.parse(data)
                                let html = ''
                                data.forEach(el => {
                                    html += '<option value="'+el.value+'">'+el.label+'</option>'
                                })
                                if(type == 'P'){
                                    $('#name_pet_filed_by').html('<option value="">Select</option> ')
                                    $('#name_pet_filed_by').append(html)
                                }else if(type == 'R'){
                                    $('#name_res_filed_by').html('<option value="">Select</option> ')
                                    $('#name_res_filed_by').append(html)
                                }                                    
                                updateCSRFToken();
                            }
                        },
                        error: function () {
                            $('#name_res_filed_by').html('<option value="">Select</option> ')
                            updateCSRFToken();
                        }
                    });    
                }

            });

            // 7case
            $("#m_doc").change(function() {
                let valOpt = $("option:selected", this).val();
                // alert(valOpt)
                if(valOpt == 7){
                    $('.7case').show()
                    $('#m_amt').attr('disabled', true)
                }else{
                    $('.7case').hide()
                    $('#m_amt').attr('disabled', false)
                }


                if(valOpt==8){
                    
                    setTimeout(() => {
                        getDocTypeDetails();
                    }, 200);

                    $("#m_doc1").removeAttr("disabled");
                    $("#m_doc1").val("");
                    $("#hd_doc_type1").val("");
                }else{
                    $("#m_doc1").attr('disabled',true);
                    $("#m_doc1").val("");
                    $("#hd_doc_type1").val("");

                    $('#m_doc1').html('')
                    $('#select2-m_doc1-container').text('')
                }

                if(valOpt==8  &&  document.getElementById('m_doc1').value==19){
                    $("#m_desc").removeAttr('disabled');
                }
                else if(valOpt == 10){
                    $("#m_desc").attr('disabled',false);
                }else{
                    $("#m_desc").attr('disabled',true);
                }


                var am = document.getElementById('m_doc').options[document.getElementById('m_doc').selectedIndex].text.split('::');
                if(document.getElementById('m_doc').value>0  && document.getElementById('m_doc').value!=7) {
                    document.getElementById('m_amt').value=am[1];
                }
                else{
                    document.getElementById('m_amt').value=0
                }

            })


            $('input#doc_remark').on('keypress', function(e) {
                // e.preventDefault()
                // e.stopPropagation()
                if(this.value.length >= 3){
                    let txt = this.value
                    setTimeout(() => {
                        getRemarksList(txt)
                    }, 200);
                }
            });


        })


        $('#m_doc1').on('change', function(){
            let val = $("option:selected", this).val();
            let text = $("option:selected", this).text()
            $('#hd_doc_type1').val(val);
            $("#m_doc1").val(val);
            $('#select2-m_doc1-container').text( $("#m_doc1 option:selected").text())


            if(document.getElementById('m_doc').value==8 && (val==50 || val==63)){
                var t_pet=document.getElementById('hd_pcount').value;  
                for(var i=1;i<=t_pet;i++)
                {
                    document.getElementById('chk_p'+i).style.display='inline';
                    document.getElementById('img_p'+i).style.display='inline';
                }
            }
            else
            {
                var t_pet=document.getElementById('hd_pcount').value;  
                for(var i=1;i<=t_pet;i++)
                {
                    if(document.getElementById('chk_p'+i))
                        document.getElementById('chk_p'+i).style.display='none';
                    if(document.getElementById('img_p'+i))
                        document.getElementById('img_p'+i).style.display='none';
                }
            }

            if(val>0) {
                if((document.getElementById('m_doc').value==8  && val==19) || (document.getElementById('m_doc').value==9  && val==10)) 		 	
                {
                    document.getElementById('m_desc').disabled=false;
                    document.getElementById('m_desc').focus();
                }
                else   					
                {
                    document.getElementById('m_desc').disabled=true;
                    document.getElementById('m_desc').value='';
                }
            }//end if
            else{		
                document.getElementById('m_desc').disabled=true;
                document.getElementById('m_amt').value=0;
            }

        })

        
        $('#aor_code').on('change', function(e){
            if(this.value != ''){
                let val = this.value 
                let valu = val.split('-') 

                setTimeout(() => {
                    $('#select2-aor_code-container').text(valu[0])
                    $('#name_aor_filed_by').val(valu[1])
                }, 100);
            }else{
                $('#name_aor_filed_by').val('')
            }
        })




        function save_loose(){
            var aorcode=0;
            var filedbyname='';
            var regNum = new RegExp('^[0-9]+$');

            updateCSRFToken();


            var if_efil=0;
            if($("#if_efil").is(":checked")){
                if_efil=1;
            }
            

            alert("Registry is directed not to accept any application or petition on behalf of:- \n 1) Suraj India Trust or Mr. Rajiv Daiya as per Hon'ble Court Order dated. 08-02-2018 in MA no. 1158/2017(Suraj India Trust Vs UOI) \n 2) ASOK PANDE as per Hon'ble Court Order dated. 26-10-2018 in WP(C) No. 965/2018 (ASOK PANDE Vs UOI) \n 3) MANOHAR LAL SHARMA  as per Hon'ble Court Order dated. 07-12-2018 in WP(CRL) No. 315/2018 (MANOHAR LAL SHARMA Vs ARUN JAITLEY (AT PRESENT FINANCE MINISTER))\n" +
                " 4) P1-SURAJ MISHRA and P2-ROHIT GUPTA  as per Hon'ble Court Order dated. 08-05-2019 in WP(C) No. 1328/2018 (SURAJ MISHRA AND ANR VS. UNION OF INDIA AND ANR)");


                
            if($('input[type=radio][name=radio_filed_by]:checked').length==0){
                alert('Please Select Filed By');
                return false;
            }
            var filedby = $('input[type=radio][name=radio_filed_by]:checked').val();
            if(filedby=='A'){
                let AORcd = $("#aor_code").val()
                if(AORcd != ''){
                    let arcd = AORcd.split('-')
                    if(!regNum.test( arcd[0] )){
                        alert("Please Fill AOR Code in Numeric");
                        $("#aor_code").focus();
                        return false;
                    }else{
                        aorcode = arcd[0]
                        filedbyname = $("#name_aor_filed_by").val();
                    }
                }else{
                    alert("Please Fill AOR Code in Numeric");
                    $("#aor_code").focus();
                    return false;
                }
                
                if($("#name_aor_filed_by").val()==''){
                    alert('Please Select Proper Advocate');
                    $("#aor_code").focus();
                    return false;
                }
            }
            else if(filedby=='P'){
                //alert($("#name_pet_filed_by").val().trim());
                if($("#name_pet_filed_by").val().trim() == ""){
                    alert('Please Fill Filedby');
                    $("#name_pet_filed_by").focus();
                    return false;
                }else{
                    filedbyname = $("#name_pet_filed_by").val();
                }
            }
            else if(filedby=='R'){
                if($("#name_res_filed_by").val().trim() == ""){
                    alert('Please Fill Filedby');
                    $("#name_res_filed_by").focus();
                    return false;
                }else{
                    filedbyname = $("#name_res_filed_by").val();
                }
            }
            
            if($("#m_doc").val()=='0'){
                alert("Please Select Document Type");
                $("#m_doc").focus();
                return false;
            }
            
            
            if($("#m_doc").val() == 8){
                if($("#m_doc1").val()==''){
                    alert("Please Select IA");
                    $("#m_doc1").focus();
                    return false;
                }
            }
            
            if(!regNum.test($("#m_amt").val())){
                alert("Please Enter Amount in Numeric Value");
                $("#m_amt").focus();
                return false;
            }
            
            if(!regNum.test($("#no_of_copy").val())){
                alert("Please Fill No. of Copies in Numeric");
                $("#no_of_copy").focus();
                return false;
            }
            
            var pet_master = "";
            if($("#last_pet"))
            {
                for(var p=1;p<=$("#last_pet").val();p++)
                {
                    if($("#petchk_pro_"+p))
                    {
                        if($("#petchk_pro_"+p).is(":checked"))
                            pet_master += $("#petchk_pro_"+p).val()+'~'+$("#pet_sel_"+p).val()+',';
                    }
                }
                pet_master = pet_master.substring(0,pet_master.length-1);
            }
            var res_master = "";
            if($("#last_res"))
            {
                for(var r=1;r<=$("#last_res").val();r++)
                {
                    if($("#reschk_pro_"+r))
                    {
                        if($("#reschk_pro_"+r).is(":checked"))
                            res_master += $("#reschk_pro_"+r).val()+'~'+$("#res_sel_"+r).val()+',';
                    }
                }
                res_master = res_master.substring(0,res_master.length-1);
            }
            
            var m_resp='';
            var f_mode='';
            var m_fee1='';
            var m_fee2='';
            if($("#m_resp")){
                m_resp = $("#m_resp").val();
            }
            if($("#f_mode")){
                f_mode = $("#f_mode").val();
            }
            if($("#m_fee1")){
                m_fee1 = $("#m_fee1").val();
            }
            if($("#m_fee2")){
                m_fee2 = $("#m_fee2").val();
            }
            
            var party='';
            for(var i=1;i<=$("#hd_pcount").val();i++)
            {
                if(document.getElementById('chk_p'+i))
                {
                    if(document.getElementById('chk_p'+i).checked==true)
                    {
                        party += document.getElementById('chk_p'+i).value+',';
                    }
                }
            }
            party = party.substring(0,party.length-1);
            $("#b_save").prop('disabled',true);

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
            $.ajax({
                type: 'POST',
                url:"<?php echo base_url('Filing/Ia_documents/save_loose'); ?>",
                data:{ CSRF_TOKEN: CSRF_TOKEN_VALUE, hdfno:$("#hdfno").val(),filedby:filedbyname,doccode:$("#m_doc").val(),doccode1:$("#hd_doc_type1").val(),docfee:$("#m_amt").val(),other1:$("#m_desc").val(),forresp:m_resp,feemode:f_mode,fee1:m_fee1,fee2:m_fee2,t_p:$("#hd_pcount").val(),pet_master:pet_master,res_master:res_master,aorcode:aorcode,party:party, remark:$("#doc_remark").val(),copy:$("#no_of_copy").val(),if_efil: if_efil }
            })
            .done(function(msg){
                if(msg!=''){
                    msg = JSON.parse(msg)
                    // console.log(msg.message)

                    // if($("#m_doc").val()==8){   
                        //alert('IA');
                        //alert(m_doc1.value);
                        //if(m_doc1.value=='2' || m_doc1.value=='38' || m_doc1.value=='27' || m_doc1.value=='16')
                        // mark_proposal($("#hdfno").val());
                    // }
                    //document.getElementById("dv_aaau").style.display='block';
                    // document.getElementById("IA_NO2").innerHTML=msg;
                    alert(msg.message);
                    setFields();
                    location.reload();
                }
                else{
                    // document.getElementById("IA_NO2").innerHTML=""; 
                }
                updateCSRFToken();
            })
            .fail(function(){
                $("#b_save").removeProp('disabled');
                alert("ERROR, Please Contact Server Room"); 
                updateCSRFToken();
            });
        }


        function setFields(){
            $("#b_save").removeProp('disabled');
            $("#doc_remark").val("");
            $("#m_desc").val("");
            $("#m_desc").prop('disabled',true);
            $("#no_of_copy").val("4");
            $("#m_amt").val("0");
            $("#m_doc1").val("");
            $("#m_doc1").prop('disabled',true);
            $("#hd_doc_type1").val("");
            $("#m_doc").val("0");
            $("#name_aor_filed_by").prop('readonly',true);
            $("#if_efil").prop("checked", false);
            

            var radio_value= $('input[name=radio_filed_by]:checked').val();
            if(radio_value=='A'){
                $("#aor_code").val("");
                $("#name_aor_filed_by").val("");
            }
            else if(radio_value=='P'){
                $("#name_pet_filed_by").val("");
            }
            else if(radio_value=='R'){
                $("#name_res_filed_by").val("");
            }
            
        }


        function delete_ld(id,rno){
            if(confirm('Sure to Delete')){
                updateCSRFToken();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: 'POST',
                    url:'<?php echo base_url('Filing/Ia_documents/del_for_ld_del'); ?>',
                    data:{CSRF_TOKEN: CSRF_TOKEN_VALUE,type:'D',idfull:id}
                })
                .done(function(msg){
                    alert(msg);
                    location.reload()
                    updateCSRFToken();
                })
                .fail(function(){
                    alert('ERROR, Please Contact Server Room'); 
                    updateCSRFToken();
                });
            }
            else{
                return false;
            }
        }

        var Objset = ''
        function update_ld(id){
            
            let fullid = id.split('~')
            Objset = id
            // console.log("id:: ", fullid)
            // return
            let dno = fullid[0];
            let doccode = fullid[1];
            let doccode1 = fullid[2];
            let docnum = fullid[3];
            let docyear = fullid[4];
            let advid = fullid[5];
            // let src = fullid[6];
            let docid = fullid[7];
            let is_efiled= fullid[8];
            let docfee = fullid[9]
            let docdesc = fullid[10]
            let remark = fullid[11]
            let no_of_copy = fullid[12]
            let filDate = fullid[13]
            let filuser = fullid[14]
            let kntgrop = fullid[15] 
            let filedby = fullid[16]

            if(is_efiled == 'Y'){
                $('#if_efil').prop('checked', true)
            }
            $('#hdfno').val(dno)

            $("#m_doc").val(doccode)
            $('#select2-m_doc-container').text( $("#m_doc option:selected").text())

            if(doccode == 8){
                setTimeout(() => {
                    getDocTypeDetails();

                    setTimeout(() => {
                        $("#m_doc1").val(doccode1)
                        $('#select2-m_doc1-container').text( $("#m_doc1 option:selected").text())
                        $("#hd_doc_type1").val(doccode1) 
                        $("#m_doc1").removeAttr("disabled");       
                    }, 500);
                }, 200);                
            }
            

            $('#m_amt').val(docfee)

            $('#m_desc').val(docdesc)
            $('#doc_remark').val(remark)
            $('#no_of_copy').val(no_of_copy)


            $('#doc_num').val( docnum+'/'+docyear+ '-'+kntgrop )
            $('#fil_date').val(filDate)
            $('#receivd_by').val(filuser)
            
            $('#updateDetails').show()
            

            if(advid == '' || advid == '0'){
                $('#filledBy').hide()
            }else{
                $('#filledBy').hide()
                $('#aor_code').val( advid+'-'+filedby )
                $('#name_aor_filed_by').val(filedby)
                $('#select2-aor_code-container').text(advid)
                $('#filed_by_aor').prop('checked', true)
                $('.filed_Aor').show()
                $('.filed_Res').hide()
                $('.filed_Pet').hide()
            }

            $('html').animate({scrollTop:0}, 'slow');//IE, FF
            $('body').animate({scrollTop:0}, 'slow');//chrome, don't know if Safari works

            $("#m_desc").attr('disabled',true);
            $('#saveLoose').hide()
            $('#updateLoose').show()

            return

        }

        function update_loose(){
            // console.log("fullid:: ", Objset)
            let fee = $('#m_amt').val()
            let if_efil=0;
            if($("#if_efil").is(":checked")){
                if_efil=1;
            }
            let doccode = $("#m_doc").val()
            let doccode1 = $("#hd_doc_type1").val()
            let other1 = $('#m_desc').val()
            let rem = $('#doc_remark').val()
            let aor = $('#select2-aor_code-container').text()
            let noc = $('#no_of_copy').val()
            let aor_name = $('#name_aor_filed_by').val()
            let frsp = $("#m_resp").val();

            let obj = { fee , if_efil , doccode , doccode1 , other1 , rem , aor , noc , aor_name , frsp }

            updateCSRFToken();

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url:'<?php echo base_url('Filing/Ia_documents/loose_up_new'); ?>',
                data:{ CSRF_TOKEN: CSRF_TOKEN_VALUE, idfull: Objset, data: obj }
            })
            .done(function(msg){
                alert(msg);
                // $("#sar").html(msg);
                setFields();
                location.reload();
                updateCSRFToken();
            })
            .fail(function(){
                updateCSRFToken();
            });


        }




        $('.caseBlckDelete').on("click",function(){
            var num = this.id;
            // alert(num)
            // return
            updateCSRFToken();
            if(confirm("ARE YOU SURE TO REMOVE THIS RECORD") == true){

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: 'POST',
                    url:"<?php echo base_url('Filing/Ia_documents/delete_case_block'); ?>",
                    data:{CSRF_TOKEN: CSRF_TOKEN_VALUE, id: num }
                })
                .done(function(msg){
                    var msg2 = msg.split('~');
                    if(msg2[0] == 1){
                        alert(msg2[1])
                        location.reload()
                        updateCSRFToken();
                        // $(".add_result").css("display","block");
                        // $(".add_result").css("color","#90C695");
                        // $(".add_result").html(msg2[1]);
                        
                        // $.ajax({
                        //     type: 'POST',
                        //     url:"./get_case_block.php",
                        //     data:{mat:2}
                        // })
                        // .done(function(msg_new){
                        //     $("#result_main").html(msg_new);
                        // })
                        // .fail(function(){
                        //     alert("ERROR, Please Contact Server Room"); 
                        // });
                    }
                    else{
                        alert('Error while deleting')
                        updateCSRFToken();
                        // $(".add_result").css("display","block");
                        // $(".add_result").css("color","red");
                        // $(".add_result").html(msg);
                    }
                })
                .fail(function(){
                    alert("ERROR, Please Contact Server Room");
                    updateCSRFToken();
                });
            }
        });


        $("#btnMain").click(function(){
        
            var low = $("#dno").val().trim();
            var up = $("#dyr").val().trim();
            var reg123 = new RegExp('^[0-9]+$');
            if(!reg123.test(low)){
                alert("Please Enter Numeric Value Only");
                $("#dno").focus();
                return false;
            }
            if(!reg123.test(up)){
                alert("Please Enter Numeric Value Only");
                $("#dyr").focus();
                return false;
            }
            if($("#bl_reason").val()==""){
                alert("Please Enter Reason");
                $("#bl_reason").focus();
                return false;
            }

            updateCSRFToken();

            let obj = { dno:low, dyr:up,reason:$("#bl_reason").val() }

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Filing/Ia_documents/save_case_block'); ?>",
                async: false,
                data:{CSRF_TOKEN: CSRF_TOKEN_VALUE, data: obj }
            })
            .done(function(msg){
                //alert(msg);
                var msg2 = msg.split('~');
                if(msg2[0] == 1){
                    alert(msg2[1])
                    location.reload()
                    updateCSRFToken();
                    // $(".add_result").css("display","block");
                    // $(".add_result").css("color","green");
                    // $(".add_result").html(msg2[1]);
                    // $("#dno").val("");
                    // $("#dyr").val("");
                    // $("#bl_reason").val("");
                    // $(".add_result").slideUp(3000);
                    // $.ajax({
                    //     type: 'POST',
                    //     url:"./get_case_block.php",
                    //     data:{mat:2}
                    // })
                    // .done(function(msg_new){
                    //     $("#result_main").html(msg_new);
                    // })
                    // .fail(function(){
                    //     alert("ERROR, Please Contact Server Room"); 
                    // });
                }
                else{
                    alert("Error while adding Case Block")
                    updateCSRFToken();
                    // $(".add_result").css("display","block");
                    // $(".add_result").css("color","red");
                    // $(".add_result").html(msg);
                }
            })
            .fail(function(){
                alert("ERROR, Please Contact Server Room");
                updateCSRFToken();
            }); 

        });



        function verifyFunction(){
            var full_data = new Array();
            var full_data_tb = new Array();
            var check = false; var vr='';
            $("input[type='checkbox'][name^='chk']").each(function () {
                if($(this).is(":checked")==true){
                    full_data.push($(this).val());
                    var thisid=$(this).attr('id');
                    thisid=thisid.replace('chk','');
                    full_data_tb.push($('#tb'+thisid).val());
                    check = true;
                }
            })

            $("input[type='radio'][name^='radio_verified']").each(function () {
                if($(this).is(":checked")==true){
                    vr = $(this).val();
                }
            })
            if(check == false){
                alert("Please select at least one document");
                return false;
            }
            if(vr == ''){
                alert("Please select Verify or Reject");
                return false;
            }
            if(vr == 'V'){
                var vr_text='Verify';
            }
            if(vr == 'R'){
                var vr_text='Reject';
            }
            vr_text = "Are you sure to "+vr_text+" selected documents";
            if(confirm(vr_text)){
            
                updateCSRFToken();

                $("#btnrece").prop('disabled','disabled');
                let obj = { alldata:full_data, vr:vr, tb:full_data_tb }
                // console.log(obj)

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                
                $.ajax({
                    type:"POST",
                    url:"<?php echo base_url('Filing/Ia_documents/verify_save'); ?>",
                    data:{ CSRF_TOKEN: CSRF_TOKEN_VALUE, data: obj }
                })
                .done(function(msg){
                    // $("#btnrece").removeProp('disabled');
                    if(msg!=''){
                        msg = Number(msg);
                        // console.log("msg: ", msg)
                        if(msg >= 1){
                            alert(msg+' : Record updated.');
                            location.reload();
                        }else{
                            alert('No record updated');
                        }
                        updateCSRFToken();
                    }else{
                        alert('Error updating ');
                        updateCSRFToken();
                    }
                    
                })
                .fail(function(){
                    // $("#btnrece").removeProp('disabled');
                    alert("Error Occured, Please Contact Server Room");
                    updateCSRFToken();
                });

            }   
        }

    </script>