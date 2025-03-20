<?php if (isset($_POST['fromDate']) && isset($_POST['toDate'])): ?>
                            <?php if (isset($dataToDispatchWithProcessId) && sizeof($dataToDispatchWithProcessId) > 0): ?>
                                <div id="printable">
                                    <?php // Uncomment the line below to debug data
                                    // var_dump($dataToDispatchWithProcessId); ?>
                                </div>
                                <div class="form-group col-sm-3 pull-right">
                                    <label></label>
                                    <button 
                                        type="button" 
                                        id="btnDispatchTop" 
                                        name="btnDispatch" 
                                        class="btn btn-primary"
                                        onclick="return doDispatch();">
                                        <i class="fa fa-fw fa-paper-plane"></i>&nbsp;Dispatch Selected Dak
                                    </button>
                                </div>
                                <!--<table id="reportTable1" class="table table-striped table-hover">-->
                                <table 
                                    id="tblDispatchDak" 
                                    style="width: 95%" 
                                    class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="4%">#</th>
                                            <th width="38%">Letter Detail</th>
                                            <th width="20%">Letter Type</th>
                                            <th width="15%">Dispatch Mode</th>
                                            <th width="10%">
                                                <!-- <label>
                                                    <input 
                                                        type="checkbox" 
                                                        id="allCheck" 
                                                        name="allCheck" 
                                                        > 
                                                    Select All
                                                </label>-->
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s_no = 1;
                                        foreach ($dataToDispatchWithProcessId as $case): ?>
                                            <tr>
                                                <td><?= $s_no ?></td>
                                                 <td>
                                                    Process Id: <?= $case['process_id'] ?>/<?= $case['process_id_year'] ?><br/>
                                                    <?= $case['case_no'] ?><br/>
                                                    <?= $case['name'] ?><br/>
                                                    <?= $case['address'] ?>, <?= $case['district_name'] ?>, <?= $case['state_name'] ?>, <?= $case['pin_code'] ?>
                                                </td>
                                                <td><?= $case['doc_type'] ?></td> 
                                                <td>
                                                <select 
                                                    class="form-control" 
                                                    id="dispatchMode_<?php if(isset($case['tw_tal_del_id'])) echo $case['tw_tal_del_id']; ?>">
                                                    <option value="0"> Select Mode</option>
                                                    <?php foreach ($dispatchModes as $mode): ?>
                                                        <option 
                                                            value="<?= $mode['id'] ?>" 
                                                            <?= ($case['ref_postal_type_id'] == $mode['id']) ? 'selected="selected"' : '' ?>>
                                                            <?= $mode['postal_type_description'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="checkbox" 
                                                        id="daks" 
                                                        name="daks[]" 
                                                        value="<?= $case['tw_tal_del_id'] ?>">
                                                </td>
                                            </tr>
                                            <?php $s_no++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class='col-sm-12'>
                                    <h4 class='text-danger'>Nothing to dispatch!!</h4>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>