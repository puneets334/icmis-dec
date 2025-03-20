<?= view('header') ?>

<style>
    .login-box {
        margin: auto;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">FDR >> SEARCH</h3>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-12">
                    <table class="table table-striped table-hover " id="Report">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Section</th>
                            <th>FDR/BG No.</th>
                            <th>A/C No.</th>
                            <th>Amount</th>
                            <th>Bank</th>
                            <th>Deposit Date</th>
                            <th>Maturity/Expiry Date</th>
                            <th>Payment Status</th>
                            <th>Rate of Interest</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        setlocale(LC_MONETARY, 'en_IN');
                        $sNo = 1;
                        foreach($searchResult as $row){
                            switch($row['type']){
                                case 1: $type="Fixed Deposit";break;
                                case 2: $type="Bank Guarantee";break;
                            }
                            echo "<tr ng-click='readOne(".$row['id'].")'>
                                <td>$sNo</td>
                                <td>".$type."</td>
                                <td>".$row['section_name']."</td>
                                <td>".$row['document_number']."</td>
                                <td>".$row['account_number']."</td>
                                <td>".money_format('%!i', $row['amount'])."</td>
                                <td>".$row['bank_name']."</td>
                                <td>".date('d-m-Y', strtotime($row['deposit_date']))."</td>
                                <td>".date('d-m-Y', strtotime($row['maturity_date']))."</td>
                                <td>".$row['status']."</td>
                                <td>".$row['roi']."</td>
                                <td>".$row['remarks']."</td>
                              </tr>";
                            $sNo++;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right">Page Total:</th>
                            <th colspan="5" style="text-align:left">Grand Total:</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function selectedCase(caseId) {
        window.location='<?=base_url()?>Fdr/continueFdr/'+caseId;
    }
</script>