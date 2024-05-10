<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Repositories\MaRepository;
use App\Repositories\CalcRepository;
use App\Repositories\MarkRepository;
use App\Repositories\PointRepository;
use App\Repositories\UniqueRepository;
use App\Repositories\Update_frequencyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class topRankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(){
        $last_date = $this -> getLastUpdate();
    
        return view('index', ['last_date' => $last_date]);
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
            var_dump($column);
            Log::debug($column);
            if (strpos($column, '-') !== false) {
                $dateColumns[] = $column;
            }
        }
        $dateColumns = sort($dateColumns);
        $this -> allToWeeks = count($dataColumns);
        return $dateColumns;

    }


    /**
     * last update.
     */
    public function getLastUpdate()
    {
        $dateColumns = $this -> getDateList();
        // 最新の日付を取得
        $last_date = null;
        foreach ($dateColumns as $column) {
            $record = Mark::orderBy($column, 'desc')->first();
            if ($record) {
                $dateValue = $record->{$column};
                if ($dateValue > $last_date) {
                    $last_date = $dateValue;
                }
            }
        }
        
        return $last_date;
    }

    /**
     * get date lists. weekly or monthly or half year or yearly or all of terms
     */
    public function getPeriod($range)
    {
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
        return $dateObj >= $startDate && $dateObj <= $currentDate;
        });
        return $filteredArray;
    }



    /**
     * .
     */
    public function generateRanking(Request $request)
    {
        $timeSpan = $request -> input('r_time_span');
        $cate = $request -> input('r_cate');
        $gan = $request -> input('r_gan');

        if(!empty($request)){
        if($timeSpan === 'weekly'){
            $r_text_time =  '週間';
            if($cate === 'lowPHighU'){
            $r_text_cate = 'ポイントの割に読者数は多い作品';
            if($gan === 'from100to300'){
                $r_text_gan = '100話以上300話未満';
                $sql_weekly_mk_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_mk_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov5);
            }
            }else if($cate === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov5);
            }else{
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov5);
            }
            }
        }else if($_GET['r_time_span'] === 'monthly'){
            $title = '月間';
            $r_text_time = $title;
            if($cate === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_monthly_mk_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 100<=`temp`.`general_all_no` 
                    AND 300>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                echo $sql_monthly_mk_ov1un3;
                $result = $db -> query($sql_monthly_mk_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_monthly_mk_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 300<=`temp`.`general_all_no` 
                    AND 500>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_monthly_mk_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_monthly_mk_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 500<=`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_monthly_mk_ov5);
            }
            }else if($cate === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_monthly_c_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 100<=`temp`.`general_all_no` 
                    AND 300>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_monthly_c_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_monthly_c_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 300<=`temp`.`general_all_no` 
                    AND 500>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_monthly_c_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_monthly_c_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                    `mark`.`mean_monthly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                    AND 500<=`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_monthly_c_ov5);
            }
            }
        }else if($_GET['r_time_span'] === 'half'){
            $title = '半期';
            $r_text_time = $title;
            if($cate === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_half_mk_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 100<=`temp`.`general_all_no` 
                AND 300>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_mk_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_half_mk_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 300<=`temp`.`general_all_no` 
                AND 500>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_mk_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_half_mk_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 500<=`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_mk_ov5);
            }
            }else if($cate === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_half_c_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 100<=`temp`.`general_all_no` 
                AND 300>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_c_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_half_c_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 300<=`temp`.`general_all_no` 
                AND 500>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_c_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_half_c_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 500<=`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_half_c_ov5);
            }
            }
        }else if($_GET['r_time_span'] === 'yearly'){
            $title = '年間';
            $r_text_time = $title;
            if($cate === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_yearly_mk_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 100<=`temp`.`general_all_no` 
                AND 300>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_mk_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_yearly_mk_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 300<=`temp`.`general_all_no` 
                AND 500>`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_mk_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_yearly_mk_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`mark`.`mean_yearly` AS `ave` 
                FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 500<=`temp`.`general_all_no` 
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_mk_ov5);
            }
            }else if($cate === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_yearly_c_ov1un3 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 100<=`temp`.`general_all_no`
                AND 300>`temp`.`general_all_no`
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_c_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_yearly_c_ov3un5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 300<=`temp`.`general_all_no`
                AND 500>`temp`.`general_all_no`
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_c_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_yearly_c_ov5 = "SELECT * FROM 
                (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                WHERE `ave` REGEXP '.+' 
                AND 500<=`temp`.`general_all_no`
                ORDER BY `ave` ASC LIMIT 20;";
                $result = $db -> query($sql_yearly_c_ov5);
            }
            }
        }else if($_GET['r_time_span'] === 'all'){
            $title = '累計';
            $r_text_time = $title;
            if($cate === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 500<=general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov5);
            }
            }else if($cate === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($gan === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_all_c_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave DESC LIMIT 20";
                $result = $db -> query($sql_all_c_ov1un3);
            }else if($gan === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_all_c_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave DESC LIMIT 20";
                $result = $db -> query($sql_all_c_ov3un5);
            }else if($gan === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_all_c_ov5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 500<=general_all_no ORDER BY ave DESC LIMIT 20";
                $result = $db -> query($sql_all_c_ov5);
            }
            }
        };
        }else{
            $title = '週間';
            $r_text_time = $title;
            $r_text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_gan = '500話以上';
            $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
            $result = $db -> query($sql_weekly_mk_ov5);
        }
        return [$title, $r_text_cate, $r_text_gan, $result];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


}
