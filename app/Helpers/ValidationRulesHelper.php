<?php
namespace App\Helpers;

class ValidationRulesHelper{

    public static function validPhone() {
        $mobilePhonePattern = "#^0(10|11|12|15)[0-9]{8}$#";
        return ['string', 'regex:' . $mobilePhonePattern];
    }

    public static function validLinePhone(){
        return ['string', 'numeric', 'max_digits:9'];
    }

    public static function requiredArray(){
        return ['required', 'array', 'min:1'];
    }

    // public function validName(){
    //     return ['string', 'max:255'];
    // }


}