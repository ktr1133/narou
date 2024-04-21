<?php

namespace App\Repositories;

use App\Models\Update_frequency;

class Update_frequencyRepository extends Repository
{
    public function topRank(){
        $update_frequency = Update_frequency::find();
        $update_frequencyRespository = $update_frequency -> query();
        return $update_frequencyRespository;
    }

}