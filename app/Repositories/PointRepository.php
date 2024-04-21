<?php

namespace App\Repositories;

use App\Models\Point;

class PointRepository extends Repository
{
    public function topRank(){
        $point = Point::find();
        $pointRespository = $point -> query();
        return $pointRespository;
    }

}