<?php

namespace App\Controllers\Extension;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\Common\Dropdown_list_model;
use CodeIgniter\Model;
use App\Views\Commom\Component\listing;
use App\Models\Extension\CauselistModel;
use App\Models\Entities\Model_heardt;
use App\Models\Entities\Model_users;

class Causelist extends BaseController
{
    public $session;

    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Calcutta');
        $dropDownListModel = new Dropdown_list_model;
        $heardt_model = new Model_heardt;
    }

    public function index()
    {
        $dropDownListModel = new Dropdown_list_model;
        $causelistModel = new CauselistModel;
        $data['section'] = $dropDownListModel->getSections();
        $data['fresh_matters'] = $this->getFreshMatters();
        $data['listingDates'] = $this->getListingDates();
        return view('Extension/Causelist/fresh_matter_report', $data);
    }

    public function getListingDates()
    {
        // echo "inside";
        $heardt_model = new Model_heardt;
        $m_f = 'M';
        if ($_POST) {
            $m_f = $_POST['m_f'];
        }

        $option_values = '';
        $listing_details = $heardt_model->select('next_dt')->where('mainhead', $m_f)->where('next_dt!=', null)/*->where('next_dt>=',date('Y-m-d'))*/
            ->groupStart()->where('main_supp_flag', '1')->orWhere('main_supp_flag', '2')->groupEnd()->groupBy('next_dt')->orderBy('next_dt')->findAll();
        //echo $this->db->getLastQuery(); 
        // print_r($listing_details);die;
        if (!empty($listing_details)) {
            if ($_POST) {
                foreach ($listing_details as $ldates) {
                    $option_values .= '<option value= ' . $ldates['next_dt'] . '>' . date('d-m-Y', strtotime($ldates['next_dt'])) . '</option>';
                }
                return $option_values;
            } else {
                return $listing_details;
            }
        } else
            return $option_values .= '<option value="0">Empty</option>';
    }

    public function getFreshMatters()
    {
        $user_model = new Model_users();
        $causelist_model = new CauselistModel();
        $heardt_model = new Model_heardt;
        $ucode = session()->get('login')['usercode'];
        $usertype = session()->get('login')['usertype'];
        $section = session()->get('login')['section'];
        if ($usertype == '14' and $section != 77 and $section != 60) {
            $da_query = $user_model->join('master.users u2', 'users.section=u2.section', 'left')->select('array_to_string(array_agg(u2.empid),', ') as allda ')
                ->where('users.display', 'Y')->where('users.usercode', $ucode)->groupBy('u2.section')->findAll();
        }
        if ($_POST) {
            $details = $causelist_model->get_data($_POST);
            $data['details'] = $details;
            $data['report_title'] = 'Details of Fresh Matters';
            $result_view = view('Extension/Causelist/fresh_matter_data', $data);
            echo '1@@@' . $result_view;
            exit();
        }
    }
}
