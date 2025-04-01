<?php

namespace App\Abstracts\Interfaces;

use App\Enums\SettingsKeys;

interface SettingsServiceInterface
{

    /**
     * Retrieve the value of a certain key 
     * 
     * @param string $key
     * @param mixed $default  [offers alernative return value if the value is null]
     * @return mixed
     * 
     *  */
    public function getValue(string $key, $default = null);


    /**
     * Set or update a setting by its key.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setValue(string $key, $value);
}
