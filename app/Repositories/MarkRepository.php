<?php

namespace App\Repositories;

use App\Models\Mark;

class MarkRepository extends Repository
{
    public function topRank(){
        $mark = Mark::find();
        $markRespository = $mark -> query();
        return $markRespository;
    }

}