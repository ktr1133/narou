<?php

namespace App\Repositories;

use App\Models\Ma;
use Illuminate\Database\Eloquent\Builder;

class MaRepository extends Repository
{
    //maテーブルとmarkテーブルを結合したﾃｰﾌﾞﾙﾃﾞｰﾀの作成
    public function concatMark()
    {
        // maテーブルとmarkテーブルをncodeで結合
        $data = Builder::table('ma')
            ->join('mark', 'ma.ncode', '=', 'mark.ncode')
            // 全てのデータを取得
            ->get();
        return $data;
    }

    //maテーブルとcalcテーブルを結合したﾃｰﾌﾞﾙﾃﾞｰﾀの作成
    public function concatCalc()
    {
        // maテーブルとcalcテーブルをncodeで結合
        $data = Builder::table('ma')
            ->join('calc', 'ma.ncode', '=', 'calc.ncode')
            // 全てのデータを取得
            ->get();
        return $data;
    }


    public function topRank(Request $request){
        $timeSpan = $request -> input('r_time_span');
        $cate = $request -> input('r_cate');
        $gan = $request -> input('r_gan');
        if(!empty($request)){
            if($cate === 'lowPHighU'){
                $maMark = $this -> concatMark();
                if($timeSpan === 'weekly'){
                    $maMarkWeekly = $maMark ->query()
                        ->where(`mark`);
                    if($gan === 'from100to300'){
                        $sql_weekly_mk_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_weekly_mk_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_weekly_mk_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_weekly_mk_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_weekly_mk_ov5);
                    }
                }else if($cate === 'lowPHighUHighF'){
                    if($gan === 'from100to300'){
                        $sql_weekly_c_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                        $result = $db -> query($sql_weekly_c_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_weekly_c_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                        $result = $db -> query($sql_weekly_c_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                        $result = $db -> query($sql_weekly_c_ov5);
                    }else{
                        $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
                        $result = $db -> query($sql_weekly_c_ov5);
                    }
                }
            }else if($timeSpan === 'monthly'){
                if($cate === 'lowPHighU'){
                    if($gan === 'from100to300'){
                        $sql_monthly_mk_ov1un3 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
                            `mark`.`mean_monthly` AS `ave` 
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                            AND 100<=`temp`.`general_all_no` 
                            AND 300>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_monthly_mk_ov1un3);
                    }else if($gan === 'from300to500'){
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
                    if($gan === 'from100to300'){
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
            }else if($timeSpan === 'half'){
                if($cate === 'lowPHighU'){
                    if($gan === 'from100to300'){
                        $sql_half_mk_ov1un3 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave` 
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 100<=`temp`.`general_all_no` 
                        AND 300>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_mk_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_half_mk_ov3un5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 300<=`temp`.`general_all_no` 
                        AND 500>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_mk_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_half_mk_ov5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 500<=`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_mk_ov5);
                    }
                }else if($cate === 'lowPHighUHighF'){
                    if($gan === 'from100to300'){
                        $sql_half_c_ov1un3 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 100<=`temp`.`general_all_no` 
                        AND 300>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_c_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_half_c_ov3un5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 300<=`temp`.`general_all_no` 
                        AND 500>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_c_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_half_c_ov5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 500<=`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_half_c_ov5);
                    }
                }
            }else if($timeSpan === 'yearly'){
                if($cate === 'lowPHighU'){
                    if($gan === 'from100to300'){
                        $sql_yearly_mk_ov1un3 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 100<=`temp`.`general_all_no` 
                        AND 300>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_mk_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_yearly_mk_ov3un5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 300<=`temp`.`general_all_no` 
                        AND 500>`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_mk_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_yearly_mk_ov5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`mark`.`mean_yearly` AS `ave` 
                        FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 500<=`temp`.`general_all_no` 
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_mk_ov5);
                    }
                }else if($cate === 'lowPHighUHighF'){
                    if($gan === 'from100to300'){
                        $sql_yearly_c_ov1un3 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 100<=`temp`.`general_all_no`
                        AND 300>`temp`.`general_all_no`
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_c_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_yearly_c_ov3un5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 300<=`temp`.`general_all_no`
                        AND 500>`temp`.`general_all_no`
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_c_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_yearly_c_ov5 = "SELECT * FROM 
                        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
                        FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
                        WHERE `ave` REGEXP '.+' 
                        AND 500<=`temp`.`general_all_no`
                        ORDER BY `ave` ASC LIMIT 20;";
                        $result = $db -> query($sql_yearly_c_ov5);
                    }
                }
            }else if($timeSpan === 'all'){
                if($cate === 'lowPHighU'){
                    if($gan === 'from100to300'){
                        $sql_all_mk_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave ASC LIMIT 20";
                        $result = $db -> query($sql_all_mk_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_all_mk_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave ASC LIMIT 20";
                        $result = $db -> query($sql_all_mk_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_all_mk_ov5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 500<=general_all_no ORDER BY ave ASC LIMIT 20";
                        $result = $db -> query($sql_all_mk_ov5);
                    }
                }else if($cate === 'lowPHighUHighF'){
                    if($gan === 'from100to300'){
                        $sql_all_c_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave DESC LIMIT 20";
                        $result = $db -> query($sql_all_c_ov1un3);
                    }else if($gan === 'from300to500'){
                        $sql_all_c_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave DESC LIMIT 20";
                        $result = $db -> query($sql_all_c_ov3un5);
                    }else if($gan === 'from500'){
                        $sql_all_c_ov5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 500<=general_all_no ORDER BY ave DESC LIMIT 20";
                        $result = $db -> query($sql_all_c_ov5);
                    }
                }
            };
        }else{
            $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
            $result = $db -> query($sql_weekly_mk_ov5);
        }
        return $result;
    }
    

}