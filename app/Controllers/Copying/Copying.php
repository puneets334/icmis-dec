<?php
namespace App\Controllers\Copying;
use App\Controllers\BaseController;
use App\Models\Copying\Model_copying;
use App\Models\Copying\CopyRequestModel;
use App\Models\Common\Dropdown_list_model;
use App\Models\Entities\Model_post_bar_code_mapping;
use App\Models\Entities\Model_CopyingOrderIssuingApplicationNew;
use App\Models\Entities\Model_CopyingRequestVerify;
use App\Models\Entities\Model_CopyingApplicationDocuments;
use App\Models\Entities\Model_CopyingApplicationDocumentsA;
use App\Models\Entities\Model_CopyingRequestVerifyDocuments;
use App\Models\Entities\Model_CopyingReasonsForRejection;
use App\Models\Entities\Model_CopyingRole;
use App\Models\Entities\Model_users;
use App\Models\Entities\Model_CopyingTrap;
use App\Models\Entities\Model_CopyingApplicationDefects;
use App\Models\LoginModel;
use setasign\Fpdi\Tcpdf\Tcpdf;
use setasign\Fpdi\Tcpdf\Fpdi;

//use setasign\Fpdi\Fpdi;
class Copying extends BaseController
{

    public $Dropdown_list_model;
    public $Copying_model;
    public $PostBarCodeMapping;
    public $Model_CopyingOrderIssuingApplicationNew;
    public $Model_CopyingRequestVerify;
    public $Model_CopyingRequestVerifyDocuments;
    public $Model_CopyingReasonsForRejection;
    public $Model_CopyingRole;
    public $Model_CopyingApplicationDocuments;
    public $Model_CopyingApplicationDocumentsA;
    public $Model_users;
    public $Model_CopyingTrap;
    public $Model_CopyingApplicationDefects;
    public $LoginModel;
    protected $copyRequestModel;
    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->Dropdown_list_model = new Dropdown_list_model();
        $this->Copying_model = new Model_copying();
        $this->PostBarCodeMapping = new Model_post_bar_code_mapping();
        $this->Model_CopyingOrderIssuingApplicationNew = new Model_CopyingOrderIssuingApplicationNew();
        $this->Model_CopyingRequestVerify = new Model_CopyingRequestVerify();
        $this->Model_CopyingRequestVerifyDocuments = new Model_CopyingRequestVerifyDocuments();
        $this->Model_CopyingReasonsForRejection = new Model_CopyingReasonsForRejection();
        $this->Model_CopyingRole = new Model_CopyingRole();
        $this->Model_CopyingApplicationDocuments = new Model_CopyingApplicationDocuments();
        $this->Model_CopyingApplicationDocumentsA = new Model_CopyingApplicationDocumentsA();
        $this->Model_users = new Model_users();
        $this->Model_CopyingTrap = new Model_CopyingTrap();
        $this->Model_CopyingApplicationDefects = new Model_CopyingApplicationDefects();
        $this->copyRequestModel = new CopyRequestModel();
        $this->LoginModel = new LoginModel();
    }
    
    public function requestedCopyEmailResponse(){
        $tokenId=$this->request->getGet('token_id');

        if (strlen($tokenId)>10){
            $db = \Config\Database::connect();

            // Fetching the copying request
            $builder = $db->table('copying_request_verify');
            $data = $builder->where('token_id',$tokenId)
                ->where('CURDATE() <= DATE_ADD(updated_on,INTERVAL 7 DAY)')
                ->where('sms_sent_on IS NOT NULL')
                ->get()
                ->getRowArray();

            if ($data) {
                // Setting session data
                session()->set([
                    'session_verify_otp' => '000000',
                    'session_otp_id' => '999999',
                    'applicant_mobile' => $data['mobile'],
                    'applicant_email' => $data['email'],
                    'is_email_send' => 'Yes',
                    'email_token' => $data['token_id'],
                    'is_token_matched' => 'Yes',
                    'session_d_no' => substr($data['diary'], 0, -4),
                    'session_d_year' => substr($data['diary'], -4),
                    'session_filed' => $data['filed_by'],
                ]);

                // Fetching user address
                $addressBuilder = $db->table('user_address');
                $addressData = $addressBuilder->where('mobile', trim($data['mobile']))
                    ->where('email', trim($data['email']))
                    ->where('is_active', 'Y')
                    ->get()
                    ->getResultArray();

                if ($addressData) {
                    session()->set('is_user_address_found', 'YES');
                    session()->set('user_address',$addressData);
                } else {
                    session()->set('is_user_address_found', 'NO');
                }

                // Check if filed_by is 6
                if ($data['filed_by'] == 6) {
                    $barBuilder = $db->table('bar');
                    $aorData = $barBuilder->where('LENGTH(mobile)',10)
                        ->where('if_aor', 'Y')
                        ->where('isdead', 'N')
                        ->where('bar_id', $data['authorized_by_aor'])
                        ->get()
                        ->getRowArray();

                    if ($aorData) {
                        session()->set('aor_mobile', $aorData['mobile']);
                    }
                }

                //return redirect()->to('case_search');
            } else {
                return "No Records Found or Request Expired";
            }
        }else{
            return "Error";
        }

    }
    public function orders()
    {
        return view('Copying/order_search_view');
    }
    public function signedPdfFromTcPdf(){

        $pdf = new Fpdi();

        // Set source file
        $pageCount = $pdf->setSourceFile('document.pdf');

       // Import the first page
       $templateId = $pdf->importPage(1);
       $pdf->AddPage();
       $pdf->useTemplate($templateId);

       // Set font
       $pdf->SetFont('Helvetica', 'B', 12);

        // Add signature text
       $pdf->SetXY(10, 10); // Set position for the signature
       $pdf->Cell(0, 10, 'Signed by: John Doe', 0, 1);
       $pdf->Cell(0, 10, 'Date: ' . date('Y-m-d H:i:s'), 0, 1);

       // Load the certificate
       $certificate = 'path/to/your/certificate.p12'; // Path to your .p12 or .pfx file
       $password = 'your_certificate_password'; // Password for the certificate

       // Sign the PDF
      $pdf->setSignature($certificate, $password, '', 2, '', 0, '');

      // Output the signed PDF
      $pdf->Output('signed_document.pdf', 'D');

        /*$pdf = new Tcpdf();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Signed PDF Example');
$pdf->SetSubject('PDF Signing');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Add a page
$pdf->AddPage();

// Set the source file
$pdf->setSourceFile('path/to/your/existing.pdf');

// Import the first page of the existing PDF
$templateId = $pdf->importPage(1);
$pdf->useTemplate($templateId);

// Set the position for the signature
$pdf->SetXY(10, 10); // Adjust the position as needed

// Add a signature image (optional)
$pdf->Image('path/to/your/signature.png', 10, 10, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);

// Add a text signature (optional)
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Text(10, 30, 'Signed by Your Name');

// Output the signed PDF
$pdf->Output('signed_pdf.pdf', 'F');

echo "PDF signed successfully!";*/
    }
    public function pdfgenerator(){
    // Create a simple PDF file
    $pdfContent = "%PDF-1.4\n";
    $pdfContent .= "1 0 obj\n";
    $pdfContent .= "<< /Type /Catalog /Pages 2 0 R >>\n";
    $pdfContent .= "endobj\n";
    $pdfContent .= "2 0 obj\n";
    $pdfContent .= "<< /Type /Pages /Kids [3 0 R] /Count 1 >>\n";
    $pdfContent .= "endobj\n";
    $pdfContent .= "3 0 obj\n";
    $pdfContent .= "<< /Type /Page /MediaBox [0 0 300 300] /Contents 4 0 R >>\n";
    $pdfContent .= "endobj\n";
    $pdfContent .= "4 0 obj\n";
    $pdfContent .= "<< /Length 44 >>\n";
    $pdfContent .= "stream\n";
    $pdfContent .= "BT /F1 24 Tf 100 200 Td (Hello World) Tj\n";
    $pdfContent .= "ET\n";
    $pdfContent .= "endstream\n";
    $pdfContent .= "endobj\n";
    $pdfContent .= "5 0 obj\n";
    $pdfContent .= "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\n";
    $pdfContent .= "endobj\n";
    $pdfContent .= "xref\n";
    $pdfContent .= "0 6\n";
    $pdfContent .= "0000000000 65535 f \n";
    $pdfContent .= "0000000010 00000 n \n";
    $pdfContent .= "0000000070 00000 n \n";
    $pdfContent .= "0000000120 00000 n \n";
    $pdfContent .= "0000000200 00000 n \n";
    $pdfContent .= "0000000250 00000 n \n";
    $pdfContent .= "trailer\n";
    $pdfContent .= "<< /Size 6 /Root 1 0 R >>\n";
    $pdfContent .= "startxref\n";
    $pdfContent .= "300\n";
    $pdfContent .= "%%EOF\n";
    // Save the PDF to a file
   file_put_contents('simple.pdf',$pdfContent);

}
   public function appendSignatureInPdfFile(){
    // Read the original PDF content
    $pdfContent = file_get_contents('simple.pdf');
    $signature=$this->digitalSignature($pdfContent);
    // Create a new signature object
    $signatureObject = "6 0 obj\n";
    $signatureObject .= "<< /Type /Sig /Filter /Adobe.PPKMS /SubFilter /adbe.pkcs7.s5 /Contents " . base64_encode($signature) . " >>\n";
    $signatureObject .= "endobj\n";
    
    // Append the signature object to the PDF content
    $pdfContent .= $signatureObject;
    
    // Calculate the offset for the new signature object
    $offset = strlen($pdfContent);
    
    // Update the cross-reference table
    $pdfContent .= "xref\n";
    $pdfContent .= "0 7\n"; // Update the count to 7 (6 original + 1 signature)
    $pdfContent .= "0000000000 65535 f \n"; // Object 0
    $pdfContent .= "0000000010 00000 n \n"; // Object 1
    $pdfContent .= "0000000070 00000 n \n"; // Object 2
    $pdfContent .= "0000000120 00000 n \n"; // Object 3
    $pdfContent .= "0000000200 00000 n \n"; // Object 4
    $pdfContent .= "0000000250 00000 n \n"; // Object 5
    $pdfContent .= str_pad($offset, 10, '0', STR_PAD_LEFT) . " 00000 n \n"; // Object 6 (signature)
    
    // Add the trailer
    $pdfContent .= "trailer\n";
    $pdfContent .= "<< /Size 7 /Root 1 0 R >>\n"; // Update the size to 7
    $pdfContent .= "startxref\n";
    $pdfContent .= $offset . "\n"; // The offset of the start of the xref table
    $pdfContent .= "%%EOF\n";
    
    // Save the signed PDF to a file
    file_put_contents('signed_document.pdf', $pdfContent);
   }
   public function digitalSignature($pdfContent){
    // Load your private key
    $privateKey = openssl_pkey_get_private(file_get_contents('private_key_capricon.pem'), 'Savan@#2020');
    // Create a hash of the PDF content
    $pdfHash = hash('sha256', $pdfContent, true);
    // Sign the hash
    openssl_sign($pdfHash,$signature,$privateKey, OPENSSL_ALGO_SHA256);
    // Save the signature to a file
   file_put_contents('signature.bin',$signature);
   return $signature;
   }

    public function download_order()
    {
        $dataArray = array(
            'diary' => $this->request->getPost('diary_no'),
            'diary_year' => $this->request->getPost('diary_year'),
            'order_date' => date('Y-m-d', strtotime($this->request->getPost('order_date')))
        );
        $file_path_row = $this->Copying_model->get_rop_path($dataArray);
        if (!empty($file_path_row)) {
            $file_path = $file_path_row[0]['file_path'];
            //$data = file_get_contents(getBasePath().'/jud_ord_html_pdf/'.$file_path);
            if (file_exists(base_url('jud_ord_html_pdf/'.$file_path))){
            $data = file_get_contents(base_url('jud_ord_html_pdf/'.$file_path)); 
            } else {
            $data = file_get_contents(base_url('jud_ord_html_pdf/2009/39473/110183.pdf'));
            }
            
            force_download($file_path_row[0]['c_no'] . '.pdf', $data);

            
        } else {
            session()->setFlashdata("error", 'ROP not found!');
            $this->response->redirect(site_url('/Copying/Copying/orders'));
        }
    }
    public function qr_embed(){
        return view('Copying/qr_embed');   
    }
    public function pdf_result(){
            //START TO MAKE PDF
    $content = $_POST['qr_data'];
    $application_id_id = $_POST['application_id_id'];
    $original_path = $_POST['path'];
    $original_path_replace = md5(uniqid(rand(),TRUE)).'.pdf'; //str_replace('/','@_',$original_path);
    $original_path_replace2 = md5(uniqid(rand(),TRUE)).'.pdf'; //str_replace('/','@_',$original_path);
    $file_path = 'qr_embed';
    $path_dir = "copy_verify/".$application_id_id."/";
     //$path_dir = "/var/www/html/verify_copy/".$application_id_id."/";
     //$path_dir = "/var/www/html/supreme_court/";
    if(!file_exists($path_dir)){
    mkdir($path_dir, 2777, true);
    }

/*$files = glob($path_dir.'*'); // get all file names
foreach($files as $file){ // iterate files
    if(is_file($file)) {
        unlink($file); // delete file
    }
}*/

    $path=$path_dir;
    $data_file=$path.$file_path.".html";
    $data_file1=$path.$file_path.".pdf";
    //echo $data_file1;
    //die;
    if(file_exists("$data_file"))
     unlink("$data_file");
     touch("$data_file");
     
     file_put_contents($data_file,$content);
     //$fp=fopen($data_file,"w+");
     //fwrite($fp,$content);
//exit();
//
     header("Content-type: image/png");

     ob_start();  // start output buffering
     include $data_file;
     $content = ob_get_clean(); // get content of the buffer and clean the buffer

     $mpdf = new \Mpdf\Mpdf();
     $mpdf->SetDisplayMode('fullpage');

// Show image errors (useful for debugging)
$mpdf->showImageErrors = true;

// Adjust table fitting options
$mpdf->shrink_tables_to_fit = 0; // Set to 0 to disable shrinking
$mpdf->keep_table_proportions = true; // Ensure table proportions are maintained

// Add a new page with specified margins
$mpdf->AddPageByArray([
    'margin-left' => 1, // Use numeric values for margins
    'margin-right' => 1,
    'margin-top' => 1,
    'margin-bottom' => 0,
]);

// Write HTML content to the PDF
$mpdf->WriteHTML($content);

// Output the PDF as inline content
$mpdf->Output($data_file1); // 'I' for inline display

//fclose($fp);
if($_POST['delivery_mode'] == 3){
    shell_exec("pdftk ".$path_dir."qr_embed.pdf background /var/www/html/copy_verify/wm.pdf output ".$path_dir."qr_embed_2.pdf");
}
else{
    shell_exec("pdftk".$path_dir."qr_embed.pdf background /var/www/html/copy_verify/water_mark.pdf output ".$path_dir."qr_embed_2.pdf");
}
//echo "java -jar /var/www/html/flattenpdf.jar $path_dir.$original_path_replace $path_dir.$original_path_replace";




shell_exec("pdftk /var/www/html/".$original_path." background ".$path_dir."qr_embed_2.pdf output ".$path_dir.$original_path_replace." ");


//echo "java -jar /var/www/html/flattenpdf.jar ".$path_dir.$original_path_replace." ".$path_dir.$original_path_replace2." ";

shell_exec("java -jar /var/www/html/flattenpdf.jar ".$path_dir.$original_path_replace." ".$path_dir.$original_path_replace2." ");
//shell_exec("java -jar /var/www/html/flattenpdf.jar $path_dir.$original_path_replace $path_dir.$original_path_replace2");



//shell_exec("pdftk https://main.sci.gov.in/".$original_path." background ".$path_dir."qr_embed.pdf output ".$path_dir.$original_path_replace." ");   
 

    $ucode =$_SESSION['login']['usercode'];
    

   //echo "<br>";
   $send_to_path_explode = explode("/",$_POST['path']);
   $application_explode = explode("_",$application_id_id);
   $application_id = $application_explode[0];
   $document_id = $application_explode[1];
   $documentData=array('pdf_embed_path'=>$data_file1,'pdf_embed_on'=>date('Y-m-d H:i:s'),'pdf_embed_by'=>$ucode);
    $this->Copying_model->save_copying_application_documents($documentData,$document_id);
    //unlink($data_file);
    //unlink($data_file1);
    //unlink($path_dir."qr_embed_2.pdf");
    //#zoom=FitV
         
        $data=array('application_id_id'=>$application_id_id,'original_path_replace2'=>$data_file1);
        return view('Copying/pdf_result',$data);   
    }
    public function pdf_action_show(){
       $application_explode = explode("_",$this->request->getPost('application_id_id'));
       $application_id = $application_explode[0];
       $document_id = $application_explode[1];
       $documentRow=$this->Copying_model->getApplication($document_id);
       $data=array('documentRow'=>$documentRow,'document_id'=>$document_id);
        return view('Copying/pdf_action_show_view',$data);
    }
    public function barcodeconsume()
    {
        return view('Copying/barcode_consume_view');
    }

    public function getbarcodeconsume()
    {
        $data['barcode_consume'] = $this->Copying_model->get_consume_barcode();
  
        return view('Copying/barcode_consume_get_view', $data);
    }

    public function barcodesave()
    {
        $response = "";
        if (strlen(trim($this->request->getPost('barcode'))) >= 12) {
            $dataArray = array(
                'copying_application_id' => $this->request->getPost('app_id'),
                'barcode' => $this->request->getPost('barcode'),
                'envelope_weight' => $this->request->getPost('envelope_weight'),
                'module_flag' => 'ecopying',
                'is_consumed' => '1',
                'consumed_by' => session()->get('login')['usercode'],
                'consumed_on' => date("Y-m-d H:i:s"),
                'create_modify' => date("Y-m-d H:i:s"),
                'ent_time' => date("Y-m-d H:i:s"),
                'updated_on' => date("Y-m-d H:i:s"),
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
            );
            $this->db = \Config\Database::connect();
            $this->db->transStart();
            $this->PostBarCodeMapping->insert($dataArray);
            $this->db->transComplete();
            $response = 'Y';
            session()->setFlashdata("success_msg", 'Barcode Consumed Data saved Successfully');
        } else {
            $response = 'N';
            session()->setFlashdata("error", 'Proper Barcode Entry Required');
        }
        echo $response;
    }

    public function track()
    {
        return view('Copying/track');
    }

    public function getConsignmentDetails()
    {
        $response = $message = "";
        $barcode = $this->request->getPost('cn');
        if (strlen(trim($barcode)) >= 10) {
            $barcode_details = $this->Copying_model->getConsignmentDetails($barcode);
            if (empty($barcode_details)) {
                $data['response'] = 'N';
                $data['message'] = 'Consignment Number not found';
                $data['barcode_details'] ="";
                $data['cn_no'] = "";
            } else {
                $data['response']  = 'Y';
                $data['barcode_details'] = $barcode_details;
                $data['cn_no'] = $barcode;
                $data['message'] = "";
            }
        } else {
            $data['response']  = 'N';
            $data['message'] = 'Proper Consignment Number Required';
            $data['barcode_details'] = "";
            $data['cn_no'] = "";
        }
        return view('Copying/consignment_details', $data);
        exit;
    }

    public function copy_search()
    {

        $data['copy_category'] = $this->Dropdown_list_model->get_copy_category();
        // $cause_title=$this->Model_main->select("pet_name,res_name,TO_CHAR(diary_no_rec_date, 'DD-MM-YYYY') as diary_no_rec_date,
        // (current_date - diary_no_rec_date::date) no_of_days,casetype_id")->where(['diary_no'=>$caveat_no])->findAll(1);

        return view('Copying/copy_search', $data);
    }

    public function get_copy_search()
    { 
        error_reporting(0);
        $track_horizonal_timeline = array();

        $application_type = $this->request->getPost('application_type');
        $application_no = $this->request->getPost('application_no');
        $application_year = $this->request->getPost('application_year');
        $crn = $this->request->getPost('crn');
        $flag = $this->request->getPost('flag');

        $data['row_barcode'] = $data['postage_response'] = "";

        //$this->Model_CopyingOrderIssuingApplicationNew = new Model_CopyingOrderIssuingApplicationNew();
        $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('master.ref_copying_source as r ', 'r.id = copying_order_issuing_application_new.source', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('master.ref_copying_status as s ', 's.status_code = copying_order_issuing_application_new.application_status', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->select('(case when m.reg_no_display is not null then m.reg_no_display else 
        ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
        ma.c_status end) as case_status, copying_order_issuing_application_new.id, copying_order_issuing_application_new.application_number_display,
        copying_order_issuing_application_new.diary, copying_order_issuing_application_new.crn, copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.updated_on,
        copying_order_issuing_application_new.name, copying_order_issuing_application_new.mobile, copying_order_issuing_application_new.email, copying_order_issuing_application_new.allowed_request, 
        copying_order_issuing_application_new.dispatch_delivery_date, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.filed_by, 
        copying_order_issuing_application_new.court_fee, copying_order_issuing_application_new.postal_fee, copying_order_issuing_application_new.delivery_mode, r.description, s.status_description,
        copying_order_issuing_application_new.address');
        if ($flag == 'ano') {
            $this->Model_CopyingOrderIssuingApplicationNew->where(['application_reg_number' => $application_no, 'application_reg_year' => $application_year, 'copy_category' => $application_type]);
            $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
            $query = $this->db->getLastQuery();
//pr($query);
            $data['application_request'] = 'application';
        } else {
            $this->Model_CopyingOrderIssuingApplicationNew->where(['crn' => $crn]);
            $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
//
            $data['application_request'] = 'application';

            if (empty($data['result'])) {
                $this->Model_CopyingRequestVerify->join('main m', 'm.diary_no = copying_request_verify.diary', 'left');
                $this->Model_CopyingRequestVerify->join('main_a ma', 'ma.diary_no = copying_request_verify.diary', 'left');
                $this->Model_CopyingRequestVerify->join('master.ref_copying_source as r ', 'r.id = copying_request_verify.source', 'left');
                $this->Model_CopyingRequestVerify->join('master.ref_copying_status as s ', 's.status_code = copying_request_verify.application_status', 'left');
                $this->Model_CopyingRequestVerify->select('(case when m.reg_no_display is not null then m.reg_no_display else 
                                    ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
                                    ma.c_status end) as case_status,  copying_request_verify.id, 
                                    copying_request_verify.application_number_display, copying_request_verify.diary, copying_request_verify.crn, copying_request_verify.application_receipt, copying_request_verify.updated_on,
                                );             copying_request_verify.name,copying_request_verify.mobile, copying_request_verify.email, copying_request_verify.allowed_request, copying_request_verify.dispatch_delivery_date,
                                    copying_request_verify.application_status, copying_request_verify.filed_by,
                                    copying_request_verify.court_fee, copying_request_verify.postal_fee, copying_request_verify.delivery_mode, r.description, s.status_description, copying_request_verify.address ');
                $data['result'] = $this->Model_CopyingRequestVerify->where(['crn' => $crn])->findAll(1);
                $data['application_request'] = 'request';
            }
        }

        if (is_array($data['result'])) {
            foreach ($data['result'] as $row) {
                $this->PostBarCodeMapping->select('string_agg(barcode::text,\',\') as barcode,copying_application_id');
                $data['row_barcode'] = $this->PostBarCodeMapping->where(['copying_application_id' => $row['id']])->groupBy(['copying_application_id', 'barcode'])->findAll();
                if (!empty($data['row_barcode'])) {
                    $row_barocode = $data['row_barcode'][0];
                    $explode_barcode = explode(",", $row_barocode['barcode']);
                    for ($k = 0; $k < count($explode_barcode); $k++) {
                        $data['postage_response'] = $this->Copying_model->getConsignmentDetails($explode_barcode[$k]);
                    }
                }



                if ($data['application_request'] == 'application') {
                }
                if ($data['application_request'] == 'request') {
                    if ($row['allowed_request'] != 'request_to_available') {
                    }
                    $this->Model_CopyingRequestVerifyDocuments->join('master.ref_order_type r', 'copying_request_verify_documents.order_type = r.id', 'left');
                    $data['row1'] = $this->Model_CopyingRequestVerifyDocuments->where(['copying_order_issuing_application_id' => $row['id']])->findAll();
                }
            }
        }
        return view('Copying/copy_search_get', $data);


       // print_r($data['row_barcode']);
    }

    public function reason_rejection()
    {

        $this->Model_CopyingReasonsForRejection->join('master.users u', 'copying_reasons_for_rejection.user_id = u.usercode', 'left');
        $this->Model_CopyingReasonsForRejection->select('copying_reasons_for_rejection.*,u.name as user_name');

        $data['rejection_reasons'] = $this->Model_CopyingReasonsForRejection->where(['is_active' => 'T'])->findAll();
        return view('Copying/reason_rejection_list_view', $data);
    }

    public function reason_rejection_add()
    {
        return view('Copying/reason_rejection_add_view');
    }

    public function reason_reject_save()
    {
        $reasons_deactive_id = $this->request->getPost('reasons_deactive_id');
        $md5_reason = urldecode($reasons_deactive_id);
        $dataArray = array(
            'is_active' => 'F',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );
        $supdated = $this->Model_CopyingReasonsForRejection->update($md5_reason, $dataArray);
        if ($supdated) {
            session()->setFlashdata("success_msg", 'Reasons Rejection Deleted Successfully!');
        } else {
            session()->setFlashdata("error", 'Reasons Rejection Not Deleted Successfully!');
        }
        echo $supdated;
    }

    public function reason_reject_insert()
    {
        $response = "";
        $reject_reasons = $this->request->getPost('reasons');

        $user_id = session()->get('login')['usercode'];

        if (intval($user_id) > 0) {
            if ($reject_reasons != '') {

                $this->Model_CopyingReasonsForRejection->select('id');

                $data['reason_already_existed'] = $this->Model_CopyingReasonsForRejection->where(['reasons' => $reject_reasons])->findAll(1);
               //  $query=$this->db->getLastQuery();
               //  echo (string) $query;exit();

                if (empty($data['reason_already_existed'])) {
                    $dataArray = array(
                        'is_active' => 'T',
                        'reasons' => $reject_reasons,
                        'user_id' => $user_id,
                        'entry_time' => date("Y-m-d H:i:s"),
                        'ip_address' => getClientIP(),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    );

                    $this->db = \Config\Database::connect();
                    $this->db->transStart();
                    $this->Model_CopyingReasonsForRejection->insert($dataArray);
                    $this->db->transComplete();
                    $response = '1';
                    session()->setFlashdata("success_msg", 'Reasons for Rejection inserted Successfully.');
                } else {
                    $response = "2";
                    session()->setFlashdata("error", 'Reasons Rejection Already Inserted');
                }
            } else {
                $response = "3";
                session()->setFlashdata("error", 'Reasons Mandatory*');
            }
        } else {
            $response = "4";
            session()->setFlashdata("error", 'User Not Found !');
        }
        echo $response;
    }

    public function user_role()
    {
        $this->Model_CopyingRole->join('master.users u', 'copying_role.role_assign_by = u.usercode', 'left');
        $this->Model_CopyingRole->select('copying_role.*,u.name as role_assined_by');

        $data['user_roles'] = $this->Model_CopyingRole->where(['status' => 'T'])->findAll();

        $arr_result = array();
        if (!empty($data['user_roles'])) {
            foreach ($data['user_roles'] as $row) {

                $id = $row['role_assign_to'];
                $application_type = $row['application_type_id'];
                $role_assign_to = $row['role_assign_to'];

                if (strstr($application_type, ",")) {
                    $all_application_types = explode(",", $application_type);
                } else {
                    $all_application_types = array($application_type);
                }


                $applicant_type_id = $row['applicant_type_id'];

                $pplicantTypeIds = explode(",", $applicant_type_id);

                $role_assign_by = $row['role_assined_by'];
                $from_date = $row['from_date'];
                $date = strtotime($from_date);
                $fromdate = date('d-m-Y H:i:s', $date);

                $users_data = is_data_from_table('master.users', ['usercode' => $role_assign_to], 'name', 'R');
                //  print_r($users_data);
                $application_data = '';
                // $row_data = is_data_from_table_whereIn('master.copy_category',"'id',$all_application_types_www",'description','R');
                $application_row_data = is_data_from_table_whereIn('master.copy_category', "id", $all_application_types, 'description');
                if (!empty($application_row_data)) {
                    foreach ($application_row_data as $row_data) {
                        $application_type_name = $row_data['description'] . ',';
                        if ($application_data == '') {
                            $application_data = $row_data['description'];
                        } else {
                            $application_data = $application_data . ',' . $row_data['description'];
                        }
                    }
                }


                $applicant_data = '';
                foreach ($pplicantTypeIds as $val) {
                    // echo $val.'<br>';
                    switch ($val) {
                        case 1:
                            $applicant_type_name = 'Advocate on Record';
                            break;
                        case 2:
                            $applicant_type_name = 'Party/Party-in-person';
                            break;
                        case 3:
                            $applicant_type_name = 'Appearing Counsel';
                            break;
                        case 4:
                            $applicant_type_name = 'Third Party';
                            break;
                        case 6:
                            $applicant_type_name = 'Authenticated By AOR';
                            break;
                    } //end of switch case..

                    if ($applicant_data == '') {
                        $applicant_data = $applicant_type_name;
                    } else {
                        $applicant_data = $applicant_data . ',' . $applicant_type_name;
                    }
                }
                array_push($arr_result, array(
                    "name" => $users_data['name'], "application_type" => $application_data, "applicant_type" => $applicant_data,
                    "role_assign_by" => $role_assign_by, "id" => $id, "from_date" => $fromdate
                ));
            }
        }
        $data['arr_result'] = $arr_result;
        return view('Copying/user_role_list_view', $data);
    }

    public function user_role_delete()
    {
        $usercode_deactive_id = $this->request->getPost('usercode_deactive');
        $md5_reason = urldecode($usercode_deactive_id);
        $dataArray = array(
            'status' => 'F',
            'to_date' => date("Y-m-d H:i:s"),
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );

        $supdated = update('master.copying_role', $dataArray, ['role_assign_to' => $usercode_deactive_id]);

        if ($supdated) {
            session()->setFlashdata("success_msg", 'Role Assigned Deleted Successfully!');
        } else {
            session()->setFlashdata("error", 'Role Assign Not Deleted!');
        }
        echo $supdated;
    }

    public function user_role_add()
    {
        $data['copy_category'] = get_from_table_json('copy_category');

        $user_type_section = session()->get('login')['section'];
        $user_id = session()->get('login')['usercode'];

        //echo $user_type_section;
        //print_r(session()->get('login'));

        if ($user_id == 1) {
            $data['users_data'] = is_data_from_table('master.users', ['display' => 'Y'], 'usercode,name');
        } else {
            $data['users_data'] = $this->Dropdown_list_model->get_copying_users($user_type_section);
        }
        //print_r($users_data);

        // print_r($data['copy_category']);
        return view('Copying/user_role_add_view', $data);
    }

    public function role_assign_add()
    {
        $response = "";
        $message = "";
        $application_type = $this->request->getPost('application_type');
        $applicant_type = $this->request->getPost('applicant_type');
        $role_assign = $this->request->getPost('role_assign');
        $applicantType = implode(",", $applicant_type);
        $applicationType = implode(",", $application_type);
        $user_id = session()->get('login')['usercode'];

        if (isset($user_id) != '') {
            if ($applicantType != '' && $role_assign != '') {
                $this->Model_CopyingRole->select('id');
                $data['role_already_assigned'] = $this->Model_CopyingRole->where(['role_assign_to' => $role_assign])->where(['status' => 'T'])->findAll();
                if (empty($data['role_already_assigned'])) {

                    $dataArray = array(
                        'status' => 'T',
                        'applicant_type_id' => $applicantType,
                        'role_assign_by' => $user_id,
                        'application_type_id' => $applicationType,
                        'role_assign_to' => $role_assign,
                        'from_date' => date("Y-m-d H:i:s"),
                        'ip_address' => getClientIP(),
                        'updated_on' => date("Y-m-d H:i:s"),
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                    );
                    $this->db = \Config\Database::connect();
                    $this->db->transStart();
                    $this->Model_CopyingRole->insert($dataArray);
                    $this->db->transComplete();
                    $response = '1';
                    $message = "Role Assign Successfully!";

                    session()->setFlashdata("success_msg", 'Role Assign Successfully!');
                } else {
                    $response = "2";
                    $message = "User Role Already Assigned !";
                    session()->setFlashdata("error", 'User Role Already Assigned !');
                }
            } else {
                $response = "3";
                $message = "All fields are Mandatory";
                session()->setFlashdata("error", 'All fields are Mandatory*');
            }
        } else {
            $response = "4";
            $message = "User Not Found !";
            session()->setFlashdata("error", 'User Not Found !');
        }
        echo $response;
    }
    public function application_search()
    {
        $usertype = session()->get('login')['usertype'];

        if (empty(session()->get('login'))) {
            exit;
        }
        if ($usertype == 50 || $usertype == 51 || $usertype == 17) {
        }

        $data['copy_category'] = get_from_table_json('copy_category');
        //print_r($data['copy_category']);
        //die;
        return view('Copying/application_search_view', $data);
    }

    public function get_application_search()
    {

        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $application_type = $this->request->getPost('application_type');
        $applicant_type = $this->request->getPost('applicant_type');
      


        $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
        $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');

        $this->Model_CopyingOrderIssuingApplicationNew->select('(case when m.reg_no_display is not null then m.reg_no_display else 
        ma.reg_no_display end) as reg_no, (case when m.c_status is not null then m.c_status else 
        ma.c_status end) as case_status, copying_order_issuing_application_new.*');

        $this->Model_CopyingOrderIssuingApplicationNew->where(['source' =>6]);

        if (!empty($application_type)) {
            $this->Model_CopyingOrderIssuingApplicationNew->whereIn('copy_category',$application_type);
        }

        if (!empty($applicant_type)) {
            $this->Model_CopyingOrderIssuingApplicationNew->whereIn('filed_by',$applicant_type);
        }
       // $this->Model_CopyingOrderIssuingApplicationNew->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

        $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.application_receipt) BETWEEN '$from_date' and '$to_date'");
        
        //die;
        $data['all_application_search'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
        //pr($this->Model_CopyingOrderIssuingApplicationNew->getLastQuery());
        $user_verification_details = [];

       // print_r($this->Model_CopyingOrderIssuingApplicationNew->getLastQuery()); 
      //  pr( $data['all_application_search'] );

        $main_array = array();
        if (is_array($data['all_application_search'])) {

           
            foreach ($data['all_application_search'] as $row) {
              
                $this->Model_CopyingApplicationDocuments->join('master.ref_order_type r', 'copying_application_documents.order_type = r.id', 'left');
                $this->Model_CopyingApplicationDocuments->select('r.order_type order_name, copying_application_documents.*');
                $this->Model_CopyingApplicationDocuments->where(['copying_order_issuing_application_id' => $row['id'], 'sent_to_applicant_on' => null]);

                $all_documents = $this->Model_CopyingApplicationDocuments->findAll();


                
                if (!empty($all_documents)) {
                    foreach ($all_documents as $docs) {
                        $main_array[$row['id']][] = $docs;
                    }
                }
                $all_data = array();
                if ($row['filed_by'] == 2 || $row['filed_by'] == 3 || $row['filed_by'] == 4) {
                    $all_data = $this->Copying_model->getUserVerficationDetails($row['mobile'], $row['email'], $row['diary']);
                }
                if (is_array($all_data)) {
                    foreach ($all_data as $all_user_ver_row) {
                        $user_verification_details[$row['id']][] = $all_user_ver_row;
                    }
                }
            }
        }

       // pr( $main_array);
        $data['all_docs_array'] = $main_array;
        //   print_r($user_verification_details);
        $data['user_verification_details'] = $user_verification_details;


        // $query=$this->db->getLastQuery();
        // echo (string) $query;exit();

        return view('Copying/application_search_get_view', $data);
    }
    public function bulk_status()
    {
        $this->Model_users->join('copying_order_issuing_application_new coian', 'users.usercode = coian.adm_updated_by');
        $this->Model_users->distinct();
        $this->Model_users->select('usercode,users.name,empid');
        $this->Model_users->whereNotIn('empid', [1, 3]);
        $data['all_copying_users'] = $this->Model_users->findAll();
        return view('Copying/bulkStatus_update_view', $data);
    }

    public function bulk_status_get_data()
    {
        $from_date = $this->request->getPost('from_date');
        $to_date = $this->request->getPost('to_date');
        $usercode = $this->request->getPost('userName');
        if (!empty($from_date) && !empty($to_date)) {

            if ($usercode != null && $from_date != '1970-01-01' && $to_date != '1970-01-01') {
                $this->Model_CopyingOrderIssuingApplicationNew->join('master.users u', 'u.usercode=copying_order_issuing_application_new.adm_updated_by', 'left');
                $this->Model_CopyingOrderIssuingApplicationNew->select("copy_category,application_reg_number,copying_order_issuing_application_new.id,application_number_display,diary,
                concat(copying_order_issuing_application_new.name,
                  case when filed_by=1 then ' (Adv)' else
                  case when filed_by=2 then ' (Party)' else case when filed_by=3 then ' (AC)' else 
                  case when filed_by=4 then ' (Other)' end end end end) as name,
                  date(application_receipt) as received_on,u.name as user,empid");

                $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.updated_on) BETWEEN '$from_date' and '$to_date'");
                $this->Model_CopyingOrderIssuingApplicationNew->where(['application_status' => 'P', 'source' => 1]);
                if (intval($usercode) > 0) {
                    $this->Model_CopyingOrderIssuingApplicationNew->where(['usercode' => $usercode]);
                    $data['user_detail'] = is_data_from_table('master.users', ['usercode' => $usercode], 'name, empid', 'R');
                }
                $data['from_date'] =  date('d-m-Y', strtotime($from_date));
                $data['to_date'] = date('d-m-Y', strtotime($to_date));

                $data['fromDate'] = date('Y-m-d', strtotime($from_date));
                $data['toDate'] = date('Y-m-d', strtotime($to_date));
                $this->Model_CopyingOrderIssuingApplicationNew->orderBy('date(application_receipt),copy_category,application_reg_number');

                $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();

            }
            return view('Copying/get_bulk_status_view', $data);
        }
    }


    public function bulk_status_index()
    {
        $this->Model_users->join('copying_order_issuing_application_new coian', 'users.usercode = coian.adm_updated_by');
        $this->Model_users->distinct();
        $this->Model_users->select('usercode,users.name,empid');
        $this->Model_users->whereNotIn('empid', [1, 3]);

        if (isset($_POST['view'])) {
            $data['user_detail']  = "";
            $from_date = $this->request->getPost('from_date');
            $to_date = $this->request->getPost('to_date');
            $usercode = $this->request->getPost('userName');
            if ($usercode != null && $from_date != '1970-01-01' && $to_date != '1970-01-01') {
                $this->Model_CopyingOrderIssuingApplicationNew->join('master.users u', 'u.usercode=copying_order_issuing_application_new.adm_updated_by', 'left');
                $this->Model_CopyingOrderIssuingApplicationNew->select("copy_category,application_reg_number,copying_order_issuing_application_new.id,application_number_display,diary,
                concat(copying_order_issuing_application_new.name,
                  case when filed_by=1 then ' (Adv)' else
                  case when filed_by=2 then ' (Party)' else case when filed_by=3 then ' (AC)' else 
                  case when filed_by=4 then ' (Other)' end end end end) as name,
                  date(application_receipt) as received_on,u.name as user,empid");

                $this->Model_CopyingOrderIssuingApplicationNew->where("DATE(copying_order_issuing_application_new.updated_on) BETWEEN '$from_date' and '$to_date'");
                $this->Model_CopyingOrderIssuingApplicationNew->where(['application_status' => 'P', 'source' => 1]);
                if (intval($usercode) > 0) {
                    $this->Model_CopyingOrderIssuingApplicationNew->where(['usercode' => $usercode]);
                    $data['user_detail'] = is_data_from_table('master.users', ['usercode' => $usercode], 'name, empid', 'R');
                }
                $data['from_date'] =  date('d-m-Y', strtotime($from_date));
                $data['to_date'] = date('d-m-Y', strtotime($to_date));

                $data['fromDate'] = date('Y-m-d', strtotime($from_date));
                $data['toDate'] = date('Y-m-d', strtotime($to_date));
                $this->Model_CopyingOrderIssuingApplicationNew->orderBy('date(application_receipt),copy_category,application_reg_number');


                $data['result'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();

                //   print_r($data['user_detail']);
                //   exit;

            }
        }

        $data['all_copying_users'] = $this->Model_users->findAll();
        // $query=$this->db->getLastQuery();
        // echo (string) $query;exit();
        return view('Copying/bulkStatus_update_view', $data);
        // print_r($all_copying_users);
    }

    function bulkStatusUpdate()
    {
        $idSelected = $this->request->getPost('idSelected');
        $all_ids = explode(",", $idSelected);
        $usercode = session()->get('login')['usercode'];
        $dataArray = array(
            'application_status' => 'R',
            'updated_on' => date("Y-m-d H:i:s"),
            'updated_by' => session()->get('login')['usercode'],
            'adm_updated_by' => session()->get('login')['usercode'],
            'updated_by_ip' => getClientIP(),
        );
        $this->db = \Config\Database::connect();
        $this->db->transStart();
        $supdated = updateIn('copying_order_issuing_application_new', $dataArray, 'id', $all_ids);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            session()->setFlashdata("error", 'Data not Updated!');
            return 'no';
        } else {
            $text = $this->bulk_status_get_data();
            return $text;
            session()->setFlashdata("success_msg", 'Data Updated Successfully');
        }
    }

    function application_status()
    {
        $data['copy_status'] = is_data_from_table('master.ref_copying_status', null, '*');
        if (!empty($this->request->getPost('category')))
            $app_no = trim($this->request->getPost('category')) . '-' . $this->request->getPost('app_no') . '/' . $this->request->getPost('year');
        $data['app_no']  = $data['category_view'] = $data['year'] = "";
        if (isset($app_no) &&  $this->request->getPost('app_no') != '') {
            $data['app_no'] = $this->request->getPost('app_no');
            $data['category_view'] = $this->request->getPost('category');
            $data['year'] = $this->request->getPost('year');

            $this->Model_CopyingOrderIssuingApplicationNew->join('main as m', 'm.diary_no = copying_order_issuing_application_new.diary', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('main_a as ma', 'ma.diary_no = copying_order_issuing_application_new.diary', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('master.users as u ', 'u.usercode=m.dacode', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->join('master.usersection as us ', 'us.id=u.section', 'left');
            // $this->Model_CopyingOrderIssuingApplicationNew->join('copying_application_documents as b', 'copying_order_issuing_application_new.id = b.copying_order_issuing_application_id', 'left');
            // $this->Model_CopyingOrderIssuingApplicationNew->join('copying_application_documents_a as ba ', 'copying_order_issuing_application_new.id = ba.copying_order_issuing_application_id', 'left');
            $this->Model_CopyingOrderIssuingApplicationNew->select("copying_order_issuing_application_new.*,
            ,(case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as reg_no_display,
            concat((case when m.reg_no_display is not null then m.pet_name else ma.pet_name end),' Vs ',
                (case when m.reg_no_display is not null then m.res_name else ma.res_name end)) as title , 
                (case when tentative_section(case when m.diary_no is null then ma.diary_no else m.diary_no end) is null 
                    then us.section_name 
                    else
                tentative_section(case when m.diary_no is null then ma.diary_no else m.diary_no end)  end) as section_name");
            $this->Model_CopyingOrderIssuingApplicationNew->where(['application_number_display' => $app_no]);
            
            $data['application_details'] = $this->Model_CopyingOrderIssuingApplicationNew->findAll();
            //echo $this->Model_CopyingOrderIssuingApplicationNew->getLastQuery();
            //die;
            if (!empty($data['application_details'])) {
                $application_details_id =  $data['application_details'][0]['id'];
                
                $data['app_documents'] = is_data_from_table('copying_application_documents', ['copying_order_issuing_application_id' => $application_details_id], '*');
                if (empty($data['app_documents'])) {
                    $data['app_documents'] = is_data_from_table('copying_application_documents_a', ['copying_order_issuing_application_id' => $application_details_id], '*');
                }
                
               
                $data['copying_order_issuing_application_id'] = $application_details_id;
                if (!empty($data['app_documents'])) {
                    
                    foreach ($data['app_documents'] as $app_Docs) {                     
                        $order_type = $app_Docs['order_type'];
                        $data_order_type = is_data_from_table('master.ref_order_type', ['id' => $order_type], 'order_type', 'R');
                        
                        $data_orderType[$order_type] = $data_order_type['order_type'];
                    }
                }

                
                $data['order_type_display'] = $data_orderType;
                $this->Model_CopyingTrap->join('master.users as u ', 'u.usercode=copying_trap.updated_by', 'left');
                $this->Model_CopyingTrap->join('master.ref_copying_status as prev ', 'prev.status_code = copying_trap.previous_value', 'left');

                $this->Model_CopyingTrap->join('master.ref_copying_status as new ', 'new.status_code=copying_trap.new_value', 'left');
                $this->Model_CopyingTrap->select('prev.status_description as prev,new.status_description as new,
                name,empid,copying_trap.updated_on');
                $this->Model_CopyingTrap->where(['copying_application_id' => $application_details_id]);

                $data['trap_list'] = $this->Model_CopyingTrap->findAll();
                //  $query=$this->db->getLastQuery();
                //  echo (string) $query;
               
                $query = $this->Model_CopyingApplicationDefects->select('ref_order_defect_id,remark')
                    ->where('defect_cure_date', null)
                    ->where('copying_order_issuing_application_id', "(SELECT id FROM copying_order_issuing_application_new WHERE application_number_display='$app_no')", false)
                    ->get();
                $data['show_defects'] = $query->getResultArray();
            }
            
            //print_r($data['application_details']);
        }


        $data['app_name'] = 'Application Status Update';
        $data['defects'] = is_data_from_table('master.ref_order_defect', null, '*');
        $data['copy_category'] = is_data_from_table('master.copy_category', null, '*');
        return view('Copying/application_status', $data);
    }

    public function application_status_update()
    {

        if ($this->request->getPost('or_defects')) {
            $defect = $this->request->getPost('or_defects');
            $fee_defecit = $this->request->getPost('fee_defecit');
            $remark = $this->request->getPost('remark');
            $feepay = $this->request->getPost('feePay');
        } else {
            $defect = array(0);
            $feepay = "";

        }

        $app_id = $this->request->getPost('app_id');
        if (!empty($app_id)) {
            $application_status = $this->request->getPost('application_status');

            if ($application_status == 'R') {
                $dataArray = array(
                    'application_status' => $application_status,
                    'ready_date' => date("Y-m-d H:i:s"),
                    'ready_remarks' => intval($feepay) ? $feepay : "NULL",
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'D') {
                $dataArray = array(
                    'application_status' => $application_status,
                    'dispatch_delivery_date' => date("Y-m-d H:i:s"),
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'C') {

                $dataArray = array(
                    'application_status' => $application_status,
                    'is_deleted' => 'f',
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else if ($application_status == 'F') {
                $defects_ids = implode(", ", $defect);
                $dataDefectsArray = array(
                    'defect_cure_date' =>  date("Y-m-d H:i:s"),
                    'defect_cured_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );

                $this->Model_CopyingApplicationDefects->set($dataDefectsArray)
                    ->where('copying_order_issuing_application_id', $app_id)
                    ->whereNotIn('ref_order_defect_id', explode(",", $defects_ids))
                    ->update();



                foreach ($defect as $all_defect) {

                    $query_def = $this->db->table('copying_application_defects')
                        ->select('ref_order_defect_id')
                        ->where('copying_order_issuing_application_id', $app_id)
                        ->where('ref_order_defect_id', $all_defect)
                        ->where('defect_cure_date IS NULL')
                        ->get();

                    //$ref_order_defect_id = is_data_from_table('copying_application_defects', ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect, 'defect_cure_date is' => null], 'ref_order_defect_id', 'R');
                    if ($query_def->getNumRows() >= 1) {
                        if ($all_defect == 1) {
                            $updateRemark = [
                                'remark' => !empty($fee_defecit) ? $fee_defecit : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $dupdated = update('copying_application_defects', $updateRemark, ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect]);
                        } else if ($all_defect == 12) {
                            $updateRemark = [
                                'remark' => !empty($remark) ? $remark : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                            $dupdated = update('copying_application_defects', $updateRemark, ['copying_order_issuing_application_id' => $app_id, 'ref_order_defect_id' => $all_defect]);
                        }
                    } else {
                        if ($all_defect == 1) {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'remark' => !empty($fee_defecit) ? $fee_defecit : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                        } else if ($all_defect == 12) {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'remark' => !empty($remark) ? $remark : "",
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                            ];
                        } else {
                            $insertDefectArray = [
                                'copying_order_issuing_application_id' => $app_id,
                                'ref_order_defect_id' => $all_defect,
                                'defect_notification_date' => date("Y-m-d H:i:s"),
                                'defect_notified_by' => session()->get('login')['usercode'],
                                'updated_on' => date("Y-m-d H:i:s"),
                                'updated_by' => session()->get('login')['usercode'],
                                'updated_by_ip' => getClientIP(),
                                'remark' => "",
                            ];
                        }
                        insert('copying_application_defects', $insertDefectArray);
                    }
                }
                $dataArray = array(
                    'application_status' => $application_status,
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            } else {
                $dataArray = array(
                    'application_status' => $application_status,
                    'adm_updated_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                $supdated = $this->Model_CopyingOrderIssuingApplicationNew->update($app_id, $dataArray);
            }
            if ($application_status != 'F') {
                $dataDefectsArray = array(
                    'defect_cure_date' =>  date("Y-m-d H:i:s"),
                    'defect_cured_by' => session()->get('login')['usercode'],
                    'updated_on' => date("Y-m-d H:i:s"),
                    'updated_by' => session()->get('login')['usercode'],
                    'updated_by_ip' => getClientIP(),
                );
                update('copying_application_defects', $dataDefectsArray, ['copying_order_issuing_application_id' => $app_id]);
            }


            $app_data = is_data_from_table('copying_order_issuing_application_new', ['id' => $app_id], '*', '');


//            if($this->request->getPost('application_status')=='F'){
//                $defect_data = is_data_from_table('copying_application_defects', ['copying_order_issuing_application_id' => $app_id], '*', '');
//                sendSMS($app_data['mobile'], "Following Defect(s) found in application No. ".$app_data['application_number_display']."- ".$defect_data['remark']." #Copying Branch. - Supreme Court of India", '1107161243443625778');
//             }
//             if($this->request->getPost('application_status')=='R'){
//                 sendSMS($app_data['mobile'], "Your application No. ".$app_data['application_number_display']." received in Copying Branch is ready for Dispatch. - Supreme Court of India", '1107161243451962063');
//             }
//             if($this->request->getPost('application_status')=='D'){
//                 sendSMS($app_data['mobile'], "Your application No. ".$app_data['application_number_display']." received in Copying Branch is Delivered. - Supreme Court of India", '1107161243456951452');
//             }

            if ($supdated) {
                session()->setFlashdata("success_msg", 'Data Updated Sucessfully');
            } else {
                session()->setFlashdata("error", 'Data not updated');
            }
            $this->response->redirect(site_url('/Copying/Copying/application_status'));
        } else {
            session()->setFlashdata("error", 'Data not updated');
            $this->response->redirect(site_url('/Copying/Copying/application_status'));
        }
    }

    function send_sms($mobile, $message, $templateID)
    {
        $otp_url = "http://10.25.78.5/eAdminSCI/a-push-sms-gw?mobileNos=$mobile&message=" . rawurlencode($message) . "&typeId=29&myUserId=NIC001001&myAccessId=root&templateId=$templateID";
        $otp_res = (array)json_decode(file_get_contents($otp_url));
    }


    function specimen_signature()
    {
        $data['app_name'] = "Specimen Signature";
       $aor_code = $this->request->getPost('aor_code');
        $data['aor_code'] = "";
        if (!empty($aor_code) && $aor_code != '')
            $data['aor_code'] = $aor_code;
        return view('Copying/specimen_signature', $data);
    }

    public function dashboard()
    {
        return view('Copying/dashboard_view');
    }
    public function dashboard_count()
    {
        $from_date = date("Y-m-d", strtotime($this->request->getPost('from_date')));
        $to_date = date("Y-m-d", strtotime($this->request->getPost('to_date')));
        $data['offline_copy_pending']= $data['offline_copy_disposed']= $data['offline_total_filed']=$data['e_copy_pending']=  $data['e_copy_disposed'] =  $data['total_filed'] =0;
        $data['e_copy_request_pending'] = $data['e_copy_request_disposed'] = $data['total_request'] = $data['e_copy_verify_pending']= $data['e_copy_verify_disposed']=0;
        $data['total_verify']=$data['e_copy_request_pending_at_copying']=$data['e_copy_request_pending_at_judicial']=0;

        $query = $this->Model_CopyingOrderIssuingApplicationNew;
        $query->select([
            'SUM(CASE WHEN application_status NOT IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_pending',
            'SUM(CASE WHEN application_status IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_disposed',
        ]);
        $query->where('source !=', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result = $query->get();
        // echo $this->db->getLastQuery();

        if ($result->getNumRows() >= 1) {
            $resultSet1 = $result->getResultArray();
            $data['offline_copy_pending'] = $resultSet1[0]['e_copy_pending'];
            $data['offline_copy_disposed'] = $resultSet1[0]['e_copy_disposed'];
            $data['offline_total_filed'] = $data['offline_copy_pending'] + $data['offline_copy_disposed'];
        }


        $query_pending = $this->Model_CopyingOrderIssuingApplicationNew;
        $query_pending->select([
            'SUM(CASE WHEN application_status NOT IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_pending',
            'SUM(CASE WHEN application_status IN (\'F\', \'R\', \'D\', \'C\', \'W\') THEN 1 ELSE 0 END) AS e_copy_disposed',
        ]);
        $query_pending->where('source', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result_pending = $query_pending->get();

        if ($result_pending->getNumRows() >= 1) {
            $resultSet2 = $result_pending->getResultArray();
            $data['e_copy_pending'] = $resultSet2[0]['e_copy_pending'];
            $data['e_copy_disposed'] = $resultSet2[0]['e_copy_disposed'];
            $data['total_filed'] = $data['e_copy_pending'] + $data['e_copy_disposed'];
        }

        $query3 = $this->Model_CopyingRequestVerify;
        $query3->select([
            "sum(case when allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending",

            "sum(case when send_to_section = 'f' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending_at_copying",
            "sum(case when send_to_section = 't' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_pending_at_judicial",

            "sum(case when allowed_request = 'request_to_available' and application_status in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_request_disposed",

            "sum(case when allowed_request != 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_verify_pending",
            "sum(case when allowed_request != 'request_to_available' and application_status in ('F', 'R', 'D', 'C', 'W') then 1 else 0 end) e_copy_verify_disposed"
        ]);
        $query3->where('source', 6)
            ->where('is_deleted', '0')
            ->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result3 = $query3->get();
        // echo $this->db->getLastQuery();
        // exit;
        if ($result3->getNumRows() >= 1) {
            $resultSet3 = $result3->getResultArray();
            $data['e_copy_request_pending'] = $resultSet3[0]['e_copy_request_pending'];
            $data['e_copy_request_disposed'] = $resultSet3[0]['e_copy_request_disposed'];
            $data['total_request'] = $data['e_copy_request_pending'] + $data['e_copy_request_disposed'];

            $data['e_copy_verify_pending'] =  $resultSet3[0]['e_copy_verify_pending'];
            $data['e_copy_verify_disposed'] =  $resultSet3[0]['e_copy_verify_disposed'];
            $data['total_verify'] = $data['e_copy_verify_pending'] + $data['e_copy_verify_disposed'];

            $data['e_copy_request_pending_at_copying'] =  $resultSet3[0]['e_copy_request_pending_at_copying'];
            $data['e_copy_request_pending_at_judicial'] =  $resultSet3[0]['e_copy_request_pending_at_judicial'];

        }

        return view('Copying/dashboard_count_view',$data    );
    }

    public function dashboard_details(){
        $from_date = date("Y-m-d", strtotime($this->request->getPost('from_date')));
        $to_date = date("Y-m-d", strtotime($this->request->getPost('to_date')));
        $flag=$this->request->getPost('flag');
        if($flag == 'offline_total_applications' OR $flag == 'offline_pending_applications' OR $flag == 'offline_disposed_applications') {
            $table = $this->Model_CopyingOrderIssuingApplicationNew;

            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_order_issuing_application_new.crn, copying_order_issuing_application_new.filed_by, copying_order_issuing_application_new.name, copying_order_issuing_application_new.application_number_display,
            copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.adm_updated_by, 
            copying_order_issuing_application_new.updated_on, copying_order_issuing_application_new.send_to_section");

            $table->join('main m', 'm.diary_no=copying_order_issuing_application_new.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_order_issuing_application_new.diary', 'left');


            if($flag == 'offline_pending_applications'){
                $table->where('source !=', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Offline Mode and Pending";
            }
            else if($flag == 'offline_disposed_applications'){
                $table->where('source !=', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Offline Mode and Disposed";
            }
            else{
                $table->where('source !=', 6);
                $heading = "Applications Received Through Offline Mode";
            }

        }else if($flag == 'total_applications' OR $flag == 'pending_applications' OR $flag == 'disposed_applications') {
            $table = $this->Model_CopyingOrderIssuingApplicationNew;
            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_order_issuing_application_new.crn, copying_order_issuing_application_new.filed_by, copying_order_issuing_application_new.name, copying_order_issuing_application_new.application_number_display,
            copying_order_issuing_application_new.application_receipt, copying_order_issuing_application_new.application_status, copying_order_issuing_application_new.adm_updated_by, 
            copying_order_issuing_application_new.updated_on, copying_order_issuing_application_new.send_to_section");

            $table->join('main m', 'm.diary_no=copying_order_issuing_application_new.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_order_issuing_application_new.diary', 'left');

            if($flag == 'pending_applications'){
                $table->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Online Mode and Pending";
            }
            else if($flag == 'disposed_applications'){
                $table->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);
                $heading = "Applications Received Through Online Mode and Disposed";
            }
            else{
                $table->where('source', 6);
                $heading = "Applications Received Through Online Mode";
            }
        }else if($flag == 'total_request' OR $flag == 'pending_request' OR $flag == 'disposed_request' OR $flag == 'request_pending_copying' OR $flag == 'request_pending_judicial' OR $flag == 'request_pending_record_room') {

            $table = $this->Model_CopyingRequestVerify;

            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_request_verify.crn, copying_request_verify.filed_by, copying_request_verify.name, copying_request_verify.application_number_display,
            copying_request_verify.application_receipt, copying_request_verify.application_status copying_request_verify.send_to_section");

            $table->join('main m', 'm.diary_no=copying_request_verify.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_request_verify.diary', 'left');

            if($flag == 'request_pending_copying'){
                $table->where('send_to_section', 'f')
                    ->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending in Copying Section";
            }
            else if($flag == 'request_pending_judicial'){
                $table->where('send_to_section', 't')
                    ->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending in Judicial Sections";
            }
            /*else if($flag == 'request_pending_record_room'){
                $sub_query = "and send_to_section = 'r' and allowed_request = 'request_to_available' and application_status not in ('F', 'R', 'D', 'C', 'W')  and a.source = 6";
                $heading = "Document Request Received Through Online Mode And Pending in Record Room";
            }*/
            else if($flag == 'pending_request'){
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Pending";
            }
            else if($flag == 'disposed_request'){
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Document Request Received Through Online Mode And Completed";
            }
            else{
                $table->where('allowed_request', 'request_to_available')
                    ->where('source', 6);

                $heading = "Document Request Received Through Online Mode";
            }
        }else if($flag == 'total_verify' OR $flag == 'pending_verify' OR $flag == 'disposed_verify') {
            $table = $this->Model_CopyingRequestVerify;
            $table->select("'section_name', 
            (case when m.reg_no_display is not null then m.reg_no_display else ma.reg_no_display end) as 
            reg_no_display, (case when m.diary_no is not null then m.diary_no else ma.diary_no end) as diary_no, 
            copying_request_verify.crn, copying_request_verify.filed_by, copying_request_verify.name, copying_request_verify.application_number_display,
            copying_request_verify.application_receipt, copying_request_verify.application_status, copying_request_verify.adm_updated_by, 
            copying_request_verify.updated_on, copying_request_verify.send_to_section");

            $table->join('main m', 'm.diary_no=copying_request_verify.diary', 'left');
            $table->join('main_a ma', 'ma.diary_no=copying_request_verify.diary', 'left');

            if($flag == 'pending_verify'){

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6)
                    ->whereNotIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Verification Request Received Through Online Mode And Pending";
            }
            else if($flag == 'disposed_verify'){

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6)
                    ->whereIn('application_status', ['F', 'R', 'D', 'C', 'W']);

                $heading = "Verification Request Received Through Online Mode And Completed";
            }
            else{

                $table->where('allowed_request!=', 'request_to_available')
                    ->where('source', 6);
                $heading = "Verification Request Received Through Online Mode";
            }
        }

        $table->where('is_deleted','0');
        $table->where("date(application_receipt) BETWEEN '$from_date' AND '$to_date'");

        $result = $table->get();
        // echo $this->db->getLastQuery();
        // exit;

        if ($result->getNumRows() >= 1) {
            $data['all_records'] = $result->getResultArray();
        }

        $heading .= " (Date ".date("d-m-Y", strtotime($this->request->getPost('from_date')))." to ".date("d-m-Y", strtotime($this->request->getPost('to_date'))).")";

        $data['heading'] = $heading;
        return view('Copying/dashboard_details_view',$data);
    }

    public function application(){
        $data['app_name'] = 'Add Application';
        $data['copy_category'] = is_data_from_table('master.copy_category', null, '*');
        $data['order_type'] = is_data_from_table('master.ref_order_type', null, '*');

        $query = $this->db->table('master.casetype');
        $query->select('*');
        $query->where('is_deleted', 'f');
        $query->where('casecode != 9999');
        $query->orderBy('casecode');
        $result = $query->get();
        if ($result->getNumRows() >= 1) {
            $data['case_types'] = $result->getResultArray();
        }
       // $data['case_types'] =  is_data_from_table('master.casetype', ['is_deleted'=>'f','casecode!='=>'9999'], '*');
        $data['case_status']= is_data_from_table('master.ref_copying_status', null, '*');
        $data['case_source']= is_data_from_table('master.ref_copying_source', null, '*');
        //var_dump($data['case_source']);
        return view('Copying/add_application_view',$data);
    }

    public function get_diary(){

        $case_type= $this->request->getPost('case_type');
        $case_number= $this->request->getPost('case_number');
        $case_year= $this->request->getPost('case_year');

        $diary_details = get_diary_case_type($case_type,$case_number,$case_year);
        $diary_no = "";
        if(!empty($diary_details)){
            $diary_no = $diary_details;
        }
        echo $diary_no;
    }

    public function previous_applies(){
        $diary_number= $this->request->getPost('diary_number');
        $diary_year= $this->request->getPost('diary_year');

        $d_no = $diary_number.$diary_year;

        $query = $this->db->table('copying_order_issuing_application_new coian');
        $query->select('coian.id, application_number_display, coian.court_fee, CONCAT(coian.name, CASE WHEN filed_by=1 THEN \' (Adv)\' WHEN filed_by=2 THEN \' (Party)\' WHEN filed_by=3 THEN \' (AC)\' WHEN filed_by=4 THEN \' (Other)\' END) AS name, rcs.status_description AS status, application_receipt AS received_on');
        $query->join('master.ref_copying_status rcs', 'coian.application_status = rcs.status_code', 'LEFT OUTER');
        $query->where('diary', $d_no);
        $query->whereNotIn('application_status', ['D', 'C', 'W']);
        $result = $query->get();
        if ($result->getNumRows() >= 1) {
            $previous_applies = $result->getResultArray();
            echo json_encode($previous_applies,JSON_UNESCAPED_SLASHES);
        }else{
            echo "0";
        }

    }

    public function contact_detail(){
        $selected_val= $this->request->getPost('selected_val');
        $applied_by= $this->request->getPost('applied_by');
        $diary_no= $this->request->getPost('diary_no');
        if($applied_by == 2){
            $selected_val = substr($selected_val,0,-3);

            $diary_details = is_data_from_table('main',['diary_no'=>$diary_no],'*','R');
            $table_alias = '';
            if(empty($diary_details)){
                $table_alias = '_a';
            }

            $query = $this->db->table('party'.$table_alias.' party')
                ->select('partyname as name, contact as mobile, email, CONCAT(addr1, \',\', addr2, \', \', TRIM(city.name), \', \', TRIM(state.name), \',\', pin) as caddress')
                ->join('master.state state', '(state.id_no = party.state::bigint)', 'left')
                ->join('master.state city', '(city.id_no = party.city::bigint)', 'left')
                ->where('diary_no', $diary_no)
                ->like('partyname', $selected_val )
                ->get();
            $result = $query->getResultArray();
            return $result[0]['name'].'|'.$result[0]['mobile'].'|'.$result[0]['caddress'];
        }

        if($applied_by == 1){
            $contact_details = is_data_from_table('master.bar',['aor_code'=>$selected_val],'*','R');
            return $contact_details['name'].'|'.$contact_details['mobile'].'|'.$contact_details['caddress'];
        }

    }


    public function advocate_or_party_details(){
        $diary_number= trim($this->request->getPost('diary_number'));
        $filed = trim($this->request->getPost('filed'));
        $diary_year = trim($this->request->getPost('diary_year'));
        $diary_no = trim($diary_number.$diary_year);

        $diary_details = is_data_from_table('main',['diary_no'=>$diary_no],'*','R');
        $table_alias = '';
        if(empty($diary_details)){
            $table_alias = '_a';
        }

        $union = $this->db->table('advocate'.$table_alias.' adv')->select("(bar.aor_code::text) as code, concat(bar.name,'(',adv.pet_res,')') as name ,1 as type")->join('master.bar bar','adv.advocate_id=bar.bar_id')->where('diary_no', $diary_no);
        $builder = $this->db->table('party'.$table_alias.' party')->select("concat(partyname,'(',pet_res,')') as code, concat(partyname,'(',pet_res,')') as name,2 as type")->where('diary_no', $diary_no)->union($union);
        $finalQuery = $this->db->newQuery()->select("*,(select CASE WHEN tentative_section(m.diary_no) IS NULL THEN us.section_name ELSE tentative_section(m.diary_no) END from main".$table_alias." m join master.users u on m.dacode=u.usercode join master.usersection us on us.id=u.section where diary_no=$diary_no) as sec")->fromSubquery($builder, 'a')->where('a.type',$filed)->get();

//        echo $finalQuery->getCompiledSelect();
//            exit;

        //
        $dropdownOptions = $section="";
        if ($finalQuery->getNumRows() >= 1) {
            $app_result = $finalQuery->getResultArray();
            $dropdownOptions = '<option value="">SELECT</option>';
            if(is_array($app_result)){
                foreach ($app_result as $data) {
                    $section = $data['sec'];
                    $dropdownOptions .= '<option value="' . sanitize($data['code']) . '">' . sanitize(strtoupper($data['name'])) . '</option>';
                }

            }

        }
        $dropdownOptions .= '<option value="0">OTHER</option>';
        echo $dropdownOptions.'|'.$section;
    }

    public function add_new_application(){
        
        $category = $this->request->getPost('category');
        if(!session()->get('login')['usercode']){
            $response = '0';
            echo $response;
            session()->setFlashdata("error_msg", 'Please login');
            $this->response->redirect(site_url('/login'));
        }

        $diary_number = $this->request->getPost('diary_number');
        $diary_year = $this->request->getPost('diary_year');
        $deliver_mode = $this->request->getPost('deliver_mode');
        $advocate_or_party = $this->request->getPost('advocate_or_party');
        $court_fee = $this->request->getPost('court_fee');
        $order_type = $this->request->getPost('order_type');
        $mobile =$this->request->getPost('mobile');
        if(isset($diary_number, $diary_year, $category, $deliver_mode, $advocate_or_party, $court_fee, $order_type)){



            $builder = $this->db->table('copying_order_issuing_application_new')->select("max(application_reg_number )+1 as app_no,(select code from master.copy_category where id='$category') as code")
                ->where('copy_category', $category)
                ->where('application_reg_year', 'EXTRACT(YEAR FROM CURRENT_DATE)', false);

            $finalQuery = $this->db->newQuery()->select("concat(code,'-',(case when app_no is null then 1 else app_no end),'/',EXTRACT(YEAR FROM CURRENT_DATE)) as application_no_display, (case when app_no is null then 1 else app_no end) as app_no")->fromSubquery($builder, 'a')->get();

            if ($finalQuery->getNumRows() >= 1) {
                $app_result = $finalQuery->getResultArray();
            }
            
            $application_reg_number = $app_result[0]['app_no'];
            $application_number_display = $app_result[0]['application_no_display'];
            $application_reg_year = date('Y');
            
            $dataArray = array(
                'diary' => $this->request->getPost('diary_number').''.$this->request->getPost('diary_year'),
                'copy_category' =>$this->request->getPost('category'),
                'application_receipt' => date('Y-m-d H:i:s'),
                'advocate_or_party' => $this->request->getPost('advocate_or_party'),
                'court_fee' => $this->request->getPost('court_fee'),
                'delivery_mode' => $this->request->getPost('deliver_mode'),
                'adm_updated_by' => session()->get('login')['usercode'],
                'updated_by' => session()->get('login')['usercode'],
                'updated_by_ip' => getClientIP(),
                'updated_on' => date('Y-m-d H:i:s'),
                'application_reg_number' => $app_result[0]['app_no']==NULL? '1': $app_result[0]['app_no'],
                // 'application_reg_number' => $app_result[0]['app_no'],
                'application_reg_year'=>date('Y'),
                'filed_by'  =>$this->request->getPost('filed'),
                'name' => !empty($this->request->getPost('name'))?$this->request->getPost('name'):'',
                'mobile'=> !empty($this->request->getPost('mobile'))?$this->request->getPost('mobile'):'',
                'address'=> !empty($this->request->getPost('address'))?$this->request->getPost('address'):'',
                'remarks'=> !empty($this->request->getPost('remarks'))?$this->request->getPost('remarks'):'',
                'source'  => $this->request->getPost('case_source'),
                'send_to_section'=> !empty($this->request->getPost('send_section'))?$this->request->getPost('send_section'):'f',
                'application_status'=>$this->request->getPost('send_section')=='t'?'A':'P',
                'application_number_display'=>$app_result[0]['application_no_display']
            );

            $this->db = \Config\Database::connect();
            $this->db->transStart();

            $isInserted = insert('copying_order_issuing_application_new',$dataArray);
            //echo "hi";
            //die;
            if($isInserted){

                $query = $this->db->table('copying_order_issuing_application_new');
                $query->select('id');
                $query->where('copy_category', $category);
                $query->where('application_reg_number', $application_reg_number);
                $query->where('application_reg_year', $application_reg_year);
                $query->orderBy('id DESC');
                $query->limit('1');

                $result = $query->get();
                if ($result->getNumRows() >= 1) {
                    $dataId = $result->getResultArray();
                    $inserted_application_id = $dataId[0]['id'];
                }


                $orderDate = $this->request->getPost('orderDate');
                $copies = $this->request->getPost('copies');

                for($i=0; $i < sizeof($order_type); $i++){
                    $tempArray = array(
                        'order_type'    =>  $order_type[$i],
                        'order_date'     => !empty($orderDate[$i])? date('Y-m-d', strtotime($orderDate[$i])) : NULL,
                        'number_of_copies'=> $copies[$i],
                        'copying_order_issuing_application_id'=>$inserted_application_id,
                        'number_of_pages_in_pdf' => 0,
                        'updated_by' => session()->get('login')['usercode'],
                        'updated_by_ip' => getClientIP(),
                        'updated_on' => date('Y-m-d H:i:s'),
                    );
                    $this->Model_CopyingApplicationDocuments->insert($tempArray);
                }

            }

            $this->db->transComplete();
            sendSMS($mobile, "Your application received in Copying Branch has been registered with application number ".$application_number_display.". - Supreme Court of India", '1107161243437551558');

            $response = '1';
            echo $response;
            session()->setFlashdata("success_msg", 'Application Inserted Sucessfully with application number - '.$application_number_display);

            return redirect()->to('/Copying/Copying/application');

            // $this->response->redirect(site_url('/Copying/Copying/application'));

        }else{
            $response = '0';
            echo $response;
            session()->setFlashdata("error_msg", 'Please fill all the fields ');
            return $this->response->redirect(site_url('/Copying/Copying/application'));
        }

    }

    public function request_search()
    {

        //$data['dcmis_multi_section_id'] = session()->get('login')['section'];
        $data['dcmis_user_idd'] = session()->get('login')['usercode'];
        $data['icmic_empid'] = session()->get('login')['empid'];
        $data['dcmis_usertype'] = session()->get('login')['usertype'];
        $data['dcmis_section'] = session()->get('login')['section'];
       $lognDetail =$this->LoginModel->get_multi_section($data['icmic_empid']);
       if (!session()->has('dcmis_multi_section_id')) {
        session()->set('dcmis_multi_section_id', []);
        }
    
    if (!session()->has('dcmis_multi_section_name')) {
        session()->set('dcmis_multi_section_name', []);
    }
    foreach ($lognDetail as $row_multi_sec) {
        // Get the session arrays (which are already initialized)
        $section_ids = session()->get('dcmis_multi_section_id');
        $section_names = session()->get('dcmis_multi_section_name');
    
        // Add new items to the session arrays
        $section_ids[] = $row_multi_sec['id'];
        $section_names[] = $row_multi_sec['section_name'];
    
        // Save the updated arrays back to the session
        session()->set('dcmis_multi_section_id', $section_ids);
        session()->set('dcmis_multi_section_name', $section_names);
    }
    $data['dcmis_multi_section_id'] = session()->get('login')['section'];


        $sql = "SELECT us.section_name, us.isda
        FROM master.user_sec_map usm
        INNER JOIN master.usersection us ON us.id = usm.usec
        WHERE usm.empid = ? 
        AND usm.display = 'Y'
        AND us.isda = 'Y'";

        // Execute the query with the prepared statement
        $query = $this->db->query($sql, [$data['icmic_empid']]);

        // Check if rows exist
        if ($query->getNumRows() > 0) {
            // Fetch the row as an associative array
            $row_sn = $query->getRowArray();
            $data['isda'] = $row_sn['isda'];
        } else {
            $data['isda'] = 'N';
        }

        //if(in_array_any( [61], $_SESSION['dcmis_multi_section_id']) OR $isda == 'Y'){
            if(!in_array_any([10], $data['dcmis_multi_section_id']) && $data['dcmis_user_idd'] != 1){
                $data['ignore_applicant_type'] = 'Y';
            }
            else{
                $data['ignore_applicant_type'] = 'N';
            }


        if(in_array_any( [10], $data['dcmis_multi_section_id']) || $data['dcmis_user_idd'] == 1) {
            if($data['dcmis_usertype'] == 50 OR $data['dcmis_usertype'] == 51 OR $data['dcmis_usertype'] == 17){
               // Prepare the raw SQL query using a parameter for binding
                $sql = "SELECT applicant_type_id, application_type_id 
                FROM copying_role 
                WHERE status = 'T' 
                AND role_assign_to = ? 
                AND to_date = '0000-00-00 00:00:00'";

                // Execute the query with binding the user ID
                $query = $this->db->query($sql, [$data['dcmis_user_idd']]);

                // Check if there are any results
                if ($query->getNumRows() > 0) {
                // Fetch the result as an associative array
                $data_role = $query->getRowArray();

                // Split the result fields into arrays
                $applicant_type_array = explode(",", $data_role['applicant_type_id']);
                $application_type_array = explode(",", $data_role['application_type_id']);
                } else {
                // If no role found, output a message and stop execution
                echo "You don't have permission";
                exit();
                }
            }
        }



        if (!empty( $data['dcmis_section']) && ( $data['dcmis_section'] == 10 ||  $data['dcmis_section'] == 1)) {
            // First query if section is 10 or 1
            $sql_section = "SELECT id, section_name, display, isda 
                            FROM master.usersection 
                            WHERE display = 'Y' 
                            ORDER BY CASE WHEN id IN (10, 61) THEN 1 ELSE 999 END ASC, 
                                     CASE WHEN isda = 'Y' THEN 2 ELSE 999 END ASC, 
                                     section_name ASC";
        } else {
            // Otherwise, restrict by the specific section id
            $sql_section = "SELECT id, section_name, display, isda 
                            FROM master.usersection 
                            WHERE display = 'Y' 
                            AND id = ?";
        }
        
        // Execute the query, binding $dcmis_section only if needed
        $query = ( $data['dcmis_section'] == 10 ||  $data['dcmis_section']== 1) 
                 ? $this->db->query($sql_section) 
                 : $this->db->query($sql_section, [ $data['dcmis_section']]);
        
        // Get the number of results
        $section_count = $query->getNumRows();
        
        if ($section_count > 0) {
            // Fetch results as an array of objects
            $section_list = $query->getResultObject();
        } else {
            $section_list = []; // No results found
        }
        
        // If necessary, handle the case where no sections are returned
        $d_none_class = empty($section_list) ? "d-none" : "";
        
        //var_dump($_SESSION);
        return view('Copying/request/request_search',$data);
    }
    public function request_search_get(){
        $data['requests'] = $this->copyRequestModel->getRequests($this->request);
        $data['srno'] = 1;
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/request_search_get',$data);   
    }
    public function request_accept_save()
    {
        
        $requestAccepted=$this->copyRequestModel->request_accept_save($this->request);
        if($requestAccepted){
        echo 1;
        }else{
            echo "Error: Not Accepted / Already Uploaded";
        }
    }
    public function request_reject_save()
    {
        
        if($this->copyRequestModel->request_reject_save()){
         echo 1;
        } else {
            echo "Error: Data not uploaded or already uploaded";
        }
    }
    public function request_send_to_section(){
        
        if ($this->copyRequestModel->request_send_to_section()) {
            echo 1;
        } else {
            echo "Session Expired";
        }
    }
    
    public function request_upload_save()
    {
        $session = session();
        $ucode = $session->get('dcmis_user_idd');
        $new_name = "";

        $allowedExts = ['pdf', 'zip'];
        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return $this->response->setJSON(['error' => 'File upload error: ' . $file->getError()]);
        }

        if ($file->getSize() > 200100000) {
            return $this->response->setJSON(['error' => 'Not more than 20 MB allowed']);
        }

        $extension = strtolower($file->getExtension());
        if (!in_array($extension, $allowedExts)) {
            return $this->response->setJSON(['error' => 'Only PDF/ZIP files allowed']);
        }

        $master_to_path = "../copy_verify/unavailble_documents_request/" . $this->request->getVar('crn') . "/";
        if (!is_dir($master_to_path)) {
            mkdir($master_to_path, 0777, true);
        }

        $new_name = md5(uniqid(rand(), true)) . '.' . $extension;
        if (file_exists($master_to_path . $new_name)) {
            return $this->response->setJSON(['error' => 'Sorry, file already exists.']);
        }

        if ($file->move($master_to_path, $new_name)) {
            $db = \Config\Database::connect();
            $builder = $db->table('copying_request_verify_documents');

            $data = [
                'path' => 'copy_verify/unavailble_documents_request/' . $this->request->getVar('crn') . '/' . $new_name,
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $ucode,
            ];

            if (!in_array_any([10], $session->get('dcmis_multi_section_id'))) {
                $data['current_section'] = '10';
            }

            $builder->where('request_status', 'P')
                ->where('id', $this->request->getVar('application_id'))
                ->update($data);

            if ($db->affectedRows() > 0) {
                if (!in_array_any([10], $session->get('dcmis_multi_section_id'))) {
                    $movementData = [
                        'copying_request_verify_documents_id' => $this->request->getVar('application_id'),
                        'from_section' => $session->get('dcmis_section'),
                        'from_section_sent_by' => $ucode,
                        'from_section_sent_on' => date('Y-m-d H:i:s'),
                        'to_section' => 10,
                        'remark' => $this->request->getVar('section_remark'),
                    ];

                    $db->table('copying_request_movement')->insert($movementData);

                    $updateRequestData = [
                        'send_to_section' => 'f',
                        'updated_on' => date('Y-m-d H:i:s'),
                        'adm_updated_by' => $ucode,
                    ];

                    $db->table('copying_request_verify')
                        ->where('application_status', 'P')
                        ->where('crn', $this->request->getVar('crn'))
                        ->update($updateRequestData);
                }

                return $this->response->setJSON(['status' =>1]);
            } else {
                return $this->response->setJSON(['status' =>0,'error' => 'Error: Data not uploaded or already uploaded']);
            }
        } else {
            return $this->response->setJSON(['status' =>0,'error' => 'Not Allowed, Check Permission']);
        }
    }
     
    public function request_fee_clc_for_certification_save(){
        //$ip = $this->getClientIP();
        $session=session();
    
        $form_status = $this->request->getPost('fee_clc_request_form_status');
        $fee_clc_request_status = $this->request->getPost('fee_clc_request_status');
        $application_id = $this->request->getPost('fee_clc_application_id');
        if (!empty($form_status) && ($form_status == 'Save' || $form_status == 'Modify')) {
            $total_path_pages = $this->request->getPost('total_path_pages');
            $fee_clc_for_certification_no_doc = $this->request->getPost('certification_no_doc');
            $fee_clc_for_certification_pages = $this->request->getPost('certification_pages');
            $fee_clc_for_uncertification_no_doc = $this->request->getPost('uncertification_no_doc');
            $fee_clc_for_uncertification_pages = $this->request->getPost('uncertification_pages');
            $total_fee_clc_for_no_doc_pages = $fee_clc_for_certification_pages + $fee_clc_for_uncertification_pages;
    
            if ($total_path_pages == $total_fee_clc_for_no_doc_pages) {
                $data = [
                    'fee_clc_for_certification_no_doc' => $fee_clc_for_certification_no_doc,
                    'fee_clc_for_certification_pages' => $fee_clc_for_certification_pages,
                    'fee_clc_for_uncertification_no_doc' => $fee_clc_for_uncertification_no_doc,
                    'fee_clc_for_uncertification_pages' => $fee_clc_for_uncertification_pages,
                    'fee_clc_created_ip' => getClientIP(),
                    'fee_clc_created_on' => date('Y-m-d H:i:s'),
                ];
    
                if ($form_status == 'Modify') {
                    $data['fee_clc_updated_by'] = $session->get('dcmis_user_idd');
                    $data['fee_clc_updated_on'] = date('Y-m-d H:i:s');
                    $data['fee_clc_updated_ip'] =getClientIP();
                } else {
                    $data['fee_clc_creaded_by'] = $session->get('dcmis_user_idd');
                }
                if ($this->copyRequestModel->request_fee_clc_for_certification_save($application_id,$data)>0) {
                    return $this->response->setJSON(['status' => 1]);
                } else {
                    return $this->response->setJSON(['status' => 99]);
                }
            } else {
                return $this->response->setJSON(['status' => 2]);
            }
        } else if (!empty($form_status) && $form_status == 'display') {
            $data=$this->copyRequestModel->getrequestedCertification($application_id);
            //$data = $model->where('request_status', 'P')->find($application_id);
    
            if (!empty($data)) {
                // Process the data as needed
                // For example, you can return it to a view or JSON
                return view('Copying/request/request_fee_clc_for_certification_save_view',$data);
            }
        } 
    }
    
    public function request_action_modal(){
        $data['sql_role']=$this->copyRequestModel->copying_reasons_for_rejection();
        $data['sec_list']=$this->copyRequestModel->get_sections();
        $data['section_id']=$this->copyRequestModel->getSectionByDiaryNo()['section_id'];
        $data['tentative_section_name']=$this->copyRequestModel->getSectionByDiaryNo()['tentative_section_name'];
    return view('Copying/request/request_action_modal_view',$data);   
    }
    public function request_action_modal_full_screen(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/request_action_modal_full_screen_view',$data);       
    }
    public function party_details_get(){ //Done
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/party_details_get_view',$data);
    }
    public function get_aadhaar_offline_details(){   //Done
        $data=array();   
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/get_aadhaar_offline_details_view',$data);
    }
    public function asset_reject(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/asset_reject_view',$data);
    }
    public function uploaded_attachments_action() {
        $data = [];
        $data['copyRequestModel'] = $this->copyRequestModel;
    
        // Start session if not already started
        if (!session()->has('dcmis_user_idd')) {
            session()->start();
        }
    
        if ($this->request->getPost('doc_action') == 'accept' || $this->request->getPost('doc_action') == 'reject') {
            if ($this->request->getPost('doc_action') == 'reject') {
                $doc_action_flag = 'F'; // defective
                $verify_status = 3;
            } else {
                $doc_action_flag = 'D'; // accepted
                $verify_status = 2;
            }
    
            // Prepare data for updating user_assets
            $data_to_update = [
                'verify_status' => $verify_status,
                'verify_by' => session()->get('dcmis_user_idd'),
                'verify_on' => date('Y-m-d H:i:s'),
                'verify_remark' => $this->request->getPost('copy_reject_detail'),
                'verify_ip' => getClientIP()
            ];
    
            // Update user_assets
            if ( $this->copyRequestModel->updateAssets($data_to_update) > 0) {
                $return_arr = ["status" => "success"];
    
                if (in_array($this->request->getPost('asset_type'), [4, 5, 6])) { // 3rd party affidavit, appearing counsel, party
                    $data_to_update_verify = [
                        'updated_on' => date('Y-m-d H:i:s'),
                        'adm_updated_by' => session()->get('dcmis_user_idd'),
                        'application_status' => $doc_action_flag
                    ];
    
                    $this->copyRequestModel->updateCopyingRequestVerify($data_to_update_verify);
                }
    
                // Uncomment and adapt the following code if needed
                /*
                $sql_verify = $this->db->table('user_assets')
                    ->select('id, asset_type, verify_status')
                    ->where('mobile', $this->request->getPost('mobile'))
                    ->where('email', $this->request->getPost('email'))
                    ->where('diary_no', 0)
                    ->orWhere('diary_no', $this->request->getPost('diary_no'))
                    ->get();
    
                if ($sql_verify->getNumRows() == 0) {
                    $data = $sql_verify->getRowArray();
                    // Additional logic can be added here
                }
                */
    
            } else {
                $return_arr = ["status" => "Error: Data not updated or already updated"];
            }
        } else {
            $return_arr = ["status" => "Error: Wrong way"];
        }
    
        return $this->response->setJSON($return_arr);
    }
    public function appearing_council_reject() {
        $data = [];
        
        // Start session if not already started
        if (!session()->has('dcmis_user_idd')) {
            session()->start();
        }
        // Get POST data
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/request/appearing_council_reject_view',$data);
       
    }
    public function appearing_council_accept(){
           // Start session if not already started
        if (!session()->has('dcmis_user_idd')) {
            session()->start();
        }
         
    
        // Get POST data
        $doc_action = $this->request->getPost('doc_action');
        $application_id = $this->request->getPost('application_id');
        $copy_reject_detail = $this->request->getPost('copy_reject_detail');
        $crn = $this->request->getPost('crn');
    
        // Determine document action flag
        $doc_action_flag = ($doc_action == 'reject') ? 'F' : 'D';
    
        // Prepare data for updating copying_request_verify_documents
        $data_to_update = [
            'request_status' => $doc_action_flag,
            'reject_cause' => $copy_reject_detail,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => session()->get('dcmis_user_idd')
        ];
    
        // Update copying_request_verify_documents
        /*$this->db->table('copying_request_verify_documents')
            ->where('request_status', 'P')
            ->where('id', $application_id)
            ->update($data_to_update);*/
        
        if ($this->copyRequestModel->updateCopyingRequestVerifyDocuments($data_to_update)) {
            $return_arr = ["status" => "success"];
    
            // Check if all requested documents are disposed
            
            
            if ($this->copyRequestModel->getCopyingRequestVerifyByCrn($crn) == 0) {
                // Check distinct request statuses
                
    
                $data3 = $this->copyRequestModel->getRequestVerifyStatusByCrn($crn);
                if ($data3['distinct_verify_status'] == 'D') {
                    $doc_action_flag = 'D'; // success
                } else {
                    $doc_action_flag = 'F'; // reject
                }
    
                // Update copying_request_verify
                $data_to_update_verify = [
                    'updated_on' => date('Y-m-d H:i:s'),
                    'adm_updated_by' => session()->get('dcmis_user_idd'),
                    'application_status' => $doc_action_flag
                ];
    
                $this->copyRequestModel->updateCopyingRequestVerify($data_to_update_verify);
            }
        } else {
            $return_arr = ["status" => "Error: Data not updated or already updated"];
        }
    
        return $this->response->setJSON($return_arr);
    }
    public function file_spilt_merge_upload(){
        $session = session();
        $db = \Config\Database::connect();
        

        if ($this->request->getMethod() === 'post') {
            $GET_SERVER_IP = base_url(); // Use base_url() for the server IP
            $ucode = $session->get('dcmis_user_idd');
            $file_url = $this->request->getPost("file_url");
            $path_file = $this->request->getPost("path_file");
            $pdf_spilt_merge_pages = str_replace(",", " ", $this->request->getPost("pdf_spilt_merge_pages"));
            $crn = $this->request->getPost("crn");
            $copy_status = $this->request->getPost("copy_status");
            $application_id = $this->request->getPost("application_id");
            $section_remarks = 'required pdf splitted from dspace pdf';
            $crvd=$this->copyRequestModel->getcopying_request_verify_documentsByPrimaryID($application_id);
            //$crvd = $model->find($application_id);

            if ($crvd) {
                $file_destination_dir = WRITEPATH . "copy_verify/unavailable_documents_request/" . $crn . "/";

                if (!is_dir($file_destination_dir)) {
                    mkdir($file_destination_dir, 0777, true);
                }

                $split_file_name = date('Ymd_his_') . md5(uniqid(rand(), TRUE)) . '.pdf';
                $path_file_new = "copy_verify/unavailable_documents_request/" . $crn . "/" . $split_file_name;
                $new_file_url = FCPATH . $path_file_new;
                $old_file_url = FCPATH . $path_file;

                $number_of_pages = exec('pdftk ' . escapeshellarg($file_url) . ' dump_data | grep NumberOfPages');
                $number_of_pages = str_replace('NumberOfPages: ', '', $number_of_pages);

                if (!empty($pdf_spilt_merge_pages)) {
                    $dest_save = $file_destination_dir . $split_file_name;
                    exec('pdftk ' . escapeshellarg($file_url) . ' cat ' . escapeshellarg($pdf_spilt_merge_pages) . ' output ' . escapeshellarg($dest_save));

                    if (file_exists($new_file_url) && file_exists($old_file_url)) {
                        $created_by = $session->get('dcmis_user_idd');
                        $created_on = date('Y-m-d');
                        $ip = $this->request->getIPAddress();

                        $data = [
                            'copying_request_verify_documents_id' => $application_id,
                            'order_type' => $crvd->order_type,
                            'order_date' => $crvd->order_date,
                            'copying_order_issuing_application_id' => $crvd->copying_order_issuing_application_id,
                            'number_of_copies' => $crvd->number_of_copies,
                            'number_of_pages_in_pdf' => $crvd->number_of_pages_in_pdf,
                            'path' => $crvd->path,
                            'from_page' => $crvd->from_page,
                            'to_page' => $crvd->to_page,
                            'display' => $crvd->display,
                            'order_type_remark' => $crvd->order_type_remark,
                            'request_status' => $crvd->request_status,
                            'updated_by' => $crvd->updated_by,
                            'updated_on' => $crvd->updated_on,
                            'reject_cause' => $crvd->reject_cause,
                            'sms_sent_on' => $crvd->sms_sent_on,
                            'email_sent_on' => $crvd->email_sent_on,
                            'current_section' => $crvd->current_section,
                            'fee_clc_for_certification_no_doc' => $crvd->fee_clc_for_certification_no_doc,
                            'fee_clc_for_certification_pages' => $crvd->fee_clc_for_certification_pages,
                            'fee_clc_for_uncertification_no_doc' => $crvd->fee_clc_for_uncertification_no_doc,
                            'fee_clc_for_uncertification_pages' => $crvd->fee_clc_for_uncertification_pages,
                            'fee_clc_creaded_by' => $crvd->fee_clc_creaded_by,
                            'fee_clc_created_on' => $crvd->fee_clc_created_on,
                            'fee_clc_created_ip' => $crvd->fee_clc_created_ip,
                            'fee_clc_updated_by' => $crvd->fee_clc_updated_by,
                            'fee_clc_updated_on' => $crvd->fee_clc_updated_on,
                            'fee_clc_updated_ip' => $crvd->fee_clc_updated_ip,
                            'creaded_by' => $created_by,
                            'created_on' => date('Y-m-d H:i:s'),
                            'created_ip' => $ip
                        ];
                        if ($this->copyRequestModel->copying_request_verify_documents_log($data) > 0) {
                            if ($this->copyRequestModel->updateCopyingRequestVerifyDocuments(['path' => $path_file_new, 'updated_on' => date('Y-m-d H:i:s'), 'updated_by' => $created_by])) {
                                $total_path_pages = exec('pdftk ' . escapeshellarg($new_file_url) . ' dump_data | grep NumberOfPages');
                                $total_path_pages = str_replace('NumberOfPages: ', '', $total_path_pages);
                                $from_page = 1;
                                $from_to_pages = $from_page . '-' . $total_path_pages;
                                $fresh_url = $GET_SERVER_IP . "/" . $path_file_new;
                                return $this->response->setJSON(['status' => '1', 'url' => $fresh_url, 'path' => $path_file_new, 'pages' => $from_to_pages]);
                            } else {
                                return $this->response->setJSON(['status' => '2', 'message' => 'Your Request not saved or updated. Please try again!']);
                            }
                        } else {
                            return $this->response->setJSON(['status' => '2', 'message' => 'Your Request not saved. Please try again']);
                        }
                    } else {
                        return $this->response->setJSON(['status' => '3', 'message' => 'Pdf not uploaded. Something went wrong!']);
                    }
                } else {
                    return $this->response->setJSON(['status' => '3', 'message' => 'Pages not found !!']);
                }
            } else {
                return $this->response->setJSON(['status' => '3', 'message' => 'Data not found!']);
            }
        } else {
            return $this->response->setJSON(['status' => '3', 'message' => 'Something went wrong!']);
        }
    }
    public function file_split_and_upload(){
        if (!session()->has('dcmis_user_idd')) {
            session()->start();
        }
    
        // Check if POST data is set
        if ($this->request->getPost()){
            $ucode = session()->get('dcmis_user_idd');
            $file_url = $this->request->getPost("file_url");
            $file_url = "http://XXXX:5008/index.php/Dspace/display_dspace4_bitstream_content/" . $file_url;
            $from_page = $this->request->getPost("from_page_no");
            $to_page = $this->request->getPost("to_page_no");
            $crn = $this->request->getPost("crn");
            $copy_status = $this->request->getPost("copy_status");
            $application_id = $this->request->getPost("application_id");
            $section_remarks = 'required pdf splitted from dspace pdf';
    
            $file_destination_dir = FCPATH . "copy_verify/unavailble_documents_request/" . $crn . "/";
    
            // Create directory if it doesn't exist
            if (!is_dir($file_destination_dir)) {
                mkdir($file_destination_dir, 0755, true);
            }
    
            // Change to the destination directory
            chdir($file_destination_dir);
    
            // Generate a unique file name
            $split_file_name = md5(uniqid(rand(), TRUE)) . '.pdf';
    
            // Get the number of pages in the PDF
            $number_of_pages = exec('pdftk ' . escapeshellarg($file_url) . ' dump_data | grep NumberOfPages');
            $number_of_pages = str_replace('NumberOfPages: ', '', $number_of_pages);
    
            if ($from_page > 0 && $to_page > 0 && $to_page <= $number_of_pages) {
                $dest_save = $file_destination_dir . $split_file_name;
                exec("pdftk " . escapeshellarg($file_url) . " cat $from_page-$to_page output " . escapeshellarg($dest_save));
    
                // Load the database
                $db = \Config\Database::connect();
    
                // Prepare the update section
                $update_section = ($copy_status != 'C') ? "current_section = '10', " : "";
    
                // Update the copying_request_verify_documents table
                $data = [
                    'path' => 'copy_verify/unavailble_documents_request/' . $crn . '/' . $split_file_name,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $ucode
                ];
    
                if ($copy_status != 'C') {
                    $data['current_section'] = '10';
                }
                if ($this->copyRequestModel->updateCopyingRequestVerifyDocuments($data)) {
                    if ($copy_status != 'C') {
                        // Insert into copying_request_movement
                        $movementData = [
                            'copying_request_verify_documents_id' => $application_id,
                            'from_section' => session()->get('dcmis_section'),
                            'from_section_sent_by' => $ucode,
                            'from_section_sent_on' => date('Y-m-d H:i:s'),
                            'to_section' => 10,
                            'remark' => $section_remarks
                        ];
                        $this->copyRequestModel->insert_copying_request_movement($movementData);
                        // Update copying_request_verify
                        $this->copyRequestModel->updateCopyingRequestVerify(['send_to_section' => 'f', 'updated_on' => date('Y-m-d H:i:s'), 'adm_updated_by' => $ucode]);
                        /*$db->table('copying_request_verify')
                            ->where('application_status', 'P')
                            ->where('crn', $crn)
                            ->update(['send_to_section' => 'f', 'updated_on' => date('Y-m-d H:i:s'), 'adm_updated_by' => $ucode]);*/
                    }
    
                    return $this->response->setJSON(['status' => 1]);
                } else {
                    return $this->response->setJSON(['status' => 2]);
                }
            } else {
                return $this->response->setJSON(['status' => 3]);
            }
        }
    }
    public function unavailable_copy_reject_save()
    {
        // Start the session if not already started
        // Load the database
        

        // Get POST data safely
        $copyRejectDetail = $this->request->getPost('copy_reject_detail');
        $applicationId = $this->request->getPost('application_id');
        $crn = $this->request->getPost('crn');

        // Prepare the data for updating the copying_request_verify_documents table
        $updateData = [
            'request_status' => 'F',
            'reject_cause' => $copyRejectDetail,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $_SESSION['dcmis_user_idd']
        ];

        // Update the copying_request_verify_documents table
        // Check if any rows were affected
        if($this->copyRequestModel->updateCopyingRequestVerifyDocuments($updateData)) {
            // Prepare the query to check if there are any related records in copying_request_verify
            
           

            // Check if there are no rows returned
            if ($this->copyRequestModel->getCopyingRequestVerifyByCrn($crn)==0) {
                // Prepare the data for updating the copying_request_verify table
                $updateVerifyData = [
                    'application_status' => 'D',
                    'updated_on' => date('Y-m-d H:i:s'),
                    'adm_updated_by' => $_SESSION['dcmis_user_idd']
                ];

                // Update the copying_request_verify table
                $this->copyRequestModel->updateCopyingRequestVerifyByCrn($updateVerifyData);
            }

            // Return success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Update successful']);
        } else {
            // Return error response
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: Data not updated or already updated']);
        }
    }
    public function unavailable_copy_reject(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/unavailable_request/unavailable_copy_reject_view',$data);
    }
    public function unavailable_copy_request_get(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/unavailable_request/unavailable_copy_request_view_get',$data);
    }
    public function unavailable_copy_request(){
        
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/unavailable_request/unavailable_copy_request_view',$data);
    }
    public function unavailable_copy_send_to_section(){
           // Start the session if not already started
        

       
       
        // Get the user code from the session
        $ucode = session()->get('dcmis_user_idd');

        // Get the copy ID from POST data safely
        $copyId = $this->request->getPost('copyid');

        // Prepare the data for updating the copying_unavailable_doc_request table
        $updateData = [
            'sent_to_section_by' => $ucode,
            'sent_to_section_on' => date('Y-m-d H:i:s'),
            'is_sent_to_section' => 't'
        ];

        // Update the copying_unavailable_doc_request table
        // Check if any rows were affected
        if ($this->copyRequestModel->update_unavailable_copy_upload($copyId,$updateData) > 0) {
            // Return success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Success']);
        } else {
            // Return error response
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unable to Insert']);
        }
    }

    
    public function unavailable_copy_upload_save(){
        
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $ucode = $_SESSION['dcmis_user_idd'];
        $allowedExts = ['pdf'];
        $file = $this->request->getFile('file');

        // Check if the file is empty
        if ($file->isValid() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Empty file cannot be uploaded']);
        }

        // Check for file type and extension
        $extension = $file->getExtension();
        if (!in_array($extension, $allowedExts) || $file->getMimeType() !== 'application/pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Only PDF file allowed']);
        }

        // Create the directory if it doesn't exist
        $copyId = $this->request->getPost('copyid');
        $masterToPath = WRITEPATH . "copy_verify/unavailable_documents_request/$copyId/";

        if (!is_dir($masterToPath)) {
            mkdir($masterToPath, 0770, true);
        }

        // Generate a new file name
        $newName = md5(uniqid(rand(), true)) . '.' . $extension;

        // Move the uploaded file
        if ($file->move($masterToPath, $newName)) {
            // Prepare the data for updating the database
            
            $data = [
                'url' => "copy_verify/unavailable_documents_request/$copyId/$newName",
                'updated_on' => date('Y-m-d H:i:s'),
                'updated_by' => $ucode,
                'request_status' => 'D'
            ];

            // Update the database
            // Check if the update was successful
            if ($this->copyRequestModel->update_unavailable_copy_upload($copyId,$data)>0) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error updating the database']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not Allowed, Check Permission']);
        }
    }
    public function unavailable_copy_upload(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/unavailable_request/unavailable_copy_upload_view',$data);
    }
    public function upload_digital_signed(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $ucode = $_SESSION['dcmis_user_idd'];
        $application_explode = explode("_", $this->request->getPost('application_id_id'));
        $application_id = $application_explode[0];
        $document_id = $application_explode[1];

        $allowedExts = ['pdf'];
        $file = $this->request->getFile('file');

        // Check if the file is empty
        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Empty file cannot be uploaded']);
        }

        // Check for file type and extension
        $extension = $file->getExtension();
        if (!in_array($extension, $allowedExts) || $file->getMimeType() !== 'application/pdf') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Only PDF file allowed']);
        }

        // Create the directory if it doesn't exist
        $master_to_path = WRITEPATH . "copy_verify/{$this->request->getPost('application_id_id')}/";
        if (!is_dir($master_to_path)) {
            mkdir($master_to_path, 0770, true);
        }

        // Generate a new file name
        $new_name = md5(uniqid(rand(), true)) . '_digital.' . $extension;

        // Move the uploaded file
        if ($file->move($master_to_path, $new_name)) {
            // Prepare the data for updating the database
            
            $data = [
                'pdf_digital_signature_path' => "copy_verify/{$this->request->getPost('application_id_id')}/$new_name",
                'pdf_digital_signature_on' => date('Y-m-d H:i:s'),
                'pdf_digital_signature_by' => $ucode
            ];

            // Update the database
            
            

            // Check if the update was successful
            if ($this->copyRequestModel->updateCopyingApplicationDocument($data,$document_id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error updating the database']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not Allowed, Check Permission']);
        }

    }
    public function uploaded_attachments(){
        $data=array();
        $data['copyRequestModel']=$this->copyRequestModel;
        return view('Copying/uploaded_attachments',$data);
    }
    public function appearing_council_reject_save()
    {
        // Start the session
        $session = session();

        // Load the database service
        

        // Get POST data
        $copyRejectDetail = $this->request->getPost('copy_reject_detail');
        $applicationId = $this->request->getPost('application_id');
        $crn = $this->request->getPost('crn');

        // Prepare the data for updating the copying_request_verify_documents table
        $updateData = [
            'request_status' => 'F',
            'reject_cause' => $copyRejectDetail,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $session->get('dcmis_user_idd')
        ];

        // Update the copying_request_verify_documents table
        $update=$this->copyRequestModel->updateCopyingRequestVerifyDocuments($updateData);

        // Check if any rows were affected
        if ($update) {
           
            // Check if there are no rows returned
            if ($this->copyRequestModel->getCopyingRequestVerifyByCrn($crn) == 0) {
                // Prepare the data for updating the copying_request_verify table
                $updateVerifyData = [
                    'application_status' => 'D',
                    'updated_on' => date('Y-m-d H:i:s'),
                    'adm_updated_by' => $session->get('dcmis_user_idd')
                ];

                // Update the copying_request_verify table
                $this->copyRequestModel->updateCopyingRequestVerifyByCrn($updateVerifyData);
            }

            // Return success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Update successful']);
        } else {
            // Return error response
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: Data not updated or already updated']);
        }
    }
    public function copy_search_verify_qr(){
        //$model = new CopyStatusModel();
        $data = [];

        if ($this->request->getGet('flag') == 'ano') {
            $applicationType =$this->request->getGet('application_type');
            $applicationNo =$this->request->getGet('application_no');
            $applicationYear =$this->request->getGet('application_year');
            $data['result'] =$this->copyRequestModel->getApplicationData($applicationType, $applicationNo, $applicationYear);
        } else {
            $crn = $this->request->getGet('crn');
            $data['result'] =$this->copyRequestModel->getRequestData($crn);
        }
    return view('Copying/copy_search_verify_qr',$data);  
    }
    public function pdf_viewer(){

    }  
}