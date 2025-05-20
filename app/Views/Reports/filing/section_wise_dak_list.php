<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php
            // pr($section_wise_dak_data['section_wise_dak_data']);
            if(!empty($section_wise_dak_data['section_wise_dak_data'])):?>
                <table  id="ReportFileTrap" class="table table-bordered table-striped">
                    <thead><tr>
                        <th  >SNo.</th>
                        <th  >Date</th>
                        <th  >Total Document Received</th>
                    </tr></thead><tbody>
                    <?php $sno = 1; foreach($section_wise_dak_data['section_wise_dak_data'] as $row):?>
                        <tr>
                            <td ><?php echo $sno;?></td>
                            <td><?php echo  $row->date1; ?></td>

                            <td>
                                <?php
                                $url= base_url().'/Reports/Filing/Report/getSectionWiseDakDetails/'.$row->date1;
                                if(!empty($section_wise_dak_data['param'][2]))
                                {
                                    $url.='/'.$section_wise_dak_data['param'][2];
                                }
                                if(!empty($section_wise_dak_data['param'][3]))
                                {
                                    $url.='/'.$section_wise_dak_data['param'][3];
                                }
                                //echo $url;
                                ?>

                                <a target="_BLANK" href="<?=$url;?>"><?=$row->total;?></a>
                               <!-- <a class="detailed_report" target="_self" href="#" data-from_date=<?/*=$section_wise_dak_data['param'][0];*/?> data-to_date=<?/*=$section_wise_dak_data['param'][1];*/?> data-section=<?/*=$section_wise_dak_data['param'][2];*/?> data-is_exclude_flag=<?/*=$section_wise_dak_data['param'][3];*/?>> <?/*=$row->total;*/?></a>-->
                            </td>

                        </tr>
                    <?php $sno++; endforeach; ?>
                    </tbody>
                </table>
            <?php else: { echo "Record Not Found"; } endif; ?>
            <!-- end of fileTrap -->
        </div>
        <script>
            $(function () {
                $("#ReportFileTrap").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' }
                        ],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

                });
            $(document).ready(function(){
                // jQuery function to handle click event on anchor tags with class "specific-class"

            });
        </script>
