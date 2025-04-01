<!DOCTYPE html>
<html lang="en">
<head>
    <title>Copy Status</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../plugin/fontawesome-free-5.7.0-web/css/all.css">
    <link rel="stylesheet" href="../css/tooplate.css">
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body class="bg03" oncontextmenu="return false;">
<div class="container">
    <div class="row tm-content-row tm-mt-big mt-2">
        <div class="tm-col tm-col-big">
            <div class="bg-white tm-block">
                <div class="row col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 mb-3" id="result" style="overflow: auto;">
                    <?php if (!empty($result)): ?>
                        <div style="border-radius: 15px;" class="p-2 m-1">
                            <div class="row">
                                <div class="col-md-4">Application No.: <span class="font-weight-bold text-gray"><?= $result['application_number_display'] ?? 'NA'; ?></span></div>
                                <div class="col-md-4">CRN: <span class="font-weight-bold text-gray"><?= $result['crn'] == '0' ? '' : $result['crn']; ?></span></div>
                                <div class="col-md-4">Date: <span class="font-weight-bold text-gray"><?= date("d-m-Y", strtotime($result['application_receipt'])); ?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Source: <span class="font-weight-bold text-gray"><?= $result['description']; ?></span></div>
                                <div class="col-md-4">Applied By: <span class="font-weight-bold text-gray"><?= $result['filed_by'] == 1 ? "AOR" : ($result['filed_by'] == 2 ? "Party" : ($result['filed_by'] == 3 ? "Appearing Counsel" : ($result['filed_by'] == 4 ? "Third Party" : "Authenticated By AOR"))); ?></span></div>
                                <div class="col-md-4">Applicant Name: <span class="font-weight-bold text-gray"><?= $result['name']; ?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Application Status: <span class="font-weight-bold text-gray"><?= $result['status_description']; ?></span></div>
                                <div class="col-md-4">Delivery Mode: <span class="font-weight-bold text-gray"><?= $result['delivery_mode'] == 1 ? "Post" : ($result['delivery_mode'] == 2 ? "Counter" : "Email"); ?></span></div>
                                <div class="col-md-4">Fee: <span class="font-weight-bold text-gray">Rs. <?= $result['court_fee'] + $result['postal_fee']; ?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">Case No.: <span class="font-weight-bold text-gray"><?= $result['reg_no_display'] . ' DNo. ' . substr($result['diary'], 0, -4) . '-' . substr($result['diary'], -4); ?></span></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No Record Found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>