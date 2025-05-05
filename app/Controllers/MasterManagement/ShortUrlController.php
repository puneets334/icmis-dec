<?php

namespace App\Controllers\MasterManagement;

use App\Controllers\BaseController;
use App\Models\MasterManagement\UserManagementModel;
use App\Models\Record_room\Model_record;
use CodeIgniter\Controller;
use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ShortUrlController extends BaseController
{
    public $UserManagementModel;
    public $Model_record;
    function __construct()
    {
        ini_set('memory_limit', '51200M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
        $this->UserManagementModel = new UserManagementModel();
        $this->Model_record = new Model_record();
    }

    public function index()
    {        
        $data = [];
        $url = $this->request->getPost('url');
        $data['url'] = $url;
        // pr($data['sel_all_jud']);
        return view('MasterManagement/shortner/shortner.php', $data);
    }

    public function process_shortner()
    {
        $urlinput = $this->request->getPost('urlinput');

        $builder = $this->db->table('master.test_redirect a');        
        $builder->select('*');        
        $builder->where('url', $urlinput);                    
        //echo $builder->getCompiledSelect();die;
        $query = $builder->get();
        $checkexist = $query->getRowArray();
        
        if( is_array($checkexist) &&  count($checkexist) > 0  )
        {
            $mainurl = 'https://cleanuri.com/';
            $hits = $checkexist['hits']+1;
            $builder = $this->db->table('master.test_redirect');
            $builder->set(['hits' => $hits])
                ->where('url', $urlinput)
                ->update();
            $html ='';
            $html = '<div class="row">
                            <div class="col-md-6 text-right font-weight-bold" style="font-size: larger">
                                    <span class="text-success" style="display:inline-block;margin-top:8px">Short Url : </span>
                            </div>
                            <div class="col-md-6 text-left font-weight-bold">                                
                                <a id="shorturl" style="color:green" href="'.$mainurl.$checkexist['slug'].'" target="_blank"  title="Click to Go!">'.$mainurl.$checkexist['slug'].'</a>
                                &nbsp;&nbsp;&nbsp;<button class="btn btn btn-primary" onclick="copyFunction()">Copy text</button>
                            </div>
                      </div>  
                    ';
            return $this->response->setJSON([
                'solve' => true,
                'Message' => $html,
                'Message2' => 'Already Exist'
            ]);

        }


        $longUrl = $urlinput;
        $apiUrl = 'https://cleanuri.com/api/v1/shorten';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'url' => $longUrl
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['result_url'])) {
            //echo "Short URL: " . $data['result_url'];
            //$response= $data['result_url'];
            $mainurl = 'https://cleanuri.com/';
            $shortCode = str_replace('https://cleanuri.com/', '', $data['result_url']);

            $insertData = [
                'slug' => $shortCode,
                'url' => $urlinput,
                'date' => date('Y-m-d H:i:s'),
                'hits' => 0,                
            ];

            $builder = $this->db->table('master.test_redirect');
            $builder->insert($insertData); 
            
            $html ='';
            $html = '<div class="row">
                            <div class="col-md-6 text-right font-weight-bold" style="font-size: larger">
                                    <span class="text-success" style="display:inline-block;margin-top:8px">Short Url : </span>
                            </div>
                            <div class="col-md-6 text-left font-weight-bold">                                
                                <a id="shorturl" style="color:green" href="'.$mainurl.$shortCode.'" target="_blank"  title="Click to Go!">'.$mainurl.$shortCode.'</a>
                                &nbsp;&nbsp;&nbsp;<button class="btn btn btn-primary" onclick="copyFunction()">Copy text</button>
                            </div>
                      </div>  
                    ';
            return $this->response->setJSON([
                'solve' => true,
                'Message' => $html,
                'Message2' => 'Newly Created',
            ]);
        } else {
            //echo "Failed to shorten URL. Response: " . $response;
            $html ='';
            $html = '<div class="row">
                            <div class="col-md-12 text-center font-weight-bold" style="font-size: larger"> 
                                <span class="text-danger">Failed to shorten URL. Response: '.$resposne.' </span>
                            </div>  
                    </div>';
            return $this->response->setJSON([
                'solve' => false,
                'Message' => $response,
                'Message2' => 'Error Message',
            ]);
        }
        
    }

    
}
