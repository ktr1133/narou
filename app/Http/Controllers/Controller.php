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

    }

}
