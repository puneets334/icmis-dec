<div class="card">
    <div class="card-body" >
    <div id="query_builder_wrapper" class="query_builder_wrapper dataTables_wrapper dt-bootstrap4">

            <?php if(!empty($Aor_list)):?>
                <table id="ReportVec" class="query_builder_report table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th> S.NO </th><th> DIARY NO. </th><th>SECTION</th><th> CASE NO.  </th><th> CAUSE TITLE</th><th>Dealing Assistant</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sno = 1;
                    //Array ( [diary_no] => 224272014 [dacode] => 726 [section] => 20 [section_name] => II [short_description] => SLP(Crl) No. [fil_no] => -005984-005992 [fil_dt] => 2014 [pet_name] => JALADI MOSES AND ORS. ETC. [res_name] => POLURU PRASADA REDDY AND ORS. ETC. )
                    
                    foreach($Aor_list as $row): //print_r($row); exit; 
                    $diary_no = $row['diary_no'];
                    
                    ?>
                    <tr>
                        <td><?php echo $sno;  ?></td><TD> <?PHP echo substr($row["diary_no"],0,-4)."/". substr($row["diary_no"],-4); ?> </TD>
                        <tD> <?php  echo $row["section_name"] ?></td>
                        <TD> <?PHP  $case_no= $row["short_description"] .$row['fil_no']."/". $row["fil_dt"]; if($case_no=='/0') { echo '-';} else echo $case_no;  ?> </TD><TD> <?PHP echo $row["pet_name"] ." v/s ". $row["res_name"] ?> </td>
                    <td>
                       <?= $row['name']?>
                    </td>
                       
                    </tr>
                    
                    <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif ?>

        </div>
        <script>

            
$(function () {
$("#ReportVec").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
}).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

});
</script>
