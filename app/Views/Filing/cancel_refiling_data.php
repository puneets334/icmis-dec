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

if (!empty($alldefect))
{
    ?>

    <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 ">
        <div >
            <h4>Default Details</h4>
            <br>
            <table id="reportTable1" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:darkgrey;">
                    <th>S.No</th>
                    <th>Defect</th>
                    <th>Remark</th>
                    <th>Mulent</th>


                </tr>
                </thead>
                <tbody>
                <?php
                $s_no = 1;
                foreach ($alldefect as $data) {

                    ?>
                    <tr>
                        <td><?= $s_no; ?></td>
                        <td><?= $data['obj_name'] ?></td>
                        <td><?= $data['remark'] ?></td>
                        <td> <?php
                            $ex_ui = explode(',', $data['mul_ent']);
                            $r = '';
                            for ($index = 0; $index < count($ex_ui); $index++) {
                                // echo 'ererere' .$ex_ui[$index];
                                if (trim($ex_ui[$index] == '')) {


                                    $r = $r . '-' . ',';
                                } else {

                                    $r = $r . $ex_ui[$index] . ',';

                                    // echo $row1['mul_ent'] ;
                                }
                            }

                            echo substr($r, 0, -1);
                            ?>

                        </td>
                    </tr>
                    <?php
                    $s_no++;
//                die;
                }
                ?>

                </tbody>
            </table>
            <?php
            if(!empty($cancel_button))
            {
                if($cancel_button === 'cancel_button_on')
                {
                    ?>
                    <div style="text-align: center">
                        <input type="button" name="btn_backdate" id="btn_cancel_ref" value="Cancel Refiling"/>
                        <input type="button" name="btn_back" id="btn_back" value="Back"/>

                    </div>
                    <br>
                    <?php
                }else{
                    echo "<br>";
                    echo "<div  style='color: red;text-align:center'>You Are Not Authorized User !!!!!</div>";
                    echo "<br>";
                }
            }
            ?>
        </div>
    </div>
    <?php
}
?>
<script>

    $(function () {
        $("#tab").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
                { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

    });

</script>
