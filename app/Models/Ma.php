<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ma extends Model
{
    use HasFactory;
    protected $fillable = [
        'ncode',
        'title',
        'writer',
        'general_all_no',
        'general_lastup',
        'records',
        'mean_mk',
        'mean_uf',
        'mean_c',
        'sum_po',
        'sum_un'
    ];

    //リレーションの定義
    public function points(){
        return $this ->hasOne(Point::class);
    }
    public function uniques(){
        return $this ->hasOne(Unique::class);
    }
    public function marks(){
        return $this ->hasOne(Mark::class);
    }
    public function updateFrequencies(){
        return $this ->hasOne(Update_frequency::class);
    }
    public function calcs(){
        return $this ->hasOne(Calc::class);
    }

}
