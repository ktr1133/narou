<?php

namespace App\Repositories;

use App\Models\Mark;

class MarkRepository
{
    public function topRank(){
        $mark = Mark::get();
        return $mark;
    }

}