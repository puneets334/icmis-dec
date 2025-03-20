<?php
namespace App\Controllers\RI;
use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\RI\RIModel;

class EcopyController extends BaseController
{
    public $RIModel;
    public function __construct()
    {
        $this->RIModel = new RIModel();

    }
    public function index()
    {
        return view('RI/Notices/envelope_movement');
    }


}