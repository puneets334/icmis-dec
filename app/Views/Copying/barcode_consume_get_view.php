
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Application No.</th>
                                    <th>Name</th>
                                    <th>Weight (gms)</th>
                                    <th>Barcode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sno = 0;?>
                                <?php if (!empty($barcode_consume)) {
                                    
                                    foreach ($barcode_consume as $row) {
                                        $total_red_wrappers = 0;
                                        if ($row['copy_category'] == 1 or $row['copy_category'] == 3) {
                                            $total_red_wrappers = $row['total_copy'];
                                        }
                                        $weight = copying_weight_calculator($row['total_pages'], $total_red_wrappers);

                                ?>
                                        <tr class="row_tr">
                                            <td><?= ++$sno; ?></td>
                                            <td><?= $row['application_number_display'] ?></td>
                                            <td><?= $row['name'] . "<br><u>Address</u>:" . $row['address'] ?></td>
                                            <td><?= $weight ?></td>
                                            <td class="input_barcode">
                                                <input name="barcode" id="barcode_id" type="text" class="form-control barcode_id bg-white" autocomplete="off" minlength="12" maxlength="15">
                                            </td>
                                            <td class="cell_tr" id="tr_row_<?= $row['id']; ?>">
                                                <input name="btn_consume" type="button" data-envelope_weight="<?= $weight ?>" data-app_id="<?= $row['id']; ?>" class="btn_consume btn btn-success" value="Consume">
                                            </td>
                                        </tr>
                                <?php } } ?>
                            </tbody>
                        </table>


