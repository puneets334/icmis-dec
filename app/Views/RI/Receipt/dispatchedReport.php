<?php
    if(isset($dispatchedDetails) && sizeof($dispatchedDetails)>0 ) {
        ?>
        <div class="form-group col-sm-6 pull-right">
            <label>&nbsp;</label>
            <button type="button" id="btnDispatchTop" name="btnDispatch" class="btn btn-success btn-block pull-right" onclick="doDispatch();" ><i class="fa fa-fw fa-download"></i>&nbsp;Dispatch Dak</button>
        </div>
        <!--<table id="reportTable1" class="table table-striped table-hover">-->
        <div class="table-responsive">
        <table id="tblDispatchReport" class="table table-striped custom-table">
            <thead>
            <tr>
                <th width="4%">#</th>
                <th width="8%">Diary Number</th>
                <th width="10%">Sent To</th>
                <th width="15%">Postal Type, Number & Date</th>
                <th width="20%">Sender Name & Address</th>
                <th width="8%">Case Number</th>
                <th width="12%">Dispatched By/ Dispatched on</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $s_no=1;
            foreach ($dispatchedDetails as $case)
            {
                ?>
                <tr>
                    <td><?=$s_no?></td>
                    <td><?=$case['diary']?></td>
                    <td>
                        <?=$case['address_to']?>
                    </td>
                    <td><?php
                        echo $case['postal_type'].'&nbsp;'.$case['postal_number'].'&nbsp;'.date("d-m-Y", strtotime($case['postal_date']));
                        ?>
                    </td>
                    <td><?php
                        echo $case['sender_name'].'&nbsp;'.$case['address'];
                        ?>
                    </td>
                    <?php
                    $diarynumber="";
                    if(!empty($case['diary_number'])){
                        $diarynumber=$case['diary_number'];
                        $diarynumber="Diary No. ".substr($diarynumber, 0, -4)."/".substr($diarynumber, -4)."<br/>".$case['reg_no_display'];;
                    }
                    ?>
                    <td><?=$diarynumber;?></td>
                    <td>
                        <?=$case['dispatched_by']?>&nbsp;On&nbsp;<?=date("d-m-Y h:i:s A", strtotime($case['dispatched_on']))?>
                    </td>
                </tr>
                <?php
                $s_no++;
            }
            ?>
            </tbody>
        </table>
        </div>
        <?php
    }
    else{
        ?>
        <div class="form-group col-sm-12">
            <label class="text-danger">&nbsp;No Record Found!!</label>
        </div>

    <?php }

    ?>
<script>
 $(function() {
        $("#tblDispatchReport").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                },
                {
                    extend: 'colvis',
                    text: 'Show/Hide'
                }
            ],
            "bProcessing": true,
            "extend": 'colvis',
            "text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });
</script>