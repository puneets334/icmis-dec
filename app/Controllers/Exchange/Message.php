<?php

namespace App\Controllers\Exchange;

use App\Controllers\BaseController;
use App\Models\Exchange\Matters_Listed;
use App\Models\Exchange\CauseListFileMovementModel;
use App\Models\Exchange\Transaction;
use App\Models\Exchange\Sql_Report;
use App\Models\Court\CourtMasterModel;
use App\Models\Exchange\FileMovementModel;
use App\Models\Exchange\MessageModel;

class Message extends BaseController
{
    public $Matters_Listed;
    public $CauseListFileMovementModel;
    public $CourtMasterModel;
    public $Transaction;
    public $FileMovementModel;
    public $MessageModel;

    function __construct()
    {   
        $this->Matters_Listed = new Matters_Listed();
        $this->CauseListFileMovementModel = new CauseListFileMovementModel();
        $this->CourtMasterModel = new CourtMasterModel();
        $this->Transaction = new Transaction();
        $this->FileMovementModel = new FileMovementModel();
        $this->MessageModel = new MessageModel();
    }

    public function composeMessage()
    {
       
        return view('Exchange/Message/compose');
    }

    public function getReceiver()
    {
        $receiverData = $this->MessageModel->get_rec();
        return $this->response->setJSON($receiverData);
    }

    public function sendMsg()
    {
        $request = \Config\Services::request();
        $mess_to_arr = explode(',', $request->getGet('msg_to'));
        $msg_frm = $request->getGet('msg_frm');
        $msg = $request->getGet('msg');
        $hd_ipadd = !empty($request->getGet('hd_ipadd')) ? $request->getGet('hd_ipadd') : '';



        $receiverData = $this->MessageModel->send_msg($mess_to_arr , $msg_frm ,$msg ,$hd_ipadd);
    }

    public function inbox()
    {
        return view('Exchange/Message/inbox');
    }

    public function inboxPro()
    {
        
        $request = \Config\Services::request();
        $dtp = date('Y-m-d',strtotime($request->getPost('dtp')));
        $q = $request->getPost('q');
        $result = $this->MessageModel->inbox_pro($q, $dtp);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function markAsSeen()
    {
        $request = \Config\Services::request();
        $id = $request->getGet('id');
        $result = $this->MessageModel->seen($id);
    }

    public function deleteMessage()
    {
        $request = \Config\Services::request();
        $id = $request->getGet('id');
        $result = $this->MessageModel->savethis($id);
    }

    public function replyMessage()
    {
        $result = $this->MessageModel->reply();
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function trash()
    {
        return view('Exchange/Message/trash');
    }

    public function trashPro()
    {
        
        $request = \Config\Services::request();
        $dtp = date('Y-m-d',strtotime($request->getGet('dtp')));
        $q = $request->getGet('q');
        $result = $this->MessageModel->trash_pro($q, $dtp);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function sentbox()
    {
        return view('Exchange/Message/sentbox');
    }

    public function sentboxPro()
    {
        $request = \Config\Services::request();
        $dtp = date('Y-m-d', strtotime($request->getGet('dtp')));
         $q = $request->getGet('q'); 

        $result = $this->MessageModel->sentbox_pro($q, $dtp);
        return $this->response->setJSON([
            'status' => true,
            'data' => $result,
        ]);
    }

    public function sentSave()
    {
        $request = \Config\Services::request();
        $id = $request->getGet('id');
        $result = $this->MessageModel->sent_save($id);
    }
}