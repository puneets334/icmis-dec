<div class='col-md-12'>
    <div class="row justify-content-center">
        <?php
        $kyc_data=$this->copyRequestModel->getKycData($_POST['mobile'],$_POST['email']);
        if (!empty($kyc_data)) {


           


                //$kyc_data = mysql_fetch_assoc($rs_kyc);
                ?>
                <div class="card text-center" style="width: 18rem;">
                    <?php
                    echo '<img src="data:image/gif;base64,' . $kyc_data['pht'] . '" class="card-img-top" alt="Aadhaar" width="100%" height="225px" />';
                    ?>
                    <div class="card-body">
                        
                        <h6 class="card-title"><b>Aadhaar No. (Last 4 digit)</b><br><?= "**** **** ".substr($kyc_data['reference_id'], 0, 4);?></h6>
                        <h5 class="card-title"><?=$kyc_data['adhar_name'];?></h5>

                        <p class="card-text">Dob: <?=date("d-m-Y", strtotime($kyc_data['dob']));?></p>
                        <p class="card-text">Gender: <?=$kyc_data['gender'];?></p>
                        <p class="card-text">Mobile: <?=$kyc_data['mobile'];?></p>
                        <p class="card-text">Email: <?=$kyc_data['email'];?></p>
                        <p class="card-text">Address: <?=$kyc_data['careof'].", ".$kyc_data['house'].", ".$kyc_data['landmark'].", ".$kyc_data['loc'].", ".$kyc_data['street'].", ".$kyc_data['vtc'].", ".$kyc_data['subdist'].", ".$kyc_data['district'].", ".$kyc_data['state'].", ".$kyc_data['pc'].", ".$kyc_data['po'].", ".$kyc_data['country'];?></p>
                    </div>
                </div>
                <?php
            }
            else{
                echo "Details not found";
                exit();
            }
        ?>
    </div>
</div>


