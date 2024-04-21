<?php

namespace App\Repositories;

use App\Models\Ma;

class MaRepository extends Repository
{
    public function topRank(){
        $ma = Ma::find();
        $maRespository = $ma -> query();
        return $maRespository;
    }
    
    public function getLastUpdate(){
        $ma = Ma::find();
        $sql_date = "SELECT last_get_date FROM ma ORDER BY last_get_date DESC";
        $result_date = $ma -> query($sql_date);
    }


}