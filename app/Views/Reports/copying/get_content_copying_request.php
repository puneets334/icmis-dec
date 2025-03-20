<div class="card">
    <div class="card-body" >
        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <?php  if(!empty($copyingRequest)):?>
                <table  id="CopyingRequest" class="table table-bordered table-striped">
                    <thead>
                    <h3 style="text-align: center;">Copying Request</h3>

                    <tr>
                        <th>SNo.</th>
                        <th>Application Number</th>
                        <th>Case No</th>
                        <th>Documents</th>
                        <th>Remarks</th>
                        <th>Registered On</th>
                        <th>Pendency<br>(in days)</th>
                        <th>Section</th>
                        <th>D.A.</th>
                        <th>Case Status</th>
                        <th>Disposal Date<br>Consignment Date</th>
                        <th>Last Updated By</th>

                    </tr>
                    </thead><tbody>
                    <?php
                    $sno = 1;
                    foreach($copyingRequest as $row):
                        if($row->c_status == 'P'){
                            $c_status = 'Pending';
                            $color = 'green';
                        }else{
                             $c_status = 'Disposed';
                             $color = 'red';
                        }
                        if(empty($row->updatedbysection)){
                            $updatedbySection = "";
                        }else{
                            $updatedbySection = $row->updatedby."(".$row->updatedbysection.")";
                        }
                        ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= $row->application_number_display ?></td>
                            <td><?= $row->diary_no_display ?> / <?= $row->reg_no_display ?></td>
                            <td><?= $row->docs ?></td>
                            <td><?= $row->remarks ?></td>
                            <td><?=isset($row->application_receipt) ? date('d-m-Y',strtotime($row->application_receipt)) : ''?></td>
                            <td><?= $row->diff ?></td>
                            <td><?= $row->sec ?></td>
                            <?php if($row->c_status=='P' && $row->section_name ){ ?>
                            <td> <?= $row->da ?> / <?= $row->section_name ?></td>
                            <?php }else { ?>
                             <td> <?= $row->da ?> [Record Room]</td>
                            <?php } ?>
                            <?php /* if(isset($row->section_name) && (isset($row->tentative_da) || isset($row->sec)) ){ ?>
                                <td> <?= $row->tentative_da ?> <br><?= $row->sec ?>[T]</td>
                            <?php } */ ?>
                            <td><?="<span style='color:$color !Important;'>$c_status</span>"?></td>
                            <td><?=isset($row->disposal_dt) ? date('d-m-Y',strtotime($row->disposal_dt)) : ''?><br><?=isset($row->consignment_date) ? date('d-m-Y',strtotime($row->consignment_date)) : ''?></td>
                            <td><?=$updatedbySection?></td>

                           </tr>

<!--                        <td>{{$index +1}}</td>-->
<!--                        <td>{{x.application_number_display}}</td>-->
<!--                        <td ng-if="x.diary_no_display!='/'">{{x.diary_no_display}}<br>{{x.reg_no_display}}</td>-->
<!---->
<!--                        <td ng-if="x.diary_no_display=='/'"></td>-->
<!--                        <td>{{x.docs}} </td>-->
<!--                        <td>{{x.remarks}} </td>-->
<!--                        <td>{{ x.application_receipt | jsDate | date: 'dd-MM-yyyy hh:mm' }}</td>-->
<!--                        <td>{{x.diff}}</td>-->
<!--                        <td ng-if="x.c_status=='P' && x.section_name">{{x.da}}<br>[{{x.section_name}}]</td>-->
<!--                        <td ng-if="x.c_status!='P' && x.section_name" >{{x.da}}<br>[Record Room]</td>-->
<!---->
<!--                        <td ng-if="!x.section_name && (x.tentative_da || x.sec) ">{{x.tentative_da}}<br>{{x.sec}}[T]</td>-->
<!--                        <td ng-if="!x.sec && !x.section_name"></td>-->
<!--                        <td ng-if="x.c_status=='P'" style="color:green !Important;">Pending</td>-->
<!--                        <td ng-if="x.c_status!='P'" style="color:red !Important;">Disposed</td>-->
<!--                        <td ng-if="x.disposal_dt || x.consignment_date">{{x.disposal_dt | jsDate | date: 'dd-MM-yyyy' }}<br>{{x.consignment_date | jsDate | date: 'dd-MM-yyyy' }}</td>-->
<!--                        <td ng-if="!x.disposal_dt && !x.consignment_date"></td>-->
<!--                        <td ng-if="x.updatedby">{{x.updatedby}}<br>({{x.updatedbysection}}) </td>-->
<!--                        <td ng-if="!x.updatedby"></td>-->

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </tfoot>
                </table>
            <?php else : ?>
                <div class="text-center align-items-center"><i class="fas fa-info"> </i> No Record Found</div>
            <?php endif; ?>
            <!-- end of refiling search -->

        </div>
        <script>
            $(function () {
                $("#CopyingRequest").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL',title: 'Copying Request'},{extend: 'print', title: 'Copying Request' },
                        { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
                }).buttons().container().appendTo('#query_builder_wrapper .col-md-6:eq(0)');

            });

        </script>
