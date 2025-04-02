<?=view('header'); ?>
    <style>
        .circle-tile {
            margin-bottom: 15px;
            text-align: center;
        }
        .circle-tile-heading {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 100%;
            color: #FFFFFF;
            height: 80px;
            margin: 0 auto -40px;
            position: relative;
            transition: all 0.3s ease-in-out 0s;
            width: 80px;
        }
        .circle-tile-heading .fa {
            line-height: 80px;
        }
        .circle-tile-content {
            /*padding-top: 50px;*/

            padding-top: 5px;
        }
        .circle-tile-number {
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
            padding: 5px 0 15px;
        }
        .circle-tile-description {
            text-transform: uppercase;
        }
        .circle-tile-footer {
            background-color: rgba(0, 0, 0, 0.1);
            color: rgba(255, 255, 255, 0.5);
            display: block;
            padding: 5px;
            transition: all 0.3s ease-in-out 0s;
        }
        .circle-tile-footer:hover {
            background-color: rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }
        .circle-tile-heading.dark-blue:hover {
            background-color: #2E4154;
        }
        .circle-tile-heading.green:hover {
            background-color: #138F77;
        }
        .circle-tile-heading.orange:hover {
            background-color: #DA8C10;
        }
        .circle-tile-heading.blue:hover {
            background-color: #2473A6;
        }
        .circle-tile-heading.red:hover {
            background-color: #CF4435;
        }
        .circle-tile-heading.purple:hover {
            background-color: #7F3D9B;
        }
        .tile-img {
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.9);
        }

        .dark-blue {
            background-color: #34495E;
            height: 102px;
            /* width: 169px; */
        }
        .green {
            background-color: #16A085;
        }
        .blue {
            background-color: #2980B9;
        }
        .orange {
            background-color: #F39C12;
        }
        .red {
            background-color: #E74C3C;
        }
        .purple {
            background-color: #8E44AD;
        }
        .dark-gray {
            background-color: #7F8C8D;
        }
        .gray {
            background-color: #95A5A6;
        }
        .light-gray {
            background-color: #BDC3C7;
        }
        .yellow {
            background-color: #F1C40F;
        }
        .text-dark-blue {
            color: #34495E;
        }
        .text-green {
            color: #16A085;
        }
        .text-blue {
            color: #2980B9;
        }
        .text-orange {
            color: #F39C12;
        }
        .text-red {
            color: #E74C3C;
        }
        .text-purple {
            color: #8E44AD;
        }
        .text-faded {
            color: rgba(255, 255, 255, 0.7);
        }
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 47px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
        .float-right {
            float: right!important;
        }

    </style>
        <section class="content">
        <div class="container-fluid">
        <div class="row" >
        <div class="col-12" >
        <div class="card" >
        <div class="card-header bg-primary text-white font-weight-bolder text-left"><h2>Document Dashboard </h2></div>
        <div class="card-body" >
            
            <div class="col-md-12">
                <div class="well">
                    <div class="row">
                        <?php
                        $total = !empty($stepWisetotal->total) ? $stepWisetotal->total : 0;
                        $pending_digital_sign = !empty($stepWisetotal->pending_digital_sign) ? $stepWisetotal->pending_digital_sign : 0;
                        $pending_institutional_sign = !empty($stepWisetotal->pending_institutional_sign) ? $stepWisetotal->pending_institutional_sign : 0;
                        $completed = !empty($stepWisetotal->completed) ? $stepWisetotal->completed : 0;
                        ?>
                        <?= csrf_field() ?>
                        <div class="col-sm-3">
                            <a href="javaScript:void(0);" class="actionType" data-title="ALL" data-type="total">
                                <div class="circle-tile ">
                                    <div class="circle-tile-content dark-blue">
                                        <div class="circle-tile-description text-faded"> ALL</div>
                                        <div class="circle-tile-number text-faded ">(<?php echo $total;?>)</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="javaScript:void(0);" class="actionType"  data-title="Pending For Digital Signature" data-type="pending_digital_sign">
                            <div class="circle-tile ">
                                <div class="circle-tile-content dark-blue">
                                    <div class="circle-tile-description text-faded">Pending For Digital Signature</div>
                                    <div class="circle-tile-number text-faded ">(<?php echo $pending_digital_sign;?>)</div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="javaScript:void(0);" class="actionType" data-title="Pending For Institutional Signature" data-type="pending_institutional_sign">
                            <div class="circle-tile ">
                                <div class="circle-tile-content dark-blue">
                                    <div class="circle-tile-description text-faded">Pending For Institutional Signature</div>
                                    <div class="circle-tile-number text-faded ">(<?php echo $pending_institutional_sign;?>)</div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="javaScript:void(0);" class="actionType" data-title="Completed" data-type="completed">
                            <div class="circle-tile ">
                                <div class="circle-tile-content dark-blue">
                                    <div class="circle-tile-description text-faded"> Completed</div>
                                    <div class="circle-tile-number text-faded ">(<?php echo $completed;?>)</div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="loader_image"></div>
            <div class="col-md-12" id="documentTableDiv" style="display: none;">
                <div class="well">
                    <div class="card-header text-white font-weight-bolder text-left"><h2 id="selectedBox"></h2></div>
                    <table id="documentTable" class="table table-striped table-hover display">
                        <thead>
                        <tr>
                            <th width="1%">Sno.</th>
                            <th>Reg.No.</th>
                            <th>Current Stage</th>
                            <th>Initiated On</th>
                            <th>User Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <div class="col-md-12 pt-3" id="timeLineDiv" style="display: none;">
                <div class="well">
                    <div class="row text-left" style="margin-bottom: -45px;margin-left: 0px;">
                        <input name="print" type="button" id="print" value="Print"></div>
                    <div class="container">
                        <div class="row" id="printArea">
                            <div class="card-header text-white font-weight-bolder text-left"><h2 id="transactionHeading">Transactions </h2></div>
                            <div class="col-md-6">
                                <ul class="timeline" id="timeLineUl">
                                </ul>
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
<script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/dataTables.bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?=base_url()?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/js/app.min.js"></script>
<script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.colVis.min.js"></script>

