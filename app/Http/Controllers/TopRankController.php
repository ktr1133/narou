<?php

namespace App\Http\Controllers;

use App\Consts\NarouConst;
use App\Models\Mark;
use App\Models\Ma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopRankController extends Controller
{
    public $weekly_to_weeks;
    public $montyly_to_weeks;
    public $half_to_weeks;
    public $yearly_to_weeks;
    public $all_to_weeks;
    
    public function ____construct($weekly_to_weeks, $montyly_to_weeks, $half_to_weeks, $yearly_to_weeks, $all_to_weeks)
    {
        $this->weekly_to_weeks=1;
        $this->montyly_to_weeks=5;
        $this->half_to_weeks=26;
        $this->yearly_to_weeks=52;
        $this->all_to_weeks = 0;
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
        $time_span_weekly = NarouConst::TOPPAGE_TITLE_WEEKLY;
        $weekly = NarouConst::WEEKLY_TEXT;
        $time_span_monthly = NarouConst::TOPPAGE_TITLE_MONTHLY;
        $monthly = NarouConst::MONTHLY_TEXT;
        $time_span_half = NarouConst::TOPPAGE_TITLE_HALF;
        $half = NarouConst::HALF_TEXT;
        $time_span_yearly = NarouConst::TOPPAGE_TITLE_YEARLY;
        $yearly = NarouConst::YEARLY_TEXT;
        $time_span_all = NarouConst::TOPPAGE_TITLE_ALL_TERMS;
        $all = NarouConst::ALL_TEXT;

        $title = '';
        $r_text_time = '';
        $r_text_cate = '';
        $r_text_gan = '';

        if(!empty($request)){
            $time_span = $request -> input(NarouConst::SELECT_TOP_TIMESPAN);
            $cate = $request -> input(NarouConst::SELECT_TOP_CATEGORY);
            $gan = $request -> input(NarouConst::SELECT_TOP_GENERAL_ALL_NO);
            if(!empty($time_span)){
                if($time_span === NarouConst::TIME_SPAN_WEEKLY){
                    $title = $time_span_weekly;
                    $r_text_time = $weekly;
                }else if($time_span === NarouConst::TIME_SPAN_MONTHLY){
                    $title = $time_span_monthly;
                    $r_text_time = $monthly;
                }else if($time_span === NarouConst::TIME_SPAN_HALF){
                    $title = $time_span_half;
                    $r_text_time = $half;
                }else if($time_span === NarouConst::TIME_SPAN_YEARLY){
                    $title = $time_span_yearly;
                    $r_text_time = $yearly;
                }else if($time_span === NarouConst::TIME_SPAN_ALL){
                    $title = $time_span_all;
                    $r_text_time = $all;
                }else{
                    $title = $time_span_weekly;
                    $r_text_time = $weekly;
                };
            }else{
                $title = $time_span_weekly;
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
            $title = $time_span_weekly;
            $r_text_time = $weekly;
            $r_text_cate = $cate_mark;
            $r_text_gan = $gan_ov500;
        }
        return ['title' => $title, 'r_text_time' => $r_text_time, 'r_text_cate' => $r_text_cate, 'r_text_gan' => $r_text_gan];
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
        return $ma->generateTopRanking($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


}
