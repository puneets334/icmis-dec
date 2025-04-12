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
    table.rop_class_custom thead th input {
        min-width: 91px;
    }
    table.rop_class_custom thead th input::placeholder {
        font-size: 10px;
    }
    table#header thead tr th {
    display: none;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header heading">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Reports</h3>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-2" style="background-color: #fff; border-bottom:none;">
                                    <h4 class="basic_heading">Statistics of ROPs Deatils
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane">
                                            
                                            <?php

                                                if($cno==21)
                                                    $cno_display='Registrar Court No. 1';
                                                else if($cno==22)
                                                    $cno_display='Registrar Court No. 2';
                                                else
                                                    $cno_display=$cno;
                                                if(!empty($cno_display)){

                                                    $heading="Matters Listed in Hon'ble Court No.".$cno_display." on ". @date('d-m-Y',strtotime(@$listing_date));
                                                }

                                                ?>
                                                <h2 class="page-header" align="center"><b><u><?= @$heading ?></u></b></h2>
                                                <?php

                                                if (isset($list_details) && sizeof($list_details) > 0) { ?>
                                                    <table id="header">
                                                        <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>search Item No</th>
                                                            <th>search D. No.</th>
                                                            <th>search Case No.</th>
                                                            <th>search Cause Title</th>
                                                            <th>search ROP Status</th>
                                                            <th>search Updation Status</th>
                                                            <!--<th>search Uploaded by</th>-->
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                    <div id="disp">
                                                        <table id="tabdata" class="rop_class_custom table table-striped table-hover table-responsive">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Item Number</th>
                                                                <th>Diary No.</th>
                                                                <th>Case No.</th>
                                                                <th>Cause Title</th>
                                                                <th>ROP Status</th>
                                                                <th>Updation Status</th>
                                                                <!--<th>Uploaded by</th>-->
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $i=0;
                                                            foreach($list_details as $result)
                                                            {$i++;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $i;?></td>
                                                                    <td><?php echo $result['item_number'];?></td>
                                                                    <td><?php echo substr($result['diary_no'],0,strlen($result['diary_no'])-4).'/'.substr($result['diary_no'], -4 );?></td>
                                                                    <td><?php echo $result['registration_number_desc'];?></td>
                                                                    <td><?php echo $result['petitioner_name'].' Vs '.$result['respondent_name'];?></td>
                                                                    <td><?php
                                                                        if ($result['if_uploaded']==0)
                                                                        {?>
                                                                            <font color="#ff0000">NOT uploaded</font>
                                                                        <?php }
                                                                        else
                                                                        {?>
                                                                            <font color="green">Uploaded</font><br>
                                                                            <font color="blue">On&nbsp;&nbsp;</font><?php if($result['uploaded_on']!=null) echo date('d-m-Y h:i:s A',strtotime($result['uploaded_on'])); else echo '';?><br>
                                                                            <font color="blue">By&nbsp;&nbsp;</font><?php if($result['uploaded_by']!=null) echo $result['uploaded_by']; else echo '';?>
                                                                        <?php }?>
                                                                    </td>
                                                                    <td><?php
                                                                        if ($result['if_updated']==0)
                                                                        {?>
                                                                            <font color="#ff0000">NOT Updated</font>
                                                                        <?php }
                                                                        else
                                                                        {?>
                                                                            <font color="green">Updated</font><br>
                                                                            <font color="blue">On&nbsp;&nbsp;</font><?php if($result['updated_on']!=null) echo date('d-m-Y h:i:s A',strtotime($result['updated_on'])); else echo '';?><br>
                                                                            <font color="blue">By&nbsp;&nbsp;</font><?php if($result['updated_by']!=null) echo $result['updated_by']; else echo '';?>
                                                                        <?php }?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <h3 class="text-danger text-center" style="">&nbsp;No Record Found!!</label> 
                                                <?php }
                                                ?>
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

    $(document).ready(function () {
        // $('#tabdata thead tr').clone(true).prependTo('#tabdata thead'); //same heading as of original table
        $('#header thead tr').clone(true).prependTo('#tabdata thead'); //Different heading
        $('#tabdata thead tr:eq(0) th').each(function (i) {
            if (i != 0) {
                var title = $(this).text();
                var width = $(this).width();
                var height = $(this).height() + 10;
                if (width > 200) {
                    width = width - 60;
                }
                else if (width < 50) {
                    width = width + 20;
                }

                $(this).html('<input type="text" style="width: ' + width + 'px;" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function () {
                    if (t.column(i).search() !== this.value) {
                        t
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            }

        });


        var t = $('#tabdata').DataTable({
            "order": [[1, 'asc']],
            "ordering": false,
            fixedHeader: true,
            scrollX: true,
            autoFill: true,

            "columnDefs": [
                {
                    "targets": [ 4 ],
                    "visible": true
                },
                {
                    "targets": [ 5 ],
                    "visible": true
                }
            ],

            // dom: 'Bfrtip', //F->filter(Universal Search)
            dom: 'Brtip',
            "pageLength": 8,
            buttons: [
                {
                    extend: 'excelHtml5'
                },

                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                        stripNewlines: false
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false,

                        customize: function (win) {
                            $(win.document.body).find('h1').css('font-size', '15px');
                            $(win.document.body).find('h1').css('text-align', 'left');
                            $(win.document.body).find('tab').css('width', 'auto');

                            var last = null;
                            var current = null;
                            var bod = [];

                            var css = '@page { size: landscape; }',
                                head = win.document.head || win.document.getElementsByTagName('head')[0],
                                style = win.document.createElement('style');

                            style.type = 'text/css';
                            style.media = 'print';

                            if (style.styleSheet) {
                                style.styleSheet.cssText = css;
                            }
                            else {
                                style.appendChild(win.document.createTextNode(css));
                            }

                            head.appendChild(style);
                        }
                    }
                },
                'pageLength',
                {
                    extend: 'colvis',
                    columns: ':gt(3)'
                },
            ],

            lengthMenu: [
                [8, 10, 25, 50, -1],
                ['8 rows', '10 rows', '25 rows', '50 rows', 'Show all']
            ]
        });

        t.on('order.dt search.dt', function () {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
                t.cell(cell).invalidate('dom');
            });
        }).draw();
    });
</script>