<script>
    $(document).on("click","#print",function(){
        var prtContent = $("#printArea").html();
        var temp_str=prtContent;
        var WinPrint = window.open('','','left=100,top=0,align=center,width=800,height=1200,menubar=1,toolbar=1,scrollbars=1,status=1');
        WinPrint.document.write(temp_str);
        WinPrint.document.title = $("#transactionHeading").text();
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    });
    var image_url = "<?php echo base_url('assets/images/load.gif');?>";
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $(document).on('click','.actionType',function(){
        var type = $(this).attr('data-type');
        $(".actionType").removeClass('activeBox');
        $(this).addClass('activeBox');
        $("#selectedBox").text($(".activeBox").attr("data-title"));
        $('#documentTable').dataTable().fnClearTable();
        $('#documentTable').dataTable().fnDraw();
        $("#documentTable").DataTable().clear();
        $("#timeLineDiv").hide();
        if(type){
            $.ajax({
                url: '<?=base_url();?>/Faster/DocumentDashboard/getStatusTypeData',
                cache: false,
                async: true,
                type: 'POST',
                dataType:'json',
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, type:type},
                beforeSend: function () {
                    $('#loader_image').html('<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>');
                },
                success: function (res){
                    updateCSRFToken();
                    $("#documentTableDiv").show();
                    $('#loader_image').html('');
                    var length = res.length;
                    var result='';
                    var id='';
                    if(length>0) {
                        var t = $('#documentTable').dataTable();
                        for (var i = 0; i < length; i++) {
                            result =res[i];
                            var rowIndex = t.fnAddData([
                                i + 1,
                                '<a href="javaScript:void(0);" title="'+result.reg_no+'">'+result.reg_no+'</a>',
                                result.current_stage,
                                result.created_on,
                                result.name+' ('+result.section_details+' )'
                            ]);
                            id= result.id;
                            var row = t.fnGetNodes(rowIndex);
                            $(row).find('td:eq(1)').attr('class', 'timeLineClass');
                            $(row).find('td:eq(1)').attr('data-id', id);
                            $(row).find('td:eq(1)').attr('data-type', type);
                        }
                    }
                },
                error: function (xhr) {
                    updateCSRFToken();
                    console.log("Error: " + xhr.status + " " + xhr.statusText);
                }
            });

        }
    });
    $(document).on('click','.timeLineClass',function () {
       var reg_no = $(this).text();
       if(reg_no){
           $("#transactionHeading").text("Transactions"+' ( '+reg_no+' )');
       }
       var rowId = $(this).attr('data-id');
       var type = $(this).attr('data-type');
       if(rowId && type){
           $.ajax({
               url: '<?=base_url();?>/Faster/DocumentDashboard/getDocumentTimelineData',
               cache: false,
               async: true,
               type: 'GET',
               dataType:'html',
               data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, rowId:rowId,type:type},
               beforeSend: function () {
                   $('#loader_image').html('<table widht="100%" align="center"><tr><td><img src="' + base_url + '/images/load.gif"/></td></tr></table>');
               },
               success: function (res) {
                    updateCSRFToken();
                   $('#loader_image').html('');
                   $("#timeLineDiv").show();
                   $("#timeLineUl").html('');
                   $("#timeLineUl").html(res);
                   $("html,body").animate({"scrollTop": $("#timeLineDiv").offset().top},1000);
               },
               error: function (xhr) {
                    updateCSRFToken();
                   console.log("Error: " + xhr.status + " " + xhr.statusText);
               }
           });
       }

    });
    
    $(document).ready(function() {
        var title = function () { return 'Document  ('+$('.activeBox').attr('data-title')+')' };
        $('#documentTable').DataTable( {
            dom: 'Bfrtip',
            deferRender: true,
            bAutoWidth: false,
            buttons: [
                {
                    extend: 'csv',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4],
                        stripHtml: true
                    }
                },
                {
                    extend: 'excel',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4],
                        stripHtml: true
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4],
                        stripHtml: true
                    }
                },
                {
                    extend: 'print',
                    title: title,
                    exportOptions: {
                        columns: [0,1,2,3,4],
                        stripHtml: true
                    }
                },
                {
                    extend: 'colvis',
                    columns: ':gt(0)',
                    columns: ':gt(1)'
                }
            ]
        });
    });

</script>
