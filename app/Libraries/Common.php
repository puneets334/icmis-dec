<?php
namespace App\Libraries;

class Common{

    public function date_formatter($date, $format){
        if($date != null){
            return date($format, strtotime($date));
        }
        else
            return null;
    }
    public function convertToTitleCase($str){
        return str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($str))));
    }


}


?>