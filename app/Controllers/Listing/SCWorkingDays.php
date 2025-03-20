<?php

namespace App\Controllers\Listing;
use App\Controllers\BaseController;

use App\Models\Listing\SCWorkingDaysModel;

class SCWorkingDays extends BaseController
{

    protected $sc_working_days_model;

    function __construct()
    {        
        $this->sc_working_days_model = new SCWorkingDaysModel();
    }

    public function working_days()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if($userTypeString != '0'){
            $userType = explode('~', $userTypeString);
            if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
                echo "YOU ARE NOT AUTHORISED";
                exit();
            }
            $data = [
                'clientIP' => get_client_ip(),
            ];
            return view('Listing/sc_working_days/working_days', $data);
        }
    }

    public function get_working()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if($userTypeString != '0'){
            $userType = explode('~', $userTypeString);
            // if (($userType[0] != 1 || $userType[0] != 57 || $userType[0] != 3 || $userType[0] != 4) && ($userType[6] != 450)) { // it will be right condition
            if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
                echo "YOU ARE NOT AUTHORISED";
                exit();
            }
            $is_working = date('Y-m-d', strtotime($_POST['is_working']));
            $data = [
                'getWorkingDays' => $this->sc_working_days_model->getWorkingDaysDetails($is_working),
                'clientIP' => get_client_ip(),
            ];
            return view('Listing/sc_working_days/get_working', $data);
        }
    }

    public function insert_working_day()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if($userTypeString != '0'){
            $userType = explode('~', $userTypeString);
            if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
                echo "YOU ARE NOT AUTHORISED";
                exit();
            }

            $this->sc_working_days_model->insertWorkingDay();
        }
    }

    public function update_working_day()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if($userTypeString != '0'){
            $userType = explode('~', $userTypeString);
            if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
                echo "YOU ARE NOT AUTHORISED";
                exit();
            }
            
            $this->sc_working_days_model->updateWorkingDay();
        }
    }

    public function buttons_added()
    {
        $usercode = session()->get('login')['usercode'];
        $userTypeString = getUser_dpdg_full_2($usercode);
        if($userTypeString != '0'){
            $data = [];
            $userType = explode('~', $userTypeString);
            if (($userType[0] != 1 && $userType[0] != 57 && $userType[0] != 3 && $userType[0] != 4) && ($userType[6] != 450)) {
                echo "YOU ARE NOT AUTHORISED";
                exit();
            }
            if(!empty($_POST['From_date']) && !empty($_POST['To_date'])){
                $data = [
                    'getWorkingDay' => $this->sc_working_days_model->getWorkingDay(),
                    'clientIP' => get_client_ip(),
                ];
            }
            return view('Listing/sc_working_days/buttons_added', $data);       
        }
    }

}