<?php

namespace App\Http\Controllers;

use App\Consts\NarouConst;
use App\Models\Mark;
use App\Models\Ma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopRankController extends Controller
{
    public $weeklyToWeeks;
    public $montylyToWeeks;
    public $halfToWeeks;
    public $yearlyToWeeks;
    public $allToWeeks;
    
    public function ____construct($weeklyToWeeks, $montylyToWeeks, $halfToWeeks, $yearlyToWeeks, $allToWeeks)
    {
        $this->weeklyToWeeks=1;
        $this->montylyToWeeks=5;
        $this->halfToWeeks=26;
        $this->yearlyToWeeks=52;
        $this->allToWeeks = 0;
    }
    /**
     * Display a listing of the resource.
     */
    public function show(Request $request){
        $last_date = $this -> getLastUpdate();
        $select_data = $this -> getSelectData($request);
        $result = $this -> generateTopRanking($request);

        return view('index', ['last_date' => $last_date, 'select_date' => $select_data, 'result' => $result]);
    }


    /**
     * Requestで取得した項目（期間、種類、話数帯をHP上の表示内容に置き換える）
     */
    public function getSelectData(Request $request)
    {
        $cate_mark = NarouConst::CATEGORY_MARK;
        $cate_calc = NarouConst::CATEGORY_CALC;
        $gan_ov100un300 = NarouConst::GAN_OVER_100_UNDER_300_TEXT;
        $gan_ov300un500 = NarouConst::GAN_OVER_300_UNDER_500_TEXT;
        $gan_ov500 = NarouConst::GAN_OVER_500_TEXT;
        $timeSpan_weekly = NarouConst::TOPPAGE_TITLE_WEEKLY;
        $weekly = NarouConst::WEEKLY_TEXT;
        $timeSpan_monthly = NarouConst::TOPPAGE_TITLE_MONTHLY;
        $monthly = NarouConst::MONTHLY_TEXT;
        $timeSpan_half = NarouConst::TOPPAGE_TITLE_HALF;
        $half = NarouConst::HALF_TEXT;
        $timeSpan_yearly = NarouConst::TOPPAGE_TITLE_YEARLY;
        $yearly = NarouConst::YEARLY_TEXT;
        $timeSpan_all = NarouConst::TOPPAGE_TITLE_ALL_TERMS;
        $all = NarouConst::ALL_TEXT;

        $title = '';
        $r_text_time = '';
        $r_text_cate = '';
        $r_text_gan = '';

        if(!empty($request)){
            $timeSpan = $request -> input(NarouConst::SELECT_TOP_TIMESPAN);
            $cate = $request -> input(NarouConst::SELECT_TOP_CATEGORY);
            $gan = $request -> input(NarouConst::SELECT_TOP_GENERAL_ALL_NO);
            if(!empty($timeSpan)){
                if($timeSpan === NarouConst::WEEKLY_TEXT){
                    $title = $timeSpan_weekly;
                    $r_text_time = $weekly;
                }else if($timeSpan === NarouConst::MONTHLY_TEXT){
                    $title = $timeSpan_monthly;
                    $r_text_time = $monthly;
                }else if($timeSpan === NarouConst::HALF_TEXT){
                    $title = $timeSpan_half;
                    $r_text_time = $half;
                }else if($timeSpan === NarouConst::YEARLY_TEXT){
                    $title = $timeSpan_yearly;
                    $r_text_time = $yearly;
                }else if($timeSpan === NarouConst::ALL_TEXT){
                    $title = $timeSpan_all;
                    $r_text_time = $all;
                }else{
                    $title = $timeSpan_weekly;
                    $r_text_time = $weekly;
                };
            }else{
                $title = $timeSpan_weekly;
                $r_text_time = $weekly;
            };
            if(!empty($cate)){
                if($cate === NarouConst::MARK){
                    $r_text_cate = $cate_mark;
                }else if($cate === NarouConst::CALC){
                    $r_text_cate = $cate_calc;
                }else{
                    $r_text_cate = $cate_mark;
                };
            }else{
                $r_text_cate = $cate_mark;
            };
            if(!empty($gan)){
                if($gan === NarouConst::GAN_OVER100_UNDER300){
                    $r_text_gan = $gan_ov100un300;
                }else if($gan === NarouConst::GAN_OVER300_UNDER500){
                    $r_text_gan = $gan_ov300un500;
                }else if($gan === NarouConst::GAN_OVER500){
                    $r_text_gan = $gan_ov500;
                }else{
                    $r_text_gan = $gan_ov500;
                };
            }else{
                $r_text_gan = $gan_ov500;
            }
        }else{
            $title = $timeSpan_weekly;
            $r_text_time = $weekly;
            $r_text_cate = $cate_mark;
            $r_text_gan = $gan_ov500;
        }
        return ['title' => $title, 'r_text_time' => $r_text_time, 'r_text_cate' => $r_text_cate, 'r_text_gan' => $r_text_gan];
    }
    

    /**
     * DBのmarkテーブルからカラム名が日付型のものをすべて取得
     */
    public function getDateList()
    {
        //日付のカラムを持つﾃｰﾌﾞﾙの１つから日付の配列を昇順に並べ替えて取得
        $dateColumns = [];
        $mark = new Mark;
        $markColumnNames = $mark->getColumnNames();
        foreach ($markColumnNames as $column) {
            if (strpos($column, '-') !== false) {
                $dateColumns[] = $column;
            }
        }
        $this -> allToWeeks = count($dateColumns, COUNT_RECURSIVE);
        return $dateColumns;

    }


    /**
     * last update.
     */
    public function getLastUpdate()
    {
        $ma = new Ma;
        $last_date = $ma -> getLatestDate();        
        return $last_date;
    }

    /**
     * ﾘｸｴｽﾄ内容に応じたランキングﾃﾞｰﾀを取得する。
     */
    public function generateTopRanking(Request $request)
    {
        $ma = new Ma;
        $result = $ma -> generateTopRanking($request);
        
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


}
