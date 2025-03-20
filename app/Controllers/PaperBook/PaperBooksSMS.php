<?php

namespace App\Controllers\PaperBook;

use App\Controllers\BaseController;
use App\Models\PaperBook\PaperBooksSMS_model;

class PaperBooksSMS extends BaseController
{
    public $PaperBooksSMS_model;

    function __construct()
    {
        $this->PaperBooksSMS_model = new PaperBooksSMS_model();
    }

    public function index()
    {
        return view('PaperBook/sendSMS_Godown');
    }

    public function sms_godown()
    {
        $toMobile = $this->request->getPost('toMobile');
        $SMSText = $this->request->getPost('SMSText');
        $usercode = $this->request->getPost('usercode');
        $ipAddress = $this->request->getPost('ipAddress');
        $deptName = "Godown";
        return $this->send_SMS($toMobile, $SMSText, $usercode, $ipAddress, $deptName);
    }


    public function displayRISMSPage()
    {
        return view('PaperBook/sendSMS_RI');
    }

    public function sms_ri()
    {
        $toMobile = $this->request->getPost('toMobile');
        $SMSText = $this->request->getPost('SMSText');
        $usercode = $this->request->getPost('usercode');
        $ipAddress = $this->request->getPost('ipAddress');
        $deptName = "RI";
        return $this->send_SMS($toMobile, $SMSText, $usercode, $ipAddress, $deptName);
    }

    private function send_SMS($toMobile = null, $SMSText = null, $userId = null, $ipAddress = null, $fromDept = null)
    {
        date_default_timezone_set('Asia/Kolkata');
        if ($SMSText == null or $SMSText == '') {
            if ($fromDept == 'Godown') {
                $sms_text = rawurlencode(" Paper Books are dispatched to R&I Section from Godown " . date("d-m-Y H:i:s", time())) . ". - Supreme Court of India";
                $templateId = SCISMS_PAPERBOOK_RI_GODOWN;
            } else {
                $sms_text = rawurlencode(" Paper Books are dispatched from R&I to residential offices of Honble CJI & Honble Judges " . date("d-m-Y H:i:s", time())) . ". - Supreme Court of India";
                $templateId = SCISMS_PAPERBOOK_RI_JUDGE_RESIDENCE;
            }
        } else {
            $sms_text = rawurlencode($SMSText);
            $templateId = SCISMS_GENERIC_TEMPLATE;
        }

        if ($toMobile == null or $toMobile == '') {
            $toMobile = '7503650509,9419205550,9999777982,9810789879,9312372552,9871922703,9711475023,8860012863,9968273526,9868464064,8800340027,9312570277,9910384809';
        } else {
            $toMobile = $toMobile;
        }
        $sms_url = 'http://xxxx/eAdminSCI/a-push-sms-gw?mobileNos=' . $toMobile . '&message=' . $sms_text . '&typeId=30&templateId=' . $templateId . '&myUserId=NIC001001&myAccessId=root&authCode=sdjkfgbsjh$1232_12nmnh';
        $sms_response = file_get_contents($sms_url);
        $json = json_decode($sms_response);
        if ($json->{'responseFlag'} == "success") {
            //echo 'Success: Causelist Uploaded alert SMS sent.';
            $this->PaperBooksSMS_model->insert_SMSLogs($toMobile, $sms_text, $userId, 'Success');
            return "1";
        } else {
            //echo 'Error: Causelist Uploaded alert SMS could not be sent.';
            $this->PaperBooksSMS_model->insert_SMSLogs($toMobile, $sms_text, $userId, 'Error');
            return "0";
        }
    }
}
