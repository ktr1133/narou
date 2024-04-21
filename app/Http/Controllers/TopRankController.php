<?php

namespace App\Http\Controllers;

use App\Models\Mas;
use Illuminate\Http\Request;

class topRankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(){

        return view('index');
    }

    /**
     * create ranking based on requests.
     */
    public function create()
    {
        $mas = Mas::find();
        
        include_once('database.php');
        try{
            // 接続
            $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
        } catch(PDOException $e){
            echo "データベース接続失敗" . PHP_EOL."<br>";
            echo $e->getMessage();
            exit;
        };
        // データを取得する
        //更新時点用
        $sql_date = "SELECT last_get_date FROM ma ORDER BY last_get_date DESC";
        $result_date = $db -> query($sql_date);
        $temp_date01 = $result_date -> fetch()[0];
        $array = preg_split("/\s/", $temp_date01);
        $temp_date02 = $array[0];
        $last_date = preg_replace('/-/', '/', $temp_date02);

        //時点取得用
        $sql_get_time = "SHOW COLUMNS FROM `mark`";
        $date_columns = $db -> query($sql_get_time);
        $date_array = array();
        while($rec =  $date_columns ->fetch()):
        if (false !== strpos($rec['Field'], '-')){
            array_push($date_array, $rec['Field']);
        };
        endwhile;

        $num01 = count($date_array) -1;
        $num02 = count($date_array) -2;
        $num03 = count($date_array) -3;
        $num04 = count($date_array) -4;
        $num05 = count($date_array) -5;
        $temp_date01 = $date_array[$num01];
        $temp_date02 = $date_array[$num02];
        $temp_date03 = $date_array[$num03];
        $temp_date04 = $date_array[$num04];
        $temp_date05 = $date_array[$num05];

        if(!empty($_GET)){
        if($_GET['r_time_span'] === 'weekly'){
            $title = '週間';
            $r_text_time = $title;
            if($_GET['r_cate'] === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_mk_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov1un3);
            }else if($_GET['r_gan'] === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_mk_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov3un5);
            }else if($_GET['r_gan'] === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                $result = $db -> query($sql_weekly_mk_ov5);
            }
            }else if($_GET['r_cate'] === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov1un3);
            }else if($_GET['r_gan'] === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_weekly_c_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                $result = $db -> query($sql_weekly_c_ov3un5);
            }else if($_GET['r_gan'] === 'from500'){
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
            if($_GET['r_cate'] === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            }else if($_GET['r_cate'] === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            if($_GET['r_cate'] === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            }else if($_GET['r_cate'] === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            if($_GET['r_cate'] === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            }else if($_GET['r_cate'] === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
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
            }else if($_GET['r_gan'] === 'from300to500'){
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
            }else if($_GET['r_gan'] === 'from500'){
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
            if($_GET['r_cate'] === 'lowPHighU'){
            $text_cate = 'ポイントの割に読者数は多い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov1un3);
            }else if($_GET['r_gan'] === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov3un5);
            }else if($_GET['r_gan'] === 'from500'){
                $text_gan = '500話以上';
                $r_text_gan = $text_gan;
                $sql_all_mk_ov5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 500<=general_all_no ORDER BY ave ASC LIMIT 20";
                $result = $db -> query($sql_all_mk_ov5);
            }
            }else if($_GET['r_cate'] === 'lowPHighUHighF'){
            $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
            $r_text_cate = $text_cate;
            if($_GET['r_gan'] === 'from100to300'){
                $text_gan = '100話以上300話未満';
                $r_text_gan = $text_gan;
                $sql_all_c_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave DESC LIMIT 20";
                $result = $db -> query($sql_all_c_ov1un3);
            }else if($_GET['r_gan'] === 'from300to500'){
                $text_gan = '300話以上500話未満';
                $r_text_gan = $text_gan;
                $sql_all_c_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave DESC LIMIT 20";
                $result = $db -> query($sql_all_c_ov3un5);
            }else if($_GET['r_gan'] === 'from500'){
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TopRankModel $topRankModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TopRankModel $topRankModel)
    {
        //
    }
}
