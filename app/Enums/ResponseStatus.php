<?php

namespace App\Enums;

enum ResponseStatus: int
{
    case Ok = 200;
    case Created = 201;
    case NoContent = 204;
    
    case NotFound = 404;

}
