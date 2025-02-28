<?php

namespace App\Models;

use App\Models\Abstracts\Owner;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Owner
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    public $guarded =[];
    
    function isCreditAllowed(){
        return $this->credit_allowed;
    }

    function getCreditLimit(){
        return $this->credit_limit;
    }
        
    function getCurrentBalance(){
        return $this->current_balance;
    }

    function setCreditLimit(float $value)
    {
        $this->credit_limit = $value;
    }
        
    function setcurrentBalance(float $value)
    {
        $this->current_balance = $value;
    }
        
}
