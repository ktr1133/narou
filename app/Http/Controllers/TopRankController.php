<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Ma;
use App\Models\TopRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class topRankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(){
        $request = new Request;
        $last_date = $this -> getLastUpdate();
        $select_data = $this -> getSelectData($request);
        $result = $this -> generateRanking($request);

        return view('index', ['last_date' => $last_date, 'select_date' => $select_data, 'result' => $result]);
    }


    /**
     * Requestで取得した項目（期間、種類、話数帯をHP上の表示内容に置き換える）
     */
    public function getSelectData(Request $request)
    {
        $cate_mark = 'ポイントの割に読者数は多い作品';
        $cate_calc = 'ポイントの割に読者数は多く、更新頻度が高い作品';
        $gan_ov100un300 = '100話以上300話未満';
        $gan_ov300un500 = '300話以上500話未満';
        $gan_ov500 = '500話以上';
        $timeSpan_weekly = '週間ランキング';
        $weekly = '週間';
        $timeSpan_monthly = '月間ランキング';
        $monthly = '月間';
        $timeSpan_half = '半期ランキング';
        $half = '半期';
        $timeSpan_yearly = '年間ランキング';
        $yearly = '年間';
        $timeSpan_all = '累計ランキング';
        $all = '累計';

        $title = '';
        $r_text_time = '';
        $r_text_cate = '';
        $r_text_gan = '';

        if(!empty($request)){
            $timeSpan = $request -> input('r_time_span');
            $cate = $request -> input('r_cate');
            $gan = $request -> input('r_gan');
            if(!empty($timeSpan)){
                if($timeSpan === 'weekly'){
                    $title = $timeSpan_weekly;
                    $r_text_time = $weekly;
                }else if($timeSpan === 'monthly'){
                    $title = $timeSpan_monthly;
                    $r_text_time = $monthly;
                }else if($timeSpan === 'half'){
                    $title = $timeSpan_half;
                    $r_text_time = $half;
                }else if($timeSpan === 'yearly'){
                    $title = $timeSpan_yearly;
                    $r_text_time = $yealy;
                }else if($timeSpan === 'all'){
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
                if($cate === 'lowPHighU'){
                    $r_text_cate = $cate_mark;
                }else if($cate === 'lowPHighUHighF'){
                    $r_text_cate = $cate_calc;
                }else{
                    $r_text_cate = $cate_mark;
                };
            }else{
                $r_text_cate = $cate_mark;
            };
            if(!empty($gan)){
                if($gan === 'from100to300'){
                    $r_text_gan = $gan_ov100un300;
                }else if($gan === 'from300to500'){
                    $r_text_gan = $gan_ov300un500;
                }else if($gan === 'from500'){
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
     * get date lists. weekly or monthly or half year or yearly or all of terms
     */
    public function getPeriod(Request $request)
    {
        if(!empty($request)){
            $range = $request -> input('r_time_span');
            // 日付配列を取得
            $dateList = $this -> getDateList();
            // 現在の日付を取得
            $currentDate = new DateTime();
            // 抽出する期間の開始日を設定
            $startDate = new DateTime();
            switch ($range) {
                case 'weekly':
                    $startDate->modify('-1 week');
                    break;
                case 'monthly':
                    $startDate->modify('-1 month');
                    break;
                case 'half':
                    $startDate->modify('-6 months');
                    break;
                case 'yearly':
                    $startDate->modify('-1 year');
                    break;
                case 'all':
                    break;
                default:
                    // デフォルトは直近の1週間
                    $startDate->modify('-1 week');
                    break;        
            }
            // 開始日以降の日付を持つ配列を生成
            $filteredArray = array_filter($dateList, function($date) use ($startDate, $currentDate) {
                $dateObj = new DateTime($date);
                $dateObj >= $startDate && $dateObj <= $currentDate;
            });
        }else{
            $dateList = $this -> getDateList();
            $currentDate = new DateTime();
            $startDate = new DateTime();
            $startDate->modify('-1 week');
            $filteredArray = array_filter($dateList, function($date) use ($startDate, $currentDate) {
                $dateObj = new DateTime($date);
                $dateObj >= $startDate && $dateObj <= $currentDate;
            });
        }
        return $filteredArray;
    }



    /**
     * ﾘｸｴｽﾄ内容に応じたランキングﾃﾞｰﾀを取得する。
     */
    public function generateRanking(Request $request)
    {
        $ma = new Ma;
        $result = $ma -> generateRanking($request);
        
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
