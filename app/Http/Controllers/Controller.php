<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $weekly;
    public $monthly;
    public $half;
    public $yearly;
    public $all;

    
    public function __construct() {
        $this->weeklyToWeeks = 1;
        $this->monthlyToWeeks = 5;
        $this->halfToWeeks = 27;
        $this->yearlyToWeeks = 53;
        $this->allToWeeks = 0;
    }

}
