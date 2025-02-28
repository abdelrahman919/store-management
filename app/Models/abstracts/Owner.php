<?php
namespace App\Models\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class Owner extends Model{

    abstract function isCreditAllowed();

    abstract function getCreditLimit();
        
    abstract function getCurrentBalance();

    abstract function setCreditLimit(float $value);

    abstract function setCurrentBalance(float $value);
    
}