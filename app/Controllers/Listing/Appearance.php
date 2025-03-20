<?php

namespace App\Controllers\Listing;

use App\Controllers\BaseController;

use App\Models\Menu_model;
use CodeIgniter\Controller;
use CodeIgniter\Model;
//use App\Models\Entities\Main;
use App\Models\Court\CourtMasterModel;

class Appearance extends BaseController
{
    public $model;

    function __construct()
    {
        $this->model = new CourtMasterModel();
    }

    public function index()
    {
        return view('Court/CourtMaster/Appearance/list');
    }

    public function listProcess_old()
     {
        $list_date = $this->request->getPost('list_date'); 
        $data['list_date_ymd'] =  date("Y-m-d", strtotime($list_date));
        $data['courtno'] = $_POST['courtno'];

        $api_url = E_FILING_URL."/api/v1/diaries?list_date=2023-01-02&court_no=6";

        $client = \Config\Services::curlrequest();
        $response = $client->get($api_url);

        if ($response->getStatusCode() == 200) {
            $resultdata = json_decode($response->getBody(), true);
            $data['result'] = $resultdata['data'];            
        } else {
            $data['result'] = array();
        }      
        
        //$data['CourtMaster'] = $this->model;
        //$data['result'] = $this->model->getDiariesformList($data['list_date_ymd'], $data['courtno']);

        return view('Listing/appearance/list_process', $data);

    }

    public function listProcess()
        {
            $list_date = $this->request->getPost('list_date'); 
            $court_no  = $this->request->getPost('courtno');

            if (empty($list_date) || empty($court_no)) {
                return view('Listing/appearance/list_process', ['error' => 'Missing required parameters']);
            }

            $data['list_date_ymd'] = $list_date_ymd = date("Y-m-d", strtotime($list_date));
            $data['courtno'] = $court_no;

            $api_url = E_FILING_URL."/api/v1/diaries?list_date=".$list_date_ymd."&court_no=".$court_no;

            $client = \Config\Services::curlrequest();

            try {
                $response = $client->get($api_url, ['http_errors' => false]); // Prevent exceptions

                $statusCode = $response->getStatusCode();

                if ($statusCode == 200) {
                    $resultdata = json_decode($response->getBody(), true);
                    
                    if (isset($resultdata['data'])) {
                        $data['result'] = $resultdata['data'];
                    } else {
                        $data['result'] = [];
                        log_message('error', 'API response is missing "data" key: ' . json_encode($resultdata));
                    }
                } else {
                    $data['result'] = [];
                    log_message('error', "API Error: $statusCode - " . $response->getBody());
                }
            } catch (\Exception $e) {
                log_message('error', 'CURL Request Failed: ' . $e->getMessage());
                $data['result'] = [];
            }

            return view('Listing/appearance/list_process', $data);
        }

}
