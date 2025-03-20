<style>
    html, body {
        height: auto;
    }

    /* td a {
         display:inline-block;
         min-height:100%;
         width:100%;
         color: #0c0c0c;
     }*/
    @media print
    {
        td a {
            display:inline-block;
            min-height:100%;
            width:100%;
            color: #0c0c0c;
        }
        a[href]:after {
            content: none !important;
        }
    }
</style>
<?php

if (!empty($display))
{

    ?>
<div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
    <table  id="datatable_report" class="table table-bordered table-striped datatable_report">
        <thead>
        <tr>
            <th>SNo.</th>
            <th>Diary No.</th>
            <th>Parties</th>
            <th>Dispatch By</th>
            <th>Dispatch On</th>
            <th>Remarks</th>
            <th>Dispatch</th>
            <th>Filing Type</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sno = 1;
        foreach ($display as $row) {
//        echo "<pre>";
//        print_r($row);
//        die;

        ?>

            <tr style="<?php if ($row['remarks'] == 'FDR -> AOR' || $row['remarks'] == 'AOR -> FDR') { ?> background-color: #cccccc <?php } ?>">
            <td><?php echo $sno++; ?></td>
            <td><?php echo substr($row['diary_no'], 0, -4) . '/' . substr($row['diary_no'], -4); ?></td>
            <td><?php echo $row['pet_name'].'<b> V/S </b>'.$row['res_name'] ?></td>
            <td><?php echo $row['d_by_name']; ?></td>
            <td><?php echo date('d-m-Y h:i:s A', strtotime($row['disp_dt'])); ?></td>
            <td><?php echo $row['remarks']; ?></td>
            <td><?php echo $row['filing_type'];?></td>
            <td><input type="button" id="comp<?php echo $row['uid']; ?>"
               value="<?php if(($row['remarks'] == 'AOR -> FDR' || $row['remarks'] == 'FDR -> AOR'))
                    {  echo "Allot To Scruitny User";
                    } elseif($row['remarks'] == 'SCR -> FDR') {
                    echo "Return to AOR";
               } else echo "Dispatch"; ?>"</td>
            <div id="div<?php echo $row['uid']; ?>"></div>
            </td>
            <?php } ?>
            </tr>

      </table>
</div>

        <?php
        }
        ?>

<script>
    $(function () {
        $(".datatable_report").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

    });


    $("[id^='comp']").click(function(){
        // alert("RRR");
        // return false;
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var idd=$(this).attr('id').split('comp');
        // console.log(idd[1]);return false;
        $(this).attr('disabled',true);
        var type='C';

        $.ajax({
            url:'<?=base_url('Filing/File_trap_dispatch_receive/receiveFDR');?>',
            cache: false,
            async: true,
            context: this,
            data:{
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
                id:idd[1],
                value:type

            },
            type: 'POST',
            success: function(data) {
                updateCSRFToken();
                alert(data);
                window.location.reload();
                // return;

            },
            error: function(data) {
                alert("ERROR, Please Contact Server Room");
            }
        });

    });
</script>