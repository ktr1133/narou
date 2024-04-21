<?php

namespace App\Repositories;

use App\Models\Unique;

class UniqueRepository extends Repository
{
    public function topRank(){
        $unique = Unique::find();
        $uniqueRespository = $unique -> query();
        return $uniqueRespository;
    }

}