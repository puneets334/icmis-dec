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
    .indexHeading {
        font-weight:bold !important;
        text-align:center!important;
        font-size:18px !important;
    }
    table tr th {
        font-weight:bold;
    }
    .docHead {
        text-align: center;
        display: block;
        color: red;
    }


</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p id="show_error"></p>

                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Add Update</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">                               
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            <form id="indexingFrm" method="POST" action="">
                                                <?=csrf_field(); ?>
                                                <div class="row">

                                                    <div class="col-md-5 diary_section">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Diary No</label>
                                                            <div class="col-sm-7">
                                                                <input type="number" class="form-control" id="diary_number" name="diary_number" placeholder="Enter Diary No" value="<?php echo isset($diary_number)?$diary_number:'';?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 diary_section">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Diary Year <?php echo $diary_year; ?></label>
                                                            <div class="col-sm-5">
                                                                <?php $year = 1950;
                                                                 $total=0;
                                                                $current_year = date('Y');
                                                                ?>
                                                                <select name="diary_year" id="diary_year" class="custom-select rounded-0">
                                                                <?php for ($x = $current_year; $x >= $year; $x--) { ?>
                                                                    <option value="<?php echo $x; ?>" <?php echo ($x === (int)$diary_year) ? 'selected' : ''; ?>>
                                                                        <?php echo $x; ?>
                                                                    </option>
                                                                <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group row">
                                                            <div class="col-sm-7">
                                                                <button type="button" id="sub" name="sub" value="date_wise" class="btn btn-block btn-primary">Search</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                                <div id="dv_data">
                                                <input type="hidden" name="hd_fil_no" id="hd_fil_no"/>
                                                <input type="hidden" name="hd_pdf_name" id="hd_pdf_name"/>
                                                <input type="hidden" name="hd_docd_ids" id="hd_docd_ids"/>
                   
                                                    <div id="result">
                                                        <?php
                                                        if(isset($result)  && !empty($result))
                                                        {
                                                            ?>

                                                            <form id="indexingSaveFrm" method="POST" action="">
                                                                <?=csrf_field(); ?>

                                                            <div class="col-12 text-center" style="padding-bottom:20px;" id="case_info">
                                                                <?php
                                                                    if ($result->c_status == 'D')
                                                                    {

                                                                        echo '<div style="color: red;">THE CASE IS DISPOSED</div>';  
                                                                    }   
                                                                ?>
                                                                <span>
                                                                    <b><?php echo $result->pet_name; ?></b>
                                                                </span>
                                                                    Versus
                                                                <span>
                                                                    <b><?php echo $result->res_name; ?></b>
                                                                </span>
                                                            </div>
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <td style="width:20%">Indexing For:</td>
                                                                    <td>
                                                                        <select id="i_type" class="form-control" onchange="set_frmNo(this.value)">
                                                                            <option value="1" <?php if ( isset($_REQUEST['itype']) && $_REQUEST['itype'] == 1) echo "Selected"; ?>>Part 1</option>
                                                                            <option value="2" <?php if ( isset($_REQUEST['itype']) && $_REQUEST['itype'] == 2) echo "Selected"; ?>>Part 2</option>
                                                                        </select></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Document:</td>
                                                                    <td>
                                                                        <select id="m_doc" name="m_doc" class="form-control" onchange="getSubDoc(this.value); chk_other();">
                                                                            <option value=''>Select</option>
                                                                            <?php 
                                                                                if(count($all_doc)>0)
                                                                                {
                                                                                    foreach($all_doc as $doc) { ?>
                                                                                    <option value="<?php echo $doc->doccode; ?>"><?php echo $doc->docdesc; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                }                                                                        
                                                                            ?>
                                                                        </select>
                                                                        <select id="s_doc" onchange="chk_other()">
                                                                            <option value="0">Select</option>
                                                                             </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Other:</td>
                                                                    <td>
                                                                    <input type="text" id="other_desc" style="width: 290px;text-transform:uppercase;" disabled class="form-control" ></td>
                                                                </tr>
                                                                <tr>
                                                                    <?php 
                                                                        $from_page_start=1; 
                                                                    ?>
                                                                    <td style="width:20%"> <span>From Page -</span> <input type="text" size="3" maxlength="5" id="fpage" onkeypress="return onlynumbers(event)" value="<?php echo $from_page_start; ?>" disabled  class="form-control"  /></td>
                                                                    <td style="white-space: nowrap;">
                                                                    <span> To Page -</span><input type="text" size="3" maxlength="5" id="tpage" onblur="fill_no('t')" onkeypress="return onlynumbers(event)" style="width: 15% !important; margin-left: 5px;"  class="form-control"  /> &nbsp; &nbsp;<span> No. of Pages - </span><input  class="form-control"  type="text" size="3" maxlength="4" id="npage" onblur="fill_no('n')" onkeypress="return onlynumbers(event)" style="width: 15% !important; margin-left: 5px;" />                                                            
                                                                
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Upload PDF:</td>
                                                                    <td>
                                                                    <input type="file"  id="upd_file"    name="upd_file" class="form-control" >
                                                                    &nbsp;&nbsp;
                                                                    <span id="sp_up_nu" style="color:red"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Court:</td>
                                                                    <td>
                                                                        <select id="ddl_court" name="ddl_court" class="form-control" onchange="getSubDoc(this.value); chk_other();">
                                                                            <option value=''>Select</option>
                                                                            <?php 
                                                                                if(count($courtDetails)>0)
                                                                                {
                                                                                    foreach($courtDetails as $court) { ?>
                                                                                    <option value="<?php echo $court->ct_code; ?>"><?php echo $court->court_name; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                }                                                                        
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">State:</td>
                                                                    <td>
                                                                        <select id="ddl_st_agncy" name="ddl_st_agncy" class="form-control">
                                                                            <option value=''>Select</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Bench:</td>
                                                                    <td>
                                                                        <select id="ddl_bench" name="ddl_bench" class="form-control">
                                                                            <option value=''>Select</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="width:20%">Case No:</td>
                                                                    <td>
                                                                        <select id="ddl_case_no" name="ddl_case_no" class="form-control">
                                                                            <option value=''>Select</option>
                                                                            
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2"><input type="button" value="Save Record" onclick="saveIT('S')" id="btnSAVE" />
                                                                        <input type="button" value="Update Record" onclick="saveIT('U')" id="btnUPDATE" style="display: none" />
                                                                        &nbsp;
                                                                        <input type="button" value="Cancel Update" onclick="CancelUp()" id="btnCN_UP" style="display: none" />
                                                                        <input type="button" name="btn_new_ent" id="btn_new_ent" style="display:none" value="New" />
                                                                    </td>
                                                                </tr>                                                               
                                                             </table>
                                                            
                                                            <div class="col-12 row">
                                                                <div style="margin: 0 auto; overflow: auto;margin: auto">
                                                                    <div id="for_print" style="width: 100%;margin: auto">
                                                                        <table align="center"  style="margin-top: 20px; display:block:text-align:center;" cellspacing="5" cellpadding="5" id="prt_tb" class="table_tr_th_w_clr c_vertical_align table-bordered">
                                                                            <tr id="r1" style="display: none" >
                                                                                <th colspan="8" style="font-size: 20px;">SUPREME COURT OF INDIA</th>
                                                                            </tr>
                                                                            <tr id="r2" style="display: none">
                                                                                <td colspan="7" style="text-align: center">Diary No - <strong><?php echo $diary_number . '-' . $diary_year; ?></strong></td>
                                                                            </tr>
                                                                            <tr id="r3" style="display: none">
                                                                                <td colspan="7"><u><?php echo $result->pet_name; ?><span style="float: right;text-decoration: underline">Petitioner</span></u></td>
                                                                            </tr>
                                                                            <tr id="r4" style="display: none">
                                                                                <td colspan="7"><u><?php echo $result->res_name; ?><span style="float: right;text-decoration: underline">Respondent</span></u></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th colspan="8" id='index_text' class="indexHeading">INDEX Part 1</th>
                                                                            </tr>
                                                                            <tr id="r5" style="display: none">
                                                                                <th colspan="7"><span style="text-decoration: underline;font-style: italic">List of documents related to 'A1' file</span></th>
                                                                            </tr>
                                                                            <tr class="with_border">
                                                                                <th rowspan="3">SNo.</th>
                                                                                <th rowspan="3">Particulars of Document</th>
                                                                                <th colspan="6">Page No. of part to which it belongs</th>
                                                                                <th rowspan="3">Against case</th>
                                                                                <th rowspan="3">Uploaded PDF</th>
                                                                                <th></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="3">
                                                                                    Part I<br />(Contents of Paper Book)
                                                                                </td>
                                                                                <td colspan="3">
                                                                                    Part II<br />(Contents of file alone)
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>From Page</th>
                                                                                <th>To Page</th>
                                                                                <th>No. of Pages</th>

                                                                                <th>From Page</th>
                                                                                <th>To Page</th>
                                                                                <th>No. of Pages</th>
                                                                            </tr>
                                                                            <?php
                                                                            $sno = 1;
                                                                            if(count($getIndexDocs)> 0) {
                                                                                foreach($getIndexDocs as $indoc) 
                                                                                {
                                                                                    ?>
                                                                                    <tr class="with_border" id="r1w<?php echo $sno ?>">
                                                                                        <td><?php echo $sno; ?></td>
                                                                                        <td>
                                                                                            <?php
                                                                                                if (trim($indoc->other) != '')
                                                                                                    echo $indoc->other;
                                                                                                else
                                                                                                    echo $indoc->docdesc;
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 1) {
                                                                                                    echo $indoc->fp; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>

                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 1) {
                                                                                                    echo $indoc->tp; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>

                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 1) {
                                                                                                    echo $indoc->np; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 2) {
                                                                                                    echo $indoc->fp; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 2) {
                                                                                                    echo $indoc->tp; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>

                                                                                        <td>
                                                                                            <?php 
                                                                                                if ($indoc->i_type == 2) {
                                                                                                    echo $indoc->np; 
                                                                                                }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php
                                                                                                if($indoc->lowerct_id !=0) {
                                                                                                    $scaningModel = new \App\Models\Scanning\ScaningModel();
                                                                                                    $lowerCourtDataDetails = $scaningModel->getLowerCourtDetails($indoc->lowerct_id);
                                                                                                    if(count($lowerCourtDataDetails)>0 ) {
                                                                                                        foreach($lowerCourtDataDetails as $lcd) {
                                                                                                            echo $lcd['court_name'] . '-' . $lcd['Name'] . '-' . $lcd['agency_name'] . '-' . $lcd['type_sname'] . '/' . $lcd['lct_caseno'] . '/' . $lcd['lct_caseyear'];
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            
                                                                                            ?>
                                                                                        </td>

                                                                                        <td>
                                                                                            <span id="spshow_<?php echo $sno; ?>" class="cl_hover">
                                                                                                <?php
                                                                                                    if ($indoc->pdf_name == '') {
                                                                                                        echo '-';
                                                                                                    } else {
                                                                                                        ?>
                                                                                                        Show 
                                                                                                        <?php
                                                                                                    }
                                                                                                ?> 
                                                                                            </span>

                                                                                            <input type="hidden" name="hdpdf_name_<?php echo $sno; ?>" id="hdpdf_name_<?php echo $sno; ?>" 
                                                                                            value="<?php if ($indoc->pdf_name != '') {  echo "../index_pdf/" . $diary_year . '/' . $diary_number . '/' . str_replace('+', ' ', urlencode($indoc->pdf_name)); } ?>" />
                                                                                        </td>

                                                                                        <td class="nodis">
                                                                                            <input type="button" onclick="give_for_update('<?php echo $indoc->fp; ?>','<?php echo $indoc->i_type ?>','0')" value="U" id="upbtn" class="cl_index1" />
                                                                                            <input type="button" onclick="give_for_delete('<?php echo $indoc->fp; ?>','<?php echo $indoc->i_type; ?>','<?php echo $sno; ?>')" value="D" id="delbtn" />
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php
                                                                                       
                                                                                        $sno++;
                                                                                        $total +=$indoc->np;
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr class="with_border">
                                                                                <td colspan="10" align="right"><strong>Total No. of Pages</strong></td>
                                                                                <td><?php echo $total; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="8" align="right"><strong>Dealing Assistant</strong></td>
                                                                            </tr>
                                                                            <tr id="last_row">
                                                                                <th colspan="8"><input type="button" value="Print" onclick="print_it('1',<?php echo $sno; ?>)" /></th>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <table width="100%" class=" table-bordered table_tr_th_w_clr c_vertical_align" cellpadding="3" cellspacing="3">
                                                                    <h6 class="docHead">Document/IA received from Loose Document counter Pending for scanning </h6>
                                                                        
                                                                   
                                                                    <tr>
                                                                        <th>
                                                                            Check
                                                                        </th>
                                                                        <th>
                                                                            SNo.
                                                                        </th>
                                                                        <th>
                                                                            Document No/Year
                                                                        </th>
                                                                        <th>
                                                                            Document Type
                                                                        </th>
                                                                    </tr>
                                                                            <?php
                                                                        if( isset($diaryDocumentsArray) && count($diaryDocumentsArray)>0) {
                                                                           $sno=1;
                                                                            foreach($diaryDocumentsArray as $k=> $row1) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <input type="radio" name="rdn_dco_type" id="rdn_dco_type<?php echo $sno ?>" class="cl_rdn_dco_type" />
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo $k+1; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span id="sp_docnum<?php echo $sno ?>">
                                                                                            <?php echo  isset($row1->docnum)?$row1->docnum:''; ?>
                                                                                        </span>/
                                                                                        <span id="sp_docyear<?php echo $sno ?>">
                                                                                        <?php echo  isset($row1->docyear)?$row1->docyear:''; ?>
                                                                                           
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span id="sp_docdesc<?php echo $sno ?>">
                                                                                            <?php echo  isset($row1->docdesc)?$row1->docdesc:''; ?>
                                                                                        </span>
                                                                                        <span id="sp_other<?php echo $sno ?>">
                                                                                            <?php
                                                                                                if ( isset($row1->other1) && $row1->other1!= '')
                                                                                                {
                                                                                                    echo ' - ' .  $row1->other1;                            
                                                                                                }
                                                                                            ?>
                                                                                        </span>
                                                                                        <input type="hidden" name="hd_doccode<?php echo $sno ?>" id="hd_doccode<?php echo $sno ?>" value="<?php echo isset($row1->doccode)?$row1->doccode:''; ?>" />
                                                                                        <input type="hidden" name="hd_doccodes<?php echo $sno ?>" id="hd_doccodes<?php echo $sno ?>" value="<?php echo isset($row1->doccode1)?$row1->doccode1:''; ?>" />
                                                                                        <input type="hidden" name="hd_docd_id<?php echo $sno ?>" id="hd_docd_id<?php echo $sno ?>" value="<?php echo isset($row1->docd_id)?$row1->docd_id:''; ?>" />
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                                $sno++;
                                                                            }
                                                                        }                                                                    
                                                                    ?>
                                                                </table>
                                                            </div>                                                            
                                                            <?php                                                         

                                                        }
                                                        else
                                                        { ?>
                                                             <tr>
                                                                <td colspan="4">
                                                                    <div class="cl_center"><b style="color:red; text-align:center; display:block;">No Record Found</b></div>
                                                                </td>
                                                            </tr>

                                                            <?php
                                                        }                                                        
                                                        ?>
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
        </div>
    </div>
</section>
<script src="<?= base_url('js/indexing.js') ?>"></script>
<script>
    $(document).ready(function() {
        $("#reportTable1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": 'Bfrtip',
            "bProcessing": true,
            "buttons": ["excel", "pdf"]
        });
    });

    $("#sub").click(function() {
        $("#result").html("");
        $('#show_error').html("");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diary_number = $("#diary_number").val().trim();
        var diary_year = $("#diary_year").val().trim();
        if (diary_number.length == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please Enter Diary Number</strong></div>');
            $("#from_date").focus();
            return false;
        }

        if (diary_year.v == 0) {
            $('#show_error').append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Please Enter Diary Year</strong></div>');
            $("#from_date").focus();
            return false;
        }
        else 
        {

            $("#indexingFrm").submit();

            // $.ajax({
            //     url: "<?php echo base_url('scanning/getIndexing'); ?>",
            //     cache: false,
            //     async: true,
            //     data: {
            //         search_flag: 'get_index_data',
            //         diary_number: diary_number,
            //         diary_year: diary_year,
            //         CSRF_TOKEN: CSRF_TOKEN_VALUE
            //     },
            //     beforeSend: function() {
            //         $('#result').html('<table width="100%" align="center"><tr><td><img src="../../images/load.gif"/></td></tr></table>');
            //     },
            //     type: 'POST',
            //     success: function(data, status) {
            //         updateCSRFToken();
            //         $("#result").html(data.html).css({
            //             "color": "red",
            //             "text-align": "center",
            //             "display": "block",
            //             "font-weight": "bold" // Optional for emphasis
            //         });
            //     },
            //     error: function(xhr) {
            //         updateCSRFToken();
            //         alert("Error: " + xhr.status + " " + xhr.statusText);
            //     }
            // });




        }
    });
    $(document).on('click','.cl_rdn_dco_type',function(){    
        var idd=$(this).attr('id');
        var sp_idd=idd.split('rdn_dco_type');
        var sp_docnum=$('#sp_docnum'+sp_idd[1]).html();
        var sp_docyear=$('#sp_docyear'+sp_idd[1]).html();
        var sp_other=$('#sp_other'+sp_idd[1]).html();
        var hd_doccode=$('#hd_doccode'+sp_idd[1]).val();          
        var sp_docdesc=$('#sp_docdesc'+sp_idd[1]).html();
        var hd_docd_id=$('#hd_docd_id'+sp_idd[1]).val();
        var diary_number = $('#diary_number').val();
        var diary_year = $('#diary_year').val();       
        $('#hd_docd_ids').val(hd_docd_id);
        $('#m_doc').val(hd_doccode);
        getSubDoc(hd_doccode,'u',sp_idd);
        $('#other_desc').val(sp_other);       
        $('#btn_new_ent').css('display','inline');
        $('#m_doc').attr('disabled',true);
        $('#s_doc').attr('disabled',true);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                url: "<?php echo base_url('scanning/addUpdateScanningDoc'); ?>",
                cache: false,
                async: true,
                data: {
                    diary_number: diary_number,
                    diary_year: diary_year,
                    CSRF_TOKEN: CSRF_TOKEN_VALUE
                },
                type: 'POST',
                success: function(data, status) {
                    // alert(data)
                    updateCSRFToken();
                    $('#fpage').val(data);
                    $(window).scrollTop(0);  
                },
                error: function(xhr) {
                    updateCSRFToken();
                    alert("Error: " + xhr.status + " " + xhr.statusText);
                }
            });
                            
                                
    });



    $(document).on('change', '#ddl_court', function() {
        //       get_benches('0');
        get_state();
    });
    
    function get_state(str,ct_code,l_state,l_dist,lower_court_id)
    {
        var ddl_court=$('#ddl_court').val(); 
        var t_h_cno = $('#t_h_cno').val();
        var t_h_cyt = $('#t_h_cyt').val();
        var diary_number = $('#diary_number').val();
        var diary_year = $('#diary_year').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
        url: "<?php echo base_url('scanning/getStateName'); ?>",
        type: 'POST',
        cache: false,
        async: true,
        data: {

            ddl_court: ddl_court,
            diary_number: diary_number,
            diary_year: diary_year,

            CSRF_TOKEN: CSRF_TOKEN_VALUE
        },
        success: function(data, status) {
            updateCSRFToken();
            $('#ddl_st_agncy').html(data);
            
            if (str == 1) {
                $('#ddl_st_agncy').val(l_state);
                get_benches('1', ct_code, l_state, l_dist, lower_court_id);
            }
        },
        error: function(xhr) {
            updateCSRFToken();
            alert("Error: " + xhr.status + " " + xhr.statusText);
        }
    });

    }








    function getSubDoc(val, upd, sp_idd)
    {
        if (val == 8 || val == 9 || val == 16 || val == 5 || val == 3 || val == 98) {
            $.ajax({
                url: "/controllerName/getSubDoc", // Update with your actual controller and method
                url: "<?php echo base_url('scanning/getSubDocuments'); ?>",

                type: "GET",
                data: { doccode: val },
                success: function(response) {
                    updateCSRFToken();
                    $('#s_doc').html(response); // Update the content of the s_doc element

                    if (upd == 'u') {
                        var hd_doccodes = $('#hd_doccodes' + sp_idd[1]).val();
                        $('#s_doc').val(hd_doccodes);
                    }
                },
                error: function(xhr, status, error) {
                    updateCSRFToken();
                    console.error("Error: " + error);
                }
            });
        } else {
            $('#s_doc').html("<option value='0'>Select</option>");
        }
    }


    function chk_other()
    {
        if (document.getElementById('m_doc').value == 10)
        {
            document.getElementById('other_desc').disabled = false;
            document.getElementById('other_desc').focus();
        }
        else
        {
            if (document.getElementById('m_doc').value == 8 && document.getElementById('s_doc').value == 19)
            {
                document.getElementById('other_desc').disabled = false;
    //            /document.getElementById('other_desc').focus();
            }
            else if (document.getElementById('m_doc').value == 9 && document.getElementById('s_doc').value == 10)
            {
                document.getElementById('other_desc').disabled = false;
                //document.getElementById('other_desc').focus();
            }
            else
            {
                document.getElementById('other_desc').value = '';
                document.getElementById('other_desc').disabled = true;
            }
        }
    }

    function saveIT(handle)
    {
        var itype = document.getElementById('i_type').value;
        if (document.getElementById('m_doc').value == 0)
        {
            alert('Please Choose Document Type');
            document.getElementById('m_doc').focus();
            return false;
        }
    
        if (document.getElementById('m_doc').value == 8 || document.getElementById('m_doc').value == 9)
        {
            if (document.getElementById('s_doc').value == 0)
            {
                alert('Please Choose Document Type');
                document.getElementById('s_doc').focus();
                return false;
            }
        }

        var other_desc = '';
        if (document.getElementById('other_desc').disabled == false)
        {
            if (document.getElementById('other_desc').value == '')
            {
                alert('Please Fill Document Type');
                document.getElementById('other_desc').focus();
                return false;
            }
            else
                other_desc = document.getElementById('other_desc').value;
        }
        

        if (document.getElementById('fpage').value == '')
        {
            alert('From Page Cannot be Blank');
            document.getElementById('fpage').focus();
            return false;
        }
        if (document.getElementById('tpage').value == '')
        {
            alert('To Page Cannot be Blank');
            document.getElementById('tpage').focus();
            return false;
        }
        if (document.getElementById('npage').value == '')
        {
            alert('no. of Pages Cannot be Blank');
            document.getElementById('npage').focus();
            return false;
        }
        
        var isValid = /\.pdf$/i.test(document.getElementById('upd_file').value);
        var upd_file= document.getElementById('upd_file').value
        if (!isValid && $('#upd_file').val() != '')
        {
            alert('Only pdf files allowed');
            document.getElementById('upd_file').focus();
            return false;
        }
    
    
    

        else
        {
        
            //alert("My Velocis 2222");
            var m_doc_nm=$('#m_doc option:selected').html();
            var s_doc_nm=$('#s_doc option:selected').html();
            var hd_docd_ids=$('#hd_docd_ids').val();

            var diary_number = $('#diary_number').val();
            var diary_year = $('#diary_year').val(); 
            //        alert(m_doc_nm);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var data = new FormData();
            data.append('file', document.getElementById('upd_file').files[0]);
            data.append('handle', handle);
            data.append('fil_no', document.getElementById('diary_number').value);
            data.append('doccode', document.getElementById('m_doc').value);
            data.append('doccode1', document.getElementById('s_doc').value);
            data.append('other_desc', other_desc);
            data.append('fp', document.getElementById('fpage').value);
            data.append('tp', document.getElementById('tpage').value);
            data.append('np', document.getElementById('npage').value);
            data.append('itype', itype);
            data.append('m_doc_nm',m_doc_nm);
            data.append('s_doc_nm',s_doc_nm);
            data.append('upd_file',upd_file);
            data.append('ddl_case_no',$('#ddl_case_no').val());
            data.append('hd_docd_ids',hd_docd_ids);
            // alert (data);
            $.ajax({
                url: "<?php echo base_url('scanning/saveIndexingData'); ?>",
                cache: false,
                async: true,
                processData: false,
                contentType: false,
                data: data,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                enctype: 'multipart/form-data',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN_VALUE
                },
                success: function(data, status) { 
                    updateCSRFToken();
                    $('#dv_result').html(data);
                    if($('#hd_sq_fkx').val()==0)
                    {
                        alert("Error in saving record");
                    }
                    else 
                    {
                        alert("Data Save Successfully");
                        if (document.getElementById('hd_ck_index'))
                        {
                        if (document.getElementById('hd_ck_index').value == '')
                        {
                            //  alert("1");
                            document.getElementById('result').innerHTML = data;
                            call_getDetails(itype);
                        }
                        else if (document.getElementById('hd_ck_index').value != '')
                        {
                            // alert("2");
                            document.getElementById('dv_b_res').innerHTML = data;

                            // if(document.getElementById('hd_ck_index').value!='')
                            // {
                            var hd_sq_fkx = document.getElementById('hd_sq_fkx').value;
                            // alert(hd_sq_fkx);
                            if (hd_sq_fkx == 1)
                            {
                                var hd_ck_index = document.getElementById('hd_ck_index').value;

                                var hd_a1_a2 = document.getElementById('hd_a1_a2').value;
                                var hd_frm_pg = document.getElementById('hd_frm_pg').value;

                                document.getElementById(hd_ck_index).value = 'Inserted';
                                document.getElementById('hd_ck_index').value = '';
                                document.getElementById('hd_a1_a2').value = '';
                                document.getElementById('hd_frm_pg').value = '';
                                var hd_efil_fil = document.getElementById('hd_efil_fil').value;
                                update_efil(hd_efil_fil, hd_a1_a2, hd_frm_pg, itype)
                            //  }
                            }
                        else if (hd_sq_fkx == 0)
                        {
                            alert("From Page or To Page already inserted");
                        }
                    }
                }
                else if (!document.getElementById('hd_ck_index'))
                {
                    document.getElementById('result').innerHTML = data;
    //                alert(xmlhttp.responseText);
                    var source = document.getElementById('result').innerHTML;
                    var found = source.search('Taken');
                    if (found < 0)
                        call_getDetails(itype);
                }     
                    }
                },
                error: function(xhr) {
                    updateCSRFToken();
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
        if ((charCode >= 48 && charCode <= 57) || charCode == 9 || charCode == 8) {
            return true;
        }
        return false;
    }



    function print_it(value, sno)
{
    sno--;
    var prtContent = '';
    WinPrint = window.open('', '', 'letf=100,top=0,width=900,height=1200,toolbar=1,scrollbars=1,status=1,menubar=1');
    WinPrint.document.write('<style type="text/css">');
    WinPrint.document.write('td,th{border:none} .nodis{display:none} .with_border{border:1px solid black;font-size:14px;} tr.with_border td,tr.with_border th{border:1px solid black}');
    WinPrint.document.write('');
    WinPrint.document.write('#index_text, #index_text2 {text-decoration: underline;}');
    WinPrint.document.write('</style>');
    WinPrint.document.write('<link rel="stylesheet" href="../css/menu_css.css">');
    if (value == 1)
    {
        prtContent = document.getElementById('for_print');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.getElementById('last_row').style.display = "none";
        WinPrint.document.getElementById('r1').style.display = "table-row";
        WinPrint.document.getElementById('r2').style.display = "table-row";
        WinPrint.document.getElementById('r3').style.display = "table-row";
        WinPrint.document.getElementById('r4').style.display = "table-row";
        WinPrint.document.getElementById('r5').style.display = "table-row";
        WinPrint.document.getElementById('index_text').innerHTML = "INDEX";
        if (sno < 15)
        {
            for (var i = sno; i < 12; i++)
            {
                var tr = document.createElement("tr");
                tr.setAttribute('class', 'with_border');
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var table = WinPrint.document.getElementById('prt_tb');
                var body = table.getElementsByTagName("tbody")[0];
                var b_row = table.getElementsByTagName("tr")[sno + 7];
                body.insertBefore(tr, b_row);
            }
        }
    }
    else if (value == 2)
    {
        prtContent = document.getElementById('for_print2');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.getElementById('last_row2').style.display = "none";
        WinPrint.document.getElementById('r12').style.display = "table-row";
        WinPrint.document.getElementById('r22').style.display = "table-row";
        WinPrint.document.getElementById('r32').style.display = "table-row";
        WinPrint.document.getElementById('r42').style.display = "table-row";
        WinPrint.document.getElementById('r52').style.display = "table-row";
        WinPrint.document.getElementById('index_text2').innerHTML = "INDEX";
        if (sno < 15)
        {
            for (var i = sno; i < 12; i++)
            {
                var tr = document.createElement("tr");
                tr.setAttribute('class', 'with_border');
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = "&nbsp;";
                tr.appendChild(td);
                var table = WinPrint.document.getElementById('prt_tb2');
                var body = table.getElementsByTagName("tbody")[0];
                var b_row = table.getElementsByTagName("tr")[sno + 7];
                body.insertBefore(tr, b_row);
            }
        }
    }
//    WinPrint.document.close();
//    WinPrint.focus();
    WinPrint.print();
//    WinPrint.close();
    prtContent = prtContent.innerHTML.replace("overflow: scroll;", "");
    prtContent = prtContent.replace("width: 500px;", "width: 600px;");
}


    $(document).on('change', '#ddl_st_agncy', function() {
       get_benches('0');

    });
     $(document).on('change', '#ddl_bench', function() {
       get_tot_cases();

    });


    function get_benches(str, ct_code, l_state, l_dist, lower_court_id)
    {
        var ddl_st_agncy = $('#ddl_st_agncy').val();
        var ddl_court = $('#ddl_court').val();
        var t_h_cno = $('#t_h_cno').val();
        var t_h_cyt = $('#t_h_cyt').val();
        var diary_number = $('#diary_number').val();
        var diary_year = $('#diary_year').val(); 
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();   
        

    if (ddl_st_agncy != '' && ddl_court != '') {
        $.ajax({
            url: "<?php echo base_url('scanning/fetchAgencies'); ?>",

            cache: false,
            async: true,
            data: {
                ddl_st_agncy: ddl_st_agncy,
                ddl_court: ddl_court,
                diary_number: diary_number,
                diary_year: diary_year,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            type: 'POST',
            success: function(data, status) {
                updateCSRFToken();
                $('#ddl_bench').html(data);
                if (str == 1) {
                    $('#ddl_bench').val(l_dist);
                    get_tot_cases('1', ct_code, l_state, l_dist, lower_court_id);
                }
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    } else {
        $('#ddl_bench').html("<option value=''>Select</option>");
        $('#ddl_case_no').html("<option value=''>Select</option>");
    }
}


function get_tot_cases(str, ct_code, l_state, l_dist, lower_court_id)
{
   
    var ddl_court = $('#ddl_court').val(); 
    var diary_number = $('#diary_number').val();
    var diary_year = $('#diary_year').val();
    var ddl_st_agncy = $('#ddl_st_agncy').val();
    var ddl_bench = $('#ddl_bench').val();
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();  
    // alert("Hi"); 
    // alert(ddl_court);
    // alert(diary_number);
    // alert(diary_year);
    // alert(ddl_st_agncy);
    // alert(ddl_bench);

    $.ajax({
            url: "<?php echo base_url('scanning/getTotalCases'); ?>",

            cache: false,
            async: true,
            data: {
                ddl_court: ddl_court,
                diary_number: diary_number,
                diary_year: diary_year,
                ddl_st_agncy: ddl_st_agncy,            
                ddl_bench: ddl_bench,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            type: 'POST',
            success: function(data, status) {
            updateCSRFToken();
            $('#ddl_case_no').html(data);
            if (str == 1) {
                $('#ddl_case_no').val(lower_court_id);
            }
            },
            error: function(xhr) {
                updateCSRFToken();
                alert("Error: " + xhr.status + " " + xhr.statusText);
            }
        });
   
        
    

   
}



   
</script>