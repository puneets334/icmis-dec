<?php
namespace App\Controllers\Cron;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\Database;
use Config\Services;
use Exception;

//use setasign\Fpdi\Fpdi;
class Cron extends BaseController
{

    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
    }
    public function cron_test()
    {
        return view('Copying/order_search_view');
    }
    public function cronTrack()
    {
        set_time_limit(25000);
        // Load the database service
        $db = \Config\Database::connect();
        // Fetch applicant details using Query Builder
        $builder = $db->table('post_bar_code_mapping p');
        $builder->select('p.barcode, c.crn, c.application_number_display, c.name, c.mobile, c.email');
        $builder->join('copying_order_issuing_application_new c', 'c.id = p.copying_application_id', 'inner');
        $builder->where('DATE(consumed_on)', 'CURRENT_DATE - INTERVAL \'1 day\'', false);
        $builder->where('cast(is_consumed as INTEGER)',1);
        $builder->where('sms_sent_time', null);
        $builder->where('email_sent_time', null);
        //echo $builder->getCompiledSelect();
        //die;
        $query = $builder->get();

        $data_list = $query->getResultArray();

        if (!empty($data_list)) {
            foreach ($data_list as $data) {
                $barcode = $data['barcode'];
                $crn = $data['crn'];
                $application_number_display = $data['application_number_display'];
                $name = $data['name'];
                $mobile = $data['mobile'];
                $email = $data['email'];

                $content = "CRN $crn dispatched with consignment no. $barcode. You may track at https://anu.sci.gov.in/84e15cf. - Supreme Court of India";

                // Send SMS
                $sms_response = sendSMS($mobile, $content, 'ecop', SCISMS_Consignment_dispatch);
                $json = json_decode($sms_response);
                if ($json->{'Status'} == "success") {
                    // Update SMS sent time
                    $db->table('post_bar_code_mapping')->update(['sms_sent_time' => date('Y-m-d H:i:s')], ['barcode' => $barcode]);
                    echo '<br>Success: SMS sent to Mobile ' . $mobile . ' Having Content : ' . $content;
                } else {
                    echo '<br>Error: Unable to Send SMS to Mobile ' . $mobile . ' ';
                }

                // Prepare email
                $subject = "Copying Consignment No. $barcode - Supreme Court of India";
                $message = $this->prepare_email_message($content);

                // Send Email
                $files = array();
               
                //$string = base64_encode(json_encode(array("allowed_key" => "7zFqrb9I5D", "sender" => "eService", "mailTo" => $email, "subject" => $subject, "message" => $message, "files" => $files)));
                //$content = http_build_query(array('a' => $string));
                //$context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content)));
                //$json_return = file_get_contents('http://XXXX/supreme_court/Copying/index.php/Api/eMailSend', null, $context);
                //$json4 = json_decode($json_return);

                if (send_mail_JIO($email,$subject,$message,$files)) {
                    // Update email sent time
                    $db->table('post_bar_code_mapping')->update(['email_sent_time' => date('Y-m-d H:i:s')], ['barcode' => $barcode]);
                    echo '<br>Success: email sent to ' . $email . ' Having Content : ' . $message;
                } else {
                    echo 'Error: Unable to Send email to ' . $email . ' ';
                }
            }
        }
    }

    private function prepare_email_message($content)
    {
        $message = "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
        $message .= "<div>Dear Sir/Madam,<br/><br/>Greetings from Supreme Court eCopying services!<br/><br/></div>";
        $message .= $content;
        $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'>
                <span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
        $message .= "</div>";
        $message .= "<br><div style='font-size: 12px; text-align: center; color: #FF0000; padding-top:20px;'>This email was sent from an email address that cannot receive emails. Please do not reply to this email.</div>";
        $message .= "</body></html>";
        return $message;
    }
    public function cron_one()
    {
        set_time_limit(25000);

        // Load the database
        $db2 = Database::connect('eservices');
        //$builder = $db2->table('copying_application_online AS cao');

        // Query to get applicant details
        //$builder = $db2->table($this->table);
        $builder1 = $db2->table('copying_application_online cao')
    ->select('cao.*')
    ->join('bharat_kosh_request bkr', 'cao.crn = bkr.order_code')
    ->join('bharat_kosh_status bks', 'bks.order_code = bkr.order_code')
    ->where('cao.application_receipt >= CURRENT_DATE - INTERVAL \'1 day\'')
    ->where('bks.response_status', 'SUCCESS')
    ->where('cao.allowed_request', 'e_copying_prepaid');

// Prepare the second part of the union with an alias
$builder2 = $db2->table('copying_application_online cao')
    ->select('cao.*')
    ->where('cao.application_receipt >= CURRENT_DATE - INTERVAL \'1 day\'')
    ->groupStart() // Start grouping conditions
        ->where('cao.copy_category', 5)
        ->where('cao.allowed_request', 'digital_copy')
    ->groupEnd() // End grouping conditions
    ->orWhere('cao.allowed_request', 'free_copy');

// Combine both queries using union and provide an alias
$query = $builder1->union($builder2, true); // The second parameter true indicates that we want to keep the original query structure

// Execute the query


// Execute the query
$applicant_detail = $query->get()->getResultArray();
        //echo $builder->getCompiledSelect();
        //die;
        //$applicant_detail = $builder->get();  
        $data_list = $applicant_detail;
        //print_r($data_list);
        //die;
        $db = Database::connect();
        if (count($data_list) > 0) {
            foreach ($data_list as $data) {
                // Check if the application already exists
                $applicant_detail_chk = $db->table('copying_order_issuing_application_new')
                    ->select('id')
                    ->where('crn',$data['crn'])
                    ->where('crn !=', '0')
                    ->where('LENGTH(crn)', 15)
                    ->get();

                if ($applicant_detail_chk->getNumRows() == 0) {
                    // Prepare data for insertion
                    $application_data = [
                        'diary' => $data['diary'],
                        'copy_category' => $data['copy_category'],
                        'application_reg_number' => $this->getMaxApplicationNumber($data['copy_category']),
                        'application_reg_year' => date('Y'),
                        'application_receipt' => date('Y-m-d H:i:s'),
                        'advocate_or_party' => '0',
                        'court_fee' => $data['court_fee'],
                        'delivery_mode' => $data['delivery_mode'],
                        'postal_fee' => $data['postal_fee'],
                        'adm_updated_by' => '0',
                        'updated_on' => date('Y-m-d H:i:s'),
                        'application_status' => 'P',
                        'filed_by' => $data['filed_by'],
                        'name' => $data['name'],
                        'mobile' => $data['mobile'],
                        'address' => $data['address'],
                        'source' => $data['source'],
                        'send_to_section' => $data['send_to_section'],
                        'application_number_display' => $this->getApplicationNumberDisplay($data['copy_category']),
                        'crn' => $data['crn'],
                        'email' => $data['email'],
                        'authorized_by_aor' => $data['authorized_by_aor'],
                        'allowed_request' => $data['allowed_request']
                    ];
                    
                    // Insert into the database
                    $db->table('copying_order_issuing_application_new')->insert($application_data);
                    //echo $db->error();
                    //die;
                    $offline_application_id = $db->insertID();

                    // Fetch online documents
                    $online_document = $db2->table('copying_application_documents_online')
                        ->where('copying_order_issuing_application_id', $data['id'])
                        ->get();

                    if ($online_document->getNumRows() > 0) {
                        foreach ($online_document->getResultArray() as $data2) {
                            $document_data = [
                                'order_type' => $data2['order_type'],
                                'order_date' => $data2['order_date'],
                                'copying_order_issuing_application_id' => $offline_application_id,
                                'number_of_copies' => $data2['number_of_copies'],
                                'number_of_pages_in_pdf' => $data2['number_of_pages_in_pdf'],
                                'path' => $data2['path'],
                                'from_page' => $data2['from_page'],
                                'to_page' => $data2['to_page'],
                                'is_bail_order' => $data2['is_bail_order']
                            ];

                            // Insert document data
                            $db->table('copying_application_documents')->insert($document_data);
                        }
                    }

                    // Send SMS and Email
                    $this->sendNotifications($data, $application_data['application_number_display']);
                }
            }
        }
    }

    private function getMaxApplicationNumber($copy_category)
    {
        $db = Database::connect();
        $builder = $db->table('copying_order_issuing_application_new');

        $max_application = $builder->select('COALESCE(MAX(application_reg_number), 0) + 1 AS app_no')
            ->where('copy_category', $copy_category)
            ->where('application_reg_year', date('Y'))
            ->get()
            ->getRow();
            
        //echo $max_application;
        //die;
        return $max_application->app_no;
    }

    private function getApplicationNumberDisplay($copy_category)
    {
        $db = Database::connect();
        $builder = $db->table('master.copy_category');

        $code = $builder->select('code')
            ->where('id', $copy_category)
            ->get()
            ->getRow();

        return $code->code . '-' . $this->getMaxApplicationNumber($copy_category) . '/' . date('Y');
    }

    private function sendNotifications($data, $application_no_display)
    {
        // Send SMS
        $content = "eCopying application no. $application_no_display generated successfully for DNo. " . $data['diary'] . " - Supreme Court Of India";
        $sms_response = sendSMS($data['mobile'], $content,'');
        if ($sms_response) {
            echo '<br>Success: SMS sent to Mobile ' . $data['mobile'] . ' Having Content : ' . $content;
        } else {
            echo '<br>Error: Unable to send SMS to Mobile ' . $data['mobile'];
        }

        // Send Email
        $email_content = $this->prepareEmailContent($data, $application_no_display);
        $subject='eCopying application no Generation';
        $email_response=send_mail_JIO($data['email'],$subject,$email_content,[]);
        //$email_response = $this->sendEmail($data['email'], $email_content);
        if($email_response){
            echo '<br>Success: Email sent to ' . $data['email'];
        } else {
            echo '<br>Error: Unable to send email to ' . $data['email'];
        }
    }

    

    private function prepareEmailContent($data, $application_no_display)
    {
        $message = "<html><body>";
        $message .= "Dear Sir/Madam,<br/><br/>Greetings from Supreme Court eCopying services!<br/><br/>";
        $message .= "eCopying application no. $application_no_display generated successfully for DNo. " . $data['diary'] . ".<br/>";
        $message .= "<br/><a href='https://anu.sci.gov.in/e_copying' target='_blank'>Click here</a> to visit eCopying Portal of Supreme Court of India.";
        $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'>
            <span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
        $message .= "<br><div style='font-size: 12px; text-align: center; color: #FF0000; padding-top:20px;'>This email was sent from an email address that cannot receive emails. Please do not reply to this email.</div>";
        $message .= "</body></html>";

        return $message;
    }
    public function cron_two()
    {
        $dbeservices = Database::connect('eservices');

        // Step 1: Update user_assets
        $dbeservices->table('user_assets')->where(['verify_status' => 1, 'asset_type' => 1, 'id_proof_type' => 6])
            ->set(['verify_status' => 2, 'verify_by' => 1, 'verify_on' => date('Y-m-d H:i:s')])
            ->update();

        // Step 2: Fetch applicant details
        $applicantDetails = $dbeservices->table('copying_application_online')
            ->where('DATE(application_receipt)', date('Y-m-d'))
            ->whereIn('allowed_request', ['party', 'third_party', 'request_to_available', 'appearing_counsel'])
            ->get()
            ->getResultArray();
            $db= Database::connect();
        foreach ($applicantDetails as $data) {
            // Check if CRN exists
            $crnCount = $db->table('copying_request_verify')
                ->where(['crn' => $data['crn'], 'crn !=' => '0', 'LENGTH(crn)' => 15])
                ->countAllResults();

            if ($crnCount == 0) {
                // Prepare data for insertion
                $insertData = [
                    'diary' => $data['diary'],
                    'copy_category' => $data['copy_category'],
                    'application_reg_number' => '0',
                    'application_reg_year' => '0',
                    'application_receipt' => date('Y-m-d H:i:s'),
                    'advocate_or_party' => '0',
                    'court_fee' => $data['court_fee'],
                    'delivery_mode' => $data['delivery_mode'],
                    'postal_fee' => $data['postal_fee'],
                    'adm_updated_by' => '0',
                    'updated_on' => date('Y-m-d H:i:s'),
                    'application_status' => 'P',
                    'filed_by' => $data['filed_by'],
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'address' => $data['address'],
                    'source' => $data['source'],
                    'send_to_section' => $data['send_to_section'],
                    'application_number_display' => '0',
                    'crn' => $data['crn'],
                    'email' => $data['email'],
                    'authorized_by_aor' => $data['authorized_by_aor'],
                    'allowed_request' => $data['allowed_request'],
                    'token_id' => $data['token_id'],
                    'address_id' => $data['address_id']
                ];

                // Insert into copying_request_verify
                $db->table('copying_request_verify')->insert($insertData);
                $offlineApplicationId = $db->insertID();

                // Fetch online documents
                $onlineDocuments = $db->table('copying_application_documents_online')
                    ->where('copying_order_issuing_application_id', $data['id'])
                    ->get()
                    ->getResultArray();

                foreach ($onlineDocuments as $document) {
                    $documentData = [
                        'order_type' => $document['order_type'],
                        'order_date' => $document['order_date'],
                        'copying_order_issuing_application_id' => $offlineApplicationId,
                        'order_type_remark' => $document['order_type_remark'],
                        'number_of_copies' => $document['number_of_copies'],
                        'number_of_pages_in_pdf' => $document['number_of_pages_in_pdf'],
                        'path' => $document['path']
                    ];

                    // Insert into copying_request_verify_documents
                    $db->table('copying_request_verify_documents')->insert($documentData);

                    if ($data['allowed_request'] == 'request_to_available') {
                        $lastCopyRequestVerifyDocumentsId = $db->insertID();

                        if($db->table('copying_request_verify_documents')->where('id', $lastCopyRequestVerifyDocumentsId)->countAllResults() > 0){
                            $movementData = [
                                'copying_request_verify_documents_id' => $lastCopyRequestVerifyDocumentsId,
                                'from_section' => '73',
                                'from_section_sent_by' => '0',
                                'from_section_sent_on' => date('Y-m-d H:i:s'),
                                'to_section' => '10'
                            ];

                            // Insert into copying_request_movement
                            $db->table('copying_request_movement')->insert($movementData);
                        }
                    }
                }
            }
        }

        // Step 3: Send emails and SMS for completed requests
        $completedRequests = $db->table('copying_request_verify')
            ->where('allowed_request', 'request_to_available')
            ->where('sms_sent_on IS NULL OR email_sent_on IS NULL')
            ->where('application_status !=', 'P')
            ->get()
            ->getResultArray();

        foreach ($completedRequests as $request) {
            $mailContent = "";
            $smsContent = "";
            $caseNo = (!empty($request['reg_no_display'])? $request['reg_no_display'] : "DNo. " . $request['diary']);

            $mailContent .= "Your request in Case No. $caseNo to available documents in eCopying software completed";
            $smsContent .= "Your request for readily available documents vide CRN " . $request['crn'] . " in Case No. $caseNo is processed.";

            $documentsLink = $db->table('copying_request_verify_documents')
                ->where('copying_order_issuing_application_id', $request['id'])
                ->where('request_status', 'D')
                ->get()
                ->getResultArray();

            if (count($documentsLink) > 0) {
                // Send link for non-digital copy
                $token = md5(uniqid(rand(), TRUE));
                $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['token_id' => $token]);

                // URL shortener
                $contentPush = array("key" => URL_SHORTNER_KEY, "url" => "https://registry.sci.gov.in/api/callback/bharat_kosh/online_copying/requested_copy_email_response.php?token_id=$token");
                $content = json_encode($contentPush);
                $base64Encode = base64_encode($content);

                $result = create_shorten($base64Encode);
                $base64Decode = base64_decode($result);
                $json = json_decode($base64Decode, true);

                if ($json['status'] == 'success') {
                    $mailContent .= "<br><a href='".$json['slug']. "' target='_BLANK'>Click Here</a> for further process. Link valid for 7 days.<br>";
                    $smsContent .= " Click here ".$json['slug']. " Link Valid for 7 days.";
                } else {
                    echo "Error: " . $json['status'] . ' Return : ' . $json['slug'];
                    exit();
                }
            }

            $documents = $db->table('copying_request_verify_documents vd');
            $documents->select('ot.order_type as order_type_name, vd.*');
            $documents->join('master.ref_order_type ot', 'vd.order_type = ot.id', 'left');
            $documents->where('copying_order_issuing_application_id',$request['id']);
            $documents->where('request_status !=', 'P');
            $query=$documents->get();
            $documents=$query->getResultArray();
             
            if (count($documents) > 0) {
                $mailContent .= "<table border='1' style='color: #00220d; border-collapse:collapse; border:1px solid #7a0707; font-family:verdana; font-size:13px; font-weight:bold'><tr><td>Requested Documents</td><td>status</td><td>Remark</td></tr>";

                foreach ($documents as $document) {
                    $requestStatus = '';
                    if ($document['request_status'] == 'F') {
                        $requestStatus = 'Rejected';
                    }
                    if ($document['request_status'] == 'D') {
                        $requestStatus = 'Accepted';
                    }
                    $mailContent .= "<tr>";
                    $mailContent .= "<td>" . $document['order_type_name'] . " order/file date " . date('d-m-Y', strtotime($document['order_date'])) . " : " . $document['order_type_remark'] . "</td>";
                    $mailContent .= "<td>" . $requestStatus . "</td>";
                    $mailContent .= "<td>" . $document['reject_cause'] . "</td>";
                    $mailContent .= "</tr>";
                }
                $mailContent .= "</table>";
            }

            if ($request['sms_sent_on'] == null) {
                $requesterMobile = $request['mobile'];
                $smsResponse = sendSMS($requesterMobile, $smsContent, 'ecop',SCISMS_e_copying_g_p);
                //print_r($smsResponse);
                //die;
                //$json1 = json_decode($smsResponse);

                if ($smsResponse) {
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['sms_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: SMS sent to Requester Mobile ' . $requesterMobile . ' Having Content : ' . $smsContent;
                } else {
                    echo '<br>Error: Unable to Sent SMS to Requester Mobile ' . $requesterMobile . ' ';
                }
            }

            if ($request['email_sent_on'] == null) {
                $requesterEmail = $request['email'];
                $header = '';
                $subject = '';
                $header = "MIME-Version: 1.0" . "\r\n";// Always set content-type when sending HTML email
                $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $header .= 'From: <sci@nic.in>' . "\r\n"; //"mphc@mphc.in"; // sender
                $message = "";
                $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
                $message .= "<div>";
                $message .= "Dear Sir/Madam,<br/><br/>Greetings from Supreme Court eCopying services!<br/><br/></div>";
                $message .= $mailContent;
                $message .= '<br/><br/><a href="https://anu.sci.gov.in/e_copying" target="_blank">Click here</a> to visit eCopying Portal of Supreme Court of India.';
                $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'>
                <span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
                $message .= "</div>";
                $message .= "<br><div style='font-size: 12px; text-align: center; color: #FF0000; padding-top:20px;'>This email was sent from an email address that can not receive emails. Please do not reply to this email.</div>";
                $message .= "</body></html>";
                $message = wordwrap($message, 70);

                $emailTo = $requesterEmail;
                $subject = "Copy Request - Supreme Court of India";
                $body = $message;

                $files = array();
                //$string = base64_encode(json_encode(array("allowed_key" => "7zFqrb9I5D", "sender" => "eService", "mailTo" =>$emailTo, "subject" => $subject, "message" => $message, "files" => $files)));
                //$content = http_build_query(array('a' => $string));
                //$context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
                //$jsonReturn = file_get_contents('http://XXXX/supreme_court/Copying/index.php/Api/eMailSend', null, $context);
                //$json2 = json_decode($jsonReturn);
                //$smsResponse=sendSMS($requesterMobile, $smsContent, 'ecop', $smsTemplateIdVerificationCompleted);
                if(send_mail_JIO($emailTo,$subject,$message,$files)){
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['email_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: email sent to Requester ' . $requesterEmail . ' Having Content : ' . $message;
                } else {
                    echo 'Error: Unable to Sent email to Requester ' . $requesterEmail . ' ';
                }
            }
        }

        // Step 4: Send emails and SMS for appearing counsel and third party requests
        $appearingCounselRequests = $db->table('copying_request_verify')
            ->where('allowed_request', 'appearing_counsel')
            ->where('sms_sent_on IS NULL OR email_sent_on IS NULL')
            ->where('application_status !=', 'P')
            ->get()
            ->getResultArray();

        foreach ($appearingCounselRequests as $request) {
            $requesterMobile = $request['mobile'];
            $mailContent = "";
            $smsContent = "";
            $caseNo = $request['reg_no_display'] != '' ? $request['reg_no_display'] : "DNo. " . $request['diary'];

            $mailContent .= "Verification process for CRN " . $request['crn'] . " in Case No. $caseNo completed. - Supreme Court of India";
            $smsContent .= "Verification process for CRN " . $request['crn'] . " in Case No. $caseNo completed. - Supreme Court of India";
            $smsTemplateIdVerificationCompleted =SCISMS_Party_verification_completed;

            $sqlAsset = "select * from user_assets u where u.mobile = '" . $request['mobile'] . "' and u.email = '" . $request['email'] . "' 
and u.asset_type = 6 and u.diary_no = " . $request['diary'] . " order by ent_time desc limit 1";
            $sqlAsset = $db->query($sqlAsset);
            $dataAsset = $sqlAsset->getRowArray();

            if ($dataAsset['verify_status'] == 2) {
                // Success
            } elseif ($dataAsset['verify_status'] == 3) {
                // Rejected
                $mailContent .= " and rejected as " . $dataAsset['verify_remark'] . "<br>";
                $smsContent .= " and rejected as " . $dataAsset['verify_remark'] . "<br>";
                $smsTemplateIdVerificationCompleted = SCISMS_Party_verification_rejected;
            } else {
                // Pending
            }

            $documentsLink = $db->table('copying_request_verify_documents')
                ->where('copying_order_issuing_application_id', $request['id'])
                ->where('request_status', 'D')
                ->get()
                ->getResultArray();

            if (count($documentsLink) > 0) {
                // Send link for non-digital copy
                $token = md5(uniqid(rand(), TRUE));
                $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['token_id' => $token]);

                // URL shortener
                $contentPush = array("key" => URL_SHORTNER_KEY, "url" => "https://registry.sci.gov.in/api/callback/bharat_kosh/online_copying/registry_verify_response.php?token_id=$token");
                $content = json_encode($contentPush);
                $base64Encode = base64_encode($content);

                $result = create_shorten($base64Encode);
                $base64Decode = base64_decode($result);
                $json = json_decode($base64Decode, true);

                if ($json['status'] == 'success') {
                    $mailContent .= "<br><a href='" . $json['slug'] . "' target='_BLANK'>Click Here</a> for further process. Link valid for 3 days.<br>";
                    $smsContent .= " Click here " . $json['slug'] . " Link Valid for 3 days.";
                } else {
                    echo "Error: " . $json['status'] . ' Return : ' . $json['slug'];
                    exit();
                }
            }

            $documents = $db->table('copying_request_verify_documents')
                ->where('copying_order_issuing_application_id', $request['id'])
                ->where('request_status !=', 'P')
                ->get()
                ->getResultArray();

            if (count($documents) > 0) {
                $mailContent .= "<table border='1' style='color: #00220d; border-collapse:collapse; border:1px solid #7a0707; font-family:verdana; font-size:13px; font-weight:bold'><tr><td>Requested Documents</td><td>status</td><td>Remark</td></tr>";

                foreach ($documents as $document) {
                    $requestStatus = '';
                    if ($document['request_status'] == 'F') {
                        $requestStatus = 'Rejected';
                    }
                    if ($document['request_status'] == 'D') {
                        $requestStatus = 'Accepted';
                    }

                    $mailContent .= "<tr>";
                    $mailContent .= "<td>" . $document['order_type_name'] . " order/file date " . date('d-m-Y', strtotime($document['order_date'])) . " : " . $document['order_type_remark'] . "</td>";
                    $mailContent .= "<td>" . $requestStatus . "</td>";
                    $mailContent .= "<td>" . $document['reject_cause'] . "</td>";
                    $mailContent .= "</tr>";
                }
                $mailContent .= "</table>";
            }

            if($request['sms_sent_on'] == null) {
                $smsResponse=sendSMS($requesterMobile, $smsContent, 'ecop', $smsTemplateIdVerificationCompleted);
                //$smsResponse =sci_send_sms($requesterMobile, $smsContent, 'ecop', $smsTemplateIdVerificationCompleted);
                $json1 = json_decode($smsResponse);

                if ($json1->{'Status'} == "success") {
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['sms_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: SMS sent to AC Mobile ' . $requesterMobile . ' Having Content : ' . $smsContent;
                } else {
                    echo '<br>Error: Unable to Sent SMS to AC Mobile ' . $requesterMobile . ' ';
                }
            }

            if ($request['email_sent_on'] == null) {
                $requesterEmail = $request['email'];
                $header = '';
                $subject = '';
                $header = "MIME-Version: 1.0" . "\r\n";// Always set content-type when sending HTML email
                $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $header .= 'From: <sci@nic.in>' . "\r\n"; //"mphc@mphc.in"; // sender
                $message = "";
                $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
                $message .= "<div>";
                $message .= "Dear Sir/Madam,<br/><br/>Greetings from Supreme Court eCopying services!<br/><br/></div>";
                $message .= $mailContent;
                $message .= '<br/><br/><a href="https://anu.sci.gov.in/e_copying" target="_blank">Click here</a> to visit eCopying Portal of Supreme Court of India.';
                $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'>
                <span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
                $message .= "</div>";
                $message .= "<br><div style='font-size: 12px; text-align: center; color: #FF0000; padding-top:20px;'>This email was sent from an email address that can not receive emails. Please do not reply to this email.</div>";
                $message .= "</body></html>";
                $message = wordwrap($message, 70);

                $emailTo = $requesterEmail;
                $subject = "Copy Request - Supreme Court of India";
                $body = $message;

                $files = array();
                //$string = base64_encode(json_encode(array("allowed_key" => "7zFqrb9I5D", "sender" => "eService", "mailTo" => $emailTo, "subject" => $subject, "message" => $message, "files" => $files)));
                //$content = http_build_query(array('a' => $string));
                //$context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
                //$jsonReturn = file_get_contents('http://XXXX/supreme_court/Copying/index.php/Api/eMailSend', null, $context);
                //$json2 = json_decode($jsonReturn);

                if(send_mail_JIO($emailTo,$subject,$message,$files)) {
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['email_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: email sent to AC ' . $requesterEmail . ' Having Content : ' . $message;
                } else {
                    echo 'Error: Unable to Sent email to AC ' . $requesterEmail . ' ';
                }
            }
        }

        // Step 5: Send emails and SMS for party verification
        $partyVerificationRequests = $db->table('copying_request_verify')
            ->where('allowed_request', 'party')
            ->where('sms_sent_on IS NULL OR email_sent_on IS NULL')
            ->where('application_status !=', 'P')
            ->get()
            ->getResultArray();

        foreach ($partyVerificationRequests as $request) {
            $requesterMobile = $request['mobile'];
            $mailContent = "";
            $smsContent = "";
            $caseNo = $request['reg_no_display'] != '' ? $request['reg_no_display'] : "DNo. " . $request['diary'];

            $mailContent .= "Party verification process for CRN " . $request['crn'] . " in Case No. $caseNo ";
            $smsContent .= "Party verification process for CRN " . $request['crn'] . " in Case No. $caseNo ";

            if ($request['application_status'] == 'D') {
                // Success
                $mailContent .= " completed <br>";
                $smsContent .= " completed. Click here https://anu.sci.gov.in/e_copying for further process.";
                $smsTemplateIdPartyVerification = SCISMS_Party_verification_completed;
            } else {
                // Rejected
                $mailContent .= " rejected<br>";
                $smsContent .= " rejected.";
                $smsTemplateIdPartyVerification = SCISMS_Party_verification_rejected;
            }
            $smsContent .= " - Supreme Court of India";

            if ($request['sms_sent_on'] == null) {
                $smsResponse = sendSMS($requesterMobile, $smsContent,'ecop',$smsTemplateIdPartyVerification);
                $json1 = json_decode($smsResponse);

                if ($json1->{'Status'} == "success") {
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['sms_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: SMS sent to Party Mobile ' .$requesterMobile.' Having Content : ' . $smsContent;
                } else {
                    echo '<br>Error: Unable to Sent SMS to Party Mobile ' . $requesterMobile . ' ';
                }
            }

            if ($request['email_sent_on'] == null) {
                $requesterEmail = $request['email'];
                $header = '';
                $subject = '';
                $header = "MIME-Version: 1.0" . "\r\n";// Always set content-type when sending HTML email
                $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $header .= 'From: <sci@nic.in>' . "\r\n"; //"mphc@mphc.in"; // sender
                $message = "";
                $message .= "<html><body><div style='font-family:verdana; font-size:13px; font-weight:bold'>";
                $message .= "<div>";
                $message .= "Dear Sir/Madam,<br/><br/>Greetings from Supreme Court eCopying services!<br/><br/></div>";
                $message .= $mailContent;
                $message .= '<br/><br/><a href="https://anu.sci.gov.in/e_copying" target="_blank">Click here</a> to visit eCopying Portal of Supreme Court of India.';
                $message .= "<br/><br/><br/><div style='font-family:verdana; font-size:13px; font-weight:bold'>
                <span style='color:#ffbb00;'>Thanks & Regards</span><BR/>SUPREME COURT OF INDIA<BR/></div>";
                $message .= "</div>";
                $message .= "<br><div style='font-size: 12px; text-align: center; color: #FF0000; padding-top:20px;'>This email was sent from an email address that can not receive emails. Please do not reply to this email.</div>";
                $message .= "</body></html>";
                $message = wordwrap($message, 70);

                $emailTo = $requesterEmail;
                $subject = "Copy Request - Supreme Court of India";
                $body = $message;

                $files = array();
                //$string = base64_encode(json_encode(array("allowed_key" => "7zFqrb9I5D", "sender" => "eService", "mailTo" => $emailTo, "subject" => $subject, "message" => $message, "files" => $files)));
                //$content = http_build_query(array('a' => $string));
                //$context = stream_context_create(array('http' => array('method' => 'POST', 'content' => $content,)));
                //$jsonReturn = file_get_contents('http://XXXX/supreme_court/Copying/index.php/Api/eMailSend', null, $context);
                //$json2 = json_decode($jsonReturn);

                if(send_mail_JIO($emailTo,$subject,$message,$files)) {
                    $db->table('copying_request_verify')->where('crn', $request['crn'])->update(['email_sent_on' => date('Y-m-d H:i:s')]);
                    echo '<br>Success: email sent to party ' . $requesterEmail . ' Having Content : ' . $message;
                } else {
                    echo 'Error: Unable to Sent email to party ' . $requesterEmail . ' ';
                }
            }
        }
    }
}