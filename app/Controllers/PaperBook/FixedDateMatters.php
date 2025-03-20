<?php

namespace App\Controllers\PaperBook;
use App\Controllers\BaseController;
use App\Models\PaperBook\FixedDateMattersModel;

class FixedDateMatters extends BaseController
{

    public  $FixedDateMattersModel;
    function __construct()
    {
         $this->FixedDateMattersModel = new FixedDateMattersModel();
    }

    /**
     * To fixed date matters filter page
     *
     * @return void
     */
    public function fd_matters()
    {
        $data['ss'] = $this->FixedDateMattersModel->getNextDate();
        return  view('PaperBook/fd_matters', $data);
    }


    /**
     * To fetch search results and display in list
     *
     * @return void
     */
    public function get_fd_matters()
    {
        $next_date = trim($this->request->getGet('q'));
        $formated_next_date = date("d-m-Y", strtotime($next_date));
        $listing_dates = [];
        if(!empty($next_date)) {
            $listing_dates = $this->FixedDateMattersModel->get_fixed_date_matters($next_date);
        }
        $data['results'] = $listing_dates;   
        $data['formated_next_date'] = $formated_next_date;  

        return  view('PaperBook/get_fd_matters', $data);        
    }
}